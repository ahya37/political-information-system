<?php

namespace App\Exports;

use App\User;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class MemberPotentialReferal implements FromCollection,WithHeadings, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
     use Exportable;

    public function collection()
    {
         $sql = "SELECT a.id, a.name, COUNT(case when b.id != b.user_id then a.user_id end) as total,
                c.name as village, d.name as district, e.name as regency, f.name as province, a.phone_number, a.whatsapp
                FROM users as a
                join users as b on a.id = b.user_id
                join villages as c on a.village_id = c.id 
                join districts as d on c.district_id = d.id 
                join regencies as e on d.regency_id = e.id
                join provinces as f on e.province_id = f.id
                group by a.id, c.name, a.name, e.name, d.name, a.photo, a.phone_number, a.whatsapp, f.name
                order by COUNT(a.user_id) desc";
        $result = DB::select($sql);

        $data = [];
        foreach ($result as $val) {
            $userModel = new User();
            $id_user = $val->id;
            $referal_undirect = $userModel->getReferalUnDirect($id_user);
            $total_referal_undirect = $referal_undirect->total == NULL ? '0' : $referal_undirect->total;
            
            $data[] = [
                'name' => $val->name,
                'referal' => $val->total,
                'referal_undirect' => $total_referal_undirect,
                'village' => $val->village,
                'district' => $val->district,
                'regency' => $val->regency,
                'province' => $val->province,
                'phone_number' => $val->phone_number,
                'whatsapp' => $val->whatsapp,
            ];
        }

        return collect($data);
    }

    public function headings(): array
    {
        return [
            'NAMA',
            'REFERAL',
            'REFERAL TIDAK LANGSUNG',
            'DESA',
            'KECAMATAN',
            'KABUPATEN / KOTA',
            'PROVINSI',
            'TELEPON',
            'WHATSAPP',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getStyle('A1:I1')->applyFromArray([
                    'font' => [
                        'bold' => true
                    ]
                ]);
            }
        ];
    }
}
