<?php

namespace App\Services\Onsite;

use App\Enums\Prioridad;
use App\Enums\PuestaMarchaSatisfactoriaEnum;
use App\Exports\GenericExport;
use App\Exports\ReparacionesOnsiteExport;
use App\Http\Requests\Onsite\HistorialEstadoOnsiteRequest;
use App\Models\Company;
use App\Models\Notificacion;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

use App\Repositories\Onsite\HistorialEstadoOnsiteRepository;
use App\Repositories\Onsite\EstadoOnsiteRepository;


//use App\Models\Onsite\EmpresaOnsite;
use App\Models\Onsite\EstadoOnsite;
use App\Models\Onsite\HistorialEstadoOnsite;
use App\Models\Onsite\ImagenOnsite;
use App\Models\Onsite\NivelOnsite;
use App\Models\Onsite\ReparacionOnsite;
use App\Models\Onsite\TipoServicioOnsite;
use App\Models\Onsite\TipoImagenOnsite;
use App\Models\Onsite\ReparacionChecklistOnsite;
use App\Models\Onsite\ReparacionDetalle;
use App\Models\Onsite\ReparacionVisita;
use App\Models\Onsite\TerminalOnsite;
use App\Models\Parametro;
use App\Models\Provincia;
use App\Models\TemplateComprobante;
use App\Models\User;
use App\Models\ViewTecnicosReparacionesResultados;
use App\Services\Onsite\LocalidadOnsiteService;
use App\Services\Onsite\EmpresaOnsiteService;
use App\Services\Onsite\SucursalOnsiteService;
use App\Services\Onsite\TerminalOnsiteService;
use App\Services\Onsite\SistemaOnsiteService;
use Riparazione\Services\TicketsService;
use Riparazione\Services\CommentTicketService;


use App\Models\MotivoConsultaTicket;
use App\CategoryTicket;
use App\GroupTicket;
use App\Models\Ticket\PriorityTicket;
use App\Models\Ticket\StatusTicket;


use Carbon\Carbon;
use DateTime;
use DB;
use Exception;
use Illuminate\Database\Eloquent\JsonEncodingException;
use Log;
use Maatwebsite\Excel\Facades\Excel;

class ReparacionOnsiteService
{
	protected $historial_estado_onsite_repository;
	protected $estado_onsite_repository;
	protected $localidadOnsiteService;
	protected $sucursalOnsiteService;
	protected $empresaOnsiteService;
	protected $terminalOnsiteService;
	protected $sistemaOnsiteService;
	protected $mailOnsiteService;
	protected $tiposServiciosService;
	protected $solicitudesTiposServices;
	protected $provinciasService;
	protected $historialEstadoService;
	protected $nivelesOnsiteService;
	protected $tiposImagenOnsiteService;
	protected $imagenOnsiteService;
	protected $parametrosService;
	protected $templateComprobanteService;
	protected $obrasOnsiteService;
	protected $empresasInstaladorasService;
	protected $reparacionDetalleService;
	protected $userCompanyId;
	protected $userService;

	public function __construct(
		HistorialEstadoOnsiteRepository $historial_estado_onsite_repository,
		EstadoOnsiteRepository $estado_onsite_repository,
		LocalidadOnsiteService $localidadOnsiteService,
		SucursalOnsiteService $sucursalOnsiteService,
		EmpresaOnsiteService $empresaOnsiteService,
		TerminalOnsiteService $terminalOnsiteService,
		SistemaOnsiteService $sistemaOnsiteService,
		MailOnsiteService $mailOnsiteService,
		TiposServiciosService $tiposServiciosService,
		SolicitudesTiposService $solicitudesTiposServices,
		ProvinciasService $provinciasService,
		HistorialEstadosOnsiteService $historialEstadoService,
		NivelesOnsiteService $nivelesOnsiteService,
		TiposImageOnsiteService $tiposImagenOnsiteService,
		ImagenesOnsiteService $imagenOnsiteService,
		ParametroService $parametrosService,
		ObrasOnsiteService $obrasOnsiteService,
		EmpresasInstaladorasServices $empresasInstaladorasService,
		TemplatesService $templateComprobanteService,
		ReparacionDetalleService $reparacionDetalleService,
		UserService $userService

	) {
		$this->historial_estado_onsite_repository = $historial_estado_onsite_repository;
		$this->estado_onsite_repository = $estado_onsite_repository;
		$this->localidadOnsiteService = $localidadOnsiteService;
		$this->sucursalOnsiteService = $sucursalOnsiteService;
		$this->empresaOnsiteService = $empresaOnsiteService;
		$this->terminalOnsiteService = $terminalOnsiteService;
		$this->sistemaOnsiteService = $sistemaOnsiteService;
		$this->mailOnsiteService = $mailOnsiteService;
		$this->tiposServiciosService = $tiposServiciosService;
		$this->solicitudesTiposServices = $solicitudesTiposServices;
		$this->provinciasService = $provinciasService;
		$this->historialEstadoService = $historialEstadoService;
		$this->nivelesOnsiteService = $nivelesOnsiteService;
		$this->tiposImagenOnsiteService = $tiposImagenOnsiteService;
		$this->imagenOnsiteService = $imagenOnsiteService;
		$this->parametrosService = $parametrosService;
		$this->obrasOnsiteService = $obrasOnsiteService;
		$this->empresasInstaladorasService = $empresasInstaladorasService;
		$this->templateComprobanteService = $templateComprobanteService;
		$this->reparacionDetalleService = $reparacionDetalleService;
		$this->userService = $userService;
		$this->userCompanyId =  session()->get('userCompanyIdDefault');
	}



	public function getDataIndex($request)
	{
		$userCompanyId = Session::get('userCompanyIdDefault');
		$userId = Auth::user()->id;
		$excludeEmpresa = '5';

		$datos['sysdate'] = date('Y-m-d H:i:s');
		$datos['user_id'] = $userId;
		$datos['tiposServicios'] = $this->tiposServiciosService->listado($userCompanyId);
		$datos['solicitudesTipos'] = $this->solicitudesTiposServices->getAllSolicitudesTipos();
		$datos['estadosOnsite'] = $this->estado_onsite_repository->listado($userCompanyId);
		$datos['tecnicosOnsite'] = $this->userService->listarTecnicosOnsite($userCompanyId);
		$datos['empresasOnsite'] = $this->empresaOnsiteService->listadoAll($userCompanyId, $request['includeEmpresa'], $request['excludeEmpresa']);

		$datos['estados_activo'] = ['activos'];
		$datos['historialEstadosOnsite'] = array();
		$datos['includeEmpresa'] = $request['includeEmpresa'];
		$datos['excludeEmpresa'] = $request['excludeEmpresa'];

		$params = [
			'userCompanyId' => $userCompanyId,
			'estadosActivos' => ['activos'],
			'excludeEmpresa' => $request['excludeEmpresa'],
			'includeEmpresa' => $request['includeEmpresa'],
		];

		$datos['reparacionesOnsite'] = $this->listar($params);

		$datos['ruta'] = $request['ruta'];

		$notificacion = Notificacion::where('tipo', 'importacion')->orderBy('id', 'desc')->first();
		if ($notificacion)
			$datos['ultima_notificacion'] = $notificacion->notificacion;

		return $datos;
	}


	public function getData($params)
	{

		$datos['empresasOnsite'] = $this->empresaOnsiteService->listadoAll($params['company_id']);
		if ($datos['empresasOnsite']) {
			$id_empresas = $datos['empresasOnsite']->pluck('id');


			$exitoso = $params['exitoso'] ?? 0;

			switch ($exitoso) {
				case 0:
					$datos['reportesGenerados'] = Notificacion::whereIn('empresa_id', $id_empresas)
						->where('tipo', 'exportacion')
						->orderBy('id', 'desc')->limit(5)->get();
						Log::alert('es cero');
					break;
				case 1:
					$datos['reportesGenerados'] = Notificacion::whereIn('empresa_id', $id_empresas)
						->where('tipo', 'exportacion_exitosa')
						->orderBy('id', 'desc')->limit(5)->get();
						Log::alert('es uno');

					break;

				case 2:
					$datos['reportesGenerados'] = Notificacion::whereIn('empresa_id', $id_empresas)
						->where('tipo', 'exportacion_no_exitosa')
						->orderBy('id', 'desc')->limit(5)->get();
						Log::alert('es dos');

					break;

				case 3:
					$datos['reportesGenerados'] = Notificacion::whereIn('empresa_id', $id_empresas)
						->where('tipo', 'exportacion_servicios')
						->orderBy('id', 'desc')->limit(5)->get();
						Log::alert('es tres');

					break;
				case 4:
					$datos['reportesGenerados'] = Notificacion::whereIn('empresa_id', $id_empresas)
						->where('tipo', 'exportacion_servicios_activos')
						->orderBy('id', 'desc')->limit(5)->get();
						Log::alert('es cuatro');

					break;
			}
		}
		$datos['tiposServicios'] = $this->tiposServiciosService->listado($params['company_id']);
		$datos['solicitudesTipos'] = $this->solicitudesTiposServices->getAllSolicitudesTipos();
		$datos['estadosOnsite'] = $this->estado_onsite_repository->getEstadosByParams($params);
		$datos['tecnicosOnsite'] = $this->userService->listarTecnicosOnsite($params['company_id']);
		$datos['localidadesOnsite'] = $this->localidadOnsiteService->listadoAll($params['company_id']);

		return $datos;
	}

	public function getDataCreate()
	{
		$userCompanyId = Session::get('userCompanyIdDefault');

		$data['company_id'] = $userCompanyId;
		$datos = $this->getData($data);

		$datos['prioridades'] = Prioridad::getOptions();
		$datos['terminalOnsite'] = array();
		$datos['terminalesOnsite'] = array();
		$datos['sucursalOnsite'] = array();
		$datos['sucursalesOnsite'] = array();

		return $datos;
	}

	public function store(Request $request, $company_id  = null, $usuario_id = null)
	{
		Log::info("ReparacionOnsiteService - store");

		if (is_null($company_id))
			$userCompanyId = Session::get('userCompanyIdDefault');
		else $userCompanyId = $company_id;

		$sysdate = date('Y-m-d H:i:s');
		$request['company_id'] = $userCompanyId;

		if (!$request['clave']) {
			$request['clave'] = $this->getClaveReparacionOnsite($request);
		}

		$sucursalOnsite = $this->sucursalOnsiteService->findSucursal($request['sucursal_onsite_id']);
		if ($sucursalOnsite && isset($sucursalOnsite->localidad_onsite_id))
			$localidadOnsite = $this->localidadOnsiteService->getLocalidad($sucursalOnsite->localidad_onsite_id);
		else $localidadOnsite = false;

		$provinciaOnsite = null;
		if ($localidadOnsite)
			$provinciaOnsite = $this->provinciasService->findProvinciaOnsite($localidadOnsite->id_provincia);

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

		$ubicacion = '';
		if ($provinciaOnsite && $localidadOnsite && $sucursalOnsite)
			$ubicacion = $provinciaOnsite->nombre . ' / ' . $localidadOnsite->localidad . ' / ' . $sucursalOnsite->direccion . ' / ' . $sucursalOnsite->telefono_contacto;

		$request['observacion_ubicacion'] = $ubicacion;
		if (!isset($request['fecha_ingreso'])) {
			$request['fecha_ingreso'] = $sysdate;
		}
		$request['id_tecnico_asignado'] = $idTecnicoAsignado;
		$request['sla_status'] = 'IN';

		$estadoOnsite = $this->estado_onsite_repository->getEstadoOnsite($request['id_estado']);
		//if ($request['id_estado'] == EstadoOnsite::CERRADA) {
		if ($estadoOnsite) {
			if ($estadoOnsite->cerrado && (!isset($request['fecha_cerrado']) || !$request['fecha_cerrado'])) {
				$request['fecha_cerrado'] = $sysdate;

				if ($request['fecha_cerrado'] > $request['fecha_vencimiento'])
					$request['sla_status'] = 'OUT';
			}
		}
		if (!$request['id_terminal']) {
			$request['id_terminal'] = TerminalOnsite::DEFAULT;
		}

		$reparacionOnsite = ReparacionOnsite::create($request->all());
		$request['reparacion_id'] = $reparacionOnsite->id;
		$reparacionOnsiteDetalles = ReparacionDetalle::create($request->all());

		//agrega un registro en reparacion_checklist_onsite
		$camposChecklist['reparacion_onsite_id'] = $reparacionOnsite->id;
		$camposChecklist['company_id'] = $reparacionOnsite->company_id;
		$estado = $request['id_estado']; //1; //nuevo
		$observacion = ((isset($request['observacion']) && $request['observacion']) ? $request['observacion'] : 'Reparación creada Manualmente');
		if (is_null($usuario_id))
			$idUsuario = Auth::user()->id;
		else $idUsuario = $usuario_id;

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



		/* se envían mails */
		$this->enviarMailTecnicoOnsiteAsignado($reparacionOnsite);

		$datos['reparacionOnsite'] = $reparacionOnsite;
		$datos['tecnicoAsignado'] = $tecnicoAsignado;

		return $datos;
	}



	public function getDataEdit($id, $company_id = null)
	{
		if (is_null($company_id)) {
			//data cuando NO se llama por API
			$userCompanyId = Session::get('userCompanyIdDefault');
			$companies_user = Session::get('userCompaniesId');
			$datos['localidadesOnsite'] = $this->localidadOnsiteService->listadoAll($userCompanyId);
			$tiposServicios = $this->tiposServiciosService->listado($userCompanyId);

			$datos['tiposServicios'] = $tiposServicios;
			$datos['solicitudesTipos'] = $this->solicitudesTiposServices->getAllSolicitudesTipos();
			$datos['estadosOnsite'] = $this->estado_onsite_repository->listado($userCompanyId);
			$datos['tecnicosOnsite'] = $this->userService->listarTecnicosOnsite($userCompanyId);
			$datos['tiposImagenOnsite'] = $this->tiposImagenOnsiteService->getTiposImagenOnsiteAll();
			$datos['obras'] = $this->obrasOnsiteService->listar(null, $userCompanyId, null, null);
			$datos['puestaMarchaSatisfactoriaEnumOptions'] = PuestaMarchaSatisfactoriaEnum::getOptions();
		} else {

			$userCompanyId = $company_id;
			$companies_user = [$company_id];
		}


		$reparacionOnsite = $this->findReparacion($id, $company_id);


		if ($reparacionOnsite && !is_null($companies_user)) {
			$datos['reparacionOnsite'] = $reparacionOnsite;

			// Validar que el usuario sea de la misma compañia que la terminal

			if (!in_array($reparacionOnsite->company_id, $companies_user)) {
				Session::flash('message-error', 'Sin Privilegios');
				return redirect('/terminalOnsite');
			}


			$datos['empresasOnsite'] = $this->empresaOnsiteService->listadoAll($userCompanyId);
			$datos['terminalesOnsite'] = $this->terminalOnsiteService->findTerminalNro($reparacionOnsite->id_terminal);
			$datos['sucursalesOnsite'] = $this->sucursalOnsiteService->findSucursales($reparacionOnsite->sucursal_onsite_id);


			$datos['sistemasOnsite'] = [];

			if (isset($reparacionOnsite) && isset($reparacionOnsite->sistema_onsite)) {

				$datos['sistemasOnsite'] = $this->sistemaOnsiteService->getSistemasPorObra($reparacionOnsite->sistema_onsite->obra_onsite_id);
			}


			$sucursalOnsite = $this->sucursalOnsiteService->findSucursal($reparacionOnsite->sucursal_onsite_id);
			if ($sucursalOnsite)
				$localidad = $this->localidadOnsiteService->getLocalidad($sucursalOnsite->localidad_onsite_id);
			else $localidad = $this->localidadOnsiteService->getLocalidad(1); //desconocida en producción

			if ($localidad)
				$datos['nivelInterior'] = $this->nivelesOnsiteService->findNivelOnsite($localidad->id_nivel);
			else
				$datos['nivelInterior'] = null;

			$datos['historialEstadosOnsite'] = $this->historialEstadoService->getHistorialEstadosNotas($reparacionOnsite->id);

			$datos['terminalOnsite'] = array();
			$datos['sucursalOnsite'] = array();

			$datos['reparacionesChecklistOnsite'] = ReparacionChecklistOnsite::where('reparacion_onsite_id', $reparacionOnsite->id)->first();

			$datos['prioridades'] = Prioridad::getOptions();
			$datos['companyId'] = $userCompanyId;

			/* Unidades Interiores y Exteriores del Sistema a editar */

			$sistemaOnsiteReparacion = $this->sistemaOnsiteService->findSistemaOnsite($reparacionOnsite->sistema_onsite_id);

			$datos['sistemaOnsiteReparacion'] = $sistemaOnsiteReparacion;

			$grupos = GroupTicket::select('id', 'name')->where('company_id', Session::get('userCompanyIdDefault'))->get();

			$motivos_consulta_ticket = MotivoConsultaTicket::select('id', 'name')->where('company_id', Session::get('userCompanyIdDefault'))->get();
			$categorias_ticket = CategoryTicket::select('id', 'name')->where('company_id', Session::get('userCompanyIdDefault'))->get();
			$grupos_ticket_ticket = GroupTicket::select('id', 'name')->where('company_id', Session::get('userCompanyIdDefault'))->get();
			$priorities_ticket = PriorityTicket::select('id', 'name')->get();
			$status_ticket = StatusTicket::select('id', 'name')->get();

			$datos['commentsTickets'] = array();
            $datos['motivos_consulta'] = $motivos_consulta_ticket;
            $datos['categorias'] = $categorias_ticket;
            $datos['grupos'] = $grupos_ticket_ticket;
            $datos['priorities'] = $priorities_ticket;
            $datos['status'] = $status_ticket;
			return $datos;
		} else {
			return false;
		}
	}

	public function getDataShow($id)
	{
		$reparacionOnsite = ReparacionOnsite::find($id);
		$userCompanyId = Session::get('userCompanyIdDefault');
		$localidad = null;

		// Validar que el usuario sea de la misma compañia que la terminal
		if (!in_array($reparacionOnsite->company_id, Session::get('userCompaniesId'))) {
			Session::flash('message-error', 'Sin Privilegios');
			return redirect('/terminalOnsite');
		}

		$data['company_id'] = $userCompanyId;
		$datos = $this->getData($data);

		$datos['reparacionOnsite'] = $reparacionOnsite;
		$datos['terminalesOnsite'] = $this->terminalOnsiteService->findTerminalNro($reparacionOnsite->id_terminal);
		$datos['sucursalesOnsite'] = $this->sucursalOnsiteService->findSucursales($reparacionOnsite->sucursal_onsite_id);

		$sucursalOnsite = $this->sucursalOnsiteService->findSucursal($reparacionOnsite->sucursal_onsite_id);
		if ($sucursalOnsite)
			$localidad = $this->localidadOnsiteService->getLocalidad($sucursalOnsite->localidad_onsite_id);
		else
			$localidad = $this->localidadOnsiteService->getLocalidad(1); //desconocida en producción

		if ($localidad)
			$datos['nivelInterior'] = $this->nivelesOnsiteService->findNivelOnsite($localidad->id_nivel);
		else
			$datos['nivelInterior'] = null;

		//$datos['historialEstadosOnsite'] = $this->historial_estado_onsite_repository->getHistorialPorReparacionOnsite($reparacionOnsite->id)->get();
		$datos['historialEstadosOnsite'] = $this->historialEstadoService->getHistorialEstadosNotas($reparacionOnsite->id);

		$datos['prioridades'] = Prioridad::getOptions();
		$datos['terminalOnsite'] = array();
		$datos['sucursalOnsite'] = array();
		$datos['companyId'] = $userCompanyId;

		return $datos;
	}

	public function update($request, $idReparacionOnsite, $company_id = null, $user_id = null)
	{


		if (is_null($company_id))
			$userCompanyId = Session::get('userCompanyIdDefault');
		else
			$userCompanyId = $company_id;

		$sysdate = date('Y-m-d H:i:s');
		$ruta = (isset($request['ruta']) && $request['ruta']) ? $request['ruta'] : 'reparacionOnsite';

		$reparacionOnsite = $this->findReparacion($idReparacionOnsite, $company_id);

		$oldEstado = $reparacionOnsite->id_estado;
		$newEstado = $request['id_estado'] ?? false;

		$oldTecnico = $reparacionOnsite->id_tecnico_asignado;
		$newTecnico = (isset($request['id_tecnico_asignado']) && $request['id_tecnico_asignado']) ? $request['id_tecnico_asignado'] : null;


		//campos reparacion
		/* $request['sla_justificado'] = (isset($request['sla_justificado'])) ? 1 : 0;
		$request['liquidado_proveedor'] = (isset($request['liquidado_proveedor'])) ? 1 : 0;
		$request['visible_cliente'] = (isset($request['visible_cliente'])) ? 1 : 0;
		$request['chequeado_cliente'] = (isset($request['chequeado_cliente'])) ? 1 : 0;
		$request['problema_resuelto'] = (isset($request['problema_resuelto'])) ? $request['problema_resuelto'] : 0;
		$request['instalacion_buzon'] = (isset($request['instalacion_buzon'])) ? 1 : 0;
		$request['requiere_nueva_visita'] = (isset($request['requiere_nueva_visita'])) ? 1 : 0; */
		if (isset($request['sla_justificado'])) {
			$request['sla_justificado'] = $request['sla_justificado'];
		}

		if (isset($request['liquidado_proveedor'])) {
			$request['liquidado_proveedor'] = $request['liquidado_proveedor'];
		}

		if (isset($request['visible_cliente'])) {
			$request['visible_cliente'] = $request['visible_cliente'];
		}

		if (isset($request['chequeado_cliente'])) {
			$request['chequeado_cliente'] = $request['chequeado_cliente'];
		}

		if (isset($request['problema_resuelto'])) {
			$request['problema_resuelto'] = $request['problema_resuelto'];
		}

		if (isset($request['instalacion_buzon'])) {
			$request['instalacion_buzon'] = $request['instalacion_buzon'];
		}

		if (isset($request['requiere_nueva_visita'])) {
			$request['requiere_nueva_visita'] = $request['requiere_nueva_visita'];
		}


		//campos checklist
		/* $camposChecklist['alimentacion_definitiva'] = (isset($request['alimentacion_definitiva'])) ? 1 : 0;
		$camposChecklist['unidades_tension_definitiva'] = (isset($request['unidades_tension_definitiva'])) ? 1 : 0;
		$camposChecklist['cable_alimentacion_comunicacion_seccion_ok'] = (isset($request['cable_alimentacion_comunicacion_seccion_ok'])) ? 1 : 0;
		$camposChecklist['minimo_conexiones_frigorificas_exteriores'] = (isset($request['minimo_conexiones_frigorificas_exteriores'])) ? 1 : 0;
		$camposChecklist['sistema_presurizado_41_5_kg'] = (isset($request['sistema_presurizado_41_5_kg'])) ? 1 : 0;
		$camposChecklist['operacion_vacio'] = (isset($request['operacion_vacio'])) ? 1 : 0;
		$camposChecklist['llave_servicio_odu_abiertos'] = (isset($request['llave_servicio_odu_abiertos'])) ? 1 : 0;
		$camposChecklist['carga_adicional_introducida'] = (isset($request['carga_adicional_introducida'])) ? 1 : 0;
		$camposChecklist['sistema_funcionando_15_min_carga_adicional'] = (isset($request['sistema_funcionando_15_min_carga_adicional'])) ? 1 : 0;
		$camposChecklist['puesta_marcha_satisfactoria'] = (isset($request['puesta_marcha_satisfactoria'])) ? $request['puesta_marcha_satisfactoria'] : 0;
		$camposChecklist['sistema_presurizado_41_5_kg_tiempo_horas'] = (isset($request['sistema_presurizado_41_5_kg_tiempo_horas'])) ? $request['sistema_presurizado_41_5_kg_tiempo_horas'] : 0;
		$camposChecklist['unidad_exterior_tension_12_horas'] = (isset($request['unidad_exterior_tension_12_horas'])) ? $request['unidad_exterior_tension_12_horas'] : 0;
		$camposChecklist['carga_adicional_introducida_kg_final'] = (isset($request['carga_adicional_introducida_kg_final'])) ? $request['carga_adicional_introducida_kg_final'] : 0;
		$camposChecklist['carga_adicional_introducida_kg_adicional'] = (isset($request['carga_adicional_introducida_kg_adicional'])) ? $request['carga_adicional_introducida_kg_adicional'] : 0; */

		if (isset($request['alimentacion_definitiva'])) {
			$camposChecklist['alimentacion_definitiva'] = $request['alimentacion_definitiva'];
		}

		if (isset($request['unidades_tension_definitiva'])) {
			$camposChecklist['unidades_tension_definitiva'] = $request['unidades_tension_definitiva'];
		}

		if (isset($request['cable_alimentacion_comunicacion_seccion_ok'])) {
			$camposChecklist['cable_alimentacion_comunicacion_seccion_ok'] = $request['cable_alimentacion_comunicacion_seccion_ok'];
		}

		if (isset($request['minimo_conexiones_frigorificas_exteriores'])) {
			$camposChecklist['minimo_conexiones_frigorificas_exteriores'] = $request['minimo_conexiones_frigorificas_exteriores'];
		}

		if (isset($request['sistema_presurizado_41_5_kg'])) {
			$camposChecklist['sistema_presurizado_41_5_kg'] = $request['sistema_presurizado_41_5_kg'];
		}

		if (isset($request['operacion_vacio'])) {
			$camposChecklist['operacion_vacio'] = $request['operacion_vacio'];
		}

		if (isset($request['llave_servicio_odu_abiertos'])) {
			$camposChecklist['llave_servicio_odu_abiertos'] = $request['llave_servicio_odu_abiertos'];
		}

		if (isset($request['carga_adicional_introducida'])) {
			$camposChecklist['carga_adicional_introducida'] = $request['carga_adicional_introducida'];
		}

		if (isset($request['sistema_funcionando_15_min_carga_adicional'])) {
			$camposChecklist['sistema_funcionando_15_min_carga_adicional'] = $request['sistema_funcionando_15_min_carga_adicional'];
		}

		if (isset($request['puesta_marcha_satisfactoria'])) {
			$camposChecklist['puesta_marcha_satisfactoria'] = $request['puesta_marcha_satisfactoria'];
		}

		if (isset($request['sistema_presurizado_41_5_kg_tiempo_horas'])) {
			$camposChecklist['sistema_presurizado_41_5_kg_tiempo_horas'] = $request['sistema_presurizado_41_5_kg_tiempo_horas'];
		}

		if (isset($request['unidad_exterior_tension_12_horas'])) {
			$camposChecklist['unidad_exterior_tension_12_horas'] = $request['unidad_exterior_tension_12_horas'];
		}

		if (isset($request['carga_adicional_introducida_kg_final'])) {
			$camposChecklist['carga_adicional_introducida_kg_final'] = $request['carga_adicional_introducida_kg_final'];
		}

		if (isset($request['carga_adicional_introducida_kg_adicional'])) {
			$camposChecklist['carga_adicional_introducida_kg_adicional'] = $request['carga_adicional_introducida_kg_adicional'];
		}


		$camposChecklist['company_id'] = $userCompanyId;
		$camposChecklist['reparacion_onsite_id'] = $reparacionOnsite->id;
		//fin campos checklist

		//campos detalle
		$camposDetalle['reparacion_id'] = $reparacionOnsite->id;

		/* $camposDetalle['transacciones_pendientes'] = (isset($request['transacciones_pendientes'])) ? 1 : 0;
		$camposDetalle['impresora_termica_scanner'] = (isset($request['impresora_termica_scanner'])) ? 1 : 0;
		$camposDetalle['usuario_agentes'] = (isset($request['usuario_agentes'])) ? 1 : 0;
		$camposDetalle['usuario_agentes_red_local'] = (isset($request['usuario_agentes_red_local'])) ? $request['usuario_agentes_red_local'] : null;
		$camposDetalle['configuracion_impresora'] = (isset($request['configuracion_impresora'])) ? 1 : 0;
		$camposDetalle['usuarios_sf2'] = (isset($request['usuarios_sf2'])) ? 1 : 0;
		$camposDetalle['configuracion_pc_servidora'] = (isset($request['configuracion_pc_servidora'])) ? 1 : 0;
		$camposDetalle['conectividad_sf2_wut_dns_vnc'] = (isset($request['conectividad_sf2_wut_dns_vnc'])) ? 1 : 0;
		$camposDetalle['carpeta_sf2_permisos'] = (isset($request['carpeta_sf2_permisos'])) ? 1 : 0;
		$camposDetalle['tension_electrica'] = (isset($request['tension_electrica'])) ? 1 : 0;
		$camposDetalle['tipo_conexion_local'] = (isset($request['tipo_conexion_local'])) ? $request['tipo_conexion_local'] : null;
		$camposDetalle['tipo_conexion_proveedor'] = (isset($request['tipo_conexion_proveedor'])) ? $request['tipo_conexion_proveedor'] : null;
		$camposDetalle['cableado'] = (isset($request['cableado'])) ? 1 : 0;
		$camposDetalle['cableado_cantidad_metros'] = (isset($request['cableado_cantidad_metros'])) ? $request['cableado_cantidad_metros'] : null;
		$camposDetalle['cableado_cantidad_fichas'] = (isset($request['cableado_cantidad_fichas'])) ? $request['cableado_cantidad_fichas'] : null;
		$camposDetalle['instalacion_cartel'] = (isset($request['instalacion_cartel'])) ? 1 : 0;
		$camposDetalle['instalacion_cartel_luz'] = (isset($request['instalacion_cartel_luz'])) ? 1 : 0;
		$camposDetalle['insumos_banner'] = (isset($request['insumos_banner'])) ? 1 : 0;
		$camposDetalle['insumos_folleteria'] = (isset($request['insumos_folleteria'])) ? 1 : 0;
		$camposDetalle['insumos_rojos_impresora'] = (isset($request['insumos_rojos_impresora'])) ? 1 : 0;
		$camposDetalle['fotos_frente_local'] = (isset($request['fotos_frente_local'])) ? 1 : 0;
		$camposDetalle['fotos_modem_enlace_switch'] = (isset($request['fotos_modem_enlace_switch'])) ? 1 : 0;
		$camposDetalle['fotos_terminal_red'] = (isset($request['fotos_terminal_red'])) ? 1 : 0; */
		if (isset($request['transacciones_pendientes'])) {
			$camposDetalle['transacciones_pendientes'] = $request['transacciones_pendientes'];
		}

		if (isset($request['impresora_termica_scanner'])) {
			$camposDetalle['impresora_termica_scanner'] = $request['impresora_termica_scanner'];
		}

		if (isset($request['usuario_agentes'])) {
			$camposDetalle['usuario_agentes'] = $request['usuario_agentes'];
		}

		if (isset($request['usuario_agentes_red_local'])) {
			$camposDetalle['usuario_agentes_red_local'] = $request['usuario_agentes_red_local'];
		}

		if (isset($request['configuracion_impresora'])) {
			$camposDetalle['configuracion_impresora'] = $request['configuracion_impresora'];
		}

		if (isset($request['usuarios_sf2'])) {
			$camposDetalle['usuarios_sf2'] = $request['usuarios_sf2'];
		}

		if (isset($request['configuracion_pc_servidora'])) {
			$camposDetalle['configuracion_pc_servidora'] = $request['configuracion_pc_servidora'];
		}

		if (isset($request['conectividad_sf2_wut_dns_vnc'])) {
			$camposDetalle['conectividad_sf2_wut_dns_vnc'] = $request['conectividad_sf2_wut_dns_vnc'];
		}

		if (isset($request['carpeta_sf2_permisos'])) {
			$camposDetalle['carpeta_sf2_permisos'] = $request['carpeta_sf2_permisos'];
		}

		if (isset($request['tension_electrica'])) {
			$camposDetalle['tension_electrica'] = $request['tension_electrica'];
		}

		if (isset($request['tipo_conexion_local'])) {
			$camposDetalle['tipo_conexion_local'] = $request['tipo_conexion_local'];
		}

		if (isset($request['tipo_conexion_proveedor'])) {
			$camposDetalle['tipo_conexion_proveedor'] = $request['tipo_conexion_proveedor'];
		}

		if (isset($request['cableado'])) {
			$camposDetalle['cableado'] = $request['cableado'];
		}

		if (isset($request['cableado_cantidad_metros'])) {
			$camposDetalle['cableado_cantidad_metros'] = $request['cableado_cantidad_metros'];
		}

		if (isset($request['cableado_cantidad_fichas'])) {
			$camposDetalle['cableado_cantidad_fichas'] = $request['cableado_cantidad_fichas'];
		}

		if (isset($request['instalacion_cartel'])) {
			$camposDetalle['instalacion_cartel'] = $request['instalacion_cartel'];
		}

		if (isset($request['instalacion_cartel_luz'])) {
			$camposDetalle['instalacion_cartel_luz'] = $request['instalacion_cartel_luz'];
		}

		if (isset($request['insumos_banner'])) {
			$camposDetalle['insumos_banner'] = $request['insumos_banner'];
		}

		if (isset($request['insumos_folleteria'])) {
			$camposDetalle['insumos_folleteria'] = $request['insumos_folleteria'];
		}

		if (isset($request['insumos_rojos_impresora'])) {
			$camposDetalle['insumos_rojos_impresora'] = $request['insumos_rojos_impresora'];
		}

		if (isset($request['fotos_frente_local'])) {
			$camposDetalle['fotos_frente_local'] = $request['fotos_frente_local'];
		}

		if (isset($request['fotos_modem_enlace_switch'])) {
			$camposDetalle['fotos_modem_enlace_switch'] = $request['fotos_modem_enlace_switch'];
		}

		if (isset($request['fotos_terminal_red'])) {
			$camposDetalle['fotos_terminal_red'] = $request['fotos_terminal_red'];
		}



		//campos detalle activos
		/* $camposDetalle['codigo_activo_nuevo1'] = (isset($request['codigo_activo_nuevo1'])) ? $request['codigo_activo_nuevo1'] : null;
		$camposDetalle['codigo_activo_retirado1'] = (isset($request['codigo_activo_retirado1'])) ? $request['codigo_activo_retirado1'] : null;
		$camposDetalle['codigo_activo_descripcion1'] = (isset($request['codigo_activo_descripcion1'])) ? $request['codigo_activo_descripcion1'] : null;
		$camposDetalle['codigo_activo_nuevo2'] = (isset($request['codigo_activo_nuevo2'])) ? $request['codigo_activo_nuevo2'] : null;
		$camposDetalle['codigo_activo_retirado2'] = (isset($request['codigo_activo_retirado2'])) ? $request['codigo_activo_retirado2'] : null;
		$camposDetalle['codigo_activo_descripcion2'] = (isset($request['codigo_activo_descripcion2'])) ? $request['codigo_activo_descripcion2'] : null;
		$camposDetalle['codigo_activo_nuevo3'] = (isset($request['codigo_activo_nuevo3'])) ? $request['codigo_activo_nuevo3'] : null;
		$camposDetalle['codigo_activo_retirado3'] = (isset($request['codigo_activo_retirado3'])) ? $request['codigo_activo_retirado3'] : null;
		$camposDetalle['codigo_activo_descripcion3'] = (isset($request['codigo_activo_descripcion3'])) ? $request['codigo_activo_descripcion3'] : null;
		$camposDetalle['codigo_activo_nuevo4'] = (isset($request['codigo_activo_nuevo4'])) ? $request['codigo_activo_nuevo4'] : null;
		$camposDetalle['codigo_activo_retirado4'] = (isset($request['codigo_activo_retirado4'])) ? $request['codigo_activo_retirado4'] : null;
		$camposDetalle['codigo_activo_descripcion4'] = (isset($request['codigo_activo_descripcion4'])) ? $request['codigo_activo_descripcion4'] : null;
		$camposDetalle['codigo_activo_nuevo5'] = (isset($request['codigo_activo_nuevo5'])) ? $request['codigo_activo_nuevo5'] : null;
		$camposDetalle['codigo_activo_retirado5'] = (isset($request['codigo_activo_retirado5'])) ? $request['codigo_activo_retirado5'] : null;
		$camposDetalle['codigo_activo_descripcion5'] = (isset($request['codigo_activo_descripcion5'])) ? $request['codigo_activo_descripcion5'] : null;
		$camposDetalle['codigo_activo_nuevo6'] = (isset($request['codigo_activo_nuevo6'])) ? $request['codigo_activo_nuevo7'] : null;
		$camposDetalle['codigo_activo_retirado6'] = (isset($request['codigo_activo_retirado6'])) ? $request['codigo_activo_retirado7'] : null;
		$camposDetalle['codigo_activo_descripcion6'] = (isset($request['codigo_activo_descripcion6'])) ? $request['codigo_activo_descripcion7'] : null;
		$camposDetalle['codigo_activo_nuevo7'] = (isset($request['codigo_activo_nuevo7'])) ? $request['codigo_activo_nuevo7'] : null;
		$camposDetalle['codigo_activo_retirado7'] = (isset($request['codigo_activo_retirado7'])) ? $request['codigo_activo_retirado7'] : null;
		$camposDetalle['codigo_activo_descripcion7'] = (isset($request['codigo_activo_descripcion7'])) ? $request['codigo_activo_descripcion7'] : null;
		$camposDetalle['codigo_activo_nuevo8'] = (isset($request['codigo_activo_nuevo8'])) ? $request['codigo_activo_nuevo8'] : null;
		$camposDetalle['codigo_activo_retirado8'] = (isset($request['codigo_activo_retirado8'])) ? $request['codigo_activo_retirado8'] : null;
		$camposDetalle['codigo_activo_descripcion8'] = (isset($request['codigo_activo_descripcion8'])) ? $request['codigo_activo_descripcion8'] : null;
		$camposDetalle['codigo_activo_nuevo9'] = (isset($request['codigo_activo_nuevo9'])) ? $request['codigo_activo_nuevo9'] : null;
		$camposDetalle['codigo_activo_retirado9'] = (isset($request['codigo_activo_retirado9'])) ? $request['codigo_activo_retirado9'] : null;
		$camposDetalle['codigo_activo_descripcion9'] = (isset($request['codigo_activo_descripcion9'])) ? $request['codigo_activo_descripcion9'] : null;
		$camposDetalle['codigo_activo_nuevo10'] = (isset($request['codigo_activo_nuevo10'])) ? $request['codigo_activo_nuevo10'] : null;
		$camposDetalle['codigo_activo_retirado10'] = (isset($request['codigo_activo_retirado10'])) ? $request['codigo_activo_retirado10'] : null;
		$camposDetalle['codigo_activo_descripcion10'] = (isset($request['codigo_activo_descripcion10'])) ? $request['codigo_activo_descripcion10'] : null;
		$camposDetalle['modem_3g_4g_sim_nuevo'] = (isset($request['modem_3g_4g_sim_nuevo'])) ? $request['modem_3g_4g_sim_nuevo'] : null;
		$camposDetalle['modem_3g_4g_sim_retirado'] = (isset($request['modem_3g_4g_sim_retirado'])) ? $request['modem_3g_4g_sim_retirado'] : null; */

		$fields = [
			'codigo_activo_nuevo1',
			'codigo_activo_retirado1',
			'codigo_activo_descripcion1',
			'codigo_activo_nuevo2',
			'codigo_activo_retirado2',
			'codigo_activo_descripcion2',
			'codigo_activo_nuevo3',
			'codigo_activo_retirado3',
			'codigo_activo_descripcion3',
			'codigo_activo_nuevo4',
			'codigo_activo_retirado4',
			'codigo_activo_descripcion4',
			'codigo_activo_nuevo5',
			'codigo_activo_retirado5',
			'codigo_activo_descripcion5',
			'codigo_activo_nuevo6',
			'codigo_activo_retirado6',
			'codigo_activo_descripcion6',
			'codigo_activo_nuevo7',
			'codigo_activo_retirado7',
			'codigo_activo_descripcion7',
			'codigo_activo_nuevo8',
			'codigo_activo_retirado8',
			'codigo_activo_descripcion8',
			'codigo_activo_nuevo9',
			'codigo_activo_retirado9',
			'codigo_activo_descripcion9',
			'codigo_activo_nuevo10',
			'codigo_activo_retirado10',
			'codigo_activo_descripcion10',
			'modem_3g_4g_sim_nuevo',
			'modem_3g_4g_sim_retirado'
		];

		foreach ($fields as $field) {
			if (isset($request[$field])) {
				$camposDetalle[$field] = $request[$field];
			}
		}


		if ($newEstado && $oldEstado != $newEstado) {
			$newEstadoOnsite = $this->estado_onsite_repository->getEstadoOnsite($newEstado);
			//if ($newEstado == 4) {
			//if ($newEstado == EstadoOnsite::CERRADA ){
			if (is_object($newEstadoOnsite) && isset($newEstadoOnsite->cerrado) && $newEstadoOnsite->cerrado) {
				if (!isset($request['fecha_cerrado']) || empty($request['fecha_cerrado'])) {
					$request['fecha_cerrado'] = $sysdate;
				}
				if ($request['fecha_cerrado'] <= $request['fecha_vencimiento']) {

					$request['sla_status'] = 'IN';
				} else {

					$request['sla_status'] = 'OUT';
				}

				if (!isset($request['fecha_coordinada'])) {
					$request['fecha_coordinada'] = $sysdate;
				}
			}
			if ($newEstado == EstadoOnsite::NOTIFICADO) { //27) {
				$request['fecha_notificado'] = $sysdate;
			}
		}


		// si se ingresa por primera vez la fecha_coordinada, seteamos fecha_registracion_coordinacion
		if (isset($request['fecha_coordinada']) && !isset($reparacionOnsite->fecha_coordinada)) {
			$request['fecha_registracion_coordinacion'] = $sysdate;
		}

		// si se ingresa fecha cerrado, seteamos fecha coordinada
		if (isset($request['fecha_cerrado']) && $request['fecha_cerrado'] && (!isset($reparacionOnsite->fecha_coordinada) && empty($request['fecha_coordinada']))) {
			$request['fecha_coordinada'] = date('Y-m-d', strtotime($request['fecha_cerrado']));
		}



		$reparacionOnsite->update($request->all());

		if ($userCompanyId == Company::DEFAULT) {
			$reparacionDetalle = ReparacionDetalle::where('reparacion_id', $reparacionOnsite->id)->first();

			if (isset($reparacionDetalle)) {
				$reparacionDetalle->update($camposDetalle);
			} else {
				ReparacionDetalle::create($camposDetalle);
			}
		}
		if ($userCompanyId == Company::BGH) {
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
		}

		/* si cambia estado se registra nuevo historial estado y se envía emails */
		if ($newEstado && $oldEstado != $newEstado) {

			$estado = $newEstado; //nuevo
			$observacion = 'Reparación modificada Manualmente';
			if (isset($request['observacion'])) {
				$observacion = $request['observacion'];
			}
			if (is_null($user_id))
				$idUsuario = Auth::user()->id;
			else ($idUsuario = $user_id);


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
		if ($newTecnico) {
			if ($oldTecnico != $newTecnico) {
				$this->enviarMailTecnicoOnsiteAsignado($reparacionOnsite);
			}
		}

		/* agrega imagenes */

		$this->actualizarImagenes($reparacionOnsite, $request->all());

		return $reparacionOnsite;
	}
	public function destroy($idReparacionOnsite)
	{
		$reparacionOnsite = $this->findReparacion($idReparacionOnsite);

		// Validar que el usuario sea de la misma compañia que la terminal
		if (!in_array($reparacionOnsite->company_id, Session::get('userCompaniesId'))) {
			Session::flash('message-error', 'Sin Privilegios');
			return redirect('/terminalOnsite');
		}

		$reparacionDetalle = $this->findReparacionDetalle($idReparacionOnsite);
		$reparacionDetalle->delete();

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

	public function getDataEmpresasActivas()
	{
		$userCompanyId = Session::get('userCompanyIdDefault');

		$datos['user_id'] = Auth::user()->id;
		$datos['sysdate'] = date('Y-m-d H:i:s');

		$idEmpresaOnsite = $this->getEmpresasOnsiteUsuario();

		$listarSoloEstadosActivos = ['activos'];

		$datos['historialEstadosOnsite'] = array();

		//$tipoServicioArrayExclude = [TipoServicioOnsite::PUESTA_MARCHA, TipoServicioOnsite::SEGUIMIENTO_OBRA];

		$params = [
			'userCompanyId' => $userCompanyId,
			'idEmpresa' => $idEmpresaOnsite,
			'estadosActivos' => $listarSoloEstadosActivos,
			//	'tipoServicioArrayExclude' => $tipoServicioArrayExclude,
		];

		$datos['reparacionesOnsite'] = $this->listar($params);

		$datos['ruta'] = 'reparacionOnsiteEmpresaActivas';

		$dataEstado['company_id'] = $userCompanyId;
		$dataEstado['activo'] = true;

		$datos['empresasOnsite'] = $this->empresaOnsiteService->listadoAll($userCompanyId);
		$datos['tiposServicios']  = $this->tiposServiciosService->listado($userCompanyId);
		$datos['estadosOnsite'] = $this->estado_onsite_repository->getEstadosByParams($dataEstado);
		$datos['tecnicosOnsite'] = $this->userService->listarTecnicosOnsite($userCompanyId);

		return $datos;
	}



	public function getDataEmpresaCerradas(Request $request)
	{
		$userCompanyId = Session::get('userCompanyIdDefault');
		$userId = Auth::user()->id;
		$datos['sysdate'] = date('Y-m-d H:i:s');

		$idEmpresaOnsite = $this->getEmpresasOnsiteUsuario();
		$listarSoloEstadosActivos = false;
		$datos['historialEstadosOnsite'] = array();

		//$tipoServicioArrayExclude = [TipoServicioOnsite::PUESTA_MARCHA, TipoServicioOnsite::SEGUIMIENTO_OBRA];

		$params = [
			'userCompanyId' => $userCompanyId,
			'idEmpresa' => $idEmpresaOnsite,
			'visible_cliente' => 1,
			'cerrado' => 1,
			//'tipoServicioArrayExclude' => $tipoServicioArrayExclude,
		];

		if (!empty($request['select_confirmadas'])) {
			if ($request['select_confirmadas'] != 'TODAS') {
				$params['chequeado_cliente'] = (int) $request['select_confirmadas'];
			}
			$select_confirmadas = $request['select_confirmadas'];
		} else {
			// Si no vino el parametro el default es sin confirmar
			$params['chequeado_cliente'] = 0;
			$select_confirmadas = 0;
		}

		$datos['select_confirmadas'] = $select_confirmadas;
		$datos['reparacionesOnsite'] = $this->listar($params);

		$datos['user_id'] = $userId;
		$datos['ruta'] = 'reparacionOnsiteEmpresaCerradas';

		$dataEstado['company_id'] = $userCompanyId;
		$dataEstado['cerrado'] = true;

		$datos['empresasOnsite'] = $this->empresaOnsiteService->listadoAll($userCompanyId);
		$datos['tiposServicios']  = $this->tiposServiciosService->listado($userCompanyId);
		$datos['estadosOnsite'] = $this->estado_onsite_repository->getEstadosByParams($dataEstado);
		$datos['tecnicosOnsite'] = $this->userService->listarTecnicosOnsite($userCompanyId);

		return $datos;
	}



	public function filtrarReparacionOnsiteActivas(Request $request)
	{
		//$idEmpresaOnsite = $this->getEmpresasOnsiteUsuario();

		//$request['id_empresa'] = $idEmpresaOnsite;
		$request['estados_activo'] = true;
		$request['activo'] = true;

		$reparacionesActivasFiltradas = $this->filtrarReparacionOnsite($request);

		$reparacionesActivasFiltradas['ruta'] = 'reparacionOnsiteEmpresaActivas';
		$reparacionesActivasFiltradas['vista'] = '_onsite.reparaciononsite.indexEmpresaActivas';

		return $reparacionesActivasFiltradas;
	}

	public function filtrarReparacionOnsiteCerradas(Request $request)
	{

		$request['estados_activo'] = false;
		$request['cerrado'] = true;

		if (!empty($request['select_confirmadas'])) {
			if ($request['select_confirmadas'] != 'TODAS') {
				$request['chequeado_cliente'] = (int) $request['select_confirmadas'];
			}
			$select_confirmadas = $request['select_confirmadas'];
		} else {
			// Si no vino el parametro el default es sin confirmar
			$request['chequeado_cliente'] = 0;
			$select_confirmadas = 0;
		}


		$reparacionesActivasFiltradas = $this->filtrarReparacionOnsite($request);

		$reparacionesActivasFiltradas['select_confirmadas'] = $select_confirmadas;

		$reparacionesActivasFiltradas['ruta'] = 'reparacionOnsiteEmpresaCerradas';
		$reparacionesActivasFiltradas['vista'] = '_onsite.reparaciononsite.indexEmpresaCerradas';

		return $reparacionesActivasFiltradas;
	}

	public function filtrarReparacionOnsite(Request $request)
	{
		$userCompanyId = Session::get('userCompanyIdDefault');
		$userId = Auth::user()->id;

		$data['company_id'] = $userCompanyId;
		$data['activo'] = (isset($request['activo']) ? $request['activo'] : null);
		$data['cerrado'] = (isset($request['cerrado']) ? $request['cerrado'] : null);
		$datos = $this->getData($data);

		$datos['userCompanyId'] = $userCompanyId;
		$datos['sysdate'] = date('Y-m-d H:i:s');
		$datos['user_id'] = $userId;

		//params
		$datos['texto'] = (isset($request['texto']) ? $request['texto'] : null);
		$datos['id_empresa'] = (isset($request['id_empresa']) ? $request['id_empresa'] : null);
		$datos['id_terminal'] = (isset($request['terminal_onsite']) ? $request['terminal_onsite'] : null);
		$datos['id_tipo_servicio'] = (isset($request['id_tipo_servicio']) ? $request['id_tipo_servicio'] : null);
		$datos['id_estado'] = (isset($request['id_estado']) ? $request['id_estado'] : null);
		$datos['id_tecnico'] = (isset($request['id_tecnico']) ? $request['id_tecnico'] : null);
		$datos['fecha_vencimiento'] = (isset($request['fecha_vencimiento']) ? $request['fecha_vencimiento'] : null);
		$datos['estados_activo'] = (isset($request['estados_activo']) ? $request['estados_activo'] : ['activos']);
		$datos['liquidado_proveedor'] = (isset($request['liquidado_proveedor']) ? $request['liquidado_proveedor'] : null);
		$datos['sucursal_onsite'] = (isset($request['sucursal_onsite']) ? $request['sucursal_onsite'] : null);
		$datos['terminal_onsite'] = (isset($request['terminal_onsite']) ? $request['terminal_onsite'] : null);
		$datos['includeEmpresa'] = (isset($request['includeEmpresa']) ? $request['includeEmpresa'] : null);
		$datos['excludeEmpresa'] = (isset($request['excludeEmpresa']) ? $request['excludeEmpresa'] : null);
		$datos['chequeado_cliente'] = (isset($request['chequeado_cliente']) ? $request['chequeado_cliente'] : null);

		$ruta = $request['ruta'];
		$vista = '_onsite.reparaciononsite.index';

		$generarCsv = ($request['boton'] == 'csv');
		$datos['historialEstadosOnsite'] = array();

		if ($ruta == 'reparacionOnsiteFacturada') {
			$vista = '_onsite.reparaciononsite.indexFacturada';
		}
		if ($ruta == 'reparacionOnsitePosnet') {
			$vista = '_onsite.reparaciononsite.index';
		}

		$datos['ruta'] = $ruta;
		$datos['vista'] = $vista;

		//$tipoServicioArrayExclude = [TipoServicioOnsite::PUESTA_MARCHA, TipoServicioOnsite::SEGUIMIENTO_OBRA];

		$params = [
			'userCompanyId' => $userCompanyId,
			'texto' => $datos['texto'],
			'idEmpresa' => $datos['id_empresa'],
			'idTerminal' => $datos['id_terminal'],
			'idTipoServicio' => $datos['id_tipo_servicio'],
			'idEstado' => $datos['id_estado'],
			'idTecnico' => $datos['id_tecnico'],
			'fechaVencimiento' => $datos['fecha_vencimiento'],
			'estadosActivos' => $datos['estados_activo'],
			'liquidadoProveedor' => $datos['liquidado_proveedor'],
			'includeEmpresa' => $datos['includeEmpresa'],
			'excludeEmpresa' => $datos['excludeEmpresa'],
			'sucursalOnsite' => $datos['sucursal_onsite'],
			'terminalOnsite' => $datos['terminal_onsite'],
			'chequeado_cliente' => $datos['chequeado_cliente'],
			//'tipoServicioArrayExclude' => $tipoServicioArrayExclude,
		];

		$datos['reparacionesOnsite'] = $this->listar($params);

		if ($generarCsv) {
			$this->generarXlsx(
				$userCompanyId,
				$datos['texto'],
				$datos['id_empresa'],
				$datos['id_terminal'],
				$datos['id_tipo_servicio'],
				$datos['id_estado'],
				$datos['id_tecnico'],
				$datos['fecha_vencimiento'],
				$datos['estados_activo'],
				$datos['liquidado_proveedor'],
				$datos['includeEmpresa'],
				$datos['excludeEmpresa'],
				$datos['sucursal_onsite'],
				$datos['terminal_onsite'],
				$userId
			);
		}
		return $datos;
	}


	public function reparacionOnsiteChequeadoPorCliente($reparacionOnsiteId)
	{
		$reparacionOnsite = ReparacionOnsite::where('id', $reparacionOnsiteId)->first();

		if ($reparacionOnsite) {
			$reparacionOnsite->chequeado_cliente = true;
			$reparacionOnsite->save();
		}

		return $reparacionOnsite;
	}

	public function getDataIndexFacturada()
	{
		$userCompanyId = Session::get('userCompanyIdDefault');
		$datos['sysdate'] = date('Y-m-d H:i:s');

		$data['company_id'] = $userCompanyId;
		$datos = $this->getData($data);

		$datos['historialEstadosOnsite'] = array();

		$idEstadoFacturada = EstadoOnsiteRepository::ESTADO_FACTURADO;

		$tipoServicioArrayExclude = [TipoServicioOnsite::PUESTA_MARCHA, TipoServicioOnsite::SEGUIMIENTO_OBRA];

		$params = [
			'userCompanyId' => $userCompanyId,
			'idEstado' => $idEstadoFacturada,
			'tipoServicioArrayExclude' => $tipoServicioArrayExclude,
		];

		$datos['reparacionesOnsite'] = $this->listar($params);
		$datos['ruta'] = 'reparacionOnsiteFacturada';
		$datos['user_id'] = Auth::user()->id;

		return $datos;
	}

	public function getDataSoporteReparacionOnsite()
	{
		$userCompanyId = Session::get('userCompanyIdDefault');

		$datos['niveles'] = $this->nivelesOnsiteService->findNivelesOnsiteAll();
		$datos['tiposServicios'] = $this->tiposServiciosService->findTiposServiciosOnsiteAll();
		$datos['solicitudesTipos'] = $this->solicitudesTiposServices->getAllSolicitudesTipos();
		$datos['estados'] = $this->estado_onsite_repository->findEstadosOnsiteAll();
		$datos['provincias'] = $this->provinciasService->findProvinciasAll();
		$datos['empresas'] = $this->empresaOnsiteService->listadoAll($userCompanyId);

		return $datos;
	}

	public function generarCsv($userCompanyId, $texto, $idEmpresa, $idTerminal, $idTipoServicio, $idEstado, $idTecnico, $fechaVencimiento, $estadosActivos, $liquidadoProveedor, $excludeEmpresa, $sucursalOnsite, $terminalOnsite, $userId)
	{
		$saltear = 0;
		$tomar = 5000;

		$fp = fopen("exports/listado_reparaciononsite_" . $userId . ".csv", 'w');

		$cabecera = array(
			'ID',
			'ID_EMPRESA_ONSITE',
			'EMPRESA_ONSITE',
			'CLAVE',
			'ID_TERMINAL',
			'TERMINAL',
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
		);

		fputcsv($fp, $cabecera, ';');

		$params = [
			'userCompanyId' => $userCompanyId,
			'texto' => $texto,
			'idEmpresa' => $idEmpresa,
			'idTerminal' => $idTerminal,
			'idTipoServicio' => $idTipoServicio,
			'idEstado' => $idEstado,
			'idTecnico' => $idTecnico,
			'fechaVencimiento' => $fechaVencimiento,
			'estadosActivos' => $estadosActivos,
			'liquidadoProveedor' => $liquidadoProveedor,
			'excludeEmpresa' => $excludeEmpresa,
			'sucursalOnsite' => $sucursalOnsite,
			'terminalOnsite' => $terminalOnsite,
			'saltear' => $saltear,
			'tomar' => $tomar,
		];

		$reparacionesOnsite = $this->listar($params);

		while ($reparacionesOnsite->count()) {

			foreach ($reparacionesOnsite as $reparacionOnsite) {
				$fila = array(
					'id' => $reparacionOnsite->id,

					'id_empresa_onsite' => $reparacionOnsite->id_empresa_onsite,
					'empresa_onsite' => ($reparacionOnsite->empresa_onsite) ? $reparacionOnsite->empresa_onsite->nombre : '',
					'clave' => $reparacionOnsite->clave,
					'id_terminal' => $reparacionOnsite->id_terminal,
					'terminal' => ($reparacionOnsite->terminal_onsite) ? $reparacionOnsite->terminal_onsite->nro . ' - ' . $reparacionOnsite->terminal_onsite->marca . ' - ' . $reparacionOnsite->terminal_onsite->modelo . ' - ' . $reparacionOnsite->terminal_onsite->serie . ' - ' . $reparacionOnsite->terminal_onsite->rotulo : '',
					'tarea' => $reparacionOnsite->tarea,
					'detalle_tarea' => $reparacionOnsite->tarea_detalle,
					'id_tipo_servicio' => $reparacionOnsite->id_tipo_servicio,
					'tipo_servicio' => ($reparacionOnsite->tipoServicioOnsite) ? $reparacionOnsite->tipoServicioOnsite->nombre : '',
					'id_estado' => $reparacionOnsite->id_estado,
					'estado' => ($reparacionOnsite->estadoOnsite) ? $reparacionOnsite->estadoOnsite->nombre : '',
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

				);

				fputcsv($fp, $fila, ';');
			}

			$saltear = $saltear + 5000;

			$params = [
				'userCompanyId' => $userCompanyId,
				'texto' => $texto,
				'idEmpresa' => $idEmpresa,
				'idTerminal' => $idTerminal,
				'idTipoServicio' => $idTipoServicio,
				'idEstado' => $idEstado,
				'idTecnico' => $idTecnico,
				'fechaVencimiento' => $fechaVencimiento,
				'estadosActivos' => $estadosActivos,
				'liquidadoProveedor' => $liquidadoProveedor,
				'excludeEmpresa' => $excludeEmpresa,
				'sucursalOnsite' => $sucursalOnsite,
				'terminalOnsite' => $terminalOnsite,
				'saltear' => $saltear,
				'tomar' => $tomar,
			];

			$reparacionesOnsite = $this->listar($params);
		}

		fclose($fp);
	}


	public function generarXlsx($userCompanyId, $texto, $idEmpresa, $idTerminal, $idTipoServicio, $idEstado, $idTecnico, $fechaVencimiento, $estadosActivos, $liquidadoProveedor, $includeEmpresa, $excludeEmpresa, $sucursalOnsite, $terminalOnsite, $userId)
	{
		$saltear = 0;
		$tomar = 5000;

		$filename = "listado_reparaciononsite_" . $userId . ".xlsx";

		$data[] = [
			'ID',
			'ID_EMPRESA_ONSITE',
			'EMPRESA_ONSITE',
			'CLAVE',
			'ID_TERMINAL',
			'TERMINAL',
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
			'FECHA_1_VISITA',
			'FECHA_1_VENCIMIENTO',
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
			'idEmpresa' => $idEmpresa,
			'idTerminal' => $idTerminal,
			'idTipoServicio' => $idTipoServicio,
			'idEstado' => $idEstado,
			'idTecnico' => $idTecnico,
			'fechaVencimiento' => $fechaVencimiento,
			'estadosActivos' => $estadosActivos,
			'liquidadoProveedor' => $liquidadoProveedor,
			'excludeEmpresa' => $excludeEmpresa,
			'sucursalOnsite' => $sucursalOnsite,
			'terminalOnsite' => $terminalOnsite,
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
					'id_terminal' => $reparacionOnsite->id_terminal,
					'terminal' => ($reparacionOnsite->terminal_onsite) ? $reparacionOnsite->terminal_onsite->nro . ' - ' . $reparacionOnsite->terminal_onsite->marca . ' - ' . $reparacionOnsite->terminal_onsite->modelo . ' - ' . $reparacionOnsite->terminal_onsite->serie . ' - ' . $reparacionOnsite->terminal_onsite->rotulo : '',
					'tarea' => $reparacionOnsite->tarea,
					'detalle_tarea' => $reparacionOnsite->tarea_detalle,
					'id_tipo_servicio' => $reparacionOnsite->id_tipo_servicio,
					'tipo_servicio' => ($reparacionOnsite->tipoServicioOnsite) ? $reparacionOnsite->tipoServicioOnsite->nombre : '',
					'id_estado' => $reparacionOnsite->id_estado,
					'estado' => ($reparacionOnsite->estadoOnsite) ? $reparacionOnsite->estadoOnsite->nombre : '',
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

					'fecha_1_visita' => $reparacionOnsite->primer_visita->fecha ?? null,
					'fecha_1_vencimiento' => $reparacionOnsite->primer_visita->fecha_vencimiento ?? null,

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
				'idEmpresa' => $idEmpresa,
				'idTerminal' => $idTerminal,
				'idTipoServicio' => $idTipoServicio,
				'idEstado' => $idEstado,
				'idTecnico' => $idTecnico,
				'fechaVencimiento' => $fechaVencimiento,
				'estadosActivos' => $estadosActivos,
				'liquidadoProveedor' => $liquidadoProveedor,
				'excludeEmpresa' => $excludeEmpresa,
				'sucursalOnsite' => $sucursalOnsite,
				'terminalOnsite' => $terminalOnsite,
				'saltear' => $saltear,
				'tomar' => $tomar,
			];

			$reparacionesOnsite = $this->listar($params);
		}

		$excelController = new GenericExport($data, $filename);
		$excelController->export();
	}

	public function generarCsvExtendido($userCompanyId, $texto, $idEmpresa, $idTerminal, $idTipoServicio, $idEstado, $idTecnico, $fechaVencimiento, $estadosActivo, $liquidadoProveedor, $excludeEmpresa, $extendido, $sucursalOnsite, $terminalOnsite)
	{
		$saltear = 0;
		$tomar = 5000;
		$userId = Auth::user()->id;
		$fp = fopen("exports/listado_reparaciononsite_extendido_" . $userId . ".csv", 'w');

		$cabecera = array(
			'ID',
			'CLAVE',
			'ID_EMPRESA_ONSITE',
			'EMPRESA_ONSITE',
			'SUCURSAL_ONSITE_ID',
			'SUCURSAL_RAZON_SOCIAL',
			'SUCURSAL_DIRECCION',
			'SUCURSAL_TELEFONO',
			'ID_TERMINAL',
			'TERMINAL_MARCA',
			'TERMINAL_MODELO',
			'TERMINAL_SERIE',
			'TERMINAL_ROTULO',
			'ID_LOCALIDAD',
			'LOCALIDAD',
			'LOCALIDAD_ID_PROVINCIA',
			'LOCALIDAD_PROVINCIA',
			'LOCALIDAD_ESTANDARD',
			'LOCALIDAD_CODIGO_POSTAL',
			'LOCALIDAD_KM',
			'LOCALIDAD_ID_NIVEL',
			'LOCALIDAD_NIVEL',
			'LOCALIDAD_ATIENDE_DESDE',
			'LOCALIDAD_ID_TECNICO',
			'LOCALIDAD_TECNICO',
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
			'INFORME_TECNICO',
			'FECHA_COORDINADA',
			'VENTANA_HORARIA_COORDINADA',
			'FECHA_REGISTRACION_COORDINACION',
			'FECHA_NOTIFICADO',

			'FECHA_1_VISITA',
			'FECHA_1_VENCIMIENTO',

			'FECHA_VENCIMIENTO',
			'FECHA_CERRADO',
			'SLA_STATUS',
			'SLA_JUSTIFICADO',
			'MONTO',
			'MONTO_EXTRA',
			'LIQUIDADO_PROVEEDOR',
			'NRO_FACTURA_PROVEEDOR',

			'TIPO_CONEXION_LOCAL',
			'TIPO_CONEXION_PROVEEDOR',
			'CABLEADO',
			'CABLEADO_CANTIDAD_METROS',
			'CABLEADO_CANTIDAD_FICHAS',
			'INSTALACION_CARTEL',
			'INSTALACION_CARTEL_LUZ',
			'INSTALACION_BUZON',
			'CANTIDAD_HORAS_TRABAJO',
			'REQUIERE_NUEVA_VISITA',
			'CODIGO_ACTIVO_NUEVO1',
			'CODIGO_ACTIVO_RETIRADO1',
			'CODIGO_ACTIVO_DESCRIPCION1',
			'CODIGO_ACTIVO_NUEVO2',
			'CODIGO_ACTIVO_RETIRADO2',
			'CODIGO_ACTIVO_DESCRIPCION2',
			'CODIGO_ACTIVO_NUEVO3',
			'CODIGO_ACTIVO_RETIRADO3',
			'CODIGO_ACTIVO_DESCRIPCION3',
			'CODIGO_ACTIVO_NUEVO4',
			'CODIGO_ACTIVO_RETIRADO4',
			'CODIGO_ACTIVO_DESCRIPCION4',
			'CODIGO_ACTIVO_NUEVO5',
			'CODIGO_ACTIVO_RETIRADO5',
			'CODIGO_ACTIVO_DESCRIPCION5',
			'CODIGO_ACTIVO_NUEVO6',
			'CODIGO_ACTIVO_RETIRADO6',
			'CODIGO_ACTIVO_DESCRIPCION6',
			'CODIGO_ACTIVO_NUEVO7',
			'CODIGO_ACTIVO_RETIRADO7',
			'CODIGO_ACTIVO_DESCRIPCION7',
			'CODIGO_ACTIVO_NUEVO8',
			'CODIGO_ACTIVO_RETIRADO8',
			'CODIGO_ACTIVO_DESCRIPCION8',
			'CODIGO_ACTIVO_NUEVO9',
			'CODIGO_ACTIVO_RETIRADO9',
			'CODIGO_ACTIVO_DESCRIPCION9',
			'CODIGO_ACTIVO_NUEVO10',
			'CODIGO_ACTIVO_RETIRADO10',
			'CODIGO_ACTIVO_DESCRIPCION10',
			'MODEM_3G_4G_SIM_NUEVO',
			'MODEM_3G_4G_SIM_RETIRADO',
			'FIRMA_CLIENTE',
			'ACLARACION_CLIENTE',
			'FIRMA_TECNICO',
			'ACLARACION_TECNICO',

		);

		fputcsv($fp, $cabecera, ';');

		$params = [
			'userCompanyId' => $userCompanyId,
			'texto' => $texto,
			'idEmpresa' => $idEmpresa,
			'idTerminal' => $idTerminal,
			'idTipoServicio' => $idTipoServicio,
			'idEstado' => $idEstado,
			'idTecnico' => $idTecnico,
			'fechaVencimiento' => $fechaVencimiento,
			'estadosActivos' => $estadosActivo,
			'liquidadoProveedor' => $liquidadoProveedor,
			'excludeEmpresa' => $excludeEmpresa,
			'sucursalOnsite' => $sucursalOnsite,
			'terminalOnsite' => $terminalOnsite,
			'saltear' => $saltear,
			'tomar' => $tomar,
			'extendida' => $extendido,
		];

		$reparacionesOnsite = $this->listar($params);

		while ($reparacionesOnsite->count()) {

			foreach ($reparacionesOnsite as $reparacionOnsite) {

				$localidad = ($reparacionOnsite->sucursal_onsite && $reparacionOnsite->sucursal_onsite->localidad_onsite) ? $reparacionOnsite->sucursal_onsite->localidad_onsite : null;
				$terminal = ($reparacionOnsite->terminal_onsite) ? $reparacionOnsite->terminal_onsite : null;

				$fila = array(
					'id' => $reparacionOnsite->id,
					'clave' => $reparacionOnsite->clave,
					'id_empresa_onsite' => $reparacionOnsite->id_empresa_onsite,
					'empresa_onsite' => ($reparacionOnsite->empresa_onsite) ? $reparacionOnsite->empresa_onsite->nombre : '',
					'sucursal_onsite_id' => $reparacionOnsite->sucursal_onsite_id,
					'sucursal_razon_social' => $reparacionOnsite->sucursal_onsite->razon_social,
					'sucursal_direccion' => $reparacionOnsite->sucursal_onsite->direccion,
					'sucursal_telefono' => $reparacionOnsite->sucursal_onsite->telefono_contacto,
					'id_terminal' => $reparacionOnsite->id_terminal,
					'terminal_marca' => ($terminal) ? $terminal->marca : '',
					'terminal_modelo' => ($terminal) ? $terminal->modelo : '',
					'terminal_serie' => ($terminal) ? $terminal->serie : '',
					'terminal_rotulo' => ($terminal) ? $terminal->rotulo : '',
					'id_localidad' => ($reparacionOnsite->sucursal_onsite) ? $reparacionOnsite->sucursal_onsite->localidad_onsite_id : '',
					'localidad' => ($localidad) ? $localidad->localidad : '',
					'localidad_id_provincia' => ($localidad) ? $localidad->id_provincia : '',
					'localidad_provincia' => ($localidad && $localidad->provincia) ? $localidad->provincia->nombre : '',
					'localidad_estandard' => ($localidad) ? $localidad->localidad_estandard : '',
					'localidad_codigo_postal' => ($localidad) ? $localidad->codigo : '',
					'localidad_km' => ($localidad) ? $localidad->km : '',
					'localidad_id_nivel' => ($localidad) ? $localidad->id_nivel : '',
					'localidad_nivel' => ($localidad && $localidad->nivelOnsite) ? $localidad->nivelOnsite->nombre : '',
					'localidad_atiende_desde' => ($localidad) ? $localidad->atiende_desde : '',
					'localidad_id_tecnico' => ($localidad) ? $localidad->id_usuario_tecnico : '',
					'localidad_tecnico' => ($localidad && $localidad->usuarioTecnico) ? $localidad->usuarioTecnico->name : '',
					'tarea' => $reparacionOnsite->tarea,
					'detalle_tarea' => $reparacionOnsite->tarea_detalle,
					'id_tipo_servicio' => $reparacionOnsite->id_tipo_servicio,
					'tipo_servicio' => ($reparacionOnsite->tipo_servicio_onsite) ? $reparacionOnsite->tipo_servicio_onsite->nombre : '',
					'id_estado' => $reparacionOnsite->id_estado,
					'estado' => ($reparacionOnsite->estado_onsite) ? $reparacionOnsite->estado_onsite->nombre : '',
					'fecha_ingreso' => $reparacionOnsite->fecha_ingreso,
					'observacion_ubicacion' => $reparacionOnsite->observacion_ubicacion,
					'id_tecnico_asignado' => $reparacionOnsite->id_tecnico_asignado,
					'tecnico_asignado' => ($reparacionOnsite->tecnicoAsignado) ? $reparacionOnsite->tecnicoAsignado->name : '',
					'informe_tecnico' => $reparacionOnsite->informe_tecnico,
					'fecha_coordinada' => $reparacionOnsite->fecha_coordinada,
					'ventana_horaria_coordinada' => $reparacionOnsite->ventana_horaria_coordinada,
					'fecha_registracion_coordinacion' => $reparacionOnsite->fecha_registracion_coordinacion,
					'fecha_notificado' => $reparacionOnsite->fecha_notificado,

					'fecha_1_visita' => $reparacionOnsite->primer_visita->fecha ?? null,
					'fecha_1_vencimiento' => $reparacionOnsite->primer_visita->fecha_vencimiento ?? null,


					'fecha_vencimiento' => $reparacionOnsite->fecha_vencimiento,
					'fecha_cerrado' => $reparacionOnsite->fecha_cerrado,
					'sla_status' => $reparacionOnsite->sla_status,
					'sla_justificado' => $reparacionOnsite->sla_justificado,
					'monto' => $reparacionOnsite->monto,
					'monto_extra' => $reparacionOnsite->monto_extra,
					'liquidado_proveedor' => $reparacionOnsite->liquidado_proveedor,
					'nro_factura_proveedor' => $reparacionOnsite->nro_factura_proveedor,

					'tipo_conexion_local' => $reparacionOnsite->tipo_conexion_local,
					'tipo_conexion_proveedor' => $reparacionOnsite->tipo_conexion_proveedor,
					'cableado' => $reparacionOnsite->cableado,
					'cableado_cantidad_metros' => $reparacionOnsite->cableado_cantidad_metros,
					'cableado_cantidad_fichas' => $reparacionOnsite->cableado_cantidad_fichas,
					'instalacion_cartel' => $reparacionOnsite->instalacion_cartel,
					'instalacion_cartel_luz' => $reparacionOnsite->instalacion_cartel_luz,
					'instalacion_buzon' => $reparacionOnsite->instalacion_buzon,
					'cantidad_horas_trabajo' => $reparacionOnsite->cantidad_horas_trabajo,
					'requiere_nueva_visita' => $reparacionOnsite->requiere_nueva_visita,
					'codigo_activo_nuevo1' => $reparacionOnsite->codigo_activo_nuevo1,
					'codigo_activo_retirado1' => $reparacionOnsite->codigo_activo_retirado1,
					'codigo_activo_descripcion1' => $reparacionOnsite->codigo_activo_descripcion1,
					'codigo_activo_nuevo2' => $reparacionOnsite->codigo_activo_nuevo2,
					'codigo_activo_retirado2' => $reparacionOnsite->codigo_activo_retirado2,
					'codigo_activo_descripcion2' => $reparacionOnsite->codigo_activo_descripcion2,
					'codigo_activo_nuevo3' => $reparacionOnsite->codigo_activo_nuevo3,
					'codigo_activo_retirado3' => $reparacionOnsite->codigo_activo_retirado3,
					'codigo_activo_descripcion3' => $reparacionOnsite->codigo_activo_descripcion3,
					'codigo_activo_nuevo4' => $reparacionOnsite->codigo_activo_nuevo4,
					'codigo_activo_retirado4' => $reparacionOnsite->codigo_activo_retirado4,
					'codigo_activo_descripcion4' => $reparacionOnsite->codigo_activo_descripcion4,
					'codigo_activo_nuevo5' => $reparacionOnsite->codigo_activo_nuevo5,
					'codigo_activo_retirado5' => $reparacionOnsite->codigo_activo_retirado5,
					'codigo_activo_descripcion5' => $reparacionOnsite->codigo_activo_descripcion5,
					'codigo_activo_nuevo6' => $reparacionOnsite->codigo_activo_nuevo6,
					'codigo_activo_retirado6' => $reparacionOnsite->codigo_activo_retirado6,
					'codigo_activo_descripcion6' => $reparacionOnsite->codigo_activo_descripcion6,
					'codigo_activo_nuevo7' => $reparacionOnsite->codigo_activo_nuevo7,
					'codigo_activo_retirado7' => $reparacionOnsite->codigo_activo_retirado7,
					'codigo_activo_descripcion7' => $reparacionOnsite->codigo_activo_descripcion7,
					'codigo_activo_nuevo8' => $reparacionOnsite->codigo_activo_nuevo8,
					'codigo_activo_retirado8' => $reparacionOnsite->codigo_activo_retirado8,
					'codigo_activo_descripcion8' => $reparacionOnsite->codigo_activo_descripcion8,
					'codigo_activo_nuevo9' => $reparacionOnsite->codigo_activo_nuevo9,
					'codigo_activo_retirado9' => $reparacionOnsite->codigo_activo_retirado9,
					'codigo_activo_descripcion9' => $reparacionOnsite->codigo_activo_descripcion9,
					'codigo_activo_nuevo10' => $reparacionOnsite->codigo_activo_nuevo10,
					'codigo_activo_retirado10' => $reparacionOnsite->codigo_activo_retirado10,
					'codigo_activo_descripcion10' => $reparacionOnsite->codigo_activo_descripcion10,
					'modem_3g_4g_sim_nuevo' => $reparacionOnsite->modem_3g_4g_sim_nuevo,
					'modem_3g_4g_sim_retirado' => $reparacionOnsite->modem_3g_4g_sim_retirado,
					'firma_cliente' => $reparacionOnsite->firma_cliente,
					'aclaracion_cliente' => $reparacionOnsite->aclaracion_cliente,
					'firma_tecnico' => $reparacionOnsite->firma_tecnico,
					'aclaracion_tecnico' => $reparacionOnsite->aclaracion_tecnico,

				);

				fputcsv($fp, $fila, ';');
			}

			$saltear = $saltear + $tomar;

			$params = [
				'userCompanyId' => $userCompanyId,
				'texto' => $texto,
				'idEmpresa' => $idEmpresa,
				'idTerminal' => $idTerminal,
				'idTipoServicio' => $idTipoServicio,
				'idEstado' => $idEstado,
				'idTecnico' => $idTecnico,
				'fechaVencimiento' => $fechaVencimiento,
				'estadosActivos' => $estadosActivo,
				'liquidadoProveedor' => $liquidadoProveedor,
				'excludeEmpresa' => $excludeEmpresa,
				'sucursalOnsite' => $sucursalOnsite,
				'terminalOnsite' => $terminalOnsite,
				'saltear' => $saltear,
				'tomar' => $tomar,
				'extendida' => $extendido,
			];

			$reparacionesOnsite = $this->listar($params);
		}

		fclose($fp);
	}

	public function generarXlsxExtendido($param)
	{
		$saltear = 0;
		$tomar = 5000;
		$userId = Auth::user()->id;
		$filename = "listado_reparaciononsite_extendido_" . $userId . ".xlsx";

		$data[] = [
			'ID',
			'CLAVE',
			'ID_EMPRESA_ONSITE',
			'EMPRESA_ONSITE',
			'SUCURSAL_ONSITE_ID',
			'SUCURSAL_RAZON_SOCIAL',
			'SUCURSAL_DIRECCION',
			'SUCURSAL_TELEFONO',
			'ID_TERMINAL',
			'TERMINAL_MARCA',
			'TERMINAL_MODELO',
			'TERMINAL_SERIE',
			'TERMINAL_ROTULO',
			'ID_LOCALIDAD',
			'LOCALIDAD',
			'LOCALIDAD_ID_PROVINCIA',
			'LOCALIDAD_PROVINCIA',
			'LOCALIDAD_ESTANDARD',
			'LOCALIDAD_CODIGO_POSTAL',
			'LOCALIDAD_KM',
			'LOCALIDAD_ID_NIVEL',
			'LOCALIDAD_NIVEL',
			'LOCALIDAD_ATIENDE_DESDE',
			'LOCALIDAD_ID_TECNICO',
			'LOCALIDAD_TECNICO',
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
			'INFORME_TECNICO',
			'FECHA_COORDINADA',
			'VENTANA_HORARIA_COORDINADA',
			'FECHA_REGISTRACION_COORDINACION',
			'FECHA_NOTIFICADO',

			'FECHA_1_VENCIMIENTO',
			'FECHA_1_VISITA',

			'FECHA_VENCIMIENTO',
			'FECHA_CERRADO',
			'SLA_STATUS',
			'SLA_JUSTIFICADO',
			'MONTO',
			'MONTO_EXTRA',
			'LIQUIDADO_PROVEEDOR',
			'NRO_FACTURA_PROVEEDOR',

			'TIPO_CONEXION_LOCAL',
			'TIPO_CONEXION_PROVEEDOR',
			'CABLEADO',
			'CABLEADO_CANTIDAD_METROS',
			'CABLEADO_CANTIDAD_FICHAS',
			'INSTALACION_CARTEL',
			'INSTALACION_CARTEL_LUZ',
			'INSTALACION_BUZON',
			'CANTIDAD_HORAS_TRABAJO',
			'REQUIERE_NUEVA_VISITA',
			'CODIGO_ACTIVO_NUEVO1',
			'CODIGO_ACTIVO_RETIRADO1',
			'CODIGO_ACTIVO_DESCRIPCION1',
			'CODIGO_ACTIVO_NUEVO2',
			'CODIGO_ACTIVO_RETIRADO2',
			'CODIGO_ACTIVO_DESCRIPCION2',
			'CODIGO_ACTIVO_NUEVO3',
			'CODIGO_ACTIVO_RETIRADO3',
			'CODIGO_ACTIVO_DESCRIPCION3',
			'CODIGO_ACTIVO_NUEVO4',
			'CODIGO_ACTIVO_RETIRADO4',
			'CODIGO_ACTIVO_DESCRIPCION4',
			'CODIGO_ACTIVO_NUEVO5',
			'CODIGO_ACTIVO_RETIRADO5',
			'CODIGO_ACTIVO_DESCRIPCION5',
			'CODIGO_ACTIVO_NUEVO6',
			'CODIGO_ACTIVO_RETIRADO6',
			'CODIGO_ACTIVO_DESCRIPCION6',
			'CODIGO_ACTIVO_NUEVO7',
			'CODIGO_ACTIVO_RETIRADO7',
			'CODIGO_ACTIVO_DESCRIPCION7',
			'CODIGO_ACTIVO_NUEVO8',
			'CODIGO_ACTIVO_RETIRADO8',
			'CODIGO_ACTIVO_DESCRIPCION8',
			'CODIGO_ACTIVO_NUEVO9',
			'CODIGO_ACTIVO_RETIRADO9',
			'CODIGO_ACTIVO_DESCRIPCION9',
			'CODIGO_ACTIVO_NUEVO10',
			'CODIGO_ACTIVO_RETIRADO10',
			'CODIGO_ACTIVO_DESCRIPCION10',
			'MODEM_3G_4G_SIM_NUEVO',
			'MODEM_3G_4G_SIM_RETIRADO',
			'FIRMA_CLIENTE',
			'ACLARACION_CLIENTE',
			'FIRMA_TECNICO',
			'ACLARACION_TECNICO',

		];

		$paramsFiltro = [
			'userCompanyId' => $param['userCompanyId'],
			'texto' => $param['texto'],
			'idEmpresa' => $param['idEmpresa'],
			'idTerminal' => $param['idTerminal'],
			'idTipoServicio' => $param['idTipoServicio'],
			'idEstado' => $param['idEstado'],
			'idTecnico' => $param['idTecnico'],
			'fechaVencimiento' => $param['fechaVencimiento'],
			'estadosActivos' => $param['estadosActivo'],
			'liquidadoProveedor' => $param['liquidadoProveedor'],
			'excludeEmpresa' => $param['excludeEmpresa'],
			'sucursalOnsite' => $param['sucursalOnsite'],
			'terminalOnsite' => $param['terminalOnsite'],
			'saltear' => $saltear,
			'tomar' => $tomar,
			'extendida' => $param['extendido'],
			'fechaCreacion' => $param['fechaCreacion'],
			'fechaCreacionDesde' => $param['fechaCreacionDesde'],
			'fechaCreacionHasta' => $param['fechaCreacionHasta'],
		];

		$reparacionesOnsite = $this->listar($paramsFiltro);

		/* return Excel::download(new ReparacionesOnsiteExport($reparacionesOnsite), 'listado.xlsx'); */
		return $reparacionesOnsite;

		while ($reparacionesOnsite->count()) {

			foreach ($reparacionesOnsite as $reparacionOnsite) {

				$localidad = ($reparacionOnsite->sucursal_onsite && $reparacionOnsite->sucursal_onsite->localidad_onsite) ? $reparacionOnsite->sucursal_onsite->localidad_onsite : null;
				$terminal = ($reparacionOnsite->terminal_onsite) ? $reparacionOnsite->terminal_onsite : null;

				$data[] = [
					'id' => $reparacionOnsite->id,
					'clave' => $reparacionOnsite->clave,
					'id_empresa_onsite' => $reparacionOnsite->id_empresa_onsite,
					'empresa_onsite' => ($reparacionOnsite->empresa_onsite) ? $reparacionOnsite->empresa_onsite->nombre : '',
					'sucursal_onsite_id' => $reparacionOnsite->sucursal_onsite_id,
					'sucursal_razon_social' => $reparacionOnsite->sucursal_onsite->razon_social,
					'sucursal_direccion' => $reparacionOnsite->sucursal_onsite->direccion,
					'sucursal_telefono' => $reparacionOnsite->sucursal_onsite->telefono_contacto,
					'id_terminal' => $reparacionOnsite->id_terminal,
					'terminal_marca' => ($terminal) ? $terminal->marca : '',
					'terminal_modelo' => ($terminal) ? $terminal->modelo : '',
					'terminal_serie' => ($terminal) ? $terminal->serie : '',
					'terminal_rotulo' => ($terminal) ? $terminal->rotulo : '',
					'id_localidad' => ($reparacionOnsite->sucursal_onsite) ? $reparacionOnsite->sucursal_onsite->localidad_onsite_id : '',
					'localidad' => ($localidad) ? $localidad->localidad : '',
					'localidad_id_provincia' => ($localidad) ? $localidad->id_provincia : '',
					'localidad_provincia' => ($localidad && $localidad->provincia) ? $localidad->provincia->nombre : '',
					'localidad_estandard' => ($localidad) ? $localidad->localidad_estandard : '',
					'localidad_codigo_postal' => ($localidad) ? $localidad->codigo : '',
					'localidad_km' => ($localidad) ? $localidad->km : '',
					'localidad_id_nivel' => ($localidad) ? $localidad->id_nivel : '',
					'localidad_nivel' => ($localidad && $localidad->nivelOnsite) ? $localidad->nivelOnsite->nombre : '',
					'localidad_atiende_desde' => ($localidad) ? $localidad->atiende_desde : '',
					'localidad_id_tecnico' => ($localidad) ? $localidad->id_usuario_tecnico : '',
					'localidad_tecnico' => ($localidad && $localidad->usuarioTecnico) ? $localidad->usuarioTecnico->name : '',
					'tarea' => $reparacionOnsite->tarea,
					'detalle_tarea' => $reparacionOnsite->tarea_detalle,
					'id_tipo_servicio' => $reparacionOnsite->id_tipo_servicio,
					'tipo_servicio' => ($reparacionOnsite->tipo_servicio_onsite) ? $reparacionOnsite->tipo_servicio_onsite->nombre : '',
					'id_estado' => $reparacionOnsite->id_estado,
					'estado' => ($reparacionOnsite->estado_onsite) ? $reparacionOnsite->estado_onsite->nombre : '',
					'fecha_ingreso' => $reparacionOnsite->fecha_ingreso,
					'observacion_ubicacion' => $reparacionOnsite->observacion_ubicacion,
					'id_tecnico_asignado' => $reparacionOnsite->id_tecnico_asignado,
					'tecnico_asignado' => ($reparacionOnsite->tecnicoAsignado) ? $reparacionOnsite->tecnicoAsignado->name : '',
					'informe_tecnico' => $reparacionOnsite->informe_tecnico,
					'fecha_coordinada' => $reparacionOnsite->fecha_coordinada,
					'ventana_horaria_coordinada' => $reparacionOnsite->ventana_horaria_coordinada,
					'fecha_registracion_coordinacion' => $reparacionOnsite->fecha_registracion_coordinacion,
					'fecha_notificado' => $reparacionOnsite->fecha_notificado,

					'fecha_1_visita' => $reparacionOnsite->primer_visita->fecha ?? null,
					'fecha_1_vencimiento' => $reparacionOnsite->primer_visita->fecha_vencimiento ?? null,


					'fecha_vencimiento' => $reparacionOnsite->fecha_vencimiento,
					'fecha_cerrado' => $reparacionOnsite->fecha_cerrado,
					'sla_status' => $reparacionOnsite->sla_status,
					'sla_justificado' => $reparacionOnsite->sla_justificado,
					'monto' => $reparacionOnsite->monto,
					'monto_extra' => $reparacionOnsite->monto_extra,
					'liquidado_proveedor' => $reparacionOnsite->liquidado_proveedor,
					'nro_factura_proveedor' => $reparacionOnsite->nro_factura_proveedor,

					'tipo_conexion_local' => $reparacionOnsite->tipo_conexion_local,
					'tipo_conexion_proveedor' => $reparacionOnsite->tipo_conexion_proveedor,
					'cableado' => $reparacionOnsite->cableado,
					'cableado_cantidad_metros' => $reparacionOnsite->cableado_cantidad_metros,
					'cableado_cantidad_fichas' => $reparacionOnsite->cableado_cantidad_fichas,
					'instalacion_cartel' => $reparacionOnsite->instalacion_cartel,
					'instalacion_cartel_luz' => $reparacionOnsite->instalacion_cartel_luz,
					'instalacion_buzon' => $reparacionOnsite->instalacion_buzon,
					'cantidad_horas_trabajo' => $reparacionOnsite->cantidad_horas_trabajo,
					'requiere_nueva_visita' => $reparacionOnsite->requiere_nueva_visita,
					'codigo_activo_nuevo1' => $reparacionOnsite->codigo_activo_nuevo1,
					'codigo_activo_retirado1' => $reparacionOnsite->codigo_activo_retirado1,
					'codigo_activo_descripcion1' => $reparacionOnsite->codigo_activo_descripcion1,
					'codigo_activo_nuevo2' => $reparacionOnsite->codigo_activo_nuevo2,
					'codigo_activo_retirado2' => $reparacionOnsite->codigo_activo_retirado2,
					'codigo_activo_descripcion2' => $reparacionOnsite->codigo_activo_descripcion2,
					'codigo_activo_nuevo3' => $reparacionOnsite->codigo_activo_nuevo3,
					'codigo_activo_retirado3' => $reparacionOnsite->codigo_activo_retirado3,
					'codigo_activo_descripcion3' => $reparacionOnsite->codigo_activo_descripcion3,
					'codigo_activo_nuevo4' => $reparacionOnsite->codigo_activo_nuevo4,
					'codigo_activo_retirado4' => $reparacionOnsite->codigo_activo_retirado4,
					'codigo_activo_descripcion4' => $reparacionOnsite->codigo_activo_descripcion4,
					'codigo_activo_nuevo5' => $reparacionOnsite->codigo_activo_nuevo5,
					'codigo_activo_retirado5' => $reparacionOnsite->codigo_activo_retirado5,
					'codigo_activo_descripcion5' => $reparacionOnsite->codigo_activo_descripcion5,
					'codigo_activo_nuevo6' => $reparacionOnsite->codigo_activo_nuevo6,
					'codigo_activo_retirado6' => $reparacionOnsite->codigo_activo_retirado6,
					'codigo_activo_descripcion6' => $reparacionOnsite->codigo_activo_descripcion6,
					'codigo_activo_nuevo7' => $reparacionOnsite->codigo_activo_nuevo7,
					'codigo_activo_retirado7' => $reparacionOnsite->codigo_activo_retirado7,
					'codigo_activo_descripcion7' => $reparacionOnsite->codigo_activo_descripcion7,
					'codigo_activo_nuevo8' => $reparacionOnsite->codigo_activo_nuevo8,
					'codigo_activo_retirado8' => $reparacionOnsite->codigo_activo_retirado8,
					'codigo_activo_descripcion8' => $reparacionOnsite->codigo_activo_descripcion8,
					'codigo_activo_nuevo9' => $reparacionOnsite->codigo_activo_nuevo9,
					'codigo_activo_retirado9' => $reparacionOnsite->codigo_activo_retirado9,
					'codigo_activo_descripcion9' => $reparacionOnsite->codigo_activo_descripcion9,
					'codigo_activo_nuevo10' => $reparacionOnsite->codigo_activo_nuevo10,
					'codigo_activo_retirado10' => $reparacionOnsite->codigo_activo_retirado10,
					'codigo_activo_descripcion10' => $reparacionOnsite->codigo_activo_descripcion10,
					'modem_3g_4g_sim_nuevo' => $reparacionOnsite->modem_3g_4g_sim_nuevo,
					'modem_3g_4g_sim_retirado' => $reparacionOnsite->modem_3g_4g_sim_retirado,
					'firma_cliente' => $reparacionOnsite->firma_cliente,
					'aclaracion_cliente' => $reparacionOnsite->aclaracion_cliente,
					'firma_tecnico' => $reparacionOnsite->firma_tecnico,
					'aclaracion_tecnico' => $reparacionOnsite->aclaracion_tecnico,
				];
			}

			$saltear = $saltear + $tomar;

			$paramsFiltro['saltear'] = $saltear;
			$paramsFiltro['tomar'] = $tomar;

			$reparacionesOnsite = $this->listar($paramsFiltro);
		}

		$excelController = new GenericExport($data, $filename);
		$excelController->export();
	}

	public function getDataReporteReparacionOnsite($exitoso = 0)
	{
		$userCompanyId = Session::get('userCompanyIdDefault');

		$data['company_id'] = $userCompanyId;
		$data['exitoso'] = $exitoso;

		$datos = $this->getData($data);

		$datos['estados_activo'] = true;
		$datos['user_id'] = Auth::user()->id;
		$datos['generarXlsxExtendido'] = false;




		return $datos;
	}

	public function generarReporteReparacionOnsite(Request $request)
	{
		$userCompanyId = Session::get('userCompanyIdDefault');

		$param['userCompanyId'] = $userCompanyId;
		$param['texto'] = $request['texto'];
		$param['idEmpresa'] = $request['id_empresa'];
		$param['idTerminal'] = null;
		$param['idTipoServicio'] = $request['id_tipo_servicio'];
		$param['estadosActivo'] = $request['estados_activo'] ? 1 : 0;
		$param['idEstado'] = $request['id_estado'];
		$param['idTecnico'] = $request['id_tecnico'];

		$fechaVencimiento = null;
		if (isset($request['fecha_vencimiento']) && $request['fecha_vencimiento']) {
			$fechaVencimiento =  DateTime::createFromFormat('d/m/Y', $request['fecha_vencimiento'])->format('Y-m-d');
		}

		$param['fechaVencimiento'] = $fechaVencimiento;
		$param['liquidadoProveedor'] = null;
		$param['excludeEmpresa'] = null;
		$param['sucursalOnsite'] = $request['sucursal_onsite'];
		$param['terminalOnsite'] = $request['terminal_onsite'];
		$param['extendido'] = true;

		$fechaCreacion = null;
		if (isset($request['fecha_creacion']) && $request['fecha_creacion']) {
			$fechaCreacion =  DateTime::createFromFormat('d/m/Y', $request['fecha_creacion'])->format('Y-m-d');
		}

		$param['fechaCreacion'] = $fechaCreacion;

		$fechaCreacionDesde = null;
		if (isset($request['fecha_creacion_desde']) && $request['fecha_creacion_desde']) {
			$fechaCreacionDesde =  DateTime::createFromFormat('d/m/Y', $request['fecha_creacion_desde'])->format('Y-m-d');
		}

		$param['fechaCreacionDesde'] = $fechaCreacionDesde;

		$fechaCreacionHasta = null;
		if (isset($request['fecha_creacion_hasta']) && $request['fecha_creacion_hasta']) {
			$fechaCreacionHasta =  DateTime::createFromFormat('d/m/Y', $request['fecha_creacion_hasta'])->format('Y-m-d');
		}
		$param['fechaCreacionHasta'] = $fechaCreacionHasta;

		return $this->generarXlsxExtendido($param);

		$data['company_id'] = $userCompanyId;
		$datos = $this->getData($data);

		$datos['texto'] = $request['texto'];
		$datos['id_empresa'] = $request['id_empresa'];
		$datos['id_tipo_servicio'] = $request['id_tipo_servicio'];
		$datos['estados_activo'] = $request['estados_activo'] ? 1 : 0;
		$datos['id_estado'] = $request['id_estado'];
		$datos['id_tecnico'] = $request['id_tecnico'];
		$datos['fecha_vencimiento'] = $request['fecha_vencimiento'];
		$datos['sucursal_onsite'] = $request['sucursal_onsite'];
		$datos['terminal_onsite'] = $request['terminal_onsite'];
		$datos['fecha_creacion'] = $request['fecha_creacion'];
		$datos['fecha_creacion_desde'] = $request['fecha_creacion_desde'];
		$datos['fecha_creacion_hasta'] = $request['fecha_creacion_hasta'];

		$datos['user_id'] = Auth::user()->id;
		$datos['generarXlsxExtendido'] = true;

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
		Log::info('Reparacion OnsiteService - enviarMailTecnicoOnsiteAsignado');
		$tecnicoAsignado = $this->userService->getUserById($reparacionOnsite->id_tecnico_asignado);
		$parametroMail = $this->parametrosService->findParametroPorNombre('MAIL_ONSITE_TECNICO');

		if ($tecnicoAsignado && $tecnicoAsignado->email) {
			if ($parametroMail && $parametroMail->valor_numerico > 1) {
				$this->mailOnsiteService->enviarMailOnsite($reparacionOnsite, $parametroMail->valor_numerico, $tecnicoAsignado->email);
			}
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
		$reparacionOnsite = $this->findReparacion($idReparacionOnsite);

		$this->enviarMailTecnicoOnsiteAsignado($reparacionOnsite);

		$mje = "Se ha reenviado el email al técnico asignado.";
		return $mje;
	}

	public function getEmpresasOnsiteUsuario()
	{
		$empresasOnsiteUser = Auth::user()->empresas_onsite;
		$idEmpresas = '';

		foreach ($empresasOnsiteUser as $empresaOnsite) {
			$idEmpresas = $idEmpresas . $empresaOnsite->id . ',';
		}

		$idEmpresas = rtrim($idEmpresas, ",");

		if (!$idEmpresas || $idEmpresas == '') {
			$idEmpresas = '9999';
		}

		return $idEmpresas;
	}

	public function eliminarImagenOnsite($id)
	{
		$imagenOnsite = $this->imagenOnsiteService->findImagenOnsitePorId($id);
		Storage::disk('local2')->delete($imagenOnsite->archivo);
		$imagenOnsite->delete();

		return $imagenOnsite;
	}

	public function getDataIndexReparacionOnsiteConPresupuestoPendienteDeAprobacion()
	{
		$userCompanyId = Session::get('userCompanyIdDefault');
		$userId = Auth::user()->id;
		$datos['sysdate'] = date('Y-m-d H:i:s');

		$idEmpresaOnsite = $this->getEmpresasOnsiteUsuario();

		$listarSoloEstadosActivos = true;

		$datos['historialEstadosOnsite'] = array();

		$tipoServicioArrayExclude = [TipoServicioOnsite::PUESTA_MARCHA, TipoServicioOnsite::SEGUIMIENTO_OBRA];

		$params = [
			'userCompanyId' => $userCompanyId,
			'idEmpresa' => $idEmpresaOnsite,
			'tipoEstadoOnsite' => $this->estado_onsite_repository::TIPO_ESTADO_PRESUPUESTO_PENDIENTE_DE_APROBACION,
			'tipoServicioArrayExclude' => $tipoServicioArrayExclude,
		];

		$datos['reparacionesOnsite'] = $this->listar($params);

		$datos['ruta'] = 'reparacionOnsiteEmpresaActivas';
		$datos['user_id'] = $userId;

		return $datos;
	}

	public function enviarMailResponsable($reparacion_onsite, $empresa_onsite_id)
	{

		$empresaOnsite = $this->empresaOnsiteService->findEmpresaOnsite($empresa_onsite_id);

		if (isset($empresaOnsite->email_responsable) && $empresaOnsite->plantilla_mail_responsable_id > 1) {

			$this->mailOnsiteService->enviarMailOnsite($reparacion_onsite, $empresaOnsite->plantilla_mail_responsable_id, $empresaOnsite->email_responsable);
		}
	}

	public function obtenerTipoTerminal($id)
	{
		$empresaOnsite = $this->empresaOnsiteService->findEmpresaOnsiteTipoTerminal($id);

		$tipo_terminal = $empresaOnsite->tipo_terminales;

		return $tipo_terminal;
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

	public function listar($params)
	{
		$consulta = ReparacionOnsite::select('reparaciones_onsite.*')
			//->where('reparaciones_onsite.id_tecnico_asignado', '<>', 14)
			->where('reparaciones_onsite.company_id', $params['userCompanyId']);

		if (!empty($params['texto'])) {
			$consulta->whereRaw(" CONCAT( reparaciones_onsite.id , ' ', reparaciones_onsite.clave, ' ', reparaciones_onsite.tarea ) like '%" . $params['texto'] . "%'");
		}

		if (!empty($params['idTerminal'])) {
			$consulta->whereRaw(" reparaciones_onsite.id_terminal = '" . $params['idTerminal'] . "' ");
		}

		if (!empty($params['idTipoServicio'])) {
			$consulta->whereRaw("reparaciones_onsite.id_tipo_servicio =" . $params['idTipoServicio']);
		}

		if (!empty($params['tipoServicioArrayInclude']) && count($params['tipoServicioArrayInclude']) > 0) {
			$consulta->whereIn("reparaciones_onsite.id_tipo_servicio", $params['tipoServicioArrayInclude']);
		}

		if (!empty($params['tipoServicioArrayExclude']) && count($params['tipoServicioArrayExclude']) > 0) {
			$consulta->whereNotIn("reparaciones_onsite.id_tipo_servicio", $params['tipoServicioArrayExclude']);
		}

		if (!empty($params['idEstado'])) {
			$consulta->whereRaw(" reparaciones_onsite.id_estado =" . $params['idEstado']);
		}

		if (!empty($params['idTecnico'])) {
			$consulta->whereRaw(" reparaciones_onsite.id_tecnico_asignado =" . $params['idTecnico']);
		}

		if (!empty($params['idEmpresa'])) {
			$consulta->whereRaw(" reparaciones_onsite.id_empresa_onsite IN (" . $params['idEmpresa'] . " )");
		}

		if (!empty($params['fechaVencimiento'])) {
			$consulta->whereRaw(empty($params['fechaVencimiento']) ? " 1 " : " DATE_FORMAT( reparaciones_onsite.fecha_vencimiento , '%Y-%m-%d' ) = '" . $params['fechaVencimiento'] . "'");
		}

		if (!empty($params['fechaCreacion']) && $params['fechaCreacion']) {
			$consulta->whereRaw(" DATE_FORMAT( reparaciones_onsite.created_at , '%Y-%m-%d' ) = '" . $params['fechaCreacion'] . "'");
		}

		if (!empty($params['fechaCreacionDesde']) && $params['fechaCreacionDesde']) {
			$consulta->whereRaw(" DATE_FORMAT( reparaciones_onsite.created_at , '%Y-%m-%d' ) >= '" . $params['fechaCreacionDesde'] . "'");
		}

		if (!empty($params['fechaCreacionHasta']) && $params['fechaCreacionHasta']) {
			$consulta->whereRaw(" DATE_FORMAT( reparaciones_onsite.created_at , '%Y-%m-%d' ) <= '" . $params['fechaCreacionHasta'] . "'");
		}

		/*if (!empty($params['estadosActivos'])) {
			$consulta = $consulta->join('estados_onsite', 'estados_onsite.id', '=', 'reparaciones_onsite.id_estado')
				->where("estados_onsite.activo", true);
		}*/

		if (isset($params['estadosActivos']) && !in_array('todos',$params['estadosActivos'])) {
			if(in_array('activos',$params['estadosActivos'])){
				$consulta = $consulta->join('estados_onsite', 'estados_onsite.id', '=', 'reparaciones_onsite.id_estado')
				->where("estados_onsite.activo", true);
			}
			if(in_array('inactivos',$params['estadosActivos'])){
				$consulta = $consulta->join('estados_onsite', 'estados_onsite.id', '=', 'reparaciones_onsite.id_estado')
				->where("estados_onsite.activo", false);
			}
		}

		if (!empty($params['tipoEstadoOnsite'])) {
			$consulta = $consulta->join('estados_onsite', 'estados_onsite.id', '=', 'reparaciones_onsite.id_estado')
				->where("estados_onsite.tipo_estado_onsite_id", $params['tipoEstadoOnsite']);
		}

		if (!empty($params['liquidadoProveedor'])) {
			$consulta->whereRaw("reparaciones_onsite.liquidado_proveedor = 0 ");
		}

		if (!empty($params['excludeEmpresa'])) {
			$consulta->whereRaw(" reparaciones_onsite.id_empresa_onsite NOT IN (" . $params['excludeEmpresa'] . ") ");
		}

		if (!empty($params['includeEmpresa'])) {
			$consulta->whereRaw(" reparaciones_onsite.id_empresa_onsite IN (" . $params['includeEmpresa'] . ") ");
		}

		if (!empty($params['sucursalOnsite'])) {
			$consulta = $consulta->join('sucursales_onsite', 'sucursales_onsite.id', '=', 'reparaciones_onsite.sucursal_onsite_id')
				->whereRaw(" CONCAT( sucursales_onsite.razon_social , ' ', sucursales_onsite.codigo_sucursal ) like '%" . $params['sucursalOnsite'] . "%'");
		}

		if (!empty($params['terminalOnsite'])) {
			$consulta = $consulta->join('terminales_onsite', 'terminales_onsite.nro', '=', 'reparaciones_onsite.id_terminal')
				->whereRaw("CONCAT(terminales_onsite.nro, ' ', terminales_onsite.marca, ' ', terminales_onsite.modelo, ' ', 
				terminales_onsite.serie, ' ', terminales_onsite.rotulo) like '%" . $params['terminalOnsite'] . "%'");
		}

		// nuevos campos
		if (!empty($params['visible_cliente'])) {
			$consulta->whereRaw("reparaciones_onsite.visible_cliente = " . $params['visible_cliente']);
		}

		// if (!empty($params['chequeado_cliente'])) {
		//   $consulta->whereRaw("reparaciones_onsite.chequeado_cliente = " . $params['chequeado_cliente']);
		// }


		if (isset($params['chequeado_cliente'])) {
			//$user = User::find(Auth::user()->id);
			//$sarasa = $user->reparaciones_onsite_confirmadas->pluck('id');

			//if ($params['chequeado_cliente']) {
			//$consulta = $consulta->whereIn('reparaciones_onsite.id', $user->reparaciones_onsite_confirmadas->pluck('id'));
			//} else {
			//$consulta = $consulta->whereNotIn('reparaciones_onsite.id', $user->reparaciones_onsite_confirmadas->pluck('id'));
			//}
			$consulta->whereRaw("reparaciones_onsite.chequeado_cliente = " . $params['chequeado_cliente']);
		}

		if (!empty($params['cerrado'])) {
			$consulta = $consulta->join('estados_onsite', 'estados_onsite.id', '=', 'reparaciones_onsite.id_estado')
				->whereRaw("estados_onsite.cerrado = " . $params['cerrado']);
		}

		$consulta->orderBy('reparaciones_onsite.id', 'desc');

		if (!empty($params['tomar']))
			return $consulta->skip($params['saltear'])->take($params['tomar'])->get();
		else
			return $consulta->paginate(25);
	}



	public function agregarImagenOnsite(Request $request)
	{
		$company_id = Session::get('userCompanyIdDefault');

		$datos['tipoImagenOnsite'] = $this->tiposImagenOnsiteService->findTipoImagenOnsite($request['imagen_onsite_tipo_id']);
		$datos['imagenOnsite']  = null;

		if ($datos['tipoImagenOnsite']) {
			$request['descripcion'] = $datos['tipoImagenOnsite']->nombre;

			if ($request->hasFile('imagen_onsite_archivo')) {
				$archivo = $request->file('imagen_onsite_archivo');

				$nombreArchivo = $this->tiposImagenOnsiteService->getCustomFilename('reparacion_onsite', $archivo->getClientOriginalName(), $request->name);

				Storage::disk('local2')->put($nombreArchivo, File::get($archivo));

				$request['archivo'] = $nombreArchivo;

				$arrayImagenOnsite = [
					'reparacion_onsite_id' => $request['reparacion_onsite_id'],
					'archivo' => $request['archivo'],
					'tipo_imagen_onsite_id' => $request['imagen_onsite_tipo_id'],
					'descripcion' => $request['descripcion'],
					'company_id' => $company_id
				];

				$imagenOnsite = $this->imagenOnsiteService->store($arrayImagenOnsite);

				$datos['imagenOnsite'] = $imagenOnsite;

				$datos['return'] = 1;
			}
			if (empty($datos['return'])) {
				$datos['return'] = 2;
			}
		}

		return $datos;
	}

	public function agregarVisita(Request $request)
	{

		if (isset($request['reparacion_onsite_id'])) {
			$reparacionDetalle = $this->reparacionDetalleService->getReparacionDetalleByReparacion($request['reparacion_onsite_id']);
			$reparacionDetalle->cantidad_visitas = $reparacionDetalle->cantidad_visitas + 1;
			$reparacionDetalle->save();

			$data = [
				'message' => 'success',
				'reparacion_id' => $request['reparacion_onsite_id'],
				'cantidad_visitas' => $reparacionDetalle->cantidad_visitas,
			];
			return  response()->json($data);
		}

		$data = [
			'message' => 'error'
		];
		return  response()->json($data);
	}


	public function findReparacion($id, $company_id = null)
	{
		if (is_null($company_id))
			$company_id = Session::get('userCompanyIdDefault');

		$reparacionOnsite = ReparacionOnsite::with('reparacion_checklist_onsite')
			->where('company_id', $company_id)->find($id);

		return $reparacionOnsite;
	}

	public function getReparacionById($id)
	{

		$reparacionOnsite = ReparacionOnsite::where('id', $id)->first();

		return $reparacionOnsite;
	}

	public function getReparacionByClave($clave)
	{

		$reparacionOnsite = ReparacionOnsite::where('clave', $clave)->first();

		return $reparacionOnsite;
	}


	public function getDataEditByClave($company_id, $clave)
	{

		$reparacionesOnsite = ReparacionOnsite::where('clave', $clave)
			->where('company_id', $company_id)
			->get();


		$reparaciones_onsite_data = array();
		if ($reparacionesOnsite) {
			foreach ($reparacionesOnsite as $key => $reparacion) {

				$data_reparacion = $this->getDataEdit($reparacion->id, $reparacion->company_id);

				if ($data_reparacion) $reparaciones_onsite_data[] = $data_reparacion;
			}
		} else return false;

		return $reparaciones_onsite_data;
	}

	public function getDataRepIdByEstado($company_id, $id_estado)
	{

		$reparaciones_onsite_data = ReparacionOnsite::where('id_estado', $id_estado)
								->where('company_id', $company_id)
								->pluck('clave','id')
								->toArray();


		return $reparaciones_onsite_data;
	}

	public function findReparacionDetalle($id)
	{
		$reparacionDetalle = ReparacionDetalle::where('reparacion_id', $id)->first();

		return $reparacionDetalle;
	}

	public function getUltimaReparacion()
	{
		$ultimaReparacion = ReparacionOnsite::orderBy('id', 'DESC')->first();

		return $ultimaReparacion;
	}




	function registrar_visita(Request $request, $reparacion_id)
	{

		$visita_anterior = ReparacionVisita::where('reparacion_id', $reparacion_id)->orderBy('id', 'desc')->first();
		if ($visita_anterior) {
			$request['orden'] = $visita_anterior->orden  + 1;
		} else
			$request['orden'] = 1;

		$request['reparacion_id'] = $reparacion_id;

		$reparacion_visita = ReparacionVisita::create(
			$request->all()
		);

		$reparacion_onsite = ReparacionOnsite::find($reparacion_id);
		$reparacion_onsite->fecha_vencimiento = $request['fecha_nuevo_vencimiento'];
		$reparacion_onsite->fecha_1_vencimiento = $request['fecha_vencimiento'];
		$reparacion_onsite->fecha_1_visita = $request['fecha'];
		$reparacion_onsite->save();

		return $reparacion_visita;
	}




	public function getPromedioCoordinadasCerradas()
	{
		$reparaciones = ReparacionOnsite::where('company_id', $this->userCompanyId)
			->where('fecha_ingreso', '<>', null)
			->get();
		//lee días          
		$hoy = date('Ymd');
		$suma_dias_coordinada = 0;
		$suma_dias_cerrada = 0;
		foreach ($reparaciones as $reparacion) {
			$fecha_ingreso = $reparacion->fecha_ingreso;
			$fecha_coordinada = $reparacion->fecha_coordinada;
			$fecha_cerrado = $reparacion->fecha_cerrado;


			if ($fecha_coordinada != null)
				$date_coordinada = new DateTime($fecha_coordinada);
			else
				$date_coordinada = new DateTime($hoy);

			if ($fecha_cerrado != null)
				$date_cerrado = new DateTime($fecha_cerrado);
			else
				$date_cerrado = new DateTime($hoy);

			$date_ingreso = new DateTime($fecha_ingreso);

			//promedio coordinadas
			$from = Carbon::parse($date_coordinada);
			$to = Carbon::parse($date_ingreso);
			$diff_coordinada = $to->diffInWeekdays($from); //cuenta los días corridos
			$suma_dias_coordinada +=  $diff_coordinada;

			//promedio cerradas
			$from_1 = Carbon::parse($date_cerrado);
			$to_1 = Carbon::parse($date_ingreso);
			$diff_cerrada = $to_1->diffInWeekdays($from_1); //cuenta los días corridos
			$suma_dias_cerrada +=  $diff_cerrada;
		}

		$promedio_coordinadas = 0;
		$promedio_cerradas = 0;

		if (count($reparaciones) > 0) {
			$promedio_coordinadas = $suma_dias_coordinada / count($reparaciones);
			$promedio_cerradas = $suma_dias_cerrada / count($reparaciones);
		}

		return [
			'promedio_coordinadas' => $promedio_coordinadas,
			'promedio_cerradas' => $promedio_cerradas
		];
	}

	public function actualizarImagenes($reparacionOnsite, $registro)
	{
		Log::alert('array');
		Log::alert($registro);
		if ($reparacionOnsite) {

			for ($i = 1; $i <= 10; $i++) {
				if (isset($registro['IMAGEN_ONSITE_' . $i]) && !empty($registro['IMAGEN_ONSITE_' . $i])) {


					if (isset($registro['TIPO_IMAGEN_ONSITE_' . $i]))
						$tipo = intval($registro['TIPO_IMAGEN_ONSITE_' . $i]);
					else $tipo = false;

					$imagenOnsite = [
						'company_id' => $reparacionOnsite->company_id,
						'reparacion_onsite_id' => $reparacionOnsite->id,
						'archivo' => $registro['IMAGEN_ONSITE_' . $i],
						'tipo_imagen_onsite_id' => $tipo ?? TipoImagenOnsite::NINGUNO,
						'descripcion' => 'Imagen importada',
					];
					$this->imagenOnsiteService->updateOrstore($imagenOnsite);
				}
			}
		}
	}
}
