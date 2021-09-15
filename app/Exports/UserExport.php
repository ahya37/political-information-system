<?php

namespace App\Exports;

use App\Models\Regency;
use App\User;
use DateTime;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class UserExport implements  
    ShouldAutoSize,
    WithMapping,
    WithHeadings,
    WithEvents,
    FromQuery,
    WithCustomStartCell,
    WithTitle
{
    /**
    * @return \Illuminate\Support\Collection
    */
    use Exportable;

    private $year;
    private $month;

    public function __construct(int $year, int $month)
    {
        $this->year = $year;
        $this->month = $month;
    }
    public function query()
    {
        return User::query()->whereYear('created_at', $this->year)->whereMonth('created_at', $this->month);
    }

     public function map($user): array
    {
        return [
            $user->id,
            $user->name,
            $user->email,
            $user->created_at
        ];
    }

    public function headings(): array
    {
        return [
            '#',
            'Name',
            'Email',
            'Created At'
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getStyle('A8:D8')->applyFromArray([
                    'font' => [
                        'bold' => true
                    ]
                ]);
            }
        ];
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('This is my logo');
        $drawing->setPath(public_path('assets/images/logos.png'));
        $drawing->setHeight(90);
        $drawing->setCoordinates('B2');

        return $drawing;
    }

    public function startCell(): string
    {
        return 'A1';
    }

    public function title(): array
    {
        // return DateTime::createFromFormat('!m', $this->month)->format('F');
        $title = [];
        $datas = Regency::where('province_id', 36)->get();
        foreach ($datas as $key => $value) {
            $title[] = $value->name;
        }
        return $title;
    }
}