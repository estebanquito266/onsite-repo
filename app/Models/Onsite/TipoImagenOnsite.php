<?php

namespace App\Models\Onsite;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class TipoImagenOnsite extends Model
{
    protected $table = "tipos_imagenes_onsite";
    
    const NINGUNO = 1;
    const TIPO_FRENTE_LOCAL = 2;
    const TIPO_EQUIPO = 3;
    const TIPO_TERMINAL_RED = 4;
    const TIPO_COMPOBANTE = 5;
    const TIPO_TRABAJO = 6;
    const EQUIPO = 16;
    const TRABAJO = 18;
    const CORTE_CANERIA = 22;
    const ANOMALIAS = 24;
    const PRESURIZACION = 26;
    const COMPROBANTE_SERVICIO_FIRMADO = 28;
    const TRABAJO_REALIZADO = 30;
    const UNIDAD_INTERIOR = 50;
    const UNIDAD_EXTERIOR = 60;

    

    /* RELATIONS */
    public function imagenOnsite()
    {
        return $this->hasMany(ImagenOnsite::class, 'tipo_imagen_onsite_id');
    }
}
