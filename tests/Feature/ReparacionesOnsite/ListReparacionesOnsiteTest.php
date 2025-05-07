<?php

namespace Tests\Feature\ReparacionesOnsite;

use App\Models\Onsite\ReparacionOnsite;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ListReparacionesOnsiteTest extends TestCase
{
    /**
     * @test
     * @return void
     */
    public function can_fetch_sigle_reparacion_onsite()
    {
        
        $reparacion_onsite = ReparacionOnsite::factory()->create();
        
        $response = $this->getJson(route('api.v1.reparaciones_onsite.show', $reparacion_onsite));
        
        $response->assertExactJson([
          'data' => [
            'type' => 'reparaciones_onsite',
            'id' => (string) $reparacion_onsite->getRouteKey(),
            'attributes' => [
              'clave' => $reparacion_onsite->clave,
              'id_empresa_onsite' => $reparacion_onsite->id_empresa_onsite,
              'id_terminal' => $reparacion_onsite->id_terminal,
              'id_tipo_servicio' => $reparacion_onsite->id_tipo_servicio,
              'id_estado' => $reparacion_onsite->id_estado,
              'id_tecnico_asignado' => $reparacion_onsite->id_tecnico_asignado,
              'tarea' => $reparacion_onsite->tarea,
              'tarea_detalle' => $reparacion_onsite->tarea_detalle,
              'fecha_ingreso' => $reparacion_onsite->fecha_ingreso,
              'observacion_ubicacion' => $reparacion_onsite->observacion_ubicacion,
              'nro_caja' => $reparacion_onsite->nro_caja,
              'informe_tecnico' => $reparacion_onsite->informe_tecnico,
              'transacciones_pendientes' => $reparacion_onsite->transacciones_pendientes,
              'impresora_termica_scanner' => $reparacion_onsite->impresora_termica_scanner,
              'usuario_agentes' => $reparacion_onsite->usuario_agentes,
              'usuario_agentes_red_local' => $reparacion_onsite->usuario_agentes_red_local,
              'configuracion_impresora' => $reparacion_onsite->configuracion_impresora,
              'usuarios_sf2' => $reparacion_onsite->usuarios_sf2,
              'configuracion_pc_servidora' => $reparacion_onsite->configuracion_pc_servidora,
              'conectividad_sf2_wut_dns_vnc' => $reparacion_onsite->conectividad_sf2_wut_dns_vnc,
              'carpeta_sf2_permisos' => $reparacion_onsite->carpeta_sf2_permisos,
              'tension_electrica' => $reparacion_onsite->tension_electrica,
              'tipo_conexion_local' => $reparacion_onsite->tipo_conexion_local,
              'tipo_conexion_proveedor' => $reparacion_onsite->tipo_conexion_proveedor,
              'cableado' => $reparacion_onsite->cableado,
              'cableado_cantidad_metros' => $reparacion_onsite->cableado_cantidad_metros,
              'cableado_cantidad_fichas' => $reparacion_onsite->cableado_cantidad_fichas,
              'instalacion_cartel' => $reparacion_onsite->instalacion_cartel,
              'instalacion_cartel_luz' => $reparacion_onsite->instalacion_cartel_luz,
              'insumos_banner' => $reparacion_onsite->insumos_banner,
              'insumos_folleteria' => $reparacion_onsite->insumos_folleteria,
              'insumos_rojos_impresora' => $reparacion_onsite->insumos_rojos_impresora,
              'fotos_frente_local' => $reparacion_onsite->fotos_frente_local,
              'fotos_modem_enlace_switch' => $reparacion_onsite->fotos_modem_enlace_switch,
              'fotos_terminal_red' => $reparacion_onsite->fotos_terminal_red,
              'instalacion_buzon' => $reparacion_onsite->instalacion_buzon,
              'cantidad_horas_trabajo' => $reparacion_onsite->cantidad_horas_trabajo,
              'requiere_nueva_visita' => $reparacion_onsite->requiere_nueva_visita,
              'codigo_activo_nuevo1' => $reparacion_onsite->codigo_activo_nuevo1,
              'codigo_activo_retirado1' => $reparacion_onsite->codigo_activo_retirado1,
              'codigo_activo_descripcion1' => $reparacion_onsite->codigo_activo_descripcion1,
              'codigo_activo_nuevo2' => $reparacion_onsite->codigo_activo_nuevo2,
              'codigo_activo_retirado2' => $reparacion_onsite->codigo_activo_retirado2,
              'codigo_activo_descripcion2' => $reparacion_onsite->codigo_activo_descripcion2,
              'codigo_activo_nuevo3' => $reparacion_onsite->codigo_activo_nuevo3,
              'codigo_activo_retirado3' => $reparacion_onsite->codigo_activo_retirado3,
              'codigo_activo_descripcion3' => $reparacion_onsite->codigo_activo_descripcion3,
              'codigo_activo_nuevo4' => $reparacion_onsite->codigo_activo_nuevo4,
              'codigo_activo_retirado4' => $reparacion_onsite->codigo_activo_retirado4,
              'codigo_activo_descripcion4' => $reparacion_onsite->codigo_activo_descripcion4,
              'codigo_activo_nuevo5' => $reparacion_onsite->codigo_activo_nuevo5,
              'codigo_activo_retirado5' => $reparacion_onsite->codigo_activo_retirado5,
              'codigo_activo_descripcion5' => $reparacion_onsite->codigo_activo_descripcion5,
              'codigo_activo_nuevo6' => $reparacion_onsite->codigo_activo_nuevo6,
              'codigo_activo_retirado6' => $reparacion_onsite->codigo_activo_retirado6,
              'codigo_activo_descripcion6' => $reparacion_onsite->codigo_activo_descripcion6,
              'codigo_activo_nuevo7' => $reparacion_onsite->codigo_activo_nuevo7,
              'codigo_activo_retirado7' => $reparacion_onsite->codigo_activo_retirado7,
              'codigo_activo_descripcion7' => $reparacion_onsite->codigo_activo_descripcion7,
              'codigo_activo_nuevo8' => $reparacion_onsite->codigo_activo_nuevo8,
              'codigo_activo_retirado8' => $reparacion_onsite->codigo_activo_retirado8,
              'codigo_activo_descripcion8' => $reparacion_onsite->codigo_activo_descripcion8,
              'codigo_activo_nuevo9' => $reparacion_onsite->codigo_activo_nuevo9,
              'codigo_activo_retirado9' => $reparacion_onsite->codigo_activo_retirado9,
              'codigo_activo_descripcion9' => $reparacion_onsite->codigo_activo_descripcion9,
              'codigo_activo_nuevo10' => $reparacion_onsite->codigo_activo_nuevo10,
              'codigo_activo_retirado10' => $reparacion_onsite->codigo_activo_retirado10,
              'codigo_activo_descripcion10' => $reparacion_onsite->codigo_activo_descripcion10,
              'modem_3g_4g_sim_nuevo' => $reparacion_onsite->modem_3g_4g_sim_nuevo,
              'modem_3g_4g_sim_retirado' => $reparacion_onsite->modem_3g_4g_sim_retirado,
              'firma_cliente' => $reparacion_onsite->firma_cliente,
              'aclaracion_cliente' => $reparacion_onsite->aclaracion_cliente,
              'firma_tecnico' => $reparacion_onsite->firma_tecnico,
              'aclaracion_tecnico' => $reparacion_onsite->aclaracion_tecnico,
              'foto_frente_local_1' => $reparacion_onsite->foto_frente_local_1,
              'foto_frente_local_2' => $reparacion_onsite->foto_frente_local_2,
              'foto_equipo_1' => $reparacion_onsite->foto_equipo_1,
              'foto_equipo_2' => $reparacion_onsite->foto_equipo_2,
              'foto_terminal_red_1' => $reparacion_onsite->foto_terminal_red_1,
              'foto_terminal_red_2' => $reparacion_onsite->foto_terminal_red_2,
              'foto_comprobante' => $reparacion_onsite->foto_comprobante,
              'fecha_coordinada' => $reparacion_onsite->fecha_coordinada,
              'ventana_horaria_coordinada' => $reparacion_onsite->ventana_horaria_coordinada,
              'fecha_registracion_coordinacion' => $reparacion_onsite->fecha_registracion_coordinacion,
              'fecha_notificado' => $reparacion_onsite->fecha_notificado,
              'fecha_vencimiento' => $reparacion_onsite->fecha_vencimiento,
              'fecha_cerrado' => $reparacion_onsite->fecha_cerrado,
              'sla_status' => $reparacion_onsite->sla_status,
              'sla_justificado' => $reparacion_onsite->sla_justificado,
              'monto' => $reparacion_onsite->monto,
              'monto_extra' => $reparacion_onsite->monto_extra,
              'liquidado_proveedor' => $reparacion_onsite->liquidado_proveedor,
              'nro_factura_proveedor' => $reparacion_onsite->nro_factura_proveedor,
              'visible_cliente' => $reparacion_onsite->visible_cliente,
              'chequeado_cliente' => $reparacion_onsite->chequeado_cliente,
            ],
            'links' => [
              'self' => route('api.v1.reparaciones_onsite.show', $reparacion_onsite)
            ]
          ]
        ]);
    }

    /**
     * test
     * @return void
     */
    public function can_fetch_search_reparaciones_onsite_asc()
    {
       
        $reparaciones_onsite[] = ReparacionOnsite::factory()->create(['clave' => 'A001']);
        $reparaciones_onsite[] = ReparacionOnsite::factory()->create(['clave' => 'A002']);
        $reparaciones_onsite[] = ReparacionOnsite::factory()->create(['clave' => 'A003']);
    
        $response = $this->getJson(route('api.v1.reparaciones_onsite.index', ['sort' => 'clave']));

        $response->assertSeeInOrder([
          'A001', 'A002', 'A003'
        ]);

        // $response->assertExactJson([
        //   'data' => [
        //     [
        //       'type' => 'reparaciones_onsite',
        //       'id' => (string) $reparaciones_onsite[0]->getRouteKey(),
        //       'attributes' => [
        //         'clave' => $reparaciones_onsite[0]->clave
        //       ],
        //       'links' => [
        //         'self' => route('api.v1.reparaciones_onsite.show', $reparaciones_onsite[0])
        //       ]
        //     ],
        //     [
        //       'type' => 'reparaciones_onsite',
        //       'id' => (string) $reparaciones_onsite[1]->getRouteKey(),
        //       'attributes' => [
        //         'clave' => $reparaciones_onsite[1]->clave
        //       ],
        //       'links' => [
        //         'self' => route('api.v1.reparaciones_onsite.show', $reparaciones_onsite[1])
        //       ]
        //     ],
        //     [
        //       'type' => 'reparaciones_onsite',
        //       'id' => (string) $reparaciones_onsite[2]->getRouteKey(),
        //       'attributes' => [
        //         'clave' => $reparaciones_onsite[2]->clave
        //       ],
        //       'links' => [
        //         'self' => route('api.v1.reparaciones_onsite.show', $reparaciones_onsite[2])
        //       ]
        //     ],
        //   ],
        //   'links' => [
        //     'self' => route('api.v1.reparaciones_onsite.index')
        //   ]
        // ]);
    }
}
