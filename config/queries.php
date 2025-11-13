<?php

return [
    'view_reparaciones_onsite' => "SELECT
    `r`.`id` AS `id`,
    `r`.`company_id` AS `company_id`,
    `r`.`clave` AS `clave`,
    `r`.`id_empresa_onsite` AS `id_empresa_onsite`,
    `e`.`nombre` AS `empresa_nombre`,
    `r`.`sucursal_onsite_id` AS `sucursal_id`,
    `s`.`razon_social` AS `razon_social`,
    `s`.`direccion` AS `direccion`,
    `s`.`telefono_contacto` AS `tel_contacto`,
    `r`.`id_terminal` AS `id_terminal`,
    `t`.`marca` AS `marca`,
    `t`.`modelo` AS `modelo`,
    `t`.`serie` AS `serie`,
    `t`.`rotulo` AS `rotulo`,
    `s`.`localidad_onsite_id` AS `localidad_onsite_id`,
    `l`.`localidad` AS `localidad`,
    `l`.`id_provincia` AS `id_provincia`,
    `p`.`nombre` AS `provincia_nombre`,
    `l`.`localidad_estandard` AS `localidad_estandard`,
    `l`.`codigo` AS `codigo`,
    `l`.`km` AS `km`,
    `l`.`id_nivel` AS `id_nivel`,
    `n`.`nombre` AS `nivel_nombre`,
    `l`.`atiende_desde` AS `atiende_desde`,
    `l`.`id_usuario_tecnico` AS `id_usuario_tecnico`,
    `u`.`name` AS `usuario`,
    `r`.`tarea` AS `tarea`,
    `r`.`tarea_detalle` AS `tarea_detalle`,
    `r`.`id_tipo_servicio` AS `id_tipo_serv`,
    `ts`.`nombre` AS `ts_nombre`,
    `r`.`id_estado` AS `id_estado`,
    `es`.`nombre` AS `estado_nombre`,
    `es`.`activo` AS `estado_activo`,
    `r`.`fecha_ingreso` AS `fecha_ingreso`,
    `r`.`observacion_ubicacion` AS `r_observacion_ubicacion`,
    `r`.`id_tecnico_asignado` AS `id_tecnico_asignado`,
    `tec`.`name` AS `tecnico`,
    `r`.`informe_tecnico` AS `r_informe_tecnico`,
    `r`.`fecha_coordinada` AS `r_fecha_coordinada`,
    `r`.`ventana_horaria_coordinada` AS `r_ventana_horaria_coordinada`,
    `r`.`fecha_registracion_coordinacion` AS `r_fecha_registracion_coordinacion`,
    `r`.`fecha_notificado` AS `r_fecha_notificado`,
    `rv`.`primer_visita` AS `primer_visita`,
    `rv`.`fecha_vencimiento` AS `fecha_vencimiento`,
    `r`.`fecha_vencimiento` AS `vencimiento`,
    `r`.`fecha_cerrado` AS `r_fecha_cerrado`,
    `r`.`sla_status` AS `r_sla_status`,
    `r`.`sla_justificado` AS `r_sla_justificado`,
    `r`.`monto` AS `r_monto`,
    `r`.`monto_extra` AS `r_monto_extra`,
    `r`.`liquidado_proveedor` AS `r_liquidado_proveedor`,
    `r`.`nro_factura_proveedor` AS `r_nro_factura_proveedor`,
    
    `rd`.`tipo_conexion_local` AS `rd_tipo_conexion_local`,
    `rd`.`tipo_conexion_proveedor` AS `rd_tipo_conexion_proveedor`,
    `rd`.`cableado` AS `rd_cableado`,
    `rd`.`cableado_cantidad_metros` AS `rd_cableado_cantidad_metros`,
    `rd`.`cableado_cantidad_fichas` AS `rd_cableado_cantidad_fichas`,
    `rd`.`instalacion_cartel` AS `rd_instalacion_cartel`,
    `rd`.`instalacion_cartel_luz` AS `rd_instalacion_cartel_luz`,
    `r`.`instalacion_buzon` AS `r_instalacion_buzon`,
    `r`.`cantidad_horas_trabajo` AS `r_cantidad_horas_trabajo`,
    `r`.`requiere_nueva_visita` AS `r_requiere_nueva_visita`,
    
    `rd`.`codigo_activo_nuevo1` AS `rd_codigo_activo_nuevo1`,
    `rd`.`codigo_activo_retirado1` AS `rd_codigo_activo_retirado1`,
    `rd`.`codigo_activo_descripcion1` AS `rd_codigo_activo_descripcion1`,
    `rd`.`codigo_activo_nuevo2` AS `rd_codigo_activo_nuevo2`,
    `rd`.`codigo_activo_retirado2` AS `rd_codigo_activo_retirado2`,
    `rd`.`codigo_activo_descripcion2` AS `rd_codigo_activo_descripcion2`,
    `rd`.`codigo_activo_nuevo3` AS `rd_codigo_activo_nuevo3`,
    `rd`.`codigo_activo_retirado3` AS `rd_codigo_activo_retirado3`,
    `rd`.`codigo_activo_descripcion3` AS `rd_codigo_activo_descripcion3`,
    `rd`.`codigo_activo_nuevo4` AS `rd_codigo_activo_nuevo4`,
    `rd`.`codigo_activo_retirado4` AS `rd_codigo_activo_retirado4`,
    `rd`.`codigo_activo_descripcion4` AS `rd_codigo_activo_descripcion4`,
    `rd`.`codigo_activo_nuevo5` AS `rd_codigo_activo_nuevo5`,
    `rd`.`codigo_activo_retirado5` AS `rd_codigo_activo_retirado5`,
    `rd`.`codigo_activo_descripcion5` AS `rd_codigo_activo_descripcion5`,
    `rd`.`codigo_activo_nuevo6` AS `rd_codigo_activo_nuevo6`,
    `rd`.`codigo_activo_retirado6` AS `rd_codigo_activo_retirado6`,
    `rd`.`codigo_activo_descripcion6` AS `rd_codigo_activo_descripcion6`,
    `rd`.`codigo_activo_nuevo7` AS `rd_codigo_activo_nuevo7`,
    `rd`.`codigo_activo_retirado7` AS `rd_codigo_activo_retirado7`,
    `rd`.`codigo_activo_descripcion7` AS `rd_codigo_activo_descripcion7`,
    `rd`.`codigo_activo_nuevo8` AS `rd_codigo_activo_nuevo8`,
    `rd`.`codigo_activo_retirado8` AS `rd_codigo_activo_retirado8`,
    `rd`.`codigo_activo_descripcion8` AS `rd_codigo_activo_descripcion8`,
    `rd`.`codigo_activo_nuevo9` AS `rd_codigo_activo_nuevo9`,
    `rd`.`codigo_activo_retirado9` AS `rd_codigo_activo_retirado9`,
    `rd`.`codigo_activo_descripcion9` AS `rd_codigo_activo_descripcion9`,
    `rd`.`codigo_activo_nuevo10` AS `rd_codigo_activo_nuevo10`,
    `rd`.`codigo_activo_retirado10` AS `rd_codigo_activo_retirado10`,
    `rd`.`codigo_activo_descripcion10` AS `rd_codigo_activo_descripcion10`,
    `rd`.`modem_3g_4g_sim_nuevo` AS `rd_modem_3g_4g_sim_nuevo`,
    `rd`.`modem_3g_4g_sim_retirado` AS `rd_modem_3g_4g_sim_retirado`,
    
    `r`.`firma_cliente` AS `r_firma_cliente`,
    `r`.`aclaracion_cliente` AS `r_aclaracion_cliente`,
    `r`.`firma_tecnico` AS `r_firma_tecnico`,
    `r`.`aclaracion_tecnico` AS `r_aclaracion_tecnico`,
    `r`.`created_at` AS `created_at`
FROM
    `reparaciones_onsite` `r`
    LEFT JOIN `sucursales_onsite` `s` ON (`r`.`sucursal_onsite_id` = `s`.`id`)
    LEFT JOIN `empresas_onsite` `e` ON (`r`.`id_empresa_onsite` = `e`.`id`)
    LEFT JOIN `terminales_onsite` `t` ON (`r`.`id_terminal` = `t`.`nro`)
    LEFT JOIN `localidades_onsite` `l` ON (`s`.`localidad_onsite_id` = `l`.`id`)
    LEFT JOIN `provincias` `p` ON (`l`.`id_provincia` = `p`.`id`)
    LEFT JOIN `niveles_onsite` `n` ON (`l`.`id_nivel` = `n`.`id`)
    LEFT JOIN `users` `u` ON (`l`.`id_usuario_tecnico` = `u`.`id`)
    LEFT JOIN `tipos_servicios_onsite` `ts` ON (`r`.`id_tipo_servicio` = `ts`.`id`)
    LEFT JOIN `estados_onsite` `es` ON (`r`.`id_estado` = `es`.`id`)
    LEFT JOIN `users` `tec` ON (`r`.`id_tecnico_asignado` = `tec`.`id`)
    LEFT JOIN (
        SELECT
            `reparacion_id`,
            MIN(`fecha`) AS `primer_visita`,
            MIN(`fecha_vencimiento`) AS `fecha_vencimiento`
        FROM `reparaciones_visitas`
        GROUP BY `reparacion_id`
    ) `rv` ON (`rv`.`reparacion_id` = `r`.`id`)
    LEFT JOIN `reparaciones_detalle` `rd` ON (`rd`.`reparacion_id` = `r`.`id`)
WHERE `r`.`id` IN (%repIds%) AND `r`.`company_id` = %company_id%
ORDER BY `r`.`id` DESC
LIMIT %offset%,%per_page%
"
];
