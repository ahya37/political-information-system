<?php

namespace App\Http\Controllers\API;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MemberController extends Controller
{
    public function members()
    {
             $members = User::with(['village.district.regency','reveral','create_by'])
                        ->whereNotNull('nik')
                        ->whereNotIn('level',[1])
                        ->orderBy('name','ASC')->get();
            return datatables ($members)->toJson();
    }
}
