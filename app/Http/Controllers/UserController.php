<?php

namespace App\Http\Controllers;

use PDF;
use Auth;
use App\Menu;
use App\User;
use App\UserMenu;
use App\AdminDistrict;
use App\Models\District;
use App\Providers\StrRandom;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;
use App\Providers\GlobalProvider;
use App\Providers\QrCodeProvider;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    private $district_id;

    public function __construct(District $district_id)
    {
        $this->district_id = $district_id;
    }
    
    public function index()
    {
        $id      = Auth::user()->id;
        $profile = app('UserModel')->getProfile($id);
        return view('pages.profile.index', compact('profile'));
    }

    public function indexMember()
    {
        return view('pages.member.index');
    }

    public function createNewMember()
    {
        return view('pages.member.create');
    }

    public function profileMyMember($id)
    {
        $gF = new GlobalProvider();
        $id_user = decrypt($id);
        $userModel = new User();
        $profile = $userModel->with(['village'])->where('id', $id_user)->first();
        $member  = $userModel->with(['village'])->where('user_id', $id_user)->whereNotIn('id', [$id_user])->get();

        // referal langsung
        $referal_undirect = $userModel->getReferalUnDirect($id_user);
        $referal_undirect = $referal_undirect->total == NULL ? 0 : $referal_undirect->total;
        $referal_direct = $userModel->getReferalDirect($id_user);
        $referal_direct = $referal_direct->total == NULL ? 0 : $referal_direct->total; 
        $total_referal = $referal_direct + $referal_undirect;
        return view('pages.member.profile', compact('gF','profile','member','total_referal','referal_undirect','referal_direct'));


    }

    public function createReveral()
    {
        $id_user = Auth::user()->id;
        $user    = User::where('id', $id_user)->first();
        return view('pages.create-reveral', compact('user'));
    }

    public function storeReveral(Request $request, $id)
    {
        $this->validate($request, [
            'code' => 'required'
        ]);

        $user_id = User::where('code', $request->code)->first();
        if ($user_id == NULL) {
            return redirect()->back()->with(['error' => 'kode Reveral tidak tersedia']);
        }else{
            $user    = User::where('id', $id)->first();
            $user->update(['user_id' => $user_id->id,'cby' => Auth::user()->id]);
        }

        return redirect()->route('user-create-profile')->with(['success' => 'koder Reveral berhasil disimpan']);
    }

    public function create()
    {
        $id_user = Auth::user()->id;
        $user    = User::where('id', $id_user)->first();
        return view('pages.create-profile', compact('user'));
    }

    public function store(Request $request)
    {
           $this->validate($request, [
               'photo' => 'required|mimes:png,jpg,jpeg',
               'ktp' => 'required|mimes:png,jpg,jpeg',
           ]);
           
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
                      'cby'          => Auth::user()->id,
                  ]);
   
                  #generate qrcode
                   $qrCode       = new QrCodeProvider();
                   $qrCodeValue  = $user->code.'-'.$user->name;
                   $qrCodeNameFile= $user->code;
                   $qrCode->create($qrCodeValue, $qrCodeNameFile);

              }
           }

        $id = encrypt($user->id);
        return redirect()->route('member-mymember', ['id' => $id])->with('success','Anggota baru telah dibuat');
    }

    public function edit($id)
    {
        $id = decrypt($id);
        $profile = app('UserModel')->getProfile($id);
        return view('pages.profile.edit', compact('profile'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nik' => 'required|unique:users'
        ]);
        
        $user = User::where('id', $id)->first();
        $id = encrypt($id);

        // set secara defualt menunya ketika mendaftar
        $menu_default = Menu::select('id','name')->get();
        // karena menu dashboard itu ada di array pertama maka kita hapus,
        // karena saat mendaftar user tidak bisa mengakses menu dashboard jika bukan admin district 
        unset($menu_default[0]);
        foreach($menu_default as $val){
            UserMenu::create([
                'user_id' => $user->id,
                'menu_id' => $val->id
            ]);
        }         

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

            // simpan data ke user menu untuk akses menu default

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

        #jika akunnya, redireck ke dashboard akunnya sendiri
        if ($user->id == Auth::user()->id) {
            return redirect()->route('home')->with('success','Profil telah diperbarui');
        }else{
            #jika anggotanya redireck ke dashoard anggotanya
            return redirect()->route('member-mymember', ['id' => $id]);
        }
    }

    public function updateMyProfile(Request $request, $id)
    {        
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
                'ktp'          => $ktp,
                'cby'          => Auth::user()->id,
            ]);

        }else{
            $user->update([
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
                'cby'          => Auth::user()->id,
            ]);
        }

        #jika akunnya, redireck ke dashboard akunnya sendiri
        $id = encrypt($id);
        if ($user->id == Auth::user()->id) {
            return redirect()->route('home')->with('success','Profil telah diperbarui');
        }else{
            #jika anggotanya redireck ke dashoard anggotanya
            return redirect()->route('member-mymember', ['id' => $id]);
        }
    }

    public function memberReportPdf()
    {
        $id_user = Auth::user()->id;
        $name    = Auth::user()->name;
        $code    = Auth::user()->code;
        $title   = "Laporan-Anggota- $name"."-$code";
        $no      = 1;
        $member  = User::with(['village'])->where('user_id', $id_user)->whereNotIn('id', [$id_user])->orderBy('name','ASC')->get();
        $pdf = PDF::loadView('pages.report.member', compact('member','title','no','name'))->setPaper('a4');
        return $pdf->download($title.'.pdf');
    }

    public function downloadCard($id)
    {
        $gF = new GlobalProvider();
        $profile = User::with('village')->where('id', $id)->first();
        $pdf = PDF::LoadView('pages.card', compact('profile','gF'))->setPaper('a4');
        return $pdf->download('e-kta-'.$profile->name.'.pdf');
    }

    public function savedNasdem($id)
    {
        $user = User::where('id', $id)->first();
        $user->update(['saved_nasdem' => 1]);
        return redirect()->back();
    }

    public function registeredNasdem($id)
    {
        $user = User::where('id', $id)->first();
        $user->update(['saved_nasdem' => 2]);
        return redirect()->back();
    }

    public function memberByUnDirectReferal()
    {
        $id_user    = Auth::user()->id;
        $userModel  = new User();
        $member     = $userModel->getDataByReferalUnDirect($id_user);
        return $member; 
    }

    public function memberByDirectReferal()
    {
        $id_user    = Auth::user()->id;
        $userModel  = new User();
        $member     = $userModel->getDataByReferalDirect($id_user);
        return $member; 
    }

    public function memberByAdminDistrict($district_id)
    {
        $district_id = decrypt($district_id); 
        // $userModel   = new User();
        // $member      = $userModel->getMemberDistrict($district_id);
         $member = User::with(['village.district.regency','reveral','create_by'])
                    ->whereNotNull('nik')
                    ->whereNotIn('id', [Auth::user()->id])
                    ->whereHas('village', function($q) use ($district_id){
                        $q->where('district_id', $district_id);
                    })
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
                            <img  class="rounded" width="40" src="'.asset('storage/'.$item->photo).'">
                            '.$item->name.'
                        ';
                    })
                    ->addColumn('referal', function($item){
                        return $item->referal;
                    })
                    ->rawColumns(['action','photo','referal'])
                    ->make();
        }
       return view('pages.admin-district.all-member');
    }

    public function verificationEmail($activate_token)
    {
        $user = User::where('activate_token', $activate_token)->first();
        $user->update([
            'activate_token' => NULL,
            'status'        => 1
        ]);

        return redirect()->route('home');
    }
}
