<?php

namespace App\Http\Controllers\Onsite;

use App\Http\Controllers\Controller;
use App\Services\Onsite\ImagenUnidadOnsiteService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ImagenUnidadOnsiteController extends Controller
{

    protected $imagenUnidadService;

    public function __construct(ImagenUnidadOnsiteService $imagenUnidadService)
    {
        $this->middleware('auth');
        //$this->historial_estado_onsite_repository = new HistorialEstadoOnsiteRepository;
        $this->imagenUnidadService  = $imagenUnidadService;

        //$this->middleware('permiso', ['only' => ['index', 'create', 'store', 'edit', 'update', 'destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //        
    }

    /**
     * Display create
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createExterior($unidadOnsite)
    {
        $datos = $this->imagenUnidadService->getDatos();
        $datos['tipo_unidad'] = 'exterior';
        $datos['unidadOnsite'] = $unidadOnsite;
        return view('_onsite.imagenunidadonsite.create', $datos);
    }

    public function createInterior($unidadOnsite)
    {
        $datos = $this->imagenUnidadService->getDatos();
        $datos['tipo_unidad'] = 'interior';
        $datos['unidadOnsite'] = $unidadOnsite;
        return view('_onsite.imagenunidadonsite.create', $datos);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $imagenUnidadOnsite = $this->imagenUnidadService->store($request);

        $mjeCreate = 'Imagen Unidad Onsite: ' . $imagenUnidadOnsite->id . ' - registro creado correctamente!';

        if ($request['botonGuardarReturnSO']) {
            if ($request['tipo_unidad'] == 'exterior') {
                return redirect('/unidadExterior/' . $imagenUnidadOnsite->unidad_exterior_onsite_id . '/edit')->with('message', $mjeCreate);
            } else {
                return redirect('/unidadInterior/' . $imagenUnidadOnsite->unidad_interior_onsite_id . '/edit')->with('message', $mjeCreate);
            }
        } else {
            return redirect('/imagenUnidadOnsite')->with('message', $mjeCreate);
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
     * Display edit.
     *
     * @param  \App\Models\UnidadInteriorOnsite  $unidadInteriorOnsite
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\UnidadInteriorOnsite  $unidadInteriorOnsite
     * @return \Illuminate\Http\Response
     */
    public function editExterior($id)
    {
        $datos = $this->imagenUnidadService->getImagenExterior($id);
        return view('_onsite.imagenunidadonsite.edit', $datos);
    }

    public function editInterior($id)
    {
        $datos = $this->imagenUnidadService->getImagenInterior($id);
        return view('_onsite.imagenunidadonsite.edit', $datos);
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

        $imagenUnidadOnsite = $this->imagenUnidadService->update($request, $id); // MAL EL UPDATE

        $mjeUpdate = 'Imagen Unidad Onsite: ' . $id . ' - registro actualizado correctamente!';

        if ($request['botonGuardarReturnSO']) {
            if ($request['tipo_unidad'] == 'exterior') {
                return redirect('/unidadExterior/' . $imagenUnidadOnsite->unidad_exterior_onsite_id . '/edit')->with('message', $mjeUpdate);
            } else {
                return redirect('/unidadInterior/' . $imagenUnidadOnsite->unidad_interior_onsite_id . '/edit')->with('message', $mjeUpdate);
            }
        } else {
            return redirect('/imagenUnidadOnsite')->with('message', $mjeUpdate);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UnidadInteriorOnsite  $unidadInteriorOnsite
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        //        $datos = $this->imagenUnidadService->getImagenExterior($id); 

        $this->imagenUnidadService->destroy($request, $id);
        $mjeDelete = 'Imagen Unidad Onsite: ' . $id . ' - eliminada correctamente!';


        if ($request['tipo_unidad'] == 'exterior') {
            return redirect('/unidadExterior/' . $request['unidad_exterior_onsite_id'] . '/edit')->with('message', $mjeDelete);
        } else {
            return redirect('/unidadInterior/' . $request['unidad_interior_onsite_id'] . '/edit')->with('message', $mjeDelete);
        }
        //return redirect('/unidadInterior')->with('message', $mjeDelete);        
    }
}
