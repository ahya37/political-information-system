<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Caleg extends Model
{
    protected $table = 'dapil_calegs';
    public $timestamps = false;
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
