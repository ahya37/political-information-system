<?php

namespace App\Imports;

use App\IntelegensiPolitik;
use Maatwebsite\Excel\Concerns\ToModel;

class IntelegensiPolitikImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new IntelegensiPolitik([
            'name' => $row[1],
            'village_id' =>(int) $row[2],
            'rt' => (int) $row[3],
            'address' => $row[4],
            'profession' => $row[5],
            'politic_potential' => (int) $row[7],
            'no_telp' => $row[10],
            'once_served' => $row[9],
            'politic_name' => $row[12],
            'politic_year' => $row[14],
            'politic_status' => $row[16],
            'politic_member' => $row[18],
            'descr' => $row[20],
            'resource_information' => $row[22],
            'ismember' => $row[24],
            'create_by' => null,
        ]);


    }
}
