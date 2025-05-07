<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\Onsite\UserService;
use Illuminate\Support\Facades\Session;
use App\Http\Resources\UserResource;
use App\Models\User;

class UserController extends Controller
{
  public $bgh_company_id;
  protected $userService;

  /**
   * Constructor de la clase UserController.
   *
   * @param \App\Services\Onsite\UserService $userService
   */
  public function __construct(UserService $userService) {
      $this->bgh_company_id = 2;
      $this->userService = $userService;
  }

  /**
   * Obtener usuarios por ID de empresa.
   *
   * @param int $company_id
   * @return \Illuminate\Http\JsonResponse
   */
  public function getUsers($company_id)
  {
      Log::info('getUsers     ==============');
      Log::info('company_id '.$company_id);

      try {
          $users_onsite = $this->userService->getUsersOnsite($company_id);
          Log::info($users_onsite);

          if (count($users_onsite) > 0) 
          {
            $respuesta = [
              'estado' => 'success',
              'codigo' => 200,
              'mensaje' => 'Usuarios Onsite: obtenidos!',
              'users_onsite' => $users_onsite
            ];
          }else{
            $respuesta = [
              'estado' => 'error',
              'codigo' => 404,
              'mensaje' => 'No se han encontrado usuarios con el id de empresa '.$company_id
            ];
          }
      } catch (\Exception $e) {
        $respuesta = [
          'estado' => 'error',
          'codigo' => 500,
          'mensaje' => 'Ha ocurrido un error al obtener los usuarios.'
        ];
      }

      return response()->json($respuesta, $respuesta['codigo']);
  }

  /**
   * Obtener usuarios BGH.
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function getUsersBgh()
  {
      $company_id = $this->bgh_company_id;
      Log::info('getUsersBgh     ==============');
      Log::info('company_id '.$company_id);

      try {
          $users_onsite = $this->userService->getUsersOnsite($company_id);
          Log::info($users_onsite);

          if (count($users_onsite) > 0) 
          {
            $respuesta = [
              'estado' => 'success',
              'codigo' => 200,
              'mensaje' => 'Usuarios Onsite: obtenidos!',
              'users_onsite' => $users_onsite
            ];
          }else{
            $respuesta = [
              'estado' => 'error',
              'codigo' => 404,
              'mensaje' => 'No se han encontrado usuarios con el id de empresa '.$company_id
            ];
          }
      } catch (\Exception $e) {
        $respuesta = [
          'estado' => 'error',
          'codigo' => 500,
          'mensaje' => 'Ha ocurrido un error al obtener los usuarios.'
        ];
      }

      return response()->json($respuesta, $respuesta['codigo']);
  }

  /**
   * Devuelve el recurso del usuario solicitado
   *
   * @param ReparacionOnsite $reparacion_onsite
   * @return ReparacionOnsiteResource
   */
  public function show(User $user)
  {
    return UserResource::make($user);
  }

 
}