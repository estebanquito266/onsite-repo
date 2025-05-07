<?php

namespace App\Models\Respuestos;

use App\Models\Onsite\EmpresaOnsite;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdenPedidoRespuestosOnsite extends Model
{
    use HasFactory;

    protected $table = "ordenes_pedido_respuestos_onsite";

    protected $fillable = [
        'id',
        'company_id',
        'user_id',
        'estado_id',
        'monto_dolar',
        'monto_euro',
        'monto_peso',
        'comentarios',        
        'empresa_onsite_id',
        'nombre_solicitante',
        'email_solicitante'
    ];

    public function user_company()
    {
      return $this->belongsToMany('App\Models\User', 'user_companies', 'company_id', 'user_id');
    }

    public function user()
    {
      return $this->belongsTo(User::class);
    }

    public function estado()
    {
      return $this->belongsTo(EstadoOrdenPedidoRespuestosOnsite::class);
    }

    public function detalle_pedido()
    {
      return $this->hasMany(DetalleOrdenPedidoRespuestosOnsite::class, 'orden_respuestos_id')->with('pieza');
    }

    public function empresa_onsite()
    {
      return $this->belongsTo(EmpresaOnsite::class);
    }


}

