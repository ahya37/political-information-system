<?php

namespace App\Http\Controllers\Admin;

use PDF;
use App\User;
use App\Providers\StrRandom;
use Illuminate\Http\Request;
use App\Providers\QrCodeProvider;
use App\Http\Controllers\Controller;
use App\Providers\GlobalProvider;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables;

class MemberController extends Controller
{
    public function index()
    {
        $member = User::with(['village.district.regency','reveral','create_by'])
                    ->whereNotNull('nik')
                    ->whereNotIn('level',[1])
                    ->orderBy('name','ASC')->get();
        if (request()->ajax()) 
        {
            return DataTables::of($member)
                    ->addColumn('action', function($item){
                        return '
                            <div class="btn-group">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-sc-primary text-white dropdown-toggle mr-1 mb-1" type="button" data-toggle="dropdown">...</button>
                                    <div class="dropdown-menu">
                                         <button type="button" class="dropdown-item" onclick="saved('.$item->id.')" id="'.$item->id.'" member="'.$item->name.'">
                                                Sudah Tersimpan di Nasdem
                                            </button>
                                            <button type="button" class="dropdown-item text-danger" onclick="registered('.$item->id.')" id="'.$item->id.'" member="'.$item->name.'">
                                                Sudah Terdaftar di Nasdem
                                            </button>
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
                    ->addColumn('referal', function($item){
                        return $item->referal;
                    })
                    // ->addColumn('saved_nasdem', function($item){
                    //    if ($item->saved_nasdem == 1) {
                    //        return '<img src="'.asset('assets/images/check-saved.svg').'">';
                    //    }elseif ($item->saved_nasdem == 2) {
                    //        return '<img src="'.asset('assets/images/check-registered.svg').'">';
                    //    }else{

                    //    }
                    // })
                    ->rawColumns(['action','photo','referal'])
                    ->make();
        }
        return view('pages.admin.member.index');
    }

    public function create()
    {
        return view('pages.admin.member.create');
    }

    public function store(Request $request)
    {
           $this->validate($request, [
               'photo' => 'required|mimes:png,jpg,jpeg',
               'ktp' => 'required|mimes:png,jpg,jpeg',
               'phone_number' => 'numeric',
           ]);

           $cby_id = User::select('id')->where('level', 1)->first();
           
           $cek_nik = User::select('nik')->where('nik', $request->nik)->first();
           #cek nik jika sudah terpakai
           if ($cek_nik != null) {
               return redirect()->back()->with(['error' => 'NIK yang anda gunakan telah terdaftar']);
           }else{
              
             //  cek jika reveral tidak tersedia
              $cek_code = User::select('code','id')->where('code', $request->code)->first();
               
              if ($cek_code == null) {
                 return redirect()->back()->with(['error' => 'Kode Reveral yang anda gunakan tidak terdaftar']);
              }else{
                  $photo = $request->file('photo')->store('assets/user/photo','public');
                  $ktp   = $request->file('ktp')->store('assets/user/ktp','public');
       
                  $strRandomProvider = new StrRandom();
                  $string            = $strRandomProvider->generateStrRandom();
       
                  $user = User::create([
                      'user_id' => $cek_code->id,
                      'code' => $string,
                      'nik'  => $request->nik,
                      'name' => strtoupper($request->name),
                      'gender' => $request->gender,
                      'place_berth' => strtoupper($request->place_berth),
                      'date_berth' => date('Y-m-d', strtotime($request->date_berth)),
                      'blood_group' => $request->blood_group,
                      'marital_status' => $request->marital_status,
                      'job_id' => $request->job_id,
                      'religion' => $request->religion,
                      'nik'  => $request->nik,
                      'education_id'  => $request->education_id,
                      'email' => $request->email,
                      'phone_number' => $request->phone_number,
                      'whatsapp' => $request->whatsapp,
                      'village_id'   => $request->village_id,
                      'rt'           => $request->rt,
                      'rw'           => $request->rw,
                      'address'      => strtoupper($request->address),
                      'photo'        => $photo,
                      'ktp'          => $ktp,
                      'cby'          => $cby_id->id,
                  ]);
   
                  #generate qrcode
                   $qrCode       = new QrCodeProvider();
                   $qrCodeValue  = $user->code.'-'.$user->name;
                   $qrCodeNameFile= $user->code;
                   $qrCode->create($qrCodeValue, $qrCodeNameFile);

              }
           }

        return redirect()->route('admin-member')->with('success','Anggota baru telah dibuat');
    }

    public function profileMember($id)
    {
        $id_user = decrypt($id);
        $userModel = new User();
        $profile = $userModel->with(['village'])->where('id', $id_user)->first();
        $member  = $userModel->with(['village','reveral'])->where('user_id', $id_user)->whereNotIn('id', [$id_user])->get();
        $total_member = count($member);

        $gF = new GlobalProvider();
        return view('pages.admin.member.profile', compact('gF','profile','member','total_member'));
    }

    public function editMember($id)
    {
        $id = decrypt($id);
        $profile = app('UserModel')->getProfile($id);
        return view('pages.admin.member.edit', compact('profile'));
    }

     public function updateMember(Request $request, $id)
    {
        $request->validate([
            'nik' => 'required'
        ]);

        $user = User::where('id', $id)->first();

        if ($request->hasFile('photo') || $request->hasFile('ktp')) {
            // delete foto lama
            $path = public_path();
            if ($request->photo != null) {
                File::delete($path.'/storage/'.$user->photo);
            }
            if ($request->ktp != null) {
                File::delete($path.'/storage/'.$user->ktp);
            }

            $photo = $request->photo != null ? $request->file('photo')->store('assets/user/photo','public') : $user->photo;
            $ktp   = $request->ktp   != null ? $request->file('ktp')->store('assets/user/ktp','public') : $user->ktp;

            $user->update([
                'nik'  => $request->nik,
                'name' => strtoupper($request->name),
                'gender' => $request->gender,
                'place_berth' => strtoupper($request->place_berth),
                'date_berth' => date('Y-m-d', strtotime($request->date_berth)),
                'blood_group' => $request->blood_group,
                'marital_status' => $request->marital_status,
                'job_id' => $request->job_id,
                'religion' => $request->religion,
                'nik'  => $request->nik,
                'education_id'  => $request->education_id,
                'phone_number' => $request->phone_number,
                'whatsapp' => $request->whatsapp,
                'village_id'   => $request->village_id,
                'rt'           => $request->rt,
                'rw'           => $request->rw,
                'address'      => strtoupper($request->address),
                'photo'        => $photo,
                'ktp'          => $ktp
            ]);

        }else{
            $user->update([
                'nik'  => $request->nik,
                'name' => strtoupper($request->name),
                'gender' => $request->gender,
                'place_berth' => strtoupper($request->place_berth),
                'date_berth' => date('Y-m-d', strtotime($request->date_berth)),
                'blood_group' => $request->blood_group,
                'marital_status' => $request->marital_status,
                'job_id' => $request->job_id,
                'religion' => $request->religion,
                'nik'  => $request->nik,
                'education_id'  => $request->education_id,
                'phone_number' => $request->phone_number,
                'whatsapp' => $request->whatsapp,
                'village_id'   => $request->village_id,
                'rt'           => $request->rt,
                'rw'           => $request->rw,
                'address'      => strtoupper($request->address),
            ]);
        }

        $id = encrypt($id);
        return redirect()->route('admin-profile-member', ['id' => $id]);
    }

    public function downloadCard($id)
    {
        $gF = new GlobalProvider();

        $profile = User::with('village')->where('id', $id)->first();
        $pdf = PDF::LoadView('pages.card', compact('profile','gF'))->setPaper('a4');
        return $pdf->download('e-kta-'.$profile->name.'.pdf');
    }
}
