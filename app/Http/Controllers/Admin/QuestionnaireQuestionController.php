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

    public function getData(Request $request, $id){
         // DATATABLE
         $orderBy = 'description';
         switch ($request->input('order.0.column')) {
             case '3':
                 $orderBy = 'description';
                 break;
         }

         $model = new QuestionnaireQuestion();
         $data = $model->getDataQuestionnaireQuestion($id);
 

           return response()->json([
                 'data'=> $data
             ]);

    }

    public function delete(){

        DB::beginTransaction();
        try {

            $id    = request()->id;

            // $model = new QuestionnaireQuestion();
            // $model->delete($id);
            DB::table('questionnaire_questions')->where('id',$id)->delete();

            DB::commit();
            return ResponseFormatter::success([
                'message' => 'Berhasil hapus inventori!'
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'message' => 'Something when wrong!',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function edit(){
        return view('pages.admin.questionnaire_questions.edit');
    }

    public function store(Request $request){
        $request->validate([
            'description' => 'required',
            'type' => 'required'
        ]);

        
    }


}