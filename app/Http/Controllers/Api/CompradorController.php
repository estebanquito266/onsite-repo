<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\Onsite\CompradoresOnsiteService;
use Illuminate\Support\Facades\Session;

class CompradorController extends Controller
{
    public $bgh_company_id;
    protected $compradoresOnsiteService;

    /**
     * Constructor de la clase CompradorController.
     *
     * @param \App\Services\Onsite\CompradoresOnsiteService $compradoresOnsiteService
     */
    public function __construct(CompradoresOnsiteService $compradoresOnsiteService) {
        $this->bgh_company_id = 2;
        $this->compradoresOnsiteService = $compradoresOnsiteService;
    }

    /**
     * Obtener compradores por ID de empresa.
     *
     * @param int $company_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCompradores($company_id)
    {
        Log::info('getCompradores     ==============');
        Log::info('company_id '.$company_id);

        try {
            $compradores_onsite = $this->compradoresOnsiteService->getCompradores($company_id);
            Log::info($compradores_onsite);

            if (count($compradores_onsite) > 0) 
            {
              $respuesta = [
                'estado' => 'success',
                'codigo' => 200,
                'mensaje' => 'Compradores Onsite: obtenidos!',
                'compradores_onsite' => $compradores_onsite
              ];
            }else{
              $respuesta = [
                'estado' => 'error',
                'codigo' => 404,
                'mensaje' => 'No se han encontrado compradores con el id de empresa '.$company_id
              ];
            }
        } catch (\Exception $e) {
          $respuesta = [
            'estado' => 'error',
            'codigo' => 500,
            'mensaje' => 'Ha ocurrido un error al obtener los compradores.'
          ];
        }

        return response()->json($respuesta, $respuesta['codigo']);
    }

    /**
     * Obtener compradores BGH.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCompradoresBgh()
    {
        $company_id = $this->bgh_company_id;
        Log::info('getCompradoresBgh     ==============');
        Log::info('company_id '.$company_id);

        try {
            $compradores_onsite = $this->compradoresOnsiteService->getCompradores($company_id);
            Log::info($compradores_onsite);

            if (count($compradores_onsite) > 0) 
            {
              $respuesta = [
                'estado' => 'success',
                'codigo' => 200,
                'mensaje' => 'Compradores Onsite: obtenidos!',
                'compradores_onsite' => $compradores_onsite
              ];
            }else{
              $respuesta = [
                'estado' => 'error',
                'codigo' => 404,
                'mensaje' => 'No se han encontrado compradores con el id de empresa '.$company_id
              ];
            }
        } catch (\Exception $e) {
          $respuesta = [
            'estado' => 'error',
            'codigo' => 500,
            'mensaje' => 'Ha ocurrido un error al obtener los compradores.'
          ];
        }

        return response()->json($respuesta, $respuesta['codigo']);
    }
}
