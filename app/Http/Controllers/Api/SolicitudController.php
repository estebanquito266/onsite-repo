<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\Onsite\SolicitudOnsiteService;
use Illuminate\Support\Facades\Session;

class SolicitudController extends Controller
{
    public $bgh_company_id;
    protected $solicitudOnsiteService;

    /**
     * Constructor de la clase SolicitudController.
     *
     * @param \App\Services\Onsite\SolicitudOnsiteService $solicitudOnsiteService
     */
    public function __construct(SolicitudOnsiteService $solicitudOnsiteService) {
        $this->bgh_company_id = 2;
        $this->solicitudOnsiteService = $solicitudOnsiteService;
    }

    /**
     * Obtener solicitudes por ID de empresa.
     *
     * @param int $company_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSolicitudes($company_id, $id = null)
    {
        Log::info('getSolicitudes     ==============');
        Log::info('company_id '.$company_id);

        try {
            $solicitudes_onsite = $this->solicitudOnsiteService->getSolicitudes($company_id, $id);
            Log::info($solicitudes_onsite);

            if (count($solicitudes_onsite) > 0) 
            {
              $respuesta = [
                'estado' => 'success',
                'codigo' => 200,
                'mensaje' => 'Solicitudes Onsite: obtenidas!',
                'solicitudes_onsite' => $solicitudes_onsite
              ];
            }else{
              $respuesta = [
                'estado' => 'error',
                'codigo' => 404,
                'mensaje' => 'No se han encontrado solicitudes con el id de empresa '.$company_id
              ];
            }
        } catch (\Exception $e) {
          $respuesta = [
            'estado' => 'error',
            'codigo' => 500,
            'mensaje' => 'Ha ocurrido un error al obtener las solicitudes.'
          ];
        }

        return response()->json($respuesta, $respuesta['codigo']);
    }

    /**
     * Obtener solicitudes BGH.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSolicitudesBgh($id = null)
    {
        $company_id = $this->bgh_company_id;
        Log::info('getSolicitudesBgh     ==============');
        Log::info('company_id '.$company_id);

        try {
            $solicitudes_onsite = $this->solicitudOnsiteService->getSolicitudes($company_id, $id);
            Log::info($solicitudes_onsite);

            if (count($solicitudes_onsite) > 0) 
            {
              $respuesta = [
                'estado' => 'success',
                'codigo' => 200,
                'mensaje' => 'Solicitudes Onsite: obtenidas!',
                'solicitudes_onsite' => $solicitudes_onsite
              ];
            }else{
              $respuesta = [
                'estado' => 'error',
                'codigo' => 404,
                'mensaje' => 'No se han encontrado solicitudes con el id de empresa '.$company_id
              ];
            }
        } catch (\Exception $e) {
          $respuesta = [
            'estado' => 'error',
            'codigo' => 500,
            'mensaje' => 'Ha ocurrido un error al obtener las solicitudes.'
          ];
        }

        return response()->json($respuesta, $respuesta['codigo']);
    }

    /**
     * Obtener solicitudes full por ID de empresa.
     *
     * @param int $company_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSolicitudesFull($company_id, $id = null)
    {
        Log::info('getSolicitudesFull     ==============');
        Log::info('company_id '.$company_id);

        try {
            $solicitudes_onsite = $this->solicitudOnsiteService->getSolicitudesFull($company_id, $id);
            Log::info($solicitudes_onsite);

            if (count($solicitudes_onsite) > 0) 
            {
              $respuesta = [
                'estado' => 'success',
                'codigo' => 200,
                'mensaje' => 'Solicitudes Onsite: obtenidas!',
                'solicitudes_onsite' => $solicitudes_onsite
              ];
            }else{
              $respuesta = [
                'estado' => 'error',
                'codigo' => 404,
                'mensaje' => 'No se han encontrado solicitudes con el id de empresa '.$company_id
              ];
            }
        } catch (\Exception $e) {
          $respuesta = [
            'estado' => 'error',
            'codigo' => 500,
            'mensaje' => 'Ha ocurrido un error al obtener las solicitudes.'
          ];
        }

        return response()->json($respuesta, $respuesta['codigo']);
    }

    /**
     * Obtener solicitudes full BGH.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSolicitudesFullBgh($id = null)
    {
        $company_id = $this->bgh_company_id;
        Log::info('getSolicitudesFullBgh     ==============');
        Log::info('company_id '.$company_id);

        try {
            $solicitudes_onsite = $this->solicitudOnsiteService->getSolicitudesFull($company_id, $id);
            Log::info($solicitudes_onsite);

            if (count($solicitudes_onsite) > 0) 
            {
              $respuesta = [
                'estado' => 'success',
                'codigo' => 200,
                'mensaje' => 'Solicitudes Onsite: obtenidas!',
                'solicitudes_onsite' => $solicitudes_onsite
              ];
            }else{
              $respuesta = [
                'estado' => 'error',
                'codigo' => 404,
                'mensaje' => 'No se han encontrado solicitudes con el id de empresa '.$company_id
              ];
            }
        } catch (\Exception $e) {
          $respuesta = [
            'estado' => 'error',
            'codigo' => 500,
            'mensaje' => 'Ha ocurrido un error al obtener las solicitudes.'
          ];
        }

        return response()->json($respuesta, $respuesta['codigo']);
    }
}
