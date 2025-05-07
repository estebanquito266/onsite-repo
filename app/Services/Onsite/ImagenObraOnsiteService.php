<?php

namespace App\Services\Onsite;

use App\Models\Onsite\ImagenObraOnsite;
use Exception;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Log;
use Storage;

class ImagenObraOnsiteService
{
    protected $tipoImagenService;
    protected $company_id;

    public function __construct(
        TiposImageOnsiteService $tipoImagenService
    ) {
        $this->tipoImagenService = $tipoImagenService;

        if (!Session::has('userCompanyIdDefault')) {
            Session::put('userCompanyIdDefault', env('BGH_COMPANY_ID', 2));
        }
        $this->company_id = Session::get('userCompanyIdDefault');
    }

    public function getData(Request $request = null)
    {

        $data = [
            'tipos_imagenes' => $this->tipoImagenService->getTiposImagenOnsiteAll(),
            'obra_onsite_id' => ($request != null ? $request['obra_onsite_id'] : null),
        ];

        return $data;
    }

    public function getDataItem($id)
    {
        $imagenObraOnsite = $this->findImagenObraOnsite($id);

        if ($imagenObraOnsite) {
            $data = [
                'imagen' => $imagenObraOnsite,
                'tipos_imagenes' => $this->tipoImagenService->getTiposImagenOnsiteAll(),
                'obra_onsite_id' => $imagenObraOnsite->obra_onsite_id
            ];

            return $data;
        } else
            return false;
    }

    public function store(Request $request)
    {
        $request['company_id'] =  $this->company_id;

        $nombreArchivo = $this->storageDiskImagenObra($request);

        $request['archivo'] = $nombreArchivo;

        $imagenObraOnsite = ImagenObraOnsite::create($request->all());

        return $imagenObraOnsite;
    }

    public function storageDiskImagenObra(Request $request)
    {
        $file = $request['file'];

        if ($request['nombre'])
            $nombre = $request['nombre'];
        else
            $nombre = 'esquema_obra';

        $nombreArchivo = $this->tipoImagenService->getCustomFilename('obra_onsite', $file->getClientOriginalName(), $nombre);

        try {
            Storage::disk('local2')->put($nombreArchivo, File::get($file));
            Log::info('Storage correcto');

            return $nombreArchivo;
        } catch (Exception $e) {
            Log::alert('error: ' . $e->getMessage());
            Log::info($e->getFile() . '(' . $e->getLine() . ')');

            return false;
        }
    }

    public function listImagenesObraOnsitePorObra($id)
    {

        $imagenesObraOnsite = ImagenObraOnsite::where('company_id', $this->company_id)
            ->where('obra_onsite_id', $id)
            ->get();

        return $imagenesObraOnsite;
    }

    public function update(Request $request, $id)
    {

        $nombreArchivo = $this->storageDiskImagenObra($request);

        $request['archivo'] = $nombreArchivo;

        $imagenObraOnsite = $this->findImagenObraOnsite($id);

        $imagenObraOnsite->update($request->all());


        if ($imagenObraOnsite) {
            $this->deleteStorageDiskImagenObra($id);
            return $imagenObraOnsite;

        } else
            return false;
    }

    public function deleteStorageDiskImagenObra($id)
    {
        $imagenObraOnsite = $this->findImagenObraOnsite($id);

        try {
            File::delete(public_path() . '/' . $imagenObraOnsite->archivo);           
        } catch (Exception $e) {
            Log::alert('error: ' . $e->getMessage());
            Log::info($e->getFile() . '(' . $e->getLine() . ')');
            return false;
        }

        return $imagenObraOnsite;
    }

    public function findImagenObraOnsite($id)
    {
        $imagenObraOnsite = ImagenObraOnsite::where('company_id', $this->company_id)
            ->find($id);

        return $imagenObraOnsite;
    }

    public function destroy($id)
    {
        $imagenObraOnsite = $this->deleteStorageDiskImagenObra($id);

        if ($imagenObraOnsite) {
            $imagenObraOnsite->delete();
            return $imagenObraOnsite;
        } else
            return false;
    }
}
