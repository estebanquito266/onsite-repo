<?php

namespace App\Services\Onsite\Reparacion;

use App\Models\Onsite\EmpresaOnsite;
use App\Models\Onsite\EstadoOnsite;
use App\Models\Onsite\HistorialEstadoOnsite;
use App\Models\Onsite\ImagenOnsite;
use App\Models\Onsite\ReparacionOnsite;
use App\Models\Onsite\SucursalOnsite;
use App\Models\Onsite\TerminalOnsite;
use App\Models\Onsite\TipoImagenOnsite;
use App\Models\Onsite\TipoServicioOnsite;
use App\Models\Parametro;
use App\Models\User;
use App\Repositories\Onsite\EstadoOnsiteRepository;
use App\Services\Onsite\EmpresaOnsiteService;
use App\Services\Onsite\EstadosService;
use App\Services\Onsite\ImagenesOnsiteService;
use App\Services\Onsite\LocalidadOnsiteService;
use App\Services\Onsite\MailOnsiteService;
use App\Services\Onsite\ParametroService;
use App\Services\Onsite\ReparacionOnsiteService;
use App\Services\Onsite\SucursalOnsiteService;
use App\Services\Onsite\TerminalOnsiteService;
use App\Services\Onsite\TiposServiciosService;
use App\Services\Onsite\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class ImportacionService
{
	private $salteadosTerminal = 0;
	private $filasTerminal;
	private $salteadosError = 0;
	private $filasError;
	private $terminalesCreadas = 0;
	private $reparacionesCreadas = 0;
	private $sucursalesCreadas = 0;
	private $salteadosExistentes = 0;
	private $reparacionesActualizadas = 0;
	private $filasActualizadas;
	private $usuarioId;
	private $companyId;
	private $filasExistentes;

	protected $sucursalesOnsite, $localidadesOnsite, $mailOnsiteService, $usersService, $parametrosService;
	protected $reparacionesOnsiteService, $imagenesOnsite, $estadosOnsite, $tiposServiciosOnsite, $empresasOnsiteService, $terminalesOnsiteService;

	public function __construct(
		SucursalOnsiteService $sucursalesOnsite,
		LocalidadOnsiteService $localidadesOnsite,
		MailOnsiteService $mailOnsiteService,
		UserService $usersService,
		ParametroService $parametrosService,
		ReparacionOnsiteService $reparacionesOnsiteService,
		ImagenesOnsiteService $imagenesOnsite,
		EstadosService $estadosOnsite,
		TiposServiciosService $tiposServiciosOnsite,
		EmpresaOnsiteService $empresasOnsiteService,
		TerminalOnsiteService $terminalesOnsiteService
	) {
		$this->sucursalesOnsite = $sucursalesOnsite;
		$this->localidadesOnsite = $localidadesOnsite;
		$this->mailOnsiteService = $mailOnsiteService;
		$this->usersService = $usersService;
		$this->parametrosService = $parametrosService;
		$this->reparacionesOnsiteService = $reparacionesOnsiteService;
		$this->imagenesOnsite = $imagenesOnsite;
		$this->estadosOnsite = $estadosOnsite;
		$this->tiposServiciosOnsite = $tiposServiciosOnsite;
		$this->empresasOnsiteService = $empresasOnsiteService;
		$this->terminalesOnsiteService = $terminalesOnsiteService;
	}

	public function importar(/* Request $request, */$filePath, $usuarioId, $company_id = null)
	{
		Log::info('=======================================');
		Log::info('ImportacionService - importar');

		//$file = $request['archivo'];

		// Retrieve the file from storage
		if (!Storage::exists($filePath)) {
			Log::error("File not found: {$filePath}");
			return "Error: File not found.";
		}

		// Get the full file path
		$absolutePath = storage_path("app/{$filePath}");

		// Ensure the file exists at the absolute path
		if (!file_exists($absolutePath)) {
			Log::error("Absolute file path not found: {$absolutePath}");
			return "Error: Absolute file path not found.";
		}

		// Convert the CSV to an array
		$registros = $this->csvToArray($absolutePath);




		if (is_null($company_id)) {
			$this->companyId = Session::get('userCompanyIdDefault');
		} else $this->companyId = $company_id;

		$this->usuarioId = $usuarioId;


		$this->salteadosError = 0;
		$this->filasError = '';

		$this->procesar_array_reparacion($registros, $company_id, $this->usuarioId);

		$mje = "Archivo importado correctamente! <br>";
		$mje .= "Resumen: <br>";
		$mje .= "Terminales creadas: " . $this->terminalesCreadas . " <br>";
		$mje .= "Reparaciones creadas: " . $this->reparacionesCreadas . " <br>";
		$mje .= "Sucursales creadas: " . $this->sucursalesCreadas . " <br>";
		$mje .= "Salteadas por Existentes: $this->salteadosExistentes " . ($this->salteadosExistentes ? "(filas: $this->filasExistentes ) - " : " - ") . "<br>";
		$mje .= "Salteadas por Errores: $this->salteadosError " . ($this->salteadosError ? "(filas: $this->filasError ) " : " - ") . "<br>";
		$mje .= "Reparaciones actualizadas: $this->reparacionesActualizadas ";

		Log::info('ImportacionService - importar - END');
		Log::info('=======================================');

		return $mje;
	}

	function storeReparacionApi(Request $request, $company_id, $usuario_id)
	{
		$this->companyId = $company_id;
		$this->usuarioId = $usuario_id;

		$registros = [$request->all()];

		$procesar = $this->procesar_array_reparacion($registros);

		if ($procesar)
			$response = [
				"reparaciones_creadas" => $this->reparacionesCreadas ?? 0,
				"Terminales_creadas" => $this->terminalesCreadas ?? 0,
				"Reparaciones_creadas" => $this->reparacionesCreadas ?? 0,
				"Sucursales_creadas" => $this->sucursalesCreadas ?? 0,
				"Salteadas_por_Existentes" => $this->salteadosExistentes ?? 0,
				"Salteadas_por_Errores" =>  $this->salteadosError ?? 0,
				"Reparaciones_actualizadas" => $this->reparacionesActualizadas ?? 0
			];

		else $response = false;

		return $response;
	}

	public function procesar_array_reparacion($registros, $company_id = null, $user_id = null)
	{
		if (!is_null($company_id))
			$this->companyId = $company_id;

		$succes = false;
		foreach ($registros as $fila => $registro) {

			try {

				Log::info('---------------PROCESAR------------------------');

				$clave = (isset($registro['CLAVE'])) ? $registro['CLAVE'] : null;
				$reparacionOnsiteId = (isset($registro['ID_REPARACION'])) ? $registro['ID_REPARACION'] : null;
				$nroTerminal = (isset($registro['NRO_TERMINAL'])) ? $registro['NRO_TERMINAL'] : null;
				$tipoServicioId = (isset($registro['ID_TIPO_SERVICIO'])) ? $registro['ID_TIPO_SERVICIO'] : null;
				$empresaId = (isset($registro['ID_EMPRESA_ONSITE'])) ? $registro['ID_EMPRESA_ONSITE'] : null;
				$codigoSucursal = (isset($registro['CODIGO_SUCURSAL'])) ? $registro['CODIGO_SUCURSAL'] : null;
				$sucursalId = (isset($registro['ID_SUCURSAL'])) ? $registro['ID_SUCURSAL'] : null;
				$estadoId = (isset($registro['ID_ESTADO_ONSITE'])) ? $registro['ID_ESTADO_ONSITE'] : null;
				$sucursalRazonSocial = (isset($registro['SUCURSAL_RAZON_SOCIAL'])) ? $registro['SUCURSAL_RAZON_SOCIAL'] : null;
				$sucursalDireccion = (isset($registro['SUCURSAL_DIRECCION'])) ? $registro['SUCURSAL_DIRECCION'] : null;
				$sucursalTelefono = (isset($registro['SUCURSAL_TELEFONO'])) ? $registro['SUCURSAL_TELEFONO'] : null;
				$sucursalContacto = (isset($registro['SUCURSAL_CONTACTO'])) ? $registro['SUCURSAL_CONTACTO'] : '--';
				$localidadCodigoPostal = (isset($registro['LOCALIDAD_CODIGO_POSTAL'])) ? $registro['LOCALIDAD_CODIGO_POSTAL'] : null;
				$localidadId = (isset($registro['LOCALIDAD_ONSITE_ID'])) ? $registro['LOCALIDAD_ONSITE_ID'] : null;

				$reparacionOnsite = $this->getReparacionOnsite($reparacionOnsiteId, $clave);

				if (!$reparacionOnsite && !$clave) {
					$requestClave = new Request([
						'id_empresa_onsite' => $empresaId
					]);
					$clave = $this->reparacionesOnsiteService->getClaveReparacionOnsite($requestClave);
				}

				$estadoReparacionOnsiteId = $estadoId;

				$tipoServicioOnsite = $this->getTipoServicioOnsite($tipoServicioId);

				$empresaOnsite = $this->getEmpresaOnsite($empresaId);

				$sucursalOnsite = null;
				if ($empresaOnsite) {
					$paramSucursal = [
						'empresaId' => $empresaId,
						'codigoSucursal' => $codigoSucursal,
						'sucursalId' => $sucursalId,
						'sucursalRazonSocial' => $sucursalRazonSocial,
						'sucursalDireccion' => $sucursalDireccion,
						'sucursalTelefono' => $sucursalTelefono,
						'sucursalContacto' => $sucursalContacto,
						'localidadId' => $localidadId,
						'localidadCodigoPostal' => $localidadCodigoPostal,
						'companyId' => $this->companyId,
					];
					$sucursalOnsite = $this->getSucursalOnsite($paramSucursal);
				}


				if (!$reparacionOnsite) {


					$estadoReparacionOnsiteId = $this->getEstadoReparacionOnsiteId($estadoId);

					$terminal = $this->getTerminalOnsite($nroTerminal, $sucursalOnsite, $empresaOnsite, $fila, $registro);

					$reparacionOnsite = $this->insertReparacionOnsite($clave, $empresaOnsite, $sucursalOnsite, $terminal, $tipoServicioOnsite, $estadoReparacionOnsiteId, $registro, $company_id);

					if ($reparacionOnsite != null) {

						$this->reparacionesCreadas++;

						$this->reparacionesOnsiteService->actualizarImagenes($reparacionOnsite['reparacionOnsite'], $registro);
					} else {
						$this->salteadosError++;
						$this->filasError = $this->filasError . ' ' . (intval($fila) + 2) . ',';
					}
				} else {

					//busca estado
					$idEstadoOld = null;
					$idEstadoNew = null;

					if ($estadoReparacionOnsiteId) {
						$idEstadoOld = $reparacionOnsite->id_estado;
						$idEstadoNew = $estadoReparacionOnsiteId;
					}

					$reparacionOnsite = $this->updateReparacionOnsite($reparacionOnsite, $estadoReparacionOnsiteId, $tipoServicioOnsite, $registro, $company_id, $user_id);

					if ($reparacionOnsite != null) {

						$this->reparacionesActualizadas++;
						$this->filasActualizadas = $this->filasActualizadas . ' ' . ($fila + 2) . ',';
					} else {
						$this->salteadosExistentes++;
						$this->filasExistentes = $this->filasExistentes . ' ' . ($fila + 2) . ',';
					}
				}

				$succes = true;
			} catch (\Throwable $th) {
				Log::alert('error creando la reparacion ' . get_class($this) . '-' . __LINE__);
				Log::alert($th);
			}
		}

		return $succes;
	}

	private function updateReparacionOnsite($reparacionOnsite, $estadoReparacionOnsiteId, $tipoServicioOnsite, $registro, $company_id = null, $user_id = null)
	{
		Log::info('ImportadorService - updateReparacionOnsite');
		if ($reparacionOnsite) {

			$data = [];



			if (isset($registro['ID_EMPRESA_ONSITE']) && $registro['ID_EMPRESA_ONSITE']) {
				$data['id_empresa_onsite'] = $registro['ID_EMPRESA_ONSITE'];
			}

			if (isset($registro['SUCURSAL_ONSITE_ID']) && $registro['SUCURSAL_ONSITE_ID']) {
				$data['sucursal_onsite_id'] = $registro['SUCURSAL_ONSITE_ID'];
			}

			if (isset($registro['ID_TERMINAL']) && $registro['ID_TERMINAL']) {
				$data['id_terminal'] = $registro['ID_TERMINAL'];
			}

			if (isset($registro['TAREA']) && $registro['TAREA']) {
				//settype($registro['TAREA'], "string");
				$data['tarea'] = (string) $registro['TAREA'];
				$data['tarea'] = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $data['tarea']);
			}

			if (isset($registro['DETALLE_TAREA']) && $registro['DETALLE_TAREA']) {
				//settype($registro['DETALLE_TAREA'], "string");
				$data['tarea_detalle'] = (string) $registro['DETALLE_TAREA'];
				$data['tarea_detalle'] = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $data['tarea_detalle']);
			}

			if ($tipoServicioOnsite) {
				$data['id_tipo_servicio'] = $tipoServicioOnsite->id;
			}

			if ($estadoReparacionOnsiteId) {
				$data['id_estado'] = $estadoReparacionOnsiteId;
			}

			if (isset($registro['FECHA_INGRESO']) && $registro['FECHA_INGRESO']) {
				$data['fecha_ingreso'] = $registro['FECHA_INGRESO'];
			}

			if (isset($registro['OBSERVACION_UBICACION'])) {
				$data['observacion_ubicacion'] = $registro['OBSERVACION_UBICACION'];
			}

			if (isset($registro['FECHA_COORDINADA']) && $registro['FECHA_COORDINADA']) {
				$data['fecha_coordinada'] = $registro['FECHA_COORDINADA'];
			}


			if (isset($registro['FECHA_VENCIMIENTO']) && $registro['FECHA_VENCIMIENTO']) {
				$data['fecha_vencimiento'] = $registro['FECHA_VENCIMIENTO'];
			}

			if (isset($registro['FECHA_CERRADO']) && $registro['FECHA_CERRADO']) {
				$data['fecha_cerrado'] = $registro['FECHA_CERRADO'];
			}

			if (isset($registro['SLA_STATUS']) && $registro['SLA_STATUS']) {
				$data['sla_status'] = $registro['SLA_STATUS'];
			}

			if (isset($registro['SLA_JUSTIFICADO']) && $registro['SLA_JUSTIFICADO']) {
				$data['sla_justificado'] = $registro['SLA_JUSTIFICADO'];
			}

			if (isset($registro['MONTO']) && $registro['MONTO']) {
				$data['monto'] = $registro['MONTO'];
			}

			if (isset($registro['MONTO_EXTRA']) && $registro['MONTO_EXTRA']) {
				$data['monto_extra'] = $registro['MONTO_EXTRA'];
			}

			if (isset($registro['LIQUIDADO_PROVEEDOR']) && $registro['LIQUIDADO_PROVEEDOR']) {
				$data['liquidado_proveedor'] = $registro['LIQUIDADO_PROVEEDOR'];
			}


			if (isset($registro['VISIBLE_CLIENTE']) && $registro['VISIBLE_CLIENTE']) {
				$data['visible_cliente'] = $registro['VISIBLE_CLIENTE'];
			}

			if (isset($registro['CHEQUEADO_CLIENTE']) && $registro['CHEQUEADO_CLIENTE']) {
				$data['chequeado_cliente'] = $registro['CHEQUEADO_CLIENTE'];
			}

			if (isset($registro['PROBLEMA_RESUELTO']) && $registro['PROBLEMA_RESUELTO']) {
				$data['problema_resuelto'] = $registro['PROBLEMA_RESUELTO'];
			}

			if (isset($registro['USUARIO_ID']) && $registro['USUARIO_ID']) {
				$data['usuario_id'] = $registro['USUARIO_ID'];
			}

			if (isset($registro['NOTA_CLIENTE']) && $registro['NOTA_CLIENTE']) {
				$data['nota_cliente'] = $registro['NOTA_CLIENTE'];
			}

			if (isset($registro['OBSERVACIONES_INTERNAS']) && $registro['OBSERVACIONES_INTERNAS']) {
				$data['observaciones_internas'] = $registro['OBSERVACIONES_INTERNAS'];
			}

			if (isset($registro['INFORME_TECNICO']) && $registro['INFORME_TECNICO']) {
				$data['informe_tecnico'] = $registro['INFORME_TECNICO'];
			}

			if (isset($registro['JUSTIFICACION']) && $registro['JUSTIFICACION']) {
				$data['justificacion'] = $registro['JUSTIFICACION'];
			}

			/* este codigo reemplaza la porción comentada de abajo */

			$fields = [
				'codigo_activo_descripcion',
				'codigo_activo_nuevo',
				'codigo_activo_retirado'
			];

			for ($i = 1; $i <= 10; $i++) {
				foreach ($fields as $field) {
					$key = "{$field}{$i}";
					if (isset($registro[$key]) && $registro[$key]) {
						$data[$key] = $registro[$key];
					}
				}
			}




			for ($i = 1; $i <= 10; $i++) {
				if (isset($registro['IMAGEN_ONSITE_' . $i]) && !empty($registro['IMAGEN_ONSITE_' . $i])) {
					$data['IMAGEN_ONSITE_' . $i] = $registro['IMAGEN_ONSITE_' . $i];
				}
			}

			$data['ruta'] = 'reparacionOnsite';

			$dataRequest = new Request($data);

			$reparacionOnsite = $this->reparacionesOnsiteService->update($dataRequest, $reparacionOnsite->id, $company_id, $user_id);

			/* registra reparacion_visita */
			if (
				isset($registro['fecha_1_visita']) && isset($registro['FECHA_VENCIMIENTO']) && isset($registro['fecha_nuevo_vencimiento'])
				&& !empty($registro['fecha_1_visita']) && !empty($registro['FECHA_VENCIMIENTO']) && !empty($registro['fecha_nuevo_vencimiento'])
			) {

				$reparacion_id = $reparacionOnsite->id;
				$request_visitas = new Request([
					'company_id' => $this->companyId,
					'fecha' => $registro['fecha_1_visita'],
					'fecha_vencimiento' => $registro['FECHA_VENCIMIENTO'],
					'fecha_nuevo_vencimiento' => $registro['fecha_nuevo_vencimiento'],
					'motivo' => $registro['motivo'] ?? null,

				]);

				try {

					$reparacion_visita = $this->reparacionesOnsiteService->registrar_visita($request_visitas, $reparacion_id);
				} catch (\Throwable $th) {
					Log::alert('error reparacion_visita');
					Log::alert($th);
				}
			}

			return $reparacionOnsite;
		}
		return null;
	}

	private function insertReparacionOnsite($clave, $empresaOnsite, $sucursalOnsite, $terminal, $tipoServicioOnsite, $estadoReparacionOnsiteId, $registro, $company_id = null)
	{
		Log::info('ImportacionService - insertReparacionOnsite');
		//Log::info($clave);
		//Log::info($empresaOnsite);
		//Log::info($sucursalOnsite);
		//Log::info($terminal);
		//Log::info($tipoServicioOnsite);
		//Log::info($estadoReparacionOnsiteId);
		//Log::info($registro);

		if ($clave && $empresaOnsite && $sucursalOnsite && $terminal && $tipoServicioOnsite) {
			$monto = (isset($registro['MONTO']) && $registro['MONTO']) ? $registro['MONTO'] : 0;
			$montoExtra = (isset($registro['MONTO_EXTRA']) && $registro['MONTO_EXTRA']) ? $registro['MONTO_EXTRA'] : 0;
			$liquidadoProveedor = (isset($registro['LIQUIDADO_PROVEEDOR']) && $registro['LIQUIDADO_PROVEEDOR']) ? $registro['LIQUIDADO_PROVEEDOR'] : null;
			$tarea = '--';

			if (isset($registro['TAREA'])) {
				$tarea = (string) $registro['TAREA'];
				$tarea  = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $tarea);
			}

			$detalleTarea = '--';

			if (isset($registro['TAREA'])) {
				$detalleTarea = (string) $registro['DETALLE_TAREA'];
				$detalleTarea  = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $detalleTarea);
			}

			$fechaIngreso = (isset($registro['FECHA_INGRESO'])) ? $registro['FECHA_INGRESO'] : null;
			$fechaVencimiento = (isset($registro['FECHA_VENCIMIENTO'])) ? $registro['FECHA_VENCIMIENTO'] : null;
			$fechaCerrado = (isset($registro['FECHA_CERRADO'])) ? $registro['FECHA_CERRADO'] : null;
			$slaStatus = (isset($registro['SLA_STATUS'])) ? $registro['SLA_STATUS'] : null;
			$usuarioTecnicoId = User::_TECHNICAL;

			if ($sucursalOnsite && $sucursalOnsite->localidad_onsite) {
				$usuarioTecnicoId = $sucursalOnsite->localidad_onsite->id_usuario_tecnico;
			}

			$informeTecnico = (isset($registro['INFORME_TECNICO'])) ? $registro['INFORME_TECNICO'] : null;
			$problemaResuelto = (isset($registro['PROBLEMA_RESUELTO'])) ? $registro['PROBLEMA_RESUELTO'] : null;
			$observacionesInternas = (isset($registro['OBSERVACIONES_INTERNAS'])) ? $registro['OBSERVACIONES_INTERNAS'] : null;
			$visibleCliente = (isset($registro['VISIBLE_CLIENTE'])) ? $registro['VISIBLE_CLIENTE'] : null;

			$observacionUbicacion = (isset($registro['OBSERVACION_UBICACION'])) ? $registro['OBSERVACION_UBICACION'] : '-';

			$justificacion = (isset($registro['JUSTIFICACION'])) ? $registro['JUSTIFICACION'] : null;

			$observacion = 'Reparación generada por Importación';

			$nuevaReparacionOnsite = new Request(array(
				'clave' => $clave,
				'id_terminal' => $terminal->nro,
				'tarea' => $tarea,
				'tarea_detalle' => $detalleTarea,
				'fecha_ingreso' => $fechaIngreso,
				'id_tipo_servicio' => $tipoServicioOnsite->id,
				'id_estado' => $estadoReparacionOnsiteId,
				'observacion_ubicacion' => $observacionUbicacion,
				'sucursal_onsite_id' => $sucursalOnsite->id,
				'id_tecnico_asignado' => $usuarioTecnicoId,
				'id_empresa_onsite' => $empresaOnsite->id,
				'fecha_vencimiento' => $fechaVencimiento,
				'fecha_cerrado' => $fechaCerrado,
				'monto' => $monto,
				'monto_extra' => $montoExtra,
				'liquidado_proveedor' => $liquidadoProveedor,
				'sla_status' => $slaStatus,
				'usuario_id' => $this->usuarioId,
				'company_id' => $this->companyId,
				'observacion' => $observacion,
				'informeTecnico' => $informeTecnico,
				'problemaResuelto' => $problemaResuelto,
				'observacionesInternas' => $observacionesInternas,
				'visibleCliente' => $visibleCliente,
				'justificacion' => $justificacion,

				'codigo_activo_descripcion1' => $registro['codigo_activo_descripcion1'] ?? null,
				'codigo_activo_descripcion10' => $registro['codigo_activo_descripcion10'] ?? null,
				'codigo_activo_descripcion2' => $registro['codigo_activo_descripcion2'] ?? null,
				'codigo_activo_descripcion3' => $registro['codigo_activo_descripcion3'] ?? null,
				'codigo_activo_descripcion4' => $registro['codigo_activo_descripcion4'] ?? null,
				'codigo_activo_descripcion5' => $registro['codigo_activo_descripcion5'] ?? null,
				'codigo_activo_descripcion6' => $registro['codigo_activo_descripcion6'] ?? null,
				'codigo_activo_descripcion7' => $registro['codigo_activo_descripcion7'] ?? null,
				'codigo_activo_descripcion8' => $registro['codigo_activo_descripcion8'] ?? null,
				'codigo_activo_descripcion9' => $registro['codigo_activo_descripcion9'] ?? null,
				'codigo_activo_nuevo1' => $registro['codigo_activo_nuevo1'] ?? null,
				'codigo_activo_nuevo10' => $registro['codigo_activo_nuevo10'] ?? null,
				'codigo_activo_nuevo2' => $registro['codigo_activo_nuevo2'] ?? null,
				'codigo_activo_nuevo3' => $registro['codigo_activo_nuevo3'] ?? null,
				'codigo_activo_nuevo4' => $registro['codigo_activo_nuevo4'] ?? null,
				'codigo_activo_nuevo5' => $registro['codigo_activo_nuevo5'] ?? null,
				'codigo_activo_nuevo6' => $registro['codigo_activo_nuevo6'] ?? null,
				'codigo_activo_nuevo7' => $registro['codigo_activo_nuevo7'] ?? null,
				'codigo_activo_nuevo8' => $registro['codigo_activo_nuevo8'] ?? null,
				'codigo_activo_nuevo9' => $registro['codigo_activo_nuevo9'] ?? null,
				'codigo_activo_retirado1' => $registro['codigo_activo_retirado1'] ?? null,
				'codigo_activo_retirado10' => $registro['codigo_activo_retirado10'] ?? null,
				'codigo_activo_retirado2' => $registro['codigo_activo_retirado2'] ?? null,
				'codigo_activo_retirado3' => $registro['codigo_activo_retirado3'] ?? null,
				'codigo_activo_retirado4' => $registro['codigo_activo_retirado4'] ?? null,
				'codigo_activo_retirado5' => $registro['codigo_activo_retirado5'] ?? null,
				'codigo_activo_retirado6' => $registro['codigo_activo_retirado6'] ?? null,
				'codigo_activo_retirado7' => $registro['codigo_activo_retirado7'] ?? null,
				'codigo_activo_retirado8' => $registro['codigo_activo_retirado8'] ?? null,
				'codigo_activo_retirado9' => $registro['codigo_activo_retirado9'] ?? null

			));

			Log::info($nuevaReparacionOnsite);

			$reparacion = $this->reparacionesOnsiteService->store($nuevaReparacionOnsite, $company_id, $this->usuarioId);


			$reparacion_id = $reparacion['reparacionOnsite']->id;
			$request_visitas = new Request([
				'company_id' => $this->companyId,
				'fecha' => $registro['fecha_1_visita'],
				'fecha_vencimiento' => $registro['FECHA_VENCIMIENTO'],
				'fecha_nuevo_vencimiento' => $registro['fecha_nuevo_vencimiento'],
				'motivo' => $registro['motivo'],

			]);

			try {

				$reparacion_visita = $this->reparacionesOnsiteService->registrar_visita($request_visitas, $reparacion_id);
			} catch (\Throwable $th) {
				Log::alert('error reparacion visita importar');
				Log::alert($th);
			}


			return $reparacion;
		}
		return null;
	}

	/*
	private function insertHistorialEstadoOnsite($reparacionOnsite, $estadoReparacionOnsiteId, $observacion)
	{

		$nuevoHistorialEstadoOnsite = array(
			'id_reparacion' => $reparacionOnsite->id,
			'id_estado' => $estadoReparacionOnsiteId,
			'fecha' => date('Y-m-d H:i:s'),
			'observacion' => $observacion,
			'id_usuario' => $this->usuarioId,
			'visible' => false
		);

		HistorialEstadoOnsite::create($nuevoHistorialEstadoOnsite);
	}
	*/

	private function getEstadoReparacionOnsiteId($estadoId)
	{

		$estadoReparacionOnsiteId = EstadoOnsiteRepository::ESTADO_NUEVO;
		if ($estadoId) {
			$estadoOnsite = $this->estadosOnsite->getEstadoById($estadoId);
			if ($estadoOnsite) {
				return $estadoOnsite->id;
			}
		}
		return $estadoReparacionOnsiteId;
	}

	private function getTipoServicioOnsite($tipoServicioOnsiteId)
	{
		if ($tipoServicioOnsiteId) {
			return $this->tiposServiciosOnsite->getTipoServicioById($tipoServicioOnsiteId);
		}
		return null;
	}

	private function getEmpresaOnsite($empresaOnsiteId)
	{
		if ($empresaOnsiteId) {
			return $this->empresasOnsiteService->getEmpresaById($empresaOnsiteId);
		}
		return null;
	}

	private function getTerminalOnsite($nroTerminal, $sucursalOnsite, $empresaOnsite, $fila, $registro = false)
	{
		Log::info('ImportacionService - getTerminalOnsite');


		if ($nroTerminal) {
			$terminal = $this->terminalesOnsiteService->getTerminalByNro($nroTerminal);

			if ($terminal) {
				$this->salteadosTerminal++;
				$this->filasTerminal = $this->filasTerminal . ' ' . ($fila + 2) . ',';
			} else {
				$terminal = $this->crearTerminalOnsite($sucursalOnsite, $empresaOnsite, $registro);
			}
		} else {

			$terminal = $this->crearTerminalOnsite($sucursalOnsite, $empresaOnsite, $registro);
		}
		return $terminal;
	}

	private function crearTerminalOnsite($sucursalOnsite, $empresaOnsite, $data = false)
	{
		Log::info('ImportacionService - crearTerminalOnsite');

		$terminal = null;
		$numero_terminal = $this->getNroTerminalOnsite($empresaOnsite, $sucursalOnsite->id);

		if ($data) {
			if (isset($data['NRO_TERMINAL']) && strlen($data['NRO_TERMINAL']) > 1)
				$numero_terminal =  $data['NRO_TERMINAL'];
		}


		if ($empresaOnsite && $sucursalOnsite) {
			$terminalArray = array(
				'nro' => $numero_terminal,
				'sucursal_onsite_id' => $sucursalOnsite->id,
				'empresa_id' => $sucursalOnsite->empresa_onsite_id,
				'company_id' => $this->companyId,
				'all_terminales_sucursal' => intval($data['all_terminales_sucursal'] ?? 0),
				'marca' => $data['marca'] ?? '-',
				'modelo' => $data['modelo'] ?? '-',
				'serie' => $data['serie'] ?? '-',
				'rotulo' => $data['rotulo'] ?? '-',
				'observaciones' => $data['observaciones_terminal'] ?? '-',


			);

			$terminalArrayRequest = new Request($terminalArray);

			Log::info($terminalArray);

			Log::info('ImportacionService - crearTerminalOnsite - store');

			$terminal = $this->terminalesOnsiteService->store($terminalArrayRequest);
			$this->terminalesCreadas++;
		}
		return $terminal;
	}

	public function getNroTerminalOnsite($empresaOnsite, $sucursalOnsiteId)
	{
		$nro = strtoupper(substr($empresaOnsite->nombre, 0, 2));
		$nro .= $sucursalOnsiteId;

		$cantTerminalesOnsite = $this->terminalesOnsiteService->getCountTerminalesBySucursal($sucursalOnsiteId);

		$cantTerminalesOnsite = $cantTerminalesOnsite + 2;
		$cantTerminalesOnsite = str_pad($cantTerminalesOnsite, 3, "0", STR_PAD_LEFT);
		$nro .= $cantTerminalesOnsite;

		return $nro;
	}

	private function getReparacionOnsite($reparacionOnsiteId, $clave)
	{
		$reparacionOnsite = null;
		if ($reparacionOnsiteId != null) {
			$reparacionOnsite = $this->reparacionesOnsiteService->getReparacionById($reparacionOnsiteId);
		}

		if ($reparacionOnsite == null && $clave != null) {
			$reparacionOnsite = $this->reparacionesOnsiteService->getReparacionByClave($clave);
		}

		return $reparacionOnsite;
	}

	private function getSucursalOnsite($param)
	{
		Log::info('ImportacionService - getSucursalOnsite');
		//Log::info($param);
		$sucursalOnsite = null;

		if ($param['sucursalId'] != null) {
			$sucursalOnsite = $this->sucursalesOnsite->getSucursalById($param['sucursalId']);
		} else if ($sucursalOnsite == null && $param['codigoSucursal'] != null) {
			$sucursalOnsite = $this->sucursalesOnsite->getSucursalByCodigo($param['codigoSucursal']);
		}

		if (!$sucursalOnsite) {
			Log::info('ImportacionService - getSucursalOnsite - inexistente');
			$localidad = $this->localidadesOnsite->getLocalidadById($param['localidadId']);

			if (!$localidad) {
				$localidad = $this->localidadesOnsite->getLocalidadByCodigoPostal($param['localidadCodigoPostal']);
			}
			//Log::info($localidad);

			if ($localidad) {
				Log::info('ImportacionService - getSucursalOnsite - store Sucursal');
				$arraySucursalOnsite = [
					'codigo_sucursal' => $param['codigoSucursal'],
					'empresa_onsite_id' => $param['empresaId'],
					'razon_social' => $param['sucursalRazonSocial'],
					'localidad_onsite_id' => $localidad->id,
					'direccion' => $param['sucursalDireccion'],
					'telefono_contacto' => $param['sucursalTelefono'],
					'nombre_contacto' => $param['sucursalContacto'],
					'horarios_atencion' => '-',
					'observaciones' => '-',
					'company_id' => $param['companyId'],
				];
				Log::info($arraySucursalOnsite);
				$sucursalOnsite = $this->sucursalesOnsite->store($arraySucursalOnsite);

				if ($sucursalOnsite) {
					$this->sucursalesCreadas++;
				}
			}
		}

		return $sucursalOnsite;
	}

	private function csvToArray($filename = '', $delimiter = ';')
	{
		ini_set("auto_detect_line_endings", false);

		if (!file_exists($filename) || !is_readable($filename))
			return false;

		$header = null;
		$data = array();
		if (($handle = fopen($filename, 'r')) !== false) {
			while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
				if (!$header) {
					$header = $row;
				} else {
					$data[] = array_combine($header, $row);
				}
			}
			fclose($handle);
		}

		return $data;
	}
}
