<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Helpers\ResponseFormatter;

class QuestionnaireRespondentController extends Controller
{
    public function index()
    {
        return view('pages.admin.questionnaire_respondent.index');
    }

    public function getDataRespondent(Request $request, $id)
    {
        // DATATABLE
        $orderBy = 'name';
        switch ($request->input('order.0.column')) {
            case '3':
                $orderBy = 'name';
                break;
        }

        $data = DB::table('questionnaire_respondents')
            ->where('questionnaire_id', $id)
            ->select('id', 'name', 'gender', 'age');

        if ($request->input('search.value') != null) {
            $data = $data->where(function ($q) use ($request) {
                $q->whereRaw('LOWER(name) like ? ', ['%' . strtolower($request->input('search.value')) . '%']);
            });
        }

        $recordsFiltered = $data->get()->count();
        $data = $data->orderBy($orderBy, $request->input('order.0.dir'))->get();
        //    $data = $data->get();

        $recordsTotal = $data->count();

        return response()->json([
            'draw' => $request->input('draw'),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ]);
    }

    public function detail($id, $respondentId)
    {
        $data = DB::table('questionnaire_titles')->select('id','name')->where('questionnaire_id', $id)->get();


        $results = [];

        foreach ($data as $titleItem) {
            // get data pertanyaan where $item->id / id judul kuisioner
            $question = DB::table('questionnaire_questions')
                ->select('id','number','desc')
                ->where('questionnaire_title_id', $titleItem->id)
                ->orderBy('number','asc')
                ->get();

            // // berelasi ke table questionnaire_answer_choices untuk mendapatkan
            // // berlasi ke tabel  answer_choice_categories untuk mendapatkan keterangan jawaban (Ya/Tidak/...);

            $resultAnsewrs = [];
            foreach ($question as $questionItem) {
                $answer = DB::table('questionnaire_answers as a')
                    ->join('questionnaire_answer_choices as b', 'a.questionnaire_answer_choice_id', '=', 'b.id')
                    ->join('answer_choice_categories as c', 'b.answer_choice_category_id', '=', 'c.id')
                    ->select('c.name')
                    ->where('a.questionnaire_respondent_id', $respondentId) // 17
                    ->where('a.questionnaire_question_id', $questionItem->id) // 104
                    ->first();

                $resultAnsewrs[] = [
                    'number' => $questionItem->number,
                    'question' => $questionItem->desc,
                    'answer' => $answer->name ?? ''
                ];

            }



            $results[] = [
                'title' => $titleItem->name,
                'questions' => $resultAnsewrs,
                // 'answer' => $answer,
            ];
        }

        $noTitle = 1;
        $noQuestion = 1;


        // dd('results', $results);

        // dd('tabel pertanyan: ', $question, 'tabel answer: ', $answer, $questionItem->id);

        return view('pages.admin.questionnaire_respondent.detail', compact('results','noTitle','noQuestion'));
    }

    public function dataAnswerRespondent(Request $request, $id)
    {
        // DATATABLE
        $orderBy = 'name';
        switch ($request->input('order.0.column')) {
            case '3':
                $orderBy = 'name';
                break;
        }

        $data = DB::table('questionnaire_answers AS a')
            ->join('answer_choice_categories AS b', 'a.number', '=', 'b.id')
            ->join('questionnaire_questions AS c', 'a.questionnaire_question_id', '=', 'c.id')
            ->where('a.questionnaire_respondent_id', '=', $id)
            ->select('c.number', 'b.name', 'c.desc');

        if ($request->input('search.value') != null) {
            $data = $data->where(function ($q) use ($request) {
                $q->whereRaw('LOWER(name) like ? ', ['%' . strtolower($request->input('search.value')) . '%']);
            });
        }

        $recordsFiltered = $data->get()->count();
        $data = $data->orderBy($orderBy, $request->input('order.0.dir'))->get();
        //    $data = $data->get();

        $recordsTotal = $data->count();

        return response()->json([
            'draw' => $request->input('draw'),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ]);
    }
}
