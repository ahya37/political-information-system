<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class QuestionnaireQuestion extends Model
{
    protected $table   = 'questionnaire_questions';
    protected $guarded = [];

    public function getDataTable(){
        $sql = "SELECT * FROM questionnaire_questions";
        return DB::select($sql);
    }
}
