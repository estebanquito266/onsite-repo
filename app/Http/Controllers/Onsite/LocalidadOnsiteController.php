<?php

namespace App\Http\Controllers\Onsite;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use App\Services\Onsite\LocalidadOnsiteService;

class LocalidadOnsiteController extends Controller
{
  protected $localidadOnsiteService;

  public function __construct(LocalidadOnsiteService $localidadOnsiteService)
  {
    $this->middleware('auth');
    $this->localidadOnsiteService = $localidadOnsiteService;
    //$this->middleware('permiso', ['only' => ['index', 'create', 'store', 'edit', 'update', 'destroy']] );
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $datos = $this->localidadOnsiteService->getDataIndex();

    return view('_onsite.localidadonsite.index', $datos);
  }

  public function filtrarLocalidadOnsite(Request $request)
  {
    $datos = $this->localidadOnsiteService->filtrarLocalidadOnsite($request);
    return view('_onsite.localidadonsite.index', $datos);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    $datos = $this->localidadOnsiteService->getData();

    return view('_onsite.localidadonsite.create', $datos);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $request->validate([
      'id_provincia' => 'required',
      'localidad' => 'required',

      'codigo' => 'numeric',

      'id_nivel' => 'required',

      'id_usuario_tecnico' => 'required',

      'atiende_desde' => 'required',
    ]);

    $localidadOnsite = $this->localidadOnsiteService->store($request);

    $mje = 'Localidad Onsite: ' . $localidadOnsite->id . ',  registro creado correctamente!';

    if ($request['botonGuardar']) {
      return redirect('/localidadOnsite/' . $localidadOnsite->id . '/edit')->with('message', $mje);
    } else {
      return redirect('/localidadOnsite')->with('message', $mje);
    }
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    $datos = $this->localidadOnsiteService->edit($id);

    return view('_onsite.localidadonsite.edit', $datos);
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
    $request->validate([
      'id_provincia' => 'required',
      'localidad' => 'required',

      'codigo' => 'numeric',

      'id_nivel' => 'required',

      'id_usuario_tecnico' => 'required',

      'atiende_desde' => 'required',
    ]);

    $localidadOnsite = $this->localidadOnsiteService->update($request, $id);

    $mje = 'Localidad Onsite: ' . $id . ',  registro modificado correctamente!';

    if ($request['botonGuardar']) {
      return redirect('/localidadOnsite/' . $localidadOnsite->id . '/edit')->with('message', $mje);
    } else {
      return redirect('/localidadOnsite')->with('message', $mje);
    }
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    $localidadOnsite = $this->localidadOnsiteService->destroy($id);

    $mje = 'Localidad Onsite: ' . $localidadOnsite->id . ',  registro eliminado correctamente!';

    return redirect('/localidadOnsite')->with('message', $mje);
  }

  public function generarCsv($userCompanyId, $texto, $idProvincia, $idNivel, $idTecnico, $idUser)
  {
    $this->localidadOnsiteService->generarXlsx($userCompanyId, $texto, $idProvincia, $idNivel, $idTecnico, $idUser);
  }


  public function getLocalidades($idProvincia)
  {
    $localidades = $this->localidadOnsiteService->getLocalidades($idProvincia);

    return response()->json($localidades);
  }
}
