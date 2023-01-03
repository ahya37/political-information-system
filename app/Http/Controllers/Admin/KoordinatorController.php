<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\KoordinatorImport;
use DB;

class KoordinatorController extends Controller
{
    public function create(){
        return view('pages.admin.koordinator.create');
    }

    public function index(){

        return view('pages.admin.koordinator.index');
    }

    public function store(Request $request){

        DB::beginTransaction();
        try {

            Excel::import(new KoordinatorImport, request()->file('file'));

            DB::commit();
           
            return redirect()->route('admin-koordinator-create')->with(['success' => 'Berhasil upload file']);

        } catch (\Exception $e) {

            DB::rollback();
            return redirect()->route('admin-koordinator-create')->with(['error' => 'Gagal upload file']);
        }
    }
}
