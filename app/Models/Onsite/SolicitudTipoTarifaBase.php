<?php

namespace App\Models\Onsite;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudTipoTarifaBase extends Model
{
    use HasFactory;

    protected $table = 'solicitudes_tipos_tarifas_bases';
    protected $fillable = [
        'company_id',
        'solicitud_tipo_id',
        'moneda',
        'precio',
        'version',
        'precio',
        'observaciones'
    ];

    public function solicitud_tipo()
    {
     return $this->belongsTo(SolicitudTipo::class, 'solicitud_tipo_id');
    }
}
