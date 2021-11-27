<?php

namespace App\Http\Controllers\API;

use App\Dapil;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DapilController extends Controller
{
    public function getRegencyDapil()
    {
        $token = request()->token;
        if ($token == true) {
            $dapilModel = new Dapil();
            $dapilRegency = $dapilModel->getRegencyDapil();
            return response()->json($dapilRegency);
        }
    }

    public function getListDapil()
    {
        $token = request()->token;
        $regency_id = request()->regencyId;
        if ($token == true) {
            $listDapil = Dapil::where('regency_id', $regency_id)->get();
            return response()->json($listDapil);
        }
    }

    public function getListDistrict()
    {
        $token = request()->token;
        $dapilId = request()->dapilId;
        $dapilModel = new Dapil();
        if ($token == true) {
            $listDistricts = $dapilModel->getDataListDistrict($dapilId);
            return response()->json($listDistricts);
        }
    }
}
