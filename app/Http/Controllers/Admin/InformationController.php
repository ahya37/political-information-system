<?php

namespace App\Http\Controllers\Admin;

use PDF;
use App\Job;
use App\User;
use App\Dapil;
use App\Figure;
use App\Referal;
use App\DetailFigure;
use App\Models\Village;
use Illuminate\Http\Request;
use App\Providers\GlobalProvider;
use App\Providers\GrafikProvider;
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
        // $dataInfo = $this->getDataInfo($request);

        DetailFigure::create([
            'name' => $request->name,
            'village_id' => $request->village_id,
            'figure_id' => $request->figure_id,
            'politic_potential' => $request->politic_potential,
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
        $dapil = new Dapil();
        $provinceDapil = $dapil->getProvinceDapil();
        return view('pages.admin.info.list-intetlegency', compact('provinceDapil'));
    }

    public function dtListIntelegency($village_id)
    {
       
        $detailFigure = DetailFigure::with(['village.district.regency.province','figure'])
                        ->where('village_id', $village_id)
                        ->get();
        if (request()->ajax()) 
        {
            return DataTables::of($detailFigure)
                        ->addColumn('address', function($item){
                            return ''.$item->village->name.'<br> KEC. '.$item->village->district->name.'<br>'.$item->village->district->regency->name.'<br> '.$item->village->district->regency->province->name.' ';
                        })
                        ->addColumn('potensi', function($item){

                            $gF = new GlobalProvider();

                            return '<div class="badge badge-warning">
                                    '.$gF->decimalFormat($item->politic_potential).'
                                    </div>';
                        })
                        ->addColumn('action', function($item){
                            return '<a href="'.route('admin-detailfigure',$item->id).'" class="btn btn-sm btn-sc-primary text-white" >Detail</a>';
                        })
                        ->rawColumns(['address','desc','action','potensi'])
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
        return $pdf->download('LAPORAN-INTELEGENSI-POLITIK.pdf');
    }

    public function downloadPdfAllByVillageId($villageId)
    {
         $gF = new GlobalProvider();
        $village = Village::with(['district.regency.province'])->where('id', $villageId)->first();
        $figure = DetailFigure::with(['village.district.regency.province','figure','user'])->where('village_id', $villageId)->orderBy('name','asc')->get();

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

        $pdf = PDF::LoadView('pages.admin.report.figurebyvillage', compact('data','no','village'));
        return $pdf->download('LAPORAN-TOKOH-DESA: '.$village->name.'.pdf');
    }

    public function getGrafikIntelegencyVillage($village_id)
    {
        $GrafikProvider = new GrafikProvider();
        $gF = new GlobalProvider();

        $figureModel = new DetailFigure();
        $figure   = $figureModel->getFigureVillage($village_id);
        $choose   = $figureModel->getChooseVillage($village_id);
        $totalChoose = $choose->choose ?? '';

        $totalPotential = collect($figure)->sum(function($q){
            return $q->politic_potential ?? '';
        });

        $potentialPercent = $gF->persen(($totalPotential / $totalChoose) * 100);

        if ($figure == null) {

            $data = [
                'cat_inputer_label' => [],
                'cat_inputer_data' => [],
                'color_inputer' => [],
            ];
            
            $listData = [];
            foreach ($figure as  $val) {
                $listData[] = [
                    'id' => [],
                    'name' =>[],
                    'politic_potential' => [],
                    'percent' => [],

                ];
            }


             $result = [
                'data' => $data,
                'listdata' => $listData,
                'totalPotential' => 0,
                'potentialPercent' => 0,
                'totalChoose' => 0
            ];

            return response()->json($result);
        }else{
            // get fungsi grafik 
            $ChartInputer = $GrafikProvider->getGrafikIntelegency($figure);
            $cat_inputer_label = $ChartInputer['cat_inputer_label'];
            $cat_inputer_data = $ChartInputer['cat_inputer_data'];
            $color_inputer = $ChartInputer['colors'];

    
            $data = [
                'cat_inputer_label' => $cat_inputer_label,
                'cat_inputer_data' => $cat_inputer_data,
                'color_inputer' => $color_inputer,
            ];


            $listData = [];
            foreach ($figure as  $val) {
                $listData[] = [
                    'id' => $val->id,
                    'name' =>$val->name,
                    'politic_potential' => $gF->decimalFormat($val->politic_potential),
                    'percent' => $gF->persen($val->total_data),

                ];
            }

            $result = [
                'data' => $data,
                'listdata' => $listData,
                'totalPotential' => $gF->decimalFormat($totalPotential),
                'potentialPercent' => $gF->persen($potentialPercent),
                'totalChoose' => $gF->decimalFormat($totalChoose),
            ];
            return response()->json($result);
        }


    }

     public function getFigureGrafikVillage($village_id)
    {
        $GrafikProvider = new GrafikProvider();
        $figureModel  = new DetailFigure();
        // $most_jobs = $figureModel->getMostJobs();
        $figure      = $figureModel->getProfesiFigureVillage($village_id);
        if ($figure == null) {
                    $data = [

                    'chart_figure_label' => [],
                    'chart_figure_data'  => [],
                    'color_figure' => [],
                ];
                return response()->json($data);
        }else{
            $ChartJobs = $GrafikProvider->getGrafikJobs($figure);
            $chart_figure_label= $ChartJobs['chart_jobs_label'];
            $chart_figure_data= $ChartJobs['chart_jobs_data'];
            $color_figure    = $ChartJobs['color_jobs'];
    
            $data = [
    
                'chart_figure_label' => $chart_figure_label,
                'chart_figure_data'  => $chart_figure_data,
                'color_figure' => $color_figure,
            ];
            return response()->json($data);

        }

    }

}
