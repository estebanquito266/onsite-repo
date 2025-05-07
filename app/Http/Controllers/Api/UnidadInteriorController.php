<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\Onsite\UnidadInteriorOnsiteService;
use Illuminate\Support\Facades\Session;

class UnidadInteriorController extends Controller
{
    public $bgh_company_id;
    protected $unidadInteriorOnsiteService;

    /**
     * Constructor de la clase UnidadInteriorController.
     *
     * @param \App\Services\Onsite\UnidadInteriorOnsiteService $unidadInteriorOnsiteService
     */
    public function __construct(UnidadInteriorOnsiteService $unidadInteriorOnsiteService) {
        $this->bgh_company_id = 2;
        $this->unidadInteriorOnsiteService = $unidadInteriorOnsiteService;
    }

    /**
     * Obtener unidades interiores por ID de empresa.
     *
     * @param int $company_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUnidadesInteriores($company_id)
    {
        Log::info('getUnidadesInteriores     ==============');
        Log::info('company_id '.$company_id);

        try {
            $unidades_interiores_onsite = $this->unidadInteriorOnsiteService->getUnidadesInteriores($company_id);
            Log::info($unidades_interiores_onsite);

            if (count($unidades_interiores_onsite) > 0) 
            {
              $respuesta = [
                'estado' => 'success',
                'codigo' => 200,
                'mensaje' => 'Unidades Interiores Onsite: obtenidas!',
                'unidades_interiores_onsite' => $unidades_interiores_onsite
              ];
            }else{
              $respuesta = [
                'estado' => 'error',
                'codigo' => 404,
                'mensaje' => 'No se han encontrado unidades interiores con el id de empresa '.$company_id
              ];
            }
        } catch (\Exception $e) {
          $respuesta = [
            'estado' => 'error',
            'codigo' => 500,
            'mensaje' => 'Ha ocurrido un error al obtener las unidades interiores.'
          ];
        }

        return response()->json($respuesta, $respuesta['codigo']);
    }

    /**
     * Obtener unidades interiores BGH.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUnidadesInterioresBgh()
    {
        $company_id = $this->bgh_company_id;
        Log::info('getUnidadesInterioresBgh     ==============');
        Log::info('company_id '.$company_id);

        try {
            $unidades_interiores_onsite = $this->unidadInteriorOnsiteService->getUnidadesInteriores($company_id);
            Log::info($unidades_interiores_onsite);

            if (count($unidades_interiores_onsite) > 0) 
            {
              $respuesta = [
                'estado' => 'success',
                'codigo' => 200,
                'mensaje' => 'Unidades Interiores Onsite: obtenidas!',
                'unidades_interiores_onsite' => $unidades_interiores_onsite
              ];
            }else{
              $respuesta = [
                'estado' => 'error',
                'codigo' => 404,
                'mensaje' => 'No se han encontrado unidades interiores con el id de empresa '.$company_id
              ];
            }
        } catch (\Exception $e) {
          $respuesta = [
            'estado' => 'error',
            'codigo' => 500,
            'mensaje' => 'Ha ocurrido un error al obtener las unidades interiores.'
          ];
        }

        return response()->json($respuesta, $respuesta['codigo']);
    }
}
