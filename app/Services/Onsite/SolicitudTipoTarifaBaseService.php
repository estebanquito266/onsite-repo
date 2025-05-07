<?php

namespace App\Services\Onsite;

use App\Models\Onsite\GarantiaOnsite;
use App\Models\Onsite\SolicitudTipoTarifa;
use App\Models\Onsite\SolicitudTipoTarifaBase;
use Log;

class SolicitudTipoTarifaBaseService
{
    protected $userCompanyId;
    protected $solicitudesTiposService;
    protected $obraOnsiteService;
    
    

    public function __construct(
        
        SolicitudesTiposService $solicitudesTiposService,
        ObrasOnsiteService $obraOnsiteService,
        UserService $userService,
        EmpresasInstaladorasServices $empresaInstaladoraService
    ) {
        $this->userCompanyId =  session()->get('userCompanyIdDefault');
        $this->solicitudesTiposService = $solicitudesTiposService;
        $this->obraOnsiteService = $obraOnsiteService;
        $this->userService = $userService;
        $this->empresaInstaladoraService = $empresaInstaladoraService;
        
    }
    public function getDataList()
    {
        $idUser = session()->get('idUser');
        $user = $this->userService->findUser($idUser);

        if (count($user->empresa_instaladora) > 0) {
            $idEmpresaInstaladora = $user->empresa_instaladora[0]->id;
            $obrasOnsite =  $this->obraOnsiteService->getAllObrasOnsitePorEmpresaUser($idEmpresaInstaladora);
        } else
            $obrasOnsite = null;

        $solicitudesTipos = $this->solicitudesTiposService->getAllSolicitudesTipos();        

        $empresas_instaladoras = $this->empresaInstaladoraService->getEmpresasInstaladoras();
        
        $data = [
            'empresas_instaladoras_admins' => $empresas_instaladoras,
            'obrasOnsite' => $obrasOnsite,
            'solicitudesTipos' => $solicitudesTipos,            
        ];

        return $data;
    }

    public function getData()
    {
        $solicitud_tipo_tarifas = SolicitudTipoTarifaBase::where('company_id', $this->userCompanyId)
       
        ->orderBy('id', 'desc')
        ->limit(4)        
        ->get();       
        
        $data = [
            'solicitudes_tipos_tarifas' => $solicitud_tipo_tarifas,
        ];

        return $data;
    }

    public function create()
    {
    }

    public function show()
    {
    }

    public function store($request)
    {
        $cantidad = count($request['solicitud_tipo_id']);
        
        for ($i=0; $i <  $cantidad; $i++) { 
            $array = [
                'company_id' => $request['company_id'][$i],
                'solicitud_tipo_id'=>$request['solicitud_tipo_id'][$i],
                'moneda' => 'peso',
                'precio' =>$request['precio'][$i],
                'version' =>$request['version'][$i],
                'observaciones' =>$request['observaciones'][$i]
            ];
                        
            $solicitud_tipo_tarifas = SolicitudTipoTarifaBase::create($array);

            $array = null;
        };            

        return true;

    }

    

    public function destroy($id)
    {
    }

   
}
