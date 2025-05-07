<?php

namespace Database\Factories\Onsite;

use App\Models\Onsite\ReparacionOnsite;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ReparacionOnsiteFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ReparacionOnsite::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
          'clave' => Str::random(10),
          'id_empresa_onsite' => 7, // Nike
          'id_terminal' => 'A21603', // SICOM SUPER COMODIN ALTO COMEDERO
          'id_tipo_servicio' => 1, // Alta
          'id_estado' => 1,
          'id_tecnico_asignado' => 70,
          'tarea' => $this->faker->name,
          'tarea_detalle' => $this->faker->name,
          'fecha_ingreso' => date("Y-m-d h:i:s"),
          'observacion_ubicacion' => $this->faker->name,
          'nro_caja' => Str::random(10),
          'informe_tecnico' => $this->faker->name,
          'transacciones_pendientes' => rand(0,1),
          'impresora_termica_scanner' => rand(0,1),
          'usuario_agentes' => rand(0,1),
          'usuario_agentes_red_local' => $this->faker->name,
          'configuracion_impresora' => rand(0,1),
          'usuarios_sf2' => rand(0,1),
          'configuracion_pc_servidora' => rand(0,1),
          'conectividad_sf2_wut_dns_vnc' => rand(0,1),
          'carpeta_sf2_permisos' => rand(0,1),
          'tension_electrica' => rand(0,1),
          'tipo_conexion_local' => $this->faker->name,
          'tipo_conexion_proveedor' => $this->faker->name,
          'cableado' => rand(0,1),
          'cableado_cantidad_metros' => rand(1,100),
          'cableado_cantidad_fichas' => rand(1,100),
          'instalacion_cartel' => rand(0,1),
          'instalacion_cartel_luz' => rand(0,1),
          'insumos_banner' => rand(0,1),
          'insumos_folleteria' => rand(0,1),
          'insumos_rojos_impresora' => rand(0,1),
          'fotos_frente_local' => rand(0,1),
          'fotos_modem_enlace_switch' => rand(0,1),
          'fotos_terminal_red' => rand(0,1),
          'instalacion_buzon' => rand(0,1),
          'cantidad_horas_trabajo' => Str::random(5),
          'requiere_nueva_visita' => rand(0,1),
          'codigo_activo_nuevo1' => Str::random(5),
          'codigo_activo_retirado1' => Str::random(5),
          'codigo_activo_descripcion1' => Str::random(5),
          'codigo_activo_nuevo2' => Str::random(5),
          'codigo_activo_retirado2' => Str::random(5),
          'codigo_activo_descripcion2' => Str::random(5),
          'codigo_activo_nuevo3' => Str::random(5),
          'codigo_activo_retirado3' => Str::random(5),
          'codigo_activo_descripcion3' => Str::random(5),
          'codigo_activo_nuevo4' => Str::random(5),
          'codigo_activo_retirado4' => Str::random(5),
          'codigo_activo_descripcion4' => Str::random(5),
          'codigo_activo_nuevo5' => Str::random(5),
          'codigo_activo_retirado5' => Str::random(5),
          'codigo_activo_descripcion5' => Str::random(5),
          'codigo_activo_nuevo6' => Str::random(5),
          'codigo_activo_retirado6' => Str::random(5),
          'codigo_activo_descripcion6' => Str::random(5),
          'codigo_activo_nuevo7' => Str::random(5),
          'codigo_activo_retirado7' => Str::random(5),
          'codigo_activo_descripcion7' => Str::random(5),
          'codigo_activo_nuevo8' => Str::random(5),
          'codigo_activo_retirado8' => Str::random(5),
          'codigo_activo_descripcion8' => Str::random(5),
          'codigo_activo_nuevo9' => Str::random(5),
          'codigo_activo_retirado9' => Str::random(5),
          'codigo_activo_descripcion9' => Str::random(5),
          'codigo_activo_nuevo10' => Str::random(5),
          'codigo_activo_retirado10' => Str::random(5),
          'codigo_activo_descripcion10' => Str::random(5),
          'modem_3g_4g_sim_nuevo' => $this->faker->name,
          'modem_3g_4g_sim_retirado' => $this->faker->name,
          'firma_cliente' => $this->faker->name,
          'aclaracion_cliente' => $this->faker->name,
          'firma_tecnico' => $this->faker->name,
          'aclaracion_tecnico' => $this->faker->name,
          'foto_frente_local_1' => $this->faker->name,
          'foto_frente_local_2' => $this->faker->name,
          'foto_equipo_1' => $this->faker->name,
          'foto_equipo_2' => $this->faker->name,
          'foto_terminal_red_1' => $this->faker->name,
          'foto_terminal_red_2' => $this->faker->name,
          'foto_comprobante' => $this->faker->name,
          'fecha_coordinada' => date("Y-m-d"),
          'ventana_horaria_coordinada' => Str::random(20),
          'fecha_registracion_coordinacion' => date("Y-m-d"),
          'fecha_notificado' => date("Y-m-d"),
          'fecha_vencimiento' => date("Y-m-d h:i:s"),
          'fecha_cerrado' => date("Y-m-d h:i:s"),
          'sla_status' => Str::random(3),
          'sla_justificado' => rand(0,1),
          'monto' => (string) number_format(rand(3000, 5000), 2, '.', ''),
          'monto_extra' => (string) number_format(rand(3000, 5000), 2, '.', ''),
          'liquidado_proveedor' => rand(0,1),
          'nro_factura_proveedor' => (string) rand(10000000,99999999),
          'visible_cliente' => rand(0,1),
          'chequeado_cliente' => rand(0,1),
        ];
    }
}
