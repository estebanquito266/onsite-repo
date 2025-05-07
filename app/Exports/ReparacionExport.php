<?php

namespace App\Exports;

use App\Models\Respuestos\DetalleOrdenPedidoRespuestosOnsite;
use App\Models\Respuestos\OrdenPedidoRespuestosOnsite;
use App\Models\Respuestos\PiezaRespuestosOnsite;
use App\Services\Onsite\Respuestos\OrdenPedidoRespuestosService;
use DB;
use Log;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

use App\Models\Onsite\ReparacionOnsite;
use App\Models\Onsite\TipoServicioOnsite;
use Auth;

class ReparacionExport implements FromArray, ShouldAutoSize, WithStyles
{
    protected $userCompanyId;

    public function __construct()
    {
        $this->userCompanyId =  session()->get('userCompanyIdDefault');
    }
    public function array(): array
    {
        $visitas_export = $this->getVisitasOnsite();
        return $visitas_export;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true, 'size' => 13]],

            /* // Styling a specific cell by coordinate.
            'B2' => ['font' => ['italic' => true]],

            // Styling an entire column.
            'C'  => ['font' => ['size' => 16]], */
        ];
    }

    /* public function columnFormats(): array
    {
        return [

            'pieza_respuestos_id' => NumberFormat::FORMAT_GENERAL,
                'spare_parts_code' =>NumberFormat::FORMAT_GENERAL,
                'part_name' =>NumberFormat::FORMAT_GENERAL,
                'moneda' =>NumberFormat::FORMAT_GENERAL,
                'precio_fob' =>NumberFormat::FORMAT_GENERAL,
                'cantidad' =>NumberFormat::FORMAT_GENERAL,

            
        ];
    }
 */



    public function getVisitasOnsite()
    {
        //$tipoServicioArrayInclude = [TipoServicioOnsite::PUESTA_MARCHA, TipoServicioOnsite::SEGUIMIENTO_OBRA];
        $tipoServicioArrayInclude = [TipoServicioOnsite::PUESTA_MARCHA];


        $datos['user_id'] = Auth::user()->id;

        $data [] = [
            'id'=>'id',
            'clave'=>'clave',
            'sistema_onsite_id'=>'sistema_onsite_id',
            'obra_id'=>'obra_id',
            'obra_onsite'=>'obra_onsite',
            /* 'tarea'=>'tarea',
            'tarea_detalle'=>'tarea_detalle', */
            'id_solicitud'=>'id_solicitud',
            'id_tipo_servicio'=>'id_tipo_servicio',
            'id_estado'=>'id_estado',
            'estado'=>'estado',
            'fecha_ingreso'=>'fecha_ingreso',
            'fecha_coordinada'=>'fecha_coordinada',
            'ventana_horaria_coordinada'=>'ventana_horaria_coordinada',
            'fecha_vencimiento'=>'fecha_vencimiento',
            'fecha_notificado'=>'fecha_notificado',
            'fecha_registracion_coordinacion'=>'fecha_registracion_coordinacion',
            'latitud'=>'latitud',
            'longitud'=>'longitud',
            'usuario_id'=>'usuario_id',
            'tecnico_asignado'=>'tecnico_asignado',

            'nota_cliente'=>'nota_cliente',
            
            'observaciones_internas'=>'observaciones_internas',

            'tiempo_coordinacion'=>'tiempo_coordinacion',
            'tiempo_cierre'=>'tiempo_cierre',
            'estado_final'=>'estado_final',
            
        ];


        $visitas = ReparacionOnsite::where('reparaciones_onsite.company_id', $this->userCompanyId)
            ->whereIn('reparaciones_onsite.id_tipo_servicio', $tipoServicioArrayInclude)
            ->orderBy('reparaciones_onsite.fecha_ingreso', 'desc')
            ->get();

        foreach ($visitas as $visita) {
            $latitud = (isset($visita->sistema_onsite) && isset($visita->sistema_onsite->obra_onsite)) ?$visita->sistema_onsite->obra_onsite->latitud : NULL;
            $latitud = str_replace(",", ".", $latitud).' ';

            $longitud = (isset($visita->sistema_onsite) && isset($visita->sistema_onsite->obra_onsite)) ?$visita->sistema_onsite->obra_onsite->longitud : NULL;
            $longitud = str_replace(",", ".", $longitud).' ';            

            $tiempoCoordinacion = $this->getTiempoCoordinacion($visita);
            $tiempoCierre = $this->getTiempoCierre($visita);

            $data[] = [
                'id'=>$visita->id,
                 'clave'=>$visita->clave,
                 'sistema_onsite_id'=>isset($visita->sistema_onsite)?$visita->sistema_onsite->id:null,
                 'obra_id'=>(isset($visita->sistema_onsite) && isset($visita->sistema_onsite->obra_onsite))?$visita->sistema_onsite->obra_onsite->id:null,
                 'obra_onsite'=>(isset($visita->sistema_onsite) && isset($visita->sistema_onsite->obra_onsite))?$visita->sistema_onsite->obra_onsite->nombre:null,
                 /* 'tarea'=>$visita->tarea,
                 'tarea_detalle'=>$visita->tarea_detalle, */
                 //'id_tipo_servicio'=>$visita->tipo_servicio_onsite->nombre,
                 'id_solicitud'=>$visita->solicitud_tipo->id,      
                 'id_tipo_servicio'=>$visita->solicitud_tipo->nombre,                 
                 'id_estado'=>$visita->estado_onsite->id,
                 'estado'=>$visita->estado_onsite->nombre,
                 'fecha_ingreso'=>$visita->fecha_ingreso,
                 'fecha_coordinada'=>(isset($visita->fecha_cerrado) ? $visita->fecha_cerrado : $visita->fecha_coordinada),
                 'ventana_horaria_coordinada' => $visita->ventana_horaria_coordinada,
                 'fecha_vencimiento'=>$visita->fecha_vencimiento,
                 'fecha_notificado'=> (isset($visita->fecha_notificado) ? $visita->fecha_notificado : NULL),
                 'fecha_registracion_coordinacion'=> (isset($visita->fecha_registracion_coordinacion) ? $visita->fecha_registracion_coordinacion : NULL),
                 'latitud' => $latitud,
                 'longitud' => $longitud,
                 'usuario_id'=>$visita->user->name,
                 'tecnico_asignado'=>$visita->tecnicoAsignado->name,                 
                 'nota_cliente'=>$visita->nota_cliente,
                 
                 'observaciones_internas'=>$visita->observaciones_internas,

                 'tiempo_coordinacion'=> $tiempoCoordinacion,
                 'tiempo_cierre'=> $tiempoCierre,
                 'estado_final'=>'estado_final',
                                  
            ];
        }

              return $data;
    }

    private function getTiempoCoordinacion($visita) 
    {
        $visita->solicitud_tipo;
    }

    private function getTiempoCierre($visita) 
    {
        
    }    
}
