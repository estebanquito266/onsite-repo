<?php

namespace App\Services\Onsite;

use App\Models\Onsite\ImagenOnsite;
use Illuminate\Support\Facades\Session;

class ImagenesOnsiteService
{
    public function store($arrayImagenOnsite)
    {
        $imagenOnsite = ImagenOnsite::create($arrayImagenOnsite);

        return $imagenOnsite;
    }

    public function updateOrstore($arrayImagenOnsite)
    {
        $imagenOnsite = ImagenOnsite::updateOrCreate(
            [
                'reparacion_onsite_id' => $arrayImagenOnsite['reparacion_onsite_id'],
                'archivo'  => $arrayImagenOnsite['archivo']
            ],
            $arrayImagenOnsite
        );

        return $imagenOnsite;
    }

    public function destroy($id)
    {
        $imagenOnsite = $this->findImagenOnsitePorId($id);

        $imagenOnsite->delete();

        return $imagenOnsite;
    }

    public function findImagenOnsitePorReparacion($idReparacionOnsite)
    {
        $company_id = Session::get('userCompanyIdDefault');

        $imagenOnsite = ImagenOnsite::where('company_id', $company_id)
            ->where('reparacion_onsite_id', $idReparacionOnsite)
            ->get();


        return $imagenOnsite;
    }

    public function findImagenOnsitePorId($idImagenOnsite)
    {
        $company_id = Session::get('userCompanyIdDefault');

        $imagenOnsite = ImagenOnsite::where('company_id', $company_id)
            ->find($idImagenOnsite);

        return $imagenOnsite;
    }
}
