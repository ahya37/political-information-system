<?php

namespace App\Http\Controllers\Admin;

use App\CostLess;
use App\Forecast;
use App\ForecastDesc;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
            'user_id' => 'required',
            'nominal' => 'required',
        ]);

        // 
        CostLess::create([
            'date' => date('Y-m-d', strtotime($request->date)),
            'forcest_id' => $request->forecast_id,
            'forecast_desc_id' => $request->forecast_desc_id,
            'user_id' => $request->user_id,
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
}
