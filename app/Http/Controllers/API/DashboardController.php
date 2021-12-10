<?php

namespace App\Http\Controllers\API;

use App\Job;
use App\User;
use App\Referal;
use App\TargetNumber;
use Carbon\Carbon;
use App\Models\Regency;
use App\Models\Village;
use App\Models\District;
use App\Providers\GrafikProvider;
use App\Http\Controllers\Controller;
use App\Models\Province;

class DashboardController extends Controller
{
    public function memberReportPerMountNation($daterange)
    {
        if ($daterange != '') {
            $date  = explode('+', $daterange);
            $start = Carbon::parse($date[0])->format('Y-m-d');
            $end   = Carbon::parse($date[1])->format('Y-m-d'); 
        }
        // dd($start);

        $userModel = new User();
        $member    = $userModel->getMemberRegisteredByDayNation($start, $end); 
       
        $data = [];
        foreach ($member as $value) {
            $data[] = [
                'day' => date('d-m-Y', strtotime($value->day)),
                'count' => $value->total
            ];
        }
        return $data;
    }

    public function memberReportPerMountProvince($daterange, $provinceID)
    {
        if ($daterange != '') {
            $date  = explode('+', $daterange);
            $start = Carbon::parse($date[0])->format('Y-m-d');
            $end   = Carbon::parse($date[1])->format('Y-m-d'); 
        }
        // dd($start);

        $province_id = $provinceID;
        $userModel = new User();
        $member    = $userModel->getMemberRegisteredByDayProvince($province_id, $start, $end); 
       
        $data = [];
        foreach ($member as $value) {
            $data[] = [
                'day' => date('d-m-Y', strtotime($value->day)),
                'count' => $value->total
            ];
        }
        return $data;
    }

    public function memberReportPerMountRegency($daterange, $regencyID)
    {
        if ($daterange != '') {
            $date  = explode('+', $daterange);
            $start = Carbon::parse($date[0])->format('Y-m-d');
            $end   = Carbon::parse($date[1])->format('Y-m-d'); 
        }
        // dd($start);

        $regency_id = $regencyID;
        $userModel = new User();
        $member    = $userModel->getMemberRegisteredByDayRegency($regency_id, $start, $end);
       
        $data = [];
        foreach ($member as $value) {
            $data[] = [
                'day' => date('d-m-Y', strtotime($value->day)),
                'count' => $value->total
            ];
        }
        return $data;
    }

    public function memberReportPerMountDistrict($daterange, $districtID)
    {
        if ($daterange != '') {
            $date  = explode('+', $daterange);
            $start = Carbon::parse($date[0])->format('Y-m-d');
            $end   = Carbon::parse($date[1])->format('Y-m-d'); 
        }
        // dd($start);

        $district_id = $districtID;
        $userModel = new User();
        $member    = $userModel->getMemberRegisteredByDayDistrict($district_id, $start, $end);
       
        $data = [];
        foreach ($member as $value) {
            $data[] = [
                'day' => date('d-m-Y', strtotime($value->day)),
                'count' => $value->total
            ];
        }
        return $data;
    }

    public function memberReportPerMountVillage($daterange, $villageID)
    {
        if ($daterange != '') {
            $date  = explode('+', $daterange);
            $start = Carbon::parse($date[0])->format('Y-m-d');
            $end   = Carbon::parse($date[1])->format('Y-m-d'); 
        }
        // dd($start);

        $village_id = $villageID;
        $userModel = new User();
        $member    = $userModel->getMemberRegisteredByDayVillage($village_id, $start, $end);
       
        $data = [];
        foreach ($member as $value) {
            $data[] = [
                'day' => date('d-m-Y', strtotime($value->day)),
                'count' => $value->total
            ];
        }
        return $data;
    }

    public function getAchievmentsNational()
    {
        $regencyModel     = new Regency();
        $achievments   = $regencyModel->achievements();
        return response()->json($achievments);
    }

    public function getMemberNational()
    {
         $regencyModel     = new Regency();
         $province = $regencyModel->getTotalMember();
         $cat_province      = [];
         $cat_province_data = [];
         foreach ($province as $val) {
                $cat_province[] = $val->province; 
                $cat_province_data[] = [
                    "y" => $val->total_member,
                    "url" => route('admin-dashboard-province', $val->province_id)
                ];
         }
        //  $colors = $province_label->map(function($item){
        //     return $rand_color = '#' . substr(md5(mt_rand()),0,6);
        // });
        $data = [
            'cat_province' => $cat_province,
            'cat_province_data' => $cat_province_data,
            // 'colors' => $colors
        ];
        return response()->json($data);
    }

    public function getMemberRegency($regency_id)
    {
        $districtModel    = new District();

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
        
        $data = [
            'cat_districts' => $cat_districts,
            'cat_districts_data' => $cat_districts_data,
            // 'colors' => $colors
        ];
        return response()->json($data);
    }

    public function getMemberDistrict($district_id)
    {
        $districtModel    = new District();

        $districts = $districtModel->getGrafikTotalMemberDistrict($district_id);;
        $cat_districts      = [];
        $cat_districts_data = [];
        foreach ($districts as $val) {
            $cat_districts[] = $val->district; 
            $cat_districts_data[] = [
                "y" => $val->total_member,
                "url" => route('admin-dashboard-village', ['district_id' => $district_id,'village_id' => $val->village_id])
            ];
        }
        
        $data = [
            'cat_districts' => $cat_districts,
            'cat_districts_data' => $cat_districts_data,
            // 'colors' => $colors
        ];
        return response()->json($data);
    }

    public function getTotalMemberNational()
    {
        $gF   = app('GlobalProvider'); // global function
        // $referalModel = new Referal();
        // $referal      = $referalModel->getReferealByDefault();
        // $total_member     = collect($referal)->sum(function($q){
        //     return $q->total;
        // });
        $userModel        = new User();
        $regencyModel     = new Regency();
        $targetMember     = $gF->calculateTargetNational();
        $total_member     = $gF->decimalFormat($userModel->where('village_id', '!=', NULL)->count());
        $target_member    = (string) $targetMember;
        $persentage_target_member = $gF->persen(($total_member / $target_member) * 100); // persentai terdata

        $villageModel   = new Village();
        $total_village  = $villageModel->getVillages()->total_village; // fungsi total desa di provinsi banten
        $village_filled = $villageModel->getVillageFill(); // fungsi total desa di provinsi banten
        $total_village_filled      = count($village_filled);
        $presentage_village_filled = $gF->persen(($total_village_filled / $total_village) * 100); // persentasi jumlah desa terisi

        $data = [
            'total_village' => $gF->decimalFormat($total_village),
            'total_village_filled' => $gF->decimalFormat($total_village_filled),
            'presentage_village_filled' => $presentage_village_filled,
            'total_member' => $gF->decimalFormat($total_member),
            'target_member' => $gF->decimalFormat($target_member),
            'persentage_target_member' => $persentage_target_member
        ];
        return response()->json($data);

    }

    public function getTotalMemberRegency($regency_id)
    {
        $gF   = app('GlobalProvider'); // global function

        $userModel        = new User();
        $member           = $userModel->getMemberRegency($regency_id);   
        $total_member     = count($member); // total anggota terdaftar

        $regencyModel     = Regency::select('target')->where('id', $regency_id)->first();
        $targetMember    = $regencyModel->target; // target anggota tercapai, per kecamatan 1000 target
;
        $target_member    = (string) $targetMember;
        $persentage_target_member = ($total_member / $target_member) * 100;

        $villageModel   = new Village();
        $villages       = $villageModel->getVillagesRegency($regency_id); // fungsi total desa di kab
        $total_village  = count($villages);
        $village_filled = $villageModel->getVillageFilledRegency($regency_id); //fungsi total desa yang terisi 
        $total_village_filled      = count($village_filled); // total desa yang terisi
        $presentage_village_filled = ($total_village_filled / $total_village) * 100; // persentasi jumlah desa terisi

        $data = [
            'total_village' => $gF->decimalFormat($total_village),
            'total_village_filled' => $gF->decimalFormat($total_village_filled),
            'presentage_village_filled' => $gF->persen($presentage_village_filled),
            'total_member' => $gF->decimalFormat($total_member),
            'target_member' => $gF->decimalFormat($target_member),
            'persentage_target_member' => $gF->persen($persentage_target_member)
        ];
        return response()->json($data);

    }

    public function getTotalMemberDistrict($district_id)
    {
        $gF   = app('GlobalProvider'); // global function
        
        $userModel        = new User();
        $member     = $userModel->getMemberDistrict($district_id);
        $total_member = count($member);
        
        // perentasi anggot  di kecamatan
        $districtModel    = District::select('target')->where('id', $district_id)->first();
        $target_member    = $districtModel->target; // target anggota tercapai, per kecamatan 1000 target
        $persentage_target_member = $gF->persen(($total_member / $target_member) * 100); // persentai terdata

        $villageModel   = new Village();
        $villages       = $villageModel->getVillagesDistrct($district_id); // fungsi total desa di kab
        $total_village  = count($villages);

        $village_filled = $villageModel->getVillageFilledDistrict($district_id); //fungsi total desa yang terisi 
        $total_village_filled      = count($village_filled); // total desa yang terisi
        $presentage_village_filled = $gF->persen(($total_village_filled / $total_village) * 100); // persentasi jumlah desa terisi

        $data = [
            'total_village' => $gF->decimalFormat($total_village),
            'total_village_filled' => $gF->decimalFormat($total_village_filled),
            'presentage_village_filled' => $presentage_village_filled,
            'total_member' => $total_member,
            'target_member' => $gF->decimalFormat($target_member),
            'persentage_target_member' => $persentage_target_member
        ];
        return response()->json($data);

    }

    public function getTotalMemberVillage($district_id, $village_id)
    {
        $gF   = app('GlobalProvider'); // global function
        $villageModel   = new Village();

        $member     = $villageModel->getMemberVillage($village_id);
        $total_member = count($member);

         // total desa yg berada di kec, yg sama
        $targetMmemberModel = $villageModel->select('target')->where('id', $village_id)->first();
        $total_target_per_district = $targetMmemberModel->target;
        $target_member  = $gF->decimalFormat($total_target_per_district);
        $persentage_target_member = $gF->persen(($total_member/$target_member)*100);
        
        // Daftar pencapaian lokasi / daerah
        $achievments   = $villageModel->achievementVillageFirst($village_id);

        $data = [
            'achievments' => $gF->decimalFormat($achievments->todays_achievement ?? ''),
            'total_member' => $gF->decimalFormat($total_member),
            'target_member' => $target_member,
            'persentage_target_member' => $persentage_target_member
        ];
        return response()->json($data);

    }

    public function getMemberVsTargetNational()
    {
        $gF   = app('GlobalProvider'); // global function

        $userModel        = new User();
        $member_registered  = $userModel->getMemberRegisteredAll();
        $chart_member_target = [];
        foreach ($member_registered as $val) {
            $chart_member_target['label'][] = $val->name;
            $chart_member_target['target'][] =  $val->target_member;
            $chart_member_target['persentage'][] = $gF->persen(($val->realisasi_member/$val->target_member)*100);
        }
        $data = [
            'label' => $chart_member_target['label'],
            'persentage' => $chart_member_target['persentage'],
            'value_target' =>  $chart_member_target['target'] 
        ];
        return response()->json($data);
    }

    public function getMemberVsTargetRegency($regency_id)
    {
        $gF   = app('GlobalProvider'); // global function

        $userModel        = new User();
        $member_registered  = $userModel->getMemberRegisteredRegency($regency_id);
        $chart_member_target = [];
        foreach ($member_registered as $val) {
            $chart_member_target['label'][] = $val->name;
            $chart_member_target['target'][] = $val->target_member;
            $chart_member_target['persentage'][] = $gF->persen(($val->realisasi_member/$val->target_member)*100);
        }
        $data = [
            'label' => $chart_member_target['label'],
            'persentage' => $chart_member_target['persentage'],
            'value_target' =>  $chart_member_target['target'] 
        ];
        return response()->json($data);
    }

    public function getMemberVsTargetDistrict($district_id)
    {
        $gF   = app('GlobalProvider'); // global function


        $userModel        = new User();
        $member_registered  = $userModel->getMemberRegisteredDistrct($district_id);
        $chart_member_target = [];
        foreach ($member_registered as $val) {
            $chart_member_target['label'][] = $val->name;
            $chart_member_target['target'][] = $gF->decimalFormat($val->target_member);
            $chart_member_target['persentage'][] = $gF->persen(($val->realisasi_member/$val->target_member)*100);
        }
        $data = [
            'label' => $chart_member_target['label'],
            'persentage' => $chart_member_target['persentage'],
            'value_target' =>  $chart_member_target['target'] 
        ];
        return response()->json($data);
    }
    

    public function getGenderNational()
    {
        $gF   = app('GlobalProvider'); // global function
        $GrafikProvider = new GrafikProvider();

        $userModel = new User();
        $gender     = $userModel->getGenders();
        $CatGender  = $GrafikProvider->getGrafikGender($gender);
       
        $cat_gender = $CatGender['cat_gender'];
        $total_male_gender  = $CatGender['total_male_gender'];
        $total_female_gender = $CatGender['total_female_gender'];

        $data  = [
            'cat_gender' => $cat_gender,
            'total_male_gender' => $total_male_gender,
            'total_female_gender' => $total_female_gender
        ];
        return response()->json($data);
        
    }

    public function getGenderRegency($regency_id)
    {
        $gF   = app('GlobalProvider'); // global function
        $GrafikProvider = new GrafikProvider();

        $userModel = new User();
        $gender     = $userModel->getGenderRegency($regency_id);
        $CatGender  = $GrafikProvider->getGrafikGender($gender);
       
        $cat_gender = $CatGender['cat_gender'];
        $total_male_gender  = $CatGender['total_male_gender'];
        $total_female_gender = $CatGender['total_female_gender'];

        $data  = [
            'cat_gender' => $cat_gender,
            'total_male_gender' => $total_male_gender,
            'total_female_gender' => $total_female_gender
        ];
        return response()->json($data);
        
    }

    public function getGenderDistrict($district_id)
    {
        $gF   = app('GlobalProvider'); // global function
        $GrafikProvider = new GrafikProvider();

        $userModel = new User();
        $gender     = $userModel->getGenderDistrict($district_id);
        $CatGender  = $GrafikProvider->getGrafikGender($gender);
       
        $cat_gender = $CatGender['cat_gender'];
        $total_male_gender  = $CatGender['total_male_gender'];
        $total_female_gender = $CatGender['total_female_gender'];

        $data  = [
            'cat_gender' => $cat_gender,
            'total_male_gender' => $total_male_gender,
            'total_female_gender' => $total_female_gender
        ];
        return response()->json($data);
        
    }

    public function getGenderVillage($village_id)
    {
        $gF   = app('GlobalProvider'); // global function
        $GrafikProvider = new GrafikProvider();

        $userModel = new User();
        $gender     = $userModel->getGenderVillage($village_id);
        $CatGender  = $GrafikProvider->getGrafikGender($gender);
       
        $cat_gender = $CatGender['cat_gender'];
        $total_male_gender  = $CatGender['total_male_gender'];
        $total_female_gender = $CatGender['total_female_gender'];

        $data  = [
            'cat_gender' => $cat_gender,
            'total_male_gender' => $total_male_gender,
            'total_female_gender' => $total_female_gender
        ];
        return response()->json($data);
        
    }

    public function getJobsNational()
    {
        $GrafikProvider = new GrafikProvider();
        $jobModel  = new Job();
        // $most_jobs = $jobModel->getMostJobs();
        $jobs      = $jobModel->getJobs();
        $ChartJobs = $GrafikProvider->getGrafikJobs($jobs);
        $chart_jobs_label= $ChartJobs['chart_jobs_label'];
        $chart_jobs_data= $ChartJobs['chart_jobs_data'];
        $color_jobs    = $ChartJobs['color_jobs'];

        $data = [

            'chart_jobs_label' => $chart_jobs_label,
            'chart_jobs_data'  => $chart_jobs_data,
            'color_jobs' => $color_jobs,
        ];
        return response()->json($data);

    }

    public function getJobsRegency($regency_id)
    {
        $GrafikProvider = new GrafikProvider();
        $jobModel  = new Job();
        // $most_jobs = $jobModel->getMostJobs();
        $jobs      = $jobModel->getJobRegency($regency_id);
        $ChartJobs = $GrafikProvider->getGrafikJobs($jobs);
        $chart_jobs_label= $ChartJobs['chart_jobs_label'];
        $chart_jobs_data= $ChartJobs['chart_jobs_data'];
        $color_jobs    = $ChartJobs['color_jobs'];

        $data = [

            'chart_jobs_label' => $chart_jobs_label,
            'chart_jobs_data'  => $chart_jobs_data,
            'color_jobs' => $color_jobs,
        ];
        return response()->json($data);

    }

    public function getJobsDistrict($district_id)
    {
        $GrafikProvider = new GrafikProvider();
        $jobModel  = new Job();
        // $most_jobs = $jobModel->getMostJobs();
        $jobs      = $jobModel->getJobDistrict($district_id);
        $ChartJobs = $GrafikProvider->getGrafikJobs($jobs);
        $chart_jobs_label= $ChartJobs['chart_jobs_label'];
        $chart_jobs_data= $ChartJobs['chart_jobs_data'];
        $color_jobs    = $ChartJobs['color_jobs'];

        $data = [

            'chart_jobs_label' => $chart_jobs_label,
            'chart_jobs_data'  => $chart_jobs_data,
            'color_jobs' => $color_jobs,
        ];
        return response()->json($data);

    }

    public function getJobsVillage($village_id)
    {
        $GrafikProvider = new GrafikProvider();
        $jobModel  = new Job();
        // $most_jobs = $jobModel->getMostJobs();
        $jobs      = $jobModel->getJobVillage($village_id);
        $ChartJobs = $GrafikProvider->getGrafikJobs($jobs);
        $chart_jobs_label= $ChartJobs['chart_jobs_label'];
        $chart_jobs_data= $ChartJobs['chart_jobs_data'];
        $color_jobs    = $ChartJobs['color_jobs'];

        $data = [

            'chart_jobs_label' => $chart_jobs_label,
            'chart_jobs_data'  => $chart_jobs_data,
            'color_jobs' => $color_jobs,
        ];
        return response()->json($data);

    }

    public function getAgeGroupNational()
    {
        $GrafikProvider = new GrafikProvider();
        $userModel = new User();

        $range_age     = $userModel->rangeAge();
        $CatRange      = $GrafikProvider->getGrafikRangeAge($range_age);
        $cat_range_age = $CatRange['cat_range_age'];
        $cat_range_age_data = $CatRange['cat_range_age_data'];

        $data = [
            'cat_range_age' => $cat_range_age,
            'cat_range_age_data' => $cat_range_age_data
        ];

        return response()->json($data);

    }

    public function getAgeGroupRegency($regency_id)
    {
        $GrafikProvider = new GrafikProvider();
        $userModel = new User();

        $range_age     = $userModel->rangeAgeRegency($regency_id);
        $CatRange      = $GrafikProvider->getGrafikRangeAge($range_age);
        $cat_range_age = $CatRange['cat_range_age'];
        $cat_range_age_data = $CatRange['cat_range_age_data'];

        $data = [
            'cat_range_age' => $cat_range_age,
            'cat_range_age_data' => $cat_range_age_data
        ];

        return response()->json($data);

    }

    public function getAgeGroupDistrict($district_id)
    {
        $GrafikProvider = new GrafikProvider();
        $userModel = new User();

        $range_age     = $userModel->rangeAgeDistrict($district_id);
        $CatRange      = $GrafikProvider->getGrafikRangeAge($range_age);
        $cat_range_age = $CatRange['cat_range_age'];
        $cat_range_age_data = $CatRange['cat_range_age_data'];

        $data = [
            'cat_range_age' => $cat_range_age,
            'cat_range_age_data' => $cat_range_age_data
        ];

        return response()->json($data);

    }

    public function getAgeGroupVillage($village_id)
    {
        $GrafikProvider = new GrafikProvider();
        $userModel = new User();

        $range_age     = $userModel->rangeAgeVillage($village_id);
        $CatRange      = $GrafikProvider->getGrafikRangeAge($range_age);
        $cat_range_age = $CatRange['cat_range_age'];
        $cat_range_age_data = $CatRange['cat_range_age_data'];

        $data = [
            'cat_range_age' => $cat_range_age,
            'cat_range_age_data' => $cat_range_age_data
        ];

        return response()->json($data);

    }

    public function getAgeGroupProvince($province_id)
    {
        $GrafikProvider = new GrafikProvider();
        $userModel = new User();

        $range_age     = $userModel->rangeAgeProvince($province_id);
        $CatRange      = $GrafikProvider->getGrafikRangeAge($range_age);
        $cat_range_age = $CatRange['cat_range_age'];
        $cat_range_age_data = $CatRange['cat_range_age_data'];

        $data = [
            'cat_range_age' => $cat_range_age,
            'cat_range_age_data' => $cat_range_age_data
        ];

        return response()->json($data);

    }

    public function genAgeNational()
    {
        $GrafikProvider = new GrafikProvider();
        $userModel = new User();

        $gen_age     = $userModel->generationAges();
        $GenAge      = $GrafikProvider->getGrafikGenAge($gen_age);
        $cat_gen_age = $GenAge['cat_gen_age'];
        $cat_gen_age_data = $GenAge['cat_gen_age_data'];

        $data = [
            'cat_gen_age' => $cat_gen_age,
            'cat_gen_age_data' => $cat_gen_age_data
        ];
        return response()->json($data);

    }

    public function genAgeRegency($regency_id)
    {
        $GrafikProvider = new GrafikProvider();
        $userModel = new User();

        $gen_age     = $userModel->generationAgeRegency($regency_id);
        $GenAge      = $GrafikProvider->getGrafikGenAge($gen_age);
        $cat_gen_age = $GenAge['cat_gen_age'];
        $cat_gen_age_data = $GenAge['cat_gen_age_data'];

        $data = [
            'cat_gen_age' => $cat_gen_age,
            'cat_gen_age_data' => $cat_gen_age_data
        ];
        return response()->json($data);

    }

     public function genAgeDistrtict($district_id)
    {
        $GrafikProvider = new GrafikProvider();
        $userModel = new User();

        $gen_age     = $userModel->generationAgeDistrict($district_id);
        $GenAge      = $GrafikProvider->getGrafikGenAge($gen_age);
        $cat_gen_age = $GenAge['cat_gen_age'];
        $cat_gen_age_data = $GenAge['cat_gen_age_data'];

        $data = [
            'cat_gen_age' => $cat_gen_age,
            'cat_gen_age_data' => $cat_gen_age_data
        ];
        return response()->json($data);

    }

     public function genAgeVillage($village_id)
    {
        $GrafikProvider = new GrafikProvider();
        $userModel = new User();

        $gen_age     = $userModel->generationAgeVillage($village_id);
        $GenAge      = $GrafikProvider->getGrafikGenAge($gen_age);
        $cat_gen_age = $GenAge['cat_gen_age'];
        $cat_gen_age_data = $GenAge['cat_gen_age_data'];

        $data = [
            'cat_gen_age' => $cat_gen_age,
            'cat_gen_age_data' => $cat_gen_age_data
        ];
        return response()->json($data);

    }


    public function genAgeProvince($province_id)
    {
        $GrafikProvider = new GrafikProvider();
        $userModel = new User();

        $gen_age     = $userModel->generationAgeProvince($province_id);
        $GenAge      = $GrafikProvider->getGrafikGenAge($gen_age);
        $cat_gen_age = $GenAge['cat_gen_age'];
        $cat_gen_age_data = $GenAge['cat_gen_age_data'];

        $data = [
            'cat_gen_age' => $cat_gen_age,
            'cat_gen_age_data' => $cat_gen_age_data
        ];
        return response()->json($data);

    }

    public function getInputerNational()
    {
        $referalModel = new Referal();
        $GrafikProvider = new GrafikProvider();

        // input admin terbanyak
        $inputer      = $referalModel->getInputers();
        // get fungsi grafik admin input terbanyak
        $ChartInputer = $GrafikProvider->getGrafikInputer($inputer);
        $cat_inputer_label = $ChartInputer['cat_inputer_label'];
        $cat_inputer_data = $ChartInputer['cat_inputer_data'];
        $color_inputer = $ChartInputer['colors'];

        $data = [
            'cat_inputer_label' => $cat_inputer_label,
            'cat_inputer_data' => $cat_inputer_data,
            'color_inputer' => $color_inputer
        ];
        return response()->json($data);

    }

    public function getInputerRegency($regency_id)
    {
        $referalModel = new Referal();
        $GrafikProvider = new GrafikProvider();

        // input admin terbanyak
        $inputer      = $referalModel->getInputerRegency($regency_id);
        // get fungsi grafik admin input terbanyak
        $ChartInputer = $GrafikProvider->getGrafikInputer($inputer);
        $cat_inputer_label = $ChartInputer['cat_inputer_label'];
        $cat_inputer_data = $ChartInputer['cat_inputer_data'];
        $color_inputer = $ChartInputer['colors'];

        $data = [
            'cat_inputer_label' => $cat_inputer_label,
            'cat_inputer_data' => $cat_inputer_data,
            'color_inputer' => $color_inputer
        ];
        return response()->json($data);

    }

    public function getInputerDistrict($district_id)
    {
        $referalModel = new Referal();
        $GrafikProvider = new GrafikProvider();

        // input admin terbanyak
        $inputer      = $referalModel->getInputerDistrict($district_id);
        // get fungsi grafik admin input terbanyak
        $ChartInputer = $GrafikProvider->getGrafikInputer($inputer);
        $cat_inputer_label = $ChartInputer['cat_inputer_label'];
        $cat_inputer_data = $ChartInputer['cat_inputer_data'];
        $color_inputer = $ChartInputer['colors'];

        $data = [
            'cat_inputer_label' => $cat_inputer_label,
            'cat_inputer_data' => $cat_inputer_data,
            'color_inputer' => $color_inputer
        ];
        return response()->json($data);

    }

    public function getInputerVillage($village_id)
    {
        $referalModel = new Referal();
        $GrafikProvider = new GrafikProvider();

        // input admin terbanyak
        $inputer      = $referalModel->getInputerVillage($village_id);
        // get fungsi grafik admin input terbanyak
        $ChartInputer = $GrafikProvider->getGrafikInputer($inputer);
        $cat_inputer_label = $ChartInputer['cat_inputer_label'];
        $cat_inputer_data = $ChartInputer['cat_inputer_data'];
        $color_inputer = $ChartInputer['colors'];

        $data = [
            'cat_inputer_label' => $cat_inputer_label,
            'cat_inputer_data' => $cat_inputer_data,
            'color_inputer' => $color_inputer
        ];
        return response()->json($data);

    }

    public function getInputerProvince($province_id)
    {
        $referalModel = new Referal();
        $GrafikProvider = new GrafikProvider();

        // input admin terbanyak
        $inputer      = $referalModel->getInputerProvince($province_id);
        // get fungsi grafik admin input terbanyak
        $ChartInputer = $GrafikProvider->getGrafikInputer($inputer);
        $cat_inputer_label = $ChartInputer['cat_inputer_label'];
        $cat_inputer_data = $ChartInputer['cat_inputer_data'];
        $color_inputer = $ChartInputer['colors'];

        $data = [
            'cat_inputer_label' => $cat_inputer_label,
            'cat_inputer_data' => $cat_inputer_data,
            'color_inputer' => $color_inputer
        ];
        return response()->json($data);

    }

    public function getRegefalNational()
    {
        $referalModel = new Referal();
        $GrafikProvider = new GrafikProvider();

        // input admin terbanyak
        $referal      = $referalModel->getReferals();
        $ChartRefereal = $GrafikProvider->getGrafikReferal($referal);
        $cat_referal_label = $ChartRefereal['cat_referal'];
        $cat_referal_data = $ChartRefereal['cat_referal_data'];
        $color_referals = $ChartRefereal['color_referals'];

        $data = [
            'cat_referal_label' => $cat_referal_label,
            'cat_referal_data' => $cat_referal_data,
            'color_referals' => $color_referals,
        ];
        return response()->json($data);

    }

    public function getRegefalRegency($regency_id)
    {
        $referalModel = new Referal();
        $GrafikProvider = new GrafikProvider();

        // input admin terbanyak
        $inputer      = $referalModel->getReferalRegency($regency_id);
        $ChartInputer = $GrafikProvider->getGrafikInputer($inputer);
        $cat_inputer_label = $ChartInputer['cat_inputer_label'];
        $cat_inputer_data = $ChartInputer['cat_inputer_data'];
        $color_inputer = $ChartInputer['colors'];

        $data = [
            'cat_inputer_label' => $cat_inputer_label,
            'cat_inputer_data' => $cat_inputer_data,
            'color_inputer' => $color_inputer,
        ];
        return response()->json($data);

    }

    public function getRegefalDistrict($district_id)
    {
        $referalModel = new Referal();
        $GrafikProvider = new GrafikProvider();

        // input admin terbanyak
        $inputer      = $referalModel->getReferalDistrict($district_id);
        $ChartInputer = $GrafikProvider->getGrafikInputer($inputer);
        $cat_inputer_label = $ChartInputer['cat_inputer_label'];
        $cat_inputer_data = $ChartInputer['cat_inputer_data'];
        $color_inputer = $ChartInputer['colors'];

        $data = [
            'cat_inputer_label' => $cat_inputer_label,
            'cat_inputer_data' => $cat_inputer_data,
            'color_inputer' => $color_inputer,
        ];
        return response()->json($data);

    }

    public function getRegefalVillage($village_id)
    {
        $referalModel = new Referal();
        $GrafikProvider = new GrafikProvider();

        // input admin terbanyak
        $inputer      = $referalModel->getReferalVillage($village_id);
        $ChartInputer = $GrafikProvider->getGrafikInputer($inputer);
        $cat_inputer_label = $ChartInputer['cat_inputer_label'];
        $cat_inputer_data = $ChartInputer['cat_inputer_data'];
        $color_inputer = $ChartInputer['colors'];

        $data = [
            'cat_inputer_label' => $cat_inputer_label,
            'cat_inputer_data' => $cat_inputer_data,
            'color_inputer' => $color_inputer,
        ];
        return response()->json($data);
    }

    public function getRegefalProvince($province_id)
    {
        $referalModel = new Referal();
        $GrafikProvider = new GrafikProvider();

        // input admin terbanyak
        $inputer      = $referalModel->getReferalProvince($province_id);
        $ChartInputer = $GrafikProvider->getGrafikInputer($inputer);
        $cat_inputer_label = $ChartInputer['cat_inputer_label'];
        $cat_inputer_data = $ChartInputer['cat_inputer_data'];
        $color_inputer = $ChartInputer['colors'];

        $data = [
            'cat_inputer_label' => $cat_inputer_label,
            'cat_inputer_data' => $cat_inputer_data,
            'color_inputer' => $color_inputer,
        ];
        return response()->json($data);

    }

    public function getTotalMemberProvince($province_id)
    {
        $gF   = app('GlobalProvider'); // global function
        
        $userModel        = new User();
        $member           = $userModel->getMemberProvince($province_id);
        $total_member     = count($member); // total anggota terdaftar

        $provinceModel    = Province::select('target')->where('id', $province_id)->first();
        $target_member    = $provinceModel->target;
        $persentage_target_member = ($total_member / $target_member) * 100; // persentai terdata
        
        $villageModel   = new Village();
        $total_village  = $villageModel->getVillagesProvince($province_id)->total_village; // fungsi total desa di provinsi banten
        $village_filled = $villageModel->getVillageFillProvince($province_id); // fungsi total desa di provinsi banten
        $total_village_filled      = count($village_filled);
        $presentage_village_filled = ($total_village_filled / $total_village) * 100; // persentasi jumlah desa terisi

        $data = [
            'total_village' => $gF->decimalFormat($total_village),
            'total_village_filled' => $gF->decimalFormat($total_village_filled),
            'presentage_village_filled' => $gF->persen($presentage_village_filled),
            'total_member' => $gF->decimalFormat($total_member),
            'target_member' => $gF->decimalFormat($target_member),
            'persentage_target_member' => $gF->persen($persentage_target_member)
        ];
        return response()->json($data);
    }

    public function getMemberProvince($province_id)
    {
        $regencyModel     = new Regency();
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
        $data = [
            'cat_regency' => $cat_regency,
            'cat_regency_data' => $cat_regency_data,
            // 'colors' => $colors
        ];
        return response()->json($data);
    }

    public function getMemberVsTargetProvince($province_id)
    {
        $gF   = app('GlobalProvider'); // global function

        $userModel        = new User();

        $member_registered  = $userModel->getMemberRegistered($province_id);
        $chart_member_target = [];
        foreach ($member_registered as $val) {
            $chart_member_target['label'][] = $val->name;
            $chart_member_target['target'][] = $val->target_member;
            $chart_member_target['persentage'][] = $gF->persen(($val->realisasi_member/$val->target_member)*100);
        }
        $data = [
            'label' => $chart_member_target['label'],
            'persentage' => $chart_member_target['persentage'],
            'value_target' =>  $chart_member_target['target'] 
        ];
        return response()->json($data);
    }

    public function getGenderProvince($province_id)
    {
        $gF   = app('GlobalProvider'); // global function
        $GrafikProvider = new GrafikProvider();

        $userModel = new User();
        $gender     = $userModel->getGenderProvince($province_id);
        $CatGender  = $GrafikProvider->getGrafikGender($gender);
       
        $cat_gender = $CatGender['cat_gender'];
        $total_male_gender  = $CatGender['total_male_gender'];
        $total_female_gender = $CatGender['total_female_gender'];

        $data  = [
            'cat_gender' => $cat_gender,
            'total_male_gender' => $total_male_gender,
            'total_female_gender' => $total_female_gender
        ];
        return response()->json($data);
        
    }

    public function getJobsProvince($province_id)
    {
        $GrafikProvider = new GrafikProvider();
        $jobModel  = new Job();
        $jobs      = $jobModel->getJobProvince($province_id);
        $ChartJobs = $GrafikProvider->getGrafikJobs($jobs);
        $chart_jobs_label= $ChartJobs['chart_jobs_label'];
        $chart_jobs_data= $ChartJobs['chart_jobs_data'];
        $color_jobs    = $ChartJobs['color_jobs'];

        $data = [

            'chart_jobs_label' => $chart_jobs_label,
            'chart_jobs_data'  => $chart_jobs_data,
            'color_jobs' => $color_jobs,
        ];
        return response()->json($data);

    }

    public function getTotalRegioanNational()
    {
        $gF   = app('GlobalProvider'); // global function

        $province = Province::count();
        $regency  = Regency::count();
        $district = District::count();
        $village  = Village::count();

        $data = 'Indonesia Memiliki '. $gF->decimalFormat($province).' Provinsi, '.$gF->decimalFormat($regency).' Kabupaten/Kota, '.$gF->decimalFormat($district).' Kecamatan, dan '.$gF->decimalFormat($village).' Desa';
        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function getTotalRegioanProvince($province_id)
    {
        $gF   = app('GlobalProvider'); // global function

        $provinceModel = new Province();
        $regional       = $provinceModel->getTotalRegion($province_id);
       
        $data = 'Provinsi '.$regional->province.' Memiliki '.$gF->decimalFormat($regional->regency).' Kabupaten/Kota, '.$gF->decimalFormat($regional->district).' Kecamatan, dan '.$gF->decimalFormat($regional->village).' Desa';
        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function getTotalRegioanRegency($regency_id)
    {
        $gF   = app('GlobalProvider'); // global function

        $regencyModel = new Regency();
        $regional     = $regencyModel->getTotalRegion($regency_id);
       
        $data = $regional->regency.' Memiliki '.$gF->decimalFormat($regional->district).' Kecamatan, dan '.$gF->decimalFormat($regional->village).' Desa';
        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function getTotalRegioanDistrict($district_id)
    {
        $gF   = app('GlobalProvider'); // global function

        $districtModel = new District();
        $regional     = $districtModel->getTotalRegion($district_id);
       
        $data = 'KECAMATAN '. $regional->district.' Memiliki '.$gF->decimalFormat($regional->village).' Desa';
        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function getMemberProvinceAdminUser($province_id)
    {
        $regencyModel     = new Regency();
        $regency = $regencyModel->getGrafikTotalMemberRegencyProvince($province_id);
        $cat_regency      = [];
        $cat_regency_data = [];
        foreach ($regency as $val) {
            $cat_regency[] = $val->regency; 
            $cat_regency_data[] = [
                "y" => $val->total_member,
                "url" => route('adminuser-dashboard-regency', $val->regency_id)
            ];
        }
        $data = [
            'cat_regency' => $cat_regency,
            'cat_regency_data' => $cat_regency_data,
            // 'colors' => $colors
        ];
        return response()->json($data);
    }

    public function getMemberRegencyAdminUser($regency_id)
    {
        $districtModel    = new District();

        $districts = $districtModel->getGrafikTotalMemberDistrictRegency($regency_id);
        $cat_districts      = [];
        $cat_districts_data = [];
        foreach ($districts as $val) {
            $cat_districts[] = $val->district; 
            $cat_districts_data[] = [
                "y" => $val->total_member,
                "url" => route('adminuser-dashboard-district', $val->distric_id)
            ];
        }
        
        $data = [
            'cat_districts' => $cat_districts,
            'cat_districts_data' => $cat_districts_data,
            // 'colors' => $colors
        ];
        return response()->json($data);
    }

    public function getMemberDistrictAdminUser($district_id)
    {
        $districtModel    = new District();

        $districts = $districtModel->getGrafikTotalMemberDistrict($district_id);;
        $cat_districts      = [];
        $cat_districts_data = [];
        foreach ($districts as $val) {
            $cat_districts[] = $val->district; 
            $cat_districts_data[] = [
                "y" => $val->total_member,
                "url" => route('adminuser-dashboard-village', ['district_id' => $district_id,'village_id' => $val->village_id])
            ];
        }
        
        $data = [
            'cat_districts' => $cat_districts,
            'cat_districts_data' => $cat_districts_data,
            // 'colors' => $colors
        ];
        return response()->json($data);
    }

    public function referalByMountAdmin()
    {
      $mounth       = request()->mounth;
      $year         = request()->year;
      $referalModel = new Referal();
      $referal      = $referalModel->getReferealByMounthAdmin($mounth, $year);
      $userModel = new User();
      $referal_undirect = '';
      $data = [];
      $no = 1;
      foreach ($referal as $val) {
          $referal_undirect = $userModel->getReferalUnDirect($val->user_id);
          $totalReferal     = $val->total + $referal_undirect->total;
          $address          = $userModel->with(['village.district.regency'])->where('id', $val->user_id)->first();
          $data[] = [ 
              'no' => $no ++,
             'photo' => $val->photo,
             'name' => $val->name,
             'village' => $address->village->name,
             'district' => $address->village->district->name,
             'regency' => $address->village->district->regency->name,
             'referal' => $val->total,
             'whatsapp' => $val->whatsapp,
             'phone' => $val->phone_number,
             'referal_undirect' => $referal_undirect->total,
             'total_referal' => $totalReferal
          ];
      }
      return response()->json($data);
    }

    public function referalByMountAdminProvince()
    {
      $mounth       = request()->mounth;
      $year         = request()->year;
      $province_id  = request()->province_id;
      $referalModel = new Referal();
      $referal      = $referalModel->getReferealByMounthAdminProvince($mounth, $year, $province_id);
      $userModel = new User();
      $referal_undirect = '';
      $data = [];
      $no = 1;
      foreach ($referal as $val) {
          $referal_undirect = $userModel->getReferalUnDirect($val->user_id);
          $totalReferal     = $val->total + $referal_undirect->total;
          $address          = $userModel->with(['village.district.regency'])->where('id', $val->user_id)->first();
          $data[] = [ 
              'no' => $no ++,
             'photo' => $val->photo,
             'name' => $val->name,
             'village' => $address->village->name,
             'district' => $address->village->district->name,
             'regency' => $address->village->district->regency->name,
             'referal' => $val->total,
             'whatsapp' => $val->whatsapp,
             'phone' => $val->phone_number,
             'referal_undirect' => $referal_undirect->total,
             'total_referal' => $totalReferal
          ];
      }
      return response()->json($data);
    }

    public function referalByMountAdminProvinceDefault()
    {
      $province_id  = request()->province_id;
      $referalModel = new Referal();
      $referal      = $referalModel->getReferealByDefaultProvince($province_id);
      $userModel = new User();
      $referal_undirect = '';
      $data = [];
      $no = 1;
      foreach ($referal as $val) {
          $referal_undirect = $userModel->getReferalUnDirectProvince($val->user_id, $province_id);
          $totalReferal     = $val->total + $referal_undirect->total;
          $address          = $userModel->with(['village.district.regency'])->where('id', $val->user_id)->first();
          $data[] = [ 
              'no' => $no ++,
             'photo' => $val->photo,
             'name' => $val->name,
             'village' => $address->village->name,
             'district' => $address->village->district->name,
             'regency' => $address->village->district->regency->name,
             'referal' => $val->total,
             'whatsapp' => $val->whatsapp,
             'phone' => $val->phone_number,
             'referal_undirect' => $referal_undirect->total,
             'total_referal' => $totalReferal
          ];
      }
      return response()->json($data);
    }

    public function referalByMountAdminByDefault()
    {
      $referalModel = new Referal();
      $referal      = $referalModel->getReferealByDefault();
      $userModel = new User();
      $referal_undirect = '';
      $data = [];
      $no = 1;
      foreach ($referal as $val) {
          $referal_undirect = $userModel->getReferalUnDirect($val->user_id);
          $totalReferal     = $val->total + $referal_undirect->total;
          $address          = $userModel->with(['village.district.regency'])->where('id', $val->user_id)->first();
          $data[] = [ 
              'no' => $no ++,
             'photo' => $val->photo,
             'name' => $val->name,
             'village' => $address->village->name,
             'district' => $address->village->district->name,
             'regency' => $address->village->district->regency->name,
             'referal' => $val->total,
             'whatsapp' => $val->whatsapp,
             'phone' => $val->phone_number,
             'referal_undirect' => $referal_undirect->total,
             'total_referal' => $totalReferal
          ];
      }
      return response()->json($data);
    }

    public function referalByMountAdminRegency()
    {
      $mounth       = request()->mounth;
      $year         = request()->year;
      $regency_id   = request()->regency_id;
      $referalModel = new Referal();
      $referal      = $referalModel->getReferealByMounthAdminRegency($mounth, $year, $regency_id);
      $userModel = new User();
      $referal_undirect = '';
      $data = [];
      $no = 1;
      foreach ($referal as $val) {
          $referal_undirect = $userModel->getReferalUnDirect($val->user_id);
          $totalReferal     = $val->total + $referal_undirect->total;
          $address          = $userModel->with(['village.district.regency'])->where('id', $val->user_id)->first();
          $data[] = [ 
              'no' => $no ++,
             'photo' => $val->photo,
             'name' => $val->name,
             'village' => $address->village->name,
             'district' => $address->village->district->name,
             'regency' => $address->village->district->regency->name,
             'referal' => $val->total,
             'whatsapp' => $val->whatsapp,
             'phone' => $val->phone_number,
             'referal_undirect' => $referal_undirect->total,
             'total_referal' => $totalReferal
          ];
      }
      return response()->json($data);
    }
    public function referalByMountAdminRegencyDefault()
    {
      $regency_id   = request()->regency_id;
      $referalModel = new Referal();
      $referal      = $referalModel->getReferealByMounthAdminRegencyDefault($regency_id);
      $userModel = new User();
      $referal_undirect = '';
      $data = [];
      $no = 1;
      foreach ($referal as $val) {
          $referal_undirect = $userModel->getReferalUnDirectRegency($val->user_id, $regency_id);
          $totalReferal     = $val->total + $referal_undirect->total;
          $address          = $userModel->with(['village.district.regency'])->where('id', $val->user_id)->first();
          $data[] = [ 
              'no' => $no ++,
             'photo' => $val->photo,
             'name' => $val->name,
             'village' => $address->village->name,
             'district' => $address->village->district->name,
             'regency' => $address->village->district->regency->name,
             'referal' => $val->total,
             'whatsapp' => $val->whatsapp,
             'phone' => $val->phone_number,
             'referal_undirect' => $referal_undirect->total,
             'total_referal' => $totalReferal
          ];
      }
      return response()->json($data);
    }

    public function referalByMountAdminDistrict()
    {
      $mounth       = request()->mounth;
      $year         = request()->year;
      $district_id  = request()->district_id;
      $referalModel = new Referal();
      $referal      = $referalModel->getReferealByMounthAdminDistrict($mounth, $year, $district_id);
      $userModel = new User();
      $referal_undirect = '';
      $data = [];
      $no = 1;
      foreach ($referal as $val) {
          $referal_undirect = $userModel->getReferalUnDirect($val->user_id);
          $totalReferal     = $val->total + $referal_undirect->total;
          $address          = $userModel->with(['village.district.regency'])->where('id', $val->user_id)->first();
          
          $data[] = [ 
              'no' => $no ++,
             'photo' => $val->photo,
             'name' => $val->name,
             'village' => $address->village->name,
             'district' => $address->village->district->name,
             'regency' => $address->village->district->regency->name,
             'referal' => $val->total,
             'whatsapp' => $val->whatsapp,
             'phone' => $val->phone_number,
             'referal_undirect' => $referal_undirect->total,
             'total_referal' => $totalReferal
          ];
      }
      return response()->json($data);
    }

    public function referalByMountAdminDistrictDefault()
    {
     
      $district_id  = request()->district_id;
      $referalModel = new Referal();
      $referal      = $referalModel->getReferealByMounthAdminDistrictDefault($district_id);
      $userModel = new User();
      $referal_undirect = '';
      $data = [];
      $no = 1;
      foreach ($referal as $val) {
          $referal_undirect = $userModel->getReferalUnDirectDistrict($val->user_id, $district_id);
          $totalReferal     = $val->total + $referal_undirect->total;
          $address          = $userModel->with(['village.district.regency'])->where('id', $val->user_id)->first();
          $data[] = [ 
             'no' => $no ++,
             'photo' => $val->photo,
             'name' => $val->name,
             'village' => $address->village->name,
             'district' => $address->village->district->name,
             'regency' => $address->village->district->regency->name,
             'referal' => $val->total,
             'whatsapp' => $val->whatsapp,
             'phone' => $val->phone_number,
             'referal_undirect' => $referal_undirect->total,
             'total_referal' => $totalReferal
          ];
      }
      return response()->json($data);
    }

    public function referalByMountAdminVillage()
    {
      $mounth       = request()->mounth;
      $year         = request()->year;
      $village_id  = request()->village_id;
      $referalModel = new Referal();
      $referal      = $referalModel->getReferealByMounthAdminVillage($mounth, $year, $village_id);
      $userModel = new User();
      $referal_undirect = '';
      $data = [];
      $no = 1;
      foreach ($referal as $val) {
          $referal_undirect = $userModel->getReferalUnDirect($val->user_id);
          $totalReferal     = $val->total + $referal_undirect->total;
           $address          = $userModel->with(['village.district.regency'])->where('id', $val->user_id)->first();
          $data[] = [ 
              'no' => $no ++,
             'photo' => $val->photo,
             'name' => $val->name,
              'village' => $address->village->name,
             'district' => $address->village->district->name,
             'regency' => $address->village->district->regency->name,
             'referal' => $val->total,
             'whatsapp' => $val->whatsapp,
             'phone' => $val->phone_number,
             'referal_undirect' => $referal_undirect->total,
             'total_referal' => $totalReferal
          ];
      }
      return response()->json($data);
    }

    public function referalByMountAdminVillageDefault()
    {
      
      $village_id  = request()->village_id;
      $referalModel = new Referal();
      $referal      = $referalModel->getReferealByMounthAdminVillageDefault($village_id);
      $userModel = new User();
      $referal_undirect = '';
      $data = [];
      $no = 1;
      foreach ($referal as $val) {
          $referal_undirect = $userModel->getReferalUnDirectVillage($val->user_id, $village_id);
          $totalReferal     = $val->total + $referal_undirect->total;
          $address          = $userModel->with(['village.district.regency'])->where('id', $val->user_id)->first();
          $data[] = [ 
              'no' => $no ++,
             'photo' => $val->photo,
             'name' => $val->name,
              'village' => $address->village->name,
             'district' => $address->village->district->name,
             'regency' => $address->village->district->regency->name,
             'referal' => $val->total,
             'whatsapp' => $val->whatsapp,
             'phone' => $val->phone_number,
             'referal_undirect' => $referal_undirect->total,
             'total_referal' => $totalReferal
          ];
      }
      return response()->json($data);
    }

}
