<?php

namespace App\Models\Onsite;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudBoucher extends Model
{
    use HasFactory;

    protected $table = 'solicitudes_bouchers';
    protected $fillable = [
        'id',
        'company_id',
        'obra_id',
        'solicitud_id',
        'solicitud_boucher_tipo_id',
        'solicitud_tarifa_id',
        'codigo',
        'precio',
        'consumido',
        'sistema_id_consumido',
        'pendiente_imputacion',
        'fecha_expira',
        'observaciones',

    ];

    public function obra_onsite()
    {
        return $this->belongsTo(ObraOnsite::class, 'obra_id');
    }

    public function tipo_boucher()
    {
        return $this->belongsTo(SolicitudBoucherTipo::class, 'solicitud_boucher_tipo_id');
    }

    public function sistema_consumido()
    {
        return $this->belongsTo(SistemaOnsite::class, 'sistema_id_consumido');
    }
}
