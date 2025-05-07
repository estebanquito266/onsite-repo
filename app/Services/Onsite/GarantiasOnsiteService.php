<?php

namespace App\Services\Onsite;

use App\Models\Onsite\GarantiaOnsite;
use DateTime;
use Log;
use Session;

class GarantiasOnsiteService
{
    protected $userCompanyId;
    protected $templateComprobanteService;
    protected $userService;

    public function __construct(
        TemplatesService $templateComprobanteService,
        UserService $userService
    ) {
        $this->userCompanyId =  session()->get('userCompanyIdDefault');
        $this->templateComprobanteService = $templateComprobanteService;
        $this->userService = $userService;
    }
    public function getDataList()
    {
        if (Session()->get('perfilAdmin') || Session()->get('perfilAdminOnsite')) {
            $data = GarantiaOnsite::where('company_id', $this->userCompanyId)
                ->get();
        } else {
            $idUser = session()->get('idUser');
            $user = $this->userService->findUser($idUser);

            if (count($user->empresa_instaladora) > 0) {
                $idEmpresaInstaladora = $user->empresa_instaladora[0]->id;
                $data = GarantiaOnsite::where('company_id', $this->userCompanyId)
                    ->where('empresa_instaladora_id', $idEmpresaInstaladora)
                    ->get();
            } else
                return redirect('/')->with('message-error', 'Usuario no tiene empresa instaladora asociada');
        }


        return $data;
    }

    public function getData($id)
    {
        $garantiaOnsite = GarantiaOnsite::where('company_id', $this->userCompanyId)
            ->find($id);

        return $garantiaOnsite;
    }

    public function create()
    {
    }

    public function show()
    {
    }

    public function store($request)
    {
        $garantiaOnsite = GarantiaOnsite::create($request->all());
        return $garantiaOnsite;
    }

    public function generaComprobanteGarantia($garantiaOnsite)
    {
        $sistema = $garantiaOnsite->sistema_onsite->nombre;


        $unidades_exteriores = $garantiaOnsite->sistema_onsite->unidades_exteriores;
        $cuadroUnidadesExteriores = $this->makeCuadroUnidades($unidades_exteriores);


        $unidades_interiores = $garantiaOnsite->sistema_onsite->unidades_interiores;
        $cuadroUnidadesInteriores = $this->makeCuadroUnidades($unidades_interiores);

        $comprador = $garantiaOnsite->sistema_onsite->comprador_onsite;

        $idtemplateComprobante = $garantiaOnsite->tipo->template_comprobante_id;

        $templateComprobante = $this->templateComprobanteService->getTemplate($idtemplateComprobante);



        $comprobante = $templateComprobante->cuerpo;

        $domicilio_empresa = $garantiaOnsite->obra_onsite->empresa_instaladora->domicilio . ', '
            . $garantiaOnsite->obra_onsite->empresa_instaladora->provincia->nombre . ', '
            . $garantiaOnsite->obra_onsite->empresa_instaladora->LocalidadOnsite->localidad . ', '
            . $garantiaOnsite->obra_onsite->empresa_instaladora->pais;

        $domcilio_obra = explode("-", $garantiaOnsite->obra_onsite->domicilio);
        $provincia = isset($domcilio_obra[3]) ? $domcilio_obra[3] : null;


        $comprobanteGarantia = str_replace('%SISTEMA_ONSITE%', $sistema, $comprobante);
        $comprobanteGarantia = str_replace('%OBRA_ONSITE%', $garantiaOnsite->obra_onsite->nombre, $comprobanteGarantia);
        $comprobanteGarantia = str_replace('%DIRECCION_OBRA%', $domcilio_obra[0] ? $domcilio_obra[0] : null, $comprobanteGarantia);
        $comprobanteGarantia = str_replace('%PAIS_OBRA%', isset($domcilio_obra[1]) ? $domcilio_obra[1] : null, $comprobanteGarantia);
        $comprobanteGarantia = str_replace('%LOCALIDAD_OBRA%', isset($domcilio_obra[2]) ? $domcilio_obra[2] . ', ' . $provincia : null, $comprobanteGarantia);


        $comprobanteGarantia = str_replace('%UNIDADES_EXTERIORES%', $cuadroUnidadesExteriores, $comprobanteGarantia);
        $comprobanteGarantia = str_replace('%UNIDADES_INTERIORES%', $cuadroUnidadesInteriores, $comprobanteGarantia);
        $comprobanteGarantia = str_replace('%NOMBRE_COMPRADOR%', $comprador->primer_nombre . ', ' . $comprador->apellido, $comprobanteGarantia);
        $comprobanteGarantia = str_replace('%CUIT_COMPRADOR%', $comprador->dni, $comprobanteGarantia);
        $comprobanteGarantia = str_replace('%DOMICILIO_COMPRADOR%', $comprador->domicilio, $comprobanteGarantia);
        $comprobanteGarantia = str_replace('%TELEFONO_COMPRADOR%', $comprador->celular, $comprobanteGarantia);
        $comprobanteGarantia = str_replace('%FECHA_COMPRA%', $garantiaOnsite->fecha_compra, $comprobanteGarantia);
        $comprobanteGarantia = str_replace('%NUMERO_FACTURA%', $garantiaOnsite->numero_factura, $comprobanteGarantia);
        $comprobanteGarantia = str_replace('%FECHA_GARANTIA%', date( 'd-m-Y', strtotime($garantiaOnsite->fecha)), $comprobanteGarantia);

        $fecha_str = date('d/m/Y', strtotime($garantiaOnsite->fecha));
        $date = DateTime::createFromFormat("d/m/Y", $fecha_str);
        $comprobanteGarantia = str_replace("%FECHA_GARANTIA_LETRA%", strftime("%A, %d de %B de %Y", $date->getTimestamp()), $comprobanteGarantia);

        $comprobanteGarantia = str_replace('%NOMBRE_INSTALADOR%', $garantiaOnsite->obra_onsite->empresa_instaladora->nombre, $comprobanteGarantia);
        $comprobanteGarantia = str_replace('%RESPONSABLE_INSTALADOR%', $garantiaOnsite->obra_onsite->empresa_instaladora_responsable, $comprobanteGarantia);
        $comprobanteGarantia = str_replace('%DOMICILIO_INSTALADOR%', $domicilio_empresa, $comprobanteGarantia);
        $comprobanteGarantia = str_replace('%TELEFONO_INSTALADOR%', $garantiaOnsite->empresa_instaladora->telefono, $comprobanteGarantia);
        $comprobanteGarantia = str_replace('%EMAIL_INSTALADOR%', $garantiaOnsite->empresa_instaladora->email, $comprobanteGarantia);
        $comprobanteGarantia = str_replace('%OBSERVACIONES_GARANTIA%', $garantiaOnsite->observaciones, $comprobanteGarantia);
        $comprobanteGarantia = str_replace('%INFORME_OBSERVACIONES_GARANTIA%', $garantiaOnsite->informe_observaciones, $comprobanteGarantia);
        $comprobanteGarantia = str_replace('%INFORMADO_A_OBSERVACIONES_GARANTIA%', $garantiaOnsite->destinatario_informe, $comprobanteGarantia);


        return $comprobanteGarantia;
    }

    public function makeCuadroUnidades($unidades)
    {
        $cuadroUnidades = '<table class="customers">
                            <tr>
                                <td>id</td>
                                <td>Modelo</td>
                                <td>Serie</td>
                                <td>Direccionamiento</td>
                                <td>VOID</td>
                            </tr>';

        foreach ($unidades as $unidad) {
                $etiqueta_void = '';
            foreach ($unidad->etiqueta as $etiqueta) {
                $etiqueta_void .= $etiqueta->nombre . '<br>';
            }

            $cuadroUnidades .= '<tr>';
            $cuadroUnidades .=  '<td>' . $unidad->id . '</td>';
            $cuadroUnidades .=  '<td>' . $unidad->modelo . '</td>';
            $cuadroUnidades .=  '<td>' . $unidad->serie . '</td>';
            $cuadroUnidades .=  '<td>' . $unidad->direccion . '</td>';
            $cuadroUnidades .=  '<td>' . $etiqueta_void. '</td>';
            $cuadroUnidades .= '</tr>';
        }
        $cuadroUnidades .= '</table>';

        return $cuadroUnidades;
    }

    public function update($request, $id)
    {
        $garantiaOnsite = GarantiaOnsite::where('company_id', $this->userCompanyId)
            ->find($id);

        $request['nombre'] = $garantiaOnsite->nombre;

        $garantiaOnsite->update($request->all());

        return $garantiaOnsite;
    }

    public function destroy($id)
    {
		$garantiaOnsite = GarantiaOnsite::find($id);

		if (!in_array($garantiaOnsite->company_id, Session::get('userCompaniesId'))) {
			Session::flash('message-error', 'Sin Privilegios');
			return redirect('/garantiaonsite');
		}
        
		$garantiaOnsite->delete();
        
		return $garantiaOnsite;        
    }

    public function garantiaOnsiteEmitir($idGarantia)
    {
        $garantiaOnsite = GarantiaOnsite::with('tipo')
            ->where('company_id', $this->userCompanyId)
            ->find($idGarantia);


        return $garantiaOnsite;
    }
}
