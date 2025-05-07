<?php

namespace App\Models\Onsite;

use Illuminate\Database\Eloquent\Model;

class ReparacionOnsiteConfirmadasPorUser extends Model
{
  protected $table = "reparaciones_onsite_confirmadas_por_users";

  protected $fillable = [
    'reparacion_onsite_id',
    'user_id',
  ];
}
