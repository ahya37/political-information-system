<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Imports\ReplaceAddressImport;
use App\User;
use App\Models\Province;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Imports\ReplaceNikImport;
use App\TmpSpamUser;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Facades\Excel as Excels;

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
        foreach ($members as $val) {
            $data[] = $val->name;
        }
        return response()->json($members);
    }

    public function getMemberById()
    {
        $user_id = request()->data;
        $members = User::with(['village.district.regency.province', 'job', 'education'])->where('id', $user_id)->first();
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
        if ($token != null) {
            $userModel   = new  User();
            $members     = $userModel->getDataMemberByDistrict($district_id);
            return response()->json($members);
        }
    }

    public function getMemberByVillage()
    {
        $token      = request()->token;
        $villages_id = request()->villages_id;
        if ($token != null) {
            $userModel   = new  User();
            $members     = $userModel->getDataMemberByVillage($villages_id);
            return response()->json($members);
        }
    }

    public function getMember(Request $request)
    {
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


        $data = User::select('id', 'name', 'nik')->where('village_id', $village)->get();


        if ($request->has('q')) {
            $search = $request->q;
            $data = User::select('id', 'name', 'nik')->where('village_id', $village)
                ->where('name', 'LIKE', "%$search%")
                ->orWhere('nik', 'LIKE', "%$search%")
                ->get();
        }

        return response()->json($data);
    }

    public function getDataMemberBySortirRT(Request $request, $village, $rt)
    {

        $data = User::select('id', 'name', 'nik')->where('village_id', $village)->where('rt', $rt)->get();

        if ($request->has('q')) {
            $search = $request->q;
            $data = User::select('id', 'name', 'nik')->where('village_id', $village)
                ->where('rt', $rt)
                ->where('name', 'LIKE', "%$search%")
                ->orWhere('nik', 'LIKE', "%$search%")
                ->get();
        }

        return response()->json($data);
    }

    public function getDataMemberBySortirKampung(Request $request, $village, $address)
    {

        $data = User::select('id', 'name', 'nik')
            ->where('village_id', $village)
            ->where('address', 'LIKE',  "%$address%")->get();

        if ($request->has('q')) {
            $search = $request->q;
            $data = User::select('id', 'name', 'nik')->where('village_id', $village)
                ->where('address', $address)
                ->where('name', 'LIKE', "%$search%")
                ->orWhere('nik', 'LIKE', "%$search%")
                ->get();
        }

        return response()->json($data);
    }

    public function getDataMemberBySortirRTNew(Request $request, $village, $address, $rt)
    {

        $data = User::select('id', 'name', 'nik')
            ->where('village_id', $village)
            ->where('address', 'LIKE',  "%$address%")
            ->where('rt', $rt)->get();

        if ($request->has('q')) {
            $search = $request->q;
            $data = User::select('id', 'name', 'nik')
                ->where('village_id', $village)
                ->where('address', 'LIKE',  "%$address%")
                ->where('rt', $rt)
                ->where('name', 'LIKE', "%$search%")
                ->orWhere('nik', 'LIKE', "%$search%")
                ->get();
        }

        return response()->json($data);
    }

    // public function replaceAddress(Request $request){

    //     try {

    //         // $data['nik'] = $request->all();

    //         // $result = [];

    //         // foreach($data['nik'] as $val){
    //         //     $result[] = [
    //         //         'nik' => $val['NIK']
    //         //     ];

    //         //     DB::table('users')->where('nik', $val['NIK'])->update(['address' => $val['ALAMAT']]);
    //         // }

    //         // return 'update';

    //        $data = Excels::import(new ReplaceAddressImport, $request->file);

    //     //    return 'success';

    //     } catch (\Exception $th) {

    //         return $th->getMessage();

    //     }

    // }

    public function replaceAddress()
    {


        try {

            Excels::import(new ReplaceAddressImport, request()->file('file'));

            return response()->json([
                'status' => 200,
                'message' => 'Updated successfully!'
            ]);
        } catch (\Exception $th) {
            return $th->getMessage();
        }
    }
    public function replaceNik()
    {


        try {

            Excels::import(new ReplaceNikImport, request()->file('file'));

            return response()->json([
                'status' => 200,
                'message' => 'Updated successfully!'
            ]);
        } catch (\Exception $th) {
            return $th->getMessage();
        }
    }

    public function storeSpam(Request $request)
    {
		

        DB::beginTransaction();
        try {

            $sql = "select nik, COUNT(*) duplicat from users where nik is not null group by nik HAVING duplicat > 1";

            $duplicatData =  DB::select($sql);

            // get data users by nik dari duplikat
            $members = [];

            foreach ($duplicatData as $key => $value) {

                $member = "SELECT a.id, a.nik, a.name, a.created_at  FROM users as a 
                                    where a.nik = $value->nik order by a.created_at desc";
                 $memberResults = collect(\DB::select($member))->first();

                // $members[] = $memberResults;
				

                $user = User::where('id', $memberResults->id)->first();

                // #save ke tb tmp_spam_user
                TmpSpamUser::create([
                    'user_id' => $user->user_id,
                    'original_nik' => $user->nik,
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
                    'category_inactive_member_id' => 7,
                    'reason' => 'NIK Doubel',
                    'created_at' => $user->created_at,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);

                #delete di tb users sebagai anggota
                $user->delete();

                // move to store
            }


            DB::commit();
            return $members;
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }
	
	public function storeSpamNikNull(Request $request)
    {
		

        DB::beginTransaction();
        try {

            $sql = "SELECT id from users where nik is null and status = 0 and village_id is null";

            $duplicatData =  DB::select($sql);

            // get data users by nik dari duplikat
            $members = [];

            foreach ($duplicatData as $key => $value) {

                // $member = "SELECT a.id, a.nik, a.name, a.created_at  FROM users as a 
                                    // where a.nik = $value->nik order by a.created_at desc";
                 // $memberResults = collect(\DB::select($member))->first();

                // $members[] = $memberResults;
				

                $user = User::where('id', $value->id)->first();

                // #save ke tb tmp_spam_user
                TmpSpamUser::create([
                    'user_id' => $user->user_id,
                    'original_nik' => $user->nik,
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
                    'category_inactive_member_id' => 7,
                    'reason' => 'NIK Doubel',
                    'created_at' => $user->created_at,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);

                #delete di tb users sebagai anggota
                $user->delete();

                // move to store
            }


            DB::commit();
            return 'OK';
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }

    public function getEmail(Request $request){

        $user = User::select('email')->where('id', $request->id)->first();

        $result = [
            'email' => $user->email ?? ''
        ];

        return ResponseFormatter::success([
            'data' => $result, 
        ], 402);
    }
	
	
}
