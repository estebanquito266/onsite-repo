<?php

namespace App\Services\Onsite;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Onsite\ImagenUnidadInteriorOnsite;
use App\Models\Onsite\ImagenUnidadExteriorOnsite;
use App\Models\Onsite\TipoImagenOnsite;
use Illuminate\Support\Facades\Log;

class ImagenUnidadOnsiteService
{
    protected $tipoImagenOnsiteService;
    public function __construct(
        TiposImageOnsiteService $tipoImagenOnsiteService
    )
    {

        $this->tipoImagenOnsiteService = $tipoImagenOnsiteService;
    }

    /*public function getDataIndex()
	{
        $datos['userCompanyId'] = Session::get('userCompanyIdDefault');
        $datos['user_id'] = Auth::user()->id;       		
		$datos['unidadesInteriores'] = UnidadInteriorOnsite::paginate(30);   
		return $datos;		
    }

    public function getDataItem($id){
        $datos['unidadInterior'] = UnidadInteriorOnsite::where('id',$id)->first();
        return $datos;
    }*/

    public function getImagenInterior($id)
    {
        $datos['imagen'] = ImagenUnidadInteriorOnsite::where('id', $id)->first();
        $datos['company'] = Session::get('userCompanyIdDefault');
        $datos['tipo_unidad'] = 'interior';
        $datos['tipos_imagenes'] = $this->tipoImagenOnsiteService->getTiposImagenOnsiteAll(); 
        $datos['unidadOnsite'] = $datos['imagen']->unidad_interior_onsite_id;
        return $datos;
    }

    public function getImagenExterior($id)
    {
        $datos['imagen'] = ImagenUnidadExteriorOnsite::where('id', $id)->first();
        $datos['company'] = Session::get('userCompanyIdDefault');
        $datos['tipo_unidad'] = 'exterior';
        $datos['tipos_imagenes'] = $this->tipoImagenOnsiteService->getTiposImagenOnsiteAll();
        $datos['unidadOnsite'] = $datos['imagen']->unidad_exterior_onsite_id;
        return $datos;
    }

    public function getDatos()
    {
        $datos['company'] = Session::get('userCompanyIdDefault');
        $datos['tipo_unidad'] = 'exterior';
        $datos['tipos_imagenes'] = $this->tipoImagenOnsiteService->getTiposImagenOnsiteAll(); 

        return $datos;
    }

    public function getImagenUnidad($tipo_unidad,$id_unidad){
        if($tipo_unidad == 'exterior'){
            $imagenes = ImagenUnidadExteriorOnsite::where('unidad_exterior_onsite_id',$id_unidad)->get();
        }   
        else{
            $imagenes = ImagenUnidadInteriorOnsite::where('unidad_interior_onsite_id',$id_unidad)->get();
        }
        return $imagenes;
    }

    public function create(){

    }

    public function store($request)
    {

        $input = $this->agregarImagenUnidadOnsite($request);

        if ($request->tipo_unidad == 'exterior') {
            $imagenUnidadOnsite = ImagenUnidadExteriorOnsite::create($input);
        }
        if ($request->tipo_unidad == 'interior') {
            $imagenUnidadOnsite = ImagenUnidadInteriorOnsite::create($input);
        }
        return $imagenUnidadOnsite;
    }

    public function update($request, $id)
    {
        $input = $request->all();
        $tipoImagenOnsite = $this->tipoImagenOnsiteService->findTipoImagenOnsite($request['tipo_imagen_onsite_id']);
        $input['tipo_imagen_onsite_id'] = $request->tipo_imagen_onsite_id;
        $input['descripcion'] = $tipoImagenOnsite->nombre;

        if ($input['tipo_unidad'] == 'exterior') {
            $imagenUnidadOnsite = ImagenUnidadExteriorOnsite::find($id);
        }
        if ($input['tipo_unidad'] == 'interior') {
            $imagenUnidadOnsite = ImagenUnidadInteriorOnsite::find($id);
        }

        if (isset($request->archivo)) {
            File::delete(public_path() . '/' . $imagenUnidadOnsite->archivo);
            $archivo = $request->file('archivo');
            $nombreArchivo = $this->tipoImagenOnsiteService->getCustomFilename('unidad_' . $request['tipo_unidad'], $archivo->getClientOriginalName(), $request->nombre);
            Storage::disk('local2')->put($nombreArchivo, File::get($archivo));
            $input['archivo'] = $nombreArchivo;

            if ($request->tipo_unidad == 'exterior') {
                $archivo->move(public_path('/imagenes/unidades_exteriores'), $nombreArchivo);
                //$input['archivo'] = '/imagenes/unidades_exteriores/' . $nombreArchivo;
                $input['archivo'] =  $nombreArchivo;
            } else {
                $archivo->move(public_path('/imagenes/unidades_interiores'), $nombreArchivo);
                //$input['archivo'] = '/imagenes/unidades_interiores/' . $nombreArchivo;
                $input['archivo'] =  $nombreArchivo;
            }
        }

        $imagenUnidadOnsite->update($input);
        return $imagenUnidadOnsite;
    }

    public function eliminarImagenUnidadOnsite($archivo)
    {        
        File::delete(public_path().'/'.$archivo);

    }

    public function destroy($request,$id)
    {
        if ($request['tipo_unidad'] == 'exterior') {
            $imagenUnidadOnsite = ImagenUnidadExteriorOnsite::find($id);
        }
        if ($request['tipo_unidad'] == 'interior') {
            $imagenUnidadOnsite = ImagenUnidadInteriorOnsite::find($id);
        }
        $this->eliminarImagenUnidadOnsite($imagenUnidadOnsite->archivo);
        $imagenUnidadOnsite->delete();
        return $imagenUnidadOnsite;
    }



    public function agregarImagenUnidadOnsite($request)
    {

        $tipoImagenOnsite = $this->tipoImagenOnsiteService->findTipoImagenOnsite($request['tipo_imagen_onsite_id']);

        if ($tipoImagenOnsite) {
            $input['tipo_imagen_onsite_id'] = $request->tipo_imagen_onsite_id;
            $input['descripcion'] = $tipoImagenOnsite->nombre;
            if ($request->hasFile('archivo')) {
                $archivo = $request->file('archivo');
                $nombreArchivo = $this->tipoImagenOnsiteService->getCustomFilename('unidad_' . $request['tipo_unidad'], $archivo->getClientOriginalName(), $request->nombre);
                Storage::disk('local2')->put($nombreArchivo, File::get($archivo));

                $input['archivo'] = $nombreArchivo;
                /* $imagenOnsite = ImagenOnsite::create($request->all()); */
                $input['company_id'] =  $request->company_id;

                if ($request->tipo_unidad == 'exterior') {
                    $archivo->move(public_path('/imagenes/unidades_exteriores'), $nombreArchivo);
                    $input['unidad_exterior_onsite_id'] = $request->unidad_exterior_onsite_id;
                    //$input['archivo'] = '/imagenes/unidades_exteriores/' . $nombreArchivo;
                    $input['archivo'] = $nombreArchivo;
                } else {
                    $archivo->move(public_path('/imagenes/unidades_interiores'), $nombreArchivo);
                    $input['unidad_interior_onsite_id'] = $request->unidad_interior_onsite_id;
                    //$input['archivo'] = '/imagenes/unidades_interiores/' . $nombreArchivo;
                    $input['archivo'] = $nombreArchivo;
                }


                return ($input);
            }
        }
    }


    public function storeImagenesUnidadInterior(Request $request, $tipo_imagen_onsite_id, $unidad_interior_onsite_id)
    {
        $unidad_interior_onsite_imagen = false;

        if (Session::has('userCompanyIdDefault')) {
            $request['company_id'] = Session::get('userCompanyIdDefault');
        }

        if ($request['company_id'] == Company::COMPANY_BGH) {
            $tipo_imagen_onsite_id = TipoImagenOnsite::UNIDAD_INTERIOR;
        }

        if ($request->hasFile('files_unidad_interior')) {
            $imagenes = $request->file('files_unidad_interior');

            foreach ($imagenes as $imagen) {

                $nombre_imagen = $this->tipoImagenOnsiteService->getCustomFilename('unidad_interior_onsite', $imagen->getClientOriginalName(), ($unidad_interior_onsite_id . '_' . $tipo_imagen_onsite_id));

                //Storage::disk('ftpSpeedupExportImagenes')->put($nombre_imagen, File::get($imagen));

                $imagen->move(public_path('/imagenes/unidades_interiores'), $nombre_imagen);

                $unidad_interior_onsite_imagen = new ImagenUnidadInteriorOnsite();
                $unidad_interior_onsite_imagen->company_id = $request['company_id'];
                $unidad_interior_onsite_imagen->unidad_interior_onsite_id = $unidad_interior_onsite_id;

                $unidad_interior_onsite_imagen->archivo = $nombre_imagen;

                $unidad_interior_onsite_imagen->tipo_imagen_onsite_id = $tipo_imagen_onsite_id;

                $unidad_interior_onsite_imagen->save();
            }
        }

        return $unidad_interior_onsite_imagen;
    }

    public function storeImagenesUnidadExterior(Request $request, $tipo_imagen_onsite_id, $unidad_exterior_onsite_id)
    {
        $unidad_exterior_onsite_imagen = false;

        if (Session::has('userCompanyIdDefault')) {
            $request['company_id'] = Session::get('userCompanyIdDefault');
        }

        if ($request['company_id'] == Company::COMPANY_BGH) {
            $tipo_imagen_onsite_id = TipoImagenOnsite::UNIDAD_EXTERIOR;
        }

        if ($request->hasFile('files_unidad_exterior')) {
            $imagenes = $request->file('files_unidad_exterior');

            foreach ($imagenes as $imagen) {

                $nombre_imagen = $this->tipoImagenOnsiteService->getCustomFilename('unidad_exterior_onsite', $imagen->getClientOriginalName(), ($unidad_exterior_onsite_id . '_' . $tipo_imagen_onsite_id));

                //Storage::disk('ftpSpeedupExportImagenes')->put($nombre_imagen, File::get($imagen));

                $imagen->move(public_path('/imagenes/unidades_exteriores'), $nombre_imagen);

                $unidad_exterior_onsite_imagen = new ImagenUnidadExteriorOnsite();
                $unidad_exterior_onsite_imagen->company_id = $request['company_id'];
                $unidad_exterior_onsite_imagen->unidad_exterior_onsite_id = $unidad_exterior_onsite_id;

                $unidad_exterior_onsite_imagen->archivo = $nombre_imagen;

                $unidad_exterior_onsite_imagen->tipo_imagen_onsite_id = $tipo_imagen_onsite_id;

                $unidad_exterior_onsite_imagen->save();
            }
        }

        return $unidad_exterior_onsite_imagen;
    }    
}
