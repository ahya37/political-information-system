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

    public function getDataTable(){
        $sql = "SELECT * FROM questionnaire_questions";
        return DB::select($sql);

    }
}
