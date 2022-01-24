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
        $sql = "SELECT b.id,  a.photo, a.name, c.name as village, d.name as district, e.name as regency, 
                f.name as province, sum(g.total_data) as total_data, sum(g.nominal) as  total_nominal, sum(g.point) as total_point
                from users as a
                join voucher_history as b on a.id = b.user_id
                join villages as c on a.village_id = c.id 
                join districts as d on c.district_id = d.id 
                join regencies as e on d.regency_id = e.id 
                join provinces as f on e.province_id = f.id
                join detail_voucher_history as g on b.id = g.voucher_history_id where g.type = 'Referal'
                group by b.id, a.photo, a.name, c.name, d.name, e.name , f.name";
        return DB::select($sql);
    }

    public function getMember($id)
    {
        $sql = "SELECT a.id, a.name, village_id from users as a
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

    public function getListMember()
    {
        $sql = "SELECT a.id as voucher_id, b.id as user_id, b.name, a.total_point , a.total_data , a.total_nominal from voucher_history as a
                join users as b on a.user_id = b.id
                join detail_voucher_history as c on a.id = c.voucher_history_id where c.tf is not null
                group by a.id, b.id, b.name, a.total_data , a.total_nominal , a.total_point";
                
        $result = DB::select($sql);
        return $result;
    }
}
