<?php

namespace App\Imports;

use App\Koordinator;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;

class KoordinatorImport implements ToModel, WithStartRow, WithCustomCsvSettings
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    public function startRow(): int {
        return 2;
    }

    public function getCsvSettings(): array {
        return [
            'delimiter' => ','
        ];
    }


    public function model(array $row)
    {
        return new Koordinator([
            'rt' => $row[0],
            'rw' => $row[1],
            'name' => $row[2],
            'dapil_id' => $row[3],
            'district_id' => $row[4],
            'village_id' => $row[5],
            'phone_number' => $row[6],
            'address' => $row[7],
            'recomender_user_id' => $row[8],
        ]);
    }
}
