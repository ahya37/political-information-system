<?php

namespace App\Http\Controllers\Admin;

use App\Exports\DptBelumTerdaftarWithSheetExport;
use App\Exports\NewDptExport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Village;
use App\Providers\GlobalProvider;
use Illuminate\Support\Facades\DB;
use PDF;
use File;
use App\OrgDiagram;
use Maatwebsite\Excel\Excel;
use App\Models\Regency;
use App\Models\District;

class DocumentController extends Controller
{
    public $excel;
    public function __construct(Excel $excel)
    {
        $this->excel = $excel;
    }

    public function downloadFormatFormKortps()
    {
        $file = public_path('/docs/util/format-upload-form.xlsx');
        $headers = array(
            'Content-Type:application/vnd.ms-excel',
        );

        return response()->download($file, 'format-upload-form.xlsx', $headers);
    }

    public function downloadKTAMembersByKortps($idx){


        // // get data anggota by korte
        $OrgDiagram = new OrgDiagram();
        $members    = $OrgDiagram->getDataMemberByKorteIdx($idx); 
        
 
        // mengelompokan collection sebanyak 5 data per kelompok
        $group_members = $members->chunk(3);

        $group_members->each(function($chunk){
            $chunk->toArray();
        });
 
        $no = 1;

        $gF = new GlobalProvider();

       $pdf = PDF::LoadView('pages.report.ktamemberbykortps', compact('group_members','gF'))->setPaper('a4','landscape');
        return $pdf->download('KTA.pdf');


   }

    public function downloadKTAKorcamKordes($districtId)
    {

        // get data korcam
        $korcam =  DB::table('org_diagram_district as a')
                ->select('b.rt', 'b.name', 'c.name as village', 'd.name as district', 'b.address', 'a.telp', 'e.tps_number','b.photo',
                    'c.id as village_id','c.name as village','d.id as district_id','d.name as district','e.id as regency_id','g.name as regency',
                    'f.id as province_id','f.name as province','b.number','b.address','b.rw'
                )
                ->join('users as b', 'b.nik', '=', 'a.nik')
                ->join('villages as c', 'c.id', '=', 'b.village_id')
                ->join('districts as d', 'd.id', '=', 'c.district_id')
                ->join('regencies as g','d.regency_id','=','g.id')
                ->join('provinces as f','g.province_id','=','f.id')
                ->leftJoin('tps as e','b.tps_id','=','e.id')
                ->where('a.district_id', $districtId)
                ->get();

        // get data kordes
        $kordes =  DB::table('org_diagram_village as a')
                ->select('b.rt', 'b.name', 'c.name as village', 'd.name as district', 'b.address', 'a.telp', 'e.tps_number','b.photo',
                    'c.id as village_id','c.name as village','d.id as district_id','d.name as district','e.id as regency_id','g.name as regency',
                    'f.id as province_id','f.name as province','b.number','b.address','b.rw'
                )
                ->join('users as b', 'b.nik', '=', 'a.nik')
                ->join('villages as c', 'c.id', '=', 'b.village_id')
                ->join('districts as d', 'd.id', '=', 'c.district_id')
                ->join('regencies as g','d.regency_id','=','g.id')
                ->join('provinces as f','g.province_id','=','f.id')
                ->leftJoin('tps as e','b.tps_id','=','e.id')
                ->where('a.district_id', $districtId)
                ->get();

        // // gabungkan data nya
        $results = $korcam->merge($kordes);


        // mengelompokan collection sebanyak 5 data per kelompok
        $group_members = $results->chunk(3);

        $group_members->each(function($chunk){
            $chunk->toArray();
        });
 
        $no = 1;

        $kecamatan = DB::table('districts')->select('name')->where('id', $districtId)->first();

        $gF = new GlobalProvider();

       $pdf = PDF::LoadView('pages.report.ktamemberbykortps', compact('group_members','gF'))->setPaper('a4','landscape');
        return $pdf->stream('KTA TIM KEC.'.$kecamatan->name.'.pdf');

   }

   public function downloadKTAMembersByKortpsByVillage(Request $request)
   {

        $village = DB::table('villages')
                    ->select('name')->where('id', $request->village_id)->first();

        
        $directory = public_path('/docs/kta/korte/pdf/KTA-ANGGOTA DS.' . $village->name);

        if (File::exists($directory)) {
                File::deleteDirectory($directory); // hapus dir nya juga
            }

        File::makeDirectory(public_path('/docs/kta/korte/pdf/KTA-ANGGOTA DS.' . $village->name));

        // GET DATA KORTE BY DESA
        $korte = DB::table('org_diagram_rt as a')
                    ->select('a.idx')
                    ->join('users as b', 'a.nik', '=', 'b.nik')
                    ->where('a.base', 'KORRT')
                    ->where('a.village_id', $village_id)
                    ->get();

        $OrgDiagram = new OrgDiagram();

        foreach ($korte as $value) {
            
            $path = '/docs/kta/korte/pdf/KTA-ANGGOTA DS.' . $village->name . '/';

            // get data anggota by korte
          
            $members    = $OrgDiagram->getDataMemberByKorteIdx($value->idx);

             // mengelompokan collection sebanyak 5 data per kelompok;
            $group_members = $members->chunk(3);

            $group_members->each(function($chunk){
                $chunk->toArray();
            });
     
            $no = 1;

            $gF = new GlobalProvider();

           $pdf = PDF::LoadView('pages.report.ktamemberbykortps', compact('group_members','gF'))->setPaper('a4','landscape');

           $pdfFilePath = public_path($path.$fileName);
           file_put_contents($pdfFilePath, $pdf->output());

        }

        $files = glob(public_path($path.'*'));
            $createZip = public_path('/docs/kta/korte/pdf/KTA-ANGGOTA DS.' .$village->name  . '.zip');
            Zipper::make(public_path($createZip))->add($files)->close();

        return response()->download(public_path($createZip));

   }

   public function downloadNewDpt(Request $request)
   {
        // get data new dpt per desa, hanya data yg  belum terdaftar di sistem saja
        $data = DB::table('new_dpt as a')
                ->select('a.*', DB::raw('(select count(*) from users where nik = a.NIK) as is_registered'))
                ->where('a.KD_KEL', $request->village_id)
                ->where('a.NO_RT', $request->rt)
                ->having('is_registered',0)
                ->get();

        // $data2 = DB::table('new_dpt as a')
        //         ->select('a.*', DB::raw('(select count(*) from users where nik = a.NIK) as is_registered'))
        //         ->where('a.KD_KEL', $request->village_id)
        //         ->where('a.NO_RT', $request->rt)
        //         ->having('is_registered',1)
        //         ->get();

        

        $village = Village::select('name')->where('id', $request->village_id)->first();

        // implement to excel
        return $this->excel->download(new NewDptExport($data), 'RT-'.$request->rt.' DPT BELUM TERDAFTAR DI SISTEM DS.' . $village->name .'.xls');

   }

   public function dptBelumTerdaftar()
   {
        $regency = Regency::select('id', 'name')->where('id', 3602)->first();

        $authAdminDistrict = auth()->guard('admin')->user()->district_id;
        $district  = District::select('name','id')->where('id', $authAdminDistrict)->first();
        $villages  = Village::select('id','name')->where('district_id', $district->id)->get();

        return view('pages.admin.document.dpt-unregistered', compact('villages', 'district'));


   }

   public function getListDataUnregistered(Request $request)
   {
        $orderBy = 'a.NAMA_LGKP';
        switch ($request->input('order.0.column')) {
            case '0':
                $orderBy = 'a.NIK';
                break;
            case '2':
                $orderBy = 'a.NAMA_LGKP';
                break;
        }

        $data = DB::table('new_dpt as a')
                        ->select('a.NIK as nik','a.NAMA_LGKP as name','villages.name as village',
                            DB::raw('(select count(nik) from users where nik = a.NIK) is_registered')
                        )
                        ->join('villages','villages.id','=','a.KD_KEL')
                        ->having('is_registered',0)
                        ->where('a.KD_KEC',  $request->district);

            
        if($request->input('search.value')!=null){
                $data = $data->where(function($q)use($request){
                    $q->whereRaw('LOWER(a.NAMA_LGKP) like ? ',['%'.strtolower($request->input('search.value')).'%'])
                    ->orWhereRaw('LOWER(villages.name) like ? ',['%'.strtolower($request->input('search.value')).'%'])
                    ->orWhereRaw('LOWER(a.NIK) like ? ',['%'.strtolower($request->input('search.value')).'%']);
                });
            }

        if ($request->input('village') != null) {
                        $data->where('villages.id', $request->village);
            }

        $recordsFiltered = $data->get()->count();
        if($request->input('length')!=-1) $data = $data->skip($request->input('start'))->take($request->input('length'));
        $data = $data->orderBy($orderBy,$request->input('order.0.dir'))->get();
        
        $recordsTotal = $data->count();


      return response()->json([
            'draw'=>$request->input('draw'),
            'recordsTotal'=>$recordsTotal,
            'recordsFiltered'=>$recordsFiltered,
            'data'=> $data
        ]);
   }

   public function DownloadDptBelumTerdaftar(Request $request)
   {
         $village = Village::select('name')->where('id', $request->village_id)->first();
         $OrgDiagram    = new OrgDiagram();

         // get data dpt baru group by rt
         $dpt_villages_rt  = $OrgDiagram->getDptBelumTerdaftarGroupByRt($request->village_id);

         return $this->excel->download(new DptBelumTerdaftarWithSheetExport($dpt_villages_rt,$request->village_id), 'DPT BELUM TERDAFTAR DS.' . $village->name . '.xls');
   }

}
