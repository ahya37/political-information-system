<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use DB;

class checkNikImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        // $data =  collect(head($collection))
        //     ->each(function($row, $key){
        //         DB::table('users')
        //         ->where('nik', $row[0])->get();
        //     });
        // $results = [];
        // foreach ($collection as $value) {
        //     $results[] = [
        //         'data' => DB::users('name')->where('nik', $value)->first()
        //     ];
        // }

        // return 'ok';
    }
}
