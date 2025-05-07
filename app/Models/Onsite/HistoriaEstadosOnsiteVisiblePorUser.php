<?php

namespace App\Models\Onsite;

use Illuminate\Database\Eloquent\Model;

class HistoriaEstadosOnsiteVisiblePorUser extends Model
{
  protected $table = "historial_estados_onsite_visible_por_user";

  protected $fillable = [
    'historial_estados_onsite_id',
    'users_id',
  ];

}
