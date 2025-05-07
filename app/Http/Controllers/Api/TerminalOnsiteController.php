<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Onsite\TerminalOnsiteRepository;
use App\Http\Resources\Onsite\TerminalOnsiteCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TerminalOnsiteController extends Controller
{
  public function __construct()
  {
    $this->terminales_onsite_repository = new TerminalOnsiteRepository;
  }

  /**
   * Devuelve una coleccion de sucursales filtrada por company_id
   *
   * @param Request $request
   * @return void
   */
  public function index(Request $request)
  {
    $terminales_onsite_query = $this->terminales_onsite_repository->filtrar($request['filter']);

    $terminales_onsite_query->applySorts($request['sort']);

    Log::info('API - TerminalOnsiteController - index: ');
    //Log::info($terminales_onsite_query->toArray());

    return TerminalOnsiteCollection::make($terminales_onsite_query->get());
  }
}
