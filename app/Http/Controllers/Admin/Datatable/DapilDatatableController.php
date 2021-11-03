<?php

namespace App\Http\Controllers\Admin\Datatable;

use App\Models\District;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class DapilDatatableController extends Controller
{
    public function dtDistrict($regency_id)
    {
        $districts = District::where('regency_id', $regency_id)->orderBy('name','ASC')->get();
        return DataTables::of($districts)
                ->addColumn('select', function($item){
                    return '<input type="checkbox" value="'.$item->id.'" name="district[]">';
                })
                ->rawColumns(['select'])
                ->make(true);
    }
}
