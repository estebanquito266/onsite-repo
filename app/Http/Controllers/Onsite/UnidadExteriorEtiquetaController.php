<?php

namespace App\Http\Controllers\Onsite;

use App\Http\Controllers\Controller;
use App\Services\Onsite\UnidadInteriorOnsiteService;
use App\Services\Onsite\ImagenUnidadOnsiteService;
use App\Services\Onsite\SistemaOnsiteService;
use App\Services\Onsite\UnidadExteriorEtiquetasService;
use App\Services\Onsite\UnidadExteriorOnsiteService;
use App\Services\Onsite\UnidadInteriorEtiquetasService;
use App\Services\Onsite\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class UnidadExteriorEtiquetaController extends Controller
{

    protected $unidadExteriorService;
    protected $unidadExteriorEtiquetaService;
    protected $userCompanyId;

    public function __construct(
        UnidadExteriorOnsiteService $unidadExteriorService,
        UnidadExteriorEtiquetasService $unidadExteriorEtiquetaService

    ) {
        $this->middleware('auth');
        $this->unidadExteriorService = $unidadExteriorService;
        $this->unidadExteriorEtiquetaService = $unidadExteriorEtiquetaService;
        $this->userCompanyId =  session()->get('userCompanyIdDefault');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $idUnidadExterior)
    {
        $request['company_id'] = $this->userCompanyId;
        $request['unidad_exterior_id'] = $idUnidadExterior;


        $request->validate([
            'nombre' => 'required',            
        ]);

        $unidad_exterior_etiqueta = $this->unidadExteriorEtiquetaService->store($request);

        return response()->json($unidad_exterior_etiqueta);


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
    }

    public function editUnidadInterior($id)
    {
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
    }



    public function destroy($idEtiqueta)
    {
        $etiqueta = $this->unidadExteriorEtiquetaService->destroy($idEtiqueta);

        return response()->json($etiqueta);
    }

    public function getEtiquetas($idUnidadExterior)
    {
        $etiquetas = $this->unidadExteriorEtiquetaService->getEtiquetasPorUnidadExterior($idUnidadExterior);

        return response()->json($etiquetas);
    }
}
