<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class KoordinatorTpsKorte extends Model
{
    public function store($idx, $request){
		
		$sql = DB::table('anggota_koordinator_tps_korte')->insert([
			'nik' => $request->nik,
			'pidx_korte' => $idx,
			'name' => $request->name,
		]);
		
		return $sql;
	}
}
