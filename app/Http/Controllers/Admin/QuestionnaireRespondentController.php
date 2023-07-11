<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Helpers\ResponseFormatter;

class QuestionnaireRespondentController extends Controller
{
    public function index(){
        return view('pages.admin.questionnaire_respondent.index');
    }

    public function getDataRespondent(Request $request, $id){

         // DATATABLE
         $orderBy = 'name';
         switch ($request->input('order.0.column')) {
             case '3':
                 $orderBy = 'name';
                 break;
         }
 
         $data = DB::table('questionnaire_respondents')->where('questionnaire_id',$id)->select('id','name','gender','age');
 
 
         if($request->input('search.value')!=null){
                 $data = $data->where(function($q)use($request){
                     $q->whereRaw('LOWER(name) like ? ',['%'.strtolower($request->input('search.value')).'%']);
                 });
             }
 
          
           $recordsFiltered = $data->get()->count();
           $data = $data->orderBy($orderBy,$request->input('order.0.dir'));
           $data = $data->get();
 
           $recordsTotal = $data->count();
 
           return response()->json([
                 'draw'=>$request->input('draw'),
                 'recordsTotal'=>$recordsTotal,
                 'recordsFiltered'=>$recordsFiltered,
                 'data'=> $data
             ]);
    }

    public function detail(){
        return view('pages.admin.questionnaire_respondent.detail');
    }


    public function dataAnswerRespondent($id){
        $sql = "SELECT a.number, b.name, c.desc FROM questionnaire_answers AS a JOIN answer_choice_categories AS b ON a.number = b.id JOIN questionnaire_questions AS c ON a.questionnaire_question_id = c.id WHERE a.questionnaire_respondent_id = $id ";
        $data = DB::select($sql);

        return response()->json([
            'data'=> $data
        ]);
    }
}
