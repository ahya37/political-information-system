<?php

namespace App\Http\Controllers\Admin;

use File;
use App\User;
use App\Models\Village;
use App\HistoryMonitoring;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class HistoryMonitoringController extends Controller
{
    public function index()
    {

        $regency = 3602;

        $historyMonitoring = HistoryMonitoring::with(['user'])->get();
        $no = 1;

        return view('pages.admin.historymonitoring.index', compact('regency', 'historyMonitoring', 'no'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'code' => 'required',
            'image' => 'nullable|mimes:jpeg,jpg,png'
        ]);

        #user_id
        $user  = User::select('id')->where('code', $request->code)->first();

        #get alamat
        $villages = Village::with(['district.regency'])->where('id', $request->village_id)->first();
        $village  = $villages->name;
        $district = $villages->district->name;
        $regency  = $villages->district->regency->name;

        $address = "DS. $village, KEC. $district, $regency";

        if ($request->hasFile('image')) {

            $fileImage = $request->image->store('assets/historymonitoring', 'public');
        } else {

            $fileImage = null;
        }

        HistoryMonitoring::create([
            'user_id' => $user->id,
            'address' => $address,
            'notes' => $request->note,
            'image' => $fileImage,
            'cby' => auth()->guard('admin')->user()->id
        ]);

        return redirect()->back()->with(['success' => 'Berhasil menambahkan monitoring!']);
    }
    public function delete()
    {

        DB::beginTransaction();
        try {

            $id    = request()->id;

            $historyMonitoring =  HistoryMonitoring::where('id', $id)->first();


            #jika ada file baru, maka update
            if ($historyMonitoring->image != null) {

                #hapus file lama
                $dir_file = storage_path('app') . '/public/' . $historyMonitoring->image;
                if (file_exists($dir_file)) {
                    File::delete($dir_file);
                }
            }

            $historyMonitoring->delete();

            DB::commit();
            return ResponseFormatter::success([
                'message' => 'Berhasil hapus galleri!'
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'message' => 'Something when wrong!',
                'error' => $e->getMessage()
            ]);
        }
    }
}
