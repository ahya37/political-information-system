<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Figure;
use App\DetailFigure;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class InformationController extends Controller
{
    public function formIntelegencyPolitic()
    {
        $figures = Figure::all();
        return view('pages.admin.info.form-intelegency', compact('figures'));
    }

    public function saveIntelegencyPolitic(Request $request)
    {
        $dataInfo = $this->getDataInfo($request);

        DetailFigure::create([
            'name' => $request->name,
            'village_id' => $request->village_id,
            'figure_id' => $request->figure_id,
            'info_politic' => $dataInfo,
            'descr' => $request->desc,
            'create_by' => 35
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

    public function formIntelegencyPoliticAccounMember()
    {
        $figures = Figure::all();
        return view('pages.info.form-intelegency', compact('figures'));
    }

    public function saveIntelegencyPoliticAccounMember(Request $request)
    {
        $dataInfo = $this->getDataInfo($request);

        DetailFigure::create([
            'name' => $request->name,
            'village_id' => $request->village_id,
            'figure_id' => $request->figure_id,
            'info_politic' => $dataInfo,
            'descr' => $request->desc,
            'create_by' => Auth::user()->id
        ]);

        return redirect()->route('member-intelegensi-index')->with(['success' => 'Data telah tersimpan']);
    }

    public function dtListIntelegencyAccountMember()
    {
        $code = request()->code;
        $user = User::select('id')->where('code', $code)->first();
        $userId = $user->id;

        $detailFigure = DetailFigure::with(['village.district.regency.province','figure'])->where('create_by', $userId)->get();
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

    public function getDataInfo($request)
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
        return $dataInfo;
    }

    public function listIntelegencyAccounMember()
    {
        return view('pages.info.list-intetlegency');
    }
}
