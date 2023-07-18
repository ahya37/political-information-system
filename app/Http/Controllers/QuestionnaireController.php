<?php

namespace App\Http\Controllers;

use App\Helpers\UpdateRespondent;
use App\Questionnaire;
use App\QuestionnaireAnswer;
use App\QuestionnaireAnswerChoice;
use App\QuestionnaireQuestion;
use App\QuestionnaireRespondent;
use App\QuestionnaireTitle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class QuestionnaireController extends Controller
{
    public function index()
    {
        $questionnaire = Questionnaire::select('id', 'name', 'number_of_respondent', 'url')
            ->orderBy('name', 'asc')
            ->get();
        $no = 1;

        return view('pages.questionnaire.index', compact('no', 'questionnaire'));
    }

    public function createRespondent($questionnaireId)
    {
        #get judul pertanyaan by kuisioner id
        $questionnaireTitle = QuestionnaireTitle::select('id', 'name')
            ->where('questionnaire_id', $questionnaireId)
            ->get();

        $questionnaireQuestionModel = new QuestionnaireQuestion();
        $questionnaireAnswerChoicesModel = new QuestionnaireAnswerChoice();

        #dalam looping
        $questions = [];
        foreach ($questionnaireTitle as $value) {
            # code...
            #get pertanyaan by judul kuisioner
            $choices = [];

            $dataQuestion = $questionnaireQuestionModel->getDataQuestionsByTitle($value->id);

            foreach ($dataQuestion as $item) {
                $answerChoices = $questionnaireAnswerChoicesModel->getDataAnswerChoiceByQuestionId($item->id);

                $choices[] = [
                    'id' => $item->id,
                    'questions' => $item->desc,
                    'type' => $item->type,
                    'answerChoices' => $answerChoices,
                ];
            }

            $no = 1;
            $questions[] = [
                'id' => $value->id,
                'title' => $value->name,
                'questions' => $choices,
            ];

            #get pilihan jawaban by pertanyaan kuisioner
        }

        // dd($dataQuestion, $respondentt);

        // $alphabet = range('A','Z');

        return view('pages.questionnaire.create-respondent', compact('questions', 'no', 'questionnaireId'));
    }

    public function storeRespondent(Request $request, $questionnaireId)
    {
        DB::beginTransaction();
        try {
            #Validasi
            $request->validate([
                'nik' => 'required|numeric',
                'name' => 'required|string',
                'gender' => 'required',
                'age' => 'required|numeric',
                'phone_number' => 'required|numeric',
                'address' => 'required|string',
            ]);

            #model
            $questionnaireRespondentModel = new QuestionnaireRespondent();
            $questionnaireAnswerChoicesModel = new QuestionnaireAnswerChoice();

            //$respondent = $questionnaireRespondentModel->getData($questionnaireId);

            #hitung panjang nik, harus 16
            $cekLengthNik = strlen($request->nik);
            if ($cekLengthNik != 16) {
                return redirect()
                    ->back()
                    ->with(['error' => 'NIK harus 16 angka, cek kembali NIK tersebut!']);
            }

            #cek jika nik sudah menjadi respondent
            $checkNik = $questionnaireRespondentModel->where('nik', $request->nik)->count();
            if ($checkNik > 0) {
                return redirect()
                    ->back()
                    ->with(['error' => 'NIK sudah menjadi responden!']);
            }

            #save ke tb respondent
            $respondent = $questionnaireRespondentModel->create([
                'questionnaire_id' => $questionnaireId,
                'nik' => $request->nik,
                'name' => strtoupper($request->name),
                'address' => $request->address,
                'gender' => $request->gender,
                'age' => $request->age,
                'phone_number' => $request->phone_number,
                'created_by' => Auth::user()->id,
            ]);

            $respondentId = $respondent->id;
            $cby = Auth::user()->id;
            $created_at = date('Y-m-d H:i:s'); 

            
            // ambil semua data disetiap input name essay 
            $answers = $request->input('essay');

            // Tampung request type ke dalam array $type
            $type = $request->type;
            $questionId = $request->input('id');
            

            // Jika nilai 'essay' ada dalam array $type
            if (in_array('essay', $type)) {
                foreach ($answers as $key => $answer) {
                    $id = $questionId[$key];
                    DB::table('questionnaire_answer_essay')->insert([
                        'questionnaire_respondent_id' => $respondentId,
                        'questionnaire_question_id' => $id,
                        'answer' => $answer,
                        'created_by' => $cby,
                        'created_at' => $created_at,
                    ]);
                }
            }

            
        
    
            // selain itu, simpan ke tabel qustionnaire_answer_essay

            $answerChoice['answerChoice'] = $request->answerchoice;

    
            foreach ($answerChoice['answerChoice'] as $key => $value) {
    
                #get id dari save respondent
                #get number by answerChoice id / id pilihan nya
                $questionnaireAnswerChoices = $questionnaireAnswerChoicesModel->select('id','questionnaire_question_id','number')->where('id', $value)->first();
                $AnswerChoicesId            =  $questionnaireAnswerChoices->id;
                $QuestionnaireQestionId     =  $questionnaireAnswerChoices->questionnaire_question_id;
                $AnswerChoicesNumber        =  $questionnaireAnswerChoices->number;
            
                #save jawaban kuisioner pilihan ganda
                QuestionnaireAnswer::create([
                    'questionnaire_question_id' => $QuestionnaireQestionId,
                    'questionnaire_respondent_id' => $respondent->id,
                    'questionnaire_answer_choice_id' => $AnswerChoicesId,
                    'number' => $AnswerChoicesNumber,
                    'created_by' => Auth::user()->id
                ]);
    
            }

            #update jumlah respondent ke tabel questionnaires
           UpdateRespondent::update($questionnaireId);
    
            #save jawaban kuisioner essay
            DB::commit();
            return redirect()->back()->with(['success' => 'Kuisioner berhasil disimpan!']);

        } catch (\Exception $e) {
            DB::rollBack();
            // return $e->getMessage();
            return redirect()->back()->with(['error' => 'Kuisioner gagal disimpan!'. $e->getMessage()]);
        }
    }

    public function detailQuestionnaireId($questionnaireId)
    {
        $userId = Auth::user()->id;

        #get data respondent
        $questionnaireRespondent = QuestionnaireRespondent::select('id', 'questionnaire_id', 'nik', 'name', 'address', 'gender', 'age', 'phone_number', 'created_at')
            ->where('questionnaire_id', $questionnaireId)
            ->where('created_by', $userId)
            ->get();

        $no = 1;

        // dd($questionnaireRespondent);

        return view('pages.questionnaire.detail', compact('no', 'questionnaireRespondent'));
    }

    public function answersByRespondent($respondentId)
    {
        #get data pertanyaan dan jawaban berdasarkan respondent id
        $questionnaireAnswerModel = new QuestionnaireAnswer();

        // get jawaban pilihan ganda
        $answers = $questionnaireAnswerModel->getDataAnswerByRespondentId($respondentId);
        $essay = $questionnaireAnswerModel->getAnswerEssay($respondentId);

        // get jawaban essay

        //dd($answers);

        $no = 1;
        // page jawaban berdasarkan respodent id
        return view('pages.questionnaire.answers', compact('answers', 'no', 'essay'));
    }
}
