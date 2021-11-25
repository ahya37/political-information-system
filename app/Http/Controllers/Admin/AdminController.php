<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Admin;
use App\AdminDapil;
use App\UserMenu;
use Illuminate\Http\Request;
use App\AdminRegionalDistrict;
use App\AdminRegionalVillage;
use App\DapilArea;
use App\Providers\GlobalProvider;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class AdminController extends Controller
{
    public function index()
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
                    ->addColumn('area', function($item){
                        if ($item->level == 1 || $item->level == 0) {
                            return $item->district;

                        }elseif ($item->level == 2) {
                            return $item->regency;
                        }elseif ($item->level == 3) {
                            return $item->province;
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
                                                Edit
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

        return view('pages.admin.admin-control.index');
    }

    public function create()
    {
        $members = User::with(['village.district.regency'])
                    ->whereNotNull('village_id')
                    ->where('level',0)
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
        // jika type form add
        $user = User::where('id', $id)->first();
        if ($request->type == 'add') {
            $user->update(['level' => $request->level]);

            // tambahkan user_id tersebut ke tbl user_menu untuk mendapatkan akses dashboard
            UserMenu::create([
                'user_id' => $user->id,
                'menu_id' => 1
                ]);

                if ($request->dapil_id != null) {
                    // simpan dapil_id ke table admin_dapils 
                    AdminDapil::create([
                        'dapil_id' => $request->dapil_id,
                        'admin_user_id' => $user->id
                    ]);
                }
            

        // jika type form update
        }elseif($request->type == 'update') {
            $user->update(['level' => $request->level]);
        }

        return redirect()->route('admin-admincontroll')->with(['success' => 'Admin telah dibuat']);

    }

    public function editSettingAdminUser($id)
    {
        $user_id = decrypt($id);
        $user    = User::select('id','name','level')->where('id', $user_id)->first();
        return view('pages.admin.admin-control.edit-set-admin', compact('user'));
    }

    public function saveMappingAdminArea(Request $request, $user_id)
    {
        $district_id = $request->districtId;
        $village_id  = $request->villageId;
        $adminDistrictSave = false;
        $adminVillageSave = false;

        // cari kecamatan terpilih berada di dapil mana
        $dapilAreamodel = new DapilArea();
        $dapilArea      = $dapilAreamodel->getSearchDapilByDistrict($district_id);
        if ($dapilArea == null) {
            return redirect()->back()->with(['warning' => 'Kecamatan terpilih belum terdaftar didapil']);
        }

        // simpan 
        
        $adminRegionalDistrictModel = new  AdminRegionalDistrict();
        $adminDistrict = $adminRegionalDistrictModel
                        ->where('district_id', $district_id)
                        ->where('user_id', $user_id)
                        ->count();

        $adminRegionalVillageModel = new AdminRegionalVillage();

        if ($district_id != null) { // cek jika district_id terisi / tidak kosong
            if ($adminDistrict < 1 ) { // cek apakah district_id sudah terdata pada admin area itu
             $adminDistrictSave =  $adminRegionalDistrictModel->create([
                    'district_id' => $district_id,
                    'user_id' => $user_id
                ]);
            }
        }

        $adminVillage = $adminRegionalVillageModel
                        ->where('village_id', $village_id)
                        ->where('user_id', $user_id)
                        ->count();

        if ($village_id != null) {
            if ($adminVillage < 1) {
               $adminVillageSave =  $adminRegionalVillageModel->create([
                    'village_id' => $village_id,
                    'user_id' => $user_id
                ]);
            }
        }

        if ($adminVillageSave == true ||  $adminDistrictSave == true) {
            return redirect()->back()->with(['success' => 'Berhasil Membuat Admin']);
        }else{
            return redirect()->back()->with(['warning' => 'Gagal Membuat Admin, Anda sudah terdaftar di daerah tersebut']);
            
        }
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
            
            //  Return response
            if ($adminVillage) {
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
    
}
