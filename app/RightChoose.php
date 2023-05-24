<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RightChoose extends Model
{
    public function getDataRightChooseProvince(){

        $sql = "select b.name as province, a.count_tps, a.count_vooter from right_to_choose_provinces as a 
                join provinces as b on a.province_id = b.id";

        return DB::select($sql);

    }
}
