<?php

namespace App\Http\Controllers\Onsite;

use App\Http\Controllers\Controller;
use App\Models\Onsite\SolicitudBoucher;
use App\Services\Onsite\MailOnsiteService;
use App\Services\Onsite\ObrasOnsiteService;
use App\Services\Onsite\ParametroService;
use App\Services\Onsite\SolicitudBoucherService;
use Illuminate\Http\Request;

use App\Services\Onsite\SolicitudOnsiteService;
use Auth;
use Illuminate\Support\Facades\Session as FacadesSession;
use Session;

class SolicitudOnsiteController extends Controller
{
	protected $solicitudOnsiteService;
	protected $parametrosService;
	protected $mailOnsiteService;
	protected $solicitudesBouchersService;
	protected $userCompanyId;
	protected $obraOnsiteService;

	public function __construct(
		SolicitudOnsiteService $solicitudOnsiteService,
		ParametroService $parametrosService,
		MailOnsiteService $mailOnsiteService,
		SolicitudBoucherService $solicitudesBouchersService,
		ObrasOnsiteService $obraOnsiteService
	) {
		$this->middleware('auth');
		$this->solicitudOnsiteService = $solicitudOnsiteService;
		$this->parametrosService = $parametrosService;
		$this->mailOnsiteService = $mailOnsiteService;
		$this->solicitudesBouchersService = $solicitudesBouchersService;
		$this->obraOnsiteService = $obraOnsiteService;
		$this->userCompanyId =  session()->get('userCompanyIdDefault');
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$datos = $this->solicitudOnsiteService->getDataIndex();

		return view('_onsite/solicitudonsite.index', $datos);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		/* $datos = $this->solicitudOnsiteService->getData();


		return view('_onsite/solicitudonsite.create', $datos); */



		$data =  $this->obraOnsiteService->dataPuestaMarcha();

		if (Session()->get('perfilAdmin') || Session()->get('perfilAdminOnsite')) {
		  $data['obrasOnsite'] = [];
		  return view('_onsite/solicitudonsite.create', $data);
		} else {
		  if (is_null($data['obrasOnsite']))
			return redirect('/')->with('message-error', 'Usuario no tiene empresa instaladora asociada');
	
		  else
			return view('_onsite/solicitudonsite.create', $data);
		}
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		$request->validate([
			'obra_onsite_id' => 'required',
		]);

		$solicitudOnsite = $this->solicitudOnsiteService->store($request);

		$mjeCreate = 'SolicitudOnsite: ' . $solicitudOnsite->id . ' - registro creado correctamente!';

		//if ($request['botonGuardar'])
		return redirect('/solicitudesOnsite/' . $solicitudOnsite->id . '/edit')->with('message', $mjeCreate);
		//else
		//return redirect('/solicitudesOnsite')->with('message', $mjeCreate);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		$datos = $this->solicitudOnsiteService->getDataShow($id);
		return view('_onsite/solicitudonsite.show', $datos);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		$datos = $this->solicitudOnsiteService->getDataEdit($id);

		return view('_onsite/solicitudonsite.edit', $datos);
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
		$request->validate([
			'obra_onsite_id' => 'required',
		]);

		$solicitudOnsiteUpdated = $this->solicitudOnsiteService->update($request, $id);

		$mjeUpdate = 'SolicitudOnsite: ' . $solicitudOnsiteUpdated->id . ' - registro modificado correctamente!';

		if ($solicitudOnsiteUpdated->estado_solicitud_onsite_id == 10) {
			return redirect()->route('solicitud.conversor', [
				'id' => $solicitudOnsiteUpdated->id
			]);
		}


		return redirect('/solicitudesOnsite/' . $solicitudOnsiteUpdated->id . '/edit')->with('message', $mjeUpdate);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		$this->solicitudOnsiteService->destroy($id);
		$mjeDelete = 'SolicitudOnsite: ' . $id . ' - registro eliminado correctamente!';

		return redirect('/solicitudesOnsite')->with('message', $mjeDelete);
	}

	public function filtrarSolicitudesOnsite(Request $request)
	{
		$datos = $this->solicitudOnsiteService->getFiltrarSolicitudesOnsite($request);
		return view('_onsite/solicitudonsite.index', $datos);
	}

	public function generarCsv($texto, $obraOnsiteId, $estadoSolicitudOnsiteId, $tipoSolicitudId)
	{
		$pendientes = true; 

		$this->solicitudOnsiteService->generarXlsx($texto, $obraOnsiteId, $estadoSolicitudOnsiteId, $tipoSolicitudId, $pendientes);
	}

	public function show_conversorReparacionOnsite($id)
	{
		$datos = $this->solicitudOnsiteService->getDataShowConversorReparacionOnsite($id);

		if ($datos)
			return view('_onsite.solicitudonsite.conversor', $datos);

		else
			return redirect('/solicitudesOnsite')->with('message-error', 'No existe Solicitud o no tiene una Obra Asociada');
	}

	public function procesarConversorVisita(Request $request, $id)
	{
		$data = $this->solicitudOnsiteService->procesarConversorVisita($request, $id);

		return view('_onsite.solicitudonsite.conversor_result', $data);
	}

	public function insertSolicitudPuestaMarcha(Request $request)
	{
		$request->validate([
			'nombre' => 'required',
			'empresa_instaladora_id' => 'required',
			'pais' => 'required',
			'localidad' => 'required',
			'provincia_onsite_id' => 'required',

			'domicilio' => 'required',
			'nro_cliente_bgh_ecosmart' => 'required',
			'cantidad_unidades_exteriores' => 'required',
			'cantidad_unidades_interiores' => 'required',
			'estado' => 'required',
			'estado_detalle' => 'required'
		]);

		$request['company_id'] = $this->userCompanyId;

		$request['estado_solicitud_onsite_id'] = 1; //nueva
		$request['terminos_condiciones'] = true;

		$request['requiere_zapatos_seguridad'] = filter_var($request['requiere_zapatos_seguridad'], FILTER_VALIDATE_BOOLEAN);
		$request['requiere_casco_seguridad'] = filter_var($request['requiere_casco_seguridad'], FILTER_VALIDATE_BOOLEAN);
		$request['requiere_proteccion_visual'] = filter_var($request['requiere_proteccion_visual'], FILTER_VALIDATE_BOOLEAN);
		$request['requiere_proteccion_auditiva'] = filter_var($request['requiere_proteccion_auditiva'], FILTER_VALIDATE_BOOLEAN);
		$request['requiere_art'] = filter_var($request['requiere_art'], FILTER_VALIDATE_BOOLEAN);
		$request['clausula_no_arrepentimiento'] = filter_var($request['clausula_no_arrepentimiento'], FILTER_VALIDATE_BOOLEAN);

		$solicitudOnsite = $this->solicitudOnsiteService->insertObraSolicitudOnsiteSpeedUp($request);

		if ($solicitudOnsite) {

			$mjeCreate = 'Solicitud creada Nº: ' . $solicitudOnsite->id . '. OBRA: ' . $solicitudOnsite->obra_onsite->nombre;

			return redirect('/SolicitudPuestaMarcha')->with('message', $mjeCreate);
		} else {
			return false;
		}
	}

	public function storeSolicitud(Request $request)
	{
		$this->validate($request, [
			'sistema_onsite_id' => 'required',

		]);



		$solicitudOnsite = $this->solicitudOnsiteService->store($request);

		/* consulto los boucher pendientes de imputar para actualizar con el numero de solicitud que se generó */
		$boucher = $this->solicitudesBouchersService->getSolicitudesBoucherPendienteImputacionPorSistema($solicitudOnsite->sistema_onsite_id);
		if ($boucher) {
			$boucher->solicitud_id = $solicitudOnsite->id;
			$boucher->pendiente_imputacion = false;
			$boucher->save();
		}

		/* estos son los mails nuevos, para usuario y administrador al registrar solicitudes con el nuevo formulario */

		$email_admin = $this->enviarMailSolicitudAdmin($solicitudOnsite, 'MAIL_ADMINISTRADOR_SOLICITUDES_TO', 'MAIL_ADMINISTRADOR_SOLICITUDES');

		$email_user = $this->enviarMailSolicitudUser($solicitudOnsite, 'MAIL_ADMINISTRADOR_SOLICITUDES');


		return response()->json($solicitudOnsite);
	}

	/**
	 * Envía mail notificando la solicitud
	 *
	 * @param Request $request
	 * @param [object] $solicitud
	 * @return void
	 */
	public function enviarMailSolicitudAdmin($solicitudOnsite, $mailTo, $plantillaMail)
	{

		$parametroMail = $this->parametrosService->findParametroPorNombre($mailTo);


		if (isset($parametroMail)) {
			$mailTo = $parametroMail->valor_cadena;
			$plantilla_mail_id = $this->parametrosService->findParametroPorNombre($plantillaMail);

			if ($solicitudOnsite && !is_null($mailTo)  && $plantilla_mail_id) {

				$plantilla_mail_id = $plantilla_mail_id->valor_numerico;

				if ($plantilla_mail_id > 0 && $mailTo !== null) {

					$email = $this->mailOnsiteService->enviarMailSolicitudes($solicitudOnsite, $plantilla_mail_id, $mailTo);
					if ($email)
						return 'Enviado correctamente';
				} else return 'No puede enviarse email';
			} else return 'NO puede enviarse email';
		}
	}

	public function enviarMailSolicitudUser($solicitudOnsite,  $plantillaMail)
	{
		$mailTo = Auth::user()->email;


		if (isset($mailTo) && $mailTo != null) {

			$plantilla_mail_id = $this->parametrosService->findParametroPorNombre($plantillaMail);

			if ($solicitudOnsite && $plantilla_mail_id) {

				$plantilla_mail_id = $plantilla_mail_id->valor_numerico;

				if ($plantilla_mail_id > 0) {

					$email = $this->mailOnsiteService->enviarMailSolicitudes($solicitudOnsite, $plantilla_mail_id, $mailTo);
					if ($email)
						return 'Enviado correctamente';
				} else return 'No puede enviarse email';
			} else return 'NO puede enviarse email';
		}
	}

	public function getSolicitudesPorSistema($idSistemas)
	{
		$data = $this->solicitudOnsiteService->getSolicitudesPorSistema($this->userCompanyId, $idSistemas);

		return response()->json($data);

	}	
}
