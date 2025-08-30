<?php

namespace App\Http\Controllers\Api;



use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\Onsite\UpdateReparacionRequest;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use App\Models\Onsite\ReparacionOnsite;
use App\Models\Onsite\ImagenOnsite;
use App\Repositories\Onsite\EstadoOnsiteRepository;
use App\Repositories\Onsite\ReparacionOnsiteRepository;
use App\Repositories\Onsite\ImagenOnsiteRepository;
use App\Repositories\Onsite\HistorialEstadoOnsiteRepository;
use App\Repositories\Onsite\EmpresaOnsiteRepository;
use App\Repositories\Onsite\ReparacionChecklistOnsiteRepository;
use App\Http\Resources\Onsite\ReparacionOnsiteResource;
use App\Http\Resources\Onsite\ReparacionOnsiteCollection;
use App\Models\UserCompany;
use App\Services\Onsite\FuncionesAuxiliaresOnsiteService;
use App\Services\Onsite\ImagenesOnsiteService;
use App\Services\Onsite\MailOnsiteService;
use App\Services\Onsite\ParametroService;
use App\Services\Onsite\Reparacion\ImportacionService;
use App\Services\Onsite\ReparacionOnsiteService;
use DateTime;

use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Log;

class ReparacionOnsiteController extends Controller
{


  protected $imagen_onsite_repository;
  protected $imagenOnsiteService;
  protected $reparacion_onsite_repository;
  protected $empresa_onsite_repository;
  protected $mailOnsiteService;
  protected $parametrosService;
  protected $funcionesAuxiliaresService;
  protected $importacionService;

  private $estado_onsite_repository;
  private $historial_estado_onsite_repository;
  private $reparacion_checklist_onsite_repository;
  private $estados_onsite_activos_ids_array;
  private $estados_onsite_pendientes_ids_array;
  protected $reparacion_onsite_service;

  public function __construct(
    ImagenOnsiteRepository $imagen_onsite_repository,
    ImagenesOnsiteService $imagenOnsiteService,
    ReparacionOnsiteRepository $reparacion_onsite_repository,
    MailOnsiteService $mailOnsiteService,
    ParametroService $parametrosService,
    FuncionesAuxiliaresOnsiteService $funcionesAuxiliaresService,
    ImportacionService $importacionService,
    ReparacionOnsiteService $reparacion_onsite_service
  ) {
    $this->imagen_onsite_repository = $imagen_onsite_repository;
    $this->imagenOnsiteService = $imagenOnsiteService;
    $this->reparacion_onsite_repository = $reparacion_onsite_repository;

    $this->estado_onsite_repository = new EstadoOnsiteRepository;
    $this->empresa_onsite_repository = new EmpresaOnsiteRepository;
    $this->historial_estado_onsite_repository = new HistorialEstadoOnsiteRepository;
    $this->reparacion_checklist_onsite_repository = new ReparacionChecklistOnsiteRepository;

    $this->estados_onsite_activos_ids_array = $this->estado_onsite_repository->activo(1)->get()->modelKeys();

    $this->estados_onsite_pendientes_ids_array = [3, 7];

    $this->mailOnsiteService = $mailOnsiteService;
    $this->parametrosService = $parametrosService;

    $this->funcionesAuxiliaresService = $funcionesAuxiliaresService;
    $this->importacionService = $importacionService;

    $this->reparacion_onsite_service = $reparacion_onsite_service;
  }

  /**
   *  Devuelve el listado de reparaciones segun los filtros
   * 
   * @param Request $request
   * @return ReparacionOnsiteCollection
   */
  public function index(Request $request)
  {
    Log::alert('index app');

    $user_id = Auth::user()->id;

    $reparaciones_onsite_query = $this->reparacion_onsite_repository->filtrar($request['filter']);

    $reparaciones_onsite_query->where('id_tecnico_asignado', $user_id)->applySorts($request['sort']);

    $reparaciones_onsite = $this->marcaVencimientos($reparaciones_onsite_query->get());

    Log::alert(json_encode($reparaciones_onsite));

    return ReparacionOnsiteCollection::make($reparaciones_onsite);
  }

  /**
   * Devuelve los datos para el dashboard
   *
   * @return void
   */
  public function dashboard(Request $request)
  {
    Log::alert('dashboard app');
    $reparaciones_onsite_activas = $this->getActivasByAuthUserQuery();

    $cantidad_total = $reparaciones_onsite_activas->count();
    $respuesta = [];

    foreach ($reparaciones_onsite_activas->get() as $reparacion_activa) {

      $respuesta[$reparacion_activa->id_estado] = [
        'icon' => $reparacion_activa->estado_onsite->card_icon,
        'titulo' => $reparacion_activa->estado_onsite->card_titulo,
        'detalle' => $reparacion_activa->estado_onsite->card_intro,
        'cantidad' => isset($respuesta[$reparacion_activa->id_estado]) ? ++$respuesta[$reparacion_activa->id_estado]['cantidad'] : 1,
        'total' => $cantidad_total,
        'estado' => $reparacion_activa->id_estado,
      ];
    }

    Log::alert(json_encode($respuesta));

    return response()->json([
      'data' => array_values($respuesta)
    ]);
  }

  /**
   * Devuelve el recurso de la reparacion onsite solicitada
   *
   * @param ReparacionOnsite $reparacion_onsite
   * @return ReparacionOnsiteResource
   */
  public function show(ReparacionOnsite $reparacion_onsite)
  {

    Log::alert('test APP TECNICOS');
    Log::alert(json_encode(ReparacionOnsiteResource::make($reparacion_onsite)));

    return ReparacionOnsiteResource::make($reparacion_onsite);
  }



  /**
   * Actualiza el estado y la observacion
   *
   * @param ReparacionOnsite $reparacion_onsite
   * @return ReparacionOnsiteResource
   */
  public function cambiarEstado(Request $request, ReparacionOnsite $reparacion_onsite)
  {

    $reparacion_onsite->id_estado = $request['estado_id'];

    $reparacion_onsite->chequearEstadoCerrado();

    $reparacion_onsite->save();

    $user_id = Auth::user()->id;

    $observacion = $request['observacion'];

    $this->historial_estado_onsite_repository->crearHistorial($reparacion_onsite, $user_id, $observacion);

    return ReparacionOnsiteResource::make($reparacion_onsite);
  }

  /**
   * Actualiza el estado, el campo fecha_coordinada y la observacion con el horario
   *
   * @param ReparacionOnsite $reparacion_onsite
   * @return ReparacionOnsiteResource
   */
  public function coordinar(Request $request, ReparacionOnsite $reparacion_onsite)
  {

    $reparacion_onsite->fecha_coordinada = $request['fecha_coordinada'];
    $reparacion_onsite->ventana_horaria_coordinada = $request['horario'];

    // Chequea si la fecha de coordinacion es mayor que la de ingreso
    $fCoordinada = new DateTime($reparacion_onsite->fecha_coordinada);
    $fIngreso = new DateTime($reparacion_onsite->fecha_ingreso);
    if ($fCoordinada <= $fIngreso) {
      abort(400, 'La fecha coordinada es anterior a la fecha de ingreso');
    }

    $reparacion_onsite->id_estado = $this->estado_onsite_repository->getEstadoCoordinadaByUserCompany()->first()->id;

    $reparacion_onsite->chequearEstadoCerrado();

    if ($request['horario'] == '07 a 11') $hora = '07:00';
    if ($request['horario'] == '11 a 15') $hora = '11:00';
    if ($request['horario'] == '15 a 19') $hora = '15:00';
    if ($request['horario'] == '19 a 23') $hora = '19:00';

    $fecha_hora = $request['fecha_coordinada'] . ' ' . $hora;

    $reparacion_onsite->save();

    try {

      $this->createGoogleCalendarEvent($reparacion_onsite, $fecha_hora);
    } catch (\Throwable $th) {
      Log::alert('Fallo al crear el evento en el calendario de onsite');
    }


    $user_id = Auth::user()->id;

    $observacion = date('d-m-Y', strtotime($reparacion_onsite->fecha_coordinada)) . ' - Horario coordinado: ' . $request['horario'];

    $this->historial_estado_onsite_repository->crearHistorial($reparacion_onsite, $user_id, $observacion);

    return ReparacionOnsiteResource::make($reparacion_onsite);
  }

  /**
   * Actualiza la reparacion con la notificacion de la visita
   *
   * @param Request $request
   * @param ReparacionOnsite $reparacion_onsite
   * @return ReparacionOnsiteResource
   */
  public function notificarVisita(Request $request, ReparacionOnsite $reparacion_onsite)
  {
    $user_id = Auth::user()->id;

    $reparacion_onsite->id_estado = $this->estado_onsite_repository->getEstadoNotificadaByUserCompany()->first()->id;

    $reparacion_onsite->informe_tecnico = $request['informe_tecnico'];

    $reparacion_onsite->requiere_nueva_visita = (int) $request['requiere_nueva_visita'];

    // SI ES DE PAGOFACIL
    if ($reparacion_onsite->id_empresa_onsite == $this->empresa_onsite_repository::PAGOFACIL) {
      $reparacion_onsite->tipo_conexion_local = $request['tipo_conexion_local'];
      $reparacion_onsite->tipo_conexion_proveedor = $request['tipo_conexion_proveedor'];
      $reparacion_onsite->instalacion_cartel = $request['instalacion_cartel'];
      $reparacion_onsite->instalacion_cartel_luz = $request['instalacion_cartel_luz'];
      $reparacion_onsite->instalacion_buzon = $request['instalacion_buzon'];
      $reparacion_onsite->cableado = $request['cableado'];
      if ($request['cableado']) {
        $reparacion_onsite->cableado_cantidad_metros = $request['cableado_cantidad_metros'];
        $reparacion_onsite->cableado_cantidad_fichas = $request['cableado_cantidad_fichas'];
      }
    }

    $reparacion_onsite->chequearEstadoCerrado();

    $reparacion_onsite->save();

    $this->historial_estado_onsite_repository->crearHistorial($reparacion_onsite, $user_id, date('d-m-Y h:i') . ' - Notificada');

    $this->guardarFilesReparacionOnsite($request, $reparacion_onsite->id);

    return ReparacionOnsiteResource::make($reparacion_onsite);
  }


  /**
   * actualiza el estado de la reparacion y usa el metodo guardarVisitaPuestaEnMarcha()
   *
   * @param Request $request
   * @param ReparacionOnsite $reparacion_onsite
   * @return ReparacionOnsiteResource
   */
  public function notificarVisitaPuestaEnMarcha(Request $request, ReparacionOnsite $reparacion_onsite)
  {

    $reparacion_onsite->id_estado = $this->estado_onsite_repository->getEstadoNotificadaByUserCompany()->first()->id;

    $reparacion_onsite->chequearEstadoCerrado();

    return $this->guardarVisitaPuestaEnMarcha($request, $reparacion_onsite);
  }

  /**
   * Undocumented function
   *
   * @param Request $request
   * @param ReparacionOnsite $reparacion_onsite
   * @return void
   */
  public function guardarVisitaPuestaEnMarcha(Request $request, ReparacionOnsite $reparacion_onsite)
  {
    $user_id = Auth::user()->id;

    $reparacion_onsite->observaciones_internas = $request->observaciones_internas;
    $reparacion_onsite->informe_tecnico = $request->informe_tecnico;

    $reparacion_onsite->save();

    $this->historial_estado_onsite_repository->crearHistorial($reparacion_onsite, $user_id, date('d-m-Y h:i') . ' - Puesta en marcha notificada');

    // Crea o actualiza las preguntas
    $data = $request->reparacion_checklist_onsite;
    $data['reparacion_onsite_id'] = $reparacion_onsite->id;
    $this->reparacion_checklist_onsite_repository->createUpdate($data);

    $this->guardarFilesReparacionOnsite($request, $reparacion_onsite->id);

    $email = $this->enviarMailReparacion($reparacion_onsite, 'MAIL_ADMINISTRADOR_REPARACIONES_TO', 'MAIL_ADMINISTRADOR_REPARACIONES');

    return ReparacionOnsiteResource::make($reparacion_onsite);
  }

  /**
   * Guarda las imagenes de la reparacion notificada
   *
   * @param Request $request
   * @param [integer] $reparacion_onsite_id
   * @return void
   */
  private function guardarFilesReparacionOnsite(Request $request, $reparacion_onsite_id)
  {
    // GUARDA LAS IMAGENES DE LA REPARACION
    if ($request->hasFile('files_trabajo_realizado')) {
      $this->guardarImagenes($request->file('files_trabajo_realizado'), $this->imagen_onsite_repository::TIPO_TRABAJO, $reparacion_onsite_id);
    }
    // GUARDA LAS IMAGENES DE LOS COMPROBANTES
    if ($request->hasFile('files_comprobantes')) {
      $this->guardarImagenes($request->file('files_comprobantes'), $this->imagen_onsite_repository::TIPO_COMPROBANTE, $reparacion_onsite_id);
    }

    // GUARDA LAS IMAGENES DE  CORTE_CANERIA
    if ($request->hasFile('files_corte_caneria')) {
      $this->guardarImagenes($request->file('files_corte_caneria'), $this->imagen_onsite_repository::CORTE_CANERIA, $reparacion_onsite_id);
    }
    // GUARDA LAS IMAGENES DE ANOMALIAS
    if ($request->hasFile('files_anomalias')) {
      $this->guardarImagenes($request->file('files_anomalias'), $this->imagen_onsite_repository::ANOMALIAS, $reparacion_onsite_id);
    }
    // GUARDA LAS IMAGENES DE PRESURIZACION
    if ($request->hasFile('files_presurizacion')) {
      $this->guardarImagenes($request->file('files_presurizacion'), $this->imagen_onsite_repository::PRESURIZACION, $reparacion_onsite_id);
    }
    // GUARDA LAS IMAGENES DE COMPROBANTE_SERVICIO_FIRMADO
    if ($request->hasFile('files_comprobante_servicio_firmado')) {
      $this->guardarImagenes($request->file('files_comprobante_servicio_firmado'), $this->imagen_onsite_repository::COMPROBANTE_SERVICIO_FIRMADO, $reparacion_onsite_id);
    }

    // GUARDA LAS IMAGENES DE TRABAJO_REALIZADO
    if ($request->hasFile('files_trabajo_realizado')) {
      $this->guardarImagenes($request->file('files_trabajo_realizado'), $this->imagen_onsite_repository::TRABAJO_REALIZADO, $reparacion_onsite_id);
    }
  }

  /**
   * Envía mail notificando la visita
   *
   * @param Request $request
   * @param [integer] $reparacion_onsite_id
   * @return void
   */
  public function enviarMailReparacion($reparacion_onsite, $mailTo, $plantillaMail)
  {

    $parametroMail = $this->parametrosService->findParametroPorNombre($mailTo);


    if (isset($parametroMail)) {
      $mailTo = $parametroMail->valor_cadena;
      $plantilla_mail_id = $this->parametrosService->findParametroPorNombre($plantillaMail);

      if ($reparacion_onsite && !is_null($mailTo)  && $plantilla_mail_id) {

        $plantilla_mail_id = $plantilla_mail_id->valor_numerico;

        if ($plantilla_mail_id > 0 && $mailTo !== null) {

          $email = $this->mailOnsiteService->enviarMailReparaciones($reparacion_onsite, $plantilla_mail_id, $mailTo);
          if ($email)
            return 'Enviado correctamente';
        } else return 'No puede enviarse email';
      } else return 'NO puede enviarse email';
    }
  }

  /**
   * agrega atributos a cada recurso
   *
   * @param Collection $reparaciones_onsite
   * @return Collection
   */
  private function marcaVencimientos(Collection $reparaciones_onsite)
  {
    $fecha_hoy = date('Y-m-d');

    $respuesta = collect();

    foreach ($reparaciones_onsite as $reparacion_onsite) {

      $reparacion_onsite->probando_variable = 'probando variable';
      $fecha_vencimiento = new DateTime($reparacion_onsite->fecha_vencimiento);
      $fecha_vencimiento_acortada = $fecha_vencimiento->format('Y-m-d');

      // VENCIDA
      if ($fecha_vencimiento_acortada < $fecha_hoy) {
        $reparacion_onsite->vencida = 1;
        $reparacion_onsite->vencimiento_color = 'danger';
        $reparacion_onsite->vencimiento_texto = 'Vencida';
      } else {
        $reparacion_onsite->vencida = 0;
      }
      // VENCE HOY
      if ($fecha_vencimiento_acortada == $fecha_hoy) {
        $reparacion_onsite->vence_hoy = 1;
        $reparacion_onsite->vencimiento_color = 'warning';
        $reparacion_onsite->vencimiento_texto = 'Vence hoy';
      } else {
        $reparacion_onsite->vence_hoy = 0;
      }
      // A TIEMPO
      if ($fecha_vencimiento_acortada > $fecha_hoy) {
        $reparacion_onsite->a_tiempo = 1;
        $reparacion_onsite->vencimiento_color = 'info';
        $reparacion_onsite->vencimiento_texto = 'A tiempo';
      } else {
        $reparacion_onsite->a_tiempo = 0;
      }

      // agrega la reparacion procesada a la respuesta      
      $respuesta->add($reparacion_onsite);
    }

    return $respuesta;
  }

  /**
   * Devuelve las reparaciones vencidas del usuario
   *
   * @return void
   */
  public function vencidas()
  {
    $reparaciones_onsite = $this->getReparacionesFechaVencimientoHoy('<');

    return ReparacionOnsiteCollection::make($reparaciones_onsite);
  }

  /**
   * Devuelve las reparaciones que vencen hoy del usuario
   *
   * @return void
   */
  public function vencenHoy()
  {
    $reparaciones_onsite = $this->getReparacionesFechaVencimientoHoy('=');

    return ReparacionOnsiteCollection::make($reparaciones_onsite);
  }

  /**
   * Devuelve las reparaciones que vencen hoy del usuario
   *
   * @return void
   */
  public function aTiempo()
  {
    $reparaciones_onsite = $this->getReparacionesFechaVencimientoHoy('>');

    return ReparacionOnsiteCollection::make($reparaciones_onsite);
  }

  /**
   * SE TIENE QUE BORRAR SOLO LA TENGO DE EJEMPLO
   *
   * @param ReparacionOnsite $reparacion_onsite
   * @return void
   */
  public function update(Request $request, ReparacionOnsite $reparacion_onsite)
  {
    $user_id = Auth::user()->id;

    // Chequea si la empresa a la que pertenece la reparacion requiere el campo requiere_tipo_conexion_local
    if (in_array($reparacion_onsite->id_empresa_onsite, $this->empresa_onsite_repository->requiereTipoConexionLocal()->get()->modelKeys())) {
      if (!isset($request['tipo_conexion_local'])) {
        abort(400, 'El campo reparacion_onsite.requiere_tipo_conexion_local es mandatorio');
      }
    }

    // Si se recibio la firma del cliente se guarda
    if (isset($request['firma_cliente'])) {
      $firma_cliente_file = $this->reparacion_onsite_repository->guardarFirmaOnsite($reparacion_onsite, $request['firma_cliente'], 'Cliente');
      $request['firma_cliente'] = $firma_cliente_file;
    }

    // Si se recibio la firma del tecnico se guarda
    if (isset($request['firma_tecnico'])) {
      $firma_tecnico_file = $this->reparacion_onsite_repository->guardarFirmaOnsite($reparacion_onsite, $request['firma_tecnico'], 'Tecnico');
      $request['firma_tecnico'] = $firma_tecnico_file;
    }

    $estado_onsite_anterior = $reparacion_onsite->id_estado;
    // Guarda los cambios
    $reparacion_onsite->update($request->all());

    // Si el estado es distinto al anterior guarda un registro en el historial de estados
    if ($reparacion_onsite->id_estado != $estado_onsite_anterior) {

      $observacion = $request['observacion'];

      $this->historial_estado_onsite_repository->crearHistorial($reparacion_onsite, $user_id, $observacion);
    }

    return ReparacionOnsiteResource::make($reparacion_onsite);
  }


  // METODOS PRIVADOS

  /**
   * Devuelve las reparaciones activas del tecnico
   *
   * @return query
   */
  private function getActivasByAuthUserQuery()
  {
    $estados_onsite_activos = $this->estado_onsite_repository->activo(1)->pluck('id')->toArray();

    return $this->getReparacionesOnsitePorEstadosQuery($estados_onsite_activos);
  }

  /**
   * Undocumented function
   *
   * @param [type] $compracion
   * @return void
   */
  private function getReparacionesFechaVencimientoHoy($compracion)
  {
    $fecha_hoy = date('Y-m-d');

    $reparaciones_onsite_query = $this->getActivasByAuthUserQuery();;

    $reparaciones_onsite_query->whereDate('fecha_vencimiento', $compracion, $fecha_hoy);

    return $this->marcaVencimientos($reparaciones_onsite_query->get());
  }

  /**
   * Devuelve la consulta de las reparaciones del tecnico por estados
   *
   * @param array $estados
   * @return query
   */
  private function getReparacionesOnsitePorEstadosQuery($estados)
  {
    $user_id = Auth::user()->id;

    $filtros = [
      'id_estado' => $estados,
      'id_tecnico_asignado' => $user_id,
    ];

    $reparaciones_onsite_activas_query = $this->reparacion_onsite_repository->filtrar($filtros);

    return $reparaciones_onsite_activas_query;
  }

  /**
   * Guarda las imagenes en la tabla
   *
   * @param array $imagenes
   * @param string $tipo_imagen_onsite_id
   * @return void
   */
  private function guardarImagenes($imagenes, $tipo_imagen_onsite_id, $reparacion_onsite_id)
  {
    if (Auth::user())
      $company_id = Auth::user()->companies->first()->id;

    foreach ($imagenes as $key => $imagen) {

      $nombre_imagen = $this->getCustomFilename('onsite', $imagen->getClientOriginalName(), ($reparacion_onsite_id . '_' . $tipo_imagen_onsite_id));

      //Storage::disk('ftpSpeedupExportImagenes')->put($imagen, $nombre_imagen);

      try {
        $imagen->move(public_path('/imagenes/reparaciones_onsite'), $nombre_imagen);
      } catch (Exception $e) {
        Log::info('WARNING: ');
        Log::alert($e->getMessage());
      }

      $arrayImagenOnsite = [
        'reparacion_onsite_id' =>  $reparacion_onsite_id,
        'archivo'  => $nombre_imagen,
        'tipo_imagen_onsite_id' => $tipo_imagen_onsite_id,
        'company_id' => $company_id
      ];

      $reparacion_onsite_imagen = $this->imagenOnsiteService->store($arrayImagenOnsite);
    }
  }


  /**
   * Devuelve filename
   *
   * @param string $recurso
   * @param string $originalName
   * @param string $originalName
   * @return string
   */
  protected static function getCustomFilename($recurso, $originalName, $nombre)
  {
    $prefijo = strval(Carbon::now()->hour) . strval(Carbon::now()->minute) . strval(Carbon::now()->second);
    $nameOriginal = str_replace(" ", "", $originalName);
    $filename = $recurso . '_' . env('APP_ENV') . '_' . str_replace(" ", "", $nombre) . '_' . $prefijo . '_' . $nameOriginal;
    return $filename;
  }

  /**
   * Crea un evento usando API Google Calendar
   *
   * @param object $reparacion_onsite   
   * @return boolean
   */
  public function createGoogleCalendarEvent($reparacion_onsite, $fecha)
  {
    $fecha = Carbon::parse($fecha);

    $nombre = $reparacion_onsite->sistema_onsite->obra_onsite->nombre . ' - ' . $reparacion_onsite->sistema_onsite->nombre
      . ' - ' . $reparacion_onsite->tecnicoAsignado->name;

    $descripcion = 'Visita coordinada. ' . 'Obra: ' . $reparacion_onsite->sistema_onsite->obra_onsite->nombre . ' - ' . $reparacion_onsite->sistema_onsite->nombre
      . ' Técnico: ' . $reparacion_onsite->tecnicoAsignado->name;

    $evento = $this->funcionesAuxiliaresService->createGoogleCalendarEvent($nombre, $descripcion, $fecha);

    return $evento;
  }

  /**
   * Devuelve las reparaciones cerradas
   *
   * @return void
   */
  public function cerradas()
  {
    //
  }

  /**
   *
   * @lrd:start
   * Procesa archivo de excel según modelo para importar reparaciones
   * @lrd:end
   * @LRDparam archivo required|file	 
   * @LRDparam reparacin required	 

   * @param Request $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function importarReparacionesOnsite(Request $request): JsonResponse
  {
    try {
      $mje = $this->importacionService->importar($request, Auth::user()->id);

      if ($mje) {
        return response()->json([
          'data' => $mje,
        ], 200);
      } else
        return response()->json([
          'error' => 'Error de conexión al servidor',
          'message' => 'Server Error'
        ], 500);
    } catch (\Exception $e) {
      Log::error('importar_arhchivo_reparaciones: ' . json_encode($request) . ' - Error: ' . $e->getMessage() . ' - File:' . $e->getFile() . ' - Line:' . $e->getLine());

      return response()->json([
        'error' => $e->getMessage(),
        'message' => 'Server Error'
      ], 500);
    }
  }


  /**
   * @lrd:start
   * # Functión
   * Update estado
   * @lrd:end
   */
  public function updateReparacionOnsite(UpdateReparacionRequest $request, $company_id,  $id_reparacion): JsonResponse
  {
    try {
      $mje = $this->reparacion_onsite_service->update($request, $id_reparacion, $company_id);

      if ($mje) {
        return response()->json([
          'data' => $mje,
        ], 200);
      } else
        return response()->json([
          'error' => 'Error de conexión al servidor',
          'message' => 'Server Error'
        ], 500);
    } catch (\Exception $e) {
      Log::error('update_reparacion_api: ' . json_encode($request) . ' - Error: ' . $e->getMessage() . ' - File:' . $e->getFile() . ' - Line:' . $e->getLine());

      return response()->json([
        'error' => $e->getMessage(),
        'message' => 'Server Error'
      ], 500);
    }
  }

  /**
   * Devuelve el recurso de la reparacion onsite solicitada
   *
   * @param ReparacionOnsite $reparacion_onsite
   * @return ReparacionOnsiteResource
   */
  public function getReparacion($company_id, $id_reparacion)
  {
    try {

      $mje = $this->reparacion_onsite_service->getDataEdit($id_reparacion, $company_id);

      if ($mje) {
        return response()->json([
          'data' => $mje,
        ], 200);
      } else
        return response()->json([
          'error' => 'Error de conexión al servidor',
          'message' => 'Server Error'
        ], 500);
    } catch (\Exception $e) {
      Log::error('update_reparacion_api: ' . json_encode($id_reparacion) . ' - Error: ' . $e->getMessage() . ' - File:' . $e->getFile() . ' - Line:' . $e->getLine());

      return response()->json([
        'error' => $e->getMessage(),
        'message' => 'Server Error'
      ], 500);
    }
  }

  public function getReparacionPorClave($company_id, $clave)
  {
    try {

      $mje = $this->reparacion_onsite_service->getDataEditByClave($company_id, $clave);
      if ($mje) {
        return response()->json([
          'data' => $mje,
        ], 200);
      } else
        return response()->json([
          'error' => 'Error de conexión al servidor',
          'message' => 'Server Error'
        ], 500);
    } catch (\Exception $e) {
      Log::error('update_reparacion_api: ' . json_encode($clave) . ' - Error: ' . $e->getMessage() . ' - File:' . $e->getFile() . ' - Line:' . $e->getLine());

      return response()->json([
        'error' => $e->getMessage(),
        'message' => 'Server Error'
      ], 500);
    }
  }

  public function getReparacionIdPorEstado($company_id, $id_estado)
  {
    try {

      $mje = $this->reparacion_onsite_service->getDataRepIdByEstado($company_id, $id_estado);
      if ($mje) {
        return response()->json([
          'data' => $mje,
        ], 200);
      } else
        return response()->json([
          'error' => 'Error de conexión al servidor',
          'message' => 'Server Error'
        ], 500);
    } catch (\Exception $e) {
      Log::error('getReparacionIdPorEstado: ' . json_encode($id_estado) . ' - Error: ' . $e->getMessage() . ' - File:' . $e->getFile() . ' - Line:' . $e->getLine());

      return response()->json([
        'error' => $e->getMessage(),
        'message' => 'Server Error'
      ], 500);
    }
  }

  public function storeReparacionApi(Request $request, $company_id)
  {
    try {

      $mje = $this->importacionService->storeReparacionApi($request, $company_id, Auth::user()->id);
      if ($mje) {
        return response()->json([
          'data' => $mje,
        ], 200);
      } else
        return response()->json([
          'error' => 'Error al crear la reparación',
          'message' => 'Server Error'
        ], 500);
    } catch (\Exception $e) {
      Log::error('update_reparacion_api: ' . json_encode($request) . ' - Error: ' . $e->getMessage() . ' - File:' . $e->getFile() . ' - Line:' . $e->getLine());

      return response()->json([
        'error' => $e->getMessage(),
        'message' => 'Server Error'
      ], 500);
    }
  }
}
