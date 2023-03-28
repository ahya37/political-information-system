<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\User;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MemberPotensialReferalByDistrict implements FromCollection, WithHeadings, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */

    protected $district;

    public function __construct(int $district){

        $this->district = $district;

    }

    public function collection()
    {
        $district = $this->district;

         $sql = "SELECT a.id, a.nik, a.cby, a.user_id, a.created_at, a.rt, a.rw, a.address, a.name, COUNT(case when b.id != b.user_id then a.user_id end) as total,
                c.name as village, d.name as district, e.name as regency, f.name as province, a.phone_number, a.whatsapp, a.gender
                FROM users as a
                join users as b on a.id = b.user_id
                join villages as c on a.village_id = c.id 
                join districts as d on c.district_id = d.id 
                join regencies as e on d.regency_id = e.id
                join provinces as f on e.province_id = f.id
                where d.id =  $district
                group by a.nik, a.gender, a.id, c.name, a.name, e.name, d.name, a.photo, a.phone_number, a.whatsapp, f.name, a.rt, a.rw, a.cby, a.user_id, a.created_at, a.address
                order by COUNT(a.user_id) desc";
        $result = DB::select($sql);

        $no = 1;
        $data = [];
        foreach ($result as $val) {
            if ($val->total >= 25) {
                $userModel = new User();
                $id_user = $val->id;
                $referal_undirect = $userModel->getReferalUnDirect($id_user);
                $total_referal_undirect = $referal_undirect->total == NULL ? '0' : $referal_undirect->total;
                $inputer = $userModel->select('name')->where('id', $val->cby)->first();
                $referal = $userModel->select('name')->where('id', $val->user_id)->first();
                $by_inputer = $inputer->name;
                $by_referal = $referal->name;

                #cek apakah namanya ada di struktur tim korte / kordes / korcam / kordapi / korpus
                $status = '';
                $korte       = DB::table('org_diagram_rt')->where('nik', $val->nik)->count();
                $korvillage  = DB::table('org_diagram_village')->where('nik', $val->nik)->count();
                $kordistrict = DB::table('org_diagram_district')->where('nik', $val->nik)->count();
                $korpus      = DB::table('org_diagram_pusat')->where('nik', $val->nik)->count();

                if ($korte > 0) $status = 'TIM KORRT';
                if ($korvillage > 0) $status = 'TIM KORDES';
                if ($kordistrict > 0) $status = 'TIM KORCAM';
                if ($korpus > 0) $status = 'TIM KORPUSAT';
                
                $data[] = [
                    'no' => $no++,
                    'name' => $val->name,
                    'jk' => $val->gender == 0 ? 'L' : 'P',
                    'referal' => $val->total,
                    'address' => $val->address,
                    'rt' => $val->rt,
                    'rw' => $val->rw,
                    'village' => $val->village,
                    'status' => $status,
    
                ];
            }
        }

        return collect($data);
    }

    public function headings(): array
    {
        return [
            'NO',
            'NAMA',
            'JENIS KELAMIN',
            'REFERAL',
            'ALAMAT',
            'RT',
            'RW',
            'DESA',
            'STATUS',
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

                $data = $this->collection()->where('jk','L');
                $total_L = collect($data)->count();

                $event->sheet->appendRows(array(
                    array('','JUMLAH LAKI',$total_L),
                ), $event);
                
                $data2 = $this->collection()->where('jk','P');
                $total_P = collect($data2)->count();

                $event->sheet->appendRows(array(
                    array('','JUMLAH PEREMPUAN',$total_P),
                ), $event);

            }
            
        ];
    }
}
