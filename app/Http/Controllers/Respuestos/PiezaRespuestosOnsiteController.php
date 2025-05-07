<?php

namespace App\Http\Controllers\Respuestos;

use App\Exports\PreciosRepuestosExport;
use App\Http\Controllers\Controller;
use App\Imports\PreciosRepuestosImport;
use App\Services\Onsite\Respuestos\ModeloRespuestosService;
use App\Services\Onsite\Respuestos\PiezaRespuestosService;
use Excel;
use Illuminate\Http\Request;
use Log;
use App\Imports\UsersImport;

class PiezaRespuestosOnsiteController extends Controller
{
    protected $piezaRespuestosService;

    public function __construct(
        PiezaRespuestosService $piezaRespuestosService
    ) {
        $this->piezaRespuestosService = $piezaRespuestosService;
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

    public function getPiezasPorModelo($idModelo)
    {
        $piezasRespuestos = $this->piezaRespuestosService->getPiezasPorModelo($idModelo);

        return response()->json($piezasRespuestos);
    }

    public function getPiezasCode($partCode)
    {
        $piezasRespuestos = $this->piezaRespuestosService->getPiezasCode($partCode);

        return response()->json($piezasRespuestos);
    }

    public function getPiezaCode($partCode)
    {
        $piezasRespuestos = $this->piezaRespuestosService->getPiezaCode($partCode);

        return response()->json($piezasRespuestos);
    }

    public function getPiezasName($partName)
    {
        $piezasRespuestos = $this->piezaRespuestosService->getPiezasName($partName);

        return response()->json($piezasRespuestos);
    }

    public function getPiezasDescription($partDescription)
    {
        $piezasRespuestos = $this->piezaRespuestosService->getPiezasDescription($partDescription);

        return response()->json($piezasRespuestos);
    }

    public function getPieza($idPieza)
    {
        $piezasRespuestos = $this->piezaRespuestosService->getPieza($idPieza);

        return response()->json($piezasRespuestos);
    }

    public function updatePieza($idPieza, Request $request)
    {
        

        $piezasRespuestos = $this->piezaRespuestosService->updatePieza($idPieza, $request);

        return response()->json($piezasRespuestos);
    }

    public function precios()
    {
        $data = [
            'version' => $this->piezaRespuestosService->getVersionPrecio()
        ];
        return view('_onsite.respuestosonsite.precios', $data);
    }

    public function export()
    {
        $response = Excel::download((new PreciosRepuestosExport), 'precios_repuestos.xlsx', null, [\Maatwebsite\Excel\Excel::XLSX]);

        return $response;
    }

    public function import(Request $request) 
    {
        $request->validate([
            'file' => 'required|max:10000|mimes:xlsx,xls',
        ]);

        $excel = $request->file('file');

        Excel::import(new PreciosRepuestosImport, $excel);
        
        return redirect('/')->with('success', 'All good!');
    }


    
    
    

    
}
