<?php

namespace App\Http\Controllers\API;

use App\Dapil;
use App\Models\Regency;
use App\Models\Village;
use App\Models\District;
use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

class LocationController extends Controller
{
    public function provinces(Request $request)
    {
        return Province::orderBy('name','ASC')->get();
    }

    public function regencies(Request $request, $province_id)
    {
        return Regency::where('province_id', $province_id)->orderBy('name','ASC')->get();
    }

    public function districts(Request $request, $regency_id)
    {
        return District::where('regency_id', $regency_id)->orderBy('name','ASC')->get();
    }

    public function getDistricts()
    {
        $districtModel =  new District();
        $district      = $districtModel->getDataDistrictByRegencyId(request()->regency_id);
        return response()->json(['status' => 'success', 'data'=> $district]);

    }

    public function getVillages()
    {
        $villageModel = new Village(); 
        $villages     = $villageModel->getDataVillageByDistrictId(request()->district_id);
        return response()->json(['status' => 'success', 'data'=> $villages ]);

    }


    public function villages(Request $request, $district_id)
    {
        return Village::where('district_id', $district_id)->orderBy('name','ASC')->get();
    }

    public function getSearchProvince()
    {
        $data = request()->data;
        $provinces = Province::select('id','name')->where('name','LIKE',"%{$data}%")->first();
        return response()->json($provinces);

    }

    public function getSearchProvinceById()
    {
        $data = request()->data;
        $province = Province::select('id','name')->where('id',$data)->first();
        return response()->json($province);

    }

    public function getSearchRegency()
    {
        $data = request()->data;
        $regency = DB::table('regencies as a')
                    ->join('provinces as b','a.province_id','=','b.id')
                    ->select('a.name','a.id','b.name as province')
                    ->where('a.name','like',"%{$data}%")
                    ->first();
        $data  = [
            'id' => $regency->id, 
            'name' => $regency->name, 
            'view' => $regency->name.', '.$regency->province, 
        ];
        return response()->json($data);

    }

    public function getSearchDistrict()
    {
        $data = request()->data;
        $district = DB::table('districts as a')
                    ->join('regencies as b','a.regency_id','b.id')
                    ->join('provinces as c','b.province_id','c.id')
                    ->select('a.name as district','b.name as regency','c.name as province','a.id')
                    ->where('a.name','like',"%{$data}%")
                    ->get();
        $data = [];
        foreach ($district as $val) {
            $data[]  = [
                'id' => $val->id, 
                'view' => $val->district.','.$val->regency.', '.$val->province, 
            ];
        }

        return response()->json($data);

    }

    public function getSearchVillage()
    {
        $data = request()->data;
        $village = Village::with(['district.regency.province'])->where('name','LIKE',"{$data}")->get();
        $data = [];
        foreach ($village as $val) {
            $data[]  = [
                'id' => $val->id, 
                'view' => $val->name.', KEC: '.$val->district->name.', '.$val->district->regency->name.', '.$val->district->regency->province->name
            ];
        }

        return response()->json($data);

    }

    public function addElement()
    {
        // Add element
        $request = request()->data;
        if($request == '2'){

            $html = "<div class='row mt-2 fieldGroup'>
                                <div class='col-1'>
                                  <button type='button' class='btn btn-danger btn-sm remove'><i class='fas fa-trash'></i></button>
                              </div>
                              <div class='col-5'>
                                <input type='text' name='name[]' class='form-control form-control-sm' placeholder='Nama'/>
                              </div>
                              <div class='col-5'>
                                 <select name='village_id[]'  class='form-control select2' required>
                                            <option value='>- pilih Desa -</option>
                                  </select>
                              </div>
                              
                   </div>";
            echo $html;
            exit;

        }
    }

    public function getSearchRegencyById()
    {
        $data = request()->data;
        $regency = Regency::select('id','name')->where('id',$data)->first();
        return response()->json($regency);

    }

    public function getSearchDistrictById()
    {
        $data = request()->data;
        $district = District::with(['regency.province'])->where('id',$data)->first();
        return response()->json($district);

    }

    public function getSearchVillageById()
    {
        $data = request()->data;
        $village = Village::with(['district.regency.province'])->where('id',$data)->first();
        return response()->json($village);

    }

    public function getSearchDapil()
    {
        $data = request()->data;
        $dapilModel = new Dapil;
        $dapil = $dapilModel->getDapilDetailById($data);
        return response()->json($dapil);

    }

    public function getVillageSelect()
    {
        $villageModel = new Village();
        $params       = request()->input('search');
        $village = $villageModel->getVillagesSelect($params);

        $list = array();
        $key=0;

        foreach ($village as $value) {
            $list[$key]['id'] = $value->id;
            $list[$key]['text'] =  $value->member.", DS. $value->village". ", KEC. $value->district".", $value->regency";
            $key++;
        }

        return response()->json($list);
    }

    public function getVillageSearch()
    {
        $villageModel = new Village();
        $params       = request()->input('search');
        $village = $villageModel->getVillagesSearch($params);

        $list = array();
        $key=0;

        foreach ($village as $value) {
            $list[$key]['id'] = $value->id;
            $list[$key]['text'] = $value->village. ", KEC. $value->district".", $value->regency";
            $key++;
        }

        return response()->json($list);
    }

    public function getRTByVIllage(){

        $rt = DB::table('users')->select('rt')->groupBy('rt')->orderBy('rt','asc')
                ->where('village_id',request()->village_id)->get();
                
        return response()->json($rt);
    }

    public function getTpsVIllage(){

        $rt = DB::table('tps')->select('id','tps_number')
                ->groupBy('id')
                ->groupBy('tps_number')
                ->where('village_id',request()->village_id)->get();
                
        return response()->json($rt);
    }

    public function getRTByVIllageNew(){

        $address = request()->address;

        $rt = DB::table('users')
                ->select('rt')
                ->groupBy('rt')
                ->orderBy('rt','asc')
                ->where('village_id',request()->village_id)
                ->where('address','LIKE', "%$address%")
                ->get();
                
        return response()->json($rt);
    }

    public function getKampungByVIllage(){

        $data = DB::table('users')->select('address')
                ->groupBy('address')
                ->orderBy('address','asc')
                ->where('village_id',request()->village_id)->get();
                
        return response()->json($data);
    }

}
