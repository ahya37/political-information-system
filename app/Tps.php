<?php

namespace App;

use App\Models\Village;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Tps extends Model
{
    protected $table   = 'tps';
    protected $guarded = [];

    public function village(){

        return $this->belongsTo(Village::class);

    }

    public function getDataTpsByTpsId($tpsId){

        $sql = DB::table('tps as a')
               ->select('a.tps_number','b.name as village')
               ->join('villages as b','a.village_id','=','b.id')
               ->where('a.id', $tpsId)
               ->first();

        return $sql;
    }

    public function getTotalTpsByDistrictId($id){

        $sql = DB::table('villages as a')
                   ->select(DB::raw('count(b.id) as tps'))
                   ->join('tps as b','a.id','=','b.village_id')
                   ->where('a.district_id', $id)
                   ->first();
        return $sql;
    }
}
