<?php

namespace App\Http\Controllers\Admin;

use App\Models\Regency;
use App\Models\Village;
use App\Models\Province;
use App\Http\Controllers\Controller;
use App\Models\District;
use Yajra\DataTables\Facades\DataTables;

class VillageController extends Controller
{
    public function villafeFilledNational()
    {
        $villageModel   = new Village(); 
        $village_filled = $villageModel->getVillageFilledNational();

            if (request()->ajax()) {
                return DataTables::of($village_filled)->make();
            }

        return view('pages.admin.village.filled');
    }

    public function villafeFilledProvince($province_id)
    {
        $province = Province::select('name')->where('id', $province_id)->first();
        $villageModel   = new Village(); 
        $village_filled = $villageModel->getVillageFilledProvince($province_id);

            if (request()->ajax()) {
                return DataTables::of($village_filled)->make();
            }

        return view('pages.admin.village.filled-province', compact('province'));
    }

    public function villafeFilledRegency($regency_id)
    {
        $regency = Regency::select('name')->where('id', $regency_id)->first();

        $villageModel   = new Village(); 
        $village_filled = $villageModel->getListVillageFilledRegency($regency_id);

            if (request()->ajax()) {
                return DataTables::of($village_filled)->make();
            }

        return view('pages.admin.village.filled-regency', compact('regency'));
    }

    public function villafeFilledDistrict($district_id)
    {
        $district = District::select('name')->where('id', $district_id)->first();

        $villageModel   = new Village(); 
        $village_filled = $villageModel->getListVillageFilledDistrict($district_id);

            if (request()->ajax()) {
                return DataTables::of($village_filled)->make();
            }

        return view('pages.admin.village.filled-district', compact('district'));
    }
}
