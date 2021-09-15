<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class MemberExportProvince implements FromCollection, WithHeadings, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    private $province;

    use Exportable;
    
    public function __construct(int $province)
    {
        $this->province = $province;
    }
    
    public function collection()
    {
        $province_id = $this->province;
        $sql = "SELECT a.name, d.name as regency, c.name as district, b.name as village, a.address, a.rt, a.rw, a.phone_number, a.whatsapp,
                e.code as referal_code
                from users as a
                join villages as b on a.village_id = b.id
                join districts as c on b.district_id = c.id
                join regencies as d on c.regency_id = d.id
                join users as e on a.user_id = e.id
                where d.province_id = $province_id and not a.level = 1
                order by d.name";
        $result = collect(\DB::select($sql));
        return $result;
    }

    public function headings(): array
    {
        return [
            'Nama',
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
                $event->sheet->getStyle('A1:J1')->applyFromArray([
                    'font' => [
                        'bold' => true
                    ]
                ]);
            }
        ];
    }

}
