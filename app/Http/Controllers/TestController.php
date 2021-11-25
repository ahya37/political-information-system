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
}
