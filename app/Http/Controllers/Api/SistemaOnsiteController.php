<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\Onsite\SistemaOnsiteService;
use App\Http\Resources\Onsite\SistemaOnsiteResource;
use App\Models\Onsite\SistemaOnsite;

class SistemaOnsiteController extends Controller
{

  protected $sistemaOnsiteService;

  public function __construct(SistemaOnsiteService $sistemaOnsiteService)
  {
    $this->sistemaOnsiteService = $sistemaOnsiteService;
  }

  public function create(Request $request)
  {
    Log::info('insertSistemaOnsite     ==============');
    Log::info($request);

    $request['company_id'] = env('BGH_COMPANY_ID', 2);
    
    $sistemaOnsite = $this->sistemaOnsiteService->store($request);
    Log::info($sistemaOnsite);

    if ($sistemaOnsite) 
    {
      return SistemaOnsiteResource::make($sistemaOnsite);

    }else{
      $respuesta = [
        'estado' => 'error',
        'codigo' => 500,
        'mensaje' => 'No se pudo crear el sistema'
      ];
    }

    return response()->json($respuesta,$respuesta['codigo']);
  }

  /**
   * Devuelve el recurso de la reparacion onsite solicitada
   *
   * @param SistemaOnsite $empresa_onsite
   * @return SistemaOnsiteResource
   */
  public function show(SistemaOnsite $sistema_onsite)
  {
    return SistemaOnsiteResource::make($sistema_onsite);
  }

  /**
   * Actualiza el sistema
   *
   * @param SistemaOnsite $empresa_onsite
   * @return SistemaOnsiteResource
   */
  public function update(Request $request, SistemaOnsite $sistema_onsite)
  {
    $sistema_onsite->fill($request->all());

    $sistema_onsite->save();

    return SistemaOnsiteResource::make($sistema_onsite);
  }

  // CREO QUE TODO ESTO NO LO USA NADIE
  public function updateSistemaOnsite(Request $request, $sistema_onsite_id)
  {
    Log::info('updateSistemaOnsite     ==============');
    Log::info('sistema_onsite_id '.$sistema_onsite_id);
    
    $sistemaOnsite = $this->sistemaOnsiteService->update($request, $sistema_onsite_id);
    Log::info($sistemaOnsite);

    if ($sistemaOnsite) 
    {
      $respuesta = [
        'estado' => 'success',
        'codigo' => 200,
        'mensaje' => 'Sistema Onsite actualizado!',
        'sistema_onsite' => $sistemaOnsite,
        'sistema_onsite_id' => $sistemaOnsite->id
      ];
    }else{
      $respuesta = [
        'estado' => 'error',
        'codigo' => 500,
        'mensaje' => 'No se pudo crear el sistema'
      ];
    }

    return response()->json($respuesta,$respuesta['codigo']);
  }

  public function getSistemaOnsite(Request $request, $sistema_onsite_id)
  {
    Log::info('getSistemaOnsite     ==============');
    Log::info('sistema_onsite_id '.$sistema_onsite_id);
    
    $sistemaOnsite = $this->sistemasOnsiteService->sistemaById($sistema_onsite_id);
    Log::info($sistemaOnsite);

    if ($sistemaOnsite) 
    {
      $respuesta = [
        'estado' => 'success',
        'codigo' => 200,
        'mensaje' => 'Sistema Onsite: obtenido!',
        'sistema_onsite' => $sistemaOnsite,
        'sistema_onsite_id' => $sistemaOnsite->id
      ];
    }else{
      $respuesta = [
        'estado' => 'error',
        'codigo' => 500,
        'mensaje' => 'No se pudo recuperar el sistema onsite'
      ];
    }

    return response()->json($respuesta,$respuesta['codigo']);
  }

  public function getSistemasOnsiteByEmpresa(Request $request, $empresa_onsite_id)
  {
    Log::info('getSistemasOnsiteByEmpresa     ==============');
    Log::info('empresa_onsite_id '.$empresa_onsite_id);
    
    $sistemasOnsite = $this->sistemasOnsiteService->sistemasPorEmpresa($empresa_onsite_id);
    Log::info($sistemasOnsite);

    if ($sistemasOnsite) 
    {
      $respuesta = [
        'estado' => 'success',
        'codigo' => 200,
        'mensaje' => 'Sistemas Onsite por Empresa: obtenido!',
        'sistemas_onsite' => $sistemasOnsite,
        'empresa_onsite_id' => $empresa_onsite_id
      ];
    }else{
      $respuesta = [
        'estado' => 'error',
        'codigo' => 500,
        'mensaje' => 'No se pudo recuperar sistema onsite por empresa'
      ];
    }

    return response()->json($respuesta,$respuesta['codigo']);
  } 

  public function getSistemasOnsiteBySucursal(Request $request, $sucursal_onsite_id)
  {
    Log::info('getSistemasOnsiteBySucursal     ==============');
    Log::info('sucursal_onsite_id '.$sucursal_onsite_id);
    
    $sistemasOnsite = $this->sistemasOnsiteService->sistemasPorSucursal($sucursal_onsite_id);
    Log::info($sistemasOnsite);

    if ($sistemasOnsite) 
    {
      $respuesta = [
        'estado' => 'success',
        'codigo' => 200,
        'mensaje' => 'Sistemas Onsite por Sucursal: obtenido!',
        'sistemas_onsite' => $sistemasOnsite,
        'sucursal_onsite_id' => $sucursal_onsite_id
      ];
    }else{
      $respuesta = [
        'estado' => 'error',
        'codigo' => 500,
        'mensaje' => 'No se pudo recuperar sistema onsite por sucursal'
      ];
    }

    return response()->json($respuesta,$respuesta['codigo']);
  }  
  // Creo que todo esto no lo usa nadie FIN

}
