<?php

namespace App\Exports;

use App\OrgDiagram;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class DptBelumTerdaftarExport implements FromCollection,  WithHeadings, WithEvents, ShouldAutoSize,WithTitle
{
    use Exportable;

    private $KD_KEL;
    private $NO_RT;

    public function __construct($KD_KEL,$NO_RT)
    {
        $this->KD_KEL = $KD_KEL;
        $this->NO_RT  = $NO_RT;

    }

    public function collection()
    {
        $orgDiagram = new OrgDiagram();
        $data = $orgDiagram->getDptBelumTerdaftarByRtAndVillage($this->KD_KEL, $this->NO_RT);

        $results = [];
        $no      = 1;

        foreach ($data as $value) {
            $results[] = [
                'no' => $no++,
                'name' => $value->NAMA_LGKP,
                'rt'   => $value->NO_RT == 0 ? '0' : $value->NO_RT,
                'rw'   => $value->NO_RW == 0 ? '0' :  $value->NO_RW,
                'tps'  => $value->NOMOR_TPS == 0 ? '0' : $value->NOMOR_TPS,
                'address' => $value->ALAMAT,
                'village' => $value->NAMA_KEL
            ];
        }

        $results = collect($results);
        return $results;
    }

    public function headings(): array
    {
        return [
            'NO',
            'NAMA',
            'RT',
            'RW',
            'TPS',
            'ALAMAT',
            'DESA',
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
                $event->sheet->getDelegate()->getColumnDimension('F')->setAutoSize(true);
                $event->sheet->getDelegate()->getColumnDimension('G')->setAutoSize(true);
              

                $event->sheet->getStyle('A1:G1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ]
                ]);
            }
        ] ;  
    }

    public function title(): string
    {
        $title = 'RT.'. $this->NO_RT;
        return $title;
    }
}
