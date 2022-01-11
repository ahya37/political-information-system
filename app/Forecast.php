<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Forecast extends Model
{
    protected $table   = 'forecast';
    protected $guarded = [];
    public $timestamps = false;
}
