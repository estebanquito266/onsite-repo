<?php

namespace App\Services\Onsite;

use App\Models\Onsite\ReparacionDetalle;
use Illuminate\Support\Facades\Session;

class ReparacionDetalleService
{

    public function getReparacionDetalleByReparacion($reparacion_id)
    {
        $reparacion_detalle = ReparacionDetalle::where('reparacion_id', $reparacion_id)->first();

        return $reparacion_detalle;
    }

}