<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AdminDistrict extends Model
{
    protected $guarded = [];

    public function getDataAdminDistrict()
    {
        $sql = "SELECT a.id, a.name, c.name as district from users as a
                join admin_districts as b on a.id = b.user_id 
                join districts as c on b.district_id = c.id";
        $result = DB::select($sql);
        return $result;
    }

    public function getDataDistrictIdbyAdmin($user_id)
    {
         $sql = "SELECT a.district_id from admin_districts as a
                where a.user_id = $user_id";
        $result = collect(\DB::select($sql))->first();
        return $result;
    }
}
