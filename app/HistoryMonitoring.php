<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HistoryMonitoring extends Model
{
    protected $table = 'history_monitoring';
    protected $guarded = [];

    public function user(){

        return $this->belongsTo(User::class);
        
    }
}
