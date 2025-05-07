<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\Onsite\TiposServiciosService;
use Illuminate\Support\Facades\Session;

class TipoServicioController extends Controller
{
    public $bgh_company_id;
    protected $tiposServiciosService;

    /**
     * Constructor de la clase TipoServicioController.
     *
     * @param \App\Services\Onsite\TiposServiciosService $tiposServiciosService
     */
    public function __construct(TiposServiciosService $tiposServiciosService) {
        $this->bgh_company_id = 2;
        $this->tiposServiciosService = $tiposServiciosService;
    }

    /**
     * Obtener tipos de servicios por ID de empresa.
     *
     * @param int $company_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTiposServicios($company_id)
    {
        Log::info('getTiposServicios     ==============');
        Log::info('company_id '.$company_id);

        try {
            $tipos_servicios_onsite = $this->tiposServiciosService->getTiposServicios($company_id);
            Log::info($tipos_servicios_onsite);

            if (count($tipos_servicios_onsite) > 0) 
            {
              $respuesta = [
                'estado' => 'success',
                'codigo' => 200,
                'mensaje' => 'Tipos de Servicios Onsite: obtenidos!',
                'tipos_servicios_onsite' => $tipos_servicios_onsite
              ];
            }else{
              $respuesta = [
                'estado' => 'error',
                'codigo' => 404,
                'mensaje' => 'No se han encontrado tipos de servicios con el id de empresa '.$company_id
              ];
            }
        } catch (\Exception $e) {
          $respuesta = [
            'estado' => 'error',
            'codigo' => 500,
            'mensaje' => 'Ha ocurrido un error al obtener los tipos de servicios.'
          ];
        }

        return response()->json($respuesta, $respuesta['codigo']);
    }

    /**
     * Obtener tipos de servicios BGH.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTiposServiciosBgh()
    {
        $company_id = $this->bgh_company_id;
        Log::info('getTiposServiciosBgh     ==============');
        Log::info('company_id '.$company_id);

        try {
            $tipos_servicios_onsite = $this->tiposServiciosService->getTiposServicios($company_id);
            Log::info($tipos_servicios_onsite);

            if (count($tipos_servicios_onsite) > 0) 
            {
              $respuesta = [
                'estado' => 'success',
                'codigo' => 200,
                'mensaje' => 'Tipos de Servicios Onsite: obtenidos!',
                'tipos_servicios_onsite' => $tipos_servicios_onsite
              ];
            }else{
              $respuesta = [
                'estado' => 'error',
                'codigo' => 404,
                'mensaje' => 'No se han encontrado tipos de servicios con el id de empresa '.$company_id
              ];
            }
        } catch (\Exception $e) {
          $respuesta = [
            'estado' => 'error',
            'codigo' => 500,
            'mensaje' => 'Ha ocurrido un error al obtener los tipos de servicios.'
          ];
        }

        return response()->json($respuesta, $respuesta['codigo']);
    }
}
