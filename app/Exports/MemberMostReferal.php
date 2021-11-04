<?php

namespace App\Exports;

use App\User;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class MemberMostReferal implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        $userModel = new User();
        $members   = $userModel->getMemberReferal();
        $no = 1;
        return view('pages.report.member-referal-excel', [
            'no' => $no,
            'members' => $members,
            'userModel' => $userModel
        ]);
    }
  
}
