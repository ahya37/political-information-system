<?php

namespace App\Http\Controllers\Admin;

use App\Figure;
use App\DetailFigure;
use App\DetailFigureInfo;
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

       $info = empty($request->info) ? [] : $request->info;
       $year = empty($request->year) ? [] : $request->year;
       $status =  empty($request->status) ? [] : $request->status;

        $result = array_map(function($info, $year, $status){
            return array_combine(
                ['name','year','status'],
                [$info,$year,$status]
            );
        }, $info,$year,$status);

        $dataInfo = json_encode($result);

        DetailFigure::create([
            'name' => $request->name,
            'village_id' => $request->village_id,
            'figure_id' => $request->figure_id,
            'info_politic' => $dataInfo,
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
                        ->addColumn('action', function($item){
                            return '<button type="button" class="btn btn-sm btn-sc-primary text-white" onclick="onDetail('.$item->id.')">Detail</button>';
                        })
                        ->rawColumns(['address','desc','action'])
                        ->make(true);
        }
    }

    public function detailFigure()
    {
        $token = request()->_token;
        if ($token != null) {
            $id = request()->id;
            $detailFigure = DetailFigure::with(['figure'])->where('id', $id)->first();

            $info_politic = json_decode($detailFigure->info_politic);

            $data = [];
            foreach($info_politic as $val){

                // $data[] = $val->name.' TAHUN: '.$val->year.' STATUS: '.$val->status.'<br>';
                $data[] = "<tr><td>$val->name</td><td>$val->year</td><td>$val->status</td></tr>";
            }

            return response()->json($data);
        }
    }
}
