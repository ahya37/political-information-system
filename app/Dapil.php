<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

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
        $result = collect(\DB::select($sql))->first();
        return $result;
    }
}
