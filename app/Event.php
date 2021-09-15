<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $guarded = [];

    public function getEvents()
    {
        $sql = "SELECT a.id, a.date, a.time, a.description, a.address,
                count(c.id) as invitation,
                COUNT(d.id) as present 
                from events as a
                left join event_details as b on a.id = b.event_id
                left join users as c on b.user_id = c.id
                left join absen_events as d on b.id = d.event_detail_id
                group by a.id, a.date, a.time, a.description, a.address";
        $result = DB::select($sql);
        return $result;
    }

    public function getEventByMember($user_id)
    {
        $sql = "SELECT  a.*, b.id as event_detail_id, c.created_at from events as a
                join event_details as b on a.id = b.event_id
                left join absen_events as c on b.id = c.event_detail_id
                where b.user_id = $user_id";

        $result = DB::select($sql);
        return $result;
    }
    
}
