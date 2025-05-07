<?php

namespace App\Http\Controllers\Api;

use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use App\Models\Onsite\EstadoOnsite;
use App\Repositories\Onsite\EstadoOnsiteRepository;
use App\Http\Resources\Onsite\EstadoOnsiteResource;
use App\Http\Resources\Onsite\EstadoOnsiteCollection;

class EstadoOnsiteController extends Controller
{
  private $estado_onsite_repository;

  private $estados_onsite_activos_ids_array;

  public function __construct()
  {
    $this->estado_onsite_repository = new EstadoOnsiteRepository;

    $this->estados_onsite_activos_ids_array = $this->estado_onsite_repository->activo(1);
  }

  /**
   * Trae todos los estados
   *
   * @param Request $request
   * @return void
   */
  public function index (Request $request)
  {
    $estados_onsite_query = $this->estado_onsite_repository->filtrar($request['filter']);

    $estados_onsite_query->applySorts($request['sort']);

    return EstadoOnsiteCollection::make($estados_onsite_query->get());
  }

  public function activos(Request $request)
  {
    return EstadoOnsiteCollection::make($this->estado_onsite_repository->activos()->get());
  }
}