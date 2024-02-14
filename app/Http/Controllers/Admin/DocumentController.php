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
	
	public function getDataDuplikatNikDpt(Request $request)
	{
		DB::beginTransaction();
		try{
			
			// get data duplikat nik DPT
			$sql = "SELECT NIK, COUNT(NIK) as duplikat  from new_dpt group by NIK HAVING COUNT(NIK) > 1 limit $request->limit";
			
			// get data id nya  berdasarkan nik yg di dapat
			$data = DB::select($sql);
			
			$results = [];  
			foreach($data as $item){
				$niks = DB::table('new_dpt')->select('NIK','ID')
						->where('NIK', $item->NIK)
						->orderBy('ID','asc')
						->get(); 
				
				// filter $niks, hilangkan id terkecil dari array
				$result_niks = [];
				foreach($niks as $value){
					$result_niks[] = [
						'NIK' => $value->NIK, 
						'ID'  => $value->ID
					];
				}
				
				$keyDelete = 0;
				
				if(array_key_exists($keyDelete, $result_niks)){
					unset($result_niks[$keyDelete]); 
				}
				 
				// $sliceKeys = array_slice(array_keys($result_niks),1,3);
				$results[] = [
					'nik'  => $item->NIK,
					'duplikat' => $item->duplikat,
					'list_nik' => $result_niks
				];
				
				// delete dpt where nik
				// foreach($result_niks as $nik){
					// DB::table('backup_new_dpt')->where('ID', $nik['ID'])->delete();
				// } 
			}  
			
			$hasil = [];
			foreach($results as $v){
				foreach($v['list_nik'] as $item){
						$hasil[] = [
							'id' => $item['ID'],
							'nik' => $item['NIK']
						];
				}
			}
			
			foreach($hasil as $h){
				// get data dpt by id 
				// simpan ke data backup
				// $old_dpt = DB::table('new_dpt')->where('ID', $h['id'])->first();
				
				$copy_data = 'insert into backup_new_dpt select * from new_dpt where ID = '.$h['id']; 
				DB::statement($copy_data); 
				
				// delete duplikat
				DB::table('new_dpt')->where('ID', $h['id'])->delete();
			}
			 
			DB::commit();
			return $hasil;
			 
		}catch(\Exception $e){
			 DB::rollback();
			 return $e->getMessage(); 
		}
		
		return $results; 
	}
	
}
