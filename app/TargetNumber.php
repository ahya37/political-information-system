<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class TargetNumber extends Model
{
    protected $guarded = [];

    public function getTotalTargetMemberNational()
    {
        $sql = "SELECT sum(a.target) as target FROM target_numbers as a
                join districts as b on a.district_id = b.id
                join regencies as c on b.regency_id = c.id
                join provinces as d on c.province_id = d.id";
        $result = collect(\ DB::select($sql))->first();
        return $result;
    }
}
