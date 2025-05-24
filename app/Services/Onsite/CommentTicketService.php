<?php

namespace App\Services;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use App\CategoryTicket;
use App\GroupTicket;
use App\Http\Requests\Onsite\TicketRequest;
use App\Models\Admin\User;
use App\Models\Ticket\CommentTicket;
use App\Models\Ticket\Ticket;
use App\Notifications\CommentTicketNotification;
use App\Services\TicketsService;
use App\Models\Derivacion\Derivacion;
use App\Models\Reparacion\Reparacion;


use Throwable;

class CommentTicketService
{
    protected $mailService;
    protected $ticketsService;
    
    public function __construct(
        MailService $mailService,
        TicketsService $ticketsService
    ) {
        $this->mailService = $mailService;
        $this->ticketsService = $ticketsService;
    }

    public function listado($id)
    {
        $company_id = Session::get('userCompanyIdDefault');
        $commentTickets = CommentTicket::where([['company_id', $company_id],['ticket_id',$id]])->orderBy('id','Desc')->get();
        return $commentTickets;
    }

    public function store(Request $request)
    {
        $company_id = Session::get('userCompanyIdDefault');
        $ticket = Ticket::findOrFail($request->ticket_id);
        $statusNameOld = $ticket->status_ticket->name;
        $statusOld = $ticket->status_ticket_id;
        $name = "";
        if($request['status_id']){
            if( $request['status_id']==5&&$ticket->user_owner_id != Auth::user()->id){
                return 'No tienes permisos para cerrar el Ticket seleccionado';
                
            }elseif (
                !(Auth::user()->group_ticket->contains($ticket->group_user_receiver_id) ||
                Auth::user()->id == $ticket->user_owner_id ||
                Auth::user()->id == $ticket->user_receiver_id)
            ) {
                return 'No tienes permisos para editar el Ticket seleccionado';
            }
            else{
                $this->ticketsService->updateStatus($request['status_id'],$ticket->id);
            }
        }
        try {
            if($request->hasFile('file')){
                $file = $request->file('file');
                $name = time()."_".$file->getClientOriginalName();
                $file->move(public_path().DIRECTORY_SEPARATOR."files".DIRECTORY_SEPARATOR,$name);
            }

            $commentTicket = new CommentTicket();
            $commentTicket->company_id = $company_id;
            $commentTicket->ticket_id = $ticket->id;
            $commentTicket->user_comment_id = $request->user_id;
            $commentTicket->cliente_id = $ticket->cliente_id;
            $commentTicket->group_user_receiver_id = $ticket->group_user_receiver_id;
            $commentTicket->user_receiver_id = $ticket->user_receiver_id;
            $commentTicket->comment = $request->comentario;
            $commentTicket->file = $name;
            $commentTicket->save();

            $users = User::join('user_group_ticket', 'users.id', '=', 'user_group_ticket.user_id')
            ->where('user_group_ticket.group_ticket_id', $ticket->group_user_receiver_id)
            ->get();

            $user = User::find($commentTicket->user_receiver_id);
            $userOwner = User::find($ticket->user_owner_id);

            //Chequea si el usuario receptor y el usuario propietario ya existe en el grupo, para no notificar 2 veces
            if(isset($user)){
                $existeObjeto = $users->contains(function ($u) use ($user) {
                    return $u->id === $user->id;
                });
                if(!$existeObjeto){
                    $users->push($user);
                }
            }if(isset($userOwner)){
                $existeObjeto = $users->contains(function ($u) use ($userOwner) {
                    return $u->id === $userOwner->id;
                });
                if(!$existeObjeto){
                    $users->push($userOwner);
                }
            }

            $ticket = Ticket::findOrFail($request->ticket_id);
            $statusNameNew = $ticket->status_ticket->name;
            $comentarioHistorial = "Se agrega comentario";
            if($statusNameOld !== $statusNameNew){
                $comentarioHistorial  .= " y actualiza estado de $statusNameOld a $statusNameNew";
            }
            $comentarioHistorial .= ". Ticket";

            if(!empty($ticket->reparacion_id)){
                $reparacion = Reparacion::find($ticket->reparacion_id);
                if($reparacion){
                    $this->ticketsService->registerHistorialEstadoReparacion($ticket, $reparacion,$comentarioHistorial);
                }
                
            }

            if(!empty($ticket->derivacion_id)){
                $derivacion = Derivacion::find($ticket->derivacion_id);
                if($derivacion){
                    $this->ticketsService->registerHistorialEstadoDerivacion($ticket, $derivacion,$comentarioHistorial);
                }
                
            }
            
        
            //Conformo el mensaje para la notificacion
            $notifMsg ='Ticket ['.$ticket->id.'] actualizado por '.Auth::user()->name.':<br>';
            $notifMsg .= "<ul><li><small>Nuevo Comentario: <b>".$commentTicket->comment."</b></small> </li> </ul>";
            $users = $users->where('id', '<>' ,Auth::user()->id)->all();
            foreach($users as $user){
                //Notificacion de usuarios
                $data = [         
                    'ticket_id' => $ticket->id,
                    'email_to'=> $user->email,
                ];
                try {
                    $user->notify(new CommentTicketNotification($ticket,$notifMsg,$this->mailService));
                } catch (Throwable  $e) {
                    Log::alert('No se pudo enviar el mail speedup. ERROR: ' . $e->getMessage());
                    Log::info($e->getFile() . '(' . $e->getLine() . ')');
    
                    $envio_email = 'No se pudo enviar el mail speedup. ERROR: ' . $e->getMessage();
                    Log::alert($envio_email);
                }                

            } 

            return $commentTicket;
        } catch (\Throwable $th) {
            return null;
        }
        
    }

    public function destroy($id)
    {
        $comment = CommentTicket::findOrFail($id);
        try {
            unlink(public_path().DIRECTORY_SEPARATOR."files".DIRECTORY_SEPARATOR.$comment->file);
            $id = $comment->id;
            $comment->delete();
        } catch (\Throwable $th) {
            $comment->delete();
        }
        return true;
    }

    public function findCommentsByTicketId($id){
        return CommentTicket::select('comment_tickets.*', 'users.name')
        ->join('users', 'comment_tickets.user_comment_id', '=', 'users.id')
        ->where('comment_tickets.ticket_id', $id)
        ->orderBy('comment_tickets.id', 'DESC')
        ->get();
    }
}
