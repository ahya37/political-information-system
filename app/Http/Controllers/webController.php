<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\ResponseFormatter;

class webController extends Controller
{
    public function qrcodeLink(){

        return view('absen-qrcode');
    }

    public function formAbsensi(){

        return view('form-absensi');

    }

    public function storeAbsen(Request $request){

        DB::beginTransaction();
        try {

            $eventid = $request->eventid;
            $name    = $request->name;
            $address = $request->address;

            // save data to tabel
            DB::table('absensi_event')->insert([
                'event_id' => $eventid,
                'name' => $name,
                'address' => $address,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            DB::commit();
            return ResponseFormatter::success([
                   'message' => 'Berhasil absen!'
            ],200); 

        } catch (\Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'message' => 'Gagal!',
                'error' => $e->getMessage()
            ]);

        }
    }
}
