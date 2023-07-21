<?php 

namespace App\Helpers;

use App\Questionnaire;
use App\QuestionnaireRespondent;
use Illuminate\Support\Facades\DB;

class DeleteNikOrg
{

    public static function delete($oldNik){
        DB::table('org_diagram_pusat')->where('nik', $oldNik)->delete();
        DB::table('org_diagram_dapil')->where('nik', $oldNik)->delete();
        DB::table('org_diagram_district')->where('nik', $oldNik)->delete();
        DB::table('org_diagram_village')->where('nik', $oldNik)->delete();
        DB::table('org_diagram_rt')->where('nik', $oldNik)->delete();
       
    }
}