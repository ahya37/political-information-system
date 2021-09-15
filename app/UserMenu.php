<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UserMenu extends Model
{
    protected $guarded = [];

    public function getUserMenu($user_id)
    {
        $result = "SELECT a.menu_id, b.name as menu, b.url, b.route from user_menus as a
                    join menus as b on a.menu_id = b.id
                    where a.user_id = $user_id order by a.menu_id ASC";
        return DB::select($result);
    }
}
