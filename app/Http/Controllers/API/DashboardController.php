<?php

namespace App\Http\Controllers\API;

use App\User;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Providers\GetRegencyId;

class DashboardController extends Controller
{
    public function memberReportPerMountProvince($daterange)
    {
        if ($daterange != '') {
            $date  = explode('+', $daterange);
            $start = Carbon::parse($date[0])->format('Y-m-d');
            $end   = Carbon::parse($date[1])->format('Y-m-d'); 
        }
        // dd($start);

        $province_id = 36;
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

}
