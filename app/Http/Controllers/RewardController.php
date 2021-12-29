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
        // jika anggota adalah admin
        $level = Auth::user()->level;
        if ($level != 0) {
            return view('pages.reward.admin');
        }elseif ($level == 0) {
            // jika anggota adalam anggota biasa
            return view('pages.reward.index');
        }

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

    public function getPoinByMonthDefaultByAccountMemberReferal($daterange)
    {
            $code = request()->code;
            $user = User::select('id')->where('code', $code)->first();

            if ($user != null) {
                # code...
                $user_id = $user->id;
                $gF = new GlobalProvider();
                $range = $daterange;
    
                if ($range != '') {
                    $date  = explode('+', $range);
                    $start = Carbon::parse($date[0])->format('Y-m-d');
                    $end   = Carbon::parse($date[1])->format('Y-m-d'); 
                }
    
                // jumlah hari
                $days = $gF->getDaysTotal($start, $end);
                $monthCategory = $gF->getMonthCategory($days);
                $mode = $gF->getPointMode($days);
                
                $referalModel = new Referal();
                $referalPoint = $referalModel->getPointByMemberAccountReferal($start, $end, $user_id);

                if ($referalPoint != null) {
                    # code...
                    $totalReferal = $referalPoint->total_input - $referalPoint->referal_inpoint;
        
                     $result = [
                        'days' => $days,
                        'monthCategory' => $monthCategory,
                        'point' => $gF->getPoint($totalReferal, $days),
                        'nominal' => $gF->decimalFormat($gF->getPointNominal($gF->getPoint($totalReferal, $days))),
                        'mode' => $mode,
                    ];
        
                    return $result;
                }else{

        
                     $result = [
                        'days' => $days,
                        'monthCategory' => $monthCategory,
                        'point' => 0,
                        'nominal' => 0,
                        'mode' => $mode,
                    ];
        
                    return $result;
                }

            }
    }

    public function getPoinByMonthByAccountMemberReferal()
    {
            $code = request()->code;
            $user = User::select('id')->where('code', $code)->first();

            if ($user != null) {
                # code...
                $user_id = $user->id;
                $gF = new GlobalProvider();
                if (request()->daterange != '') {

                    $range = request()->daterange;

                    $date  = explode('+', $range);
                    $start = Carbon::parse($date[0])->format('Y-m-d');
                    $end   = Carbon::parse($date[1])->format('Y-m-d'); 
                
                    // jumlah hari
                    $days = $gF->getDaysTotal($start, $end);
                    $monthCategory = $gF->getMonthCategoryMemberAdmin($days);
                    $mode = $gF->getPointModeMemberAdmin($days);
                    
                    $referalModel = new Referal();
                    $inputPoint = $referalModel->getPointByMemberAccount($start, $end, $user_id);
                    
                    if ($inputPoint != null) {
                        # code...
                        $totalInputMember = $inputPoint->total_input - $inputPoint->input_inpoint;
            
                         $result = [
                            'days' => $days,
                            'monthCategory' => $monthCategory,
                            'point' => $gF->getPointMemberAdmin($totalInputMember, $days),
                            'nominal' => $gF->decimalFormat($gF->getPointNominal($gF->getPointMemberAdmin($totalInputMember, $days))),
                            'mode' => $mode,
                        ];
            
                        return $result;
                    }else{
    
            
                         $result = [
                            'days' => $days,
                            'monthCategory' => $monthCategory,
                            'point' => 0,
                            'nominal' => 0,
                            'mode' => $mode,
                        ];
            
                        return $result;
                    }
                }
    


            }
    }
}
