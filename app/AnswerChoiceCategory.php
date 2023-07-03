<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AnswerChoiceCategory extends Model
{
    public function getData(){
        $sql = "SELECT name, created_at FROM answer_choice_categories";
        return DB::select($sql);
    }

    public function insertData($name,$date,$userId){
        $sql = "INSERT INTO answer_choice_categories (name,created_by,created_at) VALUES('$name', '$userId', '$date')";
        return DB::insert($sql);
    }

    public function editData($id){
        $sql = "SELECT id, name FROM answer_choice_categories WHERE id=$id";
        return collect(\DB::select($sql))->first();
    }

    public function updateData($id,$name,$date,$userId){
        $sql = "UPDATE answer_choice_categories SET name='$name', updated_at='$date', updated_by='$userId' WHERE id=$id";
        return DB::update($sql);
    }

}
