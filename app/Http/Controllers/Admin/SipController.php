<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\OrgDiagram;

class SipController extends Controller
{
    // dashbbord all level
    public function dashboard()
    { 
		
        return view('pages.sip.dashboard.regency');
    }
	
	public function getSipRegency() 
	{
		$regency 		 = 3602;
		$orgDiagramModel = new OrgDiagram();
        $data            = $orgDiagramModel->getDataSipByRegency($regency); 
		
		$chart_sip = [];
        foreach ($data as $val) {
            $chart_sip['label'][] = $val->name;
            $chart_sip['anggota'][] = $val->anggota_tercover_kortps + $val->form_manual + $val->pelapis;  
			$chart_sip['suara'][] = $val->hasil_suara;   
			$chart_sip['urls'][] = route('admin-sip-dashboard-dapil',$val->id);
        }
		
		$chartData = array(
			"labels" => $chart_sip['label'],
			"datasets" => array(
				array(
					"label" => "Suara",
					"data" => $chart_sip['suara'],  
					"urls" => $chart_sip['urls'] 
				),
				array(
					"label" => "Anggota",
					"data" => $chart_sip['anggota'],
					"urls" => $chart_sip['urls'] 
				),
			)
		);
        return response()->json($chartData);
		
	} 
	
	// dashboard level dapil
    public function dashboardDapil($dapilId)
    {
       return view('pages.sip.dashboard.dapil');
    }
	
	public function dashboardKecamatan($districtId)
    {
       return view('pages.sip.dashboard.village'); 
    }
	
	// dashboard level dapil
    public function getSipDapil($dapilId)
    {
       $regency 		 = 3602;
		$orgDiagramModel = new OrgDiagram();
        $data            = $orgDiagramModel->getDataSipByDapil($dapilId); 
		
		$chart_sip = [];
        foreach ($data as $val) {
            $chart_sip['label'][] = $val->name;
            $chart_sip['anggota'][] = $val->anggota_tercover_kortps + $val->form_manual + $val->pelapis;  
			$chart_sip['suara'][] = $val->hasil_suara;   
			$chart_sip['urls'][] = route('admin-sip-dashboard-district',$val->id);
        }
		 
		$chartData = array(
			"labels" => $chart_sip['label'],
			"datasets" => array(
				array(
					"label" => "Suara",
					"data" => $chart_sip['suara'],  
					"urls" => $chart_sip['urls'] 
				),
				array(
					"label" => "Anggota",
					"data" => $chart_sip['anggota'],
					"urls" => $chart_sip['urls'] 
				),
			)
		);
        return response()->json($chartData);
    }
	
	public function getSipDistrict($districtId)
    {
        $regency 		 = 3602;
		$orgDiagramModel = new OrgDiagram();
        $data            = $orgDiagramModel->getDataSipByDistrict($districtId); 
		
		$chart_sip = [];
        foreach ($data as $val) {
            $chart_sip['label'][] = $val->name;
            $chart_sip['anggota'][] = $val->anggota_tercover_kortps + $val->form_manual + $val->pelapis;  
			$chart_sip['suara'][] = $val->hasil_suara;   
			$chart_sip['urls'][] = route('admin-sip-dashboard-dapil',$val->id);
        }
		 
		$chartData = array(
			"labels" => $chart_sip['label'],
			"datasets" => array(
				array(
					"label" => "Suara",
					"data" => $chart_sip['suara'],  
					"urls" => $chart_sip['urls'] 
				),
				array(
					"label" => "Anggota",
					"data" => $chart_sip['anggota'],
					"urls" => $chart_sip['urls'] 
				),
			)
		);
        return response()->json($chartData);
    }
	
	public function getDataGrafikLevelAll()
	{
		//data level all sekab lebak
		// get data anggota tercover se kab lebak
		
		// get data peserta kunjungan se kab lebak
		 
		// get data perolehan suara se kab lebak 
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
