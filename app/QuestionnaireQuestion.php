<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class QuestionnaireQuestion extends Model
{
    protected $table   = 'questionnaire_questions';
    protected $guarded = [];

    public function getDataQuestionsByTitle($titleId){

        $sql = DB::table('questionnaire_questions')->select('id','desc')->where('questionnaire_title_id', $titleId)->get();
        return $sql;

    }

    public function getDataTable($id){
        $sql = "SELECT description, type FROM questionnaire_questions WHERE questionnaire_title_id=$id";
        return DB::select($sql);

    }

}
