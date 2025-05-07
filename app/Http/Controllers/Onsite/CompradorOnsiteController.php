<?php

namespace App\Http\Controllers\Onsite;

use App\Http\Controllers\Controller;
use App\Models\Onsite\SistemaOnsite;
use App\Services\Onsite\CompradoresOnsiteService;
use App\Services\Onsite\SistemaOnsiteService;
use Illuminate\Http\Request;
use Session;

class CompradorOnsiteController extends Controller
{
	protected $compradorOnsiteService;
	protected $sistemaOnsiteService;

	public function __construct(CompradoresOnsiteService $compradorOnsiteService,
	SistemaOnsiteService $sistemaOnsiteService)
	{				
		$this->compradorOnsiteService = $compradorOnsiteService;
		$this->sistemaOnsiteService = $sistemaOnsiteService;

	}

	public function storeComprador(Request $request)
	{
		$idSistema = $request['sistema_onsite_id'];
		$request['nombre'] = $request['primer_nombre'] . ', ' . $request['apellido'];
		$compradorOnsite = $this->compradorOnsiteService->store($request);

		$sistema_onsite = $this->sistemaOnsiteService->findSistemaOnsite($idSistema);
		$sistema_onsite->comprador_onsite_id = $compradorOnsite->id;
		$sistema_onsite->save();

		return response()->json($compradorOnsite);
	}


	public function getCompradorPorSistema($idSistema)
	{
		$comprador_onsite = $this->compradorOnsiteService->getCompradorPorSistema($idSistema);

		return response()->json($comprador_onsite);
	}
	

	public function updateCompradorPorId(Request $request, $idComprador)
	{
		$comprador_onsite = $this->compradorOnsiteService->updateCompradorPorId($request, $idComprador);

		return response()->json($comprador_onsite);
	}	


	

	
}
