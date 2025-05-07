<?php

namespace App\Http\Controllers\Onsite;

use App\Http\Controllers\Controller;
use App\Models\Onsite\ImagenObraOnsite;
use App\Models\Onsite\TipoImagenOnsite;
use App\Services\Onsite\ImagenesOnsiteService;
use App\Services\Onsite\ImagenObraOnsiteService;
use App\Services\Onsite\ObrasOnsiteService;
use Illuminate\Http\Request;
use Log;
use Session;

class ImagenObraOnsiteController extends Controller
{

  protected $imagenObraOnsiteService;
  protected $obraOnsiteService;

  public function __construct(
    ImagenObraOnsiteService $imagenObraOnsiteService,
    ObrasOnsiteService $obraOnsiteService

  ) {
    $this->imagenObraOnsiteService = $imagenObraOnsiteService;
    $this->obraOnsiteService = $obraOnsiteService;
  }


  public function index()
  {
    //
  }


  public function create(Request $request)
  {
    $this->validate($request, [
      'obra_onsite_id' => 'required',
    ]);

    $idObraOnsite = $request['obra_onsite_id'];
    $obraOnsite = $this->obraOnsiteService->findObraOnsite($idObraOnsite);

    $data = $this->imagenObraOnsiteService->getData($request);

    if ($obraOnsite)
      return view('_onsite.obraonsite.imagenobraonsite.create', $data);
    else {
      Session::flash('message-error', 'Obra: ' . $idObraOnsite . ' no encontrada');
      return redirect('/obrasOnsite');
    }
  }


  public function store(Request $request)
  {
    $idObraOnsite = $request['obra_onsite_id'];

    if (!$request['tipo_imagen_onsite_id'])
      $request['tipo_imagen_onsite_id'] = TipoImagenOnsite::TRABAJO;

    $imagenesObraOnsite = null;
    $imagenesCreadas = '';

    if ($request->hasFile('archivos')) {

      foreach ($request->file('archivos') as $file) {
        $request['file'] = $file;
        $imagenesObraOnsite = $this->imagenObraOnsiteService->store($request);
        $imagenesCreadas .= 'id: ' . $imagenesObraOnsite->id . ' - ';
      }
    }

    

    if (isset($request['esquema']) || isset($request['esquema_archivo'])) {
      if ($request->hasFile('esquema_archivo')) {
        foreach ($request->file('esquema_archivo') as $file) {
          $request['file'] = $file;
          $imagenesObraOnsite = $this->imagenObraOnsiteService->store($request);
        }
      }
    }

    if ($imagenesObraOnsite != null) {
      $mjeCreate = 'Se han registrado las siguientes imágenes en la obra ID: ' . $idObraOnsite . ': ' . $imagenesCreadas;

      return redirect('/obrasOnsite/' . $idObraOnsite . '/edit')->with('message', $mjeCreate);
    } else {
      Session::flash('message-error', 'Error al adjuntar archivo');
      return redirect('/obrasOnsite');
    }
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    $data = $this->imagenObraOnsiteService->getDataItem($id);

    if ($data) {
      return view('_onsite.obraonsite.imagenobraonsite.show', $data);
    } else {
      Session::flash('message-error', 'Imagen: ' . $id . ', no encontrada');
      return redirect('/obrasOnsite');
    }
  }

  public function edit($id)
  {
    $data = $this->imagenObraOnsiteService->getDataItem($id);

    if ($data) {
      return view('_onsite.obraonsite.imagenobraonsite.edit', $data);
    } else {
      Session::flash('message-error', 'Imagen: ' . $id . ', no encontrada');
      return redirect('/obrasOnsite');
    }
  }

  public function update(Request $request, $id)
  {
    $this->validate($request, [
      'archivos' => 'required',
    ]);

    $request['file'] = $request['archivos'][0];
    

    $imagenObraOnsite = $this->imagenObraOnsiteService->update($request, $id);
    

    if ($imagenObraOnsite)
      
      return redirect('obrasOnsite/' . $imagenObraOnsite->obra_onsite_id . '/edit')
        ->with('message', 'Registro ' . $id . ' actualizado correctamente');

    else {
      Session::flash('message-error', 'No pudo actualizarce correctamente el registro ' . $id);
      return redirect('/obrasOnsite');
    }
  }


  public function destroy($id)
  {
    $imagenObraOnsite = $this->imagenObraOnsiteService->destroy($id);

    if ($imagenObraOnsite) {
      return redirect('obrasOnsite/' . $imagenObraOnsite->obra_onsite_id . '/edit')
        ->with('message', 'Registro ' . $id . ' Eliminado Correctamente');
    } else {
      Session::flash('message-error', 'No se pudo eliminar el reigstro' . $id . ' correctamente');
      return redirect('obrasOnsite/' . $imagenObraOnsite->obra_onsite_id . '/edit');
    }
  }
}
