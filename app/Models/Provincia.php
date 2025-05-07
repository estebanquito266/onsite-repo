<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Provincia extends Model
{
  protected $table = "provincias";
	
  protected $fillable = ['nombre', 'id_provincia_oca', 'id_provincia_correo_argentino', 'id_georef_ar', 'company_id'];
  
  public static function listado(){
    return self::select('id', 'nombre')
      ->orderBy('nombre', 'asc')
			->get();
	}	
	
  // RELACIONES
  public function localidades_onsite()
  {
      return $this->hasMany('App\Models\Onsite\LocalidadOnsite', 'id_provincia');
  }
}
