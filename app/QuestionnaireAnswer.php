<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class QuestionnaireAnswer extends Model
{
    protected $table   = 'questionnaire_answers';
    protected $guarded = [];

    public function getDataAnswerByRespondentId($respondentId)
    {

        $sql = DB::table('questionnaire_answers as a')
            ->select('a.id', 'b.desc as question', 'd.name as answer')
            ->join('questionnaire_questions as b', 'a.questionnaire_question_id', '=', 'b.id')
            ->join('questionnaire_answer_choices as c', 'a.questionnaire_answer_choice_id', '=', 'c.id')
            ->join('answer_choice_categories as d', 'c.answer_choice_category_id', '=', 'd.id')
            ->join('questionnaire_respondents as e', 'a.questionnaire_respondent_id', '=', 'e.id')
            ->where('a.questionnaire_respondent_id', $respondentId)->get();

        return $sql;
    }

<<<<<<< HEAD
    public function getAnswerEssay($respondentId){
        $query = DB::table('questionnaire_answer_essay AS a')
        ->join('questionnaire_questions AS b', 'a.questionnaire_question_id', '=', 'b.id')
        ->select('a.answer as answer', 'b.desc as question')
        ->where('a.questionnaire_respondent_id', $respondentId)
        ->get();

        return $query;
    }
  
    public function data($id){
=======
    public function data($id)
    {
>>>>>>> d03d9ebc3c39813035b68b62dff71dcf36095435
        $sql = "SELECT a.answer_choice_category_id, a.number, b.name FROM questionnaire_answer_choices AS a JOIN answer_choice_categories AS b ON a.number = b.id WHERE questionnaire_question_id = $id ";
        return DB::select($sql);
    }

    public function getDataAnswerByRespondentIdAndQuestionnaireId($respondentId, $questionid)
    {

        $sql =  DB::table('questionnaire_answers as a')
            ->join('questionnaire_answer_choices as b', 'a.questionnaire_answer_choice_id', '=', 'b.id')
            ->join('answer_choice_categories as c', 'b.answer_choice_category_id', '=', 'c.id')
            ->select('c.name')
            ->where('a.questionnaire_respondent_id', $respondentId) // 17
            ->where('a.questionnaire_question_id', $questionid) // 104
            ->first();


        return $sql;
    }
}
