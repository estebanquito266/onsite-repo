<?php

namespace App\Http\Requests\Onsite;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReparacionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {





        return [

            'id_estado' => 'required',
            'observaciones' => '',

            'clave' => '',
            'id_empresa_onsite' => '',
            'sucursal_onsite_id' => '',
            'tarea' => '',
            'tarea_detalle' => '',
            'fecha_ingreso' => '',
            'observacion_ubicacion' => '',
            'nro_caja' => '',
            'informe_tecnico' => '',
            'prioridad' => '',
            'cantidad_horas_trabajo' => '',
            'requiere_nueva_visita' => '',
            'firma_cliente' => '',
            'aclaracion_cliente' => '',
            'firma_tecnico' => '',
            'aclaracion_tecnico' => '',
            'ventana_horaria_coordinada' => '',
            'monto' => '',
            'monto_extra' => '',
            'nro_factura_proveedor' => '',
            'doc_link1' => '',
            'doc_link2' => '',
            'doc_link3' => '',
            'usuario_id' => '',
            'nota_cliente' => '',
            'observaciones_internas' => '',
            'sistema_onsite_id' => '',
            'reparacion_onsite_puesta_marcha_id' => '',
            'solicitud_tipo_id' => '',

            'ruta'  => '',
            'id_tecnico_asignado'  => '',
            'sla_justificado'  => '',
            'liquidado_proveedor'  => '',
            'visible_cliente'  => '',
            'chequeado_cliente'  => '',
            'problema_resuelto'  => '',
            'instalacion_buzon'  => '',
            'requiere_nueva_visita'  => '',
            'alimentacion_definitiva'  => '',
            'unidades_tension_definitiva'  => '',
            'cable_alimentacion_comunicacion_seccion_ok'  => '',
            'minimo_conexiones_frigorificas_exteriores'  => '',
            'sistema_presurizado_41_5_kg'  => '',
            'operacion_vacio'  => '',
            'llave_servicio_odu_abiertos'  => '',
            'carga_adicional_introducida'  => '',
            'sistema_funcionando_15_min_carga_adicional'  => '',
            'puesta_marcha_satisfactoria'  => '',
            'sistema_presurizado_41_5_kg_tiempo_horas'  => '',
            'unidad_exterior_tension_12_horas'  => '',
            'carga_adicional_introducida_kg_final'  => '',
            'carga_adicional_introducida_kg_adicional'  => '',
            'transacciones_pendientes'  => '',
            'impresora_termica_scanner'  => '',
            'usuario_agentes'  => '',
            'usuario_agentes_red_local'  => '',
            'configuracion_impresora'  => '',
            'usuarios_sf2'  => '',
            'configuracion_pc_servidora'  => '',
            'conectividad_sf2_wut_dns_vnc'  => '',
            'carpeta_sf2_permisos'  => '',
            'tension_electrica'  => '',
            'tipo_conexion_local'  => '',
            'tipo_conexion_proveedor'  => '',
            'cableado'  => '',
            'cableado_cantidad_metros'  => '',
            'cableado_cantidad_fichas'  => '',
            'instalacion_cartel'  => '',
            'instalacion_cartel_luz'  => '',
            'insumos_banner'  => '',
            'insumos_folleteria'  => '',
            'insumos_rojos_impresora'  => '',
            'fotos_frente_local'  => '',
            'fotos_modem_enlace_switch'  => '',
            'fotos_terminal_red'  => '',
            'codigo_activo_nuevo1'  => '',
            'codigo_activo_retirado1'  => '',
            'codigo_activo_descripcion1'  => '',
            'codigo_activo_nuevo2'  => '',
            'codigo_activo_retirado2'  => '',
            'codigo_activo_descripcion2'  => '',
            'codigo_activo_nuevo3'  => '',
            'codigo_activo_retirado3'  => '',
            'codigo_activo_descripcion3'  => '',
            'codigo_activo_nuevo4'  => '',
            'codigo_activo_retirado4'  => '',
            'codigo_activo_descripcion4'  => '',
            'codigo_activo_nuevo5'  => '',
            'codigo_activo_retirado5'  => '',
            'codigo_activo_descripcion5'  => '',
            'codigo_activo_nuevo6'  => '',
            'codigo_activo_nuevo7'  => '',
            'codigo_activo_retirado6'  => '',
            'codigo_activo_retirado7'  => '',
            'codigo_activo_descripcion6'  => '',
            'codigo_activo_descripcion7'  => '',
            'codigo_activo_nuevo8'  => '',
            'codigo_activo_retirado8'  => '',
            'codigo_activo_descripcion8'  => '',
            'codigo_activo_nuevo9'  => '',
            'codigo_activo_retirado9'  => '',
            'codigo_activo_descripcion9'  => '',
            'codigo_activo_nuevo10'  => '',
            'codigo_activo_retirado10'  => '',
            'codigo_activo_descripcion10'  => '',
            'modem_3g_4g_sim_nuevo'  => '',
            'modem_3g_4g_sim_retirado'  => '',
            'fecha_cerrado'  => '',
            'fecha_vencimiento'  => '',
            'sla_status'  => '',
            'fecha_coordinada'  => '',
            'fecha_notificado'  => '',
            'id_terminal'  => '',
            'fecha_registracion_coordinacion'  => '',
            'id_tipo_servicio'  => '',
            'IMAGEN_ONSITE_1' => '',
            'IMAGEN_ONSITE_2' => '',
            'IMAGEN_ONSITE_3' => '',
            'IMAGEN_ONSITE_4' => '',
            'IMAGEN_ONSITE_5' => '',
            'IMAGEN_ONSITE_6' => '',
            'IMAGEN_ONSITE_7' => '',
            'IMAGEN_ONSITE_8' => '',
            'IMAGEN_ONSITE_9' => '',
            'IMAGEN_ONSITE_10' => '',
            'TIPO_IMAGEN_ONSITE_1' => '',
            'TIPO_IMAGEN_ONSITE_2' => '',
            'TIPO_IMAGEN_ONSITE_3' => '',
            'TIPO_IMAGEN_ONSITE_4' => '',
            'TIPO_IMAGEN_ONSITE_5' => '',
            'TIPO_IMAGEN_ONSITE_6' => '',
            'TIPO_IMAGEN_ONSITE_7' => '',
            'TIPO_IMAGEN_ONSITE_8' => '',
            'TIPO_IMAGEN_ONSITE_9' => '',
            'TIPO_IMAGEN_ONSITE_10' => ''

        ];
    }
}
