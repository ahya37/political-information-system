<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ForecastDesc extends Model
{
    protected $table   = 'forecast_desc';
    protected $guarded = [];
    public $timestamps = false;
}
