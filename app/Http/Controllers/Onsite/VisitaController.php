<?php

namespace App\Http\Controllers\Onsite;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Onsite\ObrasOnsiteService;
use App\Services\Onsite\SolicitudOnsiteService;
use App\Services\Onsite\VisitasService;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReparacionExport;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class VisitaController extends Controller
{
	protected $visitasService;
	protected $obraOnsiteService;
	protected $solicitudOnsiteService;

	public function __construct(
		VisitasService $visitasService,
		ObrasOnsiteService $obraOnsiteService,
		SolicitudOnsiteService $solicitudOnsiteService
	) {
		$this->visitasService = $visitasService;
		$this->obraOnsiteService = $obraOnsiteService;
		$this->solicitudOnsiteService = $solicitudOnsiteService;
	}


	public function index()
	{
		$datos = $this->visitasService->getDataIndexVisitas();

		return view('_onsite.visita.visitasOnsite', $datos);
	}

	public function filtrarVisitas(Request $request)
	{

		$datos = $this->visitasService->filtrarVisitas($request);

		return view('_onsite.visita.visitasOnsite', $datos);
	}



	public function create()
	{

		$data  =  $this->obraOnsiteService->dataPuestaMarcha();

		if (Session()->get('perfilAdmin') || Session()->get('perfilAdminOnsite')) {
			$data['obrasOnsite'] = [];
			return view('_onsite/visita.createVisita', $data);
		} else {
			if (is_null($data['obrasOnsite']))
				return redirect('/')->with('message-error', 'Usuario no tiene empresa instaladora asociada');

			else
				return view('_onsite/visita.createVisita', $data);
		}
	}

	public function store(Request $request)
	{
		$this->validate($request, [
			'sistema_onsite_id' => 'required',

		]);

		$reparacionOnsite = $this->visitasService->store($request);

		return response()->json($reparacionOnsite);
	}

	public function export_visitas()
	{
		$response = Excel::download((new ReparacionExport), 'visitas.xlsx', null, [\Maatwebsite\Excel\Excel::XLSX]);

		return $response;
	}

	public function show($reparacionOnsite)
	{
		//
	}

	public function edit($reparacionOnsite)
	{
		$datos = $this->visitasService->getDataEdit($reparacionOnsite);
		$sistemaOnsiteReparacion = $datos['sistemaOnsiteReparacion'];
		$userCompanyId = $datos['companyId'];

		$datos['solicitudes'] = (isset($sistemaOnsiteReparacion) ? $this->solicitudOnsiteService->getSolicitudesPorSistema($userCompanyId, $sistemaOnsiteReparacion->id) : null);


		if ($datos)
			return view('_onsite.visita.edit', $datos);

		else {
			Session::flash('message-error', 'Reparación no encontrada');
			return redirect('/visitasOnsite');
		}
	}

	public function update(Request $request, $idReparacionOnsite)
	{
		$request['id_terminal'] = 1;


		$request->validate([
			'sucursal_onsite_id' => 'required',
			'id_empresa_onsite' => 'required',
			'id_tipo_servicio' => 'required',
			'id_estado' => 'required',
			'id_terminal' => 'required',
			'fecha_coordinada' => 'nullable|date|after:fecha_ingreso',
			'fecha_cerrado' => 'nullable|date|after:fecha_coordinada',
			//'id_tecnico_asignado' => 'required'
		]);

		$reparacionOnsite = $this->visitasService->update($request, $idReparacionOnsite);

		$mjeUpdate = 'Visita: ' . $reparacionOnsite->id . ' - registro modificado correctamente!';

		if ($request['botonGuardarNotificarTecnico']) {
			$mje = $this->visitasService->reenviarMailTecnico($request, $idReparacionOnsite);
			return redirect("/visitasOnsite/$idReparacionOnsite/edit")->with('message', $mjeUpdate . ' - ' . $mje);
		}

		if ($request['botonGuardarNotificar']) {
			//$this->enviarMailResponsable($reparacionOnsite, $reparacionOnsite->id_empresa_onsite);
			$this->visitasService->enviarMailResponsableEmpresa($reparacionOnsite);
		}



		return redirect('/visitasOnsite/' . $reparacionOnsite->id . '/edit')->with('message', $mjeUpdate);
	}

	public function destroy($idReparacionOnsite)
	{
		$reparacionOnsite = $this->visitasService->destroy($idReparacionOnsite);

		$mjeDelete = 'VisitasOnsite: ' . $reparacionOnsite->id . ' - registro eliminado correctamente!';

		return redirect('/visitasOnsite')->with('message', $mjeDelete);
	}

	public function getVisitasPorTecnico()
	{
		$reparacion = $this->visitasService->getVisitasPorTecnico();

		return response()->json($reparacion);
	}

	public function comprobanteVisita($idReparacion)
	{

		$reparacion = $this->visitasService->findReparacion($idReparacion);
		$comprobanteVisita = $this->visitasService->generaComprobanteVisita($reparacion);



		$data = [
			'comprobante' => $comprobanteVisita
		];

		$html = $this->makeHtmlGarantiaPdf($data);

		$pdf = App::make('dompdf.wrapper');

		$pdf->loadHTML($html);


		return $pdf->stream();
	}

	public function getResultadosReparacionPorEmpresaInstaladora()
	{
		$reparacion = $this->visitasService->getResultadosReparacionPorEmpresaInstaladora();

		return response()->json($reparacion);
	}

	public function getResultadosReparacionPorTecnico()
	{
		$reparacion = $this->visitasService->getResultadosReparacionPorTecnico();

		return response()->json($reparacion);
	}

	public function makeHtmlGarantiaPdf($garantiaOnsite)
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

		$html .= $garantiaOnsite['comprobante'];


		return $html;
	}
}
