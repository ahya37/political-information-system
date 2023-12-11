<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use PDF;
use App\Providers\GlobalProvider;

class TestController extends Controller
{
    public function downloadKTAMembersByKortps($idx){


        // $kor_rt = DB::table('org_diagram_rt as a')
        //     ->select('b.id','a.base', 'a.rt', 'b.name', 'c.name as village', 'd.name as district','e.tps_number',
        //         DB::raw('(select count(b2.id) from users as b2 where b2.user_id= b.id and b2.village_id is not null ) as referal')
        //     )
        //     ->join('users as b', 'b.nik', '=', 'a.nik')
        //     ->join('villages as c', 'c.id', '=', 'a.village_id')
        //     ->join('districts as d', 'd.id', '=', 'a.district_id')
        //     ->join('tps as e','b.tps_id','=','e.id')
        //     ->where('a.idx', $idx)
        //     ->where('a.base', 'KORRT')
        //     ->first();
            
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

        // $data = ['1','2','3','4','5','6'];

        // $data = [
        //     ["id" => 1, "name" => "Nana"],
        //     ["id" => 2, "name" => "Indra"],
        //     ["id" => 3, "name" => "Reyhan"],
        //     ["id" => 4, "name" => "Bayu"],
        //     ["id" => 5, "name" => "Ahya"],
        //     ["id" => 6, "name" => "Kiki"],


        // ];

        // $data  = collect([
        //     (object) [
        //         "id" => 1, "name" => "Nana"
        //     ],
        //     (object) [
        //         "id" => 2, "name" => "Indra"
        //     ],
        //     (object) [
        //         "id" => 3, "name" => "Reyhan"
        //     ],
        //     (object) [
        //         "id" => 4, "name" => "Bayu"
        //     ],
        //      (object) [
        //         "id" => 5, "name" => "Ahya"
        //     ],
        //     (object) [
        //         "id" => 6, "name" => "Kiki"
        //     ],
        //     (object) [
        //         "id" => 7, "name" => "Kiki"
        //     ],
        //     (object) [
        //         "id" => 8, "name" => "Kiki"
        //     ],
        //     (object) [
        //         "id" => 9, "name" => "Kiki"
        //     ],
        //     (object) [
        //         "id" => 10, "name" => "Kiki"
        //     ],
        //     (object) [
        //         "id" => 12, "name" => "Kiki"
        //     ]
        // ]);


        // $group_members = $members->split(5);
        // $group_members->toArray();

        // $collection = collect([1, 2, 3, 4, 5, 6, 7, 8, 9,10,11,12,13,14,15,16,17,18,19,20,21,22]);

        // $chunks = $collection->chunk(5);

        // $chunks->each(function ($chunk) {
        //     // Do something with each chunk
        //     dump($chunk->toArray());
        // });

        // mengelompokan collection sebanyak 5 data per kelompok
        $group_members = $members->chunk(3);

        $group_members->each(function($chunk){
            $chunk->toArray();
        });

        // dd($group_members);

         // hitung jumlah data
        // $count_data = count($data);
        // // dd($count_data);
        // $pembagi    = 5; 
        // $sisa_bagi  = $count_data%$pembagi;
        // $hasil_bagi = ($count_data-$sisa_bagi) / $pembagi;

        // $jml_tags = $hasil_bagi + $sisa_bagi;
        // // dd($jml_tags);

        // $start = 0; // memulai objek untuk ditampilkan dari index ke 0
        // $end   = 5; // batas jumlah objek yg akan tampil per baris sampai index ke 4

        $card_tags   = []; 

        // for ($i=0; $i <= $jml_tags ; $i++) { 

        //     // tampilkan data card anggota per variabel adalah 4 array

        //     if ($i != '') {
        //         $no_start = ($end * $i) - 4;
        //         $no_end   = $end * $i;

        //         $card_tags[] = [
        //             'tags' => [
        //                 $i => $data->where('id','>=', $no_start)->where('id','<=', $no_end)
        //             ]
        //         ];
        //     }

        // }

        // dd($card_tags);
        
 
        $no = 1;

        $gF = new GlobalProvider();

       $pdf = PDF::LoadView('pages.report.ktamemberbykortps', compact('card_tags','group_members','gF'))->setPaper('a4','landscape');
        return $pdf->stream('KTA.pdf');


   }
}
