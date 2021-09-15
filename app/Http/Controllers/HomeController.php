<?php

namespace App\Http\Controllers;

use App\AdminDistrict;
use App\Job;
use App\User;
use App\Referal;
use App\Models\Village;
use App\Models\District;
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
                    ->rawColumns(['action','photo','referal'])
                    ->make();
        }

        $gF = new GlobalProvider();
        
        return view('home', compact('gF','profile','member','total_referal','referal_undirect','referal_direct'));
    }

    public function dashboardAdminDistrict()
    {
        $district_id = $this->getDistrictIdbyAdmin(); // get district_id berdasarkan user_id admin yg login
        
        $district    = District::with(['regency'])->where('id', $district_id)->first();
        // jumlah anggota di kecamatan
        $userModel  = new User();
        $member     = $userModel->getMemberDistrict($district_id);
        $total_member = count($member);

        // perentasi anggot  di kecamatan
        $districtModel    = new District();
        $target_member    = $districtModel->where('id',$district_id)->get()->count() * 5000; // target anggota tercapai, per kecamatan 1000 target
        $persentage_target_member = ($total_member / $target_member) * 100; // persentai terdata

        $villageModel   = new Village();
        $villages       = $villageModel->getVillagesDistrct($district_id); // fungsi total desa di kab
        $total_village  = count($villages);

        $village_filled = $villageModel->getVillageFilledDistrict($district_id); //fungsi total desa yang terisi 
        $total_village_filled      = count($village_filled); // total desa yang terisi
        $presentage_village_filled = ($total_village_filled / $total_village) * 100; // persentasi jumlah desa terisi

        // Grfaik Data member
        $districts = $districtModel->getGrafikTotalMemberDistrict($district_id);
        $cat_districts      = [];
        $cat_districts_data = [];
        foreach ($districts as $val) {
            $cat_districts[] = $val->district; 
            $cat_districts_data[] = [
                "y" => $val->total_member,
                "url" => route('admin-dashboard-district', $val->distric_id)
            ];
        }

        // grafik data job
        $jobModel = new Job();
        $jobs     = $jobModel->getJobDistrict($district_id);
        $cat_jobs =[];
        foreach ($jobs as  $val) {
            $cat_jobs[] = [
                "name" => $val->name,
                "y"    => $val->total_job
            ];
        }
        
        // grafik data jenis kelamin
        $gender = $userModel->getGenderDistrict($district_id);
        $cat_gender =[];
        $all_gender = [];

        // untuk menghitung jumlah keseluruhan jenis kelamin L/P
        $total_gender = 0;
        foreach ($gender as $key => $value) {
            $total_gender += $value->total;
        }
        
        foreach ($gender as  $val) {
            $all_gender[]  = $val->total;
            $cat_gender[] = [
                "name" => $val->gender == 0 ? 'Pria' : 'Wanita',
                "y"    => ($val->total/$total_gender)*100
            ];
        }

        $total_male_gender   = empty($all_gender[0]) ?  0 :  $all_gender[0];; // total gender pria
        $total_female_gender = empty($all_gender[1]) ?  0 :  $all_gender[1]; // total gender wanita
        
        // range umur
        $range_age     = $userModel->rangeAgeDistrict($district_id);
        $cat_range_age = [];
        $cat_range_age_data = [];
        foreach ($range_age as $val) {
            $cat_range_age[]      = $val->range_age;
            $cat_range_age_data[] = [
                'y'    => $val->total
            ];
        }

        $gF   = app('GlobalProvider'); // global function

         // Daftar pencapaian lokasi / daerah
        $achievments   = $villageModel->achievementVillage($district_id);
        if (request()->ajax()) {
            return DataTables::of($achievments)->make();
        }

        // anggota dengan referal terbanyak
        $referalModel = new Referal();
        $referal      = $referalModel->getReferalDistrict($district_id);
        $cat_referal      = [];
        $cat_referal_data = [];
        foreach ($referal as $val) {
            $cat_referal[] = $val->name; 
            $cat_referal_data[] = [
                "y" => $val->total_referal
            ];
        }
        return view('pages.admin-district.dashboard-district',compact('district_id','cat_referal_data','cat_referal','cat_range_age_data','cat_range_age','total_male_gender','total_female_gender','cat_gender','cat_jobs','cat_districts','cat_districts_data','total_village_filled','presentage_village_filled','total_village','target_member','persentage_target_member','district','gF','total_member'));
    }

    public function getDistrictIdbyAdmin()
    {
        $user_id = Auth::user()->id;
        $adminDistrtictModel = new AdminDistrict();
        $district_id         = $adminDistrtictModel->getDataDistrictIdbyAdmin($user_id)->district_id;
        return $district_id;
    }

}
