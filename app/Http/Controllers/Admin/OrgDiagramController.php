<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\OrgDiagram;
use App\Models\Province;
use App\Models\Regency;
use App\Helpers\ResponseFormatter;
use DB;
use App\Http\Controllers\Auth\RegisterController;
use App\User;
use Illuminate\Support\Facades\Validator;

class OrgDiagramController extends Controller
{
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
            $nodes[] = [
                'id' => $value->idx,
                'title' => $value->title ?? $value->name,
                'name' => $value->name,
                'color' => $value->color ?? '',
                'image' => '/storage/'.$value->photo ?? '',
            ];
        }

        $results = [
            'nodes' => $nodes,
            'data' => $data,
        ];

        return response()->json($results);
    }

    public function getDataOrgDiagramDistrict(){

        $district_id = request('district');
        $orgs = DB::table('org_diagram_district')
                ->select('idx','pidx','color','title','nik','name','photo')
                ->whereNotNull('pidx')
                ->where('district_id', $district_id)
                ->get();

        $data = [];
        foreach ($orgs as $value) {
            $data[] = [$value->pidx,$value->idx];
        }

        $nodes = [];
        foreach ($orgs as $value) {
            $nodes[] = [
                'id' => $value->idx,
                'title' => $value->title ?? $value->name,
                'name' => $value->name,
                'color' => $value->color ?? '',
                'image' => '/storage/'.$value->photo ?? '',
            ];
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
            $nodes[] = [
                'id' => $value->idx,
                'title' => $value->title ?? $value->name,
                'name' => $value->name,
                'color' => $value->color ?? '',
                'image' => '/storage/'.$value->photo ?? '',
            ];
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
            $nodes[] = [
                'id' => $value->idx,
                'title' => $value->title ?? $value->name,
                'name' => $value->name,
                'color' => $value->color ?? '',
                'image' => '/storage/'.$value->photo ?? '',
            ];
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
                ->select('a.village_id','a.rt','a.rw','b.address','a.title','a.nik','a.name','b.photo','b.phone_number')
                ->join('users as b','b.nik','=','a.nik');

            
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

        $orgTable = DB::table('org_diagram_pusat');
        
        #creeate idx
        $cek_count_org = $orgTable->count();

        if ($cek_count_org == 0) {
            
            $result_new_idx = "KORPUSAT";

        }else{

            $count_org     = $orgTable->max('idx'); 
    
            $exp        = explode(".", $count_org);
            $result_exp = (int) $exp[1]+1;

            $result_new_idx  = "KORPUSAT.".$result_exp;
        }

        $org      = $orgTable->select('title','name','photo','idx')->whereNotNull('nik')->orderBy('idx','asc')->get();
        $no       = 1;


        return view('pages.admin.strukturorg.pusat.index', compact('org','no','result_new_idx'));

    }

    public function saveOrgPusat(Request $request){
        

        #CEK ketersedian NIK di tb users, harus true
        $cek_nik_user = User::where('nik', $request->nik)->count();
        if ($cek_nik_user == 0) {
            return redirect()->back()->with(['error' => 'NIK tidak terdaftar dalam sistem!']);

        }else{

            $user = User::select('name','photo')->where('nik', $request->nik)->first();

            #CEK nik di tb org_diagram_pusat, harus false
            $cek_nik_org = DB::table('org_diagram_pusat')->where('nik', $request->nik)->count();
            if ($cek_nik_org > 0) return redirect()->back()->with(['error' => 'NIK sudah terdaftar dalam struktur!']);
    
            DB::table('org_diagram_pusat')->insert([
                'idx' => $request->idx,
                'pidx' => 'KORPUSAT',
                'title' => strtoupper($request->jabatan),
                'nik' => $request->nik,
                'name' => $user->name,
                'photo' => $user->photo ?? ''
            ]);
        }

        return redirect()->back()->with(['success' => 'NIK telah tersimpan!']);

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

}
