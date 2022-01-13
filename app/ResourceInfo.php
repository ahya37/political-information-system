<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ResourceInfo extends Model
{
    protected $table    = 'resource_info';
    protected  $guarded  = [];

    public function getDataResourceVillage($village_id)
    {
        $sql = "SELECT a.id, a.name from resource_info as a
                join detail_figure as b on a.id = b.resource_id where b.village_id = $village_id 
                GROUP by a.id, a.name";
        return DB::select($sql);
    }
}
