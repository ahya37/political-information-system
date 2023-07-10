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
    public function index($id)
    {

        $model = new AnswerChoiceCategory();
        $dataAnswer = $model->getData(); /// $dataAnswers


        return view('pages.admin.questionnaire_questions.index', compact('dataAnswer', 'id'));
    }

    public function getData(Request $request, $id)
    {
        // DATATABLE
        $orderBy = 'desc';
        switch ($request->input('order.0.column')) {
            case '3':
                $orderBy = 'desc';
                break;
        }
        // DATATABLE
        $orderBy = 'desc';
        switch ($request->input('order.0.column')) {
            case '3':
                $orderBy = 'desc';
                break;
        }



        $data = DB::table('questionnaire_questions')->where('questionnaire_title_id', $id)->select('id', 'desc', 'type');


        if ($request->input('search.value') != null) {
            $data = $data->where(function ($q) use ($request) {
                $q->whereRaw('LOWER(desc) like ? ', ['%' . strtolower($request->input('search.value')) . '%']);
            });
        }


        $recordsFiltered = $data->get()->count();
        $data = $data->orderBy($orderBy, $request->input('order.0.dir'));
        $data = $data->get();

        $recordsTotal = $data->count();

        return response()->json([
            'draw' => $request->input('draw'),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data
        ]);
    }

    public function delete()
    {

        DB::beginTransaction();
        try {

            $id    = request()->id;


            DB::table('questionnaire_questions')->where('id', $id)->delete();
            DB::table('questionnaire_answer_choices')->where('questionnaire_question_id', $id)->delete();

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

    public function edit($id, $titleId)
    {
        $model = new QuestionnaireQuestion();
        $data = $model->editData($id);
        return view('pages.admin.questionnaire_questions.edit', compact('data', 'titleId'));
    }

    public function update(Request $request, $titleId)
    {


        // untuk mendapatkan id akun admin yang sedang login
        $userId = auth()->guard('admin')->user()->id;
        $id = $request->id;
        $desc = $request->description;
        $type = $request->type;
        $date = date('Y-m-d h:i:s');
        $number = $request->number;

        $model = new QuestionnaireQuestion();
        $model->updateData($id, $desc, $type, $userId, $date, $number);

        return redirect()->route('admin-questionnairequestion-index', ['id' => $titleId])->with(['success' => 'Data Berhasil Diedit']);
    }

    public function store(Request $request, $id)
    {

        DB::beginTransaction();
        try {
           
            // untuk mendapatkan id akun admin yang sedang login
            $userId = auth()->guard('admin')->user()->id;
            $desc = $request->pilihan;
            $date = date('Y-m-d h:i:s');
            $answer['jawaban'] = $request->jawaban;
            $number = $request->number;

            // insert ke tabel questionnaire_answer_choices
            $model = new QuestionnaireQuestion();

            $questionnaireQuestions = $model->insertDataQuestion($id, $number, $desc, $date, $userId);

            foreach ($answer['jawaban'] as $key => $value) {

                $model = new QuestionnaireQuestion();
                $model->insertDataAnswer($questionnaireQuestions, $value, $date, $userId);
               
            }


            DB::commit();
            return redirect()->route('admin-questionnairequestion-index', ['id' => $id])->with(['success' => 'Pertanyaan Kuisioner Telah Ditambahkan']);
        } catch (\Exception $e) {
            DB::rollback();
            return $e->getMessage();
        }
    }


    public function create($id)
    {

        $model = new AnswerChoiceCategory();
        $dataAnswer = $model->getData();

        $questionnaireQuestion = new QuestionnaireQuestion();

        $count  = $questionnaireQuestion->countNumberQuestionByTitleId($id);

        // hitung nomor terakhir dari sebuah pertanyaan berdasarkan judul
        $number = $count->last_number == null ? 0 + 1 : $count->last_number + 1;
        // dd($number);

        return view('pages.admin.questionnaire_questions.create', compact('dataAnswer', 'id', 'number'));
    }

    public function storeEssay(Request $request, $id)
    {

        //ambil data kedalam variabel
        $userId = auth()->guard('admin')->user()->id;
        $desc = $request->essay;
        $date = date('Y-m-d h:i:s');
        $number = $request->number;

        //insert ke dalam database
        $model = new QuestionnaireQuestion();
        $model->insertFormEssay($id, $number, $desc, $date, $userId);

        return redirect()->route('admin-questionnairequestion-index', ['id' => $id])->with(['success' => 'Pertanyaan Kuisioner Telah Ditambahkan']);
    }
}
