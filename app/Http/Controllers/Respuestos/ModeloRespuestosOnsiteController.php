<?php

namespace App\Http\Controllers\Respuestos;

use App\Http\Controllers\Controller;
use App\Services\Onsite\Respuestos\ModeloRespuestosService;
use Illuminate\Http\Request;
use Log;

class ModeloRespuestosOnsiteController extends Controller
{
    protected $modeloRespuestosService;

    public function __construct(
        ModeloRespuestosService $modeloRespuestosService
    ) {
        $this->modeloRespuestosService = $modeloRespuestosService;
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ModeloRespuestosOnsite  $modeloRespuestosOnsite
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ModeloRespuestosOnsite  $modeloRespuestosOnsite
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ModeloRespuestosOnsite  $modeloRespuestosOnsite
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ModeloRespuestosOnsite  $modeloRespuestosOnsite
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getModelosPorCategoria($idCategoria)
    {
        $modelosRespuestos = $this->modeloRespuestosService->getModelosRespuestosPorCategoria($idCategoria);

        return response()->json($modelosRespuestos);
    }

    public function getImagenPorModelo($idModelo)
    {
        $modeloRespuestos = $this->modeloRespuestosService->getImagenPorModelo($idModelo);

        

        return response()->json($modeloRespuestos);
    }


    
}
