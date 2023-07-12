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
        $sql = "SELECT * FROM questionnaire_titles WHERE questionnaire_id = $id";
        $data = DB::select($sql);

        $results = [];

        foreach ($data as $titleItem) {
            // get data pertanyaan where $item->id / id judul kuisioner
            $question = DB::table('questionnaire_questions')
                ->where('questionnaire_title_id', $titleItem->id)
                ->get();

            // get jawaban berdasarkan respondent id dan pertanyaan id
            foreach ($question as $questionItem) {
                $answer = DB::table('questionnaire_answers')
                    ->where('questionnaire_respondent_id', $respondentId)
                    ->where('questionnaire_question_id', $questionItem->id)
                    ->get();

                // berelasi ke table questionnaire_answer_choices untuk mendapatkan answer_choice_category_id
            }
            foreach ($answer as $answerItem) {
                $questionChoice = DB::table('questionnaire_answer_choices')
                    ->select('answer_choice_category_id')
                    ->where('id', $answerItem->questionnaire_answer_choice_id)
                    ->first();

                // berlasi ke tabel  answer_choice_categories untuk mendapatkan keterangan jawaban (Ya/Tidak/...);
                foreach ($questionChoice as $questionChoiceItem) {
                    $answerChoice = DB::table('answer_choice_categories')
                        ->select('name')
                        ->where('id', $questionChoiceItem)
                        ->first();
                }
            }

            $results[] = [
                'title' => $titleItem->name,
                'questions' => $answer,
            ];
        }

        // dd('results', $results);

        dd('tabel pertanyan: ', $question, 'tabel answer: ', $answer, $questionChoice, $answerChoice);

        return view('pages.admin.questionnaire_respondent.detail');
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
