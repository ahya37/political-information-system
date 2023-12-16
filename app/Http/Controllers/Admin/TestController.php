<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use PDF;
use App\Providers\GlobalProvider;
use App\Helpers\ResponseFormatter;
use App\Imports\checkNikImport;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Facades\Excel as Excels;

class TestController extends Controller
{
    public $excel;
    public function __construct(Excel $excel)
    {
        $this->excel = $excel;
    }

    public function downloadKTAMembersByKortps($idx){


        // // get data anggota by korte
        $members = DB::table('org_diagram_rt as a')
                ->select('a.base', 'a.rt', 'b.name', 'c.name as village', 'd.name as district', 'b.address', 'a.telp', 'e.tps_number','b.photo',
                    'c.id as village_id','c.name as village','d.id as district_id','d.name as district','e.id as regency_id','g.name as regency',
                    'f.id as province_id','f.name as province','b.number','b.address','b.rt','b.rw'
                )
                ->join('users as b', 'b.nik', '=', 'a.nik')
                ->join('villages as c', 'c.id', '=', 'a.village_id')
                ->join('districts as d', 'd.id', '=', 'a.district_id')
                ->join('regencies as g','d.regency_id','=','g.id')
                ->join('provinces as f','g.province_id','=','f.id')
                ->leftJoin('tps as e','b.tps_id','=','e.id')
                ->where('a.pidx', $idx)
                ->where('a.base', 'ANGGOTA')
                ->get();

        

        // mengelompokan collection sebanyak 5 data per kelompok
        $group_members = $members->chunk(3);

        $group_members->each(function($chunk){
            $chunk->toArray();
        });
  
        $no = 1;

        $gF = new GlobalProvider();

       $pdf = PDF::LoadView('pages.report.ktamemberbykortps', compact('card_tags','group_members','gF'))->setPaper('a4','landscape');
        return $pdf->stream('KTA.pdf');


   }

   public function downloadKTAKorcamKordes($districtId)
   {
        return $districtId;
   }

   public function checkNikAnggota(Request $request)
   {

        try {

            $data =  Excels::toCollection(new checkNikImport, request()->file('file'));

            $results = [];
            foreach($data as  $value){
                foreach($value as $item){
                    foreach ($item as $val) {
                        $results[] = DB::table('users')->select('name','nik')->where('nik', $val)->first();
                    }
                }
            }

            return $results;

            } catch (\Exception $th) {
                return $th->getMessage();
            }
    }
}
