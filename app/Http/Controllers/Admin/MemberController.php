<?php

namespace App\Http\Controllers\Admin;

use PDF;
use App\Crop;
use App\Menu;
use App\User;
use App\Admin;
use App\UserMenu;
use App\LogEditUser;
use App\TmpSpamUser;
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
use App\Helpers\DeleteNikOrg;
use App\Helpers\UpdateNikOrg;
use App\CategoryInactiveMember;
use App\Providers\GlobalProvider;
use App\Providers\QrCodeProvider;
use App\Exports\MemberMostReferal;
use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\DB;
use App\Exports\MemberByInputerAll;
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
use App\Exports\MemberByInputerInDistrict;
use App\Exports\MemberByReferalInDistrict;
use App\Exports\MemberPotensialReferalByDistrict;
use App\Exports\MemberPotensialUpperByDistrictUpper;

class MemberController extends Controller
{
    public $excel;
    public function __construct(Excel $excel)
    {
        $this->excel = $excel;
    }
    public function index(Request $request)
    {
        // $provinceModel = new Province();
        // $province = $provinceModel->getDataProvince();
        $authAdminDistrict = auth()->guard('admin')->user()->district_id;
        $districtModel  = new District();
        $district       = $districtModel->getAreaAdminKoordinator($authAdminDistrict);
        // dd($district);
        $villages       = Village::select('id', 'name')->where('district_id', $authAdminDistrict)->get();


        // dd($authAdminDistrict);
        return view('pages.admin.member.index', compact('villages', 'district'));
    }

    public function create()
    {
        return view('pages.admin.member.create');
    }

    public function editReferal($id)
    {
        $profile = app('UserModel')->getProfile($id);
        return view('pages.admin.member.edit-referal', compact('profile'));
    }

    public function updateReferal(Request $request, $id)
    {
        $this->validate($request, [
            'code' => 'required',
        ]);

        $userModel = new User();
        $user = $userModel->where('id', $id)->first();



        // cek referal jika tidak  terdaftar
        $user_referal     = $userModel->select('code', 'id')->where('code', $request->code)->first();
        if ($user_referal == null) {
            return redirect()->back()->with(['error' => 'Kode Reveral yang anda gunakan tidak terdaftar']);
        } else {

            // jika referal itu milik user yang di edit, maka tolak
            if ($user->id == $user_referal->id) {
                return redirect()->back()->with(['error' => 'Anda tidak bisa mengubah referal dengan dirinya sendiri']);
            } else {
                // save log edit user beserta alasannya
                LogEditUser::create([
                    'user_id' => $user->id,
                    'reason' => $request->reason
                ]);

                $user->update([
                    'user_id' => $user_referal->id
                ]);
            }
            return redirect()->back()->with('success', 'Referal telah diperbarui');
        }
    }

    public function store(Request $request)
    {

        $this->validate($request, [
            'phone_number' => 'numeric',
        ]);

        #hitung panjang nik, harus 16
        $cekLengthNik = strlen($request->nik);
        if ($cekLengthNik <> 16) return redirect()->back()->with(['error' => 'NIK harus 16 angka, cek kembali NIK tersebut!']);

        $cby = auth()->guard('admin')->user()->id;
        //    $cby    = User::select('id')->where('user_id', $cby_id->id)->first();

        $cek_nik = User::select('nik')->where('nik', $request->nik)->count();
        #cek nik jika sudah terpakai
        if ($cek_nik > 0) {
            return redirect()->back()->with(['error' => 'NIK yang anda gunakan telah terdaftar']);
        } else {

            //  cek jika reveral tidak tersedia
            $cek_code = User::select('code', 'id')->where('code', $request->code)->first();

            if ($cek_code == null) {
                return redirect()->back()->with(['error' => 'Kode Reveral yang anda gunakan tidak terdaftar']);
            } else {

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
                    'cby'          => $cby,
                ]);

                #generate qrcode
                $qrCode       = new QrCodeProvider();
                $qrCodeValue  = $user->code . '-' . $user->name;
                $qrCodeNameFile = $user->code;
                $qrCode->create($qrCodeValue, $qrCodeNameFile);
            }
        }

        return redirect()->route('admin-member')->with('success', 'Anggota baru telah dibuat');
    }

    public function profileMember($id)
    {
        $id_user = $id;
        $userModel = new User();
        $profile = $userModel->with(['village'])->where('id', $id_user)->first();
        $member  = $userModel->with(['village', 'reveral'])->where('user_id', $id_user)
            ->whereNotIn('id', [$id_user])
            ->whereNotNull('village_id')
            ->get();
        $referal_direct = $userModel->getReferalDirect($id_user);

        $referal_direct = $referal_direct->total == NULL ? 0 : $referal_direct->total; // referal langsung
        $referal_undirect = $userModel->getReferalUnDirect($id_user);
        $referal_undirect = $referal_undirect->total == NULL ? 0 : $referal_undirect->total; // referal tidak langsung
        $total_member = count($member);

        $gF = new GlobalProvider();
        return view('pages.admin.member.profile', compact('gF', 'profile', 'member', 'total_member', 'referal_direct', 'referal_undirect'));
    }

    public function editMember($id)
    {
        $id = decrypt($id);
        $profile = app('UserModel')->getProfile($id);
        return view('pages.admin.member.edit', compact('profile'));
    }

    public function updateMember(Request $request, $id)
    {

        DB::beginTransaction();
        try {
            $request->validate([
                'nik' => 'required'
            ]);

            #hitung panjang nik, harus 16
            $cekLengthNik = strlen($request->nik);
            if ($cekLengthNik <> 16) return redirect()->back()->with(['error' => 'NIK harus 16 angka, cek kembali NIK tersebut!']);

            $user = User::where('id', $id)->first();
            $oldNik = $user->nik;

            if ($request->photo != null || $request->ktp != null) {
                // delete foto lama
                $path = public_path();
                if ($request->photo != null) {
                    File::delete($path . '/storage/' . $user->photo);
                }
                if ($request->ktp != null) {
                    File::delete($path . '/storage/' . $user->ktp);
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
                    'ktp'          => $ktp,
                    'code' => $request->code
                ]);
            } else {
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
                    'code' => $request->code
                ]);
            }

            // update nik di tb org_diagram
            UpdateNikOrg::update($oldNik, $request->nik);
            DB::commit();
            return redirect()->route('admin-profile-member', ['id' => $id]);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin-profile-member', ['id' => $id])->with(['error' => $e->getMessage()]);
        }
    }

    public function downloadCard($id)
    {
        $gF = new GlobalProvider();

        $profile = User::with('village')->where('id', $id)->first();
        $pdf = PDF::LoadView('pages.card', compact('profile', 'gF'))->setPaper('a4');
        return $pdf->download('e-kta-' . $profile->name . '.pdf');
    }

    public function createAccount($id)
    {

        $user = User::select('id', 'name')->where('id', $id)->first();
        return view('pages.admin.member.create-account', compact('user'));
    }

    public function nonActiveAccount($id)
    {

        $user = User::select('id', 'name')->where('id', $id)->first();
        $categoryInactiveMember = CategoryInactiveMember::select('id', 'name')->where('name', '!=', 'Duplikat')->orderBy('name', 'asc')->get();

        return view('pages.admin.member.create-nonactiveaccount', compact('user', 'categoryInactiveMember'));
    }

    public function storeAccount(Request $request, $id)
    {
        $user = User::select('id', 'name')->where('id', $id)->first();

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
        $menu_default = Menu::select('id', 'name')->get();
        // karena menu dashboard itu ada di array pertama maka kita hapus,
        // karena saat mendaftar user tidak bisa mengakses menu dashboard jika bukan di jadikan admin oleh administrator
        unset($menu_default[0]);
        foreach ($menu_default as $val) {
            UserMenu::create([
                'user_id' => $user->id,
                'menu_id' => $val->id
            ]);
        }

        // send link verifikasi ke email terkait
        // Mail::to($request->email)->send(new RegisterMail($user)); // send email untuk verifikasi akun

        return redirect()->route('admin-member')->with(['success' => 'Akun untuk ' . $user->name . ' telah dibuat']);
    }

    public function storeAccountNonActive(Request $request, $id)
    {
        DB::beginTransaction();
        try {

            $user = User::where('id', $id)->first();
            $oldNik = $user->nik;

            #save ke tb tmp_spam_user
            TmpSpamUser::create([
                'user_id' => $user->user_id,
                'number'  => $user->number,
                'code'    => $user->code,
                'nik'     => $user->nik,
                'name'    => $user->name,
                'gender'  => $user->gender,
                'place_berth' => $user->place_berth,
                'date_berth'  => $user->date_berth,
                'blood_group' => $user->blood_group,
                'marital_status' => $user->marital_status,
                'job_id' => $user->job_id,
                'religion' => $user->religion,
                'education_id' => $user->education_id,
                'email' => $user->email,
                'email_verified_at' => $user->email_verified_at,
                'password' => $user->password,
                'address'  => $user->address,
                'village_id' => $user->village_id,
                'rt' => $user->rt,
                'rw' => $user->rw,
                'phone_number' => $user->phone_number,
                'whatsapp' => $user->whatsapp,
                'photo' => $user->photo,
                'ktp' => $user->ktp,
                'level' => $user->level,
                'cby' => $user->cby,
                'saved_nasdem' => $user->saved_nasdem,
                'activate_token' => $user->activate_token,
                'status' => $user->status,
                'remember_token' => $user->remember_token,
                'set_admin' => $user->set_admin,
                'category_inactive_member_id' => $request->category_inactive_member_id,
                'reason' => $request->reason,
                'created_at' => $user->created_at,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            #delete di tb users sebagai anggota
            $user->delete();

            DeleteNikOrg::delete($oldNik);

            DB::commit();

            return redirect()->route('admin-member')->with(['success' => 'Anggota telah dinonaktifkan!']);
        } catch (\Exception $e) {

            // return $e->getMessage();
            return redirect()->back()->with(['warning' => 'Anggota gagal dinonaktifkan!']);
        }
    }

    public function memberProvince($province_id)
    {
        $province = Province::select('name')->where('id', $province_id)->first();

        $member = User::with(['village.district.regency.province', 'reveral', 'create_by'])
            ->whereHas('village', function ($village) use ($province_id) {
                $village->whereHas('district', function ($district) use ($province_id) {
                    $district->whereHas('regency', function ($regency) use ($province_id) {
                        $regency->where('province_id', $province_id);
                    });
                });
            })
            ->whereNotNull('nik')
            ->get();

        if (request()->ajax()) {
            return DataTables::of($member)
                ->addIndexColumn()
                ->addColumn('photo', function ($item) {
                    return '
                        <a href="' . route('admin-profile-member', $item->id) . '">
                            <img  class="rounded" width="40" src="' . asset('storage/' . $item->photo) . '">
                            ' . $item->name . '
                        </a>
                        ';
                })
                ->addColumn('referal', function ($item) {
                    return $item->referal;
                })
                ->rawColumns(['action', 'photo', 'referal'])
                ->make(true);
        }
        return view('pages.admin.member.member-province', compact('province'));
    }

    public function memberRegency($regency_id)
    {
        $regency = Regency::select('name')->where('id', $regency_id)->first();
        $member = User::with(['village.district.regency.province', 'reveral', 'create_by'])
            ->whereHas('village', function ($village) use ($regency_id) {
                $village->whereHas('district', function ($district) use ($regency_id) {
                    $district->where('regency_id', $regency_id);
                });
            })
            ->whereNotNull('nik')
            ->get();

        if (request()->ajax()) {
            return DataTables::of($member)
                ->addIndexColumn()
                ->addColumn('photo', function ($item) {
                    return '
                        <a href="' . route('admin-profile-member', $item->id) . '">
                            <img  class="rounded" width="40" src="' . asset('storage/' . $item->photo) . '">
                            ' . $item->name . '
                        </a>
                        ';
                })
                ->addColumn('referal', function ($item) {
                    return $item->referal;
                })
                ->rawColumns(['action', 'photo', 'referal'])
                ->make(true);
        }
        return view('pages.admin.member.member-regency', compact('regency'));
    }

    public function memberDistrict($district_id)
    {
        $district = District::select('name')->where('id', $district_id)->first();
        $member = User::with(['village.district.regency.province', 'reveral', 'create_by'])
            ->whereHas('village', function ($village) use ($district_id) {
                $village->where('district_id', $district_id);
            })
            ->whereNotNull('nik')
            ->get();

        if (request()->ajax()) {
            return DataTables::of($member)
                ->addIndexColumn()
                ->addColumn('photo', function ($item) {
                    return '
                        <a href="' . route('admin-profile-member', $item->id) . '">
                            <img  class="rounded" width="40" src="' . asset('storage/' . $item->photo) . '">
                            ' . $item->name . '
                        </a>
                        ';
                })
                ->addColumn('referal', function ($item) {
                    return $item->referal;
                })
                ->rawColumns(['action', 'photo', 'referal'])
                ->make(true);
        }
        return view('pages.admin.member.member-district', compact('district'));
    }

    public function memberVillage($village_id)
    {
        $village = Village::select('name')->where('id', $village_id)->first();
        $member = User::with(['village.district.regency.province', 'reveral', 'create_by'])
            ->where('village_id', $village_id)
            ->whereNotNull('nik')
            ->get();

        if (request()->ajax()) {
            return DataTables::of($member)
                ->addIndexColumn()
                ->addColumn('photo', function ($item) {
                    return '
                        <a href="' . route('admin-profile-member', $item->id) . '">
                            <img  class="rounded" width="40" src="' . asset('storage/' . $item->photo) . '">
                            ' . $item->name . '
                        </a>
                        ';
                })
                ->addColumn('referal', function ($item) {
                    return $item->referal;
                })
                ->rawColumns(['action', 'photo', 'referal'])
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
        $pdf   = PDF::loadView('pages.admin.report.member-national-pdf', compact('member', 'title', 'no'))->setPaper('landscape');
        return $pdf->download($title . '.pdf');
    }

    public function reportMemberProvincePdf($province_id)
    {
        $province = Province::select('name')->where('id', $province_id)->first();
        $member = User::with(['village'])
            ->whereHas('village', function ($village) use ($province_id) {
                $village->whereHas('district', function ($district) use ($province_id) {
                    $district->whereHas('regency', function ($regency) use ($province_id) {
                        $regency->where('province_id', $province_id);
                    });
                });
            })
            ->whereNotNull('nik')
            ->orderBy('name',)
            ->get();
        $title = 'Anggota-Province-' . $province->name;
        $no = 1;
        $pdf   = PDF::loadView('pages.admin.report.member-province-pdf', compact('member', 'title', 'no', 'province'));
        return $pdf->download($title . '.pdf');
    }

    public function reportMemberRegencyPdf($regency_id)
    {
        $regency = Regency::select('name')->where('id', $regency_id)->first();
        $member = User::with(['village'])
            ->whereHas('village', function ($village) use ($regency_id) {
                $village->whereHas('district', function ($district) use ($regency_id) {
                    $district->where('regency_id', $regency_id);
                });
            })
            ->whereNotNull('nik')
            ->orderBy('name',)
            ->get();
        $title = 'Anggota-' . $regency->name;
        $no = 1;
        $pdf   = PDF::loadView('pages.admin.report.member-regency-pdf', compact('member', 'title', 'no', 'regency'));
        return $pdf->download($title . '.pdf');
    }

    public function reportMemberDistrictPdf($district_id)
    {
        $district = District::select('name')->where('id', $district_id)->first();
        $member = DB::table('users as a')
            ->select('a.id', 'a.cby', 'a.user_id', 'a.user_id', 'a.name', 'a.photo', 'a.rt', 'a.rw', 'a.phone_number', 'a.whatsapp', 'a.address', 'regencies.name as regency', 'districts.name as district', 'villages.name as village', 'provinces.name as province', 'a.created_at', 'a.status', 'a.email')
            ->join('villages', 'villages.id', 'a.village_id')
            ->join('districts', 'districts.id', 'villages.district_id')
            ->join('regencies', 'regencies.id', 'districts.regency_id')
            ->join('provinces', 'provinces.id', 'regencies.province_id')
            ->leftJoin('dapil_areas', 'districts.id', 'dapil_areas.district_id')
            ->whereNotNull('a.village_id')
            ->orderBy('villages.name', 'asc')
            ->orderBy('a.name', 'asc')
            ->where('districts.id', $district_id)->get();

        $result = [];
        $no = 1;
        $gF = new GlobalProvider();
        foreach ($member as $val) {
            $userModel = new User();
            $total_referal = $userModel->where('user_id', $val->id)->whereNotNull('village_id')->count();
            $inputer = $userModel->select('name')->where('id', $val->cby)->first();
            $referal = $userModel->select('name')->where('id', $val->user_id)->first();
            $by_inputer = $inputer->name;
            $by_referal = $referal->name;
            $result[] = [
                'no' => $no++,
                'name' => $val->name,
                'address' => $val->address,
                'rt' => $val->rt,
                'rw' => $val->rw,
                'village' => $val->village,
                'district' => $val->district,
                'regency' => $val->regency,
                'province' => $val->province,
                'phone_number'    => $val->phone_number,
                'whatsapp' => $val->whatsapp,
                'created_at' => date('d-m-Y', strtotime($val->created_at)),
                'by_inputer' => $by_inputer,
                'by_referal' => $by_referal,
                'total_referal' => $gF->decimalFormat($total_referal),
            ];
        }

        $title = 'Anggota-' . $district->name;
        $no = 1;
        $pdf   = PDF::loadView('pages.admin.report.member-district-pdf', compact('result', 'title', 'no', 'district'))->setPaper('a4', 'landscape');
        return $pdf->download($title . '.pdf');
    }

    public function reportMemberVillagePdf($village_id)
    {
        $village = Village::select('name')->where('id', $village_id)->first();
        $member = DB::table('users as a')
            ->select('a.id', 'a.cby', 'a.user_id', 'a.user_id', 'a.name', 'a.photo', 'a.rt', 'a.rw', 'a.phone_number', 'a.whatsapp', 'a.address', 'regencies.name as regency', 'districts.name as district', 'villages.name as village', 'provinces.name as province', 'a.created_at', 'a.status', 'a.email')
            ->join('villages', 'villages.id', 'a.village_id')
            ->join('districts', 'districts.id', 'villages.district_id')
            ->join('regencies', 'regencies.id', 'districts.regency_id')
            ->join('provinces', 'provinces.id', 'regencies.province_id')
            ->leftJoin('dapil_areas', 'districts.id', 'dapil_areas.district_id')
            ->whereNotNull('a.village_id')
            ->orderBy('villages.name', 'asc')
            ->orderBy('a.name', 'asc')
            ->where('villages.id', $village_id)->get();

        $result = [];
        $no = 1;
        $gF = new GlobalProvider();
        foreach ($member as $val) {
            $userModel = new User();
            $total_referal = $userModel->where('user_id', $val->id)->whereNotNull('village_id')->count();
            $inputer = $userModel->select('name')->where('id', $val->cby)->first();
            $referal = $userModel->select('name')->where('id', $val->user_id)->first();
            $by_inputer = $inputer->name;
            $by_referal = $referal->name;
            $result[] = [
                'no' => $no++,
                'name' => $val->name,
                'address' => $val->address,
                'rt' => $val->rt,
                'rw' => $val->rw,
                'village' => $val->village,
                'district' => $val->district,
                'regency' => $val->regency,
                'province' => $val->province,
                'phone_number'    => $val->phone_number,
                'whatsapp' => $val->whatsapp,
                'created_at' => date('d-m-Y', strtotime($val->created_at)),
                'by_inputer' => $by_inputer,
                'by_referal' => $by_referal,
                'total_referal' => $gF->decimalFormat($total_referal),
            ];
        }


        $title = 'Anggota-Desa-' . $village->name;
        $no = 1;
        $pdf   = PDF::loadView('pages.admin.report.member-village-pdf', compact('result', 'title', 'no', 'village'))->setPaper('a4', 'landscape');
        return $pdf->download($title . '.pdf');
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
        $user = $userModel->select('id', 'name')->where('id', $user_id)->first();
        $districtModel = new District();
        $districts = $districtModel->getDistrictByReferalMember($user_id);
        $totalMember = $districtModel->getTotalMemberByReferal($user_id);
        return view('pages.admin.member.member-by-refeal', compact('user', 'districts', 'userModel', 'totalMember'));
    }

    public function memberByInput($user_id)
    {
        $userModel = new User();
        $user = $userModel->select('id', 'name')->where('id', $user_id)->first();
        $districtModel = new District();
        $districts = $districtModel->getDistrictByInputMember($user_id);
        $totalMember = $districtModel->getTotalMemberByInput($user_id);
        return view('pages.admin.member.member-by-input', compact('user', 'districts', 'userModel', 'totalMember'));
    }

    public function memberByReferalNationalPDF()
    {
        $userModel = new User();
        $members = $userModel->getMemberReferal();
        $no = 1;
        $pdf = PDF::LoadView('pages.report.member-referal', compact('members', 'no', 'userModel'))->setPaper('a4');
        return  $pdf->download('ANGGOTA REFERAL TERBANYAK.pdf');
    }

    public function memberByReferalDownloadExcel($user_id, $district_id)
    {
        $member = User::select('name')->where('id', $user_id)->first();
        $district = District::select('name')->where('id', $district_id)->first();

        $type = request()->input('type');

        if ($type == 'input') {
            return $this->excel->download(new MemberByInputerInDistrict($district_id, $user_id), 'ANGGOTA-INPUT-DARI ' . $member->name . ' DI KECAMATAN ' . $district->name . '.xls');
        } else {
            return $this->excel->download(new MemberByReferalInDistrict($district_id, $user_id), 'ANGGOTA-REFERAL-DARI ' . $member->name . ' DI KECAMATAN ' . $district->name . '.xls');
        }
    }

    public function memberByReferalDownloadExcelAll($user_id)
    {
        $member = User::select('name')->where('id', $user_id)->first();
        $type = request()->input('type');

        if ($type == 'input') {
            return $this->excel->download(new MemberByInputerAll($user_id), 'ANGGOTA-INPUT-DARI-' . $member->name . '.xls');
        } else {
            return $this->excel->download(new MemberByReferalAll($user_id), 'ANGGOTA-REFERAL-DARI-' . $member->name . '.xls');
        }
    }

    public function memberByReferalDownloadPDF($user_id, $district_id)
    {
        $userModel = new User();
        $user = $userModel->select('name')->where('id', $user_id)->first();
        $district = District::select('name')->where('id', $district_id)->first();
        $no = 1;

        $type = request()->input('type');

        if ($type == 'input') {
            $members  = $userModel->getListMemberByInputerDistrictId($district_id, $user_id);
            $title_file    = 'LAPORAN ANGGOTA DARI ANGGOTA POTENSIAL INPUT ' . $user->name . ' DI KECAMATAN ' . $district->name . '.pdf';
            $title_header  = 'LAPORAN ANGGOTA DARI ANGGOTA POTENSIAL INPUT';
            $title_page    = 'INPUT DARI : ' . $user->name . ', KECAMATAN : ' . $district->name;
        } else {
            $members  = $userModel->getListMemberByDistrictId($district_id, $user_id);
            $title_file    = 'LAPORAN ANGGOTA DARI ANGGOTA POTENSIAL REFERAL ' . $user->name . ' DI KECAMATAN ' . $district->name . '.pdf';
            $title_header  = 'LAPORAN ANGGOTA DARI ANGGOTA POTENSIAL REFERAL';
            $title_page    = 'REFERAL : ' . $user->name . ', KECAMATAN :' . $district->name;
        }


        $gF = new GlobalProvider();
        $pdf = PDF::LoadView('pages.report.member-referal-in-district', compact('members', 'no', 'gF', 'title_page', 'title_header'))->setPaper('a4', 'landscape');
        return  $pdf->download($title_file);
    }

    public function memberByReferalAllDownloadPDF($user_id)
    {
        $userModel = new User();
        $user = $userModel->select('name')->where('id', $user_id)->first();
        $no = 1;

        $type = request()->input('type');

        if ($type == 'input') {
            $members  = $userModel->getListMemberByUserInputerAll($user_id);
            $title_file    = 'LAPORAN ANGGOTA DARI ANGGOTA POTENSIAL INPUT ' . $user->name . '.pdf';
            $title_header  = 'LAPORAN ANGGOTA DARI ANGGOTA POTENSIAL INPUT';
            $title_page    = 'INPUT DARI : ' . $user->name;
        } else {
            $members  = $userModel->getListMemberByUserAll($user_id);
            $title_file    = 'LAPORAN ANGGOTA DARI ANGGOTA POTENSIAL REFERAL ' . $user->name . '.pdf';
            $title_header  = 'LAPORAN ANGGOTA DARI ANGGOTA POTENSIAL REFERAL';
            $title_page    = 'REFERAL : ' . $user->name;
        }
        $gF = new GlobalProvider();
        $pdf = PDF::LoadView('pages.report.member-referal-all', compact('members', 'no', 'gF', 'title_page', 'title_header'))->setPaper('a4', 'landscape');
        return  $pdf->download($title_file);
    }

    public function memberPotentialReferalDownloadExcel()
    {
        return $this->excel->download(new MemberPotentialReferal(), 'ANGGOTA POTENSIAL REFERAL.xls');
    }

    public function memberPotentialInputDownloadExcel()
    {
        return $this->excel->download(new MemberPotentialInput(), 'ANGGOTA POTENSIAL INPUT.xls');
    }

    public function memberPotentialReferalDownloadExcelUpper(Request $request)
    {
        if ($request->order) {
            $kecamatan = DB::select("SELECT  d.id, d.name, COUNT(a.user_id) as total FROM users as a
                                    join users as b on a.id = b.user_id
                                    join villages as c on a.village_id = c.id 
                                    join districts as d on c.district_id = d.id 
                                    join regencies as e on d.regency_id = e.id
                                    join provinces as f on e.province_id = f.id
                                    group by d.id, d.name
                                    order by COUNT(a.user_id) desc");
            return $kecamatan;
        } else {
            #get kecamatan yang ada referalnya
            $district = $request->district;
            $kecamatan = DB::table('districts')->select('name')->where('id', $district)->first();
            return  $this->excel->download(new MemberPotensialReferalByDistrict($district), 'ANGGOTA POTENSIAL REFERAL ' . $kecamatan->name . '.xls');
        }
    }

    public function getKecamatanReferalUpper(Request $request)
    {

        return  $this->excel->download(new MemberPotensialUpperByDistrictUpper($request->upper), 'JUMLAH ANGGOTA POTENSIAL REFERAL KECAMATAN DI ATAS ' . $request->upper . '.xls');
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
            $inputer = $userModel->select('name')->where('id', $val->cby)->first();
            $referal = $userModel->select('name')->where('id', $val->user_id)->first();
            $by_inputer = $inputer->name;
            $by_referal = $referal->name;

            $data[] = [
                'name' => $val->name,
                'referal' => $val->total,
                'referal_undirect' => $total_referal_undirect,
                'address' => $val->address,
                'rt' => $val->rt,
                'rw' => $val->rw,
                'village' => $val->village,
                'district' => $val->district,
                'regency' => $val->regency,
                'province' => $val->province,
                'phone_number' => $val->phone_number,
                'whatsapp' => $val->whatsapp,
                'created_at' => date('d-m-Y', strtotime($val->created_at)),
                'by_inputer' => $by_inputer,
                'by_referal' => $by_referal,
            ];
        }
        $gF = new GlobalProvider();
        $no  = 1;
        $pdf = PDF::LoadView('pages.report.member-potential-referal', compact('data', 'no', 'gF'))->setPaper('a4', 'landscape');
        return  $pdf->download('ANGGOTA POTENSIAL REFERAL.pdf');
    }

    public function memberPotentialInputDownloadPDF()
    {
        $gF = new GlobalProvider();
        $userModel = new User();
        $member = $userModel->getMemberInput();
        $no  = 1;
        $data = [];
        foreach ($member as $val) {
            $userModel = new User();
            $id_user = $val->id;
            $inputer = $userModel->select('name')->where('id', $val->cby)->first();
            $referal = $userModel->select('name')->where('id', $val->user_id)->first();
            $by_inputer = $inputer->name;
            $by_referal = $referal->name;

            $data[] = [
                'name' => $val->name,
                'total' => $gF->decimalFormat($val->total),
                'address' => $val->address,
                'rt' => $val->rt,
                'rw' => $val->rw,
                'village' => $val->village,
                'district' => $val->district,
                'regency' => $val->regency,
                'province' => $val->province,
                'phone_number' => $val->phone_number,
                'created_at' => date('d-m-Y', strtotime($val->created_at)),
                'by_inputer' => $by_inputer,
                'by_referal' => $by_referal,
                'whatsapp' => $val->whatsapp,
            ];
        }
        $pdf = PDF::LoadView('pages.report.member-potential-input', compact('data', 'no', 'gF'))->setPaper('a4', 'landscape');
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
            ->select('a.nik', 'a.id', 'a.user_id', 'a.name', 'a.photo', 'a.rt', 'a.rw', 'a.phone_number', 'a.whatsapp', 'a.address', 'regencies.name as regency', 'districts.name as district', 'villages.name as village', 'b.name as referal', 'c.name as cby', 'a.created_at', 'a.status', 'a.email')
            ->join('villages', 'villages.id', 'a.village_id')
            ->join('districts', 'districts.id', 'villages.district_id')
            ->join('regencies', 'regencies.id', 'districts.regency_id')
            ->join('users as b', 'b.id', 'a.user_id')
            ->join('users as c', 'c.id', 'a.cby')
            ->leftJoin('dapil_areas', 'districts.id', 'dapil_areas.district_id')
            ->whereNotNull('a.village_id')
            ->orderBy('villages.name', 'asc')
            ->orderBy('a.name', 'asc');

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
            $data->where('dapil_areas.dapil_id', $dapil);
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
            return $this->excel->download(new MemberExport($data), 'LAPORAN ANGGOTA ' . $title . '.xls');
        } else {
            $gF = new GlobalProvider();
            $result = [];
            $no = 1;
            foreach ($data as $val) {
                $total_referal = User::where('user_id', $val->id)->whereNotNull('village_id')->count();
                $result[] = [
                    'no' => $no++,
                    'nik' => $val->nik,
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
            $pdf = PDF::LoadView('pages.admin.report.member-byregional', compact('result', 'no', 'gF', 'title'))->setPaper('f4', 'landscape');
            return  $pdf->download('LAPORAN ANGGOTA ' . $title . '.pdf');
        }
    }

    public function spesialBonusReferal()
    {
        return view('pages.admin.reward.special');
    }

    public function spesialBonusAdmin()
    {
        return view('pages.admin.reward.special-admin');
    }

    public function dataSpesialBonusReferal()
    {
        $userModel  = new User();
        $referal    = $userModel->getMemberReferal();

        $gf         = new GlobalProvider();

        $referals   = [];
        foreach ($referal as $value) {

            $bonus = $gf->calculateSpecialBonusReferal($value->total);

            if ($bonus > 0 and $value->id != 35) {
                $referals[] = [
                    'id' => $value->id,
                    'name' => $value->name,
                    'photo' => $value->photo,
                    'address' => $value->address,
                    'village' => $value->village,
                    'district' => $value->district,
                    'total_referal' => $gf->decimalFormat($value->total),
                    'total' => $value->total,
                    'bonus' => $gf->decimalFormat($bonus)
                ];
            }
        }

        if (request()->ajax()) {
            return DataTables::of($referals)
                ->addIndexColumn()
                ->addColumn('photo', function ($item) {
                    return '
                        <a href="' . route('admin-profile-member', $item['id']) . '">
                            <img  class="rounded" width="40" src="' . asset('storage/' . $item['photo']) . '">
                            ' . $item['name'] . '
                        </a>
                        ';
                })
                ->addColumn('fullAdress', function ($item) {
                    return '' . $item['address'] . ', DS.' . $item['village'] . ', KEC.' . $item['district'];
                })
                ->addColumn('totalReferal', function ($item) {
                    return '<span class="badge bg-success text-light">' . $item['total_referal'] . '</span>';
                })
                ->addColumn('nominalBonus', function ($item) {
                    return '<span class="badge bg-success text-light">Rp ' . $item['bonus'] . '</span>';
                })
                ->rawColumns(['photo', 'fullAdress', 'totalReferal', 'nominalBonus'])
                ->make(true);
        }
    }

    public function dataSpesialBonusAdmin()
    {
        $adminModel = new Admin();
        $admin      = $adminModel->getAdmins();
        $gf         = new GlobalProvider();


        $admins   = [];
        foreach ($admin  as $value) {
            $bonus    = $gf->calculateSpecialBonusAdmin($value->total_data);
            if ($bonus > 0 && $value->set_admin == 'Y') {
                $village  = Village::with('district')->where('id', $value->village_id)->first();
                $admins[] = [
                    'id' => $value->user_id,
                    'name' => $value->name,
                    'photo' => $value->photo,
                    'address' => $value->address,
                    'village' => $village->name,
                    'district' => $village->district->name,
                    'total_data' => $gf->decimalFormat($value->total_data),
                    'total' => $value->total_data,
                    'bonus' => $gf->decimalFormat($bonus),

                ];
            }
        }


        if (request()->ajax()) {
            return DataTables::of($admins)
                ->addIndexColumn()
                ->addColumn('photo', function ($item) {
                    return '
                        <a href="' . route('admin-profile-member', $item['id']) . '">
                            <img  class="rounded" width="40" src="' . asset('storage/' . $item['photo']) . '">
                            ' . $item['name'] . '
                        </a>
                        ';
                })
                ->addColumn('fullAdress', function ($item) {
                    return '' . $item['address'] . ', DS.' . $item['village'] . ', KEC.' . $item['district'];
                })
                ->addColumn('totalReferal', function ($item) {
                    return '<span class="badge bg-success text-light">' . $item['total_data'] . '</span>';
                })
                ->addColumn('nominalBonus', function ($item) {
                    return '<span class="badge bg-success text-light">Rp ' . $item['bonus'] . '</span>';
                })
                ->rawColumns(['photo', 'fullAdress', 'totalReferal', 'nominalBonus'])
                ->make(true);
        }
    }

    public function spesialBonusReportReferal()
    {
        $userModel = new User();
        $referal    = $userModel->getMemberReferal();

        $gf        = new GlobalProvider();

        $referals    = [];
        foreach ($referal as $value) {

            $bonus = $gf->calculateSpecialBonusReferal($value->total);

            if ($bonus > 0 and $value->id != 35) {
                $referals[] = [
                    'name' => $value->name,
                    'photo' => $value->photo,
                    'address' => $value->address,
                    'village' => $value->village,
                    'district' => $value->district,
                    'total_referal' => $gf->decimalFormat($value->total),
                    'bonus' => $gf->decimalFormat($bonus),
                    'total_bonus' => $bonus
                ];
            }
        }

        $count_total_bonus = collect($referals)->sum(function ($q) {
            return $q['total_bonus'];
        });

        $count_total_bonus = $gf->decimalFormat($count_total_bonus);

        $no = 1;
        $title = 'LAPORAN PENERIMA BONUS KHUSUS REFERAL';
        $pdf   = PDF::loadView('pages.admin.report.member-bonus-khusus-pdf', compact('referals', 'title', 'no', 'count_total_bonus'))->setPaper('landscape');
        return $pdf->download($title . '.pdf');
    }

    public function spesialBonusReportAdmin()
    {
        $gf        = new GlobalProvider();


        $adminModel = new Admin();
        $admin    = $adminModel->getAdmins();

        $admins   = [];
        foreach ($admin  as $value) {
            $bonus    = $gf->calculateSpecialBonusAdmin($value->total_data);
            if ($bonus > 0 && $value->set_admin == 'Y') {
                $village  = Village::with('district')->where('id', $value->village_id)->first();
                $admins[] = [
                    'name' => $value->name,
                    'photo' => $value->photo,
                    'address' => $value->address,
                    'village' => $village->name,
                    'district' => $village->district->name,
                    'total_data' => $gf->decimalFormat($value->total_data),
                    'bonus' => $gf->decimalFormat($bonus),
                    'total_bonus' => $bonus,
                ];
            }
        }

        $count_total_bonus = collect($admins)->sum(function ($q) {
            return $q['total_bonus'];
        });

        $count_total_bonus = $gf->decimalFormat($count_total_bonus);

        $no = 1;
        $title = 'LAPORAN PENERIMA BONUS KHUSUS ADMIN';
        $pdf   = PDF::loadView('pages.admin.report.member-bonus-khusus-admin-pdf', compact('admins', 'title', 'no', 'count_total_bonus'))->setPaper('landscape');
        return $pdf->download($title . '.pdf');
    }

    public function spamMember(Request $request)
    {

        DB::beginTransaction();
        try {

            #jika duplikat
            #get user origiall by nik di tb users
            if ($request->niks != null) {

                $originaluser = User::select('nik')->where('nik', $request->niks)->first();

                if (!$originaluser) return redirect()->back()->with(['warning' => 'NIk tidak ditemukan!']);

                $category_inactive_member = 5;
                #save ke tmp  users beserta alasan
                $user = User::where('id', $request->id)->first();
                $this->setStoreSpamMember($user, $originaluser, $request, $category_inactive_member);

                #delete di tb users sebagai anggota
                DeleteNikOrg::delete($user->nik);
                $user->delete();
            } else {

                #save ke tmp  users beserta alasan
                $user = User::where('id', $request->id)->first();
                $this->setStoreSpamMember($user, null, $request, null);

                #delete di tb users sebagai anggota
                DeleteNikOrg::delete($user->nik);
                $user->delete();
            }


            DB::commit();
            return redirect()->back()->with(['success' => 'Anggota disimpan sebagai spam!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }

    public function setStoreSpamMember($user, $originaluser, $request, $category_inactive_member)
    {

        #save ke tb tmp_spam_user
        return TmpSpamUser::create([
            'user_id' => $user->user_id,
            'original_nik' => $originaluser->nik ?? null,
            'number'  => $user->number,
            'code'    => $user->code,
            'nik'     => $user->nik,
            'name'    => $user->name,
            'gender'  => $user->gender,
            'place_berth' => $user->place_berth,
            'date_berth'  => $user->date_berth,
            'blood_group' => $user->blood_group,
            'marital_status' => $user->marital_status,
            'job_id' => $user->job_id,
            'religion' => $user->religion,
            'education_id' => $user->education_id,
            'email' => $user->email,
            'email_verified_at' => $user->email_verified_at,
            'password' => $user->password,
            'address'  => $user->address,
            'village_id' => $user->village_id,
            'rt' => $user->rt,
            'rw' => $user->rw,
            'phone_number' => $user->phone_number,
            'whatsapp' => $user->whatsapp,
            'photo' => $user->photo,
            'ktp' => $user->ktp,
            'level' => $user->level,
            'cby' => $user->cby,
            'saved_nasdem' => $user->saved_nasdem,
            'activate_token' => $user->activate_token,
            'status' => $user->status,
            'remember_token' => $user->remember_token,
            'set_admin' => $user->set_admin,
            'category_inactive_member_id' => $category_inactive_member ?? null,
            'reason' => $request->reason,
            'created_at' => $user->created_at,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }
}
