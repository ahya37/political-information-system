<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Village;
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
}
