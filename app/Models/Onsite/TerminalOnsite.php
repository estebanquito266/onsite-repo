<?php

namespace App\Models\Onsite;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasSorts;

class TerminalOnsite extends Model
{
  use HasSorts;

  const DEFAULT = 1;

  public $campos_permitidos_para_ordenar = [
    'id',
    'nro',
    'sucursal_onsite_id',
    'empresa_onsite_id',
    'marca',
    'modelo',
    'created_at',
    'updated_at'
  ];

  protected $primaryKey = "nro";

	public $incrementing = false;

  protected $table = "terminales_onsite";

	protected $fillable = [
		'nro',
		'company_id',		
		'all_terminales_sucursal',
		'marca',
		'modelo',
		'serie',
		'rotulo',
		'observaciones',
		'empresa_onsite_id',
		'sucursal_onsite_id',

	];



  // RELACIONES

  public function reparaciones_onsite()
  {
    return $this->hasMany('App\Models\Onsite\ReparacionOnsite', 'terminal_id');
  }

  /*public function localidad_onsite()
  {
    return $this->belongsTo('App\Models\Onsite\LocalidadOnsite', 'id_localidad');
  }*/

  public function empresa_onsite()
	{
		return $this->belongsTo('App\Models\Onsite\EmpresaOnsite', 'empresa_onsite_id');
	}

	public function sucursal_onsite()
	{
		return $this->belongsTo('App\Models\Onsite\SucursalOnsite', 'sucursal_onsite_id');
	}
}
