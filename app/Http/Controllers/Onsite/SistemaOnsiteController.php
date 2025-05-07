<?php

namespace App\Http\Controllers\Onsite;

use App\Http\Controllers\Controller;
use App\Services\Onsite\ObrasOnsiteService;
use Illuminate\Http\Request;

use App\Services\Onsite\SistemaOnsiteService;

use Illuminate\Support\Facades\Redirect;

class SistemaOnsiteController extends Controller
{
    protected $sistemaOnsiteService;
    protected $obraOnsiteService;
    protected $userCompanyId;

    public function __construct(
        SistemaOnsiteService $sistemaOnsiteService,
        ObrasOnsiteService $obraOnsiteService
    ) {
        $this->sistemaOnsiteService = $sistemaOnsiteService;
        $this->obraOnsiteService = $obraOnsiteService;
        $this->userCompanyId =  session()->get('userCompanyIdDefault');
    }


    public function index()
    {
        $datos = $this->sistemaOnsiteService->getDataIndex();
        return view('_onsite.sistemaonsite.index', $datos);
    }

	public function filtrarSistemasOnsite(Request $request)
	{
		$datos = $this->sistemaOnsiteService->filtrar($request);

		return view('_onsite.sistemaonsite.index', $datos);
	}  

    public function create()
    {
        $datos = $this->sistemaOnsiteService->getDataCreate();

        return view('_onsite.sistemaonsite.create', $datos);
    }
    public function store(Request $request)
    {
        $request['company_id'] = $this->userCompanyId;

        $this->validate($request, [
            'empresa_onsite_id' => 'required',
            'sucursal_onsite_id' => 'required',
            'obra_onsite_id' => 'required',
            'nombre' => 'required'
        ]);

        $sistemaOnsite = $this->sistemaOnsiteService->store($request->all());

        $mjeCreate = 'sistemaOnsite: ' . $sistemaOnsite->id . ' - registro creado correctamente!';

        if ($request->ajax()) { //se agrega p/ Jquery Ajax de reparaciones
            return response()->json([
                "mensaje" => $mjeCreate,
                "id" => $sistemaOnsite->id,
                "nombre" => $sistemaOnsite->nombre,
            ]);
        }

        return Redirect::to('/sistemaOnsite');
    }

    public function edit($idSistema)
    {
        $datos = $this->sistemaOnsiteService->getDataEdit($idSistema);

        return view('_onsite.sistemaonsite.edit', $datos);
    }

    public function show($idSistema)
    {
        $datos = $this->sistemaOnsiteService->getDataShow($idSistema);

        return view('_onsite.sistemaonsite.show', $datos);
    }

    public function update(Request $request, $idSistema)
    {
        $this->sistemaOnsiteService->update($request, $idSistema);

        return Redirect::to('/sistemaOnsite');
    }

	public function destroy($id)
	{
		$success = $this->sistemaOnsiteService->destroy($id);

		return redirect('/sistemaOnsite')->with('message', ($success ? 'SistemaOnsite: ' . $id . ' - registro eliminado correctamente!' : null));
	}    

    public function buscarSistemasOnsite(Request $request, $sucursal_onsite_id)
    {
        if ($request->ajax()) {
            $sistemas = $this->sistemaOnsiteService->sistemasPorSucursal($sucursal_onsite_id);
            return response()->json($sistemas);
        }
    }

    public function buscarSistemasOnsiteA(Request $request, $sucursal_onsite_id)
    {
        if ($request->ajax()) {
            $sistemas      =       $this->sistemaOnsiteService->sistemasPorSucursal($sucursal_onsite_id);
            return response()->json($sistemas);
        }
    }

    public function getSistemasPorObra($idObra)
    {
        $sistemas = $this->sistemaOnsiteService->getSistemasPorObra($idObra);

        return response()->json($sistemas);
    }

    public function createSistema()
    {
        $data =  $this->obraOnsiteService->dataPuestaMarcha();

        if (Session()->get('perfilAdmin') || Session()->get('perfilAdminOnsite')) {
            $data['obrasOnsite'] = [];
            return view('_onsite/sistemaonsite.createSistema', $data);
        } else {
            if (is_null($data['obrasOnsite']))
                return redirect('/')->with('message-error', 'Usuario no tiene empresa instaladora asociada');

            else
                return view('_onsite/sistemaonsite.createSistema', $data);
        }
    }

    public function getSistemaPorId($idSistema)
    {
        $sistema = $this->sistemaOnsiteService->getSistemaPorId($idSistema);

        return response()->json($sistema);
    }
}
