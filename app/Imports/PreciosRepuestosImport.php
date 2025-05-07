<?php

namespace App\Imports;

use App\Models\Respuestos\PrecioPiezaRepuestoOnsite;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PreciosRepuestosImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new PrecioPiezaRepuestoOnsite([       
            'company_id' => $row['company_id'],
            'piezas_respuestos_onsite_id' => $row['piezas_respuestos_onsite_id'],   
            'spare_parts_code' => $row['spare_parts_code'],
            
            
            'precio_fob' => $row['precio_fob'],
            'version' =>$row['version'],
            'vencimiento_precio' => $row['vencimiento_precio'],


            
        ]);
    }
}
