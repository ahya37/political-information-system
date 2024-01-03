<?php

namespace App\Http\Controllers\Admin;

use App\Tps;
use App\User;
use App\Dapil;
use App\Exports\SaksiExport;
use App\Witness;
use App\Models\Regency;
use App\Models\Village;
use App\RightChooseRegency;
use App\RightChosseVillage;
use App\RightChooseDistrict;
use App\RightChooseProvince;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\UserMenu;
use Maatwebsite\Excel\Excel;

class TpsController extends Controller
{
    public $excel;
    public function __construct(Excel $excel)
    {
        $this->excel = $excel;
    }

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
                ->select('a.tps_number','a.rt', 'a.rw', 'b.name as village','a.id')
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
                        'cby' => auth()->guard('admin')->user()->id
                    ]);
                }

            }else{

                #else
                #simpan baru

                for ($i= 1; $i <= $countTpsNumber ; $i++) { 
                    
                    Tps::create([
                        'village_id' => $villageid,
                        'tps_number' => $i,
                        'cby' => auth()->guard('admin')->user()->id
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

    public function witnesses($tpsId){

       $regency      = Regency::select('id')->where('id', 3602)->first();

       $tpsModel     = new Tps();
       $tps          = $tpsModel->getDataTpsByTpsId($tpsId);
        
       $witnessModel = new Witness();
       $witnesses    = $witnessModel->getDataWitnrsses($tpsId);
       
       $no           = 1;

       return view('pages.admin.tps.witness', compact('regency','tpsId','witnesses','no','tps'));

    }

    public function storeWitness(Request $request, $tpsId){

        DB::beginTransaction();
        try {
            
            $this->validate($request, [
                'member' => 'required|min:1',
                'status' => 'required',
            ]);

            $userId       = $request->member;

            $witnessModel = new Witness();
            $user         = User::select('village_id')->where('id', $userId)->first();
            
            #cek apakah anggota sudah menjadi tim saksi
            $check       = $witnessModel->where('user_id', $userId)->count();
            if($check > 0) return redirect()->back()->with(['warning' => 'Gagal simpan, Sudah terdaftar sebagai saksi!']);

            $tps          = Tps::select('village_id')->where('id', $tpsId)->first();
            #cek apakah anggota tersebut domisilinya sama dengan data lokasi TPS
            #parameternya adalah village_id di tbl tps dan village_id di tb users
            if ($user->village_id != $tps->village_id) return redirect()->back()->with(['warning' => 'Gagal simpan, Alamat desa anggota tidak sama dengan alamat TPS berada!']);
            
            Witness::create([
                'tps_id' => $tpsId,
                'user_id' => $request->member,
                'status' => $request->status,
                'cby' =>  auth()->guard('admin')->user()->id
            ]);

            #buat akun untuk saksi tps
            #jadikan admin level kecamatan, dan berikan akses menu realisasi suara
            #set user level = 1, and status = 1
            UserMenu::create([
                'user_id' => $request->member,
                'menu_id' => 11, // id menu realiasi member
            ]);

            $user->update(['status' => 1]);

            DB::commit();
            return redirect()->back()->with(['success' => 'Saksi telah disimpan!']);
        
        } catch (\Exception $e) {

            DB::rollBack();
            return redirect()->back()->with(['error' => $e->getMessage()]);

        }

    }

    public function deleteWitness()
    {

        DB::beginTransaction();
        try {

            $id    = request()->id;

            $witness =  Witness::where('id', $id)->first();

            #delete akses ke menu realisasi
            UserMenu::where('user_id', $witness->user_id)->where('menu_id', 11)->delete();

            $witness->delete();

            DB::commit();
            return ResponseFormatter::success([
                'message' => 'Berhasil hapus saksi!'
            ], 200);
            
        } catch (\Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'message' => 'Something when wrong!',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function downloadSaksiPerDesa(Request $request)
    {
        if (!$request->village_id) return redirect()->back()->with(['warning' => 'Pilih desa terlebih dahulu!']);
        $village = Village::select('name')->where('id', $request->village_id)->first();
        
        // get data saksi per desa
        $saksi = DB::table('witnesses as a')
                ->select('b.nik','b.name','b.address','b.phone_number','b.whatsapp','e.tps_number','b.rt')
                ->join('users as b','a.user_id','=','b.id')
                ->join('villages as c','b.village_id','=','c.id')
                ->join('districts as d','c.district_id','=','d.id')
                ->join('tps as e','b.tps_id','=','e.id')
                ->where('a.village_id', $request->village_id)
                ->get();
        
        $title = 'DAFTAR SAKSI DS.'.$village->name.'.xls';
        return $this->excel->download(new SaksiExport($saksi), $title);
    }


}
