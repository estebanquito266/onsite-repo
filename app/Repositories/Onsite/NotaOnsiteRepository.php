<?php

namespace App\Repositories\Onsite;

use App\Models\Onsite\NotaOnsite;

class NotaOnsiteRepository
{

  /**
   * Aplica los filtros y orden a la consulta
   */
  public function getNotasByReparacion($reparacion_id)
  {
    $query = NotaOnsite::where('reparacion_onsite_id', $reparacion_id)
      ->orderBy('created_at', 'desc')
      ->with(['usuario'])
      ->get();

    return $query;
  }
}
