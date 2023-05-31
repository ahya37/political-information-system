<?php

namespace App\Http\Controllers\Admin;

use App\Tps;
use App\Dapil;
use App\Models\Regency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Village;
use App\RightChooseDistrict;
use App\RightChooseProvince;
use App\RightChooseRegency;
use App\RightChosseVillage;

class TpsController extends Controller
{
    public function index(){

        $regency = Regency::select('id','name')->where('id', 3602)->first();

        return view('pages.admin.tps.index', compact('regency'));

    }

    public function getDataTps(Request $request){

        // DATATABLE
        $orderBy = 'b.name';
        switch ($request->input('order.0.column')) {
            case '3':
                $orderBy = 'b.name';
                break;
        }

        $data = DB::table('tps as a')
                ->select('a.tps_number','a.rt', 'a.rw', 'b.name as village')
                ->join('villages as b','a.village_id','=','b.id');


        if($request->input('search.value')!=null){
                $data = $data->where(function($q)use($request){
                    $q->whereRaw('LOWER(b.name) like ? ',['%'.strtolower($request->input('search.value')).'%']);
                });
            }

            if ($request->input('village') != null) {
                            $data->where('a.village_id', $request->village);
            }

            if ($request->input('rt') != null) {
                            $data->where('a.rt', $request->rt);
            }


          $recordsFiltered = $data->get()->count();
          if($request->input('length')!=-1) $data = $data->skip($request->input('start'))->take($request->input('length'));

          $data = $data->orderBy('a.village_id','asc');
          $data = $data->orderBy('a.tps_number','asc');
          $data = $data->orderBy($orderBy,$request->input('order.0.dir'));
          $data = $data->get();

          $recordsTotal = $data->count();

          return response()->json([
                'draw'=>$request->input('draw'),
                'recordsTotal'=>$recordsTotal,
                'recordsFiltered'=>$recordsFiltered,
                'data'=> $data
            ]);
    }


    public function create(){

        $dapils = new Dapil();
        $dataDapils = $dapils->getRegencyDapil();

        return view('pages.admin.tps.create', compact('dataDapils'));

    }

    public function store(Request $request){

        DB::beginTransaction();
        try {
            
            $this->validate($request, [
                'tpnumber' => 'required|min:1',
            ]);

            $tpsModel  = new Tps();
            $villageid = $request->village_id;

            #cek jika data tps sudah ada dengan villageId tersebut
            $cekTps   = $tpsModel->where('village_id', $villageid)->count();
            $village  = Village::with(['district.regency.province'])->where('id', $villageid)->first();

            #hitung angka ari tps_number
            $countTpsNumber = $request->tpnumber;

            #jika sudah ada, maka replace data, karena data tersimpan sesuai jumlah input tps
            if ($cekTps > 0) {
                
                #replace
                $tpsModel->where('village_id', $villageid)->delete();

                for ($i= 1; $i <= $countTpsNumber ; $i++) { 
                    
                    Tps::create([
                        'village_id' => $villageid,
                        'tps_number' => $i,
                    ]);
                }

            }else{

                #else
                #simpan baru

                for ($i= 1; $i <= $countTpsNumber ; $i++) { 
                    
                    Tps::create([
                        'village_id' => $villageid,
                        'tps_number' => $i,
                    ]);
                }

            }

            $rightChooseVillageModel  = new RightChosseVillage();
            $rightChooseDistrictModel = new RightChooseDistrict();
            $rightChooseRegencyModel  = new RightChooseRegency();
            $rightChooseProvinceModel = new RightChooseProvince();

            #update TPS village
            $this->setUpdateTpsVillage($rightChooseVillageModel, $villageid,$countTpsNumber);

            #update TPS district
            $this->setUpdateTpsDistrict($rightChooseVillageModel,$rightChooseDistrictModel,$village);

            #update TPS regency
            $this->setUpdateTpsRegency($rightChooseDistrictModel,$rightChooseRegencyModel,$village);
            
            #update TPS province
            $this->setUpdateTpsProvince($rightChooseRegencyModel,$rightChooseProvinceModel,$village);

            DB::commit();
            return redirect()->back()->with(['success' => 'TPS berhasil tersimpan!']);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();

        }


    }

    public function setUpdateTpsProvince($rightChooseRegencyModel,$rightChooseProvinceModel,$village){

         #hitung count_tps regency
         $countTpsRegency         = $rightChooseRegencyModel->where('province_id', $village->district->regency->province->id)->sum('count_tps');
         #update count_tps di tb right_to_choose_provinces
         $rightChooseProvince      = $rightChooseProvinceModel->where('province_id', $village->district->regency->province->id)->first();
         if($rightChooseProvince  != null) $rightChooseProvinceModel->where('province_id', $village->district->regency->province->id)->update(['count_tps' => $countTpsRegency]);
         return;

    }

    public function setUpdateTpsRegency($rightChooseDistrictModel,$rightChooseRegencyModel,$village){

         #hitung count_tps district
         $countTpsDistrict         = $rightChooseDistrictModel->where('regency_id', $village->district->regency->id)->sum('count_tps');
         #update count_tps di tb right_to_choose_regencies

         $rightChooseRegency      = $rightChooseRegencyModel->where('regency_id', $village->district->regency->id)->first();
         if($rightChooseRegency  != null) $rightChooseRegencyModel->where('regency_id', $village->district->regency->id)->update(['count_tps' => $countTpsDistrict]);
         return;


    }

    public function setUpdateTpsDistrict($rightChooseVillageModel,$rightChooseDistrictModel,$village){

         #hitung count_tps village
         $countTpsVillage = $rightChooseVillageModel->where('district_id', $village->district->id)->sum('count_tps');

         #update count_tps di tb right_to_choose_districts
         $rightChooseDistrict      = $rightChooseDistrictModel->where('district_id', $village->district->id)->first();
         if($rightChooseDistrict  != null) $rightChooseDistrictModel->where('district_id', $village->district->id)->update(['count_tps' => $countTpsVillage]);
         return;

    }

    public function setUpdateTpsVillage($rightChooseVillageModel, $villageid,$countTpsNumber){

        #hitung jumlah tps
        #get right_to_choose_village where $villageid
        #update count_tps di tb right_to_choose_village
        $rightChooseVillage      = $rightChooseVillageModel->where('village_id', $villageid)->first();
        if($rightChooseVillage  != null) $rightChooseVillageModel->where('village_id', $villageid)->update(['count_tps' => $countTpsNumber]);
        return;

    }

    public function getDataTpsAPI(Request $request){

        $data = Tps::select('id','tps_number','village_id')->where('village_id', request()->villageId)->orderBy('tps_number','asc')->get();

        if($request->has('q')){
            $search = $request->q;
            $data = Tps::select('id','tps_number','village_id')
            ->where('village_id', request()->villageId)
            ->where('tps_number','LIKE',"%$search%")
            ->orderBy('tps_number','asc')
            ->get();

        }

        return response()->json($data);

    }

}
