<?php

namespace App\Http\Controllers\API;

use App\Admin;
use App\Models\Regency;
use App\Models\Village;
use App\Models\District;
use App\Models\Province;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    public function getAdmin()
    {
        $adminModel = new Admin();
        $admins    = $adminModel->getAdmins();
        return response()->json([
            'success' => true,
            'data' => $admins
        ]);
    }

    public function generateTarget(Request $request)
    {
        $province_id = $request->id;
        $target['target']      = $request->target;
        $provinces = Province::where('id', $province_id)->first();
        $regencies = Regency::where('province_id', $province_id)->get();
        $districts = District::with('regency')
                    ->whereHas('regency', function($q) use($province_id){
                        $q->where('province_id', $province_id);
                    })
                    ->get();
        

        $total_province = collect($districts)->sum(function($j){
            return $j['target'];
        });

        $provinces->update(['target' => $total_province]);


        #insert ke table district
        foreach ($districts as $val) {
            $updateDistrict = District::find($val->id);
            $updateDistrict->target = $target['target'];
            $updateDistrict->save();

            $villages = Village::where('district_id', $val->id)->get();
            $villages_count = Village::where('district_id', $val->id)->count();
            foreach($villages as $vill){
                $updateVillage = Village::find($vill->id);
                $updateVillage->target = $target['target'] / $villages_count;
                $updateVillage->save();
            }

        }


        #insert ke table regency
         #jumlahkan target yg ada di district berdasarkan regency_id
         foreach ($regencies  as $val) {
             $districtByRegency = District::where('regency_id', $val->id)->get();
             $updateRegenncy = Regency::find($val->id);
             $updateRegenncy->target = collect($districtByRegency)->sum(function($q){
                 return $q['target'];
             });
             $updateRegenncy->save();
             
         }

        
        $data = [
            'province' => [
                'success' => true,
            ],
            'regency' => [
                'success' => true,
            ],
            'district' => [
                'success' => true,
            ],
            'village' => [
                'success' => true,
            ]
        ];
        return response()->json([
            'data' => $data
        ]);
    }
    
}
