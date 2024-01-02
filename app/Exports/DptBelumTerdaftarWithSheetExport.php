<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\Exportable;
use stdClass;

class DptBelumTerdaftarWithSheetExport implements WithMultipleSheets
{
    use Exportable;

    protected $rt;
    protected $villageId;

    public function __construct($rt, int $villageId) 
    {
        $this->rt = $rt;
        $this->villageId = $villageId;
    }

    public function sheets(): array
    {

        $sheets   = [];

        $list_rt = $this->rt;

        // membuat sheet pertama untuk rekap data
        $sheets[] = new RekapDptBelumTerdaftarExport($this->villageId);
        
        foreach ($list_rt as $value) {
            $sheets[] = new DptBelumTerdaftarExport($value->KD_KEL,$value->NO_RT);
        }

        return $sheets;
    }

}
