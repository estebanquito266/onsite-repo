<?php

namespace App\Models\Onsite;

use Illuminate\Database\Eloquent\Model;

class HistorialEstadoNotaOnsite extends Model
{
  protected $table = "historial_estados_notas_onsite";

  protected $fillable = [
    'id', 'id_reparacion', 'id_estado', 'fecha', 'observacion', 'id_usuario'
  ];

  //RELACIONES
  public function usuario()
  {
    return $this->belongsTo('App\Models\User', 'id_usuario', 'id');
  }

  public function estado_onsite()
  {
    return $this->belongsTo('App\Models\Onsite\EstadoOnsite', 'id_estado', 'id');
  }
}
