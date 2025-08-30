<?php

namespace App\Jobs;

use App\Events\ExportCompleted;
use App\Exports\ReparacionesOnsiteExport;
use App\Models\Notificacion;
use DB;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Log;

use OpenSpout\Common\Entity\Cell;
use OpenSpout\Common\Entity\Row;
//use OpenSpout\Common\Entity\Style\Color;
//use OpenSpout\Common\Entity\Style\CellAlignment;
use OpenSpout\Common\Entity\Style\Style;
//use OpenSpout\Writer\XLSX\Options;

use OpenSpout\Writer\Common\Creator\WriterEntityFactory;
use OpenSpout\Writer\Common\Creator\Style\StyleBuilder;
use OpenSpout\Common\Entity\Style\CellAlignment;
use OpenSpout\Common\Entity\Style\Color;

class ExportarReparacionesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $request;
    public $userCompanyId;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $request, $userCompanyId)
    {
        $this->request = $request;
        $this->userCompanyId = $userCompanyId;
        Log::alert('EXITOSO CONSTRUCT: ' . $this->request['exitoso']);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::alert('comienza exportación');
        //  (new ReparacionesOnsiteExport($this->request, $this->userCompanyId))->store('exports/listado_rep_onsite.xlsx', 'local');

        //$view_reparaciones_onsite = config('queries.view_reparaciones_onsite');
        
        $query = DB::table('view_reparaciones_onsite')
            //->where('id_empresa_onsite', 1095)
            //->where('id', '<' , 50000)
            //->where('estado_activo', true)
            ->orderBy('id', 'desc');

        /*$query = DB::table(DB::raw("({$view_reparaciones_onsite}) as view_reparaciones_onsite"))
                                        ->orderBy('id','desc');*/

        switch ($this->request['exitoso']) {
            case 0:
                $filename = 'reparaciones_';
                $tipo = 'exportacion'; //es para filtrar el listado de las exportaciones generadas
                break;

            case 1:
                $filename = 'reparaciones_EX_';
                $query = $query->whereIn('id_estado', [45]);
                $tipo = 'exportacion_exitosa'; //es para filtrar el listado de las exportaciones generadas
                break;

            case 2:
                $query = $query->whereIn('id_estado', [46]);
                $filename = 'reparaciones_NO_EX_';
                $tipo = 'exportacion_no_exitosa'; //es para filtrar el listado de las exportaciones generadas
                break;
            case 3:
                $filename = 'servicios_';
                $tipo = 'exportacion_servicios'; //es para filtrar el listado de las exportaciones generadas
                unset($this->request['estados_activo']);
                break;
            case 4:
                $filename = 'servicios_ACT_';
                $tipo = 'exportacion_servicios_activos'; //es para filtrar el listado de las exportaciones generadas
                $this->request['estados_activo'] = 'on';
                break;
        }

        if (isset($this->request['id_empresa']) && count($this->request['id_empresa']) > 0) {
            $filename .= 'EMP_';
            $query = $query->whereIn('id_empresa_onsite', $this->request['id_empresa']);
            //$query = $query->where('id_empresa_onsite', 1095);

            foreach ($this->request['id_empresa'] as $key => $value) {
                $filename .= $value . '-';
            }
            $empresa_id = $this->request['id_empresa'][0];
        } else {
            $query = $query->where('id_empresa_onsite', 1095);
            $empresa_id = 1;
        }

        if (isset($this->request['estados_activo']) && $this->request['estados_activo'] === 'on') {
            $query = $query->where('estado_activo', true);
            $filename .= 'ACT-';
        }

        if (!is_null($this->request['fecha_creacion_desde']) && !is_null($this->request['fecha_creacion_hasta'])) {
            $query = $query->whereBetween('created_at', [$this->request['fecha_creacion_desde'], $this->request['fecha_creacion_hasta']]);
            $filename .= 'CREACION_' . $this->request['fecha_creacion_desde'] . '-' .  $this->request['fecha_creacion_hasta'] . '-';
        }

        if (!is_null($this->request['fecha_ingreso_desde']) && !is_null($this->request['fecha_ingreso_hasta'])) {
            $query = $query->whereBetween('fecha_ingreso', [$this->request['fecha_ingreso_desde'], $this->request['fecha_ingreso_hasta']]);
            $filename .= 'INGRESO_' . $this->request['fecha_ingreso_desde'] . '-' .  $this->request['fecha_ingreso_hasta'] . '-';
        }

        if (isset($this->request['id_estado']) && count($this->request['id_estado']) > 0) {
            $query = $query->whereIn('id_estado', $this->request['id_estado']);
            $filename .= 'ESTADOS_';
            foreach ($this->request['id_estado'] as $key => $value) {
                $filename .= $value . '-';
            }
        }
        $filename .= uniqid();

        $filename .= '.xlsx';


        $query = $query->get(); // Use lazy() to fetch data in chunks

        $reparacionIds = $query->pluck('id')->all();

        $imagenes = DB::table('imagenes_onsite')
                            ->select('reparacion_onsite_id', 'archivo')
                            ->whereIn('reparacion_onsite_id', $reparacionIds)
                            ->orderBy('reparacion_onsite_id')
                            ->orderBy('id')
                            ->get()
                            ->groupBy('reparacion_onsite_id');
    

        Log::info('Memory usage after get: ' . memory_get_usage());

        //nuevo sistema


        //$writer = new \OpenSpout\Writer\XLSX\Writer($options);
        $writer = WriterEntityFactory::createXLSXWriter();

        $filePath = storage_path() . '/app/exports/' . $filename;
        $writer->openToFile($filePath);

        switch ($this->request['exitoso']) {
            case 0:

                $header = [
                    'ID',
                    'company_id',
                    'CLAVE',
                    'ID_EMPRESA_ONSITE',
                    'EMPRESA_ONSITE',
                    'SUCURSAL_ONSITE_ID',
                    'SUCURSAL_RAZON_SOCIAL',
                    'SUCURSAL_DIRECCION',
                    'SUCURSAL_TELEFONO',
                    'ID_TERMINAL',
                    'TERMINAL_MARCA',
                    'TERMINAL_MODELO',
                    'TERMINAL_SERIE',
                    'TERMINAL_ROTULO',
                    'ID_LOCALIDAD',
                    'LOCALIDAD',
                    'LOCALIDAD_ID_PROVINCIA',
                    'LOCALIDAD_PROVINCIA',
                    'LOCALIDAD_ESTANDARD',
                    'LOCALIDAD_CODIGO_POSTAL',
                    'LOCALIDAD_KM',
                    'LOCALIDAD_ID_NIVEL',
                    'LOCALIDAD_NIVEL',
                    'LOCALIDAD_ATIENDE_DESDE',
                    'LOCALIDAD_ID_TECNICO',
                    'LOCALIDAD_TECNICO',
                    'TAREA',
                    'DETALLE_TAREA',
                    'ID_TIPO_SERVICIO',
                    'TIPO_SERVICIO',
                    'ID_ESTADO',
                    'ESTADO',
                    'es_activo',
                    'FECHA_INGRESO',
                    'OBSERVACION_UBICACION',
                    'ID_TECNICO_ASIGNADO',
                    'TECNICO_ASIGNADO',
                    'INFORME_TECNICO',
                    'FECHA_COORDINADA',
                    'VENTANA_HORARIA_COORDINADA',
                    'FECHA_REGISTRACION_COORDINACION',
                    'FECHA_NOTIFICADO',

                    'FECHA_1_VISITA',
                    'FECHA_1_VENCIMIENTO',

                    'FECHA_VENCIMIENTO',
                    'FECHA_CERRADO',
                    'SLA_STATUS',
                    'SLA_JUSTIFICADO',
                    'MONTO',
                    'MONTO_EXTRA',
                    'LIQUIDADO_PROVEEDOR',
                    'NRO_FACTURA_PROVEEDOR',

                    'TIPO_CONEXION_LOCAL',
                    'TIPO_CONEXION_PROVEEDOR',
                    'CABLEADO',
                    'CABLEADO_CANTIDAD_METROS',
                    'CABLEADO_CANTIDAD_FICHAS',
                    'INSTALACION_CARTEL',
                    'INSTALACION_CARTEL_LUZ',
                    'INSTALACION_BUZON',
                    'CANTIDAD_HORAS_TRABAJO',
                    'REQUIERE_NUEVA_VISITA',
                    'OBSERVACIONES_INTERNAS',
                    'CODIGO_ACTIVO_NUEVO1',
                    'CODIGO_ACTIVO_RETIRADO1',
                    'CODIGO_ACTIVO_DESCRIPCION1',
                    'CODIGO_ACTIVO_NUEVO2',
                    'CODIGO_ACTIVO_RETIRADO2',
                    'CODIGO_ACTIVO_DESCRIPCION2',
                    'CODIGO_ACTIVO_NUEVO3',
                    'CODIGO_ACTIVO_RETIRADO3',
                    'CODIGO_ACTIVO_DESCRIPCION3',
                    'CODIGO_ACTIVO_NUEVO4',
                    'CODIGO_ACTIVO_RETIRADO4',
                    'CODIGO_ACTIVO_DESCRIPCION4',
                    'CODIGO_ACTIVO_NUEVO5',
                    'CODIGO_ACTIVO_RETIRADO5',
                    'CODIGO_ACTIVO_DESCRIPCION5',
                    'CODIGO_ACTIVO_NUEVO6',
                    'CODIGO_ACTIVO_RETIRADO6',
                    'CODIGO_ACTIVO_DESCRIPCION6',
                    'CODIGO_ACTIVO_NUEVO7',
                    'CODIGO_ACTIVO_RETIRADO7',
                    'CODIGO_ACTIVO_DESCRIPCION7',
                    'CODIGO_ACTIVO_NUEVO8',
                    'CODIGO_ACTIVO_RETIRADO8',
                    'CODIGO_ACTIVO_DESCRIPCION8',
                    'CODIGO_ACTIVO_NUEVO9',
                    'CODIGO_ACTIVO_RETIRADO9',
                    'CODIGO_ACTIVO_DESCRIPCION9',
                    'CODIGO_ACTIVO_NUEVO10',
                    'CODIGO_ACTIVO_RETIRADO10',
                    'CODIGO_ACTIVO_DESCRIPCION10',
                    'MODEM_3G_4G_SIM_NUEVO',
                    'MODEM_3G_4G_SIM_RETIRADO',
                    'FIRMA_CLIENTE',
                    'ACLARACION_CLIENTE',
                    'FIRMA_TECNICO',
                    'ACLARACION_TECNICO',
                    'JUSTIFICACION',
                    'created_at'

                ];

                // Add header row dynamically
                $columns = [
                    'id',
                    'company_id',
                    'clave',
                    'id_empresa_onsite',
                    'empresa_nombre',
                    'sucursal_onsite_id',
                    'razon_social',
                    'direccion',
                    'telefono_contacto',
                    'id_terminal',
                    'marca',
                    'modelo',
                    'serie',
                    'rotulo',
                    'localidad_onsite_id',
                    'localidad',
                    'id_provincia',
                    'provincia_nombre',
                    'localidad_estandard',
                    'codigo',
                    'km',
                    'id_nivel',
                    'nivel_nombre',
                    'atiende_desde',
                    'id_usuario_tecnico',
                    'usuario',
                    'tarea',
                    'tarea_detalle',
                    'id_tipo_servicio',
                    'ts_nombre',
                    'id_estado',
                    'estado_nombre',
                    'estado_activo',
                    'fecha_ingreso',
                    'observacion_ubicacion',
                    'id_tecnico_asignado',
                    'tecnico',
                    'informe_tecnico',
                    'fecha_coordinada',
                    'ventana_horaria_coordinada',
                    'fecha_registracion_coordinacion',
                    'fecha_notificado',
                    'primer_visita',
                    'fecha_vencimiento',
                    'vencimiento',
                    'fecha_cerrado',
                    'sla_status',
                    'sla_justificado',
                    'monto',
                    'monto_extra',
                    'liquidado_proveedor',
                    'nro_factura_proveedor',
                    'tipo_conexion_local',
                    'tipo_conexion_proveedor',
                    'cableado',
                    'cableado_cantidad_metros',
                    'cableado_cantidad_fichas',
                    'instalacion_cartel',
                    'instalacion_cartel_luz',
                    'instalacion_buzon',
                    'cantidad_horas_trabajo',
                    'requiere_nueva_visita',
                    'observaciones_internas',
                    'codigo_activo_nuevo1',
                    'codigo_activo_retirado1',
                    'codigo_activo_descripcion1',
                    'codigo_activo_nuevo2',
                    'codigo_activo_retirado2',
                    'codigo_activo_descripcion2',
                    'codigo_activo_nuevo3',
                    'codigo_activo_retirado3',
                    'codigo_activo_descripcion3',
                    'codigo_activo_nuevo4',
                    'codigo_activo_retirado4',
                    'codigo_activo_descripcion4',
                    'codigo_activo_nuevo5',
                    'codigo_activo_retirado5',
                    'codigo_activo_descripcion5',
                    'codigo_activo_nuevo6',
                    'codigo_activo_retirado6',
                    'codigo_activo_descripcion6',
                    'codigo_activo_nuevo7',
                    'codigo_activo_retirado7',
                    'codigo_activo_descripcion7',
                    'codigo_activo_nuevo8',
                    'codigo_activo_retirado8',
                    'codigo_activo_descripcion8',
                    'codigo_activo_nuevo9',
                    'codigo_activo_retirado9',
                    'codigo_activo_descripcion9',
                    'codigo_activo_nuevo10',
                    'codigo_activo_retirado10',
                    'codigo_activo_descripcion10',
                    'modem_3g_4g_sim_nuevo',
                    'modem_3g_4g_sim_retirado',
                    'firma_cliente',
                    'aclaracion_cliente',
                    'firma_tecnico',
                    'aclaracion_tecnico',
                    'justificacion',
                    'created_at',
                ];
                break;

            case 1:

                $header = [
                    'ID',
                    'CLAVE',
                    'SUCURSAL_RAZON_SOCIAL',
                    /* NUEVO */
                    'SUCURSAL_DIRECCION',
                    'LOCALIDAD_PROVINCIA',
                    'LOCALIDAD_ESTANDARD',                    
                    //'TERMINAL_SERIE',
                    'LOCALIDAD_TECNICO',                    
                    'ESTADO',
                    'FECHA_INGRESO',
                    //'INFORME_TECNICO',                    
                    'FECHA_CERRADO',
                    //'OBSERVACIONES_INTERNAS',
                    'CODIGO_ACTIVO_RETIRADO1',
                    'CODIGO_ACTIVO_DESCRIPCION1',
                    /* NUEVO */
                    'CODIGO_ACTIVO_RETIRADO2',
                    'CODIGO_ACTIVO_DESCRIPCION2',
                    'DEVOLUCIÓN'

                ];

                // Add header row dynamically
                $columns = [
                    'id',
                    'clave',
                    'razon_social',
                    /* NUEVO */
                    'direccion',
                    'provincia_nombre',
                    'localidad_estandard',                    
                    //'serie',
                    'usuario',                    
                    'estado_nombre',
                    'fecha_ingreso',
                    //'informe_tecnico',
                    'fecha_cerrado',
                    //'observaciones_internas',
                    'codigo_activo_retirado1',
                    'codigo_activo_descripcion1',
                    /* NUEVO */
                    'codigo_activo_retirado2',
                    'codigo_activo_descripcion2',
                    'monto'

                ];
                break;

            case 2:

                $header = [
                    'ID',
                    'CLAVE',
                    'SUCURSAL_RAZON_SOCIAL',
                    'SUCURSAL_TELEFONO',
                    'TERMINAL_SERIE',
                    'LOCALIDAD_TECNICO',
                    'TIPO_SERVICIO',
                    'ESTADO',
                    'FECHA_INGRESO',
                    'INFORME_TECNICO',
                    'FECHA_VENCIMIENTO',
                    'FECHA_CERRADO',
                    'OBSERVACIONES_INTERNAS',


                ];

                // Add header row dynamically
                $columns = [
                    'id',
                    'clave',
                    'razon_social',
                    'telefono_contacto',
                    'serie',
                    'usuario',
                    'ts_nombre',
                    'estado_nombre',
                    'fecha_ingreso',
                    'informe_tecnico',
                    'vencimiento',
                    'fecha_cerrado',
                    'observaciones_internas',

                ];
                break;

            case 3:

                $header = [
                    'CLAVE',
                    'SUCURSAL_DIRECCION',
                    'SUCURSAL_TELEFONO',
                    'ID_TERMINAL',
                    'TERMINAL_MARCA',
                    'TERMINAL_MODELO',
                    'TERMINAL_SERIE',
                    'LOCALIDAD',
                    'LOCALIDAD_PROVINCIA',
                    'LOCALIDAD_ESTANDARD',
                    'LOCALIDAD_CODIGO_POSTAL',
                    'LOCALIDAD_NIVEL',
                    'LOCALIDAD_ATIENDE_DESDE',
                    'TAREA',
                    'DETALLE_TAREA',
                    'TIPO_SERVICIO',
                    'ESTADO',
                    'FECHA_INGRESO',
                    'OBSERVACION_UBICACION',
                    'TECNICO_ASIGNADO',
                    'INFORME_TECNICO',
                    'FECHA_REGISTRACION_COORDINACION',
                    'FECHA_1_VISITA',
                    'FECHA_1_VENCIMIENTO',
                    'FECHA_VENCIMIENTO',
                    'FECHA_CERRADO',
                    'SLA_STATUS',
                    'SLA_JUSTIFICADO',
                    'REQUIERE_NUEVA_VISITA',
                    'OBSERVACIONES_INTERNAS',
                    'CODIGO_ACTIVO_NUEVO1',
                    'CODIGO_ACTIVO_RETIRADO1',
                    'CODIGO_ACTIVO_DESCRIPCION1',
                    'CODIGO_ACTIVO_NUEVO2',
                    'CODIGO_ACTIVO_RETIRADO2',
                    'FIRMA_CLIENTE',
                    'ACLARACION_CLIENTE',
                    'FIRMA_TECNICO',
                    'ACLARACION_TECNICO',

                ];

                // Add header row dynamically
                $columns = [
                    'clave',
                    'direccion',
                    'telefono_contacto',
                    'id_terminal',
                    'marca',
                    'modelo',
                    'serie',
                    'localidad',
                    'provincia_nombre',
                    'localidad_estandard',
                    'codigo',
                    'nivel_nombre',
                    'atiende_desde',
                    'tarea',
                    'tarea_detalle',
                    'ts_nombre',
                    'estado_nombre',
                    'fecha_ingreso',
                    'observacion_ubicacion',
                    'tecnico',
                    'informe_tecnico',
                    'fecha_registracion_coordinacion',
                    'primer_visita',
                    'vencimiento',
                    'fecha_vencimiento',
                    'fecha_cerrado',
                    'sla_status',
                    'sla_justificado',
                    'requiere_nueva_visita',
                    'observaciones_internas',
                    'codigo_activo_nuevo1',
                    'codigo_activo_retirado1',
                    'codigo_activo_descripcion1',
                    'codigo_activo_nuevo2',
                    'codigo_activo_retirado2',
                    'firma_cliente',
                    'aclaracion_cliente',
                    'firma_tecnico',
                    'aclaracion_tecnico',
                ];

                break;

            case 4:

                $header = [
                    'CLAVE',
                    'SUCURSAL_DIRECCION',
                    'SUCURSAL_TELEFONO',
                    'ID_TERMINAL',
                    'TERMINAL_MARCA',
                    'TERMINAL_MODELO',
                    'TERMINAL_SERIE',
                    'LOCALIDAD',
                    'LOCALIDAD_PROVINCIA',
                    'LOCALIDAD_ESTANDARD',
                    'LOCALIDAD_CODIGO_POSTAL',
                    'LOCALIDAD_NIVEL',
                    'LOCALIDAD_ATIENDE_DESDE',
                    'TAREA',
                    'DETALLE_TAREA',
                    'TIPO_SERVICIO',
                    'ESTADO',
                    'FECHA_INGRESO',
                    'OBSERVACION_UBICACION',
                    'TECNICO_ASIGNADO',
                    'FECHA_1_VISITA',
                    'FECHA_1_VENCIMIENTO',
                    'FECHA_VENCIMIENTO',
                    'SLA_STATUS',
                    'OBSERVACIONES_INTERNAS',
                    'JUSTIFICACION',

                ];

                // Add header row dynamically
                $columns = [
                    'clave',
                    'direccion',
                    'telefono_contacto',
                    'id_terminal',
                    'marca',
                    'modelo',
                    'serie',
                    'localidad',
                    'provincia_nombre',
                    'localidad_estandard',
                    'codigo',
                    'nivel_nombre',
                    'atiende_desde',
                    'tarea',
                    'tarea_detalle',
                    'ts_nombre',
                    'estado_nombre',
                    'fecha_ingreso',
                    'observacion_ubicacion',
                    'tecnico',
                    'primer_visita',
                    'vencimiento',
                    'fecha_vencimiento',
                    'sla_status',
                    'observaciones_internas',
                    'justificacion'
                ];


                break;
        }

        for ($i = 1; $i <= 5; $i++) {
            $header[] = 'EVIDENCIA ' . $i;
        }
        
        $header[] = 'Log';
        

        $style = (new StyleBuilder())
            ->setBackgroundColor(Color::LIGHT_BLUE)
            ->setCellAlignment(CellAlignment::CENTER)
            ->build();


        /* $style = new Style();
        $style->setBackgroundColor(Color::LIGHT_BLUE);
        $style->setCellAlignment(CellAlignment::CENTER); */
        //$writer->addRow(Row::fromValues($header, $style));

        /* php viejo */
        $row = WriterEntityFactory::createRowFromArray($header, $style);
        $writer->addRow($row);


        // Create a style with the Style


        $style = (new StyleBuilder())
            ->setShouldWrapText(false)
            ->build();


        $URL_IMG = config('app.URL_IMG');

        $query->chunk(1000)->each(function ($chunk) use ($writer, $columns, $style,$imagenes,$URL_IMG) {
            $chunk->each(function ($reparacion) use ($writer, $columns, $style,$imagenes,$URL_IMG) {

                $data = [];
                foreach ($columns as $column) {
                    $data[] = $reparacion->$column ?? null; // Use null if column is missing
                }

                $imgs = $imagenes->get($reparacion->id, collect())->take(5);

                for ($i = 0; $i < 5; $i++) {
                    $archivo = null;
                    if(isset($imgs[$i]) && $imgs[$i]->archivo){
                        $archivo = "{$URL_IMG}/".$imgs[$i]->archivo;
                        $archivo = '=HYPERLINK("' . $archivo . '", "'.$archivo.'")';
                    }
                    $data[] = $archivo;
                }


                $data[] = $reparacion->log; //Columna Log
                
                $rowFromValues = WriterEntityFactory::createRowFromArray($data, $style); //php viejo

                //$writer->addRow(Row::fromValues($data, $style)); php nuevo
                $writer->addRow($rowFromValues);
            });
        });

        Log::info('Memory usage before export: ' . memory_get_usage());


        $writer->close();

        unset($writer);
        gc_collect_cycles();

        Log::info('Memory usage after export: ' . memory_get_usage());

        Notificacion::create([
            'notificacion' => $filename,
            'tipo' => $tipo,
            'empresa_id' => $empresa_id
        ]);
        Log::alert('finaliza exportación');


        // Start the export
        /* $export = new ReparacionesOnsiteExport($this->request, $this->userCompanyId);
        $export->store('exports/listado_rep_onsite.xlsx', 'local');
        Log::info('Memory usage before export: ' . memory_get_usage()); */

        // Unset large objects
        /*  unset($export);
        gc_collect_cycles();
        Log::info('Memory usage after export: ' . memory_get_usage()); */

        /*  $link = "<a href='/exportaciones/listado_rep_onsite.xlsx'>Descargar</a>";
        ExportCompleted::dispatch('Se completó la exportación correctamente: ' . $link); */
    }
}
