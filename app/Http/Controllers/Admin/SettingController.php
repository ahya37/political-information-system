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
use App\RightChoose;
use App\RightChooseDistrict;
use App\RightChooseProvince;
use App\RightChooseRegency;
use App\Tps;
use Illuminate\Support\Facades\DB;
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
                    ->addColumn('namelink', function($item){
                       return '<a href="'.route('admin-list-target-province', $item->id).'">'.$item->name.'</a>';
                    })                    
                    ->rawColumns(['persentage','targets','namelink'])
                    ->make();
            }
    
            return view('pages.admin.setting.list-target', compact('province'));
            
    }

    public function listTargetProvince($province_id)
    {
        
        $provinceModel = new Province();
        $province = $provinceModel->getDataProvince();
        $provincedetail = $provinceModel->select('id','name')->where('id', $province_id)->first();
        $regencyModel= new Regency();
        
        $achievments   = $regencyModel->achievementProvince($province_id);
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
                    ->addColumn('namelink', function($item){
                       return '<a href="'.route('admin-list-target-regency', $item->id).'">'.$item->name.'</a>';
                    })                    
                    ->rawColumns(['persentage','targets','namelink'])
                    ->make();
            }
    
            return view('pages.admin.setting.list-target-province', compact('province','provincedetail'));

    }

    public function listTargetRegency($regency_id)
    {
        
        $regency          = Regency::with('province')->where('id', $regency_id)->first();
    
        $districtModel    = new District();
        // Daftar pencapaian lokasi / daerah
        $achievments   = $districtModel->achievementDistrict($regency_id);
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
                    ->addColumn('namelink', function($item){
                       return '<a href="'.route('admin-list-target-district', $item->id).'">'.$item->name.'</a>';
                    })                    
                    ->rawColumns(['persentage','targets','namelink'])
                    ->make();
            }
    
            return view('pages.admin.setting.list-target-regency', compact('regency'));

    }

    public function listTargetDistric($district_id)
    {
        
       $districtModel    = new District();

        $district   = $districtModel->with(['regency'])->where('id', $district_id)->first();

        $villageModel   = new Village();
         // Daftar pencapaian lokasi / daerah
        $achievments   = $villageModel->achievementVillage($district_id);
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
                    ->addColumn('namelink', function($item){
                       return '<a href="'.route('admin-list-target-province', $item->id).'">'.$item->name.'</a>';
                    })                    
                    ->rawColumns(['persentage','targets','namelink'])
                    ->make();
            }
    
            return view('pages.admin.setting.list-target-district', compact('district'));

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

    public function listRightChoose()
    {
        $gF = new GlobalProvider();

        $no = 1;
        $rightChooseModel = new RightChooseProvince();
        $rightChoose      = $rightChooseModel->getDataRightChooseProvince();
        
        return view('pages.admin.setting.listrightchoose', compact('no','rightChoose','gF'));
    }

    public function listRightChooseRegency($provinceId)
    {
        $gF = new GlobalProvider();

        $no = 1;
        $province = Province::select('name')->where('id', $provinceId)->first();
        $rightChooseModel = new RightChooseRegency();
        $rightChoose      = $rightChooseModel->getDataRightChooseRegency($provinceId);
        
        return view('pages.admin.setting.listrightchooseregency', compact('no','rightChoose','gF','province'));
    }

    public function listRightChooseDistrict($regencyId)
    {
        $gF = new GlobalProvider();

        $no = 1;
        $regency = Regency::select('name')->where('id', $regencyId)->first();
        $rightChooseModel = new RightChooseDistrict();
        $rightChoose      = $rightChooseModel->getDataRightChooseDistrict($regencyId);
        
        return view('pages.admin.setting.listrightchoosedistrict', compact('no','rightChoose','gF','regency'));
    }

    public function listRightChooseVillage($districtId)
    {
        $gF = new GlobalProvider();

        $no = 1;
        $district = District::select('name')->where('id', $districtId)->first();
        $rightChooseModel = new RightChosseVillage();
        $rightChoose      = $rightChooseModel->getDataRightChooseVillage($districtId);

        
        return view('pages.admin.setting.listrightchoosevillage', compact('no','rightChoose','gF','district'));
    }

    public function settingRightChoose()
    {
        $dapils = new Dapil();
        $dataDapils = $dapils->getRegencyDapil();

        $righChooseModel = new RightChoose();

        #show list hak pilih level province
        $righChoose = $righChooseModel->getDataRightChooseProvince();
        $no         = 1;
        
        return view('pages.admin.setting.rightchoose', compact('dataDapils','righChoose','no'));
    }
    

    public function SaveRightChooseVillage(Request $request)
    {
        DB::beginTransaction();
        try {

            $request->validate([
                'count_vooter' => 'required|numeric'
            ]);

            $villageId    = $request->village_id;
            $districtId   = $request->district_id;
            $count_vooter = $request->count_vooter;
            
            #jumlah tps dihitung dari data di tb tps bersasarkan desa
            $count_tps       = Tps::where('village_id', $villageId)->count();
            $district        = District::with(['regency'])->select('regency_id')->where('id', $districtId)->first();
            $provinceId      = $district->regency->province_id; 
            

            $rightChooseVillageModel     = new RightChosseVillage();
            $rightChooseDistrictModel    = new RightChooseDistrict();
            $rightChooseRegencyModel     = new RightChooseRegency();
            $rightChooseProvinceModel    = new RightChooseProvince();

            #simpan hak pilih desa
            $this->setUpdateRightChooseVillage($rightChooseVillageModel, $villageId,$districtId,$count_tps,$count_vooter);
            
            #simpan hak pilih ke level distrtct
            $this->setUpdateRightChooseDistrict($rightChooseVillageModel,$rightChooseDistrictModel, $districtId,$district);

            #simpan hak pilih ke level kabkot
            $this->setUpdateRightChooseRegency($rightChooseDistrictModel,$rightChooseRegencyModel,$district);

            #simpan hak pilih ke level privinsi
            $this->setUpdateRightChooseProvince($rightChooseRegencyModel,$rightChooseProvinceModel,$provinceId);

            DB::commit();

            return redirect()->back()->with(['success' => 'Jumlah hak pilih telah disimpan']);

        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }

    }

    public function setUpdateRightChooseVillage($rightChooseVillageModel, $villageId,$districtId,$count_tps,$count_vooter){

        $cek          = $rightChooseVillageModel->where('village_id', $villageId)->count();
        $adminId      = auth()->guard('admin')->user()->id;

        if ($cek > 0) {

            #jika target sudah ada, maka update saja
            $rightChooseVillageModel->where('village_id', $villageId)->update([
                'count_tps' => $count_tps,
                'count_vooter' => $count_vooter,
                'mby' => $adminId
            ]);

        }else{

            $rightChooseVillageModel->create([
                'district_id' => $districtId,
                'village_id' => $villageId,
                'count_tps' => $count_tps,
                'count_vooter' => $count_vooter,
                'cby' => $adminId
            ]);

        }

        return;

    }

    public function setUpdateRightChooseDistrict($rightChooseVillageModel,$rightChooseDistrictModel, $districtId,$district){

         #hitung, simpan hak pilih ke level distrtct
         $countChooseVillage       = $rightChooseVillageModel->select('count_vooter','count_tps')->where('district_id', $districtId)->get();
         $countChooseVooterVillage = collect($countChooseVillage)->sum(function($q){ return $q->count_vooter; });
         $countTpsVillage          = collect($countChooseVillage)->sum(function($q){ return $q->count_tps; });

        #jika data dengan districtId sudah ada, maka update sata
        $cek = $rightChooseDistrictModel->where(['district_id' => $districtId])->count();
        $adminId      = auth()->guard('admin')->user()->id;

        if ($cek > 0) {
            
            $rightChooseDistrictModel->where(['district_id' => $districtId])->update([
                'count_tps'   => $countTpsVillage,
                'count_vooter'=> $countChooseVooterVillage,
                'mby' => $adminId
            ]);

        }else{

            $rightChooseDistrictModel->create([
                'district_id' => $districtId,
                'regency_id'  => $district->regency_id,
                'count_tps'   => $countTpsVillage,
                'count_vooter'=> $countChooseVooterVillage,
                'cby' => $adminId
            ]);

        }

        return;

    }

    public function setUpdateRightChooseRegency($rightChooseDistrictModel,$rightChooseRegencyModel, $district){

        #hitung, simpan hak pilih ke level kabkot
        $chooseVooterDistrict     = $rightChooseDistrictModel->select('count_tps','count_vooter')->where('regency_id', $district->regency_id)->get();
        $countChooseVooterDistrict= collect($chooseVooterDistrict)->sum(function($q){ return $q->count_vooter;});
        $countChooseTpsDistrict   = collect($chooseVooterDistrict)->sum(function($q){ return $q->count_tps;});
        $regency                  = Regency::select('province_id')->where('id', $district->regency_id)->first();

        $adminId      = auth()->guard('admin')->user()->id;

        #jika data dengan regencyid sudah ada, maka update sata
        $cek = $rightChooseRegencyModel->select('count_tps','count_vooter')->where('regency_id', $district->regency_id)->count();
        if ($cek > 0) {
           
            $rightChooseRegencyModel->where('regency_id', $district->regency_id)->update([
                'regency_id'  => $district->regency_id,
                'count_tps'   => $countChooseTpsDistrict,
                'count_vooter'=> $countChooseVooterDistrict,
                'mby' => $adminId
            ]);

        }else{

            $rightChooseRegencyModel->create([
                'regency_id'  => $district->regency_id,
                'province_id'  => $regency->province_id,
                'count_tps'   => $countChooseTpsDistrict,
                'count_vooter'=> $countChooseVooterDistrict,
                'cby' => $adminId
            ]);

        }

        return;


    }

    public function setUpdateRightChooseProvince($rightChooseRegencyModel,$rightChooseProvinceModel,$provinceId){

        #hitung, simpan hak pilih ke level privinsi
        $chooseVooterRegency      = $rightChooseRegencyModel->select('count_tps','count_vooter')->where('province_id', $provinceId)->get();
        $countChooseVooterRegency = collect($chooseVooterRegency)->sum(function($q){ return $q->count_vooter;});
        $countChooseTpsRegency    = collect($chooseVooterRegency)->sum(function($q){ return $q->count_tps;});

        $adminId      = auth()->guard('admin')->user()->id;
        #jika data dengan provinceid sudah ada, maka update sata
        $cek = $rightChooseProvinceModel->select('count_tps','count_vooter')->where('province_id', $provinceId)->count();
        if ($cek > 0) {
            
            $rightChooseProvinceModel->where('province_id', $provinceId)->update([
                'count_tps'   => $countChooseTpsRegency,
                'count_vooter'=> $countChooseVooterRegency,
                'mby' => $adminId
            ]);

        }else{

            $rightChooseProvinceModel->create([
                'province_id'  => $provinceId,
                'count_tps'   => $countChooseTpsRegency,
                'count_vooter'=> $countChooseVooterRegency,
                'cby' => $adminId
            ]);

        }

        return;


    }


}
