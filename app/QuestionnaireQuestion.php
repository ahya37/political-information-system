<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class QuestionnaireQuestion extends Model
{
    protected $table   = 'questionnaire_questions';
    protected $guarded = [];

    public function getDataQuestionnaireQuestion($id){
        $sql = "SELECT id, `desc`, type FROM questionnaire_questions WHERE questionnaire_title_id=$id";
        return DB::select($sql);
    }



    public function insertData($userId,$date,$desc){
        $sql = "INSERT INTO questionnaire_questions (`desc`,created_at,created_by) VALUES('$desc','$date','$userId')";
        return DB::insert($sql);
    }

    public function editData($titleId){
        $sql = "SELECT id, number, `desc`, type FROM questionnaire_questions WHERE id=$titleId";
        return collect(DB::select($sql))->first();
    }

    public function insertDataQuestion($id,$number,$desc,$date,$userId){
        $questionnaireQuestions = DB::table('questionnaire_questions')->insertGetId([
            'questionnaire_title_id' => $id,
            'number' => $number,
            'desc' => $desc,
            'created_at' => $date,
            'created_by' => $userId
        ]);

        return $questionnaireQuestions;
    }

    public function insertDataAnswer($questionnaireQuestions, $value, $date, $userId){
        $sql = "INSERT INTO questionnaire_answer_choices (questionnaire_question_id, answer_choice_category_id,created_at,created_by,number) VALUES ('$questionnaireQuestions','$value','$date','$userId','$value')";
        return DB::insert($sql);
    }

    public function insertEssay($type){
        $sql = "INSERT INTO questionnaire_answer_essay () VALUES()";
        return $sql;
    }


    public function updateData($id,$desc,$userId,$date,$number){
        $sql = "UPDATE questionnaire_questions SET `desc`='$desc', number='$number', updated_at='$date', updated_by='$userId' WHERE id=$id";
        return DB::update($sql);
    }

    public function updateDataAnswer($id,$value){
        $sql = "UPDATE questionnaire_answer_choices SET number='$value' WHERE questionnaire_question_id =$id";
        return DB::update($sql);
    }

    public function countNumberQuestionByTitleId($id){

        $sql    = "SELECT max(number) as last_number from questionnaire_questions where questionnaire_title_id = $id";
        $count  = collect(DB::select($sql))->first();
        return $count;
    }

    public function insertFormEssay($id,$number,$desc,$date,$userId){
        $sql = "INSERT INTO questionnaire_questions (questionnaire_title_id,number,`desc`,`type`,created_at,created_by) VALUES ('$id','$number','$desc','essay','$date','$userId')";
        return DB::insert($sql);
    }

    public function getDataQuestionsByTitle($titleId){

        $sql = "SELECT id, `desc`, `type` from questionnaire_questions where questionnaire_title_id = $titleId";
        return DB::select($sql);
    }

    public function deleteAnswerChoiceByQuetionnairId($id){

        $sql = "DELETE from questionnaire_answer_choices where questionnaire_question_id = $id";
        return DB::delete($sql);
    }


}
