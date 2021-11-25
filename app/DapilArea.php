<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DapilArea extends Model
{
    protected $table = 'dapil_areas';
    protected $guarded = [];
    public $timestamps = false;

    public function getSearchDapilByDistrict($district_id)
    {
        $sql = "SELECT b.id as dapil_id from dapil_areas as a
                join dapils as b on a.dapil_id = b.id 
                where a.district_id = $district_id";
        $result = collect(\DB::select($sql))->first();
        return $result;
    }
}
