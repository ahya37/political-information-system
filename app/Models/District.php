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
                GROUP by  c.name, c.id";
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
                GROUP by  b.name, b.id";
        return DB::select($sql);
    }

    public function achievementDistrict($regency_id)
    {
        $sql = "SELECT c.id, c.name,
                count(DISTINCT(b.id)) as total_village,
                ceil(5000 / count(DISTINCT(b.id))) target_member,
                ceil(5000 / count(DISTINCT(b.id))) * count(DISTINCT(b.id))  as total_target_member,
                count(a.id) as realisasi_member,
                count(IF(date(a.created_at) = CURDATE() , a.id, NULL)) as todays_achievement
                from users as a
                join villages as b on a.village_id = b.id
                join districts as c on b.district_id = c.id
                where c.regency_id = $regency_id
                group by c.id, c.name order by c.name asc";
        return DB::select($sql);
    }
}
