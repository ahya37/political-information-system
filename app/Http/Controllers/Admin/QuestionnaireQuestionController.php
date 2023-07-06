<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Helpers\ResponseFormatter;
use App\QuestionnaireQuestion;
use App\AnswerChoiceCategory;

class QuestionnaireQuestionController extends Controller
{
    public function index($id){

        $model = new AnswerChoiceCategory();
        $dataAnswer = $model->getData(); /// $dataAnswers
        // dd($tableAnswer);

        return view('pages.admin.questionnaire_questions.index', compact('dataAnswer','id'));
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
            DB::table('questionnaire_answer_choices')->where('questionnaire_question_id',$id)->delete();

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

    public function edit($id, $titleId){
        $model = new QuestionnaireQuestion();
        $data = $model->editData($id);
        return view('pages.admin.questionnaire_questions.edit', compact('data', 'titleId'));
    }

    public function update(Request $request, $titleId){
        $request->validate([
            'description' => 'required',
            'type' => 'required'
        ]);

          // untuk mendapatkan id akun admin yang sedang login
        $userId = auth()->guard('admin')->user()->id;
        $id = $request->id;
        $desc = $request->description;
        $type = $request->type;
        $date = date('Y-m-d h:i:s');

        $model = new QuestionnaireQuestion();
        $data = $model->updateData($id,$desc,$type,$userId,$date);

        return redirect()->route('admin-questionnairequestion-index', ['id' => $titleId])->with(['success' => 'Data Berhasil Diedit']);
    }

    public function store(Request $request, $id){

        DB::beginTransaction();
        try {
            # code...
            // untuk mendapatkan id akun admin yang sedang login
           $userId = auth()->guard('admin')->user()->id;
           $desc = $request->pilihan;
           $date = date('Y-m-d h:i:s');
           $answer['jawaban'] = $request->jawaban;
           $number = 1;
    
           // $model = new QuestionnaireQuestion();
           // $model->insertData($desc,$userId,$date);
    
           // insert ke tabel questionnaire_questions
           $questionnaireQuestions = DB::table('questionnaire_questions')->insertGetId([
               'questionnaire_title_id' => $id,
               'number' => $number + 1,
               'description' => $desc,
               'created_at' => $date,
               'created_by' => $userId
           ]);

           foreach ($answer['jawaban'] as $key => $value) {
               // insert ke tabel questionnaire_answer_choices
               // $questionnaireQuestionId = $insertQuestionnaireQuestions;
            //    DB::table('questionnaire_answer_choices')->insert([
            //        'questionnaire_question_id' => $questionnaireQuestions,
            //        'answer_choice_category_id' => $value,
            //        'created_at' => $date,
            //        'created_by' => $userId
            //    ]);

               $model = new QuestionnaireQuestion();
               $model->insertDataAnswer($questionnaireQuestions, $value, $date, $userId);
           }
    

           // dd($questionnaireQuestions,$answer);
           
           DB::commit();
           return redirect()->route('admin-questionnairequestion-index', ['id' => $id])->with(['success' => 'Judul Kuisioner Telah Ditambahkan']);

        } catch (\Exception $e) {
            DB::rollback();
            return $e->getMessage();
        }

    }

    public function create($id){
        $model = new AnswerChoiceCategory();
        $dataAnswer = $model->getData();

        return view('pages.admin.questionnaire_questions.create', compact('dataAnswer','id'));
    }


}