<?php

namespace App\Http\Controllers\API;

use App\User;
use App\Http\Controllers\Controller;
use GuzzleHttp\Psr7\Request;

class MemberController extends Controller
{
    public function memberReferalUp($selectKeyWord)
    {
        $memberModel = new User();
        if ($selectKeyWord == 'referal') {
            
                 $members    = $memberModel->getMemberReferal();
                 return response()->json($members);
             }
        
        if ($selectKeyWord == 'input') {
                 $members    = $memberModel->getMemberInput();
                 return response()->json($members);
             }
        
    }
}
