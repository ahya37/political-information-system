<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Dpt extends Model
{
    protected $table = 'dpt_kpu';
    protected $guarded = [];

    public function getDptLevelNational(){

        $sql = "SELECT COUNT(id) as total_dpt from dpt_kpu";
        return collect(DB::select($sql))->first();
    }

    public function getDptLevelProvince(){

        $sql = "SELECT COUNT(id) as total_dpt from dpt_kpu";
        return collect(DB::select($sql))->first();
    }

    public function getDptLevelRegency(){

        $sql = "SELECT COUNT(id) as total_dpt from dpt_kpu";
        return collect(DB::select($sql))->first();
    }
}
