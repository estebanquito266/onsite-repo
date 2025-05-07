<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\Onsite\UserService;
use App\Models\Tecnico;

class TecnicoController extends Controller
{
    public $bgh_company_id;
    protected $userService;

    /**
     * Constructor de la clase TecnicoController.
     *
     * @param \App\Services\Onsite\UserService $userService
     */
    public function __construct(UserService $userService) {
        $this->bgh_company_id = 2;
        $this->userService = $userService;
    }

    /**
     * Obtener tecnicos por ID de empresa.
     *
     * @param int $company_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTecnicos($company_id)
    {
        Log::info('getTecnicos     ==============');
        Log::info('company_id '.$company_id);

        try {
            $tecnicos_onsite = $this->userService->getTecnicos($company_id);
            Log::info($tecnicos_onsite);

            if (count($tecnicos_onsite) > 0) 
            {
                $respuesta = [
                  'estado' => 'success',
                  'codigo' => 200,
                  'mensaje' => 'Técnicos Onsite: obtenidos!',
                  'tecnicos_onsite' => $tecnicos_onsite
                ];
            } else {
                $respuesta = [
                  'estado' => 'error',
                  'codigo' => 404,
                  'mensaje' => 'No se han encontrado técnicos con el id de empresa '.$company_id
                ];
            }
        } catch (\Exception $e) {
            $respuesta = [
              'estado' => 'error',
              'codigo' => 500,
              'mensaje' => 'Ha ocurrido un error al obtener los técnicos.'
            ];
        }

        return response()->json($respuesta, $respuesta['codigo']);
    }

    /**
     * Obtener tecnicos BGH.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTecnicosBgh()
    {
        $company_id = $this->bgh_company_id;
        Log::info('getTecnicosBgh     ==============');
        Log::info('company_id '.$company_id);

        try {
            $tecnicos_onsite = $this->userService->getTecnicos($company_id);
            Log::info($tecnicos_onsite);

            if (count($tecnicos_onsite) > 0)
            {
                $respuesta = [
                  'estado' => 'success',
                  'codigo' => 200,
                  'mensaje' => 'Técnicos Onsite: obtenidos!',
                  'tecnicos_onsite' => $tecnicos_onsite
                ];
            } else {
                $respuesta = [
                  'estado' => 'error',
                  'codigo' => 404,
                  'mensaje' => 'No se han encontrado técnicos con el id de empresa '.$company_id
                ];
            }
        } catch (\Exception $e) {
            $respuesta = [
              'estado' => 'error',
              'codigo' => 500,
              'mensaje' => 'Ha ocurrido un error al obtener los técnicos.'
            ];
        }

        return response()->json($respuesta, $respuesta['codigo']);
    }
}
