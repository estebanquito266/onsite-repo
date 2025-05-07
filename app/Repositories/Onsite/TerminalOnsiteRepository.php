<?php

namespace App\Repositories\Onsite;

use Auth;
use App\Models\Onsite\TerminalOnsite;

class TerminalOnsiteRepository
{
  /**
   * Aplica los filtros y orden a la consulta
   */
  public function filtrar($filtros)
  {
    $query = TerminalOnsite::where('company_id', Auth::user()->companies->first()->id);

    if ($filtros) {
      foreach ($filtros as $key => $value) {
        $query->where($key, $value);
      }
    }

    return $query;
  }
}
