<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableQuestionnaireQuestion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questionnaire_questions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('questionnaire_title_id')->comment('judul pertanyaan');
            $table->string('number', 3)->comment('nomor pertanyaan');
            $table->string('desc')->comment('isi pertanyaan');
            $table->string('required',1)->default('N')->comment('mandatori / harus di isi');
            $table->string('type',20)->default('umum')->comment('Type pertanyaan');
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
        Schema::dropIfExists('questionnaire_questions');
    }
}
