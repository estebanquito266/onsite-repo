<?php

namespace App\Services\Onsite;

use Carbon\Carbon;
// use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
// use Illuminate\Support\Facades\Notification;
use App\CategoryTicket;
use App\GroupTicket;
use App\Enums\TicketType;
use App\Http\Requests\Onsite\HistorialEstadoOnsiteRequest;
// use App\Http\Requests\HistorialEstadoDerivacionRequest;
use App\Http\Requests\Onsite\TicketRequest;
use App\Models\Company;
// use App\Models\Admin\Company;
// use App\Models\Admin\User;
// use App\Models\Cliente\Cliente;
// use App\Models\Derivacion\ClienteDerivacion;
// use App\Models\Derivacion\Derivacion;
// use App\Models\Derivacion\HistorialEstadoDerivacion;
use App\Models\MotivoConsultaTicket;
use App\Models\Onsite\EmpresaOnsite;
use App\Models\Onsite\ReparacionOnsite;
// use App\Models\Reparacion\HistorialEstado;
// use App\Models\Reparacion\Reparacion;
use App\Models\Ticket\CommentTicket;
use App\Models\Ticket\PriorityTicket;
use App\Models\Ticket\StatusTicket;
use App\Models\Ticket\Ticket;
use App\Notifications\TicketNotification;
use App\Services\Onsite\CommentTicketService;
// use App\Services\HistorialEstadosDerivacionService;
// use App\Services\HistorialEstadosService;

use App\Services\Onsite\HistorialEstadosOnsiteService as HistorialEstadosService;
use App\Models\User;
use Throwable;

class TicketsService
{
    protected $historialEstadosService;
    protected $historialEstadosDerivacionService;
    protected $commentTicketService;
    protected $mailService;

    public function __construct(
        HistorialEstadosService $historialEstadosService
        
    ) {
        $this->historialEstadosService = $historialEstadosService;
    }
    
    public function getPaginatedListbyLoggedUser(Array $status = null){
        if(Session::get('perfilAdmin'))
            if($status)
                return Ticket::with(['cliente', 'motivo_consulta'])->where('company_id', Session::get('userCompanyIdDefault'))->whereIn('status_ticket_id',$status)->orderBy('id','DESC')->paginate(100);
            else
                return Ticket::with(['cliente', 'motivo_consulta'])->where('company_id', Session::get('userCompanyIdDefault'))->orderBy('id','DESC')->paginate(100);
        else
            if($status)
                return Ticket::where('company_id',Session::get('userCompanyIdDefault'))
                                ->orWhereIn('group_user_receiver_id',Auth::user()->group_ticket)
                                ->orWhere('user_owner_id',Auth::user()->id)
                                ->orWhere('user_receiver_id',Auth::user()->id)
                                ->orderBy('id','DESC')->whereIn('status_ticket_id',$status)->paginate(100);
            else
                return Ticket::where('company_id',Session::get('userCompanyIdDefault'))
                                ->orWhereIn('group_user_receiver_id',Auth::user()->group_ticket)
                                ->orWhere('user_owner_id',Auth::user()->id)
                                ->orWhere('user_receiver_id',Auth::user()->id)
                                ->orderBy('id','DESC')->paginate(100);
    }

    public function getPaginatedListbyLoggedUserJob(Array $status = null, $company_id,$user_id, $perfilAdmin=false, $tomar, $saltear){

        $auth_user = User::with(['group_ticket'])->where('id',$user_id)->firstOrFail();

        if($perfilAdmin)
            if($status)
                return Ticket::with(['cliente', 'motivo_consulta'])->where('company_id', $company_id)->whereIn('status_ticket_id',$status)->orderBy('id','DESC')->skip($saltear)->take($tomar)->get();
            else
                return Ticket::with(['cliente', 'motivo_consulta'])->where('company_id', $company_id)->orderBy('id','DESC')->skip($saltear)->take($tomar)->get();
        else
            if($status)
                return Ticket::where('company_id',$company_id)
                                ->orWhereIn('group_user_receiver_id',$auth_user->group_ticket)
                                ->orWhere('user_owner_id',$auth_user->id)
                                ->orWhere('user_receiver_id',$auth_user->id)
                                ->orderBy('id','DESC')->whereIn('status_ticket_id',$status)->skip($saltear)->take($tomar)->get();
            else
                return Ticket::where('company_id',$company_id)
                                ->orWhereIn('group_user_receiver_id',$auth_user->group_ticket)
                                ->orWhere('user_owner_id',$auth_user->id)
                                ->orWhere('user_receiver_id',$auth_user->id)
                                ->orderBy('id','DESC')->skip($saltear)->take($tomar)->get();
    }

    public function listado()
    {
        $company_id = Session::get('userCompanyIdDefault');
        $status = StatusTicket::whereNotIn('id', [5])->pluck('id')->toArray();
        $priorities = PriorityTicket::select('id','name')->get();
        $tickets = $this->getPaginatedListbyLoggedUser($status);
        $motivos_consulta = MotivoConsultaTicket::select('id', 'name')->where('company_id', $company_id)->get();
        $users = User::select('users.*')
        ->join('user_companies','users.id','=','user_companies.user_id')
        ->where('user_companies.company_id','=',$company_id)
        ->get();
        $categorias = CategoryTicket::select('id','name')->where('company_id', $company_id)->get();
        $grupos = GroupTicket::select('id','name')->where('company_id', $company_id)->get();
        foreach (TicketType::getValues() as $value) {
            $tipos[$value] = TicketType::getKey($value);
        }

        return [
            'tickets' => $tickets,
            'motivos_consulta' => $motivos_consulta,
            'users' => $users,
            'categorias' => $categorias,
            'grupos' => $grupos,
            'tipos' => $tipos,
            'priorities'=>$priorities,
            'checkeds' => []
        ];
    }

    public function listadoCerrados()
    {
        $company_id = Session::get('userCompanyIdDefault');
        $status = StatusTicket::whereIn('id', [5])->pluck('id')->toArray();
        $tickets = $this->getPaginatedListbyLoggedUser($status);
        $motivos_consulta = MotivoConsultaTicket::select('id', 'name')->where('company_id', $company_id)->get();
        $users = User::select('users.*')
        ->join('user_companies','users.id','=','user_companies.user_id')
        ->where('user_companies.company_id','=',$company_id)
        ->get();
        $categorias = CategoryTicket::select('id','name')->where('company_id', $company_id)->get();
        $grupos = GroupTicket::select('id','name')->where('company_id', $company_id)->get();
        foreach (TicketType::getValues() as $value) {
            $tipos[$value] = TicketType::getKey($value);
        }
        return [
            'tickets' => $tickets,
            'motivos_consulta' => $motivos_consulta,
            'users' => $users,
            'categorias' => $categorias,
            'grupos' => $grupos,
            'tipos' => $tipos,
        ];
    }

    public function create()
    {
        $ticket = null;
        $company_id = Session::get('userCompanyIdDefault');
        $motivos_consulta = MotivoConsultaTicket::select('id', 'name')->where('company_id', $company_id)->get();
        $grupos = GroupTicket::select('id','name')->where('company_id', $company_id)->get();

        //Como los id de grupos solo son de grupos de la company actual, por ende no es necesario filtrar por user company_id
        //El ticket SPEUP-5 pide que el usuario sea de la misma company y que al menos perteneza a un grupo de ticket
        $users = User::select('users.*')
            ->join('user_group_ticket','users.id','=','user_group_ticket.user_id')
            ->whereIn('user_group_ticket.group_ticket_id',$grupos->pluck('id')->toArray())
            ->groupBy('users.id')
            ->get();

        $cliente = Array();
        $clientes = EmpresaOnsite::select('id', 'nombre')->where('company_id', $company_id)->get();
        // $clientesDerivaciones = ClienteDerivacion::select('id', 'nombre')->where('company_id', $company_id)->get();
        $reparaciones = ReparacionOnsite::select('id', 'id_empresa_onsite')->where('company_id', $company_id)->get();
        // $derivaciones = Derivacion::select('id')->where('company_id', $company_id)->get();
        $categorias = CategoryTicket::select('id','name')->where('company_id', $company_id)->get();
        
        $priorities = PriorityTicket::select('id','name')->where('company_id', $company_id)->get();
        
        $status = StatusTicket::select('id','name')->where('company_id', $company_id)->get();
        $tipos = [];
        foreach (TicketType::getValues() as $value) {
            $tipos[$value] = TicketType::getKey($value);
        }

        return [
            'cliente' => $cliente,
            'clientes' => $clientes,
            'ticket' => $ticket,
            'motivos_consulta' => $motivos_consulta,
            'users' => $users,
            'reparaciones' => $reparaciones,
            // 'derivaciones' => $derivaciones,
            'categorias' => $categorias,
            'grupos' => $grupos,
            // 'clientesDerivaciones' => $clientesDerivaciones,
            'priorities' => $priorities,
            'status' => $status,
            'tipos'=>$tipos
        ];
    }

    public function edit($id)
    {
        $company_id = Session::get('userCompanyIdDefault');

        $ticket = Ticket::with(['cliente', 'motivo_consulta'])->where('id',$id)->first();
        $reparaciones = ReparacionOnsite::select('id')->where('company_id', $company_id)->get();
        // $derivaciones = Derivacion::select('id')->where('company_id', $company_id)->get();
        $motivos_consulta = MotivoConsultaTicket::select('id', 'name')->where('company_id', $company_id)->get();
        $categorias = CategoryTicket::select('id','name')->where('company_id', $company_id)->get();
        $grupos = GroupTicket::select('id','name')->where('company_id', $company_id)->get();
        $cliente = EmpresaOnsite::where('id', $ticket->id_empresa_onsite)->where('company_id', Session::get('userCompanyIdDefault'))->selectRaw("nombre as nombreDni, id")->pluck('nombreDni', 'id');
        $clientes = EmpresaOnsite::select('id', 'nombre')->where('company_id', $company_id)->get();
        // $clientesDerivaciones = ClienteDerivacion::select('id', 'nombre')->where('company_id', $company_id)->get();

        //Como los id de grupos solo son de grupos de la company actual, por ende no es necesario filtrar por user company_id
        $users = User::select('users.*')
        ->join('user_group_ticket','users.id','=','user_group_ticket.user_id')
        ->whereIn('user_group_ticket.group_ticket_id',$grupos->pluck('id')->toArray())
        ->get();
        //Si el usuario no esta en el nuevo metodo de los usuarios destinos agregar el usuario
        if(!empty($ticket->user_receiver_id)){
            if(!in_array($ticket->user_receiver_id,$users->pluck('id')->toArray())){
                $user_old = $ticket->user_receiver;
                $users->push($user_old);
            }
        }

        $commentsTickets = CommentTicket::where('ticket_id',$id)->orderBy('id','DESC')->get();
        $priorities = PriorityTicket::select('id','name')->get();
        $status = StatusTicket::select('id','name')->get();
        $tipos = [];
        foreach (TicketType::getValues() as $value) {
            $tipos[$value] = TicketType::getKey($value);
        }

      
        if($ticket->user_owner_id != Auth::user()->id){
            $status = StatusTicket::select('id','name')->where('id','<>',5)->get();
        }
        // dd([
        //     'cliente' => $cliente,
        //     'clientes' => $clientes,
        //     'ticket' => $ticket,
        //     'reparaciones' => $reparaciones,
        //     // 'derivaciones' => $derivaciones,
        //     'motivos_consulta' => $motivos_consulta,
        //     'categorias' => $categorias,
        //     'users' => $users,
        //     'commentsTickets' => $commentsTickets,
        //     'grupos' => $grupos,
        //     // 'clientesDerivaciones' => $clientesDerivaciones,
        //     'priorities' => $priorities,
        //     'status' => $status,
        //     'tipos'=>$tipos
        // ]);

        return [
            'cliente' => $cliente,
            'clientes' => $clientes,
            'ticket' => $ticket,
            'reparaciones' => $reparaciones,
            // 'derivaciones' => $derivaciones,
            'motivos_consulta' => $motivos_consulta,
            'categorias' => $categorias,
            'users' => $users,
            'commentsTickets' => $commentsTickets,
            'grupos' => $grupos,
            // 'clientesDerivaciones' => $clientesDerivaciones,
            'priorities' => $priorities,
            'status' => $status,
            'tipos'=>$tipos
        ];
    }
    //Registra en el historial la creación de un ticket para una reparacion
    public function registerHistorialEstadoReparacion(Ticket $ticket, ReparacionOnsite $reparacion, $msg = null){
        $nuevoHistorialEstadoArray = new HistorialEstadoOnsiteRequest([
            'company_id'    => $ticket->company_id,
            'id_reparacion' => $reparacion->id,
            'id_estado'     => $reparacion->id_estado,
            'fecha'         => Carbon::now(),
            'observacion'   => $msg." [".$ticket->id."]",
            'id_usuario'    => $ticket->user_owner_id
          ]);
    
        return $this->historialEstadosService->store($nuevoHistorialEstadoArray, $ticket->company_id);
    }

    //Registra en el historial la creación de un ticket para una derivacion
    public function registerHistorialEstadoDerivacion(Ticket $ticket, Derivacion $derivacion, $msg = null){
        $nuevoHistorialEstadoDerivacion = array(
            'company_id' =>  $ticket->company_id,
            'id_derivacion' =>$derivacion->id,
            'id_estado_derivacion' => $derivacion->id_estado_derivacion,
            'fecha' => Carbon::now(),
            'observacion' => $msg." [".$ticket->id."]",
            'id_usuario' => $ticket->user_owner_id
        );

        $nuevoHistorialEstadoDerivacion = new HistorialEstadoDerivacionRequest($nuevoHistorialEstadoDerivacion);
        return $this->historialEstadosDerivacionService->store($nuevoHistorialEstadoDerivacion,$ticket->company_id);
    }

    public function store(TicketRequest $request)
    {
        $company_id = Session::get('userCompanyIdDefault');
        $request['company_id'] = $company_id;
        $request['status_ticket_id'] = StatusTicket::where('name', 'Nuevo')->first()->id;
        if($request->reparacion_id!=""){
            $reparacion = ReparacionOnsite::find($request->reparacion_id);
        }else{
            $request['reparacion_id']=null;
        }
        $request['derivacion_id']=null;
        $name = "";
        if($request->hasFile('ticket_file')){
            $file = $request->file('ticket_file');
            $name = time()."_".$file->getClientOriginalName();
            $file->move(public_path().DIRECTORY_SEPARATOR."files".DIRECTORY_SEPARATOR,$name);
            $request['file'] = $name;
        }
        if($request->expiration_date=="")
            $request['expiration_date']=null;

        if($request->type==2){ // Si es una derivacion, guardo el campo cliente_id en cliente_derivacion_id
            $request['cliente_derivacion_id'] = $request->cliente_id;
            $request['cliente_id'] = null;
        }
        //Creacion de registros en tablas de historial
        $ticket = Ticket::create($request->all());
        if(isset($reparacion)){
            $this->registerHistorialEstadoReparacion($ticket,$reparacion,'Nuevo Ticket generado');
        }

        if(isset($derivacion)){
            $this->registerHistorialEstadoDerivacion($ticket, $derivacion,'Nuevo Ticket generado');
        }
            //Armado de array de usuarios a notificar
            $users = User::join('user_group_ticket', 'users.id', '=', 'user_group_ticket.user_id')
                ->where('user_group_ticket.group_ticket_id', $ticket->group_user_receiver_id)
                ->get();
            $user = User::find($request->user_receiver_id);
            if(isset($user)){
                $existeObjeto = $users->contains(function ($u) use ($user) {
                    return $u->id === $user->id;
                });
                if(!$existeObjeto){
                    $users->push($user);
                }
            }
            
            foreach($users as $user){
                //Notificacion de usuarios
                $data = [         
                    'ticket_id' => $ticket->id,
                    'email_to'=> $user->email
                ];
                try {
                    $user->notify(new TicketNotification($data,'Ticket ['.$ticket->id.'] asignado - '.$ticket->detail,$this->mailService,new ParamCompaniesService ));
                } catch (Throwable  $e) {
                    Log::alert('No se pudo enviar el mail speedup. ERROR: ' . $e->getMessage());
                    Log::info($e->getFile() . '(' . $e->getLine() . ')');
    
                    $envio_email = 'No se pudo enviar el mail speedup. ERROR: ' . $e->getMessage();
                    Log::alert($envio_email);
                }
                

            }
        
        //Log::info('Notificacion Mail Ticket: ' . $ticket->id.' - No se encontró una plantilla asignada '. __LINE__);

        return $ticket;
    }

    public function update(TicketRequest $request, $id)
    {

        ///ANTES DE HACER ALGO REVISO QUE SERA ACTUALIZAO
        $ticketUpdate = Ticket::findOrFail($id);

        //$statusOld = $ticketUpdate->status_ticket_id;
        if( $request['status_ticket_id']==5&&$ticketUpdate->user_owner_id != Auth::user()->id){
            return 'No tienes permisos para cerrar el Ticket seleccionado';
        }
        $company_id = Session::get('userCompanyIdDefault');
        $request['company_id'] = $company_id;

        if($request->reparacion_id!=""){
            $reparacion = ReparacionOnsite::find($request->reparacion_id);
        }else{
            $request['reparacion_id']=null;
        }
        $request['derivacion_id']=null;
        $name="";
        if($request->hasFile('ticket_file')){
            $file = $request->file('ticket_file');
            $name = time()."_".$file->getClientOriginalName();
            $file->move(public_path().DIRECTORY_SEPARATOR."files".DIRECTORY_SEPARATOR,$name);
            $request['file'] = $name;
        }
        if($request->expiration_date=="")
            $request['expiration_date']=null;
        
        if($request->type==2){ // Si es una derivacion, guardo el campo cliente_id en cliente_derivacion_id
            $request['cliente_derivacion_id'] = $request->cliente_id;
            $request['cliente_id'] = null;
        }

        $changes = [];

        $columns_request = $request->all();
        $columns_fillables = [
            'expiration_date',
            'cliente_id',
            'cliente_derivacion_id',
            'category_ticket_id',
            'priority_ticket_id',
            'reason_ticket_id',
            'user_receiver_id',
            'group_user_receiver_id',
            'user_owner_id',
            'detail',
        ];
        foreach ($columns_request as $column_request => $value){
            if (in_array($column_request, $columns_fillables)){
                $value_request = $request->input($column_request);
                //casos especiales
                switch ($column_request) {
                    case 'expiration_date':
                        if(!empty($value_request)){
                            $value_request .= " 00:00:00"; 
                        }
                        break;
                }
         
                if( $value_request != $ticketUpdate->{$column_request}){
                    array_push($changes,
                    [
                        'key'=>$column_request,
                        'old'=>$ticketUpdate->{$column_request},
                        'new'=>$value_request
                    ]);
                }
                
                
            }
        }

        

        


        //Creacion de registros en tablas de historial
        $ticketUpdate->update($request->all());
        if(isset($reparacion)){
            $this->registerHistorialEstadoReparacion($ticketUpdate,$reparacion,'Ticket actualizado');
        }

        if(isset($derivacion)){
            $this->registerHistorialEstadoDerivacion($ticketUpdate, $derivacion,'Ticket actualizado');
        }

        
        $users = User::join('user_group_ticket', 'users.id', '=', 'user_group_ticket.user_id')
            ->where('user_group_ticket.group_ticket_id', $ticketUpdate->group_user_receiver_id)
            ->get();

        $user = User::find($request->user_receiver_id);
        $userOwner = User::find($request->user_owner_id);

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

        $users = $users->where('id', '<>' ,Auth::user()->id)->all();
 
        
       /*
        //Conformo el mensaje para la notificacion
        $notifMsg ='El Ticket ['.$ticketUpdate->id.'] ha sido modificado';
        if($statusOld!=$ticketUpdate->status_ticket_id){
            $notifMsg = $notifMsg. ' - Status: '.$ticketUpdate->status_ticket->name;
        }
        */

        $notifMsg = '<ul style="padding-left: 1px !important;"> ';
        foreach ($changes as $change) {
            $change_to = "";
            $column_name = "";
            switch ($change['key']) {
                case 'expiration_date':
                    $column_name = "Fecha de Vto.";
                    $change_to = !empty($ticketUpdate->expiration_date) ? date('d/m/Y', strtotime($ticketUpdate->expiration_date)) : "(sin especificar)";
                    break;
                case 'cliente_id':
                    $change_to = !empty($ticketUpdate->cliente_id) ?  "[$ticketUpdate->cliente_id] ".$ticketUpdate->cliente->nombre : "(sin especificar)";
                    $column_name = "Cliente";
                    break;
                case 'cliente_derivacion_id':
                    $change_to = !empty($ticketUpdate->cliente_derivacion_id) ?  "[$ticketUpdate->cliente_derivacion_id] ".$ticketUpdate->cliente_derivacion->nombre : "(sin especificar)";
                    $column_name = "Cliente Der.";
                    break;
                case 'category_ticket_id':
                    $change_to = !empty($ticketUpdate->category_ticket_id) ?  "[$ticketUpdate->category_ticket_id] ".$ticketUpdate->category_ticket->name : "(sin especificar)";
                    $column_name = "Categoria";
                    break;
                case 'priority_ticket_id':
                    $change_to = !empty($ticketUpdate->priority_ticket_id) ?  "[$ticketUpdate->priority_ticket_id] ".$ticketUpdate->priority_ticket->name : "(sin especificar)";
                    $column_name = "Prioridad";
                    break;
                case 'detail':
                    $change_to = !empty($ticketUpdate->detail) ?  $ticketUpdate->detail : "(sin especificar)";
                    $column_name = "Detalles";
                    break;
                case 'reason_ticket_id':
                    $change_to = !empty($ticketUpdate->reason_ticket_id) ?  "[$ticketUpdate->reason_ticket_id] ".$ticketUpdate->motivo_consulta->name : "(sin especificar)";
                    $column_name = "Motivo Consulta";
                    break;
                case 'user_receiver_id':
                    $change_to = !empty($ticketUpdate->user_receiver_id) ?  "[$ticketUpdate->user_receiver_id] ".$ticketUpdate->user_receiver->name : "(sin especificar)";
                    $column_name = "Usuario Destino";
                    break;
                case 'group_user_receiver_id':
                    $change_to = !empty($ticketUpdate->group_user_receiver_id) ?  "[$ticketUpdate->group_user_receiver_id] ".$ticketUpdate->group_user_receiver->name : "(sin especificar)";
                    $column_name = "Grupo Destino";
                    break;
                case 'user_owner_id':
                    $change_to = !empty($ticketUpdate->user_owner_id) ?  "[$ticketUpdate->user_owner_id] ".$ticketUpdate->user_owner->name : "(sin especificar)";
                    $column_name = "Usuario Resp.";
                    break;
                default:
                    $change_to = $change['new'];
                    $column_name = $change['key'];
                    break;
                
            }
            $notifMsg .= "<li><small>".$column_name." a <b>".$change_to."</b></small> </li> ";
        }

        $notifMsg .= "</ul> ";

        foreach($users as $user){
            //Notificacion de usuarios
            $data = [         
                'ticket_id' => $ticketUpdate->id,
                'email_to'=> $user->email,
              ];
            try {
                $user->notify(new TicketNotification($data,'Ticket ['.$ticketUpdate->id.'] modificado por '.Auth::user()->name.': <br> '.$notifMsg,$this->mailService,new ParamCompaniesService ));
            } catch (Throwable  $e) {
                Log::alert('No se pudo enviar el mail speedup. ERROR: ' . $e->getMessage());
                Log::info($e->getFile() . '(' . $e->getLine() . ')');

                $envio_email = 'No se pudo enviar el mail speedup. ERROR: ' . $e->getMessage();
                Log::alert($envio_email);
            }        
    }
        return $ticketUpdate;
    }

    //Actualiza el status del ticket
    public function updateStatus($status, $id)
    {
        $ticketUpdate = Ticket::findOrFail($id);
        $statusOld = $ticketUpdate->status_ticket_id;
        if( $status==5&&$ticketUpdate->user_owner_id != Auth::user()->id){
            return 'No tienes permisos para cerrar el Ticket seleccionado';
        }
        
            

        //Actualizo el nuevo status
        $ticketUpdate->update(['status_ticket_id'=>$status]);
        
        $users = User::join('user_group_ticket', 'users.id', '=', 'user_group_ticket.user_id')
            ->where('user_group_ticket.group_ticket_id', $ticketUpdate->group_user_receiver_id)
            ->get();

        $user = User::find($ticketUpdate->user_receiver_id);
        $userOwner = User::find($ticketUpdate->user_owner_id);

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
       
        //Conformo el mensaje para la notificacion
        $notifMsg ='';
        if($statusOld!=$ticketUpdate->status_ticket_id){
            $notifMsg ='Ticket ['.$ticketUpdate->id.'] modificado por '.Auth::user()->name.':<br>';
            $notifMsg .= "<ul><li><small>Estado a <b>[".$ticketUpdate->status_ticket->id."] ".$ticketUpdate->status_ticket->name."</b></small> </li> </ul>";
        }
        $users = $users->where('id', '<>' ,Auth::user()->id)->all();
        if(!empty($notifMsg)){
            foreach($users as $user){
                //Notificacion de usuarios
                $data = [         
                        'ticket_id' => $ticketUpdate->id,
                        'email_to'=> $user->email,
                    ];
                    try {
                        $user->notify(new TicketNotification($data, $notifMsg,$this->mailService,new ParamCompaniesService ));
                    } catch (Throwable  $e) {
                        Log::alert('No se pudo enviar el mail speedup. ERROR: ' . $e->getMessage());
                        Log::info($e->getFile() . '(' . $e->getLine() . ')');
        
                        $envio_email = 'No se pudo enviar el mail speedup. ERROR: ' . $e->getMessage();
                        Log::alert($envio_email);
                    }        
            }
        }
        
        return $ticketUpdate;
    }

    public function destroy($id)
    {
        $this->commentTicketService = new CommentTicketService($this->mailService,$this);
        $ticket = Ticket::find($id);
        $comments = CommentTicket::where('ticket_id', $ticket->id)->get();
        try {
            unlink(public_path().DIRECTORY_SEPARATOR."files".DIRECTORY_SEPARATOR.$ticket->file);
            $id = $ticket->id;
            foreach($comments as $comment){
                $this->commentTicketService->destroy($comment->id);
            }    
            $ticket->delete();
        } catch (\Throwable $th) {
            Log::alert('No se pudo eliminar el Ticket. ERROR: ' . $th->getMessage());
            $ticket->delete();
        }
       
        return $ticket;
    }

    public function filtrarTicket($request){

        session(['request_filtrar_ticket' => $request->all()]);
        
        $filtroagil = $request->input('filtroagil');
        
        $priorities = "";
        if(!empty($request->input('priorities'))){
            $priorities = implode(",", $request->input('priorities'));
        }
       

        $checkeds = [];

        switch ($filtroagil) {
            case 'asignadosami':
                $request->merge(['user_receiver_id'=>Auth::user()->id]);
                array_push($checkeds,"asignadosami");
                break;
            case 'creadospormi':
                $request->merge(['user_owner_id'=>Auth::user()->id]);
                array_push($checkeds,"creadospormi");
                break;
            case 'asignadosamigrupo':
                $request->merge(['group_user_receiver_id'=>implode(",", Auth::user()->group_ticket->pluck('id')->toArray())]);
                array_push($checkeds,"asignadosamigrupo");
                break;
            
        }

        
 

        $company_id = Session::get('userCompanyIdDefault');
        $texto = $request->input('texto');
        $type = $request->input('type');
        $status = $request['status_ticket_id'];
        $reason_ticket_id = $request->input('reason_ticket_id');
        $category_ticket_id = $request->input('category_ticket_id');
        $user_receiver_id = $request->input('user_receiver_id');
        $user_owner_id = $request->input('user_owner_id');
        $group_user_receiver_id = $request->input('group_user_receiver_id');
        $fecha = null;
        $fecha_desde = $request->input('fecha_desde');
        $fecha_hasta = $request->input('fecha_hasta');
        $expiration_date_desde = $request->input('fecha_vto_desde');
        $expiration_date_hasta = $request->input('fecha_vto_hasta');

        $idUser = Auth::user()->id;
        $tickets = $this->queryExec($texto, $type, $status, $reason_ticket_id, $category_ticket_id, $user_receiver_id, $user_owner_id, $group_user_receiver_id, $fecha, $fecha_desde, $fecha_hasta,$priorities,$expiration_date_desde, $expiration_date_hasta ,$idUser);
                
        $motivos_consulta = MotivoConsultaTicket::select('id', 'name')->where('company_id', $company_id)->get();
        $users = User::select('users.*')
        ->join('user_companies','users.id','=','user_companies.user_id')
        ->where('user_companies.company_id','=',$company_id)
        ->get();
        $categorias = CategoryTicket::select('id','name')->where('company_id', $company_id)->get();
        $grupos = GroupTicket::select('id','name')->where('company_id', $company_id)->get();
        $priorities_ticket = PriorityTicket::select('id','name')->get();
        $status_ticket = StatusTicket::select('id','name')->get();
        foreach (TicketType::getValues() as $value) {
            $tipos[$value] = TicketType::getKey($value);
        }

        return [
            'tickets' => $tickets,
            'motivos_consulta' => $motivos_consulta,
            'users' => $users,
            'categorias' => $categorias,
            'grupos' => $grupos,
            'priorities' => $priorities_ticket,
            'status' => $status_ticket,
            'tipos' => $tipos,
            'checkeds' =>$checkeds,
            'filtroagil' =>$filtroagil,
            'priorities_selecteds' => !empty($request->input('priorities')) ? $request->input('priorities') : [],
            'creacion_date_desde'=>$fecha_desde,
            'creacion_date_hasta'=>$fecha_hasta,
            'expiration_date_desde'=>$expiration_date_desde,
            'expiration_date_hasta'=>$expiration_date_hasta
        ];
    }

    public function filtrarTicketJob($request, $user_id, $company_id, $tomar, $saltear){

        
        
        $filtroagil = $request->input('filtroagil');
        
        $priorities = "";
        if(!empty($request->input('priorities'))){
            $priorities = implode(",", $request->input('priorities'));
        }
       
        $auth_user = User::with(['group_ticket'])->where('id',$user_id)->firstOrFail();
        $checkeds = [];

        switch ($filtroagil) {
            case 'asignadosami':
                $request->merge(['user_receiver_id'=>$auth_user->id]);
                array_push($checkeds,"asignadosami");
                break;
            case 'creadospormi':
                $request->merge(['user_owner_id'=>$auth_user->id]);
                array_push($checkeds,"creadospormi");
                break;
            case 'asignadosamigrupo':
                $request->merge(['group_user_receiver_id'=>implode(",", $auth_user->group_ticket->pluck('id')->toArray())]);
                array_push($checkeds,"asignadosamigrupo");
                break;
            
        }

        
 

        $texto = $request->input('texto');
        $type = $request->input('type');
        $status = $request['status_ticket_id'];
        $reason_ticket_id = $request->input('reason_ticket_id');
        $category_ticket_id = $request->input('category_ticket_id');
        $user_receiver_id = $request->input('user_receiver_id');
        $user_owner_id = $request->input('user_owner_id');
        $group_user_receiver_id = $request->input('group_user_receiver_id');
        $fecha = null;
        $fecha_desde = $request->input('fecha_desde');
        $fecha_hasta = $request->input('fecha_hasta');
        $expiration_date_desde = $request->input('fecha_vto_desde');
        $expiration_date_hasta = $request->input('fecha_vto_hasta');

        $idUser = $auth_user->id;
        return $this->queryExec($texto, $type, $status, $reason_ticket_id, $category_ticket_id, $user_receiver_id, $user_owner_id, $group_user_receiver_id, $fecha, $fecha_desde, $fecha_hasta,$priorities,$expiration_date_desde, $expiration_date_hasta ,$idUser,$company_id, $tomar, $saltear);
       
    }




    private function queryExec($texto = null,$type = null, $status = null, $reason_ticket_id = null, $category_ticket_id = null, $user_receiver_id = null, $user_owner_id = null, $group_user_receiver_id = null, $fecha = null, $fecha_desde = null, $fecha_hasta = null, $priority = null , $expiration_date_desde= null, $expiration_date_hasta= null,  $idUser, $userCompanyId=null, $tomar=null, $saltear=null) {
       
        if(!$userCompanyId){
            $userCompanyId  =  Session::get('userCompanyIdDefault') ? Session::get('userCompanyIdDefault') : Company::DEFAULT;
        }
        
        //El ticket SPEUP-5 pide que el usuario sea de la misma company y que al menos pertenezca a un grupo de ticket
        $query = Ticket::where('company_id', $userCompanyId);

        if($texto){
            $query = $query->where(function ($q) use ($texto) {
                $q->whereRaw("detail LIKE '%$texto%'")
                ->orWhereRaw("id = '$texto'")
                ->orWhereRaw("id = '$texto'")
                ->orWhereRaw("cliente_id IN (SELECT clientes.id FROM empresas_onsite clientes
                    WHERE tickets.cliente_id = clientes.id
                    AND (clientes.nombre LIKE '%$texto%'))")
                ->orWhereRaw("reparacion_id IN (SELECT reparaciones.id FROM reparaciones_onsite reparaciones
                    WHERE tickets.reparacion_id = reparaciones.id
                    AND (reparaciones.id = '$texto'))");
            });
        }
        if($type){
            $query = $query->whereRaw('tickets.type IN (' . $type . ')');
        }
        if($status){
            $query = $query->whereRaw('tickets.status_ticket_id IN (' . $status . ')');
        }
        if($reason_ticket_id){
            $query = $query->whereRaw('tickets.reason_ticket_id IN (' . $reason_ticket_id . ')');
        }
        if($category_ticket_id){
            $query = $query->whereRaw('tickets.category_ticket_id IN (' . $category_ticket_id . ')');
        }
        if($user_receiver_id){
            $query = $query->whereRaw('tickets.user_receiver_id IN (' . $user_receiver_id . ')');
        }
        if($user_owner_id){
            $query = $query->whereRaw('tickets.user_owner_id IN (' . $user_owner_id . ')');
        }
        if($group_user_receiver_id){
            $query = $query->whereRaw('tickets.group_user_receiver_id IN (' . $group_user_receiver_id . ')');
        }
        if ($fecha) {
            $query = $query->where(DB::raw("DATE_FORMAT( tickets.created_at , '%Y-%m-%d' )"), $fecha);
        }
        if (!empty($fecha_desde)) {
            $fecha_desde .= " 00:00:00";
            $query = $query->where(DB::raw("tickets.created_at"), '>=', $fecha_desde);
        }

        if (!empty($fecha_hasta) && empty($fecha_desde)) {
            $fecha_desde = date('Y-m')."-01";
            $fecha_desde .= " 00:00:00";
            $fecha_hasta .= " 23:59:59";

            $query = $query->where(DB::raw("tickets.created_at"), '>=', $fecha_desde);
            $query = $query->where(DB::raw("tickets.created_at"), '<=', $fecha_hasta);
        }elseif(!empty($fecha_hasta)){
            $fecha_hasta .= " 23:59:59";
            $query = $query->where(DB::raw("tickets.created_at"), '<=', $fecha_hasta);
        }
        

        if (!empty($priority)) {
            $query = $query->whereRaw('tickets.priority_ticket_id IN (' . $priority . ')');
        }

        if (!empty($expiration_date_desde)) {
            $expiration_date_desde .= " 00:00:00";
            $query = $query->where(DB::raw("tickets.expiration_date"), '>=', $expiration_date_desde);
        }

        if (!empty($expiration_date_hasta) && empty($expiration_date_desde)) {
            $expiration_date_desde = date('Y-m')."-01";
            $expiration_date_desde .= " 00:00:00";
            $expiration_date_hasta .= " 23:59:59";

            $query = $query->where(DB::raw("tickets.expiration_date"), '>=', $expiration_date_desde);
            $query = $query->where(DB::raw("tickets.expiration_date"), '<=', $expiration_date_hasta);
        }elseif(!empty($expiration_date_hasta)){
            $expiration_date_hasta .= " 23:59:59";
            $query = $query->where(DB::raw("tickets.expiration_date"), '<=', $expiration_date_hasta);
        }

        if ($tomar)
                return $query->skip($saltear)->take($tomar)->get();
            else
                return $query->paginate(100);

    }

    public function findTicketsByReparacionId($id){
        //Obtiene un ticket a partir de una reparacion ($id)
        $data = Ticket::where('reparacion_id',$id)->get();
        return $data;
    }

    public function findTicketsByDerivacionId($id){
        //Obtiene un ticket a partir de una derivacion ($id)
        $data = Ticket::where('derivacion_id',$id)->get();
        return $data;
    }

    public function findTicketById($id)
    {
        $data = Ticket::with('user_receiver')->join('users', 'tickets.user_owner_id', '=', 'users.id')
            ->where('tickets.id', $id)
            ->select('tickets.*', 'users.name as user_owner_name')
            ->first(); // Usar first() en lugar de get() para obtener un solo resultado

        return $data;
    }
}
