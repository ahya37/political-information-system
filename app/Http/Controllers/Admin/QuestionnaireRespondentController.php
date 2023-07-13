<?php

namespace App\Http\Controllers\Admin;

use App\QuestionnaireTitle;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Helpers\ResponseFormatter;
use App\QuestionnaireAnswer;
use App\QuestionnaireQuestion;

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

        $QuestionnaireTitle = new QuestionnaireTitle();
        $data               = $QuestionnaireTitle->getQuestionnaireTitelByQuestiaonnaireId($id);

        $QuestionnaireQuestion = new QuestionnaireQuestion();
        $QuestionnaireAnswer   = new QuestionnaireAnswer();

        $results = [];

        foreach ($data as $titleItem) {
            // get data pertanyaan where $item->id / id judul kuisioner
            $question = $QuestionnaireQuestion->getDataQuestionsByTitle($titleItem->id);

            $resultAnsewrs = [];
            foreach ($question as $questionItem) {
                $answer = $QuestionnaireAnswer->getDataAnswerByRespondentIdAndQuesttionnaireId($respondentId, $questionItem->id);

                $resultAnsewrs[] = [
                    'number' => $questionItem->number,
                    'question' => $questionItem->desc,
                    'answer' => $answer->name ?? ''
                ];

            }


            $results[] = [
                'title' => $titleItem->name,
                'questions' => $resultAnsewrs,
            ];
        }

        $noTitle = 1;
        $noQuestion = 1;


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
