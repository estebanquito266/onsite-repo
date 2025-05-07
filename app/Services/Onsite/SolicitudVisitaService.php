<?php

namespace App\Services\Onsite;

use App\Models\Onsite\SolicitudVisita;

class SolicitudVisitaService
{

    public function store($params){
        $newSolicitudVisita = new SolicitudVisita();
        $newSolicitudVisita->solicitud_id = $params['solicitud_id'];
        $newSolicitudVisita->visita_id = $params['visita_id'];
        $newSolicitudVisita->save();

        return $newSolicitudVisita;
    }
}