<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RightChooseProvince extends Model
{
    protected $table   = 'right_to_choose_provinces';
    protected $guarded = [];

    public function getDataRightChooseProvince(){

        $sql = DB::table('right_to_choose_provinces as a')
               ->select('a.province_id','a.count_tps','a.count_vooter','a.choose','b.name')
               ->join('provinces as b','a.province_id','=','b.id')
               ->get();
        return $sql;

    }
}
