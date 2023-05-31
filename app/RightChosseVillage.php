<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RightChosseVillage extends Model
{
    protected $table = 'right_to_choose_village';
    protected $guarded = [];

   public function getDataChooseVillage($id)
   {
       $sql = "SELECT b.name , a.choose from right_to_choose_village as a 
                join villages as b on a.village_id = b.id 
                where a.village_id = $id";
        return collect(\DB::select($sql))->first();
   }

   public function getDataRightChooseVillage($districtId){

    $sql = DB::table('right_to_choose_village as a')
           ->select('a.village_id','a.count_tps','a.count_vooter','a.choose','b.name')
           ->join('villages as b','a.village_id','=','b.id')
           ->where('a.district_id', $districtId)
           ->orderBy('a.count_tps','asc')
           ->get();

    return $sql;

}
}
