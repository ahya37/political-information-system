<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Referal extends Model
{
    public function getReferals()
    {
        $sql = "SELECT b.id, b.name , count(b.id) as total_referal
                from users as a
                join users as b on a.user_id = b.id
                left join villages as c on b.village_id = c.id
                left join districts as   d on c.district_id = d.id 
                left join regencies as e on d.regency_id = e.id
                left join provinces as f on e.province_id = f.id
                and  not b.level = 1
                group by b.name, b.id
                order by count(b.id) desc
                limit 10";
        return DB::select($sql);
    }

    public function getReferalProvince($province_id)
    {
        $sql = "SELECT b.id, b.name , count(b.id) as total_referal
                from users as a
                join users as b on a.user_id = b.id
                left join villages as c on b.village_id = c.id
                left join districts as   d on c.district_id = d.id 
                left join regencies as e on d.regency_id = e.id
                where e.province_id = $province_id 
                and  not b.level = 1
                group by b.name, b.id
                order by count(b.id) desc
                limit 10";
        return DB::select($sql);
    }

    public function getInputerProvince($province_id)
    {
        $sql = "SELECT b.id, b.name , count(b.id) as total_data
                from users as a
                join users as b on a.cby = b.id
                left join villages as c on b.village_id = c.id
                left join districts as   d on c.district_id = d.id 
                left join regencies as e on d.regency_id = e.id
                where e.province_id = $province_id 
                and  not b.level = 1
                group by b.name, b.id
                order by count(b.id) desc
                limit 10";
        return DB::select($sql);
    }

    public function getInputers()
    {
        $sql = "SELECT b.id, b.name , count(b.id) as total_data
                from users as a
                join users as b on a.cby = b.id
                left join villages as c on b.village_id = c.id
                left join districts as   d on c.district_id = d.id 
                left join regencies as e on d.regency_id = e.id
                left join provinces as f on e.province_id = f.id
                and  not b.level = 1
                group by b.name, b.id
                order by count(b.id) desc
                limit 10";
        return DB::select($sql);
    }

    public function getInputerRegency($regency_id)
    {
        $sql = "SELECT b.id, b.name , count(b.id) as total_data
                from users as a
                join users as b on a.cby = b.id
                left join villages as c on b.village_id = c.id
                left join districts as   d on c.district_id = d.id 
                left join regencies as e on d.regency_id = e.id
                where e.id = $regency_id 
                and  not b.level = 1
                group by b.name, b.id
                order by count(b.id) desc
                limit 10";
        return DB::select($sql);
    }

     public function getInputerDistrict($district_id)
    {
        $sql = "SELECT b.id, b.name , count(b.id) as total_data
                from users as a
                join users as b on a.cby = b.id
                left join villages as c on b.village_id = c.id
                left join districts as   d on c.district_id = d.id 
                where d.id = $district_id
                and  not b.level = 1
                group by b.name, b.id
                order by count(b.id) desc
                limit 10";
        return DB::select($sql);
    }

    public function getReferalRegency($regency_id)
    {
        $sql = "SELECT b.id, b.name , count(b.id) as total_referal
                from users as a
                join users as b on a.user_id = b.id
                left join villages as c on b.village_id = c.id
                left join districts as d on c.district_id = d.id 
                where d.regency_id = $regency_id
                and  not b.`level` = 1 
                group by b.name, b.id
                order by count(b.id) desc 
                limit 10";
        return DB::select($sql);
    }

    public function getReferalDistrict($district_id)
    {
        $sql = "SELECT b.id, b.name , count(b.id) as total_referal
                from users as a
                join users as b on a.user_id = b.id
                left join villages as c on b.village_id = c.id
                where c.district_id = $district_id
                and  not b.`level` = 1 
                group by b.name, b.id
                order by count(b.id) desc
                limit 10";
        return DB::select($sql);
    }

     public function getInputerVillage($village_id)
    {
        $sql = "SELECT b.id, b.name , count(b.id) as total_data
                from users as a
                join users as b on a.cby = b.id
                left join villages as c on b.village_id = c.id
                where c.id = $village_id
                and  not b.level = 1
                group by b.name, b.id
                order by count(b.id) desc
                limit 10";
        return DB::select($sql);
    }

    public function getReferalVillage($village_id)
    {
        $sql = "SELECT b.id, b.name , count(b.id) as total_referal
                from users as a
                join users as b on a.user_id = b.id
                left join villages as c on b.village_id = c.id
                where c.id = $village_id
                and  not b.`level` = 1 
                group by b.name, b.id
                order by count(b.id) desc
                limit 10";
        return DB::select($sql);
    }

}
