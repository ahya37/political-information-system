<?php

namespace App\Http\Controllers\Admin;

use PDF;
use File;
use Zipper;
use Storage;
use App\User;
use DataTables;
use App\Sticker;
use App\OrgDiagram;
use App\Models\Regency;
use App\Models\Village;
use App\Models\District;
use App\Models\Province;
use App\RightChosseVillage;
use App\Exports\KorteExport;
use App\KoordinatorTpsKorte;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;
use App\Exports\KorCamExport;
use App\Exports\KorDesExport;
use App\Providers\GlobalProvider;
use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\DB;
use App\Exports\KorteMembersExport;
use App\Http\Controllers\Controller;
use App\Exports\KorteExportWithSheet;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Auth\RegisterController;

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

        $regency_id = request('regency_id');
        $dapil_id   = request('dapil_id');
        $district_id = request('district_id');
        $village_id = request('village_id');

        $org_diagram = OrgDiagram::select('id', 'idx', 'parent', 'title', 'name', 'image', 'user_id', 'base', 'regency_id', 'dapil_id', 'district_id', 'village_id')
            ->orderBy('idx', 'asc')->get();

        return response()->json([
            'data' => $org_diagram
        ]);
    }

    public function getDataCoverKorTps()
    {
        $regency    = 3602;
        $dapil_id   = request()->dapil;
        $district_id = request()->district;
        $village_id = request()->village;
        $rt         = request()->rt;

        $orgDiagram = new OrgDiagram();
        $gF         = new GlobalProvider();

        $results  = '';
        $data_pengurus = [];
        $tpsNotExists  = [];
        $tpsExists     = [];
        $target_anggota  = '';
        $jml_dpt         = '';
        
        if(isset($dapil_id) && !isset($district_id) && !isset($village_id) && !isset($rt)){

            $results = $orgDiagram->getKalkulasiTercoverDapil($dapil_id);

            #proses hitung target
            $dataTim        = $orgDiagram->getDataDaftarTimByDapil($dapil_id);
            $jml_dpt =  collect($dataTim)->sum(function($q){
                return $q->dpt;
            });

            $target_anggota = collect($dataTim)->sum(function($q){
                return ($q->dpt * $q->target_persentage)/100;
            });

        }elseif(isset($dapil_id) && isset($district_id) && !isset($village_id) && !isset($rt)){

            $results  = $orgDiagram->getKalkulasiTercoverDistrict($district_id);

            // get data pengurus
            $data_pengurus = $orgDiagram->getDataPengurusKecamatan($district_id);

            #proses hitung target
            $dataTim        = $orgDiagram->getDataDaftarTimByKecamatan($district_id);
            $district = DB::table('districts')->select('target_persentage')->where('id', $district_id)->first();

            $jml_dpt =  collect($dataTim)->sum(function($q){
                return $q->dpt;
            });
            $target_anggota = $district->target_persentage > 0 ? ($jml_dpt*$district->target_persentage)/100 : 0;

        }elseif(isset($dapil_id) && isset($district_id) && isset($village_id) && !isset($rt)){

            $results = $orgDiagram->getKalkulasiTercoverVillage($village_id);
            $data_pengurus = $orgDiagram->getDataPengurusDesa($village_id);

            #get list data tps yg belum terisi oleh kortps
            $tpsNotExists = $orgDiagram->getTpsNotExistByVillage($village_id);
            $tpsExists    = $orgDiagram->getTpsExistByVillage($village_id);

            #proses hitung target
            $dataTim      = $orgDiagram->getDataDaftarTimByVillage($village_id);
            $jml_dpt      =  $dataTim->dpt;

            $target_anggota =  $dataTim->target ?? 0;

        }elseif(isset($dapil_id) && isset($district_id) && isset($village_id) && isset($rt)){

            $results = $orgDiagram->getKalkulasiTercoverRt($village_id, $rt);
            #proses hitung target
            $dataTim        = $orgDiagram->getDataDaftarTimByVillage($village_id);
            $jml_dpt      =  $dataTim->dpt;
            $target_anggota =  $dataTim->target ?? 0;
            
            #get list data tps yg belum terisi oleh kortps
            // $tpsNotExists = $orgDiagram->getTpsNotExistByVillage($village_id);
            
        }else{

            $results = $orgDiagram->getKalkulasiTercoverAll();

            #proses hitung target
            $dataTim      = $orgDiagram->getDataDaftarTimByRegency($regency);
            $targets      = [];
            foreach ($dataTim as  $value) {
                $getTarget = $orgDiagram->getDataDaftarTimByDapilForRegency($value->id);
                $targets[] = [
                    'target' => $getTarget
                ];
            }

            $jml_dpt =  collect($dataTim)->sum(function($q){
                return $q->dpt;
            });

            $target_anggota = collect($targets)->sum(function($q){
                return $q['target'];
            });
        }

        // $ketua         = $this->searchArrayValueName($data_pengurus, 'KETUA');
        // $ketua_photo   = $this->searchArrayValuePhoto($data_pengurus, 'KETUA');
        // $referal_ketua = $this->searchArrayValueCountReferal($data_pengurus,'KETUA');

        // $sekretaris    = $this->searchArrayValueName($data_pengurus, 'SEKRETARIS');
        // $sekretaris_photo   = $this->searchArrayValuePhoto($data_pengurus, 'SEKRETARIS');
        // $referal_sekretaris = $this->searchArrayValueCountReferal($data_pengurus,'SEKRETARIS');

        // $bendahara     = $this->searchArrayValueName($data_pengurus, 'BENDAHARA');
        // $bendahara_photo   = $this->searchArrayValuePhoto($data_pengurus, 'BENDAHARA');
        // $referal_bendahara = $this->searchArrayValueCountReferal($data_pengurus,'BENDAHARA');

        $pengurus = [
            // 'ketua' => ucwords(strtolower($ketua)) ?? '',
            // 'ketua_photo' => $ketua_photo ?? '',
            // 'referal_ketua' => $referal_ketua ?? 0,
            // 'sekretaris' => ucwords(strtolower($sekretaris)) ?? '',
            // 'sekretaris_photo' => $sekretaris_photo ?? '',
            // 'referal_sekretaris' => $referal_sekretaris ?? 0,
            // 'bendahara' => ucwords(strtolower($bendahara)) ?? '',
            // 'bendahara_photo' => $bendahara_photo ?? '',
            // 'referal_bendahara' => $referal_bendahara ?? 0,
            'data_pengurus' => $data_pengurus
        ];

        return response()->json([
            'data' => $results,
            'target_anggota' => $gF->decimalFormat($target_anggota),
            'jml_dpt' => $gF->decimalFormat($jml_dpt),
            'pengurus' => $pengurus,
            'tpsnotexists' => $tpsNotExists,
            'tpsExists' => $tpsExists
        ]);
    }

    public function getDataTimKorTps()
    {
        $regencyId  = 3602;
        $dapil_id   = request()->dapil;
        $district_id = request()->district;
        $village_id = request()->village;
        $rt         = request()->rt;

        $orgDiagram         = new OrgDiagram();
        $districtModel      = new District();
        $RightChosseVillageModel   = new RightChosseVillage();
        $villageModel              = new Village();
        $gF = new GlobalProvider();

        $const_kortps = 25;

        $results = '';
        $target_kortps = '';
        if(isset($dapil_id) && !isset($district_id) && !isset($village_id) && !isset($rt)){

            $results = $orgDiagram->getCalculateDataDaftarTimKorTpsDapil($regencyId, $dapil_id);
            $getTargetKortps = $orgDiagram->getDataDaftarTimByDapilForRegency($dapil_id);

            $target_kortps   = $getTargetKortps / $const_kortps;

        }elseif(isset($dapil_id) && isset($district_id) && !isset($village_id) && !isset($rt)){

            $results                = $orgDiagram->getCalculateDataDaftarTimKorTpsDistrict($district_id);
		    $rightChooseDistrict    = $RightChosseVillageModel->getTotalDptDistrict($district_id)->total_dpt;

            $target_from_dpt  = $districtModel->getTargetPersentageDistrict($district_id)->target_persentage;
            $target_member    = ($rightChooseDistrict * $target_from_dpt)/100;

            $target_kortps = $target_member / $const_kortps;

        }elseif(isset($dapil_id) && isset($district_id) && isset($village_id) && !isset($rt)){

            $results            = $orgDiagram->getCalculateDataDaftarTimKorTpsVillage($village_id);
            $rightChooseVillage = $RightChosseVillageModel->getTotalDptVillage($village_id)->total_dpt;

            $target_from_dpt    = $villageModel->getTargetPersentageVillage($village_id)->target_persentage;
            $target_member      = ($rightChooseVillage* $target_from_dpt)/100;

            $target_kortps      = $target_member / $const_kortps;

        }else{
            $results = $orgDiagram->getCalculateDataDaftarTimKorTps($regencyId);
            $get_target_kortps = $orgDiagram->getDataDaftarTimByDapilForRegencyAll();
            // deaultnya , jumlahkah semua target kortps all level
            $target_kortps = $get_target_kortps / 25;
        }

        // $target_kortps      = collect($results)->sum(function($q){
        //     return $q->target_korte ?? 0;
        // });

        $kortps_terisi      = collect($results)->sum(function($q){
            return $q->korte_terisi ?? 0;
        });

        $tps = collect($results)->sum(function($q){
            return $q->tps ?? 0;
        });

        $count_kurang_kortps = $kortps_terisi - $target_kortps;
        if ($count_kurang_kortps > 0) {
            $count_kurang_kortps = '+'. $gF->decimalFormat($count_kurang_kortps);
        }else{
            $count_kurang_kortps = $gF->decimalFormat($count_kurang_kortps);
        }
        

        $data_results = [
            'target_kortps' => $gF->decimalFormat($target_kortps),
            'kortps_terisi' => $gF->decimalFormat($kortps_terisi),
            'kurang_kortps' =>  $count_kurang_kortps,
            'tps' => $gF->decimalFormat($tps)
        ];

        return response()->json([
            'data' => $data_results
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

        $rt      = 30;

        return view('pages.admin.strukturorg.village.index', compact('regency', 'rt'));
    }

    public function getDataOrgVillage(Request $request)
    {

        // DATATABLE
        $orderBy = 'a.name';
        switch ($request->input('order.0.column')) {
            case '1':
                $orderBy = 'a.name';
                break;
                case '3':
                    $orderBy = 'a.title';
                    break;
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
            ->select('a.id', 'a.idx', 'a.village_id', 'a.rt', 'a.rw', 'b.address', 'a.title', 'a.nik', 'a.name', 'b.photo', 'a.telp as phone_number', 'c.name as village', 'd.name as district')
            ->join('users as b', 'b.nik', '=', 'a.nik')
            ->join('villages as c', 'c.id', '=', 'b.village_id')
            ->join('districts as d', 'd.id', '=', 'c.district_id')
            ->join('dapil_areas as e','d.id','=','e.district_id');


        if ($request->input('search.value') != null) {
            $data = $data->where(function ($q) use ($request) {
                $q->whereRaw("LOWER(a.name) like ? ", ["%" . strtolower($request->input("search.value")) . "%"]);
                $q->whereRaw("LOWER(a.title) like ? ",["%".strtolower($request->input("search.value"))."%"]);
                // ->orWhereRaw("LOWER(regencies.name) like ? ",["%".strtolower($request->input("search.value"))."%"])
                // ->orWhereRaw("LOWER(districts.name) like ? ",["%".strtolower($request->input("search.value"))."%"])
                // ->orWhereRaw("LOWER(villages.name) like ? ",["%".strtolower($request->input("search.value"))."%"])
                // ->orWhereRaw("LOWER(b.name) like ? ",["%".strtolower($request->input("search.value"))."%"])
                // ->orWhereRaw("LOWER(c.name) like ? ",["%".strtolower($request->input("search.value"))."%"])
                // ->orWhereRaw("LOWER(a.created_at) like ? ",["%".strtolower($request->input("search.value"))."%"])

            }); 
        }

        if ($request->input('dapil') != null) {
            $data->where('e.dapil_id', $request->dapil);
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

        $rt      = 30;

        

        return view('pages.admin.strukturorg.rt.index', compact('regency', 'rt'));
    }

    public function newGetDataOrgRT(Request $request){

        ## Read value
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // Rows display per page

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value

         // Total records
         $totalRecords = DB::table('org_diagram_rt')->select('count(*) as allcount')->count();
         $totalRecordswithFilter = DB::table('org_diagram_rt')->select('count(*) as allcount')->where('name', 'like', '%' .$searchValue . '%')->count();

          // Fetch records
        $records = DB::table('org_diagram_rt')
                ->orderBy($columnName,$columnSortOrder)
                ->where('base','KORRT')
                ->where('org_diagram_rt.name', 'like', '%' .$searchValue . '%')
                ->select('org_diagram_rt.*')
                ->skip($start)
                ->take($rowperpage)
                ->get();

        $data_arr = array();
        $no = 1;

        foreach($records as $record){
            $id = $no ++;
            $name = $record->name;
 
            $data_arr[] = array(
                "id" => $id,
                "name" => $name,
            );
        }

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr
         );
 
         return response()->json($response); 
    }

    public function getDataOrgRT(Request $request)
    {

            // if($request->ajax()){
            //     $data = DB::table('org_diagram_rt as a')
            //         ->select('a.idx', 'a.village_id', 'a.rt', 'a.rw', 'b.address', 'a.title', 'a.nik', 'a.name', 'b.photo', 'a.telp as phone_number', 'a.base', 'a.id', 'c.name as village', 'd.name as district', 'e.tps_number','b.id as user_id',
            //                 DB::raw("(select count(*) from org_diagram_rt where pidx = a.idx and base ='ANGGOTA') as count_anggota"),
            //                 DB::raw("(select count(*) from users where user_id = b.id and village_id is not null) as referal")
            //         )
            //         ->join('users as b', 'b.nik', '=', 'a.nik')
            //         ->join('villages as c', 'c.id', '=', 'a.village_id')
            //         ->join('districts as d', 'd.id', '=', 'a.district_id')
            //         ->leftJoin('tps as e', 'b.tps_id', '=', 'e.id')
            //         ->join('dapil_areas as f','a.district_id','=','f.district_id')
            //         ->where('a.base', 'KORRT')->get();

            //         $data =  collect($data);

            //     return DataTables::of($data)
            //             ->addIndexColumn()
            //             ->filter(function ($instance) use ($request) {
            //                 // if ($request->get('status') == '0' || $request->get('status') == '1') {
    
            //                 //$instance->where('status', $request->get('status'));
    
            //                 // }
    
            //                 if (!empty($request->get('search'))) {
    
            //                      $instance->where(function($w) use($request){
    
            //                         $search = $request->get('search');
    
            //                         $w->orWhere('a.name', 'LIKE', "%$search%")
    
            //                         ->orWhere('a.title', 'LIKE', "%$search%");
    
            //                     });
    
            //                 }
    
            //             })
            //             ->make(true);
            // }

            // return view('pages.admin.strukturorg.rt.index');

        // OLD
        // DATATABLE
        $orderBy = 'a.name';
        switch ($request->input('order.0.column')) {
            case '1':
                $orderBy = 'a.name';
                break;
        }

        $data = DB::table('org_diagram_rt as a')
            ->select('a.idx', 'a.village_id', 'a.rt', 'a.rw', 'b.address', 'a.title', 'a.nik', 'a.name', 'b.photo', 'a.telp as phone_number', 'a.base', 'a.id', 'c.name as village', 'd.name as district', 'e.tps_number','b.id as user_id',
                    DB::raw("(select count(*) from org_diagram_rt where pidx = a.idx and base ='ANGGOTA') as count_anggota"),
                    DB::raw("(select count(*) from users where user_id = b.id and village_id is not null) as referal"),
                    DB::raw("(select count(*) from anggota_koordinator_tps_korte where pidx_korte = a.idx) as form_kosong")
            )
            ->join('users as b', 'b.nik', '=', 'a.nik')
            ->join('villages as c', 'c.id', '=', 'a.village_id')
            ->join('districts as d', 'd.id', '=', 'a.district_id')
            ->leftJoin('tps as e', 'b.tps_id', '=', 'e.id')
            ->join('dapil_areas as f','a.district_id','=','f.district_id')
            ->where('a.base', 'KORRT');


        if ($request->input('search.value') != null) {
            $data = $data->where(function ($q) use ($request) {
                $q->whereRaw('LOWER(a.name) like ? ', ['%' . strtolower($request->input('search.value')) . '%']);
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

        if ($request->input('tps') != null) {
            $data->where('b.tps_id', $request->tps);
        }


        $recordsFiltered = $data->get()->count();
        if ($request->input('length') != -1) $data = $data->skip($request->input('start'))->take($request->input('length'));
        $data = $data->orderBy($orderBy, $request->input('order.0.dir'))->get();

        // $data = $data->orderBy('a.rt', 'asc');
        // $data = $data->get();

        $recordsTotal = $data->count();

        // $results = [];
        // $no = 1;
        // foreach ($data as $value) {
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
        //         'count_anggota' => $value->count_anggota,
        //         'referal' => $value->referal,
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

        $regency = Regency::select('id', 'name')->where('id', 3602)->first();

        // get village id where idx
        // $cek_kor = DB::table('org_diagram_rt as a')->select('b.village_id')
        //             ->join('users as b','b.nik','=','a.nik')
        //             ->where('a.idx', $idx)->first();

        // $village_id = $cek_kor->village_id;

        #creeate idx
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
                // dd($result_exp);
                // dd($result_new_idx);

            }
        } else {

            $result_new_idx = $idx . '.1';
        }


        return view('pages.admin.strukturorg.rt.create-anggota', compact('regency', 'result_new_idx', 'idx'));
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
            $cek_nik_org  = DB::table('org_diagram_rt')->where('nik', $user->nik)->count();
            if ($cek_nik_org > 0) return redirect()->back()->with(['warning' => 'NIK sudah terdaftar distruktur!']);

            #get villlage, regency, district, rt where idx
            $domisili = DB::table('org_diagram_rt')->select('regency_id', 'district_id', 'village_id', 'rt')->where('idx', $request->pidx)->first();

            // #create idx
            // $cek_count_org = DB::table('org_diagram_rt')->where('idx', $request->pidx)->count();

            // $result_new_idx = "";

            // if ($cek_count_org > 0) {

            //     $count_org     = DB::table('org_diagram_rt')->where('pidx', $request->pidx)->max('idx'); 

            //     $exp        = explode(".", $count_org);
            //     $count_exp  = count($exp);


            //     if($count_exp == 1) {

            //         $result_new_idx  = $request->pidx.$exp[0].".1";

            //     }else{

            //         // get nilai terakhir dari idx where pidx
            //         $end_number = end($exp); 
            //         $result_exp = (int) $end_number + 1;

            //         $result_new_idx  = $request->pidx.".".$result_exp;

            //     }         

            // }else{

            //     $result_new_idx = "KORRT";

            // }

            #cek jika tps koordinator tidak sama dengan tps calon anggotanya
            $koor = DB::table('org_diagram_rt as a')->select('b.tps_id')
                ->join('users as b', 'a.nik', '=', 'b.nik')
                ->where('a.idx', $request->pidx)
                ->first();
            $tpsKoor = $koor->tps_id;

            if ($tpsKoor != $request->tpsid) {

                return redirect()->back()->with(['error' => 'Gagal, TPS anggota tidak sama dengan TPS Koordinator!']);
            } else {

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
            ->select('a.id','a.name', 'a.nik', 'b.photo', DB::raw('(select COUNT(nik) from org_diagram_rt where nik = a.nik) as is_cover'))
            ->leftJoin('users as b', 'b.nik', '=', 'a.nik')
            ->where('a.pidx_korte', $idx)
            ->orderBy(DB::raw('(select COUNT(nik) from org_diagram_rt where nik = a.nik)'), 'desc')
            ->get();

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

        return view('pages.admin.strukturorg.rt.detailanggota', compact('kor_rt', 'anggotaKorTps', 'no', 'korte_idx','no_head_familly','resultsFamilyGroup'));
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
            ->join('tps as e','b.tps_id','=','e.id')
            ->where('a.pidx', $idx)
            ->where('a.base', 'ANGGOTA')
            ->get();

        $no = 1; 

        $pdf = PDF::LoadView('pages.report.memberbykorte', compact('kor_rt', 'members', 'no'))->setPaper('a4');
        return $pdf->download('ANGGOTA KOR TPS/RT '. $kor_rt->tps_number.'/'. $kor_rt->rt . ' (' . $kor_rt->name . ') DS.' . $kor_rt->village . '.pdf');
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

    public function getListDataAnggotaByKorRt(Request $request)
    {

        // DATATABLE
        $orderBy = 'a.name';
        switch ($request->input('order.0.column')) {
            case '1':
                $orderBy = 'a.name';
                break;
        }

        $data = DB::table('org_diagram_rt as a')
            ->select('a.id', 'a.idx', 'a.village_id', 'a.rt', 'a.rw', 'b.address', 'a.title', 'a.nik', 'a.name', 'b.photo', 'a.telp as phone_number', 'a.base', 'a.id', 'c.name as village', 'd.name as district', 'e.tps_number','b.id as user_id')
            ->leftJoin('users as b', 'b.nik', '=', 'a.nik')
            ->join('villages as c', 'c.id', '=', 'a.village_id')
            ->join('districts as d', 'd.id', '=', 'a.district_id')
            ->leftJoin('tps as e', 'b.tps_id', '=', 'e.id')
            ->where('pidx', $request->idx);


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

        $results = [];
        $no = 1;
        foreach ($data as $value) {
            $results[] = [
                'no' => $no++,
                'id' => $value->id,
                'idx' => $value->idx,
                'village_id' => $value->village_id,
                'address' => $value->address,
                'tps_number' => $value->tps_number,
                'village' => $value->village,
                'district' => $value->district,
                'title' => $value->title,
                'nik' => $value->nik,
                'name' => $value->name,
                'photo' => $value->photo,
                'phone_number' => $value->phone_number,
                'user_id' => $value->user_id

            ];
        }

        return response()->json([
            'draw' => $request->input('draw'),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $results
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

        return view('pages.admin.strukturorg.district.index', compact('regency', 'rt'));
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
            ->join('districts as d', 'd.id', '=', 'c.district_id')
            ->join('dapil_areas as e','d.id','=','e.district_id');


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
            $data->where('e.dapil_id', $request->dapil);
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

        $gF = new GlobalProvider();

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
                    'c.tps_number',
                        DB::raw('(select count(*) from org_diagram_rt where pidx = a.idx and base = "ANGGOTA") as total_members'),
                        DB::raw('(select count(b2.id) from users as b2 where b2.user_id = b.id and b2.village_id is not null ) as referal')
                    )
                ->join('users as b', 'a.nik', '=', 'b.nik')
                ->join('tps as c','b.tps_id','=','c.id')
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
                    ->select('a.base', 'a.rt', 'b.name', 'c.name as village', 'd.name as district', 'b.address', 'a.telp','e.tps_number',
                        DB::raw('TIMESTAMPDIFF(YEAR, b.date_berth, NOW()) as usia')
                    )
                    ->join('users as b', 'b.nik', '=', 'a.nik')
                    ->join('villages as c', 'c.id', '=', 'a.village_id')
                    ->join('districts as d', 'd.id', '=', 'a.district_id')
                    ->join('tps as e','b.tps_id','=','e.id')
                    ->where('a.pidx', $korte->idx)
                    ->where('a.base', 'ANGGOTA')
                    ->get();


                $no = 1;

                $fileName = 'ANGGOTA KOR TPS_RT. ' .$korte->tps_number .'_'. $korte->rt . ' (' . $korte->name . ') DS.' . $village->name . '.pdf';

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

        }elseif ($request->report_type == 'Download Pengurus + Tim Kor TPS PDF'){

            // get data kordes by village
            $abs_kordes = DB::table('org_diagram_village as a')
                ->select('b.name', 'a.title','b.id', 
                        DB::raw('(select count(a2.id) from users as a2 where a2.user_id = b.id and a2.village_id is not null) as referal')
                    )
                ->join('users as b','a.nik','=','b.nik')
                ->where('a.village_id', $village->id)
                ->whereNotNull('a.nik')
                ->orderBy('a.level_org', 'asc')
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

            }else{

                $kordes = $abs_kordes;
            }


            $abs_kortes    = DB::table('org_diagram_rt as a')
                ->select('b.id','a.idx','b.name', 'a.title', 'a.rt', 'b.gender', 'a.telp', 'b.address','c.tps_number',
                    DB::raw('(select count(a2.id) from org_diagram_rt as a2 where a2.base = "ANGGOTA" and pidx =  a.idx) as jml_anggota'),
                    DB::raw('(select count(b1.id) from users as b1 where b1.user_id = b.id and b1.village_id is not null) as referal')
                )
                ->join('users as b', 'a.nik', '=', 'b.nik')
                ->join('tps as c','b.tps_id','=','c.id')
                ->where('a.village_id', $village_id)
                ->whereNotNull('a.nik')
                ->where('a.base', 'KORRT')
                ->groupBy('a.idx','a.name', 'a.title', 'a.rt', 'b.gender', 'a.telp', 'b.address','c.tps_number','b.id')
                ->orderBy('c.tps_number', 'asc')
                ->get();

            //total jml anggota 
            $jml_anggota = collect($abs_kortes)->sum(function($q){
                return $q->jml_anggota;
             });
             //total jml referal
            $jml_referal =  collect($abs_kortes)->sum(function($q){
                return $q->referal;
             });

            $no = 1;

            $pdf = PDF::LoadView('pages.report.pengurusdantimkortps', compact('village', 'kordes', 'abs_kortes', 'no','jml_anggota','jml_referal','gF'))->setPaper('a4');
            return $pdf->download('DAFTAR PENGURUS DAN TIM KORTPS DESA ' . $village->name . '.pdf'); 

        }elseif ($request->report_type == 'Download Tim Kor TPS Belum Ada Data Form Kosong'){

             $abs_kordes = DB::table('org_diagram_village as a')
                ->select('a.name', 'a.title','b.id', 
                        DB::raw('(select count(a2.id) from users as a2 where a2.user_id = b.id and a2.village_id is not null) as referal')
                    )
                ->join('users as b','a.nik','=','b.nik')
                ->where('a.village_id', $village->id)
                ->whereNotNull('a.nik')
                ->orderBy('a.level_org', 'asc')
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

            }else{

                $kordes = $abs_kordes;
            }

            $abs_kortes    = DB::table('org_diagram_rt as a')
                ->select('b.id','a.idx','a.name', 'a.title', 'a.rt', 'b.gender', 'a.telp', 'b.address','c.tps_number',
                    DB::raw('(select count(a2.id) from org_diagram_rt as a2 where a2.base = "ANGGOTA" and pidx =  a.idx) as jml_anggota'),
                    DB::raw('(select count(b1.id) from users as b1 where b1.user_id = b.id and b1.village_id is not null) as referal'),
                    DB::raw('(select count(nik) from anggota_koordinator_tps_korte where pidx_korte = a.idx) as jml_formkosong')
                ) 
                ->join('users as b', 'a.nik', '=', 'b.nik')
                ->join('tps as c','b.tps_id','=','c.id')
                ->where('a.village_id', $village_id)
                ->whereNotNull('a.nik')
                ->where('a.base', 'KORRT')
                ->groupBy('a.idx','a.name', 'a.title', 'a.rt', 'b.gender', 'a.telp', 'b.address','c.tps_number','b.id')
                ->havingRaw('(select count(nik) from anggota_koordinator_tps_korte where pidx_korte = a.idx) = 0')
                ->orderBy('c.tps_number', 'asc')
                ->get();

            //total jml anggota 
            $jml_anggota = collect($abs_kortes)->sum(function($q){
                return $q->jml_anggota;
             });
             //total jml referal
            $jml_referal =  collect($abs_kortes)->sum(function($q){
                return $q->referal;
             });

            $jml_formkosong =  collect($abs_kortes)->sum(function($q){
                return $q->jml_formkosong;
             });

            $no = 1;

            $pdf = PDF::LoadView('pages.report.kortpsbelumsetordataformkosong', compact('village', 'kordes', 'abs_kortes', 'no','jml_anggota','jml_referal','gF','jml_formkosong'))->setPaper('a4');
            return $pdf->download('DAFTAR PENGURUS DAN TIM KORTPS (BELUM SETOR DATA FORM KOR TPS) DESA ' . $village->name . '.pdf'); 

        }else {
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

    public function searchArrayValueName($data, $field)
    {

        foreach ($data as $row) {
            if ($row->title == $field)
                return $row->name;
        }
    }

    public function searchArrayValuePhoto($data, $field)
    {

        foreach ($data as $row) {
            if ($row->title == $field)
                return $row->photo;
        }
    }

    public function searchArrayValueTim($data, $field)
    {

        foreach ($data as $row) {
            if ($row->JABATAN == $field)
                return $row->JABATAN;
        }
    }

    public function searchArrayValueCountReferal($data, $field)
    {

        foreach ($data as $row) {
            if ($row->title == $field)
                return $row->referal;
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
        
        }elseif($request->report_type == 'Surat Undangan Rapat Konsolidasi Kordapil, Korcam & Admin'){

            //id dapil 1 = 8
            //id dapil 4 = 11
            //id dapil 5 = 12
            if($dapil_id == 8){

                return 'Dapil 1 gak perlu pakai Undangan, sudah di info';

            }elseif($dapil_id == 12){ // dapil 5

                // sessi 1
                if ($district_id == 3602011) {  // wanasalam
                     $jam = '09:00 s/d 12:00 WIB';
                     $lokasi = "SEKERTARIAT JARINGAN DULUR AAW - BINUANGEN";
                     $hari = 'Selasa,31 Oktober 2023';
                     $lok_surat = 'Binuangeun';
                }else{
                     $jam = '13:00 s/d 16:00 WIB';
                     $lokasi = "SEKERTARIAT JARINGAN DULUR AAW - BINUANGEN";
                     $hari = 'Selasa,31 Oktober 2023';
                     $lok_surat = 'Binuangeun';
                }

                // get data korcam kec.wanasalam
                $district = DB::table('districts')->select('name')->where('id', $district_id)->first();
                // get data korcam by kecamatan
                $korcam = DB::table('org_diagram_district as a')
                        ->select('b.nik','b.name','a.title','c.name as village','a.telp','b.address')
                        ->join('users as b', 'a.nik', '=', 'b.nik')
                        ->join('villages as c','b.village_id','=','c.id')
                        ->where('a.district_id', $district_id)
                        ->orderBy('a.district_id','asc')
                        ->orderBy('a.level_org','asc')
                        ->get();

                // get kordes by kecamatan
                $kordes = DB::table('org_diagram_village as a')
                    ->select('a.nik', 'a.name','b.address','c.name as village','a.telp','a.title')
                    ->join('users as b', 'a.nik', '=', 'b.nik')
                    ->join('villages as c','b.village_id','=','c.id')
                    ->where('a.district_id', $district_id)
                    ->orderBy('a.village_id','asc')
                    ->orderBy('a.level_org','asc')
                    ->get();


                $fileName = 'SURAT UNDANGAN TIM KORCAM KORDES KEC. ' . $district->name . '.pdf';

                $pdf  = PDF::LoadView('pages.report.suratundanganoktobernovember', compact('jam', 'lokasi', 'hari', 'lok_surat','korcam','kordes'))->setPaper('a4');
                // $pdfFilePath = public_path('/docs/suratundanganoktobernovember/SURAT UNDANGAN TIM KORCAM KORDES KEC.' . $district->name . '/' . $fileName);
                return $pdf->download('SURAT UNDANGAN TIM KORCAM KORDES KEC. ' . $district->name . '.pdf');

            }elseif($dapil_id == 11){ // dapil 4 
                // sessi 1
                if ($district_id == 3602021 || $district_id == 3602020) {  // cihara dan panggarangan 
                     $jam = '09:00 s/d 12:00 WIB';
                     $lokasi = "SEKERTARIAT JARINGAN DULUR AAW - BINUANGEN";
                     $hari = 'Rabu,01 November 2023';
                     $lok_surat = 'Binuangeun';
                }else{
                     $jam = '13:00 s/d 16:00 WIB';
                     $lokasi = "SEKERTARIAT JARINGAN DULUR AAW - BINUANGEN";
                     $hari = 'Rabu,01 November 2023';
                     $lok_surat = 'Binuangeun';
                }

                // get data korcam kec.wanasalam
                $district = DB::table('districts')->select('name')->where('id', $district_id)->first();
                // get data korcam by kecamatan
                $korcam = DB::table('org_diagram_district as a')
                        ->select('b.nik','b.name','a.title','c.name as village','a.telp','b.address')
                        ->join('users as b', 'a.nik', '=', 'b.nik')
                        ->join('villages as c','b.village_id','=','c.id')
                        ->where('a.district_id', $district_id)
                        ->orderBy('a.district_id','asc')
                        ->orderBy('a.level_org','asc')
                        ->get();

                // get kordes by kecamatan
                $kordes = DB::table('org_diagram_village as a')
                    ->select('a.nik', 'a.name','b.address','c.name as village','a.telp','a.title')
                    ->join('users as b', 'a.nik', '=', 'b.nik')
                    ->join('villages as c','b.village_id','=','c.id')
                    ->where('a.district_id', $district_id)
                    ->orderBy('a.village_id','asc')
                    ->orderBy('a.level_org','asc')
                    ->get();


                $fileName = 'SURAT UNDANGAN TIM KORCAM KORDES KEC. ' . $district->name . '.pdf';

                $pdf  = PDF::LoadView('pages.report.suratundanganoktobernovember', compact('jam', 'lokasi', 'hari', 'lok_surat','korcam','kordes'))->setPaper('a4');
                // $pdfFilePath = public_path('/docs/suratundanganoktobernovember/SURAT UNDANGAN TIM KORCAM KORDES KEC.' . $district->name . '/' . $fileName);
                return $pdf->download('SURAT UNDANGAN TIM KORCAM KORDES KEC. ' . $district->name . '.pdf');
            }

        }
        elseif ($request->report_type == 'Download Surat Undangan Per Kecamatan') {

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
            // $korcam          = $OrgModel->getKorcamByKecamatanForTitle($district_id); 'DOT'
            $korcam = DB::table('org_diagram_district as a')
                        ->select('b.nik','b.name','a.title','c.name as village','a.telp','b.address')
                        ->join('users as b', 'a.nik', '=', 'b.nik')
                        ->join('villages as c','b.village_id','=','c.id')
                        ->where('a.district_id', $district_id)
                        ->orderBy('a.district_id','asc')
                        ->orderBy('a.level_org','asc')
                        ->get();
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

        return view('pages.admin.strukturorg.rt.formkoordinatortpskorte', compact('idx'));
    }

    public function storeFormKoordinatorTps(Request $request, $idx)
    {


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
            $desc             = ucfirst(strtolower($kortps->name)).', RT.'.$kortps->rt.', Ds.'.ucfirst(strtolower($kortps->village)).', Kec.'.ucfirst(strtolower($kortps->district));

            return redirect()->back()->with(['error' => 'NIK sudah terdaftar di Kor TPS '.$desc.' !']);

        }else{

            // cek ke table users apakah ada anggota dengan nik tersebut
            $member = User::select('name', 'nik')->where('nik', $request->nik)->first();
            // jika ada sesuaikan namanya by nik yg ada di table users
            $name   = $member == null ? $request->name : $member->name;
    
            $auth = auth()->guard('admin')->user()->id;
    
            $koorModel->store($idx, $request, $name, $auth);
    
            return redirect()->back()->with(['success' => 'Anggota berhasil disimpan!']);
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

        $gF = new GlobalProvider();

        // tampilkan data dapil 
        $regency = 3602;
        // $dapils  = DB::table('dapils')->select('id','name')->where('regency_id', $regency)->get();
        $no      = 1;

        $orgDiagramModel = new OrgDiagram();
        $data            = $orgDiagramModel->getDataDaftarTimByRegency($regency);
        // dd($data);

        // mendapatkan jumlah target masing2 kecamatan per dapilnya
        // $arr_jml_target  = [];
        // foreach ($data as $value) {
        //     // merubahnya menjadi collection, agar mudah menjumlahkannya
        //     $arr_jml_target[] = collect([
        //         (object)[
        //             'target' => $orgDiagramModel->getDataDaftarTimByDapilForRegency($value->id)
        //         ]
        //     ]);
        // }
        // // jumlakan hasil all target kecamatan by dapil
        // $jml_target = collect($arr_jml_target)->sum(function($q){
        //     return $q[0]->target;
        // });

        $results = [];
        foreach ($data as $val) {
            $target = $orgDiagramModel->getDataDaftarTimByDapilForRegency($val->id);

            $results[] = [
                'id' => $val->id,
                'name' => $val->name,
                'k' => $val->k,
                's' => $val->s,
                'b' => $val->b,
                'dpt' => $val->dpt,
                'anggota' => $val->anggota,
                'anggota_tercover_kortps' => $val->anggota_tercover_kortps,
                'belum_tercover_kortps' => $val->anggota - $val->anggota_tercover_kortps,
                // 'target_korte' => $val->target_korte,
                'target_korte' => $target / 25,
                'korte_terisi' => $val->korte_terisi,
                'saksi' => $val->saksi,
                'target' => $target,
                'tps'    => $val->tps,
                'saksi'    => $val->saksi,
            ];
        }

        $dapils          = $results;

        $jml_ketua = collect($dapils)->sum(function($q){
            return $q['k'];
        });
        $jml_sekretaris = collect($dapils)->sum(function($q){
            return $q['s'];
        });

        $jml_bendahara = collect($dapils)->sum(function($q){
            return $q['b'];
        });
        $jml_dpt =  collect($dapils)->sum(function($q){
            return $q['dpt'];
        });

        $jml_anggota =  collect($dapils)->sum(function($q){
            return $q['anggota'];
        });

        $jml_target_korte =  collect($dapils)->sum(function($q){
            return $q['target_korte'];
        });

        $jml_korte_terisi =  collect($dapils)->sum(function($q){
            return $q['korte_terisi'];
        });

        $jml_anggota_tercover = collect($dapils)->sum(function($q){
            return $q['anggota_tercover_kortps'];
        });

        $jml_kurang_korte     = $jml_korte_terisi - $jml_target_korte;
        // $jml_blm_ada_korte    = 0;
        $jml_saksi            = collect($dapils)->sum(function($q){
            return $q['saksi'];
        });
        $persentage_target    = ($jml_anggota/$jml_dpt)*100;

        // $tmp_blm_ada_korte = $jml_anggota_tercover - $jml_anggota;
        $jml_blm_ada_korte = $jml_anggota - $jml_anggota_tercover;
        // if ($jml_blm_ada_korte == - 0) {
        //     $jml_blm_ada_korte = 0;
        // }elseif ($jml_blm_ada_korte > 0) {
        //     $jml_blm_ada_korte = '+'.$gF->decimalFormat($jml_blm_ada_korte);
        // }

         // // jumlakan hasil all target kecamatan by dapil
        $jml_target = collect($results)->sum(function($q){
            return $q['target'];
        });
        $jml_tps  = collect($data)->sum(function($q){
            return $q->tps;
        });

        return view('pages.admin.strukturorg.rt.daftartim.dapil', compact('jml_saksi','jml_kurang_korte','jml_tps','persentage_target','jml_blm_ada_korte','jml_anggota_tercover','jml_korte_terisi','jml_target_korte','jml_dpt','jml_ketua','jml_sekretaris','jml_bendahara','dapils','no','gF','jml_target','jml_anggota'));

    }

    public function daftatTimDapil($dapilId){

        $dapil = DB::table('dapils')->select('name')->where('id', $dapilId)->first();
        $no    = 1;
        $gF = new GlobalProvider();

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

        $jml_target_persentage = collect($data)->sum(function($q){
            return $q->target_persentage;
        });

        // $target = $jml_dpt > 0 ?  ($jml_dpt * $jml_target_persentage) / 100 : 0;
        // $jml_target_kortps = $target / 25;

        // dd($jml_target_kortps);

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
        // if ($jml_blm_ada_korte == - 0) {
        //     $jml_blm_ada_korte = 0;
        // }elseif ($jml_blm_ada_korte > 0) {
        //     $jml_blm_ada_korte = '+'.$gF->decimalFormat($jml_blm_ada_korte);
        // }

        $jml_saksi            = collect($data)->sum(function($q){
            return $q->saksi;
        });
        $persentage_target    = ($jml_anggota/$jml_dpt)*100;
        $jml_target           = collect($data)->sum(function($q){
            return ($q->dpt * $q->target_persentage)/100;
        });

        // NEW
        $jml_target_kortps = $jml_target / 25;
        $kortps_plus_minus = $jml_korte_terisi - $jml_target_kortps;
       

        $persen_dari_target_kab = $jml_target > 0 ? ($jml_anggota/$jml_target)*100 : 0;
        $jml_tps  = collect($data)->sum(function($q){
            return $q->tps;
        });

        return view('pages.admin.strukturorg.rt.daftartim.district', compact('jml_tps','persen_dari_target_kab','dapil','no','data','jml_ketua','jml_sekretaris','jml_bendahara','jml_bendahara','jml_dpt','jml_anggota','jml_target_korte','jml_korte_terisi','jml_anggota_tercover','jml_kurang_korte','jml_blm_ada_korte','persentage_target','jml_target','gF','jml_saksi','jml_target_kortps','kortps_plus_minus'));

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
            return ResponseFormatter::error([
                'message' => 'Something when wrong!',
                'error'   => $e->getMessage()
            ]);
        }
    }


}
