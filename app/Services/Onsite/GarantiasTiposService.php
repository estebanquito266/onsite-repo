<?php

namespace App\Services\Onsite;

use App\Models\Onsite\CompradorOnsite;
use App\Models\Onsite\GarantiaOnsite;
use App\Models\Onsite\GarantiaTipoOnsite;
use Illuminate\Http\Request;

class GarantiasTiposService
{
    protected $userCompanyId;

    public function __construct()
    {
        $this->userCompanyId =  session()->get('userCompanyIdDefault');
    }
    public function getDataList()
    {
    }

    public function create()
    {
    }

    public function show()
    {
    }

    public function store(Request $request)
    {
    }

    public function update($request, $idComprador)
    {
    }

    public function destroy($id)
    {
    }

    public function getAllGarantiasTipos()
    {
        //        $tiposGarantia = GarantiaTipoOnsite::where('company_id', $this->userCompanyId)->get();
        $tiposGarantia = GarantiaTipoOnsite::where('company_id', $this->userCompanyId)->get();
        return $tiposGarantia;
    }
}
