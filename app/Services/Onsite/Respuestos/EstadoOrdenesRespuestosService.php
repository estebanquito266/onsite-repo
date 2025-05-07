<?php

namespace App\Services\Onsite\Respuestos;

use App\Models\Respuestos\EstadoOrdenPedidoRespuestosOnsite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

use App\Models\Respuestos\ModeloRespuestosOnsite;

class EstadoOrdenesRespuestosService
{
    protected $userCompanyId;

    public function __construct()
    {
        $this->userCompanyId =  session()->get('userCompanyIdDefault');
    }
    public function getDataList()
    {
    }

    public function getEstadosRespuestos()
    {
        $estadosRespuestos = EstadoOrdenPedidoRespuestosOnsite::where('company_id', $this->userCompanyId)
        ->get();

        return $estadosRespuestos;
    }

    public function getEstadosRespuestosPorDefecto()
    {
        return [
            1 => 'En revisión',
            2 => 'Aprobada',
            3 => 'Rechazada',
            4 => 'En proceso',
            5 => 'En cotización',

        ];
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

    

}
