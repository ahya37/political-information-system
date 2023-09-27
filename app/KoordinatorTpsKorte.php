<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class KoordinatorTpsKorte extends Model
{
	protected $table    = 'anggota_koordinator_tps_korte';
    protected  $guarded = [];

    public function stores($idx, $request, $name, $auth){
		
		$sql = DB::table('anggota_koordinator_tps_korte')->insert([
			'nik' => $request->nik,
			'pidx_korte' => $idx,
			'name' => $name,
			'created_by' => $auth
		]);
		
		return $sql;
	}
	
	public function getAnggotaKoordinatorTpsKorte($idx){
		
		
	}
	
}
