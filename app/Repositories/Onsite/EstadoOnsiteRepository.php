<?php

namespace App\Repositories\Onsite;

use Auth;
use App\Models\Onsite\EstadoOnsite;
use Log;
use Session;

class EstadoOnsiteRepository
{
  // constantes de tipos de estado onsite
  const TIPO_ESTADO_DEFAULT = 1;
  const TIPO_ESTADO_COORDINAR = 2;
  const TIPO_ESTADO_NOTIFICAR = 3;
  const TIPO_ESTADO_PRESUPUESTO_PENDIENTE_DE_APROBACION = 20;

  const ESTADO_NUEVO = 1;
  const ESTADO_FACTURADO = 9;

  const NUEVA = 1;
  const BGH_A_COORDINAR = 28;

  /**
   * Devuelve la consulta de los estados activos
   *
   * @return EstadoOnsite $estadoOnsite
   */
  public function activo($value)
  {
    return EstadoOnsite::where('activo', $value)
      ->orWhere('id', 27);
  }

  /**
   * Aplica los filtros y orden a la consulta
   */
  public function filtrar($filtros)
  {
    $query = EstadoOnsite::where('company_id', Auth::user()->companies->first()->id);

    if ($filtros) {
      foreach ($filtros as $key => $value) {
        $query->where($key, $value);
      }
    }

    return $query;
  }

  /**
   * Devuelve el estado_onsite del estado corrdinado segun company del usuario logueado
   *
   * @return EstadoOnsite $estadoOnsite
   */
  public function getEstadoCoordinadaByUserCompany()
  {
    $query = EstadoOnsite::where('company_id', Auth::user()->companies->first()->id)
      ->where('tipo_estado_onsite_id', self::TIPO_ESTADO_COORDINAR);

    return $query;
  }

  /**
   * Devuelve el estado_onsite del estado notificada segun company del usuario logueado
   *
   * @return EstadoOnsite $estadoOnsite
   */
  public function getEstadoNotificadaByUserCompany()
  {
    $query = EstadoOnsite::where('company_id', Auth::user()->companies->first()->id)
      ->where('tipo_estado_onsite_id', self::TIPO_ESTADO_NOTIFICAR);

    return $query;
  }

  /**
   * Devuelve los estados cerrados de la company del usuario
   *
   * @return integer
   */
  public function getEstadosByCerradosByUserCompany($value)
  {
    $query = EstadoOnsite::where('company_id', Auth::user()->companies->first()->id)
      ->where('cerrado', $value);

    return $query;
  }

  public function listado($companyId)
  {
    return EstadoOnsite::select("id", "nombre")
      ->where('company_id', $companyId)
      ->orderBy('id', 'asc')
      ->get();
  }

  public function getEstadosByParams($params)
  {
    $estados = EstadoOnsite::select("id", "nombre")
      ->where('company_id', $params['company_id']);

      
    if (isset($params['activo'])) {
      $estados = $estados->where('activo', 1);
    }

    if (isset($params['cerrado'])) {
      $estados = $estados->where('cerrado', 1);
    }    

    $estados = $estados->orderBy('id', 'asc')
      ->get();

    return $estados;
  }

  public function findEstadosOnsiteAll()
  {
    $userCompanyId = Session::get('userCompanyIdDefault');
    $estadosOnsite = EstadoOnsite::where('company_id', $userCompanyId)->orderBy('nombre', 'ASC')->get();

    return $estadosOnsite;
  }

  public function findEstadoOnsite($idEstadoOnsite)
  {
    $userCompanyId = Session::get('userCompanyIdDefault');
    $estadosOnsite = EstadoOnsite::where('company_id', $userCompanyId)->find($idEstadoOnsite);

    return $estadosOnsite;
  }

  public function activos()
  {
    return EstadoOnsite::where('activo', 1);
  }

  public function getEstadoOnsite($idEstadoOnsite)
  {
    $estadosOnsite = EstadoOnsite::where('id', $idEstadoOnsite)->first();

    return $estadosOnsite;
  }
}
