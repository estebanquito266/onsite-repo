<?php

namespace App\Models\Respuestos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImagenModelosRepuestos extends Model
{
    use HasFactory;

    protected $table = "imagenes_modelos_repuestos";

    protected $fillable = [
        'id',
      'company_id',
      'modelo_respuestos_id',
      'imagen_despiece'
     
    ]; 

    public function company()
	{
		return $this->belongsTo('App\Models\Admin\Company');
	}

  public function modelo()
	{
		return $this->belongsTo(ModeloRespuestosOnsite::class, 'modelo_respuestos_id');
	}
 
}
