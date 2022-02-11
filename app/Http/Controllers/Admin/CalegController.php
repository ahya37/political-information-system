<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Caleg;
use App\UserMenu;
use App\AdminDapil;
use App\Models\Village;
use App\Models\Province;
use App\AdminDapilVillage;
use App\AdminDapilDistrict;
use App\DapilArea;
use App\DapilCalegs;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CalegController extends Controller
{
    public function create($dapil_id)
    {
        $provinceModel = new Province();
        $province = $provinceModel->getDataProvince();

        $member = "";
        if (request()->input('code') != '') {
            
            $member = User::with(['village.district.regency.province','job','education'])->where('code', request()->input('code'))->first();

        }
        return view('pages.admin.caleg.create', compact('dapil_id','province','member'));
    }

    public function save(Request $request, $dapil_id)
    {

        $adminDapilModel = new AdminDapil();
        $userMenuModel   = new UserMenu();
        $AdminDapilDistrictMode = new AdminDapilDistrict();

       
       // cek jika anggota sudah terdaftar pada data celeg
       $calegModel = new Caleg();

       $caleg = $calegModel->where('user_id', $request->user_id)->count();
       if ($caleg > 0) {
            return redirect()->back()->with(['error' => 'Caleg telah terdaftar']);
       }

       $calegModel->create([
           'dapil_id' => $dapil_id,
           'user_id' => $request->id,
        ]);

        // ROLES DIBAWAH BELUM DI UJI

        // SIMPAN KE TB CALEG
        $saveCaleg =  Caleg::create([
            'dapil_id' => $dapil_id,
            'user_id' => $request->user_id
        ]);
        
        // GET USER_ID
        $caleg_user_id = $saveCaleg->user_id;

        // SIMPAN KE TB ADMIN_DAPIL
            // set user menu dashboard
            $userMenuModel->create([
                    'user_id' => $caleg_user_id,
                    'menu_id' => 1
                    ]);

            // set level = 2
            $updateLevelUser = User::where('id', $request->user_id)->first();
            $updateLevelUser->update(['level' => 2]);
            
            // set hak akses
            $saveAdminDapil =  $adminDapilModel->create([
                            'dapil_id' => $dapil_id,
                            'admin_user_id' => $caleg_user_id
                        ]);

        $dapilAreas   = DapilArea::select('district_id')->where('dapil_id', $dapil_id)->get();

        foreach ($dapilAreas as $val) {
            $AdminDapilDistrictMode->create([
                'admin_dapils_id' => $saveAdminDapil->id,
                'district_id' => $val->district_id,
            ]);

            $village = Village::where('district_id', $val->district_id)->get();

            foreach ($village as $value) {
                $adminDapilVillage = new AdminDapilVillage();
                $adminDapilVillage->admin_dapil_id =  $saveAdminDapil->id;
                $adminDapilVillage->village_id = $value->id;
                $adminDapilVillage->save();
            }

        }

        return redirect()->route('admin-dapil-detail', ['id' => $dapil_id])->with(['success' => 'Caleg telah ditambahkan']);
    }

    
}
