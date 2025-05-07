<?php
namespace App\Repositories\Onsite;

use Auth;
use App\Models\Onsite\TipoServicioOnsite;

class TipoServicioOnsiteRepository
{
  const SEGUIMIENTO_OBRA = 50;
  const PUESTA_MARCHA = 60;
  
  /**
   * Aplica los filtros y orden a la consulta
   */
  public function filtrar($filtros)
  {
    $query = TipoServicioOnsite::where('company_id', Auth::user()->companies->first()->id);

    if ($filtros) {
      foreach ($filtros as $key => $value) {
        $query->where($key, $value);
      }
    }

    return $query;
  }
}