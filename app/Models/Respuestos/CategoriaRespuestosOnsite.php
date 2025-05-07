<?php

namespace App\Models\Respuestos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriaRespuestosOnsite extends Model
{
    use HasFactory;

    protected $table = "categorias_respuestos_onsite";

  protected $fillable = [
    'id',
    'company_id',
    'nombre',
   
  ];

  public function modelo_respuestos_onsite()
  {
    return $this->hasMany(ModeloRespuestosOnsite::class);
  }

 
}
