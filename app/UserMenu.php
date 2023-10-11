<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserMenu extends Model
{
    protected $guarded = [];

    public function getUserMenu($user_id)
    { 
        $result = "SELECT b.submenu, b.id as menu_id, b.name as menu, b.url, b.route from user_menus as a
                    join menus as b on a.menu_id = b.id
                    where a.user_id = $user_id and b.name != 'Admin' and b.status = 1 order by b.orderby ASC";
        return DB::select($result);
    }

    public function getUserMenuCaleg($user_id)
    { 
        $result = "SELECT b.submenu, b.id as menu_id, b.name as menu, b.url, b.route from user_menus as a
                    join menus as b on a.menu_id = b.id
                    where a.user_id = $user_id and b.status =1 order by b.orderby ASC";
        return DB::select($result);
    }

    public function getUserSubmenus($menu_id)
    {
        $user_id = Auth::user()->id;
        // buat akses create anggota baru (masih hard code)
        if ($user_id == 359) {
            $result = "SELECT a.id, a.name , a.url, a.route from submenus as a
                    where a.menu_id = $menu_id  order by a.name ASC";
        }else{
            $result = "SELECT a.id, a.name , a.url, a.route from submenus as a
            where a.menu_id = $menu_id and a.status = 1 order by a.name ASC";
        }

        return DB::select($result);
    }
}
