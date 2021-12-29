<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class VoucherHistory extends Model
{
    protected $table = 'voucher_history';
    protected $guarded = [];

    public function getListVoucher()
    {
        $sql = "select b.id,  a.photo, a.name, c.name as village, d.name as district, e.name as regency, f.name as province, b.total_data , b.total_nominal , b.total_point from users as a
                join voucher_history as b on a.id = b.user_id
                join villages as c on a.village_id = c.id 
                join districts as d on c.district_id = d.id 
                join regencies as e on d.regency_id = e.id 
                join provinces as f on e.province_id = f.id";
        return DB::select($sql);
    }

    public function getMember($id)
    {
        $sql = "SELECT a.name from users as a
                join voucher_history as b on a.id = b.user_id where b.id = $id";
        $result = collect(\ DB::select($sql))->first();
        return $result;
    }

    public function getListVoucherByMember($userId)
    {
        $sql = "SELECT b.code, b.total_data, b.point, b.nominal, b.created_at from voucher_history as a  
                join detail_voucher_history as b on a.id = b.voucher_history_id
                where a.user_id = $userId";
                
        $result = DB::select($sql);
        return $result;
    }
}
