<?php

namespace App\Providers;

use App\Models\District;
use App\Exports\MemberExportDistrict;
use Illuminate\Support\ServiceProvider;
use Maatwebsite\Excel\Excel;

class ExportDataProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */

    private $excel;

    public function __construct(Excel $excel)
    {
        $this->excel = $excel;
    }
    
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    public function getExportDataDistrictExcel($district_id)
    {
      $district = District::select('name')->where('id', $district_id)->first();
      return $this->excel->download(new MemberExportDistrict($district_id),'Anggota-'.$district->name.'.xls');
    }
}
