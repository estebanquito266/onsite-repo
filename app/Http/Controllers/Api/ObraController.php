<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\Onsite\ObrasOnsiteService;
use Illuminate\Support\Facades\Session;

class ObraController extends Controller
{
    public $bgh_company_id;
    protected $obrasOnsiteService;

    /**
     * Constructor de la clase ObraController.
     *
     * @param \App\Services\Onsite\ObrasOnsiteService $obrasOnsiteService
     */
    public function __construct(ObrasOnsiteService $obrasOnsiteService) {
        $this->bgh_company_id = 2;
        $this->obrasOnsiteService = $obrasOnsiteService;
    }

    /**
     * Obtener obras por ID de empresa.
     *
     * @param int $company_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getObras($company_id, $clave = null)
    {
        Log::info('getObras     ==============');
        Log::info('company_id '.$company_id);

        try {
            $obras_onsite = $this->obrasOnsiteService->getObras($company_id, $clave);
            Log::info($obras_onsite);

            if (count($obras_onsite) > 0) 
            {
              $respuesta = [
                'estado' => 'success',
                'codigo' => 200,
                'mensaje' => 'Obras Onsite: obtenidas!',
                'obras_onsite' => $obras_onsite
              ];
            }else{
              $respuesta = [
                'estado' => 'error',
                'codigo' => 404,
                'mensaje' => 'No se han encontrado obras con el id de empresa '.$company_id
              ];
            }
        } catch (\Exception $e) {
          $respuesta = [
            'estado' => 'error',
            'codigo' => 500,
            'mensaje' => 'Ha ocurrido un error al obtener las obras.'
          ];
        }

        return response()->json($respuesta, $respuesta['codigo']);
    }

    /**
     * Obtener obras BGH.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getObrasBgh($clave = null)
    {
        $company_id = $this->bgh_company_id;
        Log::info('getObrasBgh     ==============');
        Log::info('company_id '.$company_id);

        try {
            $obras_onsite = $this->obrasOnsiteService->getObras($company_id, $clave);
            Log::info($obras_onsite);

            if (count($obras_onsite) > 0) 
            {
              $respuesta = [
                'estado' => 'success',
                'codigo' => 200,
                'mensaje' => 'Obras Onsite: obtenidas!',
                'obras_onsite' => $obras_onsite
              ];
            }else{
              $respuesta = [
                'estado' => 'error',
                'codigo' => 404,
                'mensaje' => 'No se han encontrado obras con el id de empresa '.$company_id
              ];
            }
        } catch (\Exception $e) {
          $respuesta = [
            'estado' => 'error',
            'codigo' => 500,
            'mensaje' => 'Ha ocurrido un error al obtener las obras.'
          ];
        }

        return response()->json($respuesta, $respuesta['codigo']);
    }

    /**
     * Obtener obras full por ID de empresa.
     *
     * @param int $company_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getObrasFull($company_id, $clave = null)
    {
        Log::info('getObrasFull     ==============');
        Log::info('company_id '.$company_id);

        try {
            $obras_onsite = $this->obrasOnsiteService->getObrasFull($company_id, $clave);
            Log::info($obras_onsite);

            if (count($obras_onsite) > 0) 
            {
              $respuesta = [
                'estado' => 'success',
                'codigo' => 200,
                'mensaje' => 'Obras Onsite: obtenidas!',
                'obras_onsite' => $obras_onsite
              ];
            }else{
              $respuesta = [
                'estado' => 'error',
                'codigo' => 404,
                'mensaje' => 'No se han encontrado obras con el id de empresa '.$company_id
              ];
            }
        } catch (\Exception $e) {
          $respuesta = [
            'estado' => 'error',
            'codigo' => 500,
            'mensaje' => 'Ha ocurrido un error al obtener las obras.'
          ];
        }

        return response()->json($respuesta, $respuesta['codigo']);
    }

    /**
     * Obtener obras full BGH.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getObrasFullBgh($clave = null)
    {
        $company_id = $this->bgh_company_id;
        Log::info('getObrasFullBgh     ==============');
        Log::info('company_id '.$company_id);

        try {
            $obras_onsite = $this->obrasOnsiteService->getObrasFull($company_id, $clave);
            Log::info($obras_onsite);

            if (count($obras_onsite) > 0) 
            {
              $respuesta = [
                'estado' => 'success',
                'codigo' => 200,
                'mensaje' => 'Obras Onsite: obtenidas!',
                'obras_onsite' => $obras_onsite
              ];
            }else{
              $respuesta = [
                'estado' => 'error',
                'codigo' => 404,
                'mensaje' => 'No se han encontrado obras con el id de empresa '.$company_id
              ];
            }
        } catch (\Exception $e) {
          $respuesta = [
            'estado' => 'error',
            'codigo' => 500,
            'mensaje' => 'Ha ocurrido un error al obtener las obras.'
          ];
        }

        return response()->json($respuesta, $respuesta['codigo']);
    }
}
