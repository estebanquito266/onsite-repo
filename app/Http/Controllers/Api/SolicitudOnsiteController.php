<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\Onsite\SolicitudOnsiteService;
use Illuminate\Support\Facades\Session;

class SolicitudOnsiteController extends Controller
{

  protected $solicitudOnsiteService;

  public function __construct(
    SolicitudOnsiteService $solicitudOnsiteService
  ) {
    $this->solicitudOnsiteService = $solicitudOnsiteService;
  }

  /**
   * Envia un mail con los datos del forumlario de la solicitud
   *
   * @param Request $request
   * @return void
   */
  public function guardarSolicitudOnsite(Request $request)
  {
    try {

      $this->solicitudOnsiteService->enviarMailSolicitudOnsite($request);

      Log::info('API - SolicitudOnsiteController - guardarSolicitudOnsite: ');

      return response('Solicitud enviada', 200);
    } catch (Exception $e) {
      return response($e->getMessage(), 500);
    }
  }

  public function insertSolicitudPuestaMarcha(Request $request)
  {
    Log::info('insertSolicitudPuestaMarcha     ==============');
    Log::info($request);
    Log::info($request->getContent());

    // EJEMPLO DE COMO RECIBIR EL ARCHIVO
    /*
    if ($request->hasFile('esquemas')) {
      foreach ($request->file('esquemas') as $imagen) {
        return response()->json(['imagen' => $imagen->getClientOriginalName()]);
      }
    }
    */
    
    Session::put('userCompanyIdDefault', env('BGH_COMPANY_ID', 2)); //revisar. Agregado para evitar errores

    $request['company_id'] = env('BGH_COMPANY_ID', 2);
    $request['solicitud_tipo_id'] = 3;

    
    $request['estado_solicitud_onsite_id'] = 1; //nueva
    $request['terminos_condiciones'] = true;

    $request['domicilio'] = $request['domicilio_obra'];
    $request['nro_cliente_bgh_ecosmart'] = $request['nro_clienteBGH'];

    $request['requiere_zapatos_seguridad'] = filter_var($request['requiere_zapatos_seguridad'], FILTER_VALIDATE_BOOLEAN);
    $request['requiere_casco_seguridad'] = filter_var($request['requiere_casco_seguridad'], FILTER_VALIDATE_BOOLEAN);
    $request['requiere_proteccion_visual'] = filter_var($request['requiere_proteccion_visual'], FILTER_VALIDATE_BOOLEAN);
    $request['requiere_proteccion_auditiva'] = filter_var($request['requiere_proteccion_auditiva'], FILTER_VALIDATE_BOOLEAN);
    $request['requiere_art'] = filter_var($request['requiere_art'], FILTER_VALIDATE_BOOLEAN);
    $request['clausula_no_arrepentimiento'] = filter_var($request['clausula_no_arrepentimiento'], FILTER_VALIDATE_BOOLEAN);
    
    $solicitudOnsite = $this->solicitudOnsiteService->insertObraSolicitudOnsite($request);

    if ($solicitudOnsite) {
      $respuesta = [
        'estado' => 'exitosa',
        'codigo' => 200,
        'solicitud' => $solicitudOnsite->id
      ];
    } else {
      $respuesta = [
        'estado' => 'erronea',
        'codigo' => 500,
        'mensaje' => 'No se pudo crear la solicitud'
      ];
    }

    return response()->json($respuesta, $respuesta['codigo']);
  }
}
