<?php

namespace App\Http\Controllers\API;

use App\User;
use Carbon\Carbon;
use App\Models\Regency;
use App\Providers\GetRegencyId;
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
         return response()->json($province);
    }

    public function getTotalMemberProvince()
    {
        $userModel        = new User();
        $total_member     = $userModel->where('village_id', '!=', NULL)->count();
        return response()->json($total_member);

    }

}
