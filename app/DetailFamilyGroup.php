<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DetailFamilyGroup extends Model
{
    protected $table = 'detail_family_group';
    protected $guarded = [];

    public function getMemberByFamilyGroupId($id){

        $sql = DB::table('detail_family_group as a')
               ->join('users as b','a.user_id','=','b.id')
               ->select('a.id','b.name','a.notes')
               ->where('a.family_group_id', $id)
               ->orderBy('b.name','asc')
               ->get();

        return $sql;
    }
}
