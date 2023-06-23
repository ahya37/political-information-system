<?php

namespace App\Http\Controllers;

use App\Questionnaire;
use App\QuestionnaireAnswerChoice;
use App\QuestionnaireQuestion;
use App\QuestionnaireTitle;
use Illuminate\Http\Request;
use DB;
class QuestionnaireController extends Controller
{
    public function index(){

        $questionnaire = Questionnaire::select('id','name','number_of_respondent','url')->orderBy('name','asc')->get();
        $no            = 1;

        return view('pages.questionnaire.index', compact('no','questionnaire'));

    }

    public function createRespondent($questionnaireId){


        #get judul pertanyaan by kuisioner id
        $questionnaireTitle = QuestionnaireTitle::where('questionnaire_id', $questionnaireId)->get();
        $questionnaireQuestionModel      = new QuestionnaireQuestion();
        $questionnaireAnswerChoicesModel = new QuestionnaireAnswerChoice();

        #dalam looping
        $questions = [];
        foreach ($questionnaireTitle as $value) {
            # code...
            #get pertanyaan by judul kuisioner
            $choices = [];

            $dataQuestion =  $questionnaireQuestionModel->getDataQuestionsByTitle($value->id);

            foreach ($dataQuestion as $item) {

                $answerChoices = $questionnaireAnswerChoicesModel->getDataAnswerChoiceByQuestionId($item->id);

                $choices[] = [
                    'questions' => $item->desc,
                    'answerChoices' => $answerChoices
                ];
            }

            $questions[] = [
                'title' => $value->name,
                'questions' => $choices
            ];

            #get pilihan jawaban by pertanyaan kuisioner
        }


        dd($questions);
    }
}
