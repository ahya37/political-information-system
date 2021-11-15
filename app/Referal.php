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
                where a.village_id is not null
                group by b.name, b.id
                order by count(b.id) desc
                limit 5";
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
                group by b.name, b.id
                order by count(b.id) desc
                limit 5";
        return DB::select($sql);
    }

    public function getInputers()
    {
        $sql = "SELECT b.id, b.name , count(b.id) as total_data
                from users as a
                join users as b on a.cby = b.id
                where a.village_id is not null 
                group by b.name, b.id
                order by count(b.id) desc
                limit 5";
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
                group by b.name, b.id
                order by count(b.id) desc
                limit 5";
        return DB::select($sql);
    }

    public function getReferalRegency($regency_id)
    {
        $sql = "SELECT b.id, b.name , count(b.id) as total_data
                from users as a
                join users as b on a.user_id = b.id
                left join villages as c on b.village_id = c.id
                left join districts as d on c.district_id = d.id 
                where d.regency_id = $regency_id
                group by b.name, b.id
                order by count(b.id) desc 
                limit 5";
        return DB::select($sql);
    }

    public function getReferalDistrict($district_id)
    {
        $sql = "SELECT b.id, b.name , count(b.id) as total_data
                from users as a
                join users as b on a.user_id = b.id
                left join villages as c on b.village_id = c.id
                where c.district_id = $district_id
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
                group by b.name, b.id
                order by count(b.id) desc
                limit 5";
        return DB::select($sql);
    }

    public function getReferalVillage($village_id)
    {
        $sql = "SELECT b.id, b.name , count(b.id) as total_data
                from users as a
                join users as b on a.user_id = b.id
                left join villages as c on b.village_id = c.id
                where c.id = $village_id
                group by b.name, b.id
                order by count(b.id) desc
                limit 5";
        return DB::select($sql);
    }

    public function getReferealByMounthAdmin($mounth, $year)
    {
        $sql = "SELECT a.id as user_id, a.name, COUNT(b.user_id) as referal from users as a  
                join users as b on a.id = b.user_id 
                where MONTH(b.created_at) = $mounth and YEAR(b.created_at) = $year
                group by a.id, a.name order by COUNT(b.user_id) desc";
        return DB::select($sql);
    }

}
