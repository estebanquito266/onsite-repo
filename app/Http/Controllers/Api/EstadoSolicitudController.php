<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\Onsite\EstadosSolicitudesService;
use Illuminate\Support\Facades\Session;

class EstadoSolicitudController extends Controller
{
    public $bgh_company_id;
    protected $estadosSolicitudesService;

    /**
     * Constructor de la clase EstadoSolicitudController.
     *
     * @param \App\Services\Onsite\EstadosSolicitudesService $estadosSolicitudesService
     */
    public function __construct(EstadosSolicitudesService $estadosSolicitudesService) {
        $this->bgh_company_id = 2;
        $this->estadosSolicitudesService = $estadosSolicitudesService;
    }

    /**
     * Obtener estado de solicitudes por ID de empresa.
     *
     * @param int $company_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getEstadosSolicitud($company_id)
    {
        Log::info('getEstadosSolicitud     ==============');
        Log::info('company_id '.$company_id);

        try {
            $estados_solicitud_onsite = $this->estadosSolicitudesService->getEstadosSolicitud($company_id);
            Log::info($estados_solicitud_onsite);

            if (count($estados_solicitud_onsite) > 0) 
            {
              $respuesta = [
                'estado' => 'success',
                'codigo' => 200,
                'mensaje' => 'Estados de Solicitudes Onsite: obtenidos!',
                'estados_solicitud_onsite' => $estados_solicitud_onsite
              ];
            }else{
              $respuesta = [
                'estado' => 'error',
                'codigo' => 404,
                'mensaje' => 'No se han encontrado estados de solicitudes con el id de empresa '.$company_id
              ];
            }
        } catch (\Exception $e) {
          $respuesta = [
            'estado' => 'error',
            'codigo' => 500,
            'mensaje' => 'Ha ocurrido un error al obtener los estados de solicitudes.'
          ];
        }

        return response()->json($respuesta, $respuesta['codigo']);
    }

    /**
     * Obtener estado de solicitudes BGH.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getEstadosSolicitudBgh()
    {
        $company_id = $this->bgh_company_id;
        Log::info('getEstadosSolicitudBgh     ==============');
        Log::info('company_id '.$company_id);

        try {
            $estados_solicitud_onsite = $this->estadosSolicitudesService->getEstadosSolicitud($company_id);
            Log::info($estados_solicitud_onsite);

            if (count($estados_solicitud_onsite) > 0) 
            {
              $respuesta = [
                'estado' => 'success',
                'codigo' => 200,
                'mensaje' => 'Estados de Solicitudes Onsite: obtenidos!',
                'estados_solicitud_onsite' => $estados_solicitud_onsite
              ];
            }else{
              $respuesta = [
                'estado' => 'error',
                'codigo' => 404,
                'mensaje' => 'No se han encontrado estados de solicitudes con el id de empresa '.$company_id
              ];
            }
        } catch (\Exception $e) {
          $respuesta = [
            'estado' => 'error',
            'codigo' => 500,
            'mensaje' => 'Ha ocurrido un error al obtener los estados de solicitudes.'
          ];
        }

        return response()->json($respuesta, $respuesta['codigo']);
    }
}
