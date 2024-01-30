<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SipController extends Controller
{
    // dashbbord all level
    public function dashboard()
    {
        return view('pages.sip.dashboard.all');
    }
	
	public function getDataGrafikLevelAll()
	{
		//data level all sekab lebak
		// get data anggota tercover se kab lebak
		
		// get data peserta kunjungan se kab lebak
		 
		// get data perolehan suara se kab lebak 
	}

    // dashboard level dapil
    public function dashboardDapil($dapilId)
    {
        
    }

    // dashbord level kecamatan
    public function dashboardDistrict($districtId)
    {
        
    }

    // dahsboard level desa
    public function dashboardVillage($villageId)
    {
        
    }
}
