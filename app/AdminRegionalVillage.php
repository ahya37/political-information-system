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
        $sql = "SELECT a.name, b.status from villages as a 
                join admin_regional_village as b  on a.id = b.village_id ";
        return DB::select($sql);
    }

}
