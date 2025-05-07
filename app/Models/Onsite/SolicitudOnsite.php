<?php

namespace App\Models\Onsite;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class SolicitudOnsite extends Model
{
	protected $table = "solicitudes_onsite";

	protected $fillable = ['company_id', 
	'obra_onsite_id', 
	'estado_solicitud_onsite_id', 
	'terminos_condiciones', 
	'observaciones_internas', 
	'nota_cliente', 
	'comentarios', 
	'created_at',
	'solicitud_tipo_id',
	'sistema_onsite_id',
	'user_id',
	'empresa_instaladora_id'
];

	
	public function obra_onsite()
	{
		return $this->belongsTo('App\Models\Onsite\ObraOnsite', 'obra_onsite_id');
	}

	public function sistema_onsite()
	{
		return $this->belongsTo(SistemaOnsite::class, 'sistema_onsite_id');
	}

	public function estado_solicitud_onsite()
	{
		return $this->belongsTo('App\Models\Onsite\EstadoSolicitudOnsite', 'estado_solicitud_onsite_id');
	}

	public function tipo()
	{
		return $this->belongsTo(SolicitudTipo::class, 'solicitud_tipo_id');
	}

    public function visitas()
    {
        return $this->belongsToMany(ReparacionOnsite::class, 'solicitud_visitas', 'solicitud_id', 'visita_id');
    }	
}
