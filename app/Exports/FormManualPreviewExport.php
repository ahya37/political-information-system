<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Support\Facades\DB;

class FormManualPreviewExport implements FromCollection,  WithHeadings, WithEvents, ShouldAutoSize
{
    use Exportable;

    protected $idx;

    public function __construct(string $idx){

        $this->idx = $idx;

    }

    public function collection()
    {
         // download data form manual by korte
         $formManual = DB::table('tmp_form_anggota_manual_kortp as a')
                    ->select('a.nik','a.name',DB::raw('(select count(id) from users where nik = a.nik) as is_registered'))
                    ->where('a.pidx_korte', $this->idx)->get();

        $results = [];
        $no = 1;

        foreach ($formManual as $value) {
           $results[] = [
            'no' => $no++,
            'nik' => "'$value->nik",
            'name' => $value->name,
            'is_registered' => $value->is_registered == 1 ? 'TERDAFTAR' : 'BELUM'
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
            'KETERANGAN'
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
