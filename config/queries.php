<?php

return [
    'view_reparaciones_onsite'=>"SELECT
	`r`.`id` AS `id`,
	min(`r`.`company_id`) AS `company_id`,
	min(`r`.`justificacion`) AS `justificacion`,
	min(`r`.`log`) AS `log`,
	min(`r`.`clave`) AS `clave`,
	min(`r`.`id_empresa_onsite`) AS `id_empresa_onsite`,
	min(`e`.`nombre`) AS `empresa_nombre`,
	min(`r`.`sucursal_onsite_id`) AS `sucursal_id`,
	min(`s`.`razon_social`) AS `razon_social`,
	min(`s`.`direccion`) AS `direccion`,
	min(`s`.`telefono_contacto`) AS `tel_contacto`,
	min(`r`.`id_terminal`) AS `id_terminal`,
	min(`t`.`marca`) AS `marca`,
	min(`t`.`modelo`) AS `modelo`,
	min(`t`.`serie`) AS `serie`,
	min(`t`.`rotulo`) AS `rotulo`,
	min(`s`.`localidad_onsite_id`) AS `localidad_onsite_id`,
	min(`l`.`localidad`) AS `localidad`,
	min(`l`.`id_provincia`) AS `id_provincia`,
	min(`p`.`nombre`) AS `provincia_nombre`,
	min(`l`.`localidad_estandard`) AS `localidad_estandard`,
	min(`l`.`codigo`) AS `codigo`,
	min(`l`.`km`) AS `km`,
	min(`l`.`id_nivel`) AS `id_nivel`,
	min(`n`.`nombre`) AS `nivel_nombre`,
	min(`l`.`atiende_desde`) AS `atiende_desde`,
	min(`l`.`id_usuario_tecnico`) AS `id_usuario_tecnico`,
	min(`u`.`name`) AS `usuario`,
	min(`r`.`tarea`) AS `tarea`,
	min(`r`.`tarea_detalle`) AS `tarea_detalle`,
	min(`r`.`id_tipo_servicio`) AS `id_tipo_serv`,
	min(`ts`.`nombre`) AS `ts_nombre`,
	min(`r`.`id_estado`) AS `id_estado`,
	min(`es`.`nombre`) AS `estado_nombre`,
	min(`es`.`activo`) AS `estado_activo`,
	min(`r`.`fecha_ingreso`) AS `fecha_ingreso`,
	min(
		`r`.`observacion_ubicacion`
	) AS `MIN(``r``.``observacion_ubicacion``)`,
	min(`r`.`id_tecnico_asignado`) AS `id_tecnico_asignado`,
	min(`tec`.`name`) AS `tecnico`,
	min(`r`.`informe_tecnico`) AS `MIN(``r``.``informe_tecnico``)`,
	min(`r`.`fecha_coordinada`) AS `MIN(``r``.``fecha_coordinada``)`,
	min(
		`r`.`ventana_horaria_coordinada`
	) AS `MIN(``r``.``ventana_horaria_coordinada``)`,
	min(
		`r`.`fecha_registracion_coordinacion`
	) AS `MIN(``r``.``fecha_registracion_coordinacion``)`,
	min(`r`.`fecha_notificado`) AS `MIN(``r``.``fecha_notificado``)`,
	min(`rv`.`fecha`) AS `primer_visita`,
	min(`rv`.`fecha_vencimiento`) AS `fecha_vencimiento`,
	min(`r`.`fecha_vencimiento`) AS `vencimiento`,
	min(`r`.`fecha_cerrado`) AS `MIN(``r``.``fecha_cerrado``)`,
	min(`r`.`sla_status`) AS `MIN(``r``.``sla_status``)`,
	min(`r`.`sla_justificado`) AS `MIN(``r``.``sla_justificado``)`,
	min(`r`.`monto`) AS `MIN(``r``.``monto``)`,
	min(`r`.`monto_extra`) AS `MIN(``r``.``monto_extra``)`,
	min(`r`.`liquidado_proveedor`) AS `MIN(``r``.``liquidado_proveedor``)`,
	min(
		`r`.`nro_factura_proveedor`
	) AS `MIN(``r``.``nro_factura_proveedor``)`,
	min(`rd`.`tipo_conexion_local`) AS `MIN(``rd``.``tipo_conexion_local``)`,
	min(
		`rd`.`tipo_conexion_proveedor`
	) AS `MIN(``rd``.``tipo_conexion_proveedor``)`,
	min(`rd`.`cableado`) AS `MIN(``rd``.``cableado``)`,
	min(
		`rd`.`cableado_cantidad_metros`
	) AS `MIN(``rd``.``cableado_cantidad_metros``)`,
	min(
		`rd`.`cableado_cantidad_fichas`
	) AS `MIN(``rd``.``cableado_cantidad_fichas``)`,
	min(`rd`.`instalacion_cartel`) AS `MIN(``rd``.``instalacion_cartel``)`,
	min(
		`rd`.`instalacion_cartel_luz`
	) AS `MIN(``rd``.``instalacion_cartel_luz``)`,
	min(`r`.`instalacion_buzon`) AS `MIN(``r``.``instalacion_buzon``)`,
	min(
		`r`.`cantidad_horas_trabajo`
	) AS `MIN(``r``.``cantidad_horas_trabajo``)`,
	min(
		`r`.`requiere_nueva_visita`
	) AS `MIN(``r``.``requiere_nueva_visita``)`,
	min(
		`rd`.`codigo_activo_nuevo1`
	) AS `MIN(``rd``.``codigo_activo_nuevo1``)`,
	min(
		`rd`.`codigo_activo_retirado1`
	) AS `MIN(``rd``.``codigo_activo_retirado1``)`,
	min(
		`rd`.`codigo_activo_descripcion1`
	) AS `MIN(``rd``.``codigo_activo_descripcion1``)`,
	min(
		`rd`.`codigo_activo_nuevo2`
	) AS `MIN(``rd``.``codigo_activo_nuevo2``)`,
	min(
		`rd`.`codigo_activo_retirado2`
	) AS `MIN(``rd``.``codigo_activo_retirado2``)`,
	min(
		`rd`.`codigo_activo_descripcion2`
	) AS `MIN(``rd``.``codigo_activo_descripcion2``)`,
	min(
		`rd`.`codigo_activo_nuevo3`
	) AS `MIN(``rd``.``codigo_activo_nuevo3``)`,
	min(
		`rd`.`codigo_activo_retirado3`
	) AS `MIN(``rd``.``codigo_activo_retirado3``)`,
	min(
		`rd`.`codigo_activo_descripcion3`
	) AS `MIN(``rd``.``codigo_activo_descripcion3``)`,
	min(
		`rd`.`codigo_activo_nuevo4`
	) AS `MIN(``rd``.``codigo_activo_nuevo4``)`,
	min(
		`rd`.`codigo_activo_retirado4`
	) AS `MIN(``rd``.``codigo_activo_retirado4``)`,
	min(
		`rd`.`codigo_activo_descripcion4`
	) AS `MIN(``rd``.``codigo_activo_descripcion4``)`,
	min(
		`rd`.`codigo_activo_nuevo5`
	) AS `MIN(``rd``.``codigo_activo_nuevo5``)`,
	min(
		`rd`.`codigo_activo_retirado5`
	) AS `MIN(``rd``.``codigo_activo_retirado5``)`,
	min(
		`rd`.`codigo_activo_descripcion5`
	) AS `MIN(``rd``.``codigo_activo_descripcion5``)`,
	min(
		`rd`.`codigo_activo_nuevo6`
	) AS `MIN(``rd``.``codigo_activo_nuevo6``)`,
	min(
		`rd`.`codigo_activo_retirado6`
	) AS `MIN(``rd``.``codigo_activo_retirado6``)`,
	min(
		`rd`.`codigo_activo_descripcion6`
	) AS `MIN(``rd``.``codigo_activo_descripcion6``)`,
	min(
		`rd`.`codigo_activo_nuevo7`
	) AS `MIN(``rd``.``codigo_activo_nuevo7``)`,
	min(
		`rd`.`codigo_activo_retirado7`
	) AS `MIN(``rd``.``codigo_activo_retirado7``)`,
	min(
		`rd`.`codigo_activo_descripcion7`
	) AS `MIN(``rd``.``codigo_activo_descripcion7``)`,
	min(
		`rd`.`codigo_activo_nuevo8`
	) AS `MIN(``rd``.``codigo_activo_nuevo8``)`,
	min(
		`rd`.`codigo_activo_retirado8`
	) AS `MIN(``rd``.``codigo_activo_retirado8``)`,
	min(
		`rd`.`codigo_activo_descripcion8`
	) AS `MIN(``rd``.``codigo_activo_descripcion8``)`,
	min(
		`rd`.`codigo_activo_nuevo9`
	) AS `MIN(``rd``.``codigo_activo_nuevo9``)`,
	min(
		`rd`.`codigo_activo_retirado9`
	) AS `MIN(``rd``.``codigo_activo_retirado9``)`,
	min(
		`rd`.`codigo_activo_descripcion9`
	) AS `MIN(``rd``.``codigo_activo_descripcion9``)`,
	min(
		`rd`.`codigo_activo_nuevo10`
	) AS `MIN(``rd``.``codigo_activo_nuevo10``)`,
	min(
		`rd`.`codigo_activo_retirado10`
	) AS `MIN(``rd``.``codigo_activo_retirado10``)`,
	min(
		`rd`.`codigo_activo_descripcion10`
	) AS `MIN(``rd``.``codigo_activo_descripcion10``)`,
	min(
		`rd`.`modem_3g_4g_sim_nuevo`
	) AS `MIN(``rd``.``modem_3g_4g_sim_nuevo``)`,
	min(
		`rd`.`modem_3g_4g_sim_retirado`
	) AS `MIN(``rd``.``modem_3g_4g_sim_retirado``)`,
	min(`r`.`firma_cliente`) AS `MIN(``r``.``firma_cliente``)`,
	min(`r`.`aclaracion_cliente`) AS `MIN(``r``.``aclaracion_cliente``)`,
	min(`r`.`firma_tecnico`) AS `MIN(``r``.``firma_tecnico``)`,
	min(`r`.`aclaracion_tecnico`) AS `MIN(``r``.``aclaracion_tecnico``)`,
	min(`r`.`created_at`) AS `created_at`
FROM
	(
		(
			(
				(
					(
						(
							(
								(
									(
										(
											(
												(
													`reparaciones_onsite` `r`
													LEFT JOIN `sucursales_onsite` `s` ON (
														(
															`r`.`sucursal_onsite_id` = `s`.`id`
														)
													)
												)
												LEFT JOIN `empresas_onsite` `e` ON (
													(
														`r`.`id_empresa_onsite` = `e`.`id`
													)
												)
											)
											LEFT JOIN `terminales_onsite` `t` ON (
												(
													`r`.`id_terminal` = `t`.`nro`
												)
											)
										)
										LEFT JOIN `localidades_onsite` `l` ON (
											(
												`s`.`localidad_onsite_id` = `l`.`id`
											)
										)
									)
									LEFT JOIN `provincias` `p` ON (
										(
											`l`.`id_provincia` = `p`.`id`
										)
									)
								)
								LEFT JOIN `niveles_onsite` `n` ON ((`l`.`id_nivel` = `n`.`id`))
							)
							LEFT JOIN `users` `u` ON (
								(
									`l`.`id_usuario_tecnico` = `u`.`id`
								)
							)
						)
						LEFT JOIN `tipos_servicios_onsite` `ts` ON (
							(
								`r`.`id_tipo_servicio` = `ts`.`id`
							)
						)
					)
					LEFT JOIN `estados_onsite` `es` ON (
						(`r`.`id_estado` = `es`.`id`)
					)
				)
				LEFT JOIN `users` `tec` ON (
					(
						`r`.`id_tecnico_asignado` = `tec`.`id`
					)
				)
			)
			LEFT JOIN `reparaciones_visitas` `rv` ON (
				(
					`rv`.`reparacion_id` = `r`.`id`
				)
			)
		)
		LEFT JOIN `reparaciones_detalle` `rd` ON (
			(
				`rd`.`reparacion_id` = `r`.`id`
			)
		)
	)
GROUP BY
	`r`.`id`

"
];