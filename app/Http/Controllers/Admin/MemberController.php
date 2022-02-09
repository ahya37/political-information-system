<?php

namespace App\Http\Controllers\Admin;

use PDF;
use App\Crop;
use App\Menu;
use App\User;
use App\Admin;
use App\UserMenu;
use App\Models\Regency;
use App\Models\Village;
use App\Models\District;
use App\Models\Province;
use App\Mail\RegisterMail;
use Illuminate\Support\Str;
use App\Providers\StrRandom;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;
use App\Exports\MemberExport;
use App\Providers\GlobalProvider;
use App\Providers\QrCodeProvider;
use App\Exports\MemberMostReferal;
use Illuminate\Support\Facades\DB;
use App\Exports\MemberByReferalAll;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Exports\MemberPotentialInput;
use App\Exports\MemberPotentialReferal;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Yajra\DataTables\Facades\DataTables;
use App\Exports\MemberByReferalInDistrict;

class MemberController extends Controller
{
    public $excel;
    public function __construct(Excel $excel)
    {
        $this->excel = $excel;
    }
    public function index(Request $request)
    {
        $provinceModel = new Province();
        $province = $provinceModel->getDataProvince();
        return view('pages.admin.member.index', compact('province'));
    }

    public function create()
    {
        return view('pages.admin.member.create');
    }

    public function store(Request $request)
    {
           $this->validate($request, [
               'phone_number' => 'numeric',
           ]);

           $cby_id = Admin::select('id')->first();
           $cby    = User::select('id')->where('user_id', $cby_id->id)->first();
           
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
                  
                  $request_ktp = $request->ktp;
                  $request_photo = $request->photo;
                  $gF = new GlobalProvider();
                  $ktp = $gF->cropImageKtp($request_ktp);
                  $photo = $gF->cropImagePhoto($request_photo);
       
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
                      'cby'          => $cby->id,
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
        $id_user = $id;
        $userModel = new User();
        $profile = $userModel->with(['village'])->where('id', $id_user)->first();
        $member  = $userModel->with(['village','reveral'])->where('user_id', $id_user)
                             ->whereNotIn('id', [$id_user])
                             ->whereNotNull('village_id')
                             ->get();
        $referal_direct = $userModel->getReferalDirect($id_user);

        $referal_direct = $referal_direct->total == NULL ? 0 : $referal_direct->total; // referal langsung
        $referal_undirect = $userModel->getReferalUnDirect($id_user);
        $referal_undirect = $referal_undirect->total == NULL ? 0 : $referal_undirect->total; // referal tidak langsung
        $total_member = count($member);

        $gF = new GlobalProvider();
        return view('pages.admin.member.profile', compact('gF','profile','member','total_member','referal_direct','referal_undirect'));
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

        return redirect()->route('admin-profile-member', ['id' => $id]);
    }

    public function downloadCard($id)
    {
        $gF = new GlobalProvider();

        $profile = User::with('village')->where('id', $id)->first();
        $pdf = PDF::LoadView('pages.card', compact('profile','gF'))->setPaper('a4');
        return $pdf->download('e-kta-'.$profile->name.'.pdf');
    }

    public function createAccount($id)
    {

        $user = User::select('id','name')->where('id', $id)->first();
        return view('pages.admin.member.create-account', compact('user'));
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

        return redirect()->route('admin-member')->with(['success' => 'Akun untuk '.$user->name.' telah dibuat']);
        
    }

    public function memberProvince($province_id)
    {
        $province = Province::select('name')->where('id', $province_id)->first();

        $member = User::with(['village.district.regency.province','reveral','create_by'])
                    ->whereHas('village', function($village) use ($province_id){
                        $village->whereHas('district', function($district) use ($province_id){
                            $district->whereHas('regency', function($regency) use ($province_id) {
                                $regency->where('province_id', $province_id);
                            });
                        });
                    })
                    ->whereNotNull('nik')
                    ->get();

        if (request()->ajax()) 
        {
            return DataTables::of($member)
                    ->addIndexColumn()
                    ->addColumn('photo', function($item){
                        return '
                        <a href="'.route('admin-profile-member', $item->id).'">
                            <img  class="rounded" width="40" src="'.asset('storage/'.$item->photo).'">
                            '.$item->name.'
                        </a>
                        ';
                    })
                    ->addColumn('referal', function($item){
                        return $item->referal;
                    })
                    ->rawColumns(['action','photo','referal'])
                    ->make(true);
        }
        return view('pages.admin.member.member-province', compact('province'));
    }

    public function memberRegency($regency_id)
    {
        $regency = Regency::select('name')->where('id', $regency_id)->first();
        $member = User::with(['village.district.regency.province','reveral','create_by'])
                    ->whereHas('village', function($village) use ($regency_id){
                        $village->whereHas('district', function($district) use ($regency_id){
                            $district->where('regency_id', $regency_id);
                        });
                    })
                    ->whereNotNull('nik')
                    ->get();

        if (request()->ajax()) 
        {
            return DataTables::of($member)
                    ->addIndexColumn()
                    ->addColumn('photo', function($item){
                        return '
                        <a href="'.route('admin-profile-member', $item->id).'">
                            <img  class="rounded" width="40" src="'.asset('storage/'.$item->photo).'">
                            '.$item->name.'
                        </a>
                        ';
                    })
                    ->addColumn('referal', function($item){
                        return $item->referal;
                    })
                    ->rawColumns(['action','photo','referal'])
                    ->make(true);
        }
        return view('pages.admin.member.member-regency', compact('regency'));
    }

    public function memberDistrict($district_id)
    {
        $district = District::select('name')->where('id', $district_id)->first();
        $member = User::with(['village.district.regency.province','reveral','create_by'])
                    ->whereHas('village', function($village) use ($district_id){
                        $village->where('district_id', $district_id);
                    })
                    ->whereNotNull('nik')
                    ->get();

        if (request()->ajax()) 
        {
            return DataTables::of($member)
                    ->addIndexColumn()
                    ->addColumn('photo', function($item){
                        return '
                        <a href="'.route('admin-profile-member', $item->id).'">
                            <img  class="rounded" width="40" src="'.asset('storage/'.$item->photo).'">
                            '.$item->name.'
                        </a>
                        ';
                    })
                    ->addColumn('referal', function($item){
                        return $item->referal;
                    })
                    ->rawColumns(['action','photo','referal'])
                    ->make(true);
        }
        return view('pages.admin.member.member-district', compact('district'));
    }

    public function memberVillage($village_id)
    {
        $village = Village::select('name')->where('id', $village_id)->first();
        $member = User::with(['village.district.regency.province','reveral','create_by'])
                    ->where('village_id', $village_id)
                    ->whereNotNull('nik')
                    ->get();

        if (request()->ajax()) 
        {
            return DataTables::of($member)
                    ->addIndexColumn()
                    ->addColumn('photo', function($item){
                        return '
                        <a href="'.route('admin-profile-member', $item->id).'">
                            <img  class="rounded" width="40" src="'.asset('storage/'.$item->photo).'">
                            '.$item->name.'
                        </a>
                        ';
                    })
                    ->addColumn('referal', function($item){
                        return $item->referal;
                    })
                    ->rawColumns(['action','photo','referal'])
                    ->make(true);
        }
        return view('pages.admin.member.member-village', compact('village'));
    }

    public function reportMemberPdf()
    {
        $member = User::with(['village'])
                    ->whereNotNull('nik')
                    ->orderBy('name',)
                    ->get();
        $title = 'Anggota-Nasional'; 
        $no = 1;
        $pdf   = PDF::loadView('pages.admin.report.member-national-pdf', compact('member','title','no'))->setPaper('landscape');
        return $pdf->download($title.'.pdf');
    }

    public function reportMemberProvincePdf($province_id)
    {
        $province = Province::select('name')->where('id', $province_id)->first();
        $member = User::with(['village'])
                    ->whereHas('village', function($village) use ($province_id){
                        $village->whereHas('district', function($district) use ($province_id){
                            $district->whereHas('regency', function($regency) use ($province_id){
                                $regency->where('province_id', $province_id);
                            });
                        });
                    })
                    ->whereNotNull('nik')
                    ->orderBy('name',)
                    ->get();
        $title = 'Anggota-Province-'. $province->name; 
        $no = 1;
        $pdf   = PDF::loadView('pages.admin.report.member-province-pdf', compact('member','title','no','province'));
        return $pdf->download($title.'.pdf');
    }

    public function reportMemberRegencyPdf($regency_id)
    {
        $regency = Regency::select('name')->where('id', $regency_id)->first();
        $member = User::with(['village'])
                    ->whereHas('village', function($village) use ($regency_id){
                        $village->whereHas('district', function($district) use ($regency_id){
                            $district->where('regency_id', $regency_id);
                        });
                    })
                    ->whereNotNull('nik')
                    ->orderBy('name',)
                    ->get();
        $title = 'Anggota-'. $regency->name; 
        $no = 1;
        $pdf   = PDF::loadView('pages.admin.report.member-regency-pdf', compact('member','title','no','regency'));
        return $pdf->download($title.'.pdf');
    }

    public function reportMemberDistrictPdf($district_id)
    {
        $district = District::select('name')->where('id', $district_id)->first();
        $member = User::with(['village','reveral'])
                    ->whereHas('village', function($village) use ($district_id){
                        $village->where('district_id', $district_id);
                    })
                    ->whereNotNull('nik')
                    ->orderBy('name','asc')
                    ->get();
        $title = 'Anggota-'. $district->name; 
        $no = 1;
        $pdf   = PDF::loadView('pages.admin.report.member-district-pdf', compact('member','title','no','district'));
        return $pdf->download($title.'.pdf');
    }

    public function reportMemberVillagePdf($village_id)
    {
        $village = Village::select('name')->where('id', $village_id)->first();
        $member = User::with(['village','reveral'])
                    ->whereHas('village', function($village) use ($village_id){
                        $village->where('id', $village_id);
                    })
                    ->whereNotNull('nik')
                    ->orderBy('name','asc')
                    ->get();
        $title = 'Anggota-Desa-'. $village->name; 
        $no = 1;
        $pdf   = PDF::loadView('pages.admin.report.member-village-pdf', compact('member','title','no','village'))->setPaper('landscape');
        return $pdf->download($title.'.pdf');
    }

    public function cropImage()
    {
        
        return view('pages.admin.member.crop');
    }


    public function saveCropImage(Request $request)
    {
        $ktp = $request->ktp;
        $photo = $request->photo;

        $gF = new GlobalProvider();
        $crop_ktp = $gF->cropImageKtp($ktp);
        $crop_photo = $gF->cropImagePhoto($photo);

        $data = [
            'ktp' => $crop_ktp, 
            'photo' => $crop_photo, 
        ];

        return $data;

        

    }

    public function memberPotensial()
    {
        return view('pages.admin.member.member-potensial');
    }

    public function memberByReferal($user_id)
    {
        $userModel = new User(); 
        $user = $userModel->select('id','name')->where('id', $user_id)->first();
        $districtModel = new District();
        $districts = $districtModel->getDistrictByReferalMember($user_id);
        $totalMember = $districtModel->getTotalMemberByReferal($user_id);
        return view('pages.admin.member.member-by-refeal', compact('user','districts','userModel','totalMember'));
    }

    public function memberByInput($user_id)
    {
        $userModel = new User(); 
        $user = $userModel->select('id','name')->where('id', $user_id)->first();
        $districtModel = new District();
        $districts = $districtModel->getDistrictByInputMember($user_id);
        return view('pages.admin.member.member-by-input', compact('user','districts','userModel'));
    }

    public function memberByReferalNationalPDF()
    {
        $userModel = new User();
        $members = $userModel->getMemberReferal();
        $no = 1;
        $pdf = PDF::LoadView('pages.report.member-referal', compact('members','no','userModel'))->setPaper('a4');
        return  $pdf->download('ANGGOTA REFERAL TERBANYAK.pdf');
    }

    public function memberByReferalDownloadExcel($user_id, $district_id)
    {
        $member = User::select('name')->where('id', $user_id)->first();
        $district = District::select('name')->where('id', $district_id)->first();
        return $this->excel->download(new MemberByReferalInDistrict($district_id, $user_id), 'ANGGOTA REFERAL DARI '.$member->name.' DI KECAMATAN '.$district->name.'.xls');
    }

    public function memberByReferalDownloadExcelAll($user_id)
    {
        $member = User::select('name')->where('id', $user_id)->first();
        return $this->excel->download(new MemberByReferalAll($user_id), 'ANGGOTA REFERAL DARI '.$member->name.'.xls');
    }

    public function memberByReferalDownloadPDF($user_id, $district_id)
    {
        $userModel = new User();
        $user = $userModel->select('name')->where('id', $user_id)->first();
        $district = District::select('name')->where('id', $district_id)->first();
        $no = 1;
        $members  = $userModel->getListMemberByDistrictId($district_id, $user_id);
        $pdf = PDF::LoadView('pages.report.member-referal-in-district', compact('members','no','district','user'))->setPaper('a4');
        return  $pdf->download('ANGGOTA REFERAL DARI '.$user->name.' DI KECAMATAN '.$district->name.'.pdf');
    }

    public function memberByReferalAllDownloadPDF($user_id)
    {
        $userModel = new User();
        $user = $userModel->select('name')->where('id', $user_id)->first();
        $no = 1;
        $members  = $userModel->getListMemberByUserAll($user_id);
        $pdf = PDF::LoadView('pages.report.member-referal-all', compact('members','no','user'))->setPaper('a4');
        return  $pdf->download('ANGGOTA REFERAL DARI '.$user->name.'.pdf');
    }

    public function memberPotentialReferalDownloadExcel()
    {
        return $this->excel->download(new MemberPotentialReferal(), 'ANGGOTA POTENSIAL REFERAL.xls');
    }

    public function memberPotentialInputDownloadExcel()
    {
        return $this->excel->download(new MemberPotentialInput(), 'ANGGOTA POTENSIAL INPUT.xls');
    }

    public function memberPotentialReferalDownloadPDF()
    {
        $userModel = new User();
        $member = $userModel->getMemberReferal();

        $data = [];
        foreach ($member as $val) {
            $userModel = new User();
            $id_user = $val->id;
            $referal_undirect = $userModel->getReferalUnDirect($id_user);
            $total_referal_undirect = $referal_undirect->total == NULL ? '0' : $referal_undirect->total;
            
            $data[] = [
                'name' => $val->name,
                'referal' => $val->total,
                'referal_undirect' => $total_referal_undirect,
                'village' => $val->village,
                'district' => $val->district,
                'regency' => $val->regency,
                'province' => $val->province,
                'phone_number' => $val->phone_number,
                'whatsapp' => $val->whatsapp,
            ];
        }
        $gF = new GlobalProvider();
        $no  =1;
        $pdf = PDF::LoadView('pages.report.member-potential-referal',compact('data','no','gF'))->setPaper('a4');
        return  $pdf->download('ANGGOTA POTENSIAL REFERAL.pdf');
    }

    public function memberPotentialInputDownloadPDF()
    {
        $gF = new GlobalProvider();
        $userModel = new User();
        $member = $userModel->getMemberInput();
        $no  = 1;
        $pdf = PDF::LoadView('pages.report.member-potential-input',compact('member','no','gF'))->setPaper('a4');
        return  $pdf->download('ANGGOTA POTENSIAL INPUT.pdf');
    }


    public function getDownloadExcel()
    {
        $province = request()->input('province');
        $regency = request()->input('regency');
        $dapil = request()->input('dapil');
        $district = request()->input('district');
        $village = request()->input('village');
        $type = request()->input('type');

        // query
        $data = DB::table('users as a')
                        ->select('a.id','a.user_id','a.name','a.photo','a.rt','a.rw','a.phone_number','a.whatsapp','a.address','regencies.name as regency','districts.name as district','villages.name as village','b.name as referal','c.name as cby','a.created_at','a.status','a.email')
                        ->join('villages','villages.id','a.village_id')
                        ->join('districts','districts.id','villages.district_id')
                        ->join('regencies','regencies.id','districts.regency_id')
                        ->join('users as b','b.id','a.user_id')
                        ->join('users as c','c.id','a.cby')
                        ->leftJoin('dapil_areas','districts.id','dapil_areas.district_id')
                        ->whereNotNull('a.village_id')
                        ->orderBy('a.name','asc');
                        
            $title = 'LAPORAN ANGGOTA';
            if ($province != null) {
                        $data->where('regencies.province_id', $province);
                        $provinces = Province::select('name')->where('id', $province)->first();
                        $title = "PROVINSI $provinces->name";
            }

            if ($regency != null) {
                            $data->where('regencies.id',  $regency);
                            $regencies = Regency::select('name')->where('id', $regency)->first();
                            $title = $regencies->name;

                }

            if ($dapil != null) {
                            $data ->where('dapil_areas.dapil_id', $dapil);
                            $title = 'Dapil';
                }
            if ($district != null) {
                            $data->where('districts.id', $district);
                            $districts = District::select('name')->where('id', $district)->first();
                            $title = "KECAMATAN $districts->name";
                }
            if ($village != null) {
                            $data->where('villages.id', $village);
                            $villages = Village::select('name')->where('id', $village)->first();
                            $title = "DESA  $villages->name";
            }

            $data = $data->get();

        // EXPORT EXCEL
        if ($type == 'excel') {
            return $this->excel->download(new MemberExport($data), 'LAPORAN ANGGOTA '.$title.'.xls');
        }else{
            $gF = new GlobalProvider();
            $result = [];
            $no = 1;
            foreach($data as $val){
                $total_referal = User::where('user_id', $val->id)->whereNotNull('village_id')->count();
                $result[] = [
                    'no' => $no++,
                    'name' => $val->name,
                    'address' => $val->address,
                    'rt' => $val->rt,
                    'rw' => $val->rw,
                    'village' => $val->village,
                    'district' => $val->district,
                    'regency' => $val->regency,
                    'telp'    => $val->phone_number,
                    'wa' => $val->whatsapp,
                    'created_at' => date('d-m-Y', strtotime($val->created_at)),
                    'cby' => $val->cby,
                    'referal' => $val->referal,
                    'total_referal' => $gF->decimalFormat($total_referal),
                ];
            }
            $pdf = PDF::LoadView('pages.admin.report.member-byregional',compact('result','no','gF','title'))->setPaper('f4','landscape');
            return  $pdf->download('LAPORAN ANGGOTA '.$title.'.pdf');
        }

    }

}
