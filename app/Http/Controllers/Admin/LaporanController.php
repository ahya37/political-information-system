<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PDF;

class LaporanController extends Controller
{
    public function exportDaftarPemilih(){
        set_time_limit(120);
 
        $data = PDF::loadview('pages.admin.strukturorg.laporan.daftarPemilih')->setPaper('A4');

        
        //mendownload laporan.pdf
    	return $data->download('DaftarPemilih.pdf');
    }

    public function exportSurat(){
        set_time_limit(120);
        $data = PDF::loadview('pages.admin.strukturorg.laporan.suratPernyataan')->setPaper('A4');

        return $data->download('SuratPernyataan.pdf');
    }

    public function exportPermohonan(){
        set_time_limit(120);
        $data = PDF::loadview('pages.admin.strukturorg.laporan.suratPermohonan');

        return $data->download('SuratPermohonan.pdf');
    }
}
