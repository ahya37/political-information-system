<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Dapil;
use App\Models\Regency;
use App\Models\Village;
use App\Models\District;
use App\Models\Province;
use App\RightChosseVillage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\GlobalProvider;
use Yajra\DataTables\Facades\DataTables;

class SettingController extends Controller
{
    public function listTarget()
    {
        
        $provinceModel = new Province();
        $province = $provinceModel->getDataProvince();
        $regencyModel= new Regency();
        $achievments   = $regencyModel->achievements();
            if (request()->ajax()) {
                return DataTables::of($achievments)
                        ->addColumn('persentage', function($item){
                        $gF   = app('GlobalProvider'); // global function
                        $persentage = $gF->persen($item->percen);
                        $persentageWidth = $persentage + 30;
                        return '
                        <div class="mt-3 progress" style="width:100%;">
                            <span class="progress-bar progress-bar-striped bg-success" role="progressbar" style="width: '.$persentageWidth.'%" aria-valuenow="'.$persentage.'" aria-valuemin="'.$persentage.'" aria-valuemax="'.$persentage.'"><strong>'.$persentage.'%</strong></span>
                        </div>
                        ';
                    })
                    ->addColumn('targets', function($item){
                       return $item->target_member == null ? 0 : $item->target_member;
                    })                    
                    ->rawColumns(['persentage','targets'])
                    ->make();
            }
    
            return view('pages.admin.setting.list-target', compact('province'));
            
    }

    public function listTargetRegional($province_id)
    {
        
        $provinceModel = new Province();
        $province = $provinceModel->getDataProvince();
        $provincedetail = $provinceModel->select('id','name')->where('id', $province_id)->first();
        $regencyModel= new Regency();
        

            $achievments   = $regencyModel->achievementProvince($province_id);
            if (request()->ajax()) {
                return DataTables::of($achievments)
                    ->make();
            }
    
            return view('pages.admin.setting.list-target-province', compact('province','provincedetail'));

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

    public function getDatatarget()
    {
        $gF = new GlobalProvider();
        $userModel        = new User();

        if (request('province')) {
            $province = request('province');
            $target  = $userModel->getMemberRegistered($province);

            $data = [];
            foreach($target as $val){
                $data[] = [
                    'id' => $val->id,
                    'name' => $val->name,
                    'target_member' => $gF->decimalFormat($val->target_member),
                    'realisasi_member' => $gF->decimalFormat($val->realisasi_member),
                    'pencapaian' => $gF->persen($val->achivment)
                ];
            }

           


        }elseif (request('regency')) {
            $regency = request('regency');
            $target  = $userModel->getMemberRegisteredRegency($regency);

            $data = [];
            foreach($target as $val){
                $data[] = [
                    'id' => $val->id,
                    'name' => $val->name,
                    'target_member' => $gF->decimalFormat($val->target_member),
                    'realisasi_member' => $gF->decimalFormat($val->realisasi_member),
                    'pencapaian' => $gF->persen($val->achivment)
                ];
            }

            return response()->json($data);

        }elseif ( request('dapil')) {
            $dapil = request('dapil');
            
            $target  = $userModel->getMemberRegisteredRegency($dapil);

            // $data = [];
            // foreach($target as $val){
            //     $data[] = [
            //         'id' => $val->id,
            //         'name' => $val->name,
            //         'target_member' => $gF->decimalFormat($val->target_member),
            //         'realisasi_member' => $gF->decimalFormat($val->realisasi_member),
            //         'pencapaian' => $gF->persen($val->achivment)
            //     ];
            // }

            return response()->json($target);

        }elseif (request('district')) {
            $village = request('village');
            return 'district';

        }else{
            
            $regencyModel= new Regency();
            $achievments   = $regencyModel->achievements();
            return $achievments;
            
            if (request()->ajax()) {
                return DataTables::of($achievments)
                        ->addColumn('persentage', function($item){
                            $gF = new GlobalProvider();
                            $persentage = ($item->realisasi_member / $item->target_member)*100;
                            $persentage = $gF->persen($persentage);
                            $persentageWidth = $persentage + 30;
                            return '
                            <div class="mt-3 progress" style="width:100%;">
                                <span class="progress-bar progress-bar-striped bg-success" role="progressbar" style="width: '.$persentageWidth.'%" aria-valuenow="'.$persentage.'" aria-valuemin="'.$persentage.'" aria-valuemax="'.$persentage.'"><strong>'.$persentage.'%</strong></span>
                            </div>
                            ';
                        })
                        ->rawColumns(['persentage'])
                        ->make();
            }

          return view('pages.admin.setting.list-target');
        }
        
    }
}
