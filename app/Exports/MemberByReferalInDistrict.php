<?php

namespace App\Exports;

use App\User;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class MemberByReferalInDistrict implements FromCollection,  WithHeadings, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    use Exportable;

    public $district_id;
    public $user_id;
    
    public function __construct(int $district_id, int $user_id)
    {
        $this->district_id = $district_id;
        $this->user_id     = $user_id;
    }
     
    public function collection()
    {
        $user_id = $this->user_id;
        $district_id = $this->district_id;

        $userModel = new User();
        $members  = $userModel->getListMemberByDistrictId($district_id, $user_id);
        // untuk remove id dan photo pada array
        foreach($members as $val)
        {
            unset($val->id);
            unset($val->photo);
        }
        $result = collect($members);
        return $result;
    }

    public function headings(): array
    {
        return [
            'NAMA',
            'DESA',
            'KECAMATAN',
            'KABUPATEN / KOTA',
            'PPROVINSI',
            'ALAMAT',
            'RT',
            'RW',
            'TELEPON',
            'WHATSAPP',
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
