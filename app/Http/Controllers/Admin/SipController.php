<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\OrgDiagram;
use DB;
use App\Providers\GlobalProvider;
use PDF;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Helpers\PdfToImage;
use Dompdf\Dompdf;
use Dompdf\Options;
use Spatie\PdfToImage\Pdf as Spatie;
use setasign\Fpdi\Fpdi;
use PDFMerger;
use App\Models\Regency;

class SipController extends Controller
{ 
    // dashbbord all level
    public function dashboard()
    {
		$regency_id      = 3602;
		$regency         = Regency::select('name')->where('id', $regency_id)->first();
		$orgDiagramModel = new OrgDiagram();
        $data            = $orgDiagramModel->getSipDataSipByRegency($regency_id); 
		
		$gf = new GlobalProvider();
		
		$results = []; 
		foreach($data as $val){
			
			$districts = $orgDiagramModel->getSipKecamatanByDapil($val->id); // get kecacamatan by dapil
			$result_districts = [];
			foreach($districts as $district){
				$villages = DB::table('villages')->select('id','name','peserta_kunjungan')->where('district_id', $district->id)->get();
				
				// kalkulasi anggota level desa
				$results_village = [];
				foreach($villages as $village){ 
					
					$list_kortps = $orgDiagramModel->getAnggotaTercoverByVillage($village->id);
				
					$jml_kordes  = $orgDiagramModel->getCountKordesByVillage($village->id); // hotung kordes by desa;
					
					$jml_korcam  = $orgDiagramModel->getCountKorcamByVillage($village->id);
					
					//jumlahkan anggota by desa di ambil dari per korte nya
					$jml_anggota_by_korte = collect($list_kortps)->sum(function($q){
						return $q->jml_anggota;
					}); 
					
					$jml_form_manual_by_korte = collect($list_kortps)->sum(function($q){
						return $q->form_manual; 
					});
					  
					$pelapis   = DB::table('anggota_pelapis')->select('id')->where('village_id', $village->id)->count(); 
				
					$jml_korte = count($list_kortps); 
					
					$results_village[] = [
						'id' => $village->id,
						'desa' => $village->name, 
						'jml_all_anggota_desa' => $jml_anggota_by_korte + $jml_form_manual_by_korte + $pelapis + $jml_korte + $jml_kordes + $jml_korcam	
					];
				}
				
				// kalkulasi anggota level kecamatan 
				$jml_all_anggota_kecamatan = collect($results_village)->sum(function($q){
					return $q['jml_all_anggota_desa'];
				});
				
				$result_districts[] = [
					'kecamatan' => $district->name,
					'jml_all_anggota_kecamatan' => $jml_all_anggota_kecamatan,
					'desa' => $results_village    
				];  
			}
			
			// kalkulasi level dapil 
			$jml_all_anggota_dapil = collect($result_districts)->sum(function($q){
					return $q['jml_all_anggota_kecamatan'];
			});
			
			$persentage_anggota = $gf->calculatePercentage($val->hasil_suara,$jml_all_anggota_dapil);
			if ($persentage_anggota !== null) {
				$persentage_anggota  = $gf->persen($persentage_anggota);  
			}
			
			$persentage_peserta_kunjungan = $gf->calculatePercentage($val->hasil_suara,$val->peserta_kunjungan);
			if ($persentage_peserta_kunjungan !== null) {
				$persentage_peserta_kunjungan  = $gf->persen($persentage_peserta_kunjungan);  
			}
				
			$results[] = [
				'id' => $val->id,
				'dapil' => $val->name,
				'tps' => $val->tps,
				// 'anggota' => $val->anggota_tercover_kortps + $val->form_manual + $val->pelapis + $val->korte + $val->kordes + $val->korcam,
				'jml_all_anggota_dapil' => $jml_all_anggota_dapil,
				'peserta_kunjungan' => $val->peserta_kunjungan,
				'hasil_suara' => $val->hasil_suara,
				'persentage_anggota' => $persentage_anggota, 
				'persentage_peserta_kunjungan' => $persentage_peserta_kunjungan
			];
		}
		
		$jml_tps = collect($results)->sum(function($q){
			return $q['tps'];
		});
		
		$jml_all_anggota_dapil = collect($results)->sum(function($q){
			return $q['jml_all_anggota_dapil'];
		});
		
		$jml_peserta_kunjungan = collect($results)->sum(function($q){
			return $q['peserta_kunjungan'];
		});
		
		$jml_hasil_suara = collect($results)->sum(function($q){
			return $q['hasil_suara'];
		});
		 
		$persentage_anggota = $gf->calculatePercentage($jml_hasil_suara,$jml_all_anggota_dapil);
			if ($persentage_anggota !== null) {
				$persentage_anggota  = $gf->persen($persentage_anggota);  
		}
			
		$persentage_peserta_kunjungan = $gf->calculatePercentage($jml_hasil_suara,$jml_peserta_kunjungan);
		if ($persentage_peserta_kunjungan !== null) {
				$persentage_peserta_kunjungan  = $gf->persen($persentage_peserta_kunjungan);  
		}
		
		$no = 1;		
		
        return view('pages.sip.dashboard.regency', compact('regency','no','results','gf','jml_tps','jml_all_anggota_dapil','jml_peserta_kunjungan','jml_hasil_suara','persentage_anggota','persentage_peserta_kunjungan'));
    }
	
	public function getSipRegency() 
	{
		$regency 		 = 3602;
		$orgDiagramModel = new OrgDiagram();
        $data            = $orgDiagramModel->getDataSipByRegency($regency); 
		
		$chart_sip = [];
        foreach ($data as $val) {
			$districts = $orgDiagramModel->getSipKecamatanByDapil($val->id); // get kecacamatan by dapil
			$result_districts = [];
			foreach($districts as $district){
				$villages = DB::table('villages')->select('id','name','peserta_kunjungan')->where('district_id', $district->id)->get();
				
				// kalkulasi anggota level desa
				$results_village = [];
				foreach($villages as $village){ 
					
					$list_kortps = $orgDiagramModel->getAnggotaTercoverByVillage($village->id);
				
					$jml_kordes  = $orgDiagramModel->getCountKordesByVillage($village->id); // hotung kordes by desa;
					
					$jml_korcam  = $orgDiagramModel->getCountKorcamByVillage($village->id);
					
					//jumlahkan anggota by desa di ambil dari per korte nya
					$jml_anggota_by_korte = collect($list_kortps)->sum(function($q){
						return $q->jml_anggota;
					}); 
					
					$jml_form_manual_by_korte = collect($list_kortps)->sum(function($q){
						return $q->form_manual; 
					});
					  
					$pelapis   = DB::table('anggota_pelapis')->select('id')->where('village_id', $village->id)->count(); 
				
					$jml_korte = count($list_kortps); 
					
					$results_village[] = [
						'id' => $village->id,
						'desa' => $village->name, 
						'jml_all_anggota_desa' => $jml_anggota_by_korte + $jml_form_manual_by_korte + $pelapis + $jml_korte + $jml_kordes + $jml_korcam	
					];
				}
				
				// kalkulasi anggota level kecamatan 
				$jml_all_anggota_kecamatan = collect($results_village)->sum(function($q){
					return $q['jml_all_anggota_desa'];
				});
				
				$result_districts[] = [
					'kecamatan' => $district->name,
					'jml_all_anggota_kecamatan' => $jml_all_anggota_kecamatan,
					'desa' => $results_village    
				];  
			}
			
			$jml_all_anggota_dapil = collect($result_districts)->sum(function($q){
					return $q['jml_all_anggota_kecamatan'];
			});
			 
            $chart_sip['label'][] = $val->name;
            $chart_sip['anggota'][] = $jml_all_anggota_dapil;  
			$chart_sip['suara'][] = $val->hasil_suara;   
			$chart_sip['peserta_kunjungan'][] = $val->peserta_kunjungan;   
			// $chart_sip['urls'][] = route('admin-sip-dashboard-dapil',$val->id);
        }
		
		$chartData = array(
			"labels" => $chart_sip['label'],
			"datasets" => array(
				array(
					"label" => "Anggota",
					"data" => $chart_sip['anggota'],
					// "urls" => $chart_sip['urls'],
					"backgroundColor" => "rgba(255, 99, 132, 0.2)",
					"borderColor" => "rgb(255, 99, 132)",
					"borderWidth" => 2, 
				),
				array(
					"label" => "Peserta Kunjungan",
					"data" => $chart_sip['peserta_kunjungan'], 
					// "urls" => $chart_sip['urls'],
					"backgroundColor" => "rgba(255, 205, 86, 0.2)",
					"borderColor" => "rgb(255, 205, 86)", 
					"borderWidth" => 2, 
				),
				array(
					"label" => "Suara",
					"data" => $chart_sip['suara'],  
					// "urls" => $chart_sip['urls'],
					"backgroundColor" => "rgba(54, 162, 235, 0.2)",
					"borderColor" => "rgb(54, 162, 235)",
					"borderWidth" => 2, 
				),
			)
		);
        return response()->json($chartData);
		
	} 
	
	// dashboard level dapil
    public function dashboardDapil($dapilId)
    {
		$dapil  = DB::table('dapils as a')
				  ->select('b.name','a.name as dapil')
				  ->join('regencies as b','a.regency_id','b.id')
				  ->where('a.id', $dapilId)
				  ->first();
		// get data table $dapiId
		$orgDiagramModel = new OrgDiagram();
        $data            = $orgDiagramModel->getDataSipByDapil($dapilId); 
		
		$gf = new GlobalProvider();
		 
		$results = []; 
		foreach($data as $item){
			
			// get desa
			$villages = DB::table('villages')->select('id','name')->where('district_id', $item->id)->get();
			
			$results_village = [];
			foreach($villages as $val){
				$list_kortps = $orgDiagramModel->getAnggotaTercoverByVillage($val->id);
				
				$jml_kordes      = $orgDiagramModel->getCountKordesByVillage($val->id); // hotung kordes by desa;
				
				$jml_korcam          = $orgDiagramModel->getCountKorcamByVillage($val->id);
				
				//jumlahkan anggota by desa di ambil dari per korte nya
				$jml_anggota_by_korte = collect($list_kortps)->sum(function($q){
					return $q->jml_anggota;
				});
				
				$jml_form_manual_by_korte = collect($list_kortps)->sum(function($q){
					return $q->form_manual;
				});
				 
				$pelapis   = DB::table('anggota_pelapis')->select('id')->where('village_id', $val->id)->count(); 
			
				$jml_korte = count($list_kortps); 
				
				$results_village[] = [
					'desa' => $val->name,
					'jml_anggota_by_korte' => $jml_anggota_by_korte,
					'jml_form_manual_by_korte' => $jml_form_manual_by_korte,
					'pelapis' => $pelapis,
					'jml_korte' => $jml_korte,
					'jml_kordes' => $jml_kordes,
					'jml_korcam' => $jml_korcam,
					'all_anggota' => $jml_anggota_by_korte + $jml_form_manual_by_korte + $pelapis + $jml_korte + $jml_kordes + $jml_korcam	
				];
				
			}
			
			
			
			//jumlah anggota per kecamatan
			$jmlanggota_kecamatan = collect($results_village)->sum(function($q){
				return $q['jml_anggota_by_korte'];
			});
			
			$jmlformmanual_kecamatan = collect($results_village)->sum(function($q){
				return $q['jml_form_manual_by_korte'];
			});
			
			$jmlpelapis_kecamatan = collect($results_village)->sum(function($q){
				return $q['pelapis'];
			});
			
			$jmlkorte_kecamatan = collect($results_village)->sum(function($q){
				return $q['jml_korte'];
			});
			
			$jmlkordes_kecamatan = collect($results_village)->sum(function($q){
				return $q['jml_kordes'];
			});
			
			$jmlkorcam_kecamatan = collect($results_village)->sum(function($q){
				return $q['jml_korcam'];
			});
			 
			
			// $all_anggota = $item->anggota_tercover_kortps + $item->form_manual + $item->pelapis + $item->korte + $item->kordes + $item->korcam;
			$all_anggota = $jmlanggota_kecamatan + $jmlformmanual_kecamatan + $jmlpelapis_kecamatan + $jmlkorte_kecamatan + $jmlkordes_kecamatan + $jmlkorcam_kecamatan;  
			
			$persentage_anggota = $gf->calculatePercentage($item->hasil_suara,$all_anggota);
			if ($persentage_anggota !== null) {
				$persentage_anggota  = $persentage_anggota;  
			}
			
			$persentage_peserta_kunjungan = $gf->calculatePercentage($item->hasil_suara,$item->peserta_kunjungan);
			if ($persentage_peserta_kunjungan !== null) {
				$persentage_peserta_kunjungan  = $persentage_peserta_kunjungan;  
			}
			
			$results[] = [
				'id' => $item->id,
				'name' => $item->name, 
				'tps' => $item->tps, 
				'anggota' => $all_anggota, 
				'peserta_kunjungan' => $item->peserta_kunjungan,
				'hasil_suara' => $item->hasil_suara,
				'results_village' => $results_village,
				'persentage_anggota' => $gf->persen($persentage_anggota),
				'persentage_peserta_kunjungan' => $gf->persen($persentage_peserta_kunjungan)
			];
		}
		
		// dd($results);
		
		$jml_tps = collect($results)->sum(function($q){
			return $q['tps'];
		});
		
		$jml_all_anggota = collect($results)->sum(function($q){
			return $q['anggota'];
		});
		
		$jml_peserta_kunjungan = collect($results)->sum(function($q){
			return $q['peserta_kunjungan'];
		});
		
		$jml_hasil_suara = collect($results)->sum(function($q){
			return $q['hasil_suara'];
		});
		
		$persentage_anggota = $gf->calculatePercentage($jml_hasil_suara,$jml_all_anggota);
		if ($persentage_anggota !== null) {
				$persentage_anggota  = $gf->persen($persentage_anggota);  
		}
		
		$persentage_peserta_kunjungan= $gf->calculatePercentage($jml_hasil_suara,$jml_peserta_kunjungan);
		if ($persentage_peserta_kunjungan !== null) {
				$persentage_peserta_kunjungan  = $gf->persen($persentage_peserta_kunjungan);  
		}
		
		$no = 1;  
        return view('pages.sip.dashboard.dapil', compact('dapil','no','results','gf','jml_tps','jml_all_anggota','jml_peserta_kunjungan','jml_hasil_suara','persentage_anggota','persentage_peserta_kunjungan'));
    }
	
	public function dashboardKecamatan($districtId)
    {
		$district  = DB::table('dapils as a')
				  ->select('b.name','a.name as dapil','d.name as kecamatan','d.id as district_id')
				  ->join('regencies as b','a.regency_id','b.id')
				  ->join('dapil_areas as c','c.dapil_id','a.id')
				  ->join('districts as d','c.district_id','=','d.id')
				  ->where('d.id', $districtId)
				  ->first();
				   
		
		$orgDiagramModel = new OrgDiagram();
        $villages         = $orgDiagramModel->getDataSipByDistrict($districtId);
		// dd($villages);
		$gf = new GlobalProvider();
		
		// get data tps  perdesa 
		// $village = DB::table('villages')->select('name')->where('id', $villageId)->first();
		// $tps = DB::table('tps')->select('tps_number','hasil_suara')->where('village_id', $villageId)->get();
		$result_villages = [];
		$no = 1;
		foreach($villages as $item){
			// $all_anggota = $item->anggota_tercover_kortps + $item->pelapis + $item->form_manual + $item->kordes + $item->korte;
			$all_anggota = $item->anggota_tercover_kortps;
			
			$list_kortps = $orgDiagramModel->getAnggotaTercoverByVillage($item->id);
					   
					   
			$all_anggota = collect($list_kortps)->sum(function($q){
                 return $q->jml_anggota;
            });
			
			$form_manual = collect($list_kortps)->sum(function($q){
                 return $q->form_manual;
            });
			 
			$pelapis   = DB::table('anggota_pelapis')->select('id')->where('village_id', $item->id)->count(); 
			
			$jml_korte = count($list_kortps); 
			$jml_all_anggota  = $all_anggota + $jml_korte + $item->kordes + $form_manual + $pelapis + $item->korcam;
			
			$persen = $gf->calculatePercentage($item->hasil_suara, $jml_all_anggota);
             // $selisih = $selisih < 0 ? 0 : $selisih; 
			if ($persen !== null) {
				$persen  = $persen; 
			}
			
			$persen_peserta_kunjungan = $gf->calculatePercentage($item->hasil_suara, $item->peserta_kunjungan);
             // $selisih = $selisih < 0 ? 0 : $selisih; 
			if ($persen_peserta_kunjungan !== null) {
				$persen_peserta_kunjungan  = $persen_peserta_kunjungan;  
			}
			
			$result_villages[]=[
				'no'   => $no++, 
				'id' => $item->id,  
				'tps' => $item->tps,
				'name' => $item->name,
				'anggota' => $jml_all_anggota, 
				'hasil_suara' => $item->hasil_suara,
				'peserta_kunjungan' => $item->peserta_kunjungan, 
				'persentage' => $gf->persen($persen),
				'persen_peserta_kunjungan' => $gf->persen($persen_peserta_kunjungan),
			];
		}
		
		
		
		$jml_anggota = collect($result_villages)->sum(function($item){
			return $item['anggota'];
		});
		
		$jml_tps = collect($result_villages)->sum(function($item){
			return $item['tps'];
		});

		$jml_hasil_suara = collect($result_villages)->sum(function($item){
			return $item['hasil_suara'];
		});	
		
		$jml_peserta_kunjungan = collect($result_villages)->sum(function($item){
			return $item['peserta_kunjungan'];
		});

		$no = 1; 
		
		$persen = $gf->calculatePercentage($jml_hasil_suara, $jml_anggota);
             // $selisih = $selisih < 0 ? 0 : $selisih; 
		if ($persen !== null) {
				 $persen  = $persen; 
		}
		
		$persen_peserta_kunjungan = $gf->calculatePercentage($jml_hasil_suara, $jml_peserta_kunjungan);
             // $selisih = $selisih < 0 ? 0 : $selisih; 
		if ($persen_peserta_kunjungan !== null) {
				 $persen_peserta_kunjungan  = $persen_peserta_kunjungan; 
		}
		 
		$result = [
			'villages' => $result_villages,
			'jmlanggota' => $gf->decimalFormat($jml_anggota),
			'jmlhasilsuara' => $gf->decimalFormat($jml_hasil_suara),
			'jmlpesertakunjungan' => $gf->decimalFormat($jml_peserta_kunjungan),
			'jmltps' => $gf->decimalFormat($jml_tps),
			'persentage' => $gf->persen($persen),
			'persen_peserta_kunjungan' => $gf->persen($persen_peserta_kunjungan)
		];  
		
       return view('pages.sip.dashboard.village', compact('result','no','gf','district')); 
    } 
	
	
	public function dashboardDesaPdfDownload(Request $request, $villageId)
	{
		
		
		$village = DB::table('villages')->select('name','id','peserta_kunjungan')->where('id', $villageId)->first();
		
		$orgDiagram = new orgDiagram();
		$gf = new GlobalProvider();
		
		$tps     = $orgDiagram->getTpsVillage($villageId);
		$pelapis =  DB::table('anggota_pelapis')->select('id')->where('village_id', $villageId)->count();
		
		$abs_kordes = DB::table('org_diagram_village')
                ->select('name', 'title')
                ->where('village_id', $villageId)
                ->whereNotNull('nik')
                ->orderBy('level_org', 'asc')
                ->get();

            // jika title ketua tidak ada maka tambahkan value array yang memiliki title kordes 
            $list_kordes = [];
            $cek_kordes = $this->searchArrayValue($abs_kordes, 'KETUA');
            if ($cek_kordes == null) {

                // membuat object baru dengan collection  
                $array_value = collect([
                    (object)[
                        'name' => '',
                        'title' => 'KETUA'
                    ]
                ]);

                $sorted = $array_value->sortBy('name');
                $sorted->values()->all();
                $list_kordes = $sorted->merge($abs_kordes); // gabungkan object baru dengan collectiono yg ada 

            } else {

                $list_kordes = $abs_kordes;
            }
		
		
		$result_tps = [];
		$no = 1;
		foreach($tps as $value){
			
			// hitung jumlah anggota berdasarkan jumlah kortps yg ada
             $list_kortps = DB::table('org_diagram_rt as a')
                       ->select(
                         DB::raw('(select count(a1.nik) from org_diagram_rt as a1 join users as a2 on a1.nik = a2.nik where a1.pidx = a.idx and a1.base = "ANGGOTA") as jml_anggota'),
						 DB::raw("
								(
									select count(b1.id) from form_anggota_manual_kortp as b1 where b1.pidx_korte = a.idx
								) as form_manual
						 ")
						) 
                       ->join('users as b','a.nik','=','b.nik')
                       ->where('a.village_id', $value->village_id)
                       ->where('b.tps_id', $value->id)
                       ->where('a.base','KORRT')
                       ->get();
					   
			$kordes = DB::table('org_diagram_village as a')
					  ->select('b.nik')
					  ->join('users as b','a.nik','=','b.nik')
					  ->join('tps as c','b.tps_id','=','c.id')
					  ->where('a.village_id', $value->village_id)
					  ->where('b.tps_id', $value->id)
					  ->get();
					   
			$korcam =  DB::table('org_diagram_district as a')
					  ->select('b.nik')
					  ->join('users as b','a.nik','=','b.nik')
					  ->join('tps as c','b.tps_id','=','c.id')
					  ->where('b.village_id', $value->village_id)
					  ->where('b.tps_id', $value->id)
					  ->get();
					  
			 $jml_kordes = count($kordes);
			 $jml_korcam = count($korcam); 
			 
			 $jml_korte = count($list_kortps);
			 
			
			 
             $jml_anggota_kortps = collect($list_kortps)->sum(function($q){
                 return $q->jml_anggota;
             });
			 
			 $jml_anggota_form_manual = collect($list_kortps)->sum(function($q){
                 return $q->form_manual;
             }); 
			  
			 $jml_all_anggota = $jml_anggota_kortps +  $jml_korte + $jml_kordes + $jml_anggota_form_manual + $jml_korcam;  
			 
			 $persen = $gf->calculatePercentage($value->hasil_suara, $jml_all_anggota);
             // $selisih = $selisih < 0 ? 0 : $selisih; 
			 if ($persen !== null) {
				 $persen  = $persen; 
			 }
			
			$result_tps[] = [   
				'no'  => $no++, 
				'tps' => 'TPS '.$value->tps,    
				// 'kordes' => count($kordes),
				'kortps' => $value->kortps,
				// 'jml_anggota_form_manual' => $jml_anggota_form_manual, // belum di tambah korte, kordes, korcam, jika berada di tps tersebut
				'jml_all_anggota' => $jml_all_anggota, // belum di tambah korte, kordes, korcam, jika berada di tps tersebut
				'hasil_suara' => $value->hasil_suara, 
				'persentage' => $gf->persen($persen)
			];
		}
		
		// dd($result_tps); 
	
		
		$jml_tps = count($tps); 
		
		$jml_peserta_kunjungan = $village->peserta_kunjungan;
		
		$jml_korte = collect($result_tps)->sum(function($q){
			return $q['kortps'];
		});
		
		$jml_anggota = collect($result_tps)->sum(function($q){
			return $q['jml_all_anggota'];
		});
		
		$jml_hasil_suara = collect($result_tps)->sum(function($q){
			return $q['hasil_suara'];
		});
		
		$peserta_kunjungan =  $village->peserta_kunjungan;

		$jml_all_anggota = $jml_anggota + $pelapis;
		
		// $persentage = ($jml_hasil_suara/$jml_all_anggota)*100;
		$persentage = $gf->calculatePercentage($jml_hasil_suara,$jml_all_anggota);
             // $selisih = $selisih < 0 ? 0 : $selisih; 
		if ($persentage !== null) {
				 $persentage = $gf->persen($persentage);
		}
		
		$persentage_peserta_kunjungan = $gf->calculatePercentage($jml_hasil_suara,$jml_peserta_kunjungan);
             // $selisih = $selisih < 0 ? 0 : $selisih; 
		if ($persentage_peserta_kunjungan !== null) {
				 $persentage_peserta_kunjungan = $gf->persen($persentage_peserta_kunjungan);
		} 
		
		$results = [
			'villageId' => $village->id,
			'village' => $village->name,
			'tps' => $result_tps
		];
		$no = 1;
		
		$directory = public_path('/datacharttable/sip/' . $village->name);

        if (File::exists($directory)) {

                File::deleteDirectory($directory); // hapus dir nya juga
        }

        File::makeDirectory(public_path('/datacharttable/sip/' . $village->name));
		
		$fileName  = 'TABLE_PEROLEHAN_SUARA_DESA_'.$village->name.'_'.$village->id.'.pdf';
		
		$pdf = PDF::loadView('pages.report.siptps', compact('results','gf','no','jml_tps','jml_korte','jml_all_anggota','persentage','persentage_peserta_kunjungan','jml_hasil_suara','list_kordes','pelapis','peserta_kunjungan'))->setPaper('A4');
		// return $pdf->download('LAPORAN PEROLEHAN SUARA DESA '.$results['village'].'.pdf');
		
		$pdfFilePath = public_path('/datacharttable/sip/' . $village->name . '/' . $fileName);
        File::put($pdfFilePath, $pdf->output()); 
		 
		// get pdt table by file name
		// $directoryPdf1 = public_path($pdfFilePath);
		// get pdf chart by file name
		$getFileNameChart = 'GRAFIK_PEROLEHAN_SUARA_DESA_'.$village->name.'_'.$village->id.'.pdf';
		$pdfFilePathChart = public_path('datachart/sip/' . $village->name . '/'.$getFileNameChart);
		
		$getPdfFilePathTable = 'TABLE_PEROLEHAN_SUARA_DESA_'.$village->name.'_'.$village->id.'.pdf';
		$pdfFilePathTable = public_path('datacharttable/sip/' . $village->name . '/'.$getPdfFilePathTable);
		 
		$mergePDf = $this->mergePdf($pdfFilePathChart,$pdfFilePathTable,$village);
		
		return response()->download($mergePDf);
		
		// return $mergePDf;
		
		// lakukan download hasil dari merge tersebut
	}
	
	public function dashboardDesaPdfChartDownload(Request $request)
	{ 
		$villageId = $request->input('villageId');
	
		$village = DB::table('villages')->select('name','id','peserta_kunjungan')->where('id', $villageId)->first();
		 
		$chartImage = $request->input('chartimage');
		PdfToImage::convert($request, $village);
		
		// $directoryPdf1 = public_path('datachart/sip/desa/GRAFIK_PEROLEHAN_SUARA_DESA_MUARA_3602011001.pdf');
		// $directoryPdf2 = public_path('datachart/sip/desa/TABEL_PEROLEHAN_SUARA_DESA_MUARA_3602011001.pdf');
		
		// $this->mergePdf($pdf1,$pdf2);
				 
		$data = [
			'message' => 'Berhasil export grafik'
		];
		return response()->json($data); 
	}
	
	public function mergePdf($pdf1Content, $pdf2Content, $village)
	{
		// $pdfFiles = [$pdf1Content,$pdf2Content];
		  
		  // Create a new instance of PDFMerger
        $pdfMerger = PDFMerger::init();
		$pdfMerger->addPDF($pdf1Content,'all');
		$pdfMerger->addPDF($pdf2Content,'all');
		
		$pdfMerger->merge();
		$directory = public_path('/datasipfix/desa/' . $village->name);
		
		if(File::exists($directory)) {

            File::deleteDirectory($directory); // hapus dir nya juga
        }

        File::makeDirectory(public_path('/datasipfix/desa/' . $village->name));
		
		$fileName  = $directory.'/'.'PEROLEHAN_SUARA_DESA_'.$village->name.'.pdf';
		// File::put($directory, $fileName); 
		$pdfMerger->save($fileName);  
		
		return $fileName;
	    
		 // // Add each PDF file to the merger 
        // foreach ($pdfFiles as $pdfFile) {
            // $pdfMerger->addPDF($pdfFile);
        // }
        // // Merge the PDFs
        // $pdfMerger->merge();  

        // // Output the merged PDF to a file
        // $outputFilePath = 'merged.pdf';
        // $pdfMerger->save($outputFilePath, 'file');

        // return response()->download($fileName);  
	}
	
	public function dashboardDesa($villageId)
    {
		$village  = DB::table('dapils as a')
				  ->select('b.name','a.name as dapil','d.name as kecamatan','e.name as desa','e.peserta_kunjungan','e.id')
				  ->join('regencies as b','a.regency_id','b.id')
				  ->join('dapil_areas as c','c.dapil_id','a.id')
				  ->join('districts as d','c.district_id','=','d.id')
				  ->join('villages as e','e.district_id','=','d.id')
				  ->where('e.id', $villageId)
				  ->first();
				  
		
		$orgDiagram = new orgDiagram();
		$gf = new GlobalProvider();
		
		$tps     = $orgDiagram->getTpsVillage($villageId);
		$pelapis =  DB::table('anggota_pelapis')->select('id')->where('village_id', $villageId)->count();
		
		
		$result_tps = [];
		$no = 1;
		foreach($tps as $value){
			
			// hitung jumlah anggota berdasarkan jumlah kortps yg ada
             $list_kortps = DB::table('org_diagram_rt as a')
                       ->select(
                         DB::raw('(select count(a1.nik) from org_diagram_rt as a1 join users as a2 on a1.nik = a2.nik where a1.pidx = a.idx and a1.base = "ANGGOTA") as jml_anggota'),
						 DB::raw("
								(
									select count(b1.id) from form_anggota_manual_kortp as b1 where b1.pidx_korte = a.idx
								) as form_manual
						 ")
						) 
                       ->join('users as b','a.nik','=','b.nik')
                       ->where('a.village_id', $value->village_id)
                       ->where('b.tps_id', $value->id)
                       ->where('a.base','KORRT')
                       ->get();
					   
			$kordes = DB::table('org_diagram_village as a')
					  ->select('b.nik')
					  ->join('users as b','a.nik','=','b.nik')
					  ->join('tps as c','b.tps_id','=','c.id')
					  ->where('a.village_id', $value->village_id)
					  ->where('b.tps_id', $value->id)
					  ->get();
					   
			$korcam =  DB::table('org_diagram_district as a')
					  ->select('b.nik')
					  ->join('users as b','a.nik','=','b.nik')
					  ->join('tps as c','b.tps_id','=','c.id')
					  ->where('b.village_id', $value->village_id)
					  ->where('b.tps_id', $value->id)
					  ->get();
					  
			 $jml_kordes = count($kordes);
			 $jml_korcam = count($korcam); 
			 
			 $jml_korte = count($list_kortps);
			 
			 
 
             $jml_anggota_kortps = collect($list_kortps)->sum(function($q){
                 return $q->jml_anggota;
             });
			 
			 $jml_anggota_form_manual = collect($list_kortps)->sum(function($q){
                 return $q->form_manual;
             }); 
			  
			 $jml_all_anggota = $jml_anggota_kortps +  $jml_korte + $jml_kordes + $jml_anggota_form_manual + $jml_korcam;  
			 
			 $persen = $gf->calculatePercentage($value->hasil_suara, $jml_all_anggota);
             // $selisih = $selisih < 0 ? 0 : $selisih; 
			 if ($persen !== null) {
				 $persen  = $persen; 
			 }
			
			$result_tps[] = [   
				'no'  => $no++, 
				'tps' => 'TPS '.$value->tps,    
				// 'kordes' => count($kordes),
				'kortps' => $value->kortps,
				// 'jml_anggota_form_manual' => $jml_anggota_form_manual, // belum di tambah korte, kordes, korcam, jika berada di tps tersebut
				'jml_anggota_kortps' => $jml_all_anggota, // belum di tambah korte, kordes, korcam, jika berada di tps tersebut
				'hasil_suara' => $value->hasil_suara, 
				'persentage' => $gf->persen($persen)
			];
		}
		
		// dd($result_tps); 
	
		
		$jml_tps = count($tps); 
		
		$jml_peserta_kunjungan = $village->peserta_kunjungan;
		
		$jml_korte = collect($result_tps)->sum(function($q){
			return $q['kortps'];
		});
		
		$jml_anggota = collect($result_tps)->sum(function($q){
			return $q['jml_anggota_kortps'];
		});
		
		$jml_hasil_suara = collect($result_tps)->sum(function($q){
			return $q['hasil_suara'];
		});

		$jml_all_anggota = $jml_anggota + $pelapis;
		
		// $persentage = ($jml_hasil_suara/$jml_all_anggota)*100;
		$persentage = $gf->calculatePercentage($jml_hasil_suara,$jml_all_anggota);
             // $selisih = $selisih < 0 ? 0 : $selisih; 
		if ($persentage !== null) {
				 $persentage = $gf->persen($persentage);
		}
		
		// $persentage = ($jml_hasil_suara/$jml_all_anggota)*100;
		$persentage_peserta_kunjungan = $gf->calculatePercentage($jml_hasil_suara,$jml_peserta_kunjungan);
             // $selisih = $selisih < 0 ? 0 : $selisih; 
		if ($persentage_peserta_kunjungan !== null) {
				 $persentage_peserta_kunjungan = $gf->persen($persentage_peserta_kunjungan);
		}
		 
		$results = [
			'tps' => $result_tps 
		];
		
       return view('pages.sip.dashboard.tps', compact('village','results','jml_tps','jml_korte','jml_anggota','jml_hasil_suara','jml_peserta_kunjungan','persentage_peserta_kunjungan','persentage','pelapis','gf')); 
    }
	
	// dashboard level dapil
    public function getSipDapil($dapilId)
    {
       $regency 		 = 3602;
		$orgDiagramModel = new OrgDiagram();
        $data            = $orgDiagramModel->getDataSipByDapil($dapilId); 
		
		$chart_sip = [];
        foreach ($data as $val) {
			
			// get desa
			$villages = DB::table('villages')->select('id','name')->where('district_id', $val->id)->get();
			
			$results_village = [];
			foreach($villages as $item){
				$list_kortps = $orgDiagramModel->getAnggotaTercoverByVillage($item->id);
				
				$jml_kordes  = $orgDiagramModel->getCountKordesByVillage($item->id); // hotung kordes by desa;
				
				$jml_korcam  = $orgDiagramModel->getCountKorcamByVillage($item->id);
				
				//jumlahkan anggota by desa di ambil dari per korte nya
				$jml_anggota_by_korte = collect($list_kortps)->sum(function($q){
					return $q->jml_anggota;
				});
				
				$jml_form_manual_by_korte = collect($list_kortps)->sum(function($q){
					return $q->form_manual;
				}); 
				 
				$pelapis   = DB::table('anggota_pelapis')->select('id')->where('village_id', $item->id)->count(); 
				
				$jml_korte = count($list_kortps); 
				
				$results_village[] = [
					'desa' => $item->name,
					'jml_anggota_by_korte' => $jml_anggota_by_korte,
					'jml_form_manual_by_korte' => $jml_form_manual_by_korte,
					'pelapis' => $pelapis,
					'jml_korte' => $jml_korte,
					'jml_kordes' => $jml_kordes,
					'jml_korcam' => $jml_korcam,
					// 'all_anggota' => $jml_anggota_by_korte + $jml_form_manual_by_korte + $pelapis + $jml_korte + $jml_kordes + $jml_korcam	
				];
			}
			
			//jumlah anggota per kecamatan
			$jmlanggota_kecamatan = collect($results_village)->sum(function($q){
				return $q['jml_anggota_by_korte'];
			});
			
			$jmlformmanual_kecamatan = collect($results_village)->sum(function($q){
				return $q['jml_form_manual_by_korte'];
			});
			
			$jmlpelapis_kecamatan = collect($results_village)->sum(function($q){
				return $q['pelapis'];
			});
			
			$jmlkorte_kecamatan = collect($results_village)->sum(function($q){
				return $q['jml_korte'];
			});
			
			$jmlkordes_kecamatan = collect($results_village)->sum(function($q){
				return $q['jml_kordes'];
			});
			
			$jmlkorcam_kecamatan = collect($results_village)->sum(function($q){
				return $q['jml_korcam'];
			});
			
			$all_anggota = $jmlanggota_kecamatan + $jmlformmanual_kecamatan + $jmlpelapis_kecamatan + $jmlkorte_kecamatan + $jmlkordes_kecamatan + $jmlkorcam_kecamatan;  
			
			// $chart_sip[] = [
				// 'kecamatan' => $val->name,
				// 'results_village' => $results_village
			// ];
            $chart_sip['label'][] = $val->name;
            $chart_sip['anggota'][] = $all_anggota;  
			$chart_sip['suara'][] = $val->hasil_suara;   
			$chart_sip['peserta_kunjungan'][] = $val->peserta_kunjungan;   
			// $chart_sip['urls'][] = route('admin-sip-dashboard-district',$val->id);
        }
		
		// return $chart_sip;
		 
		$chartData = array(
			"labels" => $chart_sip['label'],
			"datasets" => array(
				array(
					"label" => "Anggota",
					"data" => $chart_sip['anggota'],
					// "urls" => $chart_sip['urls'],
					"backgroundColor" => "rgba(255, 99, 132, 0.2)",
					"borderColor" => "rgb(255, 99, 132)",
					"borderWidth" => 2, 
				),
				array(
					"label" => "Peserta Kunjungan",
					"data" => $chart_sip['peserta_kunjungan'], 
					// "urls" => $chart_sip['urls'],
					"backgroundColor" => "rgba(255, 205, 86, 0.2)",
					"borderColor" => "rgb(255, 205, 86)",
					"borderWidth" => 2, 
				),
				array(
					"label" => "Suara",
					"data" => $chart_sip['suara'],  
					// "urls" => $chart_sip['urls'],
					"backgroundColor" => "rgba(54, 162, 235, 0.2)",
					"borderColor" => "rgb(54, 162, 235)",
					"borderWidth" => 2, 
				),
			)
		);
        return response()->json($chartData);
    }
	
	public function getSipDistrict($districtId)
    {
        
		$orgDiagramModel = new OrgDiagram();
        $data            = $orgDiagramModel->getDataSipByDistrict($districtId); 
		
		$chart_sip = [];
        foreach ($data as $val) {
            $chart_sip['label'][] = $val->name;
            $chart_sip['anggota'][] = $val->anggota_tercover_kortps + $val->form_manual + $val->pelapis + $val->kordes + $val->korte;  
			$chart_sip['suara'][] = $val->hasil_suara;
			$chart_sip['peserta_kunjungan'][] = $val->peserta_kunjungan;
			// $chart_sip['urls'][] = $val->id;
        }
		 
		$chartData = array( 
			"labels" => $chart_sip['label'],
			"datasets" => array(
				array(
					"label" => "Anggota",
					"data" => $chart_sip['anggota'],
					// "urls" => $chart_sip['urls'],
					"backgroundColor" => "rgba(255, 99, 132, 0.2)",
					"borderColor" => "rgb(255, 99, 132)",
					"borderWidth" => 2, 
				), 
				array(
					"label" => "Peserta Kunjungan",
					"data" => $chart_sip['peserta_kunjungan'],
					// "urls" => $chart_sip['urls'],
					"backgroundColor" => "rgba(255, 205, 86, 0.2)",
					"borderColor" => "rgb(255, 205, 86)",
					"borderWidth" => 2, 
				),
				array(
					"label" => "Suara",
					"data" => $chart_sip['suara'],
					// "urls" => $chart_sip['urls'],
					"backgroundColor" => "rgba(54, 162, 235, 0.2)",
					"borderColor" => "rgb(54, 162, 235)",
					"borderWidth" => 2, 
				)
			)
		);
        return response()->json($chartData);
    }
	
	public function getRekapTpsByVillage($villageId)
	{
		$village = DB::table('villages')->select('name')->where('id', $villageId)->first();
		$orgDiagram = new orgDiagram();
		$tps    = $orgDiagram->getTpsExistByVillage($villageId);
		$gf = new GlobalProvider();
		
		$result_tps = [];
		$no = 1;
		foreach($tps as $value){
			
			// hitung jumlah anggota berdasarkan jumlah kortps yg ada
             $list_kortps = DB::table('org_diagram_rt as a')
                       ->select(
                         DB::raw('(select count(a1.nik) from org_diagram_rt as a1 join users as a2 on a1.nik = a2.nik where a1.pidx = a.idx and a1.base = "ANGGOTA") as jml_anggota')
                       )
                       ->join('users as b','a.nik','=','b.nik')
                       ->where('a.village_id', $value->village_id)
                       ->where('b.tps_id', $value->id)
                       ->where('a.base','KORRT')
                       ->get();
 
             $jml_anggota_kortps = collect($list_kortps)->sum(function($q){
                 return $q->jml_anggota;
             });

             $persen = ($value->hasil_suara/$jml_anggota_kortps)*100;
             // $selisih = $selisih < 0 ? 0 : $selisih;
			
			$result_tps[] = [
				'no'  => $no++,
				'tps' => $value->tps,
				'kortps' => $value->kortps,
				'jml_anggota_kortps' => $jml_anggota_kortps,
				'hasil_suara' => $value->hasil_suara,
				'persentage' => $gf->persen($persen)
			];
		}
		
		$results = [
			'village' => $village->name,
			'tps' => $result_tps
		];
		
		return response()->json($results); 
	}
	
	public function getRekapTpsByDistrict($districtId)
	{
		$district = DB::table('districts')->select('name')->where('id', $districtId)->first();
		$orgDiagramModel = new OrgDiagram();
        $villages         = $orgDiagramModel->getDataSipByDistrict($districtId); 
		$gf = new GlobalProvider();
		
		// get data tps  perdesa 
		// $village = DB::table('villages')->select('name')->where('id', $villageId)->first();
		// $tps = DB::table('tps')->select('tps_number','hasil_suara')->where('village_id', $villageId)->get();
		$result_villages = [];
		$no = 1;
		foreach($villages as $item){
			// $all_anggota = $item->anggota_tercover_kortps + $item->pelapis + $item->form_manual + $item->kordes + $item->korte;
			$all_anggota = $item->anggota_tercover_kortps;
			
			$list_kortps = DB::table('org_diagram_rt as a')
                       ->select(
                         DB::raw('(select count(a1.nik) from org_diagram_rt as a1 join users as a2 on a1.nik = a2.nik where a1.pidx = a.idx and a1.base = "ANGGOTA") as jml_anggota'),
						  DB::raw("
								(
									select count(b1.id) from form_anggota_manual_kortp as b1 where b1.pidx_korte = a.idx
								) as form_manual
						 ")
                       )
                       ->join('users as b','a.nik','=','b.nik')
                       ->where('a.village_id', $item->id)
                       // ->where('b.tps_id', $value->id)
                       ->where('a.base','KORRT')
                       ->get();
					   
			$all_anggota = collect($list_kortps)->sum(function($q){
                 return $q->jml_anggota;
            });
			
			$form_manual = collect($list_kortps)->sum(function($q){
                 return $q->form_manual;
            });
			
			$jml_korte = count($list_kortps); 
			
			$result_villages[]=[
				'no'   => $no++,
				'id' => $item->id,
				'tps' => $item->tps,
				'name' => $item->name,
				'anggota' => $all_anggota + $jml_korte + $item->kordes + $form_manual, 
				'hasil_suara' => $item->hasil_suara,
				'peserta_kunjungan' => $item->peserta_kunjungan, 
				'persentage' => $gf->persen(($item->hasil_suara / $all_anggota)*100) 
			];
		}
		
		$jml_anggota = collect($result_villages)->sum(function($item){
			return $item['anggota'];
		});
		
		$jml_tps = collect($result_villages)->sum(function($item){
			return $item['tps'];
		});

		$jml_hasil_suara = collect($result_villages)->sum(function($item){
			return $item['hasil_suara'];
		});	
		
		$jml_peserta_kunjungan = collect($result_villages)->sum(function($item){
			return $item['peserta_kunjungan'];
		});		
		 
		$result = [
			'district' => $district->name,
			'villages' => $result_villages,
			'jmlanggota' => $gf->decimalFormat($jml_anggota),
			'jmlhasilsuara' => $gf->decimalFormat($jml_hasil_suara),
			'jmlpesertakunjungan' => $gf->decimalFormat($jml_peserta_kunjungan),
			'jmltps' => $gf->decimalFormat($jml_tps),
			'persentage' => $gf->persen(($jml_hasil_suara/$jml_anggota)*100)
		]; 
		return response()->json($result); 
	}
	
	public function getSipVillage($villageId)
    {
        
		$village = DB::table('villages')->select('name')->where('id', $villageId)->first();
		$orgDiagram = new orgDiagram();
		$gf = new GlobalProvider();
		
		$tps     = $orgDiagram->getTpsVillage($villageId);
		$pelapis =  DB::table('anggota_pelapis')->select('id')->where('village_id', $villageId)->count();
		
		
		$result_tps = [];
		$no = 1;
		foreach($tps as $value){
			
			// hitung jumlah anggota berdasarkan jumlah kortps yg ada
             $list_kortps = DB::table('org_diagram_rt as a')
                       ->select(
                         DB::raw('(select count(a1.nik) from org_diagram_rt as a1 join users as a2 on a1.nik = a2.nik where a1.pidx = a.idx and a1.base = "ANGGOTA") as jml_anggota'),
						 DB::raw("
								(
									select count(b1.id) from form_anggota_manual_kortp as b1 where b1.pidx_korte = a.idx
								) as form_manual
						 ")
						) 
                       ->join('users as b','a.nik','=','b.nik')
                       ->where('a.village_id', $value->village_id)
                       ->where('b.tps_id', $value->id)
                       ->where('a.base','KORRT')
                       ->get();
					   
			$kordes = DB::table('org_diagram_village as a')
					  ->select('b.nik')
					  ->join('users as b','a.nik','=','b.nik')
					  ->join('tps as c','b.tps_id','=','c.id')
					  ->where('a.village_id', $value->village_id)
					  ->where('b.tps_id', $value->id)
					  ->get();
					  
			 $jml_kordes = count($kordes);
			 
			 $jml_korte = count($list_kortps);
 
             $jml_anggota_kortps = collect($list_kortps)->sum(function($q){
                 return $q->jml_anggota;
             });
			 
			 $jml_anggota_form_manual = collect($list_kortps)->sum(function($q){
                 return $q->form_manual;
             });
			  

            $persen = $gf->calculatePercentage($value->hasil_suara, $jml_anggota_kortps);
             // $selisih = $selisih < 0 ? 0 : $selisih; 
			if ($persen !== null) {
				 $persen = $gf->persen($persen);
			}
			
			$result_tps[] = [  
				'no'  => $no++, 
				'tps' => $value->tps,    
				// 'kordes' => count($kordes),
				'kortps' => $value->kortps,
				// 'jml_anggota_form_manual' => $jml_anggota_form_manual, // belum di tambah korte, kordes, korcam, jika berada di tps tersebut
				'jml_anggota_kortps' => $jml_anggota_kortps + $jml_korte +  $jml_kordes + $jml_anggota_form_manual, // belum di tambah korte, kordes, korcam, jika berada di tps tersebut
				'hasil_suara' => $value->hasil_suara, 
				'persentage' => $persen  
			];
		}
		
		// $pelapis = DB::table('anggota_pelapis')->select('id')->where('village_id', $villageId)->count();
		
		$chart_sip = [];
        foreach ($result_tps as $val) {
            $chart_sip['label'][] = 'TPS '.$val['tps'];
            $chart_sip['anggota'][] = $val['jml_anggota_kortps'];  
			$chart_sip['suara'][] = $val['hasil_suara'];
			$chart_sip['urls'][] = $val['tps'];
        }
		 
		$chartData = array( 
			"labels" => $chart_sip['label'],
			"datasets" => array(
				array(
					"label" => "Anggota",
					"data" => $chart_sip['anggota'],
					"urls" => $chart_sip['urls'],
					"backgroundColor" => "rgba(255, 99, 132, 0.2)",
					"borderColor" => "rgb(255, 99, 132)",
					"borderWidth" => 2, 
				),
				array(
					"label" => "Suara",
					"data" => $chart_sip['suara'],
					"urls" => $chart_sip['urls'],
					"backgroundColor" => "rgba(54, 162, 235, 0.2)",
					"borderColor" => "rgb(54, 162, 235)",
					"borderWidth" => 2, 
				)
			)
		);
		
        return response()->json($chartData);
    }
	
	 public function searchArrayValue($data, $field)
    {

        foreach ($data as $row) {
            if ($row->title == $field)
                return $row->title;
        }
    }
	
	public function rangkingPerolehanSuara(Request $request)
	{
		$regencyId  = 3602;
		$orgDiagramModel = new orgDiagram();
		
		// get data kecamatan hanya dapil 1, 4, 5
		$districts = $orgDiagramModel->getAllKecamatanOnly($regencyId);

		$gf = new GlobalProvider();
		 
		$results = [];
		foreach($districts as $item){
			// get desa
			$villages = DB::table('villages')->select('id','name')->where('district_id', $item->id)->get();
			$results_village = [];
			foreach($villages as $val){
				$list_kortps = $orgDiagramModel->getAnggotaTercoverByVillage($val->id);
				
				$jml_kordes      = $orgDiagramModel->getCountKordesByVillage($val->id); // hotung kordes by desa;
				
				$jml_korcam          = $orgDiagramModel->getCountKorcamByVillage($val->id);
				
				//jumlahkan anggota by desa di ambil dari per korte nya
				$jml_anggota_by_korte = collect($list_kortps)->sum(function($q){
					return $q->jml_anggota;
				});
				
				$jml_form_manual_by_korte = collect($list_kortps)->sum(function($q){
					return $q->form_manual;
				});
				 
				$pelapis   = DB::table('anggota_pelapis')->select('id')->where('village_id', $val->id)->count(); 
			
				$jml_korte = count($list_kortps); 
				
				$results_village[] = [
					'desa' => $val->name,
					'jml_anggota_by_korte' => $jml_anggota_by_korte,
					'jml_form_manual_by_korte' => $jml_form_manual_by_korte,
					'pelapis' => $pelapis,
					'jml_korte' => $jml_korte,
					'jml_kordes' => $jml_kordes,
					'jml_korcam' => $jml_korcam,
					'all_anggota' => $jml_anggota_by_korte + $jml_form_manual_by_korte + $pelapis + $jml_korte + $jml_kordes + $jml_korcam	
				];
				
			}
			
			//jumlah anggota per kecamatan
			$jmlanggota_kecamatan = collect($results_village)->sum(function($q){
				return $q['jml_anggota_by_korte'];
			});
			
			$jmlformmanual_kecamatan = collect($results_village)->sum(function($q){
				return $q['jml_form_manual_by_korte'];
			});
			
			$jmlpelapis_kecamatan = collect($results_village)->sum(function($q){
				return $q['pelapis'];
			});
			
			$jmlkorte_kecamatan = collect($results_village)->sum(function($q){
				return $q['jml_korte'];
			});
			
			$jmlkordes_kecamatan = collect($results_village)->sum(function($q){
				return $q['jml_kordes'];
			});
			
			$jmlkorcam_kecamatan = collect($results_village)->sum(function($q){
				return $q['jml_korcam'];
			});
			 
			
			// $all_anggota = $item->anggota_tercover_kortps + $item->form_manual + $item->pelapis + $item->korte + $item->kordes + $item->korcam;
			$all_anggota = $jmlanggota_kecamatan + $jmlformmanual_kecamatan + $jmlpelapis_kecamatan + $jmlkorte_kecamatan + $jmlkordes_kecamatan + $jmlkorcam_kecamatan;  
			
			$persentage_anggota = $gf->calculatePercentage($item->hasil_suara,$all_anggota);
			if ($persentage_anggota !== null) {
				$persentage_anggota  = $persentage_anggota;  
			}
			
		
			
			$results[] = [
				'id' => $item->id,
				'name' => $item->name, 
				'tps' => $item->tps, 
				'anggota' => $all_anggota, 
				'hasil_suara' => $item->hasil_suara,
				'persentage_anggota' => $gf->persen($persentage_anggota),
				
			];
			 
		}
		
		$jml_all_anggota = collect($results)->sum(function($q){
			return $q['anggota'];
		});
		
		$jml_hasil_suara = collect($results)->sum(function($q){
			return $q['hasil_suara'];
		});
		
		$persentage_anggota = $gf->calculatePercentage($jml_hasil_suara, $jml_all_anggota);
             // $selisih = $selisih < 0 ? 0 : $selisih; 
			if ($persentage_anggota !== null) {
				 $persentage_anggota = $gf->persen($persentage_anggota);
			}
		
		$result_persentase = $results;
		$result_suara      = $results;
		
		usort($result_persentase, function($a, $b){
				return $a['persentage_anggota'] < $b['persentage_anggota'];
			});
			
		usort($result_suara, function($a, $b){
				return $a['hasil_suara'] < $b['hasil_suara'];
			});
			
		$no_persentase = 1;
		$no_suara      = 1;
		
		// export PDF 
		$pdf = PDF::loadView('pages.report.rangkingkecamatan-perolehansuara', compact('persentage_anggota','jml_hasil_suara','jml_all_anggota','result_persentase','result_suara','no_persentase','no_suara','gf'))->setPaper('A4');
		return $pdf->download('RANGKING KECAMATAN.pdf');
		  
	}
	
	public function rangkingPerolehanSuaraDesa(Request $request, $districtId)
	{
		$district  = DB::table('districts')->select('name')->where('id', $districtId)->first();
				   
		$orgDiagramModel = new OrgDiagram();
        $villages         = $orgDiagramModel->getDataSipByDistrict($districtId);
		// dd($villages);
		$gf = new GlobalProvider();
		
		// get data tps  perdesa 
		// $village = DB::table('villages')->select('name')->where('id', $villageId)->first();
		// $tps = DB::table('tps')->select('tps_number','hasil_suara')->where('village_id', $villageId)->get();
		$result_villages = [];
		$no = 1;
		foreach($villages as $item){
			// $all_anggota = $item->anggota_tercover_kortps + $item->pelapis + $item->form_manual + $item->kordes + $item->korte;
			$all_anggota = $item->anggota_tercover_kortps;
			
			$list_kortps = $orgDiagramModel->getAnggotaTercoverByVillage($item->id);
					   
					   
			$all_anggota = collect($list_kortps)->sum(function($q){
                 return $q->jml_anggota;
            });
			
			$form_manual = collect($list_kortps)->sum(function($q){
                 return $q->form_manual;
            });
			 
			$pelapis   = DB::table('anggota_pelapis')->select('id')->where('village_id', $item->id)->count(); 
			
			$jml_korte = count($list_kortps); 
			$jml_all_anggota  = $all_anggota + $jml_korte + $item->kordes + $form_manual + $pelapis + $item->korcam;
			
			$persen = $gf->calculatePercentage($item->hasil_suara, $jml_all_anggota);
             // $selisih = $selisih < 0 ? 0 : $selisih; 
			if ($persen !== null) {
				$persen  = $persen; 
			}
			
			$persen_peserta_kunjungan = $gf->calculatePercentage($item->hasil_suara, $item->peserta_kunjungan);
             // $selisih = $selisih < 0 ? 0 : $selisih; 
			if ($persen_peserta_kunjungan !== null) {
				$persen_peserta_kunjungan  = $persen_peserta_kunjungan;  
			}
			
			$result_villages[]=[
				'no'   => $no++, 
				'id' => $item->id,  
				'tps' => $item->tps,
				'name' => $item->name,
				'anggota' => $jml_all_anggota, 
				'hasil_suara' => $item->hasil_suara,
				'persentage' => $persen,
				
			];
		}
		
		$jml_all_anggota = collect($result_villages)->sum(function($q){
			return $q['anggota'];
		});
		
		$jml_hasil_suara = collect($result_villages)->sum(function($q){
			return $q['hasil_suara'];
		});
		
		$persentage_anggota = $gf->calculatePercentage($jml_hasil_suara, $jml_all_anggota);
             // $selisih = $selisih < 0 ? 0 : $selisih; 
			if ($persentage_anggota !== null) {
				 $persentage_anggota = $gf->persen($persentage_anggota);
			}
		
		
		$result_persentase = $result_villages;
		$result_suara      = $result_villages;
		
		usort($result_persentase, function($a, $b){
				return $a['persentage'] < $b['persentage'];
			});
			
		usort($result_suara, function($a, $b){
				return $a['hasil_suara'] < $b['hasil_suara'];
			});
			
		$no_persentase = 1;
		$no_suara      = 1; 
		  
		$pdf = PDF::loadView('pages.report.rangkingdesa', compact('persentage_anggota','jml_hasil_suara','jml_all_anggota','result_persentase','result_suara','no_persentase','no_suara','gf','district'))->setPaper('A4');
		return $pdf->download('RANGKING DESA KECAMATAN '.$district->name.'.pdf');
	}
	
}
