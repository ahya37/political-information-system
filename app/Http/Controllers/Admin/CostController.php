<?php

namespace App\Http\Controllers\Admin;

use App\Cost;
use App\CostLess;
use App\Forecast;
use App\ForecastDesc;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\GlobalProvider;
use Carbon\Carbon;

class CostController extends Controller
{
    public function create()
    {
        $forecast = Forecast::orderBy('name','desc')->get();
        $forecast_desc = ForecastDesc::orderBy('name','desc')->get();
        return view('pages.admin.cost.create', compact('forecast','forecast_desc'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required',
            'forecast_id' => 'required',
            'forecast_desc_id' => 'required',
            'received_name' => 'required',
            'nominal' => 'required',
        ]);

        // 
        CostLess::create([
            'date' => date('Y-m-d', strtotime($request->date)),
            'forcest_id' => $request->forecast_id,
            'forecast_desc_id' => $request->forecast_desc_id,
            'received_name' => $request->received_name,
            'village_id' => $request->village_id,
            'nominal' => $request->nominal,
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
}
