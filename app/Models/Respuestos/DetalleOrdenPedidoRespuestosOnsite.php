<?php


namespace App\Models\Respuestos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleOrdenPedidoRespuestosOnsite extends Model
{
  use HasFactory;

  protected $table = "detalles_respuestos_onsite";

  protected $fillable = [
    'id',
    'company_id',
    'orden_respuestos_id',
    'categoria_respuestos_id',
    'modelo_respuestos_id',
    'pieza_respuestos_id',

    'cantidad',
    'precio_fob',
    'precio_total',
    'precio_neto'

  ];

  public function categoria()
  {
    return $this->belongsTo(CategoriaRespuestosOnsite::class, 'categoria_respuestos_id');
  }

  public function modelo()
  {
    return $this->belongsTo(ModeloRespuestosOnsite::class, 'modelo_respuestos_id');
  }

  public function pieza()
  {
    return $this->belongsTo(PiezaRespuestosOnsite::class, 'pieza_respuestos_id');
  }

  public function orden_pedido()
  {
    return $this->belongsTo(OrdenPedidoRespuestosOnsite::class, 'orden_respuestos_id');
  }
}
