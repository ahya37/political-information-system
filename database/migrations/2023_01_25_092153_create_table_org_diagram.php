<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableOrgDiagram extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('org_diagrams', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('regency_id');
            $table->integer('dapil_id');
            $table->integer('district_id');
            $table->integer('village_id');
            $table->integer('parent')->nullable();
            $table->string('title');
            $table->integer('user_id');
            $table->string('name');
            $table->text('image')->comment('Image mengambil data dari photo anggota pada saat create koordinator');
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
        Schema::dropIfExists('org_diagrams');
    }
}
