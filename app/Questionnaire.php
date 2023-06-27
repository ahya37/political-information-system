<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Questionnaire extends Model
{
    protected $table   = 'questionnaires';
    protected $guarded = [];


    public function insertData($nama,$tanggal,$userId,$url){
        $sql = "INSERT INTO questionnaires (name,created_at,url,created_by) VALUES('$nama','$tanggal','$userId','$url')";
        return DB::insert($sql);
    }

    public function updateData($id,$nama,$tanggal,$userId){
        $sql = "UPDATE questionnaires SET name='$nama', updated_at='$tanggal', updated_by='$userId' WHERE id=$id";
        return DB::update($sql);
    }

    // public function detail(){
    //     $sql = "SELECT id,  name,created_at FROM questionnaire_titles
    //             WHERE questionnaire_id = $id";  

    //     $data = DB::select($sql);

    }
}
