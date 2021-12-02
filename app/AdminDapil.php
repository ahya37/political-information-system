<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AdminDapil extends Model
{
    protected $table = 'admin_dapils';
    protected $guarded = [];
    public $timestamps = false;

    public function getAdminDapilByUserId($user_id)
    {
        $sql = "SELECT  b.regency_id from admin_dapils as a
                join dapils as b on a.dapil_id = b.id
                where a.admin_user_id = $user_id
                group by b.regency_id";
        $result = collect(\ DB::select($sql))->first();
        return $result;
    }

    public function getCekDapilArea($arvId)
    {
        $sql = "SELECT a.village_id, d.dapil_id from admin_regional_village as a
                join villages as b on a.village_id = b.id 
                join districts as c on b.district_id = c.id
                join dapil_areas as d on c.id = d.district_id 
                where a.id = $arvId";
        $result = collect(\ DB::select($sql))->first();
        return $result;
    }
}
