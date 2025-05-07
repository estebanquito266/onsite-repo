<?php
namespace App\Repositories\Onsite;

use Auth;
use App\Models\Onsite\EmpresaOnsite;

class EmpresaOnsiteRepository
{
  const PAGOFACIL = 2;

  /**
   * Aplica los filtros y orden a la consulta
   */
  public function filtrar($filtros)
  {
    if( Auth::user()->companies &&  Auth::user()->companies->first()){
    $query = EmpresaOnsite::where('company_id', Auth::user()->companies->first()->id);

    if ($filtros) {
      foreach ($filtros as $key => $value) {
        $query->where($key, $value);
      }
    }

    return $query;
  }
  return null;
  }

  public function requiereTipoConexionLocal()
  {
    return EmpresaOnsite::where('requiere_tipo_conexion_local', 1);
  }

}