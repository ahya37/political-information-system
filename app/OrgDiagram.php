<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Expr\FuncCall;

class OrgDiagram extends Model
{
    protected $table   = 'org_diagrams';
    protected $guarded = [];
	
	public function getKorcamByDapil($dapil){
		
		$sql = "SELECT a.nik as NIK , b.name as NAMA, 
						CASE when b.gender = '0' then 'L' else 'P' end as JENIS_KELAMIN, 
						DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), b.date_berth)), '%Y') + 0 as 'USIA', a.title as 'JABATAN',
						d.name as KECAMATAN, d.id as district_id
						from org_diagram_district as a
						join users as b on a.nik = b.nik
						join villages as c on b.village_id = c.id
						join districts as d on c.district_id = d.id
						join dapil_areas as e on e.district_id = d.id
						join dapils as f on e.dapil_id = f.id
						where f.id = $dapil
						order by d.name asc, c.name asc, a.level_org asc";
						
		return DB::select($sql);
	}
	
	public function getKordesByKecamatan($district_id){
		
		$sql = "SELECT b.nik as NIK , b.name as NAMA, 
							CASE when b.gender = '0' then 'L' else 'P' end as JENIS_KELAMIN, 
							DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), b.date_berth)), '%Y') + 0 as 'USIA', a.title as 'JABATAN',
							c.name as DESA, c.id as village_id, b.address,
							a.base, a.telp
							from org_diagram_village as a
							join users as b on a.nik = b.nik
							join villages as c on b.village_id = c.id
							join districts as d on c.district_id = d.id
							where a.district_id = $district_id
							order by d.name asc, c.name asc, a.level_org asc";
							
		return DB::select($sql);
	}
	

	
	
	public function getKecamatanByDapil($dapil_id){
		
		$sql = "SELECT a.id, a.name from districts as a
				join dapil_areas as b on b.district_id = a.id
				join org_diagram_district as c on c.district_id = a.id
				where b.dapil_id = $dapil_id group by a.id, a.name order by a.name asc";
				
		return DB::select($sql);
	}
	
	public function getKorcamByKecamatan($district_id){
		 
		$sql = "SELECT a.nik as NIK , b.name as NAMA, 
						CASE when b.gender = '0' then 'L' else 'P' end as JENIS_KELAMIN, 
						DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), b.date_berth)), '%Y') + 0 as 'USIA', a.title as 'JABATAN',
						d.name as KECAMATAN, d.id as district_id
						from org_diagram_district as a
						join users as b on a.nik = b.nik
						join villages as c on b.village_id = c.id
						join districts as d on c.district_id = d.id
						where d.id = $district_id
						order by d.name asc, c.name asc, a.level_org asc";
						
		return DB::select($sql);
	}
	
	public function getKorcamByKecamatanForTitle($district_id){
		
		$sql = "SELECT b.nik as NIK , b.name as NAMA,
				CASE when b.gender = '0' then 'L' else 'P' end as 'JENIS KELAMIN', 
				DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), b.date_berth)), '%Y') + 0 as 'USIA', a.title as 'JABATAN', c.name as DESA, d.name as KECAMATAN, d.id
				from org_diagram_district  as a
				join users as b on a.nik = b.nik
				join villages as c on b.village_id = c.id
				join districts as d on c.district_id = d.id
				where a.district_id = $district_id
				order by a.level_org asc";
						
		return DB::select($sql);
		
	}
	
	public function getDesaByKecamatanKoordinator($district_id){ 
		
		$sql = "SELECT a.id, a.name from villages  as a
				join org_diagram_village as c on c.village_id  = a.id
				where c.district_id  = $district_id  group by a.id, a.name order by a.name asc";
						
		return DB::select($sql);
	}
	
	public function getKordesByDesa($village_id){
		
		$sql = "SELECT b.nik as NIK , b.name as NAMA, 
							CASE when b.gender = '0' then 'L' else 'P' end as JENIS_KELAMIN, 
							DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), b.date_berth)), '%Y') + 0 as 'USIA', a.title as 'JABATAN',
							c.name as DESA, c.id as village_id,
							a.base
							from org_diagram_village as a
							join users as b on a.nik = b.nik
							join villages as c on b.village_id = c.id
							where a.village_id = $village_id
							order by c.name asc, a.level_org asc";
							
		return DB::select($sql);
	}
	
	public function getDataKordesByDapil($dapil_id){
		
		$sql = "SELECT b.nik as NIK , b.name as NAMA, 
				CASE when b.gender = '0' then 'L' else 'P' end as JENIS_KELAMIN, 
				DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), b.date_berth)), '%Y') + 0 as 'USIA', a.title as 'JABATAN',
				d.name as KECAMATAN, d.id as district_id
				from org_diagram_village as a
				join users as b on a.nik = b.nik
				join villages as c on b.village_id = c.id
				join districts as d on c.district_id = d.id
				join dapil_areas as e on e.district_id = d.id
				join dapils as f on e.dapil_id = f.id
				where f.id = $dapil_id
				order by d.name asc, c.name asc, a.level_org asc";
		return $sql = DB::select($sql);
	}
	
	public function getDataKorteByDapil($dapil_id){
		
		$sql = "SELECT b.nik as NIK , b.name as NAMA, 
				CASE when b.gender = '0' then 'L' else 'P' end as JENIS_KELAMIN, 
				DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), b.date_berth)), '%Y') + 0 as 'USIA', a.title as 'JABATAN',
				d.name as KECAMATAN, d.id as district_id
				from org_diagram_rt  as a
				join users as b on a.nik = b.nik
				join villages as c on b.village_id = c.id
				join districts as d on c.district_id = d.id
				join dapil_areas as e on e.district_id = d.id
				join dapils as f on e.dapil_id = f.id
				where f.id = $dapil_id and a.base ='KORRT'
				order by d.name asc, c.name asc";
				
		return DB::select($sql);
	}
	
	public function getDataKorteByDistrict($district_id){
		
		$sql = "SELECT b.nik as NIK , b.name as NAMA, 
				CASE when b.gender = '0' then 'L' else 'P' end as JENIS_KELAMIN, 
				DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), b.date_berth)), '%Y') + 0 as 'USIA', a.title as 'JABATAN',
				d.name as KECAMATAN, d.id as district_id
				from org_diagram_rt  as a
				join users as b on a.nik = b.nik
				join villages as c on b.village_id = c.id
				join districts as d on c.district_id = d.id
				where a.district_id  = $district_id and a.base ='KORRT'
				order by d.name asc, c.name asc";
				
		return DB::select($sql);
	}
	
	public function getDataKorteByDesa($village_id){
		
		$sql = "SELECT b.nik as NIK , b.name as NAMA, 
				CASE when b.gender = '0' then 'L' else 'P' end as JENIS_KELAMIN, 
				DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), b.date_berth)), '%Y') + 0 as 'USIA', a.title as 'JABATAN',
				d.name as KECAMATAN, c.name as DESA
				from org_diagram_rt  as a
				join users as b on a.nik = b.nik
				join villages as c on b.village_id = c.id
				join districts as d on c.district_id = d.id
				where a.village_id = $village_id and a.base ='KORRT'
				order by d.name asc, c.name asc";
				
		return DB::select($sql);
	}
	
	public function getJkAllKorcamByDapil($dapil_id){ 
	
		$sql = "SELECT  CASE when b.gender = '0' then 'L' else 'P' end as jenis_kelamin, 
				count(a.id) as total_jk_all_korcam from org_diagram_district as a
				join users as b on a.nik = b.nik 
				join dapil_areas as c on a.district_id = c.district_id
				where c.dapil_id = $dapil_id group by b.gender";
		
		return DB::select($sql);
	}
	public function getJkAllKorcamAll(){ 
	
		$sql = "SELECT  CASE when b.gender = '0' then 'L' else 'P' end as jenis_kelamin, 
				count(a.id) as total_jk_all_korcam from org_diagram_district as a
				join users as b on a.nik = b.nik 
				group by b.gender";
		
		return DB::select($sql);
	}
	
	public function getJkAllKordesByDapil($dapil_id){ 
	
		$sql = "SELECT  CASE when b.gender = '0' then 'L' else 'P' end as jenis_kelamin, 
				count(a.id) as total_jk_all_kordes from org_diagram_village as a
				join users as b on a.nik = b.nik 
				join dapil_areas as c on a.district_id = c.district_id
				where c.dapil_id = $dapil_id group by b.gender";
		
		return DB::select($sql);
	}
	
	public function getJkAllKordesAll(){ 
	
		$sql = "SELECT  CASE when b.gender = '0' then 'L' else 'P' end as jenis_kelamin, 
				count(a.id) as total_jk_all_kordes from org_diagram_village as a
				join users as b on a.nik = b.nik 
				group by b.gender";
		
		return DB::select($sql);
	}
	
	public function getJkAllKorteByDapil($dapil_id){ 
	
		$sql = "SELECT CASE when b.gender = '0' then 'L' else 'P' end as jenis_kelamin, 
				count(a.id) as total_jk_all_korte from org_diagram_rt as a
				join users as b on a.nik = b.nik 
				join dapil_areas as c on a.district_id = c.district_id
				where c.dapil_id = $dapil_id and a.base ='KORRT' group by b.gender";
		
		return DB::select($sql);
	}
	
	public function getJkAllKorteAll(){ 
	
		$sql = "SELECT CASE when b.gender = '0' then 'L' else 'P' end as jenis_kelamin, 
				count(a.id) as total_jk_all_korte from org_diagram_rt as a
				join users as b on a.nik = b.nik where a.base = 'KORRT' 
				group by b.gender";
		
		return DB::select($sql);
	}
	
	public function getJkAllKorteByKecamatan($district_id){ 
	
		$sql = "SELECT CASE when b.gender = '0' then 'L' else 'P' end as jenis_kelamin, 
				count(a.id) as total_jk_all from org_diagram_rt as a
				join users as b on a.nik = b.nik 
				where a.district_id = $district_id and a.base ='KORRT' group by b.gender";
		
		return DB::select($sql);
	}
	
	public function getJkAllKorteByDesa($village_id){ 
	
		$sql = "SELECT CASE when b.gender = '0' then 'L' else 'P' end as jenis_kelamin, 
				count(a.id) as total_jk_all from org_diagram_rt as a
				join users as b on a.nik = b.nik 
				where a.village_id = $village_id and a.base ='KORRT' group by b.gender";
		
		return DB::select($sql);
	}
	
	public function getUsiaKorcamPerDapil($dapil_id){
		
		$sql = "SELECT DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), b.date_berth)), '%Y') + 0 as usia
				from org_diagram_district as a
				join users as b on a.nik = b.nik
				join villages as c on b.village_id = c.id
				join districts as d on c.district_id = d.id
				join dapil_areas as e on e.district_id = d.id
				where e.dapil_id = $dapil_id";
		
		return DB::select($sql);
		
	}
	
	public function getDataUsiaKorcamByDapil($dapil_id){
		
		$sql = "SELECT DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), b.date_berth)), '%Y') + 0 as usia
				from org_diagram_district as a
				join users as b on a.nik = b.nik
				join villages as c on b.village_id = c.id
				join districts as d on c.district_id = d.id
				join dapil_areas as e on e.district_id = d.id
				where e.dapil_id = $dapil_id";
		return DB::select($sql);
	}
	
	public function getDataUsiaKorcamAll(){
		
		$sql = "SELECT DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), b.date_berth)), '%Y') + 0 as usia
				from org_diagram_district as a
				join users as b on a.nik = b.nik";
		return DB::select($sql);
	}
	
	public function getDataUsiaKordesByKecamatan($district_id){
		
		$sql = "SELECT DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), b.date_berth)), '%Y') + 0 as usia
				from org_diagram_village as a
				join users as b on a.nik = b.nik
				where a.district_id = $district_id";
		return DB::select($sql);
	}
	
	public function getDataUsiaKordesByDapil($dapil_id){
		
		$sql = "SELECT DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), b.date_berth)), '%Y') + 0 as usia
				from org_diagram_village as a
				join users as b on a.nik = b.nik
				join villages as c on b.village_id = c.id
				join districts as d on c.district_id = d.id
				join dapil_areas as e on e.district_id = d.id
				where e.dapil_id = $dapil_id";
		return DB::select($sql);
	}
	
	public function getDataUsiaKordesAll(){
		
		$sql = "SELECT DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), b.date_berth)), '%Y') + 0 as usia
				from org_diagram_village as a
				join users as b on a.nik = b.nik";
		return DB::select($sql);
	}
	
	public function getDataUsiaKorteByDapil($dapil_id){
		 
		$sql = "SELECT DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), b.date_berth)), '%Y') + 0 as usia
				from org_diagram_rt as a
				join users as b on a.nik = b.nik
				join villages as c on b.village_id = c.id
				join districts as d on c.district_id = d.id
				join dapil_areas as e on e.district_id = d.id
				where e.dapil_id = $dapil_id and a.base = 'KORRT'";
		return DB::select($sql);
	}
	
	public function getDataUsiaKorteAll(){
		 
		$sql = "SELECT DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), b.date_berth)), '%Y') + 0 as usia
				from org_diagram_rt as a
				join users as b on a.nik = b.nik
				where  a.base = 'KORRT'";
		return DB::select($sql);
	}
	
	public function getDataUsiaKorteByKecamtan($district_id){
		 
		$sql = "SELECT DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), b.date_berth)), '%Y') + 0 as usia
				from org_diagram_rt as a
				join users as b on a.nik = b.nik
				where a.district_id = $district_id and a.base = 'KORRT'";
		return DB::select($sql);
	}
	
	public function getDataUsiaKorteByDesa($village_id){
		 
		$sql = "SELECT DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), b.date_berth)), '%Y') + 0 as usia
				from org_diagram_rt as a
				join users as b on a.nik = b.nik
				where a.village_id = $village_id and a.base = 'KORRT'";
		return DB::select($sql);
	}
	
	
	public function getDapilById($id){ 
		
		$sql = "SELECT a.id, a.name  from dapils as a where a.id = $id";
		return collect(DB::select($sql))->first();
	}
	
	public function getJkAllKordesByKecamatan($district_id){ 
	
		$sql = "SELECT  CASE when b.gender = '0' then 'L' else 'P' end as jenis_kelamin, 
				count(a.id) as total_jk_all from org_diagram_village as a
				join users as b on a.nik = b.nik 
				where a.district_id = $district_id group by b.gender";
		
		return DB::select($sql);
	}
	
	
	public function getDataKordesByDistrict(){
		
	}
	
	public function getDataKorteDapil(){
		
	}

	public function getDataDaftarTimByKecamatan($districtId)
	{

		$sql = "SELECT a.id, a.name, a.target_persentage ,
			(select COUNT(org_diagram_village.id)  from org_diagram_village where title = 'KETUA' and village_id = a.id) as ketua,
			(select COUNT(org_diagram_village.id)  from org_diagram_village where title = 'SEKRETARIS' and village_id = a.id) as sekretaris,
			(select COUNT(org_diagram_village.id)  from org_diagram_village where title = 'BENDAHARA' and village_id = a.id) as bendahara,
			(SELECT COUNT(dpt_kpu.id) from dpt_kpu WHERE village_id = a.id ) as dpt,
			(SELECT COUNT(users.id) from users join villages on users.village_id = villages.id  WHERE villages.id = a.id) as anggota,
			((SELECT COUNT(users.id) from users join villages on users.village_id = villages.id WHERE villages.id = a.id )/25)as target_korte,
			(SELECT COUNT(org_diagram_rt.id) from org_diagram_rt join users on org_diagram_rt.nik = users.nik WHERE org_diagram_rt.base = 'KORRT' and org_diagram_rt.village_id = a.id ) as korte_terisi,
			(SELECT COUNT(witnesses.id) from witnesses WHERE village_id = a.id ) as saksi,
			(SELECT COUNT(tps.id) from tps WHERE tps.village_id = a.id) tps,
			(SELECT count(da4.id) from org_diagram_rt as da4  join users as da5 on da4.nik = da5.nik where da4.base = 'ANGGOTA' and da4.village_id = a.id) as anggota_tercover_kortps,
			(
				-- (SELECT COUNT(s1.id) from users as s1 join villages as s2 on s1.village_id = s2.id WHERE s1.village_id = a.id) - 
				-- (SELECT count(da4.nik) from org_diagram_rt as da4  join users as da5 on da4.nik = da5.nik where da4.base = 'ANGGOTA' and da4.village_id = a.id) - 
				-- (SELECT COUNT(a2.nik) from org_diagram_village as a2 join users as a3 on a2.nik = a3.nik WHERE a2.village_id = a.id) -
				-- (SELECT COUNT(a4.nik) from org_diagram_district as a4 join users as a5 on a4.nik = a5.nik WHERE a5.village_id = a.id) -
				-- (SELECT count(da4.nik) from org_diagram_rt as da4  join users as da5 on da4.nik = da5.nik where da4.base = 'KORRT' and da4.village_id = a.id)
				SELECT COUNT(DISTINCT(a1.nik))
				from users as a1
				join villages as b1 on a1.village_id = b1.id 
				left join org_diagram_rt as c1 on a1.nik = c1.nik
				left join org_diagram_village as d1 on a1.nik = d1.nik
				left join org_diagram_district  as e1 on a1.nik = e1.nik
				where b1.id  = a.id and c1.base is null and d1.nik is null and e1.nik is null and c1.nik is null
			) as belum_ada_korte,
			(
				select count(j.nik) from form_anggota_manual_kortp as j join 
				org_diagram_rt h on j.pidx_korte  = h.idx
				join users as k on h.nik = k.nik
				where h.village_id = a.id 
			) as form_manual,
			(
				select count(k.nik) from form_vivi as k join 
				org_diagram_rt g on k.pidx_korte  = g.idx
				join users as i on g.nik = i.nik
				where g.village_id = a.id 
			) as form_vivi,
			(
				select count(o.nik) from form_anggota_manual_kortp_vivi  as o join 
				org_diagram_rt p on o.pidx_korte  = p.idx
				join users as z on p.nik = z.nik
				where p.village_id = a.id 
			) as form_manual_vivi 
			from villages as a
			WHERE a.district_id = $districtId order by (SELECT COUNT(users.id) from users join villages on users.village_id = villages.id  WHERE villages.id = a.id) desc";
         
        return DB::select($sql); 
	}


	public function getDataDaftarTimByVillage($village_id){

		#get data desa by kecamatan
        $sql = "SELECT a.id, a.name, a.target_persentage ,
                -- (select COUNT(id)  from org_diagram_village where title = 'KETUA' and village_id = a.id) as ketua,
                -- (select COUNT(id)  from org_diagram_village where title = 'SEKRETARIS' and village_id = a.id) as sekretaris,
                -- (select COUNT(id)  from org_diagram_village where title = 'BENDAHARA' and village_id = a.id) as bendahara,
                (SELECT COUNT(id) from dpt_kpu WHERE village_id = a.id ) as dpt,
                -- (SELECT COUNT(id) from users WHERE village_id = a.id ) as anggota,
                -- ((SELECT COUNT(id) from users WHERE village_id = a.id )/25)as target_korte,
                -- (SELECT COUNT(id) from org_diagram_rt WHERE base = 'KORRT' and village_id = a.id and nik is not null ) as korte_terisi,
                -- ((SELECT COUNT(id) from org_diagram_rt WHERE base = 'KORRT' and village_id = a.id and nik is not null )*25) anggota_tercover,
                -- ((CEIL ((SELECT COUNT(id) from users WHERE village_id = a.id )/25))-(SELECT COUNT(id) from org_diagram_rt WHERE base = 'KORRT' and village_id = a.id and nik is not null )) as kurang_korte,
                -- ((SELECT COUNT(id) from users WHERE village_id = a.id )-((SELECT COUNT(id) from org_diagram_rt WHERE base = 'KORRT' and village_id = a.id and nik is not null )*25)) as belum_ada_korte,
                ((SELECT COUNT(id) from dpt_kpu WHERE village_id = a.id )*(SELECT target_persentage from villages where id = a.id)/100) as target
				-- (SELECT COUNT(id) from witnesses WHERE village_id = a.id ) as saksi,
				-- (SELECT COUNT(*) from tps WHERE tps.village_id = a.id) tps
                from villages as a
                WHERE a.id = $village_id";
        
        return collect(DB::select($sql))->first(); 
	}

	public function getDataDaftarTimByRegency($regencyId){

		$sql = "SELECT a.id, a.name,
					(SELECT COUNT(odd.id) from org_diagram_district as odd join dapil_areas as da on odd.district_id = da.district_id 
						where da.dapil_id = a.id and odd.title = 'KETUA') as k,
					(SELECT COUNT(odd.id) from org_diagram_district as odd join dapil_areas as da on odd.district_id = da.district_id 
						where da.dapil_id = a.id and odd.title = 'SEKRETARIS') as s,
					(SELECT COUNT(odd.id) from org_diagram_district as odd join dapil_areas as da on odd.district_id = da.district_id 
						where da.dapil_id = a.id and odd.title = 'BENDAHARA') as b,
					(
						SELECT COUNT(adpt.id) from dpt_kpu as adpt
						join districts as bdpt on adpt.district_id = bdpt.id 
						join dapil_areas as cdpt on bdpt.id = cdpt.district_id 
						WHERE cdpt.dapil_id = a.id
					) as dpt,
					(
						SELECT COUNT(a1.id) from users as a1 join villages as a2 on a1.village_id = a2.id
						join dapil_areas as a4 on a2.district_id = a4.district_id where a4.dapil_id = a.id
					) as anggota,
					(
						ceil(
							(
							SELECT COUNT(a1.id) from users as a1 join villages as a2 on a1.village_id = a2.id join districts as a3 on a2.district_id = a3.id
							join dapil_areas as a4 on a3.id = a4.district_id where a4.dapil_id = a.id
							)/25
						)
					) target_korte,
					(SELECT COUNT(odr.id)
						from org_diagram_rt as odr
						join dapil_areas da2 on odr.district_id = da2.district_id
						join users as da3 on odr.nik = da3.nik
						WHERE odr.base = 'KORRT' and da2.dapil_id = a.id
					) as korte_terisi,
					(
						SELECT COUNT(DISTINCT(a1.nik))
							from users as a1
							join villages as b1 on a1.village_id = b1.id 
							left join org_diagram_rt as c1 on a1.nik = c1.nik
							left join org_diagram_village as d1 on a1.nik = d1.nik
							left join org_diagram_district  as e1 on a1.nik = e1.nik
							join districts as f1 on b1.district_id = f1.id 
							join dapil_areas as g1 on g1.district_id = f1.id
							where g1.dapil_id  = a.id and c1.base is null and d1.nik is null and e1.nik is null and c1.nik is null
					 ) as belum_ada_korte,
					
					-- (
					-- 	SELECT COUNT(a5.nik)
					-- 	from org_diagram_village as a5
					-- 	join users as a6 on a5.nik = a6.nik
					-- 	join dapil_areas as a51 on a5.district_id = a51.district_id
					-- 	where a51.dapil_id = a.id
					-- ) as kordes,
					-- (
					-- 	SELECT COUNT(a7.nik)
					-- 	from org_diagram_district as a7
					-- 	join users as a8 on a8.nik = a7.nik
					-- 	join dapil_areas as a71 on a7.district_id = a71.district_id 
					-- 	where a71.dapil_id = a.id
					-- ) as korcam,
					(SELECT DISTINCT  COUNT(w.id) from witnesses as w join dapil_areas da3 on w.district_id = da3.district_id where da3.dapil_id = a.id ) as saksi,
					(SELECT COUNT(*) from tps join dapil_areas on tps.district_id = dapil_areas.district_id WHERE dapil_areas.dapil_id = a.id) tps,
					(
						SELECT count(da4.id) from org_diagram_rt as da4  join users as da5 on da4.nik = da5.nik join dapil_areas as da6 on da4.district_id = da6.district_id
						where da4.base = 'ANGGOTA' and da6.dapil_id = a.id
					) as anggota_tercover_kortps,
					(
						select count(j.nik) from form_anggota_manual_kortp as j join 
						org_diagram_rt h on j.pidx_korte  = h.idx
						join users as k on h.nik = k.nik
						join dapil_areas as p on h.district_id = p.district_id
						where p.dapil_id = a.id
					) as form_manual,
					(
						select count(k.nik) from form_vivi as k join 
						org_diagram_rt g on k.pidx_korte  = g.idx
						join users as i on g.nik = i.nik
						join dapil_areas as x on g.district_id = x.district_id
						where x.dapil_id = a.id
					) as form_vivi, 
					(
						select count(o.nik) from form_anggota_manual_kortp_vivi  as o join 
						org_diagram_rt p on o.pidx_korte  = p.idx
						join users as z on p.nik = z.nik
						join dapil_areas as x1 on p.district_id = x1.district_id
						where x1.dapil_id = a.id
					) as form_manual_vivi
					from dapils as a
					where a.regency_id = $regencyId";

		return DB::select($sql);

	}

	public function getCalculateDataDaftarTimKorTps($regencyId){

		$sql = "SELECT a.id, a.name,
				(
					SELECT COUNT(a1.id) from users as a1 join villages as a2 on a1.village_id = a2.id
					join dapil_areas as a4 on a2.district_id = a4.district_id where a4.dapil_id = a.id
				) as anggota,
				-- (
				-- 	ceil(
				-- 		(
				-- 		SELECT COUNT(a1.id) from users as a1 join villages as a2 on a1.village_id = a2.id join districts as a3 on a2.district_id = a3.id
				-- 		join dapil_areas as a4 on a3.id = a4.district_id where a4.dapil_id = a.id
				-- 		)/25
				-- 	)
				-- ) target_korte,
				(SELECT COUNT(odr.id)
					from org_diagram_rt as odr
					join dapil_areas da2 on odr.district_id = da2.district_id
					WHERE odr.base = 'KORRT' and odr.nik is not null and da2.dapil_id = a.id
				) as korte_terisi,
				(SELECT COUNT(tps.id) from tps join dapil_areas on tps.district_id = dapil_areas.district_id where dapil_areas.dapil_id = a.id ) tps
				from dapils as a where a.regency_id = $regencyId";	

		return DB::select($sql);
	}

	public function getCalculateDataDaftarTimKorTpsDapil($regencyId, $dapilId){

		$sql = "SELECT a.id, a.name,
				(
					SELECT COUNT(a1.id) from users as a1 join villages as a2 on a1.village_id = a2.id
					join dapil_areas as a4 on a2.district_id = a4.district_id where a4.dapil_id = a.id
				) as anggota,
				-- (
				-- 	ceil(
				-- 		(
				-- 		SELECT COUNT(a1.id) from users as a1 join villages as a2 on a1.village_id = a2.id join districts as a3 on a2.district_id = a3.id
				-- 		join dapil_areas as a4 on a3.id = a4.district_id where a4.dapil_id = a.id
				-- 		)/25
				-- 	)
				-- ) target_korte,
				(SELECT COUNT(odr.id)
					from org_diagram_rt as odr
					join dapil_areas da2 on odr.district_id = da2.district_id
					join users as odruser on odr.nik = odruser.nik
					WHERE odr.base = 'KORRT' and da2.dapil_id = a.id
				) as korte_terisi,
				(SELECT COUNT(tps.id) from tps join dapil_areas on tps.district_id = dapil_areas.district_id where dapil_areas.dapil_id = a.id ) tps
				from dapils as a where a.regency_id = $regencyId and a.id = $dapilId";	

		return DB::select($sql);
	}

	public function getCalculateDataDaftarTimKorTpsDistrict($districtId){

		$sql = "SELECT a.name,
						-- (((SELECT COUNT(id) from dpt_kpu  WHERE village_id = a.id )/a.target_persentage)/25) as target_korte,
						(SELECT COUNT(id) from org_diagram_rt WHERE base = 'KORRT' and village_id = a.id) as korte_terisi,
						(SELECT COUNT(tps.id) from tps WHERE tps.village_id = a.id ) as tps
						from villages as a
						WHERE a.district_id = $districtId";	

		return DB::select($sql);
	}

	public function getCalculateDataDaftarTimKorTpsVillage($villageId){

		$sql = "SELECT a.name,
			(SELECT COUNT(org_diagram_rt.id) from org_diagram_rt join users on org_diagram_rt.nik = users.nik where org_diagram_rt.village_id = a.id and org_diagram_rt.base = 'KORRT') as korte_terisi,
			(SELECT COUNT(tps.id) from tps WHERE tps.village_id = a.id ) as tps
			from villages as a
			WHERE a.id = $villageId";	

		return DB::select($sql);
	}

	public function getDataDaftarTimByDapil($dapilId){

		#get data desa by dapil
        $sql = "SELECT a.district_id, b.name, b.target_persentage ,
				(select COUNT(id)  from org_diagram_village where title = 'KETUA' and district_id  = a.district_id) as ketua,
				(select COUNT(id)  from org_diagram_village where title = 'SEKRETARIS' and district_id  = a.district_id) as sekretaris,
				(select COUNT(id)  from org_diagram_village where title = 'BENDAHARA' and district_id  = a.district_id) as bendahara,
				(SELECT COUNT(b1.id) from dpt_kpu as b1 join villages b2 on b1.village_id = b2.id WHERE b2.district_id = a.district_id) as dpt,
				(SELECT COUNT(a1.id) from users as a1 join villages as a2 on a1.village_id = a2.id WHERE a2.district_id = a.district_id) as anggota,
				((SELECT COUNT(a1.id) from users as a1 join villages as a2 on a1.village_id = a2.id WHERE a2.district_id = a.district_id)/25) as target_korte,
				(
					SELECT COUNT(org_diagram_rt.id) from org_diagram_rt
					join users on org_diagram_rt.nik = users.nik
					WHERE org_diagram_rt.base = 'KORRT' and org_diagram_rt.district_id  = a.district_id
				) as korte_terisi,
				-- ((SELECT COUNT(id) from org_diagram_rt WHERE base = 'KORRT' and district_id  = a.district_id and nik is not null )*25) anggota_tercover,
				(
					SELECT COUNT(DISTINCT(a1.nik))
					from users as a1
					join villages as b1 on a1.village_id = b1.id 
					left join org_diagram_rt as c1 on a1.nik = c1.nik
					left join org_diagram_village as d1 on a1.nik = d1.nik
					left join org_diagram_district  as e1 on a1.nik = e1.nik
					join districts as f1 on b1.district_id = f1.id 
					where f1.id  = a.district_id and c1.base is null and d1.nik is null and e1.nik is null and c1.nik is null
				) as belum_ada_korte,
				(SELECT COUNT(id) from witnesses WHERE district_id  = a.district_id) as saksi,
				(SELECT COUNT(tps.id) from tps WHERE tps.district_id  = a.district_id) tps,
				(
						SELECT count(da4.id) from org_diagram_rt as da4  join users as da5 on da4.nik = da5.nik
						where da4.base = 'ANGGOTA' and da4.district_id = a.district_id
				) as anggota_tercover_kortps,
				(
						select count(j.nik) from form_anggota_manual_kortp as j join 
						org_diagram_rt h on j.pidx_korte  = h.idx
						join users as k on h.nik = k.nik
						where h.district_id = a.district_id 
				) as form_manual,
				(
					select count(k.nik) from form_vivi as k join 
					org_diagram_rt g on k.pidx_korte  = g.idx
					join users as i on g.nik = i.nik
					where g.district_id  = a.district_id 
				) as form_vivi,
				(
					select count(o.nik) from form_anggota_manual_kortp_vivi  as o join 
					org_diagram_rt p on o.pidx_korte  = p.idx
					join users as z on p.nik = z.nik
					where p.district_id  = a.district_id 
				) as form_manual_vivi
				from dapil_areas as a
				join districts as b on a.district_id = b.id
				where a.dapil_id = $dapilId order by (SELECT COUNT(a1.id) from users as a1 join villages as a2 on a1.village_id = a2.id WHERE a2.district_id = a.district_id) desc";
        
        return DB::select($sql);

	} 

	public function getDataDaftarTimByDapilForRegency($dapilId){

		#get data desa by dapil
        $sql = "SELECT b.name, b.target_persentage,
				((SELECT COUNT(b1.id) from dpt_kpu as b1 join villages b2 on b1.village_id = b2.id WHERE b2.district_id = a.district_id)*b.target_persentage)/100 as target
				from dapil_areas as a
				join districts as b on a.district_id = b.id
				where a.dapil_id = $dapilId order by (SELECT COUNT(a1.id) from users as a1 join villages as a2 on a1.village_id = a2.id WHERE a2.district_id = a.district_id) desc";
		
		$result = DB::select($sql);
		$jml_target = collect($result)->sum(function($q){
			return $q->target;
		});
        $jml_target = round($jml_target);
        return $jml_target;

	}

	public function getDataDaftarTimByDapilForRegencyAll(){

		#get data desa by dapil
        $sql = "SELECT b.name, b.target_persentage,
				((SELECT COUNT(b1.id) from dpt_kpu as b1 join villages b2 on b1.village_id = b2.id WHERE b2.district_id = a.district_id)*b.target_persentage)/100 as target
				from dapil_areas as a
				join districts as b on a.district_id = b.id";
		
		$result = DB::select($sql);
		$jml_target = collect($result)->sum(function($q){
			return $q->target;
		});
        $jml_target = round($jml_target);
        return $jml_target;

	}

	public function getKalkulasiTercoverDapil($dapilId){

		// $sql = "SELECT COUNT(a.id) as tercover,
		// 	(SELECT COUNT(a1.id) from users as a1 join villages as a2 on a1.village_id = a2.id join districts as a3 on 
		// 	a2.district_id = a3.id join dapil_areas as a4 on a4.district_id = a3.id where a4.dapil_id = $dapilId) as anggota
		// 	from org_diagram_rt as a
		// 	join users as b on a.nik = b.nik
		// 	join dapil_areas as c on a.district_id = c.district_id
		// 	where a.base ='ANGGOTA'  and c.dapil_id = $dapilId";

		// $sql = "SELECT COUNT(a.id) as tercover,
		// 	(SELECT COUNT(a1.id) from users as a1 join villages as a2 on a1.village_id = a2.id join districts as a3 on a2.district_id = a3.id join dapil_areas as a4 on a4.district_id = a3.id where a4.dapil_id = $dapilId) as anggota,
		// 	(
		// 		-- (SELECT COUNT(a1.id) from users as a1 join villages as a2 on a1.village_id = a2.id join districts as a3 on a2.district_id = a3.id join dapil_areas as a4 on a4.district_id = a3.id where a4.dapil_id =  $dapilId) -
		// 		-- COUNT(a.id) -
		// 		-- (SELECT COUNT(a5.nik) from org_diagram_village as a5 join users as a6 on a5.nik = a6.nik join dapil_areas as a7 on a5.district_id = a7.district_id where a7.dapil_id = $dapilId) -
		// 		-- (SELECT COUNT(a8.nik) from org_diagram_district as a8 join users as a9 on a8.nik = a9.nik join dapil_areas as a10 on a8.district_id = a10.district_id where a10.dapil_id = $dapilId) -
		// 		-- (SELECT COUNT(a11.nik) from org_diagram_rt as a11 join users as a12 on a11.nik = a12.nik join dapil_areas as a13 on a13.district_id  = a11.district_id WHERE a13.dapil_id = $dapilId and a11.base = 'KORRT')
		// 		SELECT COUNT(a.nik) 
		// 		from users as a
		// 		join villages as b on a.village_id = b.id
		// 		join dapil_areas as c on b.district_id = c.district_id
		// 		WHERE c.dapil_id = $dapilId
		// 		and (SELECT COUNT(a1.nik) from org_diagram_rt as a1 where a1.nik = a.nik) = 0
		// 		and (SELECT COUNT(a2.nik) from org_diagram_village as a2 where a2.nik = a.nik ) = 0
		// 		and (SELECT COUNT(a3.nik) from org_diagram_district as a3 where a3.nik = a.nik) = 0
		// 		and (SELECT COUNT(a4.nik) from org_diagram_dapil as a4 WHERE a4.nik = a.nik) = 0 
		// 	)as fix_anggota_belum_tercover
		// 	from org_diagram_rt as a
		// 	join users as b on a.nik = b.nik
		// 	join dapil_areas as c on a.district_id = c.district_id
		// 	where a.base ='ANGGOTA'  and c.dapil_id = $dapilId";

		$sql = "SELECT COUNT(a.id) as tercover,
				(SELECT COUNT(a1.id) from users as a1 join villages as a2 on a1.village_id = a2.id join districts as a3 on
				a2.district_id = a3.id join dapil_areas as a4 on a4.district_id = a3.id where a4.dapil_id = $dapilId) as anggota,
				(
					SELECT COUNT(DISTINCT(a1.nik))
							from users as a1
							join villages as b1 on a1.village_id = b1.id 
							left join org_diagram_rt as c1 on a1.nik = c1.nik
							left join org_diagram_village as d1 on a1.nik = d1.nik
							left join org_diagram_district  as e1 on a1.nik = e1.nik
							join districts as f1 on b1.district_id = f1.id 
							join dapil_areas as g1 on g1.district_id = f1.id
							where g1.dapil_id  = $dapilId and c1.base is null and d1.nik is null and e1.nik is null and c1.nik is null
				) as fix_anggota_belum_tercover,
				(
					SELECT COUNT(a9.nik)
					from org_diagram_rt as a9
					join users as a10 on a9.nik = a10.nik
					join dapil_areas as a91 on a9.district_id = a91.district_id 
					where a91.dapil_id  = $dapilId and a9.base = 'KORRT'
				) as kortps_terisi,
				(SELECT COUNT(tps.id) from tps join dapil_areas on tps.district_id = dapil_areas.district_id  where dapil_areas.dapil_id  = $dapilId ) tps
				from org_diagram_rt as a
				join users as b on a.nik = b.nik
				join dapil_areas as c on a.district_id = c.district_id
				join dapils as d on c.dapil_id = d.id
				where a.base ='ANGGOTA' and d.id  = $dapilId";
        
        return collect(DB::select($sql))->first();
	}

	public function getKalkulasiTercoverDistrict($districtId){

		// $sql = "SELECT COUNT(a.id) as tercover,
		// 		(SELECT COUNT(a1.id) from users as a1 join villages as a2 on a1.village_id = a2.id join districts as a3 on 
		// 		a2.district_id = a3.id where a3.id = $districtId) as anggota
		// 		from org_diagram_rt as a
		// 		join users as b on a.nik = b.nik
		// 		join districts as c on a.district_id = c.id
		// 		where a.base ='ANGGOTA'  and a.district_id = $districtId";
		// $sql = "SELECT COUNT(a.id) as tercover,
		// 	(SELECT COUNT(a1.id) from users as a1 join villages as a2 on a1.village_id = a2.id join districts as a3 on a2.district_id = a3.id where a3.id = $districtId) as anggota,
		// 	(
		// 		-- (SELECT COUNT(a1.id) from users as a1 join villages as a2 on a1.village_id = a2.id join districts as a3 on a2.district_id = a3.id where a3.id = $districtId) - 
		// 		-- COUNT(a.id) -
		// 		-- (SELECT COUNT(a4.nik) from org_diagram_village as a4 join users as a5 on a4.nik = a5.nik where a4.district_id = $districtId) -
		// 		-- (SELECT COUNT(a6.nik) from org_diagram_district as a6 join users as a7 on a6.nik = a7.nik where a6.district_id = $districtId) -
		// 		-- (SELECT COUNT(a8.nik) from org_diagram_rt as a8 join users as a9 on a8.nik = a9.nik WHERE a8.district_id = $districtId and a8.base = 'KORRT')
		// 		SELECT COUNT(a.nik) 
		// 		from users as a
		// 		join villages as b on a.village_id = b.id
		// 		WHERE b.district_id  = 3602010
		// 		and (SELECT COUNT(a1.nik) from org_diagram_rt as a1 where a1.nik = a.nik) = 0
		// 		and (SELECT COUNT(a2.nik) from org_diagram_village as a2 where a2.nik = a.nik ) = 0
		// 		and (SELECT COUNT(a3.nik) from org_diagram_district as a3 where a3.nik = a.nik) = 0
		// 		and (SELECT COUNT(a4.nik) from org_diagram_dapil as a4 WHERE a4.nik = a.nik) = 0
		// 	) as fix_anggota_belum_tercover
		// 	from org_diagram_rt as a
		// 	join users as b on a.nik = b.nik
		// 	join districts as c on a.district_id = c.id
		// 	where a.base ='ANGGOTA'  and a.district_id = $districtId";

		$sql = "SELECT COUNT(a.id) as tercover,
				(SELECT COUNT(a1.id) from users as a1 join villages as a2 on a1.village_id = a2.id where a2.district_id = $districtId) as anggota,
				(
					SELECT COUNT(DISTINCT(a1.nik))
					from users as a1
					join villages as b1 on a1.village_id = b1.id 
					left join org_diagram_rt as c1 on a1.nik = c1.nik
					left join org_diagram_village as d1 on a1.nik = d1.nik
					left join org_diagram_district  as e1 on a1.nik = e1.nik
					where b1.district_id  = $districtId and c1.base is null and d1.nik is null and e1.nik is null and c1.nik is null
				) as fix_anggota_belum_tercover,
				-- (
				-- 	SELECT COUNT(a5.nik)
				-- 	from org_diagram_village as a5
				-- 	join users as a6 on a5.nik = a6.nik
				-- 	where a5.district_id = $districtId
				-- ) as kordes,
				-- (
				-- 	SELECT COUNT(a7.nik)
				-- 	from org_diagram_district as a7
				-- 	join users as a8 on a8.nik = a7.nik
				-- 	where a7.district_id  = $districtId
				-- ) as korcam,
				(
					SELECT COUNT(a9.nik)
					from org_diagram_rt as a9
					join users as a10 on a9.nik = a10.nik
					where a9.district_id  = $districtId and a9.base = 'KORRT'
				) as kortps_terisi,
				(SELECT COUNT(tps.id) from tps where tps.district_id = $districtId) tps
				from org_diagram_rt as a
				join users as b on a.nik = b.nik
				where a.base ='ANGGOTA' and a.district_id = $districtId";
        
        return collect(DB::select($sql))->first();
	}

	public function getKalkulasiTercoverVillage($villageId){

		// $sql = "SELECT COUNT(a.id) as tercover,
		// 		(SELECT COUNT(a1.id) from users as a1 join villages as a2 on a1.village_id = a2.id where a2.id = $villageId) as anggota
		// 		from org_diagram_rt as a
		// 		join users as b on a.nik = b.nik
		// 		where a.base ='ANGGOTA'  and a.village_id = $villageId";

		// $sql = "SELECT COUNT(a.id) as tercover,
		// 	(SELECT COUNT(a1.id) from users as a1 join villages as a2 on a1.village_id = a2.id where a2.id = $villageId) as anggota,
		// 	(
		// 		-- (SELECT COUNT(a1.id) from users as a1 join villages as a2 on a1.village_id = a2.id where a2.id = $villageId) - 
		// 		-- COUNT(a.id) - 
		// 		-- (SELECT COUNT(a3.nik) from org_diagram_village as a3 join users as a4 on a3.nik = a4.nik where a3.village_id = $villageId) - 
		// 		-- (SELECT COUNT(a5.nik) from org_diagram_rt as a5 join users as a6 on a5.nik = a6.nik where a5.village_id = $villageId and a5.base = 'KORRT') -
		// 		-- (SELECT COUNT(a7.nik) from org_diagram_district as a7 join users as a8 on a7.nik = a8.nik join villages as a9 on a8.village_id = a9.id WHERE a9.id = $villageId)
		// 		SELECT COUNT(a.nik) 
		// 		from users as a
		// 		join villages as b on a.village_id = b.id
		// 		WHERE b.id = $villageId
		// 		and (SELECT COUNT(a1.nik) from org_diagram_rt as a1 where a1.nik = a.nik) = 0
		// 		and (SELECT COUNT(a2.nik) from org_diagram_village as a2 where a2.nik = a.nik ) = 0
		// 		and (SELECT COUNT(a3.nik) from org_diagram_district as a3 where a3.nik = a.nik) = 0
		// 		and (SELECT COUNT(a4.nik) from org_diagram_dapil as a4 WHERE a4.nik = a.nik) = 0
		// 	) as fix_anggota_belum_tercover
		// 	from org_diagram_rt as a
		// 	join users as b on a.nik = b.nik
		// 	where a.base ='ANGGOTA'  and a.village_id = $villageId";

		$sql = "SELECT COUNT(a.id) as tercover,
				(SELECT COUNT(a1.id) from users as a1 join villages as a2 on a1.village_id = a2.id where a2.id  = $villageId) as anggota,
				(
					SELECT COUNT(a.nik)
					from users as a
					join villages as b on a.village_id = b.id 
					left join org_diagram_rt as c on a.nik = c.nik
					left join org_diagram_village as d on a.nik = d.nik
					left join org_diagram_district  as e on a.nik = e.nik
					where b.id  = $villageId and c.base is null and d.nik is null and e.nik is null and c.nik is null
				) as fix_anggota_belum_tercover,
				-- (
				-- 	SELECT COUNT(a5.nik)
				-- 	from org_diagram_village as a5
				-- 	join users as a6 on a5.nik = a6.nik
				-- 	where a5.village_id  = $villageId
				-- ) as kordes,
				-- (
				-- 	SELECT COUNT(a7.nik)
				-- 	from org_diagram_district as a7
				-- 	join users as a8 on a8.nik = a7.nik
				-- 	where a8.village_id = $villageId
				-- ) as korcam,
				(
					SELECT COUNT(a9.nik)
					from org_diagram_rt as a9
					join users as a10 on a9.nik = a10.nik
					where a9.village_id  = $villageId and a9.base = 'KORRT'
				) as kortps_terisi,
				(SELECT COUNT(tps.id) from tps where tps.village_id  = $villageId) tps
				from org_diagram_rt as a
				join users as b on a.nik = b.nik
				where a.base ='ANGGOTA' and a.village_id  = $villageId";
							
				return collect(DB::select($sql))->first();
	}

	public function getDataAnggotaBelumterCoverKortpsByVillage($village_id){

		// $sql = "SELECT a.nik, a.name , b.name as desa, a.rt, c.name as referal, a.rw, d.name as district,
		// 		a.phone_number, a.whatsapp, a.created_at, e.name as cby,
		// 		(SELECT COUNT(id) from org_diagram_rt WHERE nik = a.nik and base = 'ANGGOTA' ) as anggota 
		// 		from users as a 
		// 		join villages as b on a.village_id = b.id
		// 		join users as c on a.user_id = c.id
		// 		join districts as d on b.district_id = d.id
		// 		join users as e on a.cby = c.id
		// 		WHERE b.id = $village_id and (SELECT COUNT(id) from org_diagram_rt WHERE nik = a.nik ) = 0
		// 		order by a.rt asc";
		// $sql = "SELECT a.id, a.nik, a.name, a.rt, a.address , a.rw,
		// 		b.name as desa
		// 		from users as a 
		// 		join villages as b on a.village_id = b.id
		// 		WHERE b.id  = $village_id and (SELECT COUNT(id) from org_diagram_rt 
		// 		WHERE nik = a.nik and base = 'ANGGOTA' ) = 0
		// 		and (SELECT COUNT(id) from org_diagram_rt WHERE nik = a.nik and base = 'KORRT' )   = 0
		// 		and (SELECT COUNT(id) from org_diagram_village where nik = a.nik) = 0 ORDER BY a.rt asc";
		$sql = "SELECT a.id , a.nik, a.name, a.rt, a.address , a.rw, b.name as desa
				from users as a
				join villages as b on a.village_id = b.id 
				left join org_diagram_rt as c on a.nik = c.nik
				left join org_diagram_village as d on a.nik = d.nik
				left join org_diagram_district  as e on a.nik = e.nik 
				where b.id  = $village_id and c.base is null and d.nik is null and e.nik is null and c.nik is null 
				order by a.rt asc";

		return DB::select($sql);
	}

	public function getKalkulasiTercoverRt($villageId, $rt){

		// $sql = "SELECT COUNT(a.id) as tercover,
		// 		(SELECT COUNT(a1.id) from users as a1 join villages as a2 on a1.village_id = a2.id where a2.id = $villageId and a1.rt = $rt) as anggota
		// 		from org_diagram_rt as a
		// 		join users as b on a.nik = b.nik
		// 		where a.base ='ANGGOTA'  and b.village_id = $villageId and a.rt = $rt";

		// $sql = "SELECT COUNT(a.id) as tercover,
		// 	(SELECT COUNT(a1.id) from users as a1 join villages as a2 on a1.village_id = a2.id where a2.id = $villageId and a1.rt = $rt) as anggota,
		// 	(
		// 		-- (SELECT COUNT(a1.id) from users as a1 join villages as a2 on a1.village_id = a2.id where a2.id = $villageId and a1.rt = $rt) - 
		// 		-- COUNT(a.id) - 
		// 		-- (SELECT COUNT(a3.nik) from org_diagram_rt as a3 join users as a4 on a3.nik = a4.nik WHERE a3.village_id = $villageId and a3.rt = $rt and a3.base = 'KORRT') -
		// 		-- (SELECT COUNT(a5.nik) from org_diagram_village as a5 join users as a6 on a5.nik = a6.nik WHERE a5.village_id = $villageId)
		// 		SELECT COUNT(a.nik) 
		// 		from users as a
		// 		join villages as b on a.village_id = b.id
		// 		WHERE b.id = $villageId and a.rt = $rt
		// 		and (SELECT COUNT(a1.nik) from org_diagram_rt as a1 where a1.nik = a.nik) = 0
		// 		and (SELECT COUNT(a2.nik) from org_diagram_village as a2 where a2.nik = a.nik ) = 0
		// 		and (SELECT COUNT(a3.nik) from org_diagram_district as a3 where a3.nik = a.nik) = 0
		// 		and (SELECT COUNT(a4.nik) from org_diagram_dapil as a4 WHERE a4.nik = a.nik) = 0 
		// 	) as fix_anggota_belum_tercover
		// 	from org_diagram_rt as a
		// 	join users as b on a.nik = b.nik
		// 	where a.base ='ANGGOTA' and b.village_id = $villageId and a.rt = $rt";

		$sql = "SELECT COUNT(a.id) as tercover,
				(SELECT COUNT(a1.id) from users as a1 join villages as a2 on a1.village_id = a2.id where a2.id  = $villageId and a1.rt = $rt) as anggota,
				(
					(SELECT COUNT(a1.id) from users as a1 join villages as a2 on a1.village_id = a2.id where a2.id  = $villageId and a1.rt = $rt)-
					COUNT(a.id) -
					(SELECT COUNT(a5.nik)
					from org_diagram_village as a5
					join users as a6 on a5.nik = a6.nik
					where a5.village_id  = $villageId and a6.rt = $rt)-
					(SELECT COUNT(a7.nik)
					from org_diagram_district as a7
					join users as a8 on a8.nik = a7.nik
					where a8.village_id  = $villageId and a8.rt = $rt)-
					(SELECT COUNT(a9.nik)
					from org_diagram_rt as a9
					join users as a10 on a9.nik = a10.nik
					where a9.village_id  = $villageId and a9.base = 'KORRT' and a10.rt = $rt)
				) as fix_anggota_belum_tercover,
				(
					SELECT COUNT(a9.nik)
					from org_diagram_rt as a9
					join users as a10 on a9.nik = a10.nik
					where a9.village_id  = $villageId and a9.base = 'KORRT'
				) as kortps_terisi,
				(SELECT COUNT(tps.id) from tps where tps.village_id  = $villageId) tps
				from org_diagram_rt as a
				join users as b on a.nik = b.nik
				where a.base ='ANGGOTA' and a.village_id  = $villageId and b.rt = $rt";
        
        return collect(DB::select($sql))->first();
	}

	public function getKalkulasiTercoverAll($regency){

		// $sql = "SELECT COUNT(a.id) as tercover,
		// 		(SELECT COUNT(a1.id) from users as a1 join villages as a2 on a1.village_id = a2.id join districts as a3 on
		// 		a2.district_id = a3.id join dapil_areas as a4 on a4.district_id = a3.id where a3.regency_id = $regency) as anggota,
		// 		(
		// 			SELECT COUNT(a.nik) 
		// 			from users as a
		// 			WHERE  (SELECT COUNT(a1.nik) from org_diagram_rt as a1 where a1.nik = a.nik) = 0
		// 			and (SELECT COUNT(a2.nik) from org_diagram_village as a2 where a2.nik = a.nik ) = 0
		// 			and (SELECT COUNT(a3.nik) from org_diagram_district as a3 where a3.nik = a.nik) = 0
		// 			and (SELECT COUNT(a4.nik) from org_diagram_dapil as a4 WHERE a4.nik = a.nik) = 0 
		// 		) as fix_anggota_belum_tercover,
		// 		(
		// 			SELECT COUNT(a5.nik)
		// 			from org_diagram_village as a5
		// 			join users as a6 on a5.nik = a6.nik
		// 		)
		// 		from org_diagram_rt as a
		// 		join users as b on a.nik = b.nik
		// 		join dapil_areas as c on a.district_id = c.district_id
		// 		join dapils as d on c.dapil_id = d.id
		// 		where a.base ='ANGGOTA' and d.regency_id =$regency";
		$sql = "SELECT COUNT(a.id) as tercover,
				(SELECT COUNT(a1.id) from users as a1 join villages as a2 on a1.village_id = a2.id join districts as a3 on
				a2.district_id = a3.id join dapil_areas as a4 on a4.district_id = a3.id where a3.regency_id = $regency) as anggota,
				(
					SELECT COUNT(DISTINCT(a1.nik))
							from users as a1
							join villages as b1 on a1.village_id = b1.id 
							left join org_diagram_rt as c1 on a1.nik = c1.nik
							left join org_diagram_village as d1 on a1.nik = d1.nik
							left join org_diagram_district  as e1 on a1.nik = e1.nik
							join districts as f1 on b1.district_id = f1.id 
							where f1.regency_id = $regency and c1.base is null and d1.nik is null and e1.nik is null and c1.nik is null
				) as fix_anggota_belum_tercover,
				(SELECT COUNT(tps.id) from tps where tps.regency_id = 3602 ) tps,
				-- (
				-- 	SELECT COUNT(a5.nik)
				-- 	from org_diagram_village as a5
				-- 	join users as a6 on a5.nik = a6.nik
				-- 	where a5.regency_id = $regency
				-- ) as kordes,
				-- (
				-- 	SELECT COUNT(a7.nik)
				-- 	from org_diagram_district as a7
				-- 	join users as a8 on a8.nik = a7.nik
				-- 	where a7.regency_id = $regency
				-- ) as korcam,
				(
					SELECT COUNT(a9.nik)
					from org_diagram_rt as a9
					join users as a10 on a9.nik = a10.nik
					where a9.regency_id = $regency and a9.base = 'KORRT'
				) as kortps_terisi
				from org_diagram_rt as a
				join users as b on a.nik = b.nik
				join dapil_areas as c on a.district_id = c.district_id
				join dapils as d on c.dapil_id = d.id
				where a.base ='ANGGOTA' and d.regency_id = $regency";
        
        return collect(DB::select($sql))->first();
	}

	public function getKorTpsByPidxKorte($pidx){

		$sql = "SELECT a.name , a.rt , b.name as village, c.name as district from org_diagram_rt as a
				join villages as b on a.village_id = b.id
				join districts as c on a.district_id = c.id
				where a.idx = '$pidx'";
		return collect(DB::select($sql))->first();
	}

	public function getDataPengurusKecamatan($districtId){

		$sql = DB::table('org_diagram_district as a')
				->select('b.name','a.title','b.photo','b.address','c.name as village','d.name as district',
					 DB::raw('(SELECT COUNT(a1.id) from users a1 join villages a2 on a1.village_id = a2.id  WHERE a1.user_id = b.id) as referal'))
				->join('users as b','a.nik','=','b.nik')
				->join('villages as c','b.village_id','=','c.id')
				->join('districts as d','c.district_id','=','d.id')
				->where('a.district_id', $districtId)
				->orderBy('a.level_org','asc')
				->get();
		return $sql;
	}

	public function getDataPengurusDesa($villageId){

		$sql = DB::table('org_diagram_village as a')
				->select('b.name','a.title','b.photo','b.address','c.name as village','d.name as district',
					DB::raw('(SELECT COUNT(a1.id) from users a1 join villages a2 on a1.village_id = a2.id  WHERE a1.user_id = b.id) as referal'))
				->join('users as b','a.nik','=','b.nik')
				->join('villages as c','b.village_id','=','c.id')
				->join('districts as d','c.district_id','=','d.id')
				->where('a.village_id', $villageId)
				->orderBy('a.level_org','asc')
				->get();

		return $sql;
	}

	public function getDataDaftarTimByRegencyForDashboard($regencyId){

		$sql = "SELECT a.id, a.name,
					(
						SELECT COUNT(adpt.id) from dpt_kpu as adpt
						join districts as bdpt on adpt.district_id = bdpt.id 
						join dapil_areas as cdpt on bdpt.id = cdpt.district_id 
						WHERE cdpt.dapil_id = a.id
					) as dpt,
					(
						SELECT COUNT(a1.id) from users as a1 join villages as a2 on a1.village_id = a2.id
						join dapil_areas as a4 on a2.district_id = a4.district_id where a4.dapil_id = a.id
					) as anggota
					from dapils as a
					where a.regency_id = $regencyId";

		return DB::select($sql);

	}

	public function getDataDaftarTimByProvinceForDashboard($proivnceId){

		$sql = "SELECT a.id, a.name,
				(
					SELECT COUNT(adpt.id) from dpt_kpu as adpt
					join districts as bdpt on adpt.district_id = bdpt.id 
					join dapil_areas as cdpt on bdpt.id = cdpt.district_id 
					WHERE cdpt.dapil_id = a.id
				) as dpt,
				(
					SELECT COUNT(a1.id) from users as a1 join villages as a2 on a1.village_id = a2.id
					join dapil_areas as a4 on a2.district_id = a4.district_id where a4.dapil_id = a.id
				) as anggota
				from dapils as a
				join regencies as c on a.regency_id = c.id 
				where c.province_id  = $proivnceId";

		return DB::select($sql);

	}

	public function getDataDaftarTimByDapilForProvince($proivnceId){

		#get data desa by dapil
        $sql = "SELECT b.name, b.target_persentage,
					((SELECT COUNT(b1.id) from dpt_kpu as b1 join villages b2 on b1.village_id = b2.id WHERE b2.district_id = a.district_id)*b.target_persentage)/100 as target
					from dapil_areas as a
					join districts as b on a.district_id = b.id
					join regencies as c on b.regency_id = c.id
					where c.province_id = $proivnceId";
		
		$result = DB::select($sql);
		$jml_target = collect($result)->sum(function($q){
			return $q->target;
		});
        $jml_target = round($jml_target);
        return $jml_target;

	}

	public function getDataDaftarTimByDapilForNational(){

		#get data desa by dapil
        $sql = "SELECT a.target_persentage,
			((SELECT COUNT(b1.id) from dpt_kpu as b1 
			join districts as b3 on b1.district_id = b3.id
			WHERE b3.id = a.id)*a.target_persentage)/100 as target
			from districts as a";
		
		$result = DB::select($sql);
		$jml_target = collect($result)->sum(function($q){
			return $q->target;
		});
        $jml_target = round($jml_target);
        return $jml_target;

	}

	public function getDataDaftarTimByNationalForDashboard(){

		$sql = "SELECT a.id, a.name,
					(
						SELECT COUNT(adpt.id) from dpt_kpu as adpt
						join districts as bdpt on adpt.district_id = bdpt.id 
						join dapil_areas as cdpt on bdpt.id = cdpt.district_id 
						WHERE cdpt.dapil_id = a.id
					) as dpt,
					(
						SELECT COUNT(a1.id) from users as a1 join villages as a2 on a1.village_id = a2.id
						join dapil_areas as a4 on a2.district_id = a4.district_id where a4.dapil_id = a.id
					) as anggota
					from dapils as a";

		return DB::select($sql);

	}

	public function getTpsNotExistByVillage($villageId){

		$sql = "SELECT tps_number as tps,
				(SELECT COUNT(org_diagram_rt.nik) from org_diagram_rt join users on org_diagram_rt.nik = users.nik where org_diagram_rt.base = 'KORRT' and users.tps_id = tps.id) as kortps
				from tps
				WHERE village_id = $villageId 
				Having (SELECT COUNT(org_diagram_rt.nik) from org_diagram_rt join users on org_diagram_rt.nik = users.nik where org_diagram_rt.base = 'KORRT' and users.tps_id = tps.id) < 1";
		return DB::select($sql);
	}

	public function getTpsExistByVillage($villageId){

		$sql = "SELECT village_id, id, hasil_suara, tps_number as tps,
				(SELECT COUNT(org_diagram_rt.nik) from org_diagram_rt join users on org_diagram_rt.nik = users.nik where org_diagram_rt.base = 'KORRT' and users.tps_id = tps.id) as kortps
				from tps
				WHERE village_id = $villageId group by village_id, id, tps_number,hasil_suara
				Having (SELECT COUNT(org_diagram_rt.nik) from org_diagram_rt join users on org_diagram_rt.nik = users.nik where org_diagram_rt.base = 'KORRT' and users.tps_id = tps.id) > 0";
		return DB::select($sql);
	}
	
}
