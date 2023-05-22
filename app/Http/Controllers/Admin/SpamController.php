<?php

namespace App\Http\Controllers\Admin;

use App\TmpSpamUser;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\User;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

class SpamController extends Controller
{
    public function index(){

        $InActiveMembers = TmpSpamUser::orderBy('created_at','desc')->get();

        if (request()->ajax()) {
            return DataTables::of($InActiveMembers)->make();
        }
        
        return view('pages.admin.spam.index', compact('InActiveMembers'));
    }

    public function restoreSpamAnggota(){

        DB::beginTransaction();
        try {

            $id    = request()->id;

            $tmpuser  = TmpSpamUser::where('id', $id)->first();

            
            #save kembali ke tb users
            User::create([
                'user_id' => $tmpuser->user_id,
                'number'  => $tmpuser->number,
                'code'    => $tmpuser->code,
                'nik'     => $tmpuser->nik,
                'name'    => $tmpuser->name,
                'gender'  => $tmpuser->gender,
                'place_berth' => $tmpuser->place_berth,
                'date_berth'  => $tmpuser->date_berth,
                'blood_group' => $tmpuser->blood_group,
                'marital_status' => $tmpuser->marital_status,
                'job_id' => $tmpuser->job_id,
                'religion' => $tmpuser->religion,
                'education_id' => $tmpuser->education_id,
                'email' => $tmpuser->email,
                'email_verified_at' => $tmpuser->email_verified_at,
                'password' => $tmpuser->password,
                'address'  => $tmpuser->address,
                'village_id' => $tmpuser->village_id,
                'rt' => $tmpuser->rt,
                'rw' => $tmpuser->rw,
                'phone_number' => $tmpuser->phone_number,
                'photo' => $tmpuser->photo,
                'ktp' => $tmpuser->ktp,
                'level' => $tmpuser->level,
                'cby' => $tmpuser->cby,
                'saved_nasdem' => $tmpuser->saved_nasdem,
                'activate_token' => $tmpuser->activate_token,
                'status' => $tmpuser->status,
                'remember_token' => $tmpuser->remember_token,
                'set_admin' => $tmpuser->set_admin,
                'created_at' => $tmpuser->created_at,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
            
            #delete file in tb tmp_spam_user
            $tmpuser->delete();
            
            DB::commit();
            return ResponseFormatter::success([
                   'message' => 'Berhasil restore anggota!'
            ],200);

        } catch (\Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'message' => 'Something when wrong!',
                'error' => $e->getMessage()
            ]);
        }

    }

    
}
