<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class TargetNumber extends Model
{
    protected $guarded = [];

    public function getTotalTargetMemberNational()
    {
        $sql = "SELECT sum(b.target) as target FROM districts as b
                join regencies as c on b.regency_id = c.id
                join provinces as d on c.province_id = d.id";
        $result = collect(\ DB::select($sql))->first();
        return $result;
    }
}
