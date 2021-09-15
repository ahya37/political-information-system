<?php

namespace App\Http\Controllers;

// use App\Exports\DateExport;

use App\Exports\MemberExportProvince;
use App\Exports\UserExport;
use App\Exports\UserExportMulti;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;

class SampleController extends Controller
{
    private $excel;

    public function __construct(Excel $excel)
    {
      $this->excel = $excel;
    }
    public function export()
    {
      $province = 36;
      // return (new DateExport('2019'))->download('date.xlsx');   
      return $this->excel->download(new MemberExportProvince($province),'users.xls');
    }
}
