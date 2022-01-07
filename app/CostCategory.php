<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CostCategory extends Model
{
    protected $table = 'cost_category';
    public $timestamps = false;
    protected $guarded = [];
}
