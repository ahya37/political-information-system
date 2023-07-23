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
                                ->select('a.village_id','a.rt', DB::raw('count(a.id) as jml_korte'),
									// joinkan dengan user by nik = nik untuk menghitung hanya data yang tersedia sebagai anggota
                                    DB::raw("(
												select count(tb1.id) from org_diagram_rt as tb1
												join users as tb2 on tb1.nik = tb2.nik
												where tb1.village_id = a.village_id and tb1.rt = a.rt and tb1.base = 'ANGGOTA' 
												group by tb1.rt
											 ) as jml_members"
											)
										)
								->join('users as b','a.nik','=','b.nik')
                                ->where('a.base','KORRT')
                                ->where('a.village_id', $this->villageid)
                                ->groupBy('a.village_id','a.rt')
                                ->orderBy('a.rt','asc')
                                ->get();
                    // count kalkulasi anggota berdasarkan desa dan rt tersebut

                    foreach ($kortes as $korte) {
                        $event->sheet->appendRows(array(
                            array('','KORTE RT '.$korte->rt,"$korte->jml_korte orang / anggota = $korte->jml_members orang"),
                        ), $event);
                    }
					
				
				$event->sheet->appendRows(array(
                    array(' ',' '),
                ), $event);
				
				 $event->sheet->appendRows(array(
                    array('','CATATAN')
                ), $event);
				
				$catatanKortes = DB::table('org_diagram_rt as a')
                                ->select('a.rt','a.village_id')
								->join('users as b','a.nik','=','b.nik')
                                ->where('a.base','KORRT')
                                ->where('a.village_id', $this->villageid)
                                ->groupBy('a.rt','a.village_id')
                                ->orderBy('a.rt','asc')
                                ->get();
								
               
				$resultsRt = [];
				foreach($catatanKortes as $ckorte){
					
					// count kalkulasi anggota berdasarkan desa dan rt tersebut
					$countMembers = DB::table('users')->where('rt', $ckorte->rt)->where('village_id', $this->villageid)->count();
					$countKorte   = DB::table('org_diagram_rt as a')
									->join('users as b','a.nik','=','b.nik')
									->where('a.village_id',$this->villageid)
									->where('a.rt', $ckorte->rt)
									->where('a.base','KORRT')
									->where('b.nik','!=',null)
									->count();
									
									
					$resultsRt[] = [
						'rt' => $ckorte->rt,
						'jml_member' => $countMembers,
						'jml_korte_per_village' => $countKorte
					];
				}
				
				 /**
                 * Keterangan
                 * RT 1 :
                 *  - jumlah anggota = 100
                 *  - jumlah korte   = 2
                 *  - keterangan     = kekurangan 2 korte
                 */

                foreach($resultsRt as $ckorte) {
					
						// hitung kurang korte
						/**
						  max anggota = 25
						  kekurangan_korte = jml_members / max anggota
						*/
						$max_anggota      = 25;
						$kekurangan_korte = ceil($ckorte['jml_member'] / $max_anggota);
						$kekurangan_korte = $kekurangan_korte - $ckorte['jml_korte_per_village'];
						
                        $event->sheet->appendRows(array(
                            array('','RT '.$ckorte['rt']),
                            array('','Jumlah Anggota',$ckorte['jml_member']),
                            array('','Jumlah Korte',$ckorte['jml_korte_per_village']),
                            array('','Keterangan',"Kurang korte $kekurangan_korte"),
                            array(' ',' '),
                        ), $event);
					}
					
				 
				$event->sheet->appendRows(array(
                    array(' ',' '),
                ), $event);
				
				$event->sheet->appendRows(array(
                    array('','BELUM ADA KORTE')
                ), $event); 
				
				 /**
					Belum ada korte
					get rt di tb org_diagram_rt by villageid
					
                 */
				 // get rt di tb org_diagram_rt by villageid
				
				 // get rt mana saja yang belum ada korte nya
				 
				 // ===================
				// $korteIsNotYets = [];
				$korteIsNotYets = DB::table('users as a')
								->select('a.rt', 
									DB::raw("(SELECT COUNT(*) from org_diagram_rt where rt = a.rt and village_id = $this->villageid GROUP by rt) as total_korte"),
									DB::raw("(SELECT COUNT(*) from users WHERE rt = a.rt and village_id = 3602011001) as total_member")
									)
								->where('a.village_id', $this->villageid)
								->where('a.rt', '!=',0)
								->groupBy('a.rt')
								->get();
				foreach($korteIsNotYets as $korteIsNotYet){
					
					// hitung jumlah korte yang dibutuhkan
					$korte_needed = ceil($korteIsNotYet->total_member / 25);
					
					if($korteIsNotYet->total_korte == null){
							$event->sheet->appendRows(array(
							array('','RT '.$korteIsNotYet->rt, "Jumlah anggota = $korteIsNotYet->total_member (dibutuhkan $korte_needed korte)")
					), $event);
					}
					
				}
				
            }
        ];
    }
}
