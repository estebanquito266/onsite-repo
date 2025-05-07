<?php

namespace App\Services\Onsite;

use App\Models\Onsite\EstadoOnsite;
use Illuminate\Support\Facades\Session;

class EstadosService
{
    public function getEstados($company_id, $tipo = null)
    {


        if (!is_null($tipo)) {
            $estados = EstadoOnsite::where('company_id', $company_id)
                ->where('cerrado', $tipo)
                ->get();
        } else
            $estados = EstadoOnsite::where('company_id', $company_id)
                ->get();

        return $estados;
    }


    public function getEstadoById($id)
    {
        $estado = EstadoOnsite::where('id', $id)
            ->first();

        return $estado;
    }
}
