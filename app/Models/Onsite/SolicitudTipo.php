<?php

namespace App\Models\Onsite;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudTipo extends Model
{
    use HasFactory;
    protected $table = 'solicitudes_tipos';

    protected $fillable = [
        'id',
        'nombre',
        'orden',
        'observaciones'
    ];

    public function company()
    {
        return $this->belongsTo('App\Models\Admin\Company');
    }

    public function solicitud_tipo_tarifa()
    {
        return $this->hasMany(SolicitudTipoTarifa::class, 'solicitud_tipo_id');
    }

    public function solicitud_tipo_tarifa_base()
    {
        return $this->hasMany(SolicitudTipoTarifaBase::class, 'solicitud_tipo_id');
    }
}
