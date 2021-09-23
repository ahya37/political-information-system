<?php

namespace App\Http\Controllers\Admin;

use PDF;
use App\Job;
use App\User;
use App\Referal;
use App\Models\Regency;
use App\Models\Village;
use App\Models\District;
use App\Models\Province;
use Maatwebsite\Excel\Excel;
use App\Providers\GrafikProvider;
use App\Exports\MemberExportRegency;
use App\Http\Controllers\Controller;
use App\Exports\MemberExportDistrict;
use App\Exports\MemberExportNational;
use App\Exports\MemberExportProvince;
use App\Exports\MemberExportVillage;
use Yajra\DataTables\Facades\DataTables;

class DashboardController extends Controller
{
    private $excel;

    public function __construct(Excel $excel)
    {
        $this->excel = $excel;
    }

    public function index()
    {
        $regencyModel     = new Regency();

        // Daftar pencapaian lokasi / daerah
        $achievments   = $regencyModel->achievements();
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
        return view('pages.admin.dashboard.index');
    }

    public function province($province_id)
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

        return view('pages.admin.dashboard.province', compact('province'));
    }

    public function regency($regency_id)
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

        return view('pages.admin.dashboard.regency', compact('regency'));
    }

    public function district($district_id)
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

        return view('pages.admin.dashboard.district', compact('district'));
    }

    public function village($district_id, $village_id)
    {
    //    $gF   = app('GlobalProvider'); // global function
    //    $GrafikProvider = new GrafikProvider();
       
       $villageModel = new Village();
       $village = $villageModel->with('district.regency.province')->where('id', $village_id)->first();

        //get anggota yang berada di desa tersebut
        // $members = $villageModel->getMemberVillage($village_id);
        // $total_member = count($members);

        // total desa yg berada di kec, yg sama
        // $total_village = $villageModel->where('district_id', $district_id)->count();
        // $total_target_per_district = 5000;
        // $target_member  = $gF->decimalFormat($total_target_per_district / $total_village);
        // $persentage_target_member = $gF->persen(($total_member/$target_member)*100);  
        
        // $userModel = new User();
        // $gender    = $userModel->getGenderVillage($village_id);
        // $CatGender  = $GrafikProvider->getGrafikGender($gender);
        // $cat_gender = $CatGender['cat_gender'];
        // $total_male_gender  = $CatGender['total_male_gender'];
        // $total_female_gender = $CatGender['total_female_gender'];

        // grafik data job
        // $jobModel = new Job();
        // $most_jobs = $jobModel->getMostJobsVillage($village_id);
        // $jobs     = $jobModel->getJobVillage($village_id);
        // $ChartJobs = $GrafikProvider->getGrafikJobs($jobs);
        // $chart_jobs= $ChartJobs['chart_jobs'];
        // $colors    = $ChartJobs['colors'];
        
        // range umur
        // $range_age     = $userModel->rangeAgeVillage($village_id);
        // $CatRange      = $GrafikProvider->getGrafikRangeAge($range_age);
        // $cat_range_age = $CatRange['cat_range_age'];
        // $cat_range_age_data = $CatRange['cat_range_age_data'];
        
        // generasi umur
        // $gen_age     = $userModel->generationAgeDistrict($district_id);
        // $GenAge      = $GrafikProvider->getGrafikGenAge($gen_age);
        // $cat_gen_age = $GenAge['cat_gen_age'];
        // $cat_gen_age_data = $GenAge['cat_gen_age_data'];

        // $referalModel = new Referal();
        // input admin terbanyak
        // $inputer      = $referalModel->getInputerVillage($village_id);
        // $cat_inputer = [];
        // foreach($inputer as $val){
        //     $cat_inputer['label'][] = $val->name;
        //     $cat_inputer['data'][]  = $val->total_data;
        // }

        // get fungsi grafik admin input terbanyak
        // $GrafikProvider = new GrafikProvider();
        // $chart_inputer  = $GrafikProvider->getGrafikInputer($cat_inputer);

        // anggota dengan referal terbanyak
        // $referalModel = new Referal();
        // $referal      = $referalModel->getReferalVillage($village_id);
        // $CatReferal   = $GrafikProvider->getGrafikReferal($referal);
        // $cat_referal      = $CatReferal['cat_referal'];
        // $cat_referal_data = $CatReferal['cat_referal_data'];

        // Daftar pencapaian lokasi / daerah
        $achievments   = $villageModel->achievementVillageFirst($village_id);

        return view('pages.admin.dashboard.village', compact('village'));

    }

    public function exportDataNationalExcel()
    {
      return $this->excel->download(new MemberExportNational(),'Anggota-Nasional'.'.xls');
    }

    public function exportDataProvinceExcel($province_id)
    {
      
      $province     = Province::select('name')->where('id', $province_id)->first();
      return $this->excel->download(new MemberExportProvince($province_id),'Anggota-Provinsi-'.$province->name.'.xls');
    }

    public function exportDataRegencyExcel($regency_id)
    {
      $regency  = Regency::select('name')->where('id', $regency_id)->first();
      return $this->excel->download(new MemberExportRegency($regency_id),'Anggota-Kabkot-'.$regency->name.'.xls');
    }

    public function exportDataDistrictExcel($district_id)
    {
      $district = District::select('name')->where('id', $district_id)->first();
      return $this->excel->download(new MemberExportDistrict($district_id),'Anggota-Kecamatan-'.$district->name.'.xls');
    }
    public function exportDataVillageExcel($village_id)
    {
      $village = Village::select('name')->where('id', $village_id)->first();
      return $this->excel->download(new MemberExportVillage($village_id),'Anggota-Desa-'.$village->name.'.xls');
    }

    public function downloadKTA($id)
    {
        $profile = User::with(['village'])->where('id', $id)->first();
        $pdf = PDF::loadView('pages.admin.member.card', compact('profile'))->setPaper('a4');
        return $pdf->stream('kta.pdf');

    }
}
