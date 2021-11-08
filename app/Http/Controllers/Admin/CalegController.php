<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CalegController extends Controller
{
    public function create()
    {
        return view('pages.admin.caleg.create');
    }
}
