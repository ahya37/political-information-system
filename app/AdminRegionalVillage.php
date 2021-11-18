<?php

namespace App;

use App\Models\Village;
use Illuminate\Database\Eloquent\Model;

class AdminRegionalVillage extends Model
{
    protected $table = 'admin_regional_village';
    protected $guarded = [];
    public $timestamps = false;

    public function village()
    {
        return $this->belongsTo(Village::class,'village_id');
    }
}
