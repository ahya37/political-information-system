<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class AdminRegionalDistrict extends Model
{
    protected $table = 'admin_regional_district';
    protected $guarded = [];
    public $timestamps = false;

    public function getAdminRegionalDistrictByMember($user_id)
    {
        $sql = "SELECT a.name, b.status from districts as a
                join admin_regional_district as b on a.id = b.district_id
                where b.user_id = $user_id";
        return DB::select($sql);
    }

    public function getAdminRegionalDistrict()
    {
        $sql = "SELECT c.id as user_id, c.photo, b.id as ardId, c.name as member, a.name as district, b.status from districts as a
                join admin_regional_district as b on a.id = b.district_id
                join users as c on b.user_id = c.id ";
        return DB::select($sql);
    }
}
