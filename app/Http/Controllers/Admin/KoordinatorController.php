<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\KoordinatorImport;
use DB;
use App\Koordinator;
use PDF;

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

        $village_id = request('village_id');
        // $village_id = 3602011002;

        $group_koordinator = DB::table('koordinator')
                            ->select('rt','village_id')
                            ->where('village_id', $village_id)
                            ->orderBy('rt','asc')
                            ->groupBy('rt','village_id')
                            ->get();

        $koordinator = [];
        foreach ($group_koordinator as $value) {
            $tim_referal = DB::table('users as a')
                            ->select('a.name as referal','a.rt','a.address', DB::raw('count(b.id) as jml_referal'))
                            ->join('users as b','a.id','=','b.user_id')
                            ->where('b.rt', $value->rt)
                            // ->where('b.rw', $value->rw)
                            ->where('b.village_id', $value->village_id)
                            ->where('a.rt', $value->rt)
                            // ->where('a.rw', $value->rw)
                            ->where('a.village_id', $value->village_id)
                            ->groupBy('a.name','a.rt','a.address')
                            ->orderByRaw('count(b.id) DESC')
                            ->distinct()
                            ->get();
            $koordinator[] = [
                'rt' => $value->rt,
                'koordinator' => DB::table('koordinator')->select('name')->where('village_id', $village_id)->where('rt', $value->rt)->orderBy('name','asc')->distinct()->get(),
                'jumlah_anggota_rt' => DB::table('users')->where('village_id', $value->village_id)->where('rt', $value->rt)->count(),
                'tim_referal' =>  $tim_referal
            ];
        }


        // return $result;
        $village = DB::table('villages as a')
                    ->join('districts as b','a.district_id','=','b.id')
                    ->select('a.name','b.name as district')
                    ->where('a.id', $village_id)
                    ->first();

        
        // return $koordinator;

        $pdf = PDF::LoadView('pages.admin.report.koordinator', compact('koordinator','village'))->setPaper('a4');
        return $pdf->stream('KOORDINATOR DESA '.$village->name.'.pdf');

        // return $data;

    }

    public function reportAnggotaPerRt(){

        $village_id = request('village_id');
        $rt = request('rt');
        // $village_id = 3602011002;

        // tampilkan rt rw berdasarkan desa
        // $data_rt = DB::table('users')->select('rt','rw','village_id')->where('village_id', $village_id)->orderBy('rt','asc')->orderBy('rw','asc')->distinct()->get();
        $anggota = [];

        // looping 
            // get data anggota per rt dan rw, dan jumlahkan
        // foreach ($data_rt as $value) {
        //     $list_anggota = DB::table('users as a')
        //                     ->select('a.rt','a.rw','a.name','a.address','b.name as referal')
        //                     ->join('users as b','a.user_id','=','b.id')
        //                     ->where('a.village_id', $village_id)
        //                     ->where('a.rt', $rt)
        //                     // ->where('a.rw', $value->rw)
        //                     ->orderBy('a.rt','asc')
        //                     ->orderBy('a.rw','asc')
        //                     ->get();

        //     $anggota[] = [
        //         'list_anggota' => $list_anggota,
        //         'jml_per_rt' => count($list_anggota)
        //     ];
        // }

        // return $anggota;

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
        return $pdf->stream('LAMPIRAN ANGGOTA DESA '.$village->name.'.pdf');

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

}
