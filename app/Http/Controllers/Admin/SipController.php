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
		
        return view('pages.sip.dashboard.dapil');
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
            $chart_sip['suara'][] =  $val->hasil_suara;
            $chart_sip['peserta_kunjungan'][] =  $val->peserta_kunjungan;
            // $chart_member_target['persentage'][] = $val->realisasi_member;
        }
        $data = [ 
            'label' => $chart_sip['label'], 
            // 'persentage' =>  $chart_sip['persentage'],
			'anggota' => $chart_sip['anggota'], 
            'suara' =>  $chart_sip['suara'], 
            'peserta_kunjungan' =>  $chart_sip['peserta_kunjungan']   
        ];
        return response()->json($data);
		
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
