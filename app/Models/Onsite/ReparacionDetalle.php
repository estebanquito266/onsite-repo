<?php

namespace App\Models\Onsite;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Traits\HasSorts;
use App\Models\Traits\ReparacionOnsiteTrait;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ReparacionDetalle extends Model
{
	public $campos_permitidos_para_ordenar = [];

	protected $table = "reparaciones_detalle";

	protected $fillable = [
		'reparacion_id',
		'transacciones_pendientes',
		'impresora_termica_scanner',
		'usuario_agentes',
		'usuario_agentes_red_local',
		'configuracion_impresora',
		'usuarios_sf2',
		'configuracion_pc_servidora',
		'conectividad_sf2_wut_dns_vnc',
		'carpeta_sf2_permisos',
		'tension_electrica',
		'tipo_conexion_local',
		'tipo_conexion_proveedor',
		'cableado',
		'cableado_cantidad_metros',
		'cableado_cantidad_fichas',
		'instalacion_cartel',
		'instalacion_cartel_luz',
		'insumos_banner',
		'insumos_folleteria',
		'insumos_rojos_impresora',
		'fotos_frente_local',
		'fotos_modem_enlace_switch',
		'fotos_terminal_red',
		
		'codigo_activo_nuevo1',
		'codigo_activo_retirado1',
		'codigo_activo_descripcion1',
		'codigo_activo_nuevo2',
		'codigo_activo_retirado2',
		'codigo_activo_descripcion2',
		'codigo_activo_nuevo3',
		'codigo_activo_retirado3',
		'codigo_activo_descripcion3',
		'codigo_activo_nuevo4',
		'codigo_activo_retirado4',
		'codigo_activo_descripcion4',
		'codigo_activo_nuevo5',
		'codigo_activo_retirado5',
		'codigo_activo_descripcion5',
		'codigo_activo_nuevo6',
		'codigo_activo_retirado6',
		'codigo_activo_descripcion6',
		'codigo_activo_nuevo7',
		'codigo_activo_retirado7',
		'codigo_activo_descripcion7',
		'codigo_activo_nuevo8',
		'codigo_activo_retirado8',
		'codigo_activo_descripcion8',
		'codigo_activo_nuevo9',
		'codigo_activo_retirado9',
		'codigo_activo_descripcion9',
		'codigo_activo_nuevo10',
		'codigo_activo_retirado10',
		'codigo_activo_descripcion10',
		'modem_3g_4g_sim_nuevo',
		'modem_3g_4g_sim_retirado',
		'cantidad_visitas',
	];

	
	

	// RELACIONES
	public function reparacion_onsite()
	{
		return $this->belongsTo('App\Models\Onsite\ReparacionOnsite', 'reparacion_id');
	}
	  
}
