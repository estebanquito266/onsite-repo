<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Onsite\TicketRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\Onsite\UpdateTecLocalidadOnsiteRequest;


use App\Services\Onsite\LocalidadService;
use App\Services\Onsite\TicketsService;
use App\Services\Onsite\UserService;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;

class TicketController extends Controller
{
    protected $localidadService;
    protected $userService;
    protected $ticketsService;

    public function __construct(LocalidadService $LocalidadService, UserService $UserService, TicketsService $TicketsService)
    {

        $this->localidadService = $LocalidadService;
        $this->userService = $UserService;
        $this->ticketsService = $TicketsService;
    }


    public function index(Request $request)
	{
		
		try {

			$setSessionUserProfile = $this->userService->setSessionUserProfile();
			
       

			$listado=$this->ticketsService->listado();

            $response = [
                'data'=> $listado['tickets'] ? $listado['tickets'] : []
            ];


			Session::flush();

			return response()->json($response['data'], 200);

        } catch (\Exception $e) {

            Session::flush();
            Log::error('TicketController index: ' . json_encode($request) . ' - Error: ' . $e->getMessage() . ' - File:' . $e->getFile() . ' - Line:' . $e->getLine());

            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Server Error'
            ], 500);
        }

	}

    public function filtrarTickets(HttpRequest $request)
	{
		
		try {

			$setSessionUserProfile = $this->userService->setSessionUserProfile();
           

			$listado = $this->ticketsService->filtrarTicket($request);

            $response = [
                'data'=> $listado['tickets'] ? $listado['tickets'] : []
            ];
       
            
			Session::flush();

			return response()->json($response, 200);

        } catch (\Exception $e) {

            Session::flush();
            Log::error('TicketController filtrarTickets: ' . json_encode($request) . ' - Error: ' . $e->getMessage() . ' - File:' . $e->getFile() . ' - Line:' . $e->getLine());

            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Server Error'
            ], 500);
        }

	}
    
    public function destroy(Request $request, $ticket_id)
    {
        $ticket = $this->ticketsService->destroy($ticket_id);

        try {

            //Primero seteo las variables de session
            $setSessionUserProfile = $this->userService->setSessionUserProfile();

            $toReturn = ['message'=>'Ticket ['.$ticket->id.'] eliminado correctamente'];

            
            Session::flush();

            if ($toReturn) {
                return response()->json([
                    'data' => $toReturn,
                ], 200);
            } else
                return response()->json([
                    'error' => 'Error al eliminar el ticket',
                    'message' => 'Server Error'
                ], 500);

        } catch (\Exception $e) {

            Session::flush();
            Log::error('ApiTicketController destroy: ' . json_encode($request) . ' - Error: ' . $e->getMessage() . ' - File:' . $e->getFile() . ' - Line:' . $e->getLine());

            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Server Error'
            ], 500);
        }

    }

    public function show(Request $request, $ticket_id)
    {
        try {

            //Primero seteo las variables de session
            $setSessionUserProfile = $this->userService->setSessionUserProfile();

            $toReturn = $this->ticketsService->findTicketById($ticket_id);

            Session::flush();

            if ($toReturn) {
                return response()->json([
                    'data' => $toReturn,
                ], 200);
            } else
                return response()->json([
                    'error' => 'Ticket no encontrado',
                    'message' => 'Server Error'
                ], 404);

        } catch (\Exception $e) {

            Session::flush();
            Log::error('ApiTicketController show: ' . json_encode($request) . ' - Error: ' . $e->getMessage() . ' - File:' . $e->getFile() . ' - Line:' . $e->getLine());

            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Server Error'
            ], 500);
        }
    }


    public function store(TicketRequest $request)
    {
        try {

            //Primero seteo las variables de session
            $setSessionUserProfile = $this->userService->setSessionUserProfile();

            $data = $this->ticketsService->store($request);

            $toReturn = ['message' => 'Ticket creado correctamente'];
            if ($data) {
                if (is_string($data)) {
                    $toReturn = ['message' => $data];
                } elseif (isset($data->id)) {
                    $toReturn = ['message' => 'Ticket creado correctamente', 'id' => $data->id];
                }
            } else {
                $toReturn = null;
            }

            Session::flush();

            if ($toReturn) {
                return response()->json([
                    'data' => $toReturn,
                ], 200);
            } else
                return response()->json([
                    'error' => 'Error al guardar el ticket',
                    'message' => 'Server Error'
                ], 500);
        } catch (\Exception $e) {

            Session::flush();
            Log::error('ApiTicketController store: ' . json_encode($request) . ' - Error: ' . $e->getMessage() . ' - File:' . $e->getFile() . ' - Line:' . $e->getLine());

            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Server Error'
            ], 500);
        }
    }

    public function update(TicketRequest $request, $ticket_id)
    {
        try {

            //Primero seteo las variables de session
            $setSessionUserProfile = $this->userService->setSessionUserProfile();

            $data = $this->ticketsService->update($request,$ticket_id);

            $toReturn = ['message' => 'Ticket actualizado correctamente'];
            if ($data) {
                if (is_string($data)) {
                    $toReturn = ['message' => $data];
                } elseif (isset($data->id)) {
                    $toReturn = ['message' => 'Ticket actualizado correctamente', 'id' => $data->id];
                }
            } else {
                $toReturn = null;
            }

            Session::flush();

            if ($toReturn) {
                return response()->json([
                    'data' => $toReturn,
                ], 200);
            } else
                return response()->json([
                    'error' => 'Error al actualizar el ticket',
                    'message' => 'Server Error'
                ], 500);
        } catch (\Exception $e) {

            Session::flush();
            Log::error('ApiTicketController update: ' . json_encode($request) . ' - Error: ' . $e->getMessage() . ' - File:' . $e->getFile() . ' - Line:' . $e->getLine());

            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Server Error'
            ], 500);
        }
    }

    
}
