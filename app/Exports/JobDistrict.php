<?php

namespace App\Exports;

use App\Job;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;

class JobDistrict implements FromCollection, WithHeadings, WithEvents
{
    use Exportable;
    private $district;
    
    public function __construct(int $district)
    {
        $this->district = $district;
    }

    public function collection()
    {
        $district_id = $this->district;
        $jobModel  = new Job();
        $jobs      = $jobModel->getJobDistrict($district_id);
        $result    = collect($jobs);
        return $result;
    }

    public function headings(): array
    {
        return [
            'PROFESI',
            'JUMLAH'
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getStyle('A1:B1')->applyFromArray([
                    'font' => [
                        'bold' => true
                    ]
                ]);
            }
        ];
    }
}
