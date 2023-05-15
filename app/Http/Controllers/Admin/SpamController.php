<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SpamController extends Controller
{
    public function index(){

        return view('pages.admin.spam.index');
    }
}
