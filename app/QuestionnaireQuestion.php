<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class QuestionnaireQuestion extends Model
{
    protected $table   = 'questionnaire_questions';
    protected $guarded = [];

    public function getDataQuestionnaireQuestion($id){
        $sql = "SELECT description, type FROM questionnaire_questions WHERE questionnaire_title_id=$id";
        return DB::select($sql);
    }

    // public function delete($id){
    //     $sql = "DELETE FROM questionnaire_questions WHERE id=$id";
    //     return DB::delete($sql);
    // }

}
