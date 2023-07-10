<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class QuestionnaireAnswer extends Model
{
    public function data($id){
        $sql = "SELECT a.number FROM questionnaire_answer_choices AS a JOIN answer_choice_categories AS b ON a.number = b.id WHERE questionnaire_question_id = $id ";
        return collect(\DB::select($sql))->first();
    }

}
