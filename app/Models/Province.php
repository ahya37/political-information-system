<?php

/*
 * This file is part of the IndoRegion package.
 *
 * (c) Azis Hapidin <azishapidin.com | azishapidin@gmail.com>
 *
 */

namespace App\Models;

use AzisHapidin\IndoRegion\Traits\ProvinceTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
/**
 * Province Model.
 */
class Province extends Model
{
    use ProvinceTrait;
    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'provinces';
    protected $guarded = [];
    public  $timestamps = false;

    /**
     * Province has many regencies.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function regencies()
    {
        return $this->hasMany(Regency::class);
    }

    public function getTotalRegion($province_id)
    {
        $sql = "SELECT d.name as province, COUNT(DISTINCT(a.id)) as regency, COUNT(DISTINCT(b.id)) as district, COUNT(c.id) as village 
                from regencies as a 
                join districts as b on a.id = b.regency_id 
                join villages as c on b.id = c.district_id 
                join provinces as d on a.province_id = d.id
                where a.province_id = $province_id GROUP by d.name";
        return collect(\ DB::select($sql))->first();
    }

    public function getListTarget()
    {
        $sql = "SELECT e.id as province_id, e.name as province, e.target
                from users as a 
                join villages as b on a.village_id = b.id 
                join districts as c on b.district_id = c.id
                join regencies as d on c.regency_id = d.id
                join provinces as e on d.province_id = e.id
                GROUP by e.id, e.name, e.target order by e.name asc";
        $result = DB::select($sql);
        return $result;
    }

    public function getDataProvince()
    {
        $sql = "SELECT a.id, a.name from provinces as a
                join regencies as b on a.id = b.province_id 
                join districts as c on b.id = c.regency_id 
                join villages as d on c.id = d.district_id 
                join users as e on d.id = e.village_id 
                group by a.id, a.name order by a.name asc
                ";
        $result = DB::select($sql);
        return $result;
    }

    public function getAllcountMember(){
         $sql = "SELECT COUNT(*) as allcount from users ";
         $result = collect(\DB::select($sql))->first();
        return $result;
    }

     public function getAllcountMemberByProvince($searchQuery){
         $sql = "SELECT COUNT(a.id) as allcount from users as a
                join villages as b on a.village_id = b.id
                join districts as c on b.district_id = c.id 
                join regencies as d on c.regency_id = d.id
                where d.province_id = $searchQuery";
         $result = collect(\DB::select($sql))->first();
        return $result;
    }

    public function getMembers($searchQuery){
        $sql = "SELECT a.id as user_id, a.name, a.photo from users as a
                join villages as b on a.village_id = b.id 
                join districts as c on b.district_id = c.id
                join regencies as d on c.regency_id = d.id
                where d.province_id  = $searchQuery and a.village_id is not NULL and a.nik is not NULL and a.email is not null";
        $result = DB::select($sql);
        return $result;
    }

    
}
