<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

use Maatwebsite\Excel\Concerns\ToCollection;

class ReplaceAddressImport implements ToCollection
{
    /**
    * @param Collection $collection
    */

    public function collection(Collection $data)
    {
        // $model = new ExampleTableCsv();
        // return $model->where('nik', $row[1])->first()->update([
        //     'title' => $row[1]
        // ]);

        return collect(head($data))
        ->each(function($row, $key){
            DB::table('users')
            ->where('nik', $row[0])
            ->update(['address' => $row[1]]);

            // $model = ExampleTableCsv::where('nik', $row[0])->first();
            // $model->update([
            //         'title' => $row[1]
            //     ]);
        });
    }
}
