<?php

namespace App\Models\Onsite;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasSorts;

class SucursalOnsite extends Model
{
	use HasSorts;

	public $campos_permitidos_para_ordenar = [
		'id',
		'codigo_sucursal',
		'empresa_onsite_id',
		'razon_social',
		'created_at',
		'updated_at'
	];

	protected $table = "sucursales_onsite";

	protected $fillable = [
		'codigo_sucursal',
		'empresa_onsite_id',
		'razon_social',
		'localidad_onsite_id',
		'direccion',
		'telefono_contacto',
		'nombre_contacto',
		'horarios_atencion',
		'observaciones',
		'company_id',
	];


	// RELACIONES
	public function empresa_onsite()
	{
		return $this->belongsTo('App\Models\Onsite\EmpresaOnsite', 'empresa_onsite_id');
	}

	public function localidad_onsite()
	{
		return $this->belongsTo('App\Models\Onsite\LocalidadOnsite', 'localidad_onsite_id');
	}

	public function company()
	{
		return $this->belongsTo('App\Models\Company');
	}

	public function comments()
	{
		return $this->hasMany('App\Models\Onsite\ReparacionOnsite', 'sucursal_onsite_id');
	}

	public function tecnicosOnsite()
	{
		return $this->belongsToMany('App\Models\User', 'user_sucursales_onsite', 'sucursal_onsite_id', 'user_id');
	}

	public function terminalesOnsite()
	{
		return $this->hasMany('App\Models\Onsite\TerminalOnsite', 'sucursal_onsite_id');
	}

	public function sistemas_onsite()
	{
		return $this->hasMany('App\Models\Onsite\SistemaOnsite', 'sucursal_onsite_id');
	}
}
