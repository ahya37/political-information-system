<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class QuestionnaireTitle extends Model
{
    protected $table   = 'questionnaire_titles';
    protected $guarded = [];


    public function editData($id){
    $sql = "SELECT * FROM questionnaire_titles WHERE id=$id";
    return  collect(\DB::select($sql))->first();
    }

    public function updateData($id,$name,$userId){
        $sql = "UPDATE questionnaire_titles SET name='$name', updated_by=$userId WHERE id=$id";
        return DB::update($sql);
    }

    public function deleteData($id){
        $sql = "DELETE FROM questionnaire_titles WHERE id=$id";
        return $data = DB::delete($sql);
    }

    public function insertData($userId,$nama,$tanggal,$id){
        $sql = "INSERT INTO questionnaire_titles (questionnaire_id, name,created_by,created_at) VALUES('$id', '$nama','$userId','$tanggal')";
        return $data = DB::insert($sql);
    }
}


