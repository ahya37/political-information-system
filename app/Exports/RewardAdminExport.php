<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\Village;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\User;

class RewardAdminExport implements FromCollection,WithHeadings, WithEvents
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        $data = $this->data;

        $no   = 1;
        $results = [];

        foreach ($data['data'] as $item) {

            $user   = User::with(['village.district'])->where('id', $item['userId'])->first();
            $results[] = [
                'no' => $no++,
                'name' => $item['name'],
                'address' => "DS.".$user->village->name.", KEC.".$user->village->district->name,
                'referal' => $item['totalInput'],
                'poin' => $item['poin'],
                'nominal' => $item['nominal'],
                'bank' => $item['bank_number']."/".$item['bank_name']."/".$item['bank_owner'],
            ];
        }

        return collect($results);
    }

    public function headings(): array
    {
        return [
            'NO',
            'NAMA',
            'ALAMAT',
            'REFERAL',
            'POIN',
            'NOMINAL',
            'BANK',
            'KETERANGAN',
            'JUMLAH TRANFER'
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getStyle('A1:I1')->applyFromArray([
                    'font' => [
                        'bold' => true
                    ]
                ]);

                // $data = $this->collection();
                // $total = collect($data)->sum(function($q){
                //     return $q['nominal'];
                // });

                // $event->sheet->appendRows(array(
                //     array('Total','','','','',$total),
                // ), $event);
            }
        ];
    }
}
