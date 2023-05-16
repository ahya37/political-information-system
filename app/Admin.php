<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use Notifiable;
    protected $guarded = [];

    public function getAdmins()
    {
         $sql = "SELECT b.set_admin, b.photo, b.id as user_id,  b.name, b.level, b.address, b.village_id, count(a.id) as total_data
                from users as a
               left  join users as b on a.cby = b.id
                where a.village_id is not null 
                group by b.set_admin, b.photo, b.id, b.name, b.level, b.address, b.village_id
                order by count(a.id) desc";
        return DB::select($sql);
    }

    public function getAdminCaleg($user_id)
    {
         $sql = "SELECT b.photo, b.id as user_id , b.name, b.level, count(c.id) as total_data from admin_caleg as a
                join users as b on a.admin_caleg_user_id  = b.id
                left join users as c on b.id = c.cby
                where a.caleg_user_id = $user_id
                group by b.photo, b.id, b.name, b.level
                order by count(c.id) desc";
        return DB::select($sql);
    }
}
