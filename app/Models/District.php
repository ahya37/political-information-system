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

    public function getGrafikTotalMemberDistrictRegency($regency_id, $userID)
    {
        $sql = "SELECT c.id as distric_id, c.name as district,
                count(DISTINCT(a.name)) as total_member
                from users as a 
                join villages as b on a.village_id = b.id 
                join districts as c on b.district_id = c.id 
                join admin_dapil_district as d on c.id = d.district_id
                join admin_dapils as e on d.admin_dapils_id = e.id
                where c.regency_id = $regency_id and e.admin_user_id = $userID
                GROUP by  c.name, c.id order by c.name asc";
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

    public function achievementDistrict($regency_id)
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
                where c.regency_id = $regency_id
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
        $sql = "SELECT a.id as id, a.name as district FROM districts as a
                join villages as b on a.id = b.district_id 
                join users as c on b.id = c.village_id
                where c.user_id = $user_id and c.id != $user_id
                group by  a.id, a.name order by a.name asc";
        return DB::select($sql);
    }

    public function getDistrictByInputMember($user_id)
    {
        $sql = "SELECT a.id as id, a.name as district FROM districts as a
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
}
