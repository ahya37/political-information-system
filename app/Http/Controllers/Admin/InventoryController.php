<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\InventoryModel;

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
            $fileImage = $request->file->store('assets/inventories','public');
        }else{
            $fileImage = 'NULL';
        }

        InventoryModel::create([
            'name' => $request->name,
            'type' => $request->type,
            'price' => $request->price,
            'image' => $request->image,
            'create_by' => auth()->guard('admin')->user()->id
        ]);

        return redirect()->route('admin-inventory')->with(['success' => 'Inventory telah disimpan!']);

    }
}
