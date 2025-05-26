<?php

namespace App\Services\Onsite;

use Riparazione\Models\Config\ParamCompany;

class ParamCompaniesService
{


    public function __construct()
    {
    }

    public function getParamCompany($company_id)
    {
        return ParamCompany::where('company_id', $company_id)
            ->first();
    }

    
}
