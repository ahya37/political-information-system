<?php

namespace App\Http\Controllers\Admin;

use App\CategoryInactiveMember;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ReasonCategorySpamMember extends Controller
{
    public function store(Request $request){

        DB::beginTransaction();
        try {

            CategoryInactiveMember::create([
                'name' => ucwords($request->name),
                'cby'  => auth()->guard('admin')->user()->id
            ]);

            DB::commit();
            return redirect()->back()->with(['success' => 'Alasan baru telah dibuat!']);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['warning' => 'Alasan baru gagal dibuat!']);

        }

    }
}
