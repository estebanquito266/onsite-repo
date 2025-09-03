<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Onsite\VisitaController;
use App\Http\Controllers\googleApi\GoogleCalendarController;
use App\Http\Controllers\Onsite\EmpresaOnsiteController;
use App\Http\Controllers\Onsite\GarantiaOnsiteController;
use App\Http\Controllers\Onsite\LocalidadController;
use App\Http\Controllers\Onsite\ObraOnsiteController;
use App\Http\Controllers\Onsite\ReparacionOnsiteController;
use App\Http\Controllers\Onsite\SucursalOnsiteController;
use App\Http\Controllers\Onsite\SolicitudOnsiteController;
use App\Http\Controllers\Onsite\SolicitudTipoTarifaBaseController;
use App\Http\Controllers\Onsite\TemplateController;
use App\Http\Controllers\Respuestos\DetallePedidoRespuestosController;
use App\Http\Controllers\Respuestos\ModeloRespuestosOnsiteController;
use App\Http\Controllers\Respuestos\OrdenPedidoRespuestosController;
use App\Http\Controllers\Respuestos\PiezaRespuestosOnsiteController;
use App\Http\Controllers\SolicitudBoucherController;
use App\Http\Controllers\SolicitudTipoTarifaController;
use App\Http\Controllers\Ticket\CommentTicketController;
use App\Http\Controllers\Ticket\TicketController;
use App\Http\Controllers\UserController;
use App\Models\Onsite\ObraOnsite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::domain('https://bghecosmart.speedup.com.ar')->group(function () {

    Route::get('/', function () {
        return view('auth.loginbgh');
    });
});

Route::domain('https://bgh.speedup.com.ar')->group(function () {
    return redirect('https://bghecosmart.speedup.com.ar');
});

Route::get('/', [App\Http\Controllers\AdminController::class, 'index']);

Route::get('/loginbgh',    function () {
    return view('auth.loginbgh');
});

Route::get('/template',    function () {
    return view('templates.mail_repuestos_admin');
});

Route::get('/onepalma',    function () {
    $onepalma = ObraOnsite::find(167);
    return $onepalma->imagenes_obras;
});


Route::get('/admin', [App\Http\Controllers\AdminController::class, 'index'])->name('admin');

Auth::routes();

Route::group(['middleware' => 'auth'], function () {
    Route::get('notifica_eventos', [AdminController::class, 'notifica_eventos']);

    Route::get('auto-complete-address', [UserController::class, 'googleAutoAddress']);
    Route::get('mapeo_usuarios', [UserController::class, 'mapeo_usuarios']);
    Route::get('mapeo_obras', [ObraOnsiteController::class, 'mapeo_obras']);

    Route::resource('historialEstadoOnsite', App\Http\Controllers\Onsite\HistorialEstadoOnsiteController::class);
    Route::post('filtrarHistorialEstadoOnsite', [App\Http\Controllers\Onsite\HistorialEstadoOnsiteController::class, 'filtrarHistorialEstadoOnsite']);
    Route::get('filtrarHistorialEstadoOnsite', [App\Http\Controllers\Onsite\HistorialEstadoOnsiteController::class, 'filtrarHistorialEstadoOnsite']);
    Route::get('historialEstadoOnsiteTodos', [App\Http\Controllers\Onsite\HistorialEstadoOnsiteController::class, 'indexAll']);
    Route::post("ocultarHistorialEstadoOnsite/{historial_estado_onsite}", [App\Http\Controllers\Onsite\HistorialEstadoOnsiteController::class, 'ocultarHistorialEstadoOnsite']);
    Route::get('buscarHistorialEstadosOnsite/{idReparacion}', [App\Http\Controllers\Onsite\HistorialEstadoOnsiteController::class, 'getHistorialEstadosOnsite']);

    Route::resource('reparacionOnsite', ReparacionOnsiteController::class);
    Route::get('reenviarMailTecnico/{idReparacion}', [ReparacionOnsiteController::class, 'reenviarMailTecnico'])->name('reenviarMailTecnico');
    Route::post('reparacionOnsiteAgregarImagenOnsite', [ReparacionOnsiteController::class, 'agregarImagenOnsite']);
    Route::post('reparacionOnsiteAgregarVisita', [ReparacionOnsiteController::class, 'agregarVisita']);
    Route::delete('reparacionOnsiteEliminarImagenOnsite/{id}', [ReparacionOnsiteController::class, 'eliminarImagenOnsite']);
    Route::post('filtrarReparacionOnsite', [ReparacionOnsiteController::class, 'filtrarReparacionOnsite']);
    Route::get('filtrarReparacionOnsite', [ReparacionOnsiteController::class, 'filtrarReparacionOnsite']);

    Route::post('filtrarReparacionOnsitePorEmpresa', [AdminController::class, 'filtrarPorEmpresa']);
    Route::get('reparacionOnsitePosnet', [ReparacionOnsiteController::class, 'indexPosnet']);
    Route::post('importarReparacionesOnsite', [ReparacionOnsiteController::class, 'importarReparacionesOnsite']);

    Route::post('registrar_visita/{reparacion_id}', [ReparacionOnsiteController::class, 'registrar_visita'])->name('registrar_visita');

    //Route::post('reparacionOnsite', [ReparacionOnsiteController::class, 'index']);
    Route::get('importarReparacionOnsite/', [ReparacionOnsiteController::class, 'importarReparacionOnsite']);
    Route::post('importarReparacionOnsite', [ReparacionOnsiteController::class, 'importFileReparacionOnsite']);
    Route::get('getRowsReparacionesProcessed', [ReparacionOnsiteController::class, 'getRowsReparacionesProcessed']);


    Route::post('agregarNota', [App\Http\Controllers\Onsite\NotaOnsiteController::class, 'agregar']);

    Route::get('reparacionOnsiteFacturada', [ReparacionOnsiteController::class, 'indexFacturada']);
    Route::get('reparacionOnsiteEmpresaActivas', [ReparacionOnsiteController::class, 'indexEmpresaActivas']);
    Route::post('filtrarReparacionOnsiteActivas', [ReparacionOnsiteController::class, 'filtrarReparacionOnsiteActivas']);
    Route::get('filtrarReparacionOnsiteActivas', [ReparacionOnsiteController::class, 'filtrarReparacionOnsiteActivas']);

    Route::get('reparacionOnsiteEmpresaCerradas', [ReparacionOnsiteController::class, 'indexEmpresaCerradas']);
    Route::post('filtrarReparacionOnsiteCerradas', [ReparacionOnsiteController::class, 'filtrarReparacionOnsiteCerradas']);
    Route::get('filtrarReparacionOnsiteCerradas', [ReparacionOnsiteController::class, 'filtrarReparacionOnsiteCerradas']);

    Route::get('reparacionOnsiteConPresupuestoPendienteDeAprobacion', [ReparacionOnsiteController::class, 'indexReparacionOnsiteConPresupuestoPendienteDeAprobacion']);
    Route::get('reporteReparacionOnsite/{exitoso}', [ReparacionOnsiteController::class, 'reporteReparacionOnsite'])->name('reporteReparacionOnsite');
    Route::post('generarReporteReparacionOnsite', [ReparacionOnsiteController::class, 'generarReporteReparacionOnsite']);
    Route::get('soporteReparacionesOnsite', [ReparacionOnsiteController::class, 'soporteReparacionesOnsite']);
    Route::put('reparacionOnsite/chequeadoPorCliente/{reparacion_onsite}', [ReparacionOnsiteController::class, 'reparacionOnsiteChequeadoPorCliente'])->name('reparacionOnsiteChequeadoPorCliente');
    Route::get('obtenerTipoTerminal/{id}', [ReparacionOnsiteController::class, 'obtenerTipoTerminal'])->name('obtenerTipoTerminal');
    Route::get('getReparacionPorId/{idReparacion}', [ReparacionOnsiteController::class, 'getReparacionPorId']);
    Route::get('getResultadosReparacionPorEmpresaInstaladora', [VisitaController::class, 'getResultadosReparacionPorEmpresaInstaladora']);
    Route::get('getResultadosReparacionPorTecnico', [VisitaController::class, 'getResultadosReparacionPorTecnico']);

    Route::get('/getPromedioCoordinadasCerradas', [ReparacionOnsiteController::class, 'getPromedioCoordinadasCerradas']);


    Route::resource('localidadOnsite', App\Http\Controllers\Onsite\LocalidadOnsiteController::class);
    Route::post('filtrarLocalidadOnsite', [App\Http\Controllers\Onsite\LocalidadOnsiteController::class, 'filtrarLocalidadOnsite']);
    Route::get('filtrarLocalidadOnsite', [App\Http\Controllers\Onsite\LocalidadOnsiteController::class, 'filtrarLocalidadOnsite']);

    Route::resource('terminalOnsite', App\Http\Controllers\Onsite\TerminalOnsiteController::class);

    //unidades interiores onsite
    Route::resource('unidadInterior', App\Http\Controllers\Onsite\UnidadInteriorOnsiteController::class);
    Route::post('filtrarUnidadInterior', [App\Http\Controllers\Onsite\UnidadInteriorOnsiteController::class, 'filtrarUnidadInterior']);
    Route::get('filtrarUnidadInterior', [App\Http\Controllers\Onsite\UnidadInteriorOnsiteController::class, 'filtrarUnidadInterior']);


    Route::get('unidadInterior/{id}/editUnidadInterior', [App\Http\Controllers\Onsite\UnidadInteriorOnsiteController::class, 'editUnidadInterior']);
    Route::get('unidadInterior/{id}/createUnidadInterior', [App\Http\Controllers\Onsite\UnidadInteriorOnsiteController::class, 'createUnidadInterior']);

    Route::get('insertUISistema/{idSistema}', [App\Http\Controllers\Onsite\UnidadInteriorOnsiteController::class, 'insertUISistema']);
    Route::get('insertUESistema/{idSistema}', [App\Http\Controllers\Onsite\UnidadExteriorOnsiteController::class, 'insertUESistema']);
    Route::post('storeUnidadInterior', [App\Http\Controllers\Onsite\UnidadInteriorOnsiteController::class, 'store']);
    Route::get('getUnidadesInterioresPorSistema/{idSistema}', [App\Http\Controllers\Onsite\UnidadInteriorOnsiteController::class, 'getUnidadesInterioresPorSistema']);



    /* UNIDAD INTERIOR ETIQUETAS */
    Route::post('storeEtiqueta/{idUnidadInterior}', [App\Http\Controllers\Onsite\UnidadInteriorEtiquetaController::class, 'store']);
    Route::get('getEtiquetas/{idUnidadInterior}', [App\Http\Controllers\Onsite\UnidadInteriorEtiquetaController::class, 'getEtiquetas']);
    Route::get('delEtiqueta/{idEtiqueta}', [App\Http\Controllers\Onsite\UnidadInteriorEtiquetaController::class, 'destroy']);

    /* UNIDADES EXTERIORES ETIQUETAS */
    Route::post('storeEtiquetaExterior/{idUnidadExterior}', [App\Http\Controllers\Onsite\UnidadExteriorEtiquetaController::class, 'store']);
    Route::get('getEtiquetasExterior/{idUnidadExterior}', [App\Http\Controllers\Onsite\UnidadExteriorEtiquetaController::class, 'getEtiquetas']);
    Route::get('delEtiquetaExterior/{idEtiqueta}', [App\Http\Controllers\Onsite\UnidadExteriorEtiquetaController::class, 'destroy']);

    Route::get('checkIdSistema', [App\Http\Controllers\Onsite\UnidadInteriorOnsiteController::class, 'checkIdSistema']);

    //fin unidades interiores onsite

    //unidades exteriores onsite
    Route::resource('unidadExterior', App\Http\Controllers\Onsite\UnidadExteriorOnsiteController::class);
    Route::post('filtrarUnidadExterior', [App\Http\Controllers\Onsite\UnidadExteriorOnsiteController::class, 'filtrarUnidadExterior']);
    Route::get('filtrarUnidadExterior', [App\Http\Controllers\Onsite\UnidadExteriorOnsiteController::class, 'filtrarUnidadExterior']);


    Route::get('unidadExterior/{id}/editUnidadExterior', [App\Http\Controllers\Onsite\UnidadExteriorOnsiteController::class, 'editUnidadExterior']);
    Route::get('unidadExterior/{id}/createUnidadExterior', [App\Http\Controllers\Onsite\UnidadExteriorOnsiteController::class, 'createUnidadExterior']);
    Route::post('storeUnidadExterior', [App\Http\Controllers\Onsite\UnidadExteriorOnsiteController::class, 'store']);
    Route::get('getUnidadesExterioresPorSistema/{idSistema}', [App\Http\Controllers\Onsite\UnidadExteriorOnsiteController::class, 'getUnidadesExterioresPorSistema']);

    //fin unidades exteriores onsite

    //imagenes unidades onsite
    Route::resource('imagenUnidadOnsite', App\Http\Controllers\Onsite\ImagenUnidadOnsiteController::class);
    Route::get('unidadExterior/{id}/editImagen', [App\Http\Controllers\Onsite\ImagenUnidadOnsiteController::class, 'editExterior']);
    Route::get('unidadExterior/{id_unidad}/createImagen', [App\Http\Controllers\Onsite\ImagenUnidadOnsiteController::class, 'createExterior']);
    Route::get('unidadInterior/{id}/editImagen', [App\Http\Controllers\Onsite\ImagenUnidadOnsiteController::class, 'editInterior']);
    Route::get('unidadInterior/{id_unidad}/createImagen', [App\Http\Controllers\Onsite\ImagenUnidadOnsiteController::class, 'createInterior']);
    //fin imagenes unidades Onsite

    /* ImagenObraOnsite */
    Route::resource('imagenobraonsite', App\Http\Controllers\Onsite\ImagenObraOnsiteController::class);

    /* ************ */

    /* Respuestos Onsite */
    Route::resource('respuestosOnsite', OrdenPedidoRespuestosController::class);
    Route::get('exportar_repuestos', [OrdenPedidoRespuestosController::class, 'export']);
    Route::get('precios/', [PiezaRespuestosOnsiteController::class, 'precios']);
    Route::get('exportarPrecios/', [PiezaRespuestosOnsiteController::class, 'export']);

    Route::get('selectModelosRespuestos/{idCategoria}', [ModeloRespuestosOnsiteController::class, 'getModelosPorCategoria']);
    Route::get('selectPiezasRespuestos/{idModelo}', [PiezaRespuestosOnsiteController::class, 'getPiezasPorModelo']);
    Route::get('getPiezasCode/{partCode}', [PiezaRespuestosOnsiteController::class, 'getPiezasCode']);
    Route::get('getPiezaCode/{partCode}', [PiezaRespuestosOnsiteController::class, 'getPiezaCode']);
    Route::get('getPiezasName/{partName}', [PiezaRespuestosOnsiteController::class, 'getPiezasName']);
    Route::get('getPiezasDescription/{partDescription}', [PiezaRespuestosOnsiteController::class, 'getPiezasDescription']);
    Route::get('getPieza/{idPieza}', [PiezaRespuestosOnsiteController::class, 'getPieza']);
    Route::post('updatePieza/{idPieza}', [PiezaRespuestosOnsiteController::class, 'updatePieza']);
    Route::post('importPreciosRepuestos/', [PiezaRespuestosOnsiteController::class, 'import']);

    Route::get('getImagenPorModelo/{idModelo}', [ModeloRespuestosOnsiteController::class, 'getImagenPorModelo']);

    Route::get('tecnicos', function () {

        return view(
            '_onsite.respuestosonsite.tecnicos',
            [
                'email' => Auth::user()->email,
                'password' => Session::get('passwordUser')

            ]
        );
    });



    /* GARANTIAS ONSITE */

    Route::resource('garantiaonsite', GarantiaOnsiteController::class);
    Route::get('garantiaOnsiteEmitir/{idGarantia}', [GarantiaOnsiteController::class, 'garantiaOnsiteEmitir']);
    Route::get('getObrasPorEmpresa/{idEmpresaInstaladora}', [GarantiaOnsiteController::class, 'getObrasPorEmpresa']);
    Route::get('getUnidadesPorSistema/{idSistema}', [GarantiaOnsiteController::class, 'getUnidadesPorSistema']);
    Route::get('createGarantiaFromSistema/{idSistema}', [GarantiaOnsiteController::class, 'createGarantiaFromSistema']);

    Route::post('storeGarantia', [GarantiaOnsiteController::class, 'store']);

    Route::get('delGarantia/{idGarantia}', [GarantiaOnsiteController::class, 'destroy']);

    /* **************** */

    Route::post('storeOrdenPedidoRespuestos', [OrdenPedidoRespuestosController::class, 'storeOrdenPedidoRespuestos']);
    Route::post('storeDetalleOrdenRespuestos', [DetallePedidoRespuestosController::class, 'storeDetalleOrdenRespuestos']);
    Route::post('deleteDetallePedido/{idDetallePedido}', [DetallePedidoRespuestosController::class, 'deleteDetallePedido']);

    Route::get('detalleOrdenRespuestos/{idOrden}', [DetallePedidoRespuestosController::class, 'getDetalleOrden']);
    Route::get('editDetalleOrden/{idOrden}', [DetallePedidoRespuestosController::class, 'getDetalleOrdenAjax']);

    Route::get('getDetalleOrden/{idOrden}', [DetallePedidoRespuestosController::class, 'getDetalleOrdenAjax']);

    Route::post('confirmarOrden/{idOrden}', [OrdenPedidoRespuestosController::class, 'confirmarOrden']);
    Route::post('enviarEmailsRepuestos/{idOrden}', [OrdenPedidoRespuestosController::class, 'enviarEmailsRepuestos']);

    Route::post('updateDetalleOrdenRespuestos/{idDetalleOrden}', [DetallePedidoRespuestosController::class, 'updateDetalleOrdenRespuestos']);

    Route::post('updateOrdenRespuestos/{idOrden}', [OrdenPedidoRespuestosController::class, 'updateOrdenRespuestos']);
    Route::post('updateEstadoOrdenRespuestos/{idOrden}', [OrdenPedidoRespuestosController::class, 'updateEstadoOrdenRespuestos']);

    Route::get('getUsuarioEmpresa/{idEmpresa}', [OrdenPedidoRespuestosController::class, 'getUsuarioEmpresa']);

    /* TARIFAS SOLICITUDES */

    Route::resource('solicitudesTiposTarifas', SolicitudTipoTarifaController::class);
    Route::resource('solicitudesTiposTarifasBases', SolicitudTipoTarifaBaseController::class);
    Route::get('getTarifaSolicitudPorObra/{idSolicitud}/{idObra}', [SolicitudTipoTarifaController::class, 'getTarifaSolicitudPorObra']);

    /* bouchers solicitudes */
    Route::resource('solicitudBoucher', SolicitudBoucherController::class);
    Route::get('getBouchersPorObra/{idObra}', [SolicitudBoucherController::class, 'getBouchersPorObra']);
    Route::get('getAllBouchersPorObra/{idObra}', [SolicitudBoucherController::class, 'getAllBouchersPorObra']);

    Route::post('updateBoucher/{idBoucher}', [SolicitudBoucherController::class, 'update']);
    Route::post('storeBoucher', [SolicitudBoucherController::class, 'storeBoucher']);
    Route::get('unsetSessionVariable', [SolicitudBoucherController::class, 'unsetSessionVariable']);

    /* ***************** */
    Route::post('filtrarTerminalOnsite', [App\Http\Controllers\Onsite\TerminalOnsiteController::class, 'filtrarTerminalOnsite']);
    Route::get('buscarTerminalesOnsite/{idSucursalOnsite}', [App\Http\Controllers\Onsite\TerminalOnsiteController::class, 'getTerminalesOnsite']);

    Route::get('getEmpresaOnsite/{id}', [App\Http\Controllers\Onsite\EmpresaOnsiteController::class, 'getEmpresaOnsite']);
    Route::get('getEmpresasOnsitePorInstaladora/', [App\Http\Controllers\Onsite\EmpresaOnsiteController::class, 'getEmpresasOnsitePorInstaladora']);
    Route::post('storeEmpresaOnsite/', [App\Http\Controllers\Onsite\EmpresaOnsiteController::class, 'storeEmpresaOnsite']);
    Route::get('getEmpresasOnsitePorInstaladoraId/{idEmpresaInstaladora}', [App\Http\Controllers\Onsite\EmpresaOnsiteController::class, 'getEmpresasOnsitePorInstaladoraId']);

    Route::resource('sucursalesOnsite', SucursalOnsiteController::class);
    Route::get('buscarSucursalesOnsite/{idEmpresaOnsite}', [SucursalOnsiteController::class, 'getSucursalesOnsite']);
    Route::get('searchSucursalesOnsite/{idEmpresaOnsite}/{textoBuscar?}', [SucursalOnsiteController::class, 'searchSucursalesOnsite']);
    Route::post('filtrarSucursalesOnsite', [SucursalOnsiteController::class, 'filtrarSucursalesOnsite']);
    Route::get('filtrarSucursalesOnsite', [SucursalOnsiteController::class, 'filtrarSucursalesOnsite']);
    Route::get('descargarSucursalesOnsite', [SucursalOnsiteController::class, 'descargarSucursalesOnsite']);

    Route::resource('sistemaOnsite', App\Http\Controllers\Onsite\SistemaOnsiteController::class);
    Route::get('buscarSistemasOnsite/{idSucursalOnsite}', [App\Http\Controllers\Onsite\SistemaOnsiteController::class, 'buscarSistemasOnsite']);
    Route::get('getSistemasPorObra/{idObra}', [App\Http\Controllers\Onsite\SistemaOnsiteController::class, 'getSistemasPorObra']);
    Route::get('createSistema/', [App\Http\Controllers\Onsite\SistemaOnsiteController::class, 'createSistema']);
    Route::get('getSistemaPorId/{idSistema}', [App\Http\Controllers\Onsite\SistemaOnsiteController::class, 'getSistemaPorId']);
    Route::post('filtrarSistemasOnsite', [App\Http\Controllers\Onsite\SistemaOnsiteController::class, 'filtrarSistemasOnsite']);
    Route::get('filtrarSistemasOnsite', [App\Http\Controllers\Onsite\SistemaOnsiteController::class, 'filtrarSistemasOnsite']);
    Route::post('storeSistema', [App\Http\Controllers\Onsite\SistemaOnsiteController::class, 'store']);

    Route::get('configUsuario/{id}', [App\Http\Controllers\UserController::class, 'configUsuario']);
    Route::put('updateConfigUsuario/{id}', [App\Http\Controllers\UserController::class, 'updateConfigUsuario']);

    Route::get('maps', function () {
        return view('usuario.maps');
    });


    /* COMPRADOR ONSITE */
    Route::post('storeComprador', [App\Http\Controllers\Onsite\CompradorOnsiteController::class, 'storeComprador']);
    Route::get('getCompradorPorSistema/{idSistema}', [App\Http\Controllers\Onsite\CompradorOnsiteController::class, 'getCompradorPorSistema']);
    Route::post('updateCompradorPorId/{idComprador}', [App\Http\Controllers\Onsite\CompradorOnsiteController::class, 'updateCompradorPorId']);


    //-----------------------------------------------------------//

    Route::resource('obrasOnsite', ObraOnsiteController::class);
    Route::get('createObra', [ObraOnsiteController::class, 'createObra']);
    Route::post('storeObra', [ObraOnsiteController::class, 'storeObra']);
    Route::post('storeCheckList', [ObraOnsiteController::class, 'storeCheckListObra']);
    Route::get('viewDashboardObra', [ObraOnsiteController::class, 'viewDashboardObra']);
    Route::get('getObrasSinObservaciones', [ObraOnsiteController::class, 'getObrasSinObservaciones']);
    Route::get('getObraConSistema/{idObra}', [ObraOnsiteController::class, 'getObraConSistema']);

    Route::get('getSolicitudesPorSistema/{idSistemas}', [SolicitudOnsiteController::class, 'getSolicitudesPorSistema']);
    Route::post('insertSolicitudPuestaMarcha', [SolicitudOnsiteController::class, 'insertSolicitudPuestaMarcha']);

    Route::get('SolicitudPuestaMarcha', [ObraOnsiteController::class, 'createSolicitudPuestaMarcha']);
    Route::get('getObraOnsite/{idObra}', [ObraOnsiteController::class, 'getObraOnsite']);
    Route::get('getObraOnsiteWithSistema/{idObra}', [ObraOnsiteController::class, 'getObraOnsiteWithSistema']);

    Route::get('getTemplate/{idTemplate}', [TemplateController::class, 'getTemplate']);
    Route::get('getTemplateSolicitud/{idTemplate}', [TemplateController::class, 'getTemplateSolicitud']);

    Route::get('getLocalidades/{idProvincia}', [LocalidadController::class, 'getLocalidades']);

    Route::post('/filtrarObraOnsite', [ObraOnsiteController::class, 'filtrarObrasOnsite']);
    Route::get('/filtrarObraOnsite', [ObraOnsiteController::class, 'filtrarObrasOnsite']);
    Route::get('/obrasOnsiteUnificado', [ObraOnsiteController::class, 'obrasOnsiteUnificado']);
    Route::get('/getObras', [ObraOnsiteController::class, 'getObrasOnsiteDashboard']);


    /* GOOGLE API */
    Route::get('/google-calendar/connect', [GoogleCalendarController::class, 'connect']);
    Route::get('/google-calendar/store', [GoogleCalendarController::class, 'store']);
    Route::get('/get-resource', [GoogleCalendarController::class, 'getResources']);

    Route::resource('solicitudesOnsite', SolicitudOnsiteController::class);
    Route::get('createSolicitudInspeccion', [SolicitudOnsiteController::class, 'create']);
    Route::post('storeSolicitud', [SolicitudOnsiteController::class, 'storeSolicitud']);

    Route::post('filtrarSolicitudesOnsite', [SolicitudOnsiteController::class, 'filtrarSolicitudesOnsite']);
    Route::get('filtrarSolicitudesOnsite', [SolicitudOnsiteController::class, 'filtrarSolicitudesOnsite']);
    Route::get('conversorReparacionOnsite/{id}', [SolicitudOnsiteController::class, 'show_conversorReparacionOnsite'])->name('solicitud.conversor');

    Route::post('/procesarConversorVisita/{id}', [SolicitudOnsiteController::class, 'procesarConversorVisita'])->name('solicitud.conversion');

    Route::post('filtrarPedidoRepuestos', [OrdenPedidoRespuestosController::class, 'filtrarPedidoRepuestos']);
    Route::get('filtrarPedidoRepuestos', [OrdenPedidoRespuestosController::class, 'filtrarPedidoRepuestos']);

    //visitas
    //Route::get('visitasOnsite', [VisitaController::class, 'indexVisitas']);

    Route::resource('visitasOnsite', VisitaController::class);

    Route::post('filtrarVisitas', [VisitaController::class, 'filtrarVisitas']);
    Route::get('filtrarVisitas', [VisitaController::class, 'filtrarVisitas']);
    Route::get('export_visitas', [VisitaController::class, 'export_visitas']);

    Route::get('/getVisitasPorTecnico', [VisitaController::class, 'getVisitasPorTecnico']);

    Route::get('comprobanteVisita/{idReparacion}', [VisitaController::class, 'comprobanteVisita']);

    Route::get('/getObrasConVisitas', [ObraOnsiteController::class, 'getObrasConVisitas']);
    /* Route::get('/getVisitasPorTecnico', [UserController::class, 'getVisitasPorTecnico']); */

    //Route::get('createVisita', [VisitaController::class, 'createVisita']);
    Route::post('storeVisita', [VisitaController::class, 'store']);

    Route::get('crearGarantia/{idReparacion}', [GarantiaOnsiteController::class, 'createGarantiaFromReparacion']);

    Route::get('buscarClienteConReparaciones',[TicketController::class, 'buscarCliente']);
// Route::get('buscarClienteConReparaciones',[TicketController::class, 'buscarCliente']);
Route::resource('ticket',TicketController::class);
Route::get('/ticketderiv/{derivacionid}',[TicketController::class, 'createFromDerivacion']);
Route::get('/ticketrep/{reparacionid}',[TicketController::class, 'createFromReparacion']);

Route::get('indexCerrados',  [TicketController::class, 'indexCerrados']);

Route::get('findTicketsByReparacionId', [TicketController::class, 'findTicketByReparacionId']);
Route::get('findTicketsByDerivacionId', [TicketController::class, 'findTicketByDerivacionId']);
Route::get('findTicketById', [TicketController::class, 'findTicketById']);
Route::post('filtrarTickets', [TicketController::class, 'filtrarTickets']);
Route::post('filtrarTicketsCerrados', [TicketController::class, 'filtrarTicketsCerrados']);
Route::get('exportarTickets', [TicketController::class, 'exportarTickets']);

Route::resource('commentTicket', CommentTicketController::class);
Route::get('findCommentsByTicketId', [CommentTicketController::class, 'findCommentsByTicketId']);
});
//Route::get('/app/', [App\Http\Controllers\FrontendReactController::class, 'index']);

Route::get(env('APP_DIR_DEPLOY'), function () {
    return Redirect::to(env('APP_DIR_DEPLOY_REDIRECT'));
});

/* Route::get('/app', function () {
    return Redirect::to('/app/');
}); */

Route::get('/app', function () {
    return Redirect::to(env('APP_URL'));
});

Route::get('/app/', function () {
    return Redirect::to(env('APP_URL'));
});




Route::get('findReparacionById/{idReparacion}', [ReparacionOnsiteController::class, 'findReparacionById']);
Route::get('findClienteReparacionById/{id}', [EmpresaOnsiteController::class, 'getEmpresaOnsite']);
Route::get('buscarClientes/{textoBuscar}', [EmpresaOnsiteController::class, 'getClientes']);
