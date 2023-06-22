<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableQuestionnaireAnswerChoice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questionnaire_answer_choices', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('questionnaire_question_id');
            $table->integer('answer_choice_category_id');
            $table->integer('number')->comment('nomor urut jawaban');
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
        Schema::dropIfExists('questionnaire_answer_choices');
    }
}
