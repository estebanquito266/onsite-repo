<?php

namespace App\Services\Onsite;

use App\Models\PlantillaMail;
use Illuminate\Support\Facades\Session;

class PlantillasMailsService
{

    public function findPlantillaMail($idPlantillaMail)
    {
        if (Session::has('userCompanyIdDefault')) {
            $company_id = Session::get('userCompanyIdDefault');
            $request['company_id'] = $company_id;
        } else
            $company_id = env('BGH_COMPANY_ID', 2);


        $plantillaMail = PlantillaMail::where('company_id', $company_id)->find($idPlantillaMail);

        return $plantillaMail;
    }
}
