<?php

namespace App\Exports;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class GenericExport implements FromCollection, WithHeadings, ShouldAutoSize, WithColumnFormatting
{
    private $data;
    private $fileName; 
    private $format; 

    public function __construct(array $data, $fileName, array $format = [])
    {
        $this->data = $data;
        $this->fileName = $fileName;
        $this->format = $format;
    }

    public function collection()
    {
        return collect($this->data);
    }

    public function headings(): array
    {
        return array_keys($this->data[0]);
    }
 
    public function columnFormats(): array
    {
        return $this->format;
    }    

    public function export()
    {
        return Excel::store(new GenericExport($this->data, $this->fileName, $this->format), $this->fileName, 'exports');
    }
}
