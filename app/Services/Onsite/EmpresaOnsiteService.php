<?php

namespace App\Services\Onsite;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use App\Models\Onsite\EmpresaOnsite;
use Log;

class EmpresaOnsiteService
{
  protected $userService;

  public function __construct(UserService $userService)
  {
    $this->userService = $userService;
  }

  public function store($arrayEmpresaOnsite)
  {
    $empresaOnsite = EmpresaOnsite::create($arrayEmpresaOnsite);

    return $empresaOnsite;
  }
  public function findEmpresaOnsite($empresaOnsiteId)
  {

    $empresaOnsite = EmpresaOnsite::find($empresaOnsiteId);

    return $empresaOnsite;
  }

  public static function listado($userCompanyId)
  {
    $empresa = EmpresaOnsite::orderBy('nombre', 'asc')
      ->where('company_id', $userCompanyId)
      ->orderBy('nombre', 'asc')
      ->pluck('nombre', 'id');

    return $empresa;
  }

  public static function listadoAll($userCompanyId, $includeEmpresa = null, $excludeEmpresa = null)
  {
    $empresa = EmpresaOnsite::select('nombre', 'id')
      ->where('company_id', $userCompanyId);

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

        $empresa = $empresa->whereIn('id' , $empresasUserArray );
      }
    }


    if ($includeEmpresa) {
      $empresa = $empresa->whereRaw('id IN (' . $includeEmpresa . ')');
    }
    if ($excludeEmpresa) {
      $empresa = $empresa->whereRaw('id NOT IN (' . $excludeEmpresa . ')');
    }

    $empresa = $empresa->orderBy('nombre', 'asc')
      ->get();

    return $empresa;
  }

  public function getEmpresaOnsitePorInstaladora()
  {

    $empresaOnsite = EmpresaOnsite::with('empresa_instaladora_onsite')

      ->whereHas('empresa_instaladora_empresa_onsite', function ($query) {

        $idUser = session()->get('idUser');
        $user = $this->userService->findUser($idUser);
        if (count($user->empresa_instaladora) > 0) {
          $idEmpresaInstaladora = $user->empresa_instaladora[0]->id;
        } else $idEmpresaInstaladora = 1;

        $query->where('empresa_instaladora_id', $idEmpresaInstaladora);
      });

    return $empresaOnsite->get();
  }

  public function getEmpresaOnsitePorInstaladoraId($idEmpresaInstaladora)
  {
    Session()->put('idEmpresaInstaladora', $idEmpresaInstaladora);

    $empresaOnsite = EmpresaOnsite::with('empresa_instaladora_onsite')

      ->whereHas('empresa_instaladora_empresa_onsite', function ($query) {
        $idEmpresaInstaladora = Session()->get('idEmpresaInstaladora');
        $query->where('empresa_instaladora_id', $idEmpresaInstaladora);
        Session()->forget('idEmpresaInstaladora');
      });


    return $empresaOnsite->get();
  }

  public function findEmpresaClave($clave)
  {
    $empresaOnsite = EmpresaOnsite::where('clave', $clave)->first();
    return $empresaOnsite;
  }


  public function findEmpresaOnsiteTipoTerminal($idEmpresaOnsite)
  {
    $company_id = Session::get('userCompanyIdDefault');

    $empresaOnsite = EmpresaOnsite::select('tipo_terminales', 'company_id')
      ->where('company_id', $company_id)
      ->find($idEmpresaOnsite)
      ->first();

    return $empresaOnsite;
  }

  public function getEmpresaById($id)
  {
    $empresaOnsite = EmpresaOnsite::where('id', $id)->first();
    return $empresaOnsite;
  }


  public static function listadoAllBgh($userCompanyId)
  {
    $empresas = EmpresaOnsite::select('nombre', 'id')
      ->where('company_id', $userCompanyId)
      ->orderBy('nombre', 'asc')
      ->get();
    return $empresas;
  }

}
