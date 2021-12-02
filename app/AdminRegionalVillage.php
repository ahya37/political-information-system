<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class AdminRegionalVillage extends Model
{
    protected $table = 'admin_regional_village';
    protected $guarded = [];
    public $timestamps = false;

    public function getAdminRegionalVillageByMember($user_id)
    {
        $sql = "SELECT a.name, b.status from villages as a 
                join admin_regional_village as b  on a.id = b.village_id
                where b.user_id = $user_id";
        return DB::select($sql);
    }

    public function getAdminRegionalVillage()
    {
        $sql = "SELECT c.id as user_id, c.photo, c.name as member, b.id as arvId, a.name as village, d.name as district, b.status from villages as a 
                join admin_regional_village as b  on a.id = b.village_id
                join users as c on b.user_id = c.id
                join districts as d on a.district_id = d.id ";
        return DB::select($sql);
    }
    
    public function getListAdminVillage($villageID)
    {
        $sql = "SELECT a.id as user_id , a.code as referal, a.name, a.photo, a.phone_number , a.whatsapp, c.name  as village , d.name as district, e.name as regency, f.name as province 
                from users as a
                join admin_dapils as b on a.id = b.admin_user_id 
                join villages as c on a.village_id = c.id 
                join districts as d on c.district_id = d.id 
                join regencies as e  on d.regency_id = e.id 
                join provinces as f on e.province_id = f.id 
                join admin_dapil_village as g on b.id = g.admin_dapil_id 
                where g.village_id = $villageID";
        return DB::select($sql);
    }

}
