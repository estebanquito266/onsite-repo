<?php

namespace App\Models\Respuestos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrecioPiezaRepuestoOnsite extends Model
{
    use HasFactory;
    protected $table = 'precios_piezas_repuestos';
    protected $fillable = [
        'id',
        'piezas_respuestos_onsite_id',
        'spare_parts_code',
        'precio_fob',
        'version',
        'company_id',
        'vencimiento_precio'
    ];

    public function company()
	{
		return $this->belongsTo('App\Models\Admin\Company');
	}

    public function pieza()
	{
		return $this->belongsTo(PiezaRespuestosOnsite::class, 'piezas_respuestos_onsite_id');
	}
  
}