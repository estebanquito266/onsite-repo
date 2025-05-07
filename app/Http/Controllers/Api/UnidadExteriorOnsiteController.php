<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Onsite\TipoImagenOnsite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\Onsite\UnidadExteriorOnsiteService;
use App\Services\Onsite\ImagenUnidadOnsiteService;
use App\Http\Resources\Onsite\UnidadesExterioresOnsiteResource;
use App\Models\Onsite\UnidadExteriorOnsite;


class UnidadExteriorOnsiteController extends Controller
{

  protected $unidadExteriorOnsiteService;
  protected $imagenUnidadOnsiteService;

  public function __construct(
    UnidadExteriorOnsiteService $unidadExteriorOnsiteService, ImagenUnidadOnsiteService $imagenUnidadOnsiteService)
  {
    $this->unidadExteriorOnsiteService = $unidadExteriorOnsiteService;
    $this->imagenUnidadOnsiteService = $imagenUnidadOnsiteService;
  }

  public function create(Request $request)
  { 
    $request['clave'] = 1;

    $request['company_id'] = env('BGH_COMPANY_ID', 2);
    
    $unidadExteriorOnsite = $this->unidadExteriorOnsiteService->store($request);    

    if ($unidadExteriorOnsite) 
    {
      return UnidadesExterioresOnsiteResource::make($unidadExteriorOnsite);
    }else{
      $respuesta = [
        'estado' => 'error',
        'codigo' => 500,
        'mensaje' => 'No se pudo crear - unidad exterior onsite'
      ];
    }

    return response()->json($respuesta,$respuesta['codigo']);
  }  

  /**
   * Actualiza la unidad exterior
   *
   * @param UnidadExteriorOnsite $empresa_onsite
   * @return UnidadesExterioresOnsiteResource
   */
  public function update(Request $request, UnidadExteriorOnsite $unidad_exterior_onsite)
  {
    $unidad_exterior_onsite->fill($request->all());

    $unidad_exterior_onsite->save();

    return UnidadesExterioresOnsiteResource::make($unidad_exterior_onsite);
  }

  // ESTO CREO QUE NO LO USA NADIE - INICIO
  public function updateUnidadExteriorOnsite(Request $request, $unidad_exterior_onsite_id)
  {
    Log::info('updateUnidadExteriorOnsite     ==============');
    Log::info('unidad_exterior_onsite_id '.$unidad_exterior_onsite_id);
    
    $unidadExteriorOnsite = $this->unidadExteriorOnsiteService->update($request, $unidad_exterior_onsite_id);
    Log::info($unidadExteriorOnsite);

    if ($unidadExteriorOnsite) 
    {
      $respuesta = [
        'estado' => 'success',
        'codigo' => 200,
        'mensaje' => 'Unidad Exterior Onsite actualizada!',
        'unidad_exterior_onsite' => $unidadExteriorOnsite,
        'unidad_exterior_onsite_id' => $unidadExteriorOnsite->id
      ];
    }else{
      $respuesta = [
        'estado' => 'error',
        'codigo' => 500,
        'mensaje' => 'No se pudo crear - unidad exterior onsite'
      ];
    }

    return response()->json($respuesta,$respuesta['codigo']);
  }

  public function getUnidadExteriorOnsite(Request $request, $unidad_exterior_onsite_id)
  {
    Log::info('getUnidadExteriorOnsite     ==============');
    Log::info('unidad_exterior_onsite_id '.$unidad_exterior_onsite_id);
    
    $unidadExteriorOnsite = $this->unidadExteriorOnsiteService->unidadExteriorById($unidad_exterior_onsite_id);
    Log::info($unidadExteriorOnsite);

    if ($unidadExteriorOnsite) 
    {
      $respuesta = [
        'estado' => 'success',
        'codigo' => 200,
        'mensaje' => 'Unidad Exterior Onsite: obtenido!',
        'unidad_exterior_onsite' => $unidadExteriorOnsite,
        'unidad_exterior_onsite_id' => $unidadExteriorOnsite->id
      ];
    }else{
      $respuesta = [
        'estado' => 'error',
        'codigo' => 500,
        'mensaje' => 'No se pudo recuperar - unidad exterior onsite'
      ];
    }

    return response()->json($respuesta,$respuesta['codigo']);
  }

  public function getUnidadesExteriorOnsiteByEmpresa(Request $request, $empresa_onsite_id)
  {
    Log::info('getUnidadesExteriorOnsiteByEmpresa     ==============');
    Log::info('empresa_onsite_id '.$empresa_onsite_id);
    
    $unidadesExteriorOnsite = $this->unidadExteriorOnsiteService->unidadesExteriorPorEmpresa($empresa_onsite_id);
    Log::info($unidadesExteriorOnsite);

    if ($unidadesExteriorOnsite) 
    {
      $respuesta = [
        'estado' => 'success',
        'codigo' => 200,
        'mensaje' => 'Unidades Exterior Onsite por Empresa: obtenido!',
        'unidades_exterior_onsite' => $unidadesExteriorOnsite,
        'empresa_onsite_id' => $empresa_onsite_id
      ];
    }else{
      $respuesta = [
        'estado' => 'error',
        'codigo' => 500,
        'mensaje' => 'No se pudo recuperar - unidades exterior onsite por empresa'
      ];
    }

    return response()->json($respuesta,$respuesta['codigo']);
  } 

  public function getUnidadesExteriorOnsiteBySucursal(Request $request, $sucursal_onsite_id)
  {
    Log::info('getUnidadesExteriorOnsiteBySucursal     ==============');
    Log::info('sucursal_onsite_id '.$sucursal_onsite_id);
    
    $unidadesExteriorOnsite = $this->unidadExteriorOnsiteService->unidadesExteriorPorSucursal($sucursal_onsite_id);
    Log::info($unidadesExteriorOnsite);

    if ($unidadesExteriorOnsite) 
    {
      $respuesta = [
        'estado' => 'success',
        'codigo' => 200,
        'mensaje' => 'Unidades Exterior Onsite por Sucursal: obtenido!',
        'unidades_exterior_onsite' => $unidadesExteriorOnsite,
        'sucursal_onsite_id' => $sucursal_onsite_id
      ];
    }else{
      $respuesta = [
        'estado' => 'error',
        'codigo' => 500,
        'mensaje' => 'No se pudo recuperar - unidades exterior onsite por sucursal'
      ];
    }

    return response()->json($respuesta,$respuesta['codigo']);
  }  

  public function getUnidadesExteriorOnsiteBySistema(Request $request, $sistema_onsite_id)
  {
    Log::info('getUnidadesExteriorOnsiteBySistema     ==============');
    Log::info('sistema_onsite_id '.$sistema_onsite_id);
    
    $unidadesExteriorOnsite = $this->unidadExteriorOnsiteService->unidadesExteriorPorSistema($sistema_onsite_id);
    Log::info($unidadesExteriorOnsite);

    if ($unidadesExteriorOnsite) 
    {
      $respuesta = [
        'estado' => 'success',
        'codigo' => 200,
        'mensaje' => 'Unidades Exterior Onsite por Sistema: obtenido!',
        'unidades_exterior_onsite' => $unidadesExteriorOnsite,
        'sistema_onsite_id' => $sistema_onsite_id
      ];
    }else{
      $respuesta = [
        'estado' => 'error',
        'codigo' => 500,
        'mensaje' => 'No se pudo recuperar - unidades exterior onsite por sistema'
      ];
    }

    return response()->json($respuesta,$respuesta['codigo']);
  }    


  public function getImagenesUnidadExteriorOnsite(Request $request, $unidad_exterior_onsite_id)
  {
    Log::info('getImagenesUnidadExteriorOnsite     ==============');
    Log::info('unidad_exterior_onsite_id '.$unidad_exterior_onsite_id);
    
    $imagenesUnidadExteriorOnsite = $this->unidadExteriorOnsiteService->imagenesUnidadExteriorById($unidad_exterior_onsite_id);
    Log::info($imagenesUnidadExteriorOnsite);

    if ($imagenesUnidadExteriorOnsite) 
    {
      $respuesta = [
        'estado' => 'success',
        'codigo' => 200,
        'mensaje' => 'Imágenes Unidad Exterior Onsite: obtenido!',
        'imagenes_unidad_exterior_onsite' => $imagenesUnidadExteriorOnsite,
        'unidad_exterior_onsite_id' => $unidad_exterior_onsite_id
      ];
    }else{
      $respuesta = [
        'estado' => 'error',
        'codigo' => 500,
        'mensaje' => 'No se pudo recuperar - imágenes unidad exterior onsite'
      ];
    }

    return response()->json($respuesta,$respuesta['codigo']);
  }  
  // ESTO CREO QUE  NO LO USA NADIE - FIN


  public function insertImagenesUnidadExteriorOnsite(Request $request, $unidad_exterior_onsite_id)
  {
    Log::info('insertImagenesUnidadExteriorOnsite     ==============');
    Log::info($request);

    $request['company_id'] = env('BGH_COMPANY_ID', 2);
    $tipo_imagen_onsite_id = TipoImagenOnsite::NINGUNO;
    
    $imagenesUnidadExteriorOnsite = $this->imagenUnidadOnsiteService->storeImagenesUnidadExterior($request, $tipo_imagen_onsite_id, $unidad_exterior_onsite_id);
    Log::info($imagenesUnidadExteriorOnsite);

    if ($imagenesUnidadExteriorOnsite) 
    {
      $respuesta = [
        'estado' => 'success',
        'codigo' => 200,
        'mensaje' => 'Imágenes Unidad Exterior Onsite creado!',
        'unidad_exterior_onsite' => $imagenesUnidadExteriorOnsite,
        'unidad_exterior_onsite_id' => $unidad_exterior_onsite_id
      ];
    }else{
      $respuesta = [
        'estado' => 'error',
        'codigo' => 500,
        'mensaje' => 'No se pudo crear - imágenes unidad exterior onsite',
        'data' => $imagenesUnidadExteriorOnsite
      ];
    }

    return response()->json($respuesta,$respuesta['codigo']);
  }  

}
