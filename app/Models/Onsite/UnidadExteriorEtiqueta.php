<?php

namespace App\Models\Onsite;

use Illuminate\Database\Eloquent\Model;

class UnidadExteriorEtiqueta extends Model
{

  protected $table = "unidades_exteriores_etiquetas";

	protected $fillable = [		
    'company_id',
		'id',
    'unidad_exterior_id',
    'nombre'
        
	];

  // RELACIONES
 
	
  public function unidad_exterior()
  {
    return $this->belongsTo(UnidadExteriorOnsite::class, 'unidad_exterior_id');
  }	

  
}
