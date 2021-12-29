<?php

namespace App\Http\Controllers;

use App\Referal;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Providers\GlobalProvider;
use App\User;
use Illuminate\Support\Facades\Auth;

class RewardController extends Controller
{
    public function index()
    {
            return view('pages.reward.index');

    }

    public function getPoinByMonthDefaultByAccountMember()
    {
            $code = request()->code;
            $user = User::select('id','level')->where('code', $code)->first();
            $level = $user->level;

            if ($user != null) {
                # code...
                $user_id = $user->id;
                $gF = new GlobalProvider();

                $start = date('2021-08-18');
                $end = date('Y-m-d');

                $date1 = date_create($start); 
                $date2 = date_create($end); 

                $interval = date_diff($date1, $date2); 

    
                // jumlah hari
                $days = $interval->d;
                $monthCategory = $interval->m;
                // $mode = $level == 0 ? $gF->getPointMode($days) : $gF->getPointModeMemberAdmin($days);
                
                $referalModel = new Referal();
                $inputPoint   =  $referalModel->getPointByMemberAccount($start, $end, $user_id);

                if ($inputPoint != null) {
                                        
                    $inpoint =  $inputPoint->input_inpoint; 
                    $totalInputMember = $inputPoint->total_input - $inpoint;
        
                    $result = [
                        'monthCategory' => $monthCategory,
                        'point' => $gF->calPointAdmin($totalInputMember),
                        'totalData' => $totalInputMember,
                        'nominal' => $gF->decimalFormat($gF->callNominal($gF->calPointAdmin($totalInputMember))),
                    ];
        
                    return $result;
                }else{

        
                     $result = [
                        'monthCategory' => $monthCategory,
                        'point' => 0,
                        'totalData' => 0,
                        'nominal' => 0,
                    ];
        
                    return $result;
                }

            }
    }

    public function getPoinByMonthByAccountMember()
    {
            $code = request()->code;
            $user = User::select('id','level')->where('code', $code)->first();
            $level = $user->level;

            if ($user != null) {

                    $user_id = $user->id;
                    $gF = new GlobalProvider();
                    $start = date('2021-08-18');
                    $end = request()->range;
                
                    // jumlah hari
                    $date1 = date_create($start); 
                    $date2 = date_create($end); 

                    $interval = date_diff($date1, $date2); 
        
                    // jumlah hari
                    $days = $interval->d;
                    $monthCategory = $interval->m;
                    
                    $referalModel = new Referal();
                    $inputPoint  =  $referalModel->getPointByMemberAccount($start, $end, $user_id);
                    // $inputPoint = $referalModel->getPointByMemberAccount($start, $end, $user_id);
                    
                    if ($inputPoint != null) {
                        $inpoint =  $inputPoint->input_inpoint; 
                        $totalInputMember = $inputPoint->total_input - $inpoint;
                        
            
                        $result = [
                            'monthCategory' => $monthCategory,
                            'point' => $gF->calPointAdmin($totalInputMember),
                            'totalData' => $totalInputMember,
                            'nominal' => $gF->decimalFormat($gF->callNominal($gF->calPointAdmin($totalInputMember))),
                        ];
            
                        return $result;
                    }else{
                         $result = [
                            'monthCategory' => $monthCategory,
                            'point' => 0,
                            'totalData' => 0,
                            'nominal' => 0,
                        ];
            
                        return $result;
                    }
                
    


            }
    }

    public function getPoinByMonthDefaultByAccountMemberReferal()
    {
            $code = request()->code;
            $user = User::select('id','level')->where('code', $code)->first();
            $level = $user->level;

            if ($user != null) {
                # code...
                $userId = $user->id;
                $gF = new GlobalProvider();

                $start = date('2021-08-18');
                $end = date('Y-m-d');
                
                $date1 = date_create($start); 
                $date2 = date_create($end); 

                $interval = date_diff($date1, $date2); 

    
                // jumlah hari
                $days = $interval->d;
                $monthCategory = $interval->m;
                // $mode = $level == 0 ? $gF->getPointMode($days) : $gF->getPointModeMemberAdmin($days);
                
                $referalModel = new Referal();
                $inputPoint   =  $referalModel->getPointByMemberAccountReferal($start, $end, $userId);

                if ($inputPoint != null) {
                                        
                    $inpoint =  $inputPoint->referal_inpoint; 
                    $totalReferal = $inputPoint->total_referal - $inpoint;
        
                    $result = [
                        'monthCategoryReferal' => $monthCategory,
                        'pointReferal' => $gF->calPoint($totalReferal),
                        'totalDataReferal' => $totalReferal,
                        'nominalReferal' => $gF->decimalFormat($gF->callNominal($gF->calPoint($totalReferal))),
                    ];
        
                    return $result;
                }else{

        
                     $result = [
                        'monthCategoryReferal' => $monthCategory,
                        'pointReferal' => 0,
                        'totalDataReferal' => 0,
                        'nominalReferal' => 0,
                    ];
        
                    return $result;
                }

            }
    }

    public function getPoinByMonthByAccountMemberReferal()
    {
            $code = request()->code;
            $user = User::select('id','level')->where('code', $code)->first();
            $level = $user->level;

            if ($user != null) {
                # code...
                $userId = $user->id;
                $gF = new GlobalProvider();

                $start = date('2021-08-18');
                $end = request()->range;


                $date1 = date_create($start); 
                $date2 = date_create($end); 

                $interval = date_diff($date1, $date2); 

    
                // jumlah hari
                $days = $interval->d;
                $monthCategory = $interval->m;
                // $mode = $level == 0 ? $gF->getPointMode($days) : $gF->getPointModeMemberAdmin($days);
                
                $referalModel = new Referal();
                $inputPoint   =  $referalModel->getPointByMemberAccountReferal($start, $end, $userId);

                if ($inputPoint != null) {
                                        
                    $inpoint =  $inputPoint->referal_inpoint; 
                    $totalReferal = $inputPoint->total_referal - $inpoint;
        
                    $result = [
                        'monthCategoryReferal' => $monthCategory,
                        'pointReferal' => $gF->calPoint($totalReferal),
                        'totalDataReferal' => $totalReferal,
                        'nominalReferal' => $gF->decimalFormat($gF->callNominal($gF->calPoint($totalReferal))),
                    ];
        
                    return $result;
                }else{

        
                     $result = [
                        'monthCategoryReferal' => $monthCategory,
                        'pointReferal' => 0,
                        'totalDataReferal' => 0,
                        'nominalReferal' => 0,
                    ];
        
                    return $result;
                }

            }
    }
}
