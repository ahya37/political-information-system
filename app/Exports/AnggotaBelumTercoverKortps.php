<?php

namespace App\Exports;

use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Support\Facades\DB;

class AnggotaBelumTercoverKortps implements FromCollection,  WithHeadings, WithEvents, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    use Exportable;
    protected $data;
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        $data = $this->data;

        $results = [];
        foreach ($data as $value) {
            $results[] = [
                'nik'  => "'".$value->nik ?? '',
                'name' => $value->name ?? '',
                'desa' => $value->desa ?? '',
                'rt' => $value->rt ?? '',
            ];
        }
        return collect($results);
    }

    public function headings(): array
    {
        return [
            'NIK',
            'NAMA',
            'DESA',
            'RT'
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class =>  function (AfterSheet $event){

                $event->sheet->getStyle('A1:D1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ]
                ]);
            }
        ] ;  
    }
}
