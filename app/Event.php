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
        // $sql = DB::table('events as a')
        //         ->select('c.id','c.name as desa',
        //             DB::raw('(SELECT COUNT(id) from event_details where event_id = a.id) as peserta'),
        //             DB::raw("
        //                 (
        //                     SELECT COUNT(a1.nik) from org_diagram_rt as a1 
        //                     join users as b1 on a1.nik = b1.nik 
        //                     where a1.village_id = a.village_id and  a1.district_id  = $districtId and a1.base = 'ANGGOTA'
        //                 ) as anggota_korte
        //             ")
        //         )
        //         ->join('event_categories as b','a.event_category_id','=','b.id')
        //         ->join('villages as c','a.village_id','=','c.id')
        //         ->where('b.id', $event_category_id)
        //         ->where('a.district_id', $districtId)
        //         ->orderBy('c.name','asc')
        //         ->get();

        $sql = DB::table('events as a')
                ->select('b.name as desa',
                    DB::raw('(select count(id) from event_details where event_category_id = a.event_category_id and village_id = a.village_id) as peserta'),
                    DB::raw("(
                        SELECT COUNT(a1.nik) from org_diagram_rt as a1 
                        join users as b1 on a1.nik = b1.nik 
                        where a1.village_id = a.village_id and a1.district_id  = $districtId and a1.base = 'ANGGOTA'
                    )as anggota_korte")
                )
                ->join('villages as b','a.village_id','=','b.id')
                ->where('a.event_category_id', $event_category_id)
                ->where('a.district_id', $districtId)
                ->groupBy('b.name')
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
	
	public function getSapaAnggotDapilByRegencyId($regencyId)
	{
		$sql = DB::table('dapils')->select('id','name')->where('regency_id', $regencyId)->orderBy('name','asc')->get();
		return $sql; 
	}
	public function getSapaAnggotaDistrictByDapilId($dapilId) 
	{
		$sql = DB::table('districts as a')
				->select('a.id','a.name',
					DB::raw('(
						SELECT COUNT(DISTINCT(a1.village_id)) from events as a1 WHERE a1.event_category_id = 78 and a1.district_id = a.id 
					) as desa_dikunjungi'),
					DB::raw('(
						SELECT COUNT(a2.id) from villages as a2 WHERE a2.district_id = a.id
					) as jml_desa')
				)
				->join('dapil_areas as b','a.id','=','b.district_id')
				->where('b.dapil_id', $dapilId)
				->orderBy('a.name','asc')
				->get();
		return $sql; 
	}
	
	public function getSapaAnggotaVillageByDistrictId($district_id)
	{
		$sql = DB::table('villages as a')
				->select('a.id','a.name',
					DB::raw('(
						SELECT jml_titik  from pengajuan_sapa_anggota WHERE village_id = a.id limit 1 
					) as jml_titik'),
					DB::raw('(
						SELECT COUNT(village_id) from events WHERE village_id = a.id and event_category_id = 78 
					) as titik_terkunjungi'),
					DB::raw('(
						SELECT COUNT(id) from event_details WHERE event_category_id = 78 and village_id = a.id 
					) as peserta')
				)
				->where('district_id',$district_id)
				->orderBy('a.name','asc')
				->get();
				
		return $sql;
	}
	
	public function getSapaAnggotaVillageByDistrictIdDataTable($district_id)
	{
		$sql = DB::table('villages as a')
				->select('a.id','a.name',
					DB::raw('(
						SELECT jml_titik  from pengajuan_sapa_anggota WHERE village_id = a.id limit 1 
					) as jml_titik'),
					DB::raw('(
						SELECT COUNT(village_id) from events WHERE village_id = a.id and event_category_id = 78 
					) as titik_terkunjungi'),
					DB::raw('(
						SELECT COUNT(id) from event_details WHERE event_category_id = 78 and village_id = a.id 
					) as peserta')
				)
				->where('district_id',$district_id)
				->orderBy('a.name','asc');
				
		return $sql;
	}
	
	public function getTitikSapaAnggotaByDesa($villageId, $event_category_id)
	{
		$sql = DB::table('events as a')
				->select('a.id','a.address',
					DB::raw('(
						SELECT COUNT(*) from event_details where event_id =  a.id
					) as jml_peserta')
				)
				->where('a.village_id', $villageId)
				->where('a.event_category_id', $event_category_id)
				->get();
		return $sql;
	}
    
}
