<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\CatatanModel;
use DB;
use App\Helpers\ResponseFormatter;

class CatatanController extends Controller
{
    public function index(){

        return view('pages.admin.catatan.index');

    }

    public function create(){

        return view('pages.admin.catatan.create');

    }

    public function store(Request $request){

        $request->validate([
            'title' => 'required',
            'desc' => 'required',
        ]);

        CatatanModel::create([
            'title' => $request->title,
            'descr' => $request->desc,
            'create_by' => auth()->guard('admin')->user()->id
        ]);

        return redirect()->route('admin-catatan')->with(['success' => 'Catatan telah disimpan!']);

    }

    public function getListCatatan(Request $request){

        $orderBy = 'title';
        switch ($request->input('order.0.column')) {
            case '1':
                $orderBy = 'title';
                break;
        }

        $data = DB::table('catatan');

            
        if($request->input('search.value')!=null){
                $data = $data->where(function($q)use($request){
                    $q->whereRaw('LOWER(title) like ? ',['%'.strtolower($request->input('search.value')).'%']);
                });
        }

        $recordsFiltered = $data->get()->count();
        if($request->input('length')!=-1) $data = $data->skip($request->input('start'))->take($request->input('length'));
        $data = $data->orderBy($orderBy,$request->input('order.0.dir'))->get();
        
        $recordsTotal = $data->count();

        $results = [];
        $no      = 1;
        foreach ($data as $value) {
            $results[] = [
                'no' => $no++,
                'id' => $value->id,
                'title' => $value->title,
                'created_at' => date('d-m-Y', strtotime($value->created_at)),
            ];
        }
        return response()->json([
                'draw'=>$request->input('draw'),
                'recordsTotal'=>$recordsTotal,
                'recordsFiltered'=>$recordsFiltered,
                'data'=> $results
            ]);
        }

        public function delete(){

        DB::beginTransaction();
        try {

            $id    = request()->id;

            #hapus catatan
            CatatanModel::where('id', $id)->delete();

            DB::commit();
            return ResponseFormatter::success([
                   'message' => 'Berhasil hapus catatan!'
            ],200);

        } catch (\Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'message' => 'Something when wrong!',
                'error' => $e->getMessage()
            ]);
        }

    }

    public function edit($id){

        $catatan = CatatanModel::where('id', $id)->first();
        
        return view('pages.admin.catatan.edit', compact('catatan'));

    }

    public function update(Request $request, $id){

        $request->validate([
            'title' => 'required',
            'desc' => 'required',
        ]);

        $catatan = CatatanModel::where('id', $id)->first();

        $catatan->update([
            'title' => $request->title,
            'descr' => $request->desc,
        ]);

        return redirect()->route('admin-catatan')->with(['success' => 'Catatan telah disimpan!']);

    }
}
