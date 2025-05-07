<?php

namespace App\Services\Onsite\Respuestos;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

use App\Models\Respuestos\CategoriaRespuestosOnsite;

class CategoriaRespuestosService
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

    public function getCategoriasRespuestos()
    {
        $categoriasRespuestos = CategoriaRespuestosOnsite::where('company_id', $this->userCompanyId)
            ->get();

            return $categoriasRespuestos;
    }
}
