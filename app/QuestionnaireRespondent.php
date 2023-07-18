<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class QuestionnaireRespondent extends Model
{
    protected $table   = 'questionnaire_respondents';
    protected $guarded = [];

    public function createdBy(){

       return $this->belongsTo(User::class,'created_by');
    }

    public function getData(){
        $sql = "SELECT * FROM questionnaire_respondents";
        return DB::select($sql);
    }

    public function insertData($respondentId,$questionnaireId,$cby,$created_at){
        $sql = "INSERT INTO questionnaire_answer_essay (questionnaire_respondent_id,questionnaire_question_id,answer,created_by,updated_by,created_at,updated_at) VALUES('$respondentId','$questionnaireId','$cby','$created_at')";
        return DB::insert($sql);
    }
    
}
