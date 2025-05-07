<?php

namespace App\Services\Onsite;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Onsite\UnidadInteriorOnsite;
use App\Models\Onsite\ImagenUnidadInteriorOnsite;
use App\Models\Onsite\SistemaOnsite;
use App\Models\UserCompany;
use App\Services\Onsite\EmpresaOnsiteService;
use App\Services\Onsite\SucursalOnsiteService;
use Log;

class UnidadInteriorOnsiteService
{
    protected $empresaOnsiteService;
    protected $sucursalOnsiteService;
    protected $sistemasOnsiteService;
    protected $obrasOnsiteService;

    public function __construct(
        EmpresaOnsiteService $empresaOnsiteService,
        SucursalOnsiteService $sucursalOnsiteService,
        SistemaOnsiteService $sistemasOnsiteService,
        ObrasOnsiteService $obrasOnsiteService
    ) {
        $this->empresaOnsiteService = $empresaOnsiteService;
        $this->sucursalOnsiteService = $sucursalOnsiteService;
        $this->sistemasOnsiteService = $sistemasOnsiteService;
        $this->obrasOnsiteService = $obrasOnsiteService;
    }

    public function getDataIndex()
    {
        $company_id = Session::get('userCompanyIdDefault');


        $datos['user_id'] = Auth::user()->id;
        $datos['unidadesInteriores'] = UnidadInteriorOnsite::where('company_id', $company_id)->orderBy('id', 'desc')->paginate(30);
        $datos['obrasOnsite'] = $this->obrasOnsiteService->getAllObrasOnsite();
        return $datos;
    }

    public function filtrar(Request $request)
    {
        $company_id = Session::get('userCompanyIdDefault');

        $consulta = UnidadInteriorOnsite::where('company_id', $company_id);

        if ($request['sistema_onsite_id']) {
            $consulta->where('sistema_onsite_id', $request['sistema_onsite_id']);
        }
        if ($request['texto']) {
            $texto = $request['texto'];
            $consulta = $consulta->whereRaw(" CONCAT( COALESCE(clave,'') , ' ', COALESCE(modelo,''), ' ', COALESCE(direccion,''), ' ', COALESCE(serie,'')) like '%$texto%'");
        }

        $unidadesInteriores = $consulta->orderBy('id', 'desc')->paginate(30);

        $datos['unidadesInteriores'] = $unidadesInteriores;
        $datos['user_id'] = Auth::user()->id;
        $datos['obrasOnsite'] = $this->obrasOnsiteService->getAllObrasOnsite();
        $datos['sistema_onsite_id'] = $request['sistema_onsite_id'];
        $datos['texto'] = $request['texto'];

        return $datos;
    }

    public function createUnidadInterior($sistema_onsite_id)
    {
        $datos['sistemaOnsite'] = $this->sistemasOnsiteService->sistemaById($sistema_onsite_id);

        return $datos;
    }

    public function getDataItem($id)
    {
        $datos['unidadInterior'] = UnidadInteriorOnsite::where('id', $id)->first();
        return $datos;
    }

    public function getImagenes($id_unidad_interior)
    {
        $imagenes = ImagenUnidadInteriorOnsite::where('unidad_interior_onsite_id', $id_unidad_interior)->get();
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
        $sistemasOnsite = $this->sistemasOnsiteService->getSistemaSucursal($id_sucursal);
        return response()->json($sistemasOnsite);
    }

    /**
     * Store
     *
     * @param Request $request
     * @return UnidadInteriorOnsite
     */
    public function store(Request $request)
    {
        $request->validate([
            'modelo' => 'required',
            'serie' => 'required',
        ]);

        $input = $request->all();
        $input['clave'] = 1; //TODO: si la clave a a ser igual al id lo deberia manejar MySQL


        if (Auth::user()) {
            $company_id = Auth::user()->companies->first()->id;
            $input['company_id'] = $company_id;
        }

        $unidadInteriorOnsite = UnidadInteriorOnsite::create($input);

        //TODO: si la clave a a ser igual al id lo deberia manejar MySQL
        $unidadInteriorOnsite->clave = 'SIS' . $unidadInteriorOnsite->sistema_onsite_id . 'UI' . $unidadInteriorOnsite->id;
        $unidadInteriorOnsite->save();


        return $unidadInteriorOnsite;
    }

    public function update(Request $request, $id)
    {
        $unidadInteriorOnsite = UnidadInteriorOnsite::find($id);
        $input = $request->all();
        $unidadInteriorOnsite->update($input);
        return $unidadInteriorOnsite;
    }

    public function destroy($id)
    {
        $unidadInteriorOnsite = UnidadInteriorOnsite::find($id);
        $unidadInteriorOnsite->delete();
        return $unidadInteriorOnsite;
    }

    public function unidadInteriorById($unidad_interior_onsite_id)
    {
        $unidadInteriorOnsite = UnidadInteriorOnsite::find($unidad_interior_onsite_id);
        return $unidadInteriorOnsite;
    }

    public function unidadesInteriorPorEmpresa($idEmpresa)
    {
        $unidadesInterior = UnidadInteriorOnsite::where('empresa_onsite_id', $idEmpresa)->get();
        return $unidadesInterior;
    }

    public function unidadesInteriorPorSucursal($idSucursal)
    {
        $unidadesInterior = UnidadInteriorOnsite::where('sucursal_onsite_id', $idSucursal)->get();
        return $unidadesInterior;
    }

    public function unidadesInteriorPorSistema($idSistema)
    {
        $unidadesInterior = UnidadInteriorOnsite::with('sistema_onsite')
            ->with('etiqueta')
            ->where('sistema_onsite_id', $idSistema)->get();
        return $unidadesInterior;
    }

    public function imagenesUnidadInteriorById($unidad_interior_onsite_id)
    {
        $unidadInteriorOnsite = UnidadInteriorOnsite::find($unidad_interior_onsite_id);
        $imagenesUnidadInteriorOnsite = null;

        if ($unidadInteriorOnsite && $unidadInteriorOnsite->imagenes) {
            $imagenesUnidadInteriorOnsite = $unidadInteriorOnsite->imagenes;
        }

        return $imagenesUnidadInteriorOnsite;
    }

    public function getUnidadesInteriores($company_id)
    {
        $unidadesInteriores = UnidadInteriorOnsite::select(
            'id',
            'empresa_onsite_id',
            'sucursal_onsite_id',
            'sistema_onsite_id',
            'clave',
            'modelo',
            'direccion',
            'faja_garantia',
            'serie',
            'observaciones',
            'created_at'
        )
            ->where('company_id', $company_id)
            ->get();

        return $unidadesInteriores;
    }
}
