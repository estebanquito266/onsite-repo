<?php

namespace App\Models\Onsite;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasSorts;

class ImagenUnidadInteriorOnsite extends Model
{
  use HasSorts;

    public $campos_permitidos_para_ordenar = [
      'id',
      'unidad_interior_onsite_id',
      'descripcion'
    ];
  
    protected $table = "imagenes_unidades_interiores_onsite";

  protected $fillable = [ 
        'company_id',
        'unidad_interior_onsite_id',
        'tipo_imagen_onsite_id',
        'archivo',
        'descripcion',        
  ];


    // RELACIONES
  public function company()
  {
    return $this->belongsTo('App\Models\Company', 'company_id');
  }
  public function tipo_imagen()
  {
    return $this->belongsTo('App\Models\Onsite\TipoImagenOnsite', 'tipo_imagen_onsite_id');
  } 
  public function unidad_interior_onsite()
  {
    return $this->belongsTo('App\Models\Onsite\UnidadInteriorOnsite', 'unidad_interior_onsite_id');
  } 
}
