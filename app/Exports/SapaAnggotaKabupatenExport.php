<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\Exportable;

class SapaAnggotaKabupatenExport implements WithMultipleSheets
{
    use Exportable;
    protected $districts;
    protected $eventCategory;

    public function __construct($districts, int $eventCategory)
    {
        $this->eventCategory = $eventCategory;
        $this->districts = $districts;
    }

    public function sheets(): array
    {

        $sheets   = [];

        $districts = $this->districts;
        
        foreach ($districts as $value) {
            $sheets[] = new SapaAnggotaKecamatanExport($value->id,$this->eventCategory);
        }

        return $sheets;
    }
}
