<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Illuminate\Support\Facades\DB;
use App\Helpers\CountAnggaran;
use Maatwebsite\Excel\Events\AfterSheet;
use App\Providers\GlobalProvider;

class AnggaranTimSheetExport implements FromCollection, WithHeadings,WithTitle,WithEvents,ShouldAutoSize
{
   use Exportable;

    protected $id;
    protected $name;

    public function __construct(int $id, string $name){

        $this->id   = $id;
        $this->name = $name;


    }
    public function collection()
    {
		$members = DB::table('villages as a')
				   ->select('a.id','a.name',
						DB::raw('
							(
								SELECT COUNT(b1.nik) from org_diagram_village  as b1 
								join users as b2 on b1.nik = b2.nik
								WHERE b1.village_id  = a.id
							) as kordes
						'),
						DB::raw("
							(
								SELECT COUNT(c1.nik) from org_diagram_rt  as c1 
								join users as c2 on c1.nik = c2.nik
								WHERE c1.village_id  = a.id and c1.base = 'KORRT'
							) as korte
						")
				   )
				   ->where('a.district_id', $this->id)
				   ->get();
				   
        $results = [];
        $no = 1; 

        foreach ($members as $value) {
            $results[] = [
                'no' => $no++,
                'desa' => $value->name, 
				'kordes' => $value->kordes,
				'korte' => $value->korte
            ];    
        }

        $data = collect($results);
        return $data;
        
    }

    public function headings(): array
    {
        return [
            'NO',
			'DESA',
			'KORDES',
			'KORTE'
        ];
    }
	
	public function registerEvents(): array
    {
        
        return [
            AfterSheet::class => function (AfterSheet $event) {
				
				$event->sheet->getDelegate()->getColumnDimension('A')->setAutoSize(true);
                $event->sheet->getDelegate()->getColumnDimension('B')->setAutoSize(true);
                $event->sheet->getDelegate()->getColumnDimension('C')->setAutoSize(true);
                $event->sheet->getDelegate()->getColumnDimension('D')->setAutoSize(true);
				
                $event->sheet->getStyle('A1:D1')->applyFromArray([
                    'font' => [
                        'bold' => true
                    ]
                ]);
				
				$data = $this->collection();
				$gf   = new GlobalProvider();
				
				$sum_kordes = collect($data)->sum(function($q){
					return $q['kordes'];
				});
				
				$sum_korte = collect($data)->sum(function($q){
					return $q['korte'];
				});
				
				$event->sheet->appendRows(array(
                    array('','JUMLAH',$sum_kordes,$sum_korte),
                ), $event); 
				
				$event->sheet->appendRows(array(
                    array('','NOMINAL',$sum_kordes * CountAnggaran::kordes(),$sum_korte * CountAnggaran::korte()),
                ), $event); 
				
            }  
			 
			
        ];
    }
	
	public function title(): string
    {
        $title = 'KEC.'. $this->name;
        return $title;
    }
}
