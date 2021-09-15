<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;

class DayPerMonthSheet implements FromCollection, WithTitle
{
    /**
    * @return \Illuminate\Support\Collection
    */
    private $year;
    private $month;

    public function __construct($year, $month)
    {
        $this->year = $year;
        $this->month = $month;
    }

    public function collection()
    {
        $daysInMonth = Carbon::parse($this->year.'-'.$this->month)->daysInMonth;
        return collect([
            0 => [range(1, $daysInMonth)]
        ]);
    }

    public function title() : string
    {
        Return "{$this->month} month";
    }
}
