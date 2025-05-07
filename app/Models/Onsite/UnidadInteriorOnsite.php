<?php

namespace App\Models\Onsite;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasSorts;

class UnidadInteriorOnsite extends Model
{
  use HasSorts;

  public $campos_permitidos_para_ordenar = [
    'id',   
    'clave' ,
    'modelo',
    'serie',
    'created_at',
    'updated_at'
  ];

  protected $table = "unidades_interiores_onsite";

	protected $fillable = [		
    'company_id',
		'clave',
		'modelo',
		'serie',
		'direccion',		
    'faja_garantia',
		'observaciones',		
    'empresa_onsite_id',
    'sucursal_onsite_id',
    'sistema_onsite_id',
        
	];

  // RELACIONES
  /*public function compania()
  {
    return $this->belongsTo('App\Models\Onsite\Company', 'company_id');
  }*/
	public function empresa_onsite()
	{
		return $this->belongsTo('App\Models\Onsite\EmpresaOnsite', 'empresa_onsite_id');
	}
  public function sucursal_onsite()
  {
    return $this->belongsTo('App\Models\Onsite\SucursalOnsite', 'sucursal_onsite_id');
  }
  public function sistema_onsite()
  {
    return $this->belongsTo('App\Models\Onsite\SistemaOnsite', 'sistema_onsite_id');
  }

	public function imagenes()
	{
		return $this->hasMany(ImagenUnidadInteriorOnsite::class, 'unidad_interior_onsite_id');
	} 
  
  public function imagenes_unidad_interior()
	{
		return $this->hasMany(ImagenUnidadInteriorOnsite::class, 'unidad_interior_onsite_id');
	} 

  public function etiqueta()
	{
		return $this->hasMany(UnidadInteriorEtiqueta::class, 'unidad_interior_id');
	} 

  
}
