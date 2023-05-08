<?php

namespace App\Http\Controllers;

use PDF;
use App\User;
use App\FigureDetail;
use App\Models\Regency;
use App\Models\Village;
use App\Models\District;
use App\Models\Province;
use App\VillageCalegTarget;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;
use App\Exports\MemberExport;
use App\Providers\GlobalProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class MemberController extends Controller
{
    public $excel;
    public function __construct(Excel $excel)
    {
        $this->excel = $excel;
    }
    
    public function memberProvince($id)
    {
        $province_id = decrypt($id);
        $province = Province::select('name')->where('id', $province_id)->first();

        $member = User::with(['village.district.regency.province','reveral','create_by'])
                    ->whereHas('village', function($village) use ($province_id){
                        $village->whereHas('district', function($district) use ($province_id){
                            $district->whereHas('regency', function($regency) use ($province_id) {
                                $regency->where('province_id', $province_id);
                            });
                        });
                    })
                    ->whereNotNull('nik')
                    ->get();

        if (request()->ajax()) 
        {
            return DataTables::of($member)
                    ->addIndexColumn()
                    ->addColumn('photo', function($item){
                        return '
                        <a href="'.route('admin-profile-member', encrypt($item->id)).'">
                            <img  class="rounded" width="40" src="'.asset('storage/'.$item->photo).'">
                            '.$item->name.'
                        </a>
                        ';
                    })
                    ->addColumn('referal', function($item){
                        return $item->referal;
                    })
                    ->rawColumns(['action','photo','referal'])
                    ->make(true);
        }
        return view('pages.member.member-province', compact('province'));
    }

    public function memberByReferal($user_id)
    {
        $userModel = new User(); 
        $user = $userModel->select('id','name')->where('id', $user_id)->first();
        $districtModel = new District();
        $districts = $districtModel->getDistrictByReferalMember($user_id);
        return view('pages.member.member-by-refeal', compact('user','districts','userModel'));
    }

    public function memberByInput($user_id)
    {
        $userModel = new User(); 
        $user = $userModel->select('id','name')->where('id', $user_id)->first();
        $districtModel = new District();
        $districts = $districtModel->getDistrictByInputMember($user_id);
        return view('pages.member.member-by-input', compact('user','districts','userModel'));
    }

    public function saveFigurMember(Request $request)
    {
        $figureId = $request->figureId;
        foreach ($figureId as $key => $value) {
            $figureDetail = new FigureDetail();
            $figureDetail->user_id = $request->userid;
            $figureDetail->figure_id = $value;
            $figureDetail->save();
        }

        return redirect()->back()->with(['success' => 'Anggota berpengaruh telah dibuat']);
    }
	
	public function memberPotensialByAdminInput()
	{ 
	    $user_id = Auth::user()->id;
		return view('pages.member.member-potensial', ['userId' => $user_id]);
	}
	
	public function dtMemberByAdminInput($userId)
    {
        try {
			
            $sql = "select b.id, b.photo, b.name, b.phone_number, b.whatsapp,
					(SELECT COUNT(DISTINCT(id)) from users where user_id = b.id) as total
					from users as a 
					join users as b on a.user_id = b.id
					where a.cby = $userId
					group by b.id, b.name, b.photo, b.phone_number, b.whatsapp";
					
            $data = DB::select($sql);
			
            usort($data, function($a, $b) {
                return $a->total < $b->total;
            });

           return DataTables::of($data)
                    ->addColumn('photo', function($item){
                        return '
						<img  class="rounded" width="40" src="'.asset('storage/'.$item->photo).'">
                        ';
                    })
                    ->addColumn('totalReferal', function($item){
                        return '
                         <div class="badge badge-pill badge-success">
                            '.$item->total.' 
                        </div>
                        ';
                    })
                    ->addColumn('action', function($item){
                        return '
                           <a href='.route('admin-member-by-referal',$item->id).' class="btn btn-sm text-white btn-sc-primary">Detail</a> 
                        ';
                    })
                    ->rawColumns(['photo','action','totalReferal'])
                    ->make(true);
					
        } catch (\Exception $e) {
            echo $e->getMessage();
        }

    }

    public function targetMemberCaleg(){

        $user_id = Auth::user()->id;

        #get data kecamatan berdasarkan dapil caleg tersebut, dan tampilkan targetnya
        $data = DB::table('dapil_areas as a')
                ->select('b.user_id','a.district_id','d.name','c.id as idtarget','c.target',
                    DB::raw('count(e.id) as jml_village'))
                ->join('dapil_calegs as b','a.dapil_id','=','b.dapil_id')
                ->leftJoin('districts_caleg_target as c','c.district_id','=','a.district_id')
                ->join('districts as d','d.id','=','a.district_id')
                ->leftJoin('villages_caleg_target as e','e.district_id','=','c.district_id')
                ->where('b.user_id', $user_id)
                ->groupBy('b.user_id','a.district_id','d.name','c.id','c.target')
                ->get();

        $no  = 1;

        return view('pages.calegtarget.index', compact('data','no'));

    }

    public function editTargetCaleg($villageId, $userId){

        return view('pages.calegtarget.edit', compact('villageId','userId'));

    }

    public function updateTargetDistrictCaleg(Request $request, $userId){


        $targetModel = DB::table('districts_caleg_target');

        $cek_target = $targetModel->where('caleg_user_id', $userId)->where('district_id', $request->districtId)->count();
       
        if($cek_target == 0) {

            #create data
            $targetModel->insert([
                'district_id' => $request->districtId,
                'target' => $request->target,
                'caleg_user_id' => $userId,
                'cby' => Auth::user()->id,
                'mby' =>  Auth::user()->id,
            ]);

        }else{

            $targetModel->where('district_id', $request->districtId)->where('caleg_user_id', $userId)->update([
                'target' => $request->target,
                'mby' => Auth::user()->id,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }

        return redirect()->route('member-caleg-target')->with(['success' => 'Data telah tersimpan!']);

    }

    public function sinkronVillageCaleg(Request $request, $districtId, $userId){

        DB::beginTransaction();
        try {
            
            #get data desa by $districtId
            $village = Village::select('id')->where('district_id', $districtId)->get();
            

            foreach ($village as $value) {
                        
                $villageTarget = new VillageCalegTarget();
                $villageTarget->village_id = $value->id;
                $villageTarget->district_id = $districtId;
                $villageTarget->target = 0;
                $villageTarget->caleg_user_id = $userId;
                $villageTarget->cby = Auth::user()->id;
                $villageTarget->mby = Auth::user()->id;
                $villageTarget->save();

            }

            #count jumlah target by districtId
            $targetVillage = DB::table('villages_caleg_target')->select('target')->where('district_id', $districtId)->where('caleg_user_id', $userId)->get();
            $sumTargetVillage = collect($targetVillage)->sum(function($q){
                return $q->target;
            });

            #save ke tb districts_caleg_target
            $targetModel = DB::table('districts_caleg_target');
            $targetModel->insert([
                'district_id' => $districtId,
                'target' => $sumTargetVillage,
                'caleg_user_id' => $userId,
                'cby' => Auth::user()->id,
                'mby' =>  Auth::user()->id,
            ]);

            DB::commit();
            return redirect()->back()->with(['success' => 'Data telah tersimpan!']);

        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();

        }


    }

    public function villageTargetCaleg($districtId, $userId){


        #get data kecamatan berdasarkan dapil caleg tersebut, dan tampilkan targetnya
        $data = DB::table('villages as a')
                ->select('b.id','a.name','b.target','b.district_id','b.caleg_user_id as user_id')
                ->join('villages_caleg_target as b','b.village_id','=','a.id')
                ->where('a.district_id', $districtId)
                ->where('b.caleg_user_id', $userId)
                ->orderBy('a.name','asc')
                ->get();

        $no  = 1;

        return view('pages.calegtarget.village', compact('data','no'));

    }

    public function editTargetVillageCaleg($id){

        return view('pages.calegtarget.edit-village', compact('id'));

    }

    public function updateTargetVIllageCaleg(Request $request, $id){


       $targetVillage = DB::table('villages_caleg_target');
       $data          = $targetVillage->select('district_id','caleg_user_id')->where('id', $id)->first();
       $targetVillage->update(['target' => $request->target]);

        return redirect()->route('member-caleg-target-village', ['districtId', $data->district_id,'userId' => $data->caleg_user_id])->with(['success' => 'Data telah tersimpan!']);

    }

    public function getDownloadExcelCaleg()
    {
        $province = request()->input('province');
        $regency = request()->input('regency');
        $dapil = request()->input('dapil');
        $district = request()->input('district');
        $village = request()->input('village');
        $type = request()->input('type');
        $userId = Auth::user()->id;

        // query
        $data = DB::table('users as a')
                        ->select('a.nik','a.id','a.user_id','a.name','a.photo','a.rt','a.rw','a.phone_number','a.whatsapp','a.address','regencies.name as regency','districts.name as district','villages.name as village','b.name as referal','c.name as cby','a.created_at','a.status','a.email')
                        ->join('villages','villages.id','a.village_id')
                        ->join('districts','districts.id','villages.district_id')
                        ->join('regencies','regencies.id','districts.regency_id')
                        ->join('users as b','b.id','a.user_id')
                        ->join('users as c','c.id','a.cby')
                        ->leftJoin('dapil_areas','districts.id','dapil_areas.district_id')
                        ->whereNotNull('a.village_id')
                        ->where('a.user_id', $userId)
                        ->orderBy('villages.name','asc')
                        ->orderBy('a.name','asc');
                        
            $title = 'LAPORAN ANGGOTA';
            if ($province != null) {
                        $data->where('regencies.province_id', $province);
                        $provinces = Province::select('name')->where('id', $province)->first();
                        $title = "PROVINSI $provinces->name";
            }

            if ($regency != null) {
                            $data->where('regencies.id',  $regency);
                            $regencies = Regency::select('name')->where('id', $regency)->first();
                            $title = $regencies->name;

                }

            if ($dapil != null) {
                            $data ->where('dapil_areas.dapil_id', $dapil);
                            $title = 'Dapil';
                }
            if ($district != null) {
                            $data->where('districts.id', $district);
                            $districts = District::select('name')->where('id', $district)->first();
                            $title = "KECAMATAN $districts->name";
                }
            if ($village != null) {
                            $data->where('villages.id', $village);
                            $villages = Village::select('name')->where('id', $village)->first();
                            $title = "DESA  $villages->name";
            }

            $data = $data->get();

        // EXPORT EXCEL
        if ($type == 'excel') {
            return $this->excel->download(new MemberExport($data), 'LAPORAN ANGGOTA '.$title.'.xls');
        }else{
            $gF = new GlobalProvider();
            $result = [];
            $no = 1;
            foreach($data as $val){
                $total_referal = User::where('user_id', $val->id)->whereNotNull('village_id')->count();
                $result[] = [
                    'no' => $no++,
                    'nik' => $val->nik,
                    'name' => $val->name,
                    'address' => $val->address,
                    'rt' => $val->rt,
                    'rw' => $val->rw,
                    'village' => $val->village,
                    'district' => $val->district,
                    'regency' => $val->regency,
                    'telp'    => $val->phone_number,
                    'wa' => $val->whatsapp,
                    'created_at' => date('d-m-Y', strtotime($val->created_at)),
                    'cby' => $val->cby,
                    'referal' => $val->referal,
                    'total_referal' => $gF->decimalFormat($total_referal),
                ];
            }
            $pdf = PDF::LoadView('pages.admin.report.caleg.member-byregional',compact('result','no','gF','title'))->setPaper('f4','landscape');
            return  $pdf->download('LAPORAN ANGGOTA '.$title.'.pdf');
        }

    }
}
