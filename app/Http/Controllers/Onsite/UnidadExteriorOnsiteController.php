<?php

namespace App\Http\Controllers\Onsite;

use App\Http\Controllers\Controller;
use App\Services\Onsite\ImagenUnidadOnsiteService;
use App\Services\Onsite\SistemaOnsiteService;
use App\Services\Onsite\UnidadExteriorOnsiteService;
use App\Services\Onsite\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class UnidadExteriorOnsiteController extends Controller
{
    protected $unidadExteriorService;
    protected $imagenUnidadOnsiteService;
    protected $sistemaService;
    protected $userService;

    public function __construct(
        UnidadExteriorOnsiteService $unidadExteriorService,
        ImagenUnidadOnsiteService $imagenUnidadOnsiteService,
        UserService $userService,
        SistemaOnsiteService $sistemaService
    ) {
        $this->middleware('auth');
        $this->unidadExteriorService = $unidadExteriorService;
        $this->imagenUnidadOnsiteService = $imagenUnidadOnsiteService;
        $this->userService = $userService;
        $this->sistemaService = $sistemaService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $datos = $this->unidadExteriorService->getDataIndex();
        //dd($datos);
        //$this->generarCsv($datos['userCompanyId'], null, null, null, $datos['userId']);
        return view('_onsite.unidadexterioronsite.index', $datos);
    }

	public function filtrarUnidadExterior(Request $request)
	{
		$datos = $this->unidadExteriorService->filtrar($request);

		return view('_onsite.unidadexterioronsite.index', $datos);
	}  


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $idUser = session()->get('idUser');
        $user = $this->userService->findUser($idUser);

        $datos['empresasOnsite'] = $this->unidadExteriorService->obtenerEmpresas();
        $datos['userCompanyId'] = Session::get('userCompanyIdDefault');
        $datos['user'] = $user;

        return view('_onsite.unidadexterioronsite.create', $datos);
    }

    public function createUnidadExterior($sistema_onsite_id)
    {
        $datos = $this->unidadExteriorService->createUnidadExterior($sistema_onsite_id);
        return view('_onsite.unidadexterioronsite.createUnidadExterior', $datos);
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
            'sistema_onsite_id'  => 'required',
            
        ]);

        $idSistema = $request->get('sistema_onsite_id');
        $sistemaOnsite = $this->sistemaService->sistemaById($idSistema);

        $request['empresa_onsite_id'] = $sistemaOnsite->empresa_onsite_id;
        $request['sucursal_onsite_id'] = $sistemaOnsite->sucursal_onsite_id;

        $request->validate([
            'empresa_onsite_id'  => 'required',
            'sucursal_onsite_id'  => 'required',
            'clave'  => 'required',
            'sistema_onsite_id'  => 'required',
            'medida_figura_1_a'  => 'required',
            'medida_figura_1_b'  => 'required',
            'medida_figura_1_c'  => 'required',
            'medida_figura_1_d'  => 'required',
            'medida_figura_2_a'  => 'required',
            'medida_figura_2_b'  => 'required',
            'medida_figura_2_c'  => 'required',
        ]);

        $unidadExteriorOnsite = $this->unidadExteriorService->store($request);

        $mjeCreate = 'Unidad Exterior Onsite: ' . $unidadExteriorOnsite->id . ' - registro creado correctamente!';

        if($request->ajax()){
            return response()->json($unidadExteriorOnsite);
        }

        if ($request['botonGuardarReturnSO']) {
            return redirect('/sistemaOnsite/' . $unidadExteriorOnsite->sistema_onsite_id . '/edit')->with('message', $mjeCreate);
        } else {
            return redirect('/unidadExterior')->with('message', $mjeCreate);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\UnidadExteriorOnsite  $unidadExteriorOnsite
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $datos = $this->unidadExteriorService->getDataItem($id);
        return view('_onsite.unidadexterioronsite.show', $datos);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\UnidadExteriorOnsite  $unidadExteriorOnsite
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $idUser = session()->get('idUser');
        $user = $this->userService->findUser($idUser);

        $datos = $this->unidadExteriorService->getDataItem($id);
        $datos['imagenes'] = $this->unidadExteriorService->getImagenes($id);
        $datos['empresasOnsite'] = $this->unidadExteriorService->obtenerEmpresas();
        $datos['user'] = $user;

        return view('_onsite.unidadexterioronsite.edit', $datos);
    }

    public function editUnidadExterior($id)
    {
        $datos = $this->unidadExteriorService->getDataItem($id);
        $datos['imagenes'] = $this->unidadExteriorService->getImagenes($id);
        return view('_onsite.unidadexterioronsite.editUnidadExterior', $datos);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\UnidadExteriorOnsite  $unidadExteriorOnsite
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $idSistema = $request->get('sistema_onsite_id');
        $sistemaOnsite = $this->sistemaService->sistemaById($idSistema);

        $request['empresa_onsite_id'] = $sistemaOnsite->empresa_onsite_id;
        $request['sucursal_onsite_id'] = $sistemaOnsite->sucursal_onsite_id;
        
        $request->validate([
            'empresa_onsite_id'  => 'required',
            'sucursal_onsite_id'  => 'required',
            'clave'  => 'required',
            'sistema_onsite_id'  => 'required',
            'medida_figura_1_a'  => 'required',
            'medida_figura_1_b'  => 'required',
            'medida_figura_1_c'  => 'required',
            'medida_figura_1_d'  => 'required',
            'medida_figura_2_a'  => 'required',
            'medida_figura_2_b'  => 'required',
            'medida_figura_2_c'  => 'required',
        ]);

        $this->unidadExteriorService->update($request, $id);

        $mjeUpdate = 'Unidad Exterior Onsite: ' . $id . ' - registro actualizado correctamente!';

        if ($request['botonGuardarReturnSO']) {
            return redirect('/sistemaOnsite/' . $request['sistema_onsite_id'] . '/edit')->with('message', $mjeUpdate);
        } else {
            return redirect('/unidadExterior')->with('message', $mjeUpdate);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UnidadExteriorOnsite  $unidadExteriorOnsite
     * @return \Illuminate\Http\Response
     */
    public function destroyImagenes($id)
    {
        $request['tipo_unidad'] = 'exterior';
        $imagenesExteriores = $this->imagenUnidadOnsiteService->getImagenUnidad($request['tipo_unidad'], $id);
        foreach ($imagenesExteriores as $imagen) {
            $this->imagenUnidadOnsiteService->destroy($request, $imagen->id);
        }
    }

    public function destroy($id)
    {
        $this->destroyImagenes($id);
        $this->unidadExteriorService->destroy($id);
        $mjeDelete = 'Unidad Exterior Onsite: ' . $id . ' - registro eliminado correctamente!';
        return redirect('/unidadExterior')->with('message', $mjeDelete);
    }

    public function insertUESistema($idSistema)
    {
        session()->put('idSistemaCrearUnidad', $idSistema);

        $idUser = session()->get('idUser');
        $user = $this->userService->findUser($idUser);

        $datos['empresasOnsite'] = $this->unidadExteriorService->obtenerEmpresas();
        $datos['userCompanyId'] = Session::get('userCompanyIdDefault');
        $datos['user'] = $user;

        return view('_onsite.unidadexterioronsite.create', $datos);

        
        
    }

    public function getUnidadesExterioresPorSistema($idSistema)
    {
        $unidadesExteriores = $this->unidadExteriorService->unidadesExteriorPorSistema($idSistema);

        return response()->json($unidadesExteriores);
    }

    
}
