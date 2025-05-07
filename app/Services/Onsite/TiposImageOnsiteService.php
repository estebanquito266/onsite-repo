<?php

namespace App\Services\Onsite;

use App\Models\Onsite\ImagenOnsite;
use App\Models\Onsite\TipoImagenOnsite;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class TiposImageOnsiteService
{

    public function getTiposImagenOnsiteAll()
    {
        $company_id = Session::get('userCompanyIdDefault');

        $tiposImagenOnsite = TipoImagenOnsite::where('company_id', $company_id)->get();


        return $tiposImagenOnsite;
    }

    public function getCustomFilename($recurso, $originalName, $urlAmigable)
    {
        $prefijo = strval(Carbon::now()->hour) . strval(Carbon::now()->minute) . strval(Carbon::now()->second);
        $nameOriginal = str_replace(" ", "", $originalName);
        $filename = $recurso.'_' . env('APP_ENV') . '_' . str_replace(" ", "", $urlAmigable) . '_' . $prefijo . '_' . $nameOriginal;
        return $filename;
    }

    public function findTipoImagenOnsite($idImagenOnsite)
    {
        $company_id = Session::get('userCompanyIdDefault');
        $imagenOnsite = TipoImagenOnsite::where('company_id', $company_id)
        ->find($idImagenOnsite);

        return $imagenOnsite;
    }

}
