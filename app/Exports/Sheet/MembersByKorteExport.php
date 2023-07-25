<?php

namespace App\Exports\Sheet;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class MembersByKorteExport implements FromCollection, WithTitle,WithHeadings,WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    use Exportable;
    private $villageId;
    private $korteIdx;
    private $name;
    private $rt;

    public function __construct(string $korteIdx, string $name, string $rt , int $villageId){

        $this->villageId = $villageId;
        $this->korteIdx  = $korteIdx;
        $this->name      = $name;
        $this->rt        = $rt;
    }
    public function collection()
    {
        $data = DB::table('org_diagram_rt as a')
                    ->select('a.name','a.telp','b.address')
                    ->join('users as b','a.nik','=','b.nik')
                    ->where('a.base','ANGGOTA')
                    ->where('a.pidx', $this->korteIdx)
                    ->where('a.village_id', $this->villageId)
                    ->orderBy('a.name','asc')
                    ->get();

        $results = [];
        $no      = 1;
        foreach ($data as  $value) {
            $results[] = [
                'no' => $no++,
                'name' => $value->name,
                'address' => $value->address,
                'phone' => $value->telp
            ];
        }

        $results = collect($results);
        return $results;
    }

    public function headings(): array
    {
        return [
            'NO',
            'NAMA',
            'ALAMAT',
            'NO.HP / WA',
        ];
    }
    public function registerEvents(): array
    {
        
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getStyle('A1:D1')->applyFromArray([
                    'font' => [
                        'bold' => true
                    ]
                ]);
            }
        ];
    }

    public function title(): string
    {
        $title = 'RT.'. $this->rt. ' ('.$this->name.')';
        return $title;
    }
}
