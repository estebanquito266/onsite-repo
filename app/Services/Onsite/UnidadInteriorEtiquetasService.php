<?php

namespace App\Services\Onsite;

use App\Models\Onsite\GarantiaOnsite;
use App\Models\Onsite\UnidadExteriorEtiqueta;
use App\Models\Onsite\UnidadInteriorEtiqueta;
use App\Models\Onsite\UnidadInteriorOnsite;
use Log;

class UnidadInteriorEtiquetasService
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
        $unidad_interior_etiqueta = UnidadInteriorEtiqueta::create($request->all());

        return $unidad_interior_etiqueta;
    }



    public function update($request, $id)
    {
       
    }

    public function destroy($idEtiqueta)
    {
        $etiqueta = UnidadInteriorEtiqueta::find($idEtiqueta);
        $etiqueta->delete();

        return $etiqueta;
    }

    public function getEtiquetasPorUnidadInterior($idUnidadInterior)
    {
        $etiquetas = UnidadInteriorEtiqueta::where('company_id', $this->userCompanyId)
        ->where('unidad_interior_id', $idUnidadInterior)
        ->get();

        return $etiquetas;
    }

  
}
