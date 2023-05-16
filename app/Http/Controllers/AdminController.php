<?php

namespace App\Http\Controllers;

use App\User;
use App\Admin;
use App\AdminCaleg;
use App\UserMenu;
use App\DapilArea;
use App\AdminDapil;
use App\Models\Village;
use App\AdminDapilVillage;
use App\AdminDapilDistrict;
use App\Menu;
use Illuminate\Http\Request;
use App\Providers\GlobalProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class AdminController extends Controller
{
    public function index()
    {
         return view('pages.admin-control.index');
    }

    public function dtListAdmin($user_id)
    {
        $adminModel = new Admin();
        $admins    = $adminModel->getAdminCaleg($user_id);
        if (request()->ajax()) 
        {

            return DataTables::of($admins)
                    ->addIndexColumn()
                    ->addColumn('level', function($item){
                        if ($item->level == 1) {
                            return
                            '<span class="badge badge-success">Korcam / Kordes</span>';

                        }elseif ($item->level == 2) {
                            return
                            '<span class="badge badge-success">Korwil / Dapil / Caleg / TK.II </span>';
                        }elseif ($item->level == 3) {
                            return
                            '<span class="badge badge-success">Provinsi Kabupaten/ Kota/ Caleg Tk. I</span>';
                        }elseif($item->level == 0){
                           return  '<span class="badge badge-info">Hanya Input</span>';
                        }
                    })
                    ->addColumn('total_data', function($item){
                        $gF = new GlobalProvider();
                        return $gF->decimalFormat($item->total_data);
                    })
                    ->addColumn('photo', function($item){
                        return '
                            <img  class="rounded" width="40" src="'.asset('storage/'.$item->photo).'">
                            '.$item->name.'
                        ';
                    })
                    ->rawColumns(['level','area','total_data','photo'])
                    ->make();
        }
    }

    public function createAdminCaleg()
    {
         return view('pages.admin-control.create-admin-caleg');
    }

    public function storeAdminCaleg(Request $request){

       DB::beginTransaction();
       try {

        #get user_id by code referal
        $userModel  = DB::table('users');

        $user        = $userModel->select('id')->where('code', $request->code)->first(); 

        $userCalegId = Auth::user()->id;

        #get dapil_id by caleg
        $dapilId  = DB::table('dapil_calegs')->select('dapil_id')->where('user_id', $userCalegId)->first();
        $dapilId  = $dapilId->dapil_id;


        $adminDapilModel        = new AdminDapil();
       

        #cek apakah sudah terdaftar di admin dapil
        $memberAdmin = $adminDapilModel->where('admin_user_id', $user->id)->count();
        if ($memberAdmin > 0) {

            return redirect()->back()->with(['warning' => 'Sudah terdaftar sebagai admin']);
           
        }else{

            $saveAdminDapil =  $adminDapilModel->create([
                'dapil_id' => $dapilId,
                'admin_user_id' => $user->id
            ]);

            // get  district_id di dapil_areas
            $dapilAreas = DapilArea::where('dapil_id', $dapilId)->get();
            foreach($dapilAreas as $val)
            {
                $adminDapilDistrict = new AdminDapilDistrict();
                $adminDapilDistrict->admin_dapils_id = $saveAdminDapil->id;
                $adminDapilDistrict->district_id = $val->district_id;
                $adminDapilDistrict->save();

                $village = Village::where('district_id',  $val->district_id)->get();
                 foreach ($village as $val) {
                    $adminDapilVillage = new AdminDapilVillage();
                    $adminDapilVillage->admin_dapil_id =  $saveAdminDapil->id;
                    $adminDapilVillage->village_id = $val->id;
                    $adminDapilVillage->save();
                }

            }

            #cek apakah email sudah terdaftar
            $cekValidateEmail = $userModel->select('email')->where('email', $request->email)->count();
            if ($cekValidateEmail > 0) { #update beserta emailnya
                #update status user sebagai admin
                $userModel->where('code', $request->code)->update([
                    'level' => 2,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'status' => 1,
                    'set_admin' => 'Y'
                ]);
            }else{
                #tidak perlu update email
                #update status user sebagai admin
                $userModel->where('code', $request->code)->update([
                    'level' => 2,
                    'password' => Hash::make($request->password),
                    'status' => 1,
                    'set_admin' => 'Y'
                ]);
            }

            #store menus untuk admin
            $menus = Menu::select('id')->where('id', '!=', 8)->where('id', '!=', 1)->get();
            foreach ($menus as $value) {
                $userMenuModel    = new UserMenu();
                $userMenuModel->user_id = $user->id;
                $userMenuModel->menu_id = $value->id;
                $userMenuModel->save();
            }

            #store ke admin calegs
           AdminCaleg::create([
            'dapil_id' => $dapilId,
            'caleg_user_id' => $userCalegId,
            'admin_caleg_user_id' => $user->id
           ]);

        }
       
        DB::commit();
        return redirect()->route('member-admin-index')->with(['success' => 'Admin sudah tersimpan!']);

       } catch (\Exception $e) {
        DB::rollBack();
        return $e->getMessage();
       }

    }
}
