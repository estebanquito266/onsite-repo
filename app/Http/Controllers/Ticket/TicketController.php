<?php

namespace App\Http\Controllers\Ticket;

use App\Jobs\ExportTicketsExcelJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\Onsite\TicketRequest;
use App\Models\Ticket\StatusTicket;
use App\Models\Ticket\Ticket;
use App\Services\ClientesService;
use App\Services\Onsite\TicketsService;
use App\Enums\TicketType;
use App\Models\Admin\Company;
use App\Models\Derivacion\Derivacion;
use App\Models\Onsite\ReparacionOnsite;
use App\Models\Reparacion\Reparacion;

class TicketController extends Controller
{
    protected $ticketsService;
    protected $clientesService;

    public function __construct(
        TicketsService $ticketsService
        // ClientesService $clientesService
    )
    {
        $this->ticketsService = $ticketsService;
        // $this->clientesService = $clientesService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Session::forget('request_filtrar_ticket');
        $data = $this->ticketsService->listado();

        return view('tickets.index',$data);
    }

    public function indexCerrados()
    {
        $data = $this->ticketsService->listadoCerrados();
        $data['estado'] = 'Cerrado';
        return view('tickets.index',$data);
    }

    public function createFromDerivacion(Request $request, $derivacionid)
    {
        $data = $this->ticketsService->create();
        $derivacion = Derivacion::where('id', $derivacionid)->firstOrFail();
        $data['ticket_tipo'] =  TicketType::Derivacion;
        $data['der_id'] =  $derivacionid;
        $data['cliente_derivacion'] =  $derivacion->cliente_derivacion;
        return view('tickets.create',$data);
    }

    public function createFromReparacion(Request $request, $reparacionid)
    {
        $data = $this->ticketsService->create();
        $reparacion = ReparacionOnsite::where('id', $reparacionid)->firstOrFail();
        $data['ticket_tipo'] =  TicketType::Reparacion;
        $data['rep_id'] =  $reparacionid;
        $data['cliente_reparacion'] =  $reparacion->cliente;
        return view('tickets.create',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = $this->ticketsService->create();

        return view('tickets.create',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TicketRequest $request)
    {
        $data = $this->ticketsService->store($request);

        if($data)
        //Controlo el mensaje a mostrar en pantalla
            if(is_string($data)){
                return back()->withErrors(['message'=>$data]);
            }else if($request->has('_modalTicket')){
                return redirect()->back()->with(['message'=>'Ticket creado correctamente']);
            }else{
                if($request['botonGuardarCerrar']==1)
                    return redirect()->route('ticket.index')->with(['message'=>"Se creó el ticket N° <b>$data->id</b>"]);
                else
                    return redirect()->route('ticket.edit',$data->id)->with(['message'=>'Ticket creado correctamente']);
            }                
        else
            return back()->withErrors(['message'=>'El Ticket no pudo ser creado correctamente']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
        $data = $this->ticketsService->edit($id);


        return view('tickets.show',$data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = $this->ticketsService->edit($id);


        return view('tickets.edit',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TicketRequest $request, $id)
    {
        $data = $this->ticketsService->update($request,$id);
        if($data)
        //Controlo el mensaje a mostrar en pantalla
            if(is_string($data))
                return back()->withErrors(['message'=>$data]);
            else
                if($request['botonGuardarCerrar']==1)
                    return redirect()->route('ticket.index')->with(['message'=>'Ticket editado correctamente']);
                else
                    return redirect()->route('ticket.edit',$data->id)->with(['message'=>'Ticket editado correctamente']);
        else
            return back()->withErrors(['message'=>'El Ticket no pudo ser editado correctamente']);
       
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ticket = $this->ticketsService->destroy($id);

        return redirect()->route('ticket.index')->with(['message'=>'Ticket ['.$ticket->id.'] eliminado correctamente']);
    }

    public function filtrarTickets(Request $request)
    {
        $data = $this->ticketsService->filtrarTicket($request);
        return view('tickets.index',$data);
    }

    public function exportarTickets(Request $request)
    {
        $filters = session('request_filtrar_ticket',[]);
		$companyId =  Session::get('userCompanyIdDefault') ? Session::get('userCompanyIdDefault') : Company::DEFAULT;

		$user = Auth::user();

        $job = (new ExportTicketsExcelJob($user->id,$filters,$companyId,Session::get('perfilAdmin')))->onConnection('database')->onQueue('speed');
 
        dispatch($job);
		
        //Necesario dormir 3 segundos porque a veces es tan rapido que no llega la notificacion
        sleep(3);
		return redirect('/ticket')->with('message', 'Será notificado cuando el Excel esté listo.');
      
    }

    public function filtrarTicketsCerrados(Request $request)
    {
        $request['status_ticket_id'] = StatusTicket::where('name',$request->input('estado'))->first()->id;
        $data = $this->ticketsService->filtrarTicket($request);
        $data['estado'] = 'Cerrado';
        return view('tickets.index',$data);
    }

    public function buscarClienteConReparaciones(Request $request)
    {
        $textoBuscar = $request['textoBuscar'];
        $data = $this->clientesService->buscarClienteConReparaciones($textoBuscar);

        return response()->json($data);
    }

    public function buscarCliente(Request $request)
    {
        $textoBuscar = $request['textoBuscar'];
        $data = ClientesService::listarPorTextoBuscar($textoBuscar);

        return response()->json($data);
    }

    
    public function findTicketByReparacionId(Request $request)
    {
        $idReparacion = $request->input('reparacionId');
        $data = $this->ticketsService->findTicketsByReparacionId($idReparacion);

        return response()->json($data);
    }

    public function findTicketByDerivacionId(Request $request)
    {   
        $idDerivacion = $request->input('derivacionId');
        $data = $this->ticketsService->findTicketsByDerivacionId($idDerivacion);

        return response()->json($data);
    }

    public function findTicketById(Request $request)
    {   
        $ticketId = $request->input('ticketId');
        $data = $this->ticketsService->findTicketById($ticketId);

        return response()->json($data);
    }
}
