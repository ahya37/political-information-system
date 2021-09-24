<?php

namespace App\Exports;

use App\Job;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;

class JobProvince implements FromCollection, WithHeadings, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    use Exportable;

    public $province;

    public function __construct(int $province)
    {
        $this->province = $province;
    }

    public function collection()
    {
        $province_id = $this->province;
        $jobModel  = new Job();
        $jobs      = $jobModel->getJobProvince($province_id);
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
