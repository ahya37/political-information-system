<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBaseToOrgDiagramsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('org_diagrams', function (Blueprint $table) {
            $table->enum('base',['PEMBINA','PENASIHAT','KORPUS','KORDAPIL','KORCAM','KORDES','KORRT'])->after('image');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('org_diagrams', function (Blueprint $table) {
            //
        });
    }
}
