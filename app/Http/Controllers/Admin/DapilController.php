<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Dapil;
use App\DapilArea;
use App\Models\District;

class DapilController extends Controller
{
    public function index()
    {
        $dapilModel = new Dapil();
        $dapils     = $dapilModel->getDataDapilsRegency();

        return view('pages.admin.dapil.index', compact('dapils','dapilModel'));
    }

    public function create()
    {
        return view('pages.admin.dapil.create');
    }

    public function store(Request $request)
    {
         $this->validate($request, [
               'name' => 'numeric',
           ]);
        
        Dapil::create([
            'name' => 'DAPIL '.$request->name,
            'regency_id' => $request->regency_id
        ]);

        return redirect()->route('admin-dapil')->with(['success' => 'Dapil telah dibuat']);
    }

    public function detail($id)
    {
        $dapilModel = new Dapil();
        $dapil      = $dapilModel->getDapilDetailById($id);
        return view('pages.admin.dapil.detail', compact('dapil'));
    }

    public function createDapilArea($regency_id, $dapil_id)
    {
        $districtModel = new District();
        $districts     = $districtModel->getDistrictDapilByRegency($regency_id);
        return view('pages.admin.dapil.create-dapil-area', compact('districts','dapil_id','regency_id'));
    }

    public function saveDapilArea(Request $request, $dapil_id)
    {
        $district_id = $request->district_id;
        foreach($district_id as $key => $val){
            $dapil_area = new DapilArea();
            $dapil_area->dapil_id = $dapil_id;
            $dapil_area->district_id = $val;
            $dapil_area->save();
        }

        return redirect()->route('admin-dapil-detail', ['id' => $dapil_id])->with(['success' => 'Dapil Area telah ditambahkan']);
    }
}
