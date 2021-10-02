<?php

namespace App\Http\Controllers;

use App\Models\Regency;
use App\Models\Village;
use App\Models\Province;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class VillageController extends Controller
{
    public function villafeFilledProvince($province_id)
    {
        $province = Province::select('name')->where('id', $province_id)->first();
        $villageModel   = new Village(); 
        $village_filled = $villageModel->getVillageFilledProvince($province_id);

            if (request()->ajax()) {
                return DataTables::of($village_filled)->make();
            }

        return view('pages.village.filled-province', compact('province'));
    }
    
    public function villafeFilledRegency($regency_id)
    {
        $regency = Regency::select('name')->where('id', $regency_id)->first();

        $villageModel   = new Village(); 
        $village_filled = $villageModel->getListVillageFilledRegency($regency_id);

            if (request()->ajax()) {
                return DataTables::of($village_filled)->make();
            }

        return view('pages.village.filled-regency', compact('regency'));
    }
}
