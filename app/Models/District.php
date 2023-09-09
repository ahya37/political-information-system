<?php

/*
 * This file is part of the IndoRegion package.
 *
 * (c) Azis Hapidin <azishapidin.com | azishapidin@gmail.com>
 *
 */

namespace App\Models;

use AzisHapidin\IndoRegion\Traits\DistrictTrait;
use Illuminate\Database\Eloquent\Model;
use App\Models\Regency;
use App\Models\Village;
use Illuminate\Support\Facades\DB;
/**
 * District Model.
 */
class District extends Model
{
    use DistrictTrait;

    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'districts';
    protected $guarded = [];
    public  $timestamps = false;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'regency_id'
    ];

    /**
     * District belongs to Regency.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function regency()
    {
        return $this->belongsTo(Regency::class);
    }

    /**
     * District has many villages.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function villages()
    {
        return $this->hasMany(Village::class);
    }

    public function getGrafikTotalMemberDistrictRegency($regency_id)
    {
        $sql = "SELECT c.id as distric_id, c.name as district,
                count(a.name) as total_member
                from users as a 
                join villages as b on a.village_id = b.id 
                join districts as c on b.district_id = c.id 
                where c.regency_id = $regency_id
                GROUP by  c.name, c.id order by c.name asc";
        return DB::select($sql);
    }

    public function getTargetPersentageDistrict($district_id){

        $sql = DB::table('districts')->select('target_persentage')->where('id', $district_id)->first();
        return $sql;
    }

    public function getGrafikTotalMemberAdminMember($user_id)
    {
        $sql = "SELECT a.id as distric_id, a.name as district, COUNT(DISTINCT(e.id)) as total_member
                from districts as a 
                join admin_dapil_district as b on a.id = b.district_id
                join admin_dapils as c on b.admin_dapils_id = c.id
                join villages as d on a.id = d.district_id
                join users as e on d.id = e.village_id
                where c.admin_user_id = $user_id group by a.id , a.name order by COUNT(DISTINCT(e.id)) desc";
        return DB::select($sql);
    }

    public function getGrafikTotalMemberAdminMemberCaleg($user_id)
    {
        $sql = "SELECT a.id as district_id, a.name as district, COUNT(DISTINCT(e.id)) as total_member
                from districts as a 
                join admin_dapil_district as b on a.id = b.district_id
                join admin_dapils as c on b.admin_dapils_id = c.id
                join villages as d on a.id = d.district_id
                join users as e on d.id = e.village_id
                where c.admin_user_id = $user_id and e.user_id = $user_id 
                group by a.id , a.name order by COUNT(DISTINCT(e.id)) desc";
        return DB::select($sql);
    }

    public function getGrafikTotalMemberDistrict($district_id)
    {
        $sql = "SELECT b.id as village_id, b.name as district,
                count(a.name) as total_member
                from users as a 
                join villages as b on a.village_id = b.id 
                join districts as c on b.district_id = c.id 
                where c.id = $district_id
                GROUP by  b.name, b.id order by b.name asc";
        return DB::select($sql);
    }

    public function getGrafikTotalMemberDistrictCaleg($district_id, $userId)
    {
        $sql = "SELECT b.id as village_id, b.name as district,
                count(a.name) as total_member
                from users as a 
                join villages as b on a.village_id = b.id 
                join districts as c on b.district_id = c.id 
                where c.id = $district_id and a.user_id = $userId
                GROUP by  b.name, b.id order by b.name asc";
        return DB::select($sql);
    }

    public function achievementDistrict($regency_id)
    {
        $sql = "SELECT c.id, c.name,
                count(DISTINCT(b.id)) as total_village,
                c.target as target_member,
                CEIL(c.target /  count(DISTINCT(b.id)))  as total_target_member,
                count(a.id) as realisasi_member,
                count(IF(date(a.created_at) = CURDATE() , a.id, NULL)) as todays_achievement,
                (count(a.id) / c.target) * 100 as percen
                from users as a
                right join villages as b on a.village_id = b.id
                join districts as c on b.district_id = c.id
                where c.regency_id = $regency_id
                group by c.id, c.name, c.target HAVING count(a.id) != 0  order by c.name asc";
        return DB::select($sql);
    }

    public function achievementAdminMember($user_id)
    {
        $sql = "SELECT c.id, c.name,
                count(DISTINCT(b.id)) as total_village,
                c.target as target_member,
                CEIL(c.target /  count(DISTINCT(b.id)))  as total_target_member,
                count(a.id) as realisasi_member,
                count(IF(date(a.created_at) = CURDATE() , a.id, NULL)) as todays_achievement
                from users as a
                right join villages as b on a.village_id = b.id
                join districts as c on b.district_id = c.id
                join admin_dapil_district as d on c.id = d.district_id 
                join admin_dapils as e on d.admin_dapils_id = e.id 
                where e.admin_user_id = $user_id
                group by c.id, c.name, c.target HAVING count(a.id) != 0  order by c.name asc";
        return DB::select($sql);
    }

    public function achievementAdminMemberCaleg($user_id)
    {
        $sql = "SELECT c.id, c.name,
                count(DISTINCT(b.id)) as total_village,
                c.target as target_member,
                CEIL(c.target /  count(DISTINCT(b.id)))  as total_target_member,
                count(a.id) as realisasi_member,
                count(IF(date(a.created_at) = CURDATE() , a.id, NULL)) as todays_achievement
                from users as a
                right join villages as b on a.village_id = b.id
                right join districts as c on b.district_id = c.id
                right join admin_dapil_district as d on c.id = d.district_id 
                right join admin_dapils as e on d.admin_dapils_id = e.id 
                where e.admin_user_id = $user_id
                and a.user_id = $user_id
                group by c.id, c.name, c.target HAVING count(a.id) != 0  order by c.name asc";
        return DB::select($sql);
    }

    public function getTotalRegion($district_id)
    {
        $sql = "SELECT b.name as district, COUNT(DISTINCT(c.id)) as village 
                from districts as b 
                join villages as c on b.id = c.district_id
                where b.id = $district_id GROUP BY b.name ";
        return collect(\ DB::select($sql))->first();
    }

    public function getDistrictByReferalMember($user_id)
    {
        $sql = "SELECT a.id as id, a.name as district, COUNT(d.id) as total_member FROM districts as a
                join villages as b on a.id = b.district_id 
                join users as c on b.id = c.village_id
                join users as d on c.user_id = d.id
                where c.user_id = $user_id and c.id != $user_id
                group by  a.id, a.name order by COUNT(d.id) desc ";
        return DB::select($sql);
    }

    public function getDistrictByInputMember($user_id)
    {
        $sql = "SELECT a.id as id, a.name as district, COUNT(c.id) as total_member FROM districts as a
                join villages as b on a.id = b.district_id 
                join users as c on b.id = c.village_id
                where c.cby = $user_id
                group by  a.id, a.name order by a.name asc";
        return DB::select($sql);
    }

    public function getDistrictDapilByRegency($regency_id)
    {
        $sql = "SELECT a.id as district_id, a.name, b.id from districts as a
                left join dapil_areas as b on a.id = b.district_id
                where a.regency_id = $regency_id and b.id is NULL order by a.name ASC";
        return DB::select($sql);
    }

    public function getDataDistrictByRegencyId($regency_id)
    {
        $sql = "SELECT a.id, a.name from districts as a
                join villages as b on a.id = b.district_id
                join districts as c on b.district_id = c.id
                join users as d on b.id = d.village_id
                where c.regency_id = $regency_id and d.nik is not NULL and d.email is not null and d.status = 1
                group by a.id , a.name order by a.name asc";
        return DB::select($sql);
    }

    public function getTotalMemberByReferal($user_id)
    {
        $sql = "SELECT COUNT(c.id) as total_member FROM districts as a
                join villages as b on a.id = b.district_id 
                join users as c on b.id = c.village_id
                where c.user_id = $user_id and c.id != $user_id";
        return collect(\ DB::select($sql))->first();
    }

    public function getTotalMemberByInput($user_id)
    {
        $sql = "SELECT COUNT(c.id) as total_member FROM districts as a
                join villages as b on a.id = b.district_id 
                join users as c on b.id = c.village_id
                where c.cby = $user_id";
        return collect(\ DB::select($sql))->first();
    }


}
