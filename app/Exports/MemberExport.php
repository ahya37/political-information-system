<?php

namespace App\Exports;

use App\User;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class MemberExport implements FromCollection, WithHeadings, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
     use Exportable;

     private $data;
    
    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        $data = $this->data;
        
         $result = [];
            foreach($data as $val){
                $total_referal = User::where('user_id', $val->id)->whereNotNull('village_id')->count();
                $result[] = [
                    'name' => $val->name,
                    'regency' => $val->regency,
                    'district' => $val->district,
                    'village' => $val->village,
                    'referal' => $val->referal,
                    'cby' => $val->cby,
                    'created_at' => date('d-m-Y', strtotime($val->created_at)),
                    'total_referal' => $total_referal,
                ];
            }

        return collect($result);
    }

    public function headings(): array
    {
        return [
            'NAMA',
            'KABUPATEN/KOTA',
            'KECAMATAN',
            'DESA',
            'REFERAL DARI',
            'INPUT DARI',
            'TERDAFTAR',
            'JUMLAH REFERAL',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getStyle('A1:H1')->applyFromArray([
                    'font' => [
                        'bold' => true
                    ]
                ]);

                $data = $this->collection();
                $total = collect($data)->sum(function($q){
                    return $q['total_referal'];
                });

                // $event->sheet->appendRows(array(
                //     array(' ','','','','','','',''),
                // ), $event);
                // $event->sheet->appendRows(array(
                //     array('TOTAL REFERAL','','','','','','',$total),
                // ), $event);
            }
        ];
    }
}
