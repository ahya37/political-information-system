<?php

namespace App\Exports;

use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use DB;

class KorCamExport implements FromCollection, WithHeadings, WithEvents, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */

    use Exportable;

    protected $districtid;

    public function __construct(int $district)
    {
        $this->districtid = $district;

    }

    public function collection()
    {
        $district_id  =  $this->districtid;

        $data = DB::table('org_diagram_district as a')->select('a.name','a.base','a.title','b.gender','d.name as district')
                    ->join('users as b','a.nik','=','b.nik')
                    ->join('districts as d','a.district_id','=','d.id')
                    ->where('a.district_id', $district_id)
                    ->orderBy('a.level_org','asc')
                    ->get();

        $results = [];
        $no      = 1;
        foreach ($data as $value) {
            $results[] = [
                'no' => $no++,
                'name' => $value->name,
                'jk' => $value->gender == 1 ? 'P' : 'L',
                'title' => $value->base == 'KORCAM' ? $value->title : $value->base,
                'district' => $value->district 
            ];
        }
            
        $result = collect($results);
        return $result;

    }

    public function headings(): array
    {
        return [
            'NO',
            'NAMA',
            'JENIS KELAMIN',
            'JABATAN',
            'KECAMATAN',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                
                // $event->sheet->getDelegate()->getRowDimension('2')->setRowHeight(40);
                $event->sheet->getDelegate()->getColumnDimension('B')->setAutoSize(true);
                $event->sheet->getDelegate()->getColumnDimension('C')->setAutoSize(true);
                $event->sheet->getDelegate()->getColumnDimension('D')->setAutoSize(true);
                $event->sheet->getDelegate()->getColumnDimension('E')->setAutoSize(true);

                $event->sheet->getStyle('A1:E1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ]
                ]);

                $data = $this->collection()->where('jk','L');
                $total_L = collect($data)->count();

                $event->sheet->appendRows(array(
                    array('','JUMLAH LAKI','',$total_L),
                ), $event);
                
                $data2 = $this->collection()->where('jk','P');
                $total_P = collect($data2)->count();

                $event->sheet->appendRows(array(
                    array('','JUMLAH PEREMPUAN','',$total_P),
                ), $event);
            }
        ];
    }
}
