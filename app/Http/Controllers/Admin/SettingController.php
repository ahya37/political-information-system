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
    public function settingTargetMember()
    {
        return view('pages.admin.setting.target-number');
    }

    public function updateTargetMember(Request $request)
    {
        $target  = $request->target; // target kecamatan
        $targetVill  = $request->targetVill; 
        $province_id = $request->province_id;
        $district_id = $request->district_id;
        $village_id  = $request->village_id;
        $regency_id  = $request->regency_id;
        $districtModel = new District();
        $villageModel  = new Village();

        // jika target desa terisi
        if ($targetVill != null) {
            // simpan target sesuai desanya
            $village = $villageModel->where('id', $village_id)->first();
            $village->update(['target' => $targetVill]);

        }else{

            #update  target ke district terkait
            $target_number = $districtModel->where('id', $district_id)->first();
            $target_number->update(['target' => $target]);
            
            #update target province
                #get province berdasarkan province_id
                $province  = Province::where('id', $province_id)->first();
                #get district berdasarkan province_id
                $target_in_province = $districtModel->with(['regency'])
                            ->whereHas('regency', function($regency) use ($province_id){
                                $regency->where('province_id', $province_id);
                            })->get();
                
                #jumlahkan total target provinsi $tp
                $tp = collect($target_in_province)->sum(function($q){
                        return $q['target'];
                        });
                #update target yang ada di table province
                $province->update(['target' => $tp]);
    
            #update target regency
                #get regency berdasarkan regency_id
                $regency = Regency::where('id', $regency_id)->first();
                #get district berdasarkan regency_id
                $target_in_regency = $districtModel->where('regency_id', $regency_id)->get();
                #jumlahkan total target regency $tr
                $tr = collect($target_in_regency)->sum(function($q){
                    return $q['target'];
                });
                #update target yang ada di table regency
                $regency->update(['target' => $tr]);
    
            #update target village
                #get village berdasarkan district_id
                #jumlahkan total village nya
                $total_village = $villageModel->where('district_id', $district_id)->count();
                $total_target_village = round($target  / $total_village);
                $villageModel->getUpdateTargetVillage($district_id , $total_target_village);
        }



        return redirect()->route('admin-setting-targetmember')->with(['success' => 'Target telah dibuat']);
    }
}
