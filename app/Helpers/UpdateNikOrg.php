<?php 

namespace App\Helpers;

use App\Questionnaire;
use App\QuestionnaireRespondent;
use Illuminate\Support\Facades\DB;

class UpdateNikOrg
{

    public static function update($oldNik, $request){
        DB::table('org_diagram_pusat')->where('nik', $oldNik)
                ->update(['nik' => $request->nik]);
        DB::table('org_diagram_dapil')->where('nik', $oldNik)->update(['nik' => $request->nik]);
        DB::table('org_diagram_district')->where('nik', $oldNik)->update(['nik' => $request->nik]);
        DB::table('org_diagram_village')->where('nik', $oldNik)->update(['nik' => $request->nik,'village_id' => $request->village_id]);
        DB::table('org_diagram_rt')->where('nik', $oldNik)->update(['nik' => $request->nik,'village_id' => $request->village_id]);
       
    }
}