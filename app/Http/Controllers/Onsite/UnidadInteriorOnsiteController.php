<?php

namespace App\Http\Controllers\Onsite;

use App\Http\Controllers\Controller;
use App\Services\Onsite\UnidadInteriorOnsiteService;
use App\Services\Onsite\ImagenUnidadOnsiteService;
use App\Services\Onsite\SistemaOnsiteService;
use App\Services\Onsite\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class UnidadInteriorOnsiteController extends Controller
{

    protected $unidadInteriorService;
    protected $imagenUnidadOnsiteService;
    protected $userService;
    protected $sistemaService;

    public function __construct(
        UnidadInteriorOnsiteService $unidadInteriorService,
        ImagenUnidadOnsiteService $imagenUnidadOnsiteService,
        UserService $userService,
        SistemaOnsiteService $sistemaService
    ) {
        $this->middleware('auth');
        $this->unidadInteriorService = $unidadInteriorService;
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
        $datos = $this->unidadInteriorService->getDataIndex();
        //$this->generarCsv($datos['userCompanyId'], null, null, null, $datos['userId']);
        return view('_onsite.unidadinterioronsite.index', $datos);
    }

	public function filtrarUnidadInterior(Request $request)
	{
		$datos = $this->unidadInteriorService->filtrar($request);

		return view('_onsite.unidadinterioronsite.index', $datos);
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
        $datos['user'] = $user;
        $datos['empresasOnsite'] = $this->unidadInteriorService->obtenerEmpresas();
        $datos['userCompanyId'] = Session::get('userCompanyIdDefault');

        return view('_onsite.unidadinterioronsite.create', $datos);
    }

    public function createUnidadInterior($sistema_onsite_id)
    {
        $datos = $this->unidadInteriorService->createUnidadInterior($sistema_onsite_id);
        return view('_onsite.unidadinterioronsite.createUnidadInterior', $datos);
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
        ]);
        $unidadInteriorOnsite = $this->unidadInteriorService->store($request);

        if($request->ajax()){
            return response()->json($unidadInteriorOnsite);
        }

        $mjeCreate = 'Unidad Interior Onsite: ' . $unidadInteriorOnsite->id . ' - registro creado correctamente!';

        if ($request['botonGuardarReturnSO']) {
            return redirect('/sistemaOnsite/' . $unidadInteriorOnsite->sistema_onsite_id . '/edit')->with('message', $mjeCreate);
        } else {
            return redirect('/unidadInterior')->with('message', $mjeCreate);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\UnidadInteriorOnsite  $unidadInteriorOnsite
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\UnidadInteriorOnsite  $unidadInteriorOnsite
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $idUser = session()->get('idUser');
        $user = $this->userService->findUser($idUser);


        $datos = $this->unidadInteriorService->getDataItem($id);
        $datos['imagenes'] = $this->unidadInteriorService->getImagenes($id);
        $datos['empresasOnsite'] = $this->unidadInteriorService->obtenerEmpresas();
        $datos['user'] = $user;

        return view('_onsite.unidadinterioronsite.edit', $datos);
    }

    public function editUnidadInterior($id)
    {
        $datos = $this->unidadInteriorService->getDataItem($id);
        $datos['imagenes'] = $this->unidadInteriorService->getImagenes($id);
        $datos['empresasOnsite'] = $this->unidadInteriorService->obtenerEmpresas();
        return view('_onsite.unidadinterioronsite.editUnidadInterior', $datos);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\UnidadInteriorOnsite  $unidadInteriorOnsite
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
        ]);

        $this->unidadInteriorService->update($request, $id);

        $mjeUpdate = 'Unidad Interior Onsite: ' . $id . ' - registro actualizado correctamente!';

        if ($request['botonGuardarReturnSO']) {
            return redirect('/sistemaOnsite/' . $request['sistema_onsite_id'] . '/edit')->with('message', $mjeUpdate);
        } else {
            return redirect('/unidadInterior')->with('message', $mjeUpdate);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UnidadInteriorOnsite  $unidadInteriorOnsite
     * @return \Illuminate\Http\Response
     */
    public function destroyImagenes($id)
    {
        $request['tipo_unidad'] = 'interior';
        $imagenesInteriores = $this->imagenUnidadOnsiteService->getImagenUnidad($request['tipo_unidad'], $id);
        foreach ($imagenesInteriores as $imagen) {
            $this->imagenUnidadOnsiteService->destroy($request, $imagen->id);
        }
    }

    public function destroy($id)
    {
        $this->destroyImagenes($id);
        $this->unidadInteriorService->destroy($id);

        $mjeDelete = 'Unidad Interior Onsite: ' . $id . ' - registro eliminado correctamente!';
        return redirect('/unidadInterior')->with('message', $mjeDelete);
    }

    public function checkIdSistema()
    {
        $idSistema = 0;

        if (session()->get('idSistemaCrearUnidad'))
            $idSistema =  session()->get('idSistemaCrearUnidad');

        return response()->json($idSistema);
    }

    public function insertUISistema($idSistema)
    {
        session()->put('idSistemaCrearUnidad', $idSistema);

        $idUser = session()->get('idUser');
        $user = $this->userService->findUser($idUser);
        $datos['user'] = $user;
        $datos['empresasOnsite'] = $this->unidadInteriorService->obtenerEmpresas();
        $datos['userCompanyId'] = Session::get('userCompanyIdDefault');

        return view('_onsite.unidadinterioronsite.create', $datos);

        
        
    }

    public function getUnidadesInterioresPorSistema ($idSistema)
    {
        $unidadesInteriores = $this->unidadInteriorService->unidadesInteriorPorSistema($idSistema);

        return response()->json($unidadesInteriores);
    }



    
}
