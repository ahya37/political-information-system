<?php

namespace App\Http\Controllers\Admin\Datatable;

use App\User;
use App\Models\Regency;
use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class MemberDatatableController extends Controller
{
    public function dTableMember(Request $request)
    {

        $orderBy = 'a.name';
        switch ($request->input('order.0.column')) {
            case '0':
                $orderBy = 'a.name';
                break;
            case '1':
                $orderBy = 'a.nik';
                break;
            case '2':
                $orderBy = 'villages.name';
                break;
            case '3':
                $orderBy = 'districts.name';
                break;
            case '4':
                $orderBy = 'regencies.name';
                break;
            // case '6':
            //     $orderBy = 'b.name';
            //     break;
            // case '7':
            //     $orderBy = 'c.name';
            //     break;
            case '5':
                $orderBy = 'a.created_at';
                break;
        }

        $data = DB::table('users as a')
                        ->select('a.id','a.nik','a.user_id','a.name','a.photo','regencies.name as regency','districts.name as district','villages.name as village','b.name as referal','c.name as cby','a.created_at','a.status','a.email')
                        ->join('villages','villages.id','a.village_id')
                        ->join('districts','districts.id','villages.district_id')
                        ->join('regencies','regencies.id','districts.regency_id')
                        ->leftJoin('users as b','b.id','a.user_id')
                        ->leftJoin('users as c','c.id','a.cby')
                        ->leftJoin('dapil_areas','districts.id','dapil_areas.district_id')
                        ->whereNotNull('a.village_id');

            
    if($request->input('search.value')!=null){
            $data = $data->where(function($q)use($request){
                $q->whereRaw('LOWER(a.name) like ? ',['%'.strtolower($request->input('search.value')).'%'])
                ->orWhereRaw('LOWER(regencies.name) like ? ',['%'.strtolower($request->input('search.value')).'%'])
                ->orWhereRaw('LOWER(districts.name) like ? ',['%'.strtolower($request->input('search.value')).'%'])
                ->orWhereRaw('LOWER(villages.name) like ? ',['%'.strtolower($request->input('search.value')).'%'])
                // ->orWhereRaw('LOWER(b.name) like ? ',['%'.strtolower($request->input('search.value')).'%'])
                // ->orWhereRaw('LOWER(c.name) like ? ',['%'.strtolower($request->input('search.value')).'%'])
                ->orWhereRaw('LOWER(a.nik) like ? ',['%'.strtolower($request->input('search.value')).'%'])
                ->orWhereRaw('LOWER(a.created_at) like ? ',['%'.strtolower($request->input('search.value')).'%'])
                ;
            });
        }

     if ($request->input('province') != null) {
                     $data->where('regencies.province_id', $request->province);
        }

     if ($request->input('regency') != null) {
                     $data->where('regencies.id', $request->regency);
        }

     if ($request->input('dapil') != null) {
                     $data ->where('dapil_areas.dapil_id', $request->dapil);
        }
     if ($request->input('district') != null) {
                     $data->where('districts.id', $request->district);
        }
     if ($request->input('village') != null) {
                     $data->where('villages.id', $request->village);
        }



          $recordsFiltered = $data->get()->count();
          if($request->input('length')!=-1) $data = $data->skip($request->input('start'))->take($request->input('length'));
          $data = $data->orderBy($orderBy,$request->input('order.0.dir'))->get();
          
          $recordsTotal = $data->count();

        $result = [];
        foreach($data as $val){
             $total_referal = User::where('user_id', $val->id)->whereNotNull('village_id')->count();
             $result[] = [
                 'id' => $val->id,
                 'photo' => $val->photo,
                 'nik' => $val->nik,
                 'name' => $val->name,
                 'regency' => $val->regency,
                 'district' => $val->district,
                 'village' => $val->village,
                 'referal' => $val->referal,
                 'cby' => $val->cby,
                 'created_at' => date('d-m-Y', strtotime($val->created_at)),
                 'total_referal' => $total_referal,
                 'status' => $val->status,
                 'email' => $val->email
             ];
        }


          return response()->json([
                'draw'=>$request->input('draw'),
                'recordsTotal'=>$recordsTotal,
                'recordsFiltered'=>$recordsFiltered,
                'data'=> $result
            ]);
    }


    public function dTableMemberPotentialReferal()
    {
        $userModel = new User();
        $member = $userModel->getMemberReferal();
        return DataTables::of($member)
                    ->addColumn('photo', function($item){
                        return '
                        <a href="'.route('admin-profile-member', $item->id).'">
                            <img  class="rounded" width="40" src="'.asset('storage/'.$item->photo).'">
                        </a>
                        ';
                    })
                    ->addColumn('totalReferal', function($item){
                        return '
                         <div class="badge badge-pill badge-success">
                            '.$item->total.' 
                        </div>
                        ';
                    })
                    ->addColumn('totalReferalUndirect', function($item){
                        $userModel = new User();
                        $id_user = $item->id;
                        $referal_undirect = $userModel->getReferalUnDirect($id_user);
                        $referal_undirect = $referal_undirect->total == NULL ? 0 : $referal_undirect->total;
                        return '
                         <div class="badge badge-pill badge-warning">
                            '.
                            $referal_undirect
                            .' 
                        </div>
                        ';
                    })
                    ->addColumn('contact', function($item){
                        return '
                          <div class="badge badge-pill badge-primary">
                            <i class="fa fa-phone"></i>
                            </div>
                            '.$item->phone_number.'
                            <br>
                            <div class="badge badge-pill badge-success"><i class="fa fa-whatsapp"></i>
                            </div>
                            '.$item->whatsapp.'
                        ';
                    })
                    ->addColumn('address', function($item){
                        return 'DS. '.$item->village. ',<br>KEC. '.$item->district.','.$item->regency;
                    })
                    ->addColumn('action', function($item){
                        return '
                            <div class="btn-group">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-sc-primary text-white dropdown-toggle mr-1 mb-1" type="button" data-toggle="dropdown">...</button>
                                    <div class="dropdown-menu">
                                         <a href='.route('admin-member-by-referal',$item->id).' class="dropdown-item">
                                                Detail
                                        </a> 
                                    </div>
                                </div>
                            </div>
                        ';
                    })
                    ->rawColumns(['photo','action','totalReferal','address','contact','totalReferalUndirect'])
                    ->make(true);
    }
    public function dTableMemberPotentialInput()
    {
        $userModel = new User();
        $member = $userModel->getMemberInput();
        return DataTables::of($member)
                    ->addColumn('photo', function($item){
                        return '
                        <a href="'.route('admin-profile-member', $item->id).'">
                            <img  class="rounded" width="40" src="'.asset('storage/'.$item->photo).'">
                        </a>
                        ';
                    })
                    ->addColumn('totalInput', function($item){
                        return '
                         <div class="badge badge-pill badge-success">
                            '.$item->total.' 
                        </div>
                        ';
                    })
                     ->addColumn('address', function($item){
                        return 'DS. '.$item->village. ',<br>KEC. '.$item->district.','.$item->regency;
                    })
                    ->addColumn('action', function($item){
                        return '
                            <div class="btn-group">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-sc-primary text-white dropdown-toggle mr-1 mb-1" type="button" data-toggle="dropdown">...</button>
                                    <div class="dropdown-menu">
                                         <a href='.route('admin-member-by-input',$item->id).' class="dropdown-item">
                                                Detail
                                        </a> 
                                    </div>
                                </div>
                            </div>
                        ';
                    })
                    ->addColumn('contact', function($item){
                        return '
                          <div class="badge badge-pill badge-primary">
                            <i class="fa fa-phone"></i>
                            </div>
                            '.$item->phone_number.'
                            <br>
                            <div class="badge badge-pill badge-success"><i class="fa fa-whatsapp"></i>
                            </div>
                            '.$item->whatsapp.'
                        ';
                    })
                    ->rawColumns(['photo','action','totalInput','address','contact'])
                    ->make(true);
    }

    public function dTableMemberPotentialReferalByMember($id_user)
    {
        $userModel = new User();
        $member = $userModel->getMemberReferalByMember($id_user);
        return DataTables::of($member)
                    ->addColumn('photo', function($item){
                        return '
                        <a href="'.route('member-mymember', encrypt($item->id)).'">
                            <img  class="rounded" width="40" src="'.asset('storage/'.$item->photo).'">
                        </a>
                        ';
                    })
                    ->addColumn('totalReferal', function($item){
                        return '
                         <div class="badge badge-pill badge-success">
                            '.$item->total.' 
                        </div>
                        ';
                    })
                    ->addColumn('totalReferalUndirect', function($item){
                        $userModel = new User();
                        $id_user = $item->id;
                        $referal_undirect = $userModel->getReferalUnDirect($id_user);
                        $referal_undirect = $referal_undirect->total == NULL ? 0 : $referal_undirect->total;
                        return '
                         <div class="badge badge-pill badge-warning">
                            '.
                            $referal_undirect
                            .' 
                        </div>
                        ';
                    })
                    ->addColumn('contact', function($item){
                        return '
                          <div class="badge badge-pill badge-primary">
                            <i class="fa fa-phone"></i>
                            </div>
                            '.$item->phone_number.'
                            <br>
                            <div class="badge badge-pill badge-success"><i class="fa fa-whatsapp"></i>
                            </div>
                            '.$item->whatsapp.'
                        ';
                    })
                    ->addColumn('address', function($item){
                        return $item->district.','.$item->regency;
                    })
                    ->addColumn('action', function($item){
                        return '
                            <div class="btn-group">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-sc-primary text-white dropdown-toggle mr-1 mb-1" type="button" data-toggle="dropdown">...</button>
                                    <div class="dropdown-menu">
                                         <a href='.route('by-referal',$item->id).' class="dropdown-item">
                                                Detail
                                        </a> 
                                    </div>
                                </div>
                            </div>
                        ';
                    })
                    ->rawColumns(['photo','action','totalReferal','address','contact','totalReferalUndirect'])
                    ->make(true);
    }

     public function dTableMemberPotentialInputByMember($id_user)
    {
        $userModel = new User();
        $member = $userModel->getMemberInputByMember($id_user);
        return DataTables::of($member)
                    ->addColumn('photo', function($item){
                        return '
                        <a href="'.route('member-mymember', encrypt($item->id)).'">
                            <img  class="rounded" width="40" src="'.asset('storage/'.$item->photo).'">
                        </a>
                        ';
                    })
                    ->addColumn('totalInput', function($item){
                        return '
                         <div class="badge badge-pill badge-success">
                            '.$item->total.' 
                        </div>
                        ';
                    })
                     ->addColumn('address', function($item){
                        return $item->district.','.$item->regency;
                    })
                    ->addColumn('action', function($item){
                        return '
                            <div class="btn-group">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-sc-primary text-white dropdown-toggle mr-1 mb-1" type="button" data-toggle="dropdown">...</button>
                                    <div class="dropdown-menu">
                                         <a href='.route('by-input',$item->id).' class="dropdown-item">
                                                Detail
                                        </a> 
                                    </div>
                                </div>
                            </div>
                        ';
                    })
                    ->addColumn('contact', function($item){
                        return '
                          <div class="badge badge-pill badge-primary">
                            <i class="fa fa-phone"></i>
                            </div>
                            '.$item->phone_number.'
                            <br>
                            <div class="badge badge-pill badge-success"><i class="fa fa-whatsapp"></i>
                            </div>
                            '.$item->whatsapp.'
                        ';
                    })
                    ->rawColumns(['photo','action','totalInput','address','contact'])
                    ->make(true);
    }

    public function dTableMemberCaleg(Request $request)
    {

        $orderBy = 'a.name';
        switch ($request->input('order.0.column')) {
            case '1':
                $orderBy = 'a.name';
                break;
            case '2':
                $orderBy = 'regencies.name';
                break;
            case '3':
                $orderBy = 'districts.name';
                break;
            case '4':
                $orderBy = 'villages.name';
                break;
            case '5':
                $orderBy = 'b.name';
                break;
            case '6':
                $orderBy = 'c.name';
                break;
            case '7':
                $orderBy = 'a.created_at';
                break;
        }

        $data = DB::table('users as a')
                        ->select('a.id','a.user_id','a.name','a.photo','regencies.name as regency','districts.name as district','villages.name as village','b.name as referal','c.name as cby','a.created_at','a.status','a.email')
                        ->join('villages','villages.id','a.village_id')
                        ->join('districts','districts.id','villages.district_id')
                        ->join('regencies','regencies.id','districts.regency_id')
                        ->join('users as b','b.id','a.user_id')
                        ->join('users as c','c.id','a.cby')
                        ->join('dapil_areas','districts.id','dapil_areas.district_id')
                        ->whereNotNull('a.village_id')
                        ->where('a.user_id', $request->userId)
                        ->orderBy('a.created_at','desc');

            
    if($request->input('search.value')!=null){
            $data = $data->where(function($q)use($request){
                $q->whereRaw('LOWER(a.name) like ? ',['%'.strtolower($request->input('search.value')).'%'])
                ->orWhereRaw('LOWER(regencies.name) like ? ',['%'.strtolower($request->input('search.value')).'%'])
                ->orWhereRaw('LOWER(districts.name) like ? ',['%'.strtolower($request->input('search.value')).'%'])
                ->orWhereRaw('LOWER(villages.name) like ? ',['%'.strtolower($request->input('search.value')).'%'])
                ->orWhereRaw('LOWER(b.name) like ? ',['%'.strtolower($request->input('search.value')).'%'])
                ->orWhereRaw('LOWER(c.name) like ? ',['%'.strtolower($request->input('search.value')).'%'])
                ->orWhereRaw('LOWER(a.created_at) like ? ',['%'.strtolower($request->input('search.value')).'%'])
                ;
            });
        }

     if ($request->input('province') != null) {
                     $data->where('regencies.province_id', $request->province);
        }

     if ($request->input('regency') != null) {
                     $data->where('regencies.id', $request->regency);
        }

     if ($request->input('dapil') != null) {
                     $data ->where('dapil_areas.dapil_id', $request->dapil);
        }
     if ($request->input('district') != null) {
                     $data->where('districts.id', $request->district);
        }
     if ($request->input('village') != null) {
                     $data->where('villages.id', $request->village);
        }



          $recordsFiltered = $data->get()->count();
          if($request->input('length')!=-1) $data = $data->skip($request->input('start'))->take($request->input('length'));
          $data = $data->orderBy($orderBy,$request->input('order.0.dir'))->get();
          $recordsTotal = $data->count();

        $result = [];
        foreach($data as $val){
             $total_referal = User::where('user_id', $val->id)->whereNotNull('village_id')->count();
             $result[] = [
                 'id' => $val->id,
                 'photo' => $val->photo,
                 'name' => $val->name,
                 'regency' => $val->regency,
                 'district' => $val->district,
                 'village' => $val->village,
                 'referal' => $val->referal,
                 'cby' => $val->cby,
                 'created_at' => date('d-m-Y', strtotime($val->created_at)),
                 'total_referal' => $total_referal,
                 'status' => $val->status,
                 'email' => $val->email
             ];
        }


          return response()->json([
                'draw'=>$request->input('draw'),
                'recordsTotal'=>$recordsTotal,
                'recordsFiltered'=>$recordsFiltered,
                'data'=> $result
            ]);
    }

    public function dTableSpamMember(Request $request)
    {

        $orderBy = 'a.nik';
        switch ($request->input('order.0.column')) {
            case '0':
                $orderBy = 'a.name';
                break;
            case '2':
                $orderBy = 'a.name';
                break;
            case '3':
                $orderBy = 'villages.name';
                break;
            case '4':
                $orderBy = 'districts.name';
                break;
            case '5':
                $orderBy = 'regencies.name';
                break;
            case '6':
                $orderBy = 'b.name';
                break;
            case '7':
                $orderBy = 'c.name';
                break;
            case '8':
                $orderBy = 'f.reason';
                break;
            case '9':
                $orderBy = 'f.reason';
                break;
            case '10':
                $orderBy = 'f.reason_desc';
                break;
        }

        $data = DB::table('tmp_spam_user as a')
                        ->select('a.id','a.nik','a.user_id','a.name','a.photo','regencies.name as regency','districts.name as district','villages.name as village','b.name as referal','c.name as cby','a.created_at','a.status','a.email','f.name as reason','a.reason as reason_desc')
                        ->join('villages','villages.id','a.village_id')
                        ->join('districts','districts.id','villages.district_id')
                        ->join('regencies','regencies.id','districts.regency_id')
                        ->leftJoin('users as b','b.id','a.user_id')
                        ->leftJoin('users as c','c.id','a.cby')
                        ->leftJoin('dapil_areas','districts.id','dapil_areas.district_id')
                        ->leftJoin('category_inactive_member as f','a.category_inactive_member_id','=','f.id')
                        ->whereNotNull('a.village_id');

            
    if($request->input('search.value')!=null){
            $data = $data->where(function($q)use($request){
                $q->whereRaw('LOWER(a.name) like ? ',['%'.strtolower($request->input('search.value')).'%'])
                ->orWhereRaw('LOWER(regencies.name) like ? ',['%'.strtolower($request->input('search.value')).'%'])
                ->orWhereRaw('LOWER(districts.name) like ? ',['%'.strtolower($request->input('search.value')).'%'])
                ->orWhereRaw('LOWER(villages.name) like ? ',['%'.strtolower($request->input('search.value')).'%'])
                ->orWhereRaw('LOWER(b.name) like ? ',['%'.strtolower($request->input('search.value')).'%'])
                ->orWhereRaw('LOWER(c.name) like ? ',['%'.strtolower($request->input('search.value')).'%'])
                ->orWhereRaw('LOWER(a.nik) like ? ',['%'.strtolower($request->input('search.value')).'%'])
                ->orWhereRaw('LOWER(a.created_at) like ? ',['%'.strtolower($request->input('search.value')).'%'])
                ->orWhereRaw('LOWER(f.reason) like ? ',['%'.strtolower($request->input('search.value')).'%'])
                ->orWhereRaw('LOWER(a.reason_desc) like ? ',['%'.strtolower($request->input('search.value')).'%'])
                ;
            });
        }

     if ($request->input('province') != null) {
                     $data->where('regencies.province_id', $request->province);
        }

     if ($request->input('regency') != null) {
                     $data->where('regencies.id', $request->regency);
        }

     if ($request->input('dapil') != null) {
                     $data ->where('dapil_areas.dapil_id', $request->dapil);
        }
     if ($request->input('district') != null) {
                     $data->where('districts.id', $request->district);
        }
     if ($request->input('village') != null) {
                     $data->where('villages.id', $request->village);
        }



          $recordsFiltered = $data->get()->count();
          if($request->input('length')!=-1) $data = $data->skip($request->input('start'))->take($request->input('length'));
          $data = $data->orderBy($orderBy,$request->input('order.0.dir'))->get();
          
          $recordsTotal = $data->count();

        $result = [];
        foreach($data as $val){
             $total_referal = User::where('user_id', $val->id)->whereNotNull('village_id')->count();
             $result[] = [
                 'id' => $val->id,
                 'photo' => $val->photo,
                 'nik' => $val->nik,
                 'name' => $val->name,
                 'regency' => $val->regency,
                 'district' => $val->district,
                 'village' => $val->village,
                 'referal' => $val->referal,
                 'cby' => $val->cby,
                 'created_at' => date('d-m-Y', strtotime($val->created_at)),
                 'total_referal' => $total_referal,
                 'status' => $val->status,
                 'email' => $val->email,
                 'reason' => $val->reason ?? '',
                 'reason_desc' => $val->reason_desc
             ];
        }


          return response()->json([
                'draw'=>$request->input('draw'),
                'recordsTotal'=>$recordsTotal,
                'recordsFiltered'=>$recordsFiltered,
                'data'=> $result
            ]);
    }
}
