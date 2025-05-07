<?php

namespace App\Models\Onsite;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class HistorialEstadoOnsite extends Model
{
  protected $table = "historial_estados_onsite";

  protected $fillable = [
    'id_reparacion',
    'id_estado',
    'fecha',
    'observacion',
    'id_usuario',
    'visible',
    'company_id',
  ];



  // RELACIONES
  public function reparacion_onsite()
  {
    return $this->belongsTo('App\Models\Onsite\ReparacionOnsite', 'id_reparacion');
  }

  public function estado_onsite()
  {
    return $this->belongsTo('App\Models\Onsite\EstadoOnsite', 'id_estado');
  }

  public function usuario()
  {
    return $this->belongsTo('App\Models\User', 'id_usuario');
  }

  public function company()
  {
    return $this->belongsTo('App\Models\Company');
  }

  public function usuarios_visibles()
  {
    return $this->belongsToMany('App\Models\User', 'historial_estados_onsite_visible_por_user', 'historial_estados_onsite_id', 'users_id');
  }
}
