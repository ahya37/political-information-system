<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Models\Village;
use Illuminate\Support\Facades\DB;

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

    public function getFigureVillage($village_id)
    {
        $sql = "SELECT a.name, a.politic_potential, c.choose,
                SUM(a.politic_potential / c.choose ) * 100 as total_data 
                from detail_figure as a 
                join villages as b on a.village_id = b.id
                left join right_to_choose_village as c on b.id = c.village_id
                where b.id  = $village_id
                GROUP  BY a.name, a.politic_potential, c.choose";
        return DB::select($sql);
    }

    public function getProfesiFigureVillage($village_id)
    {
        $sql = "SELECT a.name, count(b.figure_id) as total_job from figure as a
                join detail_figure as b on a.id = b.figure_id
                where b.village_id = $village_id
                group by a.name";
        return DB::select($sql);
    }
}
