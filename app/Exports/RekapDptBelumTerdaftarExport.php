<?php

namespace App\Exports;

use App\OrgDiagram;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithTitle;

class RekapDptBelumTerdaftarExport implements FromCollection, WithHeadings, WithEvents, ShouldAutoSize,WithStartRow,WithTitle
{
    protected $villageId;

    public function __construct(int $villageId)
    {
        $this->villageId = $villageId;
    }
    
    public function collection()
    {
        $orgDiagram = new OrgDiagram();
        $data       = $orgDiagram->getDptBelumTerdaftarGroupByRt($this->villageId);

        // maping untuk get data terdaftar / tidak dari dpt per rt
        $results = [];
        foreach ($data as  $value) {
            $count_unregistered = $orgDiagram->getDptBelumTerdaftarByRtAndVillage($value->KD_KEL, $value->NO_RT);
            $count_registered = $orgDiagram->getDptTerdaftarByRtAndVillage($value->KD_KEL, $value->NO_RT);
            $results[] = [
                'rt'        => $value->NO_RT == 0 ? '0' : $value->NO_RT,
                'registered' => count($count_registered),
                'unregistered' => count($count_unregistered)
            ];
        }

        $results = collect($results);
        return $results;
    }

    public function headings(): array
    {
        return [
            'RT',
            'TERDAFTAR',
            'BELUM TERDAFTAR'
        ];
    }

    public function startRow(): int
    {
        // Specify the row number to start from (e.g., 3 to skip the first two rows)
        return 3;
    }

    public function registerEvents(): array
    {
        
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getStyle('A1:C1')->applyFromArray([
                    'font' => [
                        'bold' => true
                    ]
                ]);

                $data = $this->collection();

                $count_registered   = collect($data)->sum(function($q){
                    return $q['registered'];
                });

                $count_unregistered = collect($data)->sum(function($q){
                    return $q['unregistered'];
                });
                
                $event->sheet->appendRows(array(
                    array('JUMLAH',$count_registered,$count_unregistered)
                ), $event);

            }
        ];
    }

    public function title(): string
    {
        $title = 'REKAP';
        return $title;
    }
}
