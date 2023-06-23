<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class QuestionnaireQuestion extends Model
{
    protected $table   = 'questionnaire_questions';
    protected $guarded = [];

    public function getDataQuestionsByTitle($titleId){

        $sql = DB::table('questionnaire_questions')->select('id','number','desc','required','type')->where('questionnaire_title_id', $titleId)->get();
        return $sql;
    }
}
