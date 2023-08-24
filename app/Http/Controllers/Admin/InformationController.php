<?php

namespace App\Http\Controllers\Admin;

use PDF;
use App\Job;
use App\User;
use App\Dapil;
use App\Figure;
use App\Referal;
use App\DetailFigure;
use App\IntelegensiPolitik;
use App\ProfessionIntelegensiPolitik;
use App\OnceServedIntelegensiPolitik;
use App\PolitikNameIntelegensiPolitik;
use App\Models\Village;
use App\FigureNameOption;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Providers\GlobalProvider;
use App\Providers\GrafikProvider;
use App\Http\Controllers\Controller;
use App\ResourceInfo;
use App\RightChosseVillage;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\IntelegensiPolitikImport;
use DB;

class InformationController extends Controller
{
    public function index(){
		
		$situasi_politik = DB::select("SELECT d.name as village, c.photo, c.name as pengisi, a.name as pesaing, a.asal_partai , a.description as persentase
							from intelegensi_politik_political_situation as a
							join intelegensi_politik as b on a.intelegensi_politik_id = b.id
							join users as c on c.code = b.code_member 
							join villages as d on c.village_id = d.id 
							where a.name is not null 
							and a.description is not null group by a.name , a.asal_partai , a.description , c.name, c.photo, d.name");
		$no = 1;
        return view('pages.admin.intelegensipolitik.index', compact('situasi_politik','no'));
		
    }
	
	public function downloadPengisi(){
		
		// get data pengisi intelegensi
		$intel = DB::table('users as a')
				->select('a.name','a.photo', DB::raw("count(b.id) as jml_info"))
				->join('intelegensi_politik as b','a.code','=','b.code_member')
				->groupBy('a.name','a.photo')
				->orderByDesc('jml_info')
				->get();
		$no = 1;
		$pdf  = PDF::LoadView('pages.report.pengisiintelegensi', compact('intel','no'))->setPaper('a4');
		return $pdf->download('PENGISI INTELEGENSI POLITIK.pdf');
		
	}

    public function getDataIntelegensiPolitik(Request $request){

        $orderBy = 'a.created_at';
        switch ($request->input('order.0.column')) {
            case '1':
                $orderBy = 'a.name';
                break;
            case '3':
                $orderBy = 'a.profession';
                break;
            case '5':
                $orderBy = 'a.politic_potential';
                break;
			case '7':
                $orderBy = 'a.created_at';
                break;
				
        }

        $data = DB::table('intelegensi_politik as a')
                    ->select('a.id','d.name as pengisi','d.photo','a.name','a.address','a.descr','a.politic_potential','b.name as village','c.name as district','a.ismember','a.profession','a.created_at')
                    ->join('villages as b','b.id','=','a.village_id')
                    ->join('districts as c','c.id','=','b.district_id')
					->leftJoin('users as d','a.code_member','=','d.code')
					->orderBy('a.created_at','desc');
            
        if($request->input('search.value')!=null){
            $data = $data->where(function($q)use($request){
                $q->whereRaw('LOWER(a.name) like ? ',['%'.strtolower($request->input('search.value')).'%']);
                $q->whereRaw('LOWER(d.name) like ? ',['%'.strtolower($request->input('search.value')).'%']);
                $q->whereRaw('LOWER(a.profession) like ? ',['%'.strtolower($request->input('search.value')).'%']);
                $q->whereRaw('LOWER(a.politic_potential) like ? ',['%'.strtolower($request->input('search.value')).'%']);
            });
        }
        
        // if ($request->input('district') != null) {
        //                 $data->where('districts.id', $request->district);
        //     }

        // if ($request->input('village') != null) {
        //                 $data->where('villages.id', $request->village);
        //     }

		  
          $recordsFiltered = $data->get()->count();
          if($request->input('length')!=-1) $data = $data->skip($request->input('start'))->take($request->input('length'));
		  $data = $data->orderBy($orderBy,'desc')->get();
          
          $recordsTotal = $data->count();

          $results = [];
          $no = 1;
          foreach ($data as $value) {
            #get profesi where id
            $results[] = [
                'no' => $no++,
                'pengisi' => $value->pengisi ?? '',
                'photo' => $value->photo ?? '',
                'name' => $value->name,
                'descr' => $value->descr,
                'address' => $value->address,
                'profession' => $value->profession,
                'politic_potential' => $value->politic_potential,
                'village' => $value->village,
                'district' => $value->district,
                'ismember' => $value->ismember, 
				'created_at' => date('d-m-Y', strtotime($value->created_at))
            ];
          }


          return response()->json([
                'draw'=>$request->input('draw'),
                'recordsTotal'=>$recordsTotal,
                'recordsFiltered'=>$recordsFiltered,
                'data'=> $results
            ]);
    }

    public function getGrafikProfesiIntelegensiPolitik(){

        $professions = DB::table('profession_intelegensi_politik as a')
                        ->join('intelegensi_politik as b','b.id','=','a.intelegensi_politik_id')
                        ->select('a.name', DB::raw('count(b.id) as jumlah'))
                        ->groupBy('a.name')
                        ->orderBy(\DB::raw('count(b.id)'),'desc')
                        ->get();

        $total_jumlah = collect($professions)->sum(function($q){
            return $q->jumlah;
        });

        $results = [];
        foreach ($professions as $value) {
            $persentage = round(($value->jumlah/$total_jumlah)*100);
            $results[] = [ucwords($value->name ?? 'Kosong'), $persentage];
        }

        return response()->json($results);
                
    }

    public function getGrafikOnceServedIntelegensiPolitik(){

        $onceserved = DB::table('once_served_intelegensi_politik as a')
                        ->join('intelegensi_politik as b','b.id','=','a.intelegensi_politik_id')
                        ->select('a.name', DB::raw('count(b.id) as jumlah'))
                        ->groupBy('a.name')
                        ->orderBy(\DB::raw('count(b.id)'),'desc')
                        ->get();

        $total_jumlah = collect($onceserved)->sum(function($q){
            return $q->jumlah;
        });

        $results = [];
        foreach ($onceserved as $value) {
            $persentage = round(($value->jumlah/$total_jumlah)*100);
            $results[] = [ucwords($value->name ?? 'Kosong'), $persentage];
        }

        return response()->json($results);
                
    }

    public function getGrafikPolitikNameIntelegensiPolitik(){

        $politicname = DB::table('politik_name_intelegensi_politik as a')
                        ->join('intelegensi_politik as b','b.id','=','a.intelegensi_politik_id')
                        ->select('a.name', DB::raw('count(b.id) as jumlah'))
                        ->groupBy('a.name')
                        ->orderBy(\DB::raw('count(b.id)'),'desc')
                        ->get();

        $total_jumlah = collect($politicname)->sum(function($q){
            return $q->jumlah;
        });

        $results = [];
        foreach ($politicname as $value) {
            $persentage = round(($value->jumlah/$total_jumlah)*100);
            $results[] = [ucwords($value->name ?? 'Kosong'), $persentage];
        }

        return response()->json($results);
                
    }
    

    public function formIntelegencyPolitic()
    {
        $figures = Figure::all();
        $detailFigure = DetailFigure::select('idx','name')->groupBy('idx','name')->get();
        $resourceInfo = ResourceInfo::select('id','name')->get();
        return view('pages.admin.info.form-intelegency', compact('figures','detailFigure','resourceInfo'));
    }

    public function saveIntelegencyPolitic(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'resource' => 'required'
        ]);

        $cekDetailFigureSameResource = DetailFigure::where('id', $request->name)->where('resource_id', $request->resource)->count();
        if ($cekDetailFigureSameResource > 0) {
         return redirect()->back()->with(['warning' => 'Data dengan sumber tersebut sudah tersedia']);
        }else{
            // $dataInfo = $this->getDataInfo($request);
        $detailMode = DetailFigure::where('id', $request->name); 
        $detail = $detailMode->count();
        
        $resource = ResourceInfo::where('id', $request->resource);
        $cekRoesource = $resource->count();

        if ($cekRoesource > 0) {
            $getresource =  $resource->first();
            $resource_id = $getresource->id;
        }else{
            $saveRoesource = ResourceInfo::create([
                'name' => strtoupper($request->resource),
                'create_by' => 35
            ]);
            $resource_id    = $saveRoesource->id;
        }



        if ($detail > 0) {
            $figure =  $detailMode->first();
            
            DetailFigure::create([
                'idx' => $figure->id,
                'name' => strtoupper($figure->name),
                'village_id' => $request->village_id,
                'figure_id' => $request->figure_id,
                'politic_potential' => $request->politic_potential,
                'figure_other' => $request->figure_id = '11' ? $request->fiugureOther : 'NULL',
                'no_telp' => $request->no_telp,
                'info_politic' => 'NULL',
                'once_served' => $request->once_served == '11' ? $request->once_served_other : $request->once_served,
                'politic_name' => $request->politic_name == '11' ? $request->politic_name_other : $request->politic_name,
                'politic_year' => $request->politic_year,
                'politic_status' => $request->politic_status,
                'politic_member' => $request->politic_member,
                'descr' => $request->desc,
                'resource_id' => $resource_id,
                'create_by' => 35
            ]);

        }else{
           $saveFigure = DetailFigure::create([
                'name' => strtoupper($request->name),
                'village_id' => $request->village_id,
                'figure_id' => $request->figure_id,
                'politic_potential' => $request->politic_potential,
                'figure_other' => $request->figure_id = '11' ? $request->fiugureOther : 'NULL',
                'no_telp' => $request->no_telp,
                'info_politic' => 'NULL',
                'once_served' => $request->once_served == '11' ? $request->once_served_other : $request->once_served,
                'politic_name' => $request->politic_name == '11' ? $request->politic_name_other : $request->politic_name,
                'politic_year' => $request->politic_year,
                'politic_status' => $request->politic_status,
                'politic_member' => $request->politic_member,
                'descr' => $request->desc,
                'resource_id' => $resource_id,
                'create_by' => 35
            ]);

            $updateIdx = DetailFigure::where('id', $saveFigure->id)->first();
            $updateIdx->update(['idx' => $saveFigure->id]);
        }

        }
        
        return redirect()->back()->with(['success' => 'Data telah tersimpan']);
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
        $detailFigure = DetailFigure::select('idx','name')->groupBy('idx','name')->get();
        $resourceInfo = ResourceInfo::select('id','name')->get();
        return view('pages.info.form-intelegency', compact('figures','detailFigure','resourceInfo'));
    }

    public function saveIntelegencyPoliticAccounMember(Request $request)
    {
        // $dataInfo = $this->getDataInfo($request);

       $request->validate([
            'name' => 'required',
            'resource' => 'required'
        ]);
        // $dataInfo = $this->getDataInfo($request);
         $cekDetailFigureSameResource = DetailFigure::where('id', $request->name)->where('resource_id', $request->resource)->count();
         if ($cekDetailFigureSameResource > 0) {
            return redirect()->back()->with(['warning' => 'Data dengan sumber tersebut sudah tersedia']);
             
         }else{
             $detailMode = DetailFigure::where('id', $request->name); 
        $detail = $detailMode->count();
        
        $resource = ResourceInfo::where('id', $request->resource);
        $cekRoesource = $resource->count();

        if ($cekRoesource > 0) {
            $getresource =  $resource->first();
            $resource_id = $getresource->id;
        }else{
            $saveRoesource = ResourceInfo::create([
                'name' => strtoupper($request->resource),
                'create_by' => Auth::user()->id
            ]);
            $resource_id    = $saveRoesource->id;
        }




        if ($detail > 0) {
            $figure =  $detailMode->first();
            DetailFigure::create([
                'idx' => $figure->id,
                'name' => strtoupper($figure->name),
                'village_id' => $request->village_id,
                'figure_id' => $request->figure_id,
                'politic_potential' => $request->politic_potential,
                'figure_other' => $request->figure_id = '11' ? $request->fiugureOther : 'NULL',
                'no_telp' => $request->no_telp,
                'info_politic' => 'NULL',
                'once_served' => $request->once_served == '11' ? $request->once_served_other : $request->once_served,
                'politic_name' => $request->politic_name == '11' ? $request->politic_name_other : $request->politic_name,
                'politic_year' => $request->politic_year,
                'politic_status' => $request->politic_status,
                'politic_member' => $request->politic_member,
                'descr' => $request->desc,
                'resource_id' => $resource_id,
                'create_by' => Auth::user()->id
            ]);

        }else{
           $saveFigure = DetailFigure::create([
                'name' => strtoupper($request->name),
                'village_id' => $request->village_id,
                'figure_id' => $request->figure_id,
                'politic_potential' => $request->politic_potential,
                'figure_other' => $request->figure_id = '11' ? $request->fiugureOther : 'NULL',
                'no_telp' => $request->no_telp,
                'info_politic' => 'NULL',
                'once_served' => $request->once_served == '11' ? $request->once_served_other : $request->once_served,
                'politic_name' => $request->politic_name == '11' ? $request->politic_name_other : $request->politic_name,
                'politic_year' => $request->politic_year,
                'politic_status' => $request->politic_status,
                'politic_member' => $request->politic_member,
                'descr' => $request->desc,
                'resource_id' => $resource_id,
                'create_by' => Auth::user()->id
            ]);

            $updateIdx = DetailFigure::where('id', $saveFigure->id)->first();
            $updateIdx->update(['idx' => $saveFigure->id]);
        }

         }


        return redirect()->back()->with(['success' => 'Data telah tersimpan']);
    }

    public function dtListIntelegencyAccountMember()
    {
        // $code = request()->code;
        // $user = User::select('id')->where('code', $code)->first();
        $userId = Auth::user()->id;

        $detailFigure = DetailFigure::with(['village.district.regency.province','figure'])->where('create_by', $userId)->get();
        if (request()->ajax()) 
        {
            return DataTables::of($detailFigure)
                        ->addColumn('address', function($item){
                            return ''.$item->village->name.'<br> KEC. '.$item->village->district->name.'<br>'.$item->village->district->regency->name.'<br> '.$item->village->district->regency->province->name.' ';
                        })
                        ->addColumn('potensi', function($item){
                            $gF = new GlobalProvider();

                            return $gF->decimalFormat($item->politic_potential);
                        })
                        ->addColumn('action', function($item){
                            return '<a href="'.route('member-detailfigure',$item->id).'" class="btn btn-sm btn-sc-primary text-white" >Detail</a>';
                        })
                        ->rawColumns(['address','desc','action','potensi'])
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
        $totalChoose = $choose == null ? 0 : $choose->choose;

        $totalPotential = collect($figure)->sum(function($q){
            return $q->politic_potential ?? 0;
        });

        $totalPercen = collect($figure)->sum(function($q){
            return $q->total_data ?? 0;
        });

        $range =  $choose == null ? 0 : $totalChoose - $totalPotential;
        $range_percen =  $choose == null ? 0 : $gF->persen(($range / $totalChoose)*100);

        $totalOther = $totalChoose - $totalPotential;
        $totalPercenAll = $totalPercen + $range_percen;
        
        $potentialPercent = $choose == null ? 0 : $gF->persen(($totalChoose / $totalChoose) * 100);
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
                'totalChoose' => $gF->decimalFormat($totalChoose),
                'range' => 0,
                'range_percen' => 0
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
                'totalPotential' => $gF->decimalFormat($totalChoose),
                'potentialPercent' => $gF->persen($totalPercenAll) ?? 0,
                'totalChoose' => $gF->decimalFormat($totalChoose),
                'range' => $gF->decimalFormat($totalOther),
                'range_percen' => $range_percen
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

    public function listResourceInfo($village_id)
    {
        $gF = new GlobalProvider();
        $resource = new ResourceInfo();
        $dataResource = $resource->getDataResourceVillage($village_id);
        $chooseModel  = RightChosseVillage::select('choose')->where('village_id', $village_id)->first();
        $choose       = $chooseModel->choose ?? 0;

        $data = [];
        foreach ($dataResource as $value) {
            $figureModel = new  DetailFigure();
            $figure      = $figureModel->getFigureByResource($value->id);

            $total_politic_potential = collect($figure)->sum(function($q){
                return $q->politic_potential ?? 0;
            });

            $dataFigure = [];
            foreach($figure as $val ){
                $dataFigure[] = [
                    'id' => $val->id,
                    'name' => $val->name,
                    'politic_potential' => $gF->decimalFormat($val->politic_potential),
                    'persentage' => $gF->persen(($val->politic_potential / $choose) * 100),
                    'persen' => ($val->politic_potential / $choose) * 100,
                    'create_by' => $val->create_by
                ];
            }

            $others = $choose - $total_politic_potential;
            $total_choose = $total_politic_potential + $others;
            $other_persentage = collect($dataFigure)->sum(function($q){
                return $q['persen'] ?? 0;
            });

            $range =  ($total_choose / $choose) * 100;

            $total_other_persentage = $range - $other_persentage;

            $total_persentage = $other_persentage + $total_other_persentage;
            
            $data[] = [
                'name' => $value->name,
                'figure' => $dataFigure,
                'others' =>  $others >0 ? $gF->decimalFormat($others) : 0,
                'total_choose' => $gF->decimalFormat($total_choose),
                'other_persentage' => $total_other_persentage >0 ? $gF->persen($total_other_persentage) : 0,
                'total_persentage' => $gF->persen($total_persentage)
            ];
        }

        $result = [
            'data' => $data
        ];
            return response()->json($result);
    }

    public function infoRightChooseVillage($id)
    {
        $gF = new GlobalProvider();

        $chooseModel = new RightChosseVillage();
        $choose = $chooseModel->getDataChooseVillage($id);
        $data = [
            'name' => $choose->name,
            'choose' => $gF->decimalFormat($choose->choose),
        ];
        return response()->json($data);
    }

    public function intelegensiPolitikImportExcel(){

        try {
            
            Excel::import(new IntelegensiPolitikImport, request()->file('file'));

            return 'success';

        } catch (\Exception $th) {
            
            return $th->getMessage();

        }


    }

    public function  shareFormIntelegencyPolitic(){ 
        
        $figures = Figure::all();
        $detailFigure = DetailFigure::select('idx','name')->groupBy('idx','name')->get();
        $resourceInfo = ResourceInfo::select('id','name')->get();
        return view('form-intelegency', compact('figures','detailFigure','resourceInfo'));

    }
	
	public function  shareFormIntelegencyPoliticMaintenance(){
		
        
        $figures = Figure::all();
        $detailFigure = DetailFigure::select('idx','name')->groupBy('idx','name')->get();
        $resourceInfo = ResourceInfo::select('id','name')->get();
        return view('form-intelegency', compact('figures','detailFigure','resourceInfo'));

    }

    public function saveFormIntelegencyPolitic(Request $request){

        DB::beginTransaction();
        try {
					
			
			// cek apakah anggota dengan kta tersebut ada
			$checkKta = User::where('code',  str_replace(' ',' ',$request->kta))->count();
			if($checkKta == 0) return redirect()->back()->with(['error' => 'Referal tidak terdaftar!']);

            $profession['professi']     = $request->profession; 
            $onceserved['onceserved']   = $request->onceserved;
            $politicname['politicname'] = $request->politicname;

            $professions  = implode(", ", $profession['professi']);
            $onceserveds  = implode(", ", $onceserved['onceserved']);
            $politicnames = implode(", ", $politicname['politicname']);

            #simpan intelegensi politik
            $IntelegensiPolitik = IntelegensiPolitik::create([
				'code_member' => $request->kta,
                'name' => strtoupper($request->name),
                'village_id' => $request->village_id,
                'district_id' => $request->district_id,
                'regency_id' => 3602,
                'profession' => $professions,
                'once_served' => $onceserveds,
                'politik_name' => $politicnames,
                'rt' => $request->rt,
                'address' => strtoupper($request->address),
                'politic_potential' => $request->politic_potential,
                'no_telp' => $request->notelp,
                'politic_year' => $request->politic_year,
                'politic_status' => $request->politik_status,
                'politic_member' => $request->politic_member,
                'descr' => $request->descr,
                'resource_information' => strtoupper($request->resource),
                'ismember' => $request->ismember,
                'member_number' => $request->nomember
            ]);

            #jika profesi ada, simpan ke tb
            $profession = $request->profession;
            if ($profession != null) {

                foreach($profession as $key => $value){
                    if ($value != null) {
                        ProfessionIntelegensiPolitik::create([
                            'intelegensi_politik_id' =>  $IntelegensiPolitik->id,
                            'name' => $value
                        ]);
                    }
                }
            }

            # jika data pernah menjabat ada, simpan ke tb
            $onceserved = $request->onceserved;
            if ($onceserved != null) {

                foreach($onceserved as $key => $value){
                    if ($value != null) {
                        OnceServedIntelegensiPolitik::create([
                            'intelegensi_politik_id' =>  $IntelegensiPolitik->id,
                            'name' => $value
                        ]);
                    }
                }
            }

            # jika data pernah mencalonkan diri ada, simpan ke tb
            $politicname = $request->politicname;
            if ($politicname != null) {

                foreach($politicname as $key => $value){
                    if ($value != null) {
                        PolitikNameIntelegensiPolitik::create([
                            'intelegensi_politik_id' =>  $IntelegensiPolitik->id,
                            'name' => $value
                        ]);
                    }
                }
            }
			
			// save to table intelegensi_politik_political_situation untuk data perkembangan situasi politik di desa masing2
			// jika ada nama calong pesaing yang di input
			$dataSituasiPlotik['pspesaing'] = $request->pspesaing;
			if(count($dataSituasiPlotik['pspesaing']) > 0){
					$pspesaing = $dataSituasiPlotik['pspesaing'];
					foreach($pspesaing as $key => $value){
						
						DB::table('intelegensi_politik_political_situation')->insert([
							'intelegensi_politik_id' => $IntelegensiPolitik->id,
							'name' => $value,
							'asal_partai' => $request->pspartai[$key],
							'description' => $request->pssuporter[$key]
						]);	
					}
			}
			
            DB::commit();

            return view('form-intelegency-success');

        } catch (\Exception $e) {
            DB::rollback();
            return $e->getMessage();
        }
    }

    public function importIntelegensiToProfession(){

        try {
            #get data intelegensi
           
    
            #jika profesi ada, simpan ke tb
            // foreach ($IntelegensiPolitik as $value) {
    
            //     ProfessionIntelegensiPolitik::create([
            //         'intelegensi_politik_id' =>  $value->id,
            //         'name' => $value->profession
            //     ]);

            //     OnceServedIntelegensiPolitik::create([
            //         'intelegensi_politik_id' =>  $value->id,
            //         'name' => $value->once_served ?? null
            //     ]);

            //     PolitikNameIntelegensiPolitik::create([
            //         'intelegensi_politik_id' =>  $value->id,
            //         'name' => $value->politic_name ?? null
            //     ]);
              
            // }


    
            #jika data pernah menjabat ada, simpan ke tb
    
            #jika data pernah mencalonkan diri ada, simpan ke tb

            // $ProfessionIntelegensiPolitik = ProfessionIntelegensiPolitik::select('name')->where('intelegensi_politik_id', $id)->get();
            // $pro = $ProfessionIntelegensiPolitik->implode('name',', ');
            // $IntelegensiPolitik = IntelegensiPolitik::where('id', $id)->first();
            // $IntelegensiPolitik->update(['profession' => $pro]);

            $IntelegensiPolitik = IntelegensiPolitik::select('id')->get();
            $results = [];
            foreach ($IntelegensiPolitik as $value) {
                $PolitikNameIntelegensiPolitik = PolitikNameIntelegensiPolitik::select('name')->where('intelegensi_politik_id', $value->id)->get();
                $pro = $PolitikNameIntelegensiPolitik->implode('name',', ');
                $update = IntelegensiPolitik::where('id', $value->id)->first();
                $update->update(['politik_name' => $pro]);
            }


            DB::commit();
            // return $results;
 
            return 'Berhasil simpan data';
        } catch (\Exception $e) {
            DB::rollback();
            return $e->getMessage();
        }


    }
	
	public function addElementFormIntelegence()
    {
        // Add element
		 $html = "<div class='fieldGroup'>
						<div class='form-group'>
							<label class='col-sm-12 col-form-label'>Nama Calon Pesaing</label>
								<div class='col-sm-12'>
									<input type='text' name='pspesaing[]' placeholder='Isikan nama pesaing' class='form-control'>
								</div>
							</div>
						<div class='form-group'>
							<label class='col-sm-12 col-form-label'>Asal Partai</label>
								<div class='col-sm-12'>
									<input type='text'  placeholder='Isikan nama pesaing' name='pspartai[]' class='form-control'>
								</div>
						</div> 
						<div class='form-group'>
							<label class='col-sm-12 col-form-label'>Kekuatan Dukungan</label>
							<div class='col-sm-12'>
								<textarea name='pssuporter[]'  placeholder='Isikan keterangan berupa kekuatan dan dukungan yang dimiliki pesaing tersebut' class='form-control'></textarea>
							</div>
							<div class='col-sm-12 mt-2'>
						   <button type='button' class='btn btn-danger btn-sm remove'>Batalkan</button>
						</div>
						</div>  
                    </div>";
            echo $html;
            exit;
    }
	
	public function suratUndangan($referal){
		
		return view('pages.report.undangan');
	}

}
