<?php

namespace App\Services\Onsite;

use App\Models\Onsite\CompradorOnsite;
use Illuminate\Http\Request;

class CompradoresOnsiteService
{
    protected $userCompanyId;
    protected $sistemaOnsiteService;
    

    public function __construct(SistemaOnsiteService $sistemaOnsiteService)
    {
        $this->userCompanyId =  session()->get('userCompanyIdDefault');
        $this->sistemaOnsiteService = $sistemaOnsiteService;
        

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

    public function store(Request $request)
    {
        
        $compradorOnsite = CompradorOnsite::create($request->all());

        return $compradorOnsite;
    }

    public function update($request, $idComprador)
    {
        $compradorOnsite = CompradorOnsite::where('company_id', $this->userCompanyId)
        ->find($idComprador);

        $compradorOnsite->update($request->all());

        return $compradorOnsite;
    }

    public function destroy($id)
    {
    }

    public function findCompradorPorDni($comprador_dni)
    {
        $comprador = CompradorOnsite::where('company_id', $this->userCompanyId)
        ->where('dni', $comprador_dni)
        ->first();

        return $comprador;
    
    }

    public function getCompradorPorSistema($idSistema)
    {
        $sistema_onsite = $this->sistemaOnsiteService->findSistemaOnsite($idSistema);
        $idComprador = $sistema_onsite->comprador_onsite_id;

        $comprador_onsite = CompradorOnsite::find($idComprador);

        return $comprador_onsite;
    }

    public function updateCompradorPorId(Request $request, $idComprador)
    {
        $comprador_onsite = CompradorOnsite::find($idComprador);
        $comprador_onsite->update($request->all());
        

        return $comprador_onsite;
    }

    public function storeCompradorOnsite($array_comprador)
    {
        return  CompradorOnsite::create($array_comprador);
    }

    public function getCompradores($company_id)
    {
        $compradores = CompradorOnsite::where('company_id', $company_id)
        ->get();

        $compradores->each(function ($comprador) {
            $comprador->makeHidden('company_id');
        });

        return $compradores;
    }
}
