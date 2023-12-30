<?php

namespace App\Exports;

use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Support\Facades\DB;

class AnggotaBelumTercoverKortps implements FromCollection,  WithHeadings, WithEvents, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    use Exportable;
    protected $data;
    protected $villageid;

    public function __construct(array $data, int $villageid)
    {
        $this->data = $data;
        $this->villageid = $villageid;

    }

    public function collection()
    {
        $data = $this->data;

        $results = [];
        $no = 1;
        foreach ($data as $value) {
            $referal = DB::table('users as a')
                        ->join('villages as b','a.village_id','=','b.id')
                        ->where('user_id', $value->id)
                        ->count();
            $results[] = [
                'no'   => $no++,
                'nik'  => "'".$value->nik ?? '',
                'name' => $value->name ?? '',
                // 'desa' => $value->desa ?? '',
                'rt' => $value->rt ?? '',
                'rw' => $value->rw,
                'referal' => $referal ?? 0,
                'address' => $value->address,
                'referal_dari' => $value->referal_dari
                // 'village' => $value->desa,
                // 'district' => $value->district,
                // 'telp'    => $value->phone_number,
                // 'wa' => $value->whatsapp,
                // 'referal' => $value->referal,
                // 'created_at' => date('d-m-Y', strtotime($value->created_at)),
                // 'cby' => $value->cby,
            ];
        }
        return collect($results);
    }

    public function headings(): array
    {
        return [
            'NO',
            'NIK',
            'NAMA',
            // 'DESA',
            'RT',
            'RW',
            'REFERAL',
            'ALAMAT',
            'REFERAL DARI'
            // 'DESA',
            // 'KECAMATAN',
            // 'NO.TELP',
            // 'WHATSAPP',
            // 'REFERAL',
            // 'INPUT TANGGAL',
            // 'INPUT OLEH'
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class =>  function (AfterSheet $event){

                $event->sheet->getDelegate()->getColumnDimension('A')->setAutoSize(true);
                $event->sheet->getDelegate()->getColumnDimension('B')->setAutoSize(true);
                $event->sheet->getDelegate()->getColumnDimension('C')->setAutoSize(true);
                $event->sheet->getDelegate()->getColumnDimension('D')->setAutoSize(true);
                $event->sheet->getDelegate()->getColumnDimension('E')->setAutoSize(true);
                $event->sheet->getDelegate()->getColumnDimension('F')->setAutoSize(true);
                $event->sheet->getDelegate()->getColumnDimension('G')->setAutoSize(true);
              

                $event->sheet->getStyle('A1:H1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ]
                ]);

                $event->sheet->appendRows(array(
                    array(' '),
                ), $event);

                $data = $this->collection();

                $villageid = $this->villageid;

                $resultGroup = collect($data)->groupBy('rt')->count();


                $listJumlahPerRt = collect($data);

                $event->sheet->appendRows(array(
                    array('','KETERANGAN :'),
                ), $event);

                $event->sheet->appendRows(array(
                    array('',$resultGroup.' RT Belum Tercover'),
                ), $event);

                $getJumlahPerRt = DB::select("SELECT a.rt, COUNT(a.nik) as jumlah_per_rt
                                    from users as a 
                                    join villages as b on a.village_id = b.id
                                    WHERE b.id  = $villageid and (SELECT COUNT(id) from org_diagram_rt 
                                    WHERE nik = a.nik and base = 'ANGGOTA' ) = 0
                                    and (SELECT COUNT(id) from org_diagram_rt WHERE nik = a.nik and base = 'KORRT' )   = 0
                                    and (SELECT COUNT(id) from org_diagram_village where nik = a.nik) = 0 
                                    group by a.rt
                                    ORDER BY a.rt asc");

                foreach ($getJumlahPerRt as  $value) {
                    $event->sheet->appendRows(array(
                    array('','RT '.$value->rt.' ', $value->jumlah_per_rt.' Orang'),
                    ), $event);

                }
            }
        ] ;  
    }
}
