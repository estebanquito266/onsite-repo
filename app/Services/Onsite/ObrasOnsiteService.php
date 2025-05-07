<?php

namespace App\Services\Onsite;

use App\Enums\Prioridad;
use App\Exports\GenericExport;
use App\Models\EmpresaInstaladora\EmpresaInstaladoraUser;
use App\Models\Onsite\EstadoSolicitudOnsite;
use App\Models\Onsite\GarantiaOnsite;
use App\Models\Onsite\ObraOnsite;
use App\Models\Onsite\ObraChecklistOnsite;
use App\Models\Onsite\SolicitudOnsite;
use App\Models\Onsite\TipoImagenOnsite;
use App\Models\Provincia;
use App\Models\User;
use App\Models\viewObrasConReparaciones;
use App\Repositories\Onsite\EstadoOnsiteRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Log;
use PhpParser\Node\Stmt\Foreach_;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ObrasOnsiteService
{
    protected $tipoImagenService;
    protected $userService;
    protected $provinciasService;
    protected $localidadesService;
    protected $solicitudesTiposServices;
    protected $empresaInstaladoraService;
    protected $empresaOnsiteService;
    protected $funcionesAuxiliaresService;
    protected $imagenesObraOnsiteServices;
    protected $tiposServiciosService;
    protected $estado_onsite_repository;


    protected $userCompanyId;

    public function __construct(
        TiposImageOnsiteService $tipoImagenService,
        UserService $userService,
        ProvinciasService $provinciasService,
        LocalidadOnsiteService $localidadesService,
        SolicitudesTiposService $solicitudesTiposServices,
        EmpresasInstaladorasServices $empresaInstaladoraService,
        EmpresaOnsiteService $empresaOnsiteService,
        FuncionesAuxiliaresOnsiteService $funcionesAuxiliaresService,
        ImagenObraOnsiteService $imagenesObraOnsiteServices,
        TiposServiciosService $tiposServiciosService,
        EstadoOnsiteRepository $estado_onsite_repository

    ) {
        $this->userCompanyId =  session()->get('userCompanyIdDefault');

        $this->tipoImagenService = $tipoImagenService;
        $this->userService = $userService;
        $this->provinciasService = $provinciasService;
        $this->localidadesService = $localidadesService;
        $this->solicitudesTiposServices = $solicitudesTiposServices;
        $this->empresaInstaladoraService = $empresaInstaladoraService;
        $this->empresaOnsiteService = $empresaOnsiteService;
        $this->funcionesAuxiliaresService = $funcionesAuxiliaresService;
        $this->imagenesObraOnsiteServices = $imagenesObraOnsiteServices;
        $this->tiposServiciosService = $tiposServiciosService;
        $this->estado_onsite_repository = $estado_onsite_repository;
    }

    public function getDataIndex()
    {
        $company_id = Session::get('userCompanyIdDefault');

        $perfilAdminOnsite = Session::get('perfilAdminOnsite');
        $perfilAdmin = Session::get('perfilAdmin');

        if ($perfilAdminOnsite == true || $perfilAdmin == true) {
            $obrasOnsite = $this->listar(null, $company_id, null, null);
        } else {
            $idUser = session()->get('idUser');
            $user = $this->userService->findUser($idUser);

            if (isset($user->empresa_instaladora) && count($user->empresa_instaladora) > 0) {
                $idEmpresaInstaladora = $user->empresa_instaladora[0]->id;
                $obrasOnsite = $this->getObraOnsitePorEmpresaInstaladora($idEmpresaInstaladora);
            } else {

                return false;
            }
        }

        $data = [
            'obrasOnsite' => $obrasOnsite
        ];

        return $data;
    }

    public function store($request)
    {


        /* %CAMPOS_CHECKBOX% */
        $request['requiere_zapatos_seguridad'] = $request['requiere_zapatos_seguridad'] ? 1 : 0;
        $request['requiere_casco_seguridad'] = $request['requiere_casco_seguridad'] ? 1 : 0;
        $request['requiere_proteccion_visual'] = $request['requiere_proteccion_visual'] ? 1 : 0;
        $request['requiere_proteccion_auditiva'] = $request['requiere_proteccion_auditiva'] ? 1 : 0;
        $request['requiere_art'] = $request['requiere_art'] ? 1 : 0;
        $request['clausula_no_arrepentimiento'] = $request['clausula_no_arrepentimiento'] ? 1 : 0;



        /* if ($request->hasFile('esquemas')) {
            foreach ($request->file('esquemas') as $file) {
                $request['esquema'] = $this->store_image($file, $request['nombre']);
            }
        }

        if (isset($request['esquema']) || isset($request['esquema_archivo'])) {
            if ($request->hasFile('esquema_archivo')) {
                $request['esquema'] = $this->store_image($request->file('esquema_archivo'), $request['nombre']);
            }
        } */

        if (Session::has('userCompanyIdDefault')) {
            $request['company_id'] = Session::get('userCompanyIdDefault');
        }

        if ($request['clave'] == NULL || !isset($request['clave'])) {
            if (isset($request['nombre'])) {
                $request['clave'] = $this->getClave($request['nombre']);
            }
        }

        if (isset($request['pais']) && $request['pais'] == 'Argentina') {

            if ($request['provincia_onsite_id'])
                $provincia = Provincia::find($request['provincia_onsite_id']);
            else
                $provincia = Provincia::find($request['provincia']);

            $domicilio_concat = $request['domicilio'] . ' - ' . $request['pais'] . ' - ' . $provincia->nombre . ' - ' . $request['localidad'];
            $request['domicilio'] = $domicilio_concat;
        } else {

            $domicilio_concat = $request['domicilio'] . ' - ' . $request['pais'] . ' - ' . $request['localidad'];
            $request['domicilio'] = $domicilio_concat;
        }

        /* empresa instaladora */
        if (!isset($request['empresa_instaladora_id']) || $request['empresa_instaladora_id'] < 2) {
            $array_empresa = [
                'nombre' => $request->get('empresa_instaladora_nombre'),
                'pais' => $request->get('pais'),
                'provincia_onsite_id' => 26,
                'localidad_onsite_id' => 1,
                'email' => $request->get('responsable_email'),
                'celular' => $request->get('responsable_telefono'),
                'company_id' => Session::get('userCompanyIdDefault')
            ];

            $empresa_instaladora = $this->empresaInstaladoraService->store($array_empresa);


            $request['empresa_instaladora_id'] = $empresa_instaladora->id;
        }

        $obraOnsite = ObraOnsite::create($request->all());

        $request['obra_onsite_id'] = $obraOnsite->id;

        $obraChecklistOnsite = ObraChecklistOnsite::create($request->all());

        $request['tipo_imagen_onsite_id'] = TipoImagenOnsite::TRABAJO;

        if ($request->hasFile('esquemas')) {
            foreach ($request->file('esquemas') as $file) {
                $request['file'] = $file;
                $imagenesObraOnsite = $this->imagenesObraOnsiteServices->store($request);
            }
        }


        return $obraOnsite;
    }

    public function storeObra($request)
    {
        $obraOnsite = ObraOnsite::create($request->all());
        $request['obra_onsite_id'] = $obraOnsite->id;

        $request['tipo_imagen_onsite_id'] = TipoImagenOnsite::TRABAJO;

        if ($request->hasFile('esquema_archivo')) {
            foreach ($request->file('esquema_archivo') as $file) {
                $request['file'] = $file;
                $imagenesObraOnsite = $this->imagenesObraOnsiteServices->store($request);
            }
        }


        /* if ($request->hasFile('esquemas')) {
            foreach ($request->file('esquemas') as $file) {
                //$request['esquema'] = $this->store_image($file, $request['nombre']);
                $request['file'] = $file;
                $imagenesObraOnsite = $this->imagenesObraOnsiteServices->store($request);
            }
        } */

        /* if (isset($request['esquema_archivo'])) {
            if ($request->hasFile('esquema_archivo')) {
                //$request['esquema'] = $this->store_image($request->file('esquema_archivo'), $request['nombre']);
                foreach ($request->file('esquema_archivo') as $file) {
                    //$request['esquema'] = $this->store_image($file, $request['nombre']);
                    $request['file'] = $file;
                    $imagenesObraOnsite = $this->imagenesObraOnsiteServices->store($request);
                }
            }
        } */

        /* if (isset($request['esquema'])) {
            if ($request->hasFile('esquema')) {
                //$request['esquema'] = $this->store_image($request->file('esquema'), $request['nombre']);
                foreach ($request->file('esquema') as $file) {
                    //$request['esquema'] = $this->store_image($file, $request['nombre']);
                    $request['file'] = $file;
                    $imagenesObraOnsite = $this->imagenesObraOnsiteServices->store($request);
                }
            }
        } */




        return $obraOnsite;
    }

    public function storeCheckListObra($request)
    {

        $obraChecklistOnsite = ObraChecklistOnsite::create($request->all());

        return $obraChecklistOnsite;
    }


    public function getDataEdit($id)
    {
        $obraOnsite = $this->findObraOnsite($id);
        $sistemas = $obraOnsite->sistemas_onsite;
        $solicitudes = $obraOnsite->solicitud_onsite;
        $garantias = GarantiaOnsite::where('obra_onsite_id',$id)->get();
        $unidades = $this->countUnidadesPorObra($obraOnsite);
        $obraChecklistOnsite = $this->findObraCheckListPorIdObra($id);
        $imagenes = $this->imagenesObraOnsiteServices->listImagenesObraOnsitePorObra($id);

        $deleteMsj = '';

        if($sistemas->count() > 0)
        {
            $deleteMsj .= '<br/><b>Sistemas:</b><br/>';
            foreach ($sistemas as $sistema) 
            {
                $deleteMsj .= '- '.$sistema->nombre.'<br/>';
            }    
        }

        if($unidades['cantUI'] > 0 || $unidades['cantUE'] > 0)
        {
            $deleteMsj .= '<br/><b>Unidades:</b><br/>';
            $deleteMsj .= $unidades['cantUI'].' Unidades Internas<br/>';
            $deleteMsj .= $unidades['cantUE'].' Unidades Externas<br/>';
        }        
        
        if($imagenes->count() > 0)
        {
            $deleteMsj .= '<br/> <b>'.$imagenes->count().' imagenes </b><br/>';
            
        }
        
        if(isset($obraChecklistOnsite))
        {
            $deleteMsj .= '<br/> <b>Obra Checklist</b> <br/> ' . $obraChecklistOnsite->id.'-'.$obraChecklistOnsite->razon_social.'</b>';
        }

        $datos = [
            'obraOnsite' => $obraOnsite,
            'obraChecklistOnsite' => $obraChecklistOnsite,
            'imagenes' => $imagenes,
            'sistemas' => $sistemas,
            'solicitudes' => $solicitudes,
            'garantias' => $garantias,
            'deleteMsj' => ($deleteMsj != '' ? ( $deleteMsj = 'A esta obra se encuentran asociados:<br/>'.$deleteMsj) : '')
        ];

        return $datos;
    }

    public function update($request, $idObraOnsite)
    {
        //TODO validar campos checkbox
        $request['requiere_zapatos_seguridad'] = $request['requiere_zapatos_seguridad'] ? 1 : 0;
        $request['requiere_casco_seguridad'] = $request['requiere_casco_seguridad'] ? 1 : 0;
        $request['requiere_proteccion_visual'] = $request['requiere_proteccion_visual'] ? 1 : 0;
        $request['requiere_proteccion_auditiva'] = $request['requiere_proteccion_auditiva'] ? 1 : 0;
        $request['requiere_art'] = $request['requiere_art'] ? 1 : 0;
        $request['clausula_no_arrepentimiento'] = $request['clausula_no_arrepentimiento'] ? 1 : 0;
        $request['vip'] = $request['vip'] ? 1 : 0;


        $obraOnsite = $this->findObraOnsite($idObraOnsite);
        $obraChecklistOnsite = $this->findObraCheckListPorIdObra($idObraOnsite);

        if (isset($request['esquema']) || isset($request['esquema_archivo'])) {
            if ($request->hasFile('esquema_archivo')) {
                $this->delete_image($obraOnsite->esquema);
                $request['esquema'] = $this->store_image($request->file('esquema_archivo'), $request['nombre']);
            }
        }


        $obraOnsite->update($request->all());

        $request['obra_onsite_id'] = $obraOnsite->id;
        $this->comprobar_checkbox($request);

        if($obraChecklistOnsite)
        {
            $obraChecklistOnsite->update($request->all());
        }

        return $obraOnsite;
    }

    public function destroy($id)
    {
        $obraOnsite = $this->findObraOnsite($id);
        $obraChecklistOnsite = $obraOnsite->obraChecklistOnsite;

        if(!$this->verifyDestroy($id)) {
            return false;
        }       

        $sistemas = $obraOnsite->sistema_onsite;

        foreach ($sistemas as $sistemaOnsite) 
        {
            $sistemaOnsite->unidades_interiores()->delete();
            $sistemaOnsite->unidades_exteriores()->delete();
    
            $sistemaOnsite->delete();
        }

        if ($obraChecklistOnsite) {
            $obraChecklistOnsite->delete();
        }

        if ($obraOnsite) {
            if ($obraOnsite->esquema) {
                $this->delete_image($obraOnsite->esquema);
            }
            $obraOnsite->delete();
        }

        return $obraOnsite;     
    }

    public function verifyDestroy($id)
	{
        $message = '';

        $garantiasOnsite = GarantiaOnsite::where('obra_onsite_id',$id)->get();
        if($garantiasOnsite->count() > 0) {
            $message .= '<br/>   GARANTIAS: ';
            foreach ($garantiasOnsite as $garantia) {
                $message .= '<br/> - ['.$garantia->id. '] ' . $garantia->nombre;
            }
        }

        $solicitudOnsite = SolicitudOnsite::where('obra_onsite_id',$id)->get();
        if($solicitudOnsite->count() > 0) {
            $message .= '<br/>    SOLICITUDES: ';
            foreach ($solicitudOnsite as $solicitud) {
                $message .= '<br/> - ['.$solicitud->id. '] ' . $solicitud->tipo->nombre;
            }
        }
        
		if ($message != '') {
			Session::flash('message-error', 'No es posible eliminar la obra, ya que se esta utilizando en Solicitudes o Garantías.<br/> Para avanzar el proceso de eliminación de la obra es necesario eliminar o modificar previamente las siguientes entidades:'.$message);
			return false;
		}

		return true;
	}

    public function store_image($file, $nombre = 'esquema_obra')
    {
        $archivo = $file;

        $nombreArchivo = $this->tipoImagenService->getCustomFilename('obra_onsite', $file->getClientOriginalName(), $nombre);

        Storage::disk('local2')->put($nombreArchivo, File::get($archivo));

        return $nombreArchivo;
    }

    public function delete_image($esquema)
    {
        Storage::disk('local2')->delete($esquema);
    }
    public function comprobar_checkbox($request)
    {
        $request['requiere_zapatos_seguridad'] = $request['requiere_zapatos_seguridad'] ? $request['requiere_zapatos_seguridad'] : 0;
        $request['requiere_casco_seguridad'] = $request['requiere_casco_seguridad'] ? $request['requiere_casco_seguridad'] : 0;
        $request['requiere_proteccion_visual'] = $request['requiere_proteccion_visual'] ? $request['requiere_proteccion_visual'] : 0;
        $request['requiere_proteccion_auditiva'] = $request['requiere_proteccion_auditiva'] ? $request['requiere_proteccion_auditiva'] : 0;
        $request['requiere_art'] = $request['requiere_art'] ? $request['requiere_art'] : 0;
        $request['requiere_zapatos_seguridad'] = $request['requiere_zapatos_seguridad'] ? $request['requiere_zapatos_seguridad'] : 0;
        $request['clausula_no_arrepentimiento'] = $request['clausula_no_arrepentimiento'] ? $request['clausula_no_arrepentimiento'] : 0;
    }

    public function getDataFiltrarObrasOnsite($request)
    {
        $company_id = Session::get('userCompanyIdDefault');
        $datos['texto'] = $request['texto'];
        $botonFiltrar = $request['boton_filtrar'];

        /* $datos['obrasOnsite'] = $this->listar($datos['texto'], $company_id, null, null); */

        $perfilAdminOnsite = Session::get('perfilAdminOnsite');
        $perfilAdmin = Session::get('perfilAdmin');

        if ($perfilAdminOnsite == true || $perfilAdmin == true) {
            $obrasOnsite = $this->listar($datos['texto'], $company_id, null, null);
            if ($botonFiltrar == 'csv') {
                $this->generarXlsx($request['texto'], $company_id);
            }
        } else {
            $idUser = session()->get('idUser');
            $user = $this->userService->findUser($idUser);

            if (count($user->empresa_instaladora) > 0) {
                $idEmpresaInstaladora = $user->empresa_instaladora[0]->id;
                $obrasOnsite = $this->getObraOnsitePorEmpresaInstaladora($idEmpresaInstaladora);
            } else {

                return false;
                $obrasOnsite = [];
            }
        }

        $datos['obrasOnsite'] = $obrasOnsite;

        return $datos;
    }

    public function generarCsv($texto, $companyId)
    {



        $saltear = 0;
        $tomar = 1000;
        $obrasOnsite = true;

        $idUser = Auth::user()->id;

        $fp = fopen("exports/listado_obraonsite" . $idUser . ".csv", 'w');

        $cabecera = array(
            'ID',
            'NOMBRE',
            'COMPANY_ID',
            'NOMBRECOMPANYID',
            'CANTIDAD_UNIDADES_EXTERIORES',
            'CANTIDAD_UNIDADES_INTERIORES',
            'EMPRESA_INSTALADORA_NOMBRE',
            'EMPRESA_INSTALADORA_RESPONSABLE',
            'RESPONSABLE_EMAIL',
            'RESPONSABLE_TELEFONO',
            'DOMICILIO',
            'ESTADO',
            'ESTADO_DETALLE',
            'LATITUD',
            'LONGITUD',            
            'ESQUEMA',
            'created_at',
        );

        fputcsv($fp, $cabecera, ';');

        $obrasOnsite = $this->listar($texto, $companyId, $saltear, $tomar);


        while ($obrasOnsite->count()) {
            foreach ($obrasOnsite as $obraOnsite) {

                $cantUnidades = $this->countUnidadesPorObra($obraOnsite);

                $fila = array(
                    'id' => $obraOnsite->id,
                    'nombre' => $obraOnsite->nombre,
                    'company_id' => $obraOnsite->company_id,
                    'nombrecompanyid' => $obraOnsite->nombrecompanyid,
                    'cantidad_unidades_exteriores' => $cantUnidades['cantUE'],
                    'cantidad_unidades_interiores' => $cantUnidades['cantUI'],
                    'empresa_instaladora_nombre' => $obraOnsite->empresa_instaladora_nombre,
                    'empresa_instaladora_responsable' => $obraOnsite->empresa_instaladora_responsable,
                    'responsable_email' => $obraOnsite->responsable_email,
                    'responsable_telefono' => $obraOnsite->responsable_telefono,
                    'domicilio' => $obraOnsite->domicilio,
                    'estado' => $obraOnsite->estado->nombre,
                    'estado_detalle' => $obraOnsite->estado_detalle,
                    'latitud' => str_replace(",", ".", $obraOnsite->latitud).' ',
                    'longitud' => str_replace(",", ".", $obraOnsite->longitud).' ',
                    'esquema' => $obraOnsite->esquema,
                    'created_at' => $obraOnsite->created_at,
                );                

                fputcsv($fp, $fila, ';');
            }

            $saltear = $saltear + $tomar;

            $obrasOnsite = $this->listar($texto, $companyId, $saltear, $tomar);
        }

        fclose($fp);
    }

    public function generarXlsx($texto, $companyId)
    {
        $saltear = 0;
        $tomar = 1000;
        $obrasOnsite = true;

        $idUser = Auth::user()->id;

        $filename = "listado_obraonsite_" . $idUser . ".xlsx";

        $data[] = [
            'ID',
            'NOMBRE',
            'CANTIDAD_UNIDADES_EXTERIORES',
            'CANTIDAD_UNIDADES_INTERIORES',
            'EMPRESA_INSTALADORA_NOMBRE',
            'EMPRESA_INSTALADORA_RESPONSABLE',
            'RESPONSABLE_EMAIL',
            'RESPONSABLE_TELEFONO',
            'DOMICILIO',
            'ESTADO',
            'ESTADO_DETALLE',
            'LATITUD',
            'LONGITUD',            
            'ESQUEMA',
            'created_at',
        ];

        $obrasOnsite = $this->listar($texto, $companyId, $saltear, $tomar);


        while ($obrasOnsite->count()) {
            foreach ($obrasOnsite as $obraOnsite) {

                $cantUnidades = $this->countUnidadesPorObra($obraOnsite);
                
                $data[] = [
                    'id' => $obraOnsite->id,
                    'nombre' => $obraOnsite->nombre,
                    'cantidad_unidades_exteriores' => $cantUnidades['cantUE'],
                    'cantidad_unidades_interiores' => $cantUnidades['cantUI'],
                    'empresa_instaladora_nombre' => $obraOnsite->empresa_instaladora_nombre,
                    'empresa_instaladora_responsable' => $obraOnsite->empresa_instaladora_responsable,
                    'responsable_email' => $obraOnsite->responsable_email,
                    'responsable_telefono' => $obraOnsite->responsable_telefono,
                    'domicilio' => $obraOnsite->domicilio,
                    'estado' => $obraOnsite->estado,
                    'estado_detalle' => $obraOnsite->estado_detalle,
                    'latitud' => str_replace(",", ".", $obraOnsite->latitud).' ',
                    'longitud' => str_replace(",", ".", $obraOnsite->longitud).' ',
                    'esquema' => $obraOnsite->esquema,
                    'created_at' => $obraOnsite->created_at,
                ];                
            }

            $saltear = $saltear + $tomar;

            $obrasOnsite = $this->listar($texto, $companyId, $saltear, $tomar);
        }
        
        $format = [
            'L' => NumberFormat::FORMAT_TEXT,
            'M' => NumberFormat::FORMAT_TEXT,
        ];

        $excelController = new GenericExport($data, $filename, $format);
        $excelController->export();
    }

    public function countUnidadesPorObra($obraOnsite)
    {
        $cantUIObra = 0;
        $cantUEObra = 0;

        if ($obraOnsite->sistemas_onsite && count($obraOnsite->sistemas_onsite) > 0) {

            foreach ($obraOnsite->sistemas_onsite as $sistema) {
                $cantUISistema = ($sistema->unidades_interiores ? count($sistema->unidades_interiores) : 0);
                $cantUIObra = $cantUIObra + $cantUISistema;

                $cantUESistema = ($sistema->unidades_exteriores ? count($sistema->unidades_exteriores) : 0);
                $cantUEObra = $cantUEObra + $cantUESistema;
            }
        }

        $cantUnidades = ['cantUI' =>$cantUIObra, 'cantUE' => $cantUEObra];

        return $cantUnidades;
    }


    public function getClave($nombre)
    {
        $clave = strtolower($nombre);
        $clave = str_replace(" ", "", $clave);
        $clave = str_replace("(", "", $clave);
        $clave = str_replace(")", "", $clave);
        $clave = $this->eliminar_tildes($clave);

        return $clave;
    }

    function eliminar_tildes($cadena)
    {

        //Codificamos la cadena en formato utf8 en caso de que nos de errores
        $cadena = utf8_encode($cadena);

        //Ahora reemplazamos las letras
        $cadena = str_replace(
            array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
            array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
            $cadena
        );

        $cadena = str_replace(
            array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
            array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
            $cadena
        );

        $cadena = str_replace(
            array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
            array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
            $cadena
        );

        $cadena = str_replace(
            array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
            array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
            $cadena
        );

        $cadena = str_replace(
            array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
            array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
            $cadena
        );

        $cadena = str_replace(
            array('ñ', 'Ñ', 'ç', 'Ç'),
            array('n', 'N', 'c', 'C'),
            $cadena
        );

        return $cadena;
    }
    public function listar($texto, $companyId, $saltear, $tomar, $order = null)
    {
        $consulta = ObraOnsite::where('obras_onsite.company_id',  $companyId)
            ->where('obras_onsite.activo', true);

        if (!empty($texto)) {
            //para forzar acá la clausula Where
            $consulta = $consulta
                ->whereRaw(" LOWER(CONCAT( COALESCE(obras_onsite.id,'') , ' ', COALESCE(obras_onsite.nombre,''), ' ', COALESCE(obras_onsite.empresa_instaladora_nombre,''), ' ', COALESCE(obras_onsite.empresa_instaladora_responsable,''), ' ', COALESCE(obras_onsite.responsable_email,''), ' ', COALESCE(obras_onsite.domicilio,''), ' ', COALESCE(obras_onsite.estado_detalle,''), ' ', COALESCE(obras_onsite.latitud,''), ' ', COALESCE(obras_onsite.longitud,''), ' ', COALESCE(obras_onsite.esquema,''))) LIKE LOWER('%$texto%')");
        }

        if($order == 'nombre') {
            $consulta = $consulta->orderBy('obras_onsite.nombre');
        }
        else {
            $consulta = $consulta->orderBy('obras_onsite.id', 'desc');
        }

        if ($tomar) {
            return $consulta->skip($saltear)->take($tomar)->get();
        } else {
            return $consulta->paginate(100);
        }
    }

    public static function listado()
    {
        return ObraOnsite::orderBy('nombre', 'asc')
            ->pluck('nombre', 'id');
    }

    public function getAllObrasOnsitePorEmpresaUser($idEmpresaInstaladora)
    {
        $obrasOnsite =
            ObraOnsite::with('sistema_onsite')
            ->where('company_id', Session::get('userCompanyIdDefault'))
            ->where('activo', true)
            ->where('empresa_instaladora_id', $idEmpresaInstaladora)
            ->get();


        return $obrasOnsite;
    }

    public function listarObrasOnsitePorEmpresaUser($clave_empresa)
    {


        $obrasOnsite = ObraOnsite::where('company_id', Session::get('userCompanyIdDefault'))
            ->where('clave', $clave_empresa)
            /* ->get() */;

        return $obrasOnsite->paginate(100);
    }

    public function getObraOnsitePorEmpresaInstaladora($clave_empresa)
    {


        $obrasOnsite = ObraOnsite::where('company_id', Session::get('userCompanyIdDefault'))
            ->where('empresa_instaladora_id', $clave_empresa)
            /* ->get() */;

        return $obrasOnsite->paginate(100);
    }




    public function getAllObrasOnsite()
    {


        $obrasOnsite = ObraOnsite::with('sistema_onsite')
            ->where('company_id', Session::get('userCompanyIdDefault'))
            ->where('activo', true)
            ->get();

        return $obrasOnsite;
    }

    public function findObraCheckListPorIdObra($idObraOnsite)
    {
        $company_id = Session::get('userCompanyIdDefault');

        $obraCheckList = ObraChecklistOnsite::where('company_id', $company_id)
            ->where('obra_onsite_id', $idObraOnsite)
            ->first();

        return $obraCheckList;
    }

    public function findObraOnsite($id)
    {
        if (!Session::has('userCompanyIdDefault')) {
            Session::put('userCompanyIdDefault', env('BGH_COMPANY_ID', 2));
        }

        $company_id = Session::get('userCompanyIdDefault');

        $obraOnsite = ObraOnsite::with('obraChecklistOnsite')
            ->with('empresa_onsite')
            ->where('company_id', $company_id)->find($id);

        return $obraOnsite;
    }

    public function dataPuestaMarcha()
    {
        $idUser = session()->get('idUser');
        $user = $this->userService->findUser($idUser);

        $provincias = $this->provinciasService->findProvinciasAll();
        $localidades = $this->localidadesService->getAllLocalidades();

        if(is_null($user)){
            $obrasOnsite = null;
        }elseif(count($user->empresa_instaladora) > 0) {
            $idEmpresaInstaladora = $user->empresa_instaladora[0]->id;
            $obrasOnsite =  $this->getAllObrasOnsitePorEmpresaUser($idEmpresaInstaladora);
        }else{
            $obrasOnsite = null;
        }
        $solicitudesTipos = $this->solicitudesTiposServices->getAllSolicitudesTipos();

        $empresas_onsite = $this->empresaOnsiteService->getEmpresaOnsitePorInstaladora();

        $empresas_instaladoras = $this->empresaInstaladoraService->getEmpresasInstaladoras();

        $tecnicosOnsite = $this->userService->listarTecnicosOnsite($this->userCompanyId);

        $tiposServicios = $this->tiposServiciosService->listado($this->userCompanyId);

        $estadosOnsite  = $this->estado_onsite_repository->listado($this->userCompanyId);

        $data = [
            'obrasOnsite' => $obrasOnsite,
            'user' => $user,
            'provincias' => $provincias,
            'localidades' => $localidades,
            'solicitudesTipos' => $solicitudesTipos,
            'empresasOnsite' => $empresas_onsite,
            'userCompanyIdDefault' => $this->userCompanyId,
            'empresas_instaladoras_admins' => ( !is_null($user)?$user->empresa_instaladora:$empresas_instaladoras),
            'tecnicosOnsite' => $tecnicosOnsite,
            'tiposServicios' => $tiposServicios,
            'estadosOnsite' => $estadosOnsite,
            'prioridades' => Prioridad::getOptions()
        ];

        return $data;
    }

    public function getObraOnsite($idObra)
    {
        $obrasOnsite = ObraOnsite::with('sistema_onsite')
            ->where('company_id', Session::get('userCompanyIdDefault'))
            ->where('id', $idObra)
            ->get();


        return $obrasOnsite;
    }

    public function getAllObrasOnsiteUnificado()
    {
        $userId = Auth::user()->id;
        $empresaInstaladora = EmpresaInstaladoraUser::where('user_id', $userId)->first();

        if ($empresaInstaladora) {
            $empresaInstaladoraId = $empresaInstaladora->empresa_instaladora_id;
        } else $empresaInstaladoraId = 99;


        $obrasOnsite = ObraOnsite::with('sistema_onsite_unificado')
            /* ->where('company_id', Session::get('userCompanyIdDefault')) */
            /* ->where('clave', $clave_empresa) */
            ->where('company_id', $this->userCompanyId)
            ->where('activo', true)
            ->where('empresa_instaladora_id', $empresaInstaladoraId)
            ->get();



        return $obrasOnsite;
    }

    public function getObras($company_id, $clave)
    {
        $query = ObraOnsite::where('company_id', $company_id)
            ->where('activo', true);

        if ($clave !== null) {
            $query->where(function ($query) use ($clave) {
                $query->where('id', $clave)
                    ->orWhere('clave', $clave);
            });
        }

        $obras = $query->get();

        $obras->each(function ($obra) {
            $obra->makeHidden('company_id');
        });

        return $obras;
    }

    public function getObrasFull($company_id, $clave)
    {
        $query = ObraOnsite::with('company')->where('company_id', $company_id)
            ->where('activo', true);

        if ($clave !== null) {
            $query->where(function ($query) use ($clave) {
                $query->where('id', $clave)
                    ->orWhere('clave', $clave);
            });
        }

        $obras = $query->get();

        $datosObras = [];

        foreach($obras as $obra) {
            $datosObras[] = [
                'id' => $obra->id,
                'nombre' => $obra->nombre,
                'cantidad_unidades_exteriores' => $obra->cantidad_unidades_exteriores,
                'cantidad_unidades_interiores' => $obra->cantidad_unidades_interiores,
                'empresa_instaladora_id' => $obra->empresa_instaladora_id,
                'empresa_instaladora_nombre' => $obra->empresa_instaladora_nombre,
                'empresa_instaladora_responsable' => $obra->empresa_instaladora_responsable,
                'responsable_email' => $obra->responsable_email,
                'responsable_telefono' => $obra->responsable_telefono,
                'domicilio' => $obra->domicilio,
                'estado' => $obra->estado,
                'estado_detalle' => $obra->estado_detalle,
                'esquema' => $obra->esquema,
                'created_at' => $obra->created_at
            ];
        }

        return $datosObras;
    }

    public function getObrasOnsiteDashboard()
    {
        $obrasOnsite = ObraOnsite::where('company_id', $this->userCompanyId)
            ->where('activo', true)
            ->get();

        $cantidadObrasPorMes =  $this->funcionesAuxiliaresService->getMonthAndYearFromObject($obrasOnsite);


        return $cantidadObrasPorMes;
    }

    public function getObrasAcumuladas()
    {
        $obrasOnsite = ObraOnsite::where('company_id', $this->userCompanyId)
            ->where('activo', true)
            ->get();

        $cantidadObrasPorMes =  $this->funcionesAuxiliaresService->getMonthAndYearFromObject($obrasOnsite);

        return $cantidadObrasPorMes;
    }

    public function getObrasConVisitas()
    {
        $obrasOnsite = ObraOnsite::with('sistema_onsite')
            ->where('company_id', $this->userCompanyId)
            ->where('activo', true)
            ->get();

        return $obrasOnsite;
    }

    public function getObrasSinObservaciones()
    {
        /* $obrasOnsite = ObraOnsite::where('company_id', $this->userCompanyId)
            ->where('activo', true)
            ->get(); */

        $obras_con_observaciones = 0;
        $obras_sin_observaciones = 0;
        $obras_sin_sistemas = 0;
        $obras_sin_reparaciones = 0;
        $obras_sin_checklist = 0;
        $total_obras = 0;



        /* foreach ($obrasOnsite as $obra) {
            $tiene_observaciones = false;
            $sin_observaciones = false;
            $sin_reparaciones = false;
            $sin_checklist = false;
            $sin_sistema = false;

            if (count($obra->sistema_onsite_obras) > 0) {
                foreach ($obra->sistema_onsite_obras as $sistema) {
                    if (count($sistema->reparacion_onsite) > 0) {
                        foreach ($sistema->reparacion_onsite as $reparacion) {
                            if (count($reparacion->reparacion_checklist_onsite) > 0) {
                                foreach ($reparacion->reparacion_checklist_onsite as $reparacion_checklist) {
                                    $resultado_reparacion = $reparacion_checklist->puesta_marcha_satisfactoria;
                                    if ($resultado_reparacion > 0 && $resultado_reparacion != 1)
                                        $tiene_observaciones = true;
                                    else
                                        $sin_observaciones = true;
                                }
                            } else $sin_checklist = true;
                        }
                    } else $sin_reparaciones = true;
                }
            } else $sin_sistema = true;

            
            if ($tiene_observaciones) 
            {
             $obras_con_observaciones++;   
             $sin_observaciones = false;
            }
            
            if ($sin_observaciones) $obras_sin_observaciones++;           
                     
            if ($sin_sistema) $obras_sin_sistemas++;           

            $total_obras++;
        } */

        $obras_con_observaciones = viewObrasConReparaciones::where('con_observaciones', '>', 0)
            ->get();

        $obras_sin_observaciones = viewObrasConReparaciones::where('con_observaciones', 0)
            ->get();

        $obrasTotales = ObraOnsite::where('company_id', $this->userCompanyId)
            ->where('activo', true)
            ->get();

        $obras_sin_reparaciones = count($obrasTotales) - count($obras_con_observaciones) - count($obras_sin_observaciones);


        $resultados_obras = [

            'obras_sin_observaciones' => count($obras_sin_observaciones),
            'obras_con_observaciones' => count($obras_con_observaciones),
            'obras_sin_reparaciones' => $obras_sin_reparaciones,
            'total_obras' => count($obrasTotales)

        ];

        return $resultados_obras;
    }
}
