<?php

namespace App\Http\Controllers\Admin;

use App\Tps;
use App\User;
use App\Dapil;
use App\Exports\SaksiExport;
use App\Witness;
use App\Models\Regency;
use App\Models\Village;
use App\Models\District;
use App\RightChooseRegency;
use App\RightChosseVillage;
use App\RightChooseDistrict;
use App\RightChooseProvince;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Helpers\CountAnggaran;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\UserMenu;
use Maatwebsite\Excel\Excel;
use PDF;
use App\Providers\GlobalProvider;
use App\TmpSpamUser;

class TpsController extends Controller
{
    public $excel;
    public function __construct(Excel $excel)
    {
        $this->excel = $excel;
    }

    public function index(){

        $regency = Regency::select('id','name')->where('id', 3602)->first();

        return view('pages.admin.tps.index', compact('regency'));

    }

    public function getDataTps(Request $request){

        // DATATABLE
        $orderBy = 'b.name';
        switch ($request->input('order.0.column')) {
            case '3':
                $orderBy = 'b.name';
                break;
        }

        $data = DB::table('tps as a')
                ->select('a.tps_number','a.rt', 'a.rw', 'b.name as village','a.id')
                ->join('villages as b','a.village_id','=','b.id');

        if($request->input('search.value')!=null){
                $data = $data->where(function($q)use($request){
                    $q->whereRaw('LOWER(b.name) like ? ',['%'.strtolower($request->input('search.value')).'%']);
                });
            }

            if ($request->input('village') != null) {
                            $data->where('a.village_id', $request->village);
            }

            if ($request->input('rt') != null) {
                            $data->where('a.rt', $request->rt);
            }


          $recordsFiltered = $data->get()->count();
          if($request->input('length')!=-1) $data = $data->skip($request->input('start'))->take($request->input('length'));

          $data = $data->orderBy('a.village_id','asc');
          $data = $data->orderBy('a.tps_number','asc');
          $data = $data->orderBy($orderBy,$request->input('order.0.dir'));
          $data = $data->get();

          $recordsTotal = $data->count();

          return response()->json([
                'draw'=>$request->input('draw'),
                'recordsTotal'=>$recordsTotal,
                'recordsFiltered'=>$recordsFiltered,
                'data'=> $data
            ]);
    }


    public function create(){

        $dapils = new Dapil();
        $dataDapils = $dapils->getRegencyDapil();

        return view('pages.admin.tps.create', compact('dataDapils'));

    }

    public function store(Request $request){

        DB::beginTransaction();
        try {
            
            $this->validate($request, [
                'tpnumber' => 'required|min:1',
            ]);

            $tpsModel  = new Tps();
            $villageid = $request->village_id;

            #cek jika data tps sudah ada dengan villageId tersebut
            $cekTps   = $tpsModel->where('village_id', $villageid)->count();
            $village  = Village::with(['district.regency.province'])->where('id', $villageid)->first();

            #hitung angka ari tps_number
            $countTpsNumber = $request->tpnumber;

            #jika sudah ada, maka replace data, karena data tersimpan sesuai jumlah input tps
            if ($cekTps > 0) {
                
                #replace
                $tpsModel->where('village_id', $villageid)->delete();

                for ($i= 1; $i <= $countTpsNumber ; $i++) { 
                    
                    Tps::create([
                        'village_id' => $villageid,
                        'tps_number' => $i,
                        'cby' => auth()->guard('admin')->user()->id
                    ]);
                }

            }else{

                #else
                #simpan baru

                for ($i= 1; $i <= $countTpsNumber ; $i++) { 
                    
                    Tps::create([
                        'village_id' => $villageid,
                        'tps_number' => $i,
                        'cby' => auth()->guard('admin')->user()->id
                    ]);
                }

            }

            $rightChooseVillageModel  = new RightChosseVillage();
            $rightChooseDistrictModel = new RightChooseDistrict();
            $rightChooseRegencyModel  = new RightChooseRegency();
            $rightChooseProvinceModel = new RightChooseProvince();

            #update TPS village
            $this->setUpdateTpsVillage($rightChooseVillageModel, $villageid,$countTpsNumber);

            #update TPS district
            $this->setUpdateTpsDistrict($rightChooseVillageModel,$rightChooseDistrictModel,$village);

            #update TPS regency
            $this->setUpdateTpsRegency($rightChooseDistrictModel,$rightChooseRegencyModel,$village);
            
            #update TPS province
            $this->setUpdateTpsProvince($rightChooseRegencyModel,$rightChooseProvinceModel,$village);

            DB::commit();
            return redirect()->back()->with(['success' => 'TPS berhasil tersimpan!']);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();

        }


    }

    public function setUpdateTpsProvince($rightChooseRegencyModel,$rightChooseProvinceModel,$village){

         #hitung count_tps regency
         $countTpsRegency         = $rightChooseRegencyModel->where('province_id', $village->district->regency->province->id)->sum('count_tps');
         #update count_tps di tb right_to_choose_provinces
         $rightChooseProvince      = $rightChooseProvinceModel->where('province_id', $village->district->regency->province->id)->first();
         if($rightChooseProvince  != null) $rightChooseProvinceModel->where('province_id', $village->district->regency->province->id)->update(['count_tps' => $countTpsRegency]);
         return;

    }

    public function setUpdateTpsRegency($rightChooseDistrictModel,$rightChooseRegencyModel,$village){

         #hitung count_tps district
         $countTpsDistrict         = $rightChooseDistrictModel->where('regency_id', $village->district->regency->id)->sum('count_tps');
         #update count_tps di tb right_to_choose_regencies

         $rightChooseRegency      = $rightChooseRegencyModel->where('regency_id', $village->district->regency->id)->first();
         if($rightChooseRegency  != null) $rightChooseRegencyModel->where('regency_id', $village->district->regency->id)->update(['count_tps' => $countTpsDistrict]);
         return;


    }

    public function setUpdateTpsDistrict($rightChooseVillageModel,$rightChooseDistrictModel,$village){

         #hitung count_tps village
         $countTpsVillage = $rightChooseVillageModel->where('district_id', $village->district->id)->sum('count_tps');

         #update count_tps di tb right_to_choose_districts
         $rightChooseDistrict      = $rightChooseDistrictModel->where('district_id', $village->district->id)->first();
         if($rightChooseDistrict  != null) $rightChooseDistrictModel->where('district_id', $village->district->id)->update(['count_tps' => $countTpsVillage]);
         return;

    }

    public function setUpdateTpsVillage($rightChooseVillageModel, $villageid,$countTpsNumber){

        #hitung jumlah tps
        #get right_to_choose_village where $villageid
        #update count_tps di tb right_to_choose_village
        $rightChooseVillage      = $rightChooseVillageModel->where('village_id', $villageid)->first();
        if($rightChooseVillage  != null) $rightChooseVillageModel->where('village_id', $villageid)->update(['count_tps' => $countTpsNumber]);
        return;

    }

    public function getDataTpsAPI(Request $request){

        $data = Tps::select('id','tps_number','village_id')->where('village_id', request()->villageId)->orderBy('tps_number','asc')->get();

        if($request->has('q')){
            $search = $request->q;
            $data = Tps::select('id','tps_number','village_id')
            ->where('village_id', request()->villageId)
            ->where('tps_number','LIKE',"%$search%")
            ->orderBy('tps_number','asc')
            ->get();

        }

        return response()->json($data);

    }

    public function witnesses($tpsId){

       $regency      = Regency::select('id')->where('id', 3602)->first();

       $tpsModel     = new Tps();
       $tps          = $tpsModel->getDataTpsByTpsId($tpsId);
        
       $witnessModel = new Witness();
       $witnesses    = $witnessModel->getDataWitnrsses($tpsId);
       
       $no           = 1;

       return view('pages.admin.tps.witness', compact('regency','tpsId','witnesses','no','tps'));

    }

    public function storeWitness(Request $request, $tpsId){

        DB::beginTransaction();
        try {
            
            $this->validate($request, [
                'member' => 'required|min:1',
                'status' => 'required',
            ]);

            $userId       = $request->member;

            $witnessModel = new Witness();
            $user         = User::select('village_id')->where('id', $userId)->first();
            
            #cek apakah anggota sudah menjadi tim saksi
            $check       = $witnessModel->where('user_id', $userId)->count();
            if($check > 0) return redirect()->back()->with(['warning' => 'Gagal simpan, Sudah terdaftar sebagai saksi!']);

            $tps          = Tps::select('village_id')->where('id', $tpsId)->first();
            #cek apakah anggota tersebut domisilinya sama dengan data lokasi TPS
            #parameternya adalah village_id di tbl tps dan village_id di tb users
            if ($user->village_id != $tps->village_id) return redirect()->back()->with(['warning' => 'Gagal simpan, Alamat desa anggota tidak sama dengan alamat TPS berada!']);
            
            Witness::create([
                'tps_id' => $tpsId,
                'user_id' => $request->member,
                'status' => $request->status,
                'cby' =>  auth()->guard('admin')->user()->id
            ]);

            #buat akun untuk saksi tps
            #jadikan admin level kecamatan, dan berikan akses menu realisasi suara
            #set user level = 1, and status = 1
            UserMenu::create([
                'user_id' => $request->member,
                'menu_id' => 11, // id menu realiasi member
            ]);

            $user->update(['status' => 1]);

            DB::commit();
            return redirect()->back()->with(['success' => 'Saksi telah disimpan!']);
        
        } catch (\Exception $e) {

            DB::rollBack();
            return redirect()->back()->with(['error' => $e->getMessage()]);

        }

    }

    public function deleteWitness()
    {

        DB::beginTransaction();
        try {

            $id    = request()->id;

            $witness =  Witness::where('id', $id)->first();

            #delete akses ke menu realisasi
            UserMenu::where('user_id', $witness->user_id)->where('menu_id', 11)->delete();

            $witness->delete();

            DB::commit();
            return ResponseFormatter::success([
                'message' => 'Berhasil hapus saksi!'
            ], 200);
            
        } catch (\Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'message' => 'Something when wrong!',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function downloadSaksiPerDesa(Request $request)
    {
		$tpsModel = new Tps();
		
		$report_type = $request->report_type;
		if($report_type == 'Download Saksi'){
			 if (!$request->village_id) return redirect()->back()->with(['warning' => 'Pilih desa terlebih dahulu!']);
			$village = Village::select('name')->where('id', $request->village_id)->first();
			
			// get data saksi per desa
			$saksi = $tpsModel->getDataSaksiPerdesa($request);
			
			$title = 'DAFTAR SAKSI DS.'.$village->name.'.xls';
			return $this->excel->download(new SaksiExport($saksi), $title);
			
		}elseif($report_type == 'Rekap Saksi Per Kecamatan'){
			if (!$request->district_id) return redirect()->back()->with(['warning' => 'Pilih kecamatan terlebih dahulu!']);
			
			$district = District::select('name')->where('id', $request->district_id)->first();
			$gF = new GlobalProvider();
			// get rekap data saksi 
			$saksi = $tpsModel->getRekapSaksiPerKecamatan($request);
			// dd($saksi);
			$no    = 1;
			
			$jml_dpt = collect($saksi)->sum(function($q){
				return $q->dpt; 
			});
			
			$jml_saksi_dalam = collect($saksi)->sum(function($q){
				return $q->saksi_dalam; 
			});
			
			$jml_saksi_luar = collect($saksi)->sum(function($q){
				return $q->saksi_luar; 
			});
			 
			$jml_tps = collect($saksi)->sum(function($q){
				return $q->tps;
			});
			
			$jml_korte = collect($saksi)->sum(function($q){
				return $q->korte;
			});
			
			$jml_anggota_kta = collect($saksi)->sum(function($q){
				return $q->anggota;
			});
			
			$jml_anggota_form_manual = collect($saksi)->sum(function($q){
				return $q->form_manual;
			});
			
			$jml_anggota_all= collect($saksi)->sum(function($q){
				return $q->jml_all_anggota;
			});
			 
			$pdf = PDF::LoadView('pages.report.rekapsaksiperkecamatan', compact('jml_dpt','jml_anggota_all','jml_anggota_form_manual','jml_anggota_kta','no','saksi','district','jml_saksi_dalam','jml_saksi_luar','jml_tps','jml_korte','gF'))->setPaper('a4');
			return $pdf->download('REKAP SAKSI PER KECAMATAN '.$district->name.'.pdf');
			
		}elseif($report_type == 'Rekap Saksi Per Desa'){
			if (!$request->village_id) return redirect()->back()->with(['warning' => 'Pilih desa terlebih dahulu!']);
			$village = Village::select('name')->where('id', $request->village_id)->first();
			
			// $district = District::select('name')->where('id', $request->district_id)->first();
			
			// get rekap data saksi 
			$saksi = $tpsModel->getRekapSaksiPerDesa($request);
			
			$results = [];
			foreach($saksi as $item){
				
				// kalkulasi jumlah anggota dari korte berdasarkan tps nya
				$anggota_by_tps = $tpsModel->getAnggotaKorteByTps($request->village_id,$item->id);
				
				$jml_anggota = collect($anggota_by_tps)->sum(function($q){
					return $q->jml_anggota;
				}); 
				 
				$anggota_form_manual = $tpsModel->getAnggotaFormManual($request->village_id,$item->id);
				
				$jml_anggota_form_manual = collect($anggota_form_manual)->sum(function($q){
					return $q->jml_anggota;
				}); 
				
				$results[] = [
					'tps_number' => $item->tps_number,
					'saksi_dalam' => $item->saksi_dalam,
					'saksi_luar' => $item->saksi_luar,
					'korte' => $item->korte,
					'anggota' => $jml_anggota,
					'anggota_form_manual' => $jml_anggota_form_manual,
					'jml_all_anggota' => $jml_anggota + $jml_anggota_form_manual
				];
			} 
			
			
			
			
			$jml_saksi_dalam = collect($results)->sum(function($q){
				return $q['saksi_dalam']; 
			});   
			
			$jml_saksi_luar = collect($results)->sum(function($q){
				return $q['saksi_luar'];
			});
			
			$jml_korte   = collect($results)->sum(function($q){
				return $q['korte'];
			}); 
			
			$jml_anggota = collect($results)->sum(function($q){
				return $q['anggota']; 
			}); 
			 
			$jml_form_manual = collect($results)->sum(function($q){
				return $q['anggota_form_manual']; 
			});
			
			// dd($results,$jml_form_manual);
			
			// dd($results,$jml_form_manual);
			
			$jml_anggota_kta_manual = $jml_anggota + $jml_form_manual;
			
			$no = 1;
			$gF         = new GlobalProvider();
			
			$pdf = PDF::LoadView('pages.report.rekapsaksiperdesa', compact('no','jml_anggota_kta_manual','results','village','jml_saksi_dalam','jml_saksi_luar','jml_anggota','gF','jml_korte','jml_form_manual'))->setPaper('a4');
			return $pdf->download('REKAP SAKSI PER DS.'.$village->name.'.pdf');
			
		}elseif($report_type == 'Biaya Saksi Per Desa'){
			
			if (!$request->village_id) return redirect()->back()->with(['warning' => 'Pilih desa terlebih dahulu!']);
			$village = Village::select('name')->where('id', $request->village_id)->first();
			// get rekap data saksi 
			$saksi = $tpsModel->getRekapBiayaSaksiPerDesa($request->village_id);
			
			 
			$results = [];
			foreach($saksi as $item){
				
				// kalkulasi jumlah anggota dari korte berdasarkan tps nya
				// $anggota_by_tps = $tpsModel->getAnggotaKorteByTps($request->village_id,$item->id);
				
				// $jml_anggota = collect($anggota_by_tps)->sum(function($q){
					// return $q->jml_anggota;
				// }); 
				 
				// $anggota_form_manual = $tpsModel->getAnggotaFormManual($request->village_id,$item->id);
				
				// $jml_anggota_form_manual = collect($anggota_form_manual)->sum(function($q){
					// return $q->jml_anggota;
				// });

				$jml_saksi = 1;

				$results[] = [
					'tps_number' => $item->tps_number,
					// 'saksi_dalam' => $item->saksi_dalam > 0 ?  $jml_saksi : $item->saksi_dalam,
					'saksi_dalam' => $item->korte > 0 ? $jml_saksi : 0, 
					'korte' => $item->korte,
					// 'biaya_saksi_dalam' => $item->saksi_dalam * CountAnggaran::saksi()
					'biaya_saksi_dalam' => $item->korte > 0 ? $jml_saksi * CountAnggaran::saksi() : 0 
				]; 
			} 
			
			$jml_saksi_dalam = collect($results)->sum(function($q){
				return $q['saksi_dalam']; 
			});   
			
			$jml_korte = collect($results)->sum(function($q){
				return $q['korte']; 
			}); 
			
			
			$jml_biaya_all_saksi = collect($results)->sum(function($q){
				return $q['biaya_saksi_dalam'];
			});
			
			
			$no = 1;
			$gF = new GlobalProvider(); 
			
			$pdf = PDF::LoadView('pages.report.biayasaksiperdesa', compact('jml_korte','no','results','village','jml_saksi_dalam','gF','jml_biaya_all_saksi'))->setPaper('a4');
			return $pdf->download('BIAYA OPERASIONAL SAKSI DS. '.$village->name.'.pdf');
		
		}elseif($report_type == 'Biaya Saksi Per Kecamatan'){
			
			if (!$request->district_id) return redirect()->back()->with(['warning' => 'Pilih kecamatan terlebih dahulu!']);
			$district = District::select('name')->where('id', $request->district_id)->first();
			// get rekap data saksi 
			$saksi = $tpsModel->getRekapBiayaSaksiPerKecamatan($request);
			
			
			$results_data = [];
			foreach($saksi as $item){
				
				$saksi_desa = $tpsModel->getRekapBiayaSaksiPerDesa($item->id);
			
			 
					$results = [];
					foreach($saksi_desa as $value){
						
						$jml_saksi = 1;

						$results[] = [
							'tps_number' => $value->tps_number,
							'saksi_dalam' => $value->korte > 0 ? $jml_saksi : 0, 
							'korte' => $value->korte,
							'biaya_saksi_dalam' => $value->korte > 0 ? $jml_saksi * CountAnggaran::saksi() : 0 
						]; 
					}
				
				$sum_saksi_dalam = collect($results)->sum(function($q){
					return $q['saksi_dalam'];
				});
				
				$sum_korte = collect($results)->sum(function($q){
					return $q['korte'];
				});
				
				$sum_biaya = collect($results)->sum(function($q){
					return $q['biaya_saksi_dalam'];
				});
				
				
				$jml_saksi = 1;
				
				$results_data[] = [
					'desa' => $item->name,
					'tps' => $item->tps,
					'saksi_dalam' => $sum_saksi_dalam ,
					'korte' => $sum_korte,
					'biaya_saksi_dalam' =>$sum_biaya,
				];
				 
			} 
			
			$jml_tps = collect($results_data)->sum(function($q){
				return $q['tps']; 
			});
			
			$jml_saksi_dalam = collect($results_data)->sum(function($q){
				return $q['saksi_dalam']; 
			});
			
			$jml_korte = collect($results_data)->sum(function($q){
				return $q['korte']; 
			});  
			
			
			$jml_biaya_all_saksi = collect($results_data)->sum(function($q){
				return $q['biaya_saksi_dalam'];
			});
			
			$no = 1;
			$gF = new GlobalProvider(); 
			
			$pdf = PDF::LoadView('pages.report.biayasaksiperkecamatan', compact('jml_tps','jml_korte','no','results_data','district','jml_saksi_dalam','gF','jml_biaya_all_saksi'))->setPaper('a4');
			return $pdf->download('BIAYA OPERASIONAL SAKSI KECAMATAN '.$district->name.'.pdf');
		}
    }
   
    public function setDumyDataPerolehanSuara()
    {
        
        // get tps all
        $tps = DB::table('tps')->get();
        // update kan kepada data tps by id nya
        foreach ($tps as $key => $value) {
            // buat angka random dari 100 - 500
           DB::table('tps')->where('id', $value->id)->update([
            'hasil_suara' => rand(100,200)
           ]);
        }

        return 'OK';
    }

    public function updateCounterHasilSuara()
    {
       // get hasil suara by regency_id
       $tps_hasil_suara = DB::table('tps')->select('hasil_suara','village_id')->where('regency_id', 3602)->get();
      
       // jumlah level kabkot
       $jml_kabkot = collect($tps_hasil_suara)->sum(function($q){
        return $q->hasil_suara;
       });

        DB::table('counter_hasil_suara_village')->update([
            'regency_id'  => 360,
            'hasil_suara' => $jml_kabkot
        ]);

       return 'OK';

       // jumlah level kecamatan

       // jumlah level desa
    }
	
	public function updateTpsIdKordes(Request $request)
	{
		$sql = "SELECT b.id, b.name, b.tps_id, c.tps_number, d.NOMOR_TPS,
				(
					SELECT id from tps  WHERE village_id = $request->village_id and tps_number = d.NOMOR_TPS limit 1
				) as tps
				from org_diagram_district as a
				join users as b on a.nik = b.nik 
				left join tps as c on b.tps_id = c.id
				left join new_dpt as d on b.nik = d.NIK 
				WHERE b.tps_id is null ";
		$sql = DB::select($sql);
		
		foreach($sql as $item){
			DB::table('users')->where('id', $item->id)->update([
				'tps_id' => $item->tps
			]); 
		}
				
		return $sql;
	}
	
	public function replaceTpsIdKordesByKecamatan(Request $request)
	{
		DB::beginTransaction();
		try{
			// get desa by kecamatan
			$desa = DB::table('villages')->select('id','name')->where('district_id', $request->district_id)->get();
			
			$results = [];
			foreach($desa as $item){
				$getTps = "SELECT b.id, b.nik, b.name, b.tps_id , c.tps_number, d.name as desa,
							(
								SELECT id from tps WHERE village_id = $item->id and tps_number = c.tps_number 
							) as tps_id_seharusnya
							from org_diagram_village as a 
							join users as b on a.nik = b.nik
							left join tps as c on b.tps_id = c.id
							left join villages as d on c.village_id = d.id 
							WHERE a.village_id = $item->id";
				$getTps = DB::select($getTps);
				
				$results[] = [
					'desa' => $item->name, 
					'tps' => $getTps
				];
			}
			
			// replace tps_id kordes masing2 desa nya
			foreach($results as $key){
				foreach($key['tps'] as $item){
					DB::table('users')->where('id', $item->id)->update([
						'tps_id' =>  $item->tps_id_seharusnya
					]);
				}
				
			} 
			
			DB::commit();
			return 'OK';
		}catch(\Exception $e){
			DB::rollBack();
			return $e->getMessage();
		}
		
	}
	
	public function deleteAnggotaTidakTercoverKorte()
	{
		DB::beginTransaction();
        try {
			
			$sql = "SELECT a.*, 
				(
					SELECT COUNT(a1.id) from org_diagram_rt as a1 where a1.nik = a.nik
				) as korte_or_anggota,
				(
					SELECT COUNT(a2.id) from org_diagram_village  as a2 where a2.nik = a.nik
				) as kordes,
				( 
					SELECT COUNT(a3.id) from org_diagram_district as a3 where a3.nik = a.nik
				) as korcam
				from users as a
				having korte_or_anggota = 0 and kordes = 0 and korcam = 0";
		
		// save kedalam spam
		
			$sql = DB::select($sql); 
			
			// return $sql;
			
			foreach($sql as $item){
				$user = User::where('id', $item->id)->first();
				#save ke tb tmp_spam_user
				TmpSpamUser::create([
					'user_id' => $user->user_id ?? '',
					'number'  => $user->number ?? '',
					'code'    => $user->code ?? '',
					'nik'     => $user->nik ?? '',
					'name'    => $user->name ?? '',
					'gender'  => $user->gender ?? '',
					'place_berth' => $user->place_berth ?? '',
					'date_berth'  => $user->date_berth ?? '',
					'blood_group' => $user->blood_group ?? '',
					'marital_status' => $user->marital_status ?? '',
					'job_id' => $user->job_id ?? '',
					'religion' => $user->religion ?? '',
					'education_id' => $user->education_id ?? '',
					'email' => $user->email ?? '',
					'email_verified_at' => $user->email_verified_at ?? '',
					'password' => $user->password ?? '',
					'address'  => $user->address ?? '',
					'village_id' => $user->village_id ?? '',
					'rt' => $user->rt ?? '',
					'rw' => $user->rw ?? '',
					'phone_number' => $user->phone_number ?? '',
					'whatsapp' => $user->whatsapp ?? '',
					'photo' => $user->photo ?? '',
					'ktp' => $user->ktp ?? '',
					'level' => $user->level ?? '',
					'cby' => $user->cby ?? '',
					'saved_nasdem' => $user->saved_nasdem ?? '',
					'activate_token' => $user->activate_token ?? '',
					'status' => $user->status ?? '',
					'remember_token' => $user->remember_token ?? '',
					'set_admin' => $user->set_admin ?? '',
					'category_inactive_member_id' => 0 ?? '',
					'reason' => 'Tidak Tercover',
					'created_at' => $user->created_at ?? '',
					'updated_at' => date('Y-m-d H:i:s') ?? ''
					]);
					
					User::where('id', $item->id)->delete();
			}
			
			

           

            #mekanisme sortir idx jika ada yang terhapus
            DB::commit();
            return ResponseFormatter::success([
                'message' => 'Berhasil spam anggota!'
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'message' => 'Something when wrong!',
                'error'   => $e->getMessage()
            ]);
        }
		
            
	}


}
