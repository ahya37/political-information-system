<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuestionnaireRespondent extends Model
{
    protected $table   = 'questionnaire_respondents';
    protected $guarded = [];

    public function createdBy(){

       return $this->belongsTo(User::class,'created_by');
    }
    
}
