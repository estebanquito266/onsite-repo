<?php

namespace App\Http\Controllers\Ticket;

use Illuminate\Http\Request;
use PhpParser\CommentTest;
use App\Http\Controllers\Controller;
use App\Models\Ticket\CommentTicket;
use App\Services\CommentTicketService;

class CommentTicketController extends Controller
{
    protected $commentTicketsService;

    public function __construct(CommentTicketService $commentTicketsService)
    {
        $this->commentTicketsService = $commentTicketsService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $this->commentTicketsService->store($request);
        if(isset($data)){
            if($request->has('_modalComment')){
                if(is_string($data))
                    return back()->withErrors(['message'=>$data]);
                else
                    return redirect()->back()->with(['message'=>'Comentario creado correctamente']);
            }else{
                if(is_string($data))
                    return back()->withErrors(['message'=>$data]);
                else
                    return redirect()->route('ticket.edit',$data->ticket_id)->with(['message'=>'Comentario creado correctamente']);
            }
        }else{
            return back()->withErrors(['message'=>'El comentario no pudo ser creado correctamente']);
        }
            
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $res = $this->commentTicketsService->destroy($id);
        if($res)
            return redirect()->back()->with(['message'=>'Comentario eliminado correctamente']);
    }

    public function findCommentsByTicketId(Request $request){
        //Obtiene un ticket a partir de una reparacion ($id)
        $idTicket = $request->input('ticketId');
        $data = $this->commentTicketsService->findCommentsByTicketId($idTicket);
        return $data;
    }
}
