<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Onsite\TipoServicioOnsite;
use App\Repositories\Onsite\TipoServicioOnsiteRepository;
use App\Http\Resources\Onsite\TipoServicioOnsiteResource;
use App\Http\Resources\Onsite\TipoServicioOnsiteCollection;

class TipoServicioOnsiteController extends Controller
{
  private $tipo_servicio_onsite_repository;

  public function __construct()
  {
    $this->tipo_servicio_onsite_repository = new TipoServicioOnsiteRepository;

  }

  /**
   * Trae todos los estados
   *
   * @param Request $request
   * @return void
   */
  public function index (Request $request)
  {
    $tipos_servicios_onsite_query = $this->tipo_servicio_onsite_repository->filtrar($request['filter']);
    
    $tipos_servicios_onsite_query->applySorts($request['sort']);
    
    return TipoServicioOnsiteCollection::make($tipos_servicios_onsite_query->get());
  }

  /**
   * Devuelve el recurso de la reparacion onsite solicitada
   *
   * @param TipoServicioOnsite $tipo_servicio_onsite
   * @return TipoServicioOnsiteResource
   */
  public function show(TipoServicioOnsite $tipo_servicio_onsite)
  {
    return TipoServicioOnsiteResource::make($tipo_servicio_onsite);
  }
}