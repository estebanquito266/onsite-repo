<?php

namespace App\Http\Controllers\Api;

use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Onsite\EmpresaOnsite;
use App\Repositories\Onsite\EmpresaOnsiteRepository;
use App\Http\Resources\Onsite\EmpresaOnsiteResource;
use App\Http\Resources\Onsite\EmpresaOnsiteCollection;

class EmpresaOnsiteController extends Controller
{
  private $empresa_onsite_repository;

  public function __construct()
  {
    $this->empresa_onsite_repository = new EmpresaOnsiteRepository;

  }

  /**
   * Trae todos los estados
   *
   * @param Request $request
   * @return void
   */
  public function index (Request $request)
  {
    $empresas_onsite_query = $this->empresa_onsite_repository->filtrar($request['filter']);
    
    $empresas_onsite_query->applySorts($request['sort']);
    
    return EmpresaOnsiteCollection::make($empresas_onsite_query->get());
  }

  /**
   * Devuelve el recurso de la reparacion onsite solicitada
   *
   * @param EmpresaOnsite $empresa_onsite
   * @return EmpresaOnsiteResource
   */
  public function show(EmpresaOnsite $empresa_onsite)
  {
    return EmpresaOnsiteResource::make($empresa_onsite);
  }
}