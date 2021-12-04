<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Admin;
use App\AdminDapil;
use App\AdminDapilDistrict;
use App\AdminDapilVillage;
use App\UserMenu;
use Illuminate\Http\Request;
use App\AdminRegionalDistrict;
use App\AdminRegionalVillage;
use App\Dapil;
use App\DapilArea;
use App\Providers\GlobalProvider;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class AdminController extends Controller
{
    public function index()
    {
        return view('pages.admin.admin-control.index');
    }

    public function create()
    {
        $members = User::with(['village.district.regency'])
                    ->whereNotNull('village_id')
                    ->where('status', 1)->get();
        if (request()->ajax()) 
        {
            return DataTables::of($members)
                    ->addIndexColumn()
                    ->addColumn('action', function($item){
                        return '
                            <div class="btn-group">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-sc-primary text-white dropdown-toggle mr-1 mb-1" type="button" data-toggle="dropdown">...</button>
                                    <div class="dropdown-menu">
                                         <a href='.route('admin-admincontroll-setting', encrypt($item->id)).' class="dropdown-item">
                                                Admin
                                        </a> 
                                    </div>
                                </div>
                            </div>
                        ';
                    })
                    ->addColumn('photo', function($item){
                        return '
                        <a href="'.route('admin-profile-member', encrypt($item->id)).'">
                            <img  class="rounded" width="40" src="'.asset('storage/'.$item->photo).'">
                            '.$item->name.'
                        </a>
                        ';
                    })
                    ->rawColumns(['action','photo'])
                    ->make(true);
        }
       
        return view('pages.admin.admin-control.create', compact('members'));
    }

    public function settingAdminUser($id)
    {
        $user_id = decrypt($id);
        $user    = User::select('id','name')->where('id', $user_id)->first();
        return view('pages.admin.admin-control.set-admin', compact('user'));
    }

    public function storeSettingAdminUser(Request $request, $id)
    {
            $adminDapilModel = new AdminDapil();
            $userMenuModel   = new UserMenu();
            $AdminDapilDistrictMode = new AdminDapilDistrict();

            // jika type form
            $user = User::where('id', $id)->first();
            $user->update(['level' => $request->level]);
            // cek apakah sudah ter set menu_id =1 
            $cekMenu  = $userMenuModel->where('user_id', $user->id)->count();
            if ($cekMenu == 0) {
                // tambahkan user_id tersebut ke tbl user_menu untuk mendapatkan akses dashboard
                $userMenuModel->create([
                    'user_id' => $user->id,
                    'menu_id' => 1
                    ]);
            }

                // jika level = 1 , korcam kordes
                $level = $request->level;
                if ($level == '1') {
                    // cek apakah sudah terdaftar di admin_dapil
                    $memberAdmin = $adminDapilModel->where('admin_user_id', $user->id)->count();
                    if ($memberAdmin == 0) {
                        $saveAdminDapil =  $adminDapilModel->create([
                            'dapil_id' => $request->dapil_id,
                            'admin_user_id' => $user->id
                        ]);
                    }else{
                        // jika ada, get id nya
                        $adminDapilId = $adminDapilModel->select('id')->where('admin_user_id', $user->id)->first();
                        // simpan ke tabel admin_dapil_district
                        $AdminDapilDistrictMode->create([
                            'admin_dapils_id' => $adminDapilId->id,
                            'district_id' => $request->district_id,
                        ]);
                    }


                // jika level = 2, korwil / dapil TK.II
                }elseif($level == '2'){
                    // cek apakah sudah terdaftar di admin_dapil
                    $memberAdmin = $adminDapilModel->where('admin_user_id', $user->id)->count();
                    if ($memberAdmin == 0) {
                        $saveAdminDapil =  $adminDapilModel->create([
                            'dapil_id' => $request->dapil_id,
                            'admin_user_id' => $user->id
                        ]);
                    }else{
                        return redirect()->back()->with(['warning' => 'Sudah menjadi admin']);
                    }

                    // get  district_id di dapil_areas
                    $dapilAreas = DapilArea::where('dapil_id', $request->dapil_id)->get();
                    foreach($dapilAreas as $val)
                    {
                        $adminDapilDistrict = new AdminDapilDistrict();
                        $adminDapilDistrict->admin_dapils_id = $saveAdminDapil->id;
                        $adminDapilDistrict->district_id = $val->district_id;
                        $adminDapilDistrict->save();
                    } 
                }

        return redirect()->route('admin-admincontroll')->with(['success' => 'Admin telah dibuat']);

    }

    public function editSettingAdminUser($id)
    {
        $user_id = decrypt($id);
        $user = User::select('id','name')->where('id', $user_id)->first();

        // cek ke table admin_dapils where user_id
        $adminDapil = AdminDapil::where('admin_user_id', $user_id)->first();
        $dapilModel = new Dapil();
        $listDapils = $dapilModel->getDataDapilByDapilId($adminDapil->dapil_id);
        return view('pages.admin.admin-control.detail-admin-dapil', compact('user','listDapils','dapilModel'));
        

    }

    public function saveMappingAdminArea(Request $request, $user_id)
    {
       
        $district_id = $request->districtId;

        // cari kecamatan terpilih berada di dapil mana
        // $dapilAreamodel = new DapilArea();
        // $dapilArea      = $dapilAreamodel->getSearchDapilByDistrict($district_id);
        // if ($dapilArea == null) {
        //     return redirect()->back()->with(['warning' => 'Kecamatan terpilih belum terdaftar didapil']);
        // }

        // simpan dapil_id ke table admin_dapils
        // AdminDapil::create([
        //     'dapil_id' => $dapilArea->dapil_id,
        //     'admin_user_id' => $user_id
        // ]);
        
        
        return redirect()->back()->with(['success' => 'Berhasil Membuat Admin']);
    }

    public function createMappingAdminArea()
    {
        return view('pages.member.set-admin');
    }

    public function dtAdminAreaDistrcit()
    {
        $user_id = Auth::user()->id;
        $adminRegionalDistrict = new AdminRegionalDistrict();
        $adminDistrict = $adminRegionalDistrict->getAdminRegionalDistrictByMember($user_id);
        // admin district
        if (request()->ajax()) 
        {
            return DataTables::of($adminDistrict)
                        ->addColumn('status', function($item){
                            if ($item->status == 0) {
                                return '<span class="badge badge-danger">Menunggu Persetujuan</span>';
                            }else{
                                return '<span class="badge badge-success">Aktif</span>';
                            }
                        })
                        ->rawColumns(['status'])
                        ->make(true);
        }
    }

    public function dtAdminAreaVillage()
    {
        $user_id = Auth::user()->id;
        $adminRegionalVillageModel = new AdminRegionalVillage();
        $adminVillage = $adminRegionalVillageModel->getAdminRegionalVillageByMember($user_id);

        // admin village
        if (request()->ajax()) 
        {
            return DataTables::of($adminVillage)
                        ->addColumn('status', function($item){
                            if ($item->status == 0) {
                                return '<span class="badge badge-danger">Menunggu Persetujuan</span>';
                            }else{
                                return '<span class="badge badge-success">Aktif</span>';
                            }
                        })
                        ->rawColumns(['status'])
                        ->make(true);
        }

    }

    public function showListAdminSubmission()
    {
        return view('pages.admin.admin-control.submission');
    }

    public function dtAdminAreaDistrcitAdmin()
    {
        $adminRegionalDistrict = new AdminRegionalDistrict();
        $adminDistrict = $adminRegionalDistrict->getAdminRegionalDistrict();
        // admin district
        if (request()->ajax()) 
        {
            return DataTables::of($adminDistrict)
                        ->addColumn('photo', function($item){
                            return '
                                    <a href="'.route('admin-profile-member', $item->user_id).'">
                                        <img  class="rounded" width="40" src="'.asset('storage/'.$item->photo).'">
                                    </a>
                                    ';
                        })
                        ->addColumn('status', function($item){
                            if ($item->status == 0) {
                                return '<span class="badge badge-danger">Menunggu Persetujuan</span>';
                            }else{
                                return '<span class="badge badge-success">Aktif</span>';
                            }
                        })
                        ->addColumn('action', function($item){
                            return '<div class="btn-group">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-sc-primary text-white dropdown-toggle mr-1 mb-1" type="button" data-toggle="dropdown">...</button>
                                            <div class="dropdown-menu">
                                                <button data-toggle="tooltip"  data-id="'.$item->ardId.'" data-name="'.$item->member.'" district="'.$item->district.'" userId="'.$item->user_id.'" data-original-title="accDistrict" class="ml-1 btn btn-sm btn-success accAdminDistrict">ACC</button>
                                            </div>
                                        </div>
                                    </div>';
                        })
                        ->rawColumns(['status','action','photo'])
                        ->make(true);
        }
    }

    public function dtAdminAreaVillageAdmin()
    {
        $adminRegionalVillageModel = new AdminRegionalVillage();
        $adminVillage = $adminRegionalVillageModel->getAdminRegionalVillage();

        // admin village
        if (request()->ajax()) 
        {
            return DataTables::of($adminVillage)
                        ->addColumn('photo', function($item){
                            return '
                                <a href="'.route('admin-profile-member', $item->user_id).'">
                                    <img  class="rounded" width="40" src="'.asset('storage/'.$item->photo).'">
                                </a>';
                        })
                        ->addColumn('status', function($item){
                            if ($item->status == 0) {
                                return '<span class="badge badge-danger">Menunggu Persetujuan</span>';
                            }else{
                                return '<span class="badge badge-success">Aktif</span>';
                            }
                        })
                         ->addColumn('action', function($item){
                            return '<div class="btn-group">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-sc-primary text-white dropdown-toggle mr-1 mb-1" type="button" data-toggle="dropdown">...</button>
                                            <div class="dropdown-menu">
                                                <button data-toggle="tooltip"  data-id="'.$item->arvId.'" data-name="'.$item->member.'" village="'.$item->village.'" userId="'.$item->user_id.'" data-original-title="accVillage" class="ml-1 btn btn-sm btn-success accAdminVillage">ACC</button>
                                            </div>
                                        </div>
                                    </div>';
                        })
                        ->rawColumns(['status','photo','action'])
                        ->make(true);
        }

    }

    public function  accAdminDistrict()
    {
        $ardId = request()->ardId;
        $token = request()->_token;
        $user_id = request()->userId;

        if ($token != null) {
            $adminDistrict = AdminRegionalDistrict::where('id', $ardId)->first();
            $adminDistrict->update(['status' => 1]);

            $user = User::where('id', $user_id)->first();
            // jika user tersebut level dan user_menunya belum ter setting
            if ($user->level == 0) {
                // set level admin untuk hak akses infomasi dashboard tingkat korcam / kordes
                $user->update(['level' => 1]);
                // tambahkan user_id tersebut ke tbl user_menu untuk mendapatkan akses dashboard
                UserMenu::create([
                    'user_id' => $user_id,
                    'menu_id' => 1
                    ]);
            }                       
            
            //  Return response
            if ($adminDistrict) {
                $success = true;
                $message = "Berhasil ACC!";

            }else{
                $success = false;
                $message = "Gagal ACC!";
            }
            return response()->json([
                'success' => $success,
                'message' => $message,
            ]);
           
        }else{
             $success = false;
             $message = "Gagal ACC!";
            
            return response()->json([
                'success' => $success,
                'message' => $message,
            ]);
        }
    }

    public function  accAdminVillage()
    {
        $arvId = request()->arvId;
        $token = request()->_token;
        $user_id = request()->userId;
        $adminDapilModel = new AdminDapil();

        if ($token != null) {
            $adminVillage = AdminRegionalVillage::where('id', $arvId)->first();
            $adminVillage->update(['status' => 1]);

            $user = User::where('id', $user_id)->first();
            // jika user tersebut level dan user_menunya belum ter setting
            if ($user->level == 0) {
                // set level admin untuk hak akses infomasi dashboard tingkat korcam / kordes
                $user->update(['level' => 1]);
                // tambahkan user_id tersebut ke tbl user_menu untuk mendapatkan akses dashboard
                UserMenu::create([
                    'user_id' => $user_id,
                    'menu_id' => 1
                    ]);
            }
            
            // cek kecamatan berdasarkan desa terpilih
            // cek kecamatan berada di dapil mana
            $cekAdminArea = $adminDapilModel->getCekDapilArea($arvId);
            $dapilId = $cekAdminArea->dapil_id;
            // get dapil_id nya
            // save dapil_id dan admin_user_id ke table admin_dapil
            $saveAdminDapil =  $adminDapilModel->create([
                'dapil_id' => $dapilId,
                'admin_user_id' => $user_id
            ]);
            // save village_id dan admin_dapil_id ke tble admin_dapil_village
           AdminDapilVillage::create([
                'admin_dapil_id' =>  $saveAdminDapil->id,
                'village_id' => $adminVillage->village_id
            ]);            
            
            //  Return response
            if ($adminVillage) {
                $success = true;
                $message = "Berhasil Acc";

            }else{
                $success = false;
                $message = "Gagal ACC!";
            }
            return response()->json([
                'success' => $success,
                'message' => $message,
            ]);
           
        }else{
             $success = false;
             $message = "Gagal ACC!";
            
            return response()->json([
                'success' => $success,
                'message' => $message,
            ]);
        }
    }

    public function dtListAdminAreaDistrict($districtID)
    {
        $adminRegionalDistrictModel = new AdminRegionalDistrict();
        $adminDistrict = $adminRegionalDistrictModel->getListAdminDistrict($districtID);

        // admin district
        if (request()->ajax()) 
        {
            return DataTables::of($adminDistrict)
                        ->addColumn('photo', function($item){
                                        return '
                                            <a href="'.route('admin-profile-member', $item->user_id).'">
                                                <img  class="rounded" width="40" src="'.asset('storage/'.$item->photo).'">
                                            </a>';
                        })
                        ->addColumn('address', function($item){
                             return $item->village.',<br>'.$item->district.',<br>'.$item->regency.',<br>'.$item->province;
                        })
                        ->addColumn('contact', function($item){
                            return '<div class="badge badge-pill badge-primary">
                                        <i class="fa fa-phone"></i>
                                        </div>
                                       '.$item->phone_number.'
                                        <br/>
                                        <div class="badge badge-pill badge-success"><i class="fa fa-whatsapp"></i>
                                        </div>
                                        '.$item->whatsapp.' ';
                        })
                        ->rawColumns(['photo','address','contact'])
                        ->make(true);
        }

    }

    public function dtListAdminAreaVillage($villageID)
    {
        $adminRegionalVillageModel = new AdminRegionalVillage();
        $adminVillage = $adminRegionalVillageModel->getListAdminVillage($villageID);

        // admin district
        if (request()->ajax()) 
        {
            return DataTables::of($adminVillage)
                        ->addColumn('photo', function($item){
                                        return '
                                            <a href="'.route('admin-profile-member', $item->user_id).'">
                                                <img  class="rounded" width="40" src="'.asset('storage/'.$item->photo).'">
                                            </a>';
                        })
                        ->addColumn('address', function($item){
                             return $item->village.',<br>'.$item->district.',<br>'.$item->regency.',<br>'.$item->province;
                        })
                        ->addColumn('contact', function($item){
                            return '<div class="badge badge-pill badge-primary">
                                        <i class="fa fa-phone"></i>
                                        </div>
                                       '.$item->phone_number.'
                                        <br/>
                                        <div class="badge badge-pill badge-success"><i class="fa fa-whatsapp"></i>
                                        </div>
                                        '.$item->whatsapp.' ';
                        })
                        ->rawColumns(['photo','address','contact'])
                        ->make(true);
        }

    }

    public function dtListAdmin()
    {
        $adminModel = new Admin();
        $admins    = $adminModel->getAdmins();
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
                    ->addColumn('action', function($item){
                        return '
                            <div class="btn-group">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-sc-primary text-white dropdown-toggle mr-1 mb-1" type="button" data-toggle="dropdown">...</button>
                                    <div class="dropdown-menu">
                                         <a href='.route('admin-admincontroll-setting-edit', encrypt($item->user_id)).' class="dropdown-item">
                                                Detail 
                                        </a> 
                                    </div>
                                </div>
                            </div>
                        ';
                    })
                    ->addColumn('photo', function($item){
                        return '
                            <img  class="rounded" width="40" src="'.asset('storage/'.$item->photo).'">
                            '.$item->name.'
                        ';
                    })
                    ->rawColumns(['level','area','total_data','action','photo'])
                    ->make();
        }
    }

    public function dtListMemberInputerByRegency($regencyID)
    {
        $userModel = new User();
        $memberInputer = $userModel->getMemberInputerByRegency($regencyID);
        // anggota berdasarkan input di district
        if (request()->ajax()) 
        {
            return DataTables::of($memberInputer)
                        ->addColumn('photo', function($item){
                                        return '
                                            <a href="'.route('admin-profile-member', $item->user_id).'">
                                                <img  class="rounded" width="40" src="'.asset('storage/'.$item->photo).'">
                                            </a>';
                        })
                        ->addColumn('address', function($item){
                             return $item->village.',<br>'.$item->district.',<br>'.$item->regency.',<br>'.$item->province;
                        })
                        ->addColumn('totalData', function($item){
                            return '<div class="badge badge-pill badge-success">
                                        '.$item->total_data.'
                                    </div>
                                       ';
                        })
                        ->addColumn('contact', function($item){
                            return '<div class="badge badge-pill badge-primary">
                                        <i class="fa fa-phone"></i>
                                        </div>
                                       '.$item->phone_number.'
                                        <br/>
                                        <div class="badge badge-pill badge-success"><i class="fa fa-whatsapp"></i>
                                        </div>
                                        '.$item->whatsapp.' ';
                        })
                        ->rawColumns(['photo','address','contact','totalData'])
                        ->make(true);
        }

    }
    public function dtListMemberInputerByDistrict($districtID)
    {
        $userModel = new User();
        $memberInputer = $userModel->getMemberInputerByDistrict($districtID);
        // anggota berdasarkan input di district
        if (request()->ajax()) 
        {
            return DataTables::of($memberInputer)
                        ->addColumn('photo', function($item){
                                        return '
                                            <a href="'.route('admin-profile-member', $item->user_id).'">
                                                <img  class="rounded" width="40" src="'.asset('storage/'.$item->photo).'">
                                            </a>';
                        })
                        ->addColumn('address', function($item){
                             return $item->village.',<br>'.$item->district.',<br>'.$item->regency.',<br>'.$item->province;
                        })
                        ->addColumn('totalData', function($item){
                            return '<div class="badge badge-pill badge-success">
                                        '.$item->total_data.'
                                    </div>
                                       ';
                        })
                        ->addColumn('contact', function($item){
                            return '<div class="badge badge-pill badge-primary">
                                        <i class="fa fa-phone"></i>
                                        </div>
                                       '.$item->phone_number.'
                                        <br/>
                                        <div class="badge badge-pill badge-success"><i class="fa fa-whatsapp"></i>
                                        </div>
                                        '.$item->whatsapp.' ';
                        })
                        ->rawColumns(['photo','address','contact','totalData'])
                        ->make(true);
        }

    }

    public function dtListMemberInputerByVillage($villageID)
    {
        $userModel = new User();
        $memberInputer = $userModel->getMemberInputerByVillage($villageID);
        // anggota berdasarkan input di district
        if (request()->ajax()) 
        {
            return DataTables::of($memberInputer)
                        ->addColumn('photo', function($item){
                                        return '
                                            <a href="'.route('admin-profile-member', $item->user_id).'">
                                                <img  class="rounded" width="40" src="'.asset('storage/'.$item->photo).'">
                                            </a>';
                        })
                        ->addColumn('address', function($item){
                             return $item->village.',<br>'.$item->district.',<br>'.$item->regency.',<br>'.$item->province;
                        })
                        ->addColumn('totalData', function($item){
                            return '<div class="badge badge-pill badge-success">
                                        '.$item->total_data.'
                                    </div>
                                       ';
                        })
                        ->addColumn('contact', function($item){
                            return '<div class="badge badge-pill badge-primary">
                                        <i class="fa fa-phone"></i>
                                        </div>
                                       '.$item->phone_number.'
                                        <br/>
                                        <div class="badge badge-pill badge-success"><i class="fa fa-whatsapp"></i>
                                        </div>
                                        '.$item->whatsapp.' ';
                        })
                        ->rawColumns(['photo','address','contact','totalData'])
                        ->make(true);
        }

    }
    
}
