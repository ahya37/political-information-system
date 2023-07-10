<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class QuestionnaireQuestion extends Model
{
    protected $table   = 'questionnaire_questions';
    protected $guarded = [];

    // public function getDataQuestionnaireQuestion($id){
    //     $sql = "SELECT id, desc, type FROM questionnaire_questions WHERE questionnaire_title_id=$id";
    //     return DB::select($sql);
    // }

    public function editData($titleId){
        $sql = "SELECT id, number, `desc`, type FROM questionnaire_questions WHERE id=$titleId";
        return collect(\DB::select($sql))->first();
    }

    public function insertDataQuestion($id,$number,$desc,$date,$userId){
        $questionnaireQuestions = DB::table('questionnaire_questions')->insertGetId([
            'questionnaire_title_id' => $id,
            'number' => $number,
            'desc' => $desc,
            'created_at' => $date,
            'created_by' => $userId
        ]);

        // $sql = "INSERT INTO questionnaire_questions (questionnaire_title_id,number,`desc`,created_at,created_by) VALUES ('$id','$number','$desc','$date','$userId')";
        // $insert = DB::insert($sql);

        return $questionnaireQuestions;
    }

    

    public function insertDataAnswer($questionnaireQuestions, $value, $date, $userId){
        $sql = "INSERT INTO questionnaire_answer_choices (questionnaire_question_id, answer_choice_category_id,created_at,created_by,number) VALUES ('$questionnaireQuestions','$value','$date','$userId','$value')";
        return DB::insert($sql);
    }

    // public function getDataQuestion($id){
    //     $sql = "SELECT id FROM questionnaire_questions WHERE questionnaire_title_id=$id";
    //     return collect(\DB::select($sql))->first();
    // }

    public function updateData($id,$desc,$type,$userId,$date,$number){
        $sql = "UPDATE questionnaire_questions SET `desc`='$desc', type='$type', number='$number', updated_at='$date', updated_by='$userId' WHERE id=$id";
        return DB::update($sql);
    }

    public function updateDataAnswer($id,$value){
        $sql = "UPDATE questionnaire_answer_choices SET number='$value' WHERE questionnaire_question_id =$id";
        return DB::update($sql);
    }

    // public function delete($id){
    //     $sql = "DELETE FROM questionnaire_questions WHERE id=$id";
    //     return DB::delete($sql);
    // }

    public function countNumberQuestionByTitleId($id){

        $sql    = "SELECT max(number) as last_number from questionnaire_questions where questionnaire_title_id = $id";
        $count  = collect(\DB::select($sql))->first();
        return $count;
    }

    public function insertFormEssay($id,$number,$desc,$date,$userId){
        $sql = "INSERT INTO questionnaire_questions (questionnaire_title_id,number,`desc`,created_at,created_by) VALUES ('$id','$number','$desc','$date','$userId')";
        return DB::insert($sql);
    }

}
