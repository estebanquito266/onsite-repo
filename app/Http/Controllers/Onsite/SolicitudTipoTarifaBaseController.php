<?php

namespace App\Http\Controllers\Onsite;

use App\Http\Controllers\Controller;


use App\Models\Onsite\SolicitudTipoTarifa;
use App\Services\Onsite\SolicitudTipoTarifaBaseService;
use Illuminate\Http\Request;

class SolicitudTipoTarifaBaseController extends Controller
{

    protected $solicitudTipoTarifaBaseService;

    public function __construct(SolicitudTipoTarifaBaseService $solicitudTipoTarifaBaseService)
    {
        $this->solicitudTipoTarifaBaseService =$solicitudTipoTarifaBaseService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = $this->solicitudTipoTarifaBaseService->getData();

        return view('_onsite/solicitudTipoTarifaBase.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = $this->solicitudTipoTarifaBaseService->getDataList();

        return view('_onsite/solicitudTipoTarifaBase.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->solicitudTipoTarifaBaseService->store($request);

        return redirect('/solicitudesTiposTarifasBases')->with('message', 'Registro creado correctamente');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SolicitudTipoTarifa  $solicitudTipoTarifa
     * @return \Illuminate\Http\Response
     */
    public function show(SolicitudTipoTarifa $solicitudTipoTarifa)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SolicitudTipoTarifa  $solicitudTipoTarifa
     * @return \Illuminate\Http\Response
     */
    public function edit(SolicitudTipoTarifa $solicitudTipoTarifa)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SolicitudTipoTarifa  $solicitudTipoTarifa
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SolicitudTipoTarifa $solicitudTipoTarifa)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SolicitudTipoTarifa  $solicitudTipoTarifa
     * @return \Illuminate\Http\Response
     */
    public function destroy(SolicitudTipoTarifa $solicitudTipoTarifa)
    {
        //
    }
}
