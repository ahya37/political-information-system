<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Support\Facades\DB;

class AnggotaFormKortpsExport implements FromCollection,  WithHeadings, WithEvents, ShouldAutoSize
{

    use Exportable;

    protected $idx;

    public function __construct(string $idx){

        $this->idx = $idx;


    }
    public function collection()
    {
        $members = DB::table('anggota_koordinator_tps_korte')
                ->select('name','nik')
                ->where('pidx_korte', $this->idx)
                ->get();

        $results = [];
        $no = 1;

        foreach ($members as $value) {
            $results[] = [
                'no' => $no++,
                'nik' => "'$value->nik",
                'name' => $value->name,
            ];    
        }

        $data = collect($results);
        return $data;
        
    }

    public function headings(): array
    {
        return [
            'NO',
            'NIK',
            'NAMA',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class =>  function (AfterSheet $event){

                $event->sheet->getStyle('A1:C1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ]
                ]);
            }
        ] ;  
    }
}
