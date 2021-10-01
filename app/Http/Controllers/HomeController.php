<?php

namespace App\Http\Controllers;

use App\Job;
use App\User;
use App\Referal;
use App\AdminDistrict;
use App\Models\Regency;
use App\Models\Village;
use App\Models\District;
use App\Models\Province;
use Illuminate\Http\Request;
use App\Providers\GlobalProvider;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        #jika data profil belum dilengkapi
        $id_user   = Auth::user()->id;
        $userModel = new User();
        $user    = $userModel->with(['reveral'])->select('nik','user_id','status')->where('id', $id_user)->first();

        // jika user belum melakukan verifikasi email
        if ($user->status == 0) {
            return view('pages.before-verify-email');
        }

        // jika user_id null,  atau belum konek ke reveral
        if ($user->user_id == null) {
            return redirect()->route('user-create-reveral');
        }
        
        if ($user->nik == null) {
            # code...
            return redirect()->route('user-create-profile');
        }

        $profile = $userModel->with(['village','education'])->where('id', $id_user)->first();
        $member = $userModel->getDataByTotalReferalDirect($id_user); // berfungsi juga untuk menampilkan data total referal

          // referal
        $referal_undirect = $userModel->getReferalUnDirect($id_user);
        $referal_undirect = $referal_undirect->total == NULL ? 0 : $referal_undirect->total;
        $referal_direct = $userModel->getReferalDirect($id_user);
        $referal_direct = $referal_direct->total == NULL ? 0 : $referal_direct->total; 
        $total_referal = $referal_direct + $referal_undirect;

        if (request()->ajax()) 
        {
            return DataTables::of($member)
                    ->addColumn('action', function($item){
                        return '
                            <div class="btn-group">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-sc-primary text-white dropdown-toggle mr-1 mb-1" type="button" data-toggle="dropdown">...</button>
                                    <div class="dropdown-menu">
                                         <button type="button" class="dropdown-item" onclick="saved('.$item->id.')" id="'.$item->id.'" member="'.$item->name.'">
                                                Sudah Tersimpan di Nasdem
                                            </button>
                                            <button type="button" class="dropdown-item text-danger" onclick="registered('.$item->id.')" id="'.$item->id.'" member="'.$item->name.'">
                                                Sudah Terdaftar di Nasdem
                                            </button>
                                    </div>
                                </div>
                            </div>
                        ';
                    })
                    ->addColumn('photo', function($item){
                        return '
                        <a href="'.route('member-mymember', encrypt($item->id)).'">
                            <img  class="rounded" width="40" src="'.asset('storage/'.$item->photo).'">
                            '.$item->name.'
                        </a>
                        ';
                    })
                    ->addColumn('referal', function($item){
                        return $item->referal;
                    })
                     ->addColumn('register', function($item){
                        return date('d-m-Y', strtotime($item->created_at));
                    })
                    ->rawColumns(['action','photo','referal','register'])
                    ->make();
        }

        $gF = new GlobalProvider();
        
        return view('home', compact('gF','profile','member','total_referal','referal_undirect','referal_direct'));
    }

    public function dashboardAdminUser()
    {
        $user_id = Auth::user()->id;
        $user = User::with('village')->where('id', $user_id)->first();
        $level = $user->level;
        $district_id =  $user->village->district->id;
        $regency_id  =  $user->village->district->regency->id;
        $province_id  =  $user->village->district->regency->province->id;

        // jika admin level = 1
        if ($level == 1) {

            return $this->dashboardLevelOne($district_id);

        }elseif ($level == 2) {
            
            return $this->dashboardLevelTwo($regency_id);

        }elseif ($level == 3) {
            
            return $this->dashboardLevelTree($province_id);

        }
    }

    public function dashboardLevelOne($district_id)
    {
        $districtModel    = new District();

        $district   = $districtModel->with(['regency'])->where('id', $district_id)->first();
        // // jumlah anggota di kecamatan
        $userModel  = new User();
        $member     = $userModel->getMemberDistrict($district_id);
        $total_member = count($member);

        // // perentasi anggot  di kecamatan
        $target_member    = $districtModel->where('id',$district_id)->get()->count() * 5000; // target anggota tercapai, per kecamatan 1000 target

        $villageModel   = new Village();
        $villages       = $villageModel->getVillagesDistrct($district_id); // fungsi total desa di kab
        $total_village  = count($villages);


         // Daftar pencapaian lokasi / daerah
        $achievments   = $villageModel->achievementVillage($district_id);
        $total_target_member = $target_member / $total_village;
        if (request()->ajax()) {
            return DataTables::of($achievments)
                         ->addColumn('persentage', function($item) use($total_target_member){
                            $gF   = app('GlobalProvider'); // global function
                            $persentage = ($item->realisasi_member / $total_target_member)*100;
                            $persentage = $gF->persen($persentage);
                            $persentageWidth = $persentage + 30;
                            return '
                            <div class="mt-3 progress" style="width:100%;">
                                <span class="progress-bar progress-bar-striped bg-success" role="progressbar" style="width: '.$persentageWidth.'%" aria-valuenow="'.$persentage.'" aria-valuemin="'.$persentage.'" aria-valuemax="'.$persentage.'"><strong>'.$persentage.'%</strong></span>
                            </div>
                            ';
                        })
                        ->addColumn('target', function() use($total_target_member){
                            return $total_target_member;
                        })
                        ->rawColumns(['persentage','target'])
                        ->make();
        }
        return view('pages.dashboard.district', compact('district'));

    }

    public function dashboardLevelTwo($regency_id)
    {
        $regency          = Regency::with('province')->where('id', $regency_id)->first();
    
        $districtModel    = new District();
        // Daftar pencapaian lokasi / daerah
        $achievments   = $districtModel->achievementDistrict($regency_id);
        if (request()->ajax()) {
            return DataTables::of($achievments)
                    ->addColumn('persentage', function($item){
                        $gF   = app('GlobalProvider'); // global function
                        $persentage = ($item->realisasi_member / $item->total_target_member)*100;
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

        return view('pages.dashboard.regency', compact('regency'));

    }

    public function dashboardLevelTree($province_id)
    {
        $province    = Province::select('id','name')->where('id', $province_id)->first();
        $regencyModel= new Regency();

        // Daftar pencapaian lokasi / daerah
        $achievments   = $regencyModel->achievementProvince($province_id);
        if (request()->ajax()) {
            return DataTables::of($achievments)
                    ->addColumn('persentage', function($item){
                        $gF   = app('GlobalProvider'); // global function
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

        return view('pages.dashboard.province', compact('province'));
    }

}
