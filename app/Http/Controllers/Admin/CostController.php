<?php

namespace App\Http\Controllers\Admin;

use App\Cost;
use App\CostLess;
use App\Exports\CostExport;
use App\Forecast;
use App\ForecastDesc;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Village;
use App\CostFiles;
use App\Providers\GlobalProvider;
use Carbon\Carbon;
use PDF;
use Maatwebsite\Excel\Excel;
use Illuminate\Support\Facades\DB;
use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\File;

class CostController extends Controller
{
    public $excel;
    public function __construct(Excel $excel)
    {
        $this->excel = $excel;
    }

    public function create()
    {
        $forecast = Forecast::orderBy('name','desc')->get();
        $forecast_desc = ForecastDesc::orderBy('name','desc')->get();
        $regency  = 3602;
        return view('pages.admin.cost.create', compact('forecast','forecast_desc','regency'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required',
            'forecast_id' => 'required',
            'forecast_desc_id' => 'required',
            'received_name' => 'required',
            'nominal' => 'required',
            'file' => 'nullable|mimes:jpeg,jpg,png,pdf'
        ]);

       if ($request->hasFile('file')) {
                $fileImage = $request->file->store('assets/cost','public');
            }else{
                $fileImage = 'NULL';
            }

        //
        
        if($request->village_id == ''){
            $address = $request->address;
        }else{
            $village = Village::with(['district.regency'])->where('id', $request->village_id)->first();
            $address = 'DS. '. $village->name. ', KEC. ' .$village->district->name. ', '. $village->district->regency->name;
        }
        
        CostLess::create([
            'date' => date('Y-m-d', strtotime($request->date)),
            'forcest_id' => $request->forecast_id,
            'forecast_desc_id' => $request->forecast_desc_id,
            'received_name' => $request->received_name,
            'address' => $address,
            'village_id' => $request->village_id,
            'rt' => $request->rt,
            'nominal' => $request->nominal,
            'file' => $fileImage,
        ]);

        return redirect()->back()->with(['success' => 'Pengeluaran telah tersimpan']);
    }

    public function addForecast(Request $request)
    {
        Forecast::create([
            'name' => strtoupper($request->name)
        ]);

        return redirect()->back()->with(['success' => 'Perkiaraan telah tersimpan']);
    }

    public function addForecastDesc(Request $request)
    {
        ForecastDesc::create([
            'name' => strtoupper($request->name)
        ]);

        return redirect()->back()->with(['success' => 'Uraian telah tersimpan']);
    }

    public function listCostPolitic()
    {
         $costModel = new Cost();
         $cost      = $costModel->getDataCost();
         $total     = collect($cost)->sum(function($q){
             return $q->nominal;
         });

         $gF = new GlobalProvider();
         $no = 1;

         if (request('date') != '') {
             $daterange =  request('date');
             $date  = explode('+', $daterange);

             $start = Carbon::parse($date[0])->format('Y-m-d');
             $end   = Carbon::parse($date[1])->format('Y-m-d');

              $cost     = $costModel->getDataCostRange($start, $end);

              $total     = collect($cost)->sum(function($q){
                    return $q->nominal;
                });
            }


        return view('pages.admin.cost.index', compact('cost','gF','no','total'));
    }

    public function getDataCost()
    {
        $costModel = new Cost();
        $cost      = $costModel->getDataCost();
        $data = [];

        foreach($cost as $val){
            $data[] = array(
                'date' => $val->date,
                'forecast' => $val->forcest,
                'forecast_desc' => $val->forecast_desc,
                'village_id' => $val->village_id,
                'member' => $val->member,
            );
        }

        return response()->json($data);
    }

    public function downloadPDF($daterange)
    {
             $gF = new GlobalProvider();
             $date  = explode('+', $daterange);

             $start = Carbon::parse($date[0])->format('Y-m-d');
             $end   = Carbon::parse($date[1])->format('Y-m-d');
             
            $costModel = new Cost();

            $cost     = $costModel->getDataCostRange($start, $end);
            $no = 1;

            $total     = collect($cost)->sum(function($q){
                    return $q->nominal;
                });
            $date_report = date('d-m-Y', strtotime($start)) .' - '. date('d-m-Y', strtotime($end));

        $pdf = PDF::LoadView('pages.admin.report.cost-politic', compact('cost','gF','date_report','no','total'))->setPaper('landscape');;
        return $pdf->download('LAPORAN COST POLITIC '.$date_report.'.pdf');
             
    }

    public function downloadExcel($daterange)
    {
             $gF = new GlobalProvider();
             $date  = explode('+', $daterange);

             $start = Carbon::parse($date[0])->format('Y-m-d');
             $end   = Carbon::parse($date[1])->format('Y-m-d');
             
            $date_report = date('d-m-Y', strtotime($start)) .' - '. date('d-m-Y', strtotime($end));

        return $this->excel->download(new CostExport($start, $end), 'LAPORAN COST POLITIK '.$date_report.'.xls');
             
    }

    public function edit($id)
    {
        $forecast = Forecast::orderBy('name','desc')->get();
        $forecast_desc = ForecastDesc::orderBy('name','desc')->get();
        $cost = CostLess::where('id', $id)->first();
        return view('pages.admin.cost.edit', compact('forecast','forecast_desc','id','cost'));
    }

    public function listFiles($id)
    {
        $files = CostFiles::where('cost_les_id', $id)->get();
        $no    = 1;

        return view('pages.admin.cost.files', compact('files','id','no'));
    }

    public function update(Request $request, $id)
    {

        $cost = CostLess::where('id', $id)->first();
        if ($request->hasFile('file')) {
                $fileImage = $request->file->store('assets/cost','public');
            }else{
                $fileImage = 'NULL';
            }

        //
        $address = '';
        if ($request->village_id == null ) {
            $address = $cost->address;
        }else{
            $village = Village::with(['district.regency'])->where('id', $request->village_id)->first();
            $address = 'DS. '. $village->name. ', KEC. ' .$village->district->name. ', '. $village->district->regency->name;
        }
        
        $cost->update([
            'date' => date('Y-m-d', strtotime($request->date)),
            'forcest_id' => $request->forecast_id,
            'forecast_desc_id' => $request->forecast_desc_id,
            'received_name' => $request->received_name,
            'address' => $address,
            'nominal' => $request->nominal,
            'file' => $fileImage,
        ]);

        return redirect()->route('admin-cost-index')->with(['success' => 'Pengeluaran telah diubah']);
    }

    public function uploadFile(Request $request, $id)
    {
        
        $this->validate($request, [
               'file' => 'required',
        ]);

        $name  = $request->file('file')->getClientOriginalName();
        $ext   = $request->file('file')->getClientOriginalExtension();

        $file  = $request->file('file')->store('assets/cost','public');

        CostFiles::create([
            'cost_les_id' => $id,
            'name'  => $name,
            'file'  => $file,
            'type'  =>  $ext,
            'cby'   => auth()->guard('admin')->user()->id
        ]);

        return redirect()->back()->with(['success' => 'File telah disimpan!']);

    }

    public function downloadFileCost($id){


        #get file by id
        $data = CostFiles::select('file','name')->where('id', $id)->first();

        $file = storage_path('app').'/public/'.$data->file;

        if ($file) {
            $headers = array(
                'Content-Type:aplication/pdf',
            );
    
            return response()->download($file, $data->name, $headers);
        }

        return redirect()->back()->with(['error' => 'Tidak ada file!']);

    }

    public function deleteFile(){

        DB::beginTransaction();
        try {

            $id    = request()->id;

            $data = CostFiles::where('id', $id)->first();

            #delete file in directory
            $file = storage_path('app').'/public/'.$data->file;
            if (file_exists($file)) {
                File::delete($file);
            }

            #delete file in db
            $data->delete();

            DB::commit();
            return ResponseFormatter::success([
                   'message' => 'Berhasil hapus catatan!'
            ],200);

        } catch (\Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'message' => 'Something when wrong!',
                'error' => $e->getMessage()
            ]);
        }

    }

    public function updateVillageCost(){

        DB::beginTransaction();
        try {

            $data = CostLess::select('address','id')->where('address','!=','')->orderBy('address','asc')->get();

            $address = [];
            foreach ($data as $key => $value) {

                $village1 = explode(".", $value->address);
                $village1 = $village1[1];

                $village2 = explode(",", $village1);
                $village2 = $village2[0];
                $resVillage = trim($village2);

                #query untuk get village_id dari table villages yang di relasi dengan district where regency_id = 3602
                $results = DB::table('villages as a')
                        ->join('districts as b','a.district_id','=','b.id')
                        ->where('b.regency_id', 3602)
                        ->where('a.name','like',"%$resVillage%")
                        ->pluck('a.id')->first();
                
                $address[] = [
                    'id_cost' => $value->id,
                    'village_id' => (int) $results
                ];
            }

            #update village_id in tb cost
            // $updates = [];
            foreach ($address as $key => $value) {
                $id_cost    = $value['id_cost'];
                $village_id = $value['village_id'];

                // $updates[]   = $village_id;


                DB::table('cost_les')->where('id', $id_cost)->update([
                    'village_id' => $village_id
                ]);
            }
            
            DB::commit();
            return response()->json([
                'message' => 'Updated successfully!',
                'data' => $address

            ],200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Updated failed!',
                'error' => $e->getMessage()
            ],500);
        }
    }

}
