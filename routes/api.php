<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ReparacionOnsiteController;
use App\Http\Controllers\Api\EstadoOnsiteController;
use App\Http\Controllers\Api\EmpresaOnsiteController;
use App\Http\Controllers\Api\TipoServicioOnsiteController;
use App\Http\Controllers\Api\SucursalOnsiteController;
use App\Http\Controllers\Api\TerminalOnsiteController;
use App\Http\Controllers\Api\ParametroController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\SolicitudOnsiteController;
use App\Http\Controllers\Api\SistemaOnsiteController;
use App\Http\Controllers\Api\UnidadExteriorOnsiteController;
use App\Http\Controllers\Api\UnidadInteriorOnsiteController;
use App\Http\Controllers\Api\ObraController;
use App\Http\Controllers\Api\SistemaController;
use App\Http\Controllers\Api\VisitaController;
use App\Http\Controllers\Api\SolicitudController;
use App\Http\Controllers\Api\UnidadInteriorController;
use App\Http\Controllers\Api\UnidadExteriorController;
use App\Http\Controllers\Api\EstadoSolicitudController;
use App\Http\Controllers\Api\EmpresaInstaladoraController;
use App\Http\Controllers\Api\TipoServicioController;
use App\Http\Controllers\Api\TipoSolicitudController;
use App\Http\Controllers\Api\EstadoController;
use App\Http\Controllers\Api\TecnicoController;
use App\Http\Controllers\Api\SucursalController;
use App\Http\Controllers\Api\CompradorController;
use App\Http\Controllers\Api\PuestaMarchaSatisfactoriaController;
use App\Http\Controllers\Auth\ApiAuthController;
use App\Http\Controllers\Onsite\ReparacionOnsiteController as OnsiteReparacionOnsiteController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
| Se debe cumplir con:
| https://restfulapi.net/
| https://jsonapi.org/
| 
| REST FULL API: https://www.youtube.com/watch?v=Zja932hFCJk&t=6s&ab_channel=Aprendible
*/

Route::middleware(['auth:api', 'cors'])->group(function () {

  // reparaciones_onsite
  Route::get('reparaciones_onsite/dashboard', [ReparacionOnsiteController::class, 'dashboard'])
    ->name('api.v1.reparaciones_onsite.dashboard');

  Route::get('reparaciones_onsite/cerradas', [ReparacionOnsiteController::class, 'cerradas'])
    ->name('api.v1.reparaciones_onsite.cerradas');

  Route::get('reparaciones_onsite/vencidas', [ReparacionOnsiteController::class, 'vencidas'])
    ->name('api.v1.reparaciones_onsite.vencidas');

  Route::get('reparaciones_onsite/vencen_hoy', [ReparacionOnsiteController::class, 'vencenHoy'])
    ->name('api.v1.reparaciones_onsite.vencen_hoy');

  Route::get('reparaciones_onsite/a_tiempo', [ReparacionOnsiteController::class, 'aTiempo'])
    ->name('api.v1.reparaciones_onsite.a_tiempo');

  Route::get('reparaciones_onsite/{reparacion_onsite}', [ReparacionOnsiteController::class, 'show'])
    ->name('api.v1.reparaciones_onsite.show');

  Route::put('reparaciones_onsite/update_estado/{reparacion_onsite}', [ReparacionOnsiteController::class, 'cambiarEstado'])
    ->name('api.v1.reparaciones_onsite.update_estado');

  Route::post('reparaciones_onsite/{reparacion_onsite}', [ReparacionOnsiteController::class, 'update'])
    ->name('api.v1.reparaciones_onsite.update');

  Route::put('reparaciones_onsite/coordinar/{reparacion_onsite}', [ReparacionOnsiteController::class, 'coordinar'])
    ->name('api.v1.reparaciones_onsite.coordinar');

  Route::post('reparaciones_onsite/notificar_visita/{reparacion_onsite}', [ReparacionOnsiteController::class, 'notificarVisita'])
    ->name('api.v1.reparaciones_onsite.notificar_visita');

  Route::get('reparaciones_onsite', [ReparacionOnsiteController::class, 'index'])
    ->name('api.v1.reparaciones_onsite.index');

  // estados_onsite
  Route::get('estados_onsite/activos', [EstadoOnsiteController::class, 'activos'])
    ->name('api.v1.estados_onsite.activos');

  Route::get('estados_onsite', [EstadoOnsiteController::class, 'index'])
    ->name('api.v1.estados_onsite.index');

  // empresas_onsite
  Route::get('empresas_onsite', [EmpresaOnsiteController::class, 'index'])
    ->name('api.v1.empresas_onsite.index');

  Route::get('empresas_onsite/{empresa_onsite}', [EmpresaOnsiteController::class, 'show'])
    ->name('api.v1.empresas_onsite.show');

  // tipos_servicios_onsite
  Route::get('tipos_servicios_onsite', [TipoServicioOnsiteController::class, 'index'])
    ->name('api.v1.tipos_servicios_onsite.index');

  Route::get('tipos_servicios_onsite/{tipo_servicio_onsite}', [TipoServicioOnsiteController::class, 'show'])
    ->name('api.v1.tipos_servicios_onsite.show');

  // user
  Route::get('users/{user}', [UserController::class, 'show'])
    ->name('api.v1.users.show');

  Route::post('logout', [ApiAuthController::class, 'logout'])
    ->name('api.v1.logout');

  // sucursales_onsite
  Route::get('sucursales_onsite', [SucursalOnsiteController::class, 'index'])
    ->name('api.v1.sucursales_onsite.index');

  // terminales_onsite
  Route::get('terminales_onsite', [TerminalOnsiteController::class, 'index'])
    ->name('api.v1.terminales_onsite.index');

  // solicitudes_onsite
  Route::post('guardar_solicitud', [SolicitudOnsiteController::class, 'guardarSolicitudOnsite'])
    ->name('api.v1.terminales_onsite.guardar_solicitud');

  Route::get('getParametroTerminosCondiciones', [ParametroController::class, 'getParametroTerminosCondiciones']);
  Route::post('insertSolicitudPuestaMarcha', [SolicitudOnsiteController::class, 'insertSolicitudPuestaMarcha']);

  // sistemas onsite
  Route::get('sistemas_onsite/{sistema_onsite}', [SistemaOnsiteController::class, 'show'])
    ->name('api.v1.sistemas_onsite.show');
  Route::post('sistemas_onsite', [SistemaOnsiteController::class, 'create'])
    ->name('api.v1.sistemas_onsite.create');
  Route::post('sistemas_onsite/{sistema_onsite}', [SistemaOnsiteController::class, 'update'])
    ->name('api.v1.sistemas_onsite.update');

  Route::post('updateSistemaOnsite/{sistema_onsite_id}', [SistemaOnsiteController::class, 'updateSistemaOnsite']);
  Route::get('getSistemaOnsite/{sistema_onsite_id}', [SistemaOnsiteController::class, 'getSistemaOnsite']);
  Route::get('getSistemasOnsiteByEmpresa/{empresa_onsite_id}', [SistemaOnsiteController::class, 'getSistemasOnsiteByEmpresa']);
  Route::get('getSistemasOnsiteBySucursal/{sucursal_onsite_id}', [SistemaOnsiteController::class, 'getSistemasOnsiteBySucursal']);

  // unidades interior onsite
  Route::post('unidades_interiores_onsite', [UnidadInteriorOnsiteController::class, 'create']);
  Route::post('unidades_interiores_onsite/{unidad_interior_onsite}', [UnidadInteriorOnsiteController::class, 'update']);
  Route::post('insertImagenesUnidadInteriorOnsite/{unidad_interior_onsite_id}', [UnidadInteriorOnsiteController::class, 'insertImagenesUnidadInteriorOnsite']);

  Route::post('updateUnidadInteriorOnsite/{unidad_interior_onsite_id}', [UnidadInteriorOnsiteController::class, 'updateUnidadInteriorOnsite']);
  Route::get('getUnidadInteriorOnsite/{unidad_interior_onsite_id}', [UnidadInteriorOnsiteController::class, 'getUnidadInteriorOnsite']);
  Route::get('getUnidadesInteriorOnsiteByEmpresa/{empresa_onsite_id}', [UnidadInteriorOnsiteController::class, 'getUnidadesInteriorOnsiteByEmpresa']);
  Route::get('getUnidadesInteriorOnsiteBySucursal/{sucursal_onsite_id}', [UnidadInteriorOnsiteController::class, 'getUnidadesInteriorOnsiteBySucursal']);
  Route::get('getUnidadesInteriorOnsiteBySistema/{sistema_onsite_id}', [UnidadInteriorOnsiteController::class, 'getUnidadesInteriorOnsiteBySistema']);
  Route::get('getImagenesUnidadInteriorOnsite/{unidad_interior_onsite_id}', [UnidadInteriorOnsiteController::class, 'getImagenesUnidadInteriorOnsite']);

  // unidades exterior onsite
  Route::post('unidades_exteriores_onsite', [UnidadExteriorOnsiteController::class, 'create']);
  Route::post('unidades_exteriores_onsite/{unidad_exterior_onsite}', [UnidadExteriorOnsiteController::class, 'update']);
  Route::post('insertImagenesUnidadExteriorOnsite/{unidad_exterior_onsite_id}', [UnidadExteriorOnsiteController::class, 'insertImagenesUnidadExteriorOnsite']);

  Route::post('updateUnidadExteriorOnsite/{unidad_exterior_onsite_id}', [UnidadExteriorOnsiteController::class, 'updateUnidadExteriorOnsite']);
  Route::get('getUnidadExteriorOnsite/{unidad_exterior_onsite_id}', [UnidadExteriorOnsiteController::class, 'getUnidadExteriorOnsite']);
  Route::get('getUnidadesExteriorOnsiteByEmpresa/{empresa_onsite_id}', [UnidadExteriorOnsiteController::class, 'getUnidadesExteriorOnsiteByEmpresa']);
  Route::get('getUnidadesExteriorOnsiteBySucursal/{sucursal_onsite_id}', [UnidadExteriorOnsiteController::class, 'getUnidadesExteriorOnsiteBySucursal']);
  Route::get('getUnidadesExteriorOnsiteBySistema/{sistema_onsite_id}', [UnidadExteriorOnsiteController::class, 'getUnidadesExteriorOnsiteBySistema']);
  Route::get('getImagenesUnidadExteriorOnsite/{unidad_exterior_onsite_id}', [UnidadExteriorOnsiteController::class, 'getImagenesUnidadExteriorOnsite']);

  Route::post('reparaciones_onsite/notificar_visita_puesta_en_mracha/{reparacion_onsite}', [ReparacionOnsiteController::class, 'notificarVisitaPuestaEnMarcha'])
    ->name('api.v1.reparaciones_onsite.notificar_visita_puesta_en_mracha');

  Route::post('reparaciones_onsite/guardar_visita_puesta_en_mracha/{reparacion_onsite}', [ReparacionOnsiteController::class, 'guardarVisitaPuestaEnMarcha'])
    ->name('api.v1.reparaciones_onsite.guardar_visita_puesta_en_mracha');
});

Route::group(['middleware' => ['auth:api', 'cors'], 'prefix' => 'api-bgh'], function () {

  Route::get('obrasBgh/{clave?}', [ObraController::class, 'getObrasBgh']);
  Route::get('obrasFullBgh/{clave?}', [ObraController::class, 'getObrasFullBgh']);
  Route::get('sistemasBgh/{id?}', [SistemaController::class, 'getSistemasBgh']);
  Route::get('sistemasFullBgh/{id?}', [SistemaController::class, 'getSistemasFullBgh']);
  Route::get('visitasBgh/{clave?}', [VisitaController::class, 'getVisitasBgh']);
  Route::get('visitasFullBgh/{clave?}', [VisitaController::class, 'getVisitasFullBgh']);
  Route::get('solicitudesBgh/{id?}', [SolicitudController::class, 'getSolicitudesBgh']);
  Route::get('solicitudesFullBgh/{id?}', [SolicitudController::class, 'getSolicitudesFullBgh']);
  Route::get('unidadesInterioresBgh', [UnidadInteriorController::class, 'getUnidadesInterioresBgh']);
  Route::get('unidadesExterioresBgh', [UnidadExteriorController::class, 'getUnidadesExterioresBgh']);
  Route::get('estadosSolicitudBgh', [EstadoSolicitudController::class, 'getEstadosSolicitudBgh']);
  Route::get('empresasInstaladoraBgh', [EmpresaInstaladoraController::class, 'getEmpresasInstaladorasBgh']);
  Route::get('tiposServicioBgh', [TipoServicioController::class, 'getTiposServiciosBgh']);
  Route::get('tiposSolicitudBgh', [TipoSolicitudController::class, 'getTiposSolicitudesBgh']);
  Route::get('tiposSolicitud/{company_id}', [TipoSolicitudController::class, 'getTiposSolicitudes']);
  Route::get('estadosBgh', [EstadoController::class, 'getEstadosBgh']);
  Route::get('usersBgh', [UserController::class, 'getUsersBgh']);
  Route::get('tecnicosBgh', [TecnicoController::class, 'getTecnicosBgh']);
  Route::get('sucursalesBgh', [SucursalController::class, 'getSucursalesBgh']);
  Route::get('compradoresBgh', [CompradorController::class, 'getCompradoresBgh']);
  Route::get('puestaMarchaSatisfactoria', [PuestaMarchaSatisfactoriaController::class, 'getPuestaMarchaSatisfactoria']);
});

Route::group(['middleware' => ['auth:api', 'cors'], 'prefix' => 'api'], function () {

  // obras onsite api
  Route::get('obras/{company_id}/{clave?}', [ObraController::class, 'getObras']);
  Route::get('obrasFull/{company_id}/{clave?}', [ObraController::class, 'getObrasFull']);

  // sistemas onsite api
  Route::get('sistemas/{company_id}/{id?}', [SistemaController::class, 'getSistemas']);
  Route::get('sistemasFull/{company_id}/{id?}', [SistemaController::class, 'getSistemasFull']);

  // obras onsite api
  Route::get('visitas/{company_id}/{clave?}', [VisitaController::class, 'getVisitas']);
  Route::get('visitasFull/{company_id}/{clave?}', [VisitaController::class, 'getVisitasFull']);

  // solicitudes onsite api
  Route::get('solicitudes/{company_id}/{id?}', [SolicitudController::class, 'getSolicitudes']);
  Route::get('solicitudesFull/{company_id}/{id?}', [SolicitudController::class, 'getSolicitudesFull']);

  // unidades interiores onsite api
  Route::get('unidadesInteriores/{company_id}', [UnidadInteriorController::class, 'getUnidadesInteriores']);

  // unidades exteriores onsite api
  Route::get('unidadesExteriores/{company_id}', [UnidadExteriorController::class, 'getUnidadesExteriores']);

  // estados de solicitudes api
  Route::get('estadosSolicitud/{company_id}', [EstadoSolicitudController::class, 'getEstadosSolicitud']);

  // estados de solicitudes api
  Route::get('empresasInstaladoras/{company_id}', [EmpresaInstaladoraController::class, 'getEmpresasInstaladoras']);

  // tipos de servicios api
  Route::get('tiposServicio/{company_id}', [TipoServicioController::class, 'getTiposServicios']);

  // estados api
  Route::get('estados/{company_id}/{tipo?}', [EstadoController::class, 'getEstados']);

  // usuarios api
  Route::get('users/{company_id}', [UserController::class, 'getUsers']);

  // tecnicos api
  Route::get('tecnicos/{company_id}', [TecnicoController::class, 'getTecnicos']);

  // sucursales api
  Route::get('sucursales/{company_id}', [SucursalController::class, 'getSucursales']);

  // compradores api
  Route::get('compradores/{company_id}', [CompradorController::class, 'getCompradores']);

  /* importar reparación */
  Route::post('importarReparacionesOnsite', [ReparacionOnsiteController::class, 'importarReparacionesOnsite']);

  /* update reparación */
  Route::post('updateReparacionOnsite/{company_id}/{id_reparacion}', [ReparacionOnsiteController::class, 'updateReparacionOnsite']);

  /* update reparación */
  Route::get('reparacion/{company_id}/{id_reparacion}', [ReparacionOnsiteController::class, 'getReparacion']);

  /* reparacion por clave */
  Route::get('reparacion/clave/{company_id}/{clave}', [ReparacionOnsiteController::class, 'getReparacionPorClave']);

  /* crear reparacion */
  Route::post('reparacion/create/{company_id}', [ReparacionOnsiteController::class, 'storeReparacionApi']);

  
});

Route::post('login', [ApiAuthController::class, 'login'])
  ->name('api.v1.login');

Route::post('login_bgh', [ApiAuthController::class, 'loginBgh'])
  ->name('api.v1.login_bgh');
