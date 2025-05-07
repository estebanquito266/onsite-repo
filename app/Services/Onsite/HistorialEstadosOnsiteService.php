<?php

namespace App\Services\Onsite;

use App\Exports\GenericExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Onsite\EstadoOnsite;
use App\Models\User;
use App\Http\Requests\Onsite\HistorialEstadoOnsiteRequest;
use App\Models\Onsite\HistorialEstadoOnsite;
use App\Repositories\Onsite\HistorialEstadoOnsiteRepository;
use App\Repositories\Onsite\NotaOnsiteRepository;
use App\Models\Onsite\HistoriaEstadosOnsiteVisiblePorUser;
use App\Repositories\Onsite\EstadoOnsiteRepository;


class HistorialEstadosOnsiteService
{
    protected $historialEstadoRepository, $notaRepository;
    protected $estadoOnsiteRepository;
    protected $userService;

    public function __construct(
        HistorialEstadoOnsiteRepository $historialEstadoRepository,
        NotaOnsiteRepository $notaRepository,
        EstadoOnsiteRepository $estadoOnsiteRepository,
        UserService $userService

    ) {
        $this->historialEstadoRepository = $historialEstadoRepository;
        $this->notaRepository = $notaRepository;
        $this->estadoOnsiteRepository = $estadoOnsiteRepository;
        $this->userService = $userService;
    }
    public function index()
    {
        $userCompanyId = Session::get('userCompanyIdDefault');
        $visibilidad = 0;

        //$this->generarCsv(null, null, null, null, null);

        $params = [
            'userCompanyId' => $userCompanyId,
            'visibilidad' => $visibilidad,
            'tomar' => NULL,
        ];

        $historialEstadosOnsite = $this->historialEstadoRepository->findHistorialEstadoOnsiteAll();

        $datos = [
            'userCompanyId' => $userCompanyId,
            'reparacionesOnsite' => array(),
            'estadosOnsite' => $this->estadoOnsiteRepository->listado($userCompanyId),
            'usuarios' => $this->userService->listarTecnicosOnsite($userCompanyId),
            'visibilidad' => $visibilidad,
            'historialEstadosOnsite' => $historialEstadosOnsite,
            'mostrarCheck' => true
        ];

        return $datos;
    }

    public function indexAll()
    {
        $userCompanyId = Session::get('userCompanyIdDefault');
        $visibilidad = 1; //mostrar todos			

        $params = [
            'userCompanyId' => $userCompanyId,
            'visibilidad' => $visibilidad,
            'tomar' => NULL,
        ];

        $historialEstadosOnsite = $this->historialEstadoRepository::listar($params);

        $datos = [
            'reparacionesOnsite' => array(),
            'estadosOnsite' => $this->estadoOnsiteRepository->listado($userCompanyId),
            'usuarios'  => $this->userService->listarTecnicosOnsite($userCompanyId),
            'userCompanyId' => $userCompanyId,
            'visibilidad'   => $visibilidad,
            'historialEstadosOnsite' => $historialEstadosOnsite,
            'mostrarCheck' => false
        ];

        return $datos;
    }

    public function create()
    {
        $userCompanyId = Session::get('userCompanyIdDefault');

        $datos = [
            'userCompanyId' => $userCompanyId,
            'estadosOnsite' => $this->estadoOnsiteRepository->listado($userCompanyId),
            'usuarios'      => $this->userService->listarTecnicosOnsite($userCompanyId),
        ];

        return $datos;
    }

    public function show(HistorialEstadoOnsite $historialEstadoOnsite)
    {
        $userCompanyId = Session::get('userCompanyIdDefault');

        $datos = [
            'userCompanyId' => $userCompanyId,
            'estadosOnsite' => $this->estadoOnsiteRepository->listado($userCompanyId),
            'usuarios'      => $this->userService->listarTecnicosOnsite($userCompanyId),
            'historialEstadoOnsite' => $historialEstadoOnsite
        ];

        return $datos;
    }

    public function store(HistorialEstadoOnsiteRequest $request)
    {
        $request['visible'] = $request['visible'] ? 1 : 0;


        if (!isset($request['company_id']) || is_null($request['company_id']))
            $request['company_id'] = User::find(Auth::user()->id)->companies->first()->id;

        $historialEstadoOnsite = HistorialEstadoOnsite::create($request->all());

        return $historialEstadoOnsite;
    }

    public function update(HistorialEstadoOnsiteRequest $request, $id)
    {
        $historialEstadoOnsite = HistorialEstadoOnsite::find($id);

        $request['visible'] = $request['visible'] ? 1 : 0;

        $historialEstadoOnsite->update($request->all());

        return $historialEstadoOnsite;
    }

    public function destroy($id)
    {
        $historialEstadoOnsite = HistorialEstadoOnsite::find($id);

        $historialEstadoOnsite->delete();

        return $historialEstadoOnsite;
    }

    public function filtrar(Request $request)
    {
        $userCompanyId = Session::get('userCompanyIdDefault');

        $datos = [
            'texto' => $request['texto'],
            'id_estado' => $request['id_estado'],
            'id_reparacion' => $request['id_reparacion'],
            'id_estado' => $request['id_estado'],
            'idReparacion' => $request['id_reparacion'],
            'id_usuario' => $request['id_usuario'],
            'idEstado' => $request['id_estado'],
            'idUsuario' => $request['id_usuario'],
            'visibilidad' => $request['visibilidad'],
            'mostrarCheck' => $request['mostrar_check'] == '1',
            'usuarios' =>  $this->userService->listarTecnicosOnsite($userCompanyId),
            'estadosOnsite' => $this->estadoOnsiteRepository->listado($userCompanyId),
            'userCompanyId' => $userCompanyId,
            'tomar' => null
        ];

        $historialEstadosOnsite = $this->historialEstadoRepository::listar($datos);

        $datos['historialEstadosOnsite'] = $historialEstadosOnsite;
        $this->generarXlsx($datos['texto'], $datos['idReparacion'], $datos['idEstado'], $datos['idUsuario'], $datos['visibilidad']);

        return $datos;
    }

    public function generarCsv($texto, $idReparacion, $idEstado, $idUsuario, $visibilidad)
    {
        $saltear = 0;
        $tomar = 5000;
        $userCompanyId = Session::get('userCompanyIdDefault');

        $idUser = Auth::user()->id;

        $fp = fopen("exports/listado_historialestadoonsite" . $idUser . ".csv", 'w');

        $cabecera = array(
            'ID',
            'ID_REPARACION',
            'REPARACION',
            'ID_ESTADO',
            'ESTADO',
            'FECHA',
            'OBSERVACION',
            'ID_USUARIO',
            'USUARIO',
            'VISIBLE',
        );

        fputcsv($fp, $cabecera, ';');

        $params = [
            'userCompanyId' => $userCompanyId,
            'texto' => $texto,
            'idReparacion' => $idReparacion,
            'idEstado' => $idEstado,
            'idUsuario' => $idUsuario,
            'visibilidad' => $visibilidad,
            'saltear' => $saltear,
            'tomar' => $tomar,
        ];

        $historialEstadosOnsite = $this->historialEstadoRepository::listar($params);

        while ($historialEstadosOnsite->count()) {


            foreach ($historialEstadosOnsite as $historialEstadoOnsite) {
                $fila = array(
                    'id' => $historialEstadoOnsite->id,
                    'id_reparacion' => $historialEstadoOnsite->id_reparacion,
                    'reparacion' => ($historialEstadoOnsite->reparacion_onsite ? $historialEstadoOnsite->reparacion_onsite->clave : ''),
                    'id_estado' => $historialEstadoOnsite->id_estado,
                    'estado' => ($historialEstadoOnsite->estado_onsite ? $historialEstadoOnsite->estado_onsite->nombre : ''),
                    'fecha' => $historialEstadoOnsite->fecha,
                    'observacion' => $historialEstadoOnsite->observacion,
                    'id_usuario' => $historialEstadoOnsite->id_usuario,
                    'usuario' => ($historialEstadoOnsite->usuario ? $historialEstadoOnsite->usuario->name : ''),
                    'visible' => $historialEstadoOnsite->visible,
                );

                fputcsv($fp, $fila, ';');
            }

            $saltear = $saltear + $tomar;

            $params = [
                'userCompanyId' => $userCompanyId,
                'texto' => $texto,
                'idReparacion' => $idReparacion,
                'idEstado' => $idEstado,
                'idUsuario' => $idUsuario,
                'visibilidad' => $visibilidad,
                'saltear' => $saltear,
                'tomar' => $tomar,
            ];

            $historialEstadosOnsite = $this->historialEstadoRepository::listar($params);
        }

        fclose($fp);
    }

    public function generarXlsx($texto, $idReparacion, $idEstado, $idUsuario, $visibilidad)
    {
        $saltear = 0;
        $tomar = 5000;
        $userCompanyId = Session::get('userCompanyIdDefault');

        $idUser = Auth::user()->id;

        $filename = "listado_historialestadoonsite" . $idUser . ".csv";

        $data[] = [
            'ID',
            'ID_REPARACION',
            'REPARACION',
            'ID_ESTADO',
            'ESTADO',
            'FECHA',
            'OBSERVACION',
            'ID_USUARIO',
            'USUARIO',
            'VISIBLE',
        ];

        $params = [
            'userCompanyId' => $userCompanyId,
            'texto' => $texto,
            'idReparacion' => $idReparacion,
            'idEstado' => $idEstado,
            'idUsuario' => $idUsuario,
            'visibilidad' => $visibilidad,
            'saltear' => $saltear,
            'tomar' => $tomar,
        ];

        $historialEstadosOnsite = $this->historialEstadoRepository::listar($params);

        while ($historialEstadosOnsite->count()) {

            foreach ($historialEstadosOnsite as $historialEstadoOnsite) {
                $data[] = [
                    'id' => $historialEstadoOnsite->id,
                    'id_reparacion' => $historialEstadoOnsite->id_reparacion,
                    'reparacion' => ($historialEstadoOnsite->reparacion_onsite ? $historialEstadoOnsite->reparacion_onsite->clave : ''),
                    'id_estado' => $historialEstadoOnsite->id_estado,
                    'estado' => ($historialEstadoOnsite->estado_onsite ? $historialEstadoOnsite->estado_onsite->nombre : ''),
                    'fecha' => $historialEstadoOnsite->fecha,
                    'observacion' => $historialEstadoOnsite->observacion,
                    'id_usuario' => $historialEstadoOnsite->id_usuario,
                    'usuario' => ($historialEstadoOnsite->usuario ? $historialEstadoOnsite->usuario->name : ''),
                    'visible' => $historialEstadoOnsite->visible,
                ];
            }

            $saltear = $saltear + $tomar;

            $params = [
                'userCompanyId' => $userCompanyId,
                'texto' => $texto,
                'idReparacion' => $idReparacion,
                'idEstado' => $idEstado,
                'idUsuario' => $idUsuario,
                'visibilidad' => $visibilidad,
                'saltear' => $saltear,
                'tomar' => $tomar,
            ];

            $historialEstadosOnsite = $this->historialEstadoRepository::listar($params);
        }

        $excelController = new GenericExport($data, $filename);
        $excelController->export();
    }
    /**
     * Devuelve el historial de estado de una reparacion onsite
     *
     * @param Request $request
     * @param [int] $idReparacion
     * @return Response
     */
    public function getHistorialEstadosOnsite(Request $request, $idReparacion)
    {
        if ($request->ajax()) {
            $historialEstadosOnsite = $this->historialEstadoRepository->getHistorialPorReparacionOnsite($idReparacion)->get();
            return $historialEstadosOnsite;
            //return response()->json($historialEstadosOnsite);
        }
    }

    /*
	 * Elimina el registro en la tabla de relacion para ocultar el historial para el usuario logueado
	*/
    public function ocultarHistorialEstadoOnsite(HistorialEstadoOnsite $historial_estado_onsite)
    {
        $historial_estados_onsite_oculto_por_user = HistoriaEstadosOnsiteVisiblePorUser::where('users_id', Auth::user()->id)->where('historial_estados_onsite_id', $historial_estado_onsite->id)->first();

        $historial_estados_onsite_oculto_por_user->delete();

        return response()->json([
            "mensaje" => "Historial Estado Onsite: se oculto correctamente el registro: $historial_estado_onsite->id",
        ]);
    }

    public function findHistorialEstadoOnsitePorReparacion($idReparacionOnsite)
    {
        $company_id = Session::get('userCompanyIdDefault');

        $historialEstadoOnsite = HistorialEstadoOnsite::where('company_id', $company_id)
            ->where('id_reparacion', $idReparacionOnsite)
            ->orderBy('id', 'desc')
            ->get();

        return $historialEstadoOnsite;
    }

    public function findHistorialEstadoOnSitePorId($idHistorialEstadoOnsite)
    {
        $company_id = Session::get('userCompanyIdDefault');

        $historialEstadoOnsite = HistorialEstadoOnsite::where('company_id', $company_id)
            ->find($idHistorialEstadoOnsite);

        return $historialEstadoOnsite;
    }

    public function getHistorialEstadosNotas($reparacion_id)
    {
        $data = [];

        $historialEstados = $this->historialEstadoRepository->getHistorialPorReparacionOnsite($reparacion_id)->get();
        foreach ($historialEstados as $historial) {
            $data[] = [
                'id' => $historial->id,
                'id_reparacion' => $historial->id_reparacion,
                'id_estado' => $historial->id_estado,
                'estado_onsite' => ($historial->estado_onsite ? $historial->estado_onsite->nombre : '--'),
                'fecha'  => $historial->fecha,
                'observacion' => $historial->observacion,
                'id_usuario' => $historial->id_usuario,
                'usuario' => ($historial->usuario ? $historial->usuario->name : '--'),
                'usuario_foto_perfil' => ($historial->usuario ? $historial->usuario->foto_perfil : '--'),
            ];
        }
        $notas = $this->notaRepository->getNotasByReparacion($reparacion_id);
        foreach ($notas as $nota) {
            $data[] = [
                'id' => $nota->id,
                'id_reparacion' => $nota->reparacion_onsite_id,
                'id_estado' => 0,
                'estado_onsite' => 'NOTA',
                'fecha'  => $nota->created_at,
                'observacion' => $nota->nota,
                'id_usuario' => $nota->user_id,
                'usuario' => ($nota->usuario ? $nota->usuario->name : '--'),
                'usuario_foto_perfil' => ($nota->usuario ? $nota->usuario->foto_perfil : '--'),
            ];
        }

        //usort($data, fn($a, $b) => $a['fecha'] <=> $b['fecha']);

        /*
        usort($data, function($a, $b) {
            return $a['fecha'] <=> $b['fecha'];
        });
        */

        $this->array_sort_by_column($data, 'fecha', SORT_DESC);

        return $data;
    }

    public function array_sort_by_column(&$arr, $col, $dir = SORT_ASC)
    {
        $sort_col = array();
        foreach ($arr as $key => $row) {
            $sort_col[$key] = $row[$col];
        }

        array_multisort($sort_col, $dir, $arr);
    }
}
