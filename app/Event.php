<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $guarded = [];

    public function getEvents()
    {
       
		 $sql = "SELECT a.id, a.date, a.created_at as date, a.time, a.description, d.name as village, e.name as title, a.title as old_title from events as a
                 join villages as d on a.village_id = d.id
                 left join event_categories as e on a.event_category_id = e.id
				 where a.isdelete = 0
                 order by a.date desc";

        $result = DB::select($sql);
        return $result; 
    }

    public function getAddressEvent($id)
    {
        $sql = "SELECT a.id, a.date, a.time, a.description, d.name as village, c.name as district, b.name as regency from events as a
                join regencies as b on a.regency_id = b.id
                join districts as c on a.district_id = c.id 
                left join villages as d on a.village_id = d.id where a.id = $id";

        $result = collect(DB::select($sql))->first();
        return $result;
    }

    public function getEventByMember($user_id)
    {
        $sql = "SELECT a.id, a.date, a.time, a.title, a.description, d.name as village, c.name as district, b.name as regency from events as a
                left join regencies as b on a.regency_id = b.id
                left join districts as c on a.district_id = c.id 
                left join villages as d on a.village_id = d.id where a.cby = $user_id and isdelete = 0 order by a.date desc";

        $result = DB::select($sql);
        return $result;
    }

    public function getSapaAnggotaPerKecamatan($districtId, $event_category_id)
    {
        $sql = DB::table('events as a')
                ->select('c.id','c.name as desa',
                    DB::raw('(SELECT COUNT(id) from event_details where event_id = a.id) as peserta'),
                    DB::raw("
                        (
                            SELECT COUNT(a1.nik) from org_diagram_rt as a1 
                            join users as b1 on a1.nik = b1.nik 
                            where a1.village_id = a.village_id and  a1.district_id  = $districtId and a1.base = 'ANGGOTA'
                        ) as anggota_korte
                    ")
                )
                ->join('event_categories as b','a.event_category_id','=','b.id')
                ->join('villages as c','a.village_id','=','c.id')
                ->where('b.id', $event_category_id)
                ->where('a.district_id', $districtId)
                ->orderBy('c.name','asc')
                ->get();

        return $sql;
    }

    public function getKecamatanMengikutiKunjungan($regencyId, $event_category_id)
    {
        $sql = DB::table('events as a')
                ->select('b.id','b.name as kecamatan')
                ->join('districts as b','a.district_id','=','b.id')
                ->where('a.event_category_id', $event_category_id)
                ->where('b.regency_id', $regencyId)
                ->groupBy('b.name','b.id')
                ->get();

        return $sql;
    }
    
}
