<?php

namespace App\Http\Controllers\API;

use App\Job;
use App\User;
use Carbon\Carbon;
use App\Models\Regency;
use App\Models\Village;
use App\Providers\GetRegencyId;
use App\Providers\GrafikProvider;
use App\Http\Controllers\Controller;

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

    public function getMemberProvince()
    {
         $regencyModel     = new Regency();
         $province = $regencyModel->getTotalMember();
         $province_label = collect($province);
         $colors = $province_label->map(function($item){
            return $rand_color = '#' . substr(md5(mt_rand()),0,6);
        });
        $data = [
            'province' => $province,
            'colors' => $colors
        ];
        return response()->json($data);
    }

    public function getTotalMemberProvince()
    {
        $gF   = app('GlobalProvider'); // global function
        
        $userModel        = new User();
        $regencyModel     = new Regency();
        $total_member     = $gF->decimalFormat($userModel->where('village_id', '!=', NULL)->count());
        $targetMember     = $regencyModel->getRegency()->total_district * 5000;
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
            'total_member' => $total_member,
            'target_member' => $gF->decimalFormat($target_member),
            'persentage_target_member' => $persentage_target_member
        ];
        return response()->json($data);

    }

    public function getMemberVsTarget()
    {
        $userModel        = new User();
        $member_registered  = $userModel->getMemberRegisteredAll();
        return response()->json($member_registered);
    }

    public function getGenderProvince()
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

    public function getJobsProvince()
    {
        $GrafikProvider = new GrafikProvider();
        $jobModel  = new Job();
        $most_jobs = $jobModel->getMostJobs();
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

    public function getAgeGroup()
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

    public function genAge()
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

}
