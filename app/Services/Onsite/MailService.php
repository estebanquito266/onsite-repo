<?php

namespace App\Services\Onsite;

/*********-MODELS-*********/

use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Riparazione\Jobs\SendReminderEmail;
use Riparazione\Models\Admin\Company;
use Riparazione\Models\Admin\User;
use Riparazione\Models\Derivacion\Derivacion;
use Riparazione\Models\Equipo\Marca;
use Riparazione\Models\Equipo\Modelo;
use Riparazione\Models\Pagos\MercadoPagoPayment;
use Riparazione\Models\Config\PlantillaMail;
use Riparazione\Models\Config\Provincia;
use Riparazione\Models\Reparacion\Reparacion;
use Riparazione\Models\Reparacion\ServicioExtraReparacion;
use Riparazione\Models\Derivacion\Servicio;
use Riparazione\Models\Equipo\TipoEquipo;
use Riparazione\Models\PlantillaMailHeader;
use Riparazione\Models\Ticket\Ticket;
use Riparazione\Notifications\SendNotification;
use Throwable;

class MailService
{
    protected $estadosDerivacionesService;
    protected $paramCompaniesService;
    protected $lineaClaseLogs;


    public function __construct(
        ParamCompaniesService $paramCompaniesService
    ) {
        // $this->paramCompaniesService = $paramCompaniesService;

        $this->lineaClaseLogs = ' - ' . get_class($this) . ' - LINE: ';
    }
    /**
     * Envia el mail de cambio de estado al cliente
     *
     * @param integer $reparacion_id
     * @param integer $plantilla_mail_id
     * @return void
     */
    public function enviarMailEstadoSend($data)
    {
        Log::info('INICIA_MAIL_ESTADO_REPARACION' . $this->lineaClaseLogs . __LINE__);

        $reparacion_id = $data['reparacion_id'];
        $plantilla_mail_id = $data['plantilla_mail_id'];
        $email_to = isset($data['email_to']) ? $data['email_to'] : null;

        $emailBcc = env('MAIL_COPIA'); //$this->mailCopia;
        $nombreBcc = env('MAIL_COPIA_NOMBRE'); //$this->mailCopiaNombre;

        // Si NO pasa el id de reparacion no se puede enviar el mail
        if (!$reparacion_id) {
            return false;
        }


        // Busca la reparacion
        $reparacion = Reparacion::find($reparacion_id);


        if ($reparacion && !empty($reparacion->cliente)) {
            if ($reparacion->cliente->email && ($plantilla_mail_id && $plantilla_mail_id > 1)) {
                // Busca la plantilla de mail
                $plantillaMail = PlantillaMail::find($plantilla_mail_id);

                $asunto = $plantillaMail->subject;
                $cuerpo = $plantillaMail->body;
                $emailFrom = $plantillaMail->from;
                $nombreFrom = $plantillaMail->from_nombre;
                $emailBcc = $plantillaMail->cc;
                $nombreBcc = $plantillaMail->cc_nombre;

                //------ replace variables asunto -----------------------------------//
                $asunto = $this->replaceVariablesReparacion($reparacion, $asunto);

                //------ replace variables cuerpo -----------------------------------//	
                $cuerpo = $this->replaceVariablesReparacion($reparacion, $cuerpo);

                //------ ------------------------ -----------------------------------//
                if (!$email_to) {
                    $email_to = $reparacion->cliente->email;
                }

                try {

                    $array_data = [
                        'cuerpo' => $cuerpo,
                        'asunto' => $asunto,
                        'email_to' => $email_to,
                        'email_from' => $emailFrom,
                        'nombreFrom' => $nombreFrom,
                        'emailBcc' => $emailBcc,
                        'nombreBcc' => $nombreBcc,
                        'view' => 'emails.comprobante'
                    ];

                    if ($plantillaMail->plantilla_mail_header) {
                        $array_data['header'] = PlantillaMailHeader::find($plantillaMail->plantilla_mail_header)->header;
                    }

                    if ($plantillaMail->plantilla_mail_footer) {
                        $array_data['footer'] = PlantillaMailHeader::find($plantillaMail->plantilla_mail_footer)->footer;
                    }

                    return $array_data;
                } catch (Throwable  $e) {
                    Log::alert('No se pudo enviar el mail speedup. ERROR: ' . $e->getMessage());
                    Log::info($e->getFile() . '(' . $e->getLine() . ')');

                    $envio_email = 'No se pudo enviar el mail speedup. ERROR: ' . $e->getMessage();
                    Log::alert($envio_email);
                }
            }
        }
    }

    public function enviarConsultaMailSend($data)
    {
        $consulta = $data['consulta'];
        $company = $data['company'];

        if ($company && $company->consulta_email_to && $company->plantilla_mail_consulta_id > 1) {
            $plantillaMail = PlantillaMail::find($company->plantilla_mail_consulta_id);

            $asunto = $plantillaMail->subject;
            $cuerpo = $plantillaMail->body;
            $emailFrom = $plantillaMail->from;
            $nombreFrom = $plantillaMail->from_nombre;
            $emailBcc = $plantillaMail->cc;
            $nombreBcc = $plantillaMail->cc_nombre;

            $asunto = $this->replaceVariablesConsulta($consulta, $asunto);

            $cuerpo = $this->replaceVariablesConsulta($consulta, $cuerpo);

            $email_to = $company->consulta_email_to;

            $array_data = [
                'cuerpo' => $cuerpo,
                'asunto' => $asunto,
                'email_to' => $email_to,
                'email_from' => $emailFrom,
                'nombreFrom' => $nombreFrom,
                'emailBcc' => $emailBcc,
                'nombreBcc' => $nombreBcc,
                'view' => 'emails.plantilla'
            ];

            if ($plantillaMail->plantilla_mail_header) {
                $array_data['header'] = PlantillaMailHeader::find($plantillaMail->plantilla_mail_header)->header;
            }

            if ($plantillaMail->plantilla_mail_footer) {
                $array_data['footer'] = PlantillaMailHeader::find($plantillaMail->plantilla_mail_footer)->footer;
            }

            return $array_data;
        }
    }

    public function replaceVariablesConsulta($consulta, $texto_base)
    {
        if ($consulta) {
            $texto_base = str_replace("%CONSULTA_ID%", $consulta->id, $texto_base);
            $texto_base = str_replace("%CONSULTA_NOMBRE%", $consulta->nombre, $texto_base);
            $texto_base = str_replace("%CONSULTA_EMAIL%", $consulta->email, $texto_base);
            $texto_base = str_replace("%CONSULTA_TELEFONO%", $consulta->telefono, $texto_base);
            $texto_base = str_replace("%CONSULTA_DIRECCION%", $consulta->direccion, $texto_base);
            $texto_base = str_replace("%CONSULTA_CODIGO_POSTAL%", $consulta->codigo_postal, $texto_base);
            $texto_base = str_replace("%CONSULTA_DIA_PREFERENCIA%", $consulta->dia_preferencia, $texto_base);
            $texto_base = str_replace("%CONSULTA_HORA_PREFERENCIA%", $consulta->hora_preferencia, $texto_base);
            $texto_base = str_replace("%CONSULTA_IMEI%", $consulta->imei, $texto_base);
            $texto_base = str_replace("%CONSULTA_LOCALIDAD%", $consulta->localidad, $texto_base);
            $texto_base = str_replace("%CONSULTA_LATITUD_UBICACION%", $consulta->latitud_ubicacion, $texto_base);
            $texto_base = str_replace("%CONSULTA_LONGITUD_UBICACION%", $consulta->longitud_ubicacion, $texto_base);
            $texto_base = str_replace("%CONSULTA_PLAZO%", $consulta->plazo, $texto_base);
            $texto_base = str_replace("%CONSULTA_MONTO%", $consulta->monto, $texto_base);
            $texto_base = str_replace("%CONSULTA_COMENTARIOS%", $consulta->comentarios, $texto_base);

            return $texto_base;
        }
    }

    public function enviarMailServiciosExtrasSend($data)
    {
        $cliente = $data['cliente'];
        $reparacion = $data['reparacion'];

        $emailBcc = env('MAIL_REPARACIONES');
        $nombreBcc = env('MAIL_REPARACIONES_NOMBRE');

        if ($cliente->email) {

            $serviciosExtras = ServicioExtraReparacion::listarNombresServiciosExtrasPorReparacionSugeridos($reparacion->id);

            if ($serviciosExtras->count() > 0) {

                $emailTo = $cliente->email;
                $idPlantillaMailServiciosExtras = 70;
                $plantillaMail = PlantillaMail::find($idPlantillaMailServiciosExtras);

                if ($plantillaMail) {
                    $asunto = $plantillaMail->subject;
                    $cuerpo = $plantillaMail->body;
                    $emailFrom = $plantillaMail->from;
                    $nombreFrom = $plantillaMail->from_nombre;
                    $emailBcc = $plantillaMail->cc;
                    $nombreBcc = $plantillaMail->cc_nombre;

                    //------ replace variables asunto -----------------------------------//
                    $asunto = $this->replaceMailSeriviciosExtras($reparacion, $serviciosExtras, $asunto);

                    //------ replace variables cuerpo -----------------------------------//
                    $cuerpo = $this->replaceMailSeriviciosExtras($reparacion, $serviciosExtras, $cuerpo);


                    $array_data = [
                        'cuerpo' => $cuerpo,
                        'asunto' => $asunto,
                        'email_to' => $emailTo,
                        'email_from' => $emailFrom,
                        'nombreFrom' => $nombreFrom,
                        'emailBcc' => $emailBcc,
                        'nombreBcc' => $nombreBcc,
                        'view' => 'emails.plantilla'
                    ];

                    if ($plantillaMail->plantilla_mail_header) {
                        $array_data['header'] = PlantillaMailHeader::find($plantillaMail->plantilla_mail_header)->header;
                    }

                    if ($plantillaMail->plantilla_mail_footer) {
                        $array_data['footer'] = PlantillaMailHeader::find($plantillaMail->plantilla_mail_footer)->footer;
                    }

                    return $array_data;
                }
            }
        }
    }

    public function replaceMailSeriviciosExtras(object $reparacion, object $serviciosExtras, $texto_base)
    {
        if ($reparacion->id_cliente) {
            $texto_base = $this->replaceVariablesCliente($reparacion->cliente, $texto_base);
        }
        if ($reparacion->id) {
            $texto_base = $this->replaceVariablesReparacionTable($reparacion, $texto_base);
        }
        if ($reparacion->id_equipo) {
            $texto_base = $this->replaceVariablesEquipo($reparacion->equipo, $texto_base);
        }
        if ($reparacion->id) {
            $texto_base = $this->replaceVariablesServicioExtra($serviciosExtras, $texto_base);
        }

        $texto_base = $this->replaceVariablesFecha($texto_base);

        return $texto_base;
    }



    public function enviarMailEstadoDerivacionSend($data)
    {
        $derivacionId = $data['derivacionId'];
        $idPlantillaMail = $data['plantillaMailId'];
        $email = $data['email'];

        Log::info('Mail Service. Procede a enviar mail estado derivación' . $this->lineaClaseLogs . __LINE__);

        $derivacion = Derivacion::find($derivacionId);
        $plantillaMail      =   PlantillaMail::find($idPlantillaMail);
        if ($plantillaMail) {
            $emailFixup         =   $plantillaMail->from;
            $nombreFixup        =   $plantillaMail->from_nombre;
            $emailCC            =   $plantillaMail->cc;
            $nombreCC           =   $plantillaMail->cc_nombre;
            $asunto             =   $plantillaMail->subject;
            $cuerpo             =   $plantillaMail->body;

            //------ replace variables asunto -----------------------------------//

            $asunto = $this->replaceVariablesDerivacion($derivacion, $asunto);


            //------ replace variables cuerpo -----------------------------------//

            $cuerpo = $this->replaceVariablesDerivacion($derivacion, $cuerpo);

            //------ ------------------------ -----------------------------------//

            $emailTo = $email;

            $array_data = [
                'cuerpo' => $cuerpo,
                'asunto' => $asunto,
                'email_to' => $emailTo,
                'email_from' => $emailFixup,
                'nombreFrom' => $nombreFixup,
                'emailBcc' => $emailCC,
                'nombreBcc' => $nombreCC,
                'view' => 'emails.plantilla'
            ];

            if ($plantillaMail->plantilla_mail_header) {
                $array_data['header'] = PlantillaMailHeader::find($plantillaMail->plantilla_mail_header)->header;
            }

            if ($plantillaMail->plantilla_mail_footer) {
                $array_data['footer'] = PlantillaMailHeader::find($plantillaMail->plantilla_mail_footer)->footer;
            }

            return $array_data;
        }
    }

    public function enviarMailComprobanteSend($data)
    {
        $cliente = $data['cliente'];
        $reparacion = $data['reparacion'];
        $equipo = $data['equipo'];
        $tipoComprobante = $data['tipoComprobante'];
        $pdfAdjunto = $data['pdfAdjunto'];

        $idPlantillaMail = null;

        $paramCompany = $this->paramCompaniesService->getParamCompany(Company::DEFAULT);

        if ($tipoComprobante == 'i') {
            $idPlantillaMail = $paramCompany->mail_reparacion_ingreso;
        }

        if ($tipoComprobante == 's') {
            $idPlantillaMail = $paramCompany->mail_reparacion_salida;
        }

        $plantillaMail = PlantillaMail::find($idPlantillaMail);

        if (isset($idPlantillaMail) && $idPlantillaMail > 1) {
            if ($plantillaMail) {
                $asunto = $plantillaMail->subject;
                $cuerpo = $plantillaMail->body;

                $emailFrom = $plantillaMail->from;
                $nombreFrom = $plantillaMail->from_nombre;

                $emailBcc = $plantillaMail->cc;
                $nombreBcc = $plantillaMail->cc_nombre;

                $emailTo = $cliente->email;

                //------ replace variables asunto -----------------------------------//
                $asunto = $this->replaceMailComprobante($reparacion, $asunto);

                //------ replace variables cuerpo -----------------------------------//
                $cuerpo = $this->replaceMailComprobante($reparacion, $cuerpo);


                if ($emailTo) {

                    $array_data = [
                        'cuerpo' => $cuerpo,
                        'asunto' => $asunto,
                        'email_to' => $emailTo,
                        'email_from' => $emailFrom,
                        'nombreFrom' => $nombreFrom,
                        'emailBcc' => $emailBcc,
                        'nombreBcc' => $nombreBcc,
                        'view' => 'emails.comprobante'
                    ];

                    if ($plantillaMail->plantilla_mail_header) {
                        $array_data['header'] = PlantillaMailHeader::find($plantillaMail->plantilla_mail_header)->header;
                    }

                    if ($plantillaMail->plantilla_mail_footer) {
                        $array_data['footer'] = PlantillaMailHeader::find($plantillaMail->plantilla_mail_footer)->footer;
                    }

                    return $array_data;
                }
            }
        }
    }
    public function replaceMailComprobante(object $reparacion,  $texto_base)
    {
        if ($reparacion->id_cliente) {
            $texto_base = $this->replaceVariablesCliente($reparacion->cliente, $texto_base);
        }
        if ($reparacion->id) {
            $texto_base = $this->replaceVariablesReparacionTable($reparacion, $texto_base);
        }
        if ($reparacion->id_equipo) {
            $texto_base = $this->replaceVariablesEquipo($reparacion->equipo, $texto_base);
        }

        $texto_base = $this->replaceVariablesFecha($texto_base);

        return $texto_base;
    }

    public function enviarMailOcaOrdenRetiroSend($data)
    {
        $ordenOca = $data['ordenOca'];
        $cliente = $data['cliente'];
        $idPlantillaMail = $data['idPlantillaMail'];

        //$idPlantillaMail = 80;
        $plantillaMail = PlantillaMail::find($idPlantillaMail);

        if ($plantillaMail) {
            $emailFixup = $plantillaMail->from;
            $nombreFixup = $plantillaMail->from_nombre;
            $emailCC = $plantillaMail->cc;
            $nombreCC = $plantillaMail->cc_nombre;
            $asunto = $plantillaMail->subject;
            $cuerpo = $plantillaMail->body;

            $emailTo = $cliente->email;

            //------ replace variables asunto -----------------------------------//
            $asunto = $this->replaceMailOcaOrdenRetiro($ordenOca, $asunto);

            //------ replace variables cuerpo -----------------------------------//
            $cuerpo = $this->replaceMailOcaOrdenRetiro($ordenOca, $cuerpo);

            $array_data = [
                'cuerpo' => $cuerpo,
                'asunto' => $asunto,
                'email_to' => $emailTo,
                'email_from' => $emailFixup,
                'nombreFrom' => $nombreFixup,
                'emailBcc' => $emailCC,
                'nombreBcc' => $nombreCC,
                'view' => 'emails.plantilla'
            ];

            if ($plantillaMail->plantilla_mail_header) {
                $array_data['header'] = PlantillaMailHeader::find($plantillaMail->plantilla_mail_header)->header;
            }

            if ($plantillaMail->plantilla_mail_footer) {
                $array_data['footer'] = PlantillaMailHeader::find($plantillaMail->plantilla_mail_footer)->footer;
            }

            return $array_data;
        }
    }

    public function replaceMailOcaOrdenRetiro(object $ordenOca,  $texto_base)
    {
        if ($ordenOca->id_cliente) {
            $texto_base = $this->replaceVariablesCliente($ordenOca->cliente, $texto_base);
        }
        if ($ordenOca->id) {
            $texto_base = $this->replaceVariablesOrdenOca($ordenOca, $texto_base);
        }

        $texto_base = $this->replaceVariablesFecha($texto_base);

        return $texto_base;
    }

    public function enviarMailOrdenElockerSend($data)
    {
        $ordenElocker = $data['ordenElocker'];
        $cliente = $data['cliente'];
        $idPlantillaMail = $data['idPlantillaMail'];
        //$idPlantillaMail = 80;

        $plantillaMail = PlantillaMail::find($idPlantillaMail);

        if ($plantillaMail) {
            $emailFixup = $plantillaMail->from;
            $nombreFixup = $plantillaMail->from_nombre;

            $emailCC = $plantillaMail->cc;
            $nombreCC = $plantillaMail->cc_nombre;

            $asunto = $plantillaMail->subject;

            $cuerpo = $plantillaMail->body;

            //------ replace variables asunto -----------------------------------//
            $asunto = $this->replaceMailOrdenElocker($ordenElocker, $asunto);

            //------ replace variables cuerpo -----------------------------------//
            $cuerpo = $this->replaceMailOrdenElocker($ordenElocker, $cuerpo);

            $emailTo = $cliente->email;

            $array_data = [
                'cuerpo' => $cuerpo,
                'asunto' => $asunto,
                'email_to' => $emailTo,
                'email_from' => $emailFixup,
                'nombreFrom' => $nombreFixup,
                'emailBcc' => $emailCC,
                'nombreBcc' => $nombreCC,
                'view' => 'emails.plantilla'
            ];

            if ($plantillaMail->plantilla_mail_header) {
                $array_data['header'] = PlantillaMailHeader::find($plantillaMail->plantilla_mail_header)->header;
            }

            if ($plantillaMail->plantilla_mail_footer) {
                $array_data['footer'] = PlantillaMailHeader::find($plantillaMail->plantilla_mail_footer)->footer;
            }

            return $array_data;
        }
    }

    public function replaceMailOrdenElocker(object $ordenElocker,  $texto_base)
    {
        if ($ordenElocker->id_cliente) {
            $texto_base = $this->replaceVariablesCliente($ordenElocker->cliente, $texto_base);
        }
        if ($ordenElocker->id) {
            $texto_base = $this->replaceVariablesOrdenElocker($ordenElocker, $texto_base);
        }

        $texto_base = $this->replaceVariablesFecha($texto_base);

        return $texto_base;
    }

    public function enviarMailOrdenCorreoArgentinoSend($data)
    {

        $ordenCorreoArgentino = $data['ordenCorreoArgentino'];
        $cliente = $data['cliente'];
        $idPlantillaMail = $data['idPlantillaMail'];

        //$idPlantillaMail = 80;

        $plantillaMail = PlantillaMail::find($idPlantillaMail);

        if ($plantillaMail) {
            $emailFixup = $plantillaMail->from;
            $nombreFixup = $plantillaMail->from_nombre;

            $emailCC = $plantillaMail->cc;
            $nombreCC = $plantillaMail->cc_nombre;

            $asunto = $plantillaMail->subject;

            $cuerpo = $plantillaMail->body;

            //------ replace variables asunto -----------------------------------//
            $asunto = $this->replaceMailOrdenCorreoArgentino($ordenCorreoArgentino, $asunto);

            //------ replace variables cuerpo -----------------------------------//
            $cuerpo = $this->replaceMailOrdenCorreoArgentino($ordenCorreoArgentino, $cuerpo);

            $emailTo = $cliente->email;

            $array_data = [
                'cuerpo' => $cuerpo,
                'asunto' => $asunto,
                'email_to' => $emailTo,
                'email_from' => $emailFixup,
                'nombreFrom' => $nombreFixup,
                'emailBcc' => $emailCC,
                'nombreBcc' => $nombreCC,
                'view' => 'emails.plantilla'
            ];

            if ($plantillaMail->plantilla_mail_header) {
                $array_data['header'] = PlantillaMailHeader::find($plantillaMail->plantilla_mail_header)->header;
            }

            if ($plantillaMail->plantilla_mail_footer) {
                $array_data['footer'] = PlantillaMailHeader::find($plantillaMail->plantilla_mail_footer)->footer;
            }

            return $array_data;
        }
    }

    public function replaceMailOrdenCorreoArgentino(object $ordenCorreoArgentino, $texto_base)
    {
        if ($ordenCorreoArgentino->id_cliente) {
            $texto_base = $this->replaceVariablesCliente($ordenCorreoArgentino->cliente, $texto_base);
        }
        if ($ordenCorreoArgentino->id_cliente) {
            $texto_base = $this->replaceVariablesOrdenCorreoArgentino($ordenCorreoArgentino, $texto_base);
        }

        $texto_base = $this->replaceVariablesFecha($texto_base);

        return $texto_base;
    }

    /**
     * Reemplaza los parametros con los datos de la reparacion
     *
     * @param integer $repracion_id
     * @param string $texto_base
     * @return void
     */
    private function replaceVariablesReparacion($reparacion, $texto_base)
    {
        if ($reparacion) {
            $reparacionPago = false;

            if ($reparacion->reparaciones_pago->count()) {
                $reparacionPago = $reparacion->reparaciones_pago[0];
            }

            $envioMercadoPago = false;

            if ($reparacion->envios_mercado_pago->count()) {
                $envioMercadoPago = $reparacion->envios_mercado_pago[0];
            }

            // Servicios extra
            $serviciosExtras = ServicioExtraReparacion::listarNombresServiciosExtrasPorReparacionSugeridos($reparacion->id);

            if ($serviciosExtras->count() > 0) {
                $texto_base = $this->replaceVariablesServicioExtra($serviciosExtras, $texto_base);
            }

            if ($reparacion->cliente) {
                $texto_base = $this->replaceVariablesCliente($reparacion->cliente, $texto_base);
                $texto_base = $this->generateUrlAppConsultaEstadoFixup($reparacion->id, $reparacion->cliente->dni_cuit, $texto_base);
            }

            $texto_base = $this->replaceVariablesReparacionTable($reparacion, $texto_base);

            $texto_base = $this->replaceVariablesEquipo($reparacion->equipo, $texto_base);
            
            $texto_base = $this->replaceVariablesEstado($reparacion->estado, $texto_base);

            $texto_base = $this->replaceVariablesSucursal($reparacion->sucursal, $texto_base);


            if ($reparacionPago) {
                $texto_base = $this->replaceVariablesReparacionPago($reparacionPago, $texto_base);
            }

            if ($envioMercadoPago) {
                $texto_base = $this->replaceVariablesEnvioMercadoPago($envioMercadoPago, $texto_base);
            }

            if ($reparacion->orden_retiro_domicilio) {
                $texto_base = $this->replaceVariablesOrdenRetiroDomicilio($reparacion->orden_retiro_domicilio, $texto_base);
            }

            if ($reparacion->reparacion_assurant) {
                $texto_base = $this->replaceVariablesReparacionAssurant($reparacion->reparacion_assurant, $texto_base);
            }

            if ($reparacion->ordenes_andreani && $reparacion->ordenes_andreani->first() ) {
                $texto_base = $this->replaceVariablesOrdenAndreani($reparacion->ordenes_andreani->first(), $texto_base);
            }

            /*
            if ($reparacion->orden_cambio_sucursal) {
                $texto_base = $this->replaceVariablesOrdenCambioSucursal($reparacion->orden_cambio_sucursal, $texto_base);
            }
            */

            $texto_base = $this->replaceVariablesFecha($texto_base);
        }
        return $texto_base;
    }

    public function replaceVariablesDerivacion($derivacion, $texto_base)
    {

        if ($derivacion) {
            $idServicio = 6;
            if ($derivacion->id_servicio) {
                $idServicio = $derivacion->id_servicio;
            }


            $texto_base = $this->replaceVariablesDerivacionTable($derivacion, $texto_base);

            $texto_base = $this->replaceVariablesClienteDerivacion($derivacion->cliente_derivacion, $texto_base);

            $texto_base = $this->replaceVariablesEquipoDerivacion($derivacion->equipo_derivacion, $texto_base);

            $texto_base = $this->replaceVariablesGrupoEquipo($derivacion->equipo_derivacion->grupo_equipo, $texto_base);

            $texto_base = $this->replaceVariablesMarca($derivacion->equipo_derivacion->marca, $texto_base);

            $texto_base = $this->replaceVariablesModelo($derivacion->equipo_derivacion->modelo, $texto_base);

            $texto_base = $this->replaceVariablesFallaGrupoEquipo($derivacion->falla(), $texto_base);

            $texto_base = $this->replaceVariablesServicio($idServicio, $texto_base);

            $texto_base = $this->replaceVariablesEstadoDerivacion($derivacion->estado, $texto_base);

            $texto_base = $this->replaceVariablesSucursal($derivacion->sucursal, $texto_base);

            $texto_base = $this->replaceVariablesFecha($texto_base);

            if ($derivacion->orden_retiro_domicilio) {
                $texto_base = $this->replaceVariablesOrdenRetiroDomicilio($derivacion->orden_retiro_domicilio, $texto_base);
            }
        }

        return $texto_base;
    }



    private function replaceVariablesOrdenRetiroDomicilio($orden_retiro_domicilio, $texto_base)
    {

        if ($orden_retiro_domicilio) {
            $texto_base = str_replace("%ORDEN_RETIRO_DOMICILIO_ID%", $orden_retiro_domicilio->id, $texto_base);
            $texto_base = str_replace("%ORDEN_RETIRO_DOMICILIO_CODIGO_ENVIO%", $orden_retiro_domicilio->codigo_envio, $texto_base);

            $texto_base = str_replace("%ORDEN_RETIRO_DOMICILIO_ZONA%", $orden_retiro_domicilio->zona, $texto_base);
            $texto_base = str_replace("%ORDEN_RETIRO_DOMICILIO_PRECIO_ENVIO%", $orden_retiro_domicilio->precio_envio, $texto_base);
            $texto_base = str_replace("%ORDEN_RETIRO_DOMICILIO_DIA_RETIRO%", $orden_retiro_domicilio->dia_retiro, $texto_base);
            $texto_base = str_replace("%ORDEN_RETIRO_DOMICILIO_HORA_RETIRO%", $orden_retiro_domicilio->hora_retiro, $texto_base);
            $texto_base = str_replace("%ORDEN_RETIRO_DOMICILIO_LINK%", $orden_retiro_domicilio->link, $texto_base);
            $texto_base = str_replace("%ORDEN_RETIRO_DOMICILIO_EQUIPO%", $orden_retiro_domicilio->equipo, $texto_base);
            $texto_base = str_replace("%ORDEN_RETIRO_DOMICILIO_COMENTARIOS%", $orden_retiro_domicilio->comentarios, $texto_base);
            $texto_base = str_replace("%ORDEN_RETIRO_DOMICILIO_VENDEDOR%", $orden_retiro_domicilio->vendedor, $texto_base);
            $texto_base = str_replace("%ORDEN_RETIRO_DOMICILIO_DEVOLUCION_CODIGO_ENVIO%", $orden_retiro_domicilio->devolucion_codigo_envio, $texto_base);
            $texto_base = str_replace("%ORDEN_RETIRO_DOMICILIO_DEVOLUCION_COMENTARIO%", $orden_retiro_domicilio->devolucion_comentario, $texto_base);

            if ($orden_retiro_domicilio->proveedor_logistico) {
                $texto_base = str_replace("%ORDEN_RETIRO_DOMICILIO_PROVEEDOR_LOGISTICO%", $orden_retiro_domicilio->proveedor_logistico->nombre, $texto_base);
            }
            $texto_base = str_replace("%ORDEN_RETIRO_DOMICILIO_DEVOLUCION_CODIGO_ENVIO%", $orden_retiro_domicilio->devolucion_codigo_envio, $texto_base);
            if ($orden_retiro_domicilio->proveedor_logistico_devolucion) {
                $texto_base = str_replace("%ORDEN_RETIRO_DOMICILIO_DEVOLUCION_PROVEEDOR_LOGISTICO%", $orden_retiro_domicilio->proveedor_logistico_devolucion->nombre, $texto_base);
            }
        }

        return $texto_base;
    }


    private function generateUrlAppConsultaEstadoFixup($reparacion_id, $cliente_nro, $texto_base)
    {
        $url_app_consulta_estado = "https://fixupweb.com/estado/" . $reparacion_id . '/' . $cliente_nro;

        $texto_base = str_replace("%URL_APP_CONSULTA_ESTADO_FIXUP%", $url_app_consulta_estado, $texto_base);

        return $texto_base;
    }




    /**
     * Envia mail a Sirena por nueva derivacion
     * @param  int $idDerivacion [description]
     */
    public function enviarMailDerivacionSirenaSend($data)
    {

        $_derivacion_id = $data['derivacionId'] ?? 0;
        $id_plantilla_email = $data['plantillaMailId'] ?? 1;

        $plantillaMail = PlantillaMail::find($id_plantilla_email);
        if ($_derivacion_id != 0) {
            $derivacion = Derivacion::find($_derivacion_id);
        }

        if ($plantillaMail) {
            $emailFixup = $plantillaMail->from;
            $nombreFixup = $plantillaMail->from_nombre;
            $emailCC = $plantillaMail->cc;
            $nombreCC = $plantillaMail->cc_nombre;
            $asunto = $plantillaMail->subject;

            $cuerpo = $plantillaMail->body_txt;

            //------ replace variables asunto -----------------------------------//

            $asunto = $this->replaceVariablesDerivacion($derivacion, $asunto);

            //------ replace variables cuerpo -----------------------------------//

            $cuerpo = $this->replaceVariablesDerivacion($derivacion, $cuerpo);


            //------ ------------------------ -----------------------------------//

            $primer_nombre = '-';
            if ($derivacion && $derivacion->cliente_derivacion) {
                if ($derivacion->cliente_derivacion->primer_nombre) {
                    $primer_nombre = $derivacion->cliente_derivacion->primer_nombre;
                } else {
                    $primer_nombre = $derivacion->cliente_derivacion->nombre;
                }
            }

            $array_data = [
                'nombre_cliente' => $primer_nombre,
                'apellido' => ($derivacion && $derivacion->cliente_derivacion ? $derivacion->cliente_derivacion->apellido : '-'),
                'telefono_celular' => ($derivacion && $derivacion->cliente_derivacion ? $derivacion->cliente_derivacion->telefono : '-'),
                'email_to' => ($derivacion && $derivacion->cliente_derivacion ? $derivacion->cliente_derivacion->email : '-'),

                'domicilio' => ($derivacion && $derivacion->cliente_derivacion ? $derivacion->cliente_derivacion->direccion : '-'),
                'localidad' => ($derivacion && $derivacion->cliente_derivacion ? $derivacion->cliente_derivacion->localidad : '-'),
                'codigo_postal' => ($derivacion && $derivacion->cliente_derivacion ? $derivacion->cliente_derivacion->codigo_postal : '-'),
                'company_id' => ($derivacion  ? $derivacion->company_id : Company::DEFAULT),


                'cuerpo' => $cuerpo,
                'asunto' => $asunto,
                'email_from' => $emailFixup,
                'nombreFrom' => $nombreFixup,
                'emailBcc' => $emailCC,
                'nombreBcc' => $nombreCC,
                'view' => 'emails.plantilla'
            ];

            if ($plantillaMail->plantilla_mail_header) {
                $array_data['header'] = PlantillaMailHeader::find($plantillaMail->plantilla_mail_header)->header;
            }

            if ($plantillaMail->plantilla_mail_footer) {
                $array_data['footer'] = PlantillaMailHeader::find($plantillaMail->plantilla_mail_footer)->footer;
            }

            return $array_data;
        }
    }

    public function enviarMailReparacionSirenaSend($data)
    {
        $reparacion_id = $data['reparacion_id'] ?? 0;
        $id_plantilla_email = $data['id_plantilla_email'] ?? 1;

        $plantillaMail = PlantillaMail::find($id_plantilla_email);
        if ($reparacion_id != 0) {
            $reparacion = Reparacion::find($reparacion_id);
        }

        if ($plantillaMail) {
            $emailFixup = $plantillaMail->from;
            $nombreFixup = $plantillaMail->from_nombre;
            $emailCC = $plantillaMail->cc;
            $nombreCC = $plantillaMail->cc_nombre;
            $asunto = $plantillaMail->subject;

            $cuerpo = $plantillaMail->body_txt;

            //------ replace variables asunto -----------------------------------//

            $asunto = $this->replaceVariablesReparacion($reparacion, $asunto);

            //------ replace variables cuerpo -----------------------------------//

            $cuerpo = $this->replaceVariablesReparacion($reparacion, $cuerpo);

            //------ ------------------------ -----------------------------------//

            $array_data = [
                'nombre_cliente' => ($reparacion && $reparacion->cliente ? $reparacion->cliente->nombre : '-'),
                'apellido' => ($reparacion && $reparacion->cliente ? $reparacion->cliente->apellido : '-'),
                'telefono_celular' => ($reparacion && $reparacion->cliente ? $reparacion->cliente->telefono_celular : '-'),
                'email_to' => ($reparacion && $reparacion->cliente ? $reparacion->cliente->email : '-'),

                'domicilio' => ($reparacion && $reparacion->cliente ? $reparacion->cliente->domicilio : '-'),
                'localidad' => ($reparacion && $reparacion->cliente ? $reparacion->cliente->localidad : '-'),
                'codigo_postal' => ($reparacion && $reparacion->cliente ? $reparacion->cliente->codigo_postal : '-'),
                'company_id' => ($reparacion ? $reparacion->company_id : Company::DEFAULT),

                'cuerpo' => $cuerpo,
                'asunto' => $asunto,
                'email_from' => $emailFixup,
                'nombreFrom' => $nombreFixup,
                'emailBcc' => $emailCC,
                'nombreBcc' => $nombreCC,
                'view' => 'emails.plantilla'
            ];

            if ($plantillaMail->plantilla_mail_header) {
                $array_data['header'] = PlantillaMailHeader::find($plantillaMail->plantilla_mail_header)->header;
            }

            if ($plantillaMail->plantilla_mail_footer) {
                $array_data['footer'] = PlantillaMailHeader::find($plantillaMail->plantilla_mail_footer)->footer;
            }

            return $array_data;
        }
    }



    private function enviarMailSend($plantillaMail, $emailTo, $asunto, $cuerpo)
    {

        $emailFixup = $plantillaMail->from;
        $nombreFixup = $plantillaMail->from_nombre;
        $emailCC = $plantillaMail->cc;
        $nombreCC = $plantillaMail->cc_nombre;

        $array_data = [
            'cuerpo' => $cuerpo,
            'asunto' => $asunto,
            'email_to' => $emailTo,
            'email_from' => $emailFixup,
            'nombreFrom' => $nombreFixup,
            'emailBcc' => $emailCC,
            'nombreBcc' => $nombreCC,
            'view' => 'emails.plantilla'
        ];

        if ($plantillaMail->plantilla_mail_header) {
            $array_data['header'] = PlantillaMailHeader::find($plantillaMail->plantilla_mail_header)->header;
        }

        if ($plantillaMail->plantilla_mail_footer) {
            $array_data['footer'] = PlantillaMailHeader::find($plantillaMail->plantilla_mail_footer)->footer;
        }

        $user = (Auth::user() != null ? Auth::user() : User::find(99));

        try {
            $user->notify(new SendNotification($array_data, $this));
        } catch (Throwable  $e) {
            Log::alert('No se pudo enviar el mail speedup. ERROR: ' . $e->getMessage());
            Log::info($e->getFile() . '(' . $e->getLine() . ')');

            $envio_email = 'No se pudo enviar el mail speedup. ERROR: ' . $e->getMessage();
            Log::alert($envio_email);
        }
    }


    private function replaceVariablesCliente($cliente, $texto_base)
    {


        if ($cliente) {
            $provincia = Provincia::find($cliente->id_provincia);

            $texto_base = str_replace("%CLIENTE_NOMBRE%", $cliente->nombre, $texto_base);
            $texto_base = str_replace("%CLIENTE_DNI_CUIT%", $cliente->dni_cuit, $texto_base);
            $texto_base = str_replace("%CLIENTE_DOMICILIO%", $cliente->domicilio, $texto_base);
            $texto_base = str_replace("%CLIENTE_BARRIO%", $cliente->barrio, $texto_base);
            $texto_base = str_replace("%CLIENTE_LOCALIDAD%", $cliente->localidad, $texto_base);
            $texto_base = str_replace("%CLIENTE_CP%", $cliente->codigo_postal, $texto_base);
            $texto_base = str_replace("%CLIENTE_TELEFONO_LINEA%", $cliente->telefono_linea, $texto_base);
            $texto_base = str_replace("%CLIENTE_TELEFONO_CELULAR%", $cliente->telefono_celular, $texto_base);
            $texto_base = str_replace("%CLIENTE_EMAIL%", $cliente->email, $texto_base);
            $texto_base = str_replace("%CLIENTE_GRADO%", $cliente->grado, $texto_base);
            $texto_base = str_replace("%CLIENTE_ESCUELA%", $cliente->escuela, $texto_base);
            $texto_base = str_replace("%CLIENTE_APELLIDO%", $cliente->apellido, $texto_base);
            $texto_base = str_replace("%CLIENTE_PRIMER_NOMBRE%", $cliente->primer_nombre, $texto_base);
            $texto_base = str_replace("%CLIENTE_CALLE%", $cliente->calle, $texto_base);
            $texto_base = str_replace("%CLIENTE_NRO%", $cliente->nro, $texto_base);
            $texto_base = str_replace("%CLIENTE_PISO%", $cliente->piso, $texto_base);
            $texto_base = str_replace("%CLIENTE_DPTO%", $cliente->dpto, $texto_base);
            $texto_base = str_replace("%CLIENTE_OCA_CENTRO_IMPOSICION_SIGLA%", $cliente->oca_centro_imposicion_sigla, $texto_base);
            $texto_base = str_replace("%CLIENTE_OCA_CENTRO_IMPOSICION_DESCRIPCION%", $cliente->oca_centro_imposicion_descripcion, $texto_base);
            $texto_base = str_replace("%CLIENTE_OCA_CENTRO_IMPOSICION_DATOS%", $cliente->oca_centro_imposicion_datos, $texto_base);

            if ($provincia) {
                $texto_base = str_replace("%CLIENTE_PROVINCIA%", $provincia->nombre, $texto_base);
            }
        }


        return $texto_base;
    }


    public function replaceVariablesFecha($texto_base)
    {
        $texto_base = str_replace("%HOY%", date('d/m/Y'), $texto_base);
        $texto_base = str_replace("%AHORA%", date('d/m/Y H:i:s'), $texto_base);

        return $texto_base;
    }

    private function replaceVariablesReparacionTable($reparacion_table, $texto_base)
    {

        if ($reparacion_table) {
            $texto_base = str_replace("%REPARACION_ID%", $reparacion_table->id, $texto_base);
            $texto_base = str_replace("%REPARACION_NRO_RECLAMO%", $reparacion_table->nro_reclamo, $texto_base);
            $texto_base = str_replace("%REPARACION_FALLA_CLIENTE%", $reparacion_table->falla_cliente, $texto_base);
            $texto_base = str_replace("%REPARACION_FIRMA_INGRESO%", $reparacion_table->firma_ingreso, $texto_base);
            $texto_base = str_replace("%REPARACION_OBSERVACION%", $reparacion_table->observacion, $texto_base);
            $texto_base = str_replace("%REPARACION_ORDEN_TRABAJO%", $reparacion_table->orden_trabajo, $texto_base);
            $texto_base = str_replace("%REPARACION_FECHA_INGRESO%", $reparacion_table->fecha_ingreso, $texto_base);
            $texto_base = str_replace("%REPARACION_FECHA_EGRESO%", $reparacion_table->fecha_egreso, $texto_base);
            $texto_base = str_replace("%REPARACION_QUIEN_RETIRO%", $reparacion_table->quien_retiro, $texto_base);
            $texto_base = str_replace("%REPARACION_NRO_FACTURA%", $reparacion_table->nro_factura, $texto_base);
            $texto_base = str_replace("%REPARACION_FIRMA_SALIDA%", $reparacion_table->firma_salida, $texto_base);
            $texto_base = str_replace("%REPARACION_MONTO_PRESUPUESTO%", ($reparacion_table->reparacion_presupuesto ? $reparacion_table->reparacion_presupuesto->monto_presupuesto : null), $texto_base);
            $texto_base = str_replace("%REPARACION_DIAGNOSTICO%", $reparacion_table->diagnostico, $texto_base);
            $texto_base = str_replace("%REPARACION_TRABAJO_REALIZADO%", $reparacion_table->trabajo_realizado, $texto_base);
            $texto_base = str_replace("%REPARACION_FECHA_ENTREGAR%", $reparacion_table->fecha_entregar, $texto_base);
            $texto_base = str_replace("%REPARACION_PRESUPUESTO%", ($reparacion_table->reparacion_presupuesto ? $reparacion_table->reparacion_presupuesto->presupuesto : null), $texto_base);

            $texto_base = str_replace("%REPARACION_COMPROBANTE_INGRESO_FILENAME%", $reparacion_table->comprobante_ingreso_filename, $texto_base);
            $texto_base = str_replace("%REPARACION_COMPROBANTE_INGRESO%", $reparacion_table->comprobante_ingreso_filename, $texto_base);
            $texto_base = str_replace("%REPARACION_COMPROBANTE_SALIDA_FILENAME%", $reparacion_table->comprobante_salida_filename, $texto_base);
            $texto_base = str_replace("%REPARACION_COMPROBANTE_SALIDA%", $reparacion_table->comprobante_salida_filename, $texto_base);
        }
        return $texto_base;
    }


    private function replaceVariablesEquipo($equipo, $texto_base)
    {

        if ($equipo) {
            $texto_base = str_replace("%EQUIPO_NRO_SERIE%", $equipo->nro_serie, $texto_base);
            $texto_base = str_replace("%EQUIPO_PERTENECE%", $equipo->pertenece, $texto_base);
            $texto_base = str_replace("%EQUIPO_CASA_VENDEDORA%", $equipo->casa_vendedora, $texto_base);
            $texto_base = str_replace("%EQUIPO_NRO_FACTURA_COMPRA%", $equipo->nro_factura_compra, $texto_base);
            $texto_base = str_replace("%EQUIPO_USUARIO%", $equipo->usuario, $texto_base);

            $marca = Marca::find($equipo->id_marca);
            if ($marca) {
                $texto_base = str_replace("%EQUIPO_MARCA%", $marca->nombre, $texto_base);
            }

            $modelo = Modelo::find($equipo->id_modelo);
            if ($modelo) {
                $texto_base = str_replace("%EQUIPO_MODELO%", $modelo->nombre, $texto_base);
            }

            $tipoEquipo = TipoEquipo::find($equipo->id_tipo_equipo);
            if ($tipoEquipo) {
                $texto_base = str_replace("%EQUIPO_TIPO_EQUIPO%", $tipoEquipo->nombre, $texto_base);
            }
        }

        return $texto_base;
    }

    private function replaceVariablesEstado($estado, $texto_base)
    {

        if ($estado) {
            $texto_base = str_replace("%ESTADO_NOMBRE%", $estado->nombre, $texto_base);
            $texto_base = str_replace("%ESTADO_PRESUPUESTO_NOMBRE_ESTADO%", $estado->presupuesto_nombre_estado, $texto_base);
            $texto_base = str_replace("%ESTADO_PRESUPUESTO_INTRO%", $estado->presupuesto_intro, $texto_base);
            $texto_base = str_replace("%ESTADO_PRESUPUESTO_LLAMADA%", $estado->presupuesto_llamada, $texto_base);
            $texto_base = str_replace("%ESTADO_PRESUPUESTO_PASOS_ID%", $estado->presupuesto_pasos_id, $texto_base);
            $texto_base = str_replace("%ESTADO_PRESUPUESTO_AYUDA%", $estado->presupuesto_ayuda, $texto_base);
            $texto_base = str_replace("%ESTADO_PRESUPUESTO_CONTACTO%", $estado->presupuesto_contacto, $texto_base);
        }

        return $texto_base;
    }

    private function replaceVariablesServicioExtra($serviciosExtras, $texto_base)
    {
        $serviciosExtrasHtml = '';
        $serviciosExtrasProductosHtml = '';
        $serviciosExtrasTodosHtml = '';

        $serviciosExtrasConIntroHtml = '';
        $serviciosExtrasProductosConIntroHtml = '';
        $serviciosExtrasTodosConIntroHtml = '';

        $paramCompany = $this->paramCompaniesService->getParamCompany(Company::DEFAULT);

        $idServiciosExtrasProductos = $paramCompany->servicios_extras_productos_id;
        $idServiciosExtrasProductos = str_replace(" ", "", $idServiciosExtrasProductos);
        $idServiciosExtrasProductosArray = explode(",", $idServiciosExtrasProductos);

        foreach ($serviciosExtras as $servicioExtra) {
            if (in_array($servicioExtra->idservicioextra, $idServiciosExtrasProductosArray))
                $serviciosExtrasProductosHtml .= '<li>' . $servicioExtra->nombreservicioextra . '</li>';
            else
                $serviciosExtrasHtml .= '<li>' . $servicioExtra->nombreservicioextra . '</li>';
        }

        $serviciosExtrasTodosHtml = $serviciosExtrasHtml . $serviciosExtrasProductosHtml;

        if ($serviciosExtrasHtml)
            $serviciosExtrasConIntroHtml = ' <div> A continuación le sugerimos algunas recomendaciones para optimizar el funcionamiento de su equipo: <ul> ' . $serviciosExtrasHtml . '	</ul> 		</div>';

        if ($serviciosExtrasProductosHtml)
            $serviciosExtrasProductosConIntroHtml = ' <div> A continuación le sugerimos algunos productos para optimizar el funcionamiento de su equipo: <ul> ' . $serviciosExtrasProductosHtml . '	</ul> 		</div>';

        if ($serviciosExtrasTodosHtml)
            $serviciosExtrasTodosConIntroHtml = ' <div> A continuación le sugerimos algunas recomendaciones para optimizar el funcionamiento de su equipo: <ul> ' . $serviciosExtrasTodosHtml . '	</ul> 		</div>';

        $texto_base = str_replace("%SERVICIOS_EXTRAS%", $serviciosExtrasHtml, $texto_base);
        $texto_base = str_replace("%SERVICIOS_EXTRAS_CON_INTRO%", $serviciosExtrasConIntroHtml, $texto_base);

        $texto_base = str_replace("%SERVICIOS_EXTRAS_PRODUCTOS%", $serviciosExtrasProductosHtml, $texto_base);
        $texto_base = str_replace("%SERVICIOS_EXTRAS_PRODUCTOS_CON_INTRO%", $serviciosExtrasProductosConIntroHtml, $texto_base);

        $texto_base = str_replace("%SERVICIOS_EXTRAS_PRODUCTOS_TODOS%", $serviciosExtrasTodosHtml, $texto_base);
        $texto_base = str_replace("%SERVICIOS_EXTRAS_PRODUCTOS_TODOS_CON_INTRO%", $serviciosExtrasTodosConIntroHtml, $texto_base);

        return $texto_base;
    }

    private function replaceVariablesOrdenOca($ordenOca, $texto_base)
    {

        if ($ordenOca) {
            $texto_base = str_replace("%ORDEN_OCA_ID%", $ordenOca->id, $texto_base);
            $texto_base = str_replace("%ORDEN_OCA_NRO_ASSURANT%", $ordenOca->nro_assurant, $texto_base);
            $texto_base = str_replace("%ORDEN_OCA_NRO_ORDEN_RETIRO%", $ordenOca->nro_orden_retiro, $texto_base);
            $texto_base = str_replace("%ORDEN_OCA_NRO_ENVIO%", $ordenOca->nro_envio, $texto_base);
            $texto_base = str_replace("%ORDEN_OCA_PAQUETE_PESO%", $ordenOca->paquete_peso, $texto_base);
            $texto_base = str_replace("%ORDEN_OCA_PAQUETE_ALTO%", $ordenOca->paquete_alto, $texto_base);
            $texto_base = str_replace("%ORDEN_OCA_PAQUETE_LARGO%", $ordenOca->paquete_largo, $texto_base);
            $texto_base = str_replace("%ORDEN_OCA_PAQUETE_ANCHO%", $ordenOca->paquete_ancho, $texto_base);
            $texto_base = str_replace("%ORDEN_OCA_PAQUETE_VALOR%", $ordenOca->paquete_valor, $texto_base);
            $texto_base = str_replace("%ORDEN_OCA_PAQUETE_FALLA%", $ordenOca->falla, $texto_base);
            $texto_base = str_replace("%ORDEN_OCA_PAQUETE_COMENTARIO%", $ordenOca->comentario, $texto_base);
            $texto_base = str_replace("%REPARACION_ID%", $ordenOca->id_reparacion, $texto_base);
            $texto_base = str_replace("%ORDEN_OCA_NRO_ORDEN_RETIRO_DEVOLUCION%", $ordenOca->nro_orden_retiro_devolucion, $texto_base);
            $texto_base = str_replace("%ORDEN_OCA_NRO_ENVIO_DEVOLUCION%", $ordenOca->nro_envio_devolucion, $texto_base);
        }

        return $texto_base;
    }

    private function replaceVariablesOrdenCambioSucursal($ordenCambioSucursal, $texto_base)
    {
        $pathBase = env('APP_URL');
        $comprobante_ida_filename_path = $pathBase . '/tmp/' . $ordenCambioSucursal->reparacion_id . 'comprobante_ida_filename.pdf';
        $comprobante_vuelta_filename_path = $pathBase . '/tmp/' . $ordenCambioSucursal->reparacion_id . 'comprobante_vuelta_filename.pdf';

        if ($ordenCambioSucursal) {
            $texto_base = str_replace("%ORDEN_CAMBIO_SUCURSAL_ID%", $ordenCambioSucursal->id, $texto_base);
            $texto_base = str_replace("%ORDEN_CAMBIO_SUCURSAL_CODIGO_ENVIO%", $ordenCambioSucursal->codigo_envio, $texto_base);
            $texto_base = str_replace("%ORDEN_CAMBIO_SUCURSAL_LINK%", $ordenCambioSucursal->link, $texto_base);
            $texto_base = str_replace("%ORDEN_CAMBIO_SUCURSAL_CODIGO_ENVIO_DOMICILIO%", $ordenCambioSucursal->codigo_envio_devolucion, $texto_base);
            $texto_base = str_replace("%ORDEN_CAMBIO_SUCURSAL_LINK_DEVOLUCION%", $ordenCambioSucursal->link_devolucion, $texto_base);
            $texto_base = str_replace("%ORDEN_CAMBIO_SUCURSAL_SUCURSAL_ORIGEN_ID%", $ordenCambioSucursal->sucursal_origen_id, $texto_base);
            $texto_base = str_replace("%ORDEN_CAMBIO_SUCURSAL_SUCURSAL_ORIGEN%", ($ordenCambioSucursal->sucursalOrigen ? $ordenCambioSucursal->sucursalOrigen->nombre : ''), $texto_base);
            $texto_base = str_replace("%ORDEN_CAMBIO_SUCURSAL_SUCURSAL_DEVOLUCION_ID%", $ordenCambioSucursal->sucursal_detino_id, $texto_base);
            $texto_base = str_replace("%ORDEN_CAMBIO_SUCURSAL_SUCURSAL_DEVOLUCION%", ($ordenCambioSucursal->sucursalDestino ? $ordenCambioSucursal->sucursalDestino->nombre : ''), $texto_base);
            $texto_base = str_replace("%ORDEN_CAMBIO_SUCURSAL_MOTIVO_CAMBIO_SUCURSAL_ID%", $ordenCambioSucursal->motivo_cambio_sucursal_id, $texto_base);
            $texto_base = str_replace("%ORDEN_CAMBIO_SUCURSAL_MOTIVO_CAMBIO_SUCURSAL%", ($ordenCambioSucursal->motivoCambioSucursal ? $ordenCambioSucursal->motivoCambioSucursal->nombre : ''), $texto_base);
            $texto_base = str_replace("%ORDEN_CAMBIO_SUCURSAL_ESTADO_CAMBIO_SUCURSAL_ID%", $ordenCambioSucursal->estado_cambio_sucursal_id, $texto_base);
            $texto_base = str_replace("%ORDEN_CAMBIO_SUCURSAL_ESTADO_CAMBIO_SUCURSAL%", ($ordenCambioSucursal->estadoCambioSucursal ? $ordenCambioSucursal->estadoCambioSucursal->nombre : ''), $texto_base);
            $texto_base = str_replace("%ORDEN_CAMBIO_SUCURSAL_PROVEEDOR_LOGISTICO_ID%", $ordenCambioSucursal->proveedor_logistico_id, $texto_base);
            $texto_base = str_replace("%ORDEN_CAMBIO_SUCURSAL_PROVEEDOR_LOGISTICO_DEVOLUCION_ID%", $ordenCambioSucursal->proveedor_logisitico_devolucion_id, $texto_base);
            $texto_base = str_replace("%ORDEN_CAMBIO_SUCURSAL_REPARACION_ID%", $ordenCambioSucursal->reparacion_id, $texto_base);
            $texto_base = str_replace("%ORDEN_CAMBIO_SUCURSAL_COMPROBANTE_IDA_FILENAME%", $comprobante_ida_filename_path, $texto_base);
            $texto_base = str_replace("%ORDEN_CAMBIO_SUCURSAL_COMPROBANTE_VUELTA_FILENAME%", $comprobante_vuelta_filename_path, $texto_base);
        }

        return $texto_base;
    }


    private function replaceVariablesOrdenElocker($ordenElocker, $texto_base)
    {

        if ($ordenElocker) {
            $texto_base = str_replace("%ORDEN_ELOCKER_ID%", $ordenElocker->id, $texto_base);
            $texto_base = str_replace("%ORDEN_ELOCKER_NRO_RECLAMO%", $ordenElocker->nro_reclamo, $texto_base);
            $texto_base = str_replace("%ORDEN_ELOCKER_ETIQUETA_NRO%", $ordenElocker->etiqueta_id, $texto_base);
            $texto_base = str_replace("%ORDEN_ELOCKER_ETIQUETA_URL%", $ordenElocker->etiqueta_url, $texto_base);
            $texto_base = str_replace("%ORDEN_ELOCKER_ELOCKER_ID%", $ordenElocker->elocker_id, $texto_base);
            $texto_base = str_replace("%ORDEN_ELOCKER_ELOCKER_DESCRIPCION%", $ordenElocker->elocker_descripcion, $texto_base);
            $texto_base = str_replace("%ORDEN_ELOCKER_ELOCKER_LATITUD%", $ordenElocker->elocker_latitud, $texto_base);
            $texto_base = str_replace("%ORDEN_ELOCKER_ELOCKER_LONGITUD%", $ordenElocker->elocker_longitud, $texto_base);
            $texto_base = str_replace("%ORDEN_ELOCKER_FALLA%", $ordenElocker->falla, $texto_base);
            $texto_base = str_replace("%ORDEN_ELOCKER_COMENTARIO%", $ordenElocker->comentario, $texto_base);
            $texto_base = str_replace("%REPARACION_ID%", $ordenElocker->id_reparacion, $texto_base);
        }
        return $texto_base;
    }

    private function replaceVariablesOrdenCorreoArgentino($ordenCorreoArgentino, $texto_base)
    {
        if ($ordenCorreoArgentino) {
            $pathBase = env('APP_URL_HOSTING', '/');
            $pathCorreoArgentinoEtiquetas = $pathBase . 'pdf/correoargentino/';
            $fullPathRotulo = $pathCorreoArgentinoEtiquetas . $ordenCorreoArgentino->rotulo;
            $fullPathRotuloDevolucion = $pathCorreoArgentinoEtiquetas . $ordenCorreoArgentino->rotulo_devolucion;

            $texto_base = str_replace("%ORDEN_NRO_RECLAMO%", $ordenCorreoArgentino->nro_reclamo, $texto_base);
            $texto_base = str_replace("%ORDEN_TN%", $ordenCorreoArgentino->tn, $texto_base);
            $texto_base = str_replace("%ORDEN_ROTULO%", $fullPathRotulo, $texto_base);

            $texto_base = str_replace("%ORDEN_TN_DEVOLUCION%", $ordenCorreoArgentino->tn_devolucion, $texto_base);
            $texto_base = str_replace("%ORDEN_ROTULO_DEVOLUCION%", $fullPathRotuloDevolucion, $texto_base);

            $texto_base = str_replace("%ORDEN_COMPROBANTE_URL%", $ordenCorreoArgentino->rotulo, $texto_base);

            $texto_base = str_replace("%ORDEN_SUCURSAL_PLANTA%", $ordenCorreoArgentino->sucursal_planta, $texto_base);
            $texto_base = str_replace("%ORDEN_SUCURSAL_ID%", $ordenCorreoArgentino->sucursal, $texto_base);
            $texto_base = str_replace("%ORDEN_SUCURSAL_CPA%", $ordenCorreoArgentino->sucursal_cpa, $texto_base);
            $texto_base = str_replace("%ORDEN_SUCURSAL_DATOS%", $ordenCorreoArgentino->sucursal_datos, $texto_base);
            $texto_base = str_replace("%ORDEN_SUCURSAL_LATITUD%", $ordenCorreoArgentino->sucursal_latitud, $texto_base);
            $texto_base = str_replace("%ORDEN_SUCURSAL_LONGITUD%", $ordenCorreoArgentino->sucursal_longitud, $texto_base);

            $texto_base = str_replace("%EQUIPO_MARCA%", $ordenCorreoArgentino->equipo_marca, $texto_base);
            $texto_base = str_replace("%EQUIPO_NRO_SERIE%", $ordenCorreoArgentino->equipo_nro_serie, $texto_base);

            $texto_base = str_replace("%ORDEN_SUCURSAL_FALLA%", $ordenCorreoArgentino->falla, $texto_base);
            $texto_base = str_replace("%ORDEN_SUCURSAL_COMENTARIO%", $ordenCorreoArgentino->comentario, $texto_base);
        }


        return $texto_base;
    }

    private function replaceVariablesReparacionPago($reparacionPago, $texto_base)
    {
        if ($reparacionPago && $reparacionPago->mercadoPagoPayment && $reparacionPago->mercadoPagoPayment->first()) {
            $mercadoPagoPayment = $reparacionPago->mercadoPagoPayment->first();
            $texto_base = $this->replaceVariablesMercadoPagoPayment($mercadoPagoPayment->id, $texto_base);
        }
        return $texto_base;
    }

    private function replaceVariablesMercadoPagoPayment($mercadoPagoPayment_id, $texto_base)
    {
        $mercadoPagoPayment = MercadoPagoPayment::find($mercadoPagoPayment_id);

        if ($mercadoPagoPayment) {
            $texto_base = str_replace("%REPARACION_MP_INIT_POINT%", $mercadoPagoPayment->init_point, $texto_base);
            $texto_base = str_replace("%REPARACION_MP_COLLECTION_STATUS%", $mercadoPagoPayment->collection_status, $texto_base);
            $texto_base = str_replace("%REPARACION_MP_TRANSACTION_ID%", $mercadoPagoPayment->transaction_id, $texto_base);
        }
        return $texto_base;
    }

    private function replaceVariablesEnvioMercadoPago($envioMercadoPago, $texto_base)
    {
        if ($envioMercadoPago && $envioMercadoPago->mercadoPagoPayment) {
            $texto_base = str_replace("%ENVIO_MP_INIT_POINT%", $envioMercadoPago->mercadoPagoPayment->init_point, $texto_base);
            $texto_base = str_replace("%ENVIO_MP_COLLECTION_STATUS%", $envioMercadoPago->mercadoPagoPayment->collection_status, $texto_base);
            $texto_base = str_replace("%ENVIO_MP_TRANSACTION_ID%", $envioMercadoPago->mercadoPagoPayment->transaction_id, $texto_base);
        }
        return $texto_base;
    }

    private function replaceVariablesDerivacionTable($derivacion, $texto_base)
    {

        if ($derivacion) {
            $texto_base = str_replace("%DERIVACION_NOMBRE%", $derivacion->cliente_derivacion ? $derivacion->cliente_derivacion->nombre : '', $texto_base);
            $texto_base = str_replace("%DERIVACION_ID%", $derivacion->id, $texto_base);
            $texto_base = str_replace("%DERIVACION_EMAIL%", $derivacion->cliente_derivacion ? $derivacion->cliente_derivacion->email : '', $texto_base);
            $texto_base = str_replace("%DERIVACION_TELEFONO%", $derivacion->cliente_derivacion ? $derivacion->cliente_derivacion->telefono : '', $texto_base);
            $texto_base = str_replace("%DERIVACION_DIRECCION%", isset($derivacion->cliente_derivacion) ? $derivacion->cliente_derivacion->direccion : '', $texto_base);
            $texto_base = str_replace("%DERIVACION_CODIGO_POSTAL%", isset($derivacion->cliente_derivacion) ? $derivacion->cliente_derivacion->codigo_postal : '', $texto_base);
            $texto_base = str_replace("%DERIVACION_LOCALIDAD%", isset($derivacion->cliente_derivacion) ? $derivacion->cliente_derivacion->localidad : '', $texto_base);
            $texto_base = str_replace("%DERIVACION_LATITUD%", isset($derivacion->cliente_derivacion) ? $derivacion->cliente_derivacion->latitud_ubicacion : '', $texto_base);
            $texto_base = str_replace("%DERIVACION_LONGITUD%", isset($derivacion->cliente_derivacion) ? $derivacion->cliente_derivacion->longitud_ubicacion : '', $texto_base);
            $texto_base = str_replace("%DERIVACION_EQUIPO_DERIVACION%", isset($derivacion->equipo_derivacion) ? $derivacion->equipo_derivacion->id : '', $texto_base);
            $texto_base = str_replace("%DERIVACION_TIPO_COTIZACION%", isset($derivacion->tipo_cotizacion) ? $derivacion->tipo_cotizacion : '', $texto_base);
            $texto_base = str_replace("%DERIVACION_MOTIVO_MAS_INFO%", isset($derivacion->motivoMasInfoCotizacion) ? $derivacion->motivoMasInfoCotizacion->nombre : '', $texto_base);
            $texto_base = str_replace("%DERIVACION_MOTIVO_IMPOSIBLE%", isset($derivacion->motivoImposibleCotizacion) ? $derivacion->motivoImposibleCotizacion->nombre : '', $texto_base);
            $texto_base = str_replace("%DERIVACION_DIA_PREFERENCIA%", $derivacion->dia_preferencia, $texto_base);
            $texto_base = str_replace("%DERIVACION_HORA_PREFERENCIA%", date('h:i a', strtotime($derivacion->hora_preferencia)), $texto_base);
            $texto_base = str_replace("%DERIVACION_PLAZO%", $derivacion->plazo, $texto_base);
            if ($derivacion->presupuesto_derivacion) {
                $texto_base = str_replace("%DERIVACION_MONTO%", $derivacion->presupuesto_derivacion->monto, $texto_base);
                $texto_base = str_replace("%DERIVACION_MERCADO_PAGO%", $derivacion->presupuesto_derivacion->mercado_pago_transaccion, $texto_base);
                $texto_base = str_replace("%DERIVACION_MERCADO_PAGO_ESTADO%", $derivacion->presupuesto_derivacion->mercado_pago_estado, $texto_base);
                $texto_base = str_replace("%DERIVACION_MERCADO_PAGO_REFERENCIA%", $derivacion->presupuesto_derivacion->mercado_pago_referencia, $texto_base);
                $texto_base = str_replace("%DERIVACION_MERCADO_PAGO_COLLECTION_ID%", $derivacion->presupuesto_derivacion->mercado_pago_collection_id, $texto_base);
                $texto_base = str_replace("%DERIVACION_MERCADO_PAGO_PAYMENT_TYPE%", $derivacion->presupuesto_derivacion->mercado_pago_payment_type, $texto_base);
                $texto_base = str_replace("%DERIVACION_MONTO_ONLINE%", $derivacion->presupuesto_derivacion->monto_online, $texto_base);
                $texto_base = str_replace("%DERIVACION_CLAIM%", $derivacion->presupuesto_derivacion->claim, $texto_base);
                $texto_base = str_replace("%DERIVACION_CODIGO_DESCUENTO%", $derivacion->presupuesto_derivacion->codigo_descuento, $texto_base);
                $texto_base = str_replace("%DERIVACION_MONTO_BASE%", $derivacion->presupuesto_derivacion->monto_base, $texto_base);
                $texto_base = str_replace("%DERIVACION_COSTO_FINANCIERO%", $derivacion->presupuesto_derivacion->costo_financiero, $texto_base);
                $texto_base = str_replace("%DERIVACION_DESCUENTO_VALOR%", $derivacion->presupuesto_derivacion->descuento_valor, $texto_base);
                $texto_base = str_replace("%DERIVACION_MERCADO_PAGO_URL%", $derivacion->presupuesto_derivacion->mercado_pago_url, $texto_base);
            }

            $texto_base = str_replace("%DERIVACION_COMENTARIOS%", $derivacion->comentarios, $texto_base);
            $texto_base = str_replace("%DERIVACION_COMENTARIOS_MAIL_CLIENTE%", $derivacion->comentarios_mail_cliente, $texto_base);
            $texto_base = str_replace("%DERIVACION_FECHA_A_COTIZAR%", $derivacion->fecha_a_cotizar, $texto_base);
            $texto_base = str_replace("%DERIVACION_IMPOSIBLE_COTIZAR_REVISADO%", $derivacion->imposible_cotizar_revisado, $texto_base);
            $texto_base = str_replace("%DERIVACION_USER_COTIZAR%", $derivacion->user_cotizar_id, $texto_base);
            $texto_base = str_replace("%DERIVACION_SIN_SERVICIO_NOTIFICADO%", $derivacion->sin_servicio_notificado, $texto_base);
            $texto_base = str_replace("%DERIVACION_BTN_SELECCIONADO%", $derivacion->btn_seleccionado, $texto_base);

            $texto_base = str_replace("%DERIVACION_ORIGEN%", $derivacion->origen, $texto_base);
            $texto_base = str_replace("%DERIVACION_OBSERVACIONES_INTERNAS%", $derivacion->observaciones_internas, $texto_base);
            $texto_base = str_replace("%DERIVACION_ESTADO_DERIVACION%", $derivacion->estado ? $derivacion->estado->nombre : '', $texto_base);
            $texto_base = str_replace("%DERIVACION_SUCURSAL%", $derivacion->sucursal ? $derivacion->sucursal->nombre : '', $texto_base);
            $texto_base = str_replace("%DERIVACION_MERCADO_PAGO_URL%", $derivacion->mercado_pago_url, $texto_base);
            $texto_base = str_replace("%DERIVACION_ORDEN_OCA_NRO%", isset($derivacion->oca_nro_orden_retiro) ?? '', $texto_base);
            $texto_base = str_replace("%DERIVACION_OCA_NRO_ENVIO%", isset($derivacion->oca_nro_envio) ?? '', $texto_base);
            $texto_base = str_replace("%DERIVACION_OCA_CODIGO_OPERACION%", isset($derivacion->oca_codigo_operacion) ?? '', $texto_base);
            $texto_base = str_replace("%DERIVACION_OCA_SUCURSAL_ENVIO%", isset($derivacion->oca_sucursal_envio) ?? '', $texto_base);
            $texto_base = str_replace("%DERIVACION_OCA_ESTADO%", isset($derivacion->oca_estado) ?? '', $texto_base);

            $texto_base = str_replace("%DERIVACION_MOTIVO_CANCELACION_DERIVACION%", $derivacion->motivoCancelacionDerivacion ? $derivacion->motivoCancelacionDerivacion->nombre : '', $texto_base);
            $texto_base = str_replace("%DERIVACION_MOTIVO_INTERES_DERIVACION%", $derivacion->motivoInteresDerivacion ? $derivacion->motivoInteresDerivacion->nombre : '', $texto_base);
            $texto_base = str_replace("%DERIVACION_ESTADO_COTIZACION_DERIVACION%", $derivacion->estadosCotizacionDerivacion ? $derivacion->estadosCotizacionDerivacion->nombre : '', $texto_base);
            $texto_base = str_replace("%MARCA_INPUT%", $derivacion->equipo_derivacion ? $derivacion->equipo_derivacion->marca_input : '', $texto_base);
            $texto_base = str_replace("%MODELO_INPUT%", $derivacion->equipo_derivacion ? $derivacion->equipo_derivacion->modelo_input : '', $texto_base);
            $texto_base = str_replace("%FALLA_INPUT%", $derivacion->falla_input, $texto_base);
            $texto_base = str_replace("%TURNO_ID%", ($derivacion->turno ? $derivacion->turno->id : null), $texto_base);
        }

        return $texto_base;
    }

    private function replaceVariablesGrupoEquipo($grupoEquipo, $texto_base)
    {
        if ($grupoEquipo) {
            $texto_base = str_replace("%GRUPO_EQUIPO_NOMBRE%", ($grupoEquipo ? $grupoEquipo->nombre : ''), $texto_base);
            $texto_base = str_replace("%GRUPO_EQUIPO_DESCRIPCION%", ($grupoEquipo ? $grupoEquipo->descripcion : ''), $texto_base);

            $texto_base = str_replace("%GRUPO_EQUIPO_TEXTO_COMPLEMENTARIO%", ($grupoEquipo ? $grupoEquipo->texto_complementario : ''), $texto_base);

            $texto_base = str_replace("%GRUPO_EQUIPO_PAQUETE_PESO%", ($grupoEquipo ? $grupoEquipo->paquete_peso : ''), $texto_base);

            $texto_base = str_replace("%GRUPO_EQUIPO_PAQUETE_ALTO%", ($grupoEquipo ? $grupoEquipo->paquete_alto : ''), $texto_base);

            $texto_base = str_replace("%GRUPO_EQUIPO_PAQUETE_LARGO%", ($grupoEquipo ? $grupoEquipo->paquete_largo : ''), $texto_base);

            $texto_base = str_replace("%GRUPO_EQUIPO_PAQUETE_ANGO%", ($grupoEquipo ? $grupoEquipo->paquete_ancho : ''), $texto_base);

            $texto_base = str_replace("%GRUPO_EQUIPO_PAQUETE_VALOR%", ($grupoEquipo ? $grupoEquipo->paquete_valor : ''), $texto_base);
            $texto_base = str_replace("%GRUPO_EQUIPO_DESTINATARIO_APELLIDO%", ($grupoEquipo ? $grupoEquipo->destinatario_apellido : ''), $texto_base);
            $texto_base = str_replace("%GRUPO_EQUIPO_DESTINATARIO_NOMBRE%", ($grupoEquipo ? $grupoEquipo->destinatario_nombre : ''), $texto_base);
            $texto_base = str_replace("%GRUPO_EQUIPO_DESTINATARIO_CALLE%", ($grupoEquipo ? $grupoEquipo->destinatario_calle : ''), $texto_base);
            $texto_base = str_replace("%GRUPO_EQUIPO_DESTINATARIO_NRO%", ($grupoEquipo ? $grupoEquipo->destinatario_nro : ''), $texto_base);
            $texto_base = str_replace("%GRUPO_EQUIPO_DESTINATARIO_PISO%", ($grupoEquipo ? $grupoEquipo->destinatario_piso : ''), $texto_base);
            $texto_base = str_replace("%GRUPO_EQUIPO_DESTINATARIO_DPTO%", ($grupoEquipo ? $grupoEquipo->destinatario_dpto : ''), $texto_base);

            $texto_base = str_replace("%GRUPO_EQUIPO_DESTINATARIO_LOCALIDAD%", ($grupoEquipo ? $grupoEquipo->destinatario_localidad : ''), $texto_base);
            $texto_base = str_replace("%GRUPO_EQUIPO_DESTINATARIO_PROVINCIA%", ($grupoEquipo ? $grupoEquipo->destinatario_provincia : ''), $texto_base);
            $texto_base = str_replace("%GRUPO_EQUIPO_DESTINATARIO_CP%", ($grupoEquipo ? $grupoEquipo->destinatario_cp : ''), $texto_base);
            $texto_base = str_replace("%GRUPO_EQUIPO_DESTINATARIO_TELEFONO%", ($grupoEquipo ? $grupoEquipo->destinatario_telefono : ''), $texto_base);
            $texto_base = str_replace("%GRUPO_EQUIPO_DESTINATARIO_EMAIL%", ($grupoEquipo ? $grupoEquipo->destinatario_email : ''), $texto_base);
            $texto_base = str_replace("%GRUPO_EQUIPO_CELULAR%", ($grupoEquipo ? $grupoEquipo->destinatario_celular : ''), $texto_base);

            $texto_base = str_replace("%GRUPO_EQUIPO_ID%", $grupoEquipo->id, $texto_base);
        }

        return $texto_base;
    }

    private function replaceVariablesMarca($marca, $texto_base)
    {
        if ($marca) {
            $texto_base = str_replace("%MARCA_NOMBRE%", ($marca ? $marca->nombre : ''), $texto_base);
            $texto_base = str_replace("%MARCA_ID%", ($marca ? $marca->id : ''), $texto_base);
        }

        return $texto_base;
    }

    private function replaceVariablesModelo($modelo, $texto_base)
    {

        if ($modelo) {
            $texto_base = str_replace("%MODELO_NOMBRE%", ($modelo ? $modelo->nombre : ''), $texto_base);
            $texto_base = str_replace("%MODELO_ID%", ($modelo ? $modelo->id : ''), $texto_base);
        }

        return $texto_base;
    }

    private function replaceVariablesFallaGrupoEquipo($fallaGrupoEquipo, $texto_base)
    {

        if ($fallaGrupoEquipo) {
            $texto_base = str_replace("%FALLA_NOMBRE%", ($fallaGrupoEquipo ? $fallaGrupoEquipo->nombre : ''), $texto_base);
            $texto_base = str_replace("%FALLA_ID%", ($fallaGrupoEquipo ? $fallaGrupoEquipo->id : ''), $texto_base);
        }

        return $texto_base;
    }

    private function replaceVariablesServicio($idServicio, $texto_base)
    {
        $servicio = Servicio::find($idServicio);
        if ($servicio) {
            $texto_base = str_replace("%SERVICIO_NOMBRE%", ($servicio ? $servicio->nombre : ''), $texto_base);
            $texto_base = str_replace("%SERVICIO_DESCRIPCION%", ($servicio ? $servicio->descripcion : ''), $texto_base);
            $texto_base = str_replace("%SERVICIO_LEYENDA%", ($servicio ? $servicio->leyenda : ''), $texto_base);
            $texto_base = str_replace("%SERVICIO_ID%", ($servicio ? $servicio->id : ''), $texto_base);
        }

        return $texto_base;
    }

    private function replaceVariablesEstadoDerivacion($estadoDerivacion, $texto_base)
    {

        if ($estadoDerivacion) {
            $texto_base = str_replace("%ESTADO_DERIVACION%", $estadoDerivacion->nombre, $texto_base);
        }

        return $texto_base;
    }

    private function replaceVariablesSucursal($sucursal, $texto_base)
    {
        if ($sucursal) {
            $path = env('APP_URL_HOSTING', '/');
            $pathImagenes = $path . 'imagenes/';
            $imgSucursal = '<img src="' . $pathImagenes . $sucursal->direccion_mapa_imagen . '">';

            $texto_base = str_replace("%SUCURSAL_NOMBRE%", $sucursal->nombre, $texto_base);
            $texto_base = str_replace("%SUCURSAL_EMAIL%", $sucursal->email, $texto_base);
            $texto_base = str_replace("%SUCURSAL_LATITUD%", $sucursal->latitud, $texto_base);
            $texto_base = str_replace("%SUCURSAL_LONGITUD%", $sucursal->longitud, $texto_base);
            $texto_base = str_replace("%SUCURSAL_DIRECCION%", $sucursal->direccion, $texto_base);
            $texto_base = str_replace("%SUCURSAL_DIRECCION_APROXIMADA%", $sucursal->direccion_aproximada, $texto_base);
            $texto_base = str_replace("%SUCURSAL_DIRECCION_REFERENCIA%", $sucursal->direccion_referencia, $texto_base);
            $texto_base = str_replace("%SUCURSAL_DIRECCION_MAPA%", $sucursal->direccion_mapa, $texto_base);
            $texto_base = str_replace("%SUCURSAL_DIRECCION_MAPA_VINCULO%", $sucursal->direccion_mapa_vinculo, $texto_base);
            $texto_base = str_replace("%SUCURSAL_DIRECCION_MAPA_EMBED%", $sucursal->direccion_mapa_embed, $texto_base);
            $texto_base = str_replace("%SUCURSAL_DIRECCION_MAPA_IMAGEN%", $imgSucursal, $texto_base);
            $texto_base = str_replace("%SUCURSAL_HORARIOS_ATENCION%", $sucursal->horarios_atencion, $texto_base);

            $texto_base = str_replace("%SUCURSAL_ATENCION_HORA_INICIO_LUNES%", $sucursal->atencion_hora_inicio_lunes, $texto_base);
            $texto_base = str_replace("%SUCURSAL_ATENCION_HORA_FIN_LUNES%", $sucursal->atencion_hora_fin_lunes, $texto_base);
            $texto_base = str_replace("%SUCURSAL_ATENCION_HORA_INICIO_SABADO%", $sucursal->atencion_hora_inicio_sabado, $texto_base);
            $texto_base = str_replace("%SUCURSAL_ATENCION_HORA_FIN_LUNES%", $sucursal->atencion_hora_fin_sabado, $texto_base);
            $texto_base = str_replace("%SUCURSAL_DIAS_NO_LABORALES%", $sucursal->dias_no_laborables, $texto_base);
        }
        return $texto_base;
    }

    private function replaceVariablesEquipoDerivacion($equipoDerivacion, $texto_base)
    {

        if ($equipoDerivacion) {

            $texto_base = str_replace("%EQUIPO_DERIVACION_CLIENTE_DERIVACION%", $equipoDerivacion->ClienteDerivacion ? $equipoDerivacion->ClienteDerivacion->nombre : '', $texto_base);
            $texto_base = str_replace("%EQUIPO_DERIVACION_GRUPO_EQUIPO%", $equipoDerivacion->grupo_equipo ? $equipoDerivacion->grupo_equipo->nombre : '', $texto_base);
            $texto_base = str_replace("%EQUIPO_DERIVACION_MARCA%", $equipoDerivacion->marca ? $equipoDerivacion->marca->nombre : '', $texto_base);
            $texto_base = str_replace("%EQUIPO_DERIVACION_MARCA_INPUT%", $equipoDerivacion ? $equipoDerivacion->marca_input : '', $texto_base);
            $texto_base = str_replace("%EQUIPO_DERIVACION_MODELO%", $equipoDerivacion->modelo ?  $equipoDerivacion->modelo->nombre : '', $texto_base);
            $texto_base = str_replace("%EQUIPO_DERIVACION_MODELO_INPUT%", $equipoDerivacion->modelo_input, $texto_base);
            $texto_base = str_replace("%EQUIPO_DERIVACION_COLOR%", isset($equipoDerivacion->color) ? $equipoDerivacion->color->nombre : '', $texto_base);
            $texto_base = str_replace("%DERIVACION_COLOR%", isset($equipoDerivacion->color) ? $equipoDerivacion->color->nombre : '', $texto_base);
            $texto_base = str_replace("%EQUIPO_DERIVACION_COLOR_INPUT%", isset($equipoDerivacion->color_input) ? $equipoDerivacion->color_input : '', $texto_base);
            $texto_base = str_replace("%EQUIPO_DERIVACION_OPERADOR%", isset($equipoDerivacion->operador) ? $equipoDerivacion->operador->nombre : '', $texto_base);
            $texto_base = str_replace("%EQUIPO_DERIVACION_IMEI%", $equipoDerivacion->imei, $texto_base);
        }

        return $texto_base;
    }

    private function replaceVariablesClienteDerivacion($clienteDerivacion, $texto_base)
    {

        if ($clienteDerivacion) {
            $texto_base = str_replace("%CLIENTE_DERIVACION_NOMBRE%", $clienteDerivacion->nombre, $texto_base);
            $texto_base = str_replace("%CLIENTE_DERIVACION_DNI%", $clienteDerivacion->dni, $texto_base);
            $texto_base = str_replace("%CLIENTE_DERIVACION_TELEFONO%", isset($clienteDerivacion->telefono) ? $clienteDerivacion->telefono : '', $texto_base);
            $texto_base = str_replace("%CLIENTE_DERIVACION_DIRECCION%", isset($clienteDerivacion->direccion) ? $clienteDerivacion->direccion : '', $texto_base);
            $texto_base = str_replace("%CLIENTE_DERIVACION_PISO%", isset($clienteDerivacion->piso) ? $clienteDerivacion->piso : '', $texto_base);
            $texto_base = str_replace("%CLIENTE_DERIVACION_DPTO%", isset($clienteDerivacion->dpto) ? $clienteDerivacion->dpto : '', $texto_base);
            $texto_base = str_replace("%CLIENTE_DERIVACION_CODIGO_POSTAL%", isset($clienteDerivacion->codigo_postal) ? $clienteDerivacion->codigo_postal : '', $texto_base);
            $texto_base = str_replace("%CLIENTE_DERIVACION_LOCALIDAD%", isset($clienteDerivacion->localidad) ? $clienteDerivacion->localidad : '', $texto_base);
            $texto_base = str_replace("%CLIENTE_DERIVACION_LATITUD_UBICACION%", isset($clienteDerivacion->latitud_ubicacion) ? $clienteDerivacion->latitud_ubicacion : '', $texto_base);
            $texto_base = str_replace("%CLIENTE_DERIVACION_LONGITUD_UBICACION%", isset($clienteDerivacion->longitud_ubicacion) ? $clienteDerivacion->longitud_ubicacion : '', $texto_base);
        }

        return $texto_base;
    }

    private function replaceVariablesModel($arrayCollection, $html, $derivacion = null, $reparacion = null)
    {
        foreach ($arrayCollection as $model) {
            if ($model == 'EquipoDerivacion') {
                $html = $this->replaceVariablesEquipoDerivacion($derivacion->equipo_derivacion, $html);
            }

            if ($model == 'EstadoDerivacion') {
                $html = $this->replaceVariablesEstadoDerivacion($derivacion->estado_derivacion, $html);
            }

            if ($model == 'Derivacion') {
                $html = $this->replaceVariablesDerivacionTable($derivacion, $html);
            }

            if ($model == 'Sucursal') {
                $html = $this->replaceVariablesSucursal($derivacion->sucursal, $html);
            }

            if ($model == 'Servicio') {
                $html = $this->replaceVariablesServicio($derivacion->id_servicio, $html);
            }

            if ($model == 'Falla') {
                $html = $this->replaceVariablesFallaGrupoEquipo($derivacion->falla(), $html);
            }

            if ($model == 'Modelo') {
                $html = $this->replaceVariablesModelo($derivacion->equipo_derivacion->modelo, $html);
            }

            if ($model == 'Marca') {
                $html = $this->replaceVariablesMarca($derivacion->equipo_derivacion->marca, $html);
            }

            if ($model == 'GrupoEquipo') {
                $html = $this->replaceVariablesGrupoEquipo($derivacion->equipo_derivacion->grupo_equipo, $html);
            }

            if ($model == 'EnvioMercadoPago') {
                if ($reparacion && $reparacion->envios_mercado_pago->count()) {
                    $envioMercadoPago = $reparacion->envios_mercado_pago[0];
                    $html == $this->replaceVariablesEnvioMercadoPago($envioMercadoPago, $html);
                }
            }

            if ($model == 'ReparacionMercadoPagoPayment') {
                if ($reparacion && $reparacion->reparaciones_pago->count()) {
                    $reparacionPago = $reparacion->reparaciones_pago[0];
                    $html = $this->replaceVariablesReparacionPago($reparacionPago, $html);
                }
            }

            if ($model == 'ServicioExtra') {
                $serviciosExtras = ServicioExtraReparacion::listarNombresServiciosExtrasPorReparacionSugeridos($reparacion->id);

                $html = $this->replaceVariablesServicioExtra($serviciosExtras, $html);
            }

            if ($model == 'Equipo') {
                $html = $this->replaceVariablesEquipo($reparacion->equipo, $html);
            }

            if ($model == 'Reparacion') {
                $html = $this->replaceVariablesReparacion($reparacion, $html);
            }

            if ($model == 'Cliente') {
                $html = $this->replaceVariablesCliente($reparacion->cliente, $html);
            }

            return $html;
        }
    }

    public function enviarMailTicket($data)
    {

        $_ticket_id = $data['ticket_id'] ?? 2;
        $id_plantilla_email = $data['plantilla_mail_id'] ?? 1;
        $emailTo = isset($data['email_to']) ? $data['email_to'] : null;

        $plantillaMail = PlantillaMail::find($id_plantilla_email);
        if ($_ticket_id != 0) {
            $ticket = Ticket::findOrFail($_ticket_id);


            if ($plantillaMail && $id_plantilla_email > 1) {

                $asunto = $plantillaMail->subject;
                $cuerpo = $plantillaMail->body;
                $emailFrom = $plantillaMail->from;
                $nombreFrom = $plantillaMail->from_nombre;
                $emailBcc = $plantillaMail->cc;
                $nombreBcc = $plantillaMail->cc_nombre;


                $cuerpo = $plantillaMail->body_txt;

                //------ replace variables asunto -----------------------------------//

                $asunto = $this->replaceVariablesTicket($ticket, $asunto);

                //------ replace variables cuerpo -----------------------------------//

                $cuerpo = $this->replaceVariablesTicket($ticket, $cuerpo);


                //------ ------------------------ -----------------------------------//

                if (!$emailTo) {
                    $emailTo = $ticket->user_receiver->email;
                }

                $array_data = [
                    'cuerpo' => $cuerpo,
                    'asunto' => $asunto,
                    'email_to' => $emailTo,
                    'email_from' => $emailFrom,
                    'nombreFrom' => $nombreFrom,
                    'emailBcc' => $emailBcc,
                    'nombreBcc' => $nombreBcc,
                    'view' => 'emails.plantilla'
                ];

                if ($plantillaMail->plantilla_mail_header) {
                    $array_data['header'] = PlantillaMailHeader::find($plantillaMail->plantilla_mail_header)->header;
                }

                if ($plantillaMail->plantilla_mail_footer) {
                    $array_data['footer'] = PlantillaMailHeader::find($plantillaMail->plantilla_mail_footer)->footer;
                }

                return $array_data;
            }
        }
    }

    private function replaceVariablesUserReceiver($user, $texto_base)
    {

        if ($user) {
            $texto_base = str_replace("%USER_RECEIVER_NOMBRE%", $user->name ? $user->name : '', $texto_base);
            $texto_base = str_replace("%USER_RECEIVER_TELEFONO%", $user->telefono ? $user->telefono : '', $texto_base);
        }

        return $texto_base;
    }

    private function replaceVariablesUserOwner($user, $texto_base)
    {

        if ($user) {
            $texto_base = str_replace("%USER_RECEIVER_NOMBRE%", $user->name ? $user->name : '', $texto_base);
            $texto_base = str_replace("%USER_RECEIVER_TELEFONO%", $user->telefono ? $user->telefono : '', $texto_base);
        }

        return $texto_base;
    }

    private function replaceVariablesMotivoConsulta($motivo_consulta, $texto_base)
    {

        if ($motivo_consulta) {
            $texto_base = str_replace("%MOTIVO_CONSULTA_NOMBRE%", $motivo_consulta->name, $texto_base);
        }

        return $texto_base;
    }

    private function replaceVariablesCategoryTicket($category_ticket, $texto_base)
    {

        if ($category_ticket) {
            $texto_base = str_replace("%CATEGORIA_TICKET_NOMBRE%", $category_ticket->name, $texto_base);
        }

        return $texto_base;
    }

    public function replaceVariablesTicket($ticket, $texto_base)
    {

        if ($ticket) {
            $texto_base = $this->replaceVariablesReparacion($ticket->reparacion, $texto_base);
            $texto_base = $this->replaceVariablesDerivacionTable($ticket->derivacion, $texto_base);
            $texto_base = $this->replaceVariablesClienteDerivacion($ticket->cliente_derivacion, $texto_base);
            $texto_base = $this->replaceVariablesUserReceiver($ticket->user_receiver, $texto_base);
            $texto_base = $this->replaceVariablesUserOwner($ticket->user_owner, $texto_base);
            $texto_base = $this->replaceVariablesCliente($ticket->cliente, $texto_base);
            $texto_base = $this->replaceVariablesMotivoConsulta($ticket->motivo_consulta, $texto_base);
            $texto_base = $this->replaceVariablesCategoryTicket($ticket->category_ticket, $texto_base);
            $texto_base = $this->replaceVariablesFecha($texto_base);
        }

        return $texto_base;
    }

    private function replaceVariablesReparacionAssurant($reparacion_assurant, $texto_base)
    {
        if ($reparacion_assurant) {
            $texto_base = str_replace("%REPARACION_ASSURANT_MOTIVO_SINIESTRO_ID%", $reparacion_assurant->motivo_siniestro_id, $texto_base);
            $texto_base = str_replace("%REPARACION_ASSURANT_NUMERO_LINEA_ASEGURADA%", $reparacion_assurant->numero_linea_asegurada, $texto_base);
            $texto_base = str_replace("%REPARACION_ASSURANT_CLAIM_NUMBER%", $reparacion_assurant->claim_number, $texto_base);
            $texto_base = str_replace("%REPARACION_ASSURANT_CLAIM_STATUS%", $reparacion_assurant->Claim_Status, $texto_base);
            $texto_base = str_replace("%REPARACION_ASSURANT_FECHA_INCIDENTE%", $reparacion_assurant->fecha_incidente, $texto_base);
            $texto_base = str_replace("%REPARACION_ASSURANT_SUCURSAL_ORIGEN_ID%", $reparacion_assurant->sucursal_origen_id, $texto_base);
            $texto_base = str_replace("%REPARACION_ASSURANT_SUCURSAL_DESTINO_ID%", $reparacion_assurant->sucursal_destino_id, $texto_base);
            $texto_base = str_replace("%REPARACION_ASSURANT_DESCRIPCION_SINIESTRO%", $reparacion_assurant->descripcion_siniestro, $texto_base);
            $texto_base = str_replace("%REPARACION_ASSURANT_MONTO_DEDUCIBLE%", $reparacion_assurant->monto_deducible, $texto_base);
            $texto_base = str_replace("%REPARACION_ASSURANT_IMAGEN_DENUNCIA%", $reparacion_assurant->imagen_denuncia, $texto_base);
            $texto_base = str_replace("%REPARACION_ASSURANT_IMAGEN_ELITA_ID%", $reparacion_assurant->imagen_elita_id, $texto_base);
            $texto_base = str_replace("%REPARACION_ASSURANT_ASSURANT_CERTIFICATE_ID%", $reparacion_assurant->assurant_certificate_id, $texto_base);
            $texto_base = str_replace("%REPARACION_ASSURANT_CERTIFICATENUMBER%", $reparacion_assurant->CertificateNumber, $texto_base);
            $texto_base = str_replace("%REPARACION_ASSURANT_CLAIMTYPE%", $reparacion_assurant->ClaimType, $texto_base);
            $texto_base = str_replace("%REPARACION_ASSURANT_CONTACTNAME%", $reparacion_assurant->ContactName, $texto_base);
            $texto_base = str_replace("%REPARACION_ASSURANT_CONTACTNUMBER%", $reparacion_assurant->ContactNumber, $texto_base);
            $texto_base = str_replace("%REPARACION_ASSURANT_COVERAGETYPECODE%", $reparacion_assurant->CoverageTypeCode, $texto_base);
            $texto_base = str_replace("%REPARACION_ASSURANT_DATEOFLOSS%", $reparacion_assurant->DateOfLoss, $texto_base);
            $texto_base = str_replace("%REPARACION_ASSURANT_DEALERCODE%", $reparacion_assurant->DealerCode, $texto_base);
            $texto_base = str_replace("%REPARACION_ASSURANT_EMAILADDRESS%", $reparacion_assurant->EmailAddress, $texto_base);
            $texto_base = str_replace("%REPARACION_ASSURANT_MAKE%", $reparacion_assurant->Make, $texto_base);
            $texto_base = str_replace("%REPARACION_ASSURANT_MODEL%", $reparacion_assurant->Model, $texto_base);
            $texto_base = str_replace("%REPARACION_ASSURANT_PROBLEMDESCRIPTION%", $reparacion_assurant->ProblemDescription, $texto_base);
            $texto_base = str_replace("%REPARACION_ASSURANT_SERVICECENTERCODE%", $reparacion_assurant->ServiceCenterCode, $texto_base);
            $texto_base = str_replace("%REPARACION_ASSURANT_CODE%", $reparacion_assurant->Code, $texto_base);
            $texto_base = str_replace("%REPARACION_ASSURANT_STATUSDATE%", $reparacion_assurant->StatusDate, $texto_base);
            $texto_base = str_replace("%REPARACION_ASSURANT_OBSERVACIONES%", $reparacion_assurant->observaciones, $texto_base);
        }

        return $texto_base;
    }

    private function replaceVariablesOrdenAndreani($orden_andreani, $texto_base)
    {
        if ($orden_andreani) {
            $texto_base = str_replace("%ORDEN_ANDREANI_CONTRATO%", $orden_andreani->contrato, $texto_base);
            $texto_base = str_replace("%ORDEN_ANDREANI_TIPO_ENVIO%", $orden_andreani->tipo_envio, $texto_base);
            $texto_base = str_replace("%ORDEN_ANDREANI_ESTADO_ANDREANI_ID%", $orden_andreani->estado_andreani_id, $texto_base);
            $texto_base = str_replace("%ORDEN_ANDREANI_COMPLETE%", $orden_andreani->complete, $texto_base);
            $texto_base = str_replace("%ORDEN_ANDREANI_ORIGEN%", $orden_andreani->origen, $texto_base);
            $texto_base = str_replace("%ORDEN_ANDREANI_DESTINO%", $orden_andreani->destino, $texto_base);
            $texto_base = str_replace("%ORDEN_ANDREANI_REMITENTE%", $orden_andreani->remitente, $texto_base);
            $texto_base = str_replace("%ORDEN_ANDREANI_DESTINATARIO%", $orden_andreani->destinatario, $texto_base);
            $texto_base = str_replace("%ORDEN_ANDREANI_BULTOS%", $orden_andreani->bultos, $texto_base);
            $texto_base = str_replace("%ORDEN_ANDREANI_JSON_COMPLETOS%", $orden_andreani->json_completo, $texto_base);
        }

        return $texto_base;
    }
}
