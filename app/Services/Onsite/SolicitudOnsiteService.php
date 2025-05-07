<?php

namespace App\Services\Onsite;

use App\Exports\GenericExport;
use App\Http\Requests\Onsite\ReparacionOnsiteRequest;
use App\Models\Onsite\CompradorOnsite;
use App\Models\Onsite\EmpresaInstaladoraEmpresaOnsite;
use App\Models\Onsite\EmpresaOnsite;
use App\Models\User;

use App\Models\Onsite\ReparacionChecklistOnsite;
use App\Models\Onsite\SistemaOnsite;
use App\Models\Onsite\SolicitudOnsite;
use App\Models\Onsite\SucursalOnsite;
use App\Models\Onsite\TipoServicioOnsite;
use App\Repositories\Onsite\EstadoOnsiteRepository;
use App\Repositories\Onsite\TipoServicioOnsiteRepository;
use Auth;

use App\Services\Onsite\SucursalOnsiteService;
use App\Services\Onsite\ObrasOnsiteService;


use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class SolicitudOnsiteService
{
    protected $obraOnsiteService;
    protected $empresaOnsiteService;
    protected $sistemaOnsiteService;
    protected $estadosSolicitudesService;
    protected $tiposSolicitudesService;
    protected $sucursalOnsiteService;
    protected $mailOnSiteService;
    protected $parametrosService;
    protected $visitasService;
    protected $estadoOnSiteRepository;
    protected $tipoServicioOnsiteRepository;
    protected $userService;
    protected $empresaInstaladoraService;
    protected $compradorOnsiteService;

    protected $userCompanyId;

    public function __construct(
        ObrasOnsiteService $obraOnsiteService,
        EmpresaOnsiteService $empresaOnsiteService,
        SistemaOnsiteService $sistemaOnsiteService,
        EstadosSolicitudesService $estadosSolicitudesService,
        TiposSolicitudesService $tiposSolicitudesService,
        SucursalOnsiteService $sucursalOnsiteService,
        MailOnsiteService $mailOnsiteService,
        ParametroService $parametrosService,
        VisitasService $visitasService,
        EstadoOnsiteRepository $estadoOnSiteRepository,
        TipoServicioOnsiteRepository $tipoServicioOnsiteRepository,
        UserService $userService,
        EmpresasInstaladorasServices $empresaInstaladoraService,
        CompradoresOnsiteService $compradorOnsiteService
    ) {
        $this->obraOnsiteService = $obraOnsiteService;
        $this->empresaOnsiteService = $empresaOnsiteService;
        $this->sistemaOnsiteService = $sistemaOnsiteService;
        $this->estadosSolicitudesService = $estadosSolicitudesService;
        $this->tiposSolicitudesService = $tiposSolicitudesService;
        $this->sucursalOnsiteService = $sucursalOnsiteService;
        $this->mailOnSiteService = $mailOnsiteService;
        $this->parametrosService = $parametrosService;
        $this->visitasService = $visitasService;
        $this->estadoOnSiteRepository = $estadoOnSiteRepository;
        $this->tipoServicioOnsiteRepository = $tipoServicioOnsiteRepository;
        $this->userService = $userService;
        $this->empresaInstaladoraService = $empresaInstaladoraService;
        $this->compradorOnsiteService = $compradorOnsiteService;
        $this->userCompanyId =  session()->get('userCompanyIdDefault');
    }
    public function getData()
    {
        $datos['obrasOnsite'] = $this->obraOnsiteService->getAllObrasOnsite();
        $datos['estadosSolicitudOnsite'] = $this->estadosSolicitudesService->getEstadosSolicitudesAll();
        $datos['tipoSolicitud'] = $this->tiposSolicitudesService->getTiposSolicitudesAll();
        $datos['todas'] = [
            1 => 'Todas',
            0 => 'Pendientes',
        ];

        return $datos;
    }

    public function obtenerSucursales($id_empresa)
    {
        $sucursalesOnsite = $this->sucursalOnsiteService->getSucursalEmpresa($id_empresa);
        return response()->json($sucursalesOnsite);
    }

    public function getDataIndex()
    {
        $datos = $this->getData();

        $perfilAdminOnsite = Session::get('perfilAdminOnsite');
        $perfilAdmin = Session::get('perfilAdmin');

        if ($perfilAdminOnsite == true || $perfilAdmin == true) {
            $datos['solicitudesOnsite'] = $this->listar(null, null, null, null, true, null, null);
            $datos['perfilUser'] = 'admin';
        } else {

            /*  $datos['solicitudesOnsite'] = $this->listarSolicitudesOnsitePorEmpresaUser(); */
            $datos['solicitudesOnsite'] = $this->listar(null, null, null, null, true, null, null);

            $datos['perfilUser'] = 'puestaEnMarcha';
        }

        return $datos;
    }

    public function store($request)
    {
        if (Session::has('userCompanyIdDefault')) {
            $request['company_id'] = Session::get('userCompanyIdDefault');
        }

        /* Obtengo datos de obra segun sistema */
        if (!isset($request['obra_onsite_id']) || $request['obra_onsite_id'] < 1) {
            $idSistema = $request->get('sistema_onsite_id');
            $sistemaOnsite = $this->sistemaOnsiteService->findSistemaOnsite($idSistema);
            $idObraOnsite = $sistemaOnsite->obra_onsite_id;
            $request['obra_onsite_id'] = $idObraOnsite;
        }


        $request['terminos_condiciones'] = $request['terminos_condiciones'] ? 1 : 0;

        $solicitudOnsite = SolicitudOnsite::create($request->all());

        /* $this->enviarMailEstado($solicitudOnsite, $request);
        $this->enviarMailAdministrador($solicitudOnsite, $request); */

        return $solicitudOnsite;
    }

    public function getDatashow($id)
    {
        $datos = $this->getData();
        $solicitudOnSite = $this->findSolicitud($id);

        $datos['solicitudOnsite'] = $solicitudOnSite;
        $datos['detalle'] = true;
        $datos['obraOnsite'] = $solicitudOnSite->obra_onsite;
        $datos['obraChecklistOnsite'] = $solicitudOnSite->obra_onsite->obraChecklistOnsite;
        $datos['solicitudesTipos'] = $this->tiposSolicitudesService->getTiposSolicitudesAll();

        return $datos;
    }

    public function getDataEdit($id)
    {
        $datos = $this->getData();
        $solicitudOnSite = $this->findSolicitud($id);

        $datos['solicitudOnsite'] = $solicitudOnSite;
        $datos['obrasOnsite'] = $this->obraOnsiteService->getAllObrasOnsite();
        $datos['estadoSolicitudOnsite'] = $this->estadosSolicitudesService->getEstadosSolicitudesAll();
        $datos['obraOnsite'] = $solicitudOnSite->obra_onsite;
        $datos['obraChecklistOnsite'] = $solicitudOnSite->obra_onsite->obraChecklistOnsite;
        $datos['solicitudesTipos'] = $this->tiposSolicitudesService->getTiposSolicitudesAll();

        return $datos;
    }

    public function update($request, $solicitudOnsite)
    {

        $request['terminos_condiciones'] = $request['terminos_condiciones'] ? 1 : 0;

        $solicitudOnsite = $this->findSolicitud($solicitudOnsite);


        if ($solicitudOnsite->estado_solicitud_onsite_id != $request['estado_solicitud_onsite_id']) {

            /* $this->enviarMailEstado($solicitudOnsite);

            $this->enviarMailAdministrador($solicitudOnsite, $request); */
        }

        $solicitudOnsite->update($request->all());

        return $solicitudOnsite;
    }

    public function destroy($id)
    {
        $solicitudOnsite = $this->findSolicitud($id);
        $solicitudOnsite->delete();
        return $id;
    }

    public function getFiltrarSolicitudesOnsite($request)
    {
        $perfilAdminOnsite = Session::get('perfilAdminOnsite');
        $perfilAdmin = Session::get('perfilAdmin');

        if ($perfilAdminOnsite == true || $perfilAdmin == true) {
            $datos['perfilUser'] = 'admin';
        } else {

            $datos['perfilUser'] = 'puestaEnMarcha';
        }

        $datos['texto'] = $request['texto'];
        $datos['obraOnsiteId'] = $request['obra_onsite_id'];
        $datos['estadoSolicitudOnsiteId'] = $request['estado_solicitud_onsite_id'];
        $datos['tipoSolicitudId'] = $request['solicitud_tipo_id'];
        $datos['pendientes'] = $request['pendientes'];

        $datos['obraOnsite'] = $this->obraOnsiteService->listado();
        $datos['estadosSolicitudOnsite'] = $this->estadosSolicitudesService->getEstadosSolicitudesAll();
        $datos['tipoSolicitud'] = $this->tiposSolicitudesService->getTiposSolicitudesAll();
        $datos['todas'] = [
            1 => 'Todas',
            0 => 'Pendientes',
        ];

        $boton_filtrar = $request['boton_filtrar'];

        $datos['solicitudesOnsite'] = $this->listar($datos['texto'], $datos['obraOnsiteId'], $datos['estadoSolicitudOnsiteId'], $datos['tipoSolicitudId'], $request['pendientes'], null, null);

        if ($boton_filtrar == 'csv') {
            $this->generarXlsx($request['texto'], $request['obra_onsite_id'], $request['estado_solicitud_onsite_id'], $datos['tipoSolicitudId'], $request['pendientes']);
        }

        return $datos;
    }

    public function generarCsv($texto, $obraOnsiteId, $estadoSolicitudOnsiteId, $tipoSolicitudId, $pendientes)
    {
        $saltear = 0;
        $tomar = 1000;
        $solicitudesOnsite = true;

        $idUser = Auth::user()->id;

        $fp = fopen("exports/listado_solicitudonsite" . $idUser . ".csv", 'w');

        $cabecera = array(
            'ID',
            'OBRA_ONSITE_ID',
            'NOMBRE',
            'ESTADO_SOLICITUD_ONSITE_ID',
            'NOMBRE_ESTADO',
            'TERMINOS_CONDICIONES',
            'OBSERVACIONES_INTERNAS',
            'NOTA_CLIENTE',
            'COMENTARIOS',
            'created_at'
        );

        fputcsv($fp, $cabecera, ';');

        $solicitudesOnsite = $this->listar($texto, $obraOnsiteId, $estadoSolicitudOnsiteId, $tipoSolicitudId, $pendientes, $saltear, $tomar);

        while ($solicitudesOnsite->count()) {
            foreach ($solicitudesOnsite as $solicitudOnsite) {
                $fila = array(
                    'id' => $solicitudOnsite->id,
                    'obra_onsite_id' => $solicitudOnsite->obra_onsite_id,
                    'nombreobraonsiteid' => $solicitudOnsite->nombreobraonsiteid,
                    'estado_solicitud_onsite_id' => $solicitudOnsite->estado_solicitud_onsite_id,
                    'nombreestadosolicitudonsiteid' => $solicitudOnsite->nombreestadosolicitudonsiteid,
                    'terminos_condiciones' => $solicitudOnsite->terminos_condiciones,
                    'observaciones_internas' => $solicitudOnsite->observaciones_internas,
                    'nota_obra' => $solicitudOnsite->nota_cliente,
                    'comentarios' => $solicitudOnsite->comentarios,
                    'created_at' => $solicitudOnsite->created_at,
                );

                fputcsv($fp, $fila, ';');
            }
            $saltear = $saltear + 5000;
            $solicitudesOnsite = $this->listar($texto, $obraOnsiteId, $estadoSolicitudOnsiteId, $tipoSolicitudId, $pendientes, $saltear, $tomar);
        }

        fclose($fp);
    }

    public function generarXlsx($texto, $obraOnsiteId, $estadoSolicitudOnsiteId, $tipoSolicitudId, $pendientes)
    {
        $saltear = 0;
        $tomar = 1000;
        $solicitudesOnsite = true;

        $idUser = Auth::user()->id;
        $filename = "listado_solicitudonsite" . $idUser . ".xlsx";

        $data[] = [
            'ID',
            'OBRA_ONSITE_ID',
            'NOMBRE',
            'ESTADO_SOLICITUD_ONSITE_ID',
            'SOLICITUD_TIPO',
            'NOMBRE_ESTADO',
            'EMPRESA',
            'RESPONSABLE',
            'SISTEMA',
            'OBSERVACIONES_INTERNAS',
            'NOTA_CLIENTE',
            'COMENTARIOS',
            'created_at'
        ];

        $solicitudesOnsite = $this->listar($texto, $obraOnsiteId, $estadoSolicitudOnsiteId, $tipoSolicitudId, $pendientes, $saltear, $tomar);

        while ($solicitudesOnsite->count()) {
            foreach ($solicitudesOnsite as $solicitudOnsite) {
                $data[] = [
                    'id' => $solicitudOnsite->id,
                    'obra_onsite_id' => $solicitudOnsite->obra_onsite_id,
                    'nombreobraonsiteid' => $solicitudOnsite->nombreobraonsiteid,
                    'estado_solicitud_onsite_id' => $solicitudOnsite->estado_solicitud_onsite_id,
                    'solicitud_tipo' => $solicitudOnsite->nombre_tipo,
                    'nombreestadosolicitudonsiteid' => $solicitudOnsite->nombreestadosolicitudonsiteid,
                    'empresa' => $solicitudOnsite->empresa,
                    'responsable' => $solicitudOnsite->responsable,
                    'sistema' => (isset($solicitudOnsite->nombre_sistema) ? $solicitudOnsite->nombre_sistema : NULL),
                    'observaciones_internas' => $solicitudOnsite->observaciones_internas,
                    'nota_obra' => $solicitudOnsite->nota_cliente,
                    'comentarios' => $solicitudOnsite->comentarios,
                    'created_at' => $solicitudOnsite->created_at,
                ];
            }
            $saltear = $saltear + 5000;
            $solicitudesOnsite = $this->listar($texto, $obraOnsiteId, $estadoSolicitudOnsiteId, $tipoSolicitudId, $pendientes, $saltear, $tomar);
        }

        $excelController = new GenericExport($data, $filename);
        $excelController->export();
    }

    public function getDataShowConversorReparacionOnsite($id)
    {
        $solicitudOnSite = $this->findSolicitud($id);
        if ($solicitudOnSite && $solicitudOnSite->obra_onsite) {
            $datos['solicitudOnsite'] = $solicitudOnSite;

            $datos['obraOnsite'] = $solicitudOnSite->obra_onsite;
            $datos['obrasOnsite'] = $this->obraOnsiteService->getAllObrasOnsite();
            $datos['tecnicos'] = $this->userService->listarTecnicosOnsite(Session::get('userCompanyIdDefault'));
            $datos['obraChecklistOnsite'] = $solicitudOnSite->obra_onsite->obraChecklistOnsite;
            $datos['solicitudesTipos'] = $this->tiposSolicitudesService->getTiposSolicitudesAll();

            return $datos;
        } else {
            return false;
        }
    }

    public function getSolicitudes($company_id, $id)
    {
        $query = SolicitudOnsite::where('company_id', $company_id);

        if ($id !== null) {
            $query->where('id', $id);
        }

        $solicitudes = $query->get();

        $solicitudes->each(function ($solicitud) {
            $solicitud->makeHidden('company_id');
        });

        return $solicitudes;
    }

    public function getSolicitudesFull($company_id, $id)
    {
        $query = SolicitudOnsite::where('company_id', $company_id);

        if ($id !== null) {
            $query->where('id', $id);
        }

        $solicitudes = $query->get();

        $datosSolicitudes = [];

        foreach ($solicitudes as $solicitud) {
            $datosSolicitudes[] = [
                'id' => $solicitud->id,
                'nombre' => $solicitud->nombre,
                'obra_onsite_id' => $solicitud->obra_onsite_id,
                'obra_onsite' => ($solicitud->obra_onsite) ? $solicitud->obra_onsite->nombre : null,
                'estado_solicitud_id' => $solicitud->estado_solicitud_onsite_id,
                'nombre_estado' => ($solicitud->estado_solicitud_onsite) ? $solicitud->estado_solicitud_onsite->nombre : null,
                'terminos_condiciones' => $solicitud->terminos_condiciones,
                'observaciones_internas' => $solicitud->observaciones_internas,
                'nota_cliente' => $solicitud->nota_cliente,
                'comentarios' => $solicitud->comentarios,
                'created_at' => $solicitud->created_at
            ];
        }

        return $datosSolicitudes;
    }

    public function getSolicitudesPorSistema($company_id, $idSistemas)
    {
        $idSistemas = explode(',', $idSistemas);
        $solicitudes = [];

        foreach ($idSistemas as $sistemaId) {

            $sistema = SistemaOnsite::find($sistemaId);

            $query = SolicitudOnsite::where('company_id', $company_id)->where('sistema_onsite_id', $sistemaId)->with('tipo');

            $solicitudesList = $query->get();

            $solicitudesList->each(function ($solicitud) {
                $solicitud->makeHidden('company_id');
                $solicitud->makeHidden('empresa_instaladora_id');
                $solicitud->makeHidden('estado_solicitud_onsite_id');
                $solicitud->makeHidden('obra_onsite_id');
                $solicitud->makeHidden('updated_at');
                $solicitud->makeHidden('created_at');
                $solicitud->makeHidden('user_id');
            });

            $solicitudes[] = ['nombre' => $sistema->nombre, 'solicitud_onsite' => $solicitudesList];
        }

        return $solicitudes;
    }


    public function enviarMailEstado($solicitudOnsite)
    {

        if ($solicitudOnsite->estado_solicitud_onsite && $solicitudOnsite->obra_onsite) {
            if ($solicitudOnsite->estado_solicitud_onsite->plantilla_mail_cliente_id > 1 && $solicitudOnsite->obra_onsite->responsable_email) {

                $this->mailOnSiteService->enviarMailSolicitudObraOnsite(
                    $solicitudOnsite,
                    $solicitudOnsite->estado_solicitud_onsite->plantilla_mail_cliente_id,
                    $solicitudOnsite->obra_onsite->responsable_email
                );
            }
        }
    }


    private function enviarMailAdministrador($solicitudOnsite, $request)
    {
        $parametro = $this->parametrosService->findParametroPorNombre('MAIL_ONSITE_SOLICITUD_ADMIN');

        if ($parametro)
            $plantilla_mail_admin = $parametro->valor_numerico;
        else
            return redirect('SolicitudPuestaMarcha')->with('message-error', 'Parámetro de envío de email NO seteado. Consulte administrador.');


        if ($parametro)
            $parametro = $this->parametrosService->findParametroPorNombre('ONSITE_SOLICITUD_ADMIN_EMAIL_TO');
        else
            return redirect('SolicitudPuestaMarcha')->with('message-error', 'Parámetro de envío de email NO seteado. Consulte administrador.');

        $email_to = $parametro->valor_cadena;


        if ($plantilla_mail_admin > 1 && $email_to) {

            $this->mailOnSiteService->enviarMailSolicitudObraOnsite($solicitudOnsite, $plantilla_mail_admin, $email_to);
        }
    }

    public function procesarConversorVisita($request, $id)
    {
        Log::info('SolicitudOnsiteService - procesarConversorVisita');
        $solicitudOnsite = $this->findSolicitud($id);

        $sysdate = date('Y-m-d H:i:s');

        $obraOnsite = $solicitudOnsite->obra_onsite;

        $empresa_onsite_exists = $this->empresaOnsiteService->findEmpresaClave($obraOnsite->clave);

        $nombreParametro = 'MAIL_ONSITE_SOLICITUD_TECNICO';
        $parametro = $this->parametrosService->findParametroPorNombre($nombreParametro);
        $plantilla_mail_tecnico = $parametro->valor_numerico;

        $plantilla_mail_default = 1;

        $company = Session::get('userCompanyIdDefault');

        // Crear empresa onsite
        $empresaOnsite = null;
        $crearReparacionSeguimiento = false;
        $tipoTerminalSistema = 2;

        if ($empresa_onsite_exists) {
            $empresa_onsite_exists->update([

                'nombre'    =>  $obraOnsite->nombre,
                'tecnico_id' => $request['tecnico_id'],
                'plantilla_mail_asignacion_tecnico_id' => $plantilla_mail_tecnico,
                'responsable' => ($obraOnsite ? $obraOnsite->empresa_instaladora_responsable : ''),
                'email_responsable' => ($obraOnsite ? $obraOnsite->responsable_email : ''),
                'palntilla_mail_responsable' =>  $plantilla_mail_default,
                'tipo_terminales' => $tipoTerminalSistema
            ]);
            Log::info('SolicitudOnsiteService - procesarConversorVisita - $empresa_onsite_exists->update');

            $empresaOnsite =  $empresa_onsite_exists;
        } else {
            $datosEmpresaOnsite = array(
                'company_id' => $company,
                'clave'    => ($obraOnsite ? $obraOnsite->clave : ''),
                'nombre'    => ($obraOnsite ? $obraOnsite->nombre : ''),

                'tecnico_id' => $request['tecnico_id'],
                'plantilla_mail_asignacion_tecnico_id' =>  $plantilla_mail_tecnico,
                'responsable' => ($obraOnsite ? $obraOnsite->empresa_instaladora_responsable : ''),
                'email_responsable' => ($obraOnsite ? $obraOnsite->responsable_email : ''),
                'palntilla_mail_responsable' =>  $plantilla_mail_default,
                'requiere_tipo_conexion_local' => false,
                'generar_clave_reparacion' => true,
                'tipo_terminales' => $tipoTerminalSistema
            );

            $empresaOnsite = $this->empresaOnsiteService->store($datosEmpresaOnsite);
            Log::info('SolicitudOnsiteService - procesarConversorVisita - $this->empresaOnsiteService->store');

            $crearReparacionSeguimiento = true;
        }

        // Crear sucursal onsite
        $sucursalOnsite = null;
        $localidad_default = 1;


        if ($empresaOnsite->sucursales_onsite && $empresaOnsite->sucursales_onsite->count() > 0) {
            $sucursalOnsite = $empresaOnsite->sucursales_onsite->first();
        } else {
            $datos_sucursal = [
                'company_id' => $company,
                'codigo_sucursal' => ($obraOnsite ? $obraOnsite->clave : ''),
                'empresa_onsite_id' => $empresaOnsite->id,
                'razon_social' => ($obraOnsite ? $obraOnsite->nombre : ''),
                'localidad_onsite_id' => $localidad_default,
                'dirección' => ($obraOnsite ? $obraOnsite->domicilio : ''),
                'telefono_contacto' => ($obraOnsite ? $obraOnsite->responsable_telefono : ''),
                'nombre_contacto' => ($obraOnsite ? $obraOnsite->empresa_instaladora_nombre : '')
            ];
            $sucursalOnsite = $this->sucursalOnsiteService->store($datos_sucursal);
            Log::info('SolicitudOnsiteService - procesarConversorVisita - $this->sucursalOnsiteService->store');
        }

        // Consultar o Crear sistema onsite

        $sistemaOnsite = $this->sistemaOnsiteService->findSistemaOnsite($solicitudOnsite->sistema_onsite_id);

        if (!$sistemaOnsite || $sistemaOnsite == null) {
            $sistemaOnsite = null;

            if ($sucursalOnsite->sistemas_onsite && $sucursalOnsite->sistemas_onsite->count() > 0) {
                $sistemaOnsite = $sucursalOnsite->sistemas_onsite->first();
                Log::info('SolicitudOnsiteService - procesarConversorVisita - $sucursalOnsite->sistemas_onsite->count()');
            } else {
                $datosSistemaOnsite = array(
                    'company_id' => $company,
                    'empresa_onsite_id' => $empresaOnsite->id,
                    'sucursal_onsite_id' => $sucursalOnsite->id,
                    'nombre' => 'Indeterminado'
                );
                $sistemaOnsite = $this->sistemaOnsiteService->store($datosSistemaOnsite);
                Log::info('SolicitudOnsiteService - procesarConversorVisita - $this->sistemaOnsiteService->store');
            }
        }

        // Crear reparacion onsite - tipo servicio 50 SEGUIMIENTO OBRA
        $reparacionOnsiteSeguimiento = null;
        $terminalDefault = 1;

        $reparacionOnsiteSeguimiento = $this->visitasService->getReparacionEmpresaServicio($empresaOnsite->id, TipoServicioOnsite::SEGUIMIENTO_OBRA);

        if (!$reparacionOnsiteSeguimiento) {

            $datosReparacionOnsiteSeguimiento = array(
                'company_id' => $company,
                'clave' => 'E' . $empresaOnsite->id, //luego se actualiza
                'id_empresa_onsite' => $empresaOnsite->id,
                'sucursal_onsite_id' => $sucursalOnsite->id,
                'sistema_onsite_id' => $sistemaOnsite->id,
                'id_terminal' => $terminalDefault,
                'tarea' => 'Seguimiento de Obra',
                'tarea_detalle' => '<p>Seguimiento de Obra</p>',
                'id_tipo_servicio' => $this->tipoServicioOnsiteRepository::SEGUIMIENTO_OBRA,
                'id_estado' => $this->estadoOnSiteRepository::BGH_A_COORDINAR,
                'fecha_ingreso' => $sysdate,
                'usuario_id' => Auth::user()->id,
                'id_tecnico_asignado' => User::_TECHNICAL,
                'fecha_vencimiento' => $request['fecha_vencimiento'],
                'observacion_ubicacion' => $obraOnsite->domicilio,
                'doc_link1' => '/imagenes/reparaciones_onsite/' . $obraOnsite->esquema,
                'solicitud_id' => $solicitudOnsite->id
            );
            $arrayReparacion = new ReparacionOnsiteRequest($datosReparacionOnsiteSeguimiento);
            $reparacionOnsiteSeguimiento = $this->visitasService->store($arrayReparacion);
            Log::info('SolicitudOnsiteService - procesarConversorVisita - $this->visitasService->store');

            $reparacionOnsiteSeguimiento = $reparacionOnsiteSeguimiento['reparacionOnsite']; //el store devuelve un array con dos objetos

            /* SE GENERA CLAVE DE REPARACION */
            $this->visitasService->setReparacionOnsiteClave($empresaOnsite->id, $reparacionOnsiteSeguimiento->id);

            /*  checklist se crea desde el método store de reparacionOnsiteService */
            $reparacionChecklistOnsiteSeguimiento = ReparacionChecklistOnsite::create([
                'company_id' => $company,
                'reparacion_onsite_id' => $reparacionOnsiteSeguimiento->id,
                'alimentacion_definitiva' => false,
                'unidades_tension_definitiva' => false,
                'cable_alimentacion_comunicacion_seccion_ok' => false,
                'minimo_conexiones_frigorificas_exteriores' => false,
                'sistema_presurizado_41_5_kg' => false,
                'operacion_vacio' => false,
                'llave_servicio_odu_abiertos' => false,
                'carga_adicional_introducida' => false,
                'sistema_funcionando_15_min_carga_adicional' => false,
                'sistema_presurizado_41_5_kg_tiempo_horas' => 0,
            ]);
            Log::info('SolicitudOnsiteService - procesarConversorVisita - ReparacionChecklistOnsite::create');
        }

        // Crear reparacion onsite - tipo servicio 60 PUESTA EN MARCHA


        $datosReparacionOnsitePuestaMarcha = array(
            'company_id' => $company,
            'clave' => 'E' . $empresaOnsite->id, //luego se actualiza
            'id_empresa_onsite' => $empresaOnsite->id,
            'sucursal_onsite_id' => $sucursalOnsite->id,
            'sistema_onsite_id' => $sistemaOnsite->id,
            'id_terminal' => $terminalDefault,
            'tarea' => 'Puesta en Marcha',
            'tarea_detalle' => '<p>Puesta en Marcha</p>',
            'id_tipo_servicio' => $this->tipoServicioOnsiteRepository::PUESTA_MARCHA,
            'id_estado' => $this->estadoOnSiteRepository::BGH_A_COORDINAR,
            'fecha_ingreso' => $sysdate,
            'usuario_id' => Auth::user()->id,
            'reparacion_onsite_puesta_marcha_id' => $reparacionOnsiteSeguimiento->id,
            'id_tecnico_asignado' => $request['tecnico_id'],
            'fecha_vencimiento' => $request['fecha_vencimiento'],
            'observacion_ubicacion' => $obraOnsite->domicilio,
            'doc_link1' => '/imagenes/reparaciones_onsite/' . $obraOnsite->esquema,
            'solicitud_tipo_id' => $solicitudOnsite->solicitud_tipo_id,
            'solicitud_id' => $solicitudOnsite->id

        );

        $requestReparacionOnsitePuestaMarcha = new Request($datosReparacionOnsitePuestaMarcha);

        $reparacionOnsitePuestaMarcha = $this->visitasService->store($requestReparacionOnsitePuestaMarcha);
        Log::info('SolicitudOnsiteService - procesarConversorVisita - $this->visitasService->store');

        $reparacionOnsitePuestaMarcha = $reparacionOnsitePuestaMarcha['reparacionOnsite']; //el store devuelve un array con dos objetos
        /* GENERO CLAVE DE REPARACION */
        $this->visitasService->setReparacionOnsiteClave($empresaOnsite->id, $reparacionOnsitePuestaMarcha->id);

        $reparacionChecklistOnsitePuestaMarcha = ReparacionChecklistOnsite::create([
            'company_id' => $company,
            'reparacion_onsite_id' => $reparacionOnsitePuestaMarcha->id,
            'alimentacion_definitiva' => false,
            'unidades_tension_definitiva' => false,
            'cable_alimentacion_comunicacion_seccion_ok' => false,
            'minimo_conexiones_frigorificas_exteriores' => false,
            'sistema_presurizado_41_5_kg' => false,
            'operacion_vacio' => false,
            'llave_servicio_odu_abiertos' => false,
            'carga_adicional_introducida' => false,
            'sistema_funcionando_15_min_carga_adicional' => false,
            'sistema_presurizado_41_5_kg_tiempo_horas' => 0,
        ]);

        Log::info('SolicitudOnsiteService - procesarConversorVisita - ReparacionChecklistOnsite::create');

        $data = [
            'empresaOnsite' => $empresaOnsite,
            'sucursalOnsite' => $sucursalOnsite,
            'sistemaOnsite' => $sistemaOnsite,
            'reparacionOnsiteSeguimiento' => $reparacionOnsiteSeguimiento,
            'reparacionOnsitePuestaMarcha' => $reparacionOnsitePuestaMarcha
        ];

        return $data;
    }

    public function insertObraSolicitudOnsite(Request $request)
    {
        /* crea empresa instaladora */
        $datos_empresa_instaladora = [

            'company_id' =>     Session::get('userCompanyIdDefault'),
            'id_unificado' => 1,
            'nombre' => $request['empresa_instaladora_nombre'],
            'pais' => $request['pais'],
            'provincia_onsite_id' => ($request['provincia'] > 0 ? $request['provincia'] : 26),
            'localidad_onsite_id' => 1,
            'email' => $request['responsable_email'],
            'celular' => $request['responsable_telefono']

        ];

        $empresa_instaladora = $this->empresaInstaladoraService->store($datos_empresa_instaladora);

        /* crea empresa_onsite (empresa cliente) */


        $datos_empresa_onsite = [

            'company_id' =>     Session::get('userCompanyIdDefault'),
            'clave' => $this->obraOnsiteService->getClave($request['nombre']),
            'nombre' => $request['nombre'],
            'pais' => $request['pais'],
            'provincia_onsite_id' => ($request['provincia'] > 0 ? $request['provincia'] : 26),
            'localidad_onsite_id' => 1,
            'email_responsable' => '-',
            'requiere_tipo_conexion_local' => 0,
            'generar_clave_reparacion' => 0,

        ];

        $empresa_onsite = $this->empresaOnsiteService->store($datos_empresa_onsite);

        /* CREA EMPRESA_INSTLADORA_EMPRESA_ONSITE (PIVOT) */


        EmpresaInstaladoraEmpresaOnsite::create([
            'company_id' =>     Session::get('userCompanyIdDefault'),
            'empresa_instaladora_id' => $empresa_instaladora->id,
            'empresa_onsite_id' => $empresa_onsite->id
        ]);

        /* ******** */

        /* crea sucursal onsite */

        $datos_sucursal_onsite = [

            'company_id' =>     Session::get('userCompanyIdDefault'),
            'codigo_sucursal' => $this->obraOnsiteService->getClave($request['nombre']),
            'empresa_onsite_id' => $empresa_onsite->id,
            'localidad_onsite_id' => 1,
            'razon_social' => $request['nombre'],


        ];

        $sucursal_onsite = $this->sucursalOnsiteService->store($datos_sucursal_onsite);

        /*  */

        $request['sucursal_onsite_id'] = $sucursal_onsite->id;

        $request['empresa_onsite_id'] = $empresa_onsite->id;

        $request['empresa_instaladora_id'] = $empresa_instaladora->id;

        /* crea obra onsite */
        $obraOnsite = $this->obraOnsiteService->store($request);
        $sucursalOnsite = SucursalOnsite::where('empresa_onsite_id', $obraOnsite->empresa_onsite_id)->first();

        $idUser = 399;

        /* crea sistema_onsite */

        $sistemaOnsite = SistemaOnsite::create(
            [
                'obra_onsite_id' => $obraOnsite->id,
                'empresa_onsite_id' => (!is_null($obraOnsite->empresa_onsite_id) ? $obraOnsite->empresa_onsite_id :  1),
                'sucursal_onsite_id' => (($sucursalOnsite) ? $sucursalOnsite->id :  1),
                'nombre' => 'SIS(1) - ' . $obraOnsite->nombre
            ]
        );

        /* crea comprador onsite  */

        $datos_comprador = [
            'company_id' => 2,
            'primer_nombre' => $request['nombre_comprador'],
            'nombre' => $request['nombre_comprador'] . ', ' . $request['apellido_comprador'],
            'apellido' => $request['apellido_comprador'],
            'dni' => $request['dni_comprador'],
            'pais' => $request['pais'],
            'provincia_onsite_id' => 26,
            'localidad_onsite_id' => 1,
            'domicilio' => $request['domicilio_obra'],
            'email' => $request['responsable_email'],
            'celular' => $request['responsable_telefono'],

        ];
        $compadorOnsite = $this->compradorOnsiteService->storeCompradorOnsite($datos_comprador);

        /* actualizo sistema_onsite con datos del comprador creado */
        $sistemaOnsite->comprador_onsite_id = $compadorOnsite->id;
        $sistemaOnsite->save();


        /* crea solicitud onsite */
        $request['obra_onsite_id'] = $obraOnsite->id;
        $request['sistema_onsite_id']  = $sistemaOnsite->id;
        $request['user_id']  = $idUser;
        $request['empresa_instaladora_id']  = $empresa_instaladora->id;

        $solicitudOnsite = $this->store($request);

        return $solicitudOnsite;
    }

    public function insertObraSolicitudOnsiteSpeedUp(Request $request)
    {
        $idObra = $request->get('obra_onsite_id');

        if ($idObra == 0) {
            $obraOnsite = $this->obraOnsiteService->store($request);
        } else {
            $obraOnsite = $this->obraOnsiteService->update($request, $idObra);
        }

        $solicitudOnsite = $this->store($request);
        return $solicitudOnsite;
    }

    public static function listar($texto, $obraOnsiteId, $estadoSolicitudOnsiteId, $tipoSolicitudId, $mostrar_todas = true, $saltear, $tomar)
    {
        $perfilAdmin = Session::get('perfilAdmin');
        $perfilAdminOnsite = Session::get('perfilAdminOnsite');

        $consulta = SolicitudOnsite::join('obras_onsite', 'obras_onsite.id', '=', 'solicitudes_onsite.obra_onsite_id')
            ->join('estados_solicitudes_onsite', 'estados_solicitudes_onsite.id', '=', 'solicitudes_onsite.estado_solicitud_onsite_id')

            ->join('users', 'users.id', '=', 'solicitudes_onsite.user_id')
            ->join('empresas_instaladoras_onsite', 'empresas_instaladoras_onsite.id', '=', 'solicitudes_onsite.empresa_instaladora_id')
            ->join('solicitudes_tipos', 'solicitudes_tipos.id', '=', 'solicitudes_onsite.solicitud_tipo_id')
            ->join('sistemas_onsite', 'sistemas_onsite.id', '=', 'solicitudes_onsite.sistema_onsite_id')

            ->select('solicitudes_onsite.*')
            ->selectRaw('obras_onsite.nombre as nombreobraonsiteid, obras_onsite.empresa_instaladora_nombre as empresa, obras_onsite.empresa_instaladora_responsable as responsable')
            ->selectRaw('estados_solicitudes_onsite.nombre as nombreestadosolicitudonsiteid')

            ->selectRaw('users.name as nombre_user')
            ->selectRaw('empresas_instaladoras_onsite.nombre as nombre_empresa')
            ->selectRaw('solicitudes_tipos.nombre as nombre_tipo')
            ->selectRaw('sistemas_onsite.nombre as nombre_sistema')
            ->selectRaw('sistemas_onsite.id as id_sistema')



            ->where('solicitudes_onsite.company_id',  Session::get('userCompanyIdDefault'));


        if (!$perfilAdmin && !$perfilAdminOnsite) {
            $idEmpresa_instaladora = Auth::user()->empresa_instaladora[0]->id;
            $consulta->where('solicitudes_onsite.empresa_instaladora_id',  $idEmpresa_instaladora);
        }

        //para forzar acá la clausula Where
        if (!empty($texto)) {
            $consulta->whereRaw(empty($texto) ? " 1 " : " CONCAT( COALESCE(solicitudes_onsite.id,''), ' ', COALESCE(solicitudes_onsite.observaciones_internas,''), ' ', COALESCE(solicitudes_onsite.nota_cliente,''), ' ', COALESCE(solicitudes_onsite.comentarios,''), ' ', COALESCE(obras_onsite.nombre,''), ' ', COALESCE(obras_onsite.empresa_instaladora_nombre,''), ' ', COALESCE(sistemas_onsite.nombre,''), ' ', COALESCE(solicitudes_tipos.nombre,''), ' ', COALESCE(obras_onsite.empresa_instaladora_responsable,'')) like '%$texto%'");
        }
        if (!empty($obraOnsiteId)) {
            $consulta->whereRaw(empty($obraOnsiteId) ? "1" : " solicitudes_onsite.obra_onsite_id = $obraOnsiteId ");
        }
        if (!empty($estadoSolicitudOnsiteId)) {
            $consulta->whereRaw(empty($estadoSolicitudOnsiteId) ? "1" : " solicitudes_onsite.estado_solicitud_onsite_id = $estadoSolicitudOnsiteId ");
        }
        if (!empty($tipoSolicitudId)) {
            $consulta->whereRaw(empty($tipoSolicitudId) ? "1" : " solicitudes_onsite.solicitud_tipo_id = $tipoSolicitudId ");
        }
        if (isset($mostrar_todas) && $mostrar_todas == 0) {
            $consulta->whereRaw('estados_solicitudes_onsite.pendiente = 0');
        }

        $consulta = $consulta->orderBy('solicitudes_onsite.id', 'desc');

        if ($tomar) {
            return $consulta->skip($saltear)->take($tomar)->get();
        } else {
            return $consulta->paginate(100);
        }
    }

    public static function listado()
    {
        return SolicitudOnsite::orderBy('nombre', 'asc')
            ->pluck('nombre', 'id');
    }

    public function findSolicitud($id)
    {
        $company_id = Session::get('userCompanyIdDefault');
        $solicitudOnsite = SolicitudOnsite::where('company_id', $company_id)->find($id);
        return $solicitudOnsite;
    }

    public function enviarMailSolicitudOnsite($request)
    {
        $this->mailOnSiteService->enviarMailSolicitudOnsite($request);
    }

    public function listarSolicitudesOnsitePorEmpresaUser()
    {

        $solicitudesOnsite = SolicitudOnsite::with('obra_onsite')
            ->with('estado_solicitud_onsite')
            ->whereHas('obra_onsite', function ($query) {
                $idUser = session()->get('idUser');
                $user = $this->userService->findUser($idUser);
                $clave_empresa = $user->empresas_onsite[0]->clave;

                $query->where('clave', $clave_empresa);
            });



        return $solicitudesOnsite->paginate(100);
    }
}
