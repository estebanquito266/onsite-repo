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

class PedidosExport implements FromArray, ShouldAutoSize, WithStyles
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
        $pedidos = $this->getPiezasConPedidos();
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


    public function getOrdenesPedidosCantidad()
    {


        $pedidosDetalle = DetalleOrdenPedidoRespuestosOnsite::with('pieza')
            ->where('company_id', $this->userCompanyId)
            /* ->groupBy('pieza_respuestos_id')
            ->sum('cantidad') */
            ->get();

        $detalle_pieza[] = ['orden_respuestos_id', 'pieza_respuestos_id', 'spare_parts_code', 'part_name', 'precio_neto', 'cantidad', 'moneda', 'precio_fob'];
        foreach ($pedidosDetalle as $detalle) {
            $detalle_pieza[] = [
                'orden_respuestos_id' => $detalle->orden_respuestos_id,
                'pieza_respuestos_id' => $detalle->pieza_respuestos_id,
                'spare_parts_code' => $detalle->pieza->spare_parts_code,
                'part_name' => $detalle->pieza->part_name,
                'precio_neto' => $detalle->precio_neto,
                'cantidad' => $detalle->cantidad,
                'moneda' => $detalle->pieza->moneda,
                'precio_fob' => $detalle->pieza->precio_fob
            ];
        }



        return $detalle_pieza;
    }

    public function getPiezasConPedidos()
    {
        $piezas = PiezaRespuestosOnsite::with('precio')
            ->with('modelo_pieza')
            ->where('company_id', $this->userCompanyId)
            ->get();


        foreach ($piezas as $pieza) {
            $idPieza = $pieza->id;

            $pedidosDetalle = DetalleOrdenPedidoRespuestosOnsite::with('pieza')
                ->where('company_id', $this->userCompanyId)
                ->where('pieza_respuestos_id', $idPieza)
                ->get();

            /* ->whereHas('obra_onsite', function ($query) {
                $idUser = session()->get('idUser');
                $user = $this->userService->findUser($idUser);
                $clave_empresa = $user->empresas_onsite[0]->clave;

                $query->where('clave', $clave_empresa);
            }); */

            $cantidad = 0;
            if (count($pedidosDetalle) > 0) {
                foreach ($pedidosDetalle as $pedido) {
                    $cantidad = $pedido->cantidad + $cantidad;
                };
            };

            $detalle_pieza[] = [

                'pieza_respuestos_id' => intval($pieza->id),
                'spare_parts_code' => strval(" " . $pieza->spare_parts_code),
                'part_name' => $pieza->part_name,
                'moneda' => $pieza->moneda,
                'precio_fob_anterior' => count($pieza->precio) > 1  ? floatval($pieza->precio[1]->precio_fob) : floatval($pieza->precio_fob),
                'version_anterior' => count($pieza->precio) > 1  ? floatval($pieza->precio[1]->version) : 1,
                'precio_fob_actual' => count($pieza->precio) > 0  ? floatval($pieza->precio[0]->precio_fob) : floatval($pieza->precio_fob),
                'version_actual' => count($pieza->precio) > 0  ? floatval($pieza->precio[0]->version) : 1,
                'cantidad' => intval($cantidad)
            ];
        }

        usort($detalle_pieza, function ($a, $b) {
            return $b['cantidad'] <=> $a['cantidad'];
        });

        array_unshift($detalle_pieza, [
            'pieza_respuestos_id' => 'pieza_respuestos_id',
            'spare_parts_code' => 'spare_parts_code',
            'part_name' => 'part_name',
            'moneda' => 'moneda',
            'precio_fob_anterior' => 'precio_fob_anterior',
            'version_anterior' => 'version_anterior',
            'precio_fob_actual' => 'precio_fob_actual',
            'version_actual' => 'version_actual',
            'cantidad' => 'cant. acumulado',
        ]);




        return $detalle_pieza;
    }
}
