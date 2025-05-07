<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Onsite\TipoImagenOnsite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\Onsite\UnidadInteriorOnsiteService;
use App\Services\Onsite\ImagenUnidadOnsiteService;
use App\Http\Resources\Onsite\UnidadesInterioresOnsiteResource;
use App\Models\Onsite\UnidadInteriorOnsite;

class UnidadInteriorOnsiteController extends Controller
{

  protected $unidadInteriorOnsiteService;
  protected $imagenUnidadOnsiteService;

  public function __construct(UnidadInteriorOnsiteService $unidadInteriorOnsiteService, ImagenUnidadOnsiteService $imagenUnidadOnsiteService)
  {
    $this->unidadInteriorOnsiteService = $unidadInteriorOnsiteService;
    $this->imagenUnidadOnsiteService = $imagenUnidadOnsiteService;
  }

  /**
   * Crea una unidad inteior
   *
   * @param Request $request
   * @return UnidadesInterioresOnsiteResource
   */
  public function create(Request $request)
  {
    Log::info('insertUnidadInteriorOnsite     ==============');
    Log::info($request);

    $request['company_id'] = env('BGH_COMPANY_ID', 2);
    
    $unidadInteriorOnsite = $this->unidadInteriorOnsiteService->store($request);
    Log::info($unidadInteriorOnsite);

    if ($unidadInteriorOnsite) 
    {
      return UnidadesInterioresOnsiteResource::make($unidadInteriorOnsite);
    }else{
      $respuesta = [
        'estado' => 'error',
        'codigo' => 500,
        'mensaje' => 'No se pudo crear - unidad interior onsite',
        'data' => $unidadInteriorOnsite
      ];
    }

    return response()->json($respuesta,$respuesta['codigo']);
  }

  /**
   * Actualiza la unidad interior
   *
   * @param UnidadInteriorOnsite $empresa_onsite
   * @return UnidadesInterioresOnsiteResource
   */
  public function update(Request $request, UnidadInteriorOnsite $unidad_interior_onsite)
  {
    
    $unidad_interior_onsite->fill($request->all());

    $unidad_interior_onsite->save();

    return UnidadesInterioresOnsiteResource::make($unidad_interior_onsite);
  }

  // ESTO CREO QUE NO LO USA NADIE - INICIO
  public function updateUnidadInteriorOnsite(Request $request, $unidad_interior_onsite_id)
  {
    Log::info('updateUnidadInteriorOnsite     ==============');
    Log::info('unidad_interior_onsite_id '.$unidad_interior_onsite_id);
    
    $unidadInteriorOnsite = $this->unidadInteriorOnsiteService->update($request, $unidad_interior_onsite_id);
    Log::info($unidadInteriorOnsite);

    if ($unidadInteriorOnsite) 
    {
      $respuesta = [
        'estado' => 'success',
        'codigo' => 200,
        'mensaje' => 'Unidad Interior Onsite actualizada!',
        'unidad_interior_onsite' => $unidadInteriorOnsite,
        'unidad_interior_onsite_id' => $unidadInteriorOnsite->id
      ];
    }else{
      $respuesta = [
        'estado' => 'error',
        'codigo' => 500,
        'mensaje' => 'No se pudo crear - unidad interior onsite'
      ];
    }

    return response()->json($respuesta,$respuesta['codigo']);
  }

  public function getUnidadInteriorOnsite(Request $request, $unidad_interior_onsite_id)
  {
    Log::info('getUnidadInteriorOnsite     ==============');
    Log::info('unidad_interior_onsite_id '.$unidad_interior_onsite_id);
    
    $unidadInteriorOnsite = $this->unidadInteriorOnsiteService->unidadInteriorById($unidad_interior_onsite_id);
    Log::info($unidadInteriorOnsite);

    if ($unidadInteriorOnsite) 
    {
      $respuesta = [
        'estado' => 'success',
        'codigo' => 200,
        'mensaje' => 'Unidad Interior Onsite: obtenido!',
        'unidad_interior_onsite' => $unidadInteriorOnsite,
        'unidad_interior_onsite_id' => $unidadInteriorOnsite->id
      ];
    }else{
      $respuesta = [
        'estado' => 'error',
        'codigo' => 500,
        'mensaje' => 'No se pudo recuperar - unidad interior onsite'
      ];
    }

    return response()->json($respuesta,$respuesta['codigo']);
  }

  public function getUnidadesInteriorOnsiteByEmpresa(Request $request, $empresa_onsite_id)
  {
    Log::info('getUnidadesInteriorOnsiteByEmpresa     ==============');
    Log::info('empresa_onsite_id '.$empresa_onsite_id);
    
    $unidadesInteriorOnsite = $this->unidadInteriorOnsiteService->unidadesInteriorPorEmpresa($empresa_onsite_id);
    Log::info($unidadesInteriorOnsite);

    if ($unidadesInteriorOnsite) 
    {
      $respuesta = [
        'estado' => 'success',
        'codigo' => 200,
        'mensaje' => 'Unidades Interior Onsite por Empresa: obtenido!',
        'unidades_interior_onsite' => $unidadesInteriorOnsite,
        'empresa_onsite_id' => $empresa_onsite_id
      ];
    }else{
      $respuesta = [
        'estado' => 'error',
        'codigo' => 500,
        'mensaje' => 'No se pudo recuperar - unidades interior onsite por empresa'
      ];
    }

    return response()->json($respuesta,$respuesta['codigo']);
  } 

  public function getUnidadesInteriorOnsiteBySucursal(Request $request, $sucursal_onsite_id)
  {
    Log::info('getUnidadesInteriorOnsiteBySucursal     ==============');
    Log::info('sucursal_onsite_id '.$sucursal_onsite_id);
    
    $unidadesInteriorOnsite = $this->unidadInteriorOnsiteService->unidadesInteriorPorSucursal($sucursal_onsite_id);
    Log::info($unidadesInteriorOnsite);

    if ($unidadesInteriorOnsite) 
    {
      $respuesta = [
        'estado' => 'success',
        'codigo' => 200,
        'mensaje' => 'Unidades Interior Onsite por Sucursal: obtenido!',
        'unidades_interior_onsite' => $unidadesInteriorOnsite,
        'sucursal_onsite_id' => $sucursal_onsite_id
      ];
    }else{
      $respuesta = [
        'estado' => 'error',
        'codigo' => 500,
        'mensaje' => 'No se pudo recuperar - unidades interior onsite por sucursal'
      ];
    }

    return response()->json($respuesta,$respuesta['codigo']);
  }  

  public function getUnidadesInteriorOnsiteBySistema(Request $request, $sistema_onsite_id)
  {
    Log::info('getUnidadesInteriorOnsiteBySistema     ==============');
    Log::info('sistema_onsite_id '.$sistema_onsite_id);
    
    $unidadesInteriorOnsite = $this->unidadInteriorOnsiteService->unidadesInteriorPorSistema($sistema_onsite_id);
    Log::info($unidadesInteriorOnsite);

    if ($unidadesInteriorOnsite) 
    {
      $respuesta = [
        'estado' => 'success',
        'codigo' => 200,
        'mensaje' => 'Unidades Interior Onsite por Sistema: obtenido!',
        'unidades_interior_onsite' => $unidadesInteriorOnsite,
        'sistema_onsite_id' => $sistema_onsite_id
      ];
    }else{
      $respuesta = [
        'estado' => 'error',
        'codigo' => 500,
        'mensaje' => 'No se pudo recuperar - unidades interior onsite por sistema'
      ];
    }

    return response()->json($respuesta,$respuesta['codigo']);
  }    

  public function getImagenesUnidadInteriorOnsite(Request $request, $unidad_interior_onsite_id)
  {
    Log::info('getImagenesUnidadInteriorOnsite     ==============');
    Log::info('unidad_interior_onsite_id '.$unidad_interior_onsite_id);
    
    $imagenesUnidadInteriorOnsite = $this->unidadInteriorOnsiteService->imagenesUnidadInteriorById($unidad_interior_onsite_id);
    Log::info($imagenesUnidadInteriorOnsite);

    if ($imagenesUnidadInteriorOnsite) 
    {
      $respuesta = [
        'estado' => 'success',
        'codigo' => 200,
        'mensaje' => 'Imágenes Unidad Interior Onsite: obtenido!',
        'imagenes_unidad_interior_onsite' => $imagenesUnidadInteriorOnsite,
        'unidad_interior_onsite_id' => $unidad_interior_onsite_id
      ];
    }else{
      $respuesta = [
        'estado' => 'error',
        'codigo' => 500,
        'mensaje' => 'No se pudo recuperar - imágenes unidad interior onsite'
      ];
    }

    return response()->json($respuesta,$respuesta['codigo']);
  }   
  // ESTO CREO QUE NO LO USA NADIE - FIN

  public function insertImagenesUnidadInteriorOnsite(Request $request, $unidad_interior_onsite_id)
  {
    Log::info('insertImagenesUnidadInteriorOnsite     ==============');
    Log::info($request);

    $request['company_id'] = env('BGH_COMPANY_ID', 2);
    $tipo_imagen_onsite_id = TipoImagenOnsite::NINGUNO;
    
    $imagenesUnidadInteriorOnsite = $this->imagenUnidadOnsiteService->storeImagenesUnidadInterior($request, $tipo_imagen_onsite_id, $unidad_interior_onsite_id);
    Log::info($imagenesUnidadInteriorOnsite);

    if ($imagenesUnidadInteriorOnsite) 
    {
      $respuesta = [
        'estado' => 'success',
        'codigo' => 200,
        'mensaje' => 'Imágenes Unidad Interior Onsite creado!',
        'imagenes_unidad_interior_onsite' => $imagenesUnidadInteriorOnsite,
        'unidad_interior_onsite_id' => $unidad_interior_onsite_id
      ];
    }else{
      $respuesta = [
        'estado' => 'error',
        'codigo' => 500,
        'mensaje' => 'No se pudo crear - imágenes unidad interior onsite',
        'data' => $request->file('files_unidad_interior')
      ];
    }

    return response()->json($respuesta,$respuesta['codigo']);
  }   

}
