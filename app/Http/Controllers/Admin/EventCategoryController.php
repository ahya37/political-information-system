<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\EventCategory;

class EventCategoryController extends Controller
{
    public function store(Request $request){

        EventCategory::create([
            'name' => ucwords($request->name),
            'cby' => auth()->guard('admin')->user()->id
        ]);
        
        return redirect()->back()->with(['success' => 'Judul baru telah disimpan!']);

    }
}
