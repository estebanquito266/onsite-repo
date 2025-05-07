<?php

namespace App\Models\Onsite;

use Illuminate\Database\Eloquent\Model;

class ReparacionChecklistOnsite extends Model
{
  protected $table = "reparacion_checklist_onsite";

  protected $fillable = [
        'company_id',
        'reparacion_onsite_id',
        'alimentacion_definitiva',
        'unidades_tension_definitiva',
        'cable_alimentacion_comunicacion_seccion_ok', 
        'minimo_conexiones_frigorificas_exteriores',
        'sistema_presurizado_41_5_kg',
        'sistema_presurizado_41_5_kg_tiempo_horas',
        'operacion_vacio',
        'unidad_exterior_tension_12_horas',
        'llave_servicio_odu_abiertos',
        'carga_adicional_introducida',
        'carga_adicional_introducida_kg_final',
        'carga_adicional_introducida_kg_adicional',
        'sistema_funcionando_15_min_carga_adicional',
        'puesta_marcha_satisfactoria',
  ];

  public function reparacion_onsite()
  {
    return $this->belongsTo('App\Models\Onsite\ReparacionOnsite', 'reparacion_onsite_id');
  }

}
