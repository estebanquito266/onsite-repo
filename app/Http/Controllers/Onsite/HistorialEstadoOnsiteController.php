<?php

namespace App\Http\Controllers\Onsite;

use Illuminate\Http\Request;


use App\Http\Controllers\Controller;

use App\Http\Requests\Onsite\HistorialEstadoOnsiteRequest;

use App\Models\Onsite\HistorialEstadoOnsite;
use Illuminate\Support\Facades\Auth;
use App\Models\Onsite\EstadoOnsite;
use App\Models\Onsite\HistoriaEstadosOnsiteVisiblePorUser;
use App\Repositories\Onsite\HistorialEstadoOnsiteRepository;
use App\Models\User;
use App\Services\Onsite\HistorialEstadosOnsiteService;
use Illuminate\Support\Facades\Session;

class HistorialEstadoOnsiteController extends Controller
{

	protected $historialEstadoService;
	protected $historial_estado_onsite_repository;

	public function __construct(
		HistorialEstadosOnsiteService $historialEstadoService,
		HistorialEstadoOnsiteRepository $historial_estado_onsite_repository
		)
	{
		$this->middleware('auth');
		$this->historial_estado_onsite_repository = $historial_estado_onsite_repository;
		$this->historialEstadoService = $historialEstadoService;
		//$this->middleware('permiso', ['only' => ['index', 'create', 'store', 'edit', 'update', 'destroy']]);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		
		$datos_historial = $this->historialEstadoService->index();		

		return view('_onsite.historialestadoonsite.index',$datos_historial);
	}

	public function indexAll()
	{
		$datos_historial = $this->historialEstadoService->indexAll();

		return view('_onsite.historialestadoonsite.index', $datos_historial);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$datos = $this->historialEstadoService->create();

		return view('_onsite.historialestadoonsite.create', $datos);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(HistorialEstadoOnsiteRequest $request)
	{
		$historialEstadoOnsite = $this->historialEstadoService->store($request);

		$mjeCreate = 'HistorialEstadoOnsite: ' . $historialEstadoOnsite->id . ' - registro creado correctamente!';

		if ($request['botonGuardar']) {
			return redirect('/historialEstadoOnsite/' . $historialEstadoOnsite->id . '/edit')->with('message', $mjeCreate);
		} else {
			return redirect('/historialEstadoOnsite')->with('message', $mjeCreate);
		}
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show(HistorialEstadoOnsite $historialEstadoOnsite)
	{
		$datos_historial = $this->historialEstadoService->show($historialEstadoOnsite);

		return view('_onsite.historialestadoonsite.show', $datos_historial);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit(HistorialEstadoOnsite $historialEstadoOnsite)
	{
		$datos_historial = $this->historialEstadoService->show($historialEstadoOnsite);

		return view('_onsite.historialestadoonsite.edit',$datos_historial);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(HistorialEstadoOnsiteRequest $request, $id)
	{
		$historialEstadoOnsite = $this->historialEstadoService->update($request,$id);

		$mjeUpdate = 'HistorialEstadoOnsite: ' . $historialEstadoOnsite->id . ' - registro modificado correctamente!';

		if ($request['botonGuardar']) {
			return redirect('/historialEstadoOnsite/' . $historialEstadoOnsite->id . '/edit')->with('message', $mjeUpdate);
		} else {
			return redirect('/historialEstadoOnsite')->with('message', $mjeUpdate);
		}
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		$historialEstadoOnsite = $this->historialEstadoService->destroy($id);

		$mjeDelete = 'HistorialEstadoOnsite: ' . $historialEstadoOnsite->id . ' - registro eliminado correctamente!';

		return redirect('/historialEstadoOnsite')->with('message', $mjeDelete);
	}

	public function filtrarHistorialEstadoOnsite(Request $request)
	{
		$datos = $this->historialEstadoService->filtrar($request);


		return view('_onsite.historialestadoonsite.index', $datos);
	}

	public function getHistorialEstadosOnsite(Request $request, $idReparacion)
	{
		//$historialEstadosOnsite = $this->historialEstadoService->getHistorialEstadosOnsite($request,$idReparacion);
		$historialEstadosOnsite = $this->historialEstadoService->getHistorialEstadosNotas($idReparacion);

		return response()->json($historialEstadosOnsite);
	}

	public function ocultarHistorialEstadoOnsite(HistorialEstadoOnsite $historial_estado_onsite)
	{
		$this->historialEstadoService->ocultarHistorialEstadoOnsite($historial_estado_onsite);
	}
}
