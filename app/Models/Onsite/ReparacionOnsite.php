<?php

namespace App\Models\Onsite;

use App\Models\Ticket\Ticket;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Traits\HasSorts;
use App\Models\Traits\ReparacionOnsiteTrait;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Auth;

class ReparacionOnsite extends Model
{
	use HasFactory;

	use HasSorts;

	use ReparacionOnsiteTrait;

	public $campos_permitidos_para_ordenar = [
		'clave',
		'tarea',
		'fecha_vencimiento',
		'id_estado'
	];

	protected $table = "reparaciones_onsite";

	protected $fillable = [
		'clave',
		'company_id',
		'id_empresa_onsite',
		'sucursal_onsite_id',
		'id_terminal',
		'tarea',
		'tarea_detalle',
		'id_tipo_servicio',
		'id_estado',
		'fecha_ingreso',
		'observacion_ubicacion',
		'nro_caja',
		'informe_tecnico',
		'prioridad',
		'instalacion_buzon',
		'cantidad_horas_trabajo',
		'requiere_nueva_visita',
		'firma_cliente',
		'aclaracion_cliente',
		'firma_tecnico',
		'aclaracion_tecnico',
		'id_tecnico_asignado',
		'fecha_coordinada',
		'ventana_horaria_coordinada',
		'fecha_registracion_coordinacion',
		'fecha_notificado',
		'fecha_vencimiento',

		'fecha_1_vencimiento',
		'fecha_1_visita',

		'fecha_cerrado',
		'sla_status',
		'sla_justificado',
		'monto',
		'monto_extra',
		'liquidado_proveedor',
		'nro_factura_proveedor',
		'visible_cliente',
		'chequeado_cliente',
		'doc_link1',
		'doc_link2',
		'doc_link3',
		'problema_resuelto',
		'usuario_id',
		'nota_cliente',
		'observaciones_internas',

		'sistema_onsite_id',
		'reparacion_onsite_puesta_marcha_id',
		'solicitud_tipo_id'

	];

	public function cliente()
	{
		return $this->belongsTo(EmpresaOnsite::class, 'id_empresa_onsite');
	}

	// RELACIONES
	public function estado_onsite()
	{
		return $this->belongsTo('App\Models\Onsite\EstadoOnsite', 'id_estado');
	}

	public function terminal_onsite()
	{
		return $this->belongsTo('App\Models\Onsite\TerminalOnsite', 'id_terminal');
	}

	public function tipo_servicio_onsite()
	{
		return $this->belongsTo('App\Models\Onsite\TipoServicioOnsite', 'id_tipo_servicio');
	}

	public function empresa_onsite()
	{
		return $this->belongsTo('App\Models\Onsite\EmpresaOnsite', 'id_empresa_onsite');
	}

	public function sucursal_onsite()
	{
		return $this->belongsTo('App\Models\Onsite\SucursalOnsite', 'sucursal_onsite_id');
	}

	public function sistema_onsite()
	{
		return $this->belongsTo(SistemaOnsite::class, 'sistema_onsite_id')
			/* ->with('obra_onsite') */;
	}

	public function historial_estados_onsite()
	{
		return $this->hasMany('App\Models\Onsite\HistorialEstadoOnsite', 'id_reparacion');
	}

	public function imagenes()
	{
		return $this->hasMany(ImagenOnsite::class, 'reparacion_onsite_id');
	}

	public function tecnicoAsignado()
	{
		return $this->belongsTo(User::class, 'id_tecnico_asignado');
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'usuario_id');
	}

	public function imagenesOnsite()
	{
		return $this->hasMany(ImagenOnsite::class);
	}

	public function imagenesOnsiteCanierias()
	{
		return $this->hasMany(ImagenOnsite::class)->where('imagenes_onsite.tipo_imagen_onsite_id', ImagenOnsite::CORTE_CANERIA);
	}

	public function imagenesOnsiteAnomalias()
	{
		return $this->hasMany(ImagenOnsite::class)->where('imagenes_onsite.tipo_imagen_onsite_id', ImagenOnsite::ANOMALIAS);
	}

	public function imagenesOnsitePresurizacion()
	{
		return $this->hasMany(ImagenOnsite::class)->where('imagenes_onsite.tipo_imagen_onsite_id', ImagenOnsite::PRESURIZACION);
	}

	public function imagenesOnsiteServicioFirmado()
	{
		return $this->hasMany(ImagenOnsite::class)->where('imagenes_onsite.tipo_imagen_onsite_id', ImagenOnsite::COMPROBANTE_SERVICIO_FIRMADO);
	}

	public function imagenesOnsiteTrabajoRealizado()
	{
		return $this->hasMany(ImagenOnsite::class)->where('imagenes_onsite.tipo_imagen_onsite_id', ImagenOnsite::TIPO_TRABAJO);
	}

	public function usuarios_que_confirmaron()
	{
		return $this->belongsToMany('App\Models\User', 'reparaciones_onsite_confirmadas_por_users', 'user_id', 'reparacion_onsite_id');
	}

	public function confirmada()
	{
		return $this->belongsToMany('App\Models\User', 'reparaciones_onsite_confirmadas_por_users', 'reparacion_onsite_id', 'user_id')
			->wherePivot('user_id', Auth::user()->id);
	}

	public function reparacion_checklist_onsite()
	{
		return $this->hasOne('App\Models\Onsite\ReparacionChecklistOnsite', 'reparacion_onsite_id');
	}


	public function solicitud_tipo()
	{
		return $this->belongsTo(SolicitudTipo::class, 'solicitud_tipo_id');
	}

	public function reparacion_detalle()
	{
		return $this->hasOne(ReparacionDetalle::class, 'reparacion_id', 'id')->withDefault();
	}

	public function solicitud()
	{
		return $this->belongsToMany(SolicitudOnsite::class, 'solicitud_visitas', 'visita_id', 'solicitud_id');
	}

	// Sobrescribir el método getAttribute para interceptar la obtención de atributos e incrustrar los atributos de reparacion_detalle
	public function getAttribute($key)
	{
		$value = parent::getAttribute($key);

		// Si el atributo no existe en los atributos de reparaciones_onsite,
		// intentamos cargar la relación reparacion_detalle
		if ($value === null && !array_key_exists($key, $this->attributes)) {
			$this->load('reparacion_detalle');

			// Verificamos si el atributo está en el reparacion_detalle cargado
			if ($this->reparacion_detalle && $this->reparacion_detalle->getAttribute($key)) {
				return $this->reparacion_detalle->getAttribute($key);
			}
		}

		return $value;
	}

	public function visitas(): HasMany
	{
		return $this->hasMany(ReparacionVisita::class, 'reparacion_id');
	}

	public function primer_visita(): HasOne
	{
		return $this->hasOne(ReparacionVisita::class, 'reparacion_id');
	}

	public function tickets()
	{
		return $this->hasMany(Ticket::class, 'reparacion_id');
	}


	/*
   public function setAttribute($key, $value)
   {
	   // Si el atributo no existe en los atributos de reparaciones_onsite,
	   // intentamos cargar la relación reparacion_detalle
	   if (!array_key_exists($key, $this->attributes)) {
		   $this->load('reparacion_detalle');

		   // Verificamos si el atributo está en el reparacion_detalle cargado
		   if ($this->reparacion_detalle && $this->reparacion_detalle->setAttribute($key, $value)) {
			   return true;
		   }
	   }

	   parent::setAttribute($key, $value);
   }
	*/
}
