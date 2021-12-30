<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Models\Village;

class DetailFigure extends Model
{
    protected $table = 'detail_figure';
    protected $guarded = [];

    public function village()
    {
        return $this->belongsTo(Village::class,'village_id');
    }

    public function figure()
    {
        return $this->belongsTo(Figure::class,'figure_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'create_by');
    }
}
