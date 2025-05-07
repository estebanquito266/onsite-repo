<?php

namespace App\Http\Controllers\Onsite;

use App\Http\Controllers\Controller;
use App\Services\Onsite\CompradoresOnsiteService;
use App\Services\Onsite\EmpresasInstaladorasServices;
use App\Services\Onsite\GarantiasOnsiteService;
use App\Services\Onsite\GarantiasTiposService;
use App\Services\Onsite\LocalidadOnsiteService;
use App\Services\Onsite\ObrasOnsiteService;
use App\Services\Onsite\ProvinciasService;
use App\Services\Onsite\ReparacionOnsiteService;
use App\Services\Onsite\SistemaOnsiteService;
use App\Services\Onsite\UnidadExteriorOnsiteService;
use App\Services\Onsite\UnidadInteriorOnsiteService;
use App\Services\Onsite\UserService;
use App\Services\Onsite\VisitasService;
use Illuminate\Http\Request;
use Log;
use Dompdf\Options;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class GarantiaOnsiteController extends Controller
{
    protected $userCompanyId;
    protected $garantiasOnsiteService;
    protected $obrasOnsiteService;
    protected $userService;
    protected $provinciasService;
    protected $compradoresOnsiteService;
    protected $empresasInstaladorasService;
    protected $unidadInteriorService;
    protected $unidadExteriorService;
    protected $localidadesService;
    protected $tiposGarantiaService;
    protected $reparacionesService;
    protected $visitasService;
    protected $sistemaService;

    public function __construct(
        GarantiasOnsiteService $garantiasOnsiteService,
        ObrasOnsiteService $obrasOnsiteService,
        UserService $userService,
        ProvinciasService $provinciasService,
        CompradoresOnsiteService $compradoresOnsiteService,
        EmpresasInstaladorasServices $empresasInstaladorasService,
        UnidadInteriorOnsiteService $unidadInteriorService,
        UnidadExteriorOnsiteService $unidadExteriorService,
        LocalidadOnsiteService $localidadesService,
        GarantiasTiposService $tiposGarantiaService,
        ReparacionOnsiteService $reparacionesService,
        VisitasService $visitasService,
        SistemaOnsiteService $sistemaService

    ) {
        $this->userCompanyId =  session()->get('userCompanyIdDefault');

        $this->garantiasOnsiteService = $garantiasOnsiteService;
        $this->obrasOnsiteService = $obrasOnsiteService;
        $this->userService = $userService;
        $this->provinciasService = $provinciasService;
        $this->compradoresOnsiteService = $compradoresOnsiteService;
        $this->empresasInstaladorasService = $empresasInstaladorasService;
        $this->unidadInteriorService  = $unidadInteriorService;
        $this->unidadExteriorService = $unidadExteriorService;
        $this->localidadesService = $localidadesService;
        $this->tiposGarantiaService = $tiposGarantiaService;
        $this->reparacionesService = $reparacionesService;
        $this->visitasService = $visitasService;
        $this->sistemaService = $sistemaService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['garantias'] = $this->garantiasOnsiteService->getDataList();
        return view('_onsite/garantiaonsite.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = $this->getDataList();


        return view('_onsite/garantiaonsite.create', $data);
    }




    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request['company_id'] = $this->userCompanyId;

        $request->validate([
            'primer_nombre' => 'required',
            'apellido' => 'required',
            'localidad' => 'required',
            'pais' => 'required'
        ]);

        $request['nombre'] = $request['primer_nombre'] . ', ' . $request['apellido'];

        if ($request['pais'] == 'Argentina') {
            $request->validate([
                'provincia_onsite_id' => 'required',
            ]);

            $request['localidad_onsite_id'] = $request['localidad'];
        } else {
            $request['localidad_texto'] = $request['localidad'];
            $request['provincia_onsite_id'] = 26; //"desconocida" en producción
        };

        $request->validate(
            [
                //garantia onsite
                'fecha' => 'required',
                'garantia_tipo_onsite_id' => 'required',
                'nombre' => 'required',
                'empresa_instaladora_id' => 'required',
                'obra_onsite_id' => 'required',
                'sistema_onsite_id' => 'required',

                //comprador onsite
                //'dni' => 'required | unique:compradores_onsite,dni',
                'dni' => 'required',
                'domicilio' => 'required',
                'email' => 'required',
                'celular' => 'required'
            ]
        );
        /* crea o actualiza comprador onsite luego de controlar si exsite por DNI */
        $comprador_dni = $request['dni'];
        $comprador = $this->compradoresOnsiteService->findCompradorPorDni($comprador_dni);

        if (is_null($comprador)) {
            $compradorOnsite = $this->compradoresOnsiteService->store($request);
            $mensaje = 'Comprador Registrado con el DNI Nº' . $compradorOnsite->dni;
        } else {
            $compradorOnsite = $this->compradoresOnsiteService->update($request, $comprador->id);
            $mensaje = 'Comprador Actualizado. DNI Nº' . $compradorOnsite->dni;
        }

        $compradorOnsiteId = $compradorOnsite->id;

        $tipo = '';
        if ($request['garantia_tipo_onsite_id'] == 1) $tipo = 'Comp.';
        if ($request['garantia_tipo_onsite_id'] == 3 || $request['garantia_tipo_onsite_id'] == 4) $tipo = 'Parc.';
        if ($request['garantia_tipo_onsite_id'] == 2) $tipo = 'C/Obs.';

        $request['comprador_onsite_id'] = $compradorOnsiteId;
        $request['nombre'] = '[G-' . $tipo . ' - ' . $request['sistema_onsite_id'] . '] ' . $request['sistema_nombre'];

        $garantiaOnsite = $this->garantiasOnsiteService->store($request);

        $mensaje .= '. Garantía creada con la identificación: ' . $garantiaOnsite->nombre;

        if ($request->ajax()) { //se agrega p/ Jquery Ajax de reparaciones
            return response()->json([
                "mensaje" => $mensaje,
                "id" => $garantiaOnsite->id,
                "nombre" => $garantiaOnsite->nombre,
            ]);
        }

        Session::flash('message', $mensaje);

        return redirect('/garantiaonsite');
    }

    public function makeHtmlGarantiaPdf($garantiaOnsite)
    {
        /* $html = '<link rel="stylesheet" href="/assets/css/base.min.css" type="text/css">';
        $html .= '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/css/tempusdominus-bootstrap-4.min.css" />'; */
        $html = '
        <style>
            .customers {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
            }
            .customers td, .customers th {
            border: 1px solid #ddd;
            padding: 8px;
            }
            .customers tr:nth-child(even){background-color: #f2f2f2;}
            .customers tr:hover {background-color: #ddd;}
            .customers th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            background-color: #354a43;
            color: white;
            }
        </style>';

        $html .= $garantiaOnsite['comprobante'];


        return $html;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = $this->getDataList();

        $data['garantiaOnsite'] = $this->garantiasOnsiteService->getData($id);

        return view('_onsite/garantiaonsite.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request['company_id'] = $this->userCompanyId;

        $request->validate([
            'primer_nombre' => 'required',
            'apellido' => 'required',
            'localidad' => 'required',
            'pais' => 'required'
        ]);

        $request['nombre'] = $request['primer_nombre'] . ', ' . $request['apellido'];

        if ($request['pais'] == 'Argentina') {
            $request->validate([
                'provincia_onsite_id' => 'required',
            ]);

            $request['localidad_onsite_id'] = $request['localidad'];
        } else {
            $request['localidad_texto'] = $request['localidad'];
            $request['provincia_onsite_id'] = 26; //"desconocida" en producción
        };

        $request->validate(
            [
                //garantia onsite
                //'company_id' => 'required',
                'fecha' => 'required',
                'garantia_tipo_onsite_id' => 'required',
                'nombre' => 'required',
                'empresa_instaladora_id' => 'required',
                'obra_onsite_id' => 'required',
                'sistema_onsite_id' => 'required',


                //comprador onsite
                'primer_nombre' => 'required',
                'apellido' => 'required',
                'dni' => 'required',
                'pais' => 'required',
                'provincia_onsite_id' => 'required',
                'localidad' => 'required',
                'domicilio' => 'required',
                'email' => 'required',
                'celular' => 'required',

            ]

        );

        if ($request['garantia_tipo_onsite_id'] == 1) $tipo = 'Comp.';
        if ($request['garantia_tipo_onsite_id'] == 3 || $request['garantia_tipo_onsite_id'] == 4) $tipo = 'Parc.';
        if ($request['garantia_tipo_onsite_id'] == 2) $tipo = 'C/Obs.';
        $request['nombre'] = '[G-' . $tipo . ' - ' . $request['sistema_onsite_id'] . '] ' . $request['sistema_nombre'];
        $garantiaOnsite = $this->garantiasOnsiteService->update($request, $id);

        $request['localidad_onsite_id'] = $request['localidad'];
        $request['nombre'] = $request['primer_nombre'] . ', ' . $request['apellido'];

        if ($garantiaOnsite->comprador_onsite->dni !== $request['dni']) {
            $compradorOnsite = $this->compradoresOnsiteService->store($request);
            $garantiaOnsite->comprador_onsite_id = $compradorOnsite->id;
            $garantiaOnsite->save();
        } else {
            $compradorOnsite = $this->compradoresOnsiteService->update($request, $garantiaOnsite->comprador_onsite_id);
        }

        Session::put('message', 'Registro actualizado correctamente. ID: ' . $garantiaOnsite->id);

        return redirect('/garantiaonsite');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->garantiasOnsiteService->destroy($id);

        $mjeDelete = 'Garantía ID:' . $id . ' - registro eliminado correctamente!';

        return redirect('/garantiaonsite')->with('message', $mjeDelete);
    }

    public function setRequestGarantiaYComprador($request)
    {
    }

    public function garantiaOnsiteEmitir($idGarantia)
    {
        $garantiaOnsite = $this->garantiasOnsiteService->garantiaOnsiteEmitir($idGarantia);

        $comprobanteGarantia = $this->garantiasOnsiteService->generaComprobanteGarantia($garantiaOnsite);

        $data = [
            'comprobante' => $comprobanteGarantia
        ];

        $html = $this->makeHtmlGarantiaPdf($data);

        $pdf = App::make('dompdf.wrapper');

        $pdf->loadHTML($html);



        return $pdf->stream();
    }

    public function getObrasPorEmpresa($empresaInstaladoraId)
    {
        $obrasOnsite = $this->obrasOnsiteService->getAllObrasOnsitePorEmpresaUser($empresaInstaladoraId);

        return response()->json($obrasOnsite, 200);
    }

    public function getUnidadesPorSistema($idSistema)
    {
        $unidadesInteriores = $this->unidadInteriorService->unidadesInteriorPorSistema($idSistema);
        $unidadesExteriores = $this->unidadExteriorService->unidadesExteriorPorSistema($idSistema);

        $sistemas = [
            'unidades_interiores' => $unidadesInteriores,
            'unidades_exteriores' => $unidadesExteriores
        ];

        return response()->json($sistemas, 200);
    }

    public function getDataList()
    {
        $idUser = session()->get('idUser');
        $user = $this->userService->findUser($idUser);
        /* $empresaInstaladoraId = $user->empresa_instaladora[0]->id; */

        $obrasOnsite = $this->obrasOnsiteService->getAllObrasOnsite();
        $provincias = $this->provinciasService->findProvinciasAll();
        $localidades = $this->localidadesService->getAllLocalidades();
        $tiposGarantia = $this->tiposGarantiaService->getAllGarantiasTipos();


        $empresasInstaladoras = $this->empresasInstaladorasService->getEmpresasInstaladoras();

        $data = [
            'obrasOnsite' => $obrasOnsite,
            'user' => $user,
            'provincias' => $provincias,
            'empresasInstaladoras' => $empresasInstaladoras,
            'localidades' => $localidades,
            'garantiasTipos' => $tiposGarantia
        ];

        return $data;
    }

    public function createGarantiaFromReparacion($reparacionId)
    {
        $reparacion = $this->visitasService->findVisita($reparacionId);

        if (!$reparacion){
            return redirect('/visitasOnsite')->with('message-error', 'Visita no encontrada');
        }

        if (!$reparacion->sistema_onsite){
            return redirect('/visitasOnsite')->with('message-error', 'Sistema onsite no encontrado');
        }

        if (!$reparacion->sistema_onsite->obra_onsite){
            return redirect('/visitasOnsite')->with('message-error', 'Obra onsite no encontrada');
        }

        $sistema_onsite_id = $reparacion->sistema_onsite_id;
        $obra_onsite_id = $reparacion->sistema_onsite->obra_onsite_id;



        $empresa_instaladora_id = $reparacion->sistema_onsite->obra_onsite->empresa_instaladora_id;

        Session::flash(
            '_old_input.sistema_onsite_id',
            $sistema_onsite_id

        );
        Session::flash(
            '_old_input.obra_onsite_id',
            $obra_onsite_id

        );

        Session::flash(
            '_old_input.empresa_instaladora_id',
            $empresa_instaladora_id

        );


        $data = $this->getDataList();


        return view('_onsite/garantiaonsite.create', $data);
    }

    public function createGarantiaFromSistema($idSistema)
    {


        $sistema_onsite = $this->sistemaService->findSistemaOnsite($idSistema);
        $obra_onsite_id = $sistema_onsite->obra_onsite_id;

        $empresa_instaladora_id = $sistema_onsite->obra_onsite->empresa_instaladora_id;

        Session::flash(
            '_old_input.sistema_onsite_id',
            $sistema_onsite->id

        );
        Session::flash(
            '_old_input.obra_onsite_id',
            $obra_onsite_id

        );

        Session::flash(
            '_old_input.empresa_instaladora_id',
            $empresa_instaladora_id

        );


        $data = $this->getDataList();


        return view('_onsite/garantiaonsite.create', $data);
    }
}
