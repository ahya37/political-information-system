<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\GlobalProvider;
use DB;
use PDF;

class DocumentController extends Controller
{
    public function downloadFormatFormKortps()
    {
        $file = public_path('/docs/util/format-upload-form.xlsx');
        $headers = array(
            'Content-Type:application/vnd.ms-excel',
        );

        return response()->download($file, 'format-upload-form.xlsx', $headers);
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

       $pdf = PDF::LoadView('pages.report.ktamemberbykortps', compact('group_members','gF'))->setPaper('a4','landscape');
        return $pdf->download('KTA.pdf');


   }

    public function downloadKTAKorcamKordes($districtId)
    {

        // get data korcam
        $korcam =  DB::table('org_diagram_district as a')
                ->select('b.rt', 'b.name', 'c.name as village', 'd.name as district', 'b.address', 'a.telp', 'e.tps_number','b.photo',
                    'c.id as village_id','c.name as village','d.id as district_id','d.name as district','e.id as regency_id','g.name as regency',
                    'f.id as province_id','f.name as province','b.number','b.address','b.rw'
                )
                ->join('users as b', 'b.nik', '=', 'a.nik')
                ->join('villages as c', 'c.id', '=', 'b.village_id')
                ->join('districts as d', 'd.id', '=', 'c.district_id')
                ->join('regencies as g','d.regency_id','=','g.id')
                ->join('provinces as f','g.province_id','=','f.id')
                ->leftJoin('tps as e','b.tps_id','=','e.id')
                ->where('a.district_id', $districtId)
                ->get();

        // get data kordes
        $kordes =  DB::table('org_diagram_village as a')
                ->select('b.rt', 'b.name', 'c.name as village', 'd.name as district', 'b.address', 'a.telp', 'e.tps_number','b.photo',
                    'c.id as village_id','c.name as village','d.id as district_id','d.name as district','e.id as regency_id','g.name as regency',
                    'f.id as province_id','f.name as province','b.number','b.address','b.rw'
                )
                ->join('users as b', 'b.nik', '=', 'a.nik')
                ->join('villages as c', 'c.id', '=', 'b.village_id')
                ->join('districts as d', 'd.id', '=', 'c.district_id')
                ->join('regencies as g','d.regency_id','=','g.id')
                ->join('provinces as f','g.province_id','=','f.id')
                ->leftJoin('tps as e','b.tps_id','=','e.id')
                ->where('a.district_id', $districtId)
                ->get();

        // // gabungkan data nya
        $results = $korcam->merge($kordes);


        // mengelompokan collection sebanyak 5 data per kelompok
        $group_members = $results->chunk(3);

        $group_members->each(function($chunk){
            $chunk->toArray();
        });
 
        $no = 1;

        $kecamatan = DB::table('districts')->select('name')->where('id', $districtId)->first();

        $gF = new GlobalProvider();

       $pdf = PDF::LoadView('pages.report.ktamemberbykortps', compact('group_members','gF'))->setPaper('a4','landscape');
        return $pdf->stream('KTA TIM KEC.'.$kecamatan->name.'.pdf');

   }



}
