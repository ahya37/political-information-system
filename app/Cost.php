<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Cost extends Model
{
    protected $table = 'cost';
    protected $guarded = [];

    public function getDataCost()
    {
        $sql = "SELECT a.file, a.date, a.received_name, a.nominal, b.name as forcest, c.name as forecast_desc, a.address
            from cost_les as a
            join forecast as b on a.forcest_id = b.id 
            join forecast_desc as c on a.forecast_desc_id = c.id 
            order by a.date desc";
        return DB::select($sql);
    }

    public function getDataCostRange($start, $end)
    {
        $sql = "SELECT a.file, a.date, a.received_name, a.nominal, b.name as forcest, c.name as forecast_desc, a.address
            from cost_les as a
            join forecast as b on a.forcest_id = b.id 
            join forecast_desc as c on a.forecast_desc_id = c.id 
            where a.date  BETWEEN  '$start' and '$end' order by a.date desc ";
        return DB::select($sql);
    }
}
