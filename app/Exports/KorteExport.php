<?php

namespace App\Exports;

use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Support\Facades\DB;

class KorteExport implements FromCollection,  WithHeadings, WithEvents, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    use Exportable;

    protected $villageid;

    public function __construct(int $village)
    {
        $this->villageid = $village;
    }

    public function collection()
    {
        $village_id  =  $this->villageid;



        $data    = DB::table('org_diagram_rt as a')
                    ->select('b.id','a.name','a.base','a.title','a.rt','b.gender','c.name as village','d.name as district','a.telp','idx')
                    ->join('users as b','a.nik','=','b.nik')
                    ->join('villages as c','a.village_id','=','c.id')
                    ->join('districts as d','a.district_id','=','d.id')
                    ->where('a.village_id', $village_id)
                    ->whereNotNull('a.nik')
                    ->where('a.base','KORRT')
                    ->orderBy('a.rt','asc')
                    ->get();

        // $data    = $village->merge($rt); #merge kedua array

        
        $results = [];
        $no      = 1;
        foreach ($data as $value) {

            // #cek jika sudah menjadi anggota memiliki referal diatas 25
            // $member = DB::table('users')->where('user_id', $value->id)->count();

            // $desc = '';
            // if ($member >= 25) $desc = 'ANGGOTA POTENSIAL REFERAL'; 
            $count_members = DB::table('org_diagram_rt')->where('pidx', $value->idx)->where('base','ANGGOTA')->count();

            $results[] = [
                'no' => $no++,
                'name' => $value->name,
                'jk' => $value->gender == 1 ? 'P' : 'L',
                'rt' => $value->rt,
                'title' => 'KORTE RT '.$value->rt,
                'telp' => $value->telp,
                'count_members' => $count_members,
                'village' => $value->village,
                'district' => $value->district,
                'desc' => ""
            ];
        }

        $result = collect($results);
        return $result;
    }

    public function headings(): array
    {
        return [
            'NO',
            'NAMA',
            'JENIS KELAMIN',
            'RT',
            'JABATAN',
            'NO.HP',
            'JUMLAH ANGGOTA',
            'DESA',
            'KECAMATAN',
            'KETERANGAN'
        ];
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                
                // $event->sheet->getDelegate()->getRowDimension('2')->setRowHeight(40);
                $event->sheet->getDelegate()->getColumnDimension('A')->setAutoSize(true);
                $event->sheet->getDelegate()->getColumnDimension('B')->setAutoSize(true);
                $event->sheet->getDelegate()->getColumnDimension('C')->setAutoSize(true);
                $event->sheet->getDelegate()->getColumnDimension('D')->setAutoSize(true);
                $event->sheet->getDelegate()->getColumnDimension('E')->setAutoSize(true);
                $event->sheet->getDelegate()->getColumnDimension('F')->setAutoSize(true);
                $event->sheet->getDelegate()->getColumnDimension('G')->setAutoSize(true);
                $event->sheet->getDelegate()->getColumnDimension('H')->setAutoSize(true);
                $event->sheet->getDelegate()->getColumnDimension('I')->setAutoSize(true);
                $event->sheet->getDelegate()->getColumnDimension('J')->setAutoSize(true);

                $event->sheet->getStyle('A1:H1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ]
                ]);

                $data = $this->collection()->where('jk','L');
                $total_L = collect($data)->count();

                $event->sheet->appendRows(array(
                    array('','JUMLAH LAKI','',$total_L),
                ), $event);
                
                $data2 = $this->collection()->where('jk','P');
                $total_P = collect($data2)->count();

                $event->sheet->appendRows(array(
                    array('','JUMLAH PEREMPUAN','',$total_P),
                ), $event);

                $event->sheet->appendRows(array(
                    array(' ',' '),
                ), $event);
                $event->sheet->appendRows(array(
                    array('','RINCIAN'),
                ), $event);

                // Buat jumlah Korte RT (nomor RT) : (jumlah korte) orang/ anggota = jumlah anggota dari semua korte yg ada di RT tersebut
                    // get data korte berdasarkan desa
					
					 // join kan dengan user untuk get nama
                    $kortes = DB::table('org_diagram_rt as a')
                                ->select('a.village_id','rt', DB::raw('count(a.id) as jml_korte'),
									// joinkan dengan user by nik = nik untuk menghitung hanya data yang tersedia sebagai anggota
                                    DB::raw("(
												select count(tb1.id) from org_diagram_rt as tb1
												left join users as tb2 on tb1.nik = tb2.nik
												where tb1.village_id = a.village_id and tb1.rt = a.rt and tb1.base = 'ANGGOTA' 
												group by tb1.rt
											 ) as jml_members"
											)
										)
                                ->where('base','KORRT')
                                ->where('village_id', $this->villageid)
                                ->groupBy('a.village_id','a.rt')
                                ->orderBy('a.rt','asc')
                                ->get();

                    // count kalkulasi anggota berdasarkan desa dan rt tersebut

                    foreach ($kortes as $korte) {
                        $event->sheet->appendRows(array(
                            array('','KORTE RT '.$korte->rt,"$korte->jml_korte orang / anggota",'=', "$korte->jml_members orang"),
                        ), $event);
                    }

                /**
                 * Keterangan
                 * RT 1 :
                 *  - jumlah anggota = 100
                 *  - jumlah korte   = 2
                 *  - keterangan     = kekurangan 2 korte
                 */
                    
            }
        ];
    }
}
