<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\OrgDiagram;
use DB;
use App\Providers\GlobalProvider;

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
			$chart_sip['peserta_kunjungan'][] = $val->peserta_kunjungan;   
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
				)
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
        
		$orgDiagramModel = new OrgDiagram();
        $data            = $orgDiagramModel->getDataSipByDistrict($districtId); 
		
		$chart_sip = [];
        foreach ($data as $val) {
            $chart_sip['label'][] = $val->name;
            $chart_sip['anggota'][] = $val->anggota_tercover_kortps + $val->form_manual + $val->pelapis + $val->kordes + $val->korte;  
			$chart_sip['suara'][] = $val->hasil_suara;
			$chart_sip['peserta_kunjungan'][] = $val->peserta_kunjungan;
			$chart_sip['urls'][] = $val->id;
        }
		 
		$chartData = array( 
			"labels" => $chart_sip['label'],
			"datasets" => array(
				array(
					"label" => "Anggota",
					"data" => $chart_sip['anggota'],
					"urls" => $chart_sip['urls'],
					"backgroundColor" => "rgba(255, 99, 132, 0.2)",
					"borderColor" => "rgb(255, 99, 132)",
					"borderWidth" => 2, 
				), 
				array(
					"label" => "Peserta Kunjungan",
					"data" => $chart_sip['peserta_kunjungan'],
					"urls" => $chart_sip['urls'],
					"backgroundColor" => "rgba(255, 205, 86, 0.2)",
					"borderColor" => "rgb(255, 205, 86)",
					"borderWidth" => 2, 
				),
				array(
					"label" => "Suara",
					"data" => $chart_sip['suara'],
					"urls" => $chart_sip['urls'],
					"backgroundColor" => "rgba(54, 162, 235, 0.2)",
					"borderColor" => "rgb(54, 162, 235)",
					"borderWidth" => 2, 
				)
			)
		);
        return response()->json($chartData);
    }
	
	public function getRekapTpsByDistrict($districtId)
	{
		$district = DB::table('districts')->select('name')->where('id', $districtId)->first();
		$orgDiagramModel = new OrgDiagram();
        $villages         = $orgDiagramModel->getDataSipByDistrict($districtId); 
		$gf = new GlobalProvider();
		
		// get data tps  perdesa 
		// $village = DB::table('villages')->select('name')->where('id', $villageId)->first();
		// $tps = DB::table('tps')->select('tps_number','hasil_suara')->where('village_id', $villageId)->get();
		$result_villages = [];
		$no = 1;
		foreach($villages as $item){
			$all_anggota = $item->anggota_tercover_kortps + $item->pelapis + $item->form_manual + $item->kordes + $item->korte;
			$result_villages[]=[
				'no'   => $no++,
				'name' => $item->name,
				'anggota' => $gf->decimalFormat($all_anggota),
				'hasil_suara' => $gf->decimalFormat($item->hasil_suara),
				'peserta_kunjungan' => $gf->decimalFormat($item->peserta_kunjungan),
				'tps' => $item->tps,
				'persentage' => $gf->decimalFormat(($item->hasil_suara / $all_anggota)*100) 
			];
		}

		$jml_anggota = collect($villages)->sum(function($item){
			return $item->anggota_tercover_kortps + $item->pelapis + $item->form_manual + $item->kordes + $item->korte;
		});
		
		$jml_tps = collect($villages)->sum(function($item){
			return $item->tps;
		});

		$jml_hasil_suara = collect($villages)->sum(function($item){
			return $item->hasil_suara;
		});	
		
		$jml_peserta_kunjungan = collect($villages)->sum(function($item){
			return $item->peserta_kunjungan;
		});		
		 
		$result = [
			'district' => $district->name,
			'villages' => $result_villages,
			'jmlanggota' => $gf->decimalFormat($jml_anggota),
			'jmlhasilsuara' => $gf->decimalFormat($jml_hasil_suara),
			'jmlpesertakunjungan' => $gf->decimalFormat($jml_peserta_kunjungan),
			'jmltps' => $gf->decimalFormat($jml_tps),
			'persentage' => $gf->decimalFormat(($jml_hasil_suara/$jml_anggota)*100)
		];
		return response()->json($result); 
	}
}
