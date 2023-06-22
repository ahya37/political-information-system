<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableQuestionnaireAnswer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questionnaire_answers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('questionnaire_question_id');
            $table->integer('questionnaire_respondent_id');
            $table->integer('questionnaire_answer_choice_id');
            $table->integer('number')->comment('nomor jawaban yang dipilih');
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('questionnaire_answers');
    }
}
