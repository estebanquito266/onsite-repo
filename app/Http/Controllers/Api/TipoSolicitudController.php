<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Onsite\TiposSolicitudesService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TipoSolicitudController extends Controller
{
    public $bgh_company_id;
    protected $tiposSolicitudesService;

    /**
     * Constructor de la clase TiposSolicitudesService.
     *
     * @param \App\Services\Onsite\TiposSolicitudesService $tiposSolicitudesService
     */
    public function __construct(TiposSolicitudesService $tiposSolicitudesService) {
        $this->bgh_company_id = 2;
        $this->tiposSolicitudesService = $tiposSolicitudesService;
    }

    public function getTiposSolicitudes($company_id)
    {
        Log::info('getTiposSolicitudes => ');
        Log::info('company_id '.$company_id);

        try {
            $tiposSolicitudes = $this->tiposSolicitudesService->getTiposSolicitudes($company_id);
            Log::info($tiposSolicitudes);

            if (count($tiposSolicitudes) > 0) 
            {
              $respuesta = [
                'estado' => 'success',
                'codigo' => 200,
                'mensaje' => 'Tipos de Solicitudes: obtenidos!',
                'tipos_solicitudes' => $tiposSolicitudes
              ];
            }else{
              $respuesta = [
                'estado' => 'error',
                'codigo' => 404,
                'mensaje' => 'No se han encontrado tipos de solicitudes con el id de empresa '.$company_id
              ];
            }
        } catch (\Exception $e) {
          $respuesta = [
            'estado' => 'error',
            'codigo' => 500,
            'mensaje' => 'Ha ocurrido un error al obtener los tipos de solicitudes.'
          ];
        }

        return response()->json($respuesta, $respuesta['codigo']);
    }

    public function getTiposSolicitudesBgh()
    {
        Log::info('getTiposSolicitudesBgh => ');
        Log::info('company_id '.$this->bgh_company_id);

        try {
            $tiposSolicitudes = $this->tiposSolicitudesService->getTiposSolicitudes($this->bgh_company_id);
            Log::info($tiposSolicitudes);

            if (count($tiposSolicitudes) > 0) 
            {
              $respuesta = [
                'estado' => 'success',
                'codigo' => 200,
                'mensaje' => 'Tipos de Solicitudes: obtenidos!',
                'tipos_solicitudes' => $tiposSolicitudes
              ];
            }else{
              $respuesta = [
                'estado' => 'error',
                'codigo' => 404,
                'mensaje' => 'No se han encontrado tipos de solicitudes con el id de empresa '.$this->bgh_company_id
              ];
            }
        } catch (\Exception $e) {
          $respuesta = [
            'estado' => 'error',
            'codigo' => 500,
            'mensaje' => 'Ha ocurrido un error al obtener los tipos de solicitudes.'
          ];
        }

        return response()->json($respuesta, $respuesta['codigo']);
    }
}
