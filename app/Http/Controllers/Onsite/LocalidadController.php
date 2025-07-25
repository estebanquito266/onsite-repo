<?php

namespace App\Http\Controllers\Onsite;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;


use App\Services\Onsite\LocalidadService;

class LocalidadController extends Controller
{
  protected $localidadService;

  public function __construct(
    LocalidadService $localidadService
    )
  {
    $this->middleware('auth');
    $this->localidadService = $localidadService;
    //$this->middleware('permiso', ['only' => ['index', 'create', 'store', 'edit', 'update', 'destroy']] );
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
   
  }

  

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
   
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
   
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
    
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
  
  }

  public function generarCsv($userCompanyId, $texto, $idProvincia, $idNivel, $idTecnico, $idUser)
  {
   
  }


  public function getLocalidades($idProvincia)
  {
    $localidades = $this->localidadService->getLocalidades($idProvincia);

    return response()->json($localidades);

  }


}
