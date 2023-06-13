<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class FamilyGroup extends Model
{
    protected $table    = 'family_group';
    protected  $guarded = [];

    public function user(){

        return $this->belongsTo(User::class);
    }

    
    public function getDataFamilyGroups(){

        $sql = DB::table('family_group as a')
               ->join('users as b','a.user_id','=','b.id')
               ->select('a.id','b.name','a.notes')
               ->orderBy('b.name','asc')
               ->get();

        return $sql;
    }

    public function getDataFamilyGroup($id){

        $sql = DB::table('family_group as a')
               ->join('users as b','a.user_id','=','b.id')
               ->select('a.id','b.name')
               ->where('a.id', $id)
               ->first();

        return $sql;
    }

}
