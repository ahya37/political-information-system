<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\DB;

class ReplaceReferalImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $data)
    {
        // get user_id by code referal
        
        return collect(head($data))
        ->each(function($row, $key){
            DB::table('users')
            ->where('nik', $row[0])
            ->update(['user_id' => $row[1]]);
        });
    }
}
