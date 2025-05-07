<?php

namespace App\Models\Onsite;

use Illuminate\Database\Eloquent\Model;

class UnidadInteriorEtiqueta extends Model
{

  protected $table = "unidades_interiores_etiquetas";

	protected $fillable = [		
    'company_id',
		'id',
    'unidad_interior_id',
    'nombre'
        
	];

  // RELACIONES
 
	
  public function unidad_interior()
  {
    return $this->belongsTo(UnidadInteriorOnsite::class, 'unidad_interior_id');
  }	

  
}
