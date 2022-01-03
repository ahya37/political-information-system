<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Figure;
use App\DetailFigure;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Village;
use App\Providers\GlobalProvider;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use PDF;

class InformationController extends Controller
{
    public function formIntelegencyPolitic()
    {
        $figures = Figure::all();
        return view('pages.admin.info.form-intelegency', compact('figures'));
    }

    public function saveIntelegencyPolitic(Request $request)
    {
        // $dataInfo = $this->getDataInfo($request);

        DetailFigure::create([
            'name' => $request->name,
            'village_id' => $request->village_id,
            'figure_id' => $request->figure_id,
            'figure_other' => $request->figure_id = '10' ? $request->fiugureOther : 'NULL',
            'no_telp' => $request->no_telp,
            'info_politic' => 'NULL',
            'once_served' => $request->once_served == '10' ? $request->once_served_other : $request->once_served,
            'politic_name' => $request->politic_name == '10' ? $request->politic_name_other : $request->politic_name,
            'politic_year' => $request->politic_year,
            'politic_status' => $request->politic_status,
            'politic_member' => $request->politic_member,
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
                            return '<a href="'.route('admin-detailfigure',$item->id).'" class="btn btn-sm btn-sc-primary text-white" >Detail</a>';
                        })
                        ->rawColumns(['address','desc','action'])
                        ->make(true);
        }
    }

    public function detailFigure($id)
    {
        $detailFigure = DetailFigure::with(['village','figure'])->where('id', $id)->orderBy('name','ASC')->first();
        $gF = new GlobalProvider();
        return view('pages.admin.info.detail-figure', compact('detailFigure','gF'));
        

    }

    public function detailFigureAccountMember($id)
    {
        $detailFigure = DetailFigure::with(['village','figure'])->where('id', $id)->first();
        $gF = new GlobalProvider();
        return view('pages.info.detail-figure', compact('detailFigure','gF'));
        

    }

    public function formIntelegencyPoliticAccounMember()
    {
        $figures = Figure::all();
        return view('pages.info.form-intelegency', compact('figures'));
    }

    public function saveIntelegencyPoliticAccounMember(Request $request)
    {
        // $dataInfo = $this->getDataInfo($request);

        DetailFigure::create([
            'name' => $request->name,
            'village_id' => $request->village_id,
            'figure_id' => $request->figure_id,
            'figure_other' => $request->figure_id = '10' ? $request->fiugureOther : 'NULL',
            'no_telp' => $request->no_telp,
            'info_politic' => 'NULL',
            'politic_name' => $request->politic_name,
            'politic_year' => $request->politic_year,
            'politic_status' => $request->politic_status,
            'politic_member' => $request->politic_member,
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
                            return '<a href="'.route('member-detailfigure',$item->id).'" class="btn btn-sm btn-sc-primary text-white" >Detail</a>';
                        })
                        ->rawColumns(['address','desc','action'])
                        ->make(true);
        }
    }

    public function listIntelegencyAccounMember()
    {
        return view('pages.info.list-intetlegency');
    }

    public function downloadPdfAll()
    {
        $gF = new GlobalProvider();
        $figure = DetailFigure::with(['village.district.regency.province','figure','user'])->orderBy('name','asc')->get();
        $data = [];
        $no   = 1;
        foreach ($figure as $val) {
            $data[] = [
                'name' => $val->name,
                'figure' => $val->figure->name,
                'village' => $val->village->name,
                'district' => $val->village->district->name, 
                'regency' => $val->village->district->regency->name, 
                'province' => $val->village->district->regency->province->name, 
                'no_telp' => $val->no_telp,
                'once_served' => $val->once_served,
                'politic_name' => $val->politic_name,
                'politc_year' => $val->politic_year,
                'politic_status' => $val->politic_status,
                'politic_member' => $gF->decimalFormat($val->politic_member),
                'descr' => $val->descr,
                'info'  => json_decode($val->info_politic),
                'cby'   => $val->user->name,
            ];    
        }


        $pdf = PDF::LoadView('pages.admin.report.figurebyvillageall', compact('data','no'))->setPaper('landscape');
        return $pdf->stream('LAPORAN-INTELEGENSI-POLITIK.pdf');
    }

    public function downloadPdfAllByVillageId($villageId)
    {
        $village = Village::select('name')->where('id', $villageId)->first();
        $figure = DetailFigure::with(['village.district.regency.province','figure','user'])->where('village_id', $villageId)->orderBy('name','asc')->get();

        $data = [];
        $no   = 1;
        foreach ($figure as $val) {
            $data[] = [
                'name' => $val->name,
                'village' => $val->village->name,
                'district' => $val->village->district->name, 
                'regency' => $val->village->district->regency->name, 
                'province' => $val->village->district->regency->province->name, 
                'descr' => $val->descr,
                'info'  => json_decode($val->info_politic),
                'cby'   => $val->user->name,
            ];    
        }

        $pdf = PDF::LoadView('pages.admin.report.figurebyvillage', compact('data','no'));
        return $pdf->download('LAPORAN-TOKOH-DESA: '.$village->name.'.pdf');
    }
}
