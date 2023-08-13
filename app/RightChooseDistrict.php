<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RightChooseDistrict extends Model
{
    protected $table   = 'right_to_choose_districts';
    protected $guarded = [];

    public function getDataRightChooseDistrict($regencyId){

        $sql = DB::table('right_to_choose_districts as a')
               ->select('a.district_id','a.count_tps','a.count_vooter','a.choose','b.name')
               ->join('districts as b','a.district_id','=','b.id')
               ->where('a.regency_id', $regencyId)
               ->orderBy('b.name','asc')
               ->get();

        return $sql;

    }

    public function getTotalKalkulasiDPTDesaByKecamatan($regency_id){

        $sql = "SELECT 
                SUM(jumlah_dps_l) as jumlah_dps_l,
                SUM(jumlah_dps_p) as jumlah_dps_p,
                SUM(jumlah_dps) as jumlah_dps,
                SUM(tidak_memnenuhi_syarat_1) as tidak_memnenuhi_syarat_1,
                SUM(tidak_memnenuhi_syarat_2) as tidak_memnenuhi_syarat_2,
                SUM(tidak_memnenuhi_syarat_3) as tidak_memnenuhi_syarat_3,
                SUM(tidak_memnenuhi_syarat_4) as tidak_memnenuhi_syarat_4,
                SUM(tidak_memnenuhi_syarat_5) as tidak_memnenuhi_syarat_5,
                SUM(tidak_memnenuhi_syarat_6) as tidak_memnenuhi_syarat_6,
                SUM(tidak_memnenuhi_syarat_7) as tidak_memnenuhi_syarat_7,
                SUM(jml_tms) as jml_tms,
                sum(pemilih_aktif_p) as pemilih_aktif_p,
                sum(pemilih_aktif_l) as pemilih_aktif_l,
                SUM(pemilih_aktif) as pemilih_aktif,
                SUM(pemilih_baru) as pemilih_baru,
                SUM(jml_akhir_dps_tms_baru) as jml_akhir_dps_tms_baru,
                SUM(perbaikan_data_pemilih) as perbaikan_data_pemilih,
                SUM(pemilih_potensial_non_ktp) as pemilih_potensial_non_ktp,
                SUM(jml_dpshp_online_p) as jml_dpshp_online_p,
                sum(jml_dpshp_online_l) as jml_dpshp_online_l,
                SUM(jml_dpshp_online) as jml_dpshp_online
        from right_to_choose_districts where regency_id =  $regency_id";

		return collect(DB::select($sql))->first();
    }
	
	public function getDataDptDistrict($district_id){
		
		$sql = DB::table("right_to_choose_districts")->where('district_id', $district_id)->first();
		return $sql;
	}

}
