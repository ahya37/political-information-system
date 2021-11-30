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

    public function getListAdminDistrict($district_id)
    {
        $sql = "SELECT f.id as user_id, f.photo, f.name, f.code as referal, g.name as village, h.name as district, i.name as regency, j.name as province, whatsapp , f.phone_number from admin_dapils as a
            join admin_dapil_district as b on a.id = b.admin_dapils_id
            join districts as c on b.district_id = c.id
            join users as f on a.admin_user_id = f.id
            join villages as g on f.village_id = g.id 
            join districts as h on g.district_id = h.id 
            join regencies as i on h.regency_id = i.id
            join provinces as j on i.province_id = j.id 
            where c.id = $district_id";
        return DB::select($sql);
    }
}
