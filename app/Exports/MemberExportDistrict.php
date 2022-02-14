<?php

namespace App\Exports;

use App\User;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class MemberExportDistrict implements FromCollection, WithHeadings, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    private $district;
    
    public function __construct(int $district)
    {
        $this->district = $district;
    }

    public function collection()
    {
         $district_id =  $this->district;
        $sql = "SELECT a.id, a.user_id, a.cby, a.name, a.address,a.rt, a.rw, b.name as village, c.name as district,d.name as regency, f.name as province, a.created_at, a.phone_number, a.whatsapp
                from users as a
                join villages as b on a.village_id = b.id
                join districts as c on b.district_id = c.id
                join regencies as d on c.regency_id = d.id
                join provinces as f on d.province_id = f.id
               where b.district_id = $district_id
                order by b.name, a.name asc";
        $result = DB::select($sql);
        $data = [];
        $no = 1;
        foreach ($result as $val) {
             $userModel = new User();
                $total_referal = $userModel->where('user_id', $val->id)->whereNotNull('village_id')->count();
                $inputer = $userModel->select('name')->where('id', $val->cby)->first();
                $referal = $userModel->select('name')->where('id', $val->user_id)->first();
                $by_inputer = $inputer->name;
                $by_referal = $referal->name;      
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
                    'phone_number'    => $val->phone_number,
                    'whatsapp' => $val->whatsapp,
                    'created_at' => date('d-m-Y', strtotime($val->created_at)),
                    'by_inputer' => $by_inputer,
                    'by_referal' => $by_referal,
                    'total_referal' => $total_referal == 0 ? '0' : $total_referal,
                ];
        }

        $dataResult = collect($data);
        
        return $dataResult;

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
