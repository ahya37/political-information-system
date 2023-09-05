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

	public function getDataDaftarTimByKecamatan($districtId){

		#get data desa by kecamatan
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
                ((SELECT COUNT(id) from users WHERE village_id = a.id )-((SELECT COUNT(id) from org_diagram_rt WHERE base = 'KORRT' and village_id = a.id and nik is not null )*25)) as belum_ada_korte
                -- ((SELECT COUNT(id) from dpt_kpu WHERE village_id = a.id )*(SELECT target_persentage from villages where id = a.id)/100) as target
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
				((SELECT COUNT(a1.id) from users as a1 join villages as a2 on a1.village_id = a2.id WHERE a2.district_id = a.district_id)-((SELECT COUNT(id) from org_diagram_rt WHERE base = 'KORRT' and district_id  = a.district_id and nik is not null )*25)) as belum_ada_korte
				from dapil_areas as a
				join districts as b on a.district_id = b.id
				join right_to_choose_districts as c on b.id = c.district_id
				where a.dapil_id = $dapilId order by (SELECT COUNT(a1.id) from users as a1 join villages as a2 on a1.village_id = a2.id WHERE a2.district_id = a.district_id) desc";
        
        return DB::select($sql);

	}
}
