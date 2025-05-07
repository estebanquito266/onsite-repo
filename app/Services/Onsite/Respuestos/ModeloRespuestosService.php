<?php

namespace App\Services\Onsite\Respuestos;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

use App\Models\Respuestos\ModeloRespuestosOnsite;
use Log;

class ModeloRespuestosService
{
    protected $userCompanyId;

    public function __construct()
    {
        $this->userCompanyId =  session()->get('userCompanyIdDefault');
    }
    public function getDataList()
    {
    }

    public function create()
    {
    }

    public function show()
    {
    }

    public function store()
    {
    }

    public function update()
    {
    }

    public function destroy($id)
    {
    }

    public function filtrar(Request $request)
    {
    }
    public function generarCsv($texto, $idReparacion, $idEstado, $idUsuario, $visibilidad)
    {
    }

    public function getModelosRespuestos()
    {
        $modelosRespuestos = ModeloRespuestosOnsite::where('company_id', $this->userCompanyId)
            ->get();

        return $modelosRespuestos;
    }

    public function getModelosRespuestosPorCategoria($idCategoria)
    {

        if ($idCategoria > 0) {
            $modelosRespuestos = ModeloRespuestosOnsite::with('imagen')
                ->with('modelo_pieza')                
                ->where('company_id', $this->userCompanyId)
                ->where('categoria_respuestos_onsite_id', $idCategoria)
                ->where('id', '<>', 1)
                ->where('id', '<>', 25)
                
                ->get();
            
        } else
            $modelosRespuestos = ModeloRespuestosOnsite::with('imagen')
                ->with('modelo_pieza')                
                ->where('company_id', $this->userCompanyId)
                ->where('id', '<>', 1)
                ->where('id', '<>', 25)
                ->get();



        return $modelosRespuestos;
    }


    public function getImagenPorModelo($idModelo)
    {
        $modeloRespuestos = ModeloRespuestosOnsite::with('imagen')
            ->where('company_id', $this->userCompanyId)
            ->find($idModelo);

        return $modeloRespuestos->imagen;
    }
}
