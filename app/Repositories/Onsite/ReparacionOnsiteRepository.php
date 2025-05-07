<?php

namespace App\Repositories\Onsite;

use App\Models\Onsite\EstadoOnsite;
use Auth;
use App\Models\Onsite\ReparacionOnsite;
use App\Repositories\Onsite\EstadoOnsiteRepository;
use Log;

class ReparacionOnsiteRepository
{
  protected $estado_onsite_repository;

  public function __construct(
    EstadoOnsiteRepository $estado_onsite_repository
  ) {
    $this->estado_onsite_repository = $estado_onsite_repository;
  }



  /**
   * Aplica los filtros y orden a la consulta
   */
  public function filtrar($filtros)
  {

    $query = ReparacionOnsite::where('company_id', Auth::user()->companies->first()->id)
      ->where('created_at', '>', '2022-05-01');

    // Se fija si se envio el filtro 'activas'
    if (array_key_exists('activas', $filtros)) {
      $estados_query = $this->estado_onsite_repository->activo($filtros['activas']);
      $query->whereIn('id_estado', $estados_query->get()->modelKeys());
      // quita el filtro ya utilizado
      unset($filtros['activas']);
    }

    // Se fija si se envio el filtro 'cerradas'
    if (array_key_exists('cerradas', $filtros)) {
      $estados_query = $this->estado_onsite_repository->getEstadosByCerradosByUserCompany($filtros['cerradas']);
      $query->whereIn('id_estado', $estados_query->get()->modelKeys());
      // quita el filtro ya utilizado
      unset($filtros['cerradas']);
    }

    if ($filtros) {
      foreach ($filtros as $key => $value) {
        if (is_array($value)) {
          $query->whereIn($key, $value);
        } else {
          $query->where($key, $value);
        }
      }
    }

    $query->with('historial_estados_onsite.usuario');
    $query->with('sucursal_onsite.sistemas_onsite.unidades_exteriores.imagenes_unidad_exterior');
    $query->with('sucursal_onsite.sistemas_onsite.unidades_interiores.imagenes_unidad_interior');
    $query->with('empresa_onsite.obra_onsite');
    $query->with('imagenesOnsite');
    $query->with('imagenesOnsiteCanierias');
    $query->with('sistema_onsite.obra_onsite');
    $query->with('sistema_onsite.unidades_exteriores.imagenes_unidad_exterior');
    $query->with('sistema_onsite.unidades_interiores.imagenes_unidad_interior');



    return $query;
  }

  /**
   * Guarda la imagen de la firma
   *
   * @param [type] $id_reparacion_onsite
   * @param [type] $firma
   * @param [type] $tipo
   * @return void
   */
  public function guardarFirmaOnsite(ReparacionOnsite $reparacion_onsite, $firma, $tipo)
  {
    // Si la firma es la misma no hace nada y la devuelve
    if ($reparacion_onsite->firma_cliente == $firma) {
      return $firma;
    }

    // Si la firma no es igual guarda la nueva firma
    $data_uri = $firma;

    $encoded_image = explode(",", $data_uri)[1];
    $decoded_image = base64_decode($encoded_image);

    $file_name = 'firmaOnsite_' . $reparacion_onsite->id . '_' . $tipo . '.jpg';

    $url_file = 'firmas/' . $file_name;

    //file_put_contents($url_file, $decoded_image);

    return $file_name;
  }

  /**
   * Devuelve la query de los estados de reparaciones pendientes
   *
   * @return void
   */
  public static function pendientes()
  {
    return ReparacionOnsite::whereIn('id_estado', [1, 2, 3, 7]);
  }

  public static function pendientesPorEmpresa($empresa_id)
  {
    $query = ReparacionOnsite::whereIn('id_estado', [EstadoOnsite::NUEVA, EstadoOnsite::COORDINADA, EstadoOnsite::PENDIENTE_INFO, EstadoOnsite::PENDIENTE_HARDWARE])
      ->where('id_empresa_onsite', $empresa_id);

    return $query;
  }

  public static function activasPorEmpresa($empresa_id)
  {
    $estadosActivos = EstadoOnsite::where('activo', 1)->select('id')->get()->toArray();

    $query = ReparacionOnsite::whereIn('id_estado', $estadosActivos)
      ->where('id_empresa_onsite', $empresa_id);

    return $query;
  }

  public static function cerradasPorEmpresa($empresa_id)
  {
    $estadosCerrados = EstadoOnsite::where('cerrado', 1)->select('id')->get()->toArray();

    $query = ReparacionOnsite::whereIn('id_estado', $estadosCerrados)
      ->where('id_empresa_onsite', $empresa_id);

    return $query;
  }
}
