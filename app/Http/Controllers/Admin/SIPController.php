<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Province;

class SIPController extends Controller
{
    public function index(){

        $provinceModel = new Province();
        $provinces = $provinceModel->getDataProvince();

        return view('pages.admin.sip.index', compact('provinces'));
    }
}
