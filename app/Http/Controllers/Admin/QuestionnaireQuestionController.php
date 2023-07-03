<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Helpers\ResponseFormatter;
use App\QuestionnaireQuestion;

class QuestionnaireQuestionController extends Controller
{
    public function index(){
        return view('pages.admin.questionnaire_questions.index');
    }

    public function getData(Request $request){
         // DATATABLE
         $orderBy = 'desc';
         switch ($request->input('order.0.column')) {
             case '3':
                 $orderBy = 'desc';
                 break;
         }
 
         $model = new QuestionnaireQuestion();
         $data = $model->getDataTable();
 

           return response()->json([
                 'data'=> $data
             ]);

    }
}