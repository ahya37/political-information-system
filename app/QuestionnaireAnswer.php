<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class QuestionnaireAnswer extends Model
{
    protected $table   = 'questionnaire_answers';
    protected $guarded = [];

    public function getDataAnswerByRespondentId($respondentId){

        $sql = DB::table('questionnaire_answers as a')
                ->select('a.id', 'b.desc as question', 'd.name as answer')
                ->join('questionnaire_questions as b','a.questionnaire_question_id','=', 'b.id')
                ->join('questionnaire_answer_choices as c', 'a.questionnaire_answer_choice_id','=','c.id')
                ->join('answer_choice_categories as d', 'c.answer_choice_category_id','=', 'd.id')
                ->join('questionnaire_respondents as e', 'a.questionnaire_respondent_id','=', 'e.id')
                ->where('a.questionnaire_respondent_id', $respondentId)->get();

        return $sql;

    }
  
    public function data($id){
        $sql = "SELECT a.answer_choice_category_id, a.number, b.name FROM questionnaire_answer_choices AS a JOIN answer_choice_categories AS b ON a.number = b.id WHERE questionnaire_question_id = $id ";
        return DB::select($sql);
    }
    

}
