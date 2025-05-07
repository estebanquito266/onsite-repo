<?php

namespace App\Services\Onsite\Respuestos;

use App\Models\Onsite\EmpresaOnsite;
use App\Models\Respuestos\DetalleOrdenPedidoRespuestosOnsite;
use App\Models\Respuestos\EstadoOrdenPedidoRespuestosOnsite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

use App\Models\Respuestos\OrdenPedidoRespuestosOnsite;
use App\Models\TemplateComprobante;
use App\Services\Onsite\MailOnsiteService;
use App\Services\Onsite\ParametroService;
use App\Services\Onsite\Respuestos\CategoriaRespuestosService;
use App\Services\Onsite\Respuestos\ModeloRespuestosService;
use App\Services\Onsite\Respuestos\PiezaRespuestosService;
use App\Services\Onsite\TemplatesService;
use App\Services\Onsite\UserService;
use Illuminate\Support\Facades\Auth;
use Laravel\Ui\Presets\React;
use Log;

class OrdenPedidoRespuestosService
{
    protected $userCompanyId;
    protected $categoriasRespuestosService;
    protected $modelosRespuestosService;
    protected $piezasRespuestosService;
    protected $estadosOrdenesRespuestos;
    protected $userService;
    protected $parametrosService;
    protected $mailOnsiteService;
    protected $templateComprobantesService;

    public function __construct(
        CategoriaRespuestosService $categoriaRespuestosService,
        ModeloRespuestosService $modelosRespuestosService,
        PiezaRespuestosService $piezasRespuestosService,
        EstadoOrdenesRespuestosService $estadosOrdenesRespuestos,
        UserService $userService,
        ParametroService $parametrosService,
        MailOnsiteService $mailOnsiteService,
        TemplatesService $templateComprobantesService
    ) {

        $this->userCompanyId =  session()->get('userCompanyIdDefault');
        $this->categoriasRespuestosService = $categoriaRespuestosService;
        $this->modelosRespuestosService = $modelosRespuestosService;
        $this->piezasRespuestosService = $piezasRespuestosService;
        $this->estadosOrdenesRespuestos = $estadosOrdenesRespuestos;
        $this->userService = $userService;
        $this->parametrosService = $parametrosService;
        $this->mailOnsiteService = $mailOnsiteService;
        $this->templateComprobantesService = $templateComprobantesService;
    }

    public function getDataList($request = null)
    {
        $idUser = session()->get('idUser');
        $user = $this->userService->findUser($idUser);

        $estadoRepuestosId = null;
        if($request && $request['estado_repuestos_id']) 
        {
            $estadoRepuestosId = $request['estado_repuestos_id'];
        }

        $ordenesPedidoRespuestos = $this->getOrdenesRespuestos($idUser, $user, $estadoRepuestosId);
        $categoriasRespuestos = $this->categoriasRespuestosService->getCategoriasRespuestos();
        $modelosRespuestos = $this->modelosRespuestosService->getModelosRespuestos();
        $piezasRespuestos = $this->piezasRespuestosService->getPiezasRespuestos();
        $estadosRespuestos = $this->estadosOrdenesRespuestos->getEstadosRespuestosPorDefecto();
        $users = $this->userService->getUsers();
        $disclaimer_repuestos = $this->templateComprobantesService->getTemplate(TemplateComprobante::DISCLAIMER_REPUESTOS);

        $data = [
            'ordenesPedidoRespuestos' => $ordenesPedidoRespuestos,
            'categoriasRespuestos' => $categoriasRespuestos,
            'modelosRespuestos' => $modelosRespuestos,
            'piezasRespuestos' => $piezasRespuestos,
            'company_id' => $this->userCompanyId,
            'user_id' => $idUser,
            'estadosRespuestos' => $estadosRespuestos,
            'users' => $users,
            'user' => $user,
            'disclaimer_repuestos' => $disclaimer_repuestos,
            'estadosRespuestosId' => $estadoRepuestosId
        ];

        return $data;
    }

    public function getOrdenesRespuestos($idUser, $user, $estadoId = null)
    {
        $consulta = OrdenPedidoRespuestosOnsite::with('detalle_pedido');

        if (isset($user->perfil_usuario) && !($user->perfil_usuario[0]->perfil->id == 1 || $user->perfil_usuario[0]->perfil->id == 62)) {

            $consulta->where('user_id', $idUser);
            
        }

        if (!empty($estadoId)) {
            $consulta->where('estado_id',$estadoId);
        }

        $ordenesRespuestos = $consulta->orderBy('id', 'desc')->get();

        return $ordenesRespuestos;
    }

    public function create()
    {
    }

    public function show()
    {
    }

    public function store(Request $request)
    {
        $request['nombre_solicitante'] = '';
        $request['email_solicitante'] = '';

        $ordenesPedidoRespuestos = OrdenPedidoRespuestosOnsite::create($request->all());

        return $ordenesPedidoRespuestos;
    }

    public function update($request, $id)
    {
    }

    public function destroy($id)
    {
    }

    public function confirmarOrden($idOrden, Request $request)
    {
        $ordenPedidoRespuesto = $this->findOrdenPedido($idOrden);
        $estadoPedido = $request->get('estado_pedido');
        if($estadoPedido == 'REVISION') $estadoId = EstadoOrdenPedidoRespuestosOnsite::EN_REVISION;
        if($estadoPedido == 'COTIZACION') $estadoId = EstadoOrdenPedidoRespuestosOnsite::EN_COTIZACION;

        $ordenPedidoRespuesto->update([
            'estado_id' => $estadoId,
            'nombre_solicitante' => $request->get('nombre_solicitante'),
            'email_solicitante' => $request->get('email_solicitante'),
            'empresa_onsite_id' => $request->get('empresa_onsite_id')
        ]);



        return $ordenPedidoRespuesto;
    }

    public function enviarEmailsRepuestos($idOrden, Request $request)
    {
        Log::info('enviarEmailsRepuestos - inicio');
        $ordenPedidoRespuesto = $this->findOrdenPedido($idOrden);
        Log::info($ordenPedidoRespuesto);
        /* email user */
        $idUser = session()->get('idUser');
        $user = $this->userService->findUser($idUser);
        $dir_user = $user->email;        
        /* *** */
        Log::info($user);
        /* email solicitante */
        $dir_solicitante  = $request->get('email_solicitante');
        
        /* ***** */

        /* condición */
        if ($dir_user == $dir_solicitante) {
            Log::info('enviarEmailsRepuestos - Condicion 1');
            sleep(30);
            $email_user = $email_solicitante = $this->enviarMailRepuesto($ordenPedidoRespuesto, '', 'MAIL_SOLICITANTE_RESPUESTOS',  'user');
        } else {
            Log::info('enviarEmailsRepuestos - Condicion 2');
            sleep(30);
            $email_solicitante = $this->enviarMailRepuesto($ordenPedidoRespuesto, $request->get('email_solicitante'), 'MAIL_SOLICITANTE_RESPUESTOS', 'solicitante');
            sleep(30);
            $email_user = $this->enviarMailRepuesto($ordenPedidoRespuesto, '', 'MAIL_SOLICITANTE_RESPUESTOS',  'user');
            Log::info($email_solicitante);
            Log::info($email_user);
        }
        /* ***** */
        sleep(30);
        $email_admin = $this->enviarMailRepuesto($ordenPedidoRespuesto, 'MAIL_ADMINISTRADOR_RESPUESTOS_TO', 'MAIL_ADMINISTRADOR_RESPUESTOS', 'admin');
        Log::info($email_admin);

        $result_envio = 'Emails solicitud repuestos: <br><br>' . 'Administrador: ' . $email_admin . ' <br> ' . 'Usuario: ' . $email_user . ' <br> ' . 'Solicitante: ' . $email_solicitante;
        Log::info($result_envio);

        Session::flash('notificacion_index', $result_envio);

        Log::info('enviarEmailsRepuestos - fin');
        return $result_envio;
    }

    public function updateOrdenRespuestos($idOrden, $request)
    {
        $ordenPedidoRespuesto = $this->findOrdenPedido($idOrden);

        $monto_dolar = ($request->get('monto_dolar') <> null ? $request->get('monto_dolar') : 0);
        $monto_euro = ($request->get('monto_euro') <> null ? $request->get('monto_euro') : 0);
        $monto_peso = ($request->get('monto_peso') <> null ? $request->get('monto_peso') : 0);

        $ordenPedidoRespuesto->update([
            'monto_dolar' => $monto_dolar,
            'monto_euro' => $monto_euro,
            'monto_peso' => $monto_peso
        ]);

        return $ordenPedidoRespuesto;
    }

    public function updateEstadoOrdenRespuestos($idOrden, $request)
    {
        $ordenPedidoRespuesto = $this->findOrdenPedido($idOrden);
        $ordenPedidoRespuesto->update([
            'estado_id' => $request->get('estado_id')
        ]);

        return $ordenPedidoRespuesto;
    }

    public function findOrdenPedido($idOrden)
    {
        $ordenPedido = OrdenPedidoRespuestosOnsite::with('detalle_pedido')
            ->where('company_id', $this->userCompanyId)
            ->find($idOrden);

        return $ordenPedido;
    }

    public function updateMontoTotal(Request $request)
    {
        $idOrden = $request->get('orden_respuestos_id');
        $ordenPedidoRespuesto = $this->findOrdenPedido($idOrden);

        $monto_dolar = ($request->get('monto_dolar') <> null ? $request->get('monto_dolar') : 0);
        $monto_euro = ($request->get('monto_euro') <> null ? $request->get('monto_euro') : 0);
        $monto_peso = ($request->get('monto_peso') <> null ? $request->get('monto_peso') : 0);

        $ordenPedidoRespuesto->update([
            'monto_dolar' => $monto_dolar,
            'monto_euro' => $monto_euro,
            'monto_peso' => $monto_peso
        ]);

        return $ordenPedidoRespuesto;
    }

    public function enviarMailRepuesto($ordenPedidoRespuesto, $mailTo, $plantillaMail, $tipoDestinatario)
    {
        $emailTo = null;

        if ($tipoDestinatario == 'admin') {
            $parametroMail = $this->parametrosService->findParametroPorNombre($mailTo);

            if (isset($parametroMail)) {
                $emailTo = $parametroMail->valor_cadena;
            }
        }

        if ($tipoDestinatario == 'user') {
            $idUser = session()->get('idUser');
            $user = $this->userService->findUser($idUser);
            $emailTo = $user->email;
        }

        if ($tipoDestinatario == 'solicitante') {

            $emailTo = $mailTo;
        }

        $plantilla_mail_admin_id = $this->parametrosService->findParametroPorNombre($plantillaMail);

        if ($ordenPedidoRespuesto && !is_null($emailTo)  && $plantilla_mail_admin_id) {

            $plantilla_mail_admin_id = $plantilla_mail_admin_id->valor_numerico;

            if ($plantilla_mail_admin_id > 0 && $emailTo !== null) {

                $email = $this->mailOnsiteService->enviarMailRepuestosOnsite($ordenPedidoRespuesto, $plantilla_mail_admin_id, $emailTo);
                if ($email)
                    return 'Enviado correctamente';
            } else return 'No puede enviarse email';
        } else return 'NO puede enviarse email';
    }

    public function getUsuarioEmpresa($idEmpresa)
    {
        $empresa = EmpresaOnsite::with('user_repuestos')
            ->find($idEmpresa);

        return $empresa;
    }

    public function getFiltrarPedidoRepuestos($request)
    {
        return $this->getDataList($request);
    }    
}
