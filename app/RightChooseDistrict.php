<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RightChooseDistrict extends Model
{
    protected $table   = 'right_to_choose_districts';
    protected $guarded = [];

    public function getDataRightChooseDistrict($regencyId){

        $sql = DB::table('right_to_choose_districts as a')
               ->select('a.district_id','a.count_tps','a.count_vooter','a.choose','b.name')
               ->join('districts as b','a.district_id','=','b.id')
               ->where('a.regency_id', $regencyId)
               ->get();

        return $sql;

    }

}
