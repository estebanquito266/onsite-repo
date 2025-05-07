<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

use App\Models\PerfilUsuario;
use App\Models\Perfil;
use App\Models\RolPerfil;
use App\Repositories\Onsite\ReparacionOnsiteRepository;
use App\Repositories\Onsite\EmpresaOnsiteRepository;
use DateTime;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Log;

class AdminController extends Controller
{
  protected $reparacion_onsite_repository;
  protected $empresa_onsite_repository;

  public function __construct(
    ReparacionOnsiteRepository $reparacion_onsite_repository,
    EmpresaOnsiteRepository $empresa_onsite_repository
  ) {
    $this->middleware('auth');

    $this->reparacion_onsite_repository = $reparacion_onsite_repository;
    $this->empresa_onsite_repository = $empresa_onsite_repository;
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $this->setSession();

    $company_id = null;

    if (Auth::user()->companies && Auth::user()->companies->first()) {
      $company_id = Auth::user()->companies->first()->id;
    }

    if ($company_id == Company::DEFAULT) {
      $dashboardData = $this->dashboardDataDefault();
      return view('admin.index-company-1', $dashboardData);
    } else {
      $dashboardData = $this->dashboardDataBgh();
      return view('admin.index', $dashboardData);
    }
  }

  private function dashboardData()
  {

    $empresasOnsiteUser = Auth::user()->empresas_onsite;
    $empresa_seleccionada = null;
    $datosReparaciones = null;
    $datosReparaciones_reparaciones = null;
    $datosReparaciones_totalPendientes = null;

    if (count($empresasOnsiteUser) > 0) {
      $empresa_seleccionada = $this->empresa_onsite_repository->filtrar(['id' => $empresasOnsiteUser[0]->id])->first();

      $datosReparaciones = $this->cantidadesEstadosReparacionOnsite($empresasOnsiteUser[0]->id);

      $datosReparaciones_reparaciones = $datosReparaciones['reparaciones'];
      $datosReparaciones_totalPendientes = $datosReparaciones['totalPendientes'];
    }

    return [
      'empresasOnsite' => $empresasOnsiteUser,
      'reparacionesCantidadesEstados' => $datosReparaciones_reparaciones,
      'totalPendientes' => $datosReparaciones_totalPendientes,
      'empresa_seleccionada' => $empresa_seleccionada,
    ];
  }

  private function dashboardDataDefault()
  {

    $empresasOnsiteUser = Auth::user()->empresas_onsite;
    $datosReparaciones = null;
    $datosReparaciones_reparaciones = null;
    $datosReparaciones_totalPendientes = null;
    $reparacionesEmpresa = [];

    foreach ($empresasOnsiteUser as $empresaOnsite) {

      $datosReparaciones = $this->cantidadesEstadosReparacionOnsite($empresaOnsite->id);
      $datosReparaciones_reparaciones = $datosReparaciones['reparaciones'];
      $datosReparaciones_totalActivas = $datosReparaciones['totalActivas'];
      $datosReparaciones_totalCerradas = $datosReparaciones['totalCerradas'];

      $reparacionesEmpresa[$empresaOnsite->id] = [
        'reparacionesCantidadesEstados' => $datosReparaciones_reparaciones,
        'totalActivas' => $datosReparaciones_totalActivas,
        'totalCerradas' => $datosReparaciones_totalCerradas,
      ];
    }

    $result = [
      'empresasOnsite' => $empresasOnsiteUser,
      'reparacionesEmpresa' => $reparacionesEmpresa,
    ];

    return $result;
  }

  private function dashboardDataBgh()
  {

    $empresasOnsiteUser = Auth::user()->empresas_onsite;
    return [
      'empresasOnsite' => $empresasOnsiteUser,
    ];
  }

  /**
   * Filtra las reparaciones por empresa_id
   */
  public function filtrarPorEmpresa(Request $request)
  {
    $datosReparaciones = $this->cantidadesEstadosReparacionOnsite($request['empresa_id']);

    $empresa_seleccionada = $this->empresa_onsite_repository->filtrar(['id' => $request['empresa_id']])->first();

    //dd($datosReparaciones['reparaciones']);


    return view('admin.index', [
      'empresasOnsite' => Auth::user()->empresas_onsite,
      'reparacionesCantidadesEstados' => $datosReparaciones['reparaciones'],
      'totalPendientes' => $datosReparaciones['totalPendientes'],
      'empresa_id' => $request['empresa_id'],
      'empresa_seleccionada' => $empresa_seleccionada,
    ]);
  }

  /**
   * Setea las variables de session para el usuario
   *
   * @return void
   */
  private function setSession()
  {


    $idUser = Auth::user()->id;

    Session::put('idUser', $idUser);

    $userCompaniesId = array();
    $userCompanyIdDefault = null;
    $userCompanyLogoDefault = null;

    foreach (Auth::user()->companies as $company) {
      $userCompaniesId[] = $company->id;
      if (!$userCompanyIdDefault) {
        $userCompanyIdDefault = $company->id;
        $userCompanyLogoDefault = $company->logo;
      }
    }

    Session::put('userCompaniesId', $userCompaniesId);
    Session::put('userCompanyIdDefault', $userCompanyIdDefault);
    Session::put('userCompanyLogoDefault', $userCompanyLogoDefault);


    $perfilUsuario = PerfilUsuario::obtenerPerfilDelUsuario($idUser);

    $idPerfil = 0;

    if ($perfilUsuario) {
      $idPerfil = $perfilUsuario->id_perfil;
      Session::put('idPerfil', $idPerfil);

      $nombrePerfil = $perfilUsuario->nombreperfil;
      Session::put('nombrePerfil', $nombrePerfil);
    }
    //--------------------------------------//
    // PERFIL ADMINISTRADOR
    $perfilAdmin = false;

    if ($idPerfil == Perfil::ADMIN) {
      $perfilAdmin = true;
    }
    Session::put('perfilAdmin', $perfilAdmin);

    // PERFIL PROVEEDOR
    $perfilProveedor = false;

    if ($idPerfil == Perfil::PROVEEDOR) {
      $perfilProveedor = true;
    }
    Session::put('perfilProveedor', $perfilProveedor);

    //---------------------------------------//
    if ($idPerfil != Perfil::CLIENTE) {
      $roles = RolPerfil::permisosUsuario($idUser);

      foreach ($roles as $rol) {
        Session::put($rol->ruta, '1');
      }
    }

    //---------------------------------------//    
    // PERFIL ADMIN ONSITE
    $perfilAdminOnsite = false;

    if (in_array($idPerfil, [Perfil::ADMIN_ONSITE, Perfil::ADMIN_ONSITE_BGH])) {
      $perfilAdminOnsite = true;
    }
    Session::put('perfilAdminOnsite', $perfilAdminOnsite);

    //---------------------------------------//    

    //Session::put('empresasOnsite', $this->empresa_onsite_repository->filtrar(['company_id' => $userCompanyIdDefault])->get());
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    //
  }

  /**
   * Display the specified resource.
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
    //
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
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    //
  }

  /**
   * Calcula los totales de reparciones en cada estado para determinada empresa
   *
   * @return void
   */
  public function cantidadesEstadosReparacionOnsite($emrpesa_onsite_id)
  {
    $respuesta = array();
    $sysdate = date('Y-m-d H:i:s');
    $reparacionesOnsiteActivas = $this->reparacion_onsite_repository->activasPorEmpresa($emrpesa_onsite_id)->get();
    $respuesta['totalActivas'] = count($reparacionesOnsiteActivas);

    $reparacionesOnsiteCerradas = $this->reparacion_onsite_repository->cerradasPorEmpresa($emrpesa_onsite_id)->get();
    $respuesta['totalCerradas'] = count($reparacionesOnsiteCerradas);

    $totalIN = 0;
    $totalIN24 = 0;
    $totalOUT = 0;
    $totalINCERRADO = 0;
    $totalOUTCERRADO = 0;

    foreach ($reparacionesOnsiteActivas as $reparacionOnsite) {
      if ($reparacionOnsite->fecha_cerrado == '0000-00-00 00:00:00' || !$reparacionOnsite->fecha_cerrado || empty($reparacionOnsite->fecha_cerrado) ) {
        if ($sysdate <= $reparacionOnsite->fecha_vencimiento || $reparacionOnsite->sla_justificado) {
          $date1 = new DateTime($sysdate);
          $date2 = new DateTime($reparacionOnsite->fecha_vencimiento);
          $interval = $date1->diff($date2);
                      
         // if (round(abs(strtotime($reparacionOnsite->fecha_vencimiento) - strtotime($sysdate)) / 86400) <= 1) {
          if ($interval->d <= 1) {
            $totalIN24++;
          } else {
            $totalIN++;
          }
        } else {
          $totalOUT++;
        }
      }
    }

    foreach ($reparacionesOnsiteCerradas as $reparacionOnsite) {
      if ($reparacionOnsite->fecha_cerrado) {
        if ($reparacionOnsite->fecha_cerrado <= $reparacionOnsite->fecha_vencimiento || $reparacionOnsite->sla_justificado)
          $totalINCERRADO++;
        else
          $totalOUTCERRADO++;
      }
    }

    $respuesta['reparaciones']['reparacionOnsiteIN']['cantidad'] = $totalIN;
    $respuesta['reparaciones']['reparacionOnsiteIN']['clase'] = 'lnr-checkmark-circle text-success';
    $respuesta['reparaciones']['reparacionOnsiteIN']['etiqueta'] = 'in';
    $respuesta['reparaciones']['reparacionOnsiteIN24']['cantidad'] = $totalIN24;
    $respuesta['reparaciones']['reparacionOnsiteIN24']['clase'] = 'lnr-warning text-warning';
    $respuesta['reparaciones']['reparacionOnsiteIN24']['etiqueta'] = 'in (24 horas)';
    $respuesta['reparaciones']['reparacionOnsiteOUT']['cantidad'] = $totalOUT;
    $respuesta['reparaciones']['reparacionOnsiteOUT']['clase'] = 'lnr-cross-circle text-danger';
    $respuesta['reparaciones']['reparacionOnsiteOUT']['etiqueta'] = 'out';
    $respuesta['reparaciones']['reparacionOnsiteINCERRADO']['cantidad'] = $totalINCERRADO;
    $respuesta['reparaciones']['reparacionOnsiteINCERRADO']['clase'] = 'lnr-checkmark-circle text-secondary';
    $respuesta['reparaciones']['reparacionOnsiteINCERRADO']['etiqueta'] = 'in (cerrado)';
    $respuesta['reparaciones']['reparacionOnsiteOUTCERRADO']['cantidad'] = $totalOUTCERRADO;
    $respuesta['reparaciones']['reparacionOnsiteOUTCERRADO']['clase'] = 'lnr-cross-circle text-alternate';
    $respuesta['reparaciones']['reparacionOnsiteOUTCERRADO']['etiqueta'] = 'out (cerrado)';

    return $respuesta;
  }

  public function notifica_eventos()
  {
    $notificacion = false;

    if (Session::get('notificacion_index')) {
      $notificacion = Session::get('notificacion_index');
      Session::forget('notificacion_index');
    }

    return response()->json($notificacion);
  }
}
