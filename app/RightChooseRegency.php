<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RightChooseRegency extends Model
{
    protected $table   = 'right_to_choose_regencies';
    protected $guarded = [];
    
    public function getDataRightChooseRegency($provinceId){

        $sql = DB::table('right_to_choose_regencies as a')
               ->select('a.regency_id','a.count_tps','a.count_vooter','a.choose','b.name')
               ->join('regencies as b','a.regency_id','=','b.id')
               ->where('a.province_id', $provinceId)
               ->orderBy('b.name','asc')
               ->get();

        return $sql;

    }
}
