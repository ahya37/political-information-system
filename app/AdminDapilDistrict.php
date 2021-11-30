<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AdminDapilDistrict extends Model
{
    protected $table = 'admin_dapil_district';
    protected $guarded = [];
    public $timestamps = false;

    public function getListDistrict($adminDapilDistrictId)
    {
        $sql = "SELECT a.id, a.district_id, b.name as district from admin_dapil_district as a
                join districts as b on a.district_id = b.id where a.id = $adminDapilDistrictId ";
        $result = DB::select($sql);
        return $result;
    }
}
