<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\Onsite\UnidadExteriorOnsiteService;
use Illuminate\Support\Facades\Session;

class UnidadExteriorController extends Controller
{
    public $bgh_company_id;
    protected $unidadExteriorOnsiteService;

    /**
     * Constructor de la clase UnidadExteriorController.
     *
     * @param \App\Services\Onsite\UnidadExteriorOnsiteService $unidadExteriorOnsiteService
     */
    public function __construct(UnidadExteriorOnsiteService $unidadExteriorOnsiteService) {
        $this->bgh_company_id = 2;
        $this->unidadExteriorOnsiteService = $unidadExteriorOnsiteService;
    }

    /**
     * Obtener unidades exteriores por ID de empresa.
     *
     * @param int $company_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUnidadesExteriores($company_id)
    {
        Log::info('getUnidadesExteriores     ==============');
        Log::info('company_id '.$company_id);

        try {
            $unidades_exteriores_onsite = $this->unidadExteriorOnsiteService->getUnidadesExteriores($company_id);
            Log::info($unidades_exteriores_onsite);

            if (count($unidades_exteriores_onsite) > 0) 
            {
              $respuesta = [
                'estado' => 'success',
                'codigo' => 200,
                'mensaje' => 'Unidades Exteriores Onsite: obtenidas!',
                'unidades_exteriores_onsite' => $unidades_exteriores_onsite
              ];
            }else{
              $respuesta = [
                'estado' => 'error',
                'codigo' => 404,
                'mensaje' => 'No se han encontrado unidades exteriores con el id de empresa '.$company_id
              ];
            }
        } catch (\Exception $e) {
          $respuesta = [
            'estado' => 'error',
            'codigo' => 500,
            'mensaje' => 'Ha ocurrido un error al obtener las unidades exteriores.'
          ];
        }

        return response()->json($respuesta, $respuesta['codigo']);
    }

    /**
     * Obtener unidades exteriores BGH.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUnidadesExterioresBgh()
    {
        $company_id = $this->bgh_company_id;
        Log::info('getUnidadesExterioresBgh     ==============');
        Log::info('company_id '.$company_id);

        try {
            $unidades_exteriores_onsite = $this->unidadExteriorOnsiteService->getUnidadesExteriores($company_id);
            Log::info($unidades_exteriores_onsite);

            if (count($unidades_exteriores_onsite) > 0) 
            {
              $respuesta = [
                'estado' => 'success',
                'codigo' => 200,
                'mensaje' => 'Unidades Exteriores Onsite: obtenidas!',
                'unidades_exteriores_onsite' => $unidades_exteriores_onsite
              ];
            }else{
              $respuesta = [
                'estado' => 'error',
                'codigo' => 404,
                'mensaje' => 'No se han encontrado unidades exteriores con el id de empresa '.$company_id
              ];
            }
        } catch (\Exception $e) {
          $respuesta = [
            'estado' => 'error',
            'codigo' => 500,
            'mensaje' => 'Ha ocurrido un error al obtener las unidades exteriores.'
          ];
        }

        return response()->json($respuesta, $respuesta['codigo']);
    }
}
