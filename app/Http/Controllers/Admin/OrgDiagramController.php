<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\OrgDiagram;
use App\Models\Province;

class OrgDiagramController extends Controller
{
    public function index(){

        $provinceModel = new Province();
        $province = $provinceModel->getDataProvince();

        return view('pages.admin.strukturorg.index',compact('province'));
    }
    public function show(){
        
        $regency_id = request('regency_id');
        $dapil_id   = request('dapil_id');
        $district_id= request('district_id');
        $village_id = request('village_id');

        $org_diagram= OrgDiagram::select('id','parent','title','name','image','user_id')->get();

        return response()->json([
            'data' => $org_diagram
        ]);
    }
}
