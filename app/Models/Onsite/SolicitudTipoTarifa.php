<?php

namespace App\Models\Onsite;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudTipoTarifa extends Model
{
    use HasFactory;
    protected $table = 'solicitudes_tipos_tarifas';
    protected $fillable = [
        'company_id',
        'solicitud_tipo_id',
        'obra_id',
        'moneda',
        'version',
        'precio',
        'observaciones'
    ];

    public function solicitud_tipo()
    {
     return $this->belongsTo(SolicitudTipo::class, 'solicitud_tipo_id');
    }

}
