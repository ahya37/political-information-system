<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class MemberExportVillage implements FromCollection, WithHeadings, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */

    private $village;
    public function __construct(int $village)
    {
        $this->village = $village;
    }

    public function collection()
    {
        $village_id = $this->village;
        $sql = "SELECT a.name, a.address,a.rt, a.rw, b.name as village, c.name as district,d.name as regency, a.phone_number, a.whatsapp,
                e.code as referal_code
                from users as a
                join villages as b on a.village_id = b.id
                join districts as c on b.district_id = c.id
                join users as e on a.user_id = e.id
                join regencies as d on c.regency_id = d.id
                where b.id = $village_id 
                order by b.name";
        $result = collect(\ DB::select($sql));
        return $result;
    }

    public function headings(): array
    {
        return [
            'NAMA',
            'ALAMAT',
            'RT',
            'RW',
            'DESA',
            'KECAMATAN',
            'KABUPATEN / KOTA',
            'TELEPON',
            'WHATSAPP',
            'REFERAL'
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getStyle('A1:J1')->applyFromArray([
                    'font' => [
                        'bold' => true
                    ]
                ]);
            }
        ];
    }
}
