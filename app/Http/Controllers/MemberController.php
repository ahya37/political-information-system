<?php

namespace App\Http\Controllers;

use App\FigureDetail;
use App\User;
use App\Models\District;
use App\Models\Province;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use DB;
use Auth;

class MemberController extends Controller
{
    public function memberProvince($id)
    {
        $province_id = decrypt($id);
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
                        <a href="'.route('admin-profile-member', encrypt($item->id)).'">
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
        return view('pages.member.member-province', compact('province'));
    }

    public function memberByReferal($user_id)
    {
        $userModel = new User(); 
        $user = $userModel->select('id','name')->where('id', $user_id)->first();
        $districtModel = new District();
        $districts = $districtModel->getDistrictByReferalMember($user_id);
        return view('pages.member.member-by-refeal', compact('user','districts','userModel'));
    }

    public function memberByInput($user_id)
    {
        $userModel = new User(); 
        $user = $userModel->select('id','name')->where('id', $user_id)->first();
        $districtModel = new District();
        $districts = $districtModel->getDistrictByInputMember($user_id);
        return view('pages.member.member-by-input', compact('user','districts','userModel'));
    }

    public function saveFigurMember(Request $request)
    {
        $figureId = $request->figureId;
        foreach ($figureId as $key => $value) {
            $figureDetail = new FigureDetail();
            $figureDetail->user_id = $request->userid;
            $figureDetail->figure_id = $value;
            $figureDetail->save();
        }

        return redirect()->back()->with(['success' => 'Anggota berpengaruh telah dibuat']);
    }
	
	public function memberPotensialByAdminInput()
	{ 
	    $user_id = Auth::user()->id;
		return view('pages.member.member-potensial', ['userId' => $user_id]);
	}
	
	public function dtMemberByAdminInput($userId)
    {
        try {
			
            $sql = "select b.id, b.photo, b.name, b.phone_number, b.whatsapp,
					(SELECT COUNT(DISTINCT(id)) from users where user_id = b.id) as total
					from users as a 
					join users as b on a.user_id = b.id
					where a.cby = $userId
					group by b.id, b.name, b.photo, b.phone_number, b.whatsapp";
					
            $data = DB::select($sql);
			
            usort($data, function($a, $b) {
                return $a->total < $b->total;
            });

           return DataTables::of($data)
                    ->addColumn('photo', function($item){
                        return '
						<img  class="rounded" width="40" src="'.asset('storage/'.$item->photo).'">
                        ';
                    })
                    ->addColumn('totalReferal', function($item){
                        return '
                         <div class="badge badge-pill badge-success">
                            '.$item->total.' 
                        </div>
                        ';
                    })
                    ->addColumn('action', function($item){
                        return '
                           <a href='.route('admin-member-by-referal',$item->id).' class="btn btn-sm text-white btn-sc-primary">Detail</a> 
                        ';
                    })
                    ->rawColumns(['photo','action','totalReferal'])
                    ->make(true);
					
        } catch (\Exception $e) {
            echo $e->getMessage();
        }

    }
}
