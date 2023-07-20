<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Support\Facades\DB;

class KorteMembersExport implements FromCollection,  WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */

    use Exportable;

    protected $idx;

    public function __construct(string $idx){

        $this->idx = $idx;


    }
    public function collection()
    {
        $members = DB::table('org_diagram_rt as a')
                ->select('b.address','a.nik','a.name','a.telp as phone_number')
                ->leftJoin('users as b','b.nik','=','a.nik')
                ->where('pidx', $this->idx)
                ->get();

        $results = [];
        $no = 1;

        foreach ($members as $value) {
            $results[] = [
                'no' => $no++,
                'name' => $value->name,
                'address' => $value->address,
                'phone' => $value->phone_number
            ];    
        }

        $data = collect($results);
        return $data;
        
    }

    public function headings(): array
    {
        return [
            'NO',
            'NAMA',
            'ALAMAT',
            'NO.HP / WA'
        ];
    }
}
