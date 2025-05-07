<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\Onsite\SucursalOnsiteService;
use Illuminate\Support\Facades\Session;

class SucursalController extends Controller
{
    public $bgh_company_id;
    protected $sucursalOnsiteService;

    /**
     * Constructor de la clase SucursalController.
     *
     * @param \App\Services\Onsite\SucursalOnsiteService $sucursalOnsiteService
     */
    public function __construct(SucursalOnsiteService $sucursalOnsiteService) {
        $this->bgh_company_id = 2;
        $this->sucursalOnsiteService = $sucursalOnsiteService;
    }

    /**
     * Obtener sucursales por ID de empresa.
     *
     * @param int $company_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSucursales($company_id)
    {
        Log::info('getSucursales     ==============');
        Log::info('company_id '.$company_id);

        try {
            $sucursales_onsite = $this->sucursalOnsiteService->getSucursales($company_id);
            Log::info($sucursales_onsite);

            if (count($sucursales_onsite) > 0) 
            {
              $respuesta = [
                'estado' => 'success',
                'codigo' => 200,
                'mensaje' => 'Sucursales Onsite: obtenidas!',
                'sucursales_onsite' => $sucursales_onsite
              ];
            }else{
              $respuesta = [
                'estado' => 'error',
                'codigo' => 404,
                'mensaje' => 'No se han encontrado sucursales con el id de empresa '.$company_id
              ];
            }
        } catch (\Exception $e) {
          $respuesta = [
            'estado' => 'error',
            'codigo' => 500,
            'mensaje' => 'Ha ocurrido un error al obtener las sucursales.'
          ];
        }

        return response()->json($respuesta, $respuesta['codigo']);
    }

    /**
     * Obtener sucursales BGH.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSucursalesBgh()
    {
        $company_id = $this->bgh_company_id;
        Log::info('getSucursalesBgh     ==============');
        Log::info('company_id '.$company_id);

        try {
            $sucursales_onsite = $this->sucursalOnsiteService->getSucursales($company_id);
            Log::info($sucursales_onsite);

            if (count($sucursales_onsite) > 0) 
            {
              $respuesta = [
                'estado' => 'success',
                'codigo' => 200,
                'mensaje' => 'Sucursales Onsite: obtenidas!',
                'sucursales_onsite' => $sucursales_onsite
              ];
            }else{
              $respuesta = [
                'estado' => 'error',
                'codigo' => 404,
                'mensaje' => 'No se han encontrado sucursales con el id de empresa '.$company_id
              ];
            }
        } catch (\Exception $e) {
          $respuesta = [
            'estado' => 'error',
            'codigo' => 500,
            'mensaje' => 'Ha ocurrido un error al obtener las sucursales.'
          ];
        }

        return response()->json($respuesta, $respuesta['codigo']);
    }
}
