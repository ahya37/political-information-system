<?php

namespace App\Http\Controllers\API;

use App\Dpt;
use App\Job;
use App\Tps;
use App\User;
use App\Referal;
use Carbon\Carbon;
use App\OrgDiagram;
use App\TargetNumber;
use App\Models\Regency;
use App\Models\Village;
use App\Models\District;
use App\Models\Province;
use App\RightChooseRegency;
use App\RightChosseVillage;
use App\RightChooseDistrict;
use App\RightChooseProvince;
use Illuminate\Http\Request;
use App\Providers\GlobalProvider;
use App\Providers\GrafikProvider;
use Illuminate\Support\Facades\DB;
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

    public function memberReportPerMountAdminMember($daterange, $user_id)
    {
        if ($daterange != '') {
            $date  = explode('+', $daterange);
            $start = Carbon::parse($date[0])->format('Y-m-d');
            $end   = Carbon::parse($date[1])->format('Y-m-d'); 
        }
        // dd($start);

        $userModel = new User();
        $member    = $userModel->getMemberRegisteredByDayAdminMember($user_id, $start, $end);
       
        $data = [];
        foreach ($member as $value) {
            $data[] = [
                'day' => date('d-m-Y', strtotime($value->day)),
                'count' => $value->total
            ];
        }
        return $data;
    }

    public function memberReportPerMountAdminMemberCaleg($daterange, $user_id)
    {
        if ($daterange != '') {
            $date  = explode('+', $daterange);
            $start = Carbon::parse($date[0])->format('Y-m-d');
            $end   = Carbon::parse($date[1])->format('Y-m-d'); 
        }

        $userModel = new User();
        $member    = $userModel->getMemberRegisteredByDayAdminMemberCaleg($user_id, $start, $end);
       
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

    public function memberReportPerMountDistrictCaleg($daterange, $districtID, $userId)
    {
        if ($daterange != '') {
            $date  = explode('+', $daterange);
            $start = Carbon::parse($date[0])->format('Y-m-d');
            $end   = Carbon::parse($date[1])->format('Y-m-d'); 
        }
        // dd($start);

        $district_id = $districtID;
        $userModel = new User();
        $member    = $userModel->getMemberRegisteredByDayDistrictCaleg($district_id, $start, $end,$userId);
       
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

    public function memberReportPerMountVillageCaleg($daterange, $villageID, $userId)
    {
        if ($daterange != '') {
            $date  = explode('+', $daterange);
            $start = Carbon::parse($date[0])->format('Y-m-d');
            $end   = Carbon::parse($date[1])->format('Y-m-d'); 
        }
        // dd($start);

        $village_id = $villageID;
        $userModel = new User();
        $member    = $userModel->getMemberRegisteredByDayVillageCaleg($village_id, $start, $end,$userId);
       
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
        // $targetMember     = $gF->calculateTargetNational();
        $total_member     = $userModel->where('village_id', '!=', NULL)->count();
        // $target_member    = (string) $targetMember;
        // $persentage_target_member = $gF->persen(($total_member / $target_member) * 100); // persentai terdata

        $villageModel   = new Village();
        $total_village  = $villageModel->getVillages()->total_village; // fungsi total desa di provinsi banten
        $village_filled = $villageModel->getVillageFill(); // fungsi total desa di provinsi banten
        $total_village_filled      = count($village_filled);
        $presentage_village_filled = $gF->persen(($total_village_filled / $total_village) * 100); // persentasi jumlah desa terisi

        #total dpt nasioanal, sum count_vooter level provinsi 
		// $RightChooseProvinceModel = new RightChooseProvince(); 
        $dptModel                 = new Dpt();
        $rightChooseProvince      = $dptModel->getDptLevelNational()->total_dpt;

        $tpsNational      = Tps::select('id')->count();
        $orgDiagramModel = new OrgDiagram();
        $daftarTeam       = $orgDiagramModel->getDataDaftarTimByNationalForDashboard();
        $resultsDaftarTeam = [];
        foreach ($daftarTeam as $val) {
            $target = $orgDiagramModel->getDataDaftarTimByDapilForRegency($val->id);

            $resultsDaftarTeam[] = [
                'target' => $target,
            ];
        }
        // jumlakan hasil all target kecamatan by dapil
        $target_member = collect($resultsDaftarTeam)->sum(function($q){
            return $q['target'];
        });
        $persentage_target_member = $gF->persen(($total_member / $target_member) * 100); // persentai terdata

        $data = [
            'total_village' => $gF->decimalFormat($total_village),
            'total_village_filled' => $gF->decimalFormat($total_village_filled),
            'presentage_village_filled' => $presentage_village_filled,
            'total_member' => $gF->decimalFormat($total_member),
            'target_member' => $gF->decimalFormat($target_member),
            'persentage_target_member' => $persentage_target_member,
            'rightChooseProvince' => $gF->decimalFormat($rightChooseProvince) ?? 0,
            'tpsNational' => $gF->decimalFormat($tpsNational)
        ];
        return response()->json($data);
    }

    public function getTotalMemberRegency($regency_id)
    {
       
        $gF   = app('GlobalProvider'); // global function

        $userModel        = new User();
        $member           = $userModel->getMemberRegency($regency_id);   
        $total_member     = count($member); // total anggota terdaftar

        // $regencyModel     = Regency::select('target')->where('id', $regency_id)->first();
        // $targetMember    = $regencyModel->target; // target anggota tercapai, per kecamatan 1000 target

        // $target_member    = (string) $targetMember;
        // $persentage_target_member = ($total_member / $target_member) * 100;

        $villageModel   = new Village();
        $villages       = $villageModel->getVillagesRegency($regency_id); // fungsi total desa di kab
        $total_village  = count($villages);
        $village_filled = $villageModel->getVillageFilledRegency($regency_id); //fungsi total desa yang terisi 
        $total_village_filled      = count($village_filled); // total desa yang terisi
        $presentage_village_filled = ($total_village_filled / $total_village) * 100; // persentasi jumlah desa terisi
        
		// $RightChooseDistrictModel  = new RightChooseDistrict();
		// $rightChooseRegency        = $RightChooseDistrictModel->getTotalDptRegency($regency_id)->total_dpt;

        $dptModel = new Dpt();
        $rightChooseRegency = $dptModel->getDptLevelRegency()->total_dpt;

        $tpsRegency       = Tps::select('id')->where('regency_id', $regency_id)->count();

        $orgDiagramModel = new OrgDiagram();
        $daftarTeam       = $orgDiagramModel->getDataDaftarTimByRegencyForDashboard($regency_id);
        $resultsDaftarTeam = [];
        foreach ($daftarTeam as $val) {
            $target = $orgDiagramModel->getDataDaftarTimByDapilForRegency($val->id);

            $resultsDaftarTeam[] = [
                'target' => $target,
            ];
        }
        // jumlakan hasil all target kecamatan by dapil
        $target_member = collect($resultsDaftarTeam)->sum(function($q){
            return $q['target'];
        });
        $persentage_target_member = ($total_member / $target_member) * 100;

        $data = [
            'total_village' => $gF->decimalFormat($total_village),
            'total_village_filled' => $gF->decimalFormat($total_village_filled),
            'presentage_village_filled' => $gF->persenDpt($presentage_village_filled),
            'total_member' => $gF->decimalFormat($total_member),
            // 'target_member' => $gF->decimalFormat($target_member),
            'persentage_target_member' => $gF->persenDpt($persentage_target_member),
            'rightChooseRegency' => $gF->decimalFormat($rightChooseRegency) ?? 0,
            'tpsRegency' => $gF->decimalFormat($tpsRegency),
            'target_member' => $gF->decimalFormat($target_member),
        ];
        return response()->json($data);

    }

    public function getTotalMemberDistrict($district_id)
    {
        $gF   = new GlobalProvider(); // global function
        
        $userModel        = new User();
        $member     = $userModel->getMemberDistrict($district_id);
        $total_member = count($member);
        
        // perentasi anggot  di kecamatan
        $districtModel    = new District();
        // $target_member    = $districtModel->target; // target anggota tercapai, per kecamatan 1000 target

        $villageModel   = new Village();
        $villages       = $villageModel->getVillagesDistrct($district_id); // fungsi total desa di kab
        $total_village  = count($villages);

        $RightChosseVillageModel   = new RightChosseVillage();
		$rightChooseDistrict       = $RightChosseVillageModel->getTotalDptDistrict($district_id)->total_dpt;


        $target_from_dpt  = $districtModel->getTargetPersentageDistrict($district_id)->target_persentage;
        $target_member  = ($rightChooseDistrict * $target_from_dpt)/100;
        
        $persentage_target_member = $gF->persenDpt(($total_member / $target_member) * 100); // persentasi terdata

        $village_filled = $villageModel->getVillageFilledDistrict($district_id); //fungsi total desa yang terisi 
        $total_village_filled      = count($village_filled); // total desa yang terisi
        $presentage_village_filled = $gF->persenDpt(($total_village_filled / $total_village) * 100); // persentasi jumlah desa terisi

        $tpsDistrict       = Tps::select('id')->where('district_id', $district_id)->count();
		

        $data = [
            'total_village' => $gF->decimalFormat($total_village),
            'total_village_filled' => $gF->decimalFormat($total_village_filled),
            'presentage_village_filled' =>  $presentage_village_filled,
            'total_member' => $gF->decimalFormat($total_member),
            'target_from_dpt' => $gF->persenDpt($target_from_dpt),
            'target_member' => $gF->decimalFormat($target_member),
            'persentage_target_member' => $persentage_target_member,
            'rightChooseDistrict' => $gF->decimalFormat($rightChooseDistrict) ?? 0,
            'tpsDistrict' => $gF->decimalFormat($tpsDistrict)
        ];
        return response()->json($data);

    }

    public function getTotalMemberDistrictCaleg($district_id, $userId)
    {
        $gF   = app('GlobalProvider'); // global function
        
        $userModel        = new User();
        $member     = $userModel->getMemberDistrictCaleg($district_id, $userId);
        $total_member = count($member);
        
        // perentasi anggot  di kecamatan
        // $districtModel    = District::select('target')->where('id', $district_id)->first();
        // $target_member    = $districtModel->target; // target anggota tercapai, per kecamatan 1000 target
        $target_member       =   DB::table('districts_caleg_target')->select('target')
                                ->where('district_id', $district_id)
                                ->where('caleg_user_id', $userId)
                                ->first();
        $target_member       = $target_member->target;
        
        $persentage_target_member = $gF->persen(($total_member / $target_member) * 100); // persentai terdata

        $villageModel   = new Village();
        $villages       = $villageModel->getVillagesDistrctCaleg($district_id,$userId); // fungsi total desa di kab
        $total_village  = count($villages);

        $village_filled = $villageModel->getVillageFilledDistrictCaleg($district_id, $userId); //fungsi total desa yang terisi 
        $total_village_filled      = count($village_filled); // total desa yang terisi
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

    public function getTotalMemberVillage($district_id, $village_id)
    {
        $gF   = new GlobalProvider(); // global function
        $villageModel   = new Village();

        $member     = $villageModel->getMemberVillage($village_id);
        $total_member = count($member);

        $RightChosseVillageModel = new RightChosseVillage();
        $rightChooseVillage = $RightChosseVillageModel->getTotalDptVillage($village_id)->total_dpt;

        $target_from_dpt  = $villageModel->getTargetPersentageVillage($village_id)->target_persentage;
        $target_member  = ($rightChooseVillage* $target_from_dpt)/100;

         // total desa yg berada di kec, yg sama
        // $targetMmemberModel = $villageModel->select('target')->where('id', $village_id)->first();
        // $total_target_per_district = $targetMmemberModel->target;
        // $target_member  = $gF->decimalFormat($total_target_per_district);
        $persentage_target_member = ($total_member/$target_member)*100;
        
        // Daftar pencapaian lokasi / daerah
        $achievments   = $villageModel->achievementVillageFirst($village_id);
       
        $tpsVillag          = Tps::select('id')->where('village_id', $village_id)->count();

        $data = [
            'achievments' => $gF->decimalFormat($achievments->todays_achievement ?? ''),
            'total_member' => $gF->decimalFormat($total_member),
            'target_from_dpt' => $gF->persenDpt($target_from_dpt),
            'target_member' => $gF->decimalFormat($target_member),
            'persentage_target_member' => $gF->persenDpt($persentage_target_member),
            'rightChooseVillage' => $gF->decimalFormat($rightChooseVillage) ?? 0,
            'tpsVillag' => $gF->decimalFormat($tpsVillag)
        ];
        return response()->json($data);

    }

    public function getTotalMemberVillageCaleg($district_id, $village_id, $userId)
    {
        $gF             = app('GlobalProvider'); // global function
        $villageModel   = new Village();

        $member     = $villageModel->getMemberVillageCaleg($village_id, $userId);
        $total_member = count($member);

         // total desa yg berada di kec, yg sama
        // $targetMmemberModel = $villageModel->select('target')->where('id', $village_id)->first();

        $targetMmemberModel = DB::table('villages_caleg_target')
                              ->select('target')
                              ->where('village_id', $village_id)
                              ->where('caleg_user_id', $userId)
                              ->first();

        $total_target_per_district = $targetMmemberModel->target;
        $target_member  = $gF->decimalFormat($total_target_per_district);
        $persentage_target_member = ($total_member/$total_target_per_district)*100;
        
        // Daftar pencapaian lokasi / daerah
        $achievments   = $villageModel->achievementVillageFirstCaleg($village_id, $userId);

        $data = [
            'achievments' => $gF->decimalFormat($achievments->todays_achievement ?? ''),
            'total_member' => $gF->decimalFormat($total_member),
            'target_member' => $target_member,
            'persentage_target_member' => $gF->persen($persentage_target_member)
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
            // $chart_member_target['persentage'][] = $gF->persen(($val->realisasi_member/$val->target_member)*100);
            $chart_member_target['persentage'][] = $val->realisasi_member;
        }
        $data = [
            'label' => $chart_member_target['label'],
            'persentage' =>  $chart_member_target['persentage'],
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
            // $chart_member_target['persentage'][] = $gF->persen(($val->realisasi_member/$val->target_member)*100);
            $chart_member_target['persentage'][] = $val->realisasi_member;
        }
        $data = [
            'label' => $chart_member_target['label'],
            'persentage' => $chart_member_target['persentage'],
            'value_target' =>  $chart_member_target['target'] 
        ];
        return response()->json($data);
    }

    public function getMemberVsTargetAdminMember($user_id)
    {
        $gF   = app('GlobalProvider'); // global function

        $userModel        = new User();
        $member_registered  = $userModel->getMemberRegisteredAdminMember($user_id);
        $chart_member_target = [];
        foreach ($member_registered as $val) {
            $chart_member_target['label'][] = $val->name;
            $chart_member_target['target'][] = $val->target_member;
            // $chart_member_target['persentage'][] = $gF->persen(($val->realisasi_member/$val->target_member)*100);
            $chart_member_target['persentage'][] = $val->realisasi_member;
        }
        $data = [
            'label' => $chart_member_target['label'],
            'persentage' => $chart_member_target['persentage'],
            'value_target' =>  $chart_member_target['target'] 
        ];
        return response()->json($data);
    }

    public function getMemberVsTargetAdminMemberCaleg($user_id)
    {
        // $gF   = app('GlobalProvider'); // global function

        $userModel        = new User();
        $member_registered  = $userModel->getMemberRegisteredAdminMemberCaleg($user_id);
        $chart_member_target = [];
        foreach ($member_registered as $val) {
            $chart_member_target['label'][] = $val->name;
            $chart_member_target['target'][] = $val->target_member;
            // $chart_member_target['persentage'][] = $gF->persen(($val->realisasi_member/$val->target_member)*100);
            $chart_member_target['persentage'][] = $val->realisasi_member;
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
        // $gF   = app('GlobalProvider'); // global function

        $userModel        = new User();
        $member_registered  = $userModel->getMemberRegisteredDistrct($district_id);
        $chart_member_target = [];
        foreach ($member_registered as $val) {
            $chart_member_target['label'][] = $val->name;
            $chart_member_target['target'][] = $val->target_member;
            $chart_member_target['persentage'][] = $val->realisasi_member;
        }
        $data = [
            'label' => $chart_member_target['label'],
            'persentage' => $chart_member_target['persentage'],
            'value_target' =>  $chart_member_target['target'] 
        ];
        return response()->json($data);
    }

    public function getMemberVsTargetDistrictCaleg($district_id, $userId)
    {

        $userModel        = new User();
        $member_registered  = $userModel->getMemberRegisteredDistrctCaleg($district_id, $userId);
        $chart_member_target = [];
        foreach ($member_registered as $val) {
            $chart_member_target['label'][] = $val->name;
            $chart_member_target['target'][] = $val->target_member;
            $chart_member_target['persentage'][] = $val->realisasi_member;
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
            'total_male_gender' => $gF->decimalFormat($total_male_gender),
            'total_female_gender' => $gF->decimalFormat($total_female_gender)
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
            'total_male_gender' => $gF->decimalFormat($total_male_gender),
            'total_female_gender' => $gF->decimalFormat($total_female_gender)
        ];
        return response()->json($data);
        
    }

    public function getGenderAdminMember($user_id)
    {
        $gF   = app('GlobalProvider'); // global function
        $GrafikProvider = new GrafikProvider();

        $userModel = new User();
        $gender     = $userModel->getGenderAdminMember($user_id);
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

    public function getGenderAdminMemberCaleg($user_id)
    {
        $gF   = app('GlobalProvider'); // global function
        $GrafikProvider = new GrafikProvider();

        $userModel = new User();
        $gender     = $userModel->getGenderAdminMemberCaleg($user_id);
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
            'total_male_gender' => $gF->decimalFormat($total_male_gender),
            'total_female_gender' => $gF->decimalFormat($total_female_gender)
        ];
        return response()->json($data);
        
    }

    public function getGenderDistrictCaleg($district_id, $userId)
    {
        $gF   = app('GlobalProvider'); // global function
        $GrafikProvider = new GrafikProvider();

        $userModel = new User();
        $gender     = $userModel->getGenderDistrictCaleg($district_id, $userId);
        $CatGender  = $GrafikProvider->getGrafikGender($gender);
       
        $cat_gender = $CatGender['cat_gender'];
        $total_male_gender  = $CatGender['total_male_gender'];
        $total_female_gender = $CatGender['total_female_gender'];

        $data  = [
            'cat_gender' => $cat_gender,
            'total_male_gender' => $gF->decimalFormat($total_male_gender),
            'total_female_gender' => $gF->decimalFormat($total_female_gender)
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
            'total_male_gender' => $gF->decimalFormat($total_male_gender),
            'total_female_gender' => $gF->decimalFormat($total_female_gender)
        ];
        return response()->json($data);
        
    }

    public function getGenderVillageCaleg($village_id, $userId)
    {
        $gF   = app('GlobalProvider'); // global function
        $GrafikProvider = new GrafikProvider();

        $userModel = new User();
        $gender     = $userModel->getDataGenderVillageCaleg($village_id, $userId);
        $CatGender  = $GrafikProvider->getGrafikGender($gender);
       
        $cat_gender = $CatGender['cat_gender'];
        $total_male_gender  = $CatGender['total_male_gender'];
        $total_female_gender = $CatGender['total_female_gender'];

        $data  = [
            'cat_gender' => $cat_gender,
            'total_male_gender' => $gF->decimalFormat($total_male_gender),
            'total_female_gender' => $gF->decimalFormat($total_female_gender)
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

    public function getJobsAdminMemberCaleg($user_id)
    {
        $GrafikProvider = new GrafikProvider();
        $jobModel  = new Job();
        // $most_jobs = $jobModel->getMostJobs();
        $jobs      = $jobModel->getJobAdminMemberCaleg($user_id);
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
    public function getJobsDistrictCaleg($district_id, $userId)
    {
        $GrafikProvider = new GrafikProvider();
        $jobModel  = new Job();
        // $most_jobs = $jobModel->getMostJobs();
        $jobs      = $jobModel->getJobDistrictCaleg($district_id, $userId);
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

    public function getJobsVillageCaleg($village_id, $userId)
    {
        $GrafikProvider = new GrafikProvider();
        $jobModel  = new Job();
        // $most_jobs = $jobModel->getMostJobs();
        $jobs      = $jobModel->getJobVillageCaleg($village_id, $userId);
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

    public function getAgeGroupAdminMember($user_id)
    {
        $GrafikProvider = new GrafikProvider();
        $userModel = new User();

        $range_age     = $userModel->rangeAgeAdminMember($user_id);
        $CatRange      = $GrafikProvider->getGrafikRangeAge($range_age);
        $cat_range_age = $CatRange['cat_range_age'];
        $cat_range_age_data = $CatRange['cat_range_age_data'];

        $data = [
            'cat_range_age' => $cat_range_age,
            'cat_range_age_data' => $cat_range_age_data
        ];

        return response()->json($data);

    }

    public function getAgeGroupAdminMemberCaleg($user_id)
    {
        $GrafikProvider = new GrafikProvider();
        $userModel = new User();

        $range_age     = $userModel->rangeAgeAdminMemberCaleg($user_id);
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

    public function getAgeGroupDistrictcaleg($district_id, $userId)
    {
        $GrafikProvider = new GrafikProvider();
        $userModel = new User();

        $range_age     = $userModel->rangeAgeDistrictCaleg($district_id, $userId);
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

    public function getAgeGroupVillageCaleg($village_id, $userId)
    {
        $GrafikProvider = new GrafikProvider();
        $userModel = new User();

        $range_age     = $userModel->rangeAgeVillageCaleg($village_id, $userId);
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

    public function genAgeAdminMember($user_id)
    {
        $GrafikProvider = new GrafikProvider();
        $userModel = new User();

        $gen_age     = $userModel->generationAgeAdminMember($user_id);
        $GenAge      = $GrafikProvider->getGrafikGenAge($gen_age);
        $cat_gen_age = $GenAge['cat_gen_age'];
        $cat_gen_age_data = $GenAge['cat_gen_age_data'];

        $data = [
            'cat_gen_age' => $cat_gen_age,
            'cat_gen_age_data' => $cat_gen_age_data
        ];
        return response()->json($data);

    }

    public function genAgeAdminMemberCaleg($user_id)
    {
        $GrafikProvider = new GrafikProvider();
        $userModel = new User();

        $gen_age     = $userModel->generationAgeAdminMemberCaleg($user_id);
        $GenAge      = $GrafikProvider->getGrafikGenAge($gen_age);
        $cat_gen_age = $GenAge['cat_gen_age'];
        $cat_gen_age_data = $GenAge['cat_gen_age_data'];

        $data = [
            'cat_gen_age' => $cat_gen_age,
            'cat_gen_age_data' => $cat_gen_age_data
        ];
        return response()->json($data);

    }

    public function genAgeAdminMemberMember($user_id)
    {
        $GrafikProvider = new GrafikProvider();
        $userModel = new User();

        $gen_age     = $userModel->generationAgeAdminMember($user_id);
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

    public function genAgeDistrtictCaleg($district_id, $userId)
    {
        $GrafikProvider = new GrafikProvider();
        $userModel = new User();

        $gen_age     = $userModel->generationAgeDistrictCaleg($district_id, $userId);
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

    public function genAgeVillageCaleg($village_id, $userId)
    {
        $GrafikProvider = new GrafikProvider();
        $userModel = new User();

        $gen_age     = $userModel->generationAgeVillageCaleg($village_id,$userId);
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

    public function getInputerAdminMember($user_id)
    {
        $referalModel = new Referal();
        $GrafikProvider = new GrafikProvider();

        // input admin terbanyak
        $inputer      = $referalModel->getInputerAdminMember($user_id);
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


    public function getInputerAdminMemberCaleg($user_id)
    {
        $referalModel = new Referal();
        $GrafikProvider = new GrafikProvider();

        // input admin terbanyak
        $inputer      = $referalModel->getDataInputerAdminMemberCaleg($user_id);
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

    public function getInputerVillageCaleg($village_id, $userId)
    {
        $referalModel = new Referal();
        $GrafikProvider = new GrafikProvider();

        // input admin terbanyak
        $inputer      = $referalModel->getInputerVillageCaleg($village_id, $userId);
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
    
    public function getRegefalAdminMember($user_id)
    {
        $referalModel = new Referal();
        $GrafikProvider = new GrafikProvider();

        // input admin terbanyak
        $inputer      = $referalModel->getReferalAdminMember($user_id);
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

    public function getRegefalAdminMemberCaleg($user_id)
    {
        $referalModel = new Referal();
        $GrafikProvider = new GrafikProvider();

        // input admin terbanyak
        $inputer      = $referalModel->getReferalAdminMemberCaleg($user_id);
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

    public function getRegefalVillageCaleg($village_id, $userId)
    {
        $referalModel = new Referal();
        $GrafikProvider = new GrafikProvider();

        // input admin terbanyak
        $inputer      = $referalModel->getReferalVillageCaleg($village_id, $userId);
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

        // $provinceModel    = Province::select('target')->where('id', $province_id)->first();
        // $target_member    = $provinceModel->target;
        // $persentage_target_member = ($total_member / $target_member) * 100; // persentai terdata
        
        $villageModel   = new Village();
        $total_village  = $villageModel->getVillagesProvince($province_id)->total_village; // fungsi total desa di provinsi banten
        $village_filled = $villageModel->getVillageFillProvince($province_id); // fungsi total desa di provinsi banten
        $total_village_filled      = count($village_filled);
        $presentage_village_filled = ($total_village_filled / $total_village) * 100; // persentasi jumlah desa terisi
		
		// $RightChooseRegencyModel   = new RightChooseRegency();
        // $rightChooseProvince       = $RightChooseRegencyModel->getTotalDptProvince($province_id)->total_dpt;
        $dptModel = new Dpt();
        $rightChooseProvince = $dptModel->getDptLevelProvince()->total_dpt;

        $tpsProvince      = Tps::select('id')->where('province_id', $province_id)->count();

        $orgDiagramModel = new OrgDiagram();
        $daftarTeam       = $orgDiagramModel->getDataDaftarTimByProvinceForDashboard($province_id);
        $resultsDaftarTeam = [];
        foreach ($daftarTeam as $val) {
            $target = $orgDiagramModel->getDataDaftarTimByDapilForRegency($val->id);

            $resultsDaftarTeam[] = [
                'target' => $target,
            ];
        }
        // jumlakan hasil all target kecamatan by dapil
        $target_member = collect($resultsDaftarTeam)->sum(function($q){
            return $q['target'];
        });
        $persentage_target_member = ($total_member / $target_member) * 100; // persentai terdata

        $data = [
            'total_village' => $gF->decimalFormat($total_village),
            'total_village_filled' => $gF->decimalFormat($total_village_filled),
            'presentage_village_filled' => $gF->persenDpt($presentage_village_filled),
            'total_member' => $gF->decimalFormat($total_member),
            'target_member' => $gF->decimalFormat($target_member),
            'persentage_target_member' => $gF->persenDpt($persentage_target_member),
            'rightChooseProvince' => $gF->decimalFormat($rightChooseProvince) ?? 0,
            'tpsProvince' => $gF->decimalFormat($tpsProvince),
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
            // $chart_member_target['persentage'][] = $gF->persen(($val->realisasi_member/$val->target_member)*100);
            $chart_member_target['persentage'][] = $val->realisasi_member;
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
            'total_male_gender' => $gF->decimalFormat($total_male_gender),
            'total_female_gender' => $gF->decimalFormat($total_female_gender)
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

    public function getMemberDistrictAdminUserCaleg($district_id, $userId)
    {
        $districtModel    = new District();

        $districts = $districtModel->getGrafikTotalMemberDistrictCaleg($district_id, $userId);
        $cat_districts      = [];
        $cat_districts_data = [];
        foreach ($districts as $val) {
            $cat_districts[] = $val->district; 
            $cat_districts_data[] = [
                "y" => $val->total_member,
                "url" => route('adminuser-dashboard-village-caleg', ['district_id' => $district_id,'village_id' => $val->village_id])
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
      $gF = new GlobalProvider();

      // total referal per bulan 
      $referalCalculateByMonth = collect($referal)->sum(function($q){
          return $q->total;
      });

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
             'referal' => $gF->decimalFormat($val->total),
             'whatsapp' => $val->whatsapp,
             'phone' => $val->phone_number,
             'referal_undirect' => $gF->decimalFormat($referal_undirect->total),
             'total_referal' => $gF->decimalFormat($totalReferal),
             
          ];
      }
      $result = [
          'referalCalculate' => $gF->decimalFormat($referalCalculateByMonth),
          'data' => $data
      ];
      return response()->json($result);
    }

    public function referalByMountAdminProvince()
    {
      $mounth       = request()->mounth;
      $year         = request()->year;
      $province_id  = request()->province_id;
      $referalModel = new Referal();
      $referal      = $referalModel->getReferealByMounthAdminProvince($mounth, $year, $province_id);
      $referalCalculate = collect($referal)->sum(function($q){
          return $q->total;
      });
       $gF = new GlobalProvider();

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
      $result = [
          'referal_acumulate' => $gF->decimalFormat($referalCalculate),
          'data' => $data
      ];

      return response()->json($result);
    }

    public function getTotalreferalByMonthProvince(Request $request)
    {
         $gF = new GlobalProvider();
        $data      = DB::table('users as a')
                        ->select('a.id as user_id','a.name','a.phone_number','a.whatsapp','a.photo',
                        DB::raw('COUNT(b.id) as total'))
                        ->join('users as b','b.user_id','a.id')
                        ->join('villages as c','b.village_id','c.id')
                        ->join('districts as d','c.district_id','d.id')
                        ->join('regencies as e','d.regency_id','e.id')
                        ->whereNotNull('b.village_id')
                        ->where('e.province_id', $request->province_id);

                        if($request->input('dateReferal') != '' AND $request->input('yearReferal') != ''){
                            $data->whereMonth('b.created_at', $request->dateReferal);
                            $data->whereYear('b.created_at', $request->yearReferal);
                        }
                        
                        $data = $data->groupBy('a.id','a.phone_number','a.whatsapp','a.photo','a.name');
                        
                        $data = $data->get();
                        
                        //  jumlah referal secara defautl / akumulasi
                        $referalCalculate = collect($data)->sum(function($q){
                            return $q->total;
                        });
                        return response()->json([
                            'referal_acumulate' => $gF->decimalFormat($referalCalculate),
                        ]);
    }


    public function referalByMountAdminProvinceDefault(Request $request)
    {
         $gF = new GlobalProvider();
         $orderBy = 'a.name';
          switch($request->input('order.0.column')){
             case '1':
                $orderBy = 'a.name';
                break;
            case '2':
                $orderBy = 'total';
                break;
        }

         $data      = DB::table('users as a')
                        ->select('a.id as user_id','a.name','a.phone_number','a.whatsapp','a.photo',
                        DB::raw('COUNT(b.id) as total'))
                        ->join('users as b','b.user_id','a.id')
                        ->join('villages as c','b.village_id','c.id')
                        ->join('districts as d','c.district_id','d.id')
                        ->join('regencies as e','d.regency_id','e.id')
                        ->whereNotNull('b.village_id')
                        ->where('e.province_id', $request->province_id);
                        // ->orderBy(\ DB::raw('COUNT(b.id)'),'DESC');

                        if($request->input('search.value')!=null){
                            $data = $data->where(function($q)use($request){
                                $q->whereRaw('LOWER(a.name) like ? ',['%'.strtolower($request->input('search.value')).'%']);
                            });
                        }
                        
                        if($request->input('dateReferal') != '' AND $request->input('yearReferal') != ''){
                            $data->whereMonth('b.created_at', $request->dateReferal);
                            $data->whereYear('b.created_at', $request->yearReferal);
                        }

                        $data = $data->groupBy('a.id','a.phone_number','a.whatsapp','a.photo','a.name');
                         
                        $recordsFiltered = $data->get()->count();
                        if($request->input('length')!=-1) $data = $data->skip($request->input('start'))->take($request->input('length'));
                        $data = $data->orderBy($orderBy,$request->input('order.0.dir'))->get();
                        $recordsTotal = $data->count();

                        $userModel = new User();
                        $result = [];
                        $no = 1;
                        foreach ($data as $val) {
                            $referal_undirect = $userModel->getReferalUnDirect($val->user_id);
                            $totalReferal     = $val->total + $referal_undirect->total;
                            $address          = $userModel->with(['village.district.regency'])->where('id', $val->user_id)->first();
                            $result[] = [ 
                                'photo' => $val->photo,
                                'name' => $val->name,
                                'address' => $address->village->name.',<br> '.$address->village->district->name.', <br>'.$address->village->district->regency->name,
                                'referal' => $val->total,
                                'referal_undirect' => $referal_undirect->total,
                                'whatsapp' => $val->whatsapp,
                                'phone' => $val->phone_number,
                                'total_referal' => $totalReferal,
                            ];
                        }

                        return response()->json([
                            'draw'=>$request->input('draw'),
                            'recordsTotal'=>$recordsTotal,
                            'recordsFiltered'=>$recordsFiltered,
                            'data'=> $result,
                        ]);

        //   $province_id  = request()->province_id;
        //   $referalModel = new Referal();
        //   $referal      = $referalModel->getReferealByDefaultProvince($province_id);
        //   $referalCalculate = collect($referal)->sum(function($q){
        //       return $q->total;
        //   });

        //   $gF = new GlobalProvider();

        //   $userModel = new User();
        //   $referal_undirect = '';
        //   $data = [];
        //   $no = 1;
        //   foreach ($referal as $val) {
        //       $referal_undirect = $userModel->getReferalUnDirectProvince($val->user_id, $province_id);
        //       $totalReferal     = $val->total + $referal_undirect->total;
        //       $address          = $userModel->with(['village.district.regency'])->where('id', $val->user_id)->first();
        //       $data[] = [ 
        //          'no' => $no ++,
        //          'photo' => $val->photo,
        //          'name' => $val->name,
        //          'village' => $address->village->name,
        //          'district' => $address->village->district->name,
        //          'regency' => $address->village->district->regency->name,
        //          'referal' => $val->total,
        //          'whatsapp' => $val->whatsapp,
        //          'phone' => $val->phone_number,
        //          'referal_undirect' => $referal_undirect->total,
        //          'total_referal' => $totalReferal
        //       ];
        //   }
        //   $result = [
        //       'referal_acumulate' => $gF->decimalFormat($referalCalculate),
        //       'data' => $data
        //   ];
        //   return response()->json($result);
    }

    public function getTotalreferalByMonth(Request $request)
    {
         $gF = new GlobalProvider();
        $data      = DB::table('users as a')
                        ->select('a.id as user_id','a.name','a.phone_number','a.whatsapp','a.photo',
                        DB::raw('COUNT(b.id) as total'))
                        ->join('users as b','b.user_id','a.id')
                        ->whereNotNull('b.village_id');

                        if($request->input('dateReferal') != '' AND $request->input('yearReferal') != ''){
                            $data->whereMonth('b.created_at', $request->dateReferal);
                            $data->whereYear('b.created_at', $request->yearReferal);
                        }
                        
                        $data = $data->groupBy('a.id','a.phone_number','a.whatsapp','a.photo','a.name');
                        
                        $data = $data->get();
                        
                        //  jumlah referal secara defautl / akumulasi
                        $referalCalculate = collect($data)->sum(function($q){
                            return $q->total;
                        });
                        return response()->json([
                            'referal_acumulate' => $gF->decimalFormat($referalCalculate),
                        ]);
    }


    public function referalByMountAdminByDefault(Request $request)
    {
        $orderBy = 'a.name';
          switch($request->input('order.0.column')){
             case '1':
                $orderBy = 'a.name';
                break;
             case '2':
                $orderBy = 'total';
                break;
        }
        $data      = DB::table('users as a')
                        ->select('a.id as user_id','a.name','a.phone_number','a.whatsapp','a.photo',
                        DB::raw('COUNT(b.id) as total'))
                        ->join('users as b','b.user_id','a.id')
                        ->whereNotNull('b.village_id');
                        // ->orderBy(\ DB::raw('COUNT(b.id)'),'DESC');

                        if($request->input('search.value')!=null){
                            $data = $data->where(function($q)use($request){
                                $q->whereRaw('LOWER(a.name) like ? ',['%'.strtolower($request->input('search.value')).'%']);
                            });
                        }
                        
                        if($request->input('dateReferal') != '' AND $request->input('yearReferal') != ''){
                            $data->whereMonth('b.created_at', $request->dateReferal);
                            $data->whereYear('b.created_at', $request->yearReferal);
                        }
                        
                        $data = $data->groupBy('a.id','a.phone_number','a.whatsapp','a.photo','a.name');
                        
                         $recordsFiltered = $data->get()->count();
                        if($request->input('length')!=-1) $data = $data->skip($request->input('start'))->take($request->input('length'));
                        $data = $data->orderBy($orderBy,$request->input('order.0.dir'))->get();
                        $recordsTotal = $data->count();

                        $userModel = new User();
                        $result = [];
                        $no = 1;
                        foreach ($data as $val) {
                            $referal_undirect = $userModel->getReferalUnDirect($val->user_id);
                            $totalReferal     = $val->total + $referal_undirect->total;
                            $address          = $userModel->with(['village.district.regency'])->where('id', $val->user_id)->first();
                            $result[] = [ 
                                'photo' => $val->photo,
                                'name' => $val->name,
                                'address' => $address->village->name.',<br> '.$address->village->district->name.', <br>'.$address->village->district->regency->name,
                                'referal' => $val->total,
                                'referal_undirect' => $referal_undirect->total,
                                'whatsapp' => $val->whatsapp,
                                'phone' => $val->phone_number,
                                'total_referal' =>$totalReferal,
                            ];
                        }
                        
                        return response()->json([
                            'draw'=>$request->input('draw'),
                            'recordsTotal'=>$recordsTotal,
                            'recordsFiltered'=>$recordsFiltered,
                            'data'=> $result,
                        ]);
    //   $gF   = new GlobalProvider();


    //   $userModel = new User();
    //   $referal_undirect = '';
    //   $data = [];
    //   $no = 1;
    //   foreach ($referal as $val) {
    //       $referal_undirect = $userModel->getReferalUnDirect($val->user_id);
    //       $totalReferal     = $val->total + $referal_undirect->total;
    //       $address          = $userModel->with(['village.district.regency'])->where('id', $val->user_id)->first();
    //       $data[] = [ 
    //           'no' => $no ++,
    //          'photo' => $val->photo,
    //          'name' => $val->name,
    //          'village' => $address->village->name,
    //          'district' => $address->village->district->name,
    //          'regency' => $address->village->district->regency->name,
    //          'referal' => $gF->decimalFormat($val->total),
    //          'whatsapp' => $val->whatsapp,
    //          'phone' => $val->phone_number,
    //          'referal_undirect' => $gF->decimalFormat($referal_undirect->total),
    //          'total_referal' => $gF->decimalFormat($totalReferal),
    //       ];
    //   }
    //   $result = [
    //       'referal_acumulate' => $gF->decimalFormat($referalCalculate),
    //       'data' => $data
    //   ];
    //   return response()->json($result);
    }

    public function referalByMountAdminRegency()
    {
      $mounth       = request()->mounth;
      $year         = request()->year;
      $regency_id   = request()->regency_id;
      $referalModel = new Referal();
      $referal      = $referalModel->getReferealByMounthAdminRegency($mounth, $year, $regency_id);
      $referalCalculate = collect($referal)->sum(function($q){
          return $q->total;
      });
      $gF = new GlobalProvider();

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
             'referal' => $gF->decimalFormat($val->total),
             'whatsapp' => $val->whatsapp,
             'phone' => $val->phone_number,
             'referal_undirect' => $gF->decimalFormat($referal_undirect->total),
             'total_referal' => $gF->decimalFormat($totalReferal)
          ];
      }

      $result = [
          'referal_acumulate' => $gF->decimalFormat($referalCalculate),
          'data' => $data
      ];
      return response()->json($result);
    }

    public function getTotalreferalByMonthRegency(Request $request)
    {
         $gF = new GlobalProvider();
        $data      = DB::table('users as a')
                        ->select('a.id as user_id','a.name','a.phone_number','a.whatsapp','a.photo',
                        DB::raw('COUNT(b.id) as total'))
                        ->join('users as b','b.user_id','a.id')
                        ->join('villages as c','b.village_id','c.id')
                        ->join('districts as d','c.district_id','d.id')
                        ->join('regencies as e','d.regency_id','e.id')
                        ->whereNotNull('b.village_id')
                        ->where('e.id', $request->regency_id);

                        if($request->input('dateReferal') != '' AND $request->input('yearReferal') != ''){
                            $data->whereMonth('b.created_at', $request->dateReferal);
                            $data->whereYear('b.created_at', $request->yearReferal);
                        }
                        
                        $data = $data->groupBy('a.id','a.phone_number','a.whatsapp','a.photo','a.name');
                        
                        $data = $data->get();
                        
                        //  jumlah referal secara defautl / akumulasi
                        $referalCalculate = collect($data)->sum(function($q){
                            return $q->total;
                        });
                        return response()->json([
                            'referal_acumulate' => $gF->decimalFormat($referalCalculate),
                        ]);
    }


    public function referalByMountAdminRegencyDefault(Request $request)
    {
        $gF = new GlobalProvider();
         $orderBy = 'a.name';
          switch($request->input('order.0.column')){
             case '1':
                $orderBy = 'a.name';
                break;
            case '2':
                $orderBy = 'total';
                break;
        }

         $data      = DB::table('users as a')
                        ->select('a.id as user_id','a.name','a.phone_number','a.whatsapp','a.photo',
                        DB::raw('COUNT(b.id) as total'))
                        ->join('users as b','b.user_id','a.id')
                        ->join('villages as c','b.village_id','c.id')
                        ->join('districts as d','c.district_id','d.id')
                        ->join('regencies as e','d.regency_id','e.id')
                        ->whereNotNull('b.village_id')
                        ->where('e.id', $request->regency_id);
                        // ->orderBy(\ DB::raw('COUNT(b.id)'),'DESC');

                        if($request->input('search.value')!=null){
                            $data = $data->where(function($q)use($request){
                                $q->whereRaw('LOWER(a.name) like ? ',['%'.strtolower($request->input('search.value')).'%']);
                            });
                        }
                        
                        if($request->input('dateReferal') != '' AND $request->input('yearReferal') != ''){
                            $data->whereMonth('b.created_at', $request->dateReferal);
                            $data->whereYear('b.created_at', $request->yearReferal);
                        }

                        $data = $data->groupBy('a.id','a.phone_number','a.whatsapp','a.photo','a.name');
                        
                        $recordsFiltered = $data->get()->count();
                        if($request->input('length')!=-1) $data = $data->skip($request->input('start'))->take($request->input('length'));
                        $data = $data->orderBy($orderBy,$request->input('order.0.dir'))->get();
                        $recordsTotal = $data->count();

                        $userModel = new User();
                        $result = [];
                        $no = 1;
                        foreach ($data as $val) {
                            $referal_undirect = $userModel->getReferalUnDirect($val->user_id);
                            $totalReferal     = $val->total + $referal_undirect->total;
                            $address          = $userModel->with(['village.district.regency'])->where('id', $val->user_id)->first();
                            $result[] = [ 
                                'no' => $no ++,
                                'photo' => $val->photo,
                                'name' => $val->name,
                                'address' => $address->village->name.',<br> '.$address->village->district->name.', <br>'.$address->village->district->regency->name,
                                'referal' => $val->total,
                                'referal_undirect' => $referal_undirect->total,
                                'whatsapp' => $val->whatsapp,
                                'phone' => $val->phone_number,
                                'total_referal' => $totalReferal,
                            ];
                        }

                        return response()->json([
                            'draw'=>$request->input('draw'),
                            'recordsTotal'=>$recordsTotal,
                            'recordsFiltered'=>$recordsFiltered,
                            'data'=> $result,
                        ]);

    //   $regency_id   = request()->regency_id;
    //   $referalModel = new Referal();
    //   $referal      = $referalModel->getReferealByMounthAdminRegencyDefault($regency_id);
    //   $referalCalculate = collect($referal)->sum(function($q){
    //       return $q->total;
    //   });
    //   $gF = new GlobalProvider();

    //   $userModel = new User();
    //   $referal_undirect = '';
    //   $data = [];
    //   $no = 1;
    //   foreach ($referal as $val) {
    //       $referal_undirect = $userModel->getReferalUnDirectRegency($val->user_id, $regency_id);
    //       $totalReferal     = $val->total + $referal_undirect->total;
    //       $address          = $userModel->with(['village.district.regency'])->where('id', $val->user_id)->first();
    //       $data[] = [ 
    //         'no' => $no ++,
    //          'photo' => $val->photo,
    //          'name' => $val->name,
    //          'village' => $address->village->name,
    //          'district' => $address->village->district->name,
    //          'regency' => $address->village->district->regency->name,
    //          'referal' => $gF->decimalFormat($val->total),
    //          'whatsapp' => $val->whatsapp,
    //          'phone' => $val->phone_number,
    //          'referal_undirect' => $gF->decimalFormat($referal_undirect->total),
    //          'total_referal' => $gF->decimalFormat($totalReferal)
    //       ];
    //   }
    //   $result = [
    //       'referal_acumulate' => $gF->decimalFormat($referalCalculate),
    //       'data' => $data
    //   ];
    //   return response()->json($result);
    }

    public function referalByMountAdminDistrict()
    {
      $mounth       = request()->mounth;
      $year         = request()->year;
      $district_id  = request()->district_id;
      $referalModel = new Referal();
      $referal      = $referalModel->getReferealByMounthAdminDistrict($mounth, $year, $district_id);
      $referalCalculate = collect($referal)->sum(function($q){
          return $q->total;
      });
      $gF = new GlobalProvider();

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
      $result = [
          'referal_acumulate' => $gF->decimalFormat($referalCalculate),
          'data' => $data
      ];

      return response()->json($result);
    }

    public function getTotalreferalByMonthDistrict(Request $request)
    {
         $gF = new GlobalProvider();
        $data      = DB::table('users as a')
                        ->select('a.id as user_id','a.name','a.phone_number','a.whatsapp','a.photo',
                        DB::raw('COUNT(b.id) as total'))
                        ->join('users as b','b.user_id','a.id')
                        ->join('villages as c','b.village_id','c.id')
                        ->whereNotNull('b.village_id')
                        ->where('c.district_id', $request->district_id);

                        if($request->input('dateReferal') != '' AND $request->input('yearReferal') != ''){
                            $data->whereMonth('b.created_at', $request->dateReferal);
                            $data->whereYear('b.created_at', $request->yearReferal);
                        }
                        
                        $data = $data->groupBy('a.id','a.phone_number','a.whatsapp','a.photo','a.name');
                        
                        $data = $data->get();
                        
                        //  jumlah referal secara defautl / akumulasi
                        $referalCalculate = collect($data)->sum(function($q){
                            return $q->total;
                        });
                        return response()->json([
                            'referal_acumulate' => $gF->decimalFormat($referalCalculate),
                        ]);
    }


    public function referalByMountAdminDistrictDefault(Request $request)
    {
          $gF = new GlobalProvider();
        $orderBy = 'a.name';
        switch($request->input('order.0.column')){
             case '1':
                $orderBy = 'a.name';
                break;
             case '2':
                $orderBy = 'total';
                break;
        }

          $data      = DB::table('users as a')
                        ->select('a.id as user_id','a.name','a.phone_number','a.whatsapp','a.photo',
                        DB::raw('COUNT(b.id) as total'))
                        ->join('users as b','b.user_id','a.id')
                        ->join('villages as c','b.village_id','c.id')
                        ->whereNotNull('b.village_id')
                        ->where('c.district_id', $request->district_id);
                        // ->orderBy(\ DB::raw('COUNT(b.id)'),'DESC');

                        if($request->input('search.value')!=null){
                            $data = $data->where(function($q)use($request){
                                $q->whereRaw('LOWER(a.name) like ? ',['%'.strtolower($request->input('search.value')).'%']);
                            });
                        }
                        
                        if($request->input('dateReferal') != '' AND $request->input('yearReferal') != ''){
                            $data->whereMonth('b.created_at', $request->dateReferal);
                            $data->whereYear('b.created_at', $request->yearReferal);
                        }
                        
                        $data = $data->groupBy('a.id','a.phone_number','a.whatsapp','a.photo','a.name');
                        
                         $recordsFiltered = $data->get()->count();
                        if($request->input('length')!=-1) $data = $data->skip($request->input('start'))->take($request->input('length'));
                        $data = $data->orderBy($orderBy,$request->input('order.0.dir'))->get();
                        $recordsTotal = $data->count();

                        $userModel = new User();
                        $result = [];
                        $no = 1;
                        foreach ($data as $val) {
                            $referal_undirect = $userModel->getReferalUnDirect($val->user_id);
                            $totalReferal     = $val->total + $referal_undirect->total;
                            $address          = $userModel->with(['village.district.regency'])->where('id', $val->user_id)->first();
                            $result[] = [ 
                                'photo' => $val->photo,
                                'name' => $val->name,
                                'address' => $address->village->name.',<br> '.$address->village->district->name.', <br>'.$address->village->district->regency->name,
                                'referal' => $val->total,
                                'referal_undirect' => $referal_undirect->total,
                                'whatsapp' => $val->whatsapp,
                                'phone' => $val->phone_number,
                                'total_referal' =>$totalReferal,
                            ];
                        }
                        
                        return response()->json([
                            'draw'=>$request->input('draw'),
                            'recordsTotal'=>$recordsTotal,
                            'recordsFiltered'=>$recordsFiltered,
                            'data'=> $result,
                        ]);
    //   $district_id  = request()->district_id;
    //   $referalModel = new Referal();
    //   $referal      = $referalModel->getReferealByMounthAdminDistrictDefault($district_id);
    //   $referalCalculate = collect($referal)->sum(function($q){
    //       return $q->total;
    //   });
    //   $gF = new GlobalProvider();

    //   $userModel = new User();
    //   $referal_undirect = '';
    //   $data = [];
    //   $no = 1;
    //   foreach ($referal as $val) {
    //       $referal_undirect = $userModel->getReferalUnDirectDistrict($val->user_id, $district_id);
    //       $totalReferal     = $val->total + $referal_undirect->total;
    //       $address          = $userModel->with(['village.district.regency'])->where('id', $val->user_id)->first();
    //       $data[] = [ 
    //          'no' => $no ++,
    //          'photo' => $val->photo,
    //          'name' => $val->name,
    //          'village' => $address->village->name,
    //          'district' => $address->village->district->name,
    //          'regency' => $address->village->district->regency->name,
    //          'referal' => $val->total,
    //          'whatsapp' => $val->whatsapp,
    //          'phone' => $val->phone_number,
    //          'referal_undirect' => $referal_undirect->total,
    //          'total_referal' => $totalReferal
    //       ];
    //   }
    //   $result = [
    //       'referal_acumulate' => $gF->decimalFormat($referalCalculate),
    //       'data' => $data
    //   ];
    //   return response()->json($result);
    }

    public function referalByMountAdminVillage()
    {
      $mounth       = request()->mounth;
      $year         = request()->year;
      $village_id  = request()->village_id;
      $referalModel = new Referal();
      $referal      = $referalModel->getReferealByMounthAdminVillage($mounth, $year, $village_id);
       $referalCalculate = collect($referal)->sum(function($q){
          return $q->total;
      });
      $gF = new GlobalProvider();

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
       $result = [
          'referal_acumulate' => $gF->decimalFormat($referalCalculate),
          'data' => $data
      ];
      
      return response()->json($result);
    }

    public function getTotalreferalByMonthVillage(Request $request)
    {
         $gF = new GlobalProvider();
        $data      = DB::table('users as a')
                        ->select('a.id as user_id','a.name','a.phone_number','a.whatsapp','a.photo',
                        DB::raw('COUNT(b.id) as total'))
                        ->join('users as b','b.user_id','a.id')
                        ->whereNotNull('b.village_id')
                        ->where('b.village_id', $request->village_id);

                        if($request->input('dateReferal') != '' AND $request->input('yearReferal') != ''){
                            $data->whereMonth('b.created_at', $request->dateReferal);
                            $data->whereYear('b.created_at', $request->yearReferal);
                        }
                        
                        $data = $data->groupBy('a.id','a.phone_number','a.whatsapp','a.photo','a.name');
                        
                        $data = $data->get();
                        
                        //  jumlah referal secara defautl / akumulasi
                        $referalCalculate = collect($data)->sum(function($q){
                            return $q->total;
                        });
                        return response()->json([
                            'referal_acumulate' => $gF->decimalFormat($referalCalculate),
                        ]);
    }

    public function referalByMountAdminVillageDefault(Request $request)
    {
        $gF = new GlobalProvider();
         $orderBy = 'a.name';
          switch($request->input('order.0.column')){
             case '1':
                $orderBy = 'a.name';
                break;
             case '2':
                $orderBy = 'total';
                break;
        }

         $data      = DB::table('users as a')
                        ->select('a.id as user_id','a.name','a.phone_number','a.whatsapp','a.photo',
                        DB::raw('COUNT(b.id) as total'))
                        ->join('users as b','b.user_id','a.id')
                        ->whereNotNull('b.village_id')
                        ->where('b.village_id', $request->village_id);
                        // ->orderBy(\ DB::raw('COUNT(b.id)'),'DESC');

                        if($request->input('search.value')!=null){
                            $data = $data->where(function($q)use($request){
                                $q->whereRaw('LOWER(a.name) like ? ',['%'.strtolower($request->input('search.value')).'%']);
                            });
                        }
                        
                        if($request->input('dateReferal') != '' AND $request->input('yearReferal') != ''){
                            $data->whereMonth('b.created_at', $request->dateReferal);
                            $data->whereYear('b.created_at', $request->yearReferal);
                        }

                        $data = $data->groupBy('a.id','a.phone_number','a.whatsapp','a.photo','a.name');
                         
                         $recordsFiltered = $data->get()->count();
                        if($request->input('length')!=-1) $data = $data->skip($request->input('start'))->take($request->input('length'));
                        $data = $data->orderBy($orderBy,$request->input('order.0.dir'))->get();
                        $recordsTotal = $data->count();

                        $userModel = new User();
                        $result = [];
                        $no = 1;
                        foreach ($data as $val) {
                            $referal_undirect = $userModel->getReferalUnDirect($val->user_id);
                            $totalReferal     = $val->total + $referal_undirect->total;
                            $address          = $userModel->with(['village.district.regency'])->where('id', $val->user_id)->first();
                            $result[] = [ 
                                'no' => $no ++,
                                'photo' => $val->photo,
                                'name' => $val->name,
                                'address' => $address->village->name.',<br> '.$address->village->district->name.', <br>'.$address->village->district->regency->name,
                                'referal' => $val->total,
                                'referal_undirect' => $referal_undirect->total,
                                'whatsapp' => $val->whatsapp,
                                'phone' => $val->phone_number,
                                'total_referal' => $totalReferal,
                            ];
                        }

                        return response()->json([
                            'draw'=>$request->input('draw'),
                            'recordsTotal'=>$recordsTotal,
                            'recordsFiltered'=>$recordsFiltered,
                            'data'=> $result,
                        ]);
      
    //   $village_id  = request()->village_id;
    //   $referalModel = new Referal();
    //   $referal      = $referalModel->getReferealByMounthAdminVillageDefault($village_id);
    //   $referalCalculate = collect($referal)->sum(function($q){
    //       return $q->total;
    //   });
    //   $gF = new GlobalProvider();

    //   $userModel = new User();
    //   $referal_undirect = '';
    //   $data = [];
    //   $no = 1;
    //   foreach ($referal as $val) {
    //       $referal_undirect = $userModel->getReferalUnDirectVillage($val->user_id, $village_id);
    //       $totalReferal     = $val->total + $referal_undirect->total;
    //       $address          = $userModel->with(['village.district.regency'])->where('id', $val->user_id)->first();
    //       $data[] = [ 
    //           'no' => $no ++,
    //          'photo' => $val->photo,
    //          'name' => $val->name,
    //           'village' => $address->village->name,
    //          'district' => $address->village->district->name,
    //          'regency' => $address->village->district->regency->name,
    //          'referal' => $val->total,
    //          'whatsapp' => $val->whatsapp,
    //          'phone' => $val->phone_number,
    //          'referal_undirect' => $referal_undirect->total,
    //          'total_referal' => $totalReferal
    //       ];
    //   }
    //    $result = [
    //       'referal_acumulate' => $gF->decimalFormat($referalCalculate),
    //       'data' => $data
    //   ];

    //   return response()->json($result);
    }

    public function getTotalMemberByAdminMember($user_id)
    {
        $gF   = app('GlobalProvider'); // global function

        $userModel        = new User();
        $member           = $userModel->getMemberByAdminMember($user_id);   
        $total_member     = count($member); // total anggota terdaftar
        $regencyModel     = new Regency();
        $getTargetMember     = $regencyModel->getAllTarget($user_id);

        $target_member    = $getTargetMember->target; 
        $persentage_target_member = ($total_member / $target_member) * 100;

        $villageModel   = new Village();
        $villages       = $villageModel->getTotalVillageAdminMember($user_id); // fungsi total desa di kab
        $total_village  = $villages->total_village;
        $village_filled = $villageModel->getVillageFilledAdminMember($user_id); //fungsi total desa yang terisi 
        $total_village_filled      = $village_filled->total_village; // total desa yang terisi
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

    public function getTotalMemberByAdminMemberCaleg($user_id)
    {
        $gF   = app('GlobalProvider'); // global function

        $userModel        = new User();
        $member           = $userModel->getMemberByAdminMemberCaleg($user_id);   
        $total_member     = count($member); // total anggota terdaftar
        $regencyModel     = new Regency();
        $getTargetMember  = $regencyModel->getAllTargetCaleg($user_id);

        $target_member    = $getTargetMember->target; 
        $persentage_target_member = ($total_member / $target_member) * 100;

        $villageModel   = new Village();
        $villages       = $villageModel->getTotalVillageAdminMemberCaleg($user_id); // fungsi total desa di kab
        $total_village  = $villages->total_village;
        $village_filled = $villageModel->getVillageFilledAdminMemberCaleg($user_id); //fungsi total desa yang terisi 
        $total_village_filled      = $village_filled->total_village; // total desa yang terisi
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

    public function getMemberAdminMember($user_id)
    {
        $districtModel    = new District();

        $districts = $districtModel->getGrafikTotalMemberAdminMember($user_id);
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

    public function getMemberAdminMemberCaleg($user_id)
    {
        $districtModel    = new District();

        $districts = $districtModel->getGrafikTotalMemberAdminMemberCaleg($user_id);
        $cat_districts      = [];
        $cat_districts_data = [];
        foreach ($districts as $val) {
            $cat_districts[] = $val->district; 
            $cat_districts_data[] = [
                "y" => $val->total_member,
                "url" => route('adminuser-dashboard-district-caleg', $val->district_id)
            ];
        }
        
        $data = [
            'cat_districts' => $cat_districts,
            'cat_districts_data' => $cat_districts_data,
            // 'colors' => $colors
        ];
        return response()->json($data);
    }

    public function getTotalInputByMonthProvince(Request $request)
    {
        $gF = new GlobalProvider();
        $data      = DB::table('users as a')
                        ->select('a.id as user_id','a.name','a.phone_number','a.whatsapp','a.photo',
                        DB::raw('COUNT(b.id) as total'))
                       ->join('users as b','a.id','b.cby')
                       ->join('villages as c','b.village_id','c.id')
                       ->join('districts as d','c.district_id','d.id')
                        ->join('regencies as e','d.regency_id','e.id')
                         ->where('e.province_id', $request->province_id)
                        ->whereNotNull('b.village_id');

                       if($request->input('dateInputer') != '' AND $request->input('yearInputer') != ''){
                            $data->whereMonth('b.created_at', $request->dateInputer);
                            $data->whereYear('b.created_at', $request->yearInputer);
                        }
                        
                        $data = $data->groupBy('a.id','a.phone_number','a.whatsapp','a.photo','a.name');
                        
                        $data = $data->get();
                        
                        //  jumlah referal secara defautl / akumulasi
                        $referalCalculate = collect($data)->sum(function($q){
                            return $q->total;
                        });
                        return response()->json([
                            'input_acumulate' => $gF->decimalFormat($referalCalculate),
                        ]);
    }

    public function inputByMountAdminProvinceDefault(Request $request)
    {
         $gF = new GlobalProvider();
        $orderBy   = 'a.name';
        switch($request->input('order.0.column')){
             case '1':
                $orderBy = 'a.name';
                break;
             case '2':
                $orderBy = 'total';
                break;
        }

        $data      = DB::table('users as a')
                        ->select('a.id as user_id','a.name','a.phone_number','a.whatsapp','a.photo')
                        ->join('users as b','a.id','b.cby')
                        ->join('villages as c','b.village_id','c.id')
                        ->join('districts as d','c.district_id','d.id')
                        ->join('regencies as e','d.regency_id','e.id')
                        ->selectRaw('count(b.id) as total')
                        ->where('e.province_id', $request->province_id)
                        ->whereNotNull('b.village_id');
                        // ->orderBy(\ DB::raw('COUNT(b.id)'),'DESC');


                         if($request->input('search.value')!=null){
                            $data = $data->where(function($q)use($request){
                                $q->whereRaw('LOWER(a.name) like ? ',['%'.strtolower($request->input('search.value')).'%'])                            ;
                            });
                        }
                        
                        if($request->input('dateInputer') != '' AND $request->input('yearInputer') != ''){
                            $data->whereMonth('b.created_at', $request->dateInputer);
                            $data->whereYear('b.created_at', $request->yearInputer);
                        }
                        
                        $data = $data->groupBy('a.id','a.phone_number','a.whatsapp','a.photo','a.name');
                         $recordsFiltered = $data->get()->count();
                        
                        if($request->input('length')!=-1) $data = $data->skip($request->input('start'))->take($request->input('length'));
                        $data = $data->orderBy($orderBy,$request->input('order.0.dir'))->get();
                        $recordsTotal = $data->count();

                        $userModel = new User();
                        $result = [];
                        $no = 1;
                        foreach ($data as $val) {
                            $address          = $userModel->with(['village.district.regency'])->where('id', $val->user_id)->first();
                            $result[] = [ 
                                'photo' => $val->photo,
                                'name' => $val->name,
                                'address' => $address->village->name.',<br> '.$address->village->district->name.', <br>'.$address->village->district->regency->name,
                                'input' => $val->total,
                                'whatsapp' => $val->whatsapp,
                                'phone' => $val->phone_number,
                            ];
                        }

                                            
                        return response()->json([
                            'draw'=>$request->input('draw'),
                            'recordsTotal'=>$recordsTotal,
                            'recordsFiltered'=>$recordsFiltered,
                            'data'=> $result,
                        ]);

    //   $province_id  = request()->province_id;
    //   $referalModel = new Referal();
    //   $input      = $referalModel->getInputByDefaultProvince($province_id);
    //   $referalCalculate = collect($input)->sum(function($q){
    //       return $q->total;
    //   });

    //   $gF = new GlobalProvider();

    //   $userModel = new User();
    //   $data = [];
    //   $no = 1;
    //   foreach ($input as $val) {
    //       $address          = $userModel->with(['village.district.regency'])->where('id', $val->user_id)->first();
    //       $data[] = [ 
    //           'no' => $no ++,
    //          'photo' => $val->photo,
    //          'name' => $val->name,
    //          'village' => $address->village->name,
    //          'district' => $address->village->district->name,
    //          'regency' => $address->village->district->regency->name,
    //          'input' => $gF->decimalFormat($val->total),
    //          'whatsapp' => $val->whatsapp,
    //          'phone' => $val->phone_number,
    //       ];
    //   }
    //   $result = [
    //       'input_acumulate' => $gF->decimalFormat($referalCalculate),
    //       'data' => $data
    //   ];
    //   return response()->json($result);
    }

    public function inputByMountAdminProvince()
    {
      $mounth       = request()->mounth;
      $year         = request()->year;
      $province_id  = request()->province_id;
      $referalModel = new Referal();
      $input      = $referalModel->getInputByMounthAdminProvince($mounth, $year, $province_id);
      $inputCalculate = collect($input)->sum(function($q){
          return $q->total;
      });
       $gF = new GlobalProvider();

      $userModel = new User();
      $data = [];
      $no = 1;
      foreach ($input as $val) {
          $address          = $userModel->with(['village.district.regency'])->where('id', $val->user_id)->first();
          $data[] = [ 
              'no' => $no ++,
             'photo' => $val->photo,
             'name' => $val->name,
             'village' => $address->village->name,
             'district' => $address->village->district->name,
             'regency' => $address->village->district->regency->name,
             'input' => $val->total,
             'whatsapp' => $val->whatsapp,
             'phone' => $val->phone_number,
          ];
      }
      $result = [
          'input_acumulate' => $gF->decimalFormat($inputCalculate),
          'data' => $data
      ];

      return response()->json($result);
    }

    public function getTotalInputByMonthRegency(Request $request)
    {
        $gF = new GlobalProvider();
        $data      = DB::table('users as a')
                        ->select('a.id as user_id','a.name','a.phone_number','a.whatsapp','a.photo',
                        DB::raw('COUNT(b.id) as total'))
                       ->join('users as b','a.id','b.cby')
                       ->join('villages as c','b.village_id','c.id')
                       ->join('districts as d','c.district_id','d.id')
                        ->join('regencies as e','d.regency_id','e.id')
                         ->where('e.id', $request->regency_id)
                        ->whereNotNull('b.village_id');

                       if($request->input('dateInputer') != '' AND $request->input('yearInputer') != ''){
                            $data->whereMonth('b.created_at', $request->dateInputer);
                            $data->whereYear('b.created_at', $request->yearInputer);
                        }
                        
                        $data = $data->groupBy('a.id','a.phone_number','a.whatsapp','a.photo','a.name');
                        
                        $data = $data->get();
                        
                        //  jumlah referal secara defautl / akumulasi
                        $referalCalculate = collect($data)->sum(function($q){
                            return $q->total;
                        });
                        return response()->json([
                            'input_acumulate' => $gF->decimalFormat($referalCalculate),
                        ]);
    }


    public function inputByMountAdminRegencyDefault(Request $request)
    {
       $gF = new GlobalProvider();
        $orderBy   = 'a.name';
        switch($request->input('order.0.column')){
             case '1':
                $orderBy = 'a.name';
                break;
            case '2':
                $orderBy = 'total';
                break;
        }

        $data      = DB::table('users as a')
                        ->select('a.id as user_id','a.name','a.phone_number','a.whatsapp','a.photo')
                        ->join('users as b','a.id','b.cby')
                        ->join('villages as c','b.village_id','c.id')
                        ->join('districts as d','c.district_id','d.id')
                        ->join('regencies as e','d.regency_id','e.id')
                        ->selectRaw('count(b.id) as total')
                        ->where('e.id', $request->regency_id)
                        ->whereNotNull('b.village_id');
                        // ->orderBy(\ DB::raw('COUNT(b.id)'),'DESC');


                         if($request->input('search.value')!=null){
                            $data = $data->where(function($q)use($request){
                                $q->whereRaw('LOWER(a.name) like ? ',['%'.strtolower($request->input('search.value')).'%'])                            ;
                            });
                        }
                        
                        if($request->input('dateInputer') != '' AND $request->input('yearInputer') != ''){
                            $data->whereMonth('b.created_at', $request->dateInputer);
                            $data->whereYear('b.created_at', $request->yearInputer);
                        }
                        
                        $data = $data->groupBy('a.id','a.phone_number','a.whatsapp','a.photo','a.name');
                         $recordsFiltered = $data->get()->count();
                        
                        if($request->input('length')!=-1) $data = $data->skip($request->input('start'))->take($request->input('length'));
                        $data = $data->orderBy($orderBy,$request->input('order.0.dir'))->get();
                        $recordsTotal = $data->count();

                        $userModel = new User();
                        $result = [];
                        $no = 1;
                        foreach ($data as $val) {
                            $address          = $userModel->with(['village.district.regency'])->where('id', $val->user_id)->first();
                            $result[] = [ 
                                'photo' => $val->photo,
                                'name' => $val->name,
                                'address' => $address->village->name.',<br> '.$address->village->district->name.', <br>'.$address->village->district->regency->name,
                                'input' => $val->total,
                                'whatsapp' => $val->whatsapp,
                                'phone' => $val->phone_number,
                            ];
                        }

                                            
                        return response()->json([
                            'draw'=>$request->input('draw'),
                            'recordsTotal'=>$recordsTotal,
                            'recordsFiltered'=>$recordsFiltered,
                            'data'=> $result,
                        ]);
    }

    public function inputByMountAdminRegency()
    {
      $mounth       = request()->mounth;
      $year         = request()->year;
      $regency_id  = request()->regency_id;
      $referalModel = new Referal();
      $input      = $referalModel->getInputByMounthAdminRegency($mounth, $year, $regency_id);
      $inputCalculate = collect($input)->sum(function($q){
          return $q->total;
      });
       $gF = new GlobalProvider();

      $userModel = new User();
      $data = [];
      $no = 1;
      foreach ($input as $val) {
          $address          = $userModel->with(['village.district.regency'])->where('id', $val->user_id)->first();
          $data[] = [ 
              'no' => $no ++,
             'photo' => $val->photo,
             'name' => $val->name,
             'village' => $address->village->name,
             'district' => $address->village->district->name,
             'regency' => $address->village->district->regency->name,
             'input' => $val->total,
             'whatsapp' => $val->whatsapp,
             'phone' => $val->phone_number,
          ];
      }
      $result = [
          'input_acumulate' => $gF->decimalFormat($inputCalculate),
          'data' => $data
      ];

      return response()->json($result);
    }

    public function getTotalInputByMonthDistrict(Request $request)
    {
        $gF = new GlobalProvider();
         $data      = DB::table('users as a')
                        ->select('a.id as user_id','a.name','a.phone_number','a.whatsapp','a.photo')
                        ->join('users as b','a.id','b.cby')
                        ->join('villages as c','b.village_id','c.id')
                        ->selectRaw('count(b.id) as total')
                        ->where('c.district_id', $request->district_id)
                        ->whereNotNull('b.village_id');

                       if($request->input('dateInputer') != '' AND $request->input('yearInputer') != ''){
                            $data->whereMonth('b.created_at', $request->dateInputer);
                            $data->whereYear('b.created_at', $request->yearInputer);
                        }
                        
                        $data = $data->groupBy('a.id','a.phone_number','a.whatsapp','a.photo','a.name');
                        
                        $data = $data->get();
                        
                        //  jumlah referal secara defautl / akumulasi
                        $referalCalculate = collect($data)->sum(function($q){
                            return $q->total;
                        });
                        return response()->json([
                            'input_acumulate' => $gF->decimalFormat($referalCalculate),
                        ]);
    }

    public function inputByMountAdminDistrictDefault(Request $request)
    {

         $gF = new GlobalProvider();
         $orderBy   = 'a.name';
        switch($request->input('order.0.column')){
             case '1':
                $orderBy = 'a.name';
                break;
             case '2':
                $orderBy = 'total';
                break;
        }

        $data      = DB::table('users as a')
                        ->select('a.id as user_id','a.name','a.phone_number','a.whatsapp','a.photo')
                        ->join('users as b','a.id','b.cby')
                        ->join('villages as c','b.village_id','c.id')
                        ->selectRaw('count(b.id) as total')
                        ->where('c.district_id', $request->district_id)
                        ->whereNotNull('b.village_id');
                        // ->orderBy(\ DB::raw('COUNT(b.id)'),'DESC');


                         if($request->input('search.value')!=null){
                            $data = $data->where(function($q)use($request){
                                $q->whereRaw('LOWER(a.name) like ? ',['%'.strtolower($request->input('search.value')).'%'])                            ;
                            });
                        }
                        
                        if($request->input('dateInputer') != '' AND $request->input('yearInputer') != ''){
                            $data->whereMonth('b.created_at', $request->dateInputer);
                            $data->whereYear('b.created_at', $request->yearInputer);
                        }
                        
                        $data = $data->groupBy('a.id','a.phone_number','a.whatsapp','a.photo','a.name');
                        $recordsFiltered = $data->get()->count();
                        
                        if($request->input('length')!=-1) $data = $data->skip($request->input('start'))->take($request->input('length'));
                        $data = $data->orderBy($orderBy,$request->input('order.0.dir'))->get();
                        $recordsTotal = $data->count();

                        $userModel = new User();
                        $result = [];
                        $no = 1;
                        foreach ($data as $val) {
                            $address          = $userModel->with(['village.district.regency'])->where('id', $val->user_id)->first();
                            $result[] = [ 
                                'photo' => $val->photo,
                                'name' => $val->name,
                                'address' => $address->village->name.',<br> '.$address->village->district->name.', <br>'.$address->village->district->regency->name,
                                'input' => $val->total,
                                'whatsapp' => $val->whatsapp,
                                'phone' => $val->phone_number,
                            ];
                        }

                                            
                        return response()->json([
                            'draw'=>$request->input('draw'),
                            'recordsTotal'=>$recordsTotal,
                            'recordsFiltered'=>$recordsFiltered,
                            'data'=> $result,
                        ]);
    //   $district_id  = request()->district_id;
    //   $referalModel = new Referal();
    //   $input      = $referalModel->getInputByDefaultDistrict($district_id);
    //   $referalCalculate = collect($input)->sum(function($q){
    //       return $q->total;
    //   });

    //   $gF = new GlobalProvider();

    //   $userModel = new User();
    //   $data = [];
    //   $no = 1;
    //   foreach ($input as $val) {
    //       $address          = $userModel->with(['village.district.regency'])->where('id', $val->user_id)->first();
    //       $data[] = [ 
    //           'no' => $no ++,
    //          'photo' => $val->photo,
    //          'name' => $val->name,
    //          'village' => $address->village->name,
    //          'district' => $address->village->district->name,
    //          'regency' => $address->village->district->regency->name,
    //          'input' => $gF->decimalFormat($val->total),
    //          'whatsapp' => $val->whatsapp,
    //          'phone' => $val->phone_number,
    //       ];
    //   }
    //   $result = [
    //       'input_acumulate' => $gF->decimalFormat($referalCalculate),
    //       'data' => $data
    //   ];
    //   return response()->json($result);
    }

     public function inputByMountAdminDistrict()
    {
      $mounth       = request()->mounth;
      $year         = request()->year;
      $district_id  = request()->district_id;
      $referalModel = new Referal();
      $input      = $referalModel->getInputByMounthAdminDistrict($mounth, $year, $district_id);
      $inputCalculate = collect($input)->sum(function($q){
          return $q->total;
      });
       $gF = new GlobalProvider();

      $userModel = new User();
      $data = [];
      $no = 1;
      foreach ($input as $val) {
          $address          = $userModel->with(['village.district.regency'])->where('id', $val->user_id)->first();
          $data[] = [ 
              'no' => $no ++,
             'photo' => $val->photo,
             'name' => $val->name,
             'village' => $address->village->name,
             'district' => $address->village->district->name,
             'regency' => $address->village->district->regency->name,
             'input' => $val->total,
             'whatsapp' => $val->whatsapp,
             'phone' => $val->phone_number,
          ];
      }
      $result = [
          'input_acumulate' => $gF->decimalFormat($inputCalculate),
          'data' => $data
      ];

      return response()->json($result);
    }

    public function getTotalInputByMonthVillage(Request $request)
    {
        $gF = new GlobalProvider();
       $data      = DB::table('users as a')
                        ->select('a.id as user_id','a.name','a.phone_number','a.whatsapp','a.photo')
                        ->join('users as b','a.id','b.cby')
                        ->selectRaw('count(b.id) as total')
                        ->where('b.village_id', $request->village_id)
                        ->whereNotNull('b.village_id');

                       if($request->input('dateInputer') != '' AND $request->input('yearInputer') != ''){
                            $data->whereMonth('b.created_at', $request->dateInputer);
                            $data->whereYear('b.created_at', $request->yearInputer);
                        }
                        
                        $data = $data->groupBy('a.id','a.phone_number','a.whatsapp','a.photo','a.name');
                        
                        $data = $data->get();
                        
                        //  jumlah referal secara defautl / akumulasi
                        $referalCalculate = collect($data)->sum(function($q){
                            return $q->total;
                        });
                        return response()->json([
                            'input_acumulate' => $gF->decimalFormat($referalCalculate),
                        ]);
    }

    public function inputByMountAdminVillageDefault(Request $request)
    {
        $gF = new GlobalProvider();
        $orderBy   = 'a.name';
        switch($request->input('order.0.column')){
             case '1':
                $orderBy = 'a.name';
                break;
             case '2':
                $orderBy = 'total';
                break;
        }

        $data      = DB::table('users as a')
                        ->select('a.id as user_id','a.name','a.phone_number','a.whatsapp','a.photo')
                        ->join('users as b','a.id','b.cby')
                        ->selectRaw('count(b.id) as total')
                        ->where('b.village_id', $request->village_id)
                        ->whereNotNull('b.village_id');
                        // ->orderBy(\ DB::raw('COUNT(b.id)'),'DESC');


                         if($request->input('search.value')!=null){
                            $data = $data->where(function($q)use($request){
                                $q->whereRaw('LOWER(a.name) like ? ',['%'.strtolower($request->input('search.value')).'%'])                            ;
                            });
                        }
                        
                        if($request->input('dateInputer') != '' AND $request->input('yearInputer') != ''){
                            $data->whereMonth('b.created_at', $request->dateInputer);
                            $data->whereYear('b.created_at', $request->yearInputer);
                        }
                        
                        $data = $data->groupBy('a.id','a.phone_number','a.whatsapp','a.photo','a.name');
                       $recordsFiltered = $data->get()->count();
                        
                        if($request->input('length')!=-1) $data = $data->skip($request->input('start'))->take($request->input('length'));
                        $data = $data->orderBy($orderBy,$request->input('order.0.dir'))->get();
                        $recordsTotal = $data->count();

                        $userModel = new User();
                        $result = [];
                        $no = 1;
                        foreach ($data as $val) {
                            $address          = $userModel->with(['village.district.regency'])->where('id', $val->user_id)->first();
                            $result[] = [ 
                                'no' => $no ++,
                                'photo' => $val->photo,
                                'name' => $val->name,
                                'address' => $address->village->name.',<br> '.$address->village->district->name.', <br>'.$address->village->district->regency->name,
                                'input' => $val->total,
                                'whatsapp' => $val->whatsapp,
                                'phone' => $val->phone_number,
                            ];
                        }

                                            
                        return response()->json([
                            'draw'=>$request->input('draw'),
                            'recordsTotal'=>$recordsTotal,
                            'recordsFiltered'=>$recordsFiltered,
                            'data'=> $result,
                        ]);
    //   $village_id  = request()->village_id;
    //   $referalModel = new Referal();
    //   $input      = $referalModel->getInputByDefaultVillage($village_id);
    //   $referalCalculate = collect($input)->sum(function($q){
    //       return $q->total;
    //   });

    //   $gF = new GlobalProvider();

    //   $userModel = new User();
    //   $data = [];
    //   $no = 1;
    //   foreach ($input as $val) {
    //       $address          = $userModel->with(['village.district.regency'])->where('id', $val->user_id)->first();
    //       $data[] = [ 
    //           'no' => $no ++,
    //          'photo' => $val->photo,
    //          'name' => $val->name,
    //          'village' => $address->village->name,
    //          'district' => $address->village->district->name,
    //          'regency' => $address->village->district->regency->name,
    //          'input' => $gF->decimalFormat($val->total),
    //          'whatsapp' => $val->whatsapp,
    //          'phone' => $val->phone_number,
    //       ];
    //   }
    //   $result = [
    //       'input_acumulate' => $gF->decimalFormat($referalCalculate),
    //       'data' => $data
    //   ];
    //   return response()->json($result);
    }

     public function inputByMountAdminVillage()
    {
      $mounth       = request()->mounth;
      $year         = request()->year;
      $village_id  = request()->village_id;
      $referalModel = new Referal();
      $input      = $referalModel->getInputByMounthAdminVillage($mounth, $year, $village_id);
      $inputCalculate = collect($input)->sum(function($q){
          return $q->total;
      });
       $gF = new GlobalProvider();

      $userModel = new User();
      $data = [];
      $no = 1;
      foreach ($input as $val) {
          $address          = $userModel->with(['village.district.regency'])->where('id', $val->user_id)->first();
          $data[] = [ 
              'no' => $no ++,
             'photo' => $val->photo,
             'name' => $val->name,
             'village' => $address->village->name,
             'district' => $address->village->district->name,
             'regency' => $address->village->district->regency->name,
             'input' => $val->total,
             'whatsapp' => $val->whatsapp,
             'phone' => $val->phone_number,
          ];
      }
      $result = [
          'input_acumulate' => $gF->decimalFormat($inputCalculate),
          'data' => $data
      ];

      return response()->json($result);
    }

    public function getTotalInputByMonth(Request $request)
    {
        $gF = new GlobalProvider();
        $data      = DB::table('users as a')
                        ->select('a.id as user_id','a.name','a.phone_number','a.whatsapp','a.photo',
                        DB::raw('COUNT(b.id) as total'))
                       ->join('users as b','a.id','b.cby')
                        ->whereNotNull('b.village_id');

                       if($request->input('dateInputer') != '' AND $request->input('yearInputer') != ''){
                            $data->whereMonth('b.created_at', $request->dateInputer);
                            $data->whereYear('b.created_at', $request->yearInputer);
                        }
                        
                        $data = $data->groupBy('a.id','a.phone_number','a.whatsapp','a.photo','a.name');
                        
                        $data = $data->get();
                        
                        //  jumlah referal secara defautl / akumulasi
                        $referalCalculate = collect($data)->sum(function($q){
                            return $q->total;
                        });
                        return response()->json([
                            'input_acumulate' => $gF->decimalFormat($referalCalculate),
                        ]);
    }

    public function inputByMountAdmiNationalDefault(Request $request)
    {
        $gF = new GlobalProvider();
        $orderBy   = 'a.name';
        switch($request->input('order.0.column')){
             case '1':
                $orderBy = 'a.name';
                break;
            case '2':
                $orderBy = 'total';
                break;
        }

        $data      = DB::table('users as a')
                        ->select('a.id as user_id','a.name','a.phone_number','a.whatsapp','a.photo')
                        ->join('users as b','a.id','b.cby')
                        ->selectRaw('count(b.id) as total')
                        ->whereNotNull('b.village_id');
                        // ->orderBy(\ DB::raw('COUNT(b.id)'),'DESC');


                         if($request->input('search.value')!=null){
                            $data = $data->where(function($q)use($request){
                                $q->whereRaw('LOWER(a.name) like ? ',['%'.strtolower($request->input('search.value')).'%'])                            ;
                            });
                        }
                        
                        if($request->input('dateInputer') != '' AND $request->input('yearInputer') != ''){
                            $data->whereMonth('b.created_at', $request->dateInputer);
                            $data->whereYear('b.created_at', $request->yearInputer);
                        }
                        
                        $data = $data->groupBy('a.id','a.phone_number','a.whatsapp','a.photo','a.name');
                        $recordsFiltered = $data->get()->count();
                        
                        if($request->input('length')!=-1) $data = $data->skip($request->input('start'))->take($request->input('length'));
                        $data = $data->orderBy($orderBy,$request->input('order.0.dir'))->get();
                        $recordsTotal = $data->count();

                        $userModel = new User();
                        $result = [];
                        $no = 1;
                        foreach ($data as $val) {
                            $address          = $userModel->with(['village.district.regency'])->where('id', $val->user_id)->first();
                            $result[] = [ 
                                'photo' => $val->photo,
                                'name' => $val->name,
                                 'address' => $address->village->name.',<br> '.$address->village->district->name.', <br>'.$address->village->district->regency->name,
                                'input' => $val->total,
                                'whatsapp' => $val->whatsapp,
                                'phone' => $val->phone_number,
                            ];
                        }

                                            
                        return response()->json([
                            'draw'=>$request->input('draw'),
                            'recordsTotal'=>$recordsTotal,
                            'recordsFiltered'=>$recordsFiltered,
                            'data'=> $result,
                        ]);
    //   $referalModel = new Referal();
    //   $input      = $referalModel->getInputByDefaultNational();
    //   $referalCalculate = collect($input)->sum(function($q){
    //       return $q->total;
    //   });

    //   $gF = new GlobalProvider();

    //   $userModel = new User();
    //   $data = [];
    //   $no = 1;
    //   foreach ($input as $val) {
    //       $address          = $userModel->with(['village.district.regency'])->where('id', $val->user_id)->first();
    //       $data[] = [ 
    //           'no' => $no ++,
    //          'photo' => $val->photo,
    //          'name' => $val->name,
    //          'village' => $address->village->name ?? '',
    //          'district' => $address->village->district->name ?? '',
    //          'regency' => $address->village->district->regency->name ?? '',
    //          'input' => $gF->decimalFormat($val->total),
    //          'whatsapp' => $val->whatsapp,
    //          'phone' => $val->phone_number,
    //       ];
    //   }
    //   $result = [
    //       'input_acumulate' => $gF->decimalFormat($referalCalculate),
    //       'data' => $data
    //   ];
    //   return response()->json($result);
    }

     public function inputByMountAdminNational()
    {
      $mounth       = request()->mounth;
      $year         = request()->year;
      $referalModel = new Referal();
      $input      = $referalModel->getInputByMounthAdminNational($mounth, $year);
      $inputCalculate = collect($input)->sum(function($q){
          return $q->total;
      });
       $gF = new GlobalProvider();

      $userModel = new User();
      $data = [];
      $no = 1;
      foreach ($input as $val) {
          $address          = $userModel->with(['village.district.regency'])->where('id', $val->user_id)->first();
          $data[] = [ 
              'no' => $no ++,
             'photo' => $val->photo,
             'name' => $val->name,
             'village' => $address->village->name,
             'district' => $address->village->district->name,
             'regency' => $address->village->district->regency->name,
             'input' => $val->total,
             'whatsapp' => $val->whatsapp,
             'phone' => $val->phone_number,
          ];
      }
      $result = [
          'input_acumulate' => $gF->decimalFormat($inputCalculate),
          'data' => $data
      ];

      return response()->json($result);
    }

    public function getRegefalDistrictCaleg($district_id, $userId)
    {
        $referalModel = new Referal();
        $GrafikProvider = new GrafikProvider();

        // input admin terbanyak
        $inputer      = $referalModel->getReferalDistrictCaleg($district_id,$userId);
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

    public function getInputerDistrictCaleg($district_id, $user_id)
    {
        $referalModel = new Referal();
        $GrafikProvider = new GrafikProvider();

        // input admin terbanyak
        $inputer      = $referalModel->getInputerDistrictCaleg($district_id,$user_id);
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

    

}

