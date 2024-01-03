<?php

namespace App\Exports;

use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SaksiExport implements FromCollection, WithHeadings, WithEvents, ShouldAutoSize
{
    use Exportable;

    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        $data = $this->data;

        $results = [];
        $no      = 1;
        foreach ($data as $value) {
            $results[] = [
                'no' => $no++,
                'nik' => "'$value->nik",
                'name' => $value->name,
                'address' => $value->address,
                'no_hp' => $value->phone_number ?? $value->whatsapp,
                'tps' => $value->tps_number
            ];
        }

        return collect($results);

    }

    public function headings(): array
    {
        return [
            'NO',
            'NIK',
            'NAMA',
            'ALAMAT',
            'NO HP',
            'TPS'
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class =>  function (AfterSheet $event){

                $event->sheet->getDelegate()->getColumnDimension('A')->setAutoSize(true);
                $event->sheet->getDelegate()->getColumnDimension('B')->setAutoSize(true);
                $event->sheet->getDelegate()->getColumnDimension('C')->setAutoSize(true);
                $event->sheet->getDelegate()->getColumnDimension('D')->setAutoSize(true);
                $event->sheet->getDelegate()->getColumnDimension('E')->setAutoSize(true);
              
                $event->sheet->getStyle('A1:E1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ]
                ]);
            }
        ] ;  
    }
}
