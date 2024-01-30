<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Maatwebsite\Excel\Excel;
use App\Exports\KeluargaSerumahExport;

class DocumentController extends Controller
{
	public $excel;
    public function __construct(Excel $excel)
    {
        $this->excel = $excel;
    }
	
    public function downloadFormatFormKortps()
    {
        $file = public_path('/docs/util/format-upload-form.xlsx');
        $headers = array(
            'Content-Type:application/vnd.ms-excel',
        );

        return response()->download($file, 'format-upload-form.xlsx', $headers);
    }
	
	public function kalkulasiKeluargaSerumahPerKecamatan(Request $request)
	{
		// get data desa by kecamtan
		$district = DB::table('districts')->select('name')->where('id', $request->district_id)->first();
		
		$desa = DB::table('villages as a')
				->select('a.name','a.id')
				->where('a.district_id', $request->district_id)
				->orderBy('a.name','ASC')
				->get();
				
		$results = [];
		$no      = 1;
		
		foreach($desa as $item){
			// get data korte by id desa nya
			$kortes = DB::table('org_diagram_rt as a')
					  ->select('a.idx',
						DB::raw('(
							select count(id) from family_group where pidx_korte = a.idx
						) as keluarga_serumah')
					  )
					  ->join('users as b','a.nik','=','b.nik')
					  ->where('a.base','KORRT')
					  ->where('a.village_id',$item->id)
					  ->get();
					  
			$jml_keluarga_serumah = collect($kortes)->sum(function($q){
				return $q->keluarga_serumah;
			});
					  
			$results[] = [
				'no'   => $no++,
				'desa' => $item->name,
				'jml_keluarga_serumah' => $jml_keluarga_serumah,
			];
		}
		
	
		$title = 'KELUARGA SERUMAH KEC.'.$district->name.'.xls'; 
        return $this->excel->download(new KeluargaSerumahExport($results), $title);
	}
	
	public function rekapKeanggotaan(Request $request)
	{
		return 'rekap data';
	}
	
	public function storeVillageCounterSapaAnggota()
	{
		// isi table data counter_sapa_anggota 
	}
	
}
