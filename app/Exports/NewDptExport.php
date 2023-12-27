<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;


class NewDptExport implements FromCollection, WithHeadings, WithEvents, ShouldAutoSize
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
                // 'no_kk' => $value->NO_KK,
                // 'no_nik' => $value->NIK,
                'nama_lengkap' => $value->NAMA_LGKP,
                'tmpt_lahir' => $value->TMPT_LHR,
                'tgl_lahir' => $value->TGL_LAHIR,
                'jns_kelamin' => $value->JENIS_KELAMIN,
                'alamat' => $value->ALAMAT,
                'rt' => $value->NO_RT == 0 ? '0' :  $value->NO_RT,
                'rw' => $value->NO_RW == 0 ? '0' : $value->NO_RW,
                'tps' => $value->NOMOR_TPS == 0 ? '0' : $value->NOMOR_TPS 
            ];

        }

        $result = collect($results);
        return $result;
    }

    public function headings(): array
    {
        return [
            'NO',
            'NAMA',
            'TEMPAT LAHIR',
            'TGL LAHIR',
            'JENIS KELAMIN',
            'ALAMAT',
            'RT',
            'RW',
            'TPS'
            
        ];
    }

    public function registerEvents(): array
    {
        return [

            AfterSheet::class => function (AfterSheet $event) {

                $event->sheet->getStyle('A1:K1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ]
                ]);
            }

        ];
    }
}
