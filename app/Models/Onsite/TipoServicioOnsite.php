<?php

namespace App\Models\Onsite;

use App\Models\Company;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasSorts;

class TipoServicioOnsite extends Model
{
  use HasSorts;

  const SEGUIMIENTO_OBRA = 50;
  const PUESTA_MARCHA = 60;
  
  public $campos_permitidos_para_ordenar = [
    'id',
    'nombre',
    'created_at',
    'updated_at'
  ];

  protected $table = "tipos_servicios_onsite";
	
	protected $fillable = [
    'nombre',
    'company_id',
  ];

 
	
  // RELACIONES

  // BELONGS TO
  public function company()
  {
    return $this->belongsTo(Company::class, 'company_id');
  }

  // HAS MANY
  public function reparaciones_onsite()
  {
    return $this->hasMany('App\Models\Onsite\ReparacionOnsite', 'id_tipo_servicio');
  }
}
