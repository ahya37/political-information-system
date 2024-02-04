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

class AnggaranTimRekapDapilSheet implements FromCollection, WithHeadings,WithTitle,WithEvents,ShouldAutoSize
{
  use Exportable;

    protected $dapilId;

    public function __construct(int $dapilId){

        $this->dapilId   = $dapilId;

    }
    public function collection()
    {
		$members = DB::table('dapil_areas as a')
				   ->select('b.id','b.name',
						DB::raw('
							(
								SELECT COUNT(a1.nik) from org_diagram_district as a1 
								join users as a2 on a1.nik = a2.nik
								WHERE a1.district_id = b.id 
							) as korcam
						'),
						DB::raw('
							(
								SELECT COUNT(b1.nik) from org_diagram_village  as b1 
								join users as b2 on b1.nik = b2.nik
								WHERE b1.district_id = b.id
							) as kordes
						'),
						DB::raw("
							(
								SELECT COUNT(c1.nik) from org_diagram_rt  as c1 
								join users as c2 on c1.nik = c2.nik
								join villages as c3 on c1.village_id = c3.id
								WHERE c3.district_id = b.id and c1.base = 'KORRT'
							) as korte
						")
				   )
				   ->join('districts as b','a.district_id','=','b.id')
				   ->where('a.dapil_id', $this->dapilId)
				   ->get(); 
				    
        $results = []; 
        $no = 1; 

        foreach ($members as $value) {
            $results[] = [
                'no' => $no++,
                'kecamatan' => $value->name, 
				'korcam' => $value->korcam,
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
			'KECAMATAN',
			'KORCAM',
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
                $event->sheet->getDelegate()->getColumnDimension('E')->setAutoSize(true);
				
                $event->sheet->getStyle('A1:E1')->applyFromArray([
                    'font' => [
                        'bold' => true
                    ]
                ]);
				
				 $event->sheet->appendRows(array(
                    array(' ',' '),
                ), $event);
				
				$data = $this->collection();
				
				$sum_korcam = collect($data)->sum(function($q){
					return $q['korcam'] * CountAnggaran::korcam();
				}); 
				
				$sum_kordes = collect($data)->sum(function($q){
					return $q['kordes'] * CountAnggaran::kordes();
				});
				
				$sum_korte = collect($data)->sum(function($q){
					return $q['korte'] * CountAnggaran::korte();
				});
				
				$event->sheet->appendRows(array(
                    array('','JUMLAH',$sum_korcam,$sum_kordes,$sum_korte),
                ), $event); 
				 
            }
			
			
        ];
    }
	
	public function title(): string
    {
        $title = 'REKAP DAPIL';
        return $title;
    }
}
