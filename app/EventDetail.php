<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class EventDetail extends Model
{
    protected $guarded = [];

    public function getEventDetail($event_id)
    {
        $sql = "SELECT h.title, a.id, a.number, a.name, g.name as domisili, c.created_at as log_present
                FROM users as a
                join event_details as b on a.id = b.user_id
                left join absen_events as c on b.id = c.event_detail_id
                left join villages as d on a.village_id = d.id
                left join districts as e on d.district_id = e.id 
                left join regencies as f on e.regency_id = f.id
                left join provinces as g on f.province_id  = g.id
                left join events as h on b.event_id = h.id
                where b.event_id = $event_id";
        $result = DB::select($sql);
        return $result;
    }
}
