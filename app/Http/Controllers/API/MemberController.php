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
}
