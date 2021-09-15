<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Job extends Model
{
    protected $table    = 'jobs';
    protected  $fillable = ['name'];

    public function getJobRegency($regency_id)
    {
        $sql = "SELECT e.name, COUNT(e.name) as total_job from regencies as a
                join districts as b on a.id = b.regency_id
                join villages as c on b.id = c.district_id
                join users as d on c.id = d.village_id
                join jobs as e on d.job_id = e.id
                where a.id = $regency_id
                GROUP by e.name ";
        return DB::select($sql);
    }

    public function getJobs()
    {
        $sql = "SELECT e.name, COUNT(e.name) as total_job from regencies as a
                join districts as b on a.id = b.regency_id
                join villages as c on b.id = c.district_id
                join users as d on c.id = d.village_id
                join jobs as e on d.job_id = e.id
                join regencies as f on b.regency_id = f.id
                GROUP by e.name";
        return DB::select($sql);
    }

    public function getJobProvince($province_id)
    {
        $sql = "SELECT e.name, COUNT(e.name) as total_job from regencies as a
                join districts as b on a.id = b.regency_id
                join villages as c on b.id = c.district_id
                join users as d on c.id = d.village_id
                join jobs as e on d.job_id = e.id
                join regencies as f on b.regency_id = f.id
                where f.province_id = $province_id
                GROUP by e.name";
        return DB::select($sql);
    }

    public function getJobDistrict($district_id)
    {
        $sql = "SELECT e.name, COUNT(e.name) as total_job from regencies as a
                join districts as b on a.id = b.regency_id
                join villages as c on b.id = c.district_id
                join users as d on c.id = d.village_id
                join jobs as e on d.job_id = e.id
                where b.id = $district_id
                GROUP by e.name";
        return DB::select($sql);
    }

    public function getMostJobs()
    {
        $sql = "SELECT e.name, COUNT(e.name) as total_job
				from regencies as a
                join districts as b on a.id = b.regency_id
                join villages as c on b.id = c.district_id
                join users as d on c.id = d.village_id
                join jobs as e on d.job_id = e.id
                join regencies as f on b.regency_id = f.id
                GROUP by e.name
                order by COUNT(e.name) desc
                limit 6";
        return DB::select($sql);
    }

    public function getMostJobsProvince($province_id)
    {
        $sql = "SELECT e.name, COUNT(e.name) as total_job
				from regencies as a
                join districts as b on a.id = b.regency_id
                join villages as c on b.id = c.district_id
                join users as d on c.id = d.village_id
                join jobs as e on d.job_id = e.id
                join regencies as f on b.regency_id = f.id
                where f.province_id = $province_id
                GROUP by e.name
                order by COUNT(e.name) desc
                limit 6";
        return DB::select($sql);
    }

    public function getMostJobsRegency($regency_id)
    {
        $sql = "SELECT e.name, COUNT(e.name) as total_job
				from regencies as a
                join districts as b on a.id = b.regency_id
                join villages as c on b.id = c.district_id
                join users as d on c.id = d.village_id
                join jobs as e on d.job_id = e.id
                where b.regency_id = $regency_id
                GROUP by e.name
                order by COUNT(e.name) desc
                limit 6";
        return DB::select($sql);
    }

     public function getMostJobsDistrict($district_id)
    {
        $sql = "SELECT e.name, COUNT(e.name) as total_job
				from regencies as a
                join districts as b on a.id = b.regency_id
                join villages as c on b.id = c.district_id
                join users as d on c.id = d.village_id
                join jobs as e on d.job_id = e.id
                where b.id = $district_id
                GROUP by e.name
                order by COUNT(e.name) desc
                limit 6";
        return DB::select($sql);
    }

    public function getMostJobsVillage($village_id)
    {
        $sql = "SELECT e.name, COUNT(e.name) as total_job
				from regencies as a
                join districts as b on a.id = b.regency_id
                join villages as c on b.id = c.district_id
                join users as d on c.id = d.village_id
                join jobs as e on d.job_id = e.id
                where c.id = $village_id
                GROUP by e.name
                order by COUNT(e.name) desc
                limit 6";
        return DB::select($sql);
    }

    public function getJobVillage($village_id)
    {
        $sql = "SELECT e.name, COUNT(e.name) as total_job from regencies as a
                join districts as b on a.id = b.regency_id
                join villages as c on b.id = c.district_id
                join users as d on c.id = d.village_id
                join jobs as e on d.job_id = e.id
                where c.id = $village_id
                GROUP by e.name";
        return DB::select($sql);
    }
}
