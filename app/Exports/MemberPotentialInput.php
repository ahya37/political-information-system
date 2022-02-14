<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\User;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MemberPotentialInput implements FromCollection, WithHeadings, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    use Exportable;

    public function collection()
    {
         $sql = "SELECT a.id, a.cby, a.user_id, a.created_at, a.rt, a.rw, a.address, a.name, COUNT(a.user_id) as total,
                c.name as village, d.name as district, e.name as regency, f.name as province, a.phone_number, a.whatsapp
                FROM users as a
                join users as b on a.id = b.cby
                join villages as c on a.village_id = c.id 
                join districts as d on c.district_id = d.id 
                join regencies as e on d.regency_id = e.id
                join provinces as f on e.province_id = f.id
                group by a.id, c.name, a.name, e.name, d.name, a.photo, a.phone_number, a.whatsapp, f.name, a.rt, a.rw, a.cby, a.user_id, a.created_at, a.address
                order by COUNT(a.user_id) desc";
        $result = DB::select($sql);

        $data = [];
        $no = 1;
        foreach ($result as $val) {
            $userModel = new User();
            $inputer = $userModel->select('name')->where('id', $val->cby)->first();
            $referal = $userModel->select('name')->where('id', $val->user_id)->first();
            $by_inputer = $inputer->name;
            $by_referal = $referal->name;            
            $data[] = [
                'no' => $no++,
                'name' => $val->name,
                'total' => $val->total,
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
                'by_inputer' => $by_inputer,
                'by_referal' => $by_referal,
            ];
        }

        return collect($data);
    }

    public function headings(): array
    {
        return [
            'NO',
            'NAMA',
            'JUMLAH',
            'ALAMAT',
            'RT',
            'RW',
            'DESA',
            'KECAMATAN',
            'KABUPATEN / KOTA',
            'PROVINSI',
            'TELEPON',
            'WHATSAPP',
            'TERDAFTAR',
            'INPUT DARI',
            'REFERAL',
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
