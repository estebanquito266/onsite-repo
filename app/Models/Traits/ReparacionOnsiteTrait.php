<?php

namespace App\Models\Traits;

use App\Repositories\Onsite\EstadoOnsiteRepository;

trait ReparacionOnsiteTrait
{

  public function chequearEstadoCerrado()
  {
    $estado_onsite_repository = new EstadoOnsiteRepository;

    $estados_query = $estado_onsite_repository->getEstadosByCerradosByUserCompany(1);

    if (in_array($this->id_estado, $estados_query->get()->modelKeys())) {
      $this->fecha_cerrado = date('Y-m-d h:i:s');
    }

  }
}
