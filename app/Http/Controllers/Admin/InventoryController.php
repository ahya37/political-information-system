<?php

namespace App\Http\Controllers\Admin;

use File;
use App\InventoryModel;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\InventoryUser;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    public function index()
    {

        return view('pages.admin.inventory.index');
    }

    public function create()
    {

        return view('pages.admin.inventory.create');
    }

    public function store(Request $request)
    {


        $request->validate([
            'name' => 'required',
            'type' => 'nullable',
            'price' => 'nullable',
            'image' => 'nullable|mimes:jpeg,jpg,png,pdf'
        ]);

        if ($request->hasFile('image')) {

            $fileImage = $request->image->store('assets/inventories', 'public');
        } else {

            $fileImage = 'NULL';
        }

        InventoryModel::create([
            'name' => $request->name,
            'type' => $request->type,
            'price' => $request->price,
            'qty'   => $request->qty,
            'image' => $fileImage,
            'note' => $request->note,
            'create_by' => auth()->guard('admin')->user()->id
        ]);

        return redirect()->route('admin-inventory')->with(['success' => 'Inventory telah disimpan!']);
    }

    public function getListInventory(Request $request)
    {

        $orderBy = 'name';
        switch ($request->input('order.0.column')) {
            case '1':
                $orderBy = 'name';
                break;
        }

        $data = DB::table('inv_item');


        if ($request->input('search.value') != null) {
            $data = $data->where(function ($q) use ($request) {
                $q->whereRaw('LOWER(name) like ? ', ['%' . strtolower($request->input('search.value')) . '%']);
            });
        }

        $recordsFiltered = $data->get()->count();
        if ($request->input('length') != -1) $data = $data->skip($request->input('start'))->take($request->input('length'));
        $data = $data->orderBy($orderBy, $request->input('order.0.dir'))->get();

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
            'draw' => $request->input('draw'),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $results
        ]);
    }

    public function inventoryUser($id)
    {

        $inventory = InventoryModel::select('name', 'id')->where('id', $id)->first();
        $regency   = 3602;

        return view('pages.admin.inventory.users', compact('inventory', 'regency'));
    }

    public function edit($id)
    {

        $inventory = InventoryModel::where('id', $id)->first();

        return view('pages.admin.inventory.edit', compact('inventory'));
    }

    public function update(Request $request, $id)
    {


        $request->validate([
            'name' => 'required',
            'type' => 'nullable',
            'price' => 'nullable',
            'image' => 'nullable|mimes:jpeg,jpg,png,pdf'
        ]);

        $inventory = InventoryModel::where('id', $id)->first();

        if ($request->hasFile('image')) {

            $fileImage = $request->image->store('assets/inventories', 'public');

            #hapus file lama
            $dir_file = storage_path('app') . '/public/' . $inventory->image;
            if (file_exists($dir_file)) {
                File::delete($dir_file);
            }
        } else {

            $fileImage = $inventory->image;
        }

        $inventory->update([
            'name' => $request->name,
            'type' => $request->type,
            'price' => $request->price,
            'qty'   => $request->qty,
            'image' => $fileImage,
            'note' => $request->note,
            'create_by' => auth()->guard('admin')->user()->id
        ]);

        return redirect()->route('admin-inventory')->with(['success' => 'Inventory telah diubah!']);
    }

    public function delete()
    {

        DB::beginTransaction();
        try {

            $id    = request()->id;

            $data = InventoryModel::where('id', $id)->first();

            #delete file in directory
            $file = storage_path('app') . '/public/' . $data->image;
            if (file_exists($file)) {
                File::delete($file);
            }

            #delete file in db
            $data->delete();

            DB::commit();
            return ResponseFormatter::success([
                'message' => 'Berhasil hapus inventori!'
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'message' => 'Something when wrong!',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function storeInventoryUser(Request $request, $id)
    {

        DB::beginTransaction();
        try {

            InventoryUser::create([
                'inv_id' => $id,
                'user_id' => $request->member,
                'note' => $request->note,
                'cby' => auth()->guard('admin')->user()->id
            ]);

            DB::commit();
            return redirect()->back()->with(['success' => 'Pengguna telah tersimpan!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }

    public function getListInventoryUsers(Request $request)
    {

        $id = $request->id;

        $orderBy = 'name';
        switch ($request->input('order.0.column')) {
            case '1':
                $orderBy = 'name';
                break;
        }

        $data = DB::table('inventory_users as a')
            ->select('a.id', 'b.name', 'c.name as village', 'd.name as district', 'a.note')
            ->join('users as b', 'a.user_id', '=', 'b.id')
            ->join('villages as c', 'b.village_id', '=', 'c.id')
            ->join('districts as d', 'c.district_id', '=', 'd.id')
            ->where('a.inv_id', $id);


        if ($request->input('search.value') != null) {
            $data = $data->where(function ($q) use ($request) {
                $q->whereRaw('LOWER(name) like ? ', ['%' . strtolower($request->input('search.value')) . '%']);
            });
        }

        $recordsFiltered = $data->get()->count();
        if ($request->input('length') != -1) $data = $data->skip($request->input('start'))->take($request->input('length'));
        $data = $data->orderBy($orderBy, $request->input('order.0.dir'))->get();

        $recordsTotal = $data->count();

        $results = [];
        $no      = 1;
        foreach ($data as $value) {
            $results[] = [
                'no' => $no++,
                'id' => $value->id,
                'name' => $value->name,
                'village' => $value->village,
                'district' => $value->district,
                'note' => $value->note,
            ];
        }
        return response()->json([
            'draw' => $request->input('draw'),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $results
        ]);
    }

    public function deleteInventoryUser(){

        DB::beginTransaction();
        try {

            $id    = request()->id;

            InventoryUser::where('id', $id)->delete();

            DB::commit();
            return ResponseFormatter::success([
                   'message' => 'Berhasil hapus pengguna inventori!'
            ],200);

        } catch (\Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'message' => 'Something when wrong!',
                'error' => $e->getMessage()
            ]);
        }

    }
}
