<?php

namespace App\Services\Onsite;

use App\Models\Onsite\GarantiaOnsite;
use App\Models\Onsite\UnidadExteriorEtiqueta;
use App\Models\Onsite\UnidadInteriorEtiqueta;
use App\Models\Onsite\UnidadInteriorOnsite;
use Log;

class UnidadExteriorEtiquetasService
{
    protected $userCompanyId;
    

    public function __construct()
    {
        $this->userCompanyId =  session()->get('userCompanyIdDefault');
        
    }


    public function create()
    {
    }

    public function show()
    {
    }

    public function store($request)    
    {
        $unidad_Exterior_etiqueta = UnidadExteriorEtiqueta::create($request->all());

        return $unidad_Exterior_etiqueta;
    }



    public function update($request, $id)
    {
       
    }

    public function destroy($idEtiqueta)
    {
        $etiqueta = UnidadExteriorEtiqueta::find($idEtiqueta);
        $etiqueta->delete();

        return $etiqueta;
    }

    public function getEtiquetasPorUnidadExterior($idUnidadExterior)
    {
        $etiquetas = UnidadExteriorEtiqueta::where('company_id', $this->userCompanyId)
        ->where('unidad_exterior_id', $idUnidadExterior)
        ->get();

        return $etiquetas;
    }

  
}
