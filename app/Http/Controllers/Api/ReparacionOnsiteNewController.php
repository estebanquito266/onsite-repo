<?php

namespace App\Http\Controllers\Api;

use App\Events\ExportCompleted;
use App\Exports\ReparacionesOnsiteExport;
use App\Exports\ReparacionExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Onsite\ReparacionOnsiteRequest;
use App\Http\Requests\Onsite\ReparacionOnsiteUpdateRequest;
use App\Imports\ReparacionOnsiteImport;
use App\Jobs\ExportarReparacionesJob;
use App\Jobs\ImportarReparacionesJob;
use App\Models\Notificacion;
use App\Models\Onsite\ReparacionOnsite;
use App\Models\User;
use App\Services\Onsite\ObrasOnsiteService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

use App\Services\Onsite\ReparacionOnsiteService;
use App\Services\Onsite\Reparacion\ImportacionService;
use App\Services\Onsite\SolicitudOnsiteService;
use App\Services\Onsite\UserService;
use DateTime;
use DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;


class ReparacionOnsiteNewController extends Controller
{

	protected $importacionService;
	protected $reparacionOnsiteService;
	protected $obraOnsiteService;
	protected $solicitudOnsiteService;
	protected $userService;

	public function __construct(
		ReparacionOnsiteService $reparacionOnsiteService,
		ImportacionService $importacionService,
		ObrasOnsiteService $obraOnsiteService,
		SolicitudOnsiteService $solicitudOnsiteService,
		UserService $UserService
	) {
		$this->reparacionOnsiteService = $reparacionOnsiteService;
		$this->importacionService = $importacionService;
		$this->obraOnsiteService = $obraOnsiteService;
		$this->solicitudOnsiteService = $solicitudOnsiteService;
		$this->userService = $UserService;

	}

	public function index()
	{
		
		try {

			$setSessionUserProfile = $this->userService->setSessionUserProfile();
			
			$request['excludeEmpresa'] = '5';
			$request['includeEmpresa'] = null;
			$request['ruta'] = 'reparacionOnsite';
			$datos = $this->reparacionOnsiteService->getDataIndex($request);

			$response = [];
			$items = [];
			if ($datos && isset($datos['reparacionesOnsite'])) {

				$paginator = $datos['reparacionesOnsite'];
				$reparaciones = $paginator->items();

				foreach($reparaciones as $reparacionOnsite){

					$itemTicket = [
						'CLAVE'        => $reparacionOnsite->clave,
						'EMPRESA'      => $reparacionOnsite->empresa_onsite->nombre ?? '',
						'SUCURSAL'     => $reparacionOnsite->sucursal_onsite->razon_social ?? '',
						'TERMINAL'     => isset($reparacionOnsite->terminal_onsite)
											? $reparacionOnsite->terminal_onsite->nro . ' - ' .
											$reparacionOnsite->terminal_onsite->marca . ' - ' .
											$reparacionOnsite->terminal_onsite->modelo . ' - ' .
											$reparacionOnsite->terminal_onsite->serie . ' - ' .
											$reparacionOnsite->terminal_onsite->rotulo
											: '',
						'SERVICIO'     => $reparacionOnsite->tipo_servicio_onsite->nombre ?? '',
						'ESTADO'       => $reparacionOnsite->estado_onsite->nombre ?? '',
						'TECNICO'      => $reparacionOnsite->tecnicoAsignado->name ?? '',
						'INGRESO'      => $reparacionOnsite->fecha_ingreso
											? date('Y-m-d', strtotime($reparacionOnsite->fecha_ingreso))
											: '',
						'VENCIMIENTO'  => $reparacionOnsite->fecha_vencimiento
											? date('Y-m-d', strtotime($reparacionOnsite->fecha_vencimiento))
											: '',
						'SLA'          => $reparacionOnsite->sla_status ?? '',
						'PRIORIDAD'    => $reparacionOnsite->prioridad ?? '',
						'NOTA'         => $reparacionOnsite->nota_cliente ?? ''
					];

					$items[] = $itemTicket;
				}

				$response = $paginator->toArray();

				
			}

			$response['data'] = $items;

			Session::flush();

			return response()->json($response, 200);

        } catch (\Exception $e) {

            Session::flush();
            Log::error('ReparacionOnsiteNewController index: ' . json_encode($request) . ' - Error: ' . $e->getMessage() . ' - File:' . $e->getFile() . ' - Line:' . $e->getLine());

            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Server Error'
            ], 500);
        }

	}

public function filtrarReparacionOnsite(Request $request)
	{
		try {

			$setSessionUserProfile = $this->userService->setSessionUserProfile();
			
			$datos = $this->reparacionOnsiteService->filtrarReparacionOnsite($request);

			$response = [];

			$items = [];

			if ($datos && isset($datos['reparacionesOnsite'])) {

				$paginator = $datos['reparacionesOnsite'];
				$reparaciones = $paginator;

				foreach($reparaciones as $reparacionOnsite){

					$itemTicket = [
						'CLAVE'               => $reparacionOnsite->clave,
						'EMPRESA'             => $reparacionOnsite->empresa_onsite->nombre ?? '',
						'SUCURSAL'            => $reparacionOnsite->sucursal_onsite->razon_social ?? '',
						'TERMINAL'            => isset($reparacionOnsite->terminal_onsite)
													? $reparacionOnsite->terminal_onsite->nro . ' - ' .
													$reparacionOnsite->terminal_onsite->marca . ' - ' .
													$reparacionOnsite->terminal_onsite->modelo . ' - ' .
													$reparacionOnsite->terminal_onsite->serie . ' - ' .
													$reparacionOnsite->terminal_onsite->rotulo
													: '',
						'SERVICIO'            => $reparacionOnsite->tipo_servicio_onsite->nombre ?? '',
						'ESTADO'              => $reparacionOnsite->estado_onsite->nombre ?? '',
						'TECNICO'             => $reparacionOnsite->tecnicoAsignado->name ?? '',
						'INGRESO'             => $reparacionOnsite->fecha_ingreso
													? date('Y-m-d', strtotime($reparacionOnsite->fecha_ingreso))
													: '',
						'VENCIMIENTO'         => $reparacionOnsite->fecha_vencimiento
													? date('Y-m-d', strtotime($reparacionOnsite->fecha_vencimiento))
													: '',
						'SLA'                 => $reparacionOnsite->sla_status ?? '',
						'PRIORIDAD'           => $reparacionOnsite->prioridad ?? '',
						'NOTA'                => $reparacionOnsite->nota_cliente ?? '',

						// --- CAMPOS NUEVOS AGREGADOS ---
						'FECHA_1_VENCIMIENTO' => $reparacionOnsite->fecha_1_vencimiento ?? '',
						
						'ACTIVO'              => isset($reparacionOnsite->terminal_onsite) 
													? $reparacionOnsite->terminal_onsite->serie 
													: '',

						'DIRECCION'           => $reparacionOnsite->sucursal_onsite->direccion ?? '',

						'LOCALIDAD'           => isset($reparacionOnsite->sucursal_onsite->localidad_onsite)
													? $reparacionOnsite->sucursal_onsite->localidad_onsite->localidad
													: '',

						'PROVINCIA'           => isset($reparacionOnsite->sucursal_onsite->localidad_onsite)
													? $reparacionOnsite->sucursal_onsite->localidad_onsite->id_provincia
													: '',

						'CP'                  => isset($reparacionOnsite->sucursal_onsite->localidad_onsite)
													? $reparacionOnsite->sucursal_onsite->localidad_onsite->codigo
                    													: '',
                        'CONTACTO'            => $sucursal->nombre_contacto ?? '',

                        'TELEFONO_MOVIL'      => $sucursal->telefono_contacto ?? '',
                        'DETALLE TAREA'       => $reparacionOnsite->tarea_detalle ?? '',
];

					$items[] = $itemTicket;
				}

				$response = $paginator->toArray();

				
			}

			$response['data'] = $items;

			Session::flush();

			return response()->json($response, 200);

		} catch (\Exception $e) {

			Session::flush();
			Log::error('ReparacionOnsiteNewController filtrarReparacionOnsite: ' . json_encode($request) . ' - Error: ' . $e->getMessage() . ' - File:' . $e->getFile() . ' - Line:' . $e->getLine());

			return response()->json([
				'error' => $e->getMessage(),
				'message' => 'Server Error'
			], 500);
		}
	}
	public function show(Request $request,$reparacion_id)
	{
		try {

			$setSessionUserProfile = $this->userService->setSessionUserProfile();
			
			$toReturn = $this->reparacionOnsiteService->getDataShow($reparacion_id,true);

			Session::flush();

			if ($toReturn) {
                return response()->json([
                    'data' => $toReturn,
                ], 200);
            } else
                return response()->json([
                    'error' => 'ReparacionOnsite no encontrada',
                    'message' => 'Server Error'
                ], 404);

			return response()->json(['data'=>$datos], 200);

		} catch (\Exception $e) {

            Session::flush();
            Log::error('ReparacionOnsiteNewController show: ' . json_encode($request) . ' - Error: ' . $e->getMessage() . ' - File:' . $e->getFile() . ' - Line:' . $e->getLine());

            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Server Error'
            ], 500);
        }

	}

	public function generarReporteReparacion(Request $request)
	{
	
		try {

			$setSessionUserProfile = $this->userService->setSessionUserProfile();
			
			$toReturn = $this->reparacionOnsiteService->generarReporteReparacionOnsiteApi($request);

			Session::flush();

			if ($toReturn) {
                return response()->json([
                    'data' => $toReturn,
                ], 200);
            } else
                return response()->json([
                    'error' => 'ReparacionOnsite Reportee error',
                    'message' => 'Server Error'
                ], 404);

			return response()->json(['data'=>$datos], 200);

		} catch (\Exception $e) {

            Session::flush();
            Log::error('ReparacionOnsiteNewController generarReporteReparacion: ' . json_encode($request) . ' - Error: ' . $e->getMessage() . ' - File:' . $e->getFile() . ' - Line:' . $e->getLine());

            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Server Error'
            ], 500);
        }

	
	}

	public function urlReporteReparacion(Request $request,$filename)
	{
	
		try {

			$setSessionUserProfile = $this->userService->setSessionUserProfile();
			
			$toReturn = $this->reparacionOnsiteService->urlReporteReparacion($filename);

			Session::flush();

			if ($toReturn) {
                return response()->json([
                    'data' => $toReturn,
                ], 200);
            } else
                return response()->json([
                    'error' => 'ReparacionOnsite Reportee error',
                    'message' => 'Server Error'
                ], 404);

			return response()->json(['data'=>$datos], 200);

		} catch (\Exception $e) {

            Session::flush();
            Log::error('ReparacionOnsiteNewController generarReporteReparacion: ' . json_encode($request) . ' - Error: ' . $e->getMessage() . ' - File:' . $e->getFile() . ' - Line:' . $e->getLine());

            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Server Error'
            ], 500);
        }

	
	}

	public function indexPosnet()
	{
		$request['excludeEmpresa'] = null;
		$request['includeEmpresa'] = '5';
		$request['ruta'] = 'reparacionOnsitePosnet';
		$datos = $this->reparacionOnsiteService->getDataIndex($request);

		return view('_onsite.reparaciononsite.index', $datos);
	}

	public function create()
	{
		$datos = $this->reparacionOnsiteService->getDataCreate();
		return view('_onsite.reparaciononsite.createReparaciones', $datos);
	}

	public function store(Request $request)
	{
		$request->validate([
			'clave' => 'unique:reparaciones_onsite,clave',
			'sucursal_onsite_id' => 'required',
			'id_empresa_onsite' => 'required',
			'id_tipo_servicio' => 'required',
			'id_estado' => 'required',
			//'id_tecnico_asignado' => 'required'
		]);

		$datos = $this->reparacionOnsiteService->store($request);
		$reparacionOnsite = $datos['reparacionOnsite'];


		$mjeCreate = 'ReparacionOnsite: ' . $reparacionOnsite->id . ' - registro creado correctamente! Técnico Asignado: (' . $datos['tecnicoAsignado']->id . ') ' . $datos['tecnicoAsignado']->name;

		if ($request['botonGuardarNotificar']) {
			//$this->enviarMailResponsable($reparacionOnsite, $reparacionOnsite->id_empresa_onsite);
			$this->reparacionOnsiteService->enviarMailResponsableEmpresa($reparacionOnsite);
			$this->reparacionOnsiteService->enviarMailAdministrador($reparacionOnsite);
		}

		return redirect('/reparacionOnsite/' . $reparacionOnsite->id . '/edit')->with('message', $mjeCreate);
	}

	public function edit($reparacionOnsite)
	{
		$datos = $this->reparacionOnsiteService->getDataEdit($reparacionOnsite);
		if (!$datos) return redirect()->back()->with('error', 'Configurar company correctamente para este usuario');
		$sistemaOnsiteReparacion = $datos['sistemaOnsiteReparacion'];
		$userCompanyId = $datos['companyId'];

		$datos['solicitudes'] = (isset($sistemaOnsiteReparacion) ? $this->solicitudOnsiteService->getSolicitudesPorSistema($userCompanyId, $sistemaOnsiteReparacion->id) : null);


		if ($datos)
			return view('_onsite.reparaciononsite.editReparacion', $datos);

		else {
			Session::flash('message-error', 'Reparación no encontrada');
			return redirect('/reparacionOnsite');
		}
	}

	

	public function update(Request $request, $idReparacionOnsite)
	{
		if (!$request['id_terminal']) {
			$request['id_terminal'] = 1;
		}

		$fecha_ingreso_validate = '2000-01-01';
		$fecha_coordinada = date('Y-m-d');

		if (isset($request['fecha_ingreso'])) {
			$fecha_ingreso_datetime = new DateTime($request['fecha_ingreso']);
			$fecha_ingreso_validate = $fecha_ingreso_datetime->format('Y-m-d');

			$fecha_coordinada_validate = $fecha_ingreso_validate;
		}

		if (isset($request['fecha_coordinada']) && $request['fecha_coordinada']) {
			$fecha_coordinada_validate = $request['fecha_coordinada'] . ' 00:00:00';
		}

		$request->validate([
			'sucursal_onsite_id' => 'required',
			'id_empresa_onsite' => 'required',
			'id_tipo_servicio' => 'required',
			'id_estado' => 'required',
			'id_terminal' => 'required',
			'fecha_coordinada' => 'nullable|date|after_or_equal:' . $fecha_ingreso_validate,
			'fecha_cerrado' => 'nullable|date_format:Y-m-d H:i:s|after_or_equal:' . $fecha_coordinada_validate,
			'id_tecnico_asignado' => 'required'
		]);

		$reparacionOnsite = $this->reparacionOnsiteService->update($request, $idReparacionOnsite);

		$mjeUpdate = 'ReparacionOnsite: ' . $reparacionOnsite->id . ' - registro modificado correctamente!';

		if ($request['botonGuardarNotificarTecnico']) {
			$mje = $this->reparacionOnsiteService->reenviarMailTecnico($request, $idReparacionOnsite);
			return redirect("/reparacionOnsite/$idReparacionOnsite/edit")->with('message', $mjeUpdate . ' - ' . $mje);
		}

		if ($request['botonGuardarNotificar']) {
			//$this->enviarMailResponsable($reparacionOnsite, $reparacionOnsite->id_empresa_onsite);
			$this->reparacionOnsiteService->enviarMailResponsableEmpresa($reparacionOnsite);
		}

		if ($request['botonGuardarCerrarFacturadas']) {
			return redirect('/reparacionOnsiteFacturada')->with('message', $mjeUpdate);
		}

		return redirect('/reparacionOnsite/' . $reparacionOnsite->id . '/edit')->with('message', $mjeUpdate);
	}

	public function destroy($idReparacionOnsite)
	{
		$reparacionOnsite = $this->reparacionOnsiteService->destroy($idReparacionOnsite);

		$mjeDelete = 'ReparacionOnsite: ' . $reparacionOnsite->id . ' - registro eliminado correctamente!';

		return redirect('/reparacionOnsite')->with('message', $mjeDelete);
	}

	public function indexEmpresaActivas()
	{
		$datos = $this->reparacionOnsiteService->getDataEmpresasActivas();

		return view('_onsite.reparaciononsite.indexEmpresaActivas', $datos);
	}

	/**
	 * Muestra las reparaciones en estado Cerrado de Pago Facil
	 */
	public function indexEmpresaCerradas(Request $request)
	{
		$datos = $this->reparacionOnsiteService->getDataEmpresaCerradas($request);

		return view('_onsite.reparaciononsite.indexEmpresaCerradas', $datos);
	}

	/**
	 * Setea el campo chequeada_cliente en true
	 * de una reparacionOnsite en estado Cerrada
	 */
	public function reparacionOnsiteChequeadoPorCliente($reparacionOnsiteId)
	{
		$reparacionOnsite = $this->reparacionOnsiteService->reparacionOnsiteChequeadoPorCliente($reparacionOnsiteId);

		$mjeUpdate = 'ReparacionOnsite: ' . $reparacionOnsite->clave . ' (' . $reparacionOnsite->id . ') - Chequeado por el usuario.';

		return redirect('reparacionOnsiteEmpresaCerradas')->with('message', $mjeUpdate);
	}

	

	public function filtrarReparacionOnsiteActivas(Request $request)
	{
		$datos = $this->reparacionOnsiteService->filtrarReparacionOnsiteActivas($request);
		return view($datos['vista'], $datos);
	}

	public function filtrarReparacionOnsiteCerradas(Request $request)
	{
		$datos = $this->reparacionOnsiteService->filtrarReparacionOnsiteCerradas($request);
		return view($datos['vista'], $datos);
	}

	public function indexFacturada()
	{
		$datos = $this->reparacionOnsiteService->getDataIndexFacturada();

		return view('_onsite.reparaciononsite.indexFacturada', $datos);
	}

	public function soporteReparacionesOnsite()
	{
		$datos = $this->reparacionOnsiteService->getDataSoporteReparacionOnsite();

		return view('_onsite.reparaciononsite.soporte', $datos);
	}

	public function generarCsv($userCompanyId, $texto, $idEmpresa, $idTerminal, $idTipoServicio, $idEstado, $idTecnico, $fechaVencimiento, $estadosActivos, $liquidadoProveedor,  $includeEmpresa, $excludeEmpresa, $sucursalOnsite, $terminalOnsite, $userId)
	{
		$this->reparacionOnsiteService->generarXlsx($userCompanyId, $texto, $idEmpresa, $idTerminal, $idTipoServicio, $idEstado, $idTecnico, $fechaVencimiento, $estadosActivos, $liquidadoProveedor, $includeEmpresa, $excludeEmpresa, $sucursalOnsite, $terminalOnsite, $userId);
	}

	public function generarCsvExtendido($userCompanyId, $texto, $idEmpresa, $idTerminal, $idTipoServicio, $idEstado, $idTecnico, $fechaVencimiento, $estadosActivo, $liquidadoProveedor, $excludeEmpresa, $extendido, $sucursalOnsite, $terminalOnsite)
	{
		$param['userCompanyId'] = $userCompanyId;
		$param['texto'] = $texto;
		$param['idEmpresa'] = $idEmpresa;
		$param['idTerminal'] = $idTerminal;
		$param['idTipoServicio'] = $idTipoServicio;
		$param['estadosActivo'] = $estadosActivo;
		$param['idEstado'] = $idEstado;
		$param['idTecnico'] = $idTecnico;
		$param['fechaVencimiento'] = $fechaVencimiento;
		$param['liquidadoProveedor'] = $liquidadoProveedor;
		$param['excludeEmpresa'] = $excludeEmpresa;
		$param['sucursalOnsite'] = $sucursalOnsite;
		$param['terminalOnsite'] = $terminalOnsite;
		$param['extendido'] = true;

		$this->reparacionOnsiteService->generarXlsxExtendido($param);
	}

	public function reporteReparacionOnsite($exitoso)
	{
		$datos = $this->reparacionOnsiteService->getDataReporteReparacionOnsite($exitoso);
		$datos['exitoso'] = $exitoso;
		Log::alert('Exitoso: ' . $exitoso);

		return view('_onsite.reparaciononsite.reporte', $datos);
	}



	/**
	 *
	 * @lrd:start
	 * Procesa archivo de excel según modelo para importar reparaciones
	 * @lrd:end
	 * @LRDparam archivo required|file	 

	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function importarReparacionesOnsite(Request $request)
	{
		//$mje = $this->importacionService->importar($request, Auth::user()->id);		

		$file = $request->file('archivo');		
		$uniqueId = Str::uuid(); // Generate a UUID (you can use uniqid() if preferred)
		$originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
		$extension = $file->getClientOriginalExtension();
		$newFileName = "{$originalName}_{$uniqueId}.{$extension}";
		
		$filePath = $file->storeAs('imports', $newFileName);		

		$user = Auth::user()->id;
		$companyId = Session::get('userCompanyIdDefault');

		$notificacion = Notificacion::create([
			'notificacion' => 'Comienza Importación',
			'tipo' => 'importacion'
		]);

		ImportarReparacionesJob::dispatch($filePath, $user, $this->importacionService, $companyId);

		return redirect('/reparacionOnsite')->with('message', 'importación iniciada');
	}

	public function getClaveReparacionOnsite(Request $request)
	{
		$clave = $this->reparacionOnsiteService->getClaveReparacionOnsite($request);
		return $clave;
	}


	public function reenviarMailTecnico(Request $request, $idReparacionOnsite)
	{
		$mje = $this->reparacionOnsiteService->reenviarMailTecnico($request, $idReparacionOnsite);
		return redirect("/reparacionOnsite/$idReparacionOnsite/edit")->with('message', $mje);
	}

	public function getEmpresasOnsiteUsuario()
	{
		$idEmpresas = $this->reparacionOnsiteService->getEmpresasOnsiteUsuario();
		return $idEmpresas;
	}

	public function agregarImagenOnsite(Request $request)
	{

		$datos = $this->reparacionOnsiteService->agregarImagenOnsite($request);


		if ($datos['return'] == 1) {
			return response()->json([
				'status' => 'success',
				'archivo' => '/imagenes/reparaciones_onsite/' . $datos['imagenOnsite']->archivo,
				'tipoImagen' => $datos['tipoImagenOnsite']->nombre,
				'imagenOnsiteId' => $datos['imagenOnsite']->id,
			]);
		} elseif ($datos['return'] == 2) {
			return response()->json([
				'status' => 'error',
				'message' => 'Falta el archivo',
			]);
		} else {
			return response()->json([
				'status' => 'error',
				'message' => 'Falta el tipo de imagen'
			]);
		}
	}

	public function agregarVisita(Request $request)
	{
		return $this->reparacionOnsiteService->agregarVisita($request);
	}

	public function eliminarImagenOnsite($id)
	{
		$imagenOnsite = $this->reparacionOnsiteService->eliminarImagenOnsite($id);

		if ($imagenOnsite) {
			return response()->json([
				'status' => 'success',
				'message' => 'Imagen onsite eliminada'
			]);
		} else
			return response()->json([
				'status' => 'error',
				'message' => 'Imágen no encontrada'
			]);
	}

	/**
	 * Devuelve un listado de reparaciones onsite con estado del tipo pendientes de aprobacion de presupuesto
	 *
	 * @return void
	 */
	public function indexReparacionOnsiteConPresupuestoPendienteDeAprobacion()
	{
		$datos = $this->reparacionOnsiteService->getDataIndexReparacionOnsiteConPresupuestoPendienteDeAprobacion();

		return view('_onsite.reparaciononsite.indexReparacionOnsiteConPresupuestoPendienteDeAprobacion', $datos);
	}




	public function obtenerTipoTerminal($id)
	{
		$tipo_terminal = $this->reparacionOnsiteService->obtenerTipoTerminal($id);
		return response()->json($tipo_terminal, 200);
	}



	public function updateReparacionSeguimiento($reparacionOnsiteSeguimientoId, $camposChecklist)
	{
		$this->reparacionOnsiteService->updateReparacionSeguimiento($reparacionOnsiteSeguimientoId, $camposChecklist);
	}



	public function getReparacionPorId($idReparacion)
	{
		$reparacion = $this->reparacionOnsiteService->findReparacion($idReparacion);

		return response()->json($reparacion);
	}

	function registrar_visita(Request $request, $reparacion_id)
	{
		$reparacion_visita = $this->reparacionOnsiteService->registrar_visita($request, $reparacion_id);

		return redirect()->route('reparacionOnsite.edit', $reparacion_id)->with('success', 'Primer visita registrada correctamente');
	}




	public function getPromedioCoordinadasCerradas()
	{
		$reparacion = $this->reparacionOnsiteService->getPromedioCoordinadasCerradas();

		return response()->json($reparacion);
	}

	public function makeHtmlGarantiaPdf($reparacion)
	{
		/* $html = '<link rel="stylesheet" href="/assets/css/base.min.css" type="text/css">';
        $html .= '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/css/tempusdominus-bootstrap-4.min.css" />'; */
		$html = '
        <style>
            .customers {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
            }
            .customers td, .customers th {
            border: 1px solid #ddd;
            padding: 8px;
            }
            .customers tr:nth-child(even){background-color: #f2f2f2;}
            .customers tr:hover {background-color: #ddd;}
            .customers th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            background-color: #354a43;
            color: white;
            }
        </style>';

		$html .= $reparacion['comprobante'];


		return $html;
	}

	public function findReparacionById($idReparacion){
		//Retorna un registro en formato json en base al parametro idReparacion
		$company_id = Session::get('userCompanyIdDefault');
		//dd($idReparacion);
		$reparacion = ReparacionOnsite::with(['estado_onsite', 'sucursal_onsite'])->where([['company_id', $company_id],['clave',$idReparacion]])->get();
		return response()->json($reparacion);
	}
	

	public function importarReparacionOnsite()
	{
		return view('_onsite.reparaciononsite.imports.createImportReparacionOnsite');
	}

	public function importFileReparacionOnsite(Request $request)
	{
		$messages = [
			'file.required' => 'Seleccione un archivo de Excel para procesar.'
		];

		$request->validate([
			'file' => 'required|max:10000|mimes:xlsx,xls',
		], $messages);

		$excel = $request->file('file');
		$import = new ReparacionOnsiteImport($this->reparacionOnsiteService, $this->importacionService);


		Excel::import($import, $excel);

		$result_processed = ['rows_processed' => $import->getRowCount()];
		$failures_rows = '';

		$i = 0;

		$result_processed = $this->processResult($import, 'assurant_deducibles');

		if ($result_processed) return $result_processed;
	}

	function processResult($import, $tipo)
	{
		$result_processed = ['rows_processed' => $import->getRowCount()];
		$failures_rows = '';

		$i = 0;

		foreach ($import->failures() as $failure) {
			if ($i < count($import->failures()) - 1)
				$failures_rows .= '[' . $failure->row() . ']-';
			else {
				$failures_rows .= '[' . $failure->row() . ']';
			}
			$i++;
		}

		/* las funciones de php se utilizan para convertir el string en array y tomar valores únicos, luego se vuelve a convertir en string */
		$result_processed['rows_failures'] = implode('-', array_unique(explode('-', $failures_rows), SORT_STRING));

		$result_processed['rows_failures_total'] = count(array_unique(explode('-', $failures_rows), SORT_STRING));

		Log::info('Row count: ' . $import->getRowCount());
		Log::info('Errors: ');
		Log::info($import->errors());

		$failures = $import->failures();


		if ($tipo == 'reparaciones') {
			if (count($failures) > 0) {
				Log::info('No se procesaron las siguiente filas: ');
				foreach ($failures as $failure) {
					Log::info('ROW: ' . $failure->row() . '-' . $failure->attribute() . ': ' . $failure->values()['ticketunico']); // The values of the row that has failed.
				}
			}

			
		} else {
			if (count($failures) > 0) {
				Log::info('No se procesaron las siguiente filas: ');
				foreach ($failures as $failure) {
					Log::info('ROW: ' . $failure->row() . '-' . $failure->attribute() . ': ' . $failure->values()['marca'] . ' - ' . $failure->values()['codigo_sap']); // The values of the row that has failed.
				}
			}
		}

		Log::info($result_processed);
		return $result_processed;
	}


	function getRowsReparacionesProcessed()
	{
		$reparacion_mirgor = ReparacionOnsite::orderby('id', 'desc')->first();

		if ($reparacion_mirgor)

			return  $reparacion_mirgor->id;

		else return 1;
	}
}
