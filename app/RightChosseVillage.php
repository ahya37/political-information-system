<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
}
