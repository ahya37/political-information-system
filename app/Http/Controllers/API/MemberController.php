<?php

namespace App\Http\Controllers\API;

use App\Exports\MemberExport;
use App\User;
use App\Admin;
use App\Models\Province;
use App\Models\Village;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Excel;
use App\Providers\GlobalProvider;
use PDF;

class MemberController extends Controller
{
    public $excel;
    public function __construct(Excel $excel)
    {
        $this->excel = $excel;
    }

    public function getSearchMember()
    {
        $data = request()->data;
        $memberModel = new User();
        $members    = $memberModel->getSearchMember($data);
        return response()->json($members);
    }

    public function getSearchMemberByNik()
    {
        $data = request()->data;
        $user_id = request()->userId;

        $memberModel = new User();
        $members    = $memberModel->getSearchMemberByNik($data, $user_id);
        return response()->json($members);
    }

    public function getSearchMemberForCaleg()
    {
        $data = request()->data;
        $memberModel = new User();
        $members    = $memberModel->getSearchMember($data);
        $data = [];
        foreach($members as $val)
        {
            $data[] = $val->name;
        }
        return response()->json($members);
    }
    
    public function getMemberById()
    {
        $user_id = request()->data;
        $members = User::with(['village.district.regency.province','job','education'])->where('id', $user_id)->first();
        return response()->json($members);

    }

    public function getMemberByRegency()
    {
        $token      = request()->token;
        $regency_id = request()->regency_id;
        if ($token != null) {
            $userModel  = new  User();
            $members    = $userModel->getDataMemberByRegency($regency_id);
            return response()->json($members);
        }

    }

    public function getMemberByDistrict()
    {
        $token       = request()->token;
        $district_id = request()->district_id;
        if ($token != null ) {
            $userModel   = new  User();
            $members     = $userModel->getDataMemberByDistrict($district_id);
            return response()->json($members);
        }


    }

    public function getMemberByVillage()
    {
        $token      = request()->token;
        $villages_id = request()->villages_id;
        if ($token != null ) {
            $userModel   = new  User();
            $members     = $userModel->getDataMemberByVillage($villages_id);
            return response()->json($members);
        }

    }

    public function getMember(Request $request){
        $provinceModel = new Province();
        $province = $provinceModel->getDataProvince();

        $draw = $request->draw;
       

        $searchByProvince = $request->searchByProvince;

        $searchQuery = "";
        if ($searchByProvince != '') {
            $searchQuery .= $searchByProvince;
        }

        // total record tanpa filtering
        $sel = $provinceModel->getAllcountMember();
        $totalRecords = $sel->allcount;

        // total_record dengan filtering
        $sel = $provinceModel->getAllcountMember($searchQuery);
        $totalRecordwithFilter = $sel->allcount;

        // fetch
        $empQuery = $provinceModel->getMembers($searchQuery);
        $data = array();

        foreach ($empQuery as  $val) {
            $data[] = array(
                "name" => $val->name
            );
        }

        $response = array(
                "draw" => intval($draw),
                "iTotalRecords" => $totalRecords,
                "iTotalDisplayRecords" => $totalRecordwithFilter,
                "aaData" => $data
        );

        echo json_encode($response);
    }


    public function getDataMemberBySortirVillage(Request $request, $village)
    {
       
        
        $data = User::select('id','name','nik')->where('village_id', $village)->get();
    
    
        if($request->has('q')){
            $search = $request->q;
            $data = User::select('id','name','nik')->where('village_id', $village)
            ->where('name','LIKE',"%$search%")
            ->orWhere('nik','LIKE',"%$search%")
            ->get();
            
        }

        return response()->json($data);

    }

    public function getDataMemberBySortirRT(Request $request, $village, $rt)
    {
       
        $data = User::select('id','name','nik')->where('village_id', $village)->where('rt', $rt)->get();

        if($request->has('q')){
           $search = $request->q;
           $data = User::select('id','name','nik')->where('village_id', $village)
                        ->where('rt', $rt)
                        ->where('name','LIKE',"%$search%")
                        ->orWhere('nik','LIKE',"%$search%")
                        ->get();

       }

        return response()->json($data);

    }

    public function getDataMemberBySortirKampung(Request $request, $village, $address)
    {
       
        $data = User::select('id','name','nik')
                ->where('village_id', $village)
                ->where('address', 'LIKE',  "%$address%")->get();

        if($request->has('q')){
           $search = $request->q;
           $data = User::select('id','name','nik')->where('village_id', $village)
                        ->where('address', $address)
                        ->where('name','LIKE',"%$search%")
                        ->orWhere('nik','LIKE',"%$search%")
                        ->get();

       }

        return response()->json($data);

    }

    public function getDataMemberBySortirRTNew(Request $request, $village, $address, $rt)
    {
       
        $data = User::select('id','name','nik')
                ->where('village_id', $village)
                ->where('address', 'LIKE',  "%$address%")
                ->where('rt', $rt)->get();

        if($request->has('q')){
           $search = $request->q;
           $data = User::select('id','name','nik')
                        ->where('village_id', $village)
                        ->where('address', 'LIKE',  "%$address%")
                        ->where('rt', $rt)
                        ->where('name','LIKE',"%$search%")
                        ->orWhere('nik','LIKE',"%$search%")
                        ->get();

       }

        return response()->json($data);

    }

}
