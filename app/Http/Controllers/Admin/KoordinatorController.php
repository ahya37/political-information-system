<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\KoordinatorImport;
use DB;
use App\Koordinator;
use PDF;
use App\Providers\GlobalProvider;
use App\User;
use App\KorPusat;
use App\KorDapil;
use App\CekUidKoordinator;

class KoordinatorController extends Controller
{
    public function create(){
        return view('pages.admin.koordinator.create');
    }

    public function index(){
        
        return view('pages.admin.koordinator.index');
    }

    public function upload(Request $request){

        DB::beginTransaction();
        try {

            Excel::import(new KoordinatorImport, request()->file('file'));

            DB::commit();
           
            return redirect()->route('admin-koordinator-create')->with(['success' => 'Berhasil upload file']);

        } catch (\Exception $e) {

            DB::rollback();
            return redirect()->route('admin-koordinator-create')->with(['error' => 'Gagal upload file']);
        }
    }

    public function reportPdfKoordinator(){

        $gF = new GlobalProvider();

        $village_id = request('village_id');
        // $village_id = 3602011002;

        // $group_koordinator = DB::table('koordinator')
        //                     ->select('rt','village_id')
        //                     ->where('village_id', $village_id)
        //                     ->orderBy('rt','asc')
        //                     ->groupBy('rt','village_id')
        //                     ->get();

        // KEC. WANASALAM
            // wanasalam = 24 , 
            // sukatani  = 20, 
            // cipedang  = 18
            // muara     = 27

            // bejod     = 26
            // katapang  = 14
            // CISARAP   = 22
            // PARUNG SARI = 18
            // CIPEUCANG  = 13
            // PARUNG PANJANG = 13
            // CILANGKAP = 15
            // KARANG PAMINDANGAN = 12
            // CIKEUSIK = 17

        // KEC. CILOGRANG
            // CIBARENO = 5
            // CILOGRANG = 12

        $group_koordinator2 = [
            ['rt' => 1, 'village_id' => $village_id],
            ['rt' => 2, 'village_id' => $village_id],
            ['rt' => 3, 'village_id' => $village_id],
            ['rt' => 4, 'village_id' => $village_id],
            ['rt' => 5, 'village_id' => $village_id],
            ['rt' => 6, 'village_id' => $village_id],
            ['rt' => 7, 'village_id' => $village_id],
            // ['rt' => 8, 'village_id' => $village_id],
            // ['rt' => 9, 'village_id' => $village_id],
            // ['rt' => 10, 'village_id' => $village_id],
            // ['rt' => 11, 'village_id' => $village_id],
            // ['rt' => 12, 'village_id' => $village_id],
            // ['rt' => 13, 'village_id' => $village_id],
            // ['rt' => 14, 'village_id' => $village_id],
            // ['rt' => 15, 'village_id' => $village_id],
            // ['rt' => 16, 'village_id' => $village_id],
            // ['rt' => 17, 'village_id' => $village_id],
            // ['rt' => 18, 'village_id' => $village_id],
            // ['rt' => 19, 'village_id' => $village_id],
            // ['rt' => 20, 'village_id' => $village_id],
            // ['rt' => 21, 'village_id' => $village_id],
            // ['rt' => 22, 'village_id' => $village_id],
            // ['rt' => 23, 'village_id' => $village_id],
            // ['rt' => 24, 'village_id' => $village_id],
            // ['rt' => 25, 'village_id' => $village_id],
            // ['rt' => 26, 'village_id' => $village_id],
            // ['rt' => 27, 'village_id' => $village_id],
        ];

        // return $group_koordinator2;

        $koordinator = [];
        foreach ($group_koordinator2 as $value) {
            $tim_referal = DB::table('users as a')
                            ->select('a.name as referal','a.rt','a.address', DB::raw('count(b.id) as jml_referal'))
                            ->join('users as b','a.id','=','b.user_id')
                            ->where('b.rt', $value['rt'])
                            // ->where('b.rw', $value->rw)
                            ->where('b.village_id', $value['village_id'])
                            ->where('a.rt', $value['rt'])
                            // ->where('a.rw', $value->rw)
                            ->where('a.village_id', $value['village_id'])
                            ->groupBy('a.name','a.rt','a.address')
                            ->orderByRaw('count(b.id) DESC')
                            ->distinct()
                            ->get();
            $koordinator[] = [
                'rt' => $value['rt'],
                'koordinator' => DB::table('koordinator')->select('name')->where('village_id', $village_id)->where('rt', $value['rt'])->orderBy('name','asc')->distinct()->get(),
                'jumlah_anggota_rt' => DB::table('users')->where('village_id', $value['village_id'])->where('rt', $value['rt'])->count(),
                'tim_referal' =>  $tim_referal
            ];
        }


        // return $result;
        $village = DB::table('villages as a')
                    ->join('districts as b','a.district_id','=','b.id')
                    ->select('a.name','b.name as district')
                    ->where('a.id', $village_id)
                    ->first();

        // JUMLAH ANGGOTA TIAP DESA DI PER DESA
    //    $all_rt =  DB::table('users')
    //                         ->select('rt')
    //                         ->where('village_id', $village_id)
    //                         ->orderBy('rt','asc')
    //                         ->orderBy('rw','asc')
    //                         ->distinct()
    //                         ->get();

        $list_rt = [];
        foreach ($group_koordinator2 as $value) {
            $list_rt[] = [
                'rt' => $value['rt'],
                'jumlah' => DB::table('users')->where('village_id', $village_id)->where('rt', $value['rt'])->count(),
            ];
        }

        $total_jumlah_anggota = collect($list_rt)->sum(function($q){
            return $q['jumlah'];
        });

        $total_jumlah_anggota = $gF->decimalFormat($total_jumlah_anggota);

        // TIM REFERAL BERDASARKAN DESA 
        $tim_referal_in_village = DB::table('users as a')
                                ->join('users as b','a.id','=','b.user_id')
                                ->select('a.name','a.address','a.rt','a.rw', DB::raw('count(b.id) as jml_referal'))
                                ->where('a.village_id', $village_id)
                                ->where('b.village_id', $village_id)
                                ->groupBy('a.name','a.address','a.rt','a.rw')
                                ->orderByRaw('count(b.id) DESC')
                                ->get();
        

        // return $tim_referal_in_village;

        $pdf = PDF::LoadView('pages.admin.report.koordinator', compact('koordinator','village','list_rt','total_jumlah_anggota','tim_referal_in_village','gF'))->setPaper('a4');
        return $pdf->download('KOORDINATOR DESA '.$village->name.'.pdf');

        // return $data;

    }

    public function reportAnggotaPerRt(){

        $village_id = request('village_id');
        $rt = request('rt');
        
        $anggota = [];

        $anggota =  DB::table('users as a')
                            ->select('a.rt','a.rw','a.name','a.address','b.name as referal')
                            ->join('users as b','a.user_id','=','b.id')
                            ->where('a.village_id', $village_id)
                            ->where('a.rt', $rt)
                            ->orderBy('a.rt','asc')
                            ->orderBy('a.rw','asc')
                            ->orderBy('a.name','asc')
                            ->get();

        // return $anggota;

        $data = [
            'anggota' => $anggota,
            'jumlah' => count($anggota)
        ];

        $village = DB::table('villages as a')
                    ->join('districts as b','a.district_id','=','b.id')
                    ->select('a.name','b.name as district')
                    ->where('a.id', $village_id)
                    ->first();

        $pdf = PDF::LoadView('pages.admin.report.koordinator-lampiran', compact('data','village'))->setPaper('a4');
        return $pdf->download('RT '.$rt.'.LAMPIRAN ANGGOTA DESA '.$village->name.'.pdf');

        // return $data;

    }

    public function lisRTVillage(){

        $village_id = request('village_id');
        $data_rt = DB::table('users')->select('rt','rw')->where('village_id', $village_id)->orderBy('rt','asc')->orderBy('rw','asc')->distinct()->get();

        return $data_rt;

    }

    public function store(Request $request){

        DB::beginTransaction();
        try {

            DB::commit();

            Koordinator::create([
                'name' => $request->name,
                'rt' => $request->rt,
                'rw' => $request->rw,
                'dapil_id' => $request->dapil_id,
                'district_id' => $request->district_id,
                'village_id' => $request->village_id,
                'recomender_user_id' => $request->recomender_user_id,
            ]);
           
            return response()->json([
                'message' => 'Bershasil menyimpan'
            ],200);

        } catch (\Exception $e) {

            DB::rollback();
            return response()->json([
                'message' => 'Gagal menyimpan'
            ],401);
        }
    }

    public function uploadApi(Request $request){

        DB::beginTransaction();
        try {

            Excel::import(new KoordinatorImport, request()->file('file'));

            DB::commit();
           
            return response()->json([
                'message' => 'Bershasil upload'
            ],200);

        } catch (\Exception $e) {

            return response()->json([
                'message' => 'Gagal upload'
            ],401);
        }
    }

    public function absensi(){
        
        $village_id = request('village_id');

        $rt = [
            ['rt' => 1, 'village_id' => $village_id],
            ['rt' => 2, 'village_id' => $village_id],
            ['rt' => 3, 'village_id' => $village_id],
            ['rt' => 4, 'village_id' => $village_id],
            ['rt' => 5, 'village_id' => $village_id],
            ['rt' => 6, 'village_id' => $village_id],
            ['rt' => 7, 'village_id' => $village_id],
            // ['rt' => 8, 'village_id' => $village_id],
            // ['rt' => 9, 'village_id' => $village_id],
            // ['rt' => 10, 'village_id' => $village_id],
            // ['rt' => 11, 'village_id' => $village_id],
            // ['rt' => 12, 'village_id' => $village_id],
            // ['rt' => 13, 'village_id' => $village_id],
            // ['rt' => 14, 'village_id' => $village_id],
            // ['rt' => 15, 'village_id' => $village_id],
            // ['rt' => 16, 'village_id' => $village_id],
            // ['rt' => 17, 'village_id' => $village_id],
            // ['rt' => 18, 'village_id' => $village_id],
            // ['rt' => 19, 'village_id' => $village_id],
            // ['rt' => 20, 'village_id' => $village_id],
            // ['rt' => 21, 'village_id' => $village_id],
            // ['rt' => 22, 'village_id' => $village_id],
            // ['rt' => 23, 'village_id' => $village_id],
            // ['rt' => 24, 'village_id' => $village_id],
            // ['rt' => 25, 'village_id' => $village_id],
            // ['rt' => 26, 'village_id' => $village_id],
            // ['rt' => 27, 'village_id' => $village_id],
        ];

        // return $group_koordinator2;

        

        $absensi = [];
        foreach ($rt as $value) {

            $form_absensi = [
                [
                    'rt' => $value['rt'],
                    'name' => '',
                    'address' => '',
                    'jml_referal' => ''
                ],
            ];

            $form_absensi2 = [
                [
                    'rt' => $value['rt'],
                    'name' => '',
                    'address' => '',
                    'jml_referal' => ''
                ],
            ];

            $form_absensi3 = [
                [
                    'rt' => $value['rt'],
                    'name' => '',
                    'address' => '',
                    'jml_referal' => ''
                ],
            ];


            $tim_referal = DB::table('users as a')
                            ->select('a.rt','a.name','a.address', DB::raw('count(b.id) as jml_referal'))
                            ->join('users as b','a.id','=','b.user_id')
                            ->where('b.rt', $value['rt'])
                            // ->where('b.rw', $value->rw)
                            ->where('b.village_id', $value['village_id'])
                            ->where('a.rt', $value['rt'])
                            // ->where('a.rw', $value->rw)
                            ->where('a.village_id', $value['village_id'])
                            ->groupBy('a.rt','a.name','a.address')
                            ->orderByRaw('a.rt','asc')
                            ->distinct()
                            ->get();

            $absensi[] = [
                'rt' => $value['rt'],
                'absensi' => count($tim_referal) === 0 ? array_merge(array($form_absensi2), array($form_absensi3)) :  array_merge(array($tim_referal), array($form_absensi)),
                // 'jumlah_anggota_rt' => DB::table('users')->where('village_id', $value['village_id'])->where('rt', $value['rt'])->count(),
            ];
        }

        $village = DB::table('villages as a')
                    ->join('districts as b','a.district_id','=','b.id')
                    ->select('a.name','b.name as district')
                    ->where('a.id', $village_id)
                    ->first();

        return $absensi;
        

        $pdf = PDF::LoadView('pages.admin.report.absensi', compact('absensi','village'))->setPaper('a4');
        return $pdf->stream('ABSENSI DESA '.$village->name.'.pdf');
        

    }

    public function listKorPusat(){

        $koordinator = KorPusat::select('id','ketua_name','sekre_name','benda_name')->get();
        return view('pages.admin.koordinator.index-kor-pusat', compact('koordinator'));
    }

    public function createKorPusat(){
        return view('pages.admin.koordinator.create-kor-pusat');
    }
    
    public function saveKorPusat(Request $request){

        DB::beginTransaction();        
        try {
            #cek jika korpusat sudah ada, jangan tambah lagi
            $korpus = KorPusat::count();
            
            if ($korpus > 0) {
                
                return redirect()->back()->with(['warning' => 'Koordinator Pusat sudah ada!']);
    
            }else{
                
                    $nik_ketua     = $request->nik_ketua;
                    $nik_sekre     = $request->nik_sekre;
                    $nik_bendahara = $request->nik_bendahara;
            
                    // cari user id berdasarkan nik nya
                    $user = new User();
            
                    $ketua      = $user->select('id','name')->where('nik', $nik_ketua)->first();
                    $sekre      = $user->select('id','name')->where('nik', $nik_sekre)->first();
                    $bendahara  = $user->select('id','name')->where('nik', $nik_bendahara)->first();
            
                    if ($ketua == null || $sekre == null || $bendahara == null) {
            
                        return redirect()->back()->with(['error' => 'NIK tidak ditemukan']);
            
                    }else{
            
                        KorPusat::create([
                            'ketua_uid' => $ketua->id,
                            'ketua_name' => $ketua->name,
                            'sekre_uid' => $sekre->id,
                            'sekre_name' => $sekre->name,
                            'benda_uid' => $bendahara->id,
                            'benda_name' => $bendahara->name
                        ]);
    
                        $this->saveCekUidKoordinator($ketua, $sekre, $bendahara);

                        DB::commit();
                        return redirect()->route('admin-koordinator-pusat-index')->with(['success' => 'Data telah disimpan!']);
            
                    }
            }

        } catch (\Exception $e) {
            DB::rollback();
            return 'Terjadi Kesalahan'. $e->getMessage();

        }



    }

    public function createKorDapil($id){

        return view('pages.admin.koordinator.index-kor-dapil', compact('id'));

    }

    public function saveKorDapil(Request $request){

        $validated = $request->validate([
            'korpus' => 'required',
            'regency_id' => 'required',
            'dapil_id'  =>'required',
            'nik_ketua' => 'required',
            'nik_sekre'  =>'required',
            'nik_sekre'  =>'required',
            'nik_bendahara'=> 'required',
        ]);

        $nik_ketua     = $request->nik_ketua;
        $nik_sekre     = $request->nik_sekre;
        $nik_bendahara = $request->nik_bendahara;
        
        // cari user id berdasarkan nik nya
        $user = new User();
        
        $ketua      = $user->select('id','name')->where('nik', $nik_ketua)->first();
        $sekre      = $user->select('id','name')->where('nik', $nik_sekre)->first();
        $bendahara  = $user->select('id','name')->where('nik', $nik_bendahara)->first();

        #cek apakah user id tersebut sudah ada di tb cek_uid_koordinator
        $cekUidKor =  CekUidKoordinator::all();

        #jika ada beri notif, tidak boleh dua jabatan
        #jika belum ada lanjut simpan

    }

    public function saveCekUidKoordinator($ketua, $sekre,$bendahara){

        $cekUidKoordinator = new CekUidKoordinator();
        #lakukan simpan user_id sebanyak jumlah nik

        $cekUidKoordinator->create(['user_id' => $ketua->id]);
        $cekUidKoordinator->create(['user_id' => $sekre->id]);
        $cekUidKoordinator->create(['user_id' => $bendahara->id]);

        return true;

    }

}
