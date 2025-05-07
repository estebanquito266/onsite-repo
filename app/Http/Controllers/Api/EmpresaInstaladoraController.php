<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\Onsite\EmpresasInstaladorasServices;
use Illuminate\Support\Facades\Session;

class EmpresaInstaladoraController extends Controller
{
    public $bgh_company_id;
    protected $empresasInstaladorasServices;

    /**
     * Constructor de la clase EmpresaInstaladoraController.
     *
     * @param \App\Services\Onsite\EmpresasInstaladorasServices $empresasInstaladorasServices
     */
    public function __construct(EmpresasInstaladorasServices $empresasInstaladorasServices) {
        $this->bgh_company_id = 2;
        $this->empresasInstaladorasServices = $empresasInstaladorasServices;
    }

    /**
     * Obtener empresas instaladoras por ID de empresa.
     *
     * @param int $company_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getEmpresasInstaladoras($company_id)
    {
        Log::info('getEmpresasInstaladoras     ==============');
        Log::info('company_id '.$company_id);

        try {
            $empresas_instaladoras = $this->empresasInstaladorasServices->getEmpresasInstaladorasOnsite($company_id);
            Log::info($empresas_instaladoras);

            if (count($empresas_instaladoras) > 0) 
            {
              $respuesta = [
                'estado' => 'success',
                'codigo' => 200,
                'mensaje' => 'Empresas Instaladoras Onsite: obtenidas!',
                'empresas_instaladoras' => $empresas_instaladoras
              ];
            }else{
              $respuesta = [
                'estado' => 'error',
                'codigo' => 404,
                'mensaje' => 'No se han encontrado empresas instaladoras con el id de empresa '.$company_id
              ];
            }
        } catch (\Exception $e) {
          $respuesta = [
            'estado' => 'error',
            'codigo' => 500,
            'mensaje' => 'Ha ocurrido un error al obtener las empresas instaladoras.'
          ];
        }

        return response()->json($respuesta, $respuesta['codigo']);
    }

    /**
     * Obtener empresas instaladoras BGH.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getEmpresasInstaladorasBgh()
    {
        $company_id = $this->bgh_company_id;
        Log::info('getEmpresasInstaladorasBgh     ==============');
        Log::info('company_id '.$company_id);

        try {
            $empresas_instaladoras = $this->empresasInstaladorasServices->getEmpresasInstaladorasOnsite($company_id);
            Log::info($empresas_instaladoras);

            if (count($empresas_instaladoras) > 0) 
            {
              $respuesta = [
                'estado' => 'success',
                'codigo' => 200,
                'mensaje' => 'Empresas Instaladoras Onsite: obtenidas!',
                'empresas_instaladoras' => $empresas_instaladoras
              ];
            }else{
              $respuesta = [
                'estado' => 'error',
                'codigo' => 404,
                'mensaje' => 'No se han encontrado empresas instaladoras con el id de empresa '.$company_id
              ];
            }
        } catch (\Exception $e) {
          $respuesta = [
            'estado' => 'error',
            'codigo' => 500,
            'mensaje' => 'Ha ocurrido un error al obtener las empresas instaladoras.'
          ];
        }

        return response()->json($respuesta, $respuesta['codigo']);
    }
}
