<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\Onsite\EstadosService;
use Illuminate\Support\Facades\Session;

class EstadoController extends Controller
{
  public $bgh_company_id;
  protected $estadosService;

  /**
   * Constructor de la clase EstadoController.
   *
   * @param \App\Services\Onsite\EstadosService $estadosService
   */
  public function __construct(EstadosService $estadosService)
  {
    $this->bgh_company_id = 2;
    $this->estadosService = $estadosService;
  }

  /**
   * Obtener estados por ID de empresa.
   *
   * @param int $company_id
   * @return \Illuminate\Http\JsonResponse
   */
  public function getEstados($company_id, $tipo = null)
  {
    Log::info('getEstados     ==============');
    Log::info('company_id ' . $company_id);

    try {
      $estados_onsite = $this->estadosService->getEstados($company_id, $tipo);
      Log::info($estados_onsite);

      if (count($estados_onsite) > 0) {
        $respuesta = [
          'estado' => 'success',
          'codigo' => 200,
          'mensaje' => 'Estados Onsite: obtenidos!',
          'estados_onsite' => $estados_onsite
        ];
      } else {
        $respuesta = [
          'estado' => 'error',
          'codigo' => 404,
          'mensaje' => 'No se han encontrado estados con el id de empresa ' . $company_id
        ];
      }
    } catch (\Exception $e) {
      $respuesta = [
        'estado' => 'error',
        'codigo' => 500,
        'mensaje' => 'Ha ocurrido un error al obtener los estados.'
      ];
    }

    return response()->json($respuesta, $respuesta['codigo']);
  }

  /**
   * Obtener estados BGH.
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function getEstadosBgh()
  {
    $company_id = $this->bgh_company_id;
    Log::info('getEstadosBgh     ==============');
    Log::info('company_id ' . $company_id);

    try {
      $estados_onsite = $this->estadosService->getEstados($company_id);
      Log::info($estados_onsite);

      if (count($estados_onsite) > 0) {
        $respuesta = [
          'estado' => 'success',
          'codigo' => 200,
          'mensaje' => 'Estados Onsite: obtenidos!',
          'estados_onsite' => $estados_onsite
        ];
      } else {
        $respuesta = [
          'estado' => 'error',
          'codigo' => 404,
          'mensaje' => 'No se han encontrado estados con el id de empresa ' . $company_id
        ];
      }
    } catch (\Exception $e) {
      $respuesta = [
        'estado' => 'error',
        'codigo' => 500,
        'mensaje' => 'Ha ocurrido un error al obtener los estados.'
      ];
    }

    return response()->json($respuesta, $respuesta['codigo']);
  }
}
