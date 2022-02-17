<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UserMenu extends Model
{
    protected $guarded = [];

    public function getUserMenu($user_id)
    { 
        $result = "SELECT b.submenu, b.id as menu_id, b.name as menu, b.url, b.route from user_menus as a
                    join menus as b on a.menu_id = b.id
                    where a.user_id = $user_id and b.name != 'Admin' order by b.orderby ASC";
        return DB::select($result);
    }

    public function getUserMenuCaleg($user_id)
    { 
        $result = "SELECT b.submenu, b.id as menu_id, b.name as menu, b.url, b.route from user_menus as a
                    join menus as b on a.menu_id = b.id
                    where a.user_id = $user_id order by b.orderby ASC";
        return DB::select($result);
    }

    public function getUserSubmenus($menu_id)
    {
        $result = "SELECT a.id, a.name , a.url, a.route from submenus as a
                    where a.menu_id = $menu_id order by a.name ASC";

        return DB::select($result);
    }
}
