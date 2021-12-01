<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AdminDapil extends Model
{
    protected $table = 'admin_dapils';
    protected $guarded = [];
    public $timestamps = false;

    public function getAdminDapilByUserId($user_id)
    {
        $sql = "SELECT  b.regency_id from admin_dapils as a
                join dapils as b on a.dapil_id = b.id
                where a.admin_user_id = $user_id
                group by b.regency_id";
        $result = collect(\ DB::select($sql))->first();
        return $result;
    }
}
