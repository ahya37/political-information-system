<?php

namespace App\Http\Controllers\API;

use App\User;
use App\Http\Controllers\Controller;
use GuzzleHttp\Psr7\Request;

class MemberController extends Controller
{
    public function memberPotentialReferal()
    {
        $memberModel = new User();
        $members    = $memberModel->getMemberReferal();
        return response()->json($members);
    }

    public function memberPotentialInput()
    {
        $memberModel = new User();
        $members    = $memberModel->getMemberInput();
        return response()->json($members);
    }
}
