<?php 

namespace App\Helpers;

use App\Questionnaire;
use App\QuestionnaireRespondent;

class UpdateRespondent
{

    public static function update($id){
        
        $countQuestionnaireRespondent    = QuestionnaireRespondent::where('questionnaire_id', $id)->count();
        $questionnaire = Questionnaire::where('id', $id)->first();

        $questionnaire->update(['number_of_respondent' => $countQuestionnaireRespondent]);
    }
}