<?php

namespace App\Repositories\Onsite;

//use Auth;
use App\Models\Onsite\HistorialEstadoOnsite;
use App\Models\Onsite\HistorialEstadoNotaOnsite;
use App\Models\Onsite\ReparacionOnsite;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Session;

class HistorialEstadoOnsiteRepository
{
  public function crearHistorial(ReparacionOnsite $reparacion_onsite, $user_id, $observacion)
  {
    $company_id = Auth::user()->companies->first()->id;
    $user_id = Auth::user()->id;

    $fecha_hora_hoy = date('Y-m-d H:i:s');

    $company_id = Auth::user()->companies->first()->id;

    $nuevo_historial_estado_onsite = array(
      'id_reparacion' => $reparacion_onsite->id,
      'id_estado' => $reparacion_onsite->id_estado,
      'fecha' => $fecha_hora_hoy,
      'observacion' => $observacion,
      'id_usuario' => $user_id,
      'company_id' => $company_id,
      'visible' => true,
    );

    HistorialEstadoOnsite::create($nuevo_historial_estado_onsite);
  }

  /**
   * Devuelve la query que lista y filtra historiales de estado onsite
   *
   * @param [array] $params
   * @return [Builder] $query
   */

  public static function listar($params)
  {

    $consulta = HistorialEstadoOnsite::select('historial_estados_onsite.*')
      ->where('historial_estados_onsite.company_id', $params['userCompanyId']);



    if (empty($params['visibilidad'])) {
      $consulta->join('historial_estados_onsite_visible_por_user', 'historial_estados_onsite_visible_por_user.historial_estados_onsite_id', '=', 'historial_estados_onsite.id')
        ->where("historial_estados_onsite_visible_por_user.users_id", Auth::user()->id)
        ->where('historial_estados_onsite.id_usuario', '<>', Auth::user()->id)
        ->whereHas('estado_onsite', function (Builder $query) {
          $query->where('tipo_estado_onsite_id', '<>', 10);
        });
    }

    if (!empty($params['texto'])) {
      $consulta =   $consulta->whereRaw(" CONCAT( historial_estados_onsite.id_reparacion , ' ', historial_estados_onsite.fecha , ' ', historial_estados_onsite.observacion ) like '%" . $params['texto'] . "%'");
    }

    if (!empty($idReparacion)) {
      $reparacion_id = $params['idReparacion'];

      $consulta->join('reparaciones_onsite', 'historial_estados_onsite.id_reparacion', '=', 'reparaciones_onsite.id');
      $consulta->where(function ($q) use ($reparacion_id) {
        // Busco por id de reparacion
        $q->where(function ($query) use ($reparacion_id) {
          $query->where("historial_estados_onsite.id_reparacion", $reparacion_id);
        });
        // O busco por clave en la tabla reparaciones_onsite
        $q->orWhere(function ($query) use ($reparacion_id) {
          $query->where('reparaciones_onsite.clave', $reparacion_id);
        });
      });
    }

    if (!empty($params['idEstado'])) {
      $consulta =   $consulta->where("historial_estados_onsite.id_estado", $params['idEstado']);
    }

    if (!empty($params['idUsuario'])) {
      $consulta =   $consulta->where("historial_estados_onsite.id_usuario", $params['idUsuario']);
    }

    $consulta->orderBy('historial_estados_onsite.id', 'desc');

    if ($params['tomar'])
      return $consulta->skip($params['saltear'])->take($params['tomar'])->get();
    else
      return $consulta->paginate(100);
  }

  /**
   * Devuelve el historial de estados onsite de una reparacion onsite
   *
   * @param [int] $idReparacion
   * @return query
   */
  public function getHistorialPorReparacionOnsite($idReparacion)
  {
    $historialEstadosOnsite = HistorialEstadoOnsite::where("id_reparacion", $idReparacion)
      ->orderBy('fecha', 'desc')
      ->with(['estado_onsite', 'usuario']);

    return $historialEstadosOnsite;
  }

  public function findHistorialEstadoOnsiteAll()
  {

    $company_id = Session::get('userCompanyIdDefault');

    $empresas_user = $this->getEmpresasUser();

    $historialEstadosOnsite = HistorialEstadoOnsite::where('company_id', $company_id);

    if ($empresas_user) {
      $historialEstadosOnsite =  $historialEstadosOnsite->whereHas('reparacion_onsite', function ($query) use($empresas_user){
        $query->whereIn('id_empresa_onsite', $empresas_user);
      });
    }

    $historialEstadosOnsite =  $historialEstadosOnsite->orderBy('id', 'desc')
      ->paginate(100);

    return $historialEstadosOnsite;
  }

  public function getEmpresasUser()
  {
    $empresasUserArray = null;
    $perfilAdmin = false;

    if (Session::has('perfilAdmin') && Session::get('perfilAdmin')) {
      $perfilAdmin = true;
    }

    if (!$perfilAdmin) {
      $empresasUserArray = null;

      if (Auth::user()) {
        $empresasUser = Auth::user()->empresas_onsite;
        foreach ($empresasUser as $empresaUser) {
          $empresasUserArray[] = $empresaUser->id;
        }
      }
    }

    return $empresasUserArray;
  }
}
