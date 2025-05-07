<?php

namespace App\Http\Controllers;

use App\Models\Onsite\SolicitudBoucher;
use App\Services\Onsite\SolicitudBoucherService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class SolicitudBoucherController extends Controller
{
    protected $solicitudBoucherService;

    public function __construct(SolicitudBoucherService $solicitudBoucherService)
    {
        $this->solicitudBoucherService = $solicitudBoucherService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = $this->solicitudBoucherService->getData();


        return view('_onsite/solicitudBoucher.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = $this->solicitudBoucherService->getDataList();

        return view('_onsite/solicitudBoucher.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $solicitudes_bouchers = $this->solicitudBoucherService->store($request);
        return redirect('/solicitudBoucher')->with('message', 'Registro creado correctamente');
    }

    public function storeBoucher(Request $request)
    {
        $solicitudes_bouchers = $this->solicitudBoucherService->storeBoucher($request);




        return response()->json($solicitudes_bouchers);
    }



    /**
     * Display the specified resource.
     *
     *   $
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     *   $
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *   $
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $idSolicitudBoucher)
    {
        $solicitudBoucher = $this->solicitudBoucherService->update($request, $idSolicitudBoucher);

        return response()->json($solicitudBoucher);
    }

    /**
     * Remove the specified resource from storage.
     *
     *   $
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        //
    }

    public function getBouchersPorObra($idObra)
    {
        $bouchers = $this->solicitudBoucherService->getBouchersPorObra($idObra);

        return response()->json($bouchers);
    }

    public function unsetSessionVariable()
    {
        if (Session::get('idsBoucherTemporal'));
        Session::forget('idsBoucherTemporal');

        return true;
    }

    public function getAllBouchersPorObra($idObra)
    {
        $bouchers = $this->solicitudBoucherService->getAllBouchersPorObra($idObra);

        return response()->json($bouchers);
    }

    
}
