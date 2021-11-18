<?php

namespace App\Http\Controllers;

use App\AdminRegionalVillage;
use App\User;
use Illuminate\Http\Request;

class TestController extends Controller
{
   public function testAdminRegionalVillage()
   {
       $adminRegionalVillage = AdminRegionalVillage::with(['village'])->where('village_id', 3602011002)->get();
       
       $data = [];
       foreach ($adminRegionalVillage as $val) {
           $user_id = explode(',', $val->user_id);
           $members = [];

           foreach ($user_id as $value) {
               $members[] = User::select('id','photo','name')->where('id', $value)->first();
           }

           $data[] = [
               'village_id' => $val->village_id,
               'name' => $val->village->name,
               'members' => $members
           ];
       }
       return response()->json(['data' => $data]);
   }
}
