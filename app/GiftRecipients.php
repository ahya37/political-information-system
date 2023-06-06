<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GiftRecipients extends Model
{
    protected $table   = 'gift_recipients';
    protected $guarded = [];

    public function user(){

        return $this->belongsTo(User::class);
    }
}
