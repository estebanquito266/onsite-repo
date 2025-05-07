<?php

namespace App\Http\Controllers\Onsite;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Onsite\ObraOnsite;
use App\Models\Onsite\TipoImagenOnsite;
use App\Services\Onsite\ImagenObraOnsiteService;
use Illuminate\Support\Facades\Auth;

use App\Services\Onsite\ObrasOnsiteService;
use App\Services\Onsite\SucursalOnsiteService;
use Log;
use Mapper;
use Session;

class ObraOnsiteController extends Controller
{
  protected $obraOnsiteService;
  protected $sucursalOnsiteService;
  protected $userCompanyId;
  protected $imagenObraOnsiteService;



  public function __construct(
    ObrasOnsiteService $obraOnsiteService,
    ImagenObraOnsiteService $imagenObraOnsiteService,
    SucursalOnsiteService $sucursalOnsiteService
  ) {
    $this->userCompanyId =  session()->get('userCompanyIdDefault');

    $this->middleware('auth');
    $this->obraOnsiteService = $obraOnsiteService;
    $this->sucursalOnsiteService = $sucursalOnsiteService;
    $this->imagenObraOnsiteService = $imagenObraOnsiteService;
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $data = $this->obraOnsiteService->getDataIndex();

    if (!$data)
      return redirect('/')->with('message-error', 'Usuario NO posee empresa instaladora asociada');
    else
      return view('_onsite/obraonsite.index', $data);
  }


  public function create()
  {
    return view('_onsite/obraonsite.create');
  }


  public function store(Request $request)
  {
    $this->validate($request, [
      'nombre' => 'required',
      'responsable_email' => 'required',
      'estado' => 'required',
    ]);

    $obraOnsite = $this->obraOnsiteService->store($request);

    $this->storeImagenObraOnsite($request, $obraOnsite);

    $mjeCreate = 'ObraOnsite: ' . $obraOnsite->id . ' - registro creado correctamente!';

    if ($request['botonGuardar'])
      return redirect('/obrasOnsite/' . $obraOnsite->id . '/edit')->with('message', $mjeCreate);
    else
      return redirect('/obrasOnsite')->with('message', $mjeCreate);
  }

  public function storeImagenObraOnsite(Request $request, $obraOnsite)
  {
    $request['obra_onsite_id'] = $obraOnsite->id;
    $request['tipo_imagen_onsite_id'] = TipoImagenOnsite::TRABAJO;

    if ($request->hasFile('esquemas')) {
      foreach ($request->file('esquemas') as $file) {
        $request['esquema'] = $file;
        $request['esquema'] = $this->imagenObraOnsiteService->store($request);
        $imagenesObraOnsite = $this->imagenObraOnsiteService->store($request);
      }
    }

    if (isset($request['esquema']) || isset($request['esquema_archivo'])) {
      if ($request->hasFile('esquema_archivo')) {
        foreach ($request->file('esquema_archivo') as $file) {
          // $request['esquema'] = $this->imagenObraOnsiteService->storeImagenObraOnsite($request->file('esquema_archivo'), $request['nombre']); 
          $request['esquema'] = $file;
          $imagenesObraOnsite = $this->imagenObraOnsiteService->store($request);
        }
      }
    }
  }


  public function show($id)
  {
    $data = $this->obraOnsiteService->getDataEdit($id);

    return view('_onsite/obraonsite.show', $data);
  }


  public function edit($id)
  {
    $datos = $this->obraOnsiteService->getDataEdit($id);
    Log::info('Datos imagenes: ');

    Log::info($datos['imagenes']);

    return view('_onsite/obraonsite.edit', $datos);
  }


  public function update(Request $request, $id)
  {
    $this->validate($request, [
      'nombre' => 'required',
      'responsable_email' => 'required',
      'estado' => 'required',
    ]);

    $obraOnsiteUpdated = $this->obraOnsiteService->update($request, $id);

    $mjeUpdate = 'ObraOnsite: ' . $obraOnsiteUpdated->id . ' - registro modificado correctamente!';

    if ($request['botonGuardar'])
      return redirect('/obrasOnsite/' . $obraOnsiteUpdated->id . '/edit')->with('message', $mjeUpdate);
    else
      return redirect('/obrasOnsite')->with('message', $mjeUpdate);
  }


  public function destroy($id)
  {
    $success = $this->obraOnsiteService->destroy($id);

    return redirect('/obrasOnsite')->with('message', ($success ? 'ObraOnsite: ' . $id . ' - registro eliminado correctamente!' : null));
  }

  public function filtrarObrasOnsite(Request $request)
  {
    $datos = $this->obraOnsiteService->getDataFiltrarObrasOnsite($request);

    return view('_onsite/obraonsite.index', $datos);
  }

  public function generarCsv($texto, $companyId)
  {
    $this->obraOnsiteService->generarXlsx($texto, $companyId);
  }

  public function createSolicitudPuestaMarcha()
  {
/* 
    $data =  $this->obraOnsiteService->dataPuestaMarcha();

    if (Session()->get('perfilAdmin') || Session()->get('perfilAdminOnsite')) {
      $data['obrasOnsite'] = [];
      return view('_onsite/obraonsite.createSolicitudPuestaMarcha', $data);
    } else {
      if (is_null($data['obrasOnsite']))
        return redirect('/')->with('message-error', 'Usuario no tiene empresa instaladora asociada');

      else
        return view('_onsite/obraonsite.createSolicitudPuestaMarcha', $data);
    } */
  }

  public function createObra()
  {


    $data =  $this->obraOnsiteService->dataPuestaMarcha();

    if (Session()->get('perfilAdmin') || Session()->get('perfilAdminOnsite')) {
      $data['obrasOnsite'] = [];
      return view('_onsite/obraonsite.createObra', $data);
    } else {
      if (is_null($data['obrasOnsite']))
        return redirect('/')->with('message-error', 'Usuario no tiene empresa instaladora asociada');

      else
        return view('_onsite/obraonsite.createObra', $data);
    }
  }

  public function storeObra(Request $request)
  {
    $request['company_id'] = $this->userCompanyId;

    $this->validate($request, [
      'empresa_instaladora_id' => 'required',
      'empresa_onsite_id' => 'required',
      'nombre' => 'required',
      'nro_cliente_bgh_ecosmart' => 'required',
      'pais' => 'required',
      'domicilio' => 'required',
      'cantidad_unidades_exteriores' => 'required',
      'cantidad_unidades_interiores' => 'required',
      'empresa_instaladora_nombre' => 'required',
      'empresa_instaladora_responsable' => 'required',
      'estado' => 'required',
      'estado_detalle' => 'required',


    ]);


    $obraOnsite = $this->obraOnsiteService->storeObra($request);


    /* crea sucursal onsite si no existe */
    $empresaOnsiteId = $obraOnsite->empresa_onsite_id;
    $sucursalesOnsite = $this->sucursalOnsiteService->getSucursalesOnsite($empresaOnsiteId);

    if (count($sucursalesOnsite) < 1) {

      $datos_sucursal_onsite = [
        'company_id' =>     $this->userCompanyId,
        'codigo_sucursal' => $this->obraOnsiteService->getClave($request['nombre']),
        'empresa_onsite_id' => $empresaOnsiteId,
        'localidad_onsite_id' => $request['localidad_onsite_id'],
        'razon_social' => $request['nombre'],

      ];

      $this->sucursalOnsiteService->store($datos_sucursal_onsite);
    }


    return response()->json($obraOnsite);
  }

  public function storeCheckListObra(Request $request)
  {
    $request['company_id'] = $this->userCompanyId;

    $this->validate($request, [
      'obra_onsite_id' => 'required',
    ]);

    $chekListObra = $this->obraOnsiteService->storeCheckListObra($request);

    return response()->json($chekListObra);
  }


  public function createObraForm(Request $request)
  {

    $this->validate($request, [
      'nombre' => 'required',
      'responsable_email' => 'required',
      'estado' => 'required',
    ]);
    $obraOnsite = $this->obraOnsiteService->store($request);

    return response()->json($obraOnsite);
  }

  public function getObraOnsite($idObra)
  {
    $obraOnsite =  $this->obraOnsiteService->findObraOnsite($idObra);

    return response()->json($obraOnsite);
  }

  public function getObraOnsiteWithSistema($idObra)
  {
    $obraOnsite =  $this->obraOnsiteService->getObraOnsite($idObra);

    return response()->json($obraOnsite);
  }

  public function obrasOnsiteUnificado()
  {
    $obrasOnsite = $this->obraOnsiteService->getAllObrasOnsiteUnificado();
    $data = ['obrasOnsite' => $obrasOnsite];

    return view('_onsite/obraonsite.index_unificado', $data);
  }

  public function getObrasOnsiteDashboard()
  {
    $obrasOnsite = $this->obraOnsiteService->getObrasOnsiteDashboard();

    return response()->json($obrasOnsite);
  }

  public function getObrasConVisitas()
  {
    $obrasOnsite = $this->obraOnsiteService->getObrasConVisitas();

    return response()->json($obrasOnsite);
  }

  public function getObrasSinObservaciones()
  {
    $obrasOnsite = $this->obraOnsiteService->getObrasSinObservaciones();

    return response()->json($obrasOnsite);
  }







  public function viewDashboardObra()
  {

    $data =  $this->obraOnsiteService->dataPuestaMarcha();

    if (Session()->get('perfilAdmin') || Session()->get('perfilAdminOnsite')) {
      $data['obrasOnsite'] = [];
      return view('_onsite/obraonsite.viewDashboardObra', $data);
    } else {
      if (is_null($data['obrasOnsite']))
        return redirect('/')->with('message-error', 'Usuario no tiene empresa instaladora asociada');

      else
        return view('_onsite/obraonsite.viewDashboardObra', $data);
    }
  }

  public function mapeo_obras()
  {

    $obras = ObraOnsite::where('company_id', $this->userCompanyId)
      ->get();

    $i = 1;
    foreach ($obras as $obra) {

      if ($obra->longitud != null) {

        $info_obra = $obra->nombre . '<br>' . $obra->domicilio . '<br>' .
          ($obra->empresa_instaladora ? $obra->empresa_instaladora->nombre : null);

        if ($i == 1) {
         /*  Mapper::map($obra->latitud, $obra->longitud); */
         Mapper::map(-34.605864, -58.454632);
          
          $i++;
        }

        Mapper::informationWindow($obra->latitud, $obra->longitud, $info_obra, ['open' => false, 'maxWidth' => 300, 'autoClose' => true, 'markers' => ['title' => $obra->nombre]]);
      }
    }





    return view('_onsite.obraonsite.maps');
  }

  public function getObraConSistema($idObra)
{
    $obrasOnsite =
        ObraOnsite::with('sistema_onsite')
        ->where('company_id', Session::get('userCompanyIdDefault'))
        ->where('activo', true)
        ->where('id', $idObra)
        ->get();

      

    return $obrasOnsite;
}

  



}
