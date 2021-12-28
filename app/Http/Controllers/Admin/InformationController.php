<?php

namespace App\Http\Controllers\Admin;

use App\Figure;
use App\DetailFigure;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

use function GuzzleHttp\json_decode;

class InformationController extends Controller
{
    public function formIntelegencyPolitic()
    {
        $figures = Figure::all();
        return view('pages.admin.info.form-intelegency', compact('figures'));
    }

    public function saveIntelegencyPolitic(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'village_id' => 'required',
        ]);

        $info = $request->info;
        $year = $request->year;
        $status = $request->status;
        $dataInfo = '[{"name":"'.$info[0].'","year":"'.$year[0].'","status":"'.$status[0].'"},{"name":"'.$info[1].'","year":"'.$year[1].'","status":"'.$status[1].'"},{"name":"'.$info[2].'","year":"'.$year[2].'","status":"'.$status[2].'"}]';
        
        DetailFigure::create([
            'name' => $request->name,
            'village_id' => $request->village_id,
            'figure_id' => $request->figure_id,
            'info' => $dataInfo,
            'descr' => $request->desc
        ]);

        return redirect()->route('admin-listintelegency')->with(['success' => 'Data telah tersimpan']);
    }

    public function listIntelegency()
    {
        return view('pages.admin.info.list-intetlegency');
    }

    public function dtListIntelegency()
    {

        $detailFigure = DetailFigure::with(['village.district.regency.province','figure'])->get();
        if (request()->ajax()) 
        {
            return DataTables::of($detailFigure)
                        ->addColumn('address', function($item){
                            return ''.$item->village->name.'<br> KEC. '.$item->village->district->name.'<br>'.$item->village->district->regency->name.'<br> '.$item->village->district->regency->province->name.' ';
                        })
                        ->addColumn('desc', function($item){
                            return $item->descr;
                        })
                        ->rawColumns(['address','desc'])
                        ->make(true);
        }
    }
}
