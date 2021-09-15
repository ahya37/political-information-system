<?php

namespace App\Exports;

use App\User;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class DateExport implements WithMultipleSheets
{
    /**
    * @return \Illuminate\Support\Collection
    */
    use Exportable;

    private $year;

    public function __construct($year)
    {
        $this->year = $year;
    }

    public function sheets():array
    {
        $sheets = [];

        for ($month=0; $month <= 12 ; $month++) { 
            $sheets[] = new DayPerMonthSheet($this->year, $month);
        }

        return $sheets;
    }
}
