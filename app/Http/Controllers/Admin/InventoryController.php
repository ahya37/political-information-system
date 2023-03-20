<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\InventoryModel;
use DB;

class InventoryController extends Controller
{
    public function index(){

        return view('pages.admin.inventory.index');
        
    }

    public function create(){

        return view('pages.admin.inventory.create');

    }

    public function store(Request $request){

        
        $request->validate([
            'name' => 'required',
            'type' => 'nullable',
            'price' => 'nullable',
            'image' => 'nullable|mimes:jpeg,jpg,png,pdf'
        ]);

        if ($request->hasFile('image')) {

            $fileImage = $request->image->store('assets/inventories','public');

        }else{

            $fileImage = 'NULL';

        }

        InventoryModel::create([
            'name' => $request->name,
            'type' => $request->type,
            'price' => $request->price,
            'image' => $fileImage,
            'note' => $request->note,
            'create_by' => auth()->guard('admin')->user()->id
        ]);

        return redirect()->route('admin-inventory')->with(['success' => 'Inventory telah disimpan!']);

    }

    public function getListInventory(Request $request){

        $orderBy = 'name';
        switch ($request->input('order.0.column')) {
            case '1':
                $orderBy = 'name';
                break;
        }

        $data = DB::table('inv_item');

            
        if($request->input('search.value')!=null){
                $data = $data->where(function($q)use($request){
                    $q->whereRaw('LOWER(name) like ? ',['%'.strtolower($request->input('search.value')).'%']);
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
                'name' => $value->name,
                'type' => $value->type,
                'price' => $value->price,
                'image' => $value->image,
                'note' => $value->note,
            ];
        }
        return response()->json([
                'draw'=>$request->input('draw'),
                'recordsTotal'=>$recordsTotal,
                'recordsFiltered'=>$recordsFiltered,
                'data'=> $results
            ]);
        }
}
