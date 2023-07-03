<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Witness extends Model
{
    protected $table   = 'witnesses';
    protected $guarded = [];

    public function getDataWitnrsses($tpsId){

        $sql = DB::table('witnesses as a')
               ->select('b.name','b.address','c.name as village','a.created_at','a.status','a.id')
               ->join('users as b','a.user_id','=','b.id')
               ->join('villages as c','b.village_id','=','c.id')
               ->where('a.tps_id', $tpsId)
               ->get();

        return $sql;
    }
}
