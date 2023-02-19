<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\OrgDiagram;
use App\Models\Province;
use App\Models\Regency;
use App\Helpers\ResponseFormatter;
use DB;
use App\Http\Controllers\Auth\RegisterController;
use App\User;
use Illuminate\Support\Facades\Validator;

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

        $org_diagram= OrgDiagram::select('id','idx','parent','title','name','image','user_id','base','regency_id','dapil_id','district_id','village_id')
                                ->orderBy('idx','asc')->get();

        return response()->json([
            'data' => $org_diagram
        ]);
    }

    public function storeOrg(Request $request){

        DB::beginTransaction();

        try {

           $validator =  Validator::make($request->all(), [
                'nik' => 'required|numeric',
                'title' => 'required|string',
            ]);

            if ($validator->fails()) {
                return ResponseFormatter::error([
                    'message' => 'Lengkapi data, wajib diisi!',
                ],401);
            }

            #request data
            $nik = $request->nik;
            $regency_id = $request->regency;
            $dapil_id   = $request->dapil;
            $district_id = $request->district;
            $village_id = $request->village;
            $parent     = $request->parent;
            $title      = $request->title;
            $base       = $request->base;

            #cek ketersediaan nik
            $RegisterController = new RegisterController();
            $result_cek_nik = $RegisterController->nik($request);

            if ($result_cek_nik === 'Available') {
                return ResponseFormatter::error([
                    'message' => 'NIK tidak tersedia'
                ],204); 
            }else{

                #get user_id by nik
                $user = User::select('id','name','photo')->where('nik', $nik)->first();

                #create idx 
                $orgDiagram = OrgDiagram::where('regency_id', $regency_id)
                                          ->where('dapil_id', $dapil_id)
                                          ->where('district_id', $district_id)
                                          ->where('village_id', $village_id)
                                          ->max('idx');

                $idx = $orgDiagram + 1;

                #save
                OrgDiagram::create([
                    'idx' => $idx,
                    'regency_id' => $regency_id,
                    'dapil_id' => $dapil_id,
                    'district_id' => $district_id,
                    'village_id' => $village_id,
                    'parent' => $parent,
                    'title' => $title,
                    'user_id' => $user->id,
                    'name' => $user->name,
                    'image' => $user->photo,
                    'base' => $base
                ]);

                DB::commit();
                return ResponseFormatter::success([
                       'message' => 'Berhasil tambah struktur!'
                ],200); 
               
            }

        } catch (\Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'message' => 'Somethin when wrong!',
                'error' => $e->getMessage()
            ]);
        }

    }

    public function updateOrg(){

        DB::beginTransaction();
        try {

            $idx   = request()->idx;
            $title = request()->title;
            $id    = request()->id;

            #update org
            $org = OrgDiagram::where('id', $id)->first();
            $org->update([
                'title' => $title,
                'idx' => $idx
                ]
            );

            DB::commit();
            return ResponseFormatter::success([
                   'message' => 'Berhasil update struktur!'
            ],200);

        } catch (\Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'message' => 'Something when wrong!',
                'error' => $e->getMessage()
            ]);
        }

    }

    public function deleteOrg(){

        DB::beginTransaction();
        try {

            $id    = request()->id;

            #update org
            $org = OrgDiagram::where('id', $id)->delete();

            DB::commit();
            return ResponseFormatter::success([
                   'message' => 'Berhasil hapus struktur!'
            ],200);

        } catch (\Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'message' => 'Something when wrong!',
                'error' => $e->getMessage()
            ]);
        }

    }

    public function orgDiagramTest(){

        $regency = Regency::select('id','name')->where('id', 3602)->first();

        return view('pages.admin.strukturorg.index2',['regency' => $regency]);

    }

    public function getDataOrgDiagramVillage(){

        $village_id = request('village');
        $orgs = DB::table('org_diagram_village')
                ->select('idx','pidx','color','title','nik','name','photo')
                ->whereNotNull('pidx')
                ->where('village_id', $village_id)
                ->get();

        $data = [];
        foreach ($orgs as $value) {
            $data[] = [$value->pidx,$value->idx];
        }

        $nodes = [];
        foreach ($orgs as $value) {
            $nodes[] = [
                'id' => $value->idx,
                'title' => $value->title ?? $value->name,
                'name' => $value->name,
                'color' => $value->color ?? '',
                'image' => $value->photo ?? '',
            ];
        }

        $results = [
            'nodes' => $nodes,
            'data' => $data,
        ];

        return response()->json($results);
    }
}
