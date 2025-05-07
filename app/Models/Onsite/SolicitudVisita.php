<?php

namespace App\Models\Onsite;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudVisita extends Model
{
    use HasFactory;
    protected $table = 'solicitud_visitas';

    protected $fillable = [
        'id',
        'visita_id',
        'solicitud_id'
    ];

}
