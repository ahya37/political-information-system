<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\OrgDiagram;
use App\Helpers\ResponseFormatter;

class AlertController extends Controller
{
    public function memberDeferentVillageByKortps(){
        // get data anggota yang desa nya berbeda dengan kortps nya
        $orgDiagram = new OrgDiagram();
        $villageId  = request()->villageid;

        $data       = $orgDiagram->getCountMemberDeferentVillageByKortps($villageId);
        $count_data = count($data);

        return ResponseFormatter::success([
            'count_data'  =>  $count_data,
            'data' => $data,
            'message' => $count_data > 0 ? 'Kortps memiliki anggota berbeda desa' : ''
        ], 200);
    }
}
