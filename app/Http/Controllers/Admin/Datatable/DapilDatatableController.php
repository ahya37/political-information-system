<?php

namespace App\Http\Controllers\Admin\Datatable;

use App\Dapil;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class DapilDatatableController extends Controller
{
    public function dtDapilAreas($id)
    {
        $dapilModel  = new Dapil();
        $dapil_areas = $dapilModel->getDataDapilAreas($id);
        return DataTables::of($dapil_areas)->make(true);
    }
}
