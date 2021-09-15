<?php

namespace App\Providers\Model;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;

class UserModel extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    public function __construct()
    {
        
    }

    public function getProfile($id)
    {
        $sql = DB::table('users as a')
                ->leftJoin('villages as b','a.village_id','=','b.id')
                ->leftJoin('districts as c','b.district_id','=','c.id')
                ->leftJoin('regencies as d','c.regency_id','=','d.id')
                ->leftJoin('provinces as e','d.province_id','=','e.id')
                ->leftJoin('jobs as f','a.job_id','f.id')
                ->leftJoin('educations as g','a.education_id','g.id')
                ->select('a.*','f.id as job_id','g.id as education_id','b.name as village','b.id as village_id','c.name as district','c.id as district_id','d.name as regency','d.id as regency_id','e.name as province','e.id as province_id')
                ->where('a.id', $id)->first();
        return $sql;
    }
}
