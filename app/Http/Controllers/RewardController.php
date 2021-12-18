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

    public function getPoinByMonthDefaultByAccountMember($daterange)
    {
            $code = request()->code;
            $user = User::select('id','level')->where('code', $code)->first();
            $level = $user->level;

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
                $monthCategory = $level == 0 ? $gF->getMonthCategory($days) : $gF->getMonthCategoryMemberAdmin($days) ;
                $mode = $level == 0 ? $gF->getPointMode($days) : $gF->getPointModeMemberAdmin($days);
                
                $referalModel = new Referal();
                $inputPoint = $level == 0 ? $referalModel->getPointByMemberAccountReferal($start, $end, $user_id) : $referalModel->getPointByMemberAccount($start, $end, $user_id);

                if ($inputPoint != null) {
                                        
                    $inpoint = $level == 0 ? $inputPoint->referal_inpoint : $inputPoint->input_inpoint; 
                    $totalInputMember = $inputPoint->total_input - $inpoint;
                    
                    $nominal = $level == 0 ? $gF->decimalFormat($gF->getPointNominal($gF->getPoint($totalInputMember, $days))) : $gF->decimalFormat($gF->getPointNominal($gF->getPointMemberAdmin($totalInputMember, $days)));
                    $point   = $level == 0 ? $gF->getPoint($totalInputMember, $days) : $gF->getPointMemberAdmin($totalInputMember, $days);
        
                    $result = [
                        'level' => $level,
                        'days' => $days,
                        'monthCategory' => $monthCategory,
                        'point' => $point,
                        'totalData' => $totalInputMember,
                        'nominal' => $nominal,
                        'mode' => $mode,
                    ];
        
                    return $result;
                }else{

        
                     $result = [
                        'level' => $level,
                        'days' => $days,
                        'monthCategory' => $monthCategory,
                        'point' => 0,
                        'totalData' => 0,
                        'nominal' => 0,
                        'mode' => $mode,
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
                    $monthCategory = $level == 0 ? $gF->getMonthCategory($days) : $gF->getMonthCategoryMemberAdmin($days) ;
                    $mode = $level == 0 ? $gF->getPointMode($days) : $gF->getPointModeMemberAdmin($days);
                    
                    $referalModel = new Referal();
                    $inputPoint = $level == 0 ? $referalModel->getPointByMemberAccountReferal($start, $end, $user_id) : $referalModel->getPointByMemberAccount($start, $end, $user_id);
                    // $inputPoint = $referalModel->getPointByMemberAccount($start, $end, $user_id);
                    
                    if ($inputPoint != null) {
                        # code...
                        $inpoint = $level == 0 ? $inputPoint->referal_inpoint : $inputPoint->input_inpoint; 
                        $totalInputMember = $inputPoint->total_input - $inpoint;
                        
                        $nominal = $level == 0 ? $gF->decimalFormat($gF->getPointNominal($gF->getPoint($totalInputMember, $days))) : $gF->decimalFormat($gF->getPointNominal($gF->getPointMemberAdmin($totalInputMember, $days)));
                        $point   = $level == 0 ? $gF->getPoint($totalInputMember, $days) : $gF->getPointMemberAdmin($totalInputMember, $days);
            
                        $result = [
                            'level' => $level,
                            'days' => $days,
                            'monthCategory' => $monthCategory,
                            'point' => $point,
                            'totalData' => $totalInputMember,
                            'nominal' => $nominal,
                            'mode' => $mode,
                        ];
            
                        return $result;
                    }else{
    
            
                         $result = [
                            'level' => $level,
                            'days' => $days,
                            'monthCategory' => $monthCategory,
                            'point' => 0,
                            'totalData' => 0,
                            'nominal' => 0,
                            'mode' => $mode,
                        ];
            
                        return $result;
                    }
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
