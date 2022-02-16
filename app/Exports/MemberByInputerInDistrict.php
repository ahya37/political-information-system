<?php

namespace App\Exports;

use App\User;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class MemberByInputerInDistrict implements FromCollection,  WithHeadings, WithEvents
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
        $members  = $userModel->getListMemberByInputerDistrictId($district_id, $user_id);
        // untuk remove id dan photo pada array
        $data = [];
        $no = 1;
        foreach($members as $val)
        {
            unset($val->id);
            unset($val->photo);
            $data[] = [
                'no' => $no++,
                'name' => $val->name,
                'address' => $val->address,
                'rt' => $val->rt,
                'rw' => $val->rw,
                'village' => $val->village,
                'district' => $val->district,
                'regency' => $val->regency,
                'province' => $val->province,
                'phone_number' => $val->phone_number,
                'whatsapp' => $val->whatsapp,
                'created_at' => date('d-m-Y', strtotime($val->created_at)),
                'inputer' => $val->inputer,
                'referal' => $val->referal,
                'total_referal' => $val->total_referal == 0 ? '0' : $val->total_referal,
            ];
        }
        $result = collect($data);
        return $result;
    }

    public function headings(): array
    {
        return [
            'NO',
            'NAMA',
            'ALAMAT',
            'RT',
            'RW',
            'DESA',
            'KECAMATAN',
            'KABUPATEN / KOTA',
            'PPROVINSI',
            'TELEPON',
            'WHATSAPP',
            'TERDAFTAR',
            'INPUT DARI',
            'REFERAL',
            'JUMLAH REFERAL',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getStyle('A1:O1')->applyFromArray([
                    'font' => [
                        'bold' => true
                    ]
                ]);
            }
        ];
    }
}
