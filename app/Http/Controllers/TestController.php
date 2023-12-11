<?php

namespace App\Http\Controllers;

use App\AdminRegionalVillage;
use App\GroupFigureVillage;
use App\User;
use Illuminate\Http\Request;

class TestController extends Controller
{
   public function testGretFigure()
   {
       $groupFigure = GroupFigureVillage::all();
       $data = [];
       foreach ($groupFigure as $value) {
           $user = json_decode($value->user);
           $member = [];
           foreach ($user as $val) {
               $member[] = User::select('id','name','photo','phone_number','whatsapp')->where('id', $val->user_id)->first();
           }
           $data[] = [
               'village_id' => $value->village_id,
               'user' => $member
           ];
       }
       return response()->json($data);
   }

   public function downloadKTAMembersByKortps($idx){

        $kor_rt = DB::table('org_diagram_rt as a')
            ->select('b.id','a.base', 'a.rt', 'b.name', 'c.name as village', 'd.name as district','e.tps_number',
                DB::raw('(select count(b2.id) from users as b2 where b2.user_id= b.id and b2.village_id is not null ) as referal')
            )
            ->join('users as b', 'b.nik', '=', 'a.nik')
            ->join('villages as c', 'c.id', '=', 'a.village_id')
            ->join('districts as d', 'd.id', '=', 'a.district_id')
            ->join('tps as e','b.tps_id','=','e.id')
            ->where('a.idx', $idx)
            ->where('a.base', 'KORRT')
            ->first();
            
        // get data anggota by korte
        $members = DB::table('org_diagram_rt as a')
                ->select('a.base', 'a.rt', 'b.name', 'c.name as village', 'd.name as district', 'b.address', 'a.telp', 'e.tps_number',
                    DB::raw('TIMESTAMPDIFF(YEAR, b.date_berth, NOW()) as usia')
                )
                ->join('users as b', 'b.nik', '=', 'a.nik')
                ->join('villages as c', 'c.id', '=', 'a.village_id')
                ->join('districts as d', 'd.id', '=', 'a.district_id')
                ->leftJoin('tps as e','b.tps_id','=','e.id')
                ->where('a.pidx', $idx)
                ->where('a.base', 'ANGGOTA')
                ->get();
 
            $no = 1;

        dd($members);


   }
}
