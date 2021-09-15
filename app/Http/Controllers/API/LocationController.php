<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Village;
use App\Models\Regency;
use App\Models\Province;

class LocationController extends Controller
{
    public function provinces(Request $request)
    {
        return Province::orderBy('name','ASC')->get();
    }

    public function regencies(Request $request, $province_id)
    {
        return Regency::where('province_id', $province_id)->orderBy('name','ASC')->get();
    }

    public function districts(Request $request, $regency_id)
    {
        return District::where('regency_id', $regency_id)->orderBy('name','ASC')->get();
    }

    public function villages(Request $request, $district_id)
    {
        return Village::where('district_id', $district_id)->orderBy('name','ASC')->get();
    }

}
