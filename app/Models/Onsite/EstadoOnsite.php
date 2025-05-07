<?php

namespace App\Models\Onsite;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasSorts;
use DB;

class EstadoOnsite extends Model
{
  use HasSorts;

  const NUEVA = 1;
  const COORDINADA = 2;
  const PENDIENTE_INFO = 3;  
  const CERRADA = 4; //TODO: Esto creo que no va a aca va en el repositorio
  const PENDIENTE_HARDWARE = 7;
  const NOTIFICADO = 27;
  const BGH_A_COORDINAR = 28;

  public $campos_permitidos_para_ordenar = [
    'id',
    'created_at',
    'nombre',
    'activo',
    'cerrado',
    'tipo_estado_onsite_id'
  ];

  protected $table = "estados_onsite";

  protected $fillable = [
    'nombre',
    'tipo_estado_onsite_id',
    'company_id',
    'plantilla_mail_responsable_id',
    'plantilla_mail_admin_id'
  ];


  // RELACIONES
  public function reparaciones_onsite()
  {
    return $this->hasMany('App\Models\Onsite\ReparacionOnsite', 'id_estado');
  }

  public function historial_estado_onsite()
  {
    return $this->hasMany('App\Models\Onsite\HistorialEstadoOnsite', 'id_estado');
  }
}
