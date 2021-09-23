<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;


class MemberExportNational implements FromCollection, WithHeadings, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    use Exportable;

    public function collection()
    {
        
        $sql = "SELECT a.name, e.name as province, d.name as regency, c.name as district, b.name as village, a.address, a.rt, a.rw, a.phone_number, a.whatsapp,
                f.code as referal_code
                from users as a
                join villages as b on a.village_id = b.id
                join districts as c on b.district_id = c.id
                join regencies as d on c.regency_id = d.id
                join provinces as e on d.province_id  = e.id 
                join users as f on a.user_id = f.id
                where not a.level = 1
                order by e.name ASC ";
        $result = collect(\ DB::select($sql));
        return $result;
    }

    public function headings(): array
    {
        return [
            'Nama',
            'Provinsi',
            'Kabupaten / Kota',
            'Kecamatan',
            'Desa',
            'Alamat',
            'RT',
            'RW',
            'Telpon',
            'Whatsapp',
            'Reveral'
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getStyle('A1:K1')->applyFromArray([
                    'font' => [
                        'bold' => true
                    ]
                ]);
            }
        ];
    }
}
