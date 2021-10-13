<?php

/*
 * This file is part of the IndoRegion package.
 *
 * (c) Azis Hapidin <azishapidin.com | azishapidin@gmail.com>
 *
 */

namespace App\Models;

use AzisHapidin\IndoRegion\Traits\ProvinceTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
/**
 * Province Model.
 */
class Province extends Model
{
    use ProvinceTrait;
    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'provinces';
    protected $guarded = [];
    public  $timestamps = false;

    /**
     * Province has many regencies.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function regencies()
    {
        return $this->hasMany(Regency::class);
    }

    public function getTotalRegion($province_id)
    {
        $sql = "SELECT d.name as province, COUNT(DISTINCT(a.id)) as regency, COUNT(DISTINCT(b.id)) as district, COUNT(c.id) as village 
                from regencies as a 
                join districts as b on a.id = b.regency_id 
                join villages as c on b.id = c.district_id 
                join provinces as d on a.province_id = d.id
                where a.province_id = $province_id GROUP by d.name";
        return collect(\ DB::select($sql))->first();
    }

    
}
