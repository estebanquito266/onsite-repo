<?php

namespace App\Services\Onsite;

use App\Models\Onsite\EstadoSolicitudOnsite;


use Illuminate\Support\Facades\Session;

class EstadosSolicitudesService
{
    public function getEstadosSolicitudesAll()
    {
        $company_id = Session::get('userCompanyIdDefault');

        $estadosSolicitudes= EstadoSolicitudOnsite::where('company_id', $company_id)->get();

        return $estadosSolicitudes;

    }

    public function getEstadosSolicitud($company_id)
    {
        $estados = EstadoSolicitudOnsite::select(
            'id',
            'nombre',
            'plantilla_mail_cliente_id',
            'pendiente',
            'created_at',
            'updated_at'
        )
        ->where('company_id', $company_id)
        ->get();

        return $estados;
    }
}
