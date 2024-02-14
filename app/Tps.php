<?php

namespace App;

use App\Models\Village;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Tps extends Model
{
    protected $table   = 'tps';
    protected $guarded = [];

    public function village(){

        return $this->belongsTo(Village::class);

    }

    public function getDataTpsByTpsId($tpsId){

        $sql = DB::table('tps as a')
               ->select('a.tps_number','b.name as village')
               ->join('villages as b','a.village_id','=','b.id')
               ->where('a.id', $tpsId)
               ->first();

        return $sql;
    }

    public function getTotalTpsByDistrictId($id){

        $sql = DB::table('villages as a') 
                   ->select(DB::raw('count(b.id) as tps'))
                   ->join('tps as b','a.id','=','b.village_id')
                   ->where('a.district_id', $id)
                   ->first();
        return $sql;
    }
	
	public function getRekapSaksiPerKecamatan($request)
	{
		$sql = DB::table('villages as a')
				   ->select('a.name',
						DB::raw('
							(
								SELECT COUNT(a1.id) from tps as a1 WHERE a1.village_id = a.id
							) as tps
						'),
						DB::raw('
							(
								SELECT COUNT(a2.id) from witnesses as a2 join villages as a3 on a2.village_id = a3.id
								where a2.village_id = a.id and a2.status = "saksi luar"
							) as saksi_luar
						'),
						DB::raw('
							(
								SELECT COUNT(b2.id) from witnesses as b2 join villages as b3 on b2.village_id = b3.id
								where b2.village_id = a.id and b2.status = "saksi dalam"
							) as saksi_dalam
						'),
						DB::raw("
							(
								SELECT COUNT(a4.id) from org_diagram_rt as a4 join users as a5 on  a4.nik = a5.nik
								WHERE a4.base = 'KORRT' and a4.village_id = a.id
							) as korte
						"),
						DB::raw("
							(
								SELECT COUNT(a5.id) from org_diagram_rt as a5
								join users as a6 on a5.nik = a6.nik
								where a5.base = 'ANGGOTA' and a5.village_id = a.id 
							) as anggota
						"),
						DB::raw("
							(
								SELECT COUNT(b5.id) from form_anggota_manual_kortp as b5
								join org_diagram_rt as b6 on b5.pidx_korte = b6.idx
								where b6.village_id = a.id
							) as form_manual
						"),
						DB::raw("
							(
								(
									select count(j.nik) from form_anggota_manual_kortp as j join 
									org_diagram_rt h on j.pidx_korte  = h.idx
									join users as k on h.nik = k.nik
									where h.village_id = a.id 
								) +
								(
									SELECT COUNT(a5.id) from org_diagram_rt as a5
									join users as a6 on a5.nik = a6.nik
									where a5.base = 'ANGGOTA' and a5.village_id = a.id 
								)
							) as jml_all_anggota 
						"),
						DB::raw('
							(
								SELECT COUNT(sp.id) from dpt_kpu as sp WHERE sp.village_id = a.id
							) as dpt
						')
				   )  
				   ->where('a.district_id', $request->district_id)
				   ->get();
		return $sql;
	}
	
	public function getRekapSaksiPerDesa($request)
	{
		$sql = DB::table('tps as a')
				->select('a.id','a.tps_number',
					DB::raw("
						(
							SELECT COUNT(a1.id) from witnesses as a1 WHERE a1.tps_id = a.id and a1.status = 'saksi dalam'
						) as saksi_dalam
					"),
					DB::raw("
						(
							SELECT COUNT(b1.id) from witnesses as b1 WHERE b1.tps_id = a.id and b1.status = 'saksi luar'
						) as saksi_luar
					"),
					DB::raw("
						(
							SELECT COUNT(a4.id) from org_diagram_rt as a4 
							join users as a5 on  a4.nik = a5.nik
							join tps as a6 on a5.tps_id = a6.id
							WHERE a4.base = 'KORRT' and a4.village_id = a.village_id  and a5.tps_id = a.id
						) as korte   
					")
				)
				->where('a.village_id', $request->village_id)
				->get();
				
		return $sql;
	}
	
	public function getRekapBiayaSaksiPerDesa($village_id)
	{
		$sql = DB::table('tps as a')
				->select('a.id','a.tps_number',
					DB::raw("
						(
							SELECT COUNT(a1.id) from witnesses as a1 
							WHERE a1.tps_id = a.id 
							and a1.status = 'saksi dalam'
						) as saksi_dalam
					"),
					DB::raw("
						(
							SELECT COUNT(a4.id) from org_diagram_rt as a4 
							join users as a5 on  a4.nik = a5.nik
							join tps as a6 on a5.tps_id = a6.id
							WHERE a4.base = 'KORRT' and a4.village_id = a.village_id  and a5.tps_id = a.id
						) as korte   
					")
				)
				->where('a.village_id', $village_id)
				->get();
				
		return $sql;
	}
	
	public function getRekapBiayaSaksiPerKecamatan($request)   
	{
		$sql = DB::table('villages as a')
				->select('a.id','a.name',
					DB::raw('
						(
							select count(c1.id) from tps as c1 where c1.village_id = a.id
						) as tps
					')
				)
				->where('a.district_id', $request->district_id)
				->get();
				
		return $sql;
	}
	
	public function getDataSaksiPerdesa($request)
	{
		$sql = DB::table('witnesses as a')
					->select('b.nik','b.name','b.address','b.phone_number','b.whatsapp','e.tps_number','b.rt')
					->join('users as b','a.user_id','=','b.id')
					->join('villages as c','b.village_id','=','c.id')
					->join('districts as d','c.district_id','=','d.id')
					->join('tps as e','b.tps_id','=','e.id')
					->orderBy('e.tps_number','asc')
					->where('a.village_id', $request->village_id)
					->get();
		return $sql;
	}
	
	public function getAnggotaKorteByTps($village_id, $tps_id)
	{
		$sql = DB::table('org_diagram_rt as a')
		     ->select('a.name',
				DB::raw("
					(
						select COUNT(b4.id) from org_diagram_rt as b4 
						join users as b5 on  b4.nik = b5.nik 
						WHERE b4.pidx = a.idx and b4.base = 'ANGGOTA'
					) as jml_anggota
				")
			 )
			 ->join('users as b','a.nik','=','b.nik')
			 ->join('tps as c','b.tps_id','=','c.id')
			 ->where('a.village_id', $village_id)
			 ->where('a.base','KORRT') 
			 ->where('c.id',$tps_id) 
			 ->get();
			  
		return $sql;
		
	}
	
	public function getAnggotaFormManual($village_id,$tps_id)
	{
		$sql = DB::table('org_diagram_rt as a')
		     ->select(
				DB::raw(" 
					(
						select count(j.nik) from form_anggota_manual_kortp as j 
						where j.pidx_korte = a.idx
					) as jml_anggota 
				")
			 )
			 ->join('users as b','a.nik','=','b.nik')
			 ->join('tps as c','b.tps_id','=','c.id')
			 ->where('a.village_id', $village_id)
			 ->where('a.base','KORRT')
			 ->where('c.id',$tps_id)
			 ->get(); 
			  
 		return $sql;
		
	}  
}
