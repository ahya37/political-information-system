<?php

/*
 * This file is part of the IndoRegion package.
 *
 * (c) Azis Hapidin <azishapidin.com | azishapidin@gmail.com>
 *
 */

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use AzisHapidin\IndoRegion\Traits\RegencyTrait;

/**
 * Regency Model.
 */
class Regency extends Model
{
    use RegencyTrait;

    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'regencies';
    protected $guarded = [];
    public  $timestamps = false;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'province_id'
    ];

    /**
     * Regency belongs to Province.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    /**
     * Regency has many districts.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function districts()
    {
        return $this->hasMany(District::class);
    }

    public function getRegency()
    {
        $sql = "SELECT COUNT(DISTINCT (a.id)) as total_district 
                from districts as a
                join regencies as b on a.regency_id = b.id";
        return collect(\DB::select($sql))->first();
    }

    public function getRegencyProvince($province_id)
    {
        $sql = "SELECT COUNT(DISTINCT (a.id)) as total_district 
                from districts as a
                join regencies as b on a.regency_id = b.id 
                where b.province_id = $province_id";
        return collect(\DB::select($sql))->first();
    }

    public function getTotalMember()
    {
         $sql = "SELECT e.id as province_id, e.name as province,
                count(a.name) as total_member
                from users as a 
                join villages as b on a.village_id = b.id 
                right join districts as c on b.district_id = c.id
                join regencies as d on c.regency_id = d.id
                join provinces as e on d.province_id = e.id
                GROUP by e.id, e.name HAVING COUNT(a.id) !=  0 order by e.name asc";
        return DB::select($sql);
    }

    public function getGrafikTotalMemberRegencyProvince($province_id)
    {
         $sql = "SELECT d.id  as regency_id, d.name as regency,
                count(a.name) as total_member
                from users as a 
                join villages as b on a.village_id = b.id 
                join districts as c on b.district_id = c.id
                join regencies as d on c.regency_id = d.id
                where d.province_id = $province_id
                GROUP by d.id, d.name order by d.name asc";
        return DB::select($sql);
    }

    public function achievementProvince($province_id)
    {
        $sql = "SELECT d.id, d.name,
            count(DISTINCT(c.id)) as total_district,
            d.target as target_member,
            count(a.id) as realisasi_member,
            count(IF(date(a.created_at) = CURDATE() , a.id, NULL)) as todays_achievement,
            (count(a.id) / d.target) * 100 as percen
            from users as a
            join villages as b on a.village_id = b.id
            right join districts as c on b.district_id = c.id
            join regencies as d on c.regency_id = d.id 
            where d.province_id = $province_id
            group by d.id, d.name, d.target HAVING count(a.id) != 0";
        return DB::select($sql);
    }

    public function achievements()
    {
        $sql = "SELECT e.id, e.name,
            count(DISTINCT(c.id)) as total_district,
            e.target as target_member,
            count(a.id) as realisasi_member,
            count(IF(date(a.created_at) = CURDATE() , a.id, NULL)) as todays_achievement,
            (count(a.id) / e.target) * 100 as percen
            from users as a
            join villages as b on a.village_id = b.id
            right join districts as c on b.district_id = c.id
            join regencies as d on c.regency_id = d.id 
            join provinces as e on d.province_id = e.id
            group by e.id, e.name,e.target HAVING COUNT(a.id) !=  0 order by e.name asc";
        return DB::select($sql);
    }

    public function getTotalRegion($regency_id)
    {
        $sql = "SELECT a.name as regency,COUNT(DISTINCT(b.id)) as district, COUNT(c.id) as village 
                from regencies as a 
                join districts as b on a.id = b.regency_id 
                join villages as c on b.id = c.district_id
                where a.id = $regency_id GROUP BY a.name ";
        return collect(\ DB::select($sql))->first();
    }
    
    public function getSelectRegencies()
    {
        $sql = "SELECT a.id as regency_id, a.name from regencies as a
                join districts as b on a.id = b.regency_id 
                join villages as c on b.id = c.district_id
                join users as d on c.id = d.village_id
                GROUP by a.id, a.name";
        $result = DB::select($sql);
        return $result;
        
    }

    public function getAllTarget($user_id)
    {
        $sql = "SELECT
                SUM(DISTINCT(a.target)) as target
                from districts as a
                join villages as b on a.id = b.district_id
                join admin_dapil_district as d on a.id = d.district_id
                join admin_dapils as e on d.admin_dapils_id = e.id
                where e.admin_user_id = $user_id";
        return collect(\ DB::select($sql))->first();
    }

    public function getAllTargetCaleg($user_id)
    {
        $sql = "SELECT SUM(target) as target from districts_caleg_target where caleg_user_id = $user_id";
        return collect(\ DB::select($sql))->first();
    }
    
    
}