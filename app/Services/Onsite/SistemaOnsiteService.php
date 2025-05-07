<?php

namespace App\Services\Onsite;

use App\Exports\GenericExport;
use App\Models\Onsite\GarantiaOnsite;
use App\Models\Onsite\ReparacionOnsite;
use App\Models\Onsite\SistemaOnsite;
use App\Models\Onsite\SolicitudOnsite;
use App\Models\Onsite\UnidadExteriorOnsite;
use App\Models\Onsite\UnidadInteriorOnsite;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

use App\Services\Onsite\EmpresaOnsiteService;
use App\Services\Onsite\TerminalOnsiteService;
use App\Services\Onsite\UnidadExteriorOnsiteService;
use App\Services\Onsite\UnidadInteriorOnsiteService;
use App\Services\Onsite\ObrasOnsiteService;
use Illuminate\Support\Facades\Auth;

class SistemaOnsiteService
{
    protected $empresaOnsiteService;
    //protected $unidadInteriorOnsiteService;
    //protected $unidadExteriorOnsiteService;
    protected $terminalOnsiteService;
    protected $obrasOnsiteService;

    public function __construct(
        EmpresaOnsiteService $empresaOnsiteService,
        //  UnidadInteriorOnsiteService $unidadInteriorOnsiteService,
        //  UnidadExteriorOnsiteService $unidadExteriorOnsiteService,
        TerminalOnsiteService $terminalOnsiteService,
        ObrasOnsiteService $obrasOnsiteService
    ) {
        $this->empresaOnsiteService = $empresaOnsiteService;
        //$this->unidadInteriorOnsiteService = $unidadInteriorOnsiteService;
        //$this->unidadExteriorOnsiteService = $unidadExteriorOnsiteService;
        $this->terminalOnsiteService = $terminalOnsiteService;
        $this->obrasOnsiteService = $obrasOnsiteService;
    }

    public function getDataIndex()
    {
        $userCompanyId = Session::get('userCompanyIdDefault');
        $userId = Auth::user()->id;

        
        $datos['sistemasOnsite'] = $this->listar($userCompanyId, null, null, null, null, null);
        $datos['obras'] = $this->obrasOnsiteService->listar(null, $userCompanyId, null, null, 'nombre');
        $datos['user_id'] = $userId;

        return $datos;
    }

    public function filtrar(Request $request)
    {
        $userCompanyId = Session::get('userCompanyIdDefault');
        $userId = Auth::user()->id;

        $datos = [
            'texto' => $request['texto'],
            'id_obra' => (isset($request['id_obra']) ? $request['id_obra'] : null),
            'obras' => $this->obrasOnsiteService->listar(null, $userCompanyId, null, null),
            'user_id' => $userId,
            'boton_filtrar' => $request['boton_filtrar'],
        ];

        $sistemasOnsite = $this->listar($userCompanyId, $datos['texto'], null, $datos['id_obra'], null, null);

        $datos['sistemasOnsite'] = $sistemasOnsite;

        if ($request['boton_filtrar'] == 'csv') {
            $this->generarXlsx($userCompanyId, $datos['texto'], $datos['id_obra'], $userId);
        }

        return $datos;
    }

    public function generarXlsx($userCompanyId, $texto, $idObra, $userId)
    {
        $saltear = 0;
        $tomar = 5000;

        $filename = "listado_sistemasonsite_" . $userId . ".xlsx";

        $data[] = [
            'ID',
            'NOMBRE',
            'ID_EMPRESA_ONSITE',
            'EMPRESA_ONSITE',
            'ID_OBRA_ONSITE',
            'OBRA_ONSITE',
            'ID_SUCURSAL_ONSITE',
            'SUCURSAL_ONSITE',
            'ID_COMPRADOR_ONSITE',
            'COMPRADOR_ONSITE',
            'FECHA_COMPRA',
            'NUMERO_FACTURA',
            'COMENTARIOS',
            'CANTIDAD_UNIDADES_EXTERIORES',
            'CANTIDAD_UNIDADES_INTERIORES',
        ];

        $sistemasOnsite = $this->listar($userCompanyId, $texto, null, $idObra, $saltear, $tomar);

        while ($sistemasOnsite->count()) {

            foreach ($sistemasOnsite as $sistemaOnsite) {
                $data[] = [
                    'id' => $sistemaOnsite->id,
                    'nombre' => $sistemaOnsite->nombre,
                    'id_empresa_onsite' => $sistemaOnsite->empresa_onsite_id,
                    'empresa_onsite' => ($sistemaOnsite->empresa_onsite) ? $sistemaOnsite->empresa_onsite->nombre : '',
                    'id_obra_onsite' => $sistemaOnsite->obra_onsite_id,
                    'obra_onsite' => ($sistemaOnsite->obra_onsite) ? $sistemaOnsite->obra_onsite->nombre : '',
                    'id_sucursal_onsite' => $sistemaOnsite->sucursal_onsite_id,
                    'sucursal_onsite' => ($sistemaOnsite->sucursal_onsite) ? $sistemaOnsite->sucursal_onsite->nombre : '',
                    'id_comprador_onsite' => $sistemaOnsite->comprador_onsite_id,
                    'comprador_onsite' => ($sistemaOnsite->comprador_onsite) ? $sistemaOnsite->comprador_onsite->nombre : '',
                    'fecha_compra' => $sistemaOnsite->fecha_compra,
                    'numero_factura' => $sistemaOnsite->numero_factura,
                    'comentarios' => $sistemaOnsite->comentarios,
                    'cantidad_unidades_exteriores' => ($sistemaOnsite->unidades_interiores ? count($sistemaOnsite->unidades_interiores) : 0),
                    'cantidad_unidades_interiores' => ($sistemaOnsite->unidades_exteriores ? count($sistemaOnsite->unidades_exteriores) : 0)
                ];
            }

            $saltear = $saltear + 5000;

            $sistemasOnsite = $this->listar($userCompanyId, $texto, null, $idObra, $saltear, $tomar);
        }

        $excelController = new GenericExport($data, $filename);
        $excelController->export();
    }

    public function getDataCreate()
    {
        $userCompanyId = Session::get('userCompanyIdDefault');
        $datos['empresasOnsite']         =       $this->empresasOnsite($userCompanyId);
        $datos['terminalesOnsite']       =       $this->terminalesOnsite($userCompanyId);
        $datos['sucursalesOnsite']       =       array();
        $datos['company']                =       $userCompanyId;
        return $datos;
    }

    public function getDataEdit($idSistema)
    {
        $userCompanyId = Session::get('userCompanyIdDefault');
        $sistema = $this->sistemaById($idSistema);

        $datos['sistemaEditar']                      =       $sistema;
        $datos['empresasOnsite']                     =       $this->empresasOnsite($userCompanyId);
        $datos['obras']                              =       $this->obrasOnsiteService->listar(null, $userCompanyId, null, 9999);
        $datos['sucursalesOnsite']                   =       array();
        $datos['company']                            =       $userCompanyId;

        $datos['garantias'] = GarantiaOnsite::where('sistema_onsite_id', $idSistema)->get();
        $datos['solicitudes'] = SolicitudOnsite::where('sistema_onsite_id', $idSistema)->get();
        $datos['reparacionesOnsite'] = ReparacionOnsite::where('sistema_onsite_id', $idSistema)->get();

        $datos['unidadesInteriores'] = $this->unidadesInteriorPorSistema($idSistema); //se llama acá para evitar el efecto circular de los service llamados en el __construct
        $datos['unidadesExteriores'] = $this->unidadesExteriorPorSistema($idSistema); //se llama acá para evitar el efecto circular de los service llamados en el __construct

        return $datos;
    }

    public function unidadesInteriorPorSistema($idSistema)
    {
        $unidadesInterior = UnidadInteriorOnsite::where('sistema_onsite_id', $idSistema)->get();

        return $unidadesInterior;
    }

    public function unidadesExteriorPorSistema($idSistema)
    {
        $unidadesExterior = UnidadExteriorOnsite::where('sistema_onsite_id', $idSistema)->get();
        return $unidadesExterior;
    }


    public function getDataShow($idSistema)
    {
        $userCompanyId = Session::get('userCompanyIdDefault');
        $datos['terminalesOnsite']               =       $this->terminalesOnsite($userCompanyId);
        $datos['sistemaShow']                    =       $this->sistemaById($idSistema);
        $datos['terminalesUnidadInterior']       =       null;
        $datos['terminalesUnidadExterior']       =       null;

        return $datos;
    }

    public function destroy($id)
    {
        $sistemaOnsite = SistemaOnsite::find($id);

        if (!in_array($sistemaOnsite->company_id, Session::get('userCompaniesId'))) {
            Session::flash('message-error', 'Sin Privilegios');
            return redirect('/sistemaOnsite');
        }

        if (!$this->verifyDestroy($id)) {
            return false;
        }

        $sistemaOnsite->unidades_interiores()->delete();
        $sistemaOnsite->unidades_exteriores()->delete();

        $sistemaOnsite->delete();

        return $sistemaOnsite;
    }

    public function verifyDestroy($id)
    {
        $message = '';

        $garantiasOnsite = GarantiaOnsite::where('sistema_onsite_id', $id)->get();
        if ($garantiasOnsite->count() > 0) {
            $message .= '<br/>   GARANTIAS: ';
            foreach ($garantiasOnsite as $garantia) {
                $message .= '<br/> - [' . $garantia->id . '] ' . $garantia->nombre;
            }
        }

        $solicitudOnsite = SolicitudOnsite::where('sistema_onsite_id', $id)->get();
        if ($solicitudOnsite->count() > 0) {
            $message .= '<br/>    SOLICITUDES: ';
            foreach ($solicitudOnsite as $solicitud) {
                $message .= '<br/> - [' . $solicitud->id . '] ' . $solicitud->tipo->nombre;
            }
        }

        $visitas = ReparacionOnsite::where('sistema_onsite_id', $id)->get();
        if ($visitas->count() > 0) {
            $message .= '<br/>   VISITAS: ';
            foreach ($visitas as $visita) {
                $message .= '<br/> - [' . $visita->id . '] ' . $visita->clave;
            }
        }

        if ($message != '') {
            Session::flash('message-error', 'No es posible eliminar el sistema, ya que se esta utilizando en Solicitudes, Visitas o Garantías.<br/> Para avanzar el proceso de eliminación de el sistema es necesario eliminar o modificar previamente las siguientes entidades:' . $message);
            return false;
        }

        return true;
    }


    /**
     * Guarda el sistema
     *
     * @param [Request] $request
     * @return SistemaOnsite
     */
    public function store($arraySistemaOnsite)
    {
        /* if (Session::has('userCompanyIdDefault')) {
            $request['company_id'] = Session::get('userCompanyIdDefault');
        } */

        $SistemaOnsite = SistemaOnsite::create($arraySistemaOnsite);

        return $SistemaOnsite;
    }



    public function update(Request $request, $id)
    {

        $SistemaOnsite = $this->updateSistemaOnsite($request, $id);
        $SistemaOnsite = SistemaOnsite::find($id);
        $SistemaOnsite->fill($request->all());
        $SistemaOnsite->save();

        return $SistemaOnsite;
    }

    public function empresasOnsite($userCompanyId)
    {
        $empresasOnsite = $this->empresaOnsiteService->listadoAllBgh($userCompanyId);
        return $empresasOnsite;
    }

    public function sistemasOnsite($userCompanyId)
    {
        $empresasOnsite = SistemaOnsite::where('company_id', $userCompanyId)->get();
        return $empresasOnsite;
    }

    public function sistemaById($sistemaId)
    {
        $SistemaOnsite = SistemaOnsite::find($sistemaId);
        return $SistemaOnsite;
    }

    public function terminalesOnsite($userCompanyId)
    {
        $terminalesOnsite = $this->terminalOnsiteService->listar($userCompanyId, null, null, null, null, null);

        return $terminalesOnsite;
    }
    public function terminalesByTipo($nroTipo)
    {
        $unaTerminal = $this->terminalOnsiteService->findTerminalTipo($nroTipo);
        return $unaTerminal;
    }
    /*
    public function sucursalesPorEmpresa($idEmpresa)
    {
        $sucursales = $this->sucursalOnsiteService->getSucursalesOnsite(null, $idEmpresa);
        return $sucursales;
    }
    */

    public function sistemasPorEmpresa($idEmpresa)
    {
        $sistemas = SistemaOnsite::where('empresa_onsite_id', $idEmpresa)->get();
        return $sistemas;
    }

    public function sistemasPorSucursal($idSucursal)
    {
        $sistemas = SistemaOnsite::where('sucursal_onsite_id', $idSucursal)->get();

        return $sistemas;
    }

    private function updateSistemaOnsite($request, $id)
    {
        if (!isset($request->obra_onsite_id)) {
            Session::flash('message-error', 'El sistema debe tener asignada una obra.');
            return redirect()->back();
        }

        /*campos para sistema onsite*/
        $inputSistema['empresa_onsite_id'] = $request->empresa_onsite_id;
        $inputSistema['obra_onsite_id'] = $request->obra_onsite_id;
        $inputSistema['sucursal_onsite_id'] = $request->sucursal_onsite_id;

        $SistemaOnsite = SistemaOnsite::find($id);

        $SistemaOnsite->fill($inputSistema);
        $SistemaOnsite->save();

        return $SistemaOnsite;
    }

    private function updateUnidadInterior($request, $id)
    {
        /*campos para unidad interior*/
        $id_unidad_interior = $request->id_unidad_interior;

        $input['empresa_onsite_id'] = $request->empresa_onsite_id;
        $input['sucursal_onsite_id'] = $request->sucursal_onsite_id;
        $input['clave'] = $request->clave_interior;
        $input['sistema_onsite_id'] = $id;
        $input['modelo'] = $request->modelo;
        $input['serie'] = $request->serie;
        $input['direccion'] = $request->direccion;
        $input['observaciones'] = $request->observaciones_interior;

        $unidadInteriorOnsite = UnidadInteriorOnsite::find($id_unidad_interior);
        if (isset($unidadInteriorOnsite)) {
            $unidadInteriorOnsite->update($input);
        } else {
            UnidadInteriorOnsite::create($input);
        }

        return $unidadInteriorOnsite;
    }

    private function updateUnidadExterior($request, $id)
    {
        /*campos para unidad exterior*/
        $id_unidad_exterior = $request->id_unidad_exterior;

        $input['empresa_onsite_id'] = $request->empresa_onsite_id;
        $input['sucursal_onsite_id'] = $request->sucursal_onsite_id;
        $input['sistema_onsite_id'] = $id;
        $input['clave'] = $request->clave_exterior;
        $input['medida_figura_1_a'] = $request->medida_figura_1_a;
        $input['medida_figura_1_b'] = $request->medida_figura_1_b;
        $input['medida_figura_1_c'] = $request->medida_figura_1_c;
        $input['medida_figura_1_d'] = $request->medida_figura_1_d;
        $input['medida_figura_2_a'] = $request->medida_figura_2_a;
        $input['medida_figura_2_b'] = $request->medida_figura_2_b;
        $input['medida_figura_2_c'] = $request->medida_figura_2_c;
        $input['observaciones'] = $request->observaciones_exterior;
        if (isset($request->anclaje_piso)) {
            $input['anclaje_piso'] = 1;
        } else {
            $input['anclaje_piso'] = 0;
        }
        if (isset($request->contra_sifon)) {
            $input['contra_sifon'] = 1;
        } else {
            $input['contra_sifon'] = 0;
        }
        if (isset($request->mm_500_ultima_derivacion_curva)) {
            $input['mm_500_ultima_derivacion_curva'] = 1;
        } else {
            $input['mm_500_ultima_derivacion_curva'] = 0;
        }

        $unidadExteriorOnsite = UnidadExteriorOnsite::find($id_unidad_exterior);
        if (isset($unidadExteriorOnsite)) {
            $unidadExteriorOnsite->update($input);
        } else {
            UnidadExteriorOnsite::create($input);
        }

        return $unidadExteriorOnsite;
    }

    public static function listar($userCompanyId, $texto, $empresaOnsiteId, $obraId, $saltear, $tomar)
    {

        $consulta = SistemaOnsite::where('company_id', $userCompanyId);

        if (!empty($texto)) {
            //para forzar acá la clausula Where
            $consulta = $consulta->whereRaw(" CONCAT( COALESCE(sistemas_onsite.id,'') , ' ', COALESCE(sistemas_onsite.company_id,''), ' ', COALESCE(sistemas_onsite.sucursal_onsite_id,''), ' ', COALESCE(sistemas_onsite.empresa_onsite_id,''), ' ', COALESCE(sistemas_onsite.nombre,''), ' ', COALESCE(sistemas_onsite.comentarios,'')) like '%$texto%'");
        }

        if (!empty($empresaOnsiteId)) {
            $consulta = $consulta->whereRaw(" sistemas_onsite.empresa_onsite_id = $empresaOnsiteId ");
        }

        if (!empty($obraId)) {
            $consulta = $consulta->whereRaw("sistemas_onsite.obra_onsite_id = $obraId");
        }

        $consulta = $consulta->orderBy('sistemas_onsite.id', 'desc');

        if ($tomar) {
            return $consulta->skip($saltear)->take($tomar)->get();
        } else {
            return $consulta->paginate(100);
        }
    }

    public function getSistemas($company_id, $id)
    {
        $query = SistemaOnsite::where('company_id', $company_id);

        if ($id !== null) {
            $query->where('id', $id);
        }

        $sistemas = $query->get();

        return $sistemas;
    }

    public function getSistemasFull($company_id, $id)
    {
        $query = SistemaOnsite::where('company_id', $company_id);

        if ($id !== null) {
            $query->where('id', $id);
        }

        $sistemas = $query->get();

        $datosSistemas = [];

        foreach ($sistemas as $sistema) {
            $datosSistemas[] = [
                'id' => $sistema->id,
                'empresa_onsite_id' => $sistema->empresa_onsite_id,
                'empresa_onsite' => ($sistema->empresa_onsite) ? $sistema->empresa_onsite->nombre : null,
                'sucursal_onsite_id' => $sistema->sucursal_onsite_id,
                'sucursal_onsite' => ($sistema->sucursal_onsite) ? $sistema->sucursal_onsite->razon_social : null,
                'obra_onsite_id' => $sistema->obra_onsite_id,
                'obra_onsite' => ($sistema->obra_onsite) ? $sistema->obra_onsite->nombre : null,
                'obra_onsite_id_unificado' => $sistema->obra_onsite_id_unificado,
                'obra_onsite_unificado' => ($sistema->obra_onsite_unificado) ? $sistema->obra_onsite_unificado->nombre : null,
                'comprador_onsite_id' => $sistema->comprador_onsite_id,
                'comprador_onsite' => ($sistema->comprador_onsite) ? $sistema->comprador_onsite->nombre : null,
                'fecha_compra' => $sistema->fecha_compra,
                'numero_factura' => $sistema->numero_factura,
                'nombre' => $sistema->nombre,
                'comentarios' => $sistema->comentarios,
                'created_at' => $sistema->created_at
            ];
        }

        return $datosSistemas;
    }

    public function getSistemaSucursal($sucursal_onsite_id)
    {
        $sistemasOnsite = SistemaOnsite::where('sucrusal_onsite_id', $sucursal_onsite_id)->get();
        return $sistemasOnsite;
    }

    public function getSistemasOnsiteAll()
    {
        $company_id = Session::get('userCompanyIdDefault');
        $sistemasOnsite = SistemaOnsite::where('company_id', $company_id)
            ->get();

        return $sistemasOnsite;
    }

    public function getSistemasPorObra($idObra)
    {
        $sistemas = SistemaOnsite::where('obra_onsite_id', $idObra)->get();

        return $sistemas;
    }

    public function findSistemaOnsite($idSistema)
    {
        $sistemaOnsite = SistemaOnsite::with('unidades_interiores')
            ->find($idSistema);

        return $sistemaOnsite;
    }

    public function getSistemaPorId($idSistema)
    {
        $sistemaOnsite = SistemaOnsite::with('comprador_onsite')
            ->with('unidades_exteriores')
            ->with('unidades_interiores')
            ->with('reparacion_onsite')
            ->find($idSistema);

        return $sistemaOnsite;
    }
}
