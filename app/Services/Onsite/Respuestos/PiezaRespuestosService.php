<?php

namespace App\Services\Onsite\Respuestos;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use App\Models\User;
use App\Http\Requests\Onsite\HistorialEstadoOnsiteRequest;
use App\Models\Onsite\HistorialEstadoOnsite;
use App\Models\Onsite\HistoriaEstadosOnsiteVisiblePorUser;
use App\Models\Respuestos\CategoriaRespuestosOnsite;
use App\Models\Respuestos\ModeloPiezaOnsite;
use App\Models\Respuestos\ModeloRespuestosOnsite;
use App\Models\Respuestos\OrdenPedidoRespuestosOnsite;
use App\Models\Respuestos\PiezaRespuestosOnsite;
use App\Models\Respuestos\PrecioPiezaRepuestoOnsite;
use Carbon\Carbon;
use File;
use Log;
use Storage;

class PiezaRespuestosService
{
    protected $userCompanyId;
    protected $idModelo;

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

    public function getPiezasRespuestos()
    {
        $piezasRespuestos = PiezaRespuestosOnsite::where('company_id', $this->userCompanyId)
            ->get();

        return $piezasRespuestos;
    }


    public function getPiezasPorModelo($idModelo)
    {
        /*    $piezasRespuestos = PiezaRespuestosOnsite::where('company_id', $this->userCompanyId)
            ->where('modelo_respuestos_onsite_id', $idModelo)
            ->get(); */

        $piezasRespuestos = ModeloPiezaOnsite::with('pieza_respuestos_onsite')
            ->where('company_id', $this->userCompanyId)
            ->where('modelo_respuestos_id', $idModelo)
            
            ->get();
        
            

        return $piezasRespuestos;
    }

    public function getPiezaCode($partCode)
    {
        $piezasRespuestos = PiezaRespuestosOnsite::with('modelo_pieza')
            ->with('precio')
            ->where('company_id', $this->userCompanyId)
            ->where('id', $partCode)
            ->first();

        return $piezasRespuestos;
    }

    public function getPiezasCode($partCode)
    {
        $piezasRespuestos = PiezaRespuestosOnsite::with('modelo_pieza')
            ->with('precio')
            ->where('company_id', $this->userCompanyId)
            ->where('spare_parts_code', 'like', '%' .$partCode.'%')
            ->get();

        return $piezasRespuestos;
    }    

    public function getPiezasName($partName)
    {
        $piezasRespuestos = PiezaRespuestosOnsite::with('modelo_pieza')
            ->with('precio')
            ->where('company_id', $this->userCompanyId)
            ->where('part_name', 'like', '%' . $partName . '%')
            ->get();

        return $piezasRespuestos;
    }

    public function getPiezasDescription($partDescription)
    {
        $piezasRespuestos = PiezaRespuestosOnsite::with('modelo_pieza')
            ->where('company_id', $this->userCompanyId)
            ->where('description', 'like', '%' . $partDescription . '%')
            ->get();

        return $piezasRespuestos;
    }

    public function getPieza($idPieza)
    {
        $piezasRespuestos = PiezaRespuestosOnsite::where('company_id', $this->userCompanyId)
            //->with('categoria_respuestos_onsite')
            //->with('modelo_respuestos_onsite')
            //->with('modelo_pieza') //solo necesito hacer el with con la tabla pivot porque la tabla pivot hace el with con los otros dos
            ->with('precio')
            ->find($idPieza);
        
        $modelos = [];
        $piezasMismoNumeroParte = $this->getPiezasCode($piezasRespuestos->spare_parts_code)->toArray();

        foreach ($piezasMismoNumeroParte as $pieza) {
            $modelos = array_merge($modelos, $pieza['modelo_pieza']);
        }
        
        $piezasRespuestos->modelo_pieza = $modelos;
        
        return $piezasRespuestos;
    }

    public function updatePieza($idPieza, Request $request)
    {
        $piezasRespuestos = PiezaRespuestosOnsite::where('company_id', $this->userCompanyId)            
            ->with('modelo_pieza') 
            ->find($idPieza); 

    
    
        $piezasRespuestos->update($request->all());

        return $piezasRespuestos;
    }

    public function store_image($file, $nombre = 'part_image')
    {
        $file;

        $nombreArchivo = $this->getCustomFilename('part_repuestos_image', $file->getClientOriginalName(), $nombre);

        Storage::disk('avatars')->put($nombreArchivo, File::get($file));

        return $nombreArchivo;
    }

    public function getCustomFilename($recurso, $originalName, $urlAmigable)
    {
        $prefijo = strval(Carbon::now()->hour) . strval(Carbon::now()->minute) . strval(Carbon::now()->second);
        $nameOriginal = str_replace(" ", "", $originalName);
        $filename = $recurso.'_' . env('APP_ENV') . '_' . str_replace(" ", "", $urlAmigable) . '_' . $prefijo . '_' . $nameOriginal;
        
        return $filename;
    }

    public function getVersionPrecio()
    {
        $version = PrecioPiezaRepuestoOnsite::where('company_id', $this->userCompanyId)
        ->orderBy('version', 'desc')
        ->first()->version;

        return $version;
    }
}
