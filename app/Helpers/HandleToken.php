<?php 

namespace App\Helpers;

use App\TokenManagement;
use Illuminate\Support\Facades\DB;

class HandleToken
{
    public static function storeToken($token){

        return TokenManagement::create(['access_token' => $token]);

    }
    public static function isActiveToken($token){

        return DB::table('token_management')->where('access_token', $token)->update([
            'is_active' => 0,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

    }
}