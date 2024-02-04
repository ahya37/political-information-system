<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\Exportable;
use App\Exports\AnggaranTimSheetExport;
use App\Exports\AnggaranTimRekapDapilSheet;
use Maatwebsite\Excel\Facades\Excel;

class AnggaranTimExport implements WithMultipleSheets
{
    /**
    * @return \Illuminate\Support\Collection
    */
    use Exportable;

    protected $districts;
    protected $dapilId;
    public function __construct($districts,$dapilId) 
    {
        $this->districts = $districts;
        $this->dapilId   = $dapilId;
    }

    public function sheets(): array
    {
        $sheets = [];

        $data_districts = $this->districts;
		
		$data = [
			'no' => 1
		];
		
		
       
        foreach ($data_districts as $value) {
            $sheets[] = new AnggaranTimSheetExport($value->id, $value->name);
			// Excel::append(new AnggaranTimSheetExport($value->id, $value->name), $data, 'KEC.'.$value->name);
        }
		 
		// SHEET REKAPAN
		$sheets[] = new AnggaranTimRekapDapilSheet($this->dapilId);

        return $sheets;
    }

	
}
	