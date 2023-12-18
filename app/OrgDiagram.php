<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
							a.base
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
		
		$sql = "SELECT a.base, b.id, a.idx, b.nik as NIK , b.name as NAMA, 
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

	public function getDataDaftarTimByKecamatan($districtId){

		$sql = "SELECT a.id, a.name, a.target_persentage ,
                (select COUNT(id)  from org_diagram_village where title = 'KETUA' and village_id = a.id) as ketua,
                (select COUNT(id)  from org_diagram_village where title = 'SEKRETARIS' and village_id = a.id) as sekretaris,
                (select COUNT(id)  from org_diagram_village where title = 'BENDAHARA' and village_id = a.id) as bendahara,
                (SELECT COUNT(id) from dpt_kpu WHERE village_id = a.id ) as dpt,
                (SELECT COUNT(id) from users WHERE village_id = a.id ) as anggota,
                ((SELECT COUNT(id) from users WHERE village_id = a.id )/25)as target_korte,
                (SELECT COUNT(id) from org_diagram_rt WHERE base = 'KORRT' and village_id = a.id and nik is not null ) as korte_terisi,
                -- ((SELECT COUNT(id) from org_diagram_rt WHERE base = 'KORRT' and village_id = a.id and nik is not null )*25) anggota_tercover,
                -- ((CEIL ((SELECT COUNT(id) from users WHERE village_id = a.id )/25))-(SELECT COUNT(id) from org_diagram_rt WHERE base = 'KORRT' and village_id = a.id and nik is not null )) as kurang_korte,
                ((SELECT COUNT(id) from users WHERE village_id = a.id )-((SELECT COUNT(id) from org_diagram_rt WHERE base = 'KORRT' and village_id = a.id and nik is not null )*25)) as belum_ada_korte,
                -- ((SELECT COUNT(id) from dpt_kpu WHERE village_id = a.id )*(SELECT target_persentage from villages where id = a.id)/100) as target,
				(SELECT COUNT(id) from witnesses WHERE village_id = a.id ) as saksi,
				(SELECT COUNT(*) from tps WHERE tps.village_id = a.id) tps
                from villages as a
                WHERE a.district_id = $districtId order by (SELECT COUNT(id) from org_diagram_rt WHERE base = 'KORRT' and village_id = a.id and nik is not null ) desc";
        
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
				(SELECT COUNT(id) from org_diagram_rt WHERE base = 'KORRT' and district_id  = a.district_id and nik is not null ) as korte_terisi,
				((SELECT COUNT(id) from org_diagram_rt WHERE base = 'KORRT' and district_id  = a.district_id and nik is not null )*25) anggota_tercover,
				((SELECT COUNT(a1.id) from users as a1 join villages as a2 on a1.village_id = a2.id WHERE a2.district_id = a.district_id)-((SELECT COUNT(id) from org_diagram_rt WHERE base = 'KORRT' and district_id  = a.district_id and nik is not null )*25)) as belum_ada_korte,
				(SELECT COUNT(id) from witnesses WHERE district_id  = a.district_id) as saksi
				from dapil_areas as a
				join districts as b on a.district_id = b.id
				where a.dapil_id = $dapilId order by (SELECT COUNT(a1.id) from users as a1 join villages as a2 on a1.village_id = a2.id WHERE a2.district_id = a.district_id) desc";
        
        return DB::select($sql);

	}

	public function getKalkulasiTercoverDapil($dapilId){

		$sql = "SELECT COUNT(a.id) as tercover,
			(SELECT COUNT(a1.id) from users as a1 join villages as a2 on a1.village_id = a2.id join districts as a3 on 
			a2.district_id = a3.id join dapil_areas as a4 on a4.district_id = a3.id where a4.dapil_id = $dapilId) as anggota
			from org_diagram_rt as a
			join users as b on a.nik = b.nik
			join dapil_areas as c on a.district_id = c.district_id
			where a.base ='ANGGOTA'  and c.dapil_id = $dapilId";
        
        return collect(DB::select($sql))->first();
	}

	public function getKalkulasiTercoverDistrict($districtId){

		// $sql = "SELECT COUNT(a.id) as tercover,
		// 		(SELECT COUNT(a1.id) from users as a1 join villages as a2 on a1.village_id = a2.id join districts as a3 on 
		// 		a2.district_id = a3.id where a3.id = $districtId) as anggota
		// 		from org_diagram_rt as a
		// 		join users as b on a.nik = b.nik
		// 		join districts as c on a.district_id = c.id
		// 		where a.base ='ANGGOTA'  and c.id = $districtId";
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
				) as fix_anggota_belum_tercover
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
		// 		where a.base ='ANGGOTA'  and b.village_id = $villageId";
		// $sql = "SELECT COUNT(DISTINCT(a.nik)) as tercover,
		// (SELECT COUNT(a1.id) from users as a1 join villages as a2 on a1.village_id = a2.id where a2.id = $villageId) as anggota
		// 		from users as a
		// 		join villages as b on a.village_id = b.id 
		// 		left join org_diagram_rt as c on a.nik = c.nik
		// 		left join org_diagram_village as d on a.nik = d.nik
		// 		left join org_diagram_district  as e on a.nik = e.nik 
		// 		where b.id  = $villageId and c.base is null and d.nik is null and e.nik is null and c.nik is null";
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

	public function getKalkulasiTercoverRt($villageId, $rt){

		// $sql = "SELECT COUNT(a.id) as tercover,
		// 		(SELECT COUNT(a1.id) from users as a1 join villages as a2 on a1.village_id = a2.id where a2.id = $villageId and a1.rt = $rt) as anggota
		// 		from org_diagram_rt as a
		// 		join users as b on a.nik = b.nik
		// 		where a.base ='ANGGOTA'  and b.village_id = $villageId and a.rt = $rt";
		$sql = "SELECT COUNT(a.id) as tercover,
				(SELECT COUNT(a1.id) from users as a1 join villages as a2 on a1.village_id = a2.id where a2.id  = $villageId and a1.rt = $rt) as anggota,
				(
					SELECT COUNT(DISTINCT(a4.nik))
					from users as a4
					join villages as b4 on a4.village_id = b4.id 
					left join org_diagram_rt as c4 on a4.nik = c4.nik
					left join org_diagram_village as d4 on a4.nik = d4.nik
					left join org_diagram_district  as e4 on a4.nik = e4.nik
					where b4.id  = $villageId and a4.rt = $rt and c4.base is null and d4.nik is null and e4.nik is null and c4.nik is null
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
					where a9.village_id  = $villageId and a10.rt = $rt and a9.base = 'KORRT'
				) as kortps_terisi
				-- (SELECT COUNT(tps.id) from tps where tps.village_id  = $villageId) tps
				from org_diagram_rt as a
				join users as b on a.nik = b.nik
				where a.base ='ANGGOTA' and a.village_id  = $villageId and b.rt = $rt";
        
        return collect(DB::select($sql))->first();
	}

	public function getKalkulasiTercoverAll(){

		$sql = "SELECT COUNT(a.id) as tercover,
				(SELECT COUNT(a1.id) from users as a1 join villages as a2 on a1.village_id = a2.id join districts as a3 on 
				a2.district_id = a3.id join dapil_areas as a4 on a4.district_id = a3.id) as anggota
				from org_diagram_rt as a
				join users as b on a.nik = b.nik
				join dapil_areas as c on a.district_id = c.district_id
				where a.base ='ANGGOTA'";
        
        return collect(DB::select($sql))->first();
	}

	public function getKorTpsByPidxKorte($pidx){

		$sql = "SELECT a.name , a.rt , b.name as village, c.name as district from org_diagram_rt as a
				join villages as b on a.village_id = b.id
				join districts as c on a.district_id = c.id
				where a.idx = '$pidx'";
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
		// 		and (SELECT COUNT(id) from org_diagram_village where nik = a.nik) = 0";
		$sql = "SELECT a.id , a.nik, a.name, a.rt, a.address , a.rw, b.name as desa
		from users as a
		join villages as b on a.village_id = b.id 
		left join org_diagram_rt as c on a.nik = c.nik
		left join org_diagram_village as d on a.nik = d.nik
		left join org_diagram_district  as e on a.nik = e.nik 
		where b.id  = $village_id and c.base is null and d.nik is null and e.nik is null and c.nik is null 
		order by a.rt asc ";

		return DB::select($sql);
	}

	public function getDataAnggotaBelumterCoverKortpsByVillageAndRt($village_id, $rt){

		$sql = "SELECT a.nik, a.name , b.name as desa, a.rt, a.rw, a.address,
				(SELECT COUNT(id) from org_diagram_rt WHERE nik = a.nik and base = 'ANGGOTA' ) as anggota 
				from users as a 
				join villages as b on a.village_id = b.id
				-- WHERE b.id = $village_id and a.rt = $rt and (SELECT COUNT(id) from org_diagram_rt WHERE nik = a.nik ) = 0
				WHERE b.id = $village_id and a.rt = $rt and (SELECT COUNT(id) from org_diagram_rt WHERE nik = a.nik and base = 'ANGGOTA' ) = 0
				and (SELECT COUNT(id) from org_diagram_rt WHERE nik = a.nik and base = 'KORRT' )   = 0
				order by a.rt asc";
				
		return DB::select($sql);
	}

	public function getDataAnggotaBelumterCoverKortpsByDistrictId($district_id){

		$sql = "SELECT a.nik, a.name , b.name as desa, a.rt,
				(SELECT COUNT(id) from org_diagram_rt WHERE nik = a.nik and base = 'ANGGOTA' ) as anggota 
				from users as a 
				join villages as b on a.village_id = b.id
				WHERE b.district_id = $district_id and (SELECT COUNT(id) from org_diagram_rt WHERE nik = a.nik ) = 0
				order by a.rt asc";
				
		return DB::select($sql);
	}

	public function getCountMemberDeferentVillageByKortps($villageId){

		$sql = "SELECT b.photo , b.name, a.idx,
				(
					SELECT COUNT(a1.id) from org_diagram_rt as a1 join users as a2 on a1.nik=a2.nik 
					WHERE a1.pidx = a.idx and a1.base = 'ANGGOTA' and a1.village_id != a.village_id
				) as anggota
				from org_diagram_rt  as a
				join users as b on a.nik = b.nik 
				WHERE a.village_id = $villageId and a.base = 'KORRT' HAVING anggota != 0";

		return DB::select($sql);

	}

	public function getDataAnggotaByKorte($idx)
	{
		$sql = DB::table('org_diagram_rt as a')
				->select('b.id','b.name','a.base')
				->join('users as b','a.nik','=','b.nik')
				->where('a.pidx', $idx)
				->where('a.base','ANGGOTA')
				->get();
				
		return $sql;
	}

	public function getDataKorcamByAdminDistrict($districtId)
	{
		$sql = DB::table('org_diagram_district as a')
				->select('b.name','b.id','a.base')
				->join('users as b','a.nik','b.nik')
				->where('a.district_id', $districtId)
				->get();
		return $sql;
	}

	public function getDataKordesByVillage($villageId)
	{
		$sql = DB::table('org_diagram_village as a')
				->select('b.name','b.id','a.base')
				->join('users as b','a.nik','b.nik')
				->where('a.village_id', $villageId)
				->get();
		return $sql;
	}

}
