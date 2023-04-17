<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MemberPotensialUpperByDistrictUpper implements FromCollection, WithHeadings, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */

    protected $upper;

    public function __construct(int $upper){

        $this->upper = $upper;

    }

    public function collection()
    {
        #GET REFERAL KECAMATAN YANG MEMILIKI REFERAL DIATAS 25, BERAPA ANGGOTA YANG MEMILIKI REFERAL DIATAS 25
        $upper =  $this->upper;

        $kecamatan = DB::select("SELECT  d.id, d.name FROM users as a
                                    join users as b on a.id = b.user_id
                                    join villages as c on a.village_id = c.id 
                                    join districts as d on c.district_id = d.id 
                                    join regencies as e on d.regency_id = e.id
                                    join provinces as f on e.province_id = f.id
                                    group by d.id, d.name
                                    order by COUNT(a.user_id) desc");
        $no = 1;
        $results = [];
        foreach ($kecamatan as $value) {
            # BERAPA ANGGOTA YANG MEMILIKI REFERAL DIATAS 25 PERKECAMATAN
            $sql = DB::select("SELECT a.id
                        FROM users as a
                        join users as b on a.id = b.user_id
                        join villages as c on a.village_id = c.id 
                        join districts as d on c.district_id = d.id 
                        join regencies as e on d.regency_id = e.id
                        join provinces as f on e.province_id = f.id
                        where d.id =  $value->id
                        group by a.id having COUNT(a.user_id) >= $upper");
                        
            $count = count($sql);

            
            if ($count > 0) {
                $results[] = [
                    'no' => $no++,
                    'id' => $value->id,
                    'name' => $value->name,
                    'jml_referal_upper' => $count,
                    'desc' => ''
                ]; 
            }
        }

        $sort = array_column($results, 'jml_referal_upper');
        array_multisort($sort, SORT_DESC, $results);

        return collect($results);
    }

    public function headings(): array
    {
        return [
            'NO',
            'ID',
            'KECAMATAN',
            'JUMLAH PEMILIK REFERAL',
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

                $event->sheet->getStyle('A1:D1')->applyFromArray([
                    'font' => [
                        'bold' => true
                    ]
                ]);

                $datas = $this->collection();
                $sum_referal = collect($datas)->sum(function($q){
                    return $q['jml_referal_upper'];
                });

                $event->sheet->appendRows(array(
                    array('','TOTAL PEMILIK REFERAL',$sum_referal),
                ), $event);

            }
            
        ];
    }
}
