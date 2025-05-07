<?php

namespace App\Services\Onsite;

use App\Models\Onsite\TipoServicioOnsite;
use Illuminate\Support\Facades\Session;

class TiposServiciosService
{
    const SEGUIMIENTO_OBRA = 50;
    const PUESTA_MARCHA = 60;

    public function listado($userCompanyId)
    {
        $company_id = Session::get('userCompanyIdDefault');

        $tiposServiciosPuestaMarcha = [$this::PUESTA_MARCHA, $this::SEGUIMIENTO_OBRA];

        return TipoServicioOnsite::select("id", "nombre")
            ->where('company_id', $company_id)
            ->whereNotIn('id', $tiposServiciosPuestaMarcha)
            ->orderBy('nombre', 'asc')
            ->pluck('nombre', 'id');
    }

    public function listadoPuestaMarcha($userCompanyId)
    {
        $company_id = Session::get('userCompanyIdDefault');

        $tiposServiciosPuestaMarcha = [$this::PUESTA_MARCHA, $this::SEGUIMIENTO_OBRA];

        return TipoServicioOnsite::select("id", "nombre")
            ->where('company_id', $company_id)
            //->whereIn('id', $tiposServiciosPuestaMarcha)
            ->orderBy('nombre', 'asc')
            ->pluck('nombre', 'id');
    }

    public function findTiposServiciosOnsiteAll()
    {
        $userCompanyId = Session::get('userCompanyIdDefault');
		$tiposServiciosOnsite = TipoServicioOnsite::where('company_id', $userCompanyId)->orderBy('nombre', 'ASC')->get();

        return $tiposServiciosOnsite;

    }

    public function getTiposServicios($company_id)
    {
        $tiposServiciosOnsite = TipoServicioOnsite::select(
            'id',
            'nombre',
            'created_at',
            'updated_at'
        )
        ->where('company_id', $company_id)
        ->get();

        return $tiposServiciosOnsite;
    }

    public function getTipoServicioById($id)
    {
        $tiposServiciosOnsite = TipoServicioOnsite::where('id', $id)
        ->first();

        return $tiposServiciosOnsite;
    }
}
