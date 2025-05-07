<?php

namespace App\Models\Onsite;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImagenObraOnsite extends Model
{
    use HasFactory;

    protected $table = "imagenes_obras_onsite";
    
    protected $fillable = [
        'company_id',
        'obra_onsite_id',
        'tipo_imagen_onsite_id',
        'archivo',
        'descripcion'
    ];

    public function company()
  {
    return $this->belongsTo('App\Models\Company', 'company_id');
  }
  public function tipo_imagen()
  {
    return $this->belongsTo('App\Models\Onsite\TipoImagenOnsite', 'tipo_imagen_onsite_id');
  }	
}
