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
                where d.id =  $district and a.id != 35
                group by a.nik, a.gender, a.id, c.name, a.name, e.name, d.name, a.photo, a.phone_number, a.whatsapp, f.name, a.rt, a.rw, a.cby, a.user_id, a.created_at, a.address
                having COUNT(a.user_id) >= 25
                order by COUNT(a.user_id) desc";
        $result = DB::select($sql);

        // $tim_korte    = DB::table('org_diagram_rt')->select('')

        $no = 1;
        $data = [];
        foreach ($result as $val) {
                // $userModel = new User();
                // $id_user = $val->id;
                // $referal_undirect = $userModel->getReferalUnDirect($id_user);
                // $total_referal_undirect = $referal_undirect->total == NULL ? '0' : $referal_undirect->total;
                // $inputer = $userModel->select('name')->where('id', $val->cby)->first();
                // $referal = $userModel->select('name')->where('id', $val->user_id)->first();
                // $by_inputer = $inputer->name;
                // $by_referal = $referal->name;

                #cek apakah namanya ada di struktur tim korte / kordes / korcam / kordapi / korpus
                $status = '';
                $korte       = DB::table('org_diagram_rt as a')
                                ->join('villages as b','a.village_id','=','b.id')
                                ->select('b.name','a.rt')
                                ->where('a.nik', $val->nik)
                                ->first();

                $korvillage  = DB::table('org_diagram_village as a')
                                ->join('villages as b','a.village_id','=','b.id')
                                ->select('b.name')
                                ->where('a.nik', $val->nik)
                                ->first();

                $kordistrict = DB::table('org_diagram_district as a')
                                ->join('districts as b','a.district_id','=','b.id')
                                ->where('nik', $val->nik)
                                ->select('b.name')
                                ->first();
                
                $korpus      = DB::table('org_diagram_pusat')->where('nik', $val->nik)->count();

                if ($korte != null) $status = "TIM KORRT RT. $korte->rt DESA $korte->name";
                if ($korvillage != null) $status = 'TIM KORDES '.$korvillage->name;
                if ($kordistrict != null) $status = 'TIM KORCAM '.$kordistrict->name;
                if ($korpus > 0) $status = 'TIM KORPUSAT';

                #count jenis kelamin dari jumlah referal per nama
                $male   = DB::table('users')->select('gender')->where('gender',0)->where('user_id', $val->id)->count();
                $female = DB::table('users')->select('gender')->where('gender',1)->where('user_id', $val->id)->count();
                
                $data[] = [
                    'no' => $no++,
                    'name' => $val->name,
                    'jk' => $val->gender == 0 ? 'L' : 'P',
                    'district' => $val->district,
                    'referal' => $val->total,
                    // 'address' => $val->address,
                    // 'rt' => $val->rt,
                    // 'rw' => $val->rw,
                    // 'village' => $val->village,
                    'male'   => $male ?? 0,
                    'female'   => $female ?? 0,
                    'status' => $status,
    
                ];
            
        }

        return collect($data);
    }

    public function headings(): array
    {
        return [
            'NO',
            'NAMA',
            'JENIS KELAMIN',
            'KECAMATAN',
            'REFERAL',
            // 'ALAMAT',
            // 'RT',
            // 'RW',
            // 'DESA',
            'LAKI-LAKI',
            'PEREMPUAN',
            'KETERANGAN',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                // $event->sheet->row(1, function($row) { 
                //     $row->setBackground('#CCCCCC'); 
                // });
                // $event->sheet->mergeCells('F1:G1');

                $event->sheet->getStyle('A1:H1')->applyFromArray([
                    'font' => [
                        'bold' => true
                    ]
                ]);

                $data = $this->collection()->where('jk','L');
                $total_L = collect($data)->count();

                $dataMale = $this->collection();
                $total_male = collect($dataMale)->sum(function($q){
                    return $q['male'];
                });

                $dataFMale = $this->collection();
                $total_fmale = collect($dataFMale)->sum(function($q){
                    return $q['female'];
                });

                $event->sheet->appendRows(array(
                    array('','JUMLAH LAKI',$total_L),
                ), $event);
                
                $data2 = $this->collection()->where('jk','P');
                $total_P = collect($data2)->count();

                $event->sheet->appendRows(array(
                    array('','JUMLAH PEREMPUAN',$total_P),
                ), $event);

                $datas = $this->collection();
                $sum_referal = collect($datas)->sum(function($q){
                    return $q['referal'];
                });

                $event->sheet->appendRows(array(
                    array('','JUMLAH REFERAL','','',$sum_referal,$total_male,$total_fmale),
                ), $event);

            }
            
        ];
    }

}
