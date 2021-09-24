<?php

namespace App\Exports;

use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\DB;

class JobNational implements FromCollection,  WithHeadings, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    use Exportable;

    public function collection()
    {
        $sql = "SELECT e.name, COUNT(e.name) as total_job
                FROM users as d
                join jobs as e on d.job_id = e.id
                GROUP by e.name order by  COUNT(e.name) desc";
        $result = collect(\ DB::select($sql));
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
