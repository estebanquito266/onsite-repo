<?php

namespace App\Repositories\Onsite;

use Auth;
use App\Models\Onsite\ReparacionChecklistOnsite;

class ReparacionChecklistOnsiteRepository
{
  /**
   * Aplica los filtros y orden a la consulta
   */
  public function filtrar($filtros)
  {
    $query = ReparacionChecklistOnsite::where('company_id', Auth::user()->companies->first()->id);

    if ($filtros) {
      foreach ($filtros as $key => $value) {
        $query->where($key, $value);
      }
    }

    return $query;
  }

  /**
   * Crea o actualiza el registro
   *
   * @param Request $request
   * @return void
   */
  public function createUpdate($data)
  {
    unset($data['id']);
    unset($data['created_at']);
    unset($data['updated_at']);

    
    $reparacion_checklist_onsite = ReparacionChecklistOnsite::updateOrCreate(
      ['reparacion_onsite_id' => (int) $data['reparacion_onsite_id']],
      $data
    );

    return  $reparacion_checklist_onsite;
  }
}
