<?php

namespace App\Http\Controllers\Admin;

use App\Dapil;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Province;
use App\Models\Regency;
use App\Models\Village;
use App\RightChosseVillage;

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
            $regency  = $regencyModel->with(['districts.villages'])->where('province_id', $val->province_id)->orderBy('name','ASC')->get();
            
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

    public function settingRightChoose()
    {
        $dapils = new Dapil();
        $dataDapils = $dapils->getRegencyDapil();
        return view('pages.admin.setting.rightchoose', compact('dataDapils'));
    }

    public function SaveRightChooseVillage(Request $request)
    {
        $request->validate([
            'value' => 'required|numeric'
        ]);

        $choose = RightChosseVillage::where('village_id', $request->village_id)->count();
        if ($choose == 0) {
            RightChosseVillage::create([
                'village_id' => $request->village_id,
                'choose' => $request->value
            ]);

            return redirect()->back()->with(['success' => 'Jumlah hak pilih telah disimpan']);
        }else{
            $update = RightChosseVillage::where('village_id', $request->village_id)->first();
             $update->update([
                'choose' => $request->value
            ]);
            return redirect()->back()->with(['success' => 'Jumlah hak pilih telah diubah']);
        }

    }
}
