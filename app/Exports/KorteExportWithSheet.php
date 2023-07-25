<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\Exportable;
use App\Exports\Sheet\MembersByKorteExport;

class KorteExportWithSheet implements WithMultipleSheets
{
    /**
    * @return \Illuminate\Support\Collection
    */
    use Exportable;

    protected $kortes;
    public function __construct($kortes)
    {
        $this->kortes = $kortes;
    }

    public function sheets(): array
    {
        $sheets = [];

        $kortes = $this->kortes;
       

        foreach ($kortes as $value) {
            $sheets[] = new MembersByKorteExport($value->idx,$value->name, $value->rt, $value->village_id);
        }

        return $sheets;
    }
}
