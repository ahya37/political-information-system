<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class OrgDiagramVillage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('org_diagram_village', function (Blueprint $table) {
            $table->increments('id');
            $table->string('idx')->unique();
            $table->string('pidx')->nullable();
            $table->string('color')->default('#007ad0')->nullable();
            $table->string('title');
            $table->bigInteger('nik')->nullable();
            $table->string('name')->nullable();
            $table->enum('base',['KORDES','KORRT']);
            $table->string('photo')->nullable();
            $table->bigInteger('regency_id');
            $table->bigInteger('district_id');
            $table->bigInteger('village_id');
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
        Schema::dropIfExists('org_diagram_village');
    }
}
