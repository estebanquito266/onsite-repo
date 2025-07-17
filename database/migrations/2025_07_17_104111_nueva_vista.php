<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class NuevaVista extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement($this->createView());
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement($this->dropView());
    }

    private function createView(): string
    {
        return <<<SQL
                CREATE OR REPLACE
                ALGORITHM = UNDEFINED VIEW `reparaciones_onsite_export_witfiles` AS
                SELECT 
                    `r`.`id`, 
					(SELECT GROUP_CONCAT('https://onsite.speedup.com.ar/imagenes/reparaciones_onsite/',`imagenes_onsite`.`archivo`,' - ') FROM `imagenes_onsite` WHERE `imagenes_onsite`.`reparacion_onsite_id` = `r`.`id`) as `files`,
					MIN(`r`.`company_id`) AS `company_id`,
                    MIN(`r`.`clave`) as `clave`,            
					MIN(`r`.`id_empresa_onsite`) as `id_empresa_onsite`,
					MIN(`e`.`nombre`) as `empresa_nombre`,
					MIN(`r`.`sucursal_onsite_id`) as `sucursal_id`,
					MIN(`s`.`razon_social`) as `razon_social`,
					MIN(`s`.`direccion`) as `direccion`,
					MIN(`s`.`telefono_contacto`) as `tel_contacto`,
					MIN(`r`.`id_terminal`) as `id_terminal`,
					MIN(`t`.`marca`) as `marca`,
					MIN(`t`.`modelo`) as `modelo`,
					MIN(`t`.`serie`) as `serie`,
					MIN(`t`.`rotulo`) as `rotulo`,
					MIN(`s`.`localidad_onsite_id`) as `localidad_onsite_id`,
					MIN(`l`.`localidad`) as `localidad`,
					MIN(`l`.`id_provincia`) as `id_provincia`,
					MIN(`p`.`nombre` ) as `provincia_nombre`,
					MIN(`l`.`localidad_estandard`) as `localidad_estandard`,
					MIN(`l`.`codigo`) as `codigo`,
					MIN(`l`.`km`) as `km`,
					MIN(`l`.`id_nivel`) as `id_nivel`,
					MIN(`n`.`nombre` ) as 'nivel_nombre',
					MIN(`l`.`atiende_desde`) as `atiende_desde`,
					MIN(`l`.`id_usuario_tecnico`) as `id_usuario_tecnico`,
					MIN(`u`.`name` ) as 'usuario',
					MIN(`r`.`tarea`) as `tarea`,
					MIN(`r`.`tarea_detalle`) as `tarea_detalle`,
					MIN(`r`.`id_tipo_servicio`) as `id_tipo_serv`,
					MIN(`ts`.`nombre` ) as 'ts_nombre',
					MIN(`r`.`id_estado`) as `id_estado`,
					MIN(`es`.`nombre` ) as 'estado_nombre',
					MIN(`es`.`activo` ) as 'estado_activo',
					MIN(`r`.`fecha_ingreso`) as `fecha_ingreso`,
					MIN(`r`.`observacion_ubicacion`),
					MIN(`r`.`id_tecnico_asignado`) as `id_tecnico_asignado`,
					MIN(`tec`.`name` ) as 'tecnico',
					MIN(`r`.`informe_tecnico`),
					MIN(`r`.`fecha_coordinada`),
					MIN(`r`.`ventana_horaria_coordinada`),
					MIN(`r`.`fecha_registracion_coordinacion`),
					MIN(`r`.`fecha_notificado`),
					MIN(`rv`.`fecha`) AS `primer_visita`,
					
					MIN(`rv`.`fecha_vencimiento`) as 'fecha_vencimiento',

					MIN(`r`.`fecha_vencimiento`) as 'vencimiento',
					MIN(`r`.`fecha_cerrado`),
					MIN(`r`.`sla_status`),
					MIN(`r`.`sla_justificado`),
					MIN(`r`.`monto`),
					MIN(`r`.`monto_extra`),
					MIN(`r`.`liquidado_proveedor`),
					MIN(`r`.`nro_factura_proveedor`),

					MIN(`rd`.`tipo_conexion_local`),
					MIN(`rd`.`tipo_conexion_proveedor`),
					MIN(`rd`.`cableado`),
					MIN(`rd`.`cableado_cantidad_metros`),
					MIN(`rd`.`cableado_cantidad_fichas`),                    
					MIN(`rd`.`instalacion_cartel`),
					MIN(`rd`.`instalacion_cartel_luz`),

					MIN(`r`.`instalacion_buzon`),
					MIN(`r`.`cantidad_horas_trabajo`),
					MIN(`r`.`requiere_nueva_visita`),

					MIN(`rd`.`codigo_activo_nuevo1`),
					MIN(`rd`.`codigo_activo_retirado1`),
					MIN(`rd`.`codigo_activo_descripcion1`),
					MIN(`rd`.`codigo_activo_nuevo2`),
					MIN(`rd`.`codigo_activo_retirado2`),
					MIN(`rd`.`codigo_activo_descripcion2`),
					MIN(`rd`.`codigo_activo_nuevo3`),
					MIN(`rd`.`codigo_activo_retirado3`),
					MIN(`rd`.`codigo_activo_descripcion3`),
					MIN(`rd`.`codigo_activo_nuevo4`),
					MIN(`rd`.`codigo_activo_retirado4`),
					MIN(`rd`.`codigo_activo_descripcion4`),
					MIN(`rd`.`codigo_activo_nuevo5`),
					MIN(`rd`.`codigo_activo_retirado5`),
					MIN(`rd`.`codigo_activo_descripcion5`),
					MIN(`rd`.`codigo_activo_nuevo6`),
					MIN(`rd`.`codigo_activo_retirado6`),
					MIN(`rd`.`codigo_activo_descripcion6`),
					MIN(`rd`.`codigo_activo_nuevo7`),
					MIN(`rd`.`codigo_activo_retirado7`),
					MIN(`rd`.`codigo_activo_descripcion7`),
					MIN(`rd`.`codigo_activo_nuevo8`),
					MIN(`rd`.`codigo_activo_retirado8`),
					MIN(`rd`.`codigo_activo_descripcion8`),
					MIN(`rd`.`codigo_activo_nuevo9`),
					MIN(`rd`.`codigo_activo_retirado9`),
					MIN(`rd`.`codigo_activo_descripcion9`),
					MIN(`rd`.`codigo_activo_nuevo10`),
					MIN(`rd`.`codigo_activo_retirado10`),
					MIN(`rd`.`codigo_activo_descripcion10`),
					MIN(`rd`.`modem_3g_4g_sim_nuevo`),
					MIN(`rd`.`modem_3g_4g_sim_retirado`),

					MIN(`r`.`firma_cliente`),
					MIN(`r`.`aclaracion_cliente`),
					MIN(`r`.`firma_tecnico`),
					MIN(`r`.`aclaracion_tecnico`),
					MIN(`r`.`created_at`) as `created_at`
                
                FROM `reparaciones_onsite` as `r`

                LEFT JOIN `sucursales_onsite` as `s`
                ON `r`.`sucursal_onsite_id` = `s`.`id`
                LEFT JOIN `empresas_onsite` as `e`
                ON `r`.`id_empresa_onsite` = `e`.`id`
                LEFT JOIN `terminales_onsite` as `t`
                ON `r`.`id_terminal` = `t`.`nro`
                LEFT JOIN `localidades_onsite` as `l`
                ON `s`.`localidad_onsite_id` = `l`.`id`
                LEFT JOIN `provincias` as `p`
                ON `l`.`id_provincia` = `p`.`id`
                LEFT JOIN `niveles_onsite` as `n`
                ON `l`.`id_nivel` = `n`.`id`
                LEFT JOIN `users` as `u`
                ON `l`.`id_usuario_tecnico` = `u`.`id`
                LEFT JOIN `tipos_servicios_onsite` as `ts`
                ON `r`.`id_tipo_servicio` = `ts`.`id`
                LEFT JOIN `estados_onsite` as `es`
                ON `r`.`id_estado` = `es`.`id`
                LEFT join `users` as `tec`
                on `r`.`id_tecnico_asignado` = `tec`.`id`
                LEFT join `reparaciones_visitas` as `rv`
                ON `rv`.`reparacion_id` = `r`.`id`
                LEFT JOIN `reparaciones_detalle` as `rd`
                ON `rd`.`reparacion_id` = `r`.`id`
				GROUP BY `r`.`id`

SQL;
    }

    private function dropView(): string
    {
        return <<<SQL
        DROP VIEW IF EXISTS `reparaciones_onsite_export`
SQL;
        /* 



















*/
    }
}
