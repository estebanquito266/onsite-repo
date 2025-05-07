<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\Onsite\SistemaOnsiteService;
use Illuminate\Support\Facades\Session;

class SistemaController extends Controller
{
  public $bgh_company_id;
  protected $sistemaOnsiteService;

  /**
   * Constructor de la clase SistemaController.
   *
   * @param \App\Services\Onsite\SistemaOnsiteService $sistemaOnsiteService
   */
  public function __construct(SistemaOnsiteService $sistemaOnsiteService) {
      $this->bgh_company_id = 2;
      $this->sistemaOnsiteService = $sistemaOnsiteService;
  }

  /**
   * Obtener sistemas por ID de empresa.
   *
   * @param int $company_id
   * @return \Illuminate\Http\JsonResponse
   */
  public function getSistemas($company_id, $id = null)
  {
      Log::info('getSistemas     ==============');
      Log::info('company_id '.$company_id);

      try {
          $sistemas_onsite = $this->sistemaOnsiteService->getSistemas($company_id, $id);
          Log::info($sistemas_onsite);

          if (count($sistemas_onsite) > 0) 
          {
            $respuesta = [
              'estado' => 'success',
              'codigo' => 200,
              'mensaje' => 'Sistemas Onsite: obtenidos!',
              'sistemas_onsite' => $sistemas_onsite
            ];
          }else{
            $respuesta = [
              'estado' => 'error',
              'codigo' => 404,
              'mensaje' => 'No se han encontrado sistemas con el id de empresa '.$company_id
            ];
          }
      } catch (\Exception $e) {
        $respuesta = [
          'estado' => 'error',
          'codigo' => 500,
          'mensaje' => 'Ha ocurrido un error al obtener los sistemas.'
        ];
      }

      return response()->json($respuesta, $respuesta['codigo']);
  }

  /**
   * Obtener sistemas BGH.
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function getSistemasBgh($id = null)
  {
      $company_id = $this->bgh_company_id;
      Log::info('getSistemasBgh     ==============');
      Log::info('company_id '.$company_id);

      try {
          $sistemas_onsite = $this->sistemaOnsiteService->getSistemas($company_id, $id);
          Log::info($sistemas_onsite);

          if (count($sistemas_onsite) > 0) 
          {
            $respuesta = [
              'estado' => 'success',
              'codigo' => 200,
              'mensaje' => 'Sistemas Onsite: obtenidos!',
              'sistemas_onsite' => $sistemas_onsite
            ];
          }else{
            $respuesta = [
              'estado' => 'error',
              'codigo' => 404,
              'mensaje' => 'No se han encontrado sistemas con el id de empresa '.$company_id
            ];
          }
      } catch (\Exception $e) {
        $respuesta = [
          'estado' => 'error',
          'codigo' => 500,
          'mensaje' => 'Ha ocurrido un error al obtener los sistemas.'
        ];
      }

      return response()->json($respuesta, $respuesta['codigo']);
  }

  /**
   * Obtener sistemas full por ID de empresa.
   *
   * @param int $company_id
   * @return \Illuminate\Http\JsonResponse
   */
  public function getSistemasFull($company_id, $id = null)
  {
      Log::info('getSistemasFull     ==============');
      Log::info('company_id '.$company_id);

      try {
          $sistemas_onsite = $this->sistemaOnsiteService->getSistemasFull($company_id, $id);
          Log::info($sistemas_onsite);

          if (count($sistemas_onsite) > 0) 
          {
            $respuesta = [
              'estado' => 'success',
              'codigo' => 200,
              'mensaje' => 'Sistemas Onsite: obtenidos!',
              'sistemas_onsite' => $sistemas_onsite
            ];
          }else{
            $respuesta = [
              'estado' => 'error',
              'codigo' => 404,
              'mensaje' => 'No se han encontrado sistemas con el id de empresa '.$company_id
            ];
          }
      } catch (\Exception $e) {
        $respuesta = [
          'estado' => 'error',
          'codigo' => 500,
          'mensaje' => 'Ha ocurrido un error al obtener los sistemas.'
        ];
      }

      return response()->json($respuesta, $respuesta['codigo']);
  }

  /**
   * Obtener sistemas full BGH.
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function getSistemasFullBgh($id = null)
  {
      $company_id = $this->bgh_company_id;
      Log::info('getSistemasFullBgh     ==============');
      Log::info('company_id '.$company_id);

      try {
          $sistemas_onsite = $this->sistemaOnsiteService->getSistemasFull($company_id, $id);
          Log::info($sistemas_onsite);

          if (count($sistemas_onsite) > 0) 
          {
            $respuesta = [
              'estado' => 'success',
              'codigo' => 200,
              'mensaje' => 'Sistemas Onsite: obtenidos!',
              'sistemas_onsite' => $sistemas_onsite
            ];
          }else{
            $respuesta = [
              'estado' => 'error',
              'codigo' => 404,
              'mensaje' => 'No se han encontrado sistemas con el id de empresa '.$company_id
            ];
          }
      } catch (\Exception $e) {
        $respuesta = [
          'estado' => 'error',
          'codigo' => 500,
          'mensaje' => 'Ha ocurrido un error al obtener los sistemas.'
        ];
      }

      return response()->json($respuesta, $respuesta['codigo']);
  }
}
