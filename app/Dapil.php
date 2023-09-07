<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Dapil extends Model
{
    protected $table = 'dapils';
    public $timestamps = false;
    protected $guarded = [];


    public function getDataDapilsRegency()
    {
        $sql = "SELECT b.id,  b.name as regency from dapils as a
                join regencies as b on a.regency_id = b.id 
                GROUP by b.name, b.id order by b.name ASC";
        $result = DB::select($sql);
        return $result; 
    }

    public function getDataDapils($regency_id)
    {
        $sql = "SELECT * from dapils where regency_id = $regency_id";
        $result = DB::select($sql);
        return $result; 
    }
    
    public function getDapilDetailById($id)
    {
        $sql = "SELECT b.id as regency_id , a.id as dapil_id, a.name as dapil_name , b.name as regency from dapils as a
                join regencies as b on a.regency_id = b.id
                where a.id = $id";
        $result = collect(DB::select($sql))->first();
        return $result;
    }

    public function getDataDapilAreas($id)
    {
        $sql = "SELECT a.id, b.name as district from dapil_areas as a
                join districts as b on a.district_id  = b.id where a.dapil_id = $id";
        $result = DB::select($sql);
        return $result; 
    }

    public function getDataDapilCalegs($dapil_id)
    {
        $sql = "SELECT a.id as user_id, b.id, a.phone_number, a.whatsapp, a.name, a.photo, a.address, c.name as village, d.name as district, e.name as regency, f.name as province from users a
                join dapil_calegs b on a.id = b.user_id
                join villages as c on a.village_id = c.id
                join districts as d on c.district_id = d.id 
                join regencies as e on d.regency_id = e.id
                join provinces as f on e.province_id = f.id
                where b.dapil_id = $dapil_id";
        $result = DB::select($sql);
        return $result; 
    }

    public function getRegencyDapil()
    {
        $sql = "SELECT b.id , b.name from dapils as a
                join regencies as b on a.regency_id = b.id
                group by b.id, b.name 
                ";
        $result = DB::select($sql);
        return $result;
    }

    public function getDataListDistrict($dapilId)
    {
        $sql = "SELECT b.name, b.id as district_id, a.dapil_id from dapil_areas as a
                join districts as b on a.district_id = b.id 
                where a.dapil_id = $dapilId";
        $result = DB::select($sql);
        return $result;
    }

    public function getDataListVillage($districtId)
    {
        $sql = "SELECT id, name from villages
                where district_id = $districtId";
        $result = DB::select($sql);
        return $result;
    }

    public function getDataDapilByDapilId($dapilId)
    {
        $sql = "SELECT a.id as dapil_id, a.name as dapil_name, b.name as regency from dapils as a
                join regencies as b on a.regency_id = b.id
                where a.id = $dapilId";
        $result = DB::select($sql);
        return $result;
    }

    public function getDataDistrictByDapilId($dapilId)
    {
        $sql = "SELECT a.district_id ,b.name from dapil_areas as a
                join districts as b on a.district_id = b.id 
                where a.dapil_id = 11";
        $result = DB::select($sql);
        return $result;
    }

    public function getProvinceDapil()
    {
        $sql = "SELECT a.id, a.name from provinces as a
                join regencies as b on a.id = b.province_id
                join dapils as c on b.id = c.regency_id 
                group by a.id, a.name";
        $result = DB::select($sql);
        return $result;
    }

    public function getRegencyDapilByProvince($id)
    {
        $sql = "SELECT a.id, a.name from regencies as a
                join dapils as b on a.id = b.regency_id
                where a.province_id = $id
                group by a.id, a.name order by a.name asc";
        $result = DB::select($sql);
        return $result;
    }


}
