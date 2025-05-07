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

class PreciosRepuestosExport implements FromArray, ShouldAutoSize, WithStyles, WithColumnFormatting
{
    /**
     * 
     * @return \Illuminate\Support\Collection
     */

    protected $userCompanyId;


    public function __construct()
    {
        $this->userCompanyId =  session()->get('userCompanyIdDefault');
    }

    /* public function collection()
    {
        $pedidos = $this->getOrdenesPedidosCantidad();

        return $pedidos;
    } */

    public function array(): array
    {
        $pedidos = $this->getPiezasConPrecios();
        return $pedidos;
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

    public function columnFormats(): array
    {
        return [            
            'F' => NumberFormat::FORMAT_NUMBER_00,
        ];
    }



    public function getPiezasConPrecios()
    {
        $piezas = PiezaRespuestosOnsite::with(['precio' => function ($query) {
            $query->orderBy('version', 'desc');
        }])
            ->with('modelo_pieza')
            ->where('company_id', $this->userCompanyId)
            ->get();


        foreach ($piezas as $pieza) {

            if (count($pieza->precio) > 0 && $pieza->precio[0]->precio_fob >0) 
            
                $precio =  floatval($pieza->precio[0]->precio_fob);
             
                else
                    if ($pieza->precio_fob > 0) $precio = $pieza->precio_fob;
                    else $precio = 0.001;

            $detalle_pieza[] = [

                'company_id' => $pieza->company_id,
                'piezas_respuestos_onsite_id' => intval($pieza->id),
                'spare_parts_code' => strval(" " . $pieza->spare_parts_code),
                'part_name' => $pieza->part_name,
                'moneda' => $pieza->moneda,
                'precio_fob' => $precio,
                'version' => count($pieza->precio) > 0 ? $pieza->precio[0]->version : 1,
                'vencimiento_precio' => count($pieza->precio) > 0 ? $pieza->precio[0]->vencimiento_precio : '2099-01-01'
            ];
        }

        /* usort($detalle_pieza, function ($a, $b) {
            return $b['cantidad'] <=> $a['cantidad'];
        });*/

        array_unshift($detalle_pieza, [

            'company_id' => 'company_id',
            'piezas_respuestos_onsite_id' => 'piezas_respuestos_onsite_id',
            'spare_parts_code' => 'spare_parts_code',
            'part_name' => 'part_name',
            'moneda' => 'moneda',
            'precio_fob' => 'precio_fob',
            'version' => 'version',
            'vencimiento_precio' => 'vencimiento_precio',
        ]);




        return $detalle_pieza;
    }
}
