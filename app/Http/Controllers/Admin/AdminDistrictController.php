<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\AdminDistrict;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\UserMenu;
use Yajra\DataTables\Facades\DataTables;

class AdminDistrictController extends Controller
{
    public function index()
    {
        $adminDistrictModel = new AdminDistrict();
        $admin_district     = $adminDistrictModel->getDataAdminDistrict();
        if (request()->ajax()) 
        {
            return DataTables::of($admin_district)->make();
        }

        return view('pages.admin.admin-district.index');
    }

    public function create()
    {
        $members = [];
        if (request()->district_id != '') {
            $district_id = request()->district_id;
            $userModel = new User();
            $members   = $userModel->getMemberForCreateAdmin($district_id);
    
        }
        return view('pages.admin.admin-district.create', compact('members'));
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
