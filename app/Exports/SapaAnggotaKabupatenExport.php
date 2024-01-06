<?php

namespace App\Exports;

use App\Event;
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
 
        $eventModel = new Event();
        
        foreach ($districts as $value) {
            $events    = $eventModel->getSapaAnggotaPerKecamatan($value->id, $this->eventCategory);
            $sheets[] = new SapaAnggotaKecamatanExport($events, $value->kecamatan);
        }

        return $sheets;
    }

}
