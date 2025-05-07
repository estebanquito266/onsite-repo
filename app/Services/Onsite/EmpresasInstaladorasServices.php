<?php

namespace App\Services\Onsite;

use App\Models\EmpresaInstaladora\EmpresaInstaladoraOnsite;
use App\Models\EmpresaInstaladora\EmpresaInstaladoraUser;
use Log;
use Request;

class EmpresasInstaladorasServices
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

    public function store($array_empresa)
    {
        if(!isset($array_empresa['pais'])) $array_empresa['pais']  = 'Argentina';
        $empresa_instaladora = EmpresaInstaladoraOnsite::create($array_empresa);

        return $empresa_instaladora;
    }

    public function update()
    {
    }

    public function destroy($id)
    {
    }

    public function getEmpresasInstaladoras()
    {
        $empresasInstaladoras = EmpresaInstaladoraOnsite::where('company_id', $this->userCompanyId)
        ->where('activo', true)
        ->get();

        return $empresasInstaladoras;
    }

    public function getEmpresasInstaladorasOnsite($company_id)
    {
        $empresasInstaladoras = EmpresaInstaladoraOnsite::select(
            'id',
            'id_unificado',
            'nombre',
            'primer_nombre',
            'apellido',
            'razon_social',
            'cuit',
            'tipo_iva',
            'domicilio',
            'pais',
            'provincia_onsite_id',
            'localidad_onsite_id',
            'codigo_postal',
            'email',
            'celular',
            'telefono',
            'web',
            'coordenadas',
            'observaciones',
            'created_at',
            'updated_at'
        )
        ->where('company_id', $company_id)
        ->where('activo', true)
        ->get();

        return $empresasInstaladoras;
    }
}
