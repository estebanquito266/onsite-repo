<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\Onsite\ReparacionOnsiteService;
use App\Services\Onsite\VisitasService;
use Illuminate\Support\Facades\Session;

class VisitaController extends Controller
{
  public $bgh_company_id;
  protected $visitasService;


  public function __construct(
    VisitasService $visitasService
  ) {
    $this->bgh_company_id = 2;
    $this->visitasService = $visitasService;
  }

  /**
   * Obtener visitas por ID de empresa.
   *
   * @param int $company_id
   * @return \Illuminate\Http\JsonResponse
   */
  public function getVisitas($company_id, $clave = null)
  {
    Log::info('getVisitas     ==============');
    Log::info('company_id ' . $company_id);

    try {
      $visitas_onsite = $this->visitasService->getVisitas($company_id, $clave);
      Log::info($visitas_onsite);

      if (count($visitas_onsite) > 0) {
        $respuesta = [
          'estado' => 'success',
          'codigo' => 200,
          'mensaje' => 'Visitas Onsite: obtenidas!',
          'visitas_onsite' => $visitas_onsite
        ];
      } else {
        $respuesta = [
          'estado' => 'error',
          'codigo' => 404,
          'mensaje' => 'No se han encontrado visitas con el id de empresa ' . $company_id
        ];
      }
    } catch (\Exception $e) {
      $respuesta = [
        'estado' => 'error',
        'codigo' => 500,
        'mensaje' => 'Ha ocurrido un error al obtener las visitas.'
      ];
    }

    return response()->json($respuesta, $respuesta['codigo']);
  }

  /**
   * Obtener visitas BGH.
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function getVisitasBgh($clave = null)
  {
    $company_id = $this->bgh_company_id;
    Log::info('getVisitasBgh     ==============');
    Log::info('company_id ' . $company_id);

    try {
      $visitas_onsite = $this->visitasService->getVisitas($company_id, $clave);
      Log::info($visitas_onsite);

      if (count($visitas_onsite) > 0) {
        $respuesta = [
          'estado' => 'success',
          'codigo' => 200,
          'mensaje' => 'Visitas Onsite: obtenidas!',
          'visitas_onsite' => $visitas_onsite
        ];
      } else {
        $respuesta = [
          'estado' => 'error',
          'codigo' => 404,
          'mensaje' => 'No se han encontrado visitas con el id de empresa ' . $company_id
        ];
      }
    } catch (\Exception $e) {
      $respuesta = [
        'estado' => 'error',
        'codigo' => 500,
        'mensaje' => 'Ha ocurrido un error al obtener las visitas.'
      ];
    }

    return response()->json($respuesta, $respuesta['codigo']);
  }

  /**
   * Obtener visitas full por ID de empresa.
   *
   * @param int $company_id
   * @return \Illuminate\Http\JsonResponse
   */
  public function getVisitasFull($company_id, $clave = null)
  {
    Log::info('getVisitasFull     ==============');
    Log::info('company_id ' . $company_id);

    try {
      $visitas_onsite = $this->visitasService->getVisitasFull($company_id, $clave);
      Log::info($visitas_onsite);

      if (count($visitas_onsite) > 0) {
        $respuesta = [
          'estado' => 'success',
          'codigo' => 200,
          'mensaje' => 'Visitas Onsite: obtenidas!',
          'visitas_onsite' => $visitas_onsite
        ];
      } else {
        $respuesta = [
          'estado' => 'error',
          'codigo' => 404,
          'mensaje' => 'No se han encontrado visitas con el id de empresa ' . $company_id
        ];
      }
    } catch (\Exception $e) {
      $respuesta = [
        'estado' => 'error',
        'codigo' => 500,
        'mensaje' => 'Ha ocurrido un error al obtener las visitas.'
      ];
    }

    return response()->json($respuesta, $respuesta['codigo']);
  }

  /**
   * Obtener visitas full BGH.
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function getVisitasFullBgh($clave = null)
  {
    $company_id = $this->bgh_company_id;
    Log::info('getVisitasFullBgh     ==============');
    Log::info('company_id ' . $company_id);

    try {
      $visitas_onsite = $this->visitasService->getVisitasFull($company_id, $clave);
      Log::info($visitas_onsite);

      if (count($visitas_onsite) > 0) {
        $respuesta = [
          'estado' => 'success',
          'codigo' => 200,
          'mensaje' => 'Visitas Onsite: obtenidas!',
          'visitas_onsite' => $visitas_onsite
        ];
      } else {
        $respuesta = [
          'estado' => 'error',
          'codigo' => 404,
          'mensaje' => 'No se han encontrado visitas con el id de empresa ' . $company_id
        ];
      }
    } catch (\Exception $e) {
      $respuesta = [
        'estado' => 'error',
        'codigo' => 500,
        'mensaje' => 'Ha ocurrido un error al obtener las visitas.'
      ];
    }

    return response()->json($respuesta, $respuesta['codigo']);
  }
}
