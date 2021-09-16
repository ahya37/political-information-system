<?php

namespace App\Http\Controllers\Admin;

use App\Job;
use App\User;
use App\Models\Regency;
use App\Models\Village;
use App\Models\District;
use App\Models\Province;
use PDF;
use Maatwebsite\Excel\Excel;
use App\Exports\MemberExportRegency;
use App\Http\Controllers\Controller;
use App\Exports\MemberExportDistrict;
use App\Exports\MemberExportProvince;
use App\Referal;
use Yajra\DataTables\Facades\DataTables;
use App\Charts\JobChart;
use App\Providers\GrafikProvider;

class DashboardController extends Controller
{
    private $excel;

    public function __construct(Excel $excel)
    {
        $this->excel = $excel;
    }

    public function index()
    {
        $gF   = app('GlobalProvider'); // global function
        $GrafikProvider = new GrafikProvider();


        $userModel        = new User();
        $total_member     = $userModel->where('village_id', '!=', NULL)->count();

        $regencyModel     = new Regency();
        $target_member    = $regencyModel->getRegency()->total_district * 5000;
        $persentage_target_member = ($total_member / $target_member) * 100; // persentai terdata

        $villageModel   = new Village();
        $total_village  = $villageModel->getVillages()->total_village; // fungsi total desa di provinsi banten
        $village_filled = $villageModel->getVillageFill(); // fungsi total desa di provinsi banten
        $total_village_filled      = count($village_filled);
        $presentage_village_filled = ($total_village_filled / $total_village) * 100; // persentasi jumlah desa terisi

         // Grfaik Data member
        $regencyModel     = new Regency();
        $province = $regencyModel->getGrafikTotalMember();
        $cat_province      = [];
        $cat_province_data = [];
        foreach ($province as $val) {
            $cat_province[] = $val->province; 
            $cat_province_data[] = [
                "y" => $val->total_member,
                "url" => route('admin-dashboard-province', $val->province_id)
            ];
        }

        // grafik data anggota terdaftar vs target
        $member_registered  = $userModel->getMemberRegisteredAll();
        $chart_member_registered = $GrafikProvider->getGrafikMemberRegistered($member_registered);

        // grafik data job
        $jobModel  = new Job();
        // $most_jobs = $jobModel->getMostJobs();
        $jobs      = $jobModel->getJobs();
        $ChartJobs = $GrafikProvider->getGrafikJobs($jobs);
        $chart_jobs= $ChartJobs['chart_jobs'];
        $colors    = $ChartJobs['colors'];

        // grafik data jenis kelamin
        $gender     = $userModel->getGenders();
        $CatGender  = $GrafikProvider->getGrafikGender($gender);
        $cat_gender = $CatGender['cat_gender'];
        $total_male_gender  = $CatGender['total_male_gender'];
        $total_female_gender = $CatGender['total_female_gender'];

        // range umur
        $range_age     = $userModel->rangeAge();
        $CatRange      = $GrafikProvider->getGrafikRangeAge($range_age);
        $cat_range_age = $CatRange['cat_range_age'];
        $cat_range_age_data = $CatRange['cat_range_age_data'];

         // generasi umur
        $gen_age     = $userModel->generationAges();
        $GenAge      = $GrafikProvider->getGrafikGenAge($gen_age);
        $cat_gen_age = $GenAge['cat_gen_age'];
        $cat_gen_age_data = $GenAge['cat_gen_age_data'];

        $referalModel = new Referal();
        // input admin terbanyak
        $inputer      = $referalModel->getInputers();
        $cat_inputer = [];
        foreach($inputer as $val){
            $cat_inputer['label'][] = $val->name;
            $cat_inputer['data'][]  = $val->total_data;
        }
        // get fungsi grafik admin input terbanyak
        $chart_inputer  = $GrafikProvider->getGrafikInputer($cat_inputer);

        // anggota dengan referal terbanyak
        $referal      = $referalModel->getReferals();
        $CatReferal   = $GrafikProvider->getGrafikReferal($referal);
        $cat_referal      = $CatReferal['cat_referal'];
        $cat_referal_data = $CatReferal['cat_referal_data'];
        
        return view('pages.admin.dashboard.index', compact('cat_referal_data','cat_referal','chart_inputer','cat_gen_age','cat_gen_age_data','cat_range_age','cat_range_age_data','chart_jobs','cat_gender','total_female_gender','total_male_gender','chart_member_registered','cat_province','cat_province_data','total_village','total_village_filled','presentage_village_filled','gF','total_member','target_member','persentage_target_member'));
    }

    public function province($province_id)
    {
        $province    = Province::select('id','name')->where('id', $province_id)->first();
        $gF   = app('GlobalProvider'); // global function
        $GrafikProvider = new GrafikProvider();

        $userModel        = new User();
        $member           = $userModel->getMemberProvince($province_id);
        $total_member     = count($member); // total anggota terdaftar

        $regencyModel     = new Regency();
        $target_member    = $regencyModel->getRegencyProvince($province_id)->total_district * 5000;
        $persentage_target_member = ($total_member / $target_member) * 100; // persentai terdata

        $villageModel   = new Village();
        $total_village  = $villageModel->getVillagesProvince($province_id)->total_village; // fungsi total desa di provinsi banten
        $village_filled = $villageModel->getVillageFillProvince($province_id); // fungsi total desa di provinsi banten
        $total_village_filled      = count($village_filled);
        $presentage_village_filled = ($total_village_filled / $total_village) * 100; // persentasi jumlah desa terisi

        // Grfaik Data member
        $regency = $regencyModel->getGrafikTotalMemberRegencyProvince($province_id);
        $cat_regency      = [];
        $cat_regency_data = [];
        foreach ($regency as $val) {
            $cat_regency[] = $val->regency; 
            $cat_regency_data[] = [
                "y" => $val->total_member,
                "url" => route('admin-dashboard-regency', $val->regency_id)
            ];
        }
        
        // grafik data anggota terdaftar vs target
        $member_registered  = $userModel->getMemberRegistered($province_id);
        $chart_member_registered = $GrafikProvider->getGrafikMemberRegistered($member_registered);
        
        // grafik data job
        $jobModel  = new Job();
        $most_jobs = $jobModel->getMostJobsProvince($province_id);
        $jobs      = $jobModel->getJobProvince($province_id);
        $ChartJobs = $GrafikProvider->getGrafikJobs($jobs);
        $chart_jobs= $ChartJobs['chart_jobs'];
        $colors    = $ChartJobs['colors'];

        // grafik data jenis kelamin
        $gender     = $userModel->getGenderProvince($province_id);
        $CatGender  = $GrafikProvider->getGrafikGender($gender);
        $cat_gender = $CatGender['cat_gender'];
        $total_male_gender  = $CatGender['total_male_gender'];
        $total_female_gender = $CatGender['total_female_gender'];
       

        // range umur
        $range_age     = $userModel->rangeAgeProvince($province_id);
        $CatRange      = $GrafikProvider->getGrafikRangeAge($range_age);
        $cat_range_age = $CatRange['cat_range_age'];
        $cat_range_age_data = $CatRange['cat_range_age_data'];

        // generasi umur
        $gen_age     = $userModel->generationAgeProvince($province_id);
        $GenAge      = $GrafikProvider->getGrafikGenAge($gen_age);
        $cat_gen_age = $GenAge['cat_gen_age'];
        $cat_gen_age_data = $GenAge['cat_gen_age_data'];

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

        $referalModel = new Referal();
        // input admin terbanyak
        $inputer      = $referalModel->getInputerProvince($province_id);
        $cat_inputer = [];
        foreach($inputer as $val){
            $cat_inputer['label'][] = $val->name;
            $cat_inputer['data'][]  = $val->total_data;
        }
        
        // get fungsi grafik admin input terbanyak
        $chart_inputer  = $GrafikProvider->getGrafikInputer($cat_inputer);

        // anggota dengan referal terbanyak
        $referal      = $referalModel->getReferalProvince($province_id);
        $CatReferal   = $GrafikProvider->getGrafikReferal($referal);
        $cat_referal      = $CatReferal['cat_referal'];
        $cat_referal_data = $CatReferal['cat_referal_data'];

        return view('pages.admin.dashboard.province', compact('province','chart_member_registered','cat_gen_age_data','cat_gen_age','chart_inputer','most_jobs','colors','chart_jobs','cat_referal_data','cat_referal','cat_range_age','cat_range_age_data','total_male_gender','total_female_gender','regency','cat_gender','cat_regency_data','cat_regency','gF','total_member','persentage_target_member','target_member','total_village_filled','presentage_village_filled','total_village'));
    }

    public function regency($regency_id)
    {
        $gF   = app('GlobalProvider'); // global function
        $GrafikProvider = new GrafikProvider(); // fungsi untuk grafik yang berulang
        
        // kirimkan regency_id ke provider khusus API untuk Dashboard

        $regency          = Regency::with('province')->where('id', $regency_id)->first();
        $userModel        = new User();
        $member           = $userModel->getMemberRegency($regency_id);   
        $total_member     = count($member); // total anggota terdaftar

        $districtModel    = new District();
        $target_member    = $districtModel->where('regency_id',$regency_id)->get()->count() * 5000; // target anggota tercapai, per kecamatan 1000 target
        $persentage_target_member = ($total_member / $target_member) * 100; // persentai terdata
        
        $villageModel   = new Village();
        $villages       = $villageModel->getVillagesRegency($regency_id); // fungsi total desa di kab
        $total_village  = count($villages);
        $village_filled = $villageModel->getVillageFilledRegency($regency_id); //fungsi total desa yang terisi 
        $total_village_filled      = count($village_filled); // total desa yang terisi
        $presentage_village_filled = ($total_village_filled / $total_village) * 100; // persentasi jumlah desa terisi

        // Grfaik Data member
        $districts = $districtModel->getGrafikTotalMemberDistrictRegency($regency_id);
        $cat_districts      = [];
        $cat_districts_data = [];
        foreach ($districts as $val) {
            $cat_districts[] = $val->district; 
            $cat_districts_data[] = [
                "y" => $val->total_member,
                "url" => route('admin-dashboard-district', $val->distric_id)
            ];
        }

         // grafik data anggota terdaftar vs target
        $member_registered  = $userModel->getMemberRegisteredRegency($regency_id);
        $chart_member_registered = $GrafikProvider->getGrafikMemberRegistered($member_registered);

        // grafik data job
        $jobModel = new Job();
        $most_jobs = $jobModel->getMostJobsRegency($regency_id);
        $jobs     = $jobModel->getJobRegency($regency_id);
        $ChartJobs = $GrafikProvider->getGrafikJobs($jobs);
        $chart_jobs= $ChartJobs['chart_jobs'];
        $colors    = $ChartJobs['colors'];

         // grafik data jenis kelamin
        $gender = $userModel->getGenderRegency($regency_id);
        $CatGender  = $GrafikProvider->getGrafikGender($gender);
        $cat_gender = $CatGender['cat_gender'];
        $total_male_gender  = $CatGender['total_male_gender'];
        $total_female_gender = $CatGender['total_female_gender'];

        // range umur
        $range_age     = $userModel->rangeAgeRegency($regency_id);
        $CatRange      = $GrafikProvider->getGrafikRangeAge($range_age);
        $cat_range_age = $CatRange['cat_range_age'];
        $cat_range_age_data = $CatRange['cat_range_age_data'];

        // generasi umur
        $gen_age     = $userModel->generationAgeRegency($regency_id);
        $GenAge      = $GrafikProvider->getGrafikGenAge($gen_age);
        $cat_gen_age = $GenAge['cat_gen_age'];
        $cat_gen_age_data = $GenAge['cat_gen_age_data'];

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

        $referalModel = new Referal();
        // input admin terbanyak
        $inputer      = $referalModel->getInputerRegency($regency_id);
        $cat_inputer = [];
        foreach($inputer as $val){
            $cat_inputer['label'][] = $val->name;
            $cat_inputer['data'][]  = $val->total_data;
        }

        // get fungsi grafik admin input terbanyak
        $chart_inputer  = $GrafikProvider->getGrafikInputer($cat_inputer);

         // anggota dengan referal terbanyak
        $referalModel = new Referal();
        $referal      = $referalModel->getReferalRegency($regency_id);
        $CatReferal   = $GrafikProvider->getGrafikReferal($referal);
        $cat_referal      = $CatReferal['cat_referal'];
        $cat_referal_data = $CatReferal['cat_referal_data'];

        return view('pages.admin.dashboard.regency', compact('cat_inputer','chart_inputer','cat_gen_age','cat_gen_age_data','chart_member_registered','cat_referal_data','cat_referal','cat_range_age_data','cat_range_age','total_male_gender','total_female_gender','regency','gender','cat_gender','chart_jobs','total_member','target_member','persentage_target_member','gF','total_village','total_village_filled','presentage_village_filled','cat_districts','cat_districts_data'));
    }

    public function district($district_id)
    {
        $gF   = app('GlobalProvider'); // global function
        $GrafikProvider = new GrafikProvider();

        $district   = District::with(['regency'])->where('id', $district_id)->first();
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
                "url" => route('admin-dashboard-village', ['district_id' => $district_id,'village_id' => $val->village_id])
            ];
        }

        // grafik data anggota terdaftar vs target
        $member_registered  = $userModel->getMemberRegisteredDistrct($district_id);
        $chart_member_registered = $GrafikProvider->getGrafikMemberRegisteredDistrict($member_registered);

        // grafik data job
        $jobModel = new Job();
        $most_jobs = $jobModel->getMostJobsDistrict($district_id);
        $jobs     = $jobModel->getJobDistrict($district_id);
        $ChartJobs = $GrafikProvider->getGrafikJobs($jobs);
        $chart_jobs= $ChartJobs['chart_jobs'];
        $colors    = $ChartJobs['colors'];

        // grafik data jenis kelamin
        $gender = $userModel->getGenderDistrict($district_id);
        $CatGender  = $GrafikProvider->getGrafikGender($gender);
        $cat_gender = $CatGender['cat_gender'];
        $total_male_gender  = $CatGender['total_male_gender'];
        $total_female_gender = $CatGender['total_female_gender'];
        
        // range umur
        $range_age     = $userModel->rangeAgeDistrict($district_id);
        $CatRange      = $GrafikProvider->getGrafikRangeAge($range_age);
        $cat_range_age = $CatRange['cat_range_age'];
        $cat_range_age_data = $CatRange['cat_range_age_data'];

        // generasi umur
        $gen_age     = $userModel->generationAgeDistrict($district_id);
        $GenAge      = $GrafikProvider->getGrafikGenAge($gen_age);
        $cat_gen_age = $GenAge['cat_gen_age'];
        $cat_gen_age_data = $GenAge['cat_gen_age_data'];

         // Daftar pencapaian lokasi / daerah
        $achievments   = $villageModel->achievementVillage($district_id);
        if (request()->ajax()) {
            return DataTables::of($achievments)->make();
        }

        $referalModel = new Referal();
        // input admin terbanyak
        $inputer      = $referalModel->getInputerDistrict($district_id);
        $cat_inputer = [];
        foreach($inputer as $val){
            $cat_inputer['label'][] = $val->name;
            $cat_inputer['data'][]  = $val->total_data;
        }

         // get fungsi grafik admin input terbanyak
        $chart_inputer  = $GrafikProvider->getGrafikInputer($cat_inputer);

        // anggota dengan referal terbanyak
        $referalModel = new Referal();
        $referal      = $referalModel->getReferalDistrict($district_id);
        $CatReferal   = $GrafikProvider->getGrafikReferal($referal);
        $cat_referal      = $CatReferal['cat_referal'];
        $cat_referal_data = $CatReferal['cat_referal_data'];

        return view('pages.admin.dashboard.district', compact('chart_inputer','cat_gen_age','cat_gen_age_data','chart_jobs','chart_member_registered','cat_referal_data','cat_referal','cat_range_age_data','cat_range_age','total_male_gender','total_female_gender','cat_gender','cat_districts','cat_districts_data','total_village_filled','presentage_village_filled','total_village','target_member','persentage_target_member','district','gF','total_member'));
    }

    public function village($district_id, $village_id)
    {
       $gF   = app('GlobalProvider'); // global function
       $GrafikProvider = new GrafikProvider();
       
       $villageModel = new Village();
       $village = $villageModel->with('district.regency.province')->where('id', $village_id)->first();

        //get anggota yang berada di desa tersebut
        $members = $villageModel->getMemberVillage($village_id);
        $total_member = count($members);

        // total desa yg berada di kec, yg sama
        $total_village = $villageModel->where('district_id', $district_id)->count();
        $total_target_per_district = 5000;
        $target_member  = $gF->decimalFormat($total_target_per_district / $total_village);
        $persentage_target_member = $gF->persen(($total_member/$target_member)*100);  
        
        $userModel = new User();
        $gender    = $userModel->getGenderVillage($village_id);
        $CatGender  = $GrafikProvider->getGrafikGender($gender);
        $cat_gender = $CatGender['cat_gender'];
        $total_male_gender  = $CatGender['total_male_gender'];
        $total_female_gender = $CatGender['total_female_gender'];

        // grafik data job
        $jobModel = new Job();
        $most_jobs = $jobModel->getMostJobsVillage($village_id);
        $jobs     = $jobModel->getJobVillage($village_id);
        $ChartJobs = $GrafikProvider->getGrafikJobs($jobs);
        $chart_jobs= $ChartJobs['chart_jobs'];
        $colors    = $ChartJobs['colors'];
        
        // range umur
        $range_age     = $userModel->rangeAgeVillage($village_id);
        $CatRange      = $GrafikProvider->getGrafikRangeAge($range_age);
        $cat_range_age = $CatRange['cat_range_age'];
        $cat_range_age_data = $CatRange['cat_range_age_data'];
        
        // generasi umur
        $gen_age     = $userModel->generationAgeDistrict($district_id);
        $GenAge      = $GrafikProvider->getGrafikGenAge($gen_age);
        $cat_gen_age = $GenAge['cat_gen_age'];
        $cat_gen_age_data = $GenAge['cat_gen_age_data'];

        $referalModel = new Referal();
        // input admin terbanyak
        $inputer      = $referalModel->getInputerVillage($village_id);
        $cat_inputer = [];
        foreach($inputer as $val){
            $cat_inputer['label'][] = $val->name;
            $cat_inputer['data'][]  = $val->total_data;
        }

        // get fungsi grafik admin input terbanyak
        $GrafikProvider = new GrafikProvider();
        $chart_inputer  = $GrafikProvider->getGrafikInputer($cat_inputer);

        // anggota dengan referal terbanyak
        $referalModel = new Referal();
        $referal      = $referalModel->getReferalVillage($village_id);
        $CatReferal   = $GrafikProvider->getGrafikReferal($referal);
        $cat_referal      = $CatReferal['cat_referal'];
        $cat_referal_data = $CatReferal['cat_referal_data'];

        // Daftar pencapaian lokasi / daerah
        $achievments   = $villageModel->achievementVillageFirst($village_id);

        return view('pages.admin.dashboard.village', compact('achievments','cat_referal','cat_referal_data','chart_inputer','cat_gen_age_data','cat_gen_age','cat_range_age','cat_range_age_data','chart_jobs','cat_gender','total_female_gender','total_male_gender','gF','village','total_member','persentage_target_member','target_member'));

    }

    public function exportDataProvinceExcel()
    {
      $province_id  = 36;
      $province     = Province::select('name')->where('id', $province_id)->first();
      return $this->excel->download(new MemberExportProvince($province_id),'Anggota-'.$province->name.'.xls');
    }

    public function exportDataRegencyExcel($regency_id)
    {
      $regency  = Regency::select('name')->where('id', $regency_id)->first();
      return $this->excel->download(new MemberExportRegency($regency_id),'Anggota-'.$regency->name.'.xls');
    }

    public function exportDataDistrictExcel($district_id)
    {
      $district = District::select('name')->where('id', $district_id)->first();
      return $this->excel->download(new MemberExportDistrict($district_id),'Anggota-'.$district->name.'.xls');
    }

    public function downloadKTA($id)
    {
        $profile = User::with(['village'])->where('id', $id)->first();
        $pdf = PDF::loadView('pages.admin.member.card', compact('profile'))->setPaper('a4');
        return $pdf->stream('kta.pdf');

    }
}
