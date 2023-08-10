<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RightChosseVillage extends Model
{
    protected $table = 'right_to_choose_village';
    protected $guarded = [];

    public function getDataChooseVillage($id)
    {
        $sql = "SELECT b.name , a.choose from right_to_choose_village as a 
                join villages as b on a.village_id = b.id 
                where a.village_id = $id";
        return collect(\DB::select($sql))->first();
    }

    public function getDataRightChooseVillage($districtId)
    {

        $sql = DB::table('right_to_choose_village as a')
            ->select('a.*', 'b.name')
            ->join('villages as b', 'a.village_id', '=', 'b.id')
            ->where('a.district_id', $districtId)
            ->orderBy('a.count_tps', 'asc')
            ->get();

        return $sql;
    }

    public function getTotalKalkulasiDPTDesaByKecamatan($district_id){

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
                from right_to_choose_village where district_id =  $district_id";

        return collect(DB::select($sql))->first();
    }
}
