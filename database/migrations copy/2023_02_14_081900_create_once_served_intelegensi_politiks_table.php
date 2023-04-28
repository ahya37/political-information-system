<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOnceServedIntelegensiPolitiksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('once_served_intelegensi_politik', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('intelegensi_politik_id')->references('id')->on('intelegensi_politik')->cascadeOnDelete();
            $table->string('name');
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
        Schema::dropIfExists('once_served_intelegensi_politiks');
    }
}
