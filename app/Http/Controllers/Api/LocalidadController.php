<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\Onsite\UpdateTecLocalidadOnsiteRequest;


use App\Services\Onsite\LocalidadService;
use Illuminate\Support\Facades\Log;

class LocalidadController extends Controller
{
  protected $localidadService;

  public function __construct(LocalidadService $LocalidadService)
  {

    $this->localidadService = $LocalidadService;
  }



  public function getLocalidades(Request $request, $company)
  {

    try {

      $per_page = $request->input('per_page',100);

      return $this->localidadService->getLocalidadesByCompany($company,$per_page);

    } catch (\Exception $e) {
      Log::error('LocalidadController getLocalidades: ' . json_encode($request->all()) . ' - Error: ' . $e->getMessage() . ' - File:' . $e->getFile() . ' - Line:' . $e->getLine());

      return response()->json([
        'error' => 'SQL Error',
        'message' => 'Server Error'
      ], 500);
    }
  }

  public function updateTecnico(UpdateTecLocalidadOnsiteRequest $request, $localidad_id)
  {

    try {

      $mje = $this->localidadService->updateTecnico($request,$localidad_id);

      if ($mje) {
        return response()->json([
          'data' => $mje,
        ], 200);
      } else
        return response()->json([
          'error' => 'Error al asignar el tec',
          'message' => 'Server Error'
        ], 500);


    } catch (\Exception $e) {
      Log::error('LocalidadController updateTecnico: ' . json_encode($request->all()) . ' - Error: ' . $e->getMessage() . ' - File:' . $e->getFile() . ' - Line:' . $e->getLine());

      return response()->json([
        'error' => 'SQL Error',
        'message' => 'Server Error'
      ], 500);
    }
  }

}
