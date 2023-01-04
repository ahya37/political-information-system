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

    public function store(Request $request){

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

    public function reportPdf(){

        // $village_id = request('village_id');
        $village_id = 3602011002;

        $group_koordinator = DB::table('koordinator')
                            ->select('rt','rw','village_id')
                            ->where('village_id', $village_id)
                            ->orderBy('rt','asc')
                            ->groupBy('rt','rw','village_id')
                            ->get();

        $result = [];
        foreach ($group_koordinator as $value) {
            $tim_referal = DB::table('users as a')
                            ->select('a.name as referal', DB::raw('count(b.id) as jml_referal'))
                            ->join('users as b','a.id','=','b.user_id')
                            ->where('b.rt', $value->rt)
                            ->where('b.rw', $value->rw)
                            ->where('b.village_id', $value->village_id)
                            ->where('a.rt', $value->rt)
                            ->where('a.rw', $value->rw)
                            ->where('a.village_id', $value->village_id)
                            ->groupBy('a.name')
                            ->orderByRaw('count(b.id) DESC')
                            ->get();
            $result[] = [
                'rt' => $value->rt,
                'rw' => $value->rw,
                'koordinator' => DB::table('koordinator')->select('name')->where('village_id', $village_id)->where('rt', $value->rt)->where('rw', $value->rw)->orderBy('name','asc')->get(),
                'jumlah_anggota_rt' => DB::table('users')->where('village_id', $value->village_id)->where('rt', $value->rt)->where('rw', $value->rw)->count(),
                'tim_referal' =>  $tim_referal
            ];
        }

        $anggota = DB::table('users as a')
                    ->select('a.name','a.address','a.rt','a.rw','b.name as referal')
                    ->join('users as b','a.user_id','=','b.id')
                    ->where('a.village_id', $village_id)
                    ->orderBy('rt','asc')
                    ->orderBy('rw','asc')
                    ->get();

        // $total_anggota_desa = collect($result)->sum(function($q){
        //     return $q['jumlah_anggota_rt'];
        // });

        // tampilkan rt rw berdasarkan desa
        $rt = DB::table('users')->select('rt','rw','village_id')->where('village_id', $village_id)->orderBy('rt','asc')->orderBy('rw','asc')->distinct()->get();
        $result_anggota = [];
        // looping 
            // get data anggota per rt dan rw, dan jumlahkan
        foreach ($rt as $value) {
            $list_anggota = DB::table('users as a')
                            ->select('a.rt','a.rw','a.name','a.address','b.name as referal')
                            ->join('users as b','a.user_id','=','b.id')
                            ->where('a.village_id', $value->village_id)
                            ->where('a.rt', $value->rt)
                            ->where('a.rw', $value->rw)
                            ->orderBy('a.rt','asc')
                            ->orderBy('a.rw','asc')
                            ->get();

            $result_anggota[] = [
                'list_anggota' => $list_anggota,
                'jml_per_rt' => count($list_anggota)
            ];
        }

        $data = [
            'koordinator' => $result,
            'anggota' => $result_anggota
        ];

        $pdf = PDF::LoadView('pages.admin.report.koordinator', compact('data'))->setPaper('a4');
        return $pdf->stream('desa.pdf');

        // return $data;

    }

}
