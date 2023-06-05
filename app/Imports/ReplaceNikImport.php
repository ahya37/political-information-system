<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\DB;

class ReplaceNikImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $data)
    {
        return collect(head($data))
        ->each(function($row, $key){
            DB::table('users')
            ->where('nik', $row[0])
            ->update(['nik' => $row[1]]);

            // $model = ExampleTableCsv::where('nik', $row[0])->first();
            // $model->update([
            //         'title' => $row[1]
            //     ]);
        });
    }
}
