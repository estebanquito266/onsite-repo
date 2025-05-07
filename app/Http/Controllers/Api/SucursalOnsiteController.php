<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Onsite\SucursalOnsiteRepository;
use App\Http\Resources\Onsite\SucursalOnsiteCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SucursalOnsiteController extends Controller
{
  public function __construct()
  {
    $this->sucursales_onsite_repository = new SucursalOnsiteRepository;
  }

  /**
   * Devuelve una coleccion de sucursales filtrada por company_id
   *
   * @param Request $request
   * @return void
   */
  public function index(Request $request)
  {
    $sucursales_onsite_query = $this->sucursales_onsite_repository->filtrar($request['filter']);

    $sucursales_onsite_query->applySorts($request['sort']);

    Log::info('API - SucursalOnsiteController - index: ');
    //Log::info($sucursales_onsite_query->toArray());

    return SucursalOnsiteCollection::make($sucursales_onsite_query->get());
  }
}
