<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AdminDapilVillage extends Model
{
    protected $table = 'admin_dapil_village';
    protected $guarded = [];
    public $timestamps = false;
}
