<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Admin;
use App\UserMenu;
use App\AdminDistrict;
use Illuminate\Http\Request;
use App\Providers\GlobalProvider;
use App\Http\Controllers\Controller;
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

        // jika type form update
        }elseif($request->type == 'update') {
            $user->update(['level' => $request->level]);
        }

        return redirect()->route('admin-admincontroll')->with(['success' => 'Admin telah dibuat']);

    }

    public function editSettingAdminUser($id)
    {
        $user_id = decrypt($id);
        dd($user_id);
        $user    = User::select('id','name','level')->where('id', $user_id)->first();
        return view('pages.admin.admin-control.edit-set-admin', compact('user'));
    }
}
