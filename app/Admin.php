<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use Notifiable;
    protected $guarded = [];

    public function getAdmins()
    {
         $sql = "SELECT b.photo, b.id as user_id,  b.name, b.level, d.name as district, e.name as regency, f.name as province, count(b.id) as total_data
                from users as a
                join users as b on a.cby = b.id
                join villages as c on b.village_id = c.id
                join districts as d on c.district_id = d.id 
                join regencies as e on d.regency_id = e.id 
                join provinces as f on e.province_id = f.id
                where a.village_id is not null 
                group by b.id, b.name, b.level, d.name, e.name, f.name
                order by count(b.id) desc";
        return DB::select($sql);
    }
}
