<?php

namespace App\Services\Onsite;

use App\Enums\Prioridad;
use App\Enums\PuestaMarchaSatisfactoriaEnum;
use App\Http\Requests\Onsite\HistorialEstadoOnsiteRequest;
use App\Models\Company;
use App\Models\Onsite\ReparacionChecklistOnsite;
use App\Models\Onsite\ReparacionDetalle;
use App\Models\Onsite\ReparacionOnsite;
use App\Models\Onsite\SolicitudVisita;
use App\Models\Onsite\TipoServicioOnsite;
use App\Models\TemplateComprobante;
use App\Models\User;
use App\Repositories\Onsite\EstadoOnsiteRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Repositories\Onsite\HistorialEstadoOnsiteRepository;
use Illuminate\Support\Facades\DB;
use App\Exports\GenericExport;


class VisitasService
{
    protected $tiposServiciosService;
    protected $solicitudesTiposServices;
    protected $empresaOnsiteService;
    protected $obrasOnsiteService;
    protected $empresasInstaladorasService;
    protected $sucursalOnsiteService;
    protected $localidadOnsiteService;
    protected $provinciasService;
    protected $historialEstadoService;
    protected $parametrosService;
    protected $mailOnsiteService;
    protected $sistemaOnsiteService;
    protected $tiposImagenOnsiteService;
    protected $imagenOnsiteService;
    protected $templateComprobanteService;
    protected $userService;
    protected $solicitudVisitaService;
    protected $estado_onsite_repository;
    protected $historial_estado_onsite_repository;
    protected $userCompanyId;

    public function __construct(
        TiposServiciosService $tiposServiciosService,
        SolicitudesTiposService $solicitudesTiposServices,
        EmpresaOnsiteService $empresaOnsiteService,
        ObrasOnsiteService $obrasOnsiteService,
        EmpresasInstaladorasServices $empresasInstaladorasService,
        LocalidadOnsiteService $localidadOnsiteService,
        SucursalOnsiteService $sucursalOnsiteService,
        ProvinciasService $provinciasService,
        HistorialEstadosOnsiteService $historialEstadoService,
        ParametroService $parametrosService,
        MailOnsiteService $mailOnsiteService,
        SistemaOnsiteService $sistemaOnsiteService,
        ImagenesOnsiteService $imagenOnsiteService,
        TiposImageOnsiteService $tiposImagenOnsiteService,
        TemplatesService $templateComprobanteService,
        UserService $userService,
        SolicitudVisitaService $solicitudVisitaService,
        EstadoOnsiteRepository $estado_onsite_repository,
        HistorialEstadoOnsiteRepository $historial_estado_onsite_repository

    ) {
        $this->tiposServiciosService = $tiposServiciosService;
        $this->solicitudesTiposServices = $solicitudesTiposServices;
        $this->empresaOnsiteService = $empresaOnsiteService;
        $this->obrasOnsiteService = $obrasOnsiteService;
        $this->empresasInstaladorasService = $empresasInstaladorasService;
        $this->localidadOnsiteService = $localidadOnsiteService;
        $this->sucursalOnsiteService = $sucursalOnsiteService;
        $this->provinciasService = $provinciasService;
        $this->historialEstadoService = $historialEstadoService;
        $this->parametrosService = $parametrosService;
        $this->mailOnsiteService = $mailOnsiteService;
        $this->sistemaOnsiteService = $sistemaOnsiteService;
        $this->tiposImagenOnsiteService = $tiposImagenOnsiteService;
        $this->imagenOnsiteService = $imagenOnsiteService;
        $this->templateComprobanteService = $templateComprobanteService;
        $this->userService = $userService;
        $this->solicitudVisitaService = $solicitudVisitaService;
        $this->estado_onsite_repository = $estado_onsite_repository;
        $this->historial_estado_onsite_repository = $historial_estado_onsite_repository;
    }

    public function getDataIndex()
    {
        $userCompanyId = Session::get('userCompanyIdDefault');
        $userId = Auth::user()->id;
        $listarSoloEstadosActivos = true;
        $sinPosnet = true;

        $datos['sysdate'] = date('Y-m-d H:i:s');
        $datos['user_id'] = $userId;
        $datos['tiposServicios'] = $this->tiposServiciosService->listado($userCompanyId);
        $datos['solicitudesTipos'] = $this->solicitudesTiposServices->getAllSolicitudesTipos();
        $datos['estadosOnsite'] = $this->estado_onsite_repository->listado($userCompanyId);
        $datos['tecnicosOnsite'] = $this->userService->listarTecnicosOnsite($userCompanyId);
        $datos['empresasOnsite'] = $this->empresaOnsiteService->listado($userCompanyId);

        $datos['estados_activo'] = $listarSoloEstadosActivos;
        $datos['historialEstadosOnsite'] = array();

        /* $this->generarCsv($userCompanyId, null, null, null, null, null, null, null, $listarSoloEstadosActivos, null, $sinPosnet, null, null, $userId); */

        $params = [
            'userCompanyId' => $userCompanyId,
            'estadosActivos' => $listarSoloEstadosActivos,
            'sinPosnet' => $sinPosnet,
        ];

        $datos['reparacionesOnsite'] = $this->listar($params);

        $datos['ruta'] = 'visitasOnsite';

        return $datos;
    }

    public function listar($params)
    {
        $consulta = ReparacionOnsite::where('reparaciones_onsite.company_id', $params['userCompanyId'])
            ->where('reparaciones_onsite.id_tecnico_asignado', '<>', User::_TECHNICAL);

        if (!empty($params['texto'])) {
            $consulta->whereRaw(" CONCAT( reparaciones_onsite.id , ' ', reparaciones_onsite.clave, ' ', reparaciones_onsite.tarea ) like '%" . $params['texto'] . "%'");
        }

        if (isset($params['sistema_onsite_id']) && $params['sistema_onsite_id']) {
            $consulta->where('reparaciones_onsite.sistema_onsite_id', $params['sistema_onsite_id']);
        }
        if (isset($params['id_estado']) && $params['id_estado']) {
            $consulta->where('reparaciones_onsite.id_estado', $params['id_estado']);
        }
        if (isset($params['id_tipo_servicio']) && $params['id_tipo_servicio']) {
            $consulta->where('reparaciones_onsite.id_tipo_servicio', $params['id_tipo_servicio']);
        }
        if (isset($params['id_tecnico']) && $params['id_tecnico']) {
            $consulta->where('reparaciones_onsite.id_tecnico_asignado', $params['id_tecnico']);
        }

        if (isset($params['fecha_vencimiento_desde']) && $params['fecha_vencimiento_desde']) {
            $consulta->where('reparaciones_onsite.fecha_vencimiento', '>=', date('Y/m/d H:i:s', (strtotime($params['fecha_vencimiento_desde'] . ' 00:00:00'))));
        }
        if (isset($params['fecha_vencimiento_hasta']) && $params['fecha_vencimiento_hasta']) {
            $consulta->where('reparaciones_onsite.fecha_vencimiento', '<=', date('Y/m/d H:i:s', (strtotime($params['fecha_vencimiento_hasta'] . ' 23:59:59'))));
        }

        if (isset($params['fecha_ingreso_desde']) && $params['fecha_ingreso_desde']) {
            $consulta->where('reparaciones_onsite.fecha_ingreso', '>=', date('Y/m/d H:i:s', (strtotime($params['fecha_ingreso_desde'] . ' 00:00:00'))));
        }
        if (isset($params['fecha_ingreso_hasta']) && $params['fecha_ingreso_hasta']) {
            $consulta->where('reparaciones_onsite.fecha_ingreso', '<=', date('Y/m/d H:i:s', (strtotime($params['fecha_ingreso_hasta'] . ' 23:59:59'))));
        }

        $consulta->orderBy('reparaciones_onsite.id', 'desc');

        if (!empty($params['tomar']))
            return $consulta->skip($params['saltear'])->take($params['tomar'])->get();
        else
            return $consulta->paginate(100);
    }

    public function getDataIndexVisitas()
    {
        $userCompanyId = Company::COMPANY_BGH;
        if (Session::has('userCompanyIdDefault')) {
            $userCompanyId = Session::get('userCompanyIdDefault');
        }

        $obrasOnsite = $this->obrasOnsiteService->getAllObrasOnsite();

        $empresasInstaladoras = $this->empresasInstaladorasService->getEmpresasInstaladoras();

        $datos = $this->getData($userCompanyId);

        $datos['sysdate'] = date('Y-m-d H:i:s');

        $datos['historialEstadosOnsite'] = array();

        //$tipoServicioArrayInclude = [TipoServicioOnsite::PUESTA_MARCHA, TipoServicioOnsite::SEGUIMIENTO_OBRA];
        $tipoServicioArrayInclude = null;

        $params = [
            'userCompanyId' => $userCompanyId,
            'tipoServicioArrayInclude' => $tipoServicioArrayInclude,
        ];

        $datos['reparacionesOnsite'] = $this->listar($params);
        $datos['estados_activo'] = true;
        $datos['ruta'] = 'visitasOnsite';
        $datos['user_id'] = Auth::user()->id;

        $datos['obrasOnsite'] = $obrasOnsite;
        $datos['empresasInstaladoras'] = $empresasInstaladoras;

        $datos['tiposServicios'] = $this->tiposServiciosService->listado($userCompanyId);
        $datos['solicitudesTipos'] = $this->solicitudesTiposServices->getAllSolicitudesTipos();
        $datos['estadosOnsite'] = $this->estado_onsite_repository->listado($userCompanyId);
        $datos['tecnicosOnsite'] = $this->userService->listarTecnicosOnsite($userCompanyId);

        return $datos;
    }

    public function getData($userCompanyId)
    {

        $datos['empresasOnsite'] = $this->empresaOnsiteService->listadoAllBgh($userCompanyId);
        $datos['tiposServicios'] = $this->tiposServiciosService->listado($userCompanyId);
        $datos['solicitudesTipos'] = $this->solicitudesTiposServices->getAllSolicitudesTipos();
        $datos['estadosOnsite'] = $this->estado_onsite_repository->listado($userCompanyId);
        $datos['tecnicosOnsite'] = $this->userService->listarTecnicosOnsite($userCompanyId);
        $datos['localidadesOnsite'] = $this->localidadOnsiteService->listadoAll($userCompanyId);

        return $datos;
    }


    public function store(Request $request)
    {
        Log::info("visitasService - store");
        Log::info($request);

        $userCompanyId = Session::get('userCompanyIdDefault');

        $sysdate = date('Y-m-d H:i:s');

        $request['company_id'] = $userCompanyId;

        if (!$request['clave']) {
            $request['clave'] = $this->getClaveReparacionOnsite($request);
        }

        $sucursalOnsite = $this->sucursalOnsiteService->findSucursal($request['sucursal_onsite_id']);
        $localidadOnsite = $this->localidadOnsiteService->getLocalidad($sucursalOnsite->localidad_onsite_id);

        if ($localidadOnsite)
            $provinciaOnsite = $this->provinciasService->findProvinciaOnsite($localidadOnsite->id_provincia);
        else $provinciaOnsite = null;

        if ($request['id_tecnico_asignado']) {

            $idTecnicoAsignado = $request['id_tecnico_asignado'];
            $tecnicoAsignado = User::find($request['id_tecnico_asignado']);
        } else {

            $tecnicoAsignado = $sucursalOnsite->tecnicosOnsite()->first();

            if ($tecnicoAsignado) {
                $idTecnicoAsignado = $tecnicoAsignado->id;
            }
            if (!$tecnicoAsignado && $localidadOnsite) {
                $tecnicoAsignado = User::find($localidadOnsite->id_usuario_tecnico);
                $idTecnicoAsignado = $tecnicoAsignado->id;
            }

            if (!$tecnicoAsignado) {
                $tecnicoAsignado = User::find(User::_TECHNICAL);
                $idTecnicoAsignado = $tecnicoAsignado->id;
            }
        }


        if ($provinciaOnsite && $localidadOnsite && $sucursalOnsite)
            $ubicacion = $provinciaOnsite->nombre . ' / ' . $localidadOnsite->localidad . ' / ' . $sucursalOnsite->direccion . ' / ' . $sucursalOnsite->telefono_contacto;

        else $ubicacion = '';

        $request['observacion_ubicacion'] = $ubicacion;
        $request['fecha_ingreso'] = $sysdate;
        $request['id_tecnico_asignado'] = $idTecnicoAsignado;

        $request['sla_status'] = 'IN';

        if ($request['id_estado'] == 4) {
            $request['fecha_cerrado'] = $sysdate;

            if ($request['fecha_cerrado'] > $request['fecha_vencimiento'])
                $request['sla_status'] = 'OUT';
        }
        if (!$request['id_terminal']) {
            $request['id_terminal'] = 1;
        }

        $reparacionOnsite = ReparacionOnsite::create($request->all());
        $request['reparacion_id'] = $reparacionOnsite->id;
        $reparacionOnsiteDetalles = ReparacionDetalle::create($request->all());

        //agrega un registro en reparacion_checklist_onsite
        $camposChecklist['reparacion_onsite_id'] = $reparacionOnsite->id;
        $camposChecklist['company_id'] = $reparacionOnsite->company_id;


        $estado = $request['id_estado']; //1; //nuevo
        $observacion = 'Reparación creada Manualmente';
        $idUsuario = Auth::user()->id;

        $nuevoHistorialEstadoOnsite = array(
            'company_id' => $userCompanyId,
            'id_reparacion' => $reparacionOnsite->id,
            'id_estado' => $estado,
            'fecha' => $sysdate,
            'observacion' => $observacion,
            'id_usuario' => $idUsuario,
            'visible' => true,
        );

        $requestHistroalEstadoOnsite = new HistorialEstadoOnsiteRequest($nuevoHistorialEstadoOnsite);
        $this->historialEstadoService->store($requestHistroalEstadoOnsite);

        Log::info($request);

        if ($request['solicitud_id']) {
            $dataSolicitudVisita = [
                'visita_id' => $reparacionOnsite->id,
                'solicitud_id' => $request['solicitud_id']
            ];

            $newSolicitudVisita = $this->solicitudVisitaService->store($dataSolicitudVisita);
        }

        /* se envían mails */
        $this->enviarMailTecnicoOnsiteAsignado($reparacionOnsite);

        $datos['reparacionOnsite'] = $reparacionOnsite;
        $datos['tecnicoAsignado'] = $tecnicoAsignado;

        return $datos;
    }

    public function getClaveReparacionOnsite(Request $request)
    {
        $idEmpresa = '';

        if ($request['id_empresa_onsite'] < 10) {
            $idEmpresa = '0' . $request['id_empresa_onsite'];
        } else {
            $idEmpresa = $request['id_empresa_onsite'];
        }

        $reparacionOnsiteLast = $this->getUltimaReparacion();

        $reparacionOnsiteId = 1;
        if ($reparacionOnsiteLast) {
            $reparacionOnsiteId = ($reparacionOnsiteLast->id + 1);
        }

        $clave = 'E' . $idEmpresa . $reparacionOnsiteId;

        return $clave;
    }

    private function enviarMailTecnicoOnsiteAsignado($reparacionOnsite)
    {
        $tecnicoAsignado = User::find($reparacionOnsite->id_tecnico_asignado);
        $parametroMail = $this->parametrosService->findParametroPorNombre('MAIL_ONSITE_TECNICO');

        if ($parametroMail && $parametroMail->valor_numerico > 1) {
            $this->mailOnsiteService->enviarMailOnsite($reparacionOnsite, $parametroMail->valor_numerico, $tecnicoAsignado->email);
        }
    }

    public function getUltimaReparacion()
    {
        $ultimaReparacion = ReparacionOnsite::orderBy('id', 'DESC')->first();

        return $ultimaReparacion;
    }

    public function filtrarVisitas(Request $request)
    {
        $datosRequest = [
            'texto' => $request->get('texto'),
            'id_estado' => $request->get('id_estado'),
            'sistema_onsite_id' => $request->get('sistema_onsite_id'),
            //obras
            'id_tipo_servicio' => $request->get('id_tipo_servicio'),
            'id_tecnico' => $request->get('id_tecnico'),
            'fecha_ingreso_desde' => $request->get('fecha_ingreso_desde'),
            'fecha_ingreso_hasta' => $request->get('fecha_ingreso_hasta'),
            'fecha_vencimiento_desde' => $request->get('fecha_vencimiento_desde'),
            'fecha_vencimiento_hasta' => $request->get('fecha_vencimiento_hasta'),
            'boton_filtrar' => $request->get('boton_filtrar'),
        ];

        $userCompanyId = Company::COMPANY_BGH;
        if (Session::has('userCompanyIdDefault')) {
            $userCompanyId = Session::get('userCompanyIdDefault');
        }
        $datosRequest['userCompanyId'] = $userCompanyId;
        $datosRequest['user_id'] = Auth::user()->id;

        $obrasOnsite = $this->obrasOnsiteService->getAllObrasOnsite();

        $empresasInstaladoras = $this->empresasInstaladorasService->getEmpresasInstaladoras();

        $datosCompany = $this->getData($userCompanyId);

        $datos = array_merge($datosRequest, $datosCompany);

        $datos['sysdate'] = date('Y-m-d H:i:s');

        $datos['historialEstadosOnsite'] = array();

        //$tipoServicioArrayInclude = [TipoServicioOnsite::PUESTA_MARCHA, TipoServicioOnsite::SEGUIMIENTO_OBRA];

        $datos['estados_activo'] = true;
        $datos['ruta'] = 'visitasOnsite';


        $datos['obrasOnsite'] = $obrasOnsite;
        $datos['empresasInstaladoras'] = $empresasInstaladoras;

        $datos['reparacionesOnsite'] = $this->listar($datosRequest);

        if ($datos['boton_filtrar'] == 'csv') {
            $this->generarXlsx(
                $userCompanyId,
                $datos['texto'],
                $datos['id_estado'],
                $datos['sistema_onsite_id'],
                $datos['id_tipo_servicio'],
                $datos['id_tecnico'],
                $datos['fecha_ingreso_desde'],
                $datos['fecha_ingreso_hasta'],
                $datos['fecha_vencimiento_desde'],
                $datos['fecha_vencimiento_hasta'],
                $datos['user_id'],
            );
        }

        return $datos;
    }

    public function findVisita($id)
    {
        $company_id = Session::get('userCompanyIdDefault');

        $reparacionOnsite = ReparacionOnsite::with('reparacion_checklist_onsite')
            ->where('company_id', $company_id)->find($id);

        return $reparacionOnsite;
    }

    public function getDataEdit($id)
    {
        $userCompanyId = null;
        $userCompaniesId = [];

        if (Session::has('userCompanyIdDefault')) {
            $userCompanyId = Session::get('userCompanyIdDefault');
        } else {
            Session::flash('message-error', 'Sin Privilegios (user sin company asignada)');
            return redirect('/visitasOnsite');
        }


        if (Session::has('userCompaniesId')) {
            $userCompaniesId = Session::get('userCompaniesId');
        } else {
            Session::flash('message-error', 'Sin Privilegios (user sin company asignada)');
            return redirect('/visitasOnsite');
        }

        $reparacionOnsite = $this->findVisita($id);

        if ($reparacionOnsite) {
            $datos['reparacionOnsite'] = $reparacionOnsite;

            // Validar que el usuario sea de la misma compañia que la terminal

            if (!in_array($reparacionOnsite->company_id,  $userCompaniesId)) {
                Session::flash('message-error', 'Sin Privilegios');
                return redirect('/visitasOnsite');
            }


            $datos['empresasOnsite'] = $this->empresaOnsiteService->listadoAllBgh($userCompanyId);

            $datos['sucursalesOnsite'] = $this->sucursalOnsiteService->findSucursales($reparacionOnsite->sucursal_onsite_id);
            /* $datos['sistemasOnsite'] = $this->sistemaOnsiteService->getSistemasOnsiteAll(); */

            $datos['sistemasOnsite'] = [];

            if (isset($reparacionOnsite) && isset($reparacionOnsite->sistema_onsite)) {
                /* if(count($reparacionOnsite->sistema_onsite)>0) */
                $datos['sistemasOnsite'] = $this->sistemaOnsiteService->getSistemasPorObra($reparacionOnsite->sistema_onsite->obra_onsite_id);
            }




            $tiposServicios = null;

            if (in_array($reparacionOnsite->id_tipo_servicio, [TipoServicioOnsite::PUESTA_MARCHA, TipoServicioOnsite::SEGUIMIENTO_OBRA])) {
                $tiposServicios = $this->tiposServiciosService->listadoPuestaMarcha($userCompanyId);
            } else {
                $tiposServicios = $this->tiposServiciosService->listado($userCompanyId);
            }


            $datos['tiposServicios'] = $tiposServicios;
            $datos['solicitudesTipos'] = $this->solicitudesTiposServices->getAllSolicitudesTipos();
            $datos['estadosOnsite'] = $this->estado_onsite_repository->listado($userCompanyId);
            $datos['tecnicosOnsite'] = $this->userService->listarTecnicosOnsite($userCompanyId);

            $sucursalOnsite = $this->sucursalOnsiteService->findSucursal($reparacionOnsite->sucursal_onsite_id);
            if ($sucursalOnsite)
                $localidad = $this->localidadOnsiteService->getLocalidad($sucursalOnsite->localidad_onsite_id);
            else $localidad = $this->localidadOnsiteService->getLocalidad(1); //desconocida en producción



            //$datos['historialEstadosOnsite'] = $this->historial_estado_onsite_repository->getHistorialPorReparacionOnsite($reparacionOnsite->id)->get();
            $datos['historialEstadosOnsite'] = $this->historialEstadoService->getHistorialEstadosNotas($reparacionOnsite->id);


            $datos['localidadesOnsite'] = $this->localidadOnsiteService->listadoAll($userCompanyId);

            $datos['tiposImagenOnsite'] = $this->tiposImagenOnsiteService->getTiposImagenOnsiteAll();

            $datos['terminalOnsite'] = array();
            $datos['sucursalOnsite'] = array();

            $datos['reparacionesChecklistOnsite'] = ReparacionChecklistOnsite::where('reparacion_onsite_id', $reparacionOnsite->id)->first();
            //$reparacionesChecklistOnsite = $this->reparacionChecklistOnsiteService->findPorReparacion($reparacionOnsite->id);

            $datos['prioridades'] = Prioridad::getOptions();
            $datos['companyId'] = $userCompanyId;

            /* Unidades Interiores y Exteriores del Sistema a editar */

            $sistemaOnsiteReparacion = $this->sistemaOnsiteService->findSistemaOnsite($reparacionOnsite->sistema_onsite_id);



            $datos['sistemaOnsiteReparacion'] = $sistemaOnsiteReparacion;

            $datos['obras'] = $this->obrasOnsiteService->listar(null, $userCompanyId, null, null);

            $datos['puestaMarchaSatisfactoriaEnumOptions'] = PuestaMarchaSatisfactoriaEnum::getOptions();

            return $datos;
        } else {
            return false;
        }
    }

    public function update($request, $idReparacionOnsite)
    {
        $userCompanyId = Session::get('userCompanyIdDefault');
        $sysdate = date('Y-m-d H:i:s');
        $ruta = $request['ruta'];

        $reparacionOnsite = $this->findVisita($idReparacionOnsite);

        $oldEstado = $reparacionOnsite->id_estado;
        $newEstado = $request['id_estado'];

        $oldTecnico = $reparacionOnsite->id_tecnico_asignado;
        $newTecnico = $request['id_tecnico_asignado'];

        $request['sla_justificado'] = $request['sla_justificado'] ? 1 : 0;
        $request['liquidado_proveedor'] = $request['liquidado_proveedor'] ? 1 : 0;
        $request['visible_cliente'] = $request['visible_cliente'] ? 1 : 0;
        $request['chequeado_cliente'] = $request['chequeado_cliente'] ? 1 : 0;
        $request['problema_resuelto'] = $request['problema_resuelto'] ? 1 : 0;

        //$request['cableado'] = $request['cableado'] ? 1 : 0;
        //$request['instalacion_cartel'] = $request['instalacion_cartel'] ? 1 : 0;
        //$request['instalacion_cartel_luz'] = $request['instalacion_cartel_luz'] ? 1 : 0;

        $request['instalacion_buzon'] = $request['instalacion_buzon'] ? 1 : 0;
        $request['requiere_nueva_visita'] = $request['requiere_nueva_visita'] ? 1 : 0;

        /*campos checklist*/
        $camposChecklist['alimentacion_definitiva'] = $request['alimentacion_definitiva'] ? 1 : 0;
        $camposChecklist['unidades_tension_definitiva'] = $request['unidades_tension_definitiva'] ? 1 : 0;
        $camposChecklist['cable_alimentacion_comunicacion_seccion_ok'] = $request['cable_alimentacion_comunicacion_seccion_ok'] ? 1 : 0;
        $camposChecklist['minimo_conexiones_frigorificas_exteriores'] = $request['minimo_conexiones_frigorificas_exteriores'] ? 1 : 0;
        $camposChecklist['sistema_presurizado_41_5_kg'] = $request['sistema_presurizado_41_5_kg'] ? 1 : 0;
        $camposChecklist['operacion_vacio'] = $request['operacion_vacio'] ? 1 : 0;
        $camposChecklist['llave_servicio_odu_abiertos'] = $request['llave_servicio_odu_abiertos'] ? 1 : 0;
        $camposChecklist['carga_adicional_introducida'] = $request['carga_adicional_introducida'] ? 1 : 0;
        $camposChecklist['sistema_funcionando_15_min_carga_adicional'] = $request['sistema_funcionando_15_min_carga_adicional'] ? 1 : 0;
        $camposChecklist['puesta_marcha_satisfactoria'] = $request['puesta_marcha_satisfactoria'] ? $request['puesta_marcha_satisfactoria'] : 0;
        $camposChecklist['company_id'] = $userCompanyId;
        $camposChecklist['reparacion_onsite_id'] = $reparacionOnsite->id;

        $camposChecklist['sistema_presurizado_41_5_kg_tiempo_horas'] = $request['sistema_presurizado_41_5_kg_tiempo_horas'] ? $request['sistema_presurizado_41_5_kg_tiempo_horas'] : 0;
        $camposChecklist['unidad_exterior_tension_12_horas'] = $request['unidad_exterior_tension_12_horas'] ? $request['unidad_exterior_tension_12_horas'] : 0;
        $camposChecklist['carga_adicional_introducida_kg_final'] = $request['carga_adicional_introducida_kg_final'] ? $request['carga_adicional_introducida_kg_final'] : 0;
        $camposChecklist['carga_adicional_introducida_kg_adicional'] = $request['carga_adicional_introducida_kg_adicional'] ? $request['carga_adicional_introducida_kg_adicional'] : 0;
        /*fin campos checklist*/

        if ($oldEstado != $newEstado) {
            if ($newEstado == 4) {
                $request['fecha_cerrado'] = $sysdate;
                if ($request['fecha_cerrado'] <= $request['fecha_vencimiento'])
                    $request['sla_status'] = 'IN';
                else
                    $request['sla_status'] = 'OUT';

                if (!isset($request['fecha_coordinada'])) {
                    $request['fecha_coordinada'] = $sysdate;
                }
            }
            if ($newEstado == 27) {
                $request['fecha_notificado'] = $sysdate;
            }
        }

        if (!$request['id_terminal']) {
            $request['id_terminal'] = 1;
        }

        // si se ingresa por primera vez la fecha_coordinada, seteamos fecha_registracion_coordinacion
        if (isset($request['fecha_coordinada']) && !isset($reparacionOnsite->fecha_coordinada)) {
            $request['fecha_registracion_coordinacion'] = $sysdate;
        }

        // si se ingresa fecha cerrado, seteamos fecha coordinada
        if (isset($request['fecha_cerrado']) && $request['fecha_cerrado'] && (!isset($reparacionOnsite->fecha_coordinada) && empty($request['fecha_coordinada']))) {
            $request['fecha_coordinada'] = date('Y-m-d', strtotime($request['fecha_cerrado']));
        }

        // Solicitud
        $currentSolicitud = ((isset($reparacionOnsite->solicitud) &&  $reparacionOnsite->solicitud->count() > 0) ? $reparacionOnsite->solicitud[0] : null);

        if (isset($request['solicitud_id']) && $request['solicitud_id']) {

            if ($currentSolicitud && $currentSolicitud->id != $request['solicitud_id']) {
                SolicitudVisita::where('visita_id', $reparacionOnsite->id)->delete();
            }

            if (!$currentSolicitud || $currentSolicitud->id != $request['solicitud_id']) {
                $dataSolicitudVisita['solicitud_id'] = $request['solicitud_id'];
                $dataSolicitudVisita['reparacion_id'] = $reparacionOnsite->id;

                $newSolicitudVisita = $this->solicitudVisitaService->store($dataSolicitudVisita);
            }
        } else {

            if ($currentSolicitud) {
                SolicitudVisita::where('visita_id', $reparacionOnsite->id)->delete();
            }
        }

        $reparacionOnsite->update($request->all());

        if (in_array($request['id_tipo_servicio'], [TipoServicioOnsite::SEGUIMIENTO_OBRA, TipoServicioOnsite::PUESTA_MARCHA])) {
            $reparacionChecklistOnsite = ReparacionChecklistOnsite::where('reparacion_onsite_id', $reparacionOnsite->id)->first();

            if (isset($reparacionChecklistOnsite)) {
                ReparacionChecklistOnsite::where('id', $reparacionChecklistOnsite->id)->update($camposChecklist);
                //$this->reparacionChecklistOnsiteService->update($reparacionChecklistOnsite->id,$camposChecklist);
            } else {
                ReparacionChecklistOnsite::create($camposChecklist);
                ////$this->reparacionChecklistOnsiteService->create($camposChecklist);
            }

            if ($reparacionOnsite->reparacion_onsite_puesta_marcha_id) {
                $this->updateReparacionSeguimiento($reparacionOnsite->reparacion_onsite_puesta_marcha_id, $camposChecklist);
            }
        }

        /* si cambia estado se registra nuevo historial estado y se envía emails */
        if ($oldEstado != $newEstado) {

            $estado = $newEstado; //nuevo
            $observacion = 'Reparación modificada Manualmente';
            if ($request['observacion']) {
                $observacion = $request['observacion'];
            }
            $idUsuario = Auth::user()->id;


            $nuevoHistorialEstadoOnsite = array(
                'company_id' => $userCompanyId,
                'id_reparacion' => $reparacionOnsite->id,
                'id_estado' => $estado,
                'fecha' => $sysdate,
                'observacion' => $observacion,
                'id_usuario' => $idUsuario,
                'visible' => true,

            );

            $requestHistroalEstadoOnsite = new HistorialEstadoOnsiteRequest($nuevoHistorialEstadoOnsite);
            $this->historialEstadoService->store($requestHistroalEstadoOnsite);

            /* envío emails */
            $this->enviarMailResponsableEmpresa($reparacionOnsite);
            $this->enviarMailAdministrador($reparacionOnsite);
        }

        //si cambia el técnico, se envia mail -------------
        if ($oldTecnico != $newTecnico) {
            $this->enviarMailTecnicoOnsiteAsignado($reparacionOnsite);
        }

        return $reparacionOnsite;
    }


    public function updateReparacionSeguimiento($reparacionOnsiteSeguimientoId, $camposChecklist)
    {
        $reparacionChecklistOnsiteSeguimiento = ReparacionChecklistOnsite::where('reparacion_onsite_id', $reparacionOnsiteSeguimientoId)->first();

        $checklist_update = [];

        if ($camposChecklist['alimentacion_definitiva']) {
            $checklist_update['alimentacion_definitiva'] = 1;
        }
        if ($camposChecklist['unidades_tension_definitiva']) {
            $checklist_update['unidades_tension_definitiva'] = 1;
        }
        if ($camposChecklist['cable_alimentacion_comunicacion_seccion_ok']) {
            $checklist_update['cable_alimentacion_comunicacion_seccion_ok'] = 1;
        }

        if ($camposChecklist['minimo_conexiones_frigorificas_exteriores']) {
            $checklist_update['minimo_conexiones_frigorificas_exteriores'] = 1;
        }
        if ($camposChecklist['sistema_presurizado_41_5_kg']) {
            $checklist_update['sistema_presurizado_41_5_kg'] = 1;
        }
        if ($camposChecklist['operacion_vacio']) {
            $checklist_update['operacion_vacio'] = 1;
        }

        if ($camposChecklist['llave_servicio_odu_abiertos']) {
            $checklist_update['llave_servicio_odu_abiertos'] = 1;
        }
        if ($camposChecklist['carga_adicional_introducida']) {
            $checklist_update['carga_adicional_introducida'] = 1;
        }
        if ($camposChecklist['sistema_funcionando_15_min_carga_adicional']) {
            $checklist_update['sistema_funcionando_15_min_carga_adicional'] = 1;
        }

        if ($reparacionChecklistOnsiteSeguimiento) {
            $reparacionChecklistOnsiteSeguimiento->update($checklist_update);
        }
    }

    public function enviarMailResponsableEmpresa($reparacionOnsite)
    {
        $idEstadoOnsite = $reparacionOnsite->id_estado;
        $idEmpresaOnsite = $reparacionOnsite->id_empresa_onsite;

        $estadoOnsite = $this->estado_onsite_repository->findEstadoOnsite($idEstadoOnsite);
        $empresaOnsite = $this->empresaOnsiteService->findEmpresaOnsite($idEmpresaOnsite);

        if ($estadoOnsite && $empresaOnsite) {
            $plantilla_mail_responsable_id = $estadoOnsite->plantilla_mail_responsable_id;
            $emailResponsableEmpresa = $empresaOnsite->email_responsable;

            if ($plantilla_mail_responsable_id > 1 && $emailResponsableEmpresa !== null) {

                $this->mailOnsiteService->enviarMailOnsite($reparacionOnsite, $plantilla_mail_responsable_id, $emailResponsableEmpresa);
            }
        }
    }

    public function enviarMailAdministrador($reparacionOnsite)
    {
        $idEstadoOnsite = $reparacionOnsite->id_estado;
        $estadoOnsite = $this->estado_onsite_repository->findEstadoOnsite($idEstadoOnsite);
        $parametroMail = $this->parametrosService->findParametroPorNombre('ONSITE_SOLICITUD_ADMIN_EMAIL_TO');

        if ($estadoOnsite && $parametroMail) {

            $plantilla_mail_admin_id = $estadoOnsite->plantilla_mail_admin_id;
            $emailAdmiOnsite = $parametroMail->valor_cadena;

            if ($plantilla_mail_admin_id > 1 && $emailAdmiOnsite !== null) {
                $this->mailOnsiteService->enviarMailOnsite($reparacionOnsite, $plantilla_mail_admin_id, $emailAdmiOnsite);
            }
        }
    }

    public function reenviarMailTecnico(Request $request, $idReparacionOnsite)
    {
        $reparacionOnsite = $this->findVisita($idReparacionOnsite);

        $this->enviarMailTecnicoOnsiteAsignado($reparacionOnsite);

        $mje = "Se ha reenviado el email al técnico asignado.";
        return $mje;
    }

    public function findReparacionDetalle($id)
    {
        $reparacionDetalle = ReparacionDetalle::where('reparacion_id', $id)->first();

        return $reparacionDetalle;
    }

    public function destroy($idReparacionOnsite)
    {
        $reparacionOnsite = $this->findVisita($idReparacionOnsite);

        // Validar que el usuario sea de la misma compañia que la terminal
        if (!in_array($reparacionOnsite->company_id, Session::get('userCompaniesId'))) {
            Session::flash('message-error', 'Sin Privilegios');
            return redirect('/terminalOnsite');
        }

        $reparacionDetalle = $this->findReparacionDetalle($idReparacionOnsite);

        if ($reparacionDetalle) {
            $reparacionDetalle->delete();
        }

        $historialEstados = $this->historialEstadoService->findHistorialEstadoOnsitePorReparacion($reparacionOnsite->id);

        if ($historialEstados)
            foreach ($historialEstados as $historial) {
                $this->historialEstadoService->destroy($historial->id);
            }

        $imagenesOnsite = $this->imagenOnsiteService->findImagenOnsitePorReparacion($reparacionOnsite->id);
        if ($imagenesOnsite)
            foreach ($imagenesOnsite as $imagenOnsite) {
                $this->imagenOnsiteService->destroy($imagenOnsite->id);
            }


        $reparacionOnsite->delete();

        return $reparacionOnsite;
    }

    public function getVisitasPorTecnico()
    {

        $reparaciones = DB::table('view_tecnicos_reparaciones_resultados')
            ->select(DB::raw('idTecnico, Tecnico, (sum(sin_observaciones) + sum(con_observaciones) + sum(rechazada) + sum(obs_elec) + sum(obs_mecanica)) as total'))
            ->where('idTecnico', '<>', 14)
            ->groupBy('idTecnico', 'Tecnico')
            ->get();

        return $reparaciones;
    }

    public function generaComprobanteVisita($reparacion)
    {
        $sistema = $reparacion->sistema_onsite->nombre;
        $obra = $reparacion->sistema_onsite->obra_onsite->nombre;
        //$tipo_servicio = $reparacion->tipo_servicio_onsite->nombre;
        $tipo_servicio = $reparacion->solicitud_tipo->nombre;

        $detalle_tarea = '-';
        if (!is_null($reparacion->informe_tecnico) && strlen($reparacion->informe_tecnico) > 2)
            $detalle_tarea = $reparacion->informe_tecnico;


        if ($reparacion->observaciones_internas != 'null') {
            $observaciones = $reparacion->observaciones_internas;
        } else
            $observaciones = '-';


        $resultado = ReparacionChecklistOnsite::/* where('company_id', $this->userCompanyId)
            -> */where('reparacion_onsite_id', $reparacion->id)
            ->first();


        if ($resultado) {
            $puesta_marcha = $resultado->puesta_marcha_satisfactoria;
            if ($puesta_marcha == 1)
                $resultado_texto = 'Inspección Satisfactoria';
            else
                $resultado_texto = 'Inspección con Observaciones';
        } else
            $resultado_texto = '-';

        $idtemplateComprobante = TemplateComprobante::COMPROBANTE_VISITA;

        $templateComprobante = $this->templateComprobanteService->getTemplate($idtemplateComprobante);


        $comprobante = $templateComprobante->cuerpo;


        $domcilio_obra = explode("-", $reparacion->sistema_onsite->obra_onsite->domicilio);
        $provincia = isset($domcilio_obra[3]) ? $domcilio_obra[3] : null;

        $fecha_vista = ($reparacion->fecha_coordinada ? date('d-m-Y', strtotime($reparacion->fecha_coordinada)) : ($reparacion->fecha_notificado ? date('d-m-Y', strtotime($reparacion->fecha_notificado)) : '-'));

        $comprobanteVisita = str_replace('%SISTEMA_ONSITE%', $sistema, $comprobante);
        $comprobanteVisita = str_replace('%OBRA%', $obra, $comprobanteVisita);
        $comprobanteVisita = str_replace('%DOMICILIO_OBRA%', $domcilio_obra[0] ? $domcilio_obra[0] : null, $comprobanteVisita);
        $comprobanteVisita = str_replace('%PAIS_OBRA%', isset($domcilio_obra[1]) ? $domcilio_obra[1] : null, $comprobanteVisita);
        $comprobanteVisita = str_replace('%LOCALIDAD_OBRA%', isset($domcilio_obra[2]) ? $domcilio_obra[2] . ', ' . $provincia : null, $comprobanteVisita);


        $comprobanteVisita = str_replace('%FECHA_VISITA%', $fecha_vista, $comprobanteVisita);



        $comprobanteVisita = str_replace('%TIPO_SOLICITUD%', $tipo_servicio, $comprobanteVisita);

        $comprobanteVisita = str_replace('%DETALLE_VISITA%', $detalle_tarea, $comprobanteVisita);

        $comprobanteVisita = str_replace('%OBSERVACIONES_VISITA%', $observaciones, $comprobanteVisita);

        $comprobanteVisita = str_replace('%RESULTADO_VISITA%', $resultado_texto, $comprobanteVisita);



        return $comprobanteVisita;
    }

    public function findReparacion($id)
    {
        $company_id = Session::get('userCompanyIdDefault');

        $reparacionOnsite = ReparacionOnsite::with('reparacion_checklist_onsite')
            ->where('company_id', $company_id)->find($id);

        return $reparacionOnsite;
    }

    public function generarXlsx($userCompanyId, $texto, $idEstado, $idSistema,   $idTipoServicio,  $idTecnico, $fechaIngresoDesde, $fechaIngresoHasta, $fechaVencimientoDesde, $fechaVencimientoHasta, $userId)
    {
        $saltear = 0;
        $tomar = 5000;

        $filename = "listado_visitas_" . $userId . ".xlsx";

        $data[] = [
            'ID',
            'ID_EMPRESA_ONSITE',
            'EMPRESA_ONSITE',
            'CLAVE',
            'ID_SISTEMA',
            'SISTEMA',
            'TAREA',
            'DETALLE_TAREA',
            'ID_TIPO_SERVICIO',
            'TIPO_SERVICIO',
            'ID_ESTADO',
            'ESTADO',
            'FECHA_INGRESO',
            'OBSERVACION_UBICACION',
            'ID_TECNICO_ASIGNADO',
            'TECNICO_ASIGNADO',
            'NRO_CAJA',
            'INFORME_TECNICO',
            'FECHA_COORDINADA',
            'VENTANA_HORARIA_COORDINADA',
            'FECHA_REGISTRACION_COORDINACION',
            'FECHA_NOTIFICADO',
            'FECHA_VENCIMIENTO',
            'FECHA_CERRADO',
            'SLA_STATUS',
            'SLA_JUSTIFICADO',
            'MONTO',
            'MONTO_EXTRA',
            'LIQUIDADO_PROVEEDOR',
            'NRO_FACTURA_PROVEEDOR',
        ];

        $params = [
            'userCompanyId' => $userCompanyId,
            'texto' => $texto,
            'id_estado' => $idEstado,
            'sistema_onsite_id' => $idSistema,
            'id_tipo_servicio' => $idTipoServicio,
            'id_tecnico' => $idTecnico,
            'fecha_ingreso_desde' => $fechaIngresoDesde,
            'fecha_ingreso_hasta' => $fechaIngresoHasta,
            'fecha_vencimiento_desde' => $fechaVencimientoDesde,
            'fecha_vencimiento_hasta' => $fechaVencimientoHasta,
            'saltear' => $saltear,
            'tomar' => $tomar,
        ];

        $reparacionesOnsite = $this->listar($params);

        while ($reparacionesOnsite->count()) {

            foreach ($reparacionesOnsite as $reparacionOnsite) {
                $data[] = [
                    'id' => $reparacionOnsite->id,
                    'id_empresa_onsite' => $reparacionOnsite->id_empresa_onsite,
                    'empresa_onsite' => ($reparacionOnsite->empresa_onsite) ? $reparacionOnsite->empresa_onsite->nombre : '',
                    'clave' => $reparacionOnsite->clave,
                    'sistema_onsite_id' => $reparacionOnsite->sistema_onsite_id,
                    'sistema' => ($reparacionOnsite->sistema_onsite) ? $reparacionOnsite->sistema_onsite->id . ' - ' . $reparacionOnsite->sistema_onsite->nombre : '--',
                    'tarea' => $reparacionOnsite->tarea,
                    'detalle_tarea' => $reparacionOnsite->tarea_detalle,
                    'id_tipo_servicio' => $reparacionOnsite->id_tipo_servicio,
                    'tipo_servicio' => ($reparacionOnsite->tipo_servicio_onsite) ? $reparacionOnsite->tipo_servicio_onsite->nombre : '---',
                    'id_estado' => $reparacionOnsite->id_estado,
                    'estado' => ($reparacionOnsite->estado_onsite) ? $reparacionOnsite->estado_onsite->nombre : '---',
                    'fecha_ingreso' => $reparacionOnsite->fecha_ingreso,
                    'observacion_ubicacion' => $reparacionOnsite->observacion_ubicacion,
                    'id_tecnico_asignado' => $reparacionOnsite->id_tecnico_asignado,
                    'tecnico_asignado' => ($reparacionOnsite->tecnicoAsignado) ? $reparacionOnsite->tecnicoAsignado->name : '',
                    'nro_caja' => $reparacionOnsite->nro_caja,
                    'informe_tecnico' => $reparacionOnsite->informe_tecnico,
                    'fecha_coordinada' => $reparacionOnsite->fecha_coordinada,
                    'ventana_horaria_coordinada' => $reparacionOnsite->ventana_horaria_coordinada,
                    'fecha_registracion_coordinacion' => $reparacionOnsite->fecha_registracion_coordinacion,
                    'fecha_notificado' => $reparacionOnsite->fecha_notificado,
                    'fecha_vencimiento' => $reparacionOnsite->fecha_vencimiento,
                    'fecha_cerrado' => $reparacionOnsite->fecha_cerrado,
                    'sla_status' => $reparacionOnsite->sla_status,
                    'sla_justificado' => $reparacionOnsite->sla_justificado,
                    'monto' => $reparacionOnsite->monto,
                    'monto_extra' => $reparacionOnsite->monto_extra,
                    'liquidado_proveedor' => $reparacionOnsite->liquidado_proveedor,
                    'nro_factura_proveedor' => $reparacionOnsite->nro_factura_proveedor,

                ];
            }

            $saltear = $saltear + 5000;

            $params = [
                'userCompanyId' => $userCompanyId,
                'texto' => $texto,
                'idEstado' => $idEstado,
                'sistema_onsite_id' => $idSistema,
                'idTipoServicio' => $idTipoServicio,
                'idTecnico' => $idTecnico,
                'fecha_ingreso_desde' => $fechaIngresoDesde,
                'fecha_ingreso_hasta' => $fechaIngresoHasta,
                'fecha_vencimiento_desde' => $fechaVencimientoDesde,
                'fecha_vencimiento_hasta' => $fechaVencimientoHasta,
                'saltear' => $saltear,
                'tomar' => $tomar,
            ];

            $reparacionesOnsite = $this->listar($params);
        }

        $excelController = new GenericExport($data, $filename);
        $excelController->export();
    }

    public function getVisitas($company_id, $clave)
    {
        $query = ReparacionOnsite::select(
            'reparaciones_onsite.id',
            'reparaciones_onsite.clave',
            'reparaciones_onsite.sistema_onsite_id',
            'reparaciones_onsite.id_terminal',
            'reparaciones_onsite.id_tipo_servicio',
            'reparaciones_onsite.fecha_ingreso',
            'reparaciones_onsite.fecha_coordinada',
            'reparaciones_onsite.ventana_horaria_coordinada',
            'reparaciones_onsite.problema_resuelto',
            'reparaciones_onsite.usuario_id',
            'reparaciones_onsite.id_tecnico_asignado',
            'reparaciones_onsite.nota_cliente',
            'reparaciones_onsite.observaciones_internas',
            'reparacion_checklist_onsite.puesta_marcha_satisfactoria',
            'reparaciones_onsite.solicitud_tipo_id',
            'reparaciones_onsite.created_at',
            'solicitudes_tipos.nombre as solicitud_tipo_nombre'
        )
            ->leftJoin('reparacion_checklist_onsite', 'reparaciones_onsite.id', '=', 'reparacion_checklist_onsite.reparacion_onsite_id')
            ->leftJoin('solicitudes_tipos', 'reparaciones_onsite.solicitud_tipo_id', 'solicitudes_tipos.id')
            ->with(['solicitud:id,created_at'])
            ->where('reparaciones_onsite.company_id', $company_id);

        if ($clave !== null) {
            $query->where(function ($q) use ($clave) {
                $q->where('reparaciones_onsite.id', $clave)
                    ->orWhere('reparaciones_onsite.clave', $clave);
            });
        }
        $visitas = $query->get();

        return $visitas;
    }

    public function getVisitasFull($company_id, $clave)
    {
        $query = ReparacionOnsite::with([
            'sistema_onsite',
            'terminal_onsite',
            'tipo_servicio_onsite',
            'estado_onsite',
            'user',
            'tecnicoAsignado',
            'solicitud',
            'reparacion_checklist_onsite',
            'solicitud_tipo'
        ])
            ->where('company_id', $company_id);


        if ($clave !== null) {
            $query->where(function ($query) use ($clave) {
                $query->where('id', $clave)
                    ->orWhere('clave', $clave);
            });
        }

        $visitas = $query->get();

        $datosVisitas = [];

        foreach ($visitas as $visita) {
            $solicitud_created_at = null;
            if (isset($visita->solicitud[0])) {
                $solicitud_created_at = date('Y-m-d H:i:s', strtotime($visita->solicitud[0]->created_at));
            }
            $visita_created_at =  date('Y-m-d H:i:s', strtotime($visita->created_at));

            $datosVisitas[] = [
                'id' => $visita->id,
                'clave' => $visita->clave,
                'sistema_onsite_id' => $visita->sistema_onsite_id,
                'sistema_onsite' => ($visita->sistema_onsite) ? $visita->sistema_onsite->nombre : null,
                'terminal_onsite_id' => $visita->id_terminal,
                'terminal_onsite' => ($visita->terminal_onsite) ? $visita->terminal_onsite->nombre : null,
                'tipo_servicio_onsite_id' => $visita->id_tipo_servicio,
                'tipo_servicio_onsite' => ($visita->tipo_servicio_onsite) ? $visita->tipo_servicio_onsite->nombre : null,
                'estado_onsite_id' => $visita->id_estado,
                'estado_onsite' => ($visita->estado_onsite) ? $visita->estado_onsite->nombre : null,
                'solicitud_id' => (isset($visita->solicitud[0]) ? $visita->solicitud[0]->id : null),
                'fecha_ingreso' => $visita->fecha_ingreso,
                'fecha_coordinada' => $visita->fecha_coordinada,
                'ventana_horaria_coordinada' => $visita->ventana_horaria_coordinada,
                'problema_resuelto' => $visita->problema_resuelto,
                'user_id' => $visita->usuario_id,
                'user' => ($visita->user) ? $visita->user->name : null,
                'tecnicoAsignado_id' => $visita->id_tecnico_asignado,
                'tecnicoAsignado' => ($visita->tecnicoAsignado) ? $visita->tecnicoAsignado->name : null,
                'nota_cliente' => $visita->nota_cliente,
                'observaciones_internas' => $visita->observaciones_internas,
                'puesta_marcha_satisfactoria' => optional($visita->reparacion_checklist_onsite)->puesta_marcha_satisfactoria,
                'solicitud_tipo_id' => $visita->solicitud_tipo_id,
                'solicitud_tipo_nombre' => optional($visita->solicitud_tipo)->nombre,
                'solicitud_created_at' => $solicitud_created_at,
                'visita_created_at' => $visita_created_at,
            ];
        }

        return $datosVisitas;
    }


    public function getResultadosReparacionPorEmpresaInstaladora()
    {

        $reparaciones = DB::table('view_tecnicos_reparaciones_resultados')
            ->select(DB::raw('count(idObra) as cantidad, EmpInsta'))
            ->where('con_observaciones', '<>', 0)
            ->orWhere('obs_elec', '<>', 0)
            ->orWhere('obs_mecanica', '<>', 0)
            ->orWhere('rechazada', '<>', 0)
            ->groupBy('EmpInsta')
            ->get();

        return $reparaciones;
    }


    public function getResultadosReparacionPorTecnico()
    {

        $reparaciones = DB::table('view_tecnicos_reparaciones_resultados')
            ->select(DB::raw('idTecnico, Tecnico, count(idObra) as cantidad'))
            ->where('con_observaciones', '<>', 0)
            ->orWhere('obs_elec', '<>', 0)
            ->orWhere('obs_mecanica', '<>', 0)
            ->orWhere('rechazada', '<>', 0)
            ->groupBy('idTecnico', 'Tecnico')
            ->get();

        return $reparaciones;
    }

    public function getReparacionEmpresaServicio($empresa_onsite_id, $id_tipo_servicio)
    {
        $company_id = Session::get('userCompanyIdDefault');

        $reparacionOnsiteSeguimiento = ReparacionOnsite::where('company_id', $company_id)
            ->where('id_empresa_onsite', $empresa_onsite_id)
            ->where('id_tipo_servicio', $id_tipo_servicio)
            ->orderBy('created_at', 'DESC')
            ->first();

        return $reparacionOnsiteSeguimiento;
    }

    public function setReparacionOnsiteClave($idempresaOnsite, $idReparacionOnsite)
    {
        $reparacionOnsite = $this->findReparacion($idReparacionOnsite);
        $claveReparacionOnsite = 'E' . $idempresaOnsite . $idReparacionOnsite;

        if ($reparacionOnsite) {
            $reparacionOnsite->clave = $claveReparacionOnsite;
            $reparacionOnsite->save();

            return $reparacionOnsite;
        } else
            return false;
    }
}
