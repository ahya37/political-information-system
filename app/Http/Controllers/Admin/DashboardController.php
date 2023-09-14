<?php

namespace App\Http\Controllers\Admin;

use PDF;
use App\Job;
use App\User;
use App\Referal;
use App\Models\Regency;
use App\Models\Village;
use App\Models\District;
use App\Models\Province;
use App\Exports\JobRegency;
use App\Exports\JobVillage;
use App\Exports\JobDistrict;
use App\Exports\JobNational;
use App\Exports\JobProvince;
use Maatwebsite\Excel\Excel;
use App\Exports\MemberMostReferal;
use App\Exports\MemberExportRegency;
use App\Exports\MemberExportVillage;
use App\Http\Controllers\Controller;
use App\Exports\MemberExportDistrict;
use App\Exports\MemberExportNational;
use App\Exports\MemberExportProvince;
use Yajra\DataTables\Facades\DataTables;

class DashboardController extends Controller
{
    private $excel;

    public function __construct(Excel $excel)
    {
        $this->excel = $excel;
    }

    public function index()
    {
        return view('pages.admin.dashboard.index');
    }

    public function indexDistrictKor()
    {
      $authAdminDistrict = auth()->guard('admin')->user()->district_id;

      $districtModel  = new District();
      $district       = $districtModel->with(['regency'])->where('id', $authAdminDistrict)->first();
      $villageModel   = new Village();
         // Daftar pencapaian lokasi / daerah
        $achievments   = $villageModel->achievementVillage($authAdminDistrict);
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

        return view('pages.admin.dashboard.adminkor.district', compact('district'));
        // return view('pages.admin.dashboard.index-district', compact('district'));
    }

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
        return view('pages.admin.dashboard.province', compact('province'));
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

        return view('pages.admin.dashboard.regency', compact('regency'));
    }

    public function district($district_id)
    {
        $districtModel    = new District();

        $district   = $districtModel->with(['regency'])->where('id', $district_id)->first();

        $villageModel   = new Village();
         // Daftar pencapaian lokasi / daerah
        $achievments   = $villageModel->achievementVillage($district_id);
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

        return view('pages.admin.dashboard.district', compact('district'));
    }

    public function village($district_id, $village_id)
    {
       
       $villageModel = new Village();
       $village = $villageModel->with('district.regency.province')->where('id', $village_id)->first();

        // Daftar pencapaian lokasi / daerah
        $achievments   = $villageModel->achievementVillageFirst($village_id);

        return view('pages.admin.dashboard.village', compact('village'));

    }

    // report member function
    public function exportDataNationalExcel()
    {
      return $this->excel->download(new MemberExportNational(),'Anggota-Nasional'.'.xls');
    }

    public function exportDataProvinceExcel($province_id)
    {
      
      $province     = Province::select('name')->where('id', $province_id)->first();
      return $this->excel->download(new MemberExportProvince($province_id),'Anggota-Provinsi-'.$province->name.'.xls');
    }

    public function exportDataRegencyExcel($regency_id)
    {
      $regency  = Regency::select('name')->where('id', $regency_id)->first();
      return $this->excel->download(new MemberExportRegency($regency_id),'Anggota-Kabkot-'.$regency->name.'.xls');
    }

    public function exportDataDistrictExcel($district_id)
    {
      $district = District::select('name')->where('id', $district_id)->first();
      return $this->excel->download(new MemberExportDistrict($district_id),'Anggota-Kecamatan-'.$district->name.'.xls');
    }

    public function exportDataVillageExcel($village_id)
    {
      $village = Village::select('name')->where('id', $village_id)->first();
      return $this->excel->download(new MemberExportVillage($village_id),'Anggota-Desa-'.$village->name.'.xls');
    }

    // report jobs function
    public function exportJobsNationalExcel()
    {
      return $this->excel->download(new JobNational(),'Profesi-Nasional'.'.xls');
    }

    public function exportJobsProvinceExcel($province_id)
    {
        $province     = Province::select('name')->where('id', $province_id)->first();
        return $this->excel->download(new JobProvince($province_id),'Profesi-'.$province->name.'.xls');
    }

    public function exportJobsRegencyExcel($regency_id)
    {
      $regency  = Regency::select('name')->where('id', $regency_id)->first();
      return $this->excel->download(new JobRegency($regency_id),'Profesi-Kabkot-'.$regency->name.'.xls');
    }

    public function exportJobsDistrictExcel($district_id)
    {
      $district = District::select('name')->where('id', $district_id)->first();
      return $this->excel->download(new JobDistrict($district_id),'Profesi-Kecamatan-'.$district->name.'.xls');
    }

    public function exportJobsVillageExcel($village_id)
    {
      $village = Village::select('name')->where('id', $village_id)->first();
      return $this->excel->download(new JobVillage($village_id),'Profesi-Desa-'.$village->name.'.xls');
    }

    public function downloadKTA($id)
    {
        $profile = User::with(['village'])->where('id', $id)->first();
        $pdf = PDF::loadView('pages.admin.member.card', compact('profile'))->setPaper('a4');
        return $pdf->stream('kta.pdf');

    }

    public function memberByReferalNationalExcel()
    {
        return $this->excel->download(new MemberMostReferal(),'ANGGOTA REFERAL TERBANYAK.xls');
    }

}
