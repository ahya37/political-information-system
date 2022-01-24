<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CostExport implements FromCollection, WithHeadings, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    use Exportable;

    public $start;
    public $end;

    public function __construct($start, $end)
    {
        $this->start = $start;
        $this->end = $end;
    }

    public function collection()
    {
            $start = $this->start;
            $end = $this->end;

            $sql = "SELECT a.file, a.date, a.received_name, a.nominal, b.name as forcest, c.name as forecast_desc,
                e.name as village , f.name as district , g.name as regency 
                from cost_les as a
                join forecast as b on a.forcest_id = b.id 
                join forecast_desc as c on a.forecast_desc_id = c.id 
                join villages as e on a.village_id = e.id 
                join districts as f on e.district_id = f.id 
                join regencies as g on f.regency_id = g.id 
                where a.date  BETWEEN  '$start' and '$end' order by a.date desc ";
        $result = DB::select($sql);

        $data = [];
        $no = 1;
        foreach($result as $val){
            $data[] = [
                'no' => $no++,
                'date' => date('d-m-Y', strtotime($val->date)),
                'forecast' => $val->forcest,
                'forecast_desc' => $val->forecast_desc,
                'received_name' => $val->received_name,
                'address' => $val->village.', '.$val->district.', '.$val->regency,
                'nominal' => $val->nominal
            ];
        }

        return collect($data);

    }

    public function headings(): array
    {
        return [
            'NO',
            'TANGGAL',
            'PERKIRAAN',
            'URAIAN',
            'PENERIMA',
            'ALAMAT',
            'JUMLAH',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getStyle('A1:G1')->applyFromArray([
                    'font' => [
                        'bold' => true
                    ]
                ]);
            }
        ];
    }
}
