<?php

namespace App\Http\Resources\Onsite;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Onsite\ReparacionOnsite;
use Log;

class ReparacionOnsiteResource extends JsonResource
{
  /**
   * 

   * Transform the resource into an array.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array
   */
  public function toArray($request)
  {
    
    /* if ($this->empresa_onsite->obra_onsite && $this->empresa_onsite->obra_onsite->obraChecklistOnsite) {
      $obraOnsite = [$this->empresa_onsite->obra_onsite->toArray()];
    } else
      $obraOnsite = null; */

      if ($this->sistema_onsite && $this->sistema_onsite->obra_onsite && $this->sistema_onsite->obra_onsite->obraChecklistOnsite) {
        $obraOnsite = [$this->sistema_onsite->obra_onsite->toArray()];
      } else
        $obraOnsite = null;

  
    return [
      'type' => 'reparaciones_onsite',
      'id' => (string) $this->getRouteKey(),
      'attributes' => [
        'clave' => $this->clave,
        'company_id' => $this->company_id,
        'id_empresa_onsite' => $this->id_empresa_onsite,
        'id_terminal' => $this->id_terminal,
        'id_tipo_servicio' => $this->id_tipo_servicio,
        'id_estado' => $this->id_estado,
        'id_tecnico_asignado' => $this->id_tecnico_asignado,
        'sucursal_onsite_id' => $this->sucursal_onsite_id,
        'tarea' => $this->tarea,
        'tarea_detalle' => $this->tarea_detalle,
        'fecha_ingreso' => $this->fecha_ingreso,
        'observacion_ubicacion' => $this->observacion_ubicacion,
        'observaciones_internas' => $this->observaciones_internas,
        'nro_caja' => $this->nro_caja,
        'informe_tecnico' => $this->informe_tecnico,
        'transacciones_pendientes' => $this->transacciones_pendientes,
        'impresora_termica_scanner' => $this->impresora_termica_scanner,
        'usuario_agentes' => $this->usuario_agentes,
        'usuario_agentes_red_local' => $this->usuario_agentes_red_local,
        'configuracion_impresora' => $this->configuracion_impresora,
        'usuarios_sf2' => $this->usuarios_sf2,
        'configuracion_pc_servidora' => $this->configuracion_pc_servidora,
        'conectividad_sf2_wut_dns_vnc' => $this->conectividad_sf2_wut_dns_vnc,
        'carpeta_sf2_permisos' => $this->carpeta_sf2_permisos,
        'tension_electrica' => $this->tension_electrica,
        'tipo_conexion_local' => $this->tipo_conexion_local,
        'tipo_conexion_proveedor' => $this->tipo_conexion_proveedor,
        'cableado' => $this->cableado,
        'cableado_cantidad_metros' => $this->cableado_cantidad_metros,
        'cableado_cantidad_fichas' => $this->cableado_cantidad_fichas,
        'instalacion_cartel' => $this->instalacion_cartel,
        'instalacion_cartel_luz' => $this->instalacion_cartel_luz,
        'insumos_banner' => $this->insumos_banner,
        'insumos_folleteria' => $this->insumos_folleteria,
        'insumos_rojos_impresora' => $this->insumos_rojos_impresora,
        'fotos_frente_local' => $this->fotos_frente_local,
        'fotos_modem_enlace_switch' => $this->fotos_modem_enlace_switch,
        'fotos_terminal_red' => $this->fotos_terminal_red,
        'instalacion_buzon' => $this->instalacion_buzon,
        'cantidad_horas_trabajo' => $this->cantidad_horas_trabajo,
        'requiere_nueva_visita' => $this->requiere_nueva_visita,
        'codigo_activo_nuevo1' => $this->codigo_activo_nuevo1,
        'codigo_activo_retirado1' => $this->codigo_activo_retirado1,
        'codigo_activo_descripcion1' => $this->codigo_activo_descripcion1,
        'codigo_activo_nuevo2' => $this->codigo_activo_nuevo2,
        'codigo_activo_retirado2' => $this->codigo_activo_retirado2,
        'codigo_activo_descripcion2' => $this->codigo_activo_descripcion2,
        'codigo_activo_nuevo3' => $this->codigo_activo_nuevo3,
        'codigo_activo_retirado3' => $this->codigo_activo_retirado3,
        'codigo_activo_descripcion3' => $this->codigo_activo_descripcion3,
        'codigo_activo_nuevo4' => $this->codigo_activo_nuevo4,
        'codigo_activo_retirado4' => $this->codigo_activo_retirado4,
        'codigo_activo_descripcion4' => $this->codigo_activo_descripcion4,
        'codigo_activo_nuevo5' => $this->codigo_activo_nuevo5,
        'codigo_activo_retirado5' => $this->codigo_activo_retirado5,
        'codigo_activo_descripcion5' => $this->codigo_activo_descripcion5,
        'codigo_activo_nuevo6' => $this->codigo_activo_nuevo6,
        'codigo_activo_retirado6' => $this->codigo_activo_retirado6,
        'codigo_activo_descripcion6' => $this->codigo_activo_descripcion6,
        'codigo_activo_nuevo7' => $this->codigo_activo_nuevo7,
        'codigo_activo_retirado7' => $this->codigo_activo_retirado7,
        'codigo_activo_descripcion7' => $this->codigo_activo_descripcion7,
        'codigo_activo_nuevo8' => $this->codigo_activo_nuevo8,
        'codigo_activo_retirado8' => $this->codigo_activo_retirado8,
        'codigo_activo_descripcion8' => $this->codigo_activo_descripcion8,
        'codigo_activo_nuevo9' => $this->codigo_activo_nuevo9,
        'codigo_activo_retirado9' => $this->codigo_activo_retirado9,
        'codigo_activo_descripcion9' => $this->codigo_activo_descripcion9,
        'codigo_activo_nuevo10' => $this->codigo_activo_nuevo10,
        'codigo_activo_retirado10' => $this->codigo_activo_retirado10,
        'codigo_activo_descripcion10' => $this->codigo_activo_descripcion10,
        'modem_3g_4g_sim_nuevo' => $this->modem_3g_4g_sim_nuevo,
        'modem_3g_4g_sim_retirado' => $this->modem_3g_4g_sim_retirado,
        'firma_cliente' => $this->firma_cliente,
        'aclaracion_cliente' => $this->aclaracion_cliente,
        'firma_tecnico' => $this->firma_tecnico,
        'aclaracion_tecnico' => $this->aclaracion_tecnico,
        'foto_frente_local_1' => $this->foto_frente_local_1,
        'foto_frente_local_2' => $this->foto_frente_local_2,
        'foto_equipo_1' => $this->foto_equipo_1,
        'foto_equipo_2' => $this->foto_equipo_2,
        'foto_terminal_red_1' => $this->foto_terminal_red_1,
        'foto_terminal_red_2' => $this->foto_terminal_red_2,
        'foto_comprobante' => $this->foto_comprobante,
        'fecha_coordinada' => $this->fecha_coordinada,
        'ventana_horaria_coordinada' => $this->ventana_horaria_coordinada,
        'fecha_registracion_coordinacion' => $this->fecha_registracion_coordinacion,
        'fecha_notificado' => $this->fecha_notificado,
        'fecha_vencimiento' => $this->fecha_vencimiento,
        'fecha_cerrado' => $this->fecha_cerrado,
        'sla_status' => $this->sla_status,
        'sla_justificado' => $this->sla_justificado,
        'monto' => $this->monto,
        'monto_extra' => $this->monto_extra,
        'liquidado_proveedor' => $this->liquidado_proveedor,
        'nro_factura_proveedor' => $this->nro_factura_proveedor,
        'visible_cliente' => $this->visible_cliente,
        'activa' => $this->activa,
        'vence_hoy' => $this->vence_hoy,
        'a_tiempo' => $this->a_tiempo,
        'vencida' => $this->vencida,
        'vencimiento_color' => $this->vencimiento_color,
        'vencimiento_texto' => $this->vencimiento_texto,
        'empresa' => $this->empresa_onsite->toArray(),
        'tipo_de_servicio' => $this->tipo_servicio_onsite->toArray(),
        'terminal' => $this->terminal_onsite->toArray(),
        'estado' => $this->estado_onsite->nombre,
        'sucursal' => $this->sucursal_onsite->toArray(),
        'historial' => $this->historial_estados_onsite->toArray(),
        'reparacion_checklist_onsite' => ($this->reparacion_checklist_onsite ? $this->reparacion_checklist_onsite->toArray() : []),
        'reparacion_detalle' => ($this->reparacion_detalle ? $this->reparacion_detalle->toArray() : []),
        'doc_link1' => $this->doc_link1,
        'obraOnsite' => $obraOnsite,
        'imagenesOnsite' => $this->imagenesOnsite->toArray(),
        'imagenesOnsiteCanierias' => ($this->imagenesOnsiteCanierias ? $this->imagenesOnsiteCanierias->toArray() : null),
        'imagenesOnsiteAnomalias' =>($this->imagenesOnsiteAnomalias ? $this->imagenesOnsiteAnomalias->toArray() : null),
        'imagenesOnsitePresurizacion' =>($this->imagenesOnsitePresurizacion ? $this->imagenesOnsitePresurizacion->toArray() : null),
        'imagenesOnsiteServicioFirmado' =>($this->imagenesOnsiteServicioFirmado ? $this->imagenesOnsiteServicioFirmado->toArray() : null),
        'imagenesOnsiteTrabajoRealizado' =>($this->imagenesOnsiteTrabajoRealizado ? $this->imagenesOnsiteTrabajoRealizado->toArray() : null),
        'sistema_onsite' =>$this->sistema_onsite? $this->sistema_onsite->toArray(): [],
        'solicitud_tipo' =>$this->solicitud_tipo->nombre,
        'imagenes_obra' =>($this->sistema_onsite && $this->sistema_onsite->obra_onsite && $this->sistema_onsite->obra_onsite->imagenes_obras? $this->sistema_onsite->obra_onsite->imagenes_obras->toArray(): []),


      ],

      'links' => [
        'self' => route('api.v1.reparaciones_onsite.show', $this),
      ]
    ];
  }
}
