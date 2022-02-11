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

    public function dtDapilCalegs($id)
    {
        $dapilModel  = new Dapil();
        $dapil_areas = $dapilModel->getDataDapilCalegs($id);
        return DataTables::of($dapil_areas)
                ->addColumn('photo', function($item){
                        return '
                        <a href="'.route('admin-profile-member', $item->user_id).'">
                            <img  class="rounded" width="40" src="'.asset('storage/'.$item->photo).'">
                        </a>
                        ';
                })
                 ->addColumn('contact', function($item){
                        return '
                          <div class="badge badge-pill badge-primary">
                            <i class="fa fa-phone"></i>
                            </div>
                            '.$item->phone_number.'
                            <br>
                            <div class="badge badge-pill badge-success"><i class="fa fa-whatsapp"></i>
                            </div>
                            '.$item->whatsapp.'
                        ';
                    })
                 ->addColumn('action', function($item){
                        return '
                            <div class="btn-group">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-sc-primary text-white dropdown-toggle mr-1 mb-1" type="button" data-toggle="dropdown">...</button>
                                    <div class="dropdown-menu">
                                         <a href='.route('admin-addadmin-caleg', $item->user_id).' class="dropdown-item">
                                                Tambah Admin
                                        </a> 
                                    </div>
                                </div>
                            </div>
                        ';
                    })
                ->addColumn('fulladdress', function($item){
                    return $item->address."<br>". "DESA. ". $item->village."<br>"."KEC. ".$item->district."<br>". $item->regency."<br>".$item->province;
                })
                ->rawColumns(['fulladdress','photo','contact','action'])
                ->make(true);
    }
}
