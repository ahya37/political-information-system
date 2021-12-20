<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Province;
use App\Models\Regency;
use App\Models\Village;

class SettingController extends Controller
{
    public function listTarget()
    {
        return view('pages.admin.setting.list-target');
    }

    public function settingTargetMember()
    {
        return view('pages.admin.setting.target-number');
    }

    public function updateTarget(Request $request)
    {
        $target  = $request->target; // target kecamatan
        $targetVill  = $request->targetVill; 
        $province_id = $request->province_id;
        $district_id = $request->district_id;
        $village_id  = $request->village_id;
        $regency_id  = $request->regency_id;
        $districtModel = new District();
        $villageModel  = new Village();
        $regencyModel  = new Regency();
        $provinceModel = new Province();

         // jika target desa terisi
        if ($targetVill != null) {
            
            // simpan target desa
            $village = $villageModel->where('id', $village_id)->first();
            $village->update(['target' => $targetVill]);

            // kalkulasi kan jumlah target desa berdasrkan kecamatan terpilih
            $targetVillage = $villageModel->select('target')->where('district_id', $district_id)->get();
            $totalTargetVillage = collect($targetVillage)->sum(function($q){
                return $q->target;
            });

            // simpan target kecamatan
            $saveTargetDistrict = $districtModel->where('id', $district_id)->first();
            $saveTargetDistrict->update(['target' => $totalTargetVillage]);

            $this->getCalculateTarget($districtModel, $regency_id, $regencyModel, $province_id, $provinceModel);

        }else{
            // simpan target kecamatan
            $saveTargetDistrict = $districtModel->where('id', $district_id)->first();
            $saveTargetDistrict->update(['target' => $target]);

            $this->getCalculateTarget($districtModel, $regency_id, $regencyModel, $province_id, $provinceModel);
        }

        return redirect()->route('admin-setting-targetmember')->with(['success' => 'Target telah dibuat']);
    }

    public function getCalculateTarget($districtModel, $regency_id, $regencyModel, $province_id, $provinceModel)
    {
        
            // kalkulasikan jumlah target kecamatan berdasarkan regency_id terpilih
            $targetDistrict = $districtModel->select('target')->where('regency_id', $regency_id)->get();
            $totalTargetDistrict = collect($targetDistrict)->sum(function($q){
                return $q->target;
            });

            // hasilnya simpan ke target regency terkait
            $saveTargetRegency = $regencyModel->where('id', $regency_id)->first();
            $saveTargetRegency->update(['target' => $totalTargetDistrict]);

            // kalkulasikan jumlah target regency berdasarkan provinsi terkait
            $targetRegency  = $regencyModel->select('target')->where('province_id', $province_id)->get();
            $totalTargetRegency = collect($targetRegency)->sum(function($q){
                return $q->target;
            });

            // hasilnya simpan ke target provinsi terpilih
            $saveTargetProvince = $provinceModel->where('id', $province_id)->first();
            $saveTargetProvince->update(['target' => $totalTargetRegency]);
    }

    public function getListTarget()
    {
        $provinceModel = new Province();
        $regencyModel  = new Regency();

        $target        = $provinceModel->getListTarget();

        $data = [];
        foreach ($target as $key => $val) {
            $regency  = $regencyModel->getListTargetRegency($val->province_id);
            
            $data[] = [
                'province_id' => $val->province_id,
                'province' => $val->province,
                'target' => $val->target,
                'regencies' => $regency
            ];
        }
        $result = [
            'data' => $data
        ];
        return $result;
    }

    // public function updateTargetMember(Request $request)
    // {
    //     $target  = $request->target; // target kecamatan
    //     $targetVill  = $request->targetVill; 
    //     $province_id = $request->province_id;
    //     $district_id = $request->district_id;
    //     $village_id  = $request->village_id;
    //     $regency_id  = $request->regency_id;
    //     $districtModel = new District();
    //     $villageModel  = new Village();

    //     // jika target desa terisi
    //     if ($targetVill != null) {
    //         // simpan target sesuai desanya
    //         $village = $villageModel->where('id', $village_id)->first();
    //         $village->update(['target' => $targetVill]);

    //     }else{

    //         #update  target ke district terkait
    //         $target_number = $districtModel->where('id', $district_id)->first();
    //         $target_number->update(['target' => $target]);
            
    //         #update target province
    //             #get province berdasarkan province_id
    //             $province  = Province::where('id', $province_id)->first();
    //             #get district berdasarkan province_id
    //             $target_in_province = $districtModel->with(['regency'])
    //                         ->whereHas('regency', function($regency) use ($province_id){
    //                             $regency->where('province_id', $province_id);
    //                         })->get();
                
    //             #jumlahkan total target provinsi $tp
    //             $tp = collect($target_in_province)->sum(function($q){
    //                     return $q['target'];
    //                     });
    //             #update target yang ada di table province
    //             $province->update(['target' => $tp]);
    
    //         #update target regency
    //             #get regency berdasarkan regency_id
    //             $regency = Regency::where('id', $regency_id)->first();
    //             #get district berdasarkan regency_id
    //             $target_in_regency = $districtModel->where('regency_id', $regency_id)->get();
    //             #jumlahkan total target regency $tr
    //             $tr = collect($target_in_regency)->sum(function($q){
    //                 return $q['target'];
    //             });
    //             #update target yang ada di table regency
    //             $regency->update(['target' => $tr]);
    
    //         #update target village
    //             #get village berdasarkan district_id
    //             #jumlahkan total village nya
    //             $total_village = $villageModel->where('district_id', $district_id)->count();
    //             $total_target_village = round($target  / $total_village);
    //             $villageModel->getUpdateTargetVillage($district_id , $total_target_village);
    //     }

    //     return redirect()->route('admin-setting-targetmember')->with(['success' => 'Target telah dibuat']);
    // }
}
