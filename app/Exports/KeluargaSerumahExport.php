<?php

namespace App\Exports;

use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class KeluargaSerumahExport implements FromCollection, WithHeadings, WithEvents, ShouldAutoSize
{
    use Exportable;

    protected $data;
	
	public function __construct($data)
    {
        $this->data = $data;
    } 
	
    public function collection()
    {
        return collect($this->data); 
    }
	
	public function headings(): array
    {
        return [
            'NO',
            'DESA',
            'KELUARGA SERUMAH'
        ];
    }
	
	public function registerEvents(): array
    {
        return [
		
            AfterSheet::class =>  function (AfterSheet $event){

                $event->sheet->getDelegate()->getColumnDimension('A')->setAutoSize(true);
                $event->sheet->getDelegate()->getColumnDimension('B')->setAutoSize(true);
                $event->sheet->getDelegate()->getColumnDimension('C')->setAutoSize(true);
              
                $event->sheet->getStyle('A1:c1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ]
                ]);
				
				$sumKeluargaSerumah = collect($this->data)->sum(function($q){
					return $q['jml_keluarga_serumah'];
				});
				
				$event->sheet->appendRows(array(
                    array(' ','JUMLAH',$sumKeluargaSerumah),
                ), $event);
            }
        ];  
    }
}
