<?php

namespace App\Services\Onsite;

use App\Models\Localidad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\Onsite\EstadoOnsite;
use App\Models\Onsite\LocalidadOnsite;
use App\Models\Onsite\TipoServicioOnsite;
use App\Models\Onsite\SlaOnsite;
use App\Models\Onsite\SucursalOnsite;
use App\Models\Onsite\TerminalOnsite;
use App\Models\PlantillaMail;
use App\Models\TemplateComprobante;
use App\Services\Onsite\EmpresaOnsiteService;
use App\Services\Onsite\SucursalOnsiteService;
use App\Services\Onsite\TerminalOnsiteService;
use App\Services\Onsite\LocalidadOnsiteService;
use App\Services\Onsite\SolicitudOnsiteService;
use App\Services\Onsite\ReparacionOnsiteService;
use App\Services\Onsite\ObrasOnsiteService;
use Exception;
use Log;

class MailOnsiteService
{
	protected $mailCopia = null;
	protected $mailCopiaNombre = null;

	protected $mailReparaciones = null;
	protected $mailReparacionesNombre = null;

	protected $mailReparacionesOnsite = null;
	protected $mailReparacionesOnsiteNombre = null;

	protected $empresaOnsiteService;
	protected $sucursalOnsiteService;
	protected $terminalOnsiteService;
	protected $localidadOnsiteService;
	protected $obraOnsiteService;
	protected $plantillaMailService;
	protected $templateService;


	public function __construct(
		EmpresaOnsiteService  $empresaOnsiteService,
		SucursalOnsiteService  $sucursalOnsiteService,
		TerminalOnsiteService  $terminalOnsiteService,
		LocalidadOnsiteService  $localidadOnsiteService,
		ObrasOnsiteService  $obraOnsiteService,
		PlantillasMailsService $plantillaMailService,
		TemplatesService $templateService

	) {
		$this->mailCopia = env('MAIL_COPIA');
		$this->mailCopiaNombre = env('MAIL_COPIA_NOMBRE');

		$this->mailReparaciones = env('MAIL_REPARACIONES');
		$this->mailReparacionesNombre = env('MAIL_REPARACIONES_NOMBRE');

		$this->mailReparacionesOnsite = env('MAIL_REPARACIONES_ONSITE');
		$this->mailReparacionesOnsiteNombre = env('MAIL_REPARACIONES_ONSITE_NOMBRE');

		$this->empresaOnsiteService     = $empresaOnsiteService;
		$this->sucursalOnsiteService    = $sucursalOnsiteService;
		$this->terminalOnsiteService    = $terminalOnsiteService;
		$this->localidadOnsiteService   = $localidadOnsiteService;
		$this->obraOnsiteService  		= $obraOnsiteService;
		$this->plantillaMailService 	= $plantillaMailService;
		$this->templateService			= $templateService;
	}

	public function enviarMailTecnicoOnsiteAsignado($reparacionOnsite)
	{

		//$reparacionOnsite   = $this->reparacionOnsiteService->findReparacion($reparacionOnsite->id);
		$terminalOnsite     = TerminalOnsite::where('nro', $reparacionOnsite->id_terminal)->first();
		$tecnicoOnsite      = User::find($reparacionOnsite->id_tecnico_asignado);
		$tipoServicioOnsite = TipoServicioOnsite::find($reparacionOnsite->id_tipo_servicio);

		$sucursalOnsite     = SucursalOnsite::find($reparacionOnsite->sucursal_onsite_id);
		$localidadOnsite    = LocalidadOnsite::find($sucursalOnsite->localidad_onsite_id);
		$slaOnsite          = SlaOnsite::buscarSla($reparacionOnsite->id_tipo_servicio, $localidadOnsite->id_nivel);

		$emailFrom          = $this->mailReparacionesOnsite;
		$nombreFrom         = $this->mailReparacionesOnsiteNombre;

		$emailBcc           = $this->mailCopia;
		$nombreBcc          = $this->mailCopiaNombre;

		$asunto             = $reparacionOnsite->clave . ' – Nuevo servicio asignado';

		$emailTo            = $tecnicoOnsite->email;

		Mail::send(
			'emails.asignacion',
			[
				'reparacionOnsite' => $reparacionOnsite,
				'sucursalOnsite' => $sucursalOnsite, 'terminalOnsite' => $terminalOnsite,
				'tipoServicioOnsite' => $tipoServicioOnsite, 'slaOnsite' => $slaOnsite,
				'tecnicoOnsite' => $tecnicoOnsite,
			],
			function ($msj) use ($asunto, $emailTo, $emailFrom, $nombreFrom, $emailBcc, $nombreBcc) {
				$msj->from($emailFrom, $nombreFrom);
				$msj->to($emailTo);

				if ($emailBcc)
					$msj->bcc($emailBcc, $nombreBcc);

				$msj->subject($asunto);
			}
		);
	}

	public function enviarMailOnsite($reparacionOnsite, $plantillaMailId, $emailTo)
	{
		Log::info('MailOnsiteService - enviarMailOnsite');
		Log::info($plantillaMailId.' | '. $emailTo);
		$plantillaMail     = PlantillaMail::find($plantillaMailId);
		$asunto            = $this->replaceVariablesOnsite($reparacionOnsite, $plantillaMail->subject);
		$cuerpo            = $this->replaceVariablesOnsite($reparacionOnsite, $plantillaMail->body);
		$this->enviarMail($plantillaMail, $emailTo, $asunto, $cuerpo);
	}


	public function enviarMailSolicitudOnsite($data)
	{
		$sucursal_onsite        = $this->sucursalOnsiteService->findSucursal($data['sucursal_onsite_id']);
		$terminal_onsite        = $this->terminalOnsiteService->findTerminal($data['terminal_onsite_id']);
		$tipo_servicio_onsite   = tipoServicioOnsite::find($data['tipo_servicio_onsite_id']);

		$mail_params['nombre_sucursal'] = $sucursal_onsite->razon_social;
		$mail_params['nombre_terminal'] = $terminal_onsite->marca . ' - ' . $terminal_onsite->modelo;
		$mail_params['nombre_tipo_servicio'] = $tipo_servicio_onsite->nombre;
		$mail_params['detalle'] = $data->detalle;
		$mail_params['solicitante'] = $data->solicitante;
		$mail_params['documento'] = $data->documento;
		$mail_params['telefono'] = $data->telefono;
		$mail_params['urgencia'] = $data->urgencia;

		$mail_params   = $mail_params;
		$asunto        = 'Nuevsolicitud de servicio';
		$nombreFrom    = 'Fixup';
		$emailTo       = env('CORREO_ONSITE_SERVICIOS_TO');
		$emailFrom     = env('CORREO_ONSITE_SERVICIOS_FROM');

		Mail::send('emails.solicitud_onsite', $mail_params, function ($msj)
		use ($asunto, $emailTo, $emailFrom, $nombreFrom) {
			$msj->from($emailFrom, $nombreFrom);
			$msj->to($emailTo);

			$msj->subject($asunto);
		});
	}

	public function enviarMailSolicitudObraOnsite($solicitudOnsite, $plantillaMailId, $emailTo)
	{

		$plantillaMail     = $this->plantillaMailService->findPlantillaMail($plantillaMailId);

		if ($plantillaMail) {
			$asunto            = $this->replaceVariablesSolicitudOnsite($solicitudOnsite, $plantillaMail->subject);
			$cuerpo            = $this->replaceVariablesSolicitudOnsite($solicitudOnsite, $plantillaMail->body);

			$this->enviarMail($plantillaMail, $emailTo, $asunto, $cuerpo);
		}
	}

	private function enviarMail($plantillaMail, $emailTo, $asunto, $cuerpo)
	{
		Log::info('MailOnsiteService - enviarMail');
		Log::info($plantillaMail->id . ' | ' . $emailTo);

		$emailFixup    = $plantillaMail->from;
		$nombreFixup   = $plantillaMail->from_nombre;
		$emailCC       = $plantillaMail->cc;
		$nombreCC      = $plantillaMail->cc_nombre;
		$cuerpo .= '</body></html>';

		/* se agregan 5 segundos de delay para evitar que el servidor de correo rechace 
		múltiples envios */
		sleep(5);

		try {
			Mail::send(
				'emails.plantilla',
				['cuerpo' => $cuerpo,],
				function ($msj) use (
					$emailFixup,
					$nombreFixup,
					$emailTo,
					$asunto,
					$emailCC,
					$nombreCC
				) {
					$msj->from($emailFixup, $nombreFixup);
					$msj->to($emailTo);

					if ($emailCC)
						$msj->bcc($emailCC, $nombreCC);

					$msj->subject($asunto);
				}
			);

			$envio_email = true;
			Log::alert($envio_email);
		} catch (Exception $e) {
			Log::alert('No se pudo enviar el mail onsite. ERROR: ' . $e->getMessage());
			Log::info($e->getFile() . '(' . $e->getLine() . ')');

			$envio_email = 'No se pudo enviar el mail onsite. ERROR: ' . $e->getMessage();
			Log::alert($envio_email);
		}

		return $envio_email;
	}
	/*******/
	private function replaceVariablesOnsite($reparacionOnsite, $textoBase)
	{
		//$reparacionOnsite = $this->reparacionOnsiteService->findReparacion($reparacionOnsiteId);

		$textoBase = $this->replaceVariablesReparacionOnsite($reparacionOnsite, $textoBase);
		$textoBase = $this->replaceVariablesEmpresaOnsite($reparacionOnsite->id_empresa_onsite, $textoBase);
		$textoBase = $this->replaceVariablesEstadoOnsite($reparacionOnsite->id_estado, $textoBase);
		$textoBase = $this->replaceVariablesSucursalOnsite($reparacionOnsite->sucursal_onsite_id, $textoBase);
		$textoBase = $this->replaceVariablesTerminalOnsite($reparacionOnsite->terminal_onsite, $textoBase);
		$textoBase = $this->replaceVariablesTipoServicioOnsite($reparacionOnsite->id_tipo_servicio, $textoBase);
		$textoBase = $this->replaceVariablesTecnicoOnsite($reparacionOnsite->id_tecnico_asignado, $textoBase);

		return $textoBase;
	}



	private function replaceVariablesReparacionOnsite($reparacionOnsite, $textoBase)
	{
		//$reparacionOnsite = $this->reparacionOnsiteService->findReparacion($reparacionOnsiteId);

		$textoBase = str_replace("%REPARACION_ONSITE_ID%", $reparacionOnsite->id, $textoBase);
		$textoBase = str_replace("%REPARACION_ONSITE_COMPANY_ID%", $reparacionOnsite->company_id, $textoBase);
		$textoBase = str_replace("%REPARACION_ONSITE_CLAVE%", $reparacionOnsite->clave, $textoBase);
		$textoBase = str_replace("%REPARACION_ONSITE_ID_EMPRESA_ONSITE%", $reparacionOnsite->id_empresa_onsite, $textoBase);
		$textoBase = str_replace("%REPARACION_ONSITE_SUCURSAL_ONSITE_ID%", $reparacionOnsite->sucursal_onsite_id, $textoBase);
		$textoBase = str_replace("%REPARACION_ONSITE_ID_TERMINAL%", $reparacionOnsite->id_terminal, $textoBase);
		$textoBase = str_replace("%REPARACION_ONSITE_TAREA%", $reparacionOnsite->tarea, $textoBase);
		$textoBase = str_replace("%REPARACION_ONSITE_TAREA_DETALLE%", $reparacionOnsite->tarea_detalle, $textoBase);
		$textoBase = str_replace("%REPARACION_ONSITE_ID_TIPO_SERVICIO%", $reparacionOnsite->id_tipo_servicio, $textoBase);
		$textoBase = str_replace("%REPARACION_ONSITE_ID_ESTADO%", $reparacionOnsite->id_estado, $textoBase);
		$textoBase = str_replace("%REPARACION_ONSITE_FECHA_INGRESO%", $reparacionOnsite->fecha_ingreso, $textoBase);
		$textoBase = str_replace("%REPARACION_ONSITE_OBSERVACION_UBICACION%", $reparacionOnsite->observacion_ubicacion, $textoBase);
		$textoBase = str_replace("%REPARACION_ONSITE_PRIORIDAD%", $reparacionOnsite->prioridad, $textoBase);
		$textoBase = str_replace("%REPARACION_ONSITE_NRO_CAJA%", $reparacionOnsite->nro_caja, $textoBase);
		$textoBase = str_replace("%REPARACION_ONSITE_INFORME_TECNICO%", $reparacionOnsite->informe_tecnico, $textoBase);
		$textoBase = str_replace("%REPARACION_ONSITE_FIRMA_CLIENTE%", $reparacionOnsite->firma_cliente, $textoBase);
		$textoBase = str_replace("%REPARACION_ONSITE_ACLARACION_CLIENTE%", $reparacionOnsite->aclaracion_cliente, $textoBase);
		$textoBase = str_replace("%REPARACION_ONSITE_FIRMA_TECNICO%", $reparacionOnsite->firma_tecnico, $textoBase);
		$textoBase = str_replace("%REPARACION_ONSITE_ACLARACION_TECNICO%", $reparacionOnsite->aclaracion_tecnico, $textoBase);
		$textoBase = str_replace("%REPARACION_ONSITE_ID_TECNICO_ASIGNADO%", $reparacionOnsite->id_tecnico_asignado, $textoBase);
		$textoBase = str_replace("%REPARACION_ONSITE_FECHA_COORDINADA%", $reparacionOnsite->fecha_coordinada, $textoBase);
		$textoBase = str_replace("%REPARACION_ONSITE_VENTANA_HORARIA_COORDINADA%", $reparacionOnsite->ventana_horaria_coordinada, $textoBase);
		$textoBase = str_replace("%REPARACION_ONSITE_FECHA_REGISTRACION_COORDINACION%", $reparacionOnsite->fecha_registracion_coordinacion, $textoBase);
		$textoBase = str_replace("%REPARACION_ONSITE_FECHA_NOTIFICADO%", $reparacionOnsite->fecha_notificado, $textoBase);
		$textoBase = str_replace("%REPARACION_ONSITE_FECHA_VENCIMIENTO%", $reparacionOnsite->fecha_vencimiento, $textoBase);
		$textoBase = str_replace("%REPARACION_ONSITE_FECHA_CERRADO%", $reparacionOnsite->fecha_cerrado, $textoBase);
		$textoBase = str_replace("%REPARACION_ONSITE_SLA_STATUS%", $reparacionOnsite->sla_status, $textoBase);
		$textoBase = str_replace("%REPARACION_ONSITE_SLA_JUSTIFICADO%", $reparacionOnsite->sla_justificado, $textoBase);
		$textoBase = str_replace("%REPARACION_ONSITE_MONTO%", $reparacionOnsite->monto, $textoBase);
		$textoBase = str_replace("%REPARACION_ONSITE_MONTO_EXTRA%", $reparacionOnsite->monto_extra, $textoBase);
		$textoBase = str_replace("%REPARACION_ONSITE_LIQUIDADO_PROVEEDOR%", $reparacionOnsite->liquidado_proveedor, $textoBase);
		$textoBase = str_replace("%REPARACION_ONSITE_NRO_FACTURA_PROVEEDOR%", $reparacionOnsite->nro_factura_proveedor, $textoBase);
		$textoBase = str_replace("%REPARACION_ONSITE_VISIBLE_CLIENTE%", $reparacionOnsite->visible_cliente, $textoBase);
		$textoBase = str_replace("%REPARACION_ONSITE_CHEQUEADO_CLIENTE%", $reparacionOnsite->chequeado_cliente, $textoBase);
		$textoBase = str_replace("%REPARACION_ONSITE_DOC_LINK1%", $reparacionOnsite->doc_link1, $textoBase);
		$textoBase = str_replace("%REPARACION_ONSITE_DOC_LINK2%", $reparacionOnsite->doc_link2, $textoBase);
		$textoBase = str_replace("%REPARACION_ONSITE_DOC_LINK3%", $reparacionOnsite->doc_link3, $textoBase);
		$textoBase = str_replace("%REPARACION_ONSITE_PROBLEMA_RESUELTO%", $reparacionOnsite->problema_resuelto, $textoBase);
		$textoBase = str_replace("%REPARACION_ONSITE_USUARIO_ID%", $reparacionOnsite->usuario_id, $textoBase);

		return $textoBase;
	}

	private function replaceVariablesEmpresaOnsite($empresaOnsiteId, $textoBase)
	{
		$empresaOnsite = $this->empresaOnsiteService->findEmpresaOnsite($empresaOnsiteId);

		$textoBase = str_replace("%EMPRESA_ONSITE_ID%", $empresaOnsiteId, $textoBase);
		$textoBase = str_replace("%EMPRESA_ONSITE_COMPANY_ID%", $empresaOnsite->company_id, $textoBase);
		$textoBase = str_replace("%EMPRESA_ONSITE_NOMBRE%", $empresaOnsite->nombre, $textoBase);
		$textoBase = str_replace("%EMPRESA_ONSITE_REQUIERE_TIPO_CONEXION_LOCAL%", $empresaOnsite->requiere_tipo_conexion_local, $textoBase);
		$textoBase = str_replace("%EMPRESA_ONSITE_GENERAR_CLAVE_REPARACION%", $empresaOnsite->clave_reparacion, $textoBase);

		return $textoBase;
	}

	private function replaceVariablesEstadoOnsite($estadoOnsiteId, $textoBase)
	{
		$estadoOnsite = EstadoOnsite::find($estadoOnsiteId);

		$textoBase = str_replace("%ESTADO_ONSITE_ID%", $estadoOnsiteId, $textoBase);
		$textoBase = str_replace("%ESTADO_ONSITE_COMPANY_ID%", $estadoOnsite->company_id, $textoBase);
		$textoBase = str_replace("%ESTADO_ONSITE_NOMBRE%", $estadoOnsite->nombre, $textoBase);
		$textoBase = str_replace("%ESTADO_ONSITE_ACTIVO%", $estadoOnsite->activo, $textoBase);
		$textoBase = str_replace("%ESTADO_ONSITE_CARD_TITULO%", $estadoOnsite->card_titulo, $textoBase);
		$textoBase = str_replace("%ESTADO_ONSITE_CARD_INTRO%", $estadoOnsite->card_intro, $textoBase);
		$textoBase = str_replace("%ESTADO_ONSITE_CARD_ICONO%", $estadoOnsite->card_icono, $textoBase);
		$textoBase = str_replace("%ESTADO_ONSITE_CERRADO%", $estadoOnsite->card_cerrado, $textoBase);
		$textoBase = str_replace("%ESTADO_ONSITE_TIPO_ESTADO_ONSITE_ID%", $estadoOnsite->tipo_estado_onsite_id, $textoBase);

		return $textoBase;
	}

	private function replaceVariablesSucursalOnsite($sucursalOnsiteId, $textoBase)
	{
		$sucursalOnsite = $this->sucursalOnsiteService->findSucursal($sucursalOnsiteId);

		$textoBase = str_replace("%SUCURSAL_ONSITE_ID%", $sucursalOnsiteId, $textoBase);
		$textoBase = str_replace("%SUCURSAL_ONSITE_COMPANY_ID%", $sucursalOnsite->company_id, $textoBase);
		$textoBase = str_replace("%SUCURSAL_ONSITE_CODIGO_SUCURSAL%", $sucursalOnsite->codigo_sucursal, $textoBase);
		$textoBase = str_replace("%SUCURSAL_ONSITE_RAZON_SOCIAL%", $sucursalOnsite->razon_social, $textoBase);
		$textoBase = str_replace("%SUCURSAL_ONSITE_DIRECCION%", $sucursalOnsite->direccion, $textoBase);
		$textoBase = str_replace("%SUCURSAL_ONSITE_TELEFONO_CONTACTO%", $sucursalOnsite->telefono_contacto, $textoBase);
		$textoBase = str_replace("%SUCURSAL_ONSITE_NOMBRE_CONTACTO%", $sucursalOnsite->nombre_contacto, $textoBase);
		$textoBase = str_replace("%SUCURSAL_ONSITE_HORARIOS_ATENCION%", $sucursalOnsite->horarios_atencion, $textoBase);
		$textoBase = str_replace("%SUCURSAL_ONSITE_OBSERVACIONES%", $sucursalOnsite->observaciones, $textoBase);
		$textoBase = str_replace("%SUCURSAL_ONSITE_EMPRESA_ONSITE_ID%", $sucursalOnsite->empresa_onsite_id, $textoBase);
		$textoBase = str_replace("%SUCURSAL_ONSITE_LOCALIDAD_ONSITE_ID%", $sucursalOnsite->localidad_onsite_id, $textoBase);

		return $textoBase;
	}

	private function replaceVariablesTerminalOnsite($terminalOnsite, $textoBase)
	{
		//$terminalOnsite = $this->terminalOnsiteService->findTerminal($terminalOnsiteId);
		$textoBase = str_replace("%TERMINAL_ONSITE_ID%", $terminalOnsite->id, $textoBase);
		$textoBase = str_replace("%TERMINAL_ONSITE_COMPANY_ID%", $terminalOnsite->company_id, $textoBase);
		$textoBase = str_replace("%TERMINAL_ONSITE_MARCA%", $terminalOnsite->marca, $textoBase);
		$textoBase = str_replace("%TERMINAL_ONSITE_MODELO%", $terminalOnsite->modelo, $textoBase);
		$textoBase = str_replace("%TERMINAL_ONSITE_SERIE%", $terminalOnsite->serie, $textoBase);
		$textoBase = str_replace("%TERMINAL_ONSITE_ROTULO%", $terminalOnsite->rotulo, $textoBase);
		$textoBase = str_replace("%TERMINAL_ONSITE_OBSERVACIONES%", $terminalOnsite->observaciones, $textoBase);
		$textoBase = str_replace("%TERMINAL_ONSITE_SUCURSAL_ONSITE_ID%", $terminalOnsite->sucursal_onsite_id, $textoBase);
		$textoBase = str_replace("%TERMINAL_ONSITE_EMPRESA_ONSITE_ID%", $terminalOnsite->empresa_onsite_id, $textoBase);
		$textoBase = str_replace("%TERMINAL_ONSITE_ALL_TERMINALES_SUCURSAL%", $terminalOnsite->all_terminales_sucursal, $textoBase);

		return $textoBase;
	}

	private function replaceVariablesTipoServicioOnsite($tipoServicioOnsiteId, $textoBase)
	{
		$tipoServicioOnsite = TipoServicioOnsite::find($tipoServicioOnsiteId);

		$textoBase = str_replace("%TIPO_SERVICIO_ONSITE_ID%", $tipoServicioOnsiteId, $textoBase);
		$textoBase = str_replace("%TIPO_SERVICIO_COMPANY_ID%", $tipoServicioOnsite->company_id, $textoBase);
		$textoBase = str_replace("%TIPO_SERVICIO_NOMBRE%", $tipoServicioOnsite->nombre, $textoBase);

		return $textoBase;
	}

	private function replaceVariablesTecnicoOnsite($tecnicoOnsiteId, $textoBase)
	{
		$tecnicoOnsite = User::find($tecnicoOnsiteId);

		if ($tecnicoOnsite) {
			$textoBase = str_replace("%TECNICO_ONSITE_ID%", $tecnicoOnsiteId, $textoBase);
			$textoBase = str_replace("%TECNICO_ONSITE_NAME%", $tecnicoOnsite->name, $textoBase);
			$textoBase = str_replace("%TECNICO_ONSITE_EMAIL%", $tecnicoOnsite->email, $textoBase);
			$textoBase = str_replace("%TECNICO_ONSITE_DOMICILIO%", $tecnicoOnsite->domicilio, $textoBase);
			$textoBase = str_replace("%TECNICO_ONSITE_CUIT%", $tecnicoOnsite->cuit, $textoBase);
			$textoBase = str_replace("%TECNICO_ONSITE_TELEFONO%", $tecnicoOnsite->telefono, $textoBase);
			$textoBase = str_replace("%TECNICO_ONSITE_ID_TIPO_VISIBILIDAD%", $tecnicoOnsite->id_tipo_visibilidad, $textoBase);
			$textoBase = str_replace("%TECNICO_ONSITE_FOTO_PERFIL%", $tecnicoOnsite->foto_perfil, $textoBase);
		}

		return $textoBase;
	}
	private function replaceVariablesSolicitudOnsite($solicitudOnsite, $textoBase)
	{
		//$solicitudOnsite = $this->solicitudOnsiteService->findSolicitud($solicitudOnsiteId);

		$textoBase = $this->replaceVariablesSolicitudOnsiteTable($solicitudOnsite, $textoBase);
		$textoBase = $this->replaceVariablesObraOnsite($solicitudOnsite, $textoBase);

		return $textoBase;
	}

	private function replaceVariablesSolicitudOnsiteTable($solicitudOnsite, $textoBase)
	{

		$textoBase = str_replace("%SOLICITUD_ONSITE_ID%", $solicitudOnsite->id, $textoBase);

		return $textoBase;
	}

	private function replaceVariablesObraOnsite($solicitudOnsite, $textoBase)
	{
		//$obraOnsite = $this->obraOnsiteService->findObraOnsite($obraOnsiteId);
		$obraOnsite = $solicitudOnsite->obra_onsite;

		$textoBase = str_replace("%OBRA_ONSITE_ID%", $obraOnsite->id, $textoBase);
		$textoBase = str_replace("%OBRA_ONSITE_COMPANY_ID%", $obraOnsite->company_id, $textoBase);
		$textoBase = str_replace("%OBRA_ONSITE_NOMBRE%", $obraOnsite->nombre, $textoBase);
		$textoBase = str_replace("%OBRA_ONSITE_CANTIDAD_UNIDADES_EXTERIORES%", $obraOnsite->cantidad_unidades_exteriores, $textoBase);
		$textoBase = str_replace("%OBRA_ONSITE_CANTIDAD_UNIDADES_INTERIORES%", $obraOnsite->cantidad_unidades_interiores, $textoBase);
		$textoBase = str_replace("%OBRA_ONSITE_EMPRESA_INSTALADORA_NOMBRE%", $obraOnsite->empresa_instaladora_nombre, $textoBase);
		$textoBase = str_replace("%OBRA_ONSITE_EMPRESA_INSTALADORA_RESPONSABLE%", $obraOnsite->empresa_instaladora_responsable, $textoBase);
		$textoBase = str_replace("%OBRA_ONSITE_RESPONSABLE_EMAIL%", $obraOnsite->responsable_email, $textoBase);
		$textoBase = str_replace("%OBRA_ONSITE_RESPONSABLE_TELEFONO%", $obraOnsite->responsable_telefono, $textoBase);
		$textoBase = str_replace("%OBRA_ONSITE_DOMICILIO%", $obraOnsite->domicilio, $textoBase);
		$textoBase = str_replace("%OBRA_ONSITE_ESTADO%", $obraOnsite->estado, $textoBase);
		$textoBase = str_replace("%OBRA_ONSITE_ESTADO_DETALLE%", $obraOnsite->estado_detalle, $textoBase);
		$textoBase = str_replace("%OBRA_ONSITE_ESQUEMA%", $obraOnsite->esquema, $textoBase);

		return $textoBase;
	}

	/* email repuestos onsite */

	public function enviarMailRepuestosOnsite($ordenPedidoRespuesto, $plantillaMailId, $emailTo)
	{
		$plantillaMail     = PlantillaMail::find($plantillaMailId);
		$asunto            = $this->replaceVariablesRepuestosOnsite($ordenPedidoRespuesto, $plantillaMail->subject);

		$cuerpo = $this->replaceVariablesRepuestosOnsite($ordenPedidoRespuesto, $plantillaMail->body);
		$email = $this->enviarMail($plantillaMail, $emailTo, $asunto, $cuerpo);

		return $email;
	}

	private function replaceVariablesRepuestosOnsite($ordenPedidoRespuesto, $textoBase)
	{
		$textoBase = $this->replaceVariablesOrdenRepuestosOnsite($ordenPedidoRespuesto, $textoBase);

		return $textoBase;
	}

	private function replaceVariablesOrdenRepuestosOnsite($ordenPedidoRespuesto, $textoBase)
	{
		$monto =
			'<p>Monto: '
			. 'USD: ' . $ordenPedidoRespuesto->monto_dolar . ' - '
			. 'AR$: ' . $ordenPedidoRespuesto->monto_peso . ' - '
			. 'EUR: ' . $ordenPedidoRespuesto->monto_euro;

		$anio = date('Y');
		$piezas = '';

		foreach ($ordenPedidoRespuesto->detalle_pedido as $detalle) {
			if ($detalle->pieza->moneda == 'dolar') $simbolo = 'USD ';
			if ($detalle->pieza->moneda == 'euro') $simbolo = 'EUR ';
			if ($detalle->pieza->moneda == 'peso') $simbolo = 'AR$ ';

			$piezas .=
				'<tr>'
				. '<td>' . $detalle->pieza->spare_parts_code . '</td>'
				. '<td>' . $detalle->pieza->part_name . '</td>'
				. '<td>' . $detalle->cantidad . '</td>'
				. '<td>' . $simbolo . $detalle->precio_fob . '</td>'
				. '<td>' . $simbolo . $detalle->precio_total . '</td>'
				. '<td>'	. $simbolo . $detalle->precio_neto . '</td>'
				. '</tr>';
		}

		$datos_solicitante = '<tr>'
			/* . '<td>' . $ordenPedidoRespuesto->user->name . '</td>'
		. '<td>' . $ordenPedidoRespuesto->user->email . '</td>' */
			. '<td>' . $ordenPedidoRespuesto->nombre_solicitante . '</td>'
			. '<td>' . $ordenPedidoRespuesto->email_solicitante . '</td>'
			. '<td>' . $ordenPedidoRespuesto->user->telefono . '</td>'
			. '<td>' . $ordenPedidoRespuesto->user->domicilio . '</td>'
			. '<td>' . $ordenPedidoRespuesto->user->id . '</td>'
			. '</tr>';;

		$textoBase = str_replace("%ID_ORDEN_PEDIDO%", $ordenPedidoRespuesto->id, $textoBase);
		$textoBase = str_replace("%NOMBRE_SOLICITANTE%", $ordenPedidoRespuesto->nombre_solicitante, $textoBase);
		$textoBase = str_replace("%FECHA_PEDIDO%", date('d-m-Y - H:i ', strtotime($ordenPedidoRespuesto->created_at)) . 'Hs.', $textoBase);

		$textoBase = str_replace("%MONTO_ORDEN_PEDIDO%", $monto, $textoBase);
		$textoBase = str_replace("%PIEZAS_ORDEN_PEDIDO%", $piezas, $textoBase);
		$textoBase = str_replace("%DATOS_SOLICITANTE%", $datos_solicitante, $textoBase);

		$textoBase = str_replace("%ANIO_DERECHOS%", $anio, $textoBase);
		$textoBase = str_replace("%ESTADO_PEDIDO%", $ordenPedidoRespuesto->estado->nombre, $textoBase);
		$textoBase = str_replace("%TERMINOS_CONDICIONES%", $this->templateService->getTemplate(TemplateComprobante::DISCLAIMER_REPUESTOS)->cuerpo, $textoBase);

		return $textoBase;
	}

	/* email reparaciones (notificación) onsite */

	public function enviarMailReparaciones($reparacion, $plantillaMailId, $emailTo)
	{

		$plantillaMail     = PlantillaMail::find($plantillaMailId);
		$asunto            = $this->replaceVariablesReparaciones($reparacion, $plantillaMail->subject);

		$cuerpo 			= $this->replaceVariablesReparaciones($reparacion, $plantillaMail->body);
		$email 				= $this->enviarMail($plantillaMail, $emailTo, $asunto, $cuerpo);

		return $email;
	}

	private function replaceVariablesReparaciones($reparacion, $textoBase)
	{
		$textoBase = $this->replaceVariablesOrdenReparaciones($reparacion, $textoBase);

		return $textoBase;
	}

	private function replaceVariablesOrdenReparaciones($reparacion, $textoBase)
	{

		$textoBase = str_replace("%ID_REPARACION%", $reparacion->id, $textoBase);
		$textoBase = str_replace("%FECHA_REPARACION%", $reparacion->created_at, $textoBase);
		$textoBase = str_replace("%NOMBRE_OBRA%", $reparacion->sistema_onsite->obra_onsite->nombre, $textoBase);

		$textoBase = str_replace("%NOMBRE_SISTEMA%", $reparacion->sistema_onsite->nombre, $textoBase);
		$textoBase = str_replace("%OBSERVACIONES_INTERNAS%", $reparacion->observaciones_internas, $textoBase);
		$textoBase = str_replace("%INFORME_TECNICO%", $reparacion->informe_tecnico, $textoBase);
		$textoBase = str_replace("%ANIO_DERECHOS%", date('Y'), $textoBase);


		return $textoBase;
	}




	/* email solicitudes  */

	public function enviarMailSolicitudes($solicitudOnsite, $plantillaMailId, $emailTo)
	{

		$plantillaMail     = PlantillaMail::find($plantillaMailId);
		$asunto            = $this->replaceVariablesSolicitudes($solicitudOnsite, $plantillaMail->subject);

		$cuerpo 			= $this->replaceVariablesSolicitudes($solicitudOnsite, $plantillaMail->body);
		$email 				= $this->enviarMail($plantillaMail, $emailTo, $asunto, $cuerpo);

		return $email;
	}

	private function replaceVariablesSolicitudes($solicitudOnsite, $textoBase)
	{
		$textoBase = $this->replaceVariablesOrdenSolicitudes($solicitudOnsite, $textoBase);

		return $textoBase;
	}

	private function replaceVariablesOrdenSolicitudes($solicitudOnsite, $textoBase)
	{

		$textoBase = str_replace("%ID_SOLICITUD%", $solicitudOnsite->id, $textoBase);
		$textoBase = str_replace("%FECHA%", $solicitudOnsite->created_at, $textoBase);
		$textoBase = str_replace("%EMPRESA_INSTALADORA%", $solicitudOnsite->obra_onsite->empresa_instaladora->nombre, $textoBase);

		$textoBase = str_replace("%OBRA_ONSITE%", $solicitudOnsite->obra_onsite->nombre, $textoBase);
		$textoBase = str_replace("%SISTEMA_ONSITE%", $solicitudOnsite->sistema_onsite->nombre, $textoBase);

		$textoBase = str_replace("%TIPO%", $solicitudOnsite->tipo->nombre, $textoBase);
		$textoBase = str_replace("%OBSERVACIONES_INTERNAS%", $solicitudOnsite->observaciones_internas, $textoBase);

		$textoBase = str_replace("%DISCLAIMER%", $this->templateService->getTemplate(TemplateComprobante::DISCLAIMER_SOLICITUDES)->body_text, $textoBase);

		$textoBase = str_replace("%ANIO_DERECHOS%", date('Y'), $textoBase);


		return $textoBase;
	}
}
