<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class QuestionnaireAnswerChoice extends Model
{
    
    protected $table   = 'questionnaire_answer_choices';
    protected $guarded = [];

    public function getDataAnswerChoiceByQuestionId($questionId){

       $sql = DB::table('questionnaire_answer_choices as a')
                ->select('a.id','a.number','b.name')
                ->join('answer_choice_categories as b','a.answer_choice_category_id','=','b.id')
                ->where('questionnaire_question_id', $questionId)
                ->orderBy('a.number','asc')
                ->get();
       return $sql;

    }
}
