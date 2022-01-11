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
        return $request->all();
        $request->validate([
            'date' => 'required',
            'forecast_id' => 'required',
            'forecast_desc_id' => 'required',
            'village_id' => 'required',
            'nominal' => 'required',
        ]);

        // 
        CostLess::create([
            'date' => $request->date,
            'forcest_id' => $request->forecast_id,
            'forcest_desc_id' => $request->forecast_desc_id,
            'user_id' => $request->user_id,
            'village_id' => $request->village_id,
        ]);

        return redirect()->back()->with(['success' => 'Pengeluaran telah tersimpan']);
    }
}
