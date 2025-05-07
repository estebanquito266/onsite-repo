<?php

namespace App\Models\Onsite;

use Illuminate\Database\Eloquent\Model;
use DB;

class SlaOnsite extends Model
{
    protected $primaryKey = "codigo";
	
	public $incrementing = false;
	
    protected $table = "sla_onsite";
	
	protected $fillable = [
    'codigo',
    'id_tipo_servicio',
    'id_nivel',
    'horas',
    'company_id',
  ];	

	public static function listar( $texto , $idTipoServicio, $idNivel , $saltear, $tomar ){
		
		if( !empty($texto) || !empty($idTipoServicio) || !empty($idNivel) ){
			$consulta = self::table('sla_onsite')					
				->join('tipos_servicios_onsite', 'tipos_servicios_onsite.id', '=', 'sla_onsite.id_tipo_servicio') 
				->join('niveles_onsite', 'niveles_onsite.id', '=', 'sla_onsite.id_nivel') 
							
				->select('sla_onsite.*' )				
				->selectRaw('tipos_servicios_onsite.nombre as nombretiposervicio' ) 
				->selectRaw('niveles_onsite.nombre as nombrenivel' ) 
								
				//para forzar acá la clausula Where
				->whereRaw(" 1 ") 
				//si texto esta vacío -> fuerzo 1, si no -> agrego clausula
				->whereRaw( empty($texto)?" 1 ":" CONCAT( sla_onsite.id , ' ', sla_onsite.codigo, ' ', sla_onsite.horas ) like '%$texto%'")				
				
				->whereRaw( empty($idTipoServicio)?"1":" sla_onsite.id_tipo_servicio = $idTipoServicio ") 
				->whereRaw( empty($idNivel)?"1":" sla_onsite.id_nivel = $idNivel ") 
								
				->orderBy('sla_onsite.codigo', 'asc');
				
			if($tomar)	
				return $consulta->skip($saltear)->take($tomar)->get();
			else
				return $consulta->paginate(100);				
		}		
		else{
			$consulta = self::table('sla_onsite')		
				->join('tipos_servicios_onsite', 'tipos_servicios_onsite.id', '=', 'sla_onsite.id_tipo_servicio') 
				->join('niveles_onsite', 'niveles_onsite.id', '=', 'sla_onsite.id_nivel') 
								
				->select('sla_onsite.*')				
				->selectRaw('tipos_servicios_onsite.nombre as nombretiposervicio' ) 
				->selectRaw('niveles_onsite.nombre as nombrenivel' ) 
								
				->orderBy('sla_onsite.codigo', 'asc');	
				
			if($tomar)	
				return $consulta->skip($saltear)->take($tomar)->get();
			else
				return $consulta->paginate(100);		
		}	
	}	
	
	public static function listado(){
		return SlaOnsite::join('tipos_servicios_onsite', 'tipos_servicios_onsite.id', '=', 'sla_onsite.id_tipo_servicio') 
			->join('niveles_onsite', 'niveles_onsite.id', '=', 'sla_onsite.id_nivel')
			->selectRaw(" CONCAT(sla_onsite.codigo,'-',tipos_servicios_onsite.nombre,'-',niveles_onsite.nombre) as nombre, codigo ")
			->orderBy('codigo', 'asc')
			->pluck('nombre','codigo');
	}		
	
	public static function buscar($codigo ){
		return SlaOnsite::select("sla_onsite.codigo", "sla_onsite.id_tipo_servicio", "sla_onsite.id_nivel", "sla_onsite.horas")			
			->where("sla_onsite.codigo", "=", $codigo )			
			->first();
	}		
	
	public static function buscarSla($idTipoServicio, $idNivel ){
		return SlaOnsite::select("sla_onsite.*")			
			->where("sla_onsite.id_tipo_servicio", "=", $idTipoServicio )			
			->where("sla_onsite.id_nivel", "=", $idNivel )			
			->first();
	}			
	
}
