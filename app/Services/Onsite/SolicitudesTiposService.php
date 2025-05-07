<?php

namespace App\Services\Onsite;

use App\Models\Onsite\SolicitudTipo;
use Log;

class SolicitudesTiposService
{
    protected $userCompanyId;
    protected $templateComprobanteService;

    public function __construct(TemplatesService $templateComprobanteService)
    {
        $this->userCompanyId =  session()->get('userCompanyIdDefault');
        $this->templateComprobanteService = $templateComprobanteService;
    }
    public function getDataList()
    {
    }

    public function getData($id)
    {
    }

    public function create()
    {
    }

    public function show()
    {
    }

    public function store($request)
    {
    }





    public function update($request, $id)
    {
    }

    public function destroy($id)
    {
    }

    public function garantiaOnsiteEmitir($idGarantia)
    {
    }

    public function getAllSolicitudesTipos()
    {
        $solicitudes = SolicitudTipo::with(['solicitud_tipo_tarifa_base' => function ($query) {
            $query->orderBy('version', 'desc');
        }])->where('company_id', $this->userCompanyId)
            ->get();

            

        return $solicitudes;
    }
}
