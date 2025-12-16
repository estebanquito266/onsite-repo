<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use PhpParser\CommentTest;
use App\Http\Controllers\Controller;
use App\Models\Ticket\CommentTicket;
use App\Services\Onsite\CommentTicketService;
use App\Services\Onsite\UserService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class CommentTicketController extends Controller
{
    protected $commentTicketsService;
    protected $userService;
    public function __construct(CommentTicketService $commentTicketsService, UserService $UserService)
    {
        $this->commentTicketsService = $commentTicketsService;
        $this->userService = $UserService;
    }
  
    public function store(Request $request)
    {
        try {

            $setSessionUserProfile = $this->userService->setSessionUserProfile();


            
            $data = $this->commentTicketsService->store($request);

            $toReturn = ['message' => 'Comentario creado correctamente'];
            $statusCode = 200;

            Session::flush();

            if ($data) {
                if(is_string($data)){
                    $toReturn = ['message' => $data];
                    $statusCode = 401;
                }
            } else {
                $toReturn = null;
            }

            if ($toReturn) {
                return response()->json([
                    'data' => $toReturn,
                ], $statusCode);
            } else
                return response()->json([
                    'error' => 'Error al guardar el ticket',
                    'message' => 'Server Error'
                ], 500);

        } catch (\Exception $e) {

            Session::flush();
            Log::error('API CommentTicketController store: ' . json_encode($request) . ' - Error: ' . $e->getMessage() . ' - File:' . $e->getFile() . ' - Line:' . $e->getLine());

            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Server Error'
            ], 500);
        }
        
            
    }

   

    public function findCommentsByTicketId(Request $request,$ticket_id){

        
        try {
            $setSessionUserProfile = $this->userService->setSessionUserProfile();

            //Obtiene un ticket a partir de una reparacion ($id)
            $data = $this->commentTicketsService->findCommentsByTicketId($ticket_id);

            Session::flush();

            return response()->json([
                        'data' => $data,
                    ], 200);

        } catch (\Exception $e) {

            Session::flush();
            Log::error('API CommentTicketController findCommentsByTicketId: ' . json_encode($request) . ' - Error: ' . $e->getMessage() . ' - File:' . $e->getFile() . ' - Line:' . $e->getLine());

            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Server Error'
            ], 500);
        }
    }
}
