<?php

namespace App\Http\Controllers\Admin;

use App\DetailFamilyGroup;
use App\Exports\AnggotaBelumTercoverKortps;
use App\Exports\KorteExportWithSheet;
use App\Sticker;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\OrgDiagram;
use App\KoordinatorTpsKorte;
use App\Models\Province;
use App\Models\Regency;
use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Auth\RegisterController;
use App\User;
use Illuminate\Support\Facades\Validator;
use App\Providers\GlobalProvider;
use Maatwebsite\Excel\Excel;
use App\Exports\KorDesExport;
use App\Exports\KorCamExport;
use App\Exports\KorteExport;
use App\Exports\KorteMembersExport;
use App\FamilyGroup;
use App\Models\District;
use App\Models\Village;
use App\Providers\QrCodeProvider;
use App\Providers\StrRandom;
use PDF;
use Zipper;
use File;
use Illuminate\Support\Facades\Log;
use App\Imports\FormManualImport;
use App\Imports\FormKortpsImport;
use Maatwebsite\Excel\Facades\Excel as Excels;

class OrgDiagramController extends Controller
{
    public $excel;
    public function __construct(Excel $excel)
    {
        $this->excel = $excel;
    }

    public function index()
    {

        $provinceModel = new Province();
        $province = $provinceModel->getDataProvince();

        return view('pages.admin.strukturorg.index', compact('province'));
    }

    public function show()
    {

        $org_diagram = OrgDiagram::select('id', 'idx', 'parent', 'title', 'name', 'image', 'user_id', 'base', 'regency_id', 'dapil_id', 'district_id', 'village_id')
            ->orderBy('idx', 'asc')->get();

        return response()->json([
            'data' => $org_diagram
        ]);
    }

    public function getDataCoverKorTps()
    {

        $dapil_id   = request()->dapil;
        $district_id = request()->district;
        $village_id = request()->village;
        $rt         = request()->rt;

        $orgDiagram = new OrgDiagram();

        $results = '';
        if(isset($dapil_id) && !isset($district_id) && !isset($village_id) && !isset($rt)){

            $results = $orgDiagram->getKalkulasiTercoverDapil($dapil_id);

        }elseif(isset($dapil_id) && isset($district_id) && !isset($village_id) && !isset($rt)){

            $results = $orgDiagram->getKalkulasiTercoverDistrict($district_id);

        }elseif(isset($dapil_id) && isset($district_id) && isset($village_id) && !isset($rt)){

            $results = $orgDiagram->getKalkulasiTercoverVillage($village_id);

        }elseif(isset($dapil_id) && isset($district_id) && isset($village_id) && isset($rt)){

            $results = $orgDiagram->getKalkulasiTercoverRt($village_id, $rt);
            
        }else{

            $results = $orgDiagram->getKalkulasiTercoverAll();
        }

        return response()->json([
            'data' => $results
        ]);
    }

    public function storeOrg(Request $request)
    {

        DB::beginTransaction();

        try {

            $validator =  Validator::make($request->all(), [
                'nik' => 'required|numeric',
                'title' => 'required|string',
            ]);

            if ($validator->fails()) {
                return ResponseFormatter::error([
                    'message' => 'Lengkapi data, wajib diisi!',
                ], 401);
            }

            #request data
            $nik = $request->nik;
            $regency_id = $request->regency;
            $dapil_id   = $request->dapil;
            $district_id = $request->district;
            $village_id = $request->village;
            $parent     = $request->parent;
            $title      = $request->title;
            $base       = $request->base;

            #cek ketersediaan nik
            $RegisterController = new RegisterController();
            $result_cek_nik = $RegisterController->nik($request);

            if ($result_cek_nik === 'Available') {
                return ResponseFormatter::error([
                    'message' => 'NIK tidak tersedia'
                ], 204);
            } else {

                #get user_id by nik
                $user = User::select('id', 'name', 'photo')->where('nik', $nik)->first();

                #create idx 
                $orgDiagram = OrgDiagram::where('regency_id', $regency_id)
                    ->where('dapil_id', $dapil_id)
                    ->where('district_id', $district_id)
                    ->where('village_id', $village_id)
                    ->max('idx');

                $idx = $orgDiagram + 1;

                #save
                OrgDiagram::create([
                    'idx' => $idx,
                    'regency_id' => $regency_id,
                    'dapil_id' => $dapil_id,
                    'district_id' => $district_id,
                    'village_id' => $village_id,
                    'parent' => $parent,
                    'title' => $title,
                    'user_id' => $user->id,
                    'name' => $user->name,
                    'image' => $user->photo,
                    'base' => $base
                ]);

                DB::commit();
                return ResponseFormatter::success([
                    'message' => 'Berhasil tambah struktur!'
                ], 200);
            }
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            return ResponseFormatter::error([
                'message' => 'Somethin when wrong!',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function updateOrg()
    {

        DB::beginTransaction();
        try {

            $idx   = request()->idx;
            $title = request()->title;
            $id    = request()->id;

            #update org
            $org = OrgDiagram::where('id', $id)->first();
            $org->update(
                [
                    'title' => $title,
                    'idx' => $idx
                ]
            );

            DB::commit();
            return ResponseFormatter::success([
                'message' => 'Berhasil update struktur!'
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            return ResponseFormatter::error([
                'message' => 'Something when wrong!',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function deleteOrg()
    {

        DB::beginTransaction();
        try {

            $id    = request()->id;

            #update org
            $org = OrgDiagram::where('id', $id)->delete();

            DB::commit();
            return ResponseFormatter::success([
                'message' => 'Berhasil hapus struktur!'
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            return ResponseFormatter::error([
                'message' => 'Something when wrong!',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function orgDiagramTest()
    {

        $regency = Regency::select('id', 'name')->where('id', 3602)->first();

        return view('pages.admin.strukturorg.index', ['regency' => $regency]);
    }

    public function getDataOrgDiagramVillage()
    {

        $village_id = request('village');
        $orgs = DB::table('org_diagram_village as a')
            ->select('a.idx', 'a.pidx', 'a.color', 'a.title', 'a.nik', 'a.name', 'a.photo')
            ->join('users as b', 'a.nik', '=', 'b.nik')
            ->whereNotNull('a.pidx')
            ->where('a.village_id', $village_id)
            ->orderBy('a.level_org', 'asc')
            ->get();

        $data = [];
        foreach ($orgs as $value) {
            $data[] = [$value->pidx, $value->idx];
        }

        $nodes = [];
        foreach ($orgs as $value) {
            if ($value->photo) {
                # code...
                $nodes[] = [
                    'id' => $value->idx,
                    'title' => $value->title ?? $value->name,
                    'name' => $value->name,
                    'color' => $value->color ?? '',
                    'image' => '/storage/' . $value->photo ?? '',
                ];
            } else {
                $nodes[] = [
                    'id' => $value->idx,
                    'title' => $value->title ?? $value->name,
                    'name' => $value->name,
                    'color' => $value->color ?? '',
                ];
            }
        }

        $results = [
            'nodes' => $nodes,
            'data' => $data,
        ];

        return response()->json($results);
    }

    public function getDataOrgDiagramRT()
    {

        $village_id   = request('village');

        $orgs = DB::table('org_diagram_rt')
            ->select('idx', 'pidx', 'title', 'nik', 'name', 'photo', 'rt')
            ->whereNotNull('pidx')
            ->where('base', 'KORRT')
            ->where('nik', '!=', null)
            ->where('village_id', $village_id)
            ->orderBy('rt', 'asc')->get();

        $results = [];
        foreach ($orgs as $value) {
            $child = DB::table('org_diagram_rt')
                ->select('idx', 'pidx', 'title', 'name', 'photo')->whereNotNull('pidx')->where('base', 'ANGGOTA')->where('pidx', $value->idx)->get();
            $count_child = count($child);
            $results[] = [
                'idx' => $value->idx,
                'pidx' => $value->pidx,
                'name' => $value->name,
                'photo' => $value->photo,
                'rt' => $value->rt,
                'child_org' => $child,
                'count' => $count_child
            ];
        }


        return response()->json($results);

        // // $rt           = request('rt');
        // $village_id   = request('village');
        // $orgs = DB::table('org_diagram_rt')
        //         ->select('idx','pidx','color','title','nik','name','photo')
        //         ->whereNotNull('pidx')
        //         ->where('village_id', $village_id);

        // // if ($rt != '') {
        // //     $orgs = $orgs->where('rt', $rt);
        // // }

        // $orgs = $orgs->orderBy('idx','asc')->get();

        // $data = [];
        // foreach ($orgs as $value) {
        //     $data[] = [$value->pidx,$value->idx];
        // }

        // $nodes = [];
        // foreach ($orgs as $value) {
        //     if ($value->photo) {
        //         # code...
        //         $nodes[] = [
        //             'id' => $value->idx,
        //             'title' => $value->title ?? $value->name,
        //             'name' => $value->name,
        //             'color' => $value->color ?? '',
        //             'image' => '/storage/'.$value->photo ?? '',
        //         ];
        //     }else{
        //         $nodes[] = [
        //             'id' => $value->idx,
        //             'title' => $value->title ?? $value->name,
        //             'name' => $value->name,
        //             'color' => $value->color ?? '',
        //         ];
        //     }
        // }

        // $results = [
        //     'nodes' => $nodes,
        //     'data' => $data,
        // ];

        // return response()->json($results);
    }

    public function getDataOrgDiagramRTMemberNew()
    {

        $rt           = request('rt');
        $village_id   = request('village');

        $orgs = DB::table('org_diagram_rt')
            ->select('idx', 'pidx', 'title', 'nik', 'name', 'photo')
            ->whereNotNull('pidx')
            ->where('base', 'KORRT')
            ->where('village_id', $village_id)->where('rt', $rt)
            ->orderBy('name', 'asc')->get();

        $results = [];
        foreach ($orgs as $value) {
            $child = DB::table('org_diagram_rt')
                ->select('idx', 'pidx', 'title', 'name', 'photo')->whereNotNull('pidx')->where('base', 'ANGGOTA')->where('pidx', $value->idx)->get();
            $count_child = count($child);

            $results[] = [
                'idx' => $value->idx,
                'pidx' => $value->pidx,
                'name' => $value->name,
                'photo' => $value->photo,
                'child_org' => $child,
                'count' =>  $count_child
            ];
        }


        return response()->json($results);
    }

    public function getDataOrgDiagramDistrict()
    {

        $district_id = request('district');
        $orgs = DB::table('org_diagram_district as a')
            ->select('a.idx', 'a.pidx', 'a.color', 'a.title', 'a.nik', 'b.name', 'b.photo', 'a.id')
            ->join('users as b', 'a.nik', '=', 'b.nik')
            ->whereNotNull('a.pidx')
            ->where('a.district_id', $district_id)
            ->orderBy('a.level_org', 'asc')
            ->get();

        $data = [];
        foreach ($orgs as $value) {
            $data[] = [$value->pidx, $value->idx];
        }

        $nodes = [];
        foreach ($orgs as $value) {
            if ($value->photo) {
                # code...
                $nodes[] = [
                    'id' => $value->idx,
                    'title' => $value->title ?? $value->name,
                    'name' => $value->name,
                    'color' => $value->color ?? '',
                    'image' => '/storage/' . $value->photo ?? '',
                ];
            } else {
                $nodes[] = [
                    'id' => $value->idx,
                    'title' => $value->title ?? $value->name,
                    'name' => $value->name,
                    'color' => $value->color ?? '',
                ];
            }
        }

        $results = [
            'nodes' => $nodes,
            'data' => $data,
        ];

        return response()->json($results);
    }

    public function getDataOrgDiagramDapil()
    {

        $dapil_id = request('dapil');
        $orgs = DB::table('org_diagram_dapil')
            ->select('idx', 'pidx', 'color', 'title', 'nik', 'name', 'photo')
            ->whereNotNull('pidx')
            ->where('dapil_id', $dapil_id)
            ->get();

        $data = [];
        foreach ($orgs as $value) {
            $data[] = [$value->pidx, $value->idx];
        }

        $nodes = [];
        foreach ($orgs as $value) {
            if ($value->photo) {
                # code...
                $nodes[] = [
                    'id' => $value->idx,
                    'title' => $value->title ?? $value->name,
                    'name' => $value->name,
                    'color' => $value->color ?? '',
                    'image' => '/storage/' . $value->photo ?? '',
                ];
            } else {
                $nodes[] = [
                    'id' => $value->idx,
                    'title' => $value->title ?? $value->name,
                    'name' => $value->name,
                    'color' => $value->color ?? '',
                ];
            }
        }

        $results = [
            'nodes' => $nodes,
            'data' => $data,
        ];

        return response()->json($results);
    }

    public function getDataOrgDiagramPusat()
    {

        $orgs = DB::table('org_diagram_pusat')
            ->select('idx', 'pidx', 'color', 'title', 'nik', 'name', 'photo')
            ->whereNotNull('pidx')
            ->get();

        $data = [];
        foreach ($orgs as $value) {
            $data[] = [$value->pidx, $value->idx];
        }

        $nodes = [];
        foreach ($orgs as $value) {

            if ($value->photo) {
                # code...
                $nodes[] = [
                    'id' => $value->idx,
                    'title' => $value->title ?? $value->name,
                    'name' => $value->name,
                    'color' => $value->color ?? '',
                    'image' => '/storage/' . $value->photo ?? '',
                ];
            } else {
                $nodes[] = [
                    'id' => $value->idx,
                    'title' => $value->title ?? $value->name,
                    'name' => $value->name,
                    'color' => $value->color ?? '',
                ];
            }
        }

        $results = [
            'nodes' => $nodes,
            'data' => $data,
        ];

        return response()->json($results);
    }

    public function indexOrgVillage()
    {

        $regency = Regency::select('id', 'name')->where('id', 3602)->first();

        $authAdminDistrict = auth()->guard('admin')->user()->district_id;
        $district  = District::select('name','id')->where('id', $authAdminDistrict)->first();
        $villages  = Village::select('id','name')->where('district_id', $district->id)->get();
        
        // get dapil berdasarkan kecamatan admin
        // $rt        = 30;

        return view('pages.admin.strukturorg.village.index', compact('regency','district','villages'));
    }

    public function getDataOrgVillage(Request $request)
    {

        // DATATABLE
        $orderBy = 'a.name';
        switch ($request->input('order.0.column')) {
            case '1':
                $orderBy = 'a.name';
                break;
                // case '3':
                //     $orderBy = 'a.rt';
                //     break;
                // case '3':
                //     $orderBy = 'districts.name';
                //     break;
                // case '4':
                //     $orderBy = 'villages.name';
                //     break;
                // case '5':
                //     $orderBy = 'b.name';
                //     break;
                // case '6':
                //     $orderBy = 'c.name';
                //     break;
                // case '7':
                //     $orderBy = 'a.created_at';
                //     break;
        }

        $data = DB::table('org_diagram_village as a')
            ->select('a.id', 'a.idx', 'a.village_id', 'a.rt', 'a.rw', 'b.address', 'a.title', 'a.nik', 'b.name', 'b.photo', 'a.telp as phone_number', 'c.name as village', 'd.name as district')
            ->join('users as b', 'b.nik', '=', 'a.nik')
            ->join('villages as c', 'c.id', '=', 'a.village_id')
            ->join('districts as d', 'd.id', '=', 'a.district_id')
            ->where('a.district_id', $request->district);


        if ($request->input('search.value') != null) {
            $data = $data->where(function ($q) use ($request) {
                $q->whereRaw('LOWER(a.name) like ? ', ['%' . strtolower($request->input('search.value')) . '%']);
                // $q->whereRaw('LOWER(a.rt) like ? ',['%'.strtolower($request->input('search.value')).'%']);
                // ->orWhereRaw('LOWER(regencies.name) like ? ',['%'.strtolower($request->input('search.value')).'%'])
                // ->orWhereRaw('LOWER(districts.name) like ? ',['%'.strtolower($request->input('search.value')).'%'])
                // ->orWhereRaw('LOWER(villages.name) like ? ',['%'.strtolower($request->input('search.value')).'%'])
                // ->orWhereRaw('LOWER(b.name) like ? ',['%'.strtolower($request->input('search.value')).'%'])
                // ->orWhereRaw('LOWER(c.name) like ? ',['%'.strtolower($request->input('search.value')).'%'])
                // ->orWhereRaw('LOWER(a.created_at) like ? ',['%'.strtolower($request->input('search.value')).'%'])

            });
        }

        if ($request->input('village') != null) {
            $data->where('a.village_id', $request->village);
        }

        if ($request->input('rt') != null) {
            $data->where('a.rt', $request->rt);
        }

        $recordsFiltered = $data->get()->count();
        if ($request->input('length') != -1) $data = $data->skip($request->input('start'))->take($request->input('length'));
        $data = $data->orderBy('a.level_org', 'asc');
        $data = $data->orderBy($orderBy, $request->input('order.0.dir'))->get();

        $recordsTotal = $data->count();

        return response()->json([
            'draw' => $request->input('draw'),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data
        ]);
    }

    public function setSaveOrgVillage()
    {

        DB::beginTransaction();
        try {

            $idx         = request()->id;
            $nik         = request()->nik;
            $villageId   = request()->regionId;
            $title       = request()->title;

            #cek nik di tb users, apakah sudah terdaftar
            $cek_user       =  DB::table('users')->select('photo', 'name')->where('nik', $nik);
            $cek_count_user = $cek_user->count();

            if ($cek_count_user < 1) {

                return ResponseFormatter::error([
                    'message' => 'NIK tidak tersedia di sistem!',
                ]);
            } else {

                $org         = DB::table('org_diagram_village');

                #cek apakah nik tersebut sudah ada di tb org_diagram_village
                $user        =  $org->where('nik', $nik)->count();

                if ($user > 0) {

                    return ResponseFormatter::error([
                        'message' => 'NIK sudah terdaftar distruktur!',
                    ]);
                } else {

                    #membuat idx
                    #hitung data terakhir dari $idx, lalu di tambah 1 : XXXX.KORRT.1.x
                    $data       = DB::table('org_diagram_village')->where('pidx', $idx)->where('village_id', $villageId)->max('idx');
                    $data_count = DB::table('org_diagram_village')->where('pidx', $idx)->where('village_id', $villageId)->count('idx');

                    #jika belum ada
                    if (!$data) {

                        #buat idx baru
                        $new_idx = $data_count + 1;
                        $new_idx = $idx . "." . $new_idx;
                    } else {

                        #hitung jumlah data terakhir dari $idx, lalu di tambah 1 : XXXX.KORRT.1.x
                        $new_idx    = $data_count + 1;
                        $new_idx    = $idx . "." . $new_idx;
                    }

                    $user = $cek_user->first();


                    #get id domisili by villageId
                    $domisili = DB::table('villages as a')
                        ->join('districts as b', 'b.id', '=', 'a.district_id')
                        ->select('a.id as village_id', 'b.id as district_id', 'b.regency_id')
                        ->where('a.id', $villageId)
                        ->first();

                    #save data org village
                    $save_org  = $org->insert([
                        'idx'    => $new_idx,
                        'pidx'   => $idx,
                        'title'  => $title,
                        'nik'    => $nik,
                        'name'   => $user->name,
                        'base'   => 'KORRT',
                        'photo'  => $user->photo ?? '',
                        'regency_id'  => $domisili->regency_id,
                        'district_id' => $domisili->district_id,
                        'village_id'  => $domisili->village_id,
                        'created_by' => auth()->guard('admin')->user()->id,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);

                    DB::commit();
                    return ResponseFormatter::success([
                        'message' => 'Berhasil tambah struktur!'
                    ], 200);
                }
            }
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            return ResponseFormatter::error([
                'message' => 'Something when wrong!',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function setSaveOrgDistrict()
    {

        DB::beginTransaction();
        try {

            $idx         = request()->id;
            $nik         = request()->nik;
            $districtId   = request()->regionId;
            $title       = request()->title;

            #cek nik di tb users, apakah sudah terdaftar
            $cek_user       =  DB::table('users')->select('photo', 'name')->where('nik', $nik);
            $cek_count_user = $cek_user->count();

            if ($cek_count_user < 1) {

                return ResponseFormatter::error([
                    'message' => 'NIK tidak tersedia di sistem!',
                ]);
            } else {

                $org         = DB::table('org_diagram_district');

                #cek apakah nik tersebut sudah ada di tb org_diagram_village
                $user        =  $org->where('nik', $nik)->count();

                if ($user > 0) {

                    return ResponseFormatter::error([
                        'message' => 'NIK sudah terdaftar distruktur!',
                    ]);
                } else {

                    #membuat idx
                    #hitung data terakhir dari $idx, lalu di tambah 1 : XXXX.KORRT.1.x
                    $data       = DB::table('org_diagram_district')->where('pidx', $idx)->where('district_id', $districtId)->max('idx');
                    $data_count = DB::table('org_diagram_district')->where('pidx', $idx)->where('district_id', $districtId)->count('idx');


                    #jika belum ada
                    if (!$data) {

                        #buat idx baru
                        $new_idx = $data_count + 1;
                        $new_idx = $idx . "." . $new_idx;
                    } else {

                        #hitung jumlah data terakhir dari $idx, lalu di tambah 1 : XXXX.KORRT.1.x
                        // $new_idx    = $data_count;
                        $new_idx    = $idx . "." . $data_count;
                    }

                    $user = $cek_user->first();

                    #get id domisili by villageId
                    $domisili = DB::table('districts')->select('id', 'regency_id')->where('id', $districtId)->first();

                    #save data org village
                    $save_org  = $org->insert([
                        'idx'    => $new_idx,
                        'pidx'   => $idx,
                        'title'  => $title,
                        'nik'    => $nik,
                        'name'   => $user->name,
                        'base'   => 'KORRT',
                        'photo'  => $user->photo ?? '',
                        'regency_id'  => $domisili->regency_id,
                        'district_id' => $domisili->id,
                        'created_by' => auth()->guard('admin')->user()->id,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);

                    DB::commit();
                    return ResponseFormatter::success([
                        'message' => 'Berhasil tambah struktur!'
                    ], 200);
                }
            }
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            return ResponseFormatter::error([
                'message' => 'Something when wrong!',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function listDataStrukturPusat()
    {

        // $orgTable = DB::table('org_diagram_pusat');

        // #creeate idx
        // $cek_count_org = $orgTable->count();

        // if ($cek_count_org == 0) {

        //     $result_new_idx = "KORPUSAT";

        // }else{

        //     $count_org     = $orgTable->max('idx'); 

        //     $exp        = explode(".", $count_org);
        //     $result_exp = (int) $exp[1]+1;

        //     $result_new_idx  = "KORPUSAT.".$result_exp;
        // }

        // $org      = $orgTable->select('title','name','photo','idx')->whereNotNull('nik')->orderBy('idx','asc')->get();
        // $no       = 1;


        return view('pages.admin.strukturorg.pusat.index');
    }


    public function setOrderStrukturOrgPusat()
    {

        DB::beginTransaction();
        try {

            $idx = request()->data;

            $orgTable =  DB::table('org_diagram_pusat');

            $old_data = $orgTable->select('idx')->orderBy('idx', 'asc')->get();

            #get data where idx
            // $results = [];
            // foreach ($idx as $key => $value) {
            //     $results[] = [
            //         'old' => $old_data,
            //         'new' => $idx
            //     ];
            // }

            DB::commit();
            return ResponseFormatter::success([
                'data' => $results,
                'message' => 'Berhasil set!'
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            return ResponseFormatter::error([
                'message' => 'Something when wrong!',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function createOrgVillage()
    {

        $regency = Regency::select('id', 'name')->where('id', 3602)->first();

        #creeate idx
        $cek_count_org = DB::table('org_diagram_village')->count();

        $result_new_idx = "";

        if ($cek_count_org > 0) {

            $count_org     = DB::table('org_diagram_village')->select('idx')->orderBy('id', 'desc')->get();

            $exp        = explode(".", $count_org);
            $count_exp  = count($exp);

            if ($count_exp == 1) {

                $result_new_idx = $exp[0] . ".1";
            } else {

                $result_exp = (int) $exp[1] + 1;

                $result_new_idx  = time() . "KORDES." . $result_exp;
            }
        } else {

            $result_new_idx = "KORDES";
        }


        return view('pages.admin.strukturorg.village.create', compact('regency', 'result_new_idx'));
    }

    public function saveOrgVillage(Request $request)
    {

        DB::beginTransaction();
        try {

            #cek ketersediaan nik di tb users
            $userTable     = DB::table('users');
            // $cek_nik_user  = $userTable->select('nik','name')->where('id', $request->member)->first();
            $user         = $userTable->select('name', 'photo', 'nik')->where('id', $request->member)->first();

            // if ($cek_nik_user == 0) return redirect()->back()->with(['warning' => 'NIK tidak terdaftar disistem!']);

            #cek jika nik sudah terdaftar di tb org_diagram_village
            $cek_nik_org  = DB::table('org_diagram_village')->where('nik', $user->nik)->where('village_id', $request->village_id)->count();
            if ($cek_nik_org > 0) return redirect()->back()->with(['warning' => 'NIK sudah terdaftar distruktur!']);

            // $user         = $userTable->select('name','photo')->where('nik', $request->nik)->first();


            #save to tb org_diagram_village
            DB::table('org_diagram_village')->insert([
                'idx'    => $request->idx,
                'pidx'   => 'KORDES',
                'title'  => strtoupper($request->jabatan),
                'nik'    => $user->nik,
                'name'   => $user->name,
                'base'   => 'KORDES',
                'level_org'   => GlobalProvider::generateLevelOrg($request->jabatan),
                'photo'  => $user->photo ?? '',
                'telp'  => $request->telp,
                'regency_id'  => $request->regency_id,
                'district_id' => $request->district_id,
                'village_id'  => $request->village_id,
                'created_by' => auth()->guard('admin')->user()->id,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            DB::commit();
            return redirect()->back()->with(['success' => 'Data telah tersimpan!']);
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['error' => 'Data gagal tersimpan!' . $e->getMessage()]);
        }
    }

    public function indexOrgRT()
    {

        $regency = Regency::select('id', 'name')->where('id', 3602)->first();

        $authAdminDistrict = auth()->guard('admin')->user()->district_id;
        $districtModel  = new District();
        $district       = $districtModel->getAreaAdminKoordinator($authAdminDistrict);
        $villages  = Village::select('id','name')->where('district_id', $authAdminDistrict)->get();

        // $rt      = 30;

        return view('pages.admin.strukturorg.rt.index', compact('regency','district','villages'));
    }

    public function getDataOrgRT(Request $request)
    {

        // DATATABLE
        $orderBy = 'b.name';
        switch ($request->input('order.0.column')) {
            case '1':
                $orderBy = 'b.name';
                break;
            case '9':
                $orderBy = 'b.form_kosong';
                break;
        }

        $data = DB::table('org_diagram_rt as a')
            ->select('a.idx', 'a.village_id', 'a.rt', 'a.rw', 'b.address', 'a.title', 'a.nik', 'b.name', 'b.photo', 'a.telp as phone_number', 'a.base', 'a.id', 'c.name as village', 'd.name as district', 'e.tps_number','b.id as user_id',
                    DB::raw("(select count(*) from org_diagram_rt where pidx = a.idx and base ='ANGGOTA') as count_anggota"),
                    DB::raw("(select count(*) from users where user_id = b.id and village_id is not null) as referal"),
                    DB::raw("(select count(*) from anggota_koordinator_tps_korte where pidx_korte = a.idx) as formkortps"),
                    DB::raw("(select count(*) from family_group where pidx_korte = a.idx) as keluargaserumah"),
                    DB::raw('(select count(*) from form_anggota_manual_kortp where pidx_korte = a.idx) as formmanual')
            )
            ->join('users as b', 'b.nik', '=', 'a.nik')
            ->join('villages as c', 'c.id', '=', 'a.village_id')
            ->join('districts as d', 'd.id', '=', 'a.district_id')
            ->leftJoin('tps as e', 'b.tps_id', '=', 'e.id')
            ->join('dapil_areas as f','a.district_id','=','f.district_id')
            ->where('a.base', 'KORRT');


        if ($request->input('search.value') != null) {
            $data = $data->where(function ($q) use ($request) {
                $q->whereRaw('LOWER(b.name) like ? ', ['%' . strtolower($request->input('search.value')) . '%']);
            });
        }

        if ($request->input('dapil') != null) {
            $data->where('f.dapil_id', $request->dapil);
        }

        if ($request->input('district') != null) {
            $data->where('a.district_id', $request->district);
        }

        if ($request->input('village') != null) {
            $data->where('a.village_id', $request->village);
        }

        if ($request->input('rt') != null) {
            $data->where('a.rt', $request->rt);
        }


        $recordsFiltered = $data->get()->count();
        if ($request->input('length') != -1) $data = $data->skip($request->input('start'))->take($request->input('length'));
        $data = $data->orderBy($orderBy, $request->input('order.0.dir'))->get();


        // $data = $data->orderBy('a.rt', 'asc');
        // $data = $data->orderBy($orderBy, $request->input('order.0.dir'));
        // $data = $data->get();

        $recordsTotal = $data->count();

        // $results = [];
        // $no = 1;
        // foreach ($data as $value) {
        //     $count_anggota = DB::table('org_diagram_rt')->where('pidx', $value->idx)->count();
        //     $referal = DB::table('users as a')->join('villages as b','a.village_id','=','b.id')->where('a.user_id', $value->user_id)->count();
        //     $formkortps = DB::table('anggota_koordinator_tps_korte')->where('pidx_korte', $value->idx)->count();
        //     $results[] = [
        //         'no' => $no++,
        //         'id' => $value->id,
        //         'idx' => $value->idx,
        //         'village_id' => $value->village_id,
        //         'rt' => $value->rt,
        //         'tps_number' => $value->tps_number,
        //         'rw' => $value->rw,
        //         'address' => $value->address,
        //         'village' => $value->village,
        //         'district' => $value->district,
        //         'title' => $value->title,
        //         'nik' => $value->nik,
        //         'name' => $value->name,
        //         'photo' => $value->photo,
        //         'phone_number' => $value->phone_number,
        //         'count_anggota' => $count_anggota,
        //         'formkortps' => $formkortps,
        //         'referal' => $referal,
        //         'user_id' => $value->user_id,
        //         'base' => "KORTPS"

        //     ];
        // }

        return response()->json([
            'draw' => $request->input('draw'),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data
        ]);
    }

    public function createOrgRT()
    {

        $regency = Regency::select('id', 'name')->where('id', 3602)->first();

        #creeate idx
        $cek_count_org = DB::table('org_diagram_rt')->count();

        $result_new_idx = "";

        if ($cek_count_org > 0) {

            $count_org     = DB::table('org_diagram_rt')->select('idx')->where('base', 'KORRT')->orderBy('id', 'desc')->first();
            $count_org   = $count_org->idx;
            $exp        = explode(".", $count_org);
            // dd($exp);
            $count_exp  = count($exp);

            if ($count_exp == 1) {

                $result_new_idx = $exp[0] . ".1";
            } else {

                $result_exp = (int) $exp[1] + 1;

                $result_new_idx  = time() . "KORRT." . $result_exp;
            }
        } else {

            $result_new_idx = "KORRT";
        }

        return view('pages.admin.strukturorg.rt.create', compact('regency', 'result_new_idx'));
    }

    public function editAnggotaOrgRT($id)
    {


        $regency = Regency::select('id', 'name')->where('id', 3602)->first();

        $anggota_korte = DB::table('org_diagram_rt')->select('name', 'telp')->where('id', $id)->first();

        return view('pages.admin.strukturorg.rt.edit-anggota', compact('regency', 'id', 'anggota_korte'));
    }

    public function editOrgRT($id)
    {


        $regency = Regency::select('id', 'name')->where('id', 3602)->first();

        $korte = DB::table('org_diagram_rt')->select('name', 'telp', 'rt')->where('id', $id)->first();

        return view('pages.admin.strukturorg.rt.edit', compact('regency', 'id', 'korte'));
    }

    public function editTps($id)
    {


        $regency = Regency::select('id', 'name')->where('id', 3602)->first();

        $korte = DB::table('org_diagram_rt')->select('name', 'telp', 'rt')->where('id', $id)->first();

        return view('pages.admin.strukturorg.rt.edittps', compact('regency', 'id', 'korte'));
    }

    public function editTpsMember($id)
    {


        $regency = Regency::select('id', 'name')->where('id', 3602)->first();

        $korte = DB::table('org_diagram_rt')->select('name', 'telp', 'rt')->where('id', $id)->first();

        return view('pages.admin.strukturorg.rt.edittps-member', compact('regency', 'id', 'korte'));
    }

    public function createOrgRTAnggota($idx)
    {
        
        $authAdminDistrict = auth()->guard('admin')->user()->district_id;
        $districtModel  = new District();
        $district       = $districtModel->getAreaAdminKoordinator($authAdminDistrict);
        $villages       = Village::select('id', 'name')->where('district_id', $authAdminDistrict)->get();

        $kor_rt = DB::table('org_diagram_rt as a')
                ->select('a.rt', 'a.name', 'c.name as village', 'd.name as district', 'e.tps_number')
                ->join('users as b', 'b.nik', '=', 'a.nik')
                ->join('villages as c', 'c.id', '=', 'a.village_id')
                ->join('districts as d', 'd.id', '=', 'a.district_id')
                ->leftJoin('tps as e', 'b.tps_id', '=', 'e.id')
                ->where('idx', $idx)
                ->first();

        $regency = Regency::select('id', 'name')->where('id', 3602)->first();

        $cek_count_org = DB::table('org_diagram_rt')->where('pidx', $idx)->count();

        $result_new_idx = "";

        if ($cek_count_org > 0) {

            $count_org     = DB::table('org_diagram_rt')->select('idx')->where('pidx', $idx)->orderBy('id', 'desc')->first();
            $count_org   = $count_org->idx;
            $exp        = explode(".", $count_org);
            $count_exp  = count($exp);

            if ($count_exp == 1) {

                $result_new_idx = $exp[0] . ".1";
            } else {

                $result_exp = (int) $exp[2] + 1;
                $result_new_idx  = $exp[0] . "." . $exp[1] . "." . $result_exp;
            }
            
        } else {

            $result_new_idx = $idx . '.1';
        }


        return view('pages.admin.strukturorg.rt.create-anggota', compact('regency', 'result_new_idx', 'idx','villages', 'district','kor_rt'));
    }

    public function saveOrgRT(Request $request)
    {

        DB::beginTransaction();
        try {

            #cek ketersediaan nik di tb users
            $userTable     = DB::table('users');

            $user         = $userTable->select('name', 'photo', 'nik')->where('id', $request->member)->first();

            // $cek_nik_user  = $userTable->where('nik', $request->nik)->count();

            // if ($cek_nik_user == 0) return redirect()->back()->with(['warning' => 'NIK tidak terdaftar disistem!']);

            #cek jika nik sudah terdaftar di tb org_diagram_village
            $cek_nik_org  = DB::table('org_diagram_rt')->where('nik', $user->nik)->where('village_id', $request->village_id)->count();
            if ($cek_nik_org > 0) return redirect()->back()->with(['warning' => 'NIK sudah terdaftar distruktur!']);

            // $user         = $userTable->select('name','photo')->where('nik', $request->nik)->first();


            #save to tb org_diagram_rt
            DB::table('org_diagram_rt')->insert([
                'idx'    => $request->idx,
                'pidx'   => 'KORRT',
                'title'  => 'RT ' . $request->rts,
                'nik'    => $user->nik,
                'name'   => $user->name,
                'base'   => 'KORRT',
                'photo'  => $user->photo ?? '',
                'telp'  => $request->telp,
                'regency_id'  => $request->regency_id,
                'district_id' => $request->district_id,
                'village_id'  => $request->village_id,
                'rt'  => $request->rts,
                'cby' => auth()->guard('admin')->user()->id,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            #update tps_id ke tb users
            DB::table('users')->where('nik', $user->nik)->update([
                'tps_id' => $request->tps
            ]);

            DB::commit();
            return redirect()->back()->with(['success' => 'Data telah tersimpan!']);
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['error' => 'Data gagal tersimpan!' . $e->getMessage()]);
        }
    }

    public function saveAnggotaByKorRTAndNewAnggotaKTA(Request $request){

        // menyimpan anggota by kortps dan membuat KTA
        DB::beginTransaction();
        try {
            # code...
            $this->validate($request, [
                'phone_number' => 'numeric',
            ]);
    
            #hitung panjang nik, harus 16
            $cekLengthNik = strlen($request->nik);
            if ($cekLengthNik < 16) return redirect()->back()->with(['error' => 'NIK harus 16 angka, cek kembali NIK tersebut!']);
    
            $cby = auth()->guard('admin')->user()->id;
            //    $cby    = User::select('id')->where('user_id', $cby_id->id)->first();
    
            $cek_nik = User::select('nik')->where('nik', $request->nik)->count();
            #cek nik jika sudah terpakai
            if ($cek_nik > 0) {
                return redirect()->back()->with(['error' => 'NIK yang anda gunakan telah terdaftar']);
            } else {

    
                //  get referal by kortps
                $refeal_code = DB::table('users as a')
                                ->select('a.id')
                                ->join('org_diagram_rt as b','a.nik','=','b.nik')
                                ->where('b.idx', $request->pidx)
                                ->first();

                $request_ktp = $request->ktp;
                $request_photo = $request->photo;
                $gF = new GlobalProvider();
                $ktp = $gF->cropImageKtp($request_ktp);
                $photo = $gF->cropImagePhoto($request_photo);

                $strRandomProvider = new StrRandom();
                $string            = $strRandomProvider->generateStrRandom();
                $potong_nik        = substr($request->nik, -5); // get angka nik 5 angka dari belakang

                $user = User::create([
                    'user_id' => $refeal_code->id,
                    'code' => $string.$potong_nik,
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
                    'rt'           => $request->newrt,
                    'rw'           => $request->newrw,
                    'tps_id'       => $request->tps_new_id,
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

                #hitung jumlah anggota kortpsnya
                $message_kortps = '';
                // $count_anggota_kortps = DB::table('org_diagram_rt as a')
                //                         ->join('users as b','a.nik','=','b.nik')
                //                         ->where('a.pidx', $request->pidx)
                //                         ->count();

                // if ($count_anggota_kortps >= 25) {

                //     $message_kortps = 'Tapi data tidak tersimpan ke anggota Kor Tps, karena sudah 25';

                // }else{
                //     #save to table org_diagram_rt;
                //     #get villlage, regency, district, rt where idx
                //     $domisili_by_kortps = DB::table('org_diagram_rt')->select('regency_id', 'district_id', 'village_id', 'rt')->where('idx', $request->pidx)->first();
                //     DB::table('org_diagram_rt')->insert([
                //         'idx'    => $request->idx,
                //         'pidx'   => $request->pidx,
                //         'title'  => 'ANGGOTA',
                //         'nik'    => $user->nik,
                //         'name'   => $user->name,
                //         'base'   => 'ANGGOTA',
                //         'photo'  => $user->photo ?? '',
                //         'telp'  => $request->phone_number,
                //         'regency_id'  => $domisili_by_kortps->regency_id,
                //         'district_id' => $domisili_by_kortps->district_id,
                //         'village_id'  => $domisili_by_kortps->village_id,
                //         'rt'  => $domisili_by_kortps->rt,
                //         'cby' => auth()->guard('admin')->user()->id,
                //         'created_at' => date('Y-m-d H:i:s')
                //     ]);
                // }

                #save to table org_diagram_rt;
                    #get villlage, regency, district, rt where idx
                    $domisili_by_kortps = DB::table('org_diagram_rt')->select('regency_id', 'district_id', 'village_id', 'rt')->where('idx', $request->pidx)->first();
                    DB::table('org_diagram_rt')->insert([
                        'idx'    => $request->idx,
                        'pidx'   => $request->pidx,
                        'title'  => 'ANGGOTA',
                        'nik'    => $user->nik,
                        'name'   => $user->name,
                        'base'   => 'ANGGOTA',
                        'photo'  => $user->photo ?? '',
                        'telp'  => $request->phone_number,
                        'regency_id'  => $domisili_by_kortps->regency_id,
                        'district_id' => $domisili_by_kortps->district_id,
                        'village_id'  => $domisili_by_kortps->village_id,
                        'rt'  => $domisili_by_kortps->rt,
                        'cby' => auth()->guard('admin')->user()->id,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);

                // DB::table('users')->where('id', $user->id)->update(['tps_id' => $request->tpsNewAnggotaBaru]);
                #save to table form kosong sebagai history
                DB::table('anggota_koordinator_tps_korte')->insert([
                    'nik' => $request->nik,
                    'pidx_korte' => $request->pidx,
                    'name' => strtoupper($request->name),
                    'created_by' => auth()->guard('admin')->user()->id,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }
    
            DB::commit();
            return redirect()->back()->with(['success' => 'Data telah tersimpan ', $message_kortps]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return redirect()->back()->with('error', 'Anggota baru gagal dibuat', $e->getMessage());

        }
    }

    public function saveAnggotaByKorRT(Request $request)
    {
        DB::beginTransaction();
        try {

            #cek ketersediaan nik di tb users
            $userTable     = DB::table('users');
            // $cek_nik_user  = $userTable->where('id', $request->member)->count();
            $user         = $userTable->select('name', 'photo', 'phone_number', 'nik')->where('id', $request->member)->first();

            // if ($cek_nik_user == 0) return redirect()->back()->with(['warning' => 'NIK tidak terdaftar disistem!']);

            // #cek jika nik sudah terdaftar di tb org_diagram_village
            $cek_nik_org  = DB::table('org_diagram_rt')->select('pidx')->where('nik', $user->nik)->first();
            if($cek_nik_org != null){
                $kortps = DB::table('org_diagram_rt as a')
                        ->select('c.name','b.name as desa')
                        ->join('villages as b','a.village_id','=','b.id')
                        ->join('users as c','a.nik','=','c.nik')
                        ->where('a.idx', $cek_nik_org->pidx)
                        ->first();

                return redirect()->back()->with(['warning' => 'NIK sudah terdaftar distruktur anggota, Kortps : '.$kortps->name.' Ds.'.$kortps->desa]);
                
            }
            // if ($cek_nik_org > 0) return redirect()->back()->with(['warning' => 'NIK sudah terdaftar distruktur anggota!']);

            #get villlage, regency, district, rt where idx
            $domisili = DB::table('org_diagram_rt')->select('regency_id', 'district_id', 'village_id', 'rt')->where('idx', $request->pidx)->first();


            #cek jika tps koordinator tidak sama dengan tps calon anggotanya
            $koor = DB::table('org_diagram_rt as a')->select('b.tps_id','a.nik')
                ->join('users as b', 'a.nik', '=', 'b.nik')
                ->where('a.idx', $request->pidx)
                ->first();

                // dd($koor); 

            // dd($request->tpsid);
            $tpsKoor = $koor->tps_id;

            if ($tpsKoor != $request->tpsid) {

                return redirect()->back()->with(['error' => 'Gagal, TPS anggota tidak sama dengan TPS Koordinator!']);

            } else {

                #hitung anggota per kortps, jika lebih dari 25
                // $count_anggota_kortps =  DB::table('org_diagram_rt as a')
                //                         ->join('users as b','a.nik','=','b.nik')
                //                         ->where('a.pidx', $request->pidx)
                //                         ->count();

                // if ($count_anggota_kortps >= 25) {

                //     return redirect()->back()->with(['error' => 'Gagal tersimpan tersimpan, anggota sudah 25']);

                // }else{
                //     #save to tb org_diagram_rt
                //     DB::table('org_diagram_rt')->insert([
                //         'idx'    => $request->idx,
                //         'pidx'   => $request->pidx,
                //         'title'  => 'ANGGOTA',
                //         'nik'    => $user->nik,
                //         'name'   => $user->name,
                //         'base'   => 'ANGGOTA',
                //         'photo'  => $user->photo ?? '',
                //         'telp'  => $request->telp,
                //         'regency_id'  => $domisili->regency_id,
                //         'district_id' => $domisili->district_id,
                //         'village_id'  => $domisili->village_id,
                //         'rt'  => $domisili->rt,
                //         'cby' => auth()->guard('admin')->user()->id,
                //         'created_at' => date('Y-m-d H:i:s')
                //     ]);
                //     DB::table('users')->where('nik', $user->nik)->update(['tps_id' => $request->tpsid]);

                // }

                #save to tb org_diagram_rt
                    DB::table('org_diagram_rt')->insert([
                        'idx'    => $request->idx,
                        'pidx'   => $request->pidx,
                        'title'  => 'ANGGOTA',
                        'nik'    => $user->nik,
                        'name'   => $user->name,
                        'base'   => 'ANGGOTA',
                        'photo'  => $user->photo ?? '',
                        'telp'  => $request->telp,
                        'regency_id'  => $domisili->regency_id,
                        'district_id' => $domisili->district_id,
                        'village_id'  => $domisili->village_id,
                        'rt'  => $domisili->rt,
                        'cby' => auth()->guard('admin')->user()->id,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
                    DB::table('users')->where('nik', $user->nik)->update(['tps_id' => $request->tpsid]);

                DB::commit();
                return redirect()->back()->with(['success' => 'Data telah tersimpan!']);
            }
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            return redirect()->back()->with(['error' => 'Data gagal tersimpan!' . $e->getMessage()]);
        }
    }

    public function updateAnggotaByKorRT(Request $request, $id)
    {
        DB::beginTransaction();
        try {

            if ($request->member != null) {
                #cek ketersediaan nik di tb users
                $userTable     = DB::table('users');
                // $cek_nik_user  = $userTable->where('id', $request->member)->count();
                $user         = $userTable->select('name', 'photo', 'phone_number', 'nik')->where('id', $request->member)->first();

                // if ($cek_nik_user == 0) return redirect()->back()->with(['warning' => 'NIK tidak terdaftar disistem!']);

                // #cek jika nik sudah terdaftar di tb org_diagram_village
                $cek_nik_org  = DB::table('org_diagram_rt')->where('nik', $user->nik)->count();
                if ($cek_nik_org > 0) return redirect()->back()->with(['warning' => 'NIK sudah terdaftar distruktur!']);

                $old_anggota_korte = DB::table('org_diagram_rt')->select('name', 'pidx')->where('id', $id)->first();

                // #get villlage, regency, district, rt where idx
                // $domisili = DB::table('org_diagram_rt')->select('regency_id','district_id','village_id','rt')->where('idx', $old_anggota_korte->pidx)->first();
                // dd($domisili);

                DB::table('org_diagram_rt')->where('id', $id)->update([
                    'nik'    => $user->nik,
                    'name'   => $user->name,
                    'photo'  => $user->photo ?? '',
                    'telp'  => $request->telp,
                ]);
            } else {
                $old_anggota_korte = DB::table('org_diagram_rt')->select('name', 'pidx')->where('id', $id)->first();

                DB::table('org_diagram_rt')->where('id', $id)->update([
                    'telp'  => $request->telp,
                ]);
            }

            DB::commit();
            return redirect()->route('admin-struktur-organisasi-rt-detail-anggota', ['idx' => $old_anggota_korte->pidx]);
            // return redirect()->back()->with(['success' => 'Data telah tersimpan!']);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            return redirect()->back()->with(['error' => 'Data gagal tersimpan!' . $e->getMessage()]);
        }
    }

    public function updateKorRT(Request $request, $id)
    {
        DB::beginTransaction();
        try {

            if ($request->member != null) {
                #cek ketersediaan nik di tb users
                $userTable     = DB::table('users');
                // $cek_nik_user  = $userTable->where('id', $request->member)->count();
                $user         = $userTable->select('name', 'photo', 'phone_number', 'nik')->where('id', $request->member)->first();

                // if ($cek_nik_user == 0) return redirect()->back()->with(['warning' => 'NIK tidak terdaftar disistem!']);

                // #cek jika nik sudah terdaftar di tb org_diagram_village
                $cek_nik_org  = DB::table('org_diagram_rt')->where('nik', $user->nik)->count();
                if ($cek_nik_org > 0) return redirect()->back()->with(['warning' => 'NIK sudah terdaftar distruktur!']);

                $old_anggota_korte = DB::table('org_diagram_rt')->select('name', 'pidx')->where('id', $id)->first();

                // #get villlage, regency, district, rt where idx
                // $domisili = DB::table('org_diagram_rt')->select('regency_id','district_id','village_id','rt')->where('idx', $old_anggota_korte->pidx)->first();
                // dd($domisili);

                DB::table('org_diagram_rt')->where('id', $id)->update([
                    'nik'    => $user->nik,
                    'name'   => $user->name,
                    'photo'  => $user->photo ?? '',
                    'telp'  => $request->telp,
                    'rt'  => $request->rts,
                ]);
            } else {

                DB::table('org_diagram_rt')->where('id', $id)->update([
                    'telp'  => $request->telp,
                    'rt'  => $request->rts,
                ]);
            }

            DB::commit();
            return redirect()->route('admin-struktur-organisasi-rt');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            return redirect()->back()->with(['error' => 'Data gagal tersimpan!' . $e->getMessage()]);
        }
    }

    public function updateOrgRT()
    {

        DB::beginTransaction();
        try {

            $id   = request()->id;
            $nik  = request()->nik;

            #cek nik di tb users, true
            $userTable     = DB::table('users');
            $cek_nik_user  = $userTable->where('nik', $nik)->count();
            if ($cek_nik_user == 0) return ResponseFormatter::error(['message' => 'NIK tidak terdaftar disistem!']);

            #cek nik di tb org_diagram_rt, false
            $cek_nik_org  = DB::table('org_diagram_rt')->where('nik', $nik)->count();
            if ($cek_nik_org > 0) return ResponseFormatter::error(['message' => 'NIK sudah terdaftar distruktur!']);

            #update org
            $user         = $userTable->select('name', 'photo', 'phone_number', 'nik')->where('nik', $nik)->first();

            DB::table('org_diagram_rt')->where('id', $id)->update([
                'nik'    => $user->nik,
                'name'   => $user->name,
                'photo'  => $user->photo ?? '',
                'telp'  => $user->phone_number,
            ]);


            DB::commit();
            return ResponseFormatter::success([
                'message' => 'Berhasil update struktur!'
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            return ResponseFormatter::error([
                'message' => 'Something when wrong!',
                'error'   => $e->getMessage()
            ]);
        }
    }

    public function detailAnggotaByKorRT($idx)
    {
        $kor_rt = DB::table('org_diagram_rt as a')
            ->select('a.rt', 'a.name', 'c.name as village', 'd.name as district', 'e.tps_number')
            ->join('users as b', 'b.nik', '=', 'a.nik')
            ->join('villages as c', 'c.id', '=', 'a.village_id')
            ->join('districts as d', 'd.id', '=', 'a.district_id')
            ->leftJoin('tps as e', 'b.tps_id', '=', 'e.id')
            ->where('idx', $idx)
            ->first();

        $anggotaKorTps = DB::table('anggota_koordinator_tps_korte as a')
            ->select('a.id','a.name', 'a.nik', 'b.photo', 
                DB::raw('(select COUNT(nik) from org_diagram_rt where nik = a.nik) as is_cover'),
                DB::raw('(select COUNT(nik) from org_diagram_rt where nik = a.nik and pidx = a.pidx_korte) as myanggota')

            )
            ->leftJoin('users as b', 'b.nik', '=', 'a.nik')
            ->where('a.pidx_korte', $idx)
            // ->orderBy(DB::raw('(select COUNT(nik) from org_diagram_rt where nik = a.nik)'), 'desc')
            ->orderByRaw('myanggota DESC')
            ->get();

        // usort($anggotaKorTps, fn($a, $b) => $a->myanggota < $b->myanggota);


        // dd($anggotaKorTps);

        $no = 1;
        $korte_idx = $idx;

        // keluarga serumah
        $famillyGroup = DB::table('family_group as a')
                        ->select('a.id','b.name','b.photo')
                        ->leftJoin('users as b','a.nik','=','b.nik')
                        ->where('a.pidx_korte', $idx)
                        ->get();
        // anggota keluarga serumah
        $resultsFamilyGroup = [];
        foreach ($famillyGroup as $key => $value) {
            $members = DB::table('detail_family_group as a')
                    ->select('a.id','b.name','b.photo','b.address','c.name as village','d.name as district','e.tps_number','f.telp')
                    ->join('users as b','a.nik','=','b.nik')
                    ->join('villages as c','b.village_id','=','c.id')
                    ->join('districts as d','c.district_id','=','d.id')
                    ->leftJoin('tps as e','b.tps_id','=','e.id')
                    ->join('org_diagram_rt as f','a.nik','=','f.nik')
                    ->where('a.family_group_id', $value->id)
                    ->get();

            $resultsFamilyGroup[] = [
                'id' => $value->id,
                'head_famlly_name' => $value->name,
                'head_famlly_photo' => $value->photo,
                'members' => $members
            ];
        }

        $no_head_familly    = 1;
        return view('pages.admin.strukturorg.rt.detailanggota', compact('kor_rt', 'anggotaKorTps', 'no', 'korte_idx','no_head_familly','resultsFamilyGroup','idx'));
    }

    public function downloadMembersRt($idx)
    {

        $kor_rt = DB::table('org_diagram_rt as a')
            ->select('a.rt', 'a.name', 'c.name as village', 'd.name as district')
            ->join('users as b', 'b.nik', '=', 'a.nik')
            ->join('villages as c', 'c.id', '=', 'a.village_id')
            ->join('districts as d', 'd.id', '=', 'a.district_id')
            ->where('idx', $idx)
            ->first();

        $title = 'ANGGOTA KORTE : ' . $kor_rt->name . ' RT (' . $kor_rt->rt . '), DS.' . $kor_rt->village . ', KEC.' . $kor_rt->district . '.xls';
        return $this->excel->download(new KorteMembersExport($idx), $title);
    }

    public function storeSuratPernyatanKorte($idx)
    {

        // get data korte by idx 
        $korte = DB::table('org_diagram_rt as a')
            ->select('a.rt', 'a.name', 'b.address', 'c.name as village', 'd.name as district', 'a.rt', 'b.rw', 'a.telp', 'b.code')
            ->join('users as b', 'b.nik', '=', 'a.nik')
            ->join('villages as c', 'c.id', '=', 'a.village_id')
            ->join('districts as d', 'd.id', '=', 'a.district_id')
            ->where('idx', $idx)
            ->first();

        $pdf  = PDF::LoadView('pages.report.suratpernyataantim', compact('korte'))->setPaper('a4');
        return $pdf->download('SURAT PERNYATAAN KETERSEDIAAN ' . $korte->rt . '( ' . $korte->name . ').pdf');
    }

    public function downloadMembersRtPDF($idx)
    {

        $kor_rt = DB::table('org_diagram_rt as a')
            ->select('b.id','a.base', 'a.rt', 'b.name', 'c.name as village', 'd.name as district','e.tps_number',
                DB::raw('(select count(b2.id) from users as b2 where b2.user_id= b.id and b2.village_id is not null ) as referal')
            )
            ->join('users as b', 'b.nik', '=', 'a.nik')
            ->join('villages as c', 'c.id', '=', 'a.village_id')
            ->join('districts as d', 'd.id', '=', 'a.district_id')
            ->join('tps as e','b.tps_id','=','e.id')
            ->where('a.idx', $idx)
            ->where('a.base', 'KORRT')
            ->first();
            
            // get data anggota by korte
            $members = DB::table('org_diagram_rt as a')
            ->select('a.base', 'a.rt', 'b.name', 'c.name as village', 'd.name as district', 'b.address', 'a.telp', 'e.tps_number',
                DB::raw('TIMESTAMPDIFF(YEAR, b.date_berth, NOW()) as usia')
            )
            ->join('users as b', 'b.nik', '=', 'a.nik')
            ->join('villages as c', 'c.id', '=', 'a.village_id')
            ->join('districts as d', 'd.id', '=', 'a.district_id')
            ->leftJoin('tps as e','b.tps_id','=','e.id')
            ->where('a.pidx', $idx)
            ->where('a.base', 'ANGGOTA')
            ->get();
 
            $no = 1;


        $pdf = PDF::LoadView('pages.report.memberbykorte', compact('kor_rt', 'members', 'no'))->setPaper('a4');
        return $pdf->download('ANGGOTA KORTE RT ' . $kor_rt->rt . ' (' . $kor_rt->name . ') DS.' . $kor_rt->village . '.pdf');
    }

    public function downloadMembersFormManualRtPDF($idx)
    {

        $kor_rt = DB::table('org_diagram_rt as a')
            ->select('b.id','a.base', 'a.rt', 'b.name', 'c.name as village', 'd.name as district','e.tps_number',
                DB::raw('(select count(b2.id) from users as b2 where b2.user_id= b.id and b2.village_id is not null ) as referal')
            )
            ->join('users as b', 'b.nik', '=', 'a.nik')
            ->join('villages as c', 'c.id', '=', 'a.village_id')
            ->join('districts as d', 'd.id', '=', 'a.district_id')
            ->join('tps as e','b.tps_id','=','e.id')
            ->where('a.idx', $idx)
            ->where('a.base', 'KORRT')
            ->first();


            // get data anggota by korte, form manual
            $members = DB::table('form_anggota_manual_kortp as a')
            ->select('a.name','a.nik')
            ->where('a.pidx_korte', $idx)
            ->orderBy('a.name','asc')
            ->get();

            $no = 1;


        $pdf = PDF::LoadView('pages.report.memberformmanualbykorte', compact('kor_rt', 'members', 'no'))->setPaper('a4');
        return $pdf->download('ANGGOTA FORM MANUAL KORTE RT ' . $kor_rt->rt . ' (' . $kor_rt->name . ') DS.' . $kor_rt->village . '.pdf');
    }

    public function downloadMembersKeluargaSerumahRtPDF($idx)
    {
        $kor_rt = DB::table('org_diagram_rt as a')
            ->select('b.id','a.base', 'a.rt', 'b.name', 'c.name as village', 'd.name as district','e.tps_number',
                DB::raw('(select count(b2.id) from users as b2 where b2.user_id= b.id and b2.village_id is not null ) as referal')
            )
            ->join('users as b', 'b.nik', '=', 'a.nik')
            ->join('villages as c', 'c.id', '=', 'a.village_id')
            ->join('districts as d', 'd.id', '=', 'a.district_id')
            ->join('tps as e','b.tps_id','=','e.id')
            ->where('a.idx', $idx)
            ->where('a.base', 'KORRT')
            ->first();

            
            // get data anggota by korte
        $members = DB::table('org_diagram_rt as a')
            ->select('a.base', 'a.rt', 'b.name', 'c.name as village', 'd.name as district', 'b.address', 'a.telp', 'e.tps_number',
                DB::raw('TIMESTAMPDIFF(YEAR, b.date_berth, NOW()) as usia'),
                DB::raw('(select id from family_group where nik = a.nik limit 1) as fg_kepala'),
                DB::raw('(select family_group_id from detail_family_group where nik = a.nik limit 1) as fg_anggota')
            )
            ->join('users as b', 'b.nik', '=', 'a.nik')
            ->join('villages as c', 'c.id', '=', 'a.village_id')
            ->join('districts as d', 'd.id', '=', 'a.district_id')
            ->leftJoin('tps as e','b.tps_id','=','e.id')
            ->where('a.pidx', $idx)
            ->where('a.base', 'ANGGOTA')
            ->get();

        // dd($members); 

         // bedah data array untuk menyatukan kelompok keluarga
        $results = [];
        foreach ($members as  $value) {
            $results[] = [
                'name' => $value->name,
                'base' => $value->base,
                'rt'   => $value->rt,
                'village'  => $value->village,
                'district' => $value->district,
                'address'  => $value->address,
                'telp'     => $value->telp,
                'tps_number' => $value->tps_number,
                'usia' => $value->usia,
                'family_group_id' => $value->fg_kepala == null ? $value->fg_anggota : $value->fg_kepala
            ];
        }

        $after_results = [];
        foreach ($results as $key => $item) {
           $family_group_id = $item['family_group_id'] == null ? 'Belum Terkelompokan' : $item['family_group_id'];
           $after_results[] = [
                'name' => $item['name'],
                'base' => $item['base'],
                'rt'   => $item['rt'],
                'village'  => $item['village'],
                'district' => $item['district'],
                'address'  => $item['address'],
                'telp'     => $item['telp'],
                'tps_number' => $item['tps_number'],
                'usia' => $item['usia'],
                'family_group_id' => $family_group_id
           ];
        }

        // dd($after_results);
        usort($after_results, fn($a, $b) => $a['family_group_id'] <= $b['family_group_id']);

        
        // kelompokan data dengan keluarga nya
        $results_family_group = [];
        foreach ($after_results as  $key => $item) {
                $results_family_group[$item['family_group_id']][$key] = $item;
        }


        // dd($results_family_group);
        // dd($results);

        $list_keluarga = DB::table('family_group as a')
                        ->select('a.id','b.name')
                        ->join('users as b','a.nik','=','b.nik')
                        ->where('a.pidx_korte', $idx)
                        ->get();

        $coutn_list_keluarga = count($list_keluarga);

        $anggota = DB::table('detail_family_group as a')
                        ->select('b.nik','b.name','a.family_group_id') 
                        ->join('users as b','a.user_id','=','b.id')
                        ->where('a.pidx_korte', $idx)
                        ->get();

        $results = [];
    
        foreach($list_keluarga as $item){
            $anggota = DB::table('detail_family_group as a')
                        ->select('b.nik','b.name') 
                        ->join('users as b','a.user_id','=','b.id')
                        ->where('a.family_group_id', $item->id)
                        ->get();

            $count_anggota = count($anggota);

            $results[] = [
                'kepala_keluarga' => $item->name,
                'anggota' => $anggota,
                'count_anggota' => $count_anggota,
            ];
        }

        // dd($results);  
            $no = 1;
            $no_anggota = 1;


        $pdf = PDF::LoadView('pages.report.keluargaserumah', compact('kor_rt', 'no','results','no_anggota','coutn_list_keluarga','members','results_family_group'))->setPaper('a4');
        return $pdf->download('ANGGOTA KELUARGA SERUMAH ' . $kor_rt->rt . ' (' . $kor_rt->name . ') DS.' . $kor_rt->village . '.pdf');
    }

    public function downloadTpsTimPemenanganSuara($id)
    {

        // get data korte, rt, no hp, desa, kecamatan
        $korte = DB::table('org_diagram_rt as a')
            ->select('b.name', 'a.rt', 'a.telp', 'c.name as village', 'd.name as district')
            ->join('users as b', 'a.nik', '=', 'b.nik')
            ->join('villages as c', 'a.village_id', '=', 'c.id')
            ->join('districts as d', 'a.district_id', '=', 'd.id')
            ->where('a.idx', $id)
            ->first();

        $anggota = DB::table('org_diagram_rt as a')
            ->select('b.name', 'a.rt', 'b.address')
            ->join('users as b', 'a.nik', '=', 'b.nik')
            ->where('a.pidx', $id)
            ->where('a.base', 'ANGGOTA')
            ->get();
        $no     = 1;

        $pdf = PDF::LoadView('pages.report.daftarpemilih', compact('no', 'korte', 'anggota'))->setPaper('a4');
        return $pdf->download('TPS TIM PEMENANGAN SUARA KORTE RT.' . $korte->rt . '(' . $korte->name . ') DS.' . $korte->village . '.pdf');
    }

    public function getDataAnggotaByKortpsForFamillyGroup(Request $request, $idx){

        $data = DB::table('org_diagram_rt as a')
            ->select('a.idx', 'a.name', 
                        DB::raw('(select count(id) from family_group where nik = a.nik) as cek_kepkeluarga'),
                        DB::raw('(select count(id) from detail_family_group where nik = a.nik) as cek_member')
                   )
            ->join('users as b', 'b.nik', '=', 'a.nik')
            ->where('a.pidx', $idx);

        if($request->has('q')){
                $search = $request->q;
                $data = DB::table('org_diagram_rt as a')
                ->select('a.idx', 'a.name',
                            DB::raw('(select count(id) from family_group where nik = a.nik) as cek_kepkeluarga'),
                            DB::raw('(select count(id) from detail_family_group where nik = a.nik) as cek_member')
                        )
                ->join('users as b', 'b.nik', '=', 'a.nik')
                ->where('a.pidx', $idx)
                ->Where('a.name', 'LIKE',"%$search%");
        }

        $data    = $data->get();
        $results = [];
        // kirim data yang hanya belum terdaftar sebagai keluarga serumah
        foreach ($data as  $value) {
            if ($value->cek_kepkeluarga == 0 AND $value->cek_member == 0) {
                $results[] = [
                    'idx' => $value->idx,
                    'name' => $value->name
                ];
            }
        }

        return response()->json($results);
    }

    public function deleteDataAnggotaByKortpsForFamillyGroup(){

        DB::beginTransaction();
        try {

            $id   = request()->id;

            DB::table('detail_family_group')->where('id', $id)->delete();

            #mekanisme sortir idx jika ada yang terhapus
            DB::commit();
            return ResponseFormatter::success([
                'message' => 'Berhasil hapus anggota!'
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            return ResponseFormatter::error([
                'message' => 'Something when wrong!',
                'error'   => $e->getMessage()
            ]);
        }
    }

    public function deleteDataHeadByKortpsForFamillyGroup(){

        DB::beginTransaction();
        try {

            $id   = request()->id;

            DB::table('detail_family_group')->where('family_group_id', $id)->delete();
            DB::table('family_group')->where('id', $id)->delete();

            #mekanisme sortir idx jika ada yang terhapus
            DB::commit();
            return ResponseFormatter::success([
                'message' => 'Berhasil hapus anggota!'
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            return ResponseFormatter::error([
                'message' => 'Something when wrong!',
                'error'   => $e->getMessage()
            ]);
        }
    }

    public function getListDataAnggotaByKorRt(Request $request)
    {

        // DATATABLE
        $orderBy = 'b.name';
        switch ($request->input('order.0.column')) {
            case '1':
                $orderBy = 'b.name';
                break;
        }

        $data = DB::table('org_diagram_rt as a')
            ->select('a.id', 'a.idx','a.pidx', 'a.village_id', 'a.rt', 'a.rw', 'b.address', 'a.title', 'a.nik', 'b.name', 'b.photo', 'a.telp as phone_number', 'a.base', 'a.id', 'c.name as village', 'd.name as district', 'e.tps_number','b.id as user_id',
                DB::raw('(select count(nik) from  anggota_koordinator_tps_korte where nik = a.nik and pidx_korte = a.pidx) as formkortps'),
                DB::raw("(select village_id from  org_diagram_rt where idx = a.pidx and base = 'KORRT' limit 1) as village_id_kortps")
            )
            ->leftJoin('users as b', 'b.nik', '=', 'a.nik')
            ->join('villages as c', 'c.id', '=', 'a.village_id')
            ->join('districts as d', 'd.id', '=', 'a.district_id')
            ->leftJoin('tps as e', 'b.tps_id', '=', 'e.id')
            ->where('a.pidx', $request->idx);


        if ($request->input('search.value') != null) {
            $data = $data->where(function ($q) use ($request) {
                $q->whereRaw('LOWER(a.name) like ? ', ['%' . strtolower($request->input('search.value')) . '%']);
            });
        }

        if ($request->input('village') != null) {
            $data->where('a.village_id', $request->village);
        }

        if ($request->input('rt') != null) {
            $data->where('a.rt', $request->rt);
        }


        $recordsFiltered = $data->get()->count();
        if ($request->input('length') != -1) $data = $data->skip($request->input('start'))->take($request->input('length'));
        $data = $data->orderBy($orderBy, $request->input('order.0.dir'))->get();

        $recordsTotal = $data->count();

        // $results = [];
        // $no = 1;
        // foreach ($data as $value) {
        //     $results[] = [
        //         'no' => $no++,
        //         'id' => $value->id,
        //         'idx' => $value->idx,
        //         'village_id' => $value->village_id,
        //         'address' => $value->address,
        //         'tps_number' => $value->tps_number,
        //         'village' => $value->village,
        //         'district' => $value->district,
        //         'title' => $value->title,
        //         'nik' => $value->nik,
        //         'name' => $value->name,
        //         'photo' => $value->photo,
        //         'phone_number' => $value->phone_number,
        //         'user_id' => $value->user_id,
        //         'formkortps' => $value->formkortps

        //     ];
        // }

        return response()->json([
            'draw' => $request->input('draw'),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data
        ]);
    }

    public function deleteAnggotaByKorgRT()
    {

        DB::beginTransaction();
        try {

            $id   = request()->id;

            DB::table('org_diagram_rt')->where('id', $id)->delete();

            #mekanisme sortir idx jika ada yang terhapus

            DB::commit();
            return ResponseFormatter::success([
                'message' => 'Berhasil hapus anggota!'
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            return ResponseFormatter::error([
                'message' => 'Something when wrong!',
                'error'   => $e->getMessage()
            ]);
        }
    }

    public function deleteKorgRT()
    {

        DB::beginTransaction();
        try {

            $id   = request()->id;

            $kor_rt =  DB::table('org_diagram_rt')->select('idx')->where('id', $id)->first();
            DB::table('org_diagram_rt')->where('pidx', $kor_rt->idx)->delete(); // delete child dari korrt
            DB::table('org_diagram_rt')->where('id', $id)->delete();

            DB::commit();
            return ResponseFormatter::success([
                'message' => 'Berhasil hapus KOR RT!'
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            return ResponseFormatter::error([
                'message' => 'Something when wrong!',
                'error'   => $e->getMessage()
            ]);
        }
    }


    public function indexOrgDistrict()
    {

        $regency = Regency::select('id', 'name')->where('id', 3602)->first();

        $rt      = 30;

        $authAdminDistrict = auth()->guard('admin')->user()->district_id;
        $district  = District::select('name','id')->where('id', $authAdminDistrict)->first();

       
        return view('pages.admin.strukturorg.district.index', compact('regency', 'rt','district'));
    }

    public function indexOrgDapil()
    {

        $regency = Regency::select('id', 'name')->where('id', 3602)->first();

        $rt      = 30;

        return view('pages.admin.strukturorg.dapil.index', compact('regency', 'rt'));
    }

    public function createOrgDistrict()
    {

        $regency = Regency::select('id', 'name')->where('id', 3602)->first();

        #creeate idx
        $cek_count_org = DB::table('org_diagram_district')->count();

        $result_new_idx = "";

        if ($cek_count_org > 0) {

            $count_org     = DB::table('org_diagram_district')->select('idx')->orderBy('id', 'desc')->first();
            $count_org     = $count_org->idx;

            $exp        = explode(".", $count_org);
            $count_exp  = count($exp);

            if ($count_exp == 1) {

                $result_new_idx = time() . $exp[0] . ".1";
            } else {

                $result_exp = (int) $exp[1] + 1;

                $result_new_idx  = time() . "KORCAM." . $result_exp;
            }
        } else {

            $result_new_idx = "KORCAM";
        }


        return view('pages.admin.strukturorg.district.create', compact('regency', 'result_new_idx'));
    }

    public function createOrgDapil()
    {

        $regency = Regency::select('id', 'name')->where('id', 3602)->first();

        #creeate idx
        $cek_count_org = DB::table('org_diagram_dapil')->count();

        $result_new_idx = "";

        if ($cek_count_org > 0) {

            $count_org     = DB::table('org_diagram_dapil')->max('idx');

            $exp        = explode(".", $count_org);
            $count_exp  = count($exp);

            if ($count_exp == 1) {

                $result_new_idx = time() . $exp[0] . ".1";
            } else {

                $result_exp = (int) $exp[1] + 1;

                $result_new_idx  = time() . "KORDAPIL." . $result_exp;
            }
        } else {

            $result_new_idx = "KORDAPIL";
        }


        return view('pages.admin.strukturorg.dapil.create', compact('regency', 'result_new_idx'));
    }

    public function getDataOrgDistrict(Request $request)
    {

        // DATATABLE
        $orderBy = 'a.name';
        switch ($request->input('order.0.column')) {
            case '1':
                $orderBy = 'a.name';
                break;
                // case '3':
                //     $orderBy = 'a.rt';
                //     break;
                // case '3':
                //     $orderBy = 'districts.name';
                //     break;
                // case '4':
                //     $orderBy = 'villages.name';
                //     break;
                // case '5':
                //     $orderBy = 'b.name';
                //     break;
                // case '6':
                //     $orderBy = 'c.name';
                //     break;
                // case '7':
                //     $orderBy = 'a.created_at';
                //     break;
        }

        $data = DB::table('org_diagram_district as a')
            ->select('a.id', 'a.idx', 'a.district_id', 'b.address', 'a.title', 'a.nik', 'a.name', 'b.photo', 'a.telp as phone_number', 'c.name as village', 'd.name as district')
            ->join('users as b', 'b.nik', '=', 'a.nik')
            ->join('villages as c', 'c.id', '=', 'b.village_id')
            ->join('districts as d', 'd.id', '=', 'c.district_id');


        if ($request->input('search.value') != null) {
            $data = $data->where(function ($q) use ($request) {
                $q->whereRaw('LOWER(a.name) like ? ', ['%' . strtolower($request->input('search.value')) . '%']);
                // $q->whereRaw('LOWER(a.rt) like ? ',['%'.strtolower($request->input('search.value')).'%']);
                // ->orWhereRaw('LOWER(regencies.name) like ? ',['%'.strtolower($request->input('search.value')).'%'])
                // ->orWhereRaw('LOWER(districts.name) like ? ',['%'.strtolower($request->input('search.value')).'%'])
                // ->orWhereRaw('LOWER(villages.name) like ? ',['%'.strtolower($request->input('search.value')).'%'])
                // ->orWhereRaw('LOWER(b.name) like ? ',['%'.strtolower($request->input('search.value')).'%'])
                // ->orWhereRaw('LOWER(c.name) like ? ',['%'.strtolower($request->input('search.value')).'%'])
                // ->orWhereRaw('LOWER(a.created_at) like ? ',['%'.strtolower($request->input('search.value')).'%'])

            });
        }

        if ($request->input('district') != null) {
            $data->where('a.district_id', $request->district);
        }

        if ($request->input('rt') != null) {
            $data->where('a.rt', $request->rt);
        }

        $recordsFiltered = $data->get()->count();
        if ($request->input('length') != -1) $data = $data->skip($request->input('start'))->take($request->input('length'));
        $data = $data->orderBy('a.level_org', 'asc');
        $data = $data->orderBy($orderBy, $request->input('order.0.dir'))->get();

        $recordsTotal = $data->count();

        return response()->json([
            'draw' => $request->input('draw'),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data
        ]);
    }

    public function saveOrgDistrict(Request $request)
    {

        DB::beginTransaction();
        try {

            #cek ketersediaan nik di tb users
            $userTable     = DB::table('users');
            // $cek_nik_user  = $userTable->where('nik', $request->nik)->count();
            $user         = $userTable->select('name', 'photo', 'nik')->where('id', $request->member)->first();

            // if ($cek_nik_user == 0) return redirect()->back()->with(['warning' => 'NIK tidak terdaftar disistem!']);

            #cek jika nik sudah terdaftar di tb org_diagram_village
            $cek_nik_org  = DB::table('org_diagram_district')->where('nik', $user->nik)->where('district_id', $request->district_id)->count();
            if ($cek_nik_org > 0) return redirect()->back()->with(['warning' => 'NIK sudah terdaftar distruktur!']);

            #save to tb org_diagram_district
            DB::table('org_diagram_district')->insert([
                'idx'    => $request->idx,
                'pidx'   => 'KORCAM',
                'title'  => strtoupper($request->jabatan),
                'nik'    => $user->nik,
                'name'   => $user->name,
                'base'   => 'KORCAM',
                'level_org'   => GlobalProvider::generateLevelOrg($request->jabatan),
                'photo'  => $user->photo ?? '',
                'telp'  => $request->telp,
                'regency_id'  => $request->regency_id,
                'district_id' => $request->district_id,
                'created_by' => auth()->guard('admin')->user()->id,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            DB::commit();
            return redirect()->back()->with(['success' => 'Data telah tersimpan!']);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            return redirect()->back()->with(['error' => 'Data gagal tersimpan!' . $e->getMessage()]);
        }
    }

    public function deleteKorCam()
    {

        DB::beginTransaction();
        try {

            $id   = request()->id;

            DB::table('org_diagram_district')->where('id', $id)->delete();


            DB::commit();
            return ResponseFormatter::success([
                'message' => 'Berhasil hapus struktur!'
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            return ResponseFormatter::error([
                'message' => 'Something when wrong!',
                'error'   => $e->getMessage()
            ]);
        }
    }

    public function deleteKorDes()
    {

        DB::beginTransaction();
        try {

            $id   = request()->id;

            DB::table('org_diagram_village')->where('id', $id)->delete();

            DB::commit();
            return ResponseFormatter::success([
                'message' => 'Berhasil hapus struktur!'
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            return ResponseFormatter::error([
                'message' => 'Something when wrong!',
                'error'   => $e->getMessage()
            ]);
        }
    }

    public function updateOrgDistrict()
    {

        DB::beginTransaction();
        try {

            $id   = request()->id;
            $nik  = request()->nik;

            #cek nik di tb users, true
            $userTable     = DB::table('users');
            $cek_nik_user  = $userTable->where('nik', $nik)->count();
            if ($cek_nik_user == 0) return ResponseFormatter::error(['message' => 'NIK tidak terdaftar disistem!']);

            #cek nik di tb org_diagram_rt, false
            $cek_nik_org  = DB::table('org_diagram_district')->where('nik', $nik)->count();
            if ($cek_nik_org > 0) return ResponseFormatter::error(['message' => 'NIK sudah terdaftar distruktur!']);

            #update org
            $user         = $userTable->select('name', 'photo', 'phone_number', 'nik')->where('nik', $nik)->first();

            DB::table('org_diagram_district')->where('id', $id)->update([
                'nik'    => $user->nik,
                'name'   => $user->name,
                'photo'  => $user->photo ?? '',
                'telp'  => $user->phone_number,
            ]);


            DB::commit();
            return ResponseFormatter::success([
                'message' => 'Berhasil update struktur!'
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            return ResponseFormatter::error([
                'message' => 'Something when wrong!',
                'error'   => $e->getMessage()
            ]);
        }
    }

    public function updateOrgVillage()
    {

        DB::beginTransaction();
        try {

            $id   = request()->id;
            $nik  = request()->nik;

            #cek nik di tb users, true
            $userTable     = DB::table('users');
            $cek_nik_user  = $userTable->where('nik', $nik)->count();
            if ($cek_nik_user == 0) return ResponseFormatter::error(['message' => 'NIK tidak terdaftar disistem!']);

            #cek nik di tb org_diagram_rt, false
            $cek_nik_org  = DB::table('org_diagram_village')->where('nik', $nik)->count();
            if ($cek_nik_org > 0) return ResponseFormatter::error(['message' => 'NIK sudah terdaftar distruktur!']);

            #update org
            $user         = $userTable->select('name', 'photo', 'phone_number', 'nik')->where('nik', $nik)->first();

            DB::table('org_diagram_village')->where('id', $id)->update([
                'nik'    => $user->nik,
                'name'   => $user->name,
                'photo'  => $user->photo ?? '',
                'telp'  => $user->phone_number,
            ]);


            DB::commit();
            return ResponseFormatter::success([
                'message' => 'Berhasil update struktur!'
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            return ResponseFormatter::error([
                'message' => 'Something when wrong!',
                'error'   => $e->getMessage()
            ]);
        }
    }

    public function getDataOrgDapil(Request $request)
    {

        // DATATABLE
        $orderBy = 'a.name';
        switch ($request->input('order.0.column')) {
            case '1':
                $orderBy = 'a.name';
                break;
                // case '3':
                //     $orderBy = 'a.rt';
                //     break;
                // case '3':
                //     $orderBy = 'districts.name';
                //     break;
                // case '4':
                //     $orderBy = 'villages.name';
                //     break;
                // case '5':
                //     $orderBy = 'b.name';
                //     break;
                // case '6':
                //     $orderBy = 'c.name';
                //     break;
                // case '7':
                //     $orderBy = 'a.created_at';
                //     break;
        }

        $data = DB::table('org_diagram_dapil as a')
            ->select('a.id', 'a.idx', 'b.address', 'a.title', 'a.nik', 'a.name', 'b.photo', 'a.telp as phone_number', 'c.name as village', 'd.name as district')
            ->join('users as b', 'b.nik', '=', 'a.nik')
            ->join('villages as c', 'c.id', '=', 'b.village_id')
            ->join('districts as d', 'd.id', '=', 'c.district_id');


        if ($request->input('search.value') != null) {
            $data = $data->where(function ($q) use ($request) {
                $q->whereRaw('LOWER(a.name) like ? ', ['%' . strtolower($request->input('search.value')) . '%']);
                // $q->whereRaw('LOWER(a.rt) like ? ',['%'.strtolower($request->input('search.value')).'%']);
                // ->orWhereRaw('LOWER(regencies.name) like ? ',['%'.strtolower($request->input('search.value')).'%'])
                // ->orWhereRaw('LOWER(districts.name) like ? ',['%'.strtolower($request->input('search.value')).'%'])
                // ->orWhereRaw('LOWER(villages.name) like ? ',['%'.strtolower($request->input('search.value')).'%'])
                // ->orWhereRaw('LOWER(b.name) like ? ',['%'.strtolower($request->input('search.value')).'%'])
                // ->orWhereRaw('LOWER(c.name) like ? ',['%'.strtolower($request->input('search.value')).'%'])
                // ->orWhereRaw('LOWER(a.created_at) like ? ',['%'.strtolower($request->input('search.value')).'%'])

            });
        }

        if ($request->input('dapil') != null) {
            $data->where('a.dapil_id', $request->dapil);
        }

        $recordsFiltered = $data->get()->count();
        if ($request->input('length') != -1) $data = $data->skip($request->input('start'))->take($request->input('length'));
        $data = $data->orderBy('a.level_org', 'asc');
        $data = $data->orderBy($orderBy, $request->input('order.0.dir'))->get();

        $recordsTotal = $data->count();

        return response()->json([
            'draw' => $request->input('draw'),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data
        ]);
    }

    public function saveOrgDapil(Request $request)
    {

        DB::beginTransaction();
        try {

            #cek ketersediaan nik di tb users
            $userTable     = DB::table('users');
            // $cek_nik_user  = $userTable->where('nik', $request->nik)->count();
            $user         = $userTable->select('name', 'photo', 'nik')->where('id', $request->member)->first();

            // if ($cek_nik_user == 0) return redirect()->back()->with(['warning' => 'NIK tidak terdaftar disistem!']);

            #cek jika nik sudah terdaftar di tb org_diagram_village
            $cek_nik_org  = DB::table('org_diagram_dapil')->where('nik', $user->nik)->where('dapil_id', $request->dapil_id)->count();
            if ($cek_nik_org > 0) return redirect()->back()->with(['warning' => 'NIK sudah terdaftar distruktur!']);


            #save to tb org_diagram_dapil
            DB::table('org_diagram_dapil')->insert([
                'idx'    => $request->idx,
                'pidx'   => 'KORDAPIL',
                'title'  => strtoupper($request->jabatan),
                'nik'    => $user->nik,
                'name'   => $user->name,
                'base'   => 'KORDAPIL',
                'level_org'   => GlobalProvider::generateLevelOrg($request->jabatan),
                'photo'  => $user->photo ?? '',
                'telp'  => $request->telp,
                'dapil_id'  => $request->dapil_id,
                'created_by' => auth()->guard('admin')->usre()->id
            ]);

            DB::commit();
            return redirect()->back()->with(['success' => 'Data telah tersimpan!']);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            return redirect()->back()->with(['error' => 'Data gagal tersimpan!' . $e->getMessage()]);
        }
    }
    public function deleteKorDapil()
    {

        DB::beginTransaction();
        try {

            $id   = request()->id;

            DB::table('org_diagram_dapil')->where('id', $id)->delete();


            DB::commit();
            return ResponseFormatter::success([
                'message' => 'Berhasil hapus struktur!'
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            return ResponseFormatter::error([
                'message' => 'Something when wrong!',
                'error'   => $e->getMessage()
            ]);
        }
    }

    public function deleteKorPusat()
    {

        DB::beginTransaction();
        try {

            $id   = request()->id;

            DB::table('org_diagram_pusat')->where('id', $id)->delete();


            DB::commit();
            return ResponseFormatter::success([
                'message' => 'Berhasil hapus struktur!'
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            return ResponseFormatter::error([
                'message' => 'Something when wrong!',
                'error'   => $e->getMessage()
            ]);
        }
    }

    public function createOrgPusat()
    {

        $regency = Regency::select('id', 'name')->where('id', 3602)->first();

        #creeate idx
        $cek_count_org = DB::table('org_diagram_pusat')->count();

        $result_new_idx = "";

        if ($cek_count_org > 0) {

            $count_org     = DB::table('org_diagram_pusat')->select('idx')->orderBy('id', 'desc')->first();
            $count_org     = $count_org->idx;

            $exp        = explode(".", $count_org);
            $count_exp  = count($exp);

            if ($count_exp == 1) {

                $result_new_idx = time() . $exp[0] . ".1";
            } else {

                $result_exp = (int) $exp[1] + 1;

                $result_new_idx  = time() . "KORPUSAT." . $result_exp;
            }
        } else {

            $result_new_idx = "KORPUSAT";
        }




        return view('pages.admin.strukturorg.pusat.create', compact('regency', 'result_new_idx'));
    }

    public function saveOrgPusat(Request $request)
    {

        DB::beginTransaction();
        try {

            #cek ketersediaan nik di tb users
            $userTable     = DB::table('users');
            // $cek_nik_user  = $userTable->where('nik', $request->nik)->count();
            $user          = $userTable->select('name', 'photo', 'nik')->where('id', $request->member)->first();

            // if ($cek_nik_user == 0) return redirect()->back()->with(['warning' => 'NIK tidak terdaftar disistem!']);

            #cek jika nik sudah terdaftar di tb org_diagram_village
            $cek_nik_org  = DB::table('org_diagram_pusat')->where('nik', $user->nik)->count();
            if ($cek_nik_org > 0) return redirect()->back()->with(['warning' => 'NIK sudah terdaftar distruktur!']);

            //jika jabatan selain ketua                    
            // $gf = new GlobalProvider();
            // $generateLevel = $gf->generateLevelPengurus($request->jabatan,'pusat',null,null,null);

            // if ($generateLevel == 0) {

            //     return 'Belum ada ketua, buat terlebih dahulu!';

            // }

            #save to tb org_diagram_pusat
            DB::table('org_diagram_pusat')->insert([
                'idx'    => $request->idx,
                'pidx'   => 'KORPUSAT',
                'title'  => strtoupper($request->jabatan),
                'nik'    => $user->nik,
                'name'   => $user->name,
                'base'   => 'KORPUSAT',
                'level_org'   => GlobalProvider::generateLevelOrg($request->jabatan),
                'photo'  => $user->photo ?? '',
                'telp'  => $request->telp,
                'created_by' => auth()->guard('admin')->usre()->id,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            DB::commit();
            return redirect()->back()->with(['success' => 'Data telah tersimpan!']);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            return redirect()->back()->with(['error' => 'Data gagal tersimpan!' . $e->getMessage()]);
        }
    }

    public function getDataOrgPusat(Request $request)
    {

        // DATATABLE
        $orderBy = 'a.name';
        switch ($request->input('order.0.column')) {
            case '1':
                $orderBy = 'a.name';
                break;
                // case '3':
                //     $orderBy = 'a.rt';
                //     break;
                // case '3':
                //     $orderBy = 'districts.name';
                //     break;
                // case '4':
                //     $orderBy = 'villages.name';
                //     break;
                // case '5':
                //     $orderBy = 'b.name';
                //     break;
                // case '6':
                //     $orderBy = 'c.name';
                //     break;
                // case '7':
                //     $orderBy = 'a.created_at';
                //     break;
        }

        $data = DB::table('org_diagram_pusat as a')
            ->select('a.id', 'a.idx', 'b.address', 'a.title', 'a.nik', 'a.name', 'b.photo', 'a.telp as phone_number', 'c.name as village', 'd.name as district')
            ->join('users as b', 'b.nik', '=', 'a.nik')
            ->join('villages as c', 'c.id', '=', 'b.village_id')
            ->join('districts as d', 'd.id', '=', 'c.district_id');


        if ($request->input('search.value') != null) {
            $data = $data->where(function ($q) use ($request) {
                $q->whereRaw('LOWER(a.name) like ? ', ['%' . strtolower($request->input('search.value')) . '%']);
                // $q->whereRaw('LOWER(a.rt) like ? ',['%'.strtolower($request->input('search.value')).'%']);
                // ->orWhereRaw('LOWER(regencies.name) like ? ',['%'.strtolower($request->input('search.value')).'%'])
                // ->orWhereRaw('LOWER(districts.name) like ? ',['%'.strtolower($request->input('search.value')).'%'])
                // ->orWhereRaw('LOWER(villages.name) like ? ',['%'.strtolower($request->input('search.value')).'%'])
                // ->orWhereRaw('LOWER(b.name) like ? ',['%'.strtolower($request->input('search.value')).'%'])
                // ->orWhereRaw('LOWER(c.name) like ? ',['%'.strtolower($request->input('search.value')).'%'])
                // ->orWhereRaw('LOWER(a.created_at) like ? ',['%'.strtolower($request->input('search.value')).'%'])

            });
        }

        if ($request->input('dapil') != null) {
            $data->where('a.dapil_id', $request->dapil);
        }

        $recordsFiltered = $data->get()->count();
        if ($request->input('length') != -1) $data = $data->skip($request->input('start'))->take($request->input('length'));
        $data = $data->orderBy('a.level_org', 'asc');
        $data = $data->orderBy($orderBy, $request->input('order.0.dir'))->get();

        $recordsTotal = $data->count();

        return response()->json([
            'draw' => $request->input('draw'),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data
        ]);
    }
    public function updateOrgPusat()
    {

        DB::beginTransaction();
        try {

            $id   = request()->id;
            $nik  = request()->nik;

            #cek nik di tb users, true
            $userTable     = DB::table('users');
            $cek_nik_user  = $userTable->where('nik', $nik)->count();
            if ($cek_nik_user == 0) return ResponseFormatter::error(['message' => 'NIK tidak terdaftar disistem!']);

            #cek nik di tb org_diagram_rt, false
            $cek_nik_org  = DB::table('org_diagram_pusat')->where('nik', $nik)->count();
            if ($cek_nik_org > 0) return ResponseFormatter::error(['message' => 'NIK sudah terdaftar distruktur!']);

            #update org
            $user         = $userTable->select('name', 'photo', 'phone_number', 'nik')->where('nik', $nik)->first();

            DB::table('org_diagram_pusat')->where('id', $id)->update([
                'nik'    => $user->nik,
                'name'   => $user->name,
                'photo'  => $user->photo ?? '',
                'telp'  => $user->phone_number,
            ]);


            DB::commit();
            return ResponseFormatter::success([
                'message' => 'Berhasil update struktur!'
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            return ResponseFormatter::error([
                'message' => 'Something when wrong!',
                'error'   => $e->getMessage()
            ]);
        }
    }

    public function reportExcel(Request $request)
    {

        $dapil_id    = $request->dapil_id;
        $district_id = $request->district_id;
        $village_id  = $request->village_id;
        $rt          = $request->rt;

        // dd([$dapil_id, $district_id, $village_id, $rt]);

        if ($rt == null and $dapil_id != null and $district_id != null and $village_id != null) {

            #report by desa       
            $village = DB::table('villages')->select('name')->where('id', $village_id)->first();
            return $this->excel->download(new KorDesExport($village_id), 'TIM KOORDINATOR DESA ' . $village->name . '.xls');
        } elseif ($village_id == null and $rt == null and $district_id != null) {


            $district = DB::table('districts')->select('name')->where('id', $district_id)->first();
            return $this->excel->download(new KorCamExport($district_id), 'TIM KOORDINATOR KECAMATAN ' . $district->name . '.xls');
        } else {

            $org = DB::table('org_diagram_dapil')->select('name', 'base', 'title')->where('dapil_id', $dapil_id)->get();
            return $org;
        }
    }

    public function reportOrgRTExcel(Request $request)
    {

        $village_id  = $request->village_id;
        // $village = DB::table('villages')->select('name')->where('id', $village_id)->first();
        $village = Village::with(['district'])->where('id', $village_id)->first();

        if ($request->report_type == 'Download Korte + Anggota PDF') {

            // get data kordes by village_id untuk absensi

            $kordes = DB::table('org_diagram_village as a')
                ->select('b.name', 'a.title')
                ->join('users as b', 'a.nik', '=', 'b.nik')
                ->where('a.village_id', $village_id)
                ->orderBy('level_org', 'asc')
                ->get();


            // get data korte by village_id untuk absensi
            $kortes    = DB::table('org_diagram_rt as a')
                ->select(
                    'b.id',
                    'b.name',
                    'a.base',
                    'a.title',
                    'a.rt',
                    'b.gender',
                    'a.idx',
                    DB::raw('(select count(*) from org_diagram_rt where pidx = a.idx and base = "ANGGOTA") as total_members ')
                )
                ->join('users as b', 'a.nik', '=', 'b.nik')
                ->where('a.village_id', $village_id)
                ->whereNotNull('a.nik')
                ->where('a.base', 'KORRT')
                ->orderBy('a.rt', 'asc')
                ->get();


            // buat direktori memberkorte sebanyak data korte

            $directory = public_path('/docs/pdf/ANGGOTA KORTE DS.' . $village->name);

            if (File::exists($directory)) {

                File::deleteDirectory($directory); // hapus dir nya juga
                File::delete($directory . '.zip'); // hapus zip nya juga 
            }

            File::makeDirectory(public_path('/docs/pdf/ANGGOTA KORTE DS.' . $village->name));

            foreach ($kortes as $korte) {

                $path = '/docs/pdf/ANGGOTA KORTE DS.' . $village->name . '/';

                //get members by korte
                // get data anggota by korte
                $members = DB::table('org_diagram_rt as a')
                    ->select('a.base', 'a.rt', 'b.name', 'c.name as village', 'd.name as district', 'b.address', 'a.telp')
                    ->join('users as b', 'b.nik', '=', 'a.nik')
                    ->join('villages as c', 'c.id', '=', 'a.village_id')
                    ->join('districts as d', 'd.id', '=', 'a.district_id')
                    ->where('a.pidx', $korte->idx)
                    ->where('a.base', 'ANGGOTA')
                    ->get();


                $no = 1;

                $fileName = 'ANGGOTA KORTE RT. ' . $korte->rt . ' (' . $korte->name . ') DS.' . $village->name . '.pdf';

                $pdf = PDF::LoadView('pages.report.memberbykorte-all', compact('village', 'members', 'korte', 'no'))->setPaper('a4');

                $pdfFilePath = public_path('/docs/pdf/ANGGOTA KORTE DS.' . $village->name . '/' . $fileName);
                file_put_contents($pdfFilePath, $pdf->output());
            }


            $files = glob(public_path('/docs/pdf/ANGGOTA KORTE DS.' . $village->name . '/*'));
            $createZip = public_path('/docs/pdf/ANGGOTA KORTE DS.' . $village->name . '.zip');
            Zipper::make(public_path($createZip))->add($files)->close();

            return response()->download(public_path($createZip));

            // File::deleteDirectory($directory); // hapus dir nya juga
            // File::delete($directory.'.zip'); // hapus zip nya juga

            // return redirect()->back();

        } elseif ($request->report_type == 'Download Korte + Anggota') {

            $kortes    = DB::table('org_diagram_rt as a')
                ->select('b.id', 'a.name', 'a.base', 'a.title', 'a.rt', 'b.gender', 'a.telp', 'a.idx', 'a.village_id')
                ->join('users as b', 'a.nik', '=', 'b.nik')
                ->where('a.village_id', $village_id)
                ->whereNotNull('a.nik')
                ->where('a.base', 'KORRT')
                ->orderBy('a.rt', 'asc')
                ->get();

            // return $this->excel->download(new KorteExportWithSheet($kortes),'ANGGOTA PER KORTE' . $village->name . '.xls');
            return (new KorteExportWithSheet($kortes))->download('TIM KOORDINATOR RT + ANGGOTA' . $village->name . '.xls');
        } elseif ($request->report_type == 'Download Absensi Korte Per Desa PDF') {

            // get data kordes by village
            $abs_kordes = DB::table('org_diagram_village')
                ->select('name', 'title')
                ->where('village_id', $village->id)
                ->whereNotNull('nik')
                ->orderBy('level_org', 'asc')
                ->get();

            // jika title ketua tidak ada maka tambahkan value array yang memiliki title kordes 
            $kordes = [];
            $cek_kordes = $this->searchArrayValue($abs_kordes, 'KETUA');
            if ($cek_kordes == null) {

                // membuat object baru dengan collection 
                $array_value = collect([
                    (object)[
                        'name' => '',
                        'title' => 'KETUA'
                    ]
                ]);

                $sorted = $array_value->sortBy('name');
                $sorted->values()->all();
                $kordes = $sorted->merge($abs_kordes); // gabungkan object baru dengan collectiono yg ada 

            } else {

                $kordes = $abs_kordes;
            }


            $abs_kortes    = DB::table('org_diagram_rt as a')
                ->select('a.name', 'a.title', 'a.rt', 'b.gender', 'a.telp', 'b.address')
                ->join('users as b', 'a.nik', '=', 'b.nik')
                ->where('a.village_id', $village_id)
                ->whereNotNull('a.nik')
                ->where('a.base', 'KORRT')
                ->orderBy('a.rt', 'asc')
                ->get();

            $no = 1;

            $pdf = PDF::LoadView('pages.report.korte', compact('village', 'kordes', 'abs_kortes', 'no'))->setPaper('a4');
            return $pdf->download('ABSENSI TIM KORTE DESA ' . $village->name . '.pdf');
        } elseif ($request->report_type == 'Download Catatan Korte PDF') {

            // get data korte 
            $dataKorte    = DB::table('org_diagram_rt as a')
                ->select(
                    'a.name',
                    'a.title',
                    'a.rt',
                    'a.idx',
                    DB::raw("(select count(*) from org_diagram_rt where pidx = a.idx and base = 'ANGGOTA') as total_member")
                )
                ->join('users as b', 'a.nik', '=', 'b.nik')
                ->join('villages as c', 'a.village_id', '=', 'c.id')
                ->where('a.village_id', $village_id)
                ->whereNotNull('a.nik')
                ->where('a.base', 'KORRT')
                ->orderBy('a.rt', 'asc')
                ->get();

            // rincian 
            // get data korte by rt, jumlah orang / jumlah anggota , kelompokkan by korte 
            $kortes = DB::table('org_diagram_rt as a')
                ->select(
                    'a.village_id',
                    'a.rt',
                    DB::raw('count(a.id) as jml_korte'),
                    // joinkan dengan user by nik = nik untuk menghitung hanya data yang tersedia sebagai anggota
                    DB::raw(
                        "(
												select count(tb1.id) from org_diagram_rt as tb1
												join users as tb2 on tb1.nik = tb2.nik
												where tb1.village_id = a.village_id and tb1.rt = a.rt and tb1.base = 'ANGGOTA' 
												group by tb1.rt
											 ) as jml_members"
                    )
                )
                ->join('users as b', 'a.nik', '=', 'b.nik')
                ->where('a.base', 'KORRT')
                ->where('a.village_id', $village_id)
                ->groupBy('a.village_id', 'a.rt')
                ->orderBy('a.rt', 'asc')
                ->get();

            // catatan 
            // rt 1
            // jumlah anggota
            // jumlah korte
            // keterangan = kekurangan korte 
            // $catatanKortes = DB::table('org_diagram_rt as a')
            // ->select('a.rt','a.village_id')
            // ->join('users as b','a.nik','=','b.nik')
            // ->where('a.base','KORRT')
            // ->where('a.village_id', $village_id)
            // ->groupBy('a.rt','a.village_id')
            // ->orderBy('a.rt','asc')
            // ->get();
            $catatanKortes = DB::table('users as a')
                ->select(
                    'a.rt',
                    'a.village_id',
                    DB::raw("(SELECT COUNT(*) from users where rt = a.rt and village_id = a.village_id) as total")
                )
                ->leftJoin('org_diagram_rt as b', 'b.nik', '=', 'a.nik')
                ->where('a.village_id', $village_id)
                ->groupBy('a.rt', 'a.village_id')
                ->orderBy('a.rt', 'asc')
                ->get();


            $resultCatatanKorte = [];
            foreach ($catatanKortes as $ckorte) {

                $countKorte   = DB::table('org_diagram_rt as a')
                    ->join('users as b', 'a.nik', '=', 'b.nik')
                    ->where('a.village_id', $village_id)
                    ->where('a.rt', $ckorte->rt)
                    ->where('a.base', 'KORRT')
                    ->where('b.nik', '!=', null)
                    ->count();

                $max_anggota      = 25;
                $kekurangan_korte = ceil($ckorte->total / $max_anggota);
                $hasil_kekurangan_korte = $kekurangan_korte - $countKorte;
                $hasil_kekurangan_korte = $hasil_kekurangan_korte < 0 ? 0 : $kekurangan_korte - $countKorte;

                $resultCatatanKorte[] = [
                    'rt' => $ckorte->rt,
                    'jml_member' => $ckorte->total,
                    'jml_korte_per_village' => $countKorte,
                    'kekurangan_korte' => $hasil_kekurangan_korte
                ];
            }

            // total kekurangan korte per rt 
            $total_kekurangan_korte_per_rt = collect($resultCatatanKorte)->sum(function ($q) {
                return $q['kekurangan_korte'];
            });

            // belum ada korte
            $korteIsNotYets = DB::table('users as a')
                ->select(
                    'a.rt',
                    DB::raw("(SELECT COUNT(*) from org_diagram_rt where rt = a.rt and village_id = $village_id GROUP by rt) as total_korte"),
                    DB::raw("(SELECT COUNT(*) from users WHERE rt = a.rt and village_id = $village_id) as total_member")
                )
                ->where('a.village_id', $village_id)
                ->where('a.rt', '!=', 0)
                ->groupBy('a.rt')
                ->get();

            $resultKorteIsNotYets = [];

            foreach ($korteIsNotYets as $korteIsNotYet) {

                // hitung jumlah korte yang dibutuhkan
                $korte_needed = ceil($korteIsNotYet->total_member / 25);
                if ($korteIsNotYet->total_korte == null) {
                    $resultKorteIsNotYets[] = [
                        'rt' => $korteIsNotYet->rt,
                        'jml_member' => $korteIsNotYet->total_member,
                        'dibutuhkan_korte' => $korte_needed
                    ];
                }
            }

            // total kekurangan korte 
            $total_kekurangan_korte_belum_ada = collect($korteIsNotYets)->sum(function ($q) {
                if ($q->total_korte == null) return ceil($q->total_member / 25);
            });


            // total kekurangan rt per desa
            $total_kekurangan_korte_per_desa = $total_kekurangan_korte_per_rt + $total_kekurangan_korte_belum_ada;

            $village = Village::with(['district'])->where('id', $village_id)->first();

            $kordes  = DB::table('org_diagram_village as a')
                ->select('a.title', 'b.name')
                ->join('users as b', 'a.nik', '=', 'b.nik')
                ->where('a.village_id', $village_id)
                ->orderBy('a.level_org')->get();


            $results = [
                'dataKorte' => $dataKorte,
                'rincian' => $kortes,
                'catatan' => $resultCatatanKorte,
                'belum_ada_korte' => $resultKorteIsNotYets,
                'total_kekurangan_korte_per_desa' => $total_kekurangan_korte_per_desa
            ];

            $no = 1;

            // jumlah belum ada korte nya 

            // total kekurangan korte
            $pdf = PDF::LoadView('pages.report.korteandrincian', compact('village', 'results', 'no', 'kordes'))->setPaper('a4');
            return $pdf->download('TIM KORDES DAN KORTE DESA ' . $village->name . '.pdf');
        } elseif ($request->report_type == 'Download Surat Undangan Korte Per Desa PDF') {

            $jam = '07:30 WIB s/d 11:30 WIB';
            $lokasi = "SEKERTARIAT JARINGAN DULUR AAW - BINUANGEN";
            $hari = "Jum'at, 01 September 2023";
            $lok_surat = 'Binuangeun';


            $village = DB::table('villages')->select('name')->where('id', $village_id)->first();

            if ($request->rt != '') {

                $korte = DB::table('org_diagram_rt as a')
                    ->select('a.nik')
                    ->join('users as b', 'a.nik', '=', 'b.nik')
                    ->where('a.base', 'KORRT')
                    ->where('a.village_id', $village_id)
                    ->where('a.rt', $request->rt)
                    ->get();
            } else {

                $korte = DB::table('org_diagram_rt as a')
                    ->select('a.nik')
                    ->join('users as b', 'a.nik', '=', 'b.nik')
                    ->where('a.base', 'KORRT')
                    ->where('a.village_id', $village_id)
                    ->get();
            }



            // get dat korcam by nik
            $directory = public_path('/docs/suratundangan/korte/pdf/SURAT UNDANGAN TIM KORTE DS.' . $village->name);

            if (File::exists($directory)) {
                File::deleteDirectory($directory); // hapus dir nya juga
            }

            File::makeDirectory(public_path('/docs/suratundangan/korte/pdf/SURAT UNDANGAN TIM KORTE DS.' . $village->name));

            foreach ($korte as $val) {

                $path = '/docs/suratundangan/korte/pdf/SURAT UNDANGAN TIM KORTE DS.' . $village->name . '/';

                // get nama tim by nik 
                $kordesItem = DB::table('org_diagram_rt as a')
                    ->select('b.name')
                    ->join('users as b', 'b.nik', '=', 'a.nik')
                    ->where('a.village_id', $village_id)
                    ->where('a.nik', $val->nik)
                    ->first();

                $tim = $kordesItem;

                $fileName = 'SURAT UNDANGAN TIM KORTE DS. ' . $kordesItem->name . '.pdf';

                $pdf  = PDF::LoadView('pages.report.surat-undangan', compact('tim', 'hari', 'jam', 'lokasi', 'lok_surat'))->setPaper('a4');
                $pdfFilePath = public_path('/docs/suratundangan/korte/pdf/SURAT UNDANGAN TIM KORTE DS.' . $village->name . '/' . $fileName);

                file_put_contents($pdfFilePath, $pdf->output());
            }

            $files = glob(public_path('/docs/suratundangan/korte/pdf/SURAT UNDANGAN TIM KORTE DS.' . $village->name . '/*'));
            $createZip = public_path('/docs/suratundangan/korte/pdf/SURAT UNDANGAN TIM KORTE DS.' . $village->name . '.zip');
            Zipper::make(public_path($createZip))->add($files)->close();

            return response()->download(public_path($createZip));
        }elseif ($request->report_type == 'Download Anggota Belum Tercover Kortps'){


            $orgDiagramModel = new OrgDiagram();

            if(isset($village_id) AND !isset($request->rt)){

                $village = Village::select('name')->where('id', $village_id)->first();
                $anggota         = $orgDiagramModel->getDataAnggotaBelumterCoverKortpsByVillage($village_id);
                // dd($anggota);
                return $this->excel->download(new AnggotaBelumTercoverKortps($anggota, $village_id), 'ANGGOTA BELUM TERCOVER DS.' . $village->name . '.xls');

            }elseif(isset($village_id) AND isset($request->rt)){

                return 'PILIH PER DESA SAJA';

                //  $anggota         = $orgDiagramModel->getDataAnggotaBelumterCoverKortpsByVillageAndRt($village_id, $request->rt);
                // dd($anggota);

                //  return $this->excel->download(new AnggotaBelumTercoverKortps($anggota), 'ANGGOTA BELUM TERCOVER DS.' . $village->name .', RT. '.$request->rt.'.xls');

            }else{ 

                $district = District::select('name')->where('id', $request->districtid)->first();
                $anggota         = $orgDiagramModel->getDataAnggotaBelumterCoverKortpsByDistrictId($request->districtid);
                return $this->excel->download(new AnggotaBelumTercoverKortps($anggota, $village_id), 'ANGGOTA BELUM TERCOVER KEC.' . $district->name .'.xls');
            }

        }elseif($request->report_type == 'Download KTA Kortps Per Desa'){

            if(isset($village_id)){

                $village = Village::select('name')->where('id', $village_id)->first();
                $directory = public_path('/docs/kta/korte/pdf/KTA-ANGGOTA DS.' . $village->name);

                if (File::exists($directory)) {
                        File::deleteDirectory($directory); // hapus dir nya juga
                    }

                File::makeDirectory(public_path('/docs/kta/korte/pdf/KTA-ANGGOTA DS.' . $village->name));

                // GET DATA KORTE BY DESA
                $korte = DB::table('org_diagram_rt as a')
                            ->select('a.idx','b.name')
                            ->join('users as b', 'a.nik', '=', 'b.nik')
                            ->where('a.base', 'KORRT')
                            ->where('a.village_id', $village_id)
                            ->get();

                $OrgDiagram = new OrgDiagram();


                foreach ($korte as $value) {
            
                    $path = '/docs/kta/korte/pdf/KTA-ANGGOTA DS.' . $village->name . '/';

                        // get data anggota by korte
                      
                        $members    = $OrgDiagram->getDataMemberByKorteIdx($value->idx);

                         // mengelompokan collection sebanyak 5 data per kelompok;
                        $group_members = $members->chunk(3);

                        $group_members->each(function($chunk){
                            $chunk->toArray();
                        });
                 
                        $no = 1;

                        $gF = new GlobalProvider();

                        $fileName = 'KTA-ANGGOTA KORTPS.' . $value->name . '.pdf';

                       $pdf = PDF::LoadView('pages.report.ktamemberbykortps', compact('group_members','gF'))->setPaper('a4','landscape');

                       $pdfFilePath = public_path($path.$fileName);
                       file_put_contents($pdfFilePath, $pdf->output());

                }

                $files = glob(public_path($path.'*'));
                $createZip = public_path('/docs/kta/korte/pdf/KTA-ANGGOTA DS.' .$village->name  . '.zip');
                Zipper::make(public_path($createZip))->add($files)->close();

                return response()->download(public_path($createZip));

            }else{

                return redirect()->back()->with(['error' => 'Pilih desa terlebih dahulu']); 
            }
        }else{
            #report by desa 

            return $this->excel->download(new KorteExport($village_id), 'TIM KOORDINATOR RT ' . $village->name . '.xls');
        }
    }

    public function searchArrayValue($data, $field)
    {

        foreach ($data as $row) {
            if ($row->title == $field)
                return $row->title;
        }
    }

    public function searchArrayValueTim($data, $field)
    {

        foreach ($data as $row) {
            if ($row->JABATAN == $field)
                return $row->JABATAN;
        }
    }


    public function reportOrgDistrictExcel(Request $request)
    {
        $district_id = $request->district_id;
        $dapil_id    = $request->dapil_id;

        if ($request->report_type == 'Download Excel') {
            // dd([$dapil_id, $district_id, $village_id, $rt]);
            $district = DB::table('districts')->select('name')->where('id', $district_id)->first();
            return $this->excel->download(new KorCamExport($district_id), 'TIM KOORDINATOR KECAMATAN ' . $district->name . '.xls');
        } elseif ($request->report_type == 'Download Surat Undangan Per Kecamatan') {

            $jam = '08:30 WIB s/d selesai';


            if ($dapil_id == 12 || $dapil_id == 13) { // dapil 5 dan 6

                $jam = '08:30 WIB s/d selesai';
                $lokasi = "SEKERTARIAT JARINGAN DULUR AAW - BINUANGEN";
                $hari = 'Minggu,20 Agustus 2023';
                $lok_surat = 'Binuangeun';
            } else { // dapil 4
                $lokasi = "KP. BAYAH TUGU, RT 02 / RW 09 - DESA BAYAH BARAT. KECAMATAN BAYAH. LEBAK - BANTEN (KANTOR SEKRETARIAT JALUR AAW BAYAH)";
                $lok_surat = 'Bayah';
                $hari = 'Selasa,22 Agustus 2023';
                if ($district_id == 3602031 || $district_id == 3602030) {
                    $jam = '08:30 WIB s/d 12.00 WIB'; // sesi 1
                } else {
                    $jam = '12:30 WIB s/d 16.00 WIB';
                }
            }

            $district = DB::table('districts')->select('name')->where('id', $district_id)->first();
            // get data korcam by kecamatan
            $korcam = DB::table('org_diagram_district as a')
                ->select('a.nik')
                ->join('users as b', 'a.nik', '=', 'b.nik')
                ->where('a.district_id', $district_id)
                ->get();

            // get dat korcam by nik
            $directory = public_path('/docs/suratundangan/korcam/pdf/SURAT UNDANGAN TIM KORCAM KEC.' . $district->name);

            if (File::exists($directory)) {
                File::deleteDirectory($directory); // hapus dir nya juga
            }

            File::makeDirectory(public_path('/docs/suratundangan/korcam/pdf/SURAT UNDANGAN TIM KORCAM KEC.' . $district->name));

            foreach ($korcam as $val) {

                $path = '/docs/suratundangan/korcam/pdf/SURAT UNDANGAN TIM KORCAM KEC.' . $district->name . '/';

                // get nama tim by nik 
                $korcamItem = DB::table('org_diagram_district as a')
                    ->select('b.rt', 'b.name', 'b.address', 'c.name as village', 'd.name as district', 'b.rw', 'a.telp', 'b.code')
                    ->join('users as b', 'b.nik', '=', 'a.nik')
                    ->join('villages as c', 'c.id', '=', 'b.village_id')
                    ->join('districts as d', 'd.id', '=', 'a.district_id')
                    ->where('a.district_id', $district_id)
                    ->where('a.nik', $val->nik)
                    ->first();

                $tim = $korcamItem;

                $fileName = 'SURAT UNDANGAN TIM KORCAM KEC. ' . $korcamItem->name . '.pdf';

                $pdf  = PDF::LoadView('pages.report.surat-undangan', compact('tim', 'jam', 'lokasi', 'hari', 'lok_surat'))->setPaper('a4');
                $pdfFilePath = public_path('/docs/suratundangan/korcam/pdf/SURAT UNDANGAN TIM KORCAM KEC.' . $district->name . '/' . $fileName);

                file_put_contents($pdfFilePath, $pdf->output());
            }

            $files = glob(public_path('/docs/suratundangan/korcam/pdf/SURAT UNDANGAN TIM KORCAM KEC.' . $district->name . '/*'));
            $createZip = public_path('/docs/suratundangan/korcam/pdf/SURAT UNDANGAN TIM KORCAM KEC.' . $district->name . '.zip');
            Zipper::make(public_path($createZip))->add($files)->close();

            return response()->download(public_path($createZip));
        } else {

            $district = DB::table('districts')->select('name')->where('id', $district_id)->first();
            // get data korcam by kecamatan
            $korcam = DB::table('org_diagram_district as a')
                ->select('a.nik')
                ->join('users as b', 'a.nik', '=', 'b.nik')
                ->where('a.district_id', $district_id)
                ->get();

            // get dat korcam by nik
            $directory = public_path('/docs/suratpernyataan/korcam/pdf/SURAT PERNYATAAN TIM KORCAM KEC.' . $district->name);

            if (File::exists($directory)) {
                File::deleteDirectory($directory); // hapus dir nya juga
            }

            File::makeDirectory(public_path('/docs/suratpernyataan/korcam/pdf/SURAT PERNYATAAN TIM KORCAM KEC.' . $district->name));

            foreach ($korcam as $val) {

                $path = '/docs/suratpernyataan/korcam/pdf/SURAT PERNYATAAN TIM KORCAM KEC.' . $district->name . '/';

                // get nama tim by nik 
                $korcamItem = DB::table('org_diagram_district as a')
                    ->select('b.rt', 'b.name', 'b.address', 'c.name as village', 'd.name as district', 'b.rw', 'a.telp', 'b.code')
                    ->join('users as b', 'b.nik', '=', 'a.nik')
                    ->join('villages as c', 'c.id', '=', 'b.village_id')
                    ->join('districts as d', 'd.id', '=', 'a.district_id')
                    ->where('a.district_id', $district_id)
                    ->where('a.nik', $val->nik)
                    ->first();

                $korte = $korcamItem;

                $fileName = 'SURAT PERNYATAAN TIM KORCAM KEC. ' . $korcamItem->name . '.pdf';

                $pdf  = PDF::LoadView('pages.report.suratpernyataantim', compact('korte'))->setPaper('a4');
                $pdfFilePath = public_path('/docs/suratpernyataan/korcam/pdf/SURAT PERNYATAAN TIM KORCAM KEC.' . $district->name . '/' . $fileName);

                file_put_contents($pdfFilePath, $pdf->output());
            }

            $files = glob(public_path('/docs/suratpernyataan/korcam/pdf/SURAT PERNYATAAN TIM KORCAM KEC.' . $district->name . '/*'));
            $createZip = public_path('/docs/suratpernyataan/korcam/pdf/SURAT PERNYATAAN TIM KORCAM KEC.' . $district->name . '.zip');
            Zipper::make(public_path($createZip))->add($files)->close();

            return response()->download(public_path($createZip));
        }
    }

    public function reportOrgVillagetExcel(Request $request)
    {

        $village_id  = $request->village_id;
        $district_id  = $request->district_id;
        $dapil_id     = $request->dapil_id;
        $report_type  = $request->report_type;

        if ($report_type == 'Download Excel') {

            // dd([$dapil_id, $district_id, $village_id, $rt]);
            $village = DB::table('villages')->select('name')->where('id', $village_id)->first();
            return $this->excel->download(new KorDesExport($village_id), 'TIM KOORDINATOR DESA ' . $village->name . '.xls');
        } elseif ($report_type == 'Download Surat Pernyataan Kordes Per Desa PDF') {



            $village = DB::table('villages')->select('name')->where('id', $village_id)->first();

            // get korde per kecamatan

            // get data korte by idx 
            $kordes = DB::table('org_diagram_village as a')
                ->select('a.nik', 'a.name')
                ->join('users as b', 'a.nik', '=', 'b.nik')
                ->where('a.village_id', $village_id)
                ->get();

            // buat direktori memberkorte sebanyak data korte

            $directory = public_path('/docs/suratpernyataan/kordes/pdf/SURAT PERNYATAAN TIM KORDES DS.' . $village->name);

            if (File::exists($directory)) {

                File::deleteDirectory($directory); // hapus dir nya juga
                File::delete($directory . '.zip'); // hapus zip nya juga 
            }

            File::makeDirectory(public_path('/docs/suratpernyataan/kordes/pdf/SURAT PERNYATAAN TIM KORDES DS.' . $village->name));

            foreach ($kordes as $val) {

                $path = '/docs/suratpernyataan/kordes/pdf/SURAT PERNYATAAN TIM KORDES DS.' . $village->name . '/';

                // get nama tim by nik 
                $kordesItem = DB::table('org_diagram_village as a')
                    ->select('b.rt', 'b.name', 'b.address', 'c.name as village', 'd.name as district', 'b.rw', 'a.telp', 'b.code')
                    ->join('users as b', 'b.nik', '=', 'a.nik')
                    ->join('villages as c', 'c.id', '=', 'a.village_id')
                    ->join('districts as d', 'd.id', '=', 'a.district_id')
                    ->where('a.village_id', $village_id)
                    ->where('a.nik', $val->nik)
                    ->first();

                $korte = $kordesItem;

                $fileName = 'SURAT PERNYATAAN TIM KORDES . ' . $kordesItem->name . '.pdf';

                $pdf  = PDF::LoadView('pages.report.suratpernyataantim', compact('korte'))->setPaper('a4');
                $pdfFilePath = public_path('/docs/suratpernyataan/kordes/pdf/SURAT PERNYATAAN TIM KORDES DS.' . $village->name . '/' . $fileName);

                file_put_contents($pdfFilePath, $pdf->output());
            }

            $files = glob(public_path('/docs/suratpernyataan/kordes/pdf/SURAT PERNYATAAN TIM KORDES DS.' . $village->name . '/*'));
            $createZip = public_path('/docs/suratpernyataan/kordes/pdf/SURAT PERNYATAAN TIM KORDES DS.' . $village->name . '.zip');
            Zipper::make(public_path($createZip))->add($files)->close();

            return response()->download(public_path($createZip));
        } elseif ($report_type == 'Download Surat Undangan Per Desa') {

            $jam = '08:30 WIB s/d selesai';

            if ($dapil_id == 12 || $dapil_id == 13) { // dapil 5 dan 6

                $jam = '08:30 WIB s/d selesai';
                $lokasi = "SEKERTARIAT JARINGAN DULUR AAW - BINUANGEN";
                $hari = 'Minggu,20 Agustus 2023';
                $lok_surat = 'Binuangeun';
            } else { // dapil 4
                $lokasi = "KP. BAYAH TUGU, RT 02 / RW 09 - DESA BAYAH BARAT. KECAMATAN BAYAH. LEBAK - BANTEN (KANTOR SEKRETARIAT JARINGAN DULUR AAW BAYAH)";
                $lok_surat = 'Bayah';
                $hari = 'Selasa,22 Agustus 2023';

                if ($district_id == 3602031 || $district_id == 3602030) {
                    $jam = '08:30 WIB s/d 12.00 WIB'; // sesi 1
                } else {
                    $jam = '12:30 WIB s/d 16.00 WIB';
                }
            }



            $village = DB::table('villages')->select('name')->where('id', $village_id)->first();
            // get data korcam by kecamatan
            $kordes = DB::table('org_diagram_village as a')
                ->select('a.nik')
                ->join('users as b', 'a.nik', '=', 'b.nik')
                ->where('a.village_id', $village_id)
                ->get();

            // get dat korcam by nik
            $directory = public_path('/docs/suratundangan/kordes/pdf/SURAT UNDANGAN TIM KORDES DS.' . $village->name);

            if (File::exists($directory)) {
                File::deleteDirectory($directory); // hapus dir nya juga
            }

            File::makeDirectory(public_path('/docs/suratundangan/kordes/pdf/SURAT UNDANGAN TIM KORDES DS.' . $village->name));

            foreach ($kordes as $val) {
                $path = '/docs/suratundangan/kordes/pdf/SURAT UNDANGAN TIM KORDES DS.' . $village->name . '/';

                // get nama tim by nik 
                $kordesItem = DB::table('org_diagram_village as a')
                    ->select('b.name')
                    ->join('users as b', 'b.nik', '=', 'a.nik')
                    ->where('a.village_id', $village_id)
                    ->where('a.nik', $val->nik)
                    ->first();

                $tim = $kordesItem;

                $fileName = 'SURAT UNDANGAN TIM KORDES (' . $kordesItem->name . ').pdf';

                $pdf  = PDF::LoadView('pages.report.surat-undangan', compact('tim', 'jam', 'lokasi', 'lok_surat', 'hari'))->setPaper('a4');
                $pdfFilePath = public_path('/docs/suratundangan/kordes/pdf/SURAT UNDANGAN TIM KORDES DS.' . $village->name . '/' . $fileName);

                file_put_contents($pdfFilePath, $pdf->output());
            }

            $files = glob(public_path('/docs/suratundangan/kordes/pdf/SURAT UNDANGAN TIM KORDES DS.' . $village->name . '/*'));
            $createZip = public_path('/docs/suratundangan/kordes/pdf/SURAT UNDANGAN TIM KORDES DS.' . $village->name . '.zip');
            Zipper::make(public_path($createZip))->add($files)->close();

            return response()->download(public_path($createZip));
        } else {


            $district = DB::table('districts')->select('name')->where('id', $district_id)->first();
            $OrgModel = new OrgDiagram();
            // get data korcam by kecamatan
            $korcam          = $OrgModel->getKorcamByKecamatanForTitle($district_id);
            $kordes = $OrgModel->getKordesByKecamatan($district_id);
            $no = 1;

            $pdf = PDF::LoadView('pages.admin.report.kordesperkecamatan', compact('district', 'kordes', 'korcam', 'no'))->setPaper('a4');
            return $pdf->download('TIM KORDES KECAMATAN ' . $district->name . '.pdf');
        }
    }

    public function updateLelelOrgAll()
    {

        $org = DB::table('org_diagram_village')->select('id', 'title')->get();

        foreach ($org as $value) {

            DB::table('org_diagram_village')->where('id', $value->id)
                ->update(['level_org' => GlobalProvider::generateLevelOrgUpdate($value->title)]);
        }

        return 'ok';
    }

    public function updateTps(Request $request, $id)
    {

        $this->validate($request, [
            'tpsid' => 'required',
        ]);

        #get nik by id
        $org     = DB::table('org_diagram_rt')->select('nik')->where('id', $id)->first();
        $nik     = $org->nik;

        #update tps where nik di tb users
        DB::table('users')->where('nik', $nik)->update(['tps_id' => $request->tpsid]);
        return redirect()->route('admin-struktur-organisasi-rt')->with(['success' => 'TPS berhasil tersimpan!']);
    }

    public function updateTpsMember(Request $request, $id)
    {


        $this->validate($request, [
            'tpsid' => 'required',
        ]);

        #get nik by id
        $org     = DB::table('org_diagram_rt')->select('nik', 'pidx', 'name')->where('id', $id)->first();
        $nik     = $org->nik;

        $koor = DB::table('org_diagram_rt as a')->select('b.tps_id')
            ->join('users as b', 'a.nik', '=', 'b.nik')
            ->where('a.idx', $org->pidx)
            ->first();

        #cek apakah koordinator nya sudah memiliki TPS
        if (!$koor->tps_id) return redirect()->back()->with(['error' => "Kor TPS Anggota $org->name belum memiliki data TPS!"]);

        #cek apakah TPS koordinator sama dengan TPS anggota nya
        if ($koor->tps_id != $request->tpsid) return redirect()->back()->with(['error' => 'Gagal, TPS anggota tidak sama dengan TPS Koordinator!']);

        #update tps where nik di tb users
        DB::table('users')->where('nik', $nik)->update(['tps_id' => $request->tpsid]);
        return redirect()->route('admin-struktur-organisasi-rt-detail-anggota', ['idx' => $org->pidx])->with(['success' => 'TPS anggota berhasil tersimpan!']);
    }

    public function testPdf()
    {

        $pdf = PDF::LoadView('pages.report.pdf-test')->setPaper('a4');
        return $pdf->stream('ABSENSI TIM KORTE DESA.pdf');
    }

    public function suratUndanganKorte($id)
    {

        // get nik by id 
        $tim = DB::table('org_diagram_district as a')
            ->select('b.id', 'b.name')
            ->join('users as b', 'a.nik', '=', 'b.nik')
            ->where('a.id', $id)
            ->first();

        $pdf = PDF::LoadView('pages.report.surat-undangan', compact('tim'))->setPaper('a4');
        return $pdf->download('SURAT UNDANGAN ' . $tim->name . '.pdf');
    }

    public function formKoordinatorTpsKorte($idx)
    {
        $regency = Regency::select('id', 'name')->where('id', 3602)->first();
        $cek_count_org = DB::table('org_diagram_rt')->where('pidx', $idx)->count();

        $authAdminDistrict = auth()->guard('admin')->user()->district_id;
        $districtModel  = new District();
        $district       = $districtModel->getAreaAdminKoordinator($authAdminDistrict);
        $villages       = Village::select('id', 'name')->where('district_id', $authAdminDistrict)->get();

        $result_new_idx = "";

        if ($cek_count_org > 0) {

            $count_org     = DB::table('org_diagram_rt')->select('idx')->where('pidx', $idx)->orderBy('id', 'desc')->first();
            $count_org   = $count_org->idx;
            $exp        = explode(".", $count_org);
            $count_exp  = count($exp);

            if ($count_exp == 1) {

                $result_new_idx = $exp[0] . ".1";
            } else {

                $result_exp = (int) $exp[2] + 1;
                $result_new_idx  = $exp[0] . "." . $exp[1] . "." . $result_exp;
            }
            
        } else {

            $result_new_idx = $idx . '.1';
        }        

        return view('pages.admin.strukturorg.rt.formkoordinatortpskorte', compact('idx','regency','result_new_idx','villages', 'district'));
    }

    public function storeFormKoordinatorTps(Request $request, $idx)
    {
        DB::beginTransaction();
        try {

            $request->validate([
                'name' => 'required',
                'nik' => 'required',
            ]);
    
            $koorModel = new KoordinatorTpsKorte();
    
            #hitung panjang nik, harus 16
            $cekLengthNik = strlen($request->nik);
            if ($cekLengthNik <> 16) return redirect()->back()->with(['error' => 'NIK harus 16 angka, cek kembali NIK tersebut!']);
    
            #cek jangan double data
            $cek = $koorModel->where('nik', $request->nik)->count();
           
            if ($cek > 0) {
    
                // get keterangan kortpsnya
                $getAnggotaKortps = $koorModel->where('nik', $request->nik)->select('pidx_korte')->first();
                $orgDiagramModel  = new OrgDiagram();
                $kortps           = $orgDiagramModel->getKorTpsByPidxKorte($getAnggotaKortps->pidx_korte);
                $rt               = $kortps->rt ?? '';
                $desc             = ucfirst(strtolower($kortps->name ?? '')).', RT.'.$rt.', Ds.'.ucfirst(strtolower($kortps->village ?? '')).', Kec.'.ucfirst(strtolower($kortps->district ?? ''));
    
                return redirect()->back()->with(['error' => 'NIK sudah terdaftar di Kor TPS '.$desc.' !']);
    
            }else{
    
                # cek apakah sudah terdaftar sebagai anggota / memiliki KTA
                $cek_member = User::where('nik', $request->nik)->count();
                    # jika sudah simpan ke anggota korTPS 25, 
                    if ($cek_member > 0) {
                        
                    #cek ketersediaan nik di tb users
                    $userTable     = DB::table('users');
                    // $cek_nik_user  = $userTable->where('id', $request->member)->count();
                    $user         = $userTable->select('name', 'photo', 'phone_number', 'nik')->where('nik', $request->nik)->first();
        
                    // #cek jika nik sudah terdaftar di tb org_diagram_village
                    $cek_nik_org  = DB::table('org_diagram_rt')->where('nik', $user->nik)->count();
                    if ($cek_nik_org > 0) return redirect()->back()->with(['warning' => 'NIK sudah terdaftar distruktur!']);
    
                    #get villlage, regency, district, rt where idx
                    $domisili = DB::table('org_diagram_rt')->select('regency_id', 'district_id', 'village_id', 'rt')->where('idx', $idx)->first();
    
                    #cek jika tps koordinator tidak sama dengan tps calon anggotanya
                    $koor = DB::table('org_diagram_rt as a')->select('b.tps_id')
                        ->join('users as b', 'a.nik', '=', 'b.nik')
                        ->where('a.idx', $idx)
                        ->first();
                    $tpsKoor = $koor->tps_id;
    
                    if ($tpsKoor != $request->tpsid) {
    
                            return redirect()->back()->with(['error' => 'Gagal, TPS anggota tidak sama dengan TPS Koordinator!']);
    
                        }else{
    
                             #save to tb org_diagram_rt
                            DB::table('org_diagram_rt')->insert([
                                'idx'    => $request->newidx,
                                'pidx'   => $idx,
                                'title'  => 'ANGGOTA',
                                'nik'    => $user->nik,
                                'name'   => $user->name,
                                'base'   => 'ANGGOTA',
                                'photo'  => $user->photo ?? '',
                                'telp'  => $request->telp,
                                'regency_id'  => $domisili->regency_id,
                                'district_id' => $domisili->district_id,
                                'village_id'  => $domisili->village_id,
                                'rt'  => $domisili->rt,
                                'cby' => auth()->guard('admin')->user()->id,
                                'created_at' => date('Y-m-d H:i:s')
                            ]);
    
                            DB::table('users')->where('nik', $user->nik)->update(['tps_id' => $request->tpsid]);
    
                        }
    
                }else{
    
                    # jika belum tampung di data anggota form kosong kor tps
                    // cek ke table users apakah ada anggota dengan nik tersebut
                    $member = User::select('name', 'nik')->where('nik', $request->nik)->first();
                    // jika ada sesuaikan namanya by nik yg ada di table users
                    $name   = $member == null ? strtoupper($request->name) : strtoupper($member->name);
                    $name   = strtoupper($request->name);
            
                    $auth = auth()->guard('admin')->user()->id;
            
                    $koorModel->stores($idx, $request, $name, $auth);
            
    
                }
            } 
             
            DB::commit();
            return redirect()->back()->with(['success' => 'Anggota berhasil disimpan!']);

        } catch (\Exception $e) {
            DB::rollback();
            // return $e->getMessage();
            Log::error($e->getMessage());
            return redirect()->back()->with(['error' => 'Data gagal tersimpan!' . $e->getMessage()]);

        }
        

    }

    public function createNewAnggota($id){

        $anggotaKorTps = DB::table('anggota_koordinator_tps_korte')->where('id', $id)->first();

        $cek_count_org = DB::table('org_diagram_rt')->where('pidx', $anggotaKorTps->pidx_korte)->count();

        $result_new_idx = "";

        if ($cek_count_org > 0) {

            $count_org     = DB::table('org_diagram_rt')->select('idx')->where('pidx', $anggotaKorTps->pidx_korte)->orderBy('id', 'desc')->first();
            $count_org   = $count_org->idx;
            $exp        = explode(".", $count_org);
            $count_exp  = count($exp);

            if ($count_exp == 1) {

                $result_new_idx = $exp[0] . ".1";
            } else {

                $result_exp = (int) $exp[2] + 1;
                $result_new_idx  = $exp[0] . "." . $exp[1] . "." . $result_exp;
            }
            
        } else {

            $result_new_idx = $anggotaKorTps->pidx_korte . '.1';
        }


        return view('pages.admin.strukturorg.rt.create-new-anggota', compact('id','anggotaKorTps','result_new_idx'));

    }

    public function storeNewAnggotaByKorTps(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            
            $this->validate($request, [
                'phone_number' => 'numeric',
            ]);
    
            #hitung panjang nik, harus 16
            $cekLengthNik = strlen($request->nik);
            if ($cekLengthNik < 16) return redirect()->back()->with(['error' => 'NIK harus 16 angka, cek kembali NIK tersebut!']);

             #input ke table org_diagram_rt sebagai anggota
            $anggotaKorTps = DB::table('anggota_koordinator_tps_korte')->where('id', $id)->first();
    
            $cby = auth()->guard('admin')->user()->id;
            //    $cby    = User::select('id')->where('user_id', $cby_id->id)->first();
    
            $cek_nik = User::select('nik')->where('nik', $request->nik)->count();
            #cek nik jika sudah terpakai
            if ($cek_nik > 0) {
                return redirect()->back()->with(['error' => 'NIK yang anda gunakan telah terdaftar']);
            } else {
    
                //  cek jika reveral tidak tersedia
                $cek_code = DB::table('org_diagram_rt as a')
                                ->select('b.id')
                                ->join('users as b','a.nik','=','b.nik')
                                ->where('a.idx', $anggotaKorTps->pidx_korte)->first();
                // $cek_code = User::select('code', 'id')->where('code', $request->code)->first();
    
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
                        'code' => $string.$potong_nik,
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

            

            #input ke table org_diagram_rt sebagai anggota
            $anggotaKorTps = DB::table('anggota_koordinator_tps_korte')->where('id', $id)->first();

            #hitung jumlah anggota per kortps nya 
            $count_anggota_kortps = DB::table('org_diagram_rt as a')
                        ->join('users as b','a.nik','=','b.nik')
                        ->where('a.pidx', $anggotaKorTps->pidx_korte)
                        ->count();

            $message_kortps = '';
            if ($count_anggota_kortps >= 25) {
                $message_kortps = 'Tapi data tidak tersimpan ke anggota Kor Tps, karena sudah 25';
            }else{
                $domisili = DB::table('org_diagram_rt')->select('regency_id', 'district_id', 'village_id', 'rt')->where('idx', $anggotaKorTps->pidx_korte)->first();
                #save to tb org_diagram_rt
                DB::table('org_diagram_rt')->insert([
                    'idx'    => $request->new_idx,
                    'pidx'   =>  $anggotaKorTps->pidx_korte,
                    'title'  => 'ANGGOTA',
                    'nik'    => $user->nik,
                    'name'   => $user->name,
                    'base'   => 'ANGGOTA',
                    'photo'  => $user->photo ?? '',
                    'telp'  => $request->phone_number,
                    'regency_id'  => $domisili->regency_id,
                    'district_id' => $domisili->district_id,
                    'village_id'  => $domisili->village_id,
                    'rt'  => $domisili->rt,
                    'cby' => auth()->guard('admin')->user()->id,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }

            # hapus dari table anggota_koordinator_tps_korte by id;
            // DB::table('anggota_koordinator_tps_korte')->where('id', $id)->delete();
            
            DB::commit();
            if ($count_anggota_kortps >= 25) {
                return redirect()->route('admin-struktur-organisasi-rt-detail-anggota', $anggotaKorTps->pidx_korte)->with('success', 'Anggota baru telah disimpan, tapi data tidak tersimpan ke anggota Kor Tps, karena sudah 25');
            }else{
                return redirect()->route('admin-struktur-organisasi-rt-detail-anggota', $anggotaKorTps->pidx_korte)->with('success', 'Anggota baru telah disimpan !');
            }

        } catch (\Exception $e) {
            DB::rollBack();
            // return $e->getMessage();
            Log::error($e->getMessage());
            return redirect()->back()->with('error', 'Anggota baru gagal disimpan!', $e->getMessage());
        }
    }

    public function deleteDataFormKoordinatorTps(Request $request){

        $request->validate([
            'id' => 'required',
        ]);

        DB::table('anggota_koordinator_tps_korte')->where('id', $request->id)->delete();
        return redirect()->back()->with(['success' => 'Data berhasil dihapus!']);

        // $cek     = $anggota->first();

        // if($cek->created_by == auth()->guard('admin')->user()->id){
        //     $anggota->delete();

        //     return redirect()->back()->with(['success' => 'Data berhasil dihapus!']);

        // }else{

        //     return redirect()->back()->with(['warning' => 'Gagal, Anda tidak punya akses!']);

        // }


    }

    public function updateOnlyNikDataFormKoordinatorTps(Request $request){

        $request->validate([
            'id' => 'required',
            'nik' => 'required',
        ]);

        $anggota = DB::table('anggota_koordinator_tps_korte')->where('id', $request->id)->first();

        $cekLengthNik = strlen($request->nik);
        if ($cekLengthNik < 16) return redirect()->back()->with(['error' => 'NIK harus 16 angka, cek kembali NIK tersebut!']);

        // cek data di tbl users
        $user = DB::table('users')->select('nik','name')->where('nik', $request->nik)->first();
        if ($user == null) {
            // dd('tidak ada');
             #hitung panjang nik, harus 16
            
            DB::table('anggota_koordinator_tps_korte')->where('id', $request->id)->update([
                'nik' => $request->nik,
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => auth()->guard('admin')->user()->id

            ]);

        }else{

            DB::table('anggota_koordinator_tps_korte')->where('id', $request->id)->update([
                'nik' => $user->nik,
                'name' => $user->name,
                'tps_id' => $user->tps_id ?? null,
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => auth()->guard('admin')->user()->id

            ]);
        }

        // DB::table('anggota_koordinator_tps_korte')->where('id', $request->id)->delete();
        return redirect()->back()->with(['success' => 'Data berhasil diubah!']);

        // $cek     = $anggota->first();

        // if($cek->created_by == auth()->guard('admin')->user()->id){
        //     $anggota->delete();

        //     return redirect()->back()->with(['success' => 'Data berhasil dihapus!']);

        // }else{

        //     return redirect()->back()->with(['warning' => 'Gagal, Anda tidak punya akses!']);

        // }


    }

    public function downloadAnggotaKorTpsPdf(Request $request)
    {

        return 'ok';
        
    }

    public function uploadSticker(Request $request, $korte_idx)
    {
        $this->validate($request, [
            'file' => 'required|mimes:png,jpg,jpeg',
        ]);

        $file = $request->file('file')->store('assets/user/galleries/sticker', 'public');
        Sticker::create([
            'anggotaidx' => $request->anggotaidx,
            'korte_idx' => $korte_idx,
            'image' => $file,
            'created_by' => auth()->guard('admin')->user()->id,
        ]);

        return redirect()->back()->with(['success' => 'Stiker telah ditambahkan']);
    }

    public function listStikerByKorte($idx)
    {

        $kor_rt = DB::table('org_diagram_rt as a')
            ->select('a.rt', 'a.name', 'c.name as village', 'd.name as district')
            ->join('users as b', 'b.nik', '=', 'a.nik')
            ->join('villages as c', 'c.id', '=', 'a.village_id')
            ->join('districts as d', 'd.id', '=', 'a.district_id')
            ->where('idx', $idx)
            ->first();

        // get daftar anggota by korte, join kan dengan table sticker
        $data = DB::table('org_diagram_rt as a')
            ->select('b.name', 'a.rt', 'c.image', 'b.photo', 'c.id')
            ->join('sticker as c', 'a.id', '=', 'c.anggotaidx')
            ->join('users as b', 'a.nik', '=', 'b.nik')
            ->where('a.pidx', $idx)
            ->where('a.base', 'ANGGOTA')->get();

        $no = 1;
        return view('pages.admin.strukturorg.rt.liststickerbykorte', compact('data', 'kor_rt', 'no'));
    }

    public function deleteStikerByAnggota($id)
    {

        // hapus foto
        $getdata = DB::table('sticker')->where('id', $id);
        #hapus file lama
        $data = $getdata->first();
        $dir_file = storage_path('app') . '/public/' . $data->image;
        if (file_exists($dir_file)) {
            File::delete($dir_file);
        }

        // hapus data
        $getdata->delete();
        return redirect()->back()->with(['success' => 'Stiker telah dihapus!']);
    }

    public function countMemberNotCover(Request $request)
    {

        if (!isset($request->dapil_id)) {

            $total_tim      = DB::table('org_diagram_rt as a')
                ->join('users as b', 'a.nik', '=', 'b.nik')
                ->where('a.base', 'ANGGOTA')->count();

            $total_anggota  = DB::table('org_diagram_rt as a')
                ->join('users as b', 'a.nik', '=', 'b.nik')
                ->where('a.base', 'KORRT')->count();

            $total_anggota_db = DB::table('users as a')
                ->join('villages as b', 'a.village_id', '=', 'b.id')
                ->count();

            $tim          =  $total_tim + $total_anggota;

            $data = [
                'tim' => $tim,
                'anggota_db' => $total_anggota_db,
                'not_tercover' => $total_anggota_db - $tim
            ];

            return $data;

        }else{

            return 'per dapil';
        }
    }

    public function daftarTim(){

        // tampilkan data dapil 
        // $regency = 3602;
        // $dapils  = DB::table('dapils')->select('id','name')->where('regency_id', $regency)->get();
        // $no      = 1;

        $authAdminDistrict = auth()->guard('admin')->user()->district_id;

        // redirect ke daftar tim level kecamatan sesuai admin koordinator
        return $this->daftarTimDistrict($authAdminDistrict);

        // return view('pages.admin.strukturorg.rt.daftartim.dapil', compact('dapils','no'));

    }

    public function daftatTimDapil($dapilId){

        $dapil = DB::table('dapils')->select('name')->where('id', $dapilId)->first();
        $no    = 1;

        $orgDiagramModel = new OrgDiagram();
        $data            = $orgDiagramModel->getDataDaftarTimByDapil($dapilId);
        // dd($data);

        $jml_ketua = collect($data)->sum(function($q){
            return $q->ketua;
        });
        $jml_sekretaris = collect($data)->sum(function($q){
            return $q->sekretaris;
        });

        $jml_bendahara = collect($data)->sum(function($q){
            return $q->bendahara;
        });
        $jml_dpt =  collect($data)->sum(function($q){
            return $q->dpt;
        });

        $jml_anggota =  collect($data)->sum(function($q){
            return $q->anggota;
        });

        $jml_target_korte =  collect($data)->sum(function($q){
            return $q->target_korte;
        });

        $jml_korte_terisi =  collect($data)->sum(function($q){
            return $q->korte_terisi;
        });

        $jml_anggota_tercover = $jml_korte_terisi * 25;
        $jml_kurang_korte     = $jml_korte_terisi - $jml_target_korte;
        $jml_blm_ada_korte    = collect($data)->sum(function($q){
            return $q->belum_ada_korte;
        });
        $jml_saksi            = collect($data)->sum(function($q){
            return $q->saksi;
        });
        $persentage_target    = ($jml_anggota/$jml_dpt)*100;
        $jml_target           = collect($data)->sum(function($q){
            return ($q->dpt * $q->target_persentage)/100;
        });

        $gF = new GlobalProvider();

        return view('pages.admin.strukturorg.rt.daftartim.district', compact('dapil','no','data','jml_ketua','jml_sekretaris','jml_bendahara','jml_bendahara','jml_dpt','jml_anggota','jml_target_korte','jml_korte_terisi','jml_anggota_tercover','jml_kurang_korte','jml_blm_ada_korte','persentage_target','jml_target','gF','jml_saksi'));

    }

    public function daftarTimDistrict($districtId){

        $district = DB::table('districts')->select('name','target_persentage')->where('id', $districtId)->first();
        $gF = new GlobalProvider();

        $orgDiagramModel = new OrgDiagram();
        #get data desa by kecamatan
        $data = $orgDiagramModel->getDataDaftarTimByKecamatan($districtId);
        
        $jml_ketua = collect($data)->sum(function($q){
            return $q->ketua;
        });
        $jml_sekretaris = collect($data)->sum(function($q){
            return $q->sekretaris;
        });
        $jml_bendahara = collect($data)->sum(function($q){
            return $q->bendahara;
        });
        $jml_dpt =  collect($data)->sum(function($q){
            return $q->dpt;
        });

        $jml_anggota =  collect($data)->sum(function($q){
            return $q->anggota;
        });

        $jml_target_korte =  collect($data)->sum(function($q){
            return $q->target_korte;
        });
        $jml_korte_terisi =  collect($data)->sum(function($q){
            return $q->korte_terisi;
        });

        $jml_anggota_tercover = $jml_korte_terisi * 25;
        $jml_kurang_korte     = $jml_korte_terisi - $jml_target_korte;
        // $jml_blm_ada_korte    = collect($data)->sum(function($q){
        //     return $q->belum_ada_korte;
        // });
        $tmp_blm_ada_korte = $jml_anggota_tercover - $jml_anggota;
        $jml_blm_ada_korte = $tmp_blm_ada_korte;
        if ($jml_blm_ada_korte == - 0) {
            $jml_blm_ada_korte = 0;
        }elseif ($jml_blm_ada_korte > 0) {
            $jml_blm_ada_korte = '+'.$gF->decimalFormat($jml_blm_ada_korte);
        }

        $jml_saksi            = collect($data)->sum(function($q){
            return $q->saksi;
        });
        $jml_tps  = collect($data)->sum(function($q){
            return $q->tps;
        });
        $persentage_target    = ($jml_anggota/$jml_dpt)*100;
        $jml_target           = $district->target_persentage > 0 ? ($jml_dpt*$district->target_persentage)/100 : 0;
        $persen_dari_target_kec = $jml_target > 0 ? ($jml_anggota/$jml_target)*100 : 0;
        $no = 1;

        return view('pages.admin.strukturorg.rt.daftartim.village', compact('jml_tps','persen_dari_target_kec','gF','data','no','jml_ketua','jml_sekretaris','jml_bendahara','jml_dpt','jml_anggota','jml_target_korte','jml_korte_terisi','jml_anggota_tercover','jml_kurang_korte','jml_blm_ada_korte','persentage_target','jml_target','district','jml_saksi'));
    }

    public function storeKeluargaSerumahByKorTps(Request $request, $idx){

        DB::beginTransaction();

        try {
            $this->validate($request, [
                'kepalakel' => 'required',
            ]);
    
            // get and nik by idx korte
            $data_org = DB::table('org_diagram_rt as a')
                        ->select('a.nik','b.id')
                        ->join('users as b','a.nik','=','b.nik')
                        ->where('a.idx', $request->kepalakel)
                        ->first();
    
            // insert ke tbl familly group
            $famillyGroup = FamilyGroup::create([
                'user_id' => $data_org->id,
                'nik' => $data_org->nik,
                'pidx_korte' => $idx,
                'cby' => auth()->guard('admin')->user()->id 
            ]);

    
            // insert ke detail familly group
            if ($request->members != null) {
                $member['members'] = $request->members;
                foreach ($member['members'] as $key => $value) {
    
                    // jangan simpan jika member nya terpilih sebagai kepala keluarga
                    if ($request->kepalakel != $value) {
                        $data_org = DB::table('org_diagram_rt as a')
                                ->select('a.nik','b.id')
                                ->join('users as b','a.nik','=','b.nik')
                                ->where('a.idx', $value)
                                ->first();
        
                        $members = new DetailFamilyGroup();
                        $members->family_group_id = $famillyGroup->id;
                        $members->user_id = $data_org->id;
                        $members->nik = $data_org->nik;
                        $members->pidx_korte = $idx;
                        $members->save();
                    }
                }
            }

            DB::commit();
            return redirect()->back()->with(['success' => 'Data berhasil tersimpan!']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return redirect()->back()->with(['error' => 'Data gagal tersimpan!']);
        }


    }

    public function downloadAnggotaKorTpsFormKosongByKortps(Request $request, $idx){


        $kor_rt = DB::table('org_diagram_rt as a')
            ->select('a.base', 'a.rt', 'b.name', 'c.name as village', 'd.name as district','b.rw','e.tps_number','a.telp')
            ->join('users as b', 'b.nik', '=', 'a.nik')
            ->join('villages as c', 'c.id', '=', 'a.village_id')
            ->join('districts as d', 'd.id', '=', 'a.district_id')
            ->join('tps as e','b.tps_id','=','e.id')
            ->where('a.idx', $idx)
            ->where('a.base', 'KORRT')
            ->first();
            // get data anggota by korte
            $members = DB::table('anggota_koordinator_tps_korte as a')
                ->select('a.name','a.nik','b.nik as registered')
                ->leftJoin('users as b','a.nik','b.nik')
                ->where('a.pidx_korte', $idx)
                ->orderBy('b.nik','asc')
                ->get();

            $no = 1;


            // dd($members);


    $pdf = PDF::LoadView('pages.report.memberbyformkortps', compact('kor_rt', 'members', 'no'))->setPaper('a4');
    return $pdf->download('ANGGOTA KORTPS RT ' . $kor_rt->rt . ' (' . $kor_rt->name . ') DS.' . $kor_rt->village . '.pdf');

    //     $kor_rt = DB::table('org_diagram_rt as a')
    //             ->select('a.rt', 'a.name', 'c.name as village', 'd.name as district')
    //             ->join('users as b', 'b.nik', '=', 'a.nik')
    //             ->join('villages as c', 'c.id', '=', 'a.village_id')
    //             ->join('districts as d', 'd.id', '=', 'a.district_id')
    //             ->where('idx', $idx)
    //             ->first();

        // $title = 'ANGGOTA KORTPS : ' . $kor_rt->name . ' RT (' . $kor_rt->rt . '), DS.' . $kor_rt->village . ', KEC.' . $kor_rt->district . '.xls';
        // return $this->excel->download(new AnggotaFormKortpsExport($idx), $title);

    }

    public function migrasiDataFormKorTpsToOrgDiagramKorRt(Request $request){
        

        // -- get data kortps
        $korTps = DB::table('org_diagram_rt as a')
                ->select('a.idx', 'b.tps_id', 
                // DB::raw('(select count(id) from anggota_koordinator_tps_korte where pidx_korte = a.idx) as jml_anggota')
                )
                ->join('users as b','a.nik','b.nik')
                ->where('a.base','KORRT')
                ->where('a.district_id', $request->district_id)
                ->groupBy('a.idx','b.tps_id')
                ->havingRaw('(select count(id) from anggota_koordinator_tps_korte where pidx_korte = a.idx) != 0')
                ->get();
        // -- get data dari anggota_koordinator_tps_korte per kortps nya
        $results = [];
        foreach ($korTps as  $value) {
            #query hanya nik yg memiliki KTA saja
            $getAnggotaKorTps = DB::table('anggota_koordinator_tps_korte as a')
                                ->join('users as b','a.nik','=','b.nik')
                                ->select('a.nik','a.pidx_korte', 
                                    DB::raw('(select count(id) from org_diagram_rt where nik = a.nik) as anggota_kortps'))
                                ->where('a.pidx_korte', $value->idx)
                                ->get();
            $results[] = [
                // 'kortps_idx' => $value->idx,
                // 'tps_id' => $value->tps_id,
                // 'jml_anggota' => $value->jml_anggota,
                'anggota' => $getAnggotaKorTps
            ];
        }


        $idxExists = [];

        foreach ($results as $item) {
            foreach ($item['anggota'] as $value) {
                #buat anggota baru
                if ($value->anggota_kortps == 0) {
                    
                    // $result_new_idx = $this->generateNewIdx($item->pidx_korte);
                    $idxExists[] = [
                        'idx_tersedia' => $value->nik
                    ];
                }
            }

    
                // else {
                //     // -- jika sudah jadi anggota, migrasikan ke tb org_diagram_rt sebagai anggota, replace
                //     #replace kortps sesuai form kosong bawaan korte nya
                // }
            }

        return $idxExists;

        #mencetak idx yg tersedia, chil dari pidx kortps
        // -- cek apakah sudah jadi anggota
        
        // -- atur algoritma untuk idx nya yg memiliki parent kortps di atasnya
    }

    public function generateNewIdx($idx_korte){

        $cek_count_org = DB::table('org_diagram_rt')->where('pidx', $idx_korte)->count();
        $result_new_idx = "";
                        if ($cek_count_org > 0) {
        
                            $count_org     = DB::table('org_diagram_rt')->select('idx')->where('pidx', $idx_korte)->orderBy('id', 'desc')->first();
                            $count_org   = $count_org->idx;
                            $exp        = explode(".", $count_org);
                            $count_exp  = count($exp);
                
                            if ($count_exp == 1) {
                
                                $result_new_idx == $exp[0] . ".1";
                            } else {
                
                                $result_exp = (int) $exp[2] + 1;
                                $result_new_idx  == $exp[0] . "." . $exp[1] . "." . $result_exp;
                            }
                            
                        } else {
                
                            $result_new_idx == $idx_korte . '.1';
                        }

        return $result_new_idx;

    }

    public function updateNoTelpKortps()
    {

        DB::beginTransaction();
        try {

            $id    = request()->id;
            $telp  = request()->telp;

            #update org
            $org = DB::table('org_diagram_rt')->where('id', $id)->first();

            DB::table('users')->where('nik', $org->nik)->update([
                'phone_number' => $telp,
                'whatsapp' => $telp
            ]);

            DB::table('org_diagram_rt')->where('id', $id)->update([
                'telp' => $telp
            ]);

            DB::commit();
            return ResponseFormatter::success([
                'message' => 'Berhasil ubah no.telp!'
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            return ResponseFormatter::error([
                'message' => 'Something when wrong!',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function singkronisasiData(Request $request, $idx){

        DB::beginTransaction();
        try {

            #get data anggota dari form kortps, yang sudah punya KTA saja, dan bukan dari anggota kortps lain
            $anggota_kortps = DB::table('anggota_koordinator_tps_korte')
                            ->select('name','nik', 
                                DB::raw('(select count(id) from users where nik =  anggota_koordinator_tps_korte.nik) as cek_kta'),
                                DB::raw('(select COUNT(nik) from org_diagram_rt where nik = anggota_koordinator_tps_korte.nik and pidx = anggota_koordinator_tps_korte.pidx_korte) as myanggota')
                            )
                            ->where('pidx_korte', $idx)
                            ->havingRaw('cek_kta > 0')
                            ->havingRaw('myanggota > 0')
                            ->get();

            // dd($anggota_kortps);

            #hapus dulu data dari form kortps kecuali yg belum mempunyai KTA, dan anggota kortps orang lain
            foreach( $anggota_kortps as $item){
                DB::table('anggota_koordinator_tps_korte')->where('nik', $item->nik)->where('pidx_korte', $idx)->delete();
            }

            #get data anggota berdasrkan idx kortps
            $anggota = DB::table('org_diagram_rt as a')
                       ->select('a.pidx','a.nik' ,'b.name','a.cby','a.created_at')
                       ->join('users as b','a.nik','=','b.nik')
                       ->where('a.base','ANGGOTA')->where('a.pidx', $idx)->get();

            #insert kembali ke anggotaan 25 ke form kortps
            foreach ($anggota as  $value) {
                DB::table('anggota_koordinator_tps_korte')->insert([
                    'pidx_korte' => $value->pidx,
                    'nik'  => $value->nik,
                    'name' => $value->name,
                    'created_by' => $value->cby,
                    'created_at' => $value->created_at,
                    'updated_by' => auth()->guard('admin')->user()->id,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }
           
            DB::commit();
            return redirect()->back()->with(['success' => 'Berhasil singkronisasi!']);
        } catch (\Exception $e) {
           DB::rollback();
        //    return $e->getMessage();
        Log::error($e->getMessage());
           return redirect()->back()->with(['warning' => 'Gagal singkronisasi!']);
        }

    }

    public function daftarFormManual($idx)
    {
        $regency = Regency::select('id', 'name')->where('id', 3602)->first();

        $authAdminDistrict = auth()->guard('admin')->user()->district_id;
        $district  = District::select('name','id')->where('id', $authAdminDistrict)->first();
        $villages  = Village::select('id','name')->where('district_id', $district->id)->get();

        $korte_idx = $idx;

        $kor_rt = DB::table('org_diagram_rt as a')
                ->select('a.rt', 'a.name', 'c.name as village', 'd.name as district', 'e.tps_number','b.nik')
                ->join('users as b', 'b.nik', '=', 'a.nik')
                ->join('villages as c', 'c.id', '=', 'a.village_id')
                ->join('districts as d', 'd.id', '=', 'a.district_id')
                ->leftJoin('tps as e', 'b.tps_id', '=', 'e.id')
                ->where('idx', $idx)
                ->first();

        #get data ke anggotaan sementara 
        // $admin_id =  auth()->guard('admin')->user()->id ?? 0;
        // $redisKey = $idx.'-'.$admin_id;
        // $tmp_anggota = Redis::get($redisKey);
        $results_tmp_anggota =  DB::table('tmp_form_anggota_manual_kortp')->where('pidx_korte', $idx)->get();
        $no = 1;

        #data fix anggota form manual
        $anggota = DB::table('form_anggota_manual_kortp')->where('pidx_korte', $idx)->get();

        return view('pages.admin.strukturorg.rt.formmanual.index', compact('regency','district','villages','idx','korte_idx','kor_rt','results_tmp_anggota','no','anggota'));
    }

    public function previewSaveAnggotaFormManual(Request $request, $idx){

        DB::beginTransaction();
        try {

            $request->validate([
                'file' => 'required|mimes:xls,xlsx',
            ]);

            #get nik kortps by $idx

            // tampung data dari excel
            $data =  Excels::toCollection(new FormManualImport, request()->file('file'));

            // export excel to collection
            $list_anggota = [];
            foreach($data as  $value){
                // $list_anggota[] = $value;
                foreach($value as $item){
                    $anggota =  DB::table('users')->select('name','nik')->where('nik', $item['nik'])->first();
                    $list_anggota[] = [
                        'is_cover' => $anggota == null ? 0 : 1,
                        'nik' => $anggota->nik ?? $item['nik'],
                        'name' => $anggota->name ?? $item['nama']
                    ];
                }
            }


            // simpan ke tb sementara
            $admin_id =  auth()->guard('admin')->user()->id ?? 0;
            foreach ($list_anggota as $value) {
                    DB::table('tmp_form_anggota_manual_kortp')->insert([
                        'pidx_korte' => $idx,
                        'nik'  => $value['nik'],
                        'name' => $value['name'],
                        'is_cover' => $value['is_cover'],
                        'created_by' => $admin_id,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
                
            }

            // return $list_anggota;

            // Convert the array to a JSON string before saving to Redis
            // $jsonData = json_encode($list_anggota);

            // Specify the key under which you want to store the data in Redis
            // jadikan nik korte dan id admin sebagai key redis nya
            // $admin_id =  auth()->guard('admin')->user()->id ?? 0;
            // $redisKey =  $idx.'-'.$admin_id;

            // // Save the JSON string to Redis
            // Redis::del($redisKey);
            // Redis::set($redisKey, $jsonData);

            // $results = Redis::get($redisKey);

            // $results = json_decode($results);

            // tampilkan kedalam view

            DB::commit();
            return redirect()->route('admin-struktur-form-manual', $idx);

        } catch (\Exception $e) {
            return $e->getMessage();
           return redirect()->with(['error' => $e->getMessage()]);
        }
    }

    public function saveAnggotaFormManual(Request $request, $idx)
    {
        DB::beginTransaction();
        try {

            $request->validate([
                'act' => 'required|string'
            ]);

             #get data ke anggotaan sementara 
             $admin_id =  auth()->guard('admin')->user()->id ?? 0;
            //  $redisKey = $idx.'-'.$admin_id;
            //  $tmp_anggota = Redis::get($redisKey);
    
             #jika act remove, maka remove dari tmp
             if ($request->act == 'remove') {
                $tmp_anggota = DB::table('tmp_form_anggota_manual_kortp')->where('pidx_korte', $idx)->get();
                foreach ($tmp_anggota as  $value) {
                    DB::table('tmp_form_anggota_manual_kortp')->where('id', $value->id)->delete();
                }

                DB::commit();

                return redirect()->back()->with(['success' => 'Berhasil terhapus dari preview!']);

             }else{
                // save to db
                // get tps_id korte nya
                $korte = DB::table('org_diagram_rt as a')->select('b.tps_id')->join('users as b','a.nik','=','b.nik')->where('idx', $idx)->first();
                $results_tmp_anggota = DB::table('tmp_form_anggota_manual_kortp')->where('pidx_korte', $idx)->get();
                foreach ($results_tmp_anggota as  $value) {
                    // simpan yg hanya belum terdaftar di sistem, is_cover = 0
                    if ($value->is_cover == 0) {
                        DB::table('form_anggota_manual_kortp')->insert([
                             'pidx_korte' => $idx,
                             'nik'  => $value->nik,
                             'name' => $value->name,
                             'tps_id' => $korte->tps_id,
                             'created_by' => $admin_id,
                             'created_at' => date('Y-m-d H:i:s'),
                             'updated_at' => date('Y-m-d H:i:s'),

                        ]);
                    }
                }
                // remove dari tmp
                // $tmp_anggota = DB::table('tmp_form_anggota_manual_kortp')->where('pidx_korte', $idx)->get();
                DB::table('tmp_form_anggota_manual_kortp')->where('pidx_korte', $idx)->delete();
    
             }
             DB::commit();
             return redirect()->back()->with(['success' => 'Berhasil tersimpan!']);
        } catch (\Exception $e) {
            // return $e->getMessage();
            DB::rollBack();
            return redirect()->back()->with(['error' =>'Gagal tersimpan !', $e->getMessage()]);

        }

    }

    public function deleteAnggotaFormManual()
    {

        DB::beginTransaction();
        try {

            $id    = request()->id;

            #update org
            DB::table('form_anggota_manual_kortp')->where('id', $id)->delete();

            DB::commit();
            return ResponseFormatter::success([
                'message' => 'Berhasil dihapus!'
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            return ResponseFormatter::error([
                'message' => 'Something when wrong!',
                'error' => $e->getMessage()
            ]);
        }
    }
 
    public function uploadFormKortps(Request $request, $idx)
    {
        DB::beginTransaction();
        try {

            $request->validate([
                'file' => 'required|mimes:xls,xlsx',
            ]);

            DB::table('anggota_koordinator_tps_korte')->where('pidx_korte', $idx)->delete();

            $data =  Excels::toCollection(new FormKortpsImport, request()->file('file'));
            foreach ($data as $value) {
                foreach ($value as  $item) {
                    DB::table('anggota_koordinator_tps_korte')->insert([
                        'pidx_korte' => $idx,
                        'nik' => $item['nik'] ?? '',
                        'name' => strtoupper($item['nama'] ?? ''),
                        'created_by' => auth()->guard('admin')->user()->id,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                }
            }

            DB::commit();
            return redirect()->back()->with(['success' => 'Berhasil di upload!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['error' => 'Gagal di upload!', $e->getMessage()]);
        }
    }

    public function formVivi($idx)
    {
        $regency = Regency::select('id', 'name')->where('id', 3602)->first();

        $authAdminDistrict = auth()->guard('admin')->user()->district_id;
        $district  = District::select('name','id')->where('id', $authAdminDistrict)->first();
        $villages  = Village::select('id','name')->where('district_id', $district->id)->get();

        $korte_idx = $idx;

        $kor_rt = DB::table('org_diagram_rt as a')
                ->select('a.rt', 'a.name', 'c.name as village', 'd.name as district', 'e.tps_number','b.nik')
                ->join('users as b', 'b.nik', '=', 'a.nik')
                ->join('villages as c', 'c.id', '=', 'a.village_id')
                ->join('districts as d', 'd.id', '=', 'a.district_id')
                ->leftJoin('tps as e', 'b.tps_id', '=', 'e.id')
                ->where('a.idx', $idx)
                ->first();


        #get data anggota by korts
        $anggota = DB::table('org_diagram_rt as a')
                ->select('b.id', 'b.name', 
                    DB::raw('(select count(id) from form_vivi where nik = a.nik) as is_registered')
                )
                ->join('users as b', 'b.nik', '=', 'a.nik')
                ->where('a.pidx', $idx)
                ->where('a.base', 'ANGGOTA')
                ->having('is_registered',0)
                ->orderBy('b.name','asc')
                ->get();

        $count_anggota = count($anggota);

        #anggota form vivi
        $anggota_formvivi = DB::table('form_vivi as a')
                ->select('a.id', 'b.name','b.photo')
                ->join('users as b', 'b.nik', '=', 'a.nik')
                ->where('a.pidx_korte', $idx)
                ->orderBy('b.name','asc')
                ->get();
        

        $no = 1;

        return view('pages.admin.strukturorg.rt.formvivi.index', compact('regency','district','villages','idx','korte_idx','kor_rt','no','anggota','anggota_formvivi','count_anggota'));
    }

    public function saveFormVivi(Request $request, $idx)
    {
        DB::beginTransaction();
        try {

            #get data anggota berdasarkan id nya dari tb users
            $req_data['id'] = $request->member;
            foreach ($req_data['id'] as $key => $value) {
                $member = DB::table('users')->select('nik','name','tps_id')->where('id', $value)->first();
                DB::table('form_vivi')->insert([
                    'pidx_korte' => $idx,
                    'nik' => $member->nik,
                    'name' => $member->name,
                    'tps_id' => $member->tps_id,
                    'created_by' => auth()->guard('admin')->user()->id,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }  

            DB::commit();
            return redirect()->back()->with(['success' => 'Berhasil di simpan!']);

        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
            return redirect()->back()->with(['error' => 'Gagal di simpan!', $e->getMessage()]);
        }
    }

    public function deleteAnggotaFormVivi()
    {

        DB::beginTransaction();
        try {

            $id    = request()->id;

            #update org
            DB::table('form_vivi')->where('id', $id)->delete();

            DB::commit();
            return ResponseFormatter::success([
                'message' => 'Berhasil dihapus!'
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            return ResponseFormatter::error([
                'message' => 'Something when wrong!',
                'error' => $e->getMessage()
            ]);
        }
    }

}
