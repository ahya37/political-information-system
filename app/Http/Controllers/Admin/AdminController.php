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
                        }
                    })
                    ->addColumn('area', function($item){
                        if ($item->level == 1) {
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
                    ->rawColumns(['level','area','total_data'])
                    ->make();
        }

        return view('pages.admin.admin-control.index');
    }

    public function create()
    {
        $members = User::with(['village.district.regency'])->where('status', 1)->get();
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
                                         <a href='.route('admin-member-create-account', encrypt($item->id)).' class="dropdown-item">
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

    public function saveAdminDistrict($id)
    {
        $user        = User::with('village')->where('id', $id)->first();
        $district_id = $user->village->district_id;
        $user_id     = $user->id;
        
        // create admin save to table
        AdminDistrict::create(['district_id' => $district_id, 'user_id' => $user_id]);

        // simpan ke tb user_menu untuk akses menu dashboard
        UserMenu::create([
            'user_id' => $user_id,
            'menu_id' => 1
        ]);
        return redirect()->route('admin-admincontroll-district-create')->with(['success' => ''.$user->name.' telah ditambahkan sebagai Admin']);
    }
}
