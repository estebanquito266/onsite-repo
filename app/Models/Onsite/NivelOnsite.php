<?php

namespace App\Models\Onsite;

use Illuminate\Database\Eloquent\Model;
use DB;

class NivelOnsite extends Model
{
  protected $table = "niveles_onsite";
	
	protected $fillable = [
    'nombre',
    'company_id',
  ];
	
	public static function listar(){
		return NivelOnsite::select('niveles_onsite.*')
			->orderBy('nombre', 'asc')
			->get();
	}
	
	public static function listado(){
		return NivelOnsite::select("id", "nombre")
			->orderBy('nombre', 'asc')
			->pluck('nombre', 'id');
	}	
}
