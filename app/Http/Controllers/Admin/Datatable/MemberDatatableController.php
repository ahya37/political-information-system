<?php

namespace App\Http\Controllers\Admin\Datatable;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Yajra\DataTables\Facades\DataTables;

class MemberDatatableController extends Controller
{
    public function dTableMember()
    {
        $userModel = new User();
        $member = $userModel->getDataMemberWhereNikIsNotNull();
        return DataTables::of($member)
                    ->addColumn('photo', function($item){
                        return '
                        <a href="'.route('admin-profile-member', $item->id).'">
                            <img  class="rounded" width="40" src="'.asset('storage/'.$item->photo).'">
                        </a>
                        ';
                    })
                    ->addColumn('action', function($item){
                        if ($item->status == 1 ) {
                            return '
                                <span class="badge badge-success">Akun Aktif</span>
                            ';
                        }elseif($item->activate_token != null ){
                            return '
                                <span class="badge badge-warning">Akun Non Veririfikasi</span>
                            ';
                        }
                        return '
                            <div class="btn-group">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-sc-primary text-white dropdown-toggle mr-1 mb-1" type="button" data-toggle="dropdown">...</button>
                                    <div class="dropdown-menu">
                                         <a href='.route('admin-member-create-account',$item->id).' class="dropdown-item">
                                                Buat Akun
                                        </a> 
                                    </div>
                                </div>
                            </div>
                        ';
                    })
                     ->addColumn('input', function($item){
                        return $item->input;
                    })
                     ->addColumn('registered', function($item){
                        return date('d-m-Y', strtotime($item->created_at));
                    })
                    ->rawColumns(['photo','action','registered','input'])
                    ->make(true);
    }


    public function dTableMemberPotentialReferal()
    {
        $userModel = new User();
        $member = $userModel->getMemberReferal();
        return DataTables::of($member)
                    ->addColumn('photo', function($item){
                        return '
                        <a href="'.route('admin-profile-member', $item->id).'">
                            <img  class="rounded" width="40" src="'.asset('storage/'.$item->photo).'">
                        </a>
                        ';
                    })
                    ->addColumn('totalReferal', function($item){
                        return '
                         <div class="badge badge-pill badge-success">
                            '.$item->total.' 
                        </div>
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
                    ->addColumn('address', function($item){
                        return $item->district.','.$item->regency;
                    })
                    ->addColumn('action', function($item){
                        return '
                            <div class="btn-group">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-sc-primary text-white dropdown-toggle mr-1 mb-1" type="button" data-toggle="dropdown">...</button>
                                    <div class="dropdown-menu">
                                         <a href='.route('admin-member-by-referal',$item->id).' class="dropdown-item">
                                                Detail
                                        </a> 
                                    </div>
                                </div>
                            </div>
                        ';
                    })
                    ->rawColumns(['photo','action','totalReferal','address','contact'])
                    ->make(true);
    }
    public function dTableMemberPotentialInput()
    {
        $userModel = new User();
        $member = $userModel->getMemberInput();
        return DataTables::of($member)
                    ->addColumn('photo', function($item){
                        return '
                        <a href="'.route('admin-profile-member', $item->id).'">
                            <img  class="rounded" width="40" src="'.asset('storage/'.$item->photo).'">
                        </a>
                        ';
                    })
                    ->addColumn('totalInput', function($item){
                        return '
                         <div class="badge badge-pill badge-success">
                            '.$item->total.' 
                        </div>
                        ';
                    })
                     ->addColumn('address', function($item){
                        return $item->district.','.$item->regency;
                    })
                    ->addColumn('action', function($item){
                        return '
                            <div class="btn-group">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-sc-primary text-white dropdown-toggle mr-1 mb-1" type="button" data-toggle="dropdown">...</button>
                                    <div class="dropdown-menu">
                                         <a href='.route('admin-member-by-input',$item->id).' class="dropdown-item">
                                                Detail
                                        </a> 
                                    </div>
                                </div>
                            </div>
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
                    ->rawColumns(['photo','action','totalInput','address','contact'])
                    ->make(true);
    }
}
