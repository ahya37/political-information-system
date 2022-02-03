<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    protected $table = 'member_bank';
    public $timestamps = false;
    protected $guarded = [];
}
