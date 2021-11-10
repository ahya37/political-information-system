<?php

namespace App\Http\Controllers\API;

use App\User;
use App\Http\Controllers\Controller;
use GuzzleHttp\Psr7\Request;

class MemberController extends Controller
{

    public function getSearchMember()
    {
        $data = request()->data;
        $memberModel = new User();
        $members    = $memberModel->getSearchMember($data);
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

}
