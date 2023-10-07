<?php

namespace App\Http\Controllers;

use PDF;
use App\Bank;
use App\Menu;
use App\User;
use App\Figure;
use App\UserMenu;
use App\LogEditUser;
use App\AdminDistrict;
use App\Helpers\UpdateNikOrg;
use App\Models\District;
use App\Models\Province;
use App\Providers\StrRandom;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;
use App\Providers\GlobalProvider;
use App\Providers\QrCodeProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
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
        $member  = $userModel->with(['village'])->where('user_id', $id_user)->whereNotIn('id', [$id_user])->orderBy('id','DESC')->get();

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
               'phone_number' => 'numeric',
               'nik' => 'required|min:16'
           ]);

            #hitung panjang nik, harus 16
            $cekLengthNik = strlen($request->nik);
            if($cekLengthNik <> 16) return redirect()->back()->with(['error' => 'NIK harus 16 angka, cek kembali NIK tersebut!']);
           
           $cek_nik = User::select('nik')->where('nik', $request->nik)->count();
           #cek nik jika sudah terpakai
           if ($cek_nik > 0) {
               return redirect()->back()->with(['error' => 'NIK yang anda gunakan telah terdaftar']);
           }else{
              
             //  cek jika reveral tidak tersedia
              $cek_code = User::select('code','id')->where('code', $request->code)->first();
               
              if ($cek_code == null) {
                 return redirect()->back()->with(['error' => 'Kode Reveral yang anda gunakan tidak terdaftar']);
              }else{

                  $request_ktp = $request->ktp;
                  $request_photo = $request->photo;
                  $gF = new GlobalProvider();
                  $ktp = $gF->cropImageKtp($request_ktp);
                  $photo = $gF->cropImagePhoto($request_photo);
       
                  $strRandomProvider = new StrRandom();
                  $string            = $strRandomProvider->generateStrRandom();
                  $potong_nik        = substr($request->nik, -5); // get angka nik 5 angka dari belakang
       
                  $user = User::create([
                      'user_id' => $cek_code->id,
                      'code' => $potong_nik.$string,
                      'nik'  => $request->nik,
                      'name' => strtoupper($request->name),
                      'gender' => $request->gender,
                      'place_berth' => strtoupper($request->place_berth),
                      'date_berth' => date('Y-m-d', strtotime($request->date_berth)),
                      'blood_group' => $request->blood_group,
                      'marital_status' => $request->marital_status,
                      'job_id' => $request->job_id,
                      'religion' => $request->religion,
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

    public function editReferal($id)
    {
        $id = decrypt($id);
        $profile = app('UserModel')->getProfile($id);
        return view('pages.profile.edit-referal', compact('profile'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nik' => 'required|unique:users'
        ]);

        
        $user = User::where('id', $id)->first();
        $oldNik = $user->nik;
        $id = encrypt($id);         

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
        $userModel = new User();
        $user      = $userModel->where('id', $id)->first();

        if ($request->photo != null || $request->ktp != null) {
            // delete foto lama
            $path = public_path();
            if ($request->photo != null) {
                File::delete($path.'/storage/'.$user->photo);
            }
            if ($request->ktp != null) {
                File::delete($path.'/storage/'.$user->ktp);
            }


            $request_ktp = $request->ktp;
            $request_photo = $request->photo;
            $gF = new GlobalProvider();
            $ktp = $request->ktp != null ?  $gF->cropImageKtp($request_ktp) : $user->ktp;
            $photo = $request->photo != null ? $gF->cropImagePhoto($request_photo) : $user->photo;

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

    public function updateReferalMember(Request $request, $id)
    {
        $userModel = new User();
        $user = $userModel->where('id', $id)->first();
        $id = encrypt($id);

         // cek referal jika tidak  terdaftar
        $user_referal     = $userModel->select('code','id')->where('code', $request->code)->first();
        if ($user_referal == null) {
             return redirect()->back()->with(['error' => 'Kode Reveral yang anda gunakan tidak terdaftar']);
        }else{

            // jika referal itu milik user yang di edit, maka tolak
            if ($user->id == $user_referal->id) {
                return redirect()->back()->with(['error' => 'Anda tidak bisa mengubah referal dengan dirinya sendiri']);
            }else{
                // save log edit user beserta alasannya
                LogEditUser::create([
                    'user_id' => $user->id,
                    'reason' => $request->reason
                    ]);
    
                $user->update([
                    'user_id' => $user_referal->id
                    ]);
            }
            return redirect()->route('member-registered-user')->with('success','Referal telah diperbarui');
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

        #cek cby nya siapa
        $cby     = $profile->cby;

        #cek cby ada di admin caleg mana
        $adminCalegCount = DB::table('admin_caleg')->where('admin_caleg_user_id', $cby)->count();
        #cetak KTA by caleg_user_id
        if ($adminCalegCount > 0) {
            # code...
             $adminCaleg = DB::table('admin_caleg')->where('admin_caleg_user_id', $cby)->first();

            if($adminCaleg->caleg_user_id == 359 ){ 
                $pdf = PDF::LoadView('pages.card.usep.caleg', compact('profile','gF'))->setPaper('a4');
                return $pdf->download('e-kta-'.$profile->name.'.pdf');
                
            }
        }else{

            $pdf = PDF::LoadView('pages.card', compact('profile','gF'))->setPaper('a4');
            return $pdf->download('e-kta-'.$profile->name.'.pdf');
        }
		
       
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

        // set secara defualt menunya ketika mendaftar
        $menu_default = Menu::select('id','name')->get();
        // karena menu dashboard itu ada di array pertama maka kita hapus,
        // karena saat mendaftar user tidak bisa mengakses menu dashboard jika bukan di jadikan admin oleh administrator
        unset($menu_default[0]);
        foreach($menu_default as $val){
            UserMenu::create([
                'user_id' => $user->id,
                'menu_id' => $val->id
            ]);
        }  
        
        return redirect()->route('home');
    }
    
    public function memberRegisterIndex(){

        $level  = Auth::user()->level;
        if ($level == 4) {
            
           return $this->memberRegisterCaleg();

        }else{

           return $this->memberRegister();

        }
        
    }

    public function memberRegister()
    {
        $figure   = Figure::all();
        $user_id  = Auth::user()->id;
        $member   = User::with(['village.district.regency','reveral','create_by'])
                    ->where('cby', $user_id)
                    ->whereNotIn('id',[$user_id])
                    ->orderBy('created_at','DESC')
                    ->get();

            if (request()->ajax()) 
            {
                return DataTables::of($member)
                        ->addColumn('action', function($item){
                            return '
                                <div class="btn-group">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-sc-primary text-white dropdown-toggle mr-1 mb-1" type="button" data-toggle="dropdown">...</button>
                                        <div class="dropdown-menu">
                                            <a
                                            href="'.route('member-card-download', $item->id).'"
                                            class="btn "
                                            >
                                            Download KTA
                                            </a>
                                             <a
                                                href="'.route('user-profile-edit-referal', encrypt($item->id)).'"
                                                class="btn "
                                                >
                                                Edit Referal
                                                </a>
                                             <a
                                                href="'.route('member-registered-create-account', $item->id).'"
                                                class="btn "
                                                >
                                                Buat Akun
                                                </a>
                                        </div>
                                    </div>
                                </div>
                            ';
                        })
                        ->addColumn('photo', function($item){
                            return '
                                <a href="'.route('member-registered-user-edit', $item->id).'">
                                <img  class="rounded" width="40" src="'.asset('storage/'.$item->photo).'">
                                '.$item->name.'
                                </a>
                            ';
                        })
                        ->addColumn('referal', function($item){
                            return $item->referal;
                        })
                        ->addColumn('countreferal', function($item){
                            $countreferal = User::select('nik')->where('user_id', $item->id)->whereNotNull('village_id')->count();
                            $countreferal = 0 ? 0 : $countreferal;
                            return '<p class="text-right">'.$countreferal.'</p>';
                        })
                        ->addColumn('register', function($item){
                            return date('d-m-Y', strtotime($item->created_at));
                        })
                        ->rawColumns(['action','photo','referal','register','countreferal'])
                        ->make();
                    }
                    return view('pages.member.member-register', compact('figure'));
    }

    public function memberRegisterCaleg(){

        $userId        = Auth::user()->id;
        $provinceModel = new Province();
        $province = $provinceModel->getDataProvinceCaleg($userId);
        return view('pages.member.caleg.member-register', compact('province','userId'));

    }

    public function EditmemberRegister($id)
    {
        $profile = app('UserModel')->getProfile($id);
        return view('pages.member.edit-member-register', compact('profile'));
    }

    public function updateMemberRegister(Request $request, $id)
    {
        try {
            $request->validate([
                'nik' => 'required'
            ]);
    
               #hitung panjang nik, harus 16
               $cekLengthNik = strlen($request->nik);
               if($cekLengthNik <> 16) return redirect()->back()->with(['error' => 'NIK harus 16 angka, cek kembali NIK tersebut!']);
    
            $cek_nik = User::select('nik')->where('nik', $request->nik)->whereNotIn('id', [$id])->count();
    
            if ($cek_nik > 0) {            
                return redirect()->back()->with(['error' => 'NIK yang anda gunakan telah terdaftar']);
            }else{
    
                $user = User::where('id', $id)->first();
                $oldNik = $user->nik;
        
                if ($request->photo != null || $request->ktp != null) {
                    // delete foto lama
                    $path = public_path();
                    if ($request->photo != null) {
                        File::delete($path.'/storage/'.$user->photo);
                    }
                    if ($request->ktp != null) {
                        File::delete($path.'/storage/'.$user->ktp);
                    }
        
                    $request_ktp = $request->ktp;
                    $request_photo = $request->photo;
                    $gF = new GlobalProvider();
                    $ktp = $request->ktp != null ?  $gF->cropImageKtp($request_ktp) : $user->ktp;
                    $photo = $request->photo != null ? $gF->cropImagePhoto($request_photo) : $user->photo;
        
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
                        'education_id'  => $request->education_id,
                        'phone_number' => $request->phone_number,
                        'whatsapp' => $request->whatsapp,
                        'village_id'   => $request->village_id,
                        'rt'           => $request->rt,
                        'rw'           => $request->rw,
                        'address'      => strtoupper($request->address),
                    ]);
                }
    
                UpdateNikOrg::update($oldNik, $request->nik);
                DB::commit();
                return redirect()->route('member-registered-user')->with('success','Anggota telah diubah');
            }
        } catch (\Exception $e) {
           DB::rollBack();
           return redirect()->back()->with(['error' => $e->getMessage()]);
        }

        


    }

    public function createAccount($id)
    {

        $user = User::select('id','name','password')->where('id', $id)->first();

        if ($user->password != null) {
            return redirect()->back()->with(['warning' => 'Anggota tersebut sudah memiliki akun']);
        }else{
            return view('pages.member.create-account', compact('user'));
        }

    }

    public function storeAccount(Request $request, $id)
    {
        $user = User::select('id','name')->where('id', $id)->first();

        $this->validate($request, [
            'email' => 'required|email'
        ]);

        $user->update([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            // 'activate_token' => Str::random(10),
            'status' => 1
        ]);

        // set secara defualt menunya ketika mendaftar
        $menu_default = Menu::select('id','name')->get();
        // karena menu dashboard itu ada di array pertama maka kita hapus,
        // karena saat mendaftar user tidak bisa mengakses menu dashboard jika bukan di jadikan admin oleh administrator
        unset($menu_default[0]);
        foreach($menu_default as $val){
            UserMenu::create([
                'user_id' => $user->id,
                'menu_id' => $val->id
            ]);
        }
        // send link verifikasi ke email terkait
        // Mail::to($request->email)->send(new RegisterMail($user)); // send email untuk verifikasi akun
        
    

        return redirect()->route('member-registered-user')->with(['success' => 'Akun untuk '.$user->name.' telah dibuat']);
        
    }

    public function storeBank(Request $request)
    {
        $user_id = Auth::user()->id;

        $bank    = new Bank();
        $cekBank = $bank->where('user_id', $user_id)->count();
        if ($cekBank > 0) {
            $update = $bank->where('user_id', $user_id)->first();
            $update->update([
                'number' => $request->number == null ? strtoupper($update->number) : strtoupper($request->number),
                'owner' => $request->owner == null ? strtoupper($update->owner) : strtoupper($request->owner),
                'bank' => $request->bank == null ? strtoupper($update->bank) :  strtoupper($request->bank),
            ]);
        }else{
            Bank::create([
                'user_id' => Auth::user()->id,
                'number' => strtoupper($request->number),
                'owner' => strtoupper($request->owner),
                'bank' => strtoupper($request->bank),
            ]);
        }
        
        return redirect()->back()->with(['success' => 'Rekening Bank telah tersimpan']);
    }
    

}
