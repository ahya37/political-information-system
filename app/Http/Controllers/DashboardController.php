<?php

namespace App\Http\Controllers;

use App\User;
use App\Models\Regency;
use App\Models\Village;
use App\Models\District;
use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class DashboardController extends Controller
{
    public function province($province_id)
    {
        $province    = Province::select('id','name')->where('id', $province_id)->first();
        $regencyModel= new Regency();

        // Daftar pencapaian lokasi / daerah
        $achievments   = $regencyModel->achievementProvince($province_id);
        if (request()->ajax()) {
            return DataTables::of($achievments)
                    ->addColumn('persentage', function($item){
                        $gF   = app('GlobalProvider'); // global function
                        $persentage = ($item->realisasi_member / $item->target_member)*100;
                        $persentage = $gF->persen($persentage);
                        $persentageWidth = $persentage + 30;
                        return '
                        <div class="mt-3 progress" style="width:100%;">
                            <span class="progress-bar progress-bar-striped bg-success" role="progressbar" style="width: '.$persentageWidth.'%" aria-valuenow="'.$persentage.'" aria-valuemin="'.$persentage.'" aria-valuemax="'.$persentage.'"><strong>'.$persentage.'%</strong></span>
                        </div>
                        ';
                    })
                    ->rawColumns(['persentage'])
                    ->make();
        }
        return view('pages.dashboard.province', compact('province'));
    }

    public function regency($regency_id)
    {
        $regency          = Regency::with('province')->where('id', $regency_id)->first();
    
        $districtModel    = new District();
        // Daftar pencapaian lokasi / daerah
        $achievments   = $districtModel->achievementDistrict($regency_id);
        if (request()->ajax()) {
            return DataTables::of($achievments)
                    ->addColumn('persentage', function($item){
                        $gF   = app('GlobalProvider'); // global function
                        $persentage = ($item->realisasi_member / $item->total_target_member)*100;
                        $persentage = $gF->persen($persentage);
                        $persentageWidth = $persentage + 30;
                        return '
                        <div class="mt-3 progress" style="width:100%;">
                            <span class="progress-bar progress-bar-striped bg-success" role="progressbar" style="width: '.$persentageWidth.'%" aria-valuenow="'.$persentage.'" aria-valuemin="'.$persentage.'" aria-valuemax="'.$persentage.'"><strong>'.$persentage.'%</strong></span>
                        </div>
                        ';
                    })
                    ->rawColumns(['persentage'])
                    ->make();
        }


        return view('pages.dashboard.regency', compact('regency'));
    }

    public function district($district_id)
    {
        $districtModel    = new District();

        $district   = $districtModel->with(['regency'])->where('id', $district_id)->first();
        // // jumlah anggota di kecamatan
        $userModel  = new User();
        $member     = $userModel->getMemberDistrict($district_id);
        $total_member = count($member);

        // // perentasi anggot  di kecamatan
        $target_member    = $districtModel->where('id',$district_id)->get()->count() * 5000; // target anggota tercapai, per kecamatan 1000 target

        $villageModel   = new Village();
        $villages       = $villageModel->getVillagesDistrct($district_id); // fungsi total desa di kab
        $total_village  = count($villages);


         // Daftar pencapaian lokasi / daerah
        $achievments   = $villageModel->achievementVillage($district_id);
        $total_target_member = $target_member / $total_village;
        if (request()->ajax()) {
            return DataTables::of($achievments)
                         ->addColumn('persentage', function($item) use($total_target_member){
                            $gF   = app('GlobalProvider'); // global function
                            $persentage = ($item->realisasi_member / $total_target_member)*100;
                            $persentage = $gF->persen($persentage);
                            $persentageWidth = $persentage + 30;
                            return '
                            <div class="mt-3 progress" style="width:100%;">
                                <span class="progress-bar progress-bar-striped bg-success" role="progressbar" style="width: '.$persentageWidth.'%" aria-valuenow="'.$persentage.'" aria-valuemin="'.$persentage.'" aria-valuemax="'.$persentage.'"><strong>'.$persentage.'%</strong></span>
                            </div>
                            ';
                        })
                        ->addColumn('target', function() use($total_target_member){
                            return $total_target_member;
                        })
                        ->rawColumns(['persentage','target'])
                        ->make();
        }

        return view('pages.dashboard.district', compact('district'));
    }

    public function village($district_id, $village_id)
    {
       
       $villageModel = new Village();
       $village = $villageModel->with('district.regency.province')->where('id', $village_id)->first();

        // Daftar pencapaian lokasi / daerah
        $achievments   = $villageModel->achievementVillageFirst($village_id);

        return view('pages.dashboard.village', compact('village'));

    }

    public function villageCaleg($district_id, $village_id)
    {
       $userId       = Auth::user()->id;
       $villageModel = new Village();
       $village      = $villageModel->with('district.regency.province')->where('id', $village_id)->first();

        // Daftar pencapaian lokasi / daerah
        // $achievments   = $villageModel->achievementVillageFirstCaleg($village_id, $userId);

        return view('pages.dashboard.caleg.village', compact('village','userId'));

    }

    public function districtCaleg($district_id)
    {

        $userId           = Auth::user()->id;
        
        $districtModel    = new District();

        $district   = $districtModel->with(['regency'])->where('id', $district_id)->first();
        // // jumlah anggota di kecamatan
        $userModel  = new User();
        $member     = $userModel->getMemberDistrictCaleg($district_id, $userId);
        $total_member = count($member);

        // // perentasi anggot  di kecamatan
        // $target_member    = $districtModel->where('id',$district_id)->get()->count() * 5000; // target anggota tercapai, per kecamatan 1000 target
        $target_member    = DB::table('districts_caleg_target')->select('target')
                            ->where('district_id', $district_id)
                            ->where('caleg_user_id', $userId)
                            ->first();
        $target_member    = $target_member->target;

        $villageModel   = new Village();
        $villages       = $villageModel->getVillagesDistrctCaleg($district_id,$userId); // fungsi total desa caleg
        $total_village  = count($villages);


         // Daftar pencapaian lokasi / daerah
        $achievments   = $villageModel->achievementVillageCaleg($district_id,$userId);
        $total_target_member = $target_member / $total_village;
        if (request()->ajax()) {
            return DataTables::of($achievments)
                         ->addColumn('persentage', function($item){
                            $gF   = app('GlobalProvider'); // global function
                            $persentage = ($item->realisasi_member / $item->target_member)*100;
                            $persentage = $gF->persen($persentage);
                            $persentageWidth = $persentage + 30;
                            return '
                            <div class="mt-3 progress" style="width:100%;">
                                <span class="progress-bar progress-bar-striped bg-success" role="progressbar" style="width: '.$persentageWidth.'%" aria-valuenow="'.$persentage.'" aria-valuemin="'.$persentage.'" aria-valuemax="'.$persentage.'"><strong>'.$persentage.'%</strong></span>
                            </div>
                            ';
                        })
                        ->addColumn('target', function($item){
                            return $item->target_member;
                        })
                        ->rawColumns(['persentage','target'])
                        ->make();
        }

        return view('pages.dashboard.caleg.district', compact('district','userId'));
    }

}
