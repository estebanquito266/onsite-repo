<?php

namespace App\Services\Onsite;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Onsite\UnidadExteriorOnsite;
use App\Models\Onsite\ImagenUnidadExteriorOnsite;
use App\Models\UserCompany;
use App\Services\Onsite\EmpresaOnsiteService;
use App\Services\Onsite\SucursalOnsiteService;
use App\Services\Onsite\SistemaOnsiteService;
use Log;

class UnidadExteriorOnsiteService
{
    protected $empresaOnsiteService;
    protected $sucursalOnsiteService;
    protected $sistemaOnsiteService;
    protected $obrasOnsiteService;

    public function __construct(
        EmpresaOnsiteService $empresaOnsiteService,
        SucursalOnsiteService $sucursalOnsiteService,
        SistemaOnsiteService $sistemaOnsiteService,
        ObrasOnsiteService $obrasOnsiteService
    ) {
        $this->empresaOnsiteService = $empresaOnsiteService;
        $this->sucursalOnsiteService = $sucursalOnsiteService;
        $this->sistemaOnsiteService = $sistemaOnsiteService;
        $this->obrasOnsiteService = $obrasOnsiteService;
    }

    public function getDataIndex()
    {
        $datos['user_id'] = Auth::user()->id;
        $datos['unidadesExteriores'] = UnidadExteriorOnsite::orderby('id', 'desc')->paginate(30);
        $datos['obrasOnsite'] = $this->obrasOnsiteService->getAllObrasOnsite();
        return $datos;
    }

    public function filtrar(Request $request)
    {
        $company_id = Session::get('userCompanyIdDefault');
        
        $consulta = UnidadExteriorOnsite::where('company_id', $company_id);
        
        if($request['sistema_onsite_id']) {
            $consulta->where('sistema_onsite_id', $request['sistema_onsite_id']);
        }
        if($request['texto']) {
            $texto = $request['texto'];
            $consulta = $consulta->whereRaw(" CONCAT( COALESCE(clave,'') , ' ', COALESCE(modelo,''), ' ', COALESCE(observaciones,''), ' ', COALESCE(serie,'')) like '%$texto%'");
        }        

        $unidadesExteriores = $consulta->orderBy('id', 'desc')->paginate(30);

        $datos['unidadesExteriores'] = $unidadesExteriores;
        $datos['user_id'] = Auth::user()->id;
        $datos['obrasOnsite'] = $this->obrasOnsiteService->getAllObrasOnsite();

        $datos['sistema_onsite_id'] = $request['sistema_onsite_id'];
        $datos['texto'] = $request['texto'];

        return $datos;
    }

    public function createUnidadExterior($sistema_onsite_id)
    {
        //$datos['sistemaOnsite'] = $this->sistemaonsiteService->sistemaById($sistema_onsite_id);
        $datos['sistemaOnsite'] = $this->sistemaOnsiteService->sistemaById($sistema_onsite_id);
        return $datos;
    }

    public function getDataItem($id)
    {
        $datos['unidadExterior'] = UnidadExteriorOnsite::where('id', $id)->first();
        return $datos;
    }

    public function getImagenes($id_unidad_exterior)
    {
        $imagenes = ImagenUnidadExteriorOnsite::where('unidad_exterior_onsite_id', $id_unidad_exterior)->get();
        return $imagenes;
    }

    public function obtenerEmpresas()
    {
        $datos = $this->empresaOnsiteService->listadoAllBgh(Session::get('userCompanyIdDefault'));
        return $datos;
    }

    public function obtenerSucursales($id_empresa)
    {
        $sucursalesOnsite = $this->sucursalOnsiteService->getSucursalEmpresa($id_empresa);
        return response()->json($sucursalesOnsite);
    }

    public function obtenerSistemas($id_sucursal)
    {
        $sistemasOnsite = $this->sistemaOnsiteService->getSistemaSucursal($id_sucursal);
        return response()->json($sistemasOnsite);
    }

    public function store(Request $request)
    {

        $request->validate([
            //'clave' => 'required|unique:unidades_exteriores_onsite|max:255',
            'modelo' => 'required',
            'serie' => 'required',
            'medida_figura_1_a' => 'required',
            'medida_figura_1_b' => 'required',
            'medida_figura_1_c' => 'required',
            'medida_figura_1_d' => 'required',
            'medida_figura_2_a' => 'required',
            'medida_figura_2_b' => 'required',
            'medida_figura_2_c' => 'required',
            'anclaje_piso' => 'numeric'
        ]);

        $input = $request->all();
       

        if (Auth::user()) {                                    
            $company_id = Auth::user()->companies->first()->id;
            $input['company_id'] = $company_id;
        }
        

        $unidadExteriorOnsite = UnidadExteriorOnsite::create($input);
        
        $unidadExteriorOnsite->clave = 'SIS'. $unidadExteriorOnsite->sistema_onsite_id . 'UE'. $unidadExteriorOnsite->id;
        $unidadExteriorOnsite->save();

        return $unidadExteriorOnsite;
    }

    public function update($request, $id)
    {
        $unidadExteriorOnsite = UnidadExteriorOnsite::find($id);

        $input = $request->all();
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

        $unidadExteriorOnsite->update($input);
        return $unidadExteriorOnsite;
    }

    public function destroy($id)
    {
        $unidadExteriorOnsite = UnidadExteriorOnsite::find($id);
        $unidadExteriorOnsite->delete();
        return $unidadExteriorOnsite;
    }

    public function unidadExteriorById($unidad_exterior_onsite_id)
    {
        $unidadExteriorOnsite = UnidadExteriorOnsite::find($unidad_exterior_onsite_id);
        return $unidadExteriorOnsite;
    }

    public function unidadesExteriorPorEmpresa($idEmpresa)
    {
        $unidadesExterior = UnidadExteriorOnsite::where('empresa_onsite_id', $idEmpresa)->get();
        return $unidadesExterior;
    }

    public function unidadesExteriorPorSucursal($idSucursal)
    {
        $unidadesExterior = UnidadExteriorOnsite::where('sucursal_onsite_id', $idSucursal)->get();
        return $unidadesExterior;
    }

    public function unidadesExteriorPorSistema($idSistema)
    {
        $unidadesExterior = UnidadExteriorOnsite::with('sistema_onsite')
        ->with('etiqueta')
        ->where('sistema_onsite_id', $idSistema)
        ->get();
        
        return $unidadesExterior;
    }

    public function imagenesUnidadExteriorById($unidad_exterior_onsite_id)
    {
        $unidadExteriorOnsite = UnidadExteriorOnsite::find($unidad_exterior_onsite_id);
        $imagenesUnidadExteriorOnsite = null;

        if ($unidadExteriorOnsite && $unidadExteriorOnsite->imagenes) {
            $imagenesUnidadExteriorOnsite = $unidadExteriorOnsite->imagenes;
        }

        return $imagenesUnidadExteriorOnsite;
    }

    public function findUltimaUnidadExterior()
    {
        $unidadExterior = UnidadExteriorOnsite::orderBy('id', 'desc')->first();

        return $unidadExterior;
    }

    public function getUnidadesExteriores($company_id)
    {
        $unidadesInteriores = UnidadExteriorOnsite::select(
            'id',
            'empresa_onsite_id',
            'sucursal_onsite_id',
            'sistema_onsite_id',
            'clave',
            'medida_figura_1_a',
            'medida_figura_1_b',
            'medida_figura_1_c',
            'medida_figura_1_d',
            'medida_figura_2_a',
            'medida_figura_2_b',
            'medida_figura_2_c',
            'anclaje_piso',
            'contra_sifon',
            'mm_500_ultima_derivacion_curva',
            'observaciones',
            'modelo',
            'serie',
            'faja_garantia',
            'direccion',
            'created_at'
        )
        ->where('company_id', $company_id)
        ->get();

        return $unidadesInteriores;
    }
}

