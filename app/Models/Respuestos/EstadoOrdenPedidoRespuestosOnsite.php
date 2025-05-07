<?php

namespace App\Models\Respuestos;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadoOrdenPedidoRespuestosOnsite extends Model
{
    const EN_REVISION = 1;
    const APROBADA = 2;
    const RECHAZADA = 3;
    const EN_PROCESO = 4;
    const EN_COTIZACION = 5;


    use HasFactory;

    protected $table = "estados_ordenes_pedido_respuestos_onsite";

    protected $fillable = [
        'id',
        'company_id',
        'nombre',

    ];
}
