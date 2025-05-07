<?php

namespace App\Services\Onsite;

use App\Models\Onsite\GarantiaOnsite;
use App\Models\Onsite\SolicitudBoucher;
use App\Models\Onsite\SolicitudBoucherTipo;
use App\Models\Onsite\SolicitudTipoTarifa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Log;


class SolicitudBoucherService
{
    protected $userCompanyId;
    protected $empresasInstaladorasServices;
    protected $obrasService;


    public function __construct(
        EmpresasInstaladorasServices $empresasInstaladorasServices,
        ObrasOnsiteService $obrasService

    ) {
        $this->userCompanyId =  session()->get('userCompanyIdDefault');
        $this->empresasInstaladorasServices = $empresasInstaladorasServices;
        $this->obrasService = $obrasService;
    }
    public function getDataList()
    {
        $data = [
            'empresas_instaladoras' => $this->empresasInstaladorasServices->getEmpresasInstaladoras(),
            'obras' => $this->obrasService->getAllObrasOnsite()
        ];

        return $data;
    }

    public function getData()
    {

        $data = [
            'solicitudes_bouchers' => SolicitudBoucher::where('company_id', $this->userCompanyId)
                ->get()
        ];

        return $data;
    }


    public function show()
    {
    }

    public function store($request)
    {
        $cantidad = $request['cantidad'];
        $codigo = 'BI-' . 'E' . $request['empresa_instaladora_id'] . 'O' . $request['obra_id'] . 'N';

        for ($i = 0; $i <  $cantidad; $i++) {
            $array = [
                'company_id' => $request['company_id'],
                'obra_id' => $request['obra_id'],

                'solicitud_boucher_tipo_id' => SolicitudBoucherTipo::BOUCHER_INICIAL,
                'solicitud_tarifa_id' => 1,
                'codigo' => $codigo . $i,
                'precio' => 0,
                'consumido' => false,
                'fecha_expira' => $request['fecha_expira'],
                'observaciones' => $request['observaciones']
            ];

            $bouchers = SolicitudBoucher::create($array);

            $array = null;
        };

        return true;
    }

    public function storeBoucher(Request $request)
    {
        $request['company_id'] = $this->userCompanyId;       

        
        $idBoucherCreado = Session::get('idBouchersCreados');

        $boucher = SolicitudBoucher::create($request->all());

        $idBoucherCreado[] = [
            $boucher->id
        ];

        return $boucher;
    }



    public function destroy($id)
    {
    }



    public function update(Request $request, $idBoucher)
    {
        

        $solicitudBoucher = SolicitudBoucher::find($idBoucher);
        $solicitudBoucher->update($request->all());
                

        return $solicitudBoucher;
    }

    public function getBouchersPorObra($idObra)
    {
        if (!Session::get('idsBoucherTemporal')) Session::put('idsBoucherTemporal', ['x']);

        $boucher = SolicitudBoucher::with('obra_onsite')
            ->where('company_id', $this->userCompanyId)
            ->where('obra_id', $idObra)
            ->where('consumido', false)
            ->whereNotIn('id', Session::get('idsBoucherTemporal'))
            ->first();

        if ($boucher) {
            $idsBoucherTemporal = Session::get('idsBoucherTemporal');
            $idsBoucherTemporal[] = $boucher->id;
            Session::put('idsBoucherTemporal', $idsBoucherTemporal);
            
        } else Log::alert('no posee boucher');


        return $boucher;
    }

    public function findSolicitudBoucher($idBoucher)
    {
        $boucher = SolicitudBoucher::where('company_id', $this->userCompanyId)
        ->get();

        return $boucher;
    }

    public function getSolicitudesBoucherPendienteImputacionPorSistema($sistemaId)
    {
      $boucherPendiente = SolicitudBoucher::where('company_id', $this->userCompanyId)
      ->where('pendiente_imputacion', true)
      ->where('sistema_id_consumido', $sistemaId)
      ->first();

      return $boucherPendiente;

    }

    public function getAllBouchersPorObra($idObra)
    {
        $bouchers = SolicitudBoucher::with('tipo_boucher')
        ->with('obra_onsite')
        ->with('sistema_consumido')
        ->where('company_id', $this->userCompanyId)        
        ->where('obra_id', $idObra)
        ->get();

        return $bouchers;
    }
}
