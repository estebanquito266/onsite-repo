<?php

namespace App\Models\Onsite;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadoSolicitudOnsite extends Model
{
    const APROBADO = 10;
    use HasFactory;

    protected $table = 'estados_solicitudes_onsite';

    protected $fillable = [
        'id',
        'company_id',
        'nombre',
        'plantilla_mail_cliente_id',
        'pendiente',
        'created_at',
        'updated_at',
    ];
}
