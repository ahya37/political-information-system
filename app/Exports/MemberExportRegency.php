<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class MemberExportRegency implements FromCollection, WithHeadings, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    private $regency;
    
    public function __construct(int $regency)
    {
        $this->regency = $regency;
    }
    public function collection()
    {
        $regency_id =  $this->regency;
        $sql = "SELECT a.name,c.name as district,d.name as regency, b.name as village, a.address, a.rt, a.rw, a.phone_number, a.whatsapp,
                e.code as referal_code
                from users as a
                join villages as b on a.village_id = b.id
                join districts as c on b.district_id = c.id
                join users as e on a.user_id = e.id
                join regencies as d on c.regency_id = d.id
                where c.regency_id = $regency_id and not a.level = 1
                order by c.name";
        $result = collect(\DB::select($sql));
        return $result;
    }

    public function headings(): array
    {
        return [
            'Nama',
            'Kecamatan',
            'Kabupaten / Kota',
            'Desa',
            'Alamat',
            'RT',
            'RW',
            'Telpon',
            'Whatsapp',
            'Referal'
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
