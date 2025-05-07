<?php

namespace App\Http\Controllers;

use App\Models\Onsite\SolicitudTipoTarifa;
use App\Services\Onsite\SolicitudTipoTarifaService;
use Illuminate\Http\Request;

class SolicitudTipoTarifaController extends Controller
{

    protected $solicitudTipoTarifaService;

    public function __construct(SolicitudTipoTarifaService $solicitudTipoTarifaService)
    {
        $this->solicitudTipoTarifaService = $solicitudTipoTarifaService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = $this->solicitudTipoTarifaService->getData();

        return view('_onsite/solicitudTipoTarifa.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = $this->solicitudTipoTarifaService->getDataList();

        return view('_onsite/solicitudTipoTarifa.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->solicitudTipoTarifaService->store($request);

        return redirect('/solicitudesTiposTarifas')->with('message', 'Registro creado correctamente');
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

    public function getTarifaSolicitudPorObra($idSolicitud, $idObra)
    {
        $tarifa = $this->solicitudTipoTarifaService->getTarifaSolicitudPorObra($idSolicitud, $idObra);

        return response()->json($tarifa);
    }
}
