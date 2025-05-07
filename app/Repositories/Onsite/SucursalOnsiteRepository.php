<?php

namespace App\Repositories\Onsite;

use Auth;
use App\Models\Onsite\SucursalOnsite;

class SucursalOnsiteRepository
{
  /**
   * Aplica los filtros y orden a la consulta
   */
  public function filtrar($filtros)
  {
    $query = SucursalOnsite::where('company_id', Auth::user()->companies->first()->id);

    if ($filtros) {
      foreach ($filtros as $key => $value) {
        $query->where($key, $value);
      }
    }

    return $query;
  }
}
