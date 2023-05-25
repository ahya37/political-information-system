<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\OrgDiagram;
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

class OrgDiagramController extends Controller
{
    public $excel;
    public function __construct(Excel $excel)
    {
        $this->excel = $excel;
    }

    public function index(){

        $provinceModel = new Province();
        $province = $provinceModel->getDataProvince();

        return view('pages.admin.strukturorg.index',compact('province'));
    }

    public function show(){
        
        $regency_id = request('regency_id');
        $dapil_id   = request('dapil_id');
        $district_id= request('district_id');
        $village_id = request('village_id');

        $org_diagram= OrgDiagram::select('id','idx','parent','title','name','image','user_id','base','regency_id','dapil_id','district_id','village_id')
                                ->orderBy('idx','asc')->get();

        return response()->json([
            'data' => $org_diagram
        ]);
    }

    public function storeOrg(Request $request){

        DB::beginTransaction();

        try {

           $validator =  Validator::make($request->all(), [
                'nik' => 'required|numeric',
                'title' => 'required|string',
            ]);

            if ($validator->fails()) {
                return ResponseFormatter::error([
                    'message' => 'Lengkapi data, wajib diisi!',
                ],401);
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
                ],204); 
            }else{

                #get user_id by nik
                $user = User::select('id','name','photo')->where('nik', $nik)->first();

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
                ],200); 
               
            }

        } catch (\Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'message' => 'Somethin when wrong!',
                'error' => $e->getMessage()
            ]);
        }

    }

    public function updateOrg(){

        DB::beginTransaction();
        try {

            $idx   = request()->idx;
            $title = request()->title;
            $id    = request()->id;

            #update org
            $org = OrgDiagram::where('id', $id)->first();
            $org->update([
                'title' => $title,
                'idx' => $idx
                ]
            );

            DB::commit();
            return ResponseFormatter::success([
                   'message' => 'Berhasil update struktur!'
            ],200);

        } catch (\Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'message' => 'Something when wrong!',
                'error' => $e->getMessage()
            ]);
        }

    }

    public function deleteOrg(){

        DB::beginTransaction();
        try {

            $id    = request()->id;

            #update org
            $org = OrgDiagram::where('id', $id)->delete();

            DB::commit();
            return ResponseFormatter::success([
                   'message' => 'Berhasil hapus struktur!'
            ],200);

        } catch (\Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'message' => 'Something when wrong!',
                'error' => $e->getMessage()
            ]);
        }

    }

    public function orgDiagramTest(){

        $regency = Regency::select('id','name')->where('id', 3602)->first();

        return view('pages.admin.strukturorg.index',['regency' => $regency]);

    }

    public function getDataOrgDiagramVillage(){

        $village_id = request('village');
        $orgs = DB::table('org_diagram_village')
                ->select('idx','pidx','color','title','nik','name','photo')
                ->whereNotNull('pidx')
                ->where('village_id', $village_id)
                ->get();

        $data = [];
        foreach ($orgs as $value) {
            $data[] = [$value->pidx,$value->idx];
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
                    'image' => '/storage/'.$value->photo ?? '',
                ];
            }else{
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

    public function getDataOrgDiagramRT(){

        $village_id   = request('village');

        $orgs = DB::table('org_diagram_rt')
                ->select('idx','pidx','title','nik','name','photo','rt')
                ->whereNotNull('pidx')
                ->where('base','KORRT')
                ->where('nik','!=',null)
                ->where('village_id', $village_id)
                ->orderBy('rt','asc')->get();

        $results = [];
        foreach ($orgs as $value) {
            $child = DB::table('org_diagram_rt')
                ->select('idx','pidx','title','name','photo')->whereNotNull('pidx')->where('base','ANGGOTA')->where('pidx', $value->idx)->get();
            $count_child = count($child);
            $results[]= [
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

    public function getDataOrgDiagramRTMemberNew(){

        $rt           = request('rt');
        $village_id   = request('village');

        $orgs = DB::table('org_diagram_rt')
                ->select('idx','pidx','title','nik','name','photo')
                ->whereNotNull('pidx')
                ->where('base','KORRT')
                ->where('village_id', $village_id)->where('rt', $rt)
                ->orderBy('name','asc')->get();

        $results = [];
        foreach ($orgs as $value) {
            $child = DB::table('org_diagram_rt')
                ->select('idx','pidx','title','name','photo')->whereNotNull('pidx')->where('base','ANGGOTA')->where('pidx', $value->idx)->get();
            $count_child = count($child);
            
            $results[]= [
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

    public function getDataOrgDiagramDistrict(){

        $district_id = request('district');
        $orgs = DB::table('org_diagram_district')
                ->select('idx','pidx','color','title','nik','name','photo','id')
                ->whereNotNull('pidx')
                ->where('district_id', $district_id)
                ->get();

        $data = [];
        foreach ($orgs as $value) {
            $data[] = [$value->pidx,$value->idx];
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
                    'image' => '/storage/'.$value->photo ?? '',
                ];
            }else{
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

    public function getDataOrgDiagramDapil(){

        $dapil_id = request('dapil');
        $orgs = DB::table('org_diagram_dapil')
                ->select('idx','pidx','color','title','nik','name','photo')
                ->whereNotNull('pidx')
                ->where('dapil_id', $dapil_id)
                ->get();

        $data = [];
        foreach ($orgs as $value) {
            $data[] = [$value->pidx,$value->idx];
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
                    'image' => '/storage/'.$value->photo ?? '',
                ];
            }else{
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

    public function getDataOrgDiagramPusat(){

        $orgs = DB::table('org_diagram_pusat')
                ->select('idx','pidx','color','title','nik','name','photo')
                ->whereNotNull('pidx')
                ->get();

        $data = [];
        foreach ($orgs as $value) {
            $data[] = [$value->pidx,$value->idx];
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
                    'image' => '/storage/'.$value->photo ?? '',
                ];
            }else{
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

    public function indexOrgVillage(){

        $regency = Regency::select('id','name')->where('id', 3602)->first();

        $rt      = 30;

        return view('pages.admin.strukturorg.village.index', compact('regency','rt'));

    }

    public function getDataOrgVillage(Request $request){

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
                ->select('a.id','a.idx','a.village_id','a.rt','a.rw','b.address','a.title','a.nik','a.name','b.photo','a.telp as phone_number','c.name as village','d.name as district')
                ->join('users as b','b.nik','=','a.nik')
                ->join('villages as c','c.id','=','a.village_id')
                ->join('districts as d','d.id','=','a.district_id');

            
        if($request->input('search.value')!=null){
                $data = $data->where(function($q)use($request){
                    $q->whereRaw('LOWER(a.name) like ? ',['%'.strtolower($request->input('search.value')).'%']);
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
          if($request->input('length')!=-1) $data = $data->skip($request->input('start'))->take($request->input('length'));
          $data = $data->orderBy('a.level_org','asc');
          $data = $data->orderBy($orderBy,$request->input('order.0.dir'))->get();
          
          $recordsTotal = $data->count();

          return response()->json([
                'draw'=>$request->input('draw'),
                'recordsTotal'=>$recordsTotal,
                'recordsFiltered'=>$recordsFiltered,
                'data'=> $data
            ]);
    }

    public function setSaveOrgVillage(){

        DB::beginTransaction();
        try {

            $idx         = request()->id;
            $nik         = request()->nik;
            $villageId   = request()->regionId;
            $title       = request()->title;

            #cek nik di tb users, apakah sudah terdaftar
            $cek_user       =  DB::table('users')->select('photo','name')->where('nik', $nik);
            $cek_count_user = $cek_user->count();

            if ($cek_count_user < 1) {
                
                return ResponseFormatter::error([
                    'message' => 'NIK tidak tersedia di sistem!',
                ]);

            }else{

                $org         = DB::table('org_diagram_village');
    
                #cek apakah nik tersebut sudah ada di tb org_diagram_village
                $user        =  $org->where('nik', $nik)->count();
    
                if ($user > 0) {
                    
                    return ResponseFormatter::error([
                        'message' => 'NIK sudah terdaftar distruktur!',
                    ]);
    
                }else{
    
                    #membuat idx
                    #hitung data terakhir dari $idx, lalu di tambah 1 : XXXX.KORRT.1.x
                    $data       = DB::table('org_diagram_village')->where('pidx', $idx)->where('village_id', $villageId)->max('idx');
                    $data_count = DB::table('org_diagram_village')->where('pidx', $idx)->where('village_id', $villageId)->count('idx');
        
                    #jika belum ada
                    if (!$data) {
                        
                        #buat idx baru
                        $new_idx = $data_count + 1; 
                        $new_idx = $idx.".".$new_idx;
        
                    }else{
        
                        #hitung jumlah data terakhir dari $idx, lalu di tambah 1 : XXXX.KORRT.1.x
                        $new_idx    = $data_count + 1;
                        $new_idx    = $idx.".".$new_idx;
                    }
    
                    $user = $cek_user->first();
        
        
                    #get id domisili by villageId
                    $domisili = DB::table('villages as a')
                                ->join('districts as b','b.id','=','a.district_id')
                                ->select('a.id as village_id','b.id as district_id','b.regency_id')
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
                                ]);
        
                    DB::commit();
                    return ResponseFormatter::success([
                           'message' => 'Berhasil tambah struktur!'
                    ],200);
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

    public function setSaveOrgDistrict(){

        DB::beginTransaction();
        try {

            $idx         = request()->id;
            $nik         = request()->nik;
            $districtId   = request()->regionId;
            $title       = request()->title;

            #cek nik di tb users, apakah sudah terdaftar
            $cek_user       =  DB::table('users')->select('photo','name')->where('nik', $nik);
            $cek_count_user = $cek_user->count();

            if ($cek_count_user < 1) {
                
                return ResponseFormatter::error([
                    'message' => 'NIK tidak tersedia di sistem!',
                ]);

            }else{

                $org         = DB::table('org_diagram_district');
    
                #cek apakah nik tersebut sudah ada di tb org_diagram_village
                $user        =  $org->where('nik', $nik)->count();
    
                if ($user > 0) {
                    
                    return ResponseFormatter::error([
                        'message' => 'NIK sudah terdaftar distruktur!',
                    ]);
    
                }else{
    
                    #membuat idx
                    #hitung data terakhir dari $idx, lalu di tambah 1 : XXXX.KORRT.1.x
                    $data       = DB::table('org_diagram_district')->where('pidx', $idx)->where('district_id', $districtId)->max('idx');
                    $data_count = DB::table('org_diagram_district')->where('pidx', $idx)->where('district_id', $districtId)->count('idx');

        
                    #jika belum ada
                    if (!$data) {
                        
                        #buat idx baru
                        $new_idx = $data_count + 1; 
                        $new_idx = $idx.".".$new_idx;

                    }else{
        
                        #hitung jumlah data terakhir dari $idx, lalu di tambah 1 : XXXX.KORRT.1.x
                        // $new_idx    = $data_count;
                        $new_idx    = $idx.".".$data_count;
                    }
    
                    $user = $cek_user->first();
        
                    #get id domisili by villageId
                    $domisili = DB::table('districts')->select('id','regency_id')->where('id', $districtId)->first();
        
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
                                ]);
        
                    DB::commit();
                    return ResponseFormatter::success([
                           'message' => 'Berhasil tambah struktur!'
                    ],200);
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

    public function listDataStrukturPusat(){

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


    public function setOrderStrukturOrgPusat(){

        DB::beginTransaction();
        try {
            
            $idx = request()->data;

            $orgTable =  DB::table('org_diagram_pusat');

            $old_data = $orgTable->select('idx')->orderBy('idx','asc')->get();

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
         ],200);
        } catch (\Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'message' => 'Something when wrong!',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function createOrgVillage(){

        $regency = Regency::select('id','name')->where('id', 3602)->first();

        #creeate idx
            $cek_count_org = DB::table('org_diagram_village')->count();

            $result_new_idx = "";

            if ($cek_count_org > 0) {
                
                $count_org     = DB::table('org_diagram_village')->select('idx')->orderBy('id','desc')->get(); 

                $exp        = explode(".", $count_org);
                $count_exp  = count($exp);
                
                if($count_exp == 1) {

                    $result_new_idx = $exp[0].".1";

                }else{

                    $result_exp = (int) $exp[1]+1;
    
                    $result_new_idx  = time()."KORDES.".$result_exp;

                }         
                
            }else{

                $result_new_idx = "KORDES";
                
            }


        return view('pages.admin.strukturorg.village.create', compact('regency','result_new_idx'));
    }

    public function saveOrgVillage(Request $request){

        DB::beginTransaction();
        try {

            #cek ketersediaan nik di tb users
            $userTable     = DB::table('users');
            // $cek_nik_user  = $userTable->select('nik','name')->where('id', $request->member)->first();
            $user         = $userTable->select('name','photo','nik')->where('id', $request->member)->first();
            
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
            ]);

            DB::commit();
            return redirect()->back()->with(['success' => 'Data telah tersimpan!']);
           
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['error' => 'Data gagal tersimpan!'. $e->getMessage()]);
        }
        
    }

    public function indexOrgRT(){

        $regency = Regency::select('id','name')->where('id', 3602)->first();

        $rt      = 30;

        return view('pages.admin.strukturorg.rt.index', compact('regency','rt'));
    }

    public function getDataOrgRT(Request $request){

        // DATATABLE
        $orderBy = 'a.name';
        switch ($request->input('order.0.column')) {
            case '1':
                $orderBy = 'a.name';
                break;
        }

        $data = DB::table('org_diagram_rt as a')
                ->select('a.idx','a.village_id','a.rt','a.rw','b.address','a.title','a.nik','a.name','b.photo','a.telp as phone_number','a.base','a.id','c.name as village','d.name as district')
                ->join('users as b','b.nik','=','a.nik')
                ->join('villages as c','c.id','=','a.village_id')
                ->join('districts as d','d.id','=','a.district_id')
                ->where('base','KORRT');

            
        if($request->input('search.value')!=null){
                $data = $data->where(function($q)use($request){
                    $q->whereRaw('LOWER(a.name) like ? ',['%'.strtolower($request->input('search.value')).'%']);
                });
            }

            if ($request->input('village') != null) {
                            $data->where('a.village_id', $request->village);
            }

            if ($request->input('rt') != null) {
                            $data->where('a.rt', $request->rt);
            }


          $recordsFiltered = $data->get()->count();
          if($request->input('length')!=-1) $data = $data->skip($request->input('start'))->take($request->input('length'));
        
          $data = $data->orderBy('a.rt','asc');
          $data = $data->orderBy($orderBy,$request->input('order.0.dir'));
          $data = $data->get();
          
          $recordsTotal = $data->count();

          $results = [];
          $no = 1;
          foreach ($data as $value) {
            $count_anggota = DB::table('org_diagram_rt')->where('pidx', $value->idx)->count();
            $results[] = [
                'no' => $no++,
                'id' => $value->id,
                'idx' => $value->idx,
                'village_id' => $value->village_id,
                'rt' => $value->rt,
                'rw' => $value->rw,
                'address' => $value->address,
                'village' => $value->village,
                'district' => $value->district,
                'title' => $value->title,
                'nik' => $value->nik,
                'name' => $value->name,
                'photo' => $value->photo,
                'phone_number' => $value->phone_number,
                'count_anggota' => $count_anggota,
                'base' => "KOR RT"

            ];
          }

          return response()->json([
                'draw'=>$request->input('draw'),
                'recordsTotal'=>$recordsTotal,
                'recordsFiltered'=>$recordsFiltered,
                'data'=> $results
            ]);
    }

    public function createOrgRT(){

        $regency = Regency::select('id','name')->where('id', 3602)->first();

        #creeate idx
            $cek_count_org = DB::table('org_diagram_rt')->count();

            $result_new_idx = "";

            if ($cek_count_org > 0) {
                
                $count_org     = DB::table('org_diagram_rt')->select('idx')->where('base','KORRT')->orderBy('id','desc')->first(); 
                $count_org   = $count_org->idx;
                $exp        = explode(".", $count_org);
                // dd($exp);
                $count_exp  = count($exp);
                
                if($count_exp == 1) {

                    $result_new_idx = $exp[0].".1";

                }else{

                    $result_exp = (int) $exp[1]+1;
    
                    $result_new_idx  = time()."KORRT.".$result_exp;

                }         
                
            }else{

                $result_new_idx = "KORRT";
                
            }

        return view('pages.admin.strukturorg.rt.create', compact('regency','result_new_idx'));
    }

    public function editAnggotaOrgRT($id){

        
        $regency = Regency::select('id','name')->where('id', 3602)->first();

        $anggota_korte = DB::table('org_diagram_rt')->select('name','telp')->where('id', $id)->first();
        
        return view('pages.admin.strukturorg.rt.edit-anggota', compact('regency','id','anggota_korte'));
    }

    public function editOrgRT($id){

        
        $regency = Regency::select('id','name')->where('id', 3602)->first();

        $korte = DB::table('org_diagram_rt')->select('name','telp','rt')->where('id', $id)->first();
        
        return view('pages.admin.strukturorg.rt.edit', compact('regency','id','korte'));
    }

    public function editTps($id){

        
        $regency = Regency::select('id','name')->where('id', 3602)->first();

        $korte = DB::table('org_diagram_rt')->select('name','telp','rt')->where('id', $id)->first();
        
        return view('pages.admin.strukturorg.rt.edittps', compact('regency','id','korte'));
    }

    public function editTpsMember($id){

        
        $regency = Regency::select('id','name')->where('id', 3602)->first();

        $korte = DB::table('org_diagram_rt')->select('name','telp','rt')->where('id', $id)->first();
        
        return view('pages.admin.strukturorg.rt.edittps-member', compact('regency','id','korte'));
    }

    public function createOrgRTAnggota($idx){

        $regency = Regency::select('id','name')->where('id', 3602)->first();

        // get village id where idx
        // $cek_kor = DB::table('org_diagram_rt as a')->select('b.village_id')
        //             ->join('users as b','b.nik','=','a.nik')
        //             ->where('a.idx', $idx)->first();

        // $village_id = $cek_kor->village_id;

        #creeate idx
            $cek_count_org = DB::table('org_diagram_rt')->where('pidx', $idx)->count();

            $result_new_idx = "";

            if ($cek_count_org > 0) {
                
                $count_org     = DB::table('org_diagram_rt')->select('idx')->where('pidx', $idx)->orderBy('id','desc')->first(); 
                $count_org   = $count_org->idx;
                $exp        = explode(".", $count_org);
                $count_exp  = count($exp);
                
                if($count_exp == 1) {

                    $result_new_idx = $exp[0].".1";

                }else{
                    
                    $result_exp = (int) $exp[2]+1;
                    $result_new_idx  = $exp[0].".".$exp[1].".".$result_exp;
                    // dd($result_exp);
                    // dd($result_new_idx);

                }         
                
            }else{

                $result_new_idx = $idx.'.1';
                
            }


        return view('pages.admin.strukturorg.rt.create-anggota', compact('regency','result_new_idx','idx'));
    }

    public function saveOrgRT(Request $request){

        DB::beginTransaction();
        try {

            #cek ketersediaan nik di tb users
            $userTable     = DB::table('users');

            $user         = $userTable->select('name','photo','nik')->where('id', $request->member)->first();

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
                'title'  => 'RT '.$request->rts,
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
            return redirect()->back()->with(['error' => 'Data gagal tersimpan!'. $e->getMessage()]);
        }
        
    }

    public function saveAnggotaByKorRT(Request $request)
    {
        DB::beginTransaction();
        try {


            #cek ketersediaan nik di tb users
            $userTable     = DB::table('users');
            // $cek_nik_user  = $userTable->where('id', $request->member)->count();
            $user         = $userTable->select('name','photo','phone_number','nik')->where('id', $request->member)->first();
            
            // if ($cek_nik_user == 0) return redirect()->back()->with(['warning' => 'NIK tidak terdaftar disistem!']);

            // #cek jika nik sudah terdaftar di tb org_diagram_village
            $cek_nik_org  = DB::table('org_diagram_rt')->where('nik', $user->nik)->count();
            if ($cek_nik_org > 0) return redirect()->back()->with(['warning' => 'NIK sudah terdaftar distruktur!']);

            #get villlage, regency, district, rt where idx
            $domisili = DB::table('org_diagram_rt')->select('regency_id','district_id','village_id','rt')->where('idx', $request->pidx)->first();

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
                        ->join('users as b','a.nik','=','b.nik')
                        ->where('a.idx', $request->pidx)
                        ->first();
            $tpsKoor = $koor->tps_id;

            if ($tpsKoor != $request->tpsid) {

                return redirect()->back()->with(['error' => 'Gagal, TPS anggota tidak sama dengan TPS Koordinator!']);

            } else{

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
                ]);
    
                DB::table('users')->where('nik', $user->nik)->update(['tps_id' => $request->tpsid]);
    
                DB::commit();
                return redirect()->back()->with(['success' => 'Data telah tersimpan!']);

            }
           
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['error' => 'Data gagal tersimpan!'. $e->getMessage()]);
        }
    }

    public function updateAnggotaByKorRT(Request $request, $id)
    {
        DB::beginTransaction();
        try {

            if ($request->member != null ) {
                #cek ketersediaan nik di tb users
                $userTable     = DB::table('users');
                // $cek_nik_user  = $userTable->where('id', $request->member)->count();
                $user         = $userTable->select('name','photo','phone_number','nik')->where('id', $request->member)->first();
                
                // if ($cek_nik_user == 0) return redirect()->back()->with(['warning' => 'NIK tidak terdaftar disistem!']);
    
                // #cek jika nik sudah terdaftar di tb org_diagram_village
                $cek_nik_org  = DB::table('org_diagram_rt')->where('nik', $user->nik)->count();
                if ($cek_nik_org > 0) return redirect()->back()->with(['warning' => 'NIK sudah terdaftar distruktur!']);
                
                $old_anggota_korte = DB::table('org_diagram_rt')->select('name','pidx')->where('id', $id)->first();
    
                // #get villlage, regency, district, rt where idx
                // $domisili = DB::table('org_diagram_rt')->select('regency_id','district_id','village_id','rt')->where('idx', $old_anggota_korte->pidx)->first();
                // dd($domisili);
    
                DB::table('org_diagram_rt')->where('id', $id)->update([
                    'nik'    => $user->nik,
                    'name'   => $user->name,
                    'photo'  => $user->photo ?? '',
                    'telp'  => $request->telp,
                ]);

            }else{
                $old_anggota_korte = DB::table('org_diagram_rt')->select('name','pidx')->where('id', $id)->first();

                DB::table('org_diagram_rt')->where('id', $id)->update([
                    'telp'  => $request->telp,
                ]);
            }

            DB::commit();
            return redirect()->route('admin-struktur-organisasi-rt-detail-anggota',['idx' => $old_anggota_korte->pidx]);
            return redirect()->back()->with(['success' => 'Data telah tersimpan!']);
           
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['error' => 'Data gagal tersimpan!'. $e->getMessage()]);
        }
    }

    public function updateKorRT(Request $request, $id)
    {
        DB::beginTransaction();
        try {

            if ($request->member != null ) {
                #cek ketersediaan nik di tb users
                $userTable     = DB::table('users');
                // $cek_nik_user  = $userTable->where('id', $request->member)->count();
                $user         = $userTable->select('name','photo','phone_number','nik')->where('id', $request->member)->first();
                
                // if ($cek_nik_user == 0) return redirect()->back()->with(['warning' => 'NIK tidak terdaftar disistem!']);
    
                // #cek jika nik sudah terdaftar di tb org_diagram_village
                $cek_nik_org  = DB::table('org_diagram_rt')->where('nik', $user->nik)->count();
                if ($cek_nik_org > 0) return redirect()->back()->with(['warning' => 'NIK sudah terdaftar distruktur!']);
                
                $old_anggota_korte = DB::table('org_diagram_rt')->select('name','pidx')->where('id', $id)->first();
    
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

            }else{

                DB::table('org_diagram_rt')->where('id', $id)->update([
                    'telp'  => $request->telp,
                    'rt'  => $request->rts,
                ]);
            }

            DB::commit();
            return redirect()->route('admin-struktur-organisasi-rt');
           
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['error' => 'Data gagal tersimpan!'. $e->getMessage()]);
        }
    }

    public function updateOrgRT(){

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
                $user         = $userTable->select('name','photo','phone_number','nik')->where('nik', $nik)->first();
                
                DB::table('org_diagram_rt')->where('id', $id)->update([
                    'nik'    => $user->nik,
                    'name'   => $user->name,
                    'photo'  => $user->photo ?? '',
                    'telp'  => $user->phone_number,
                ]);

            
                DB::commit();
                return ResponseFormatter::success([
                    'message' => 'Berhasil update struktur!'
                ],200);

            } catch (\Exception $e) {
                DB::rollback();
                return ResponseFormatter::error([
                    'message' => 'Something when wrong!',
                    'error'   => $e->getMessage()
                ]);
            }

    }

    public function detailAnggotaByKorRT($idx){

        $kor_rt = DB::table('org_diagram_rt as a')
                ->select('a.rt','a.name','c.name as village','d.name as district')
                ->join('users as b','b.nik','=','a.nik')
                ->join('villages as c','c.id','=','a.village_id')
                ->join('districts as d','d.id','=','a.district_id')
                ->where('idx', $idx)
                ->first();

        $data = DB::table('org_diagram_rt as a')
                    ->select('a.idx','a.village_id','a.rt','a.rw','b.address','a.title','a.nik','a.name','b.photo','a.telp as phone_number','a.base','a.id')
                    ->join('users as b','b.nik','=','a.nik')
                    ->where('pidx', $idx)
                    ->get();

        $no = 1;

       return view('pages.admin.strukturorg.rt.detailanggota', compact('data','no','kor_rt'));

    }

    public function getListDataAnggotaByKorRt(Request $request){

        // DATATABLE
        $orderBy = 'a.name';
        switch ($request->input('order.0.column')) {
            case '1':
                $orderBy = 'a.name';
                break;
        }

        $data = DB::table('org_diagram_rt as a')
                    ->select('a.id','a.idx','a.village_id','a.rt','a.rw','b.address','a.title','a.nik','a.name','b.photo','a.telp as phone_number','a.base','a.id','c.name as village','d.name as district')
                    ->join('users as b','b.nik','=','a.nik')
                    ->join('villages as c','c.id','=','a.village_id')
                    ->join('districts as d','d.id','=','a.district_id')
                    ->where('pidx', $request->idx);

            
        if($request->input('search.value')!=null){
                $data = $data->where(function($q)use($request){
                    $q->whereRaw('LOWER(a.name) like ? ',['%'.strtolower($request->input('search.value')).'%']);
                });
            }

            if ($request->input('village') != null) {
                            $data->where('a.village_id', $request->village);
            }

            if ($request->input('rt') != null) {
                            $data->where('a.rt', $request->rt);
            }


          $recordsFiltered = $data->get()->count();
          if($request->input('length')!=-1) $data = $data->skip($request->input('start'))->take($request->input('length'));
          $data = $data->orderBy($orderBy,$request->input('order.0.dir'))->get();
          
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
                'village' => $value->village,
                'district' => $value->district,
                'title' => $value->title,
                'nik' => $value->nik,
                'name' => $value->name,
                'photo' => $value->photo,
                'phone_number' => $value->phone_number,
                'base' => "KOR RT"

            ];
          }

          return response()->json([
                'draw'=>$request->input('draw'),
                'recordsTotal'=>$recordsTotal,
                'recordsFiltered'=>$recordsFiltered,
                'data'=> $results
            ]);
    }

    public function deleteAnggotaByKorgRT(){

        DB::beginTransaction();
        try {

            $id   = request()->id;
            
            DB::table('org_diagram_rt')->where('id', $id)->delete();

            #mekanisme sortir idx jika ada yang terhapus

            DB::commit();
            return ResponseFormatter::success([
                'message' => 'Berhasil hapus anggota!'
            ],200);

        } catch (\Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'message' => 'Something when wrong!',
                'error'   => $e->getMessage()
            ]);
        }

}

public function deleteKorgRT(){

    DB::beginTransaction();
    try {

        $id   = request()->id;
        
        $kor_rt =  DB::table('org_diagram_rt')->select('idx')->where('id', $id)->first();
        DB::table('org_diagram_rt')->where('pidx', $kor_rt->idx)->delete(); // delete child dari korrt
        DB::table('org_diagram_rt')->where('id', $id)->delete();

        DB::commit();
        return ResponseFormatter::success([
            'message' => 'Berhasil hapus KOR RT!'
        ],200);

    } catch (\Exception $e) {
        DB::rollback();
        return ResponseFormatter::error([
            'message' => 'Something when wrong!',
            'error'   => $e->getMessage()
        ]);
    }

}


public function indexOrgDistrict(){

    $regency = Regency::select('id','name')->where('id', 3602)->first();

    $rt      = 30;

    return view('pages.admin.strukturorg.district.index', compact('regency','rt'));

}

public function indexOrgDapil(){

    $regency = Regency::select('id','name')->where('id', 3602)->first();

    $rt      = 30;

    return view('pages.admin.strukturorg.dapil.index', compact('regency','rt'));

}

public function createOrgDistrict(){

    $regency = Regency::select('id','name')->where('id', 3602)->first();

    #creeate idx
        $cek_count_org = DB::table('org_diagram_district')->count();

        $result_new_idx = "";

        if ($cek_count_org > 0) {
            
            $count_org     = DB::table('org_diagram_district')->select('idx')->orderBy('id','desc')->first();
            $count_org     = $count_org->idx; 

            $exp        = explode(".", $count_org);
            $count_exp  = count($exp);
            
            if($count_exp == 1) {

                $result_new_idx = time().$exp[0].".1";

            }else{

                $result_exp = (int) $exp[1]+1;

                $result_new_idx  = time()."KORCAM.".$result_exp;

            }         
            
        }else{

            $result_new_idx = "KORCAM";
            
        }


    return view('pages.admin.strukturorg.district.create', compact('regency','result_new_idx'));
}

public function createOrgDapil(){

    $regency = Regency::select('id','name')->where('id', 3602)->first();

    #creeate idx
        $cek_count_org = DB::table('org_diagram_dapil')->count();

        $result_new_idx = "";

        if ($cek_count_org > 0) {
            
            $count_org     = DB::table('org_diagram_dapil')->max('idx'); 

            $exp        = explode(".", $count_org);
            $count_exp  = count($exp);
            
            if($count_exp == 1) {

                $result_new_idx = time().$exp[0].".1";

            }else{

                $result_exp = (int) $exp[1]+1;

                $result_new_idx  = time()."KORDAPIL.".$result_exp;

            }         
            
        }else{

            $result_new_idx = "KORDAPIL";
            
        }


    return view('pages.admin.strukturorg.dapil.create', compact('regency','result_new_idx'));
}

public function getDataOrgDistrict(Request $request){

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
            ->select('a.id','a.idx','a.district_id','b.address','a.title','a.nik','a.name','b.photo','a.telp as phone_number','c.name as village','d.name as district')
            ->join('users as b','b.nik','=','a.nik')
            ->join('villages as c','c.id','=','b.village_id')
            ->join('districts as d','d.id','=','c.district_id');

        
    if($request->input('search.value')!=null){
            $data = $data->where(function($q)use($request){
                $q->whereRaw('LOWER(a.name) like ? ',['%'.strtolower($request->input('search.value')).'%']);
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
      if($request->input('length')!=-1) $data = $data->skip($request->input('start'))->take($request->input('length'));
      $data = $data->orderBy('a.level_org','asc');
      $data = $data->orderBy($orderBy,$request->input('order.0.dir'))->get();
      
      $recordsTotal = $data->count();

      return response()->json([
            'draw'=>$request->input('draw'),
            'recordsTotal'=>$recordsTotal,
            'recordsFiltered'=>$recordsFiltered,
            'data'=> $data
        ]);
}

public function saveOrgDistrict(Request $request){

    DB::beginTransaction();
    try {

        #cek ketersediaan nik di tb users
        $userTable     = DB::table('users');
        // $cek_nik_user  = $userTable->where('nik', $request->nik)->count();
        $user         = $userTable->select('name','photo','nik')->where('id', $request->member)->first();

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
        ]);

        DB::commit();
        return redirect()->back()->with(['success' => 'Data telah tersimpan!']);
       
    } catch (\Exception $e) {
        DB::rollback();
        return redirect()->back()->with(['error' => 'Data gagal tersimpan!'. $e->getMessage()]);
    }
    
}

public function deleteKorCam(){

    DB::beginTransaction();
    try {

        $id   = request()->id;
        
        DB::table('org_diagram_district')->where('id', $id)->delete();


        DB::commit();
        return ResponseFormatter::success([
            'message' => 'Berhasil hapus struktur!'
        ],200);

    } catch (\Exception $e) {
        DB::rollback();
        return ResponseFormatter::error([
            'message' => 'Something when wrong!',
            'error'   => $e->getMessage()
        ]);
    }

 }

 public function deleteKorDes(){

    DB::beginTransaction();
    try {

        $id   = request()->id;
        
        DB::table('org_diagram_village')->where('id', $id)->delete();

        DB::commit();
        return ResponseFormatter::success([
            'message' => 'Berhasil hapus struktur!'
        ],200);

    } catch (\Exception $e) {
        DB::rollback();
        return ResponseFormatter::error([
            'message' => 'Something when wrong!',
            'error'   => $e->getMessage()
        ]);
    }

 }

 public function updateOrgDistrict(){

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
        $user         = $userTable->select('name','photo','phone_number','nik')->where('nik', $nik)->first();
        
        DB::table('org_diagram_district')->where('id', $id)->update([
            'nik'    => $user->nik,
            'name'   => $user->name,
            'photo'  => $user->photo ?? '',
            'telp'  => $user->phone_number,
        ]);

    
        DB::commit();
        return ResponseFormatter::success([
            'message' => 'Berhasil update struktur!'
        ],200);

    } catch (\Exception $e) {
        DB::rollback();
        return ResponseFormatter::error([
            'message' => 'Something when wrong!',
            'error'   => $e->getMessage()
        ]);
    }

}

public function updateOrgVillage(){

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
        $user         = $userTable->select('name','photo','phone_number','nik')->where('nik', $nik)->first();
        
        DB::table('org_diagram_village')->where('id', $id)->update([
            'nik'    => $user->nik,
            'name'   => $user->name,
            'photo'  => $user->photo ?? '',
            'telp'  => $user->phone_number,
        ]);

    
        DB::commit();
        return ResponseFormatter::success([
            'message' => 'Berhasil update struktur!'
        ],200);

    } catch (\Exception $e) {
        DB::rollback();
        return ResponseFormatter::error([
            'message' => 'Something when wrong!',
            'error'   => $e->getMessage()
        ]);
    }

}

public function getDataOrgDapil(Request $request){

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
            ->select('a.id','a.idx','b.address','a.title','a.nik','a.name','b.photo','a.telp as phone_number','c.name as village','d.name as district')
            ->join('users as b','b.nik','=','a.nik')
            ->join('villages as c','c.id','=','b.village_id')
            ->join('districts as d','d.id','=','c.district_id');

        
    if($request->input('search.value')!=null){
            $data = $data->where(function($q)use($request){
                $q->whereRaw('LOWER(a.name) like ? ',['%'.strtolower($request->input('search.value')).'%']);
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
      if($request->input('length')!=-1) $data = $data->skip($request->input('start'))->take($request->input('length'));
      $data = $data->orderBy('a.level_org','asc');
      $data = $data->orderBy($orderBy,$request->input('order.0.dir'))->get();
      
      $recordsTotal = $data->count();

      return response()->json([
            'draw'=>$request->input('draw'),
            'recordsTotal'=>$recordsTotal,
            'recordsFiltered'=>$recordsFiltered,
            'data'=> $data
        ]);
}

public function saveOrgDapil(Request $request){

    DB::beginTransaction();
    try {

        #cek ketersediaan nik di tb users
        $userTable     = DB::table('users');
        // $cek_nik_user  = $userTable->where('nik', $request->nik)->count();
        $user         = $userTable->select('name','photo','nik')->where('id', $request->member)->first();
        
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
        ]);

        DB::commit();
        return redirect()->back()->with(['success' => 'Data telah tersimpan!']);
       
    } catch (\Exception $e) {
        DB::rollback();
        return redirect()->back()->with(['error' => 'Data gagal tersimpan!'. $e->getMessage()]);
    }
    
}
public function deleteKorDapil(){

    DB::beginTransaction();
    try {

        $id   = request()->id;
        
        DB::table('org_diagram_dapil')->where('id', $id)->delete();


        DB::commit();
        return ResponseFormatter::success([
            'message' => 'Berhasil hapus struktur!'
        ],200);

    } catch (\Exception $e) {
        DB::rollback();
        return ResponseFormatter::error([
            'message' => 'Something when wrong!',
            'error'   => $e->getMessage()
        ]);
    }

 }

public function deleteKorPusat(){

    DB::beginTransaction();
    try {

        $id   = request()->id;
        
        DB::table('org_diagram_pusat')->where('id', $id)->delete();


        DB::commit();
        return ResponseFormatter::success([
            'message' => 'Berhasil hapus struktur!'
        ],200);

    } catch (\Exception $e) {
        DB::rollback();
        return ResponseFormatter::error([
            'message' => 'Something when wrong!',
            'error'   => $e->getMessage()
        ]);
    }

 }

 public function createOrgPusat(){

    $regency = Regency::select('id','name')->where('id', 3602)->first();

    #creeate idx
        $cek_count_org = DB::table('org_diagram_pusat')->count();

        $result_new_idx = "";

        if ($cek_count_org > 0) {
            
            $count_org     = DB::table('org_diagram_pusat')->select('idx')->orderBy('id','desc')->first(); 
            $count_org     = $count_org->idx;
            
            $exp        = explode(".", $count_org);
            $count_exp  = count($exp);
            
            if($count_exp == 1) {

                $result_new_idx = time().$exp[0].".1";

            }else{

                $result_exp = (int) $exp[1]+1;

                $result_new_idx  = time()."KORPUSAT.".$result_exp;

            }         
            
        }else{

            $result_new_idx = "KORPUSAT";
            
        }




    return view('pages.admin.strukturorg.pusat.create', compact('regency','result_new_idx'));
}

public function saveOrgPusat(Request $request){

    DB::beginTransaction();
    try {

        #cek ketersediaan nik di tb users
        $userTable     = DB::table('users');
        // $cek_nik_user  = $userTable->where('nik', $request->nik)->count();
        $user          = $userTable->select('name','photo','nik')->where('id', $request->member)->first();
        
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
        ]);

        DB::commit();
        return redirect()->back()->with(['success' => 'Data telah tersimpan!']);
       
    } catch (\Exception $e) {
        DB::rollback();
        return redirect()->back()->with(['error' => 'Data gagal tersimpan!'. $e->getMessage()]);
    }
    
}

public function getDataOrgPusat(Request $request){

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
            ->select('a.id','a.idx','b.address','a.title','a.nik','a.name','b.photo','a.telp as phone_number','c.name as village','d.name as district')
            ->join('users as b','b.nik','=','a.nik')
            ->join('villages as c','c.id','=','b.village_id')
            ->join('districts as d','d.id','=','c.district_id');

        
    if($request->input('search.value')!=null){
            $data = $data->where(function($q)use($request){
                $q->whereRaw('LOWER(a.name) like ? ',['%'.strtolower($request->input('search.value')).'%']);
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
      if($request->input('length')!=-1) $data = $data->skip($request->input('start'))->take($request->input('length'));
      $data = $data->orderBy('a.level_org','asc');
      $data = $data->orderBy($orderBy,$request->input('order.0.dir'))->get();
      
      $recordsTotal = $data->count();

      return response()->json([
            'draw'=>$request->input('draw'),
            'recordsTotal'=>$recordsTotal,
            'recordsFiltered'=>$recordsFiltered,
            'data'=> $data
        ]);
}
public function updateOrgPusat(){

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
        $user         = $userTable->select('name','photo','phone_number','nik')->where('nik', $nik)->first();
        
        DB::table('org_diagram_pusat')->where('id', $id)->update([
            'nik'    => $user->nik,
            'name'   => $user->name,
            'photo'  => $user->photo ?? '',
            'telp'  => $user->phone_number,
        ]);

    
        DB::commit();
        return ResponseFormatter::success([
            'message' => 'Berhasil update struktur!'
        ],200);

    } catch (\Exception $e) {
        DB::rollback();
        return ResponseFormatter::error([
            'message' => 'Something when wrong!',
            'error'   => $e->getMessage()
        ]);
    }

}

public function reportExcel(Request $request){

    $dapil_id    = $request->dapil_id;
    $district_id = $request->district_id;
    $village_id  = $request->village_id;
    $rt          = $request->rt;

    // dd([$dapil_id, $district_id, $village_id, $rt]);

    if ($rt == null AND $dapil_id != null AND $district_id != null AND $village_id != null ) {

       #report by desa       
       $village = DB::table('villages')->select('name')->where('id', $village_id)->first();
       return $this->excel->download(new KorDesExport($village_id), 'TIM KOORDINATOR DESA '.$village->name.'.xls');

    }elseif ($village_id == null AND $rt == null AND $district_id != null) {


       $district = DB::table('districts')->select('name')->where('id', $district_id)->first();
       return $this->excel->download(new KorCamExport($district_id), 'TIM KOORDINATOR KECAMATAN '.$district->name.'.xls');

    }else{
        
        $org = DB::table('org_diagram_dapil')->select('name','base','title')->where('dapil_id', $dapil_id)->get();
        return $org;
 
    }

}

public function updateLelelOrgAll(){

    $org = DB::table('org_diagram_village')->select('id','title')->get();

    foreach ($org as $value) {
        
        DB::table('org_diagram_village')->where('id', $value->id)
                ->update(['level_org' => GlobalProvider::generateLevelOrgUpdate($value->title) ]);
    }

    return 'ok';
    
}

public function updateTps(Request $request, $id){

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

public function updateTpsMember(Request $request, $id){


    $this->validate($request, [
        'tpsid' => 'required',
    ]);

    #get nik by id
    $org     = DB::table('org_diagram_rt')->select('nik','pidx','name')->where('id', $id)->first();
    $nik     = $org->nik;

    $koor = DB::table('org_diagram_rt as a')->select('b.tps_id')
        ->join('users as b','a.nik','=','b.nik')
        ->where('a.idx', $org->pidx)
        ->first();

    #cek apakah koordinator nya sudah memiliki TPS
    if (!$koor->tps_id) return redirect()->back()->with(['error' => "Koordinator Anggota $org->name belum memiliki data TPS!"]);
    
    #cek apakah TPS koordinator sama dengan TPS anggota nya
    if ($koor->tps_id != $request->tpsid) return redirect()->back()->with(['error' => 'Gagal, TPS anggota tidak sama dengan TPS Koordinator!']);

    #update tps where nik di tb users
    DB::table('users')->where('nik', $nik)->update(['tps_id' => $request->tpsid]);
    return redirect()->route('admin-struktur-organisasi-rt-detail-anggota', ['idx' => $org->pidx])->with(['success' => 'TPS anggota berhasil tersimpan!']);

}


}