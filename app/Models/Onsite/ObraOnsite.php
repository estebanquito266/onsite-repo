<?php

namespace App\Models\Onsite;

use App\Models\EmpresaInstaladora\EmpresaInstaladoraOnsite;
use App\Models\EmpresaInstaladora\EmpresaInstaladoraUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ObraOnsite extends Model
{
	protected $table = "obras_onsite";

	protected $fillable = [
		'id_unificado',
		'company_id',
		'clave',
		'nombre',
		'nro_cliente_bgh_ecosmart',
		'cantidad_unidades_exteriores',
		'cantidad_unidades_interiores',
		'empresa_instaladora_id',
		'empresa_instaladora_nombre',
		'empresa_onsite_id',
		'empresa_instaladora_responsable',
		'responsable_email',
		'responsable_telefono',
		'pais',
		'provincia_onsite_id',
		'localidad_onsite_id',
		'localidad_texto',
		'domicilio',
		'latitud',
		'longitud',
		'estado',
		'estado_detalle',
		'esquema',
	];


	public function obraChecklistOnsite()
	{
		return $this->hasOne('App\Models\Onsite\ObraChecklistOnsite', 'obra_onsite_id', 'id');
	}

	public function empresa_onsite()
	{
		return $this->belongsTo('App\Models\Onsite\EmpresaOnsite', 'clave', 'clave');
	}

	public function company()
	{
		return $this->belongsTo('App\Models\Company', 'company_id');
	}

	public function solicitud_onsite()
	{
		return $this->hasMany('App\Models\Onsite\SolicitudOnsite', 'obra_onsite_id', 'id');
	}


	public function sistema_onsite()
	{
		return $this->hasMany(SistemaOnsite::class, 'obra_onsite_id')->with([
			'reparacion_onsite' => function ($query) {
				$query->orderBy('id', 'desc');
			}
		]);
	}

	public function sistema_onsite_obras()
	{
		return $this->hasMany(SistemaOnsite::class, 'obra_onsite_id');
	}

	

	public function empresa_instaladora()
	{
		return $this->belongsTo(EmpresaInstaladoraOnsite::class, 'empresa_instaladora_id');
	}

	public function sistema_onsite_unificado()
	{
		return $this->hasMany(SistemaOnsite::class, 'obra_onsite_id_unificado');
	}

	public function sistemas_onsite()
	{
		return $this->hasMany(SistemaOnsite::class, 'obra_onsite_id');
	}

	public function imagenes_obras()
	{
		return $this->hasMany(ImagenObraOnsite::class, 'obra_onsite_id');
	}

}
