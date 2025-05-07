<?php

namespace App\Models\Onsite;

use Illuminate\Database\Eloquent\Model;

class NotaOnsite extends Model
{
  protected $table = "notas_onsite";

  protected $fillable = [
    'nota',
    'reparacion_onsite_id',
    'user_id',
    'created_at',
  ];

  public function usuario()
  {
    return $this->belongsTo('App\Models\User', 'user_id');
  }
}
