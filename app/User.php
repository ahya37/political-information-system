<?php

namespace App;

use App\Models\Village;
use Illuminate\Support\Facades\DB;
use Alfa6661\AutoNumber\AutoNumberTrait;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    use AutoNumberTrait;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getAutoNumberOptions()
    {
        return [
            'number' => [
                'length' => 7
            ]
        ];
    }

    public function village()
    {
        return $this->belongsTo(Village::class,'village_id');
    }

    public function job()
    {
        return $this->belongsTo(Job::class,'job_id');
    }

    public function education()
    {
        return $this->belongsTo(Education::class,'education_id');
    }

    public function reveral()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function referalMember()
    {
        // relasi untuk maping anggota yang mereferal padanya
        return $this->belongsTo(User::class,'id','user_id');
    }

    

    public function eventDetail()
    {
        return $this->belongsTo(EventDetail::class,'id','user_id');
    }

    public function create_by()
    {
        return $this->belongsTo(User::class,'cby');
    }
    
    public function getMemberProvince($province_id)
    {
        $sql = "SELECT a.name
                from users as a 
                join villages as b on a.village_id = b.id 
                join districts as c on b.district_id = c.id
                join regencies as d on c.regency_id = d.id 
                WHERE  d.province_id = $province_id";
        return DB::select($sql);
    }

    public function getMemberRegency($regency_id)
    {
        $sql = "SELECT a.name
                from users as a 
                join villages as b on a.village_id = b.id 
                join districts as c on b.district_id = c.id 
                where c.regency_id = $regency_id";
        return DB::select($sql);
    }

    public function getMemberDistrict($district_id)
    {
        $sql = "SELECT a.name
                from users as a 
                join villages as b on a.village_id = b.id 
                join districts as c on b.district_id = c.id 
                where c.id = $district_id";
        return DB::select($sql);
    }

    public function getGenderRegency($regency_id)
    {
        $sql = "SELECT a.gender, count(a.id) as total
                from users as a 
                join villages as b on a.village_id = b.id 
                join districts as c on b.district_id = c.id 
                where c.regency_id = $regency_id  group by a.gender  order by a.gender ASC";
        return DB::select($sql);
    }

    public function getGenders()
    {
        $sql = "SELECT a.gender, count(a.id) as total
                from users as a 
                join villages as b on a.village_id = b.id 
                join districts as c on b.district_id = c.id 
                join regencies as d on c.regency_id = d.id
                group by a.gender  order by a.gender ASC";
        return DB::select($sql);
    }

    public function getGenderProvince($province_id)
    {
        $sql = "SELECT a.gender, count(a.id) as total
                from users as a 
                join villages as b on a.village_id = b.id 
                join districts as c on b.district_id = c.id 
                join regencies as d on c.regency_id = d.id
                where d.province_id = $province_id  group by a.gender  order by a.gender ASC";
        return DB::select($sql);
    }

    public function getGenderDistrict($district_id)
    {
        $sql = "SELECT a.gender, count(a.id) as total
                from users as a 
                join villages as b on a.village_id = b.id
                where  b.district_id = $district_id  group by a.gender  order by a.gender ASC";
        return DB::select($sql);
    }

    public function getMemberByUser($id_user)
    {
        $result = DB::table('users as a')
                ->leftJoin('villages as b','a.village_id','=','b.id')
                ->leftJoin('districts as c','b.district_id','=','c.id')
                ->leftJoin('regencies as d','c.regency_id','=','d.id')
                ->select('a.*','b.name as village','c.name as district','d.name as regency')
                ->where('a.user_id', $id_user)
                ->whereNotIn('a.id', [$id_user])
                ->get();
        return $result;
    }

    public function getReferalUnDirect($id_user)
    {
        $sql = "SELECT sum(if(user_id != $id_user ,1,0)) as total from users  where user_id in (
                    SELECT id from users where user_id = $id_user
                ) and not id = $id_user";
        $result = collect(\DB::select($sql))->first();
        return $result;
    }

    public function getReferalDirect($id_user)
    {
        $sql = "SELECT sum(if(user_id = $id_user ,1,0)) as total from users  where user_id in (
                    SELECT id from users where id = $id_user
                ) and not id = $id_user";
        $result = collect(\DB::select($sql))->first();
        return $result;
    }

    public function getDataByReferalUnDirect($id_user)
    {
        $sql = "SELECT a.id, a.name, e.name as reveral, b.name as village, c.name as districts, d.name as regency from users as a
                left join villages as b on a.village_id = b.id
                left join districts as c on b.district_id = c.id
                left join regencies as d on c.regency_id = d.id
                join users as e on a.user_id = e.id
                where a.user_id in (SELECT id from users where user_id = $id_user)
                and not a.user_id = $id_user";
        $result = DB::select($sql);
        return $result;
    }

    public function getDataByReferalDirect($id_user)
    {
        $sql = "SELECT a.id, a.user_id , a.name, e.name as reveral, b.name as village, c.name as districts, d.name as regency from users as a
                left join villages as b on a.village_id = b.id
                left join districts as c on b.district_id = c.id
                left join regencies as d on c.regency_id = d.id
                join users as e on a.user_id = e.id
                where a.user_id in (SELECT id from users where user_id = $id_user)
                and not a.id = $id_user and a.user_id = $id_user";
        $result = DB::select($sql);
        return $result;
    }

    public function getDataByTotalReferalDirect($id_user)
    {
         $sql = "SELECT a.*, e.name as referal, e.id as referal_id, e.photo as photo_referal, b.name as village, c.name as district, d.name as regency from users as a
                left join villages as b on a.village_id = b.id
                left join districts as c on b.district_id = c.id
                left join regencies as d on c.regency_id = d.id
                join users as e on a.user_id = e.id
                where a.user_id in (SELECT id from users where id = $id_user)
                and not a.id = $id_user";
        $result = DB::select($sql);
        return $result;
    }

    public function rangeAge()
    {
        $sql = "SELECT 
            CASE 
                when age < 20 then '... - 20'
                when age between 20 and 25 then '20 - 25'
                when age between 25 and 30 then '25 - 30'
                when age between 30 and 35 then '30 - 35'
                when age between 35 and 40 then '35 - 40'
                when age between 40 and 45 then '40 - 45'
                when age between 45 and 50 then '45 - 50'
                when age between 50 and 60 then '50 - 55'
                when age between 55 and 60 then '55 - 60'
                when age >= 60 then '60 - ...'
                when age is null then '(NULL)'
                end as range_age,
                count(*) as total
                
            from 
            (
                select date_berth, TIMESTAMPDIFF(YEAR, date_berth, CURDATE()) as age from users as a
                where a.village_id is not null
            ) as tb_age
            group by range_age order by range_age asc";
        $result = DB::select($sql);
        return $result;
    }

    public function rangeAgeProvince($province_id)
    {
        $sql = "SELECT 
            CASE 
                when age < 20 then '... - 20'
                when age between 20 and 25 then '20 - 25'
                when age between 25 and 30 then '25 - 30'
                when age between 30 and 35 then '30 - 35'
                when age between 35 and 40 then '35 - 40'
                when age between 40 and 45 then '40 - 45'
                when age between 45 and 50 then '45 - 50'
                when age between 50 and 60 then '50 - 55'
                when age between 55 and 60 then '55 - 60'
                when age >= 60 then '60 - ...'
                when age is null then '(NULL)'
                end as range_age,
                count(*) as total
                
            from 
            (
                select date_berth, TIMESTAMPDIFF(YEAR, date_berth, CURDATE()) as age from users as a
                join villages as b on a.village_id = b.id
                join districts as c on b.district_id = c.id
                join regencies as d on c.regency_id = d.id
                where d.province_id = $province_id
            ) as tb_age
            group by range_age order by range_age asc";
        $result = DB::select($sql);
        return $result;
    }

    public function generationAges()
    {
        $sql = "SELECT 
                CASE 
                when age between 17 and 40 then '17 - 40'
                when age between 41 and 50 then '41 - 50'
                when age > 50 then '50 - ...'
                when age is null then '(NULL)'                 
                end as gen_age,
                count(*) as total
                
            from 
            (
                select date_berth, TIMESTAMPDIFF(YEAR, date_berth, CURDATE()) as age from users as a
                where a.village_id is not null
            ) as tb_age
            group by gen_age order by gen_age asc";
        $result = DB::select($sql);
        return $result;
    }

    public function generationAgeProvince($province_id)
    {
        $sql = "SELECT 
                CASE 
                when age between 17 and 40 then '17 - 40'
                when age between 41 and 50 then '41 - 50'
                when age > 50 then '50 - ...'
                when age is null then '(NULL)'                 
                end as gen_age,
                count(*) as total
                
            from 
            (
                select date_berth, TIMESTAMPDIFF(YEAR, date_berth, CURDATE()) as age from users as a
                join villages as b on a.village_id = b.id
                join districts as c on b.district_id = c.id
                join regencies as d on c.regency_id = d.id
                where d.province_id = $province_id
            ) as tb_age
            group by gen_age order by gen_age asc";
        $result = DB::select($sql);
        return $result;
    }

    public function generationAgeRegency($province_id)
    {
        $sql = "SELECT 
                CASE 
                when age between 17 and 40 then '17 - 40'
                when age between 41 and 50 then '41 - 50'
                when age > 50 then '50 - ...'
                when age is null then '(NULL)'                 
                end as gen_age,
                count(*) as total
                
            from 
            (
                select date_berth, TIMESTAMPDIFF(YEAR, date_berth, CURDATE()) as age from users as a
                join villages as b on a.village_id = b.id
                join districts as c on b.district_id = c.id
                join regencies as d on c.regency_id = d.id
                where d.id = $province_id
            ) as tb_age
            group by gen_age order by gen_age asc";
        $result = DB::select($sql);
        return $result;
    }

    public function generationAgeDistrict($district_id)
    {
        $sql = "SELECT 
                CASE 
                when age between 17 and 40 then '17 - 40'
                when age between 41 and 50 then '41 - 50'
                when age > 50 then '50 - ...'
                when age is null then '(NULL)'                 
                end as gen_age,
                count(*) as total
                
            from 
            (
                select date_berth, TIMESTAMPDIFF(YEAR, date_berth, CURDATE()) as age from users as a
                join villages as b on a.village_id = b.id
                join districts as c on b.district_id = c.id
                where c.id = $district_id
            ) as tb_age
            group by gen_age order by gen_age asc";
        $result = DB::select($sql);
        return $result;
    }

    public function rangeAgeRegency($regency_id)
    {
        $sql = "SELECT 
            CASE 
               when age < 20 then '... - 20'
                when age between 20 and 25 then '20 - 25'
                when age between 25 and 30 then '25 - 30'
                when age between 30 and 35 then '30 - 35'
                when age between 35 and 40 then '35 - 40'
                when age between 40 and 45 then '40 - 45'
                when age between 45 and 50 then '45 - 50'
                when age between 50 and 60 then '50 - 55'
                when age between 55 and 60 then '55 - 60'
                when age >= 60 then '60 - ...'
                when age is null then '(NULL)'
                end as range_age,
                count(*) as total
                
            from 
            (
                select date_berth, TIMESTAMPDIFF(YEAR, date_berth, CURDATE()) as age from users as a
                join villages as b on a.village_id = b.id
                join districts as c on b.district_id = c.id
                where c.regency_id = $regency_id
            ) as tb_age
            group by range_age order by range_age asc";
        $result = DB::select($sql);
        return $result;
    }

    public function rangeAgeDistrict($district_id)
    {
        $sql = "SELECT 
                CASE 
                    when age < 20 then '... - 20'
                    when age between 20 and 25 then '20 - 25'
                    when age between 25 and 30 then '25 - 30'
                    when age between 30 and 35 then '30 - 35'
                    when age between 35 and 40 then '35 - 40'
                    when age between 40 and 45 then '40 - 45'
                    when age between 45 and 50 then '45 - 50'
                    when age between 50 and 60 then '50 - 55'
                    when age between 55 and 60 then '55 - 60'
                    when age >= 60 then '60 - ...'
                    when age is null then '(NULL)'
                    end as range_age,
                    count(*) as total
                
            from 
            (
                select date_berth, TIMESTAMPDIFF(YEAR, date_berth, CURDATE()) as age from users as a
                join villages as b on a.village_id = b.id
                where b.district_id = $district_id
            ) as tb_age
            group by range_age order by range_age asc";
        $result = DB::select($sql);
        return $result;
    }

    public function getMemberForCreateAdmin($district_id)
    {
        $sql = "SELECT a.id, a.name, c.user_id FROM users as a
                join villages as b on a.village_id = b.id
                left join admin_districts as c on a.id = c.user_id 
                where b.district_id = $district_id  and c.user_id is NULL ";
        $result = DB::select($sql);
        return $result;
    }

    public function getMemberRegisteredAll()
    {
         $sql = "SELECT e.id, e.name,
                e.target as target_member,
                count(a.id) as realisasi_member
                from users as a
                join villages as b on a.village_id = b.id
                right join districts as c on b.district_id = c.id
                join regencies as d on c.regency_id = d.id 
                join provinces as e on d.province_id  = e.id 
                group by e.id, e.name, e.target HAVING COUNT(a.id) !=  0 order by e.name asc";
        $result = DB::select($sql);
        return $result;
    }

    public function getMemberRegistered($province_id)
    {
         $sql = "SELECT d.id, d.name,
                d.target as target_member,
                count(a.id) as realisasi_member,
                (
                	SELECT  COUNT(id) from regencies where province_id = $province_id
                ) as total_child
                from users as a
                join villages as b on a.village_id = b.id
                right join districts as c on b.district_id = c.id
                join regencies as d on c.regency_id = d.id 
                where d.province_id = $province_id
                group by d.id, d.name , d.target HAVING  count(a.id) != 0 order by d.name asc";
        $result = DB::select($sql);
        return $result;
    }

    public function getMemberRegisteredRegency($regency_id)
    {
         $sql = "SELECT c.id, c.name,
                c.target as target_member,
                count(a.id) as realisasi_member
                from users as a
                join villages as b on a.village_id = b.id
                join districts as c on b.district_id = c.id
                where c.regency_id = $regency_id
                group by c.id, c.name, c.target order by c.name asc";
        $result = DB::select($sql);
        return $result;
    }

    public function getMemberRegisteredDistrct($district_id)
    {
         $sql = "SELECT b.id, b.name,
                b.target as target_member,
                count(a.id) as realisasi_member,
                 (
                	SELECT count(id) from villages  where district_id = $district_id
                ) as total_village
                from users as a
                join villages as b on a.village_id = b.id
                join districts as c on b.district_id = c.id
                where c.id = $district_id
                group by b.id, b.name, b.target  HAVING count(a.id) != 0 order by b.name asc";
        $result = DB::select($sql);
        return $result;
    }

    public function getMemberRegisteredByDayProvince($province_id, $start, $end)
    {
        $sql  = "select count(a.id) as total, DATE(a.created_at) as day from users as a
                    join villages as b on a.village_id = b.id 
                    join districts as c on b.district_id = c.id
                    join regencies as d on c.regency_id = d.id
                    where a.created_at between '".$start."' and '".$end."' and d.province_id = ".$province_id." 
                    and a.village_id is not null
                    group by day  order by DATE(a.created_at) asc";
        $result = DB::select($sql);
        return $result;
    }

    public function getMemberRegisteredByDayNation($start, $end)
    {
        $sql  = "select count(a.id) as total, DATE(a.created_at) as day from users as a
                    where a.created_at between '".$start."' and '".$end."' 
                    and a.village_id is not null
                    group by day  order by DATE(a.created_at) asc";
        $result = DB::select($sql);
        return $result;
    }

    public function getMemberRegisteredByDayRegency($regency_id, $start, $end)
    {
        $sql  = "select count(a.id) as total, DATE(a.created_at) as day from users as a
                    join villages as b on a.village_id = b.id 
                    join districts as c on b.district_id = c.id
                    join regencies as d on c.regency_id = d.id
                    where a.created_at between '".$start."' and '".$end."' and d.id = $regency_id
                    and a.village_id is not null
                    group by day  order by DATE(a.created_at) asc";
        $result = DB::select($sql);
        return $result;
    }

    public function getMemberRegisteredByDayDistrict($district_id, $start, $end)
    {
        $sql  = "select count(a.id) as total, DATE(a.created_at) as day from users as a
                    join villages as b on a.village_id = b.id 
                    join districts as c on b.district_id = c.id
                    where a.created_at between '".$start."' and '".$end."' and c.id = $district_id
                    and a.village_id is not null
                    group by day  order by DATE(a.created_at) asc";
        $result = DB::select($sql);
        return $result;
    }

    public function getMemberRegisteredByDayVillage($village_id, $start, $end)
    {
        $sql  = "select count(a.id) as total, DATE(a.created_at) as day from users as a
                    join villages as b on a.village_id = b.id 
                    where a.created_at between '".$start."' and '".$end."' and b.id = $village_id
                    and a.village_id is not null
                    group by day  order by DATE(a.created_at) asc";
        $result = DB::select($sql);
        return $result;
    }


    public function getMemberForEvent()
    {
        $sql  = "SELECT b.id, b.event_id, a.id as user_id, a.name, f.name as provincy, e.name  as regency, d.name as district, c.name as village
                from users as a
                left join event_details as b on a.id = b.user_id
                left join villages as c on a.village_id = c.id
                left join districts as d on c.district_id = d.id 
                left join regencies as e on d.regency_id = e.id
                left join provinces as f on e.province_id  = f.id
                where a.email is not null and a.password is not NULL order by a.id asc";
        $result = DB::select($sql);
        return $result;
    }

    public function getGenderVillage($village_id)
    {
        $sql = "SELECT a.gender, count(a.id) as total
                from users as a 
                join villages as b on a.village_id = b.id
                where  b.id = $village_id  group by a.gender  order by a.gender ASC";
        return DB::select($sql);
    }

    public function rangeAgeVillage($village_id)
    {
        $sql = "SELECT 
                CASE 
                    when age < 20 then '... - 20'
                    when age between 20 and 25 then '20 - 25'
                    when age between 25 and 30 then '25 - 30'
                    when age between 30 and 35 then '30 - 35'
                    when age between 35 and 40 then '35 - 40'
                    when age between 40 and 45 then '40 - 45'
                    when age between 45 and 50 then '45 - 50'
                    when age between 50 and 60 then '50 - 55'
                    when age between 55 and 60 then '55 - 60'
                    when age >= 60 then '60 - ...'
                    when age is null then '(NULL)'
                    end as range_age,
                    count(*) as total
                
            from 
            (
                select date_berth, TIMESTAMPDIFF(YEAR, date_berth, CURDATE()) as age from users as a
                join villages as b on a.village_id = b.id
                where b.id = $village_id
            ) as tb_age
            group by range_age order by range_age asc";
        $result = DB::select($sql);
        return $result;
    }

    public function generationAgeVillage($village_id)
    {
        $sql = "SELECT 
                CASE 
                when age between 17 and 40 then '17 - 40'
                when age between 41 and 50 then '41 - 50'
                when age > 50 then '50 - ...'
                when age is null then '(NULL)'                 
                end as gen_age,
                count(*) as total
                
            from 
            (
                select date_berth, TIMESTAMPDIFF(YEAR, date_berth, CURDATE()) as age from users as a
                join villages as b on a.village_id = b.id
                where b.id = $village_id
            ) as tb_age
            group by gen_age order by gen_age asc";
        $result = DB::select($sql);
        return $result;
    }

    public function getMemberInput()
    {
        $sql = "SELECT a.id, a.phone_number, a.whatsapp, a.name, e.name as regency, d.name as district, a.photo, COUNT(a.user_id) as total FROM users as a
                join users as b on a.id = b.cby
                join villages as c on a.village_id = c.id 
                join districts as d on c.district_id = d.id 
                join regencies as e on d.regency_id = e.id
                group by a.id, a.name, e.name, d.name , a.photo , a.phone_number, a.whatsapp
                order by COUNT(a.user_id) desc";
        return DB::select($sql);
    }
    public function getMemberReferal()
    {
        $sql = "SELECT a.id, a.phone_number, a.whatsapp, a.name, e.name as regency, d.name as district, c.name as village, a.photo, 
                COUNT(case when b.id != b.user_id then a.user_id end) as total FROM users as a
                join users as b on a.id = b.user_id
                join villages as c on a.village_id = c.id 
                join districts as d on c.district_id = d.id 
                join regencies as e on d.regency_id = e.id
                group by c.name, a.id, a.name, e.name, d.name, a.photo, a.phone_number, a.whatsapp 
                order by COUNT(a.user_id) desc";
        return DB::select($sql);
    }

    public function getListMemberByDistrictId($district_id, $user_id)
    {
        $sql = "SELECT  a.id, a.name,c.name as village, d.name as district, e.name as regency, f.name as province, a.address, a.rt, a.rw, a.phone_number, a.whatsapp, a.photo FROM users as a
                join villages as c on a.village_id = c.id 
                join districts as d on c.district_id = d.id 
                join regencies as e on d.regency_id = e.id
                join provinces as f on e.province_id = f.id
                where d.id = $district_id and a.user_id = $user_id  order by a.name";
        return DB::select($sql);
    }

    public function getListMemberByDistrictIdInput($district_id, $user_id)
    {
        $sql = "SELECT a.id, a.name, e.name as regency, d.name as district, c.name as village, a.photo FROM users as a
                join villages as c on a.village_id = c.id 
                join districts as d on c.district_id = d.id 
                join regencies as e on d.regency_id = e.id
                where d.id = $district_id and a.cby = $user_id  order by a.name";
        return DB::select($sql);
    }

    public function getDataMemberWhereNikIsNotNull()
    {
         $sql = "SELECT a.code, a.activate_token, a.id, a.photo, a.name, d.name as regency, c.name as district, b.name as village, e.name as referal, f.name as input, a.created_at, a.status from users as a
                join villages as b on a.village_id = b.id 
                join districts as c on b.district_id = c.id 
                join regencies as d on c.regency_id = d.id
                join users as e on a.user_id = e.id 
                join users as f on a.cby = f.id
                where a.nik is not NULL order by a.id DESC";
        return DB::select($sql);
    }

    public function getSearchMember($data)
    {
        $sql = "SELECT a.code, a.id, a.photo, a.name, d.name as regency, c.name as district, b.name as village from users as a
                join villages as b on a.village_id = b.id 
                join districts as c on b.district_id = c.id 
                join regencies as d on c.regency_id = d.id
                join users as e on a.user_id = e.id 
                where a.nik is not NULL and a.name like '%$data%'  order by a.id DESC";
        $result = DB::select($sql);
        return $result;
    }

    public function getMemberReferalByMember($id_user)
    {
        $sql = "SELECT a.id, a.phone_number, a.whatsapp, a.name, e.name as regency, d.name as district, c.name as village, a.photo, 
                COUNT(case when b.id != b.user_id then a.user_id end) as total FROM users as a
                join users as b on a.id = b.user_id
                join villages as c on a.village_id = c.id 
                join districts as d on c.district_id = d.id 
                join regencies as e on d.regency_id = e.id
                where a.user_id = $id_user and not a.id = $id_user
                group by c.name, a.id, a.name, e.name, d.name, a.photo, a.phone_number, a.whatsapp
                order by COUNT(a.user_id) desc";
        return DB::select($sql);
    }

    public function getMemberInputByMember($id_user)
    {
        $sql = "SELECT a.id ,a.phone_number, a.whatsapp, a.name, e.name as regency, d.name as district, a.photo, COUNT(a.user_id) as total FROM users as a
                join users as b on a.id = b.cby
                join villages as c on a.village_id = c.id 
                join districts as d on c.district_id = d.id 
                join regencies as e on d.regency_id = e.id
                where a.user_id = $id_user and not a.id = $id_user
                group by a.id, a.name, e.name, d.name , a.photo , a.phone_number, a.whatsapp
                order by COUNT(a.user_id) desc";
        return DB::select($sql);
    }

    public function getMemberInputerByDistrict($districtID)
    {
         $sql = "SELECT c.id as user_id, c.name as member, COUNT(a.id) as total_data, c.photo, d.name as village, e.name as district, f.name as regency, g.name as province, c.phone_number , c.whatsapp from users as a
                join villages as b on a.village_id = b.id
                join users as c on a.cby = c.id 
                join villages as d on c.village_id = d.id 
                join districts as e on d.district_id = e.id 
                join regencies as f on e.regency_id = f.id 
                join provinces as g on f.province_id = g.id
                where b.district_id = $districtID
                group by c.id, c.name, c.photo, d.name, e.name , f.name , c.photo , c.phone_number , c.whatsapp , g.name
                order by COUNT(a.id) desc";
        return DB::select($sql);
    }

}
