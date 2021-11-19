<?php

namespace App\Http\Controllers\API;

use App\Models\Regency;
use App\Models\Village;
use App\Models\District;
use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

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
            'view' => $regency->name.' '.$regency->province, 
        ];
        return response()->json($data);

    }

    public function getSearchRegencyById()
    {
        $data = request()->data;
        $regency = Regency::select('id','name')->where('id',$data)->first();
        return response()->json($regency);

    }

}
