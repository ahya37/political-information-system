<?php

namespace App\Http\Controllers\Admin;

use PDF;
use App\Cost;
use App\User;
use App\Referal;
use App\CostLess;
use Carbon\Carbon;
use App\Models\Village;
use App\VoucherHistory;
use Illuminate\Support\Str;
use App\VoucherHistoryAdmin;
use Illuminate\Http\Request;
use App\DetailVoucherHistory;
use App\Providers\GlobalProvider;
use App\DetailVoucherHistoryAdmin;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class RewardController extends Controller
{
    public function index()
    {
        return view('pages.admin.reward.index');
    }

    public function indexAdmin()
    {
        return view('pages.admin.reward.admin');
    }

    public function getPoinByMonth()
    {
            $gF = new GlobalProvider();
            $start = date('2021-08-18');
            $end = request()->range;
            
            // $exRange = explode('-', $range);
            // $year    = $exRange[0];
            // $month   = $exRange[1];

            $date1 = date_create($start); 
            $date2 = date_create($end); 
            
            $interval = date_diff($date1, $date2); 
        
            // jumlah hari
            // $days = $interval;
            $rangeMonth = $interval->m;
            $mode = 0;

            $referalModel = new Referal();
            $referalPoint = $referalModel->getPointByThisMonth($start, $end);

            $data = [];
            foreach ($referalPoint as $key => $val) {
                $totalReferalByMember = $val->total_referal - $val->referal_inpoint;
                if ($gF->calPoint($totalReferalByMember) != 0 AND $gF->calPoint($totalReferalByMember) > 0) {
                    # code...
                    $data[] = [
                        'userId' => $val->id,
                        'photo' => $val->photo,
                        'name' => $val->name,
                        'totalReferal' =>  $totalReferalByMember,
                        'poin' => $gF->calPoint($totalReferalByMember),
                        'nominal' => $gF->decimalFormat($gF->callNominal($gF->calPoint($totalReferalByMember))),
                        'totalNominal' => $gF->callNominal($gF->calPoint($totalReferalByMember))
                        // 'days' => $days,
                        // 'date' => $start.'/'.$end,
                        // 'month'=> $monthCategory
                    ];
                }
                
            }
            $totalPoint = collect($data)->sum(function($q){
                return $q['poin'];
            });
            $totalNominal = collect($data)->sum(function($q){
                return  $q['totalNominal'];
            });
             $totalReferalCalculate = collect($data)->sum(function($q){
                return $q['totalReferal'];
            });

            $result = [
                'monthCategory' => $rangeMonth,
                'mode' => $mode,
                'totalReferalCalculate' => $gF->decimalFormat($totalReferalCalculate),
                'totalPoint' => $totalPoint,
                'totalNominal' => $gF->decimalFormat($totalNominal),
                'data' => $data
            ];
            return $result;
    }

    public function getPoinByMonthDefault()
    {
            $gF = new GlobalProvider();
            $start = date('2021-08-18');
            $end = date('Y-m-d');

            $date1 = date_create($start); 
            $date2 = date_create($end); 
            
            $interval = date_diff($date1, $date2); 
        
            // jumlah hari
            $days = $interval->d;
            $rangeMonth = $interval->m;
            $mode = $gF->getPointMode($days);

            $referalModel = new Referal();
            $referalPoint = $referalModel->getPoint($start, $end);

            $data = [];
            foreach ($referalPoint as $key => $val) {
                $detailVoucher    = DetailVoucherHistory::select('total_data')
                                    ->where('voucher_history_id', $val->voucher_id)
                                    ->where('type','Referal')
                                    ->get();
                $total_inpoint  = collect($detailVoucher)->sum(function($q){
                    return $q->total_data;
                });

                $totalReferalByMember = $val->total_referal - $total_inpoint;
                    if ($gF->calPoint($totalReferalByMember) != 0 AND $gF->calPoint($totalReferalByMember) > 0) {
                        $data[] = [
                            'userId' => $val->id,
                            'photo' => $val->photo,
                            'name' => $val->name,
                            'bank_number' => $val->bank_number,
                            'bank_name' => $val->bank_name,
                            'bank_owner' => $val->owner,
                            'totalReferal' =>  $totalReferalByMember,
                            'poin' => $gF->calPoint($totalReferalByMember),
                            'nominal' => $gF->decimalFormat($gF->callNominal($gF->calPoint($totalReferalByMember))),
                            'totalNominal' => $gF->callNominal($gF->calPoint($totalReferalByMember))
                            // 'days' => $days,
                            // 'date' => $start.'/'.$end,
                            // 'month' => $monthCategory
                        ];
                    }
                
            }
            $totalPoint = collect($data)->sum(function($q){
                return $q['poin'];
            });
            $totalNominal = collect($data)->sum(function($q){
                return  $q['totalNominal'];
            });
             $totalReferalCalculate = collect($data)->sum(function($q){
                return $q['totalReferal'];
            });

            $result = [
                'monthCategory' => $rangeMonth,
                'mode' => $mode,
                'totalReferalCalculate' => $gF->decimalFormat($totalReferalCalculate),
                'totalPoint' => $totalPoint,
                'totalNominal' => $gF->decimalFormat($totalNominal),
                'data' => $data
            ];
            return $result;
    }

    public function getPoinByMonthMemberAdmin()
    {
        $gF = new GlobalProvider();
            $start = date('2021-08-18');
            $end = request()->range;
            
            // $exRange = explode('-', $range);
            // $year    = $exRange[0];
            // $month   = $exRange[1];

            $date1 = date_create($start); 
            $date2 = date_create($end); 
            
            $interval = date_diff($date1, $date2); 
        
            // jumlah hari
            $days = 0;
            $rangeMonth = $interval->m;
            $mode = 0;

            $referalModel = new Referal();
            $referalPoint = $referalModel->getPointByThisMonthAdmin($start, $end);

            $data = [];
            foreach ($referalPoint as $key => $val) {
                 $detailVoucher    = DetailVoucherHistory::select('total_data')
                                    ->where('voucher_history_id', $val->voucher_id)
                                    ->where('type','Input')
                                    ->get();
                $total_inpoint  = collect($detailVoucher)->sum(function($q){
                    return $q->total_data;
                });

                $totalInputMember = $val->total_input - $total_inpoint;
                    if ($gF->calPointAdmin($totalInputMember) != 0 AND $gF->calPointAdmin($totalInputMember) > 0 ) {
                        $data[] = [
                            'userId' => $val->id,
                            'photo' => $val->photo,
                            'name' => $val->name,
                            'totalInput' =>  $totalInputMember,
                            'poin' => $gF->calPointAdmin($totalInputMember),
                            'nominal' => $gF->decimalFormat($gF->callNominal($gF->calPointAdmin($totalInputMember))),
                            'totalNominal' => $gF->callNominal($gF->calPointAdmin($totalInputMember))
                            // 'days' => $days,
                            // 'date' => $start.'/'.$end,
                            // 'month' => $monthCategory
                        ];
                    }
                
            }
            $totalPoint = collect($data)->sum(function($q){
                return $q['poin'];
            });
            $totalNominal = collect($data)->sum(function($q){
                return  $q['totalNominal'];
            });
             $totalInputCalculate = collect($data)->sum(function($q){
                return $q['totalInput'];
            });

            $result = [
                'monthCategory' => $rangeMonth,
                'mode' => $mode,
                'totalInputCalculate' => $gF->decimalFormat($totalInputCalculate),
                'totalPoint' => $totalPoint,
                'totalNominal' => $gF->decimalFormat($totalNominal),
                'data' => $data
            ];
            return $result;
    }

    public function getPoinByMonthMemberAdminDefaul()
    {
            $gF = new GlobalProvider();
            $start = date('2021-08-18');
            $end = date('Y-m-d');

            $date1 = date_create($start); 
            $date2 = date_create($end); 

            
            $interval = date_diff($date1, $date2); 
        
            // jumlah hari
            $days = $interval->d;
            $rangeMonth = $interval->m;
            $mode = $gF->getPointMode($days);

            $referalModel = new Referal();
            $referalPoint = $referalModel->getPointMemberAdmin($start, $end);
            

            $data = [];
            foreach ($referalPoint as $key => $val) {
                // get detail_voucher_history
                $detailVoucher    = DetailVoucherHistory::select('total_data')
                                    ->where('voucher_history_id', $val->voucher_id)
                                    ->where('type','Input')
                                    ->get();
                $total_inpoint  = collect($detailVoucher)->sum(function($q){
                    return $q->total_data;
                });
                $totalInputMember = $val->total_input - $total_inpoint;
                    if ($gF->calPointAdmin($totalInputMember) != 0 AND $gF->calPointAdmin($totalInputMember) > 0) {
                        $data[] = [
                            'userId' => $val->id,
                            'photo' => $val->photo,
                            'name' => $val->name,
                            'totalInput' =>  $totalInputMember,
                            'poin' => $gF->calPointAdmin($totalInputMember),
                            'nominal' => $gF->decimalFormat($gF->callNominal($gF->calPointAdmin($totalInputMember))),
                            'totalNominal' => $gF->callNominal($gF->calPointAdmin($totalInputMember))
                            // 'days' => $days,
                            // 'date' => $start.'/'.$end,
                            // 'month' => $monthCategory
                        ];
                    }
                
            }
            $totalPoint = collect($data)->sum(function($q){
                return $q['poin'];
            });
            $totalNominal = collect($data)->sum(function($q){
                return  $q['totalNominal'];
            });
             $totalInputCalculate = collect($data)->sum(function($q){
                return $q['totalInput'];
            });

            $result = [
                'monthCategory' => $rangeMonth,
                'mode' => $mode,
                'totalInputCalculate' => $gF->decimalFormat($totalInputCalculate),
                'totalPoint' => $totalPoint,
                'totalNominal' => $gF->decimalFormat($totalNominal),
                'data' => $data
            ];
            return $result;
    }


    public function CustomSaveVoucherHistory(Request $request)
    {
        $userId   = $request->userId;
        $point = preg_replace("/[^0-9]/", "", $request->point);
        $nominal = preg_replace("/[^0-9]/", "", $request->nominal);
        $referal = preg_replace("/[^0-9]/", "", $request->referal);
        $pointReq = $request->pointReq;

        $requestNominal = $pointReq * 100000;
        $requestTotalData = $pointReq * 50;

        $vhModel  = new VoucherHistory();
        $dvhModel = new DetailVoucherHistory();

        // jika pointReq = point
        if ($pointReq == $point) {

            // cek jika sudah ada voucher_history denga userId tersebut
            $cekVh = $vhModel->where('user_id', $userId)->first();
            // jika beulm ada, create detailnya dan update vh nya
            if ($cekVh == null) {
                $saveVh = $vhModel->create([
                     'user_id' => $userId,
                     'total_point' => 0,
                     'total_nominal' => 0,
                     'total_data' => 0,
                 ]);

                $saveDetailVh =  DetailVoucherHistory::create([
                    'voucher_history_id' => $saveVh->id,
                    'point' => $pointReq,
                    'nominal' => $requestNominal,
                    'total_data' => $requestTotalData,
                    'code' => Str::random(5),
                    'type' => 'Referal'
                ]);

                $detailVh = $dvhModel->where('voucher_history_id', $saveVh->id)->get();
                $total_point = collect($detailVh)->sum(function($q){
                    return $q->point;
                });
                $total_nominal = collect($detailVh)->sum(function($q){
                    return $q->nominal;
                });
                $total_data = collect($detailVh)->sum(function($q){
                    return $q->total_data;
                });
                $updateVh = $vhModel->where('id', $saveVh->id)->first();
                $updateVh->update([
                    'total_point' => $total_point,
                    'total_nominal' => $total_nominal,
                    'total_data' => $total_data
                ]);

            }else{
                // jika sudah ada, buatkan dan create detailnya
                // create voucher_history
                // create_detail_voucher_history
                $cekVhUpdte = $vhModel->where('user_id', $userId)->first();
                $saveDetailVh =  DetailVoucherHistory::create([
                    'voucher_history_id' => $cekVhUpdte->id,
                    'point' => $pointReq,
                    'nominal' => $requestNominal,
                    'total_data' => $requestTotalData,
                    'code' => Str::random(5),
                    'type' => 'Referal'
                ]);

                $detailVh = $dvhModel->where('voucher_history_id', $cekVhUpdte->id)->get();
                $total_point = collect($detailVh)->sum(function($q){
                    return $q->point;
                });
                $total_nominal = collect($detailVh)->sum(function($q){
                    return $q->nominal;
                });
                $total_data = collect($detailVh)->sum(function($q){
                    return $q->total_data;
                });
                $updateVh = $vhModel->where('id', $cekVhUpdte->id)->first();
                $updateVh->update([
                    'total_point' => $total_point,
                    'total_nominal' => $total_nominal,
                    'total_data' => $total_data
                ]);

                // simpan ke table cost_less
                // $updateVh->user_id
                // $updateVh->total_nominal
                // $date-> tanggal saat create
                // perkiraan = VOUCHER = 4
                // uraian = VOUCHER REFERAL = 3

                 // simpan ke tb cost_less
                $user = User::select('name','village_id')->where('id', $userId)->first();
                $village = Village::with(['district.regency.province'])->where('id', $user->village_id)->first();
                CostLess::create([
                    'date' => date('Y-m-d'),
                    'forcest_id' => 4, // voucher
                    'forecast_desc_id' => $request->type == 'referal' ? 3 : 6, // voucher referal atau voucher admina
                    'received_name' => $user->name,
                    'address' => 'DS. '.$village->name.', KEC. '.$village->district->name.','. $village->district->regency->name.','.$village->district->regency->province->name,
                    'nominal' => $total_nominal,
                    'file' => NULL,
                ]);


            }
            
        }elseif ($pointReq < $point) {

             // cek jika sudah ada voucher_history denga userId tersebut
            $cekVh = $vhModel->where('user_id', $userId)->first();
            // jika beulm ada, create detailnya dan update vh nya
            if ($cekVh == null) {
                $saveVh = $vhModel->create([
                     'user_id' => $userId,
                     'total_point' => 0,
                     'total_nominal' => 0,
                     'total_data' => 0,
                 ]);

                $saveDetailVh =  DetailVoucherHistory::create([
                    'voucher_history_id' => $saveVh->id,
                    'point' => $pointReq,
                    'nominal' => $requestNominal,
                    'total_data' => $requestTotalData,
                    'code' => Str::random(5),
                    'type' => 'Referal'
                ]);

                $detailVh = $dvhModel->where('voucher_history_id', $saveVh->id)->get();
                $total_point = collect($detailVh)->sum(function($q){
                    return $q->point;
                });
                $total_nominal = collect($detailVh)->sum(function($q){
                    return $q->nominal;
                });
                $total_data = collect($detailVh)->sum(function($q){
                    return $q->total_data;
                });
                $updateVh = $vhModel->where('id', $saveVh->id)->first();
                $updateVh->update([
                    'total_point' => $total_point,
                    'total_nominal' => $total_nominal,
                    'total_data' => $total_data
                ]);

            }else{
                // jika sudah ada, buatkan dan create detailnya
                // create voucher_history
                // create_detail_voucher_history
                $cekVhUpdte = $vhModel->where('user_id', $userId)->first();
                $saveDetailVh =  DetailVoucherHistory::create([
                    'voucher_history_id' => $cekVhUpdte->id,
                    'point' => $pointReq,
                    'nominal' => $requestNominal,
                    'total_data' => $requestTotalData,
                    'code' => Str::random(5),
                    'type' => 'Referal'
                ]);

                $detailVh = $dvhModel->where('voucher_history_id', $cekVhUpdte->id)->get();
                $total_point = collect($detailVh)->sum(function($q){
                    return $q->point;
                });
                $total_nominal = collect($detailVh)->sum(function($q){
                    return $q->nominal;
                });
                $total_data = collect($detailVh)->sum(function($q){
                    return $q->total_data;
                });
                $updateVh = $vhModel->where('id', $cekVhUpdte->id)->first();
                $updateVh->update([
                    'total_point' => $total_point,
                    'total_nominal' => $total_nominal,
                    'total_data' => $total_data
                ]);
            }

            // simpan ke tb cost_less
                $user = User::select('name','village_id')->where('id', $userId)->first();
                $village = Village::with(['district.regency.province'])->where('id', $user->village_id)->first();
                CostLess::create([
                    'date' => date('Y-m-d'),
                    'forcest_id' => 4, // voucher
                    'forecast_desc_id' => $request->type == 'referal' ? 3 : 6, // voucher referal atau voucher admina
                    'received_name' => $user->name,
                    'address' => 'DS. '.$village->name.', KEC. '.$village->district->name.','. $village->district->regency->name.','.$village->district->regency->province->name,
                    'nominal' => $total_nominal,
                    'file' => NULL,
                ]);

        }

        return redirect()->back()->with(['success' => 'Voucher telah diberikan']);
    }

    public function CustomSaveVoucherHistoryAdmin(Request $request)
    {
        $userId   = $request->userId;
        $point = preg_replace("/[^0-9]/", "", $request->point);
        $nominal = preg_replace("/[^0-9]/", "", $request->nominal);
        $referal = preg_replace("/[^0-9]/", "", $request->referal);
        $pointReq = $request->pointReq;

        $requestNominal = $pointReq * 100000;
        $requestTotalData = $pointReq * 200;

        $vhModel  = new VoucherHistory();
        $dvhModel = new DetailVoucherHistory();

        // jika pointReq = point
        if ($pointReq == $point) {

            // cek jika sudah ada voucher_history denga userId tersebut
            $cekVh = $vhModel->where('user_id', $userId)->first();
            // jika beulm ada, create detailnya dan update vh nya
            if ($cekVh == null) {
                $saveVh = $vhModel->create([
                     'user_id' => $userId,
                     'total_point' => 0,
                     'total_nominal' => 0,
                     'total_data' => 0,
                 ]);

                $saveDetailVh =  $dvhModel->create([
                    'voucher_history_id' => $saveVh->id,
                    'point' => $pointReq,
                    'nominal' => $requestNominal,
                    'total_data' => $requestTotalData,
                    'code' => Str::random(5),
                    'type' => 'Input'
                ]);

                $detailVh = $dvhModel->where('voucher_history_id', $saveVh->id)->get();
                $total_point = collect($detailVh)->sum(function($q){
                    return $q->point;
                });
                $total_nominal = collect($detailVh)->sum(function($q){
                    return $q->nominal;
                });
                $total_data = collect($detailVh)->sum(function($q){
                    return $q->total_data;
                });
                $updateVh = $vhModel->where('id', $saveVh->id)->first();
                $updateVh->update([
                    'total_point' => $total_point,
                    'total_nominal' => $total_nominal,
                    'total_data' => $total_data
                ]);

            }else{
                // jika sudah ada, buatkan dan create detailnya
                // create voucher_history
                // create_detail_voucher_history
                $cekVhUpdte = $vhModel->where('user_id', $userId)->first();
                $saveDetailVh =  $dvhModel->create([
                    'voucher_history_id' => $cekVhUpdte->id,
                    'point' => $pointReq,
                    'nominal' => $requestNominal,
                    'total_data' => $requestTotalData,
                    'code' => Str::random(5),
                    'type' => 'Input'
                ]);

                $detailVh = $dvhModel->where('voucher_history_id', $cekVhUpdte->id)->get();
                $total_point = collect($detailVh)->sum(function($q){
                    return $q->point;
                });
                $total_nominal = collect($detailVh)->sum(function($q){
                    return $q->nominal;
                });
                $total_data = collect($detailVh)->sum(function($q){
                    return $q->total_data;
                });
                $updateVh = $vhModel->where('id', $cekVhUpdte->id)->first();
                $updateVh->update([
                    'total_point' => $total_point,
                    'total_nominal' => $total_nominal,
                    'total_data' => $total_data
                ]);
            }

            // simpan ke tb cost_less
                $user = User::select('name','village_id')->where('id', $userId)->first();
                $village = Village::with(['district.regency.province'])->where('id', $user->village_id)->first();
                CostLess::create([
                    'date' => date('Y-m-d'),
                    'forcest_id' => 4, // voucher
                    'forecast_desc_id' => $request->type == 'referal' ? 3 : 6, // voucher referal atau voucher admina
                    'received_name' => $user->name,
                    'address' => 'DS. '.$village->name.', KEC. '.$village->district->name.','. $village->district->regency->name.','.$village->district->regency->province->name,
                    'nominal' => $total_nominal,
                    'file' => NULL,
                ]);
            
        }elseif ($pointReq < $point) {

             // cek jika sudah ada voucher_history denga userId tersebut
            $cekVh = $vhModel->where('user_id', $userId)->first();
            // jika beulm ada, create detailnya dan update vh nya
            if ($cekVh == null) {
                $saveVh = $vhModel->create([
                     'user_id' => $userId,
                     'total_point' => 0,
                     'total_nominal' => 0,
                     'total_data' => 0,
                 ]);

                $saveDetailVh =  $dvhModel->create([
                    'voucher_history_id' => $saveVh->id,
                    'point' => $pointReq,
                    'nominal' => $requestNominal,
                    'total_data' => $requestTotalData,
                    'code' => Str::random(5),
                    'type' => 'Input'
                ]);

                $detailVh = $dvhModel->where('voucher_history_id', $saveVh->id)->get();
                $total_point = collect($detailVh)->sum(function($q){
                    return $q->point;
                });
                $total_nominal = collect($detailVh)->sum(function($q){
                    return $q->nominal;
                });
                $total_data = collect($detailVh)->sum(function($q){
                    return $q->total_data;
                });
                $updateVh = $vhModel->where('id', $saveVh->id)->first();
                $updateVh->update([
                    'total_point' => $total_point,
                    'total_nominal' => $total_nominal,
                    'total_data' => $total_data
                ]);

            }else{
                // jika sudah ada, buatkan dan create detailnya
                // create voucher_history
                // create_detail_voucher_history
                $cekVhUpdte = $vhModel->where('user_id', $userId)->first();
                $saveDetailVh =  $dvhModel->create([
                    'voucher_history_id' => $cekVhUpdte->id,
                    'point' => $pointReq,
                    'nominal' => $requestNominal,
                    'total_data' => $requestTotalData,
                    'code' => Str::random(5),
                    'type' => 'Input'
                ]);

                $detailVh = $dvhModel->where('voucher_history_id', $cekVhUpdte->id)->get();
                $total_point = collect($detailVh)->sum(function($q){
                    return $q->point;
                });
                $total_nominal = collect($detailVh)->sum(function($q){
                    return $q->nominal;
                });
                $total_data = collect($detailVh)->sum(function($q){
                    return $q->total_data;
                });
                $updateVh = $vhModel->where('id', $cekVhUpdte->id)->first();
                $updateVh->update([
                    'total_point' => $total_point,
                    'total_nominal' => $total_nominal,
                    'total_data' => $total_data
                ]);
            }

            // simpan ke tb cost_less
                $user = User::select('name','village_id')->where('id', $userId)->first();
                $village = Village::with(['district.regency.province'])->where('id', $user->village_id)->first();
                CostLess::create([
                    'date' => date('Y-m-d'),
                    'forcest_id' => 4, // voucher
                    'forecast_desc_id' => $request->type == 'referal' ? 3 : 6, // voucher referal atau voucher admina
                    'received_name' => $user->name,
                    'address' => 'DS. '.$village->name.', KEC. '.$village->district->name.','. $village->district->regency->name.','.$village->district->regency->province->name,
                    'nominal' => $total_nominal,
                    'file' => NULL,
                ]);

        }

        return redirect()->back()->with(['success' => 'Voucher telah diberikan']);
    }

    public function saveVoucherHistory()
    {
        
        $token = request()->_token;

        if ($token != null) {
            $userId = request()->userId;
            $point = preg_replace("/[^0-9]/", "", request()->point);
            $nominal = preg_replace("/[^0-9]/", "", request()->nominal);
            $referal = preg_replace("/[^0-9]/", "", request()->referal);

            $requestNominal = $point * 100000;
            $requestTotalData = $point * 50;

            $vhModel  = new VoucherHistory();
            $dvhModel = new DetailVoucherHistory();

            $cekVh = $vhModel->where('user_id', $userId)->first();
            // jika beulm ada, create detailnya dan update vh nya
            if ($cekVh == null) {
                $saveVh = $vhModel->create([
                     'user_id' => $userId,
                     'total_point' => 0,
                     'total_nominal' => 0,
                     'total_data' => 0,
                 ]);

                $saveDetailVh =  DetailVoucherHistory::create([
                    'voucher_history_id' => $saveVh->id,
                    'point' => $point,
                    'nominal' => $requestNominal,
                    'total_data' => $requestTotalData,
                    'code' => Str::random(5),
                    'type' => 'Referal'
                ]);

                $detailVh = $dvhModel->where('voucher_history_id', $saveVh->id)->get();
                $total_point = collect($detailVh)->sum(function($q){
                    return $q->point;
                });
                $total_nominal = collect($detailVh)->sum(function($q){
                    return $q->nominal;
                });
                $total_data = collect($detailVh)->sum(function($q){
                    return $q->total_data;
                });
                $updateVh = $vhModel->where('id', $saveVh->id)->first();
                $updateVh->update([
                    'total_point' => $total_point,
                    'total_nominal' => $total_nominal,
                    'total_data' => $total_data
                ]);

            }else{
                // jika sudah ada, buatkan dan create detailnya
                // create voucher_history
                // create_detail_voucher_history
                $cekVhUpdte = $vhModel->where('user_id', $userId)->first();
                $saveDetailVh =  DetailVoucherHistory::create([
                    'voucher_history_id' => $cekVhUpdte->id,
                    'point' => $point,
                    'nominal' => $requestNominal,
                    'total_data' => $requestTotalData,
                    'code' => Str::random(5),
                    'type' => 'Referal'
                ]);

                $detailVh = $dvhModel->where('voucher_history_id', $cekVhUpdte->id)->get();
                $total_point = collect($detailVh)->sum(function($q){
                    return $q->point;
                });
                $total_nominal = collect($detailVh)->sum(function($q){
                    return $q->nominal;
                });
                $total_data = collect($detailVh)->sum(function($q){
                    return $q->total_data;
                });
                $updateVh = $vhModel->where('id', $cekVhUpdte->id)->first();
                $updateVh->update([
                    'total_point' => $total_point,
                    'total_nominal' => $total_nominal,
                    'total_data' => $total_data
                ]);
            }

            // simpan ke tb cost_less
                $user = User::select('name','village_id')->where('id', $userId)->first();
                $village = Village::with(['district.regency.province'])->where('id', $user->village_id)->first();
                CostLess::create([
                    'date' => date('Y-m-d'),
                    'forcest_id' => 4, // voucher
                    'forecast_desc_id' => 3, // voucher referal atau voucher admina
                    'received_name' => $user->name,
                    'address' => 'DS. '.$village->name.', KEC. '.$village->district->name.','. $village->district->regency->name.','.$village->district->regency->province->name,
                    'nominal' => $total_nominal,
                    'file' => NULL,
                ]);


          if ($saveDetailVh) {
                $success = true;
                $message = 'Voucher telah diberikan';

            }else{
                $success = false;
                $message = 'Voucher gagal diberikan';
            }
            return response()->json([
                'success' => $success,
                'message' => $message,
            ]);
            
        }

    }

    public function saveVoucherHistoryAdmin()
    {
        
       $token = request()->_token;

        if ($token != null) {
            $userId = request()->userId;
            $point = preg_replace("/[^0-9]/", "", request()->point);
            $nominal = preg_replace("/[^0-9]/", "", request()->nominal);
            $referal = preg_replace("/[^0-9]/", "", request()->referal);

            $requestNominal = $point * 100000;
            $requestTotalData = $point * 200;

            $vhModel  = new VoucherHistory();
            $dvhModel = new DetailVoucherHistory();

            $cekVh = $vhModel->where('user_id', $userId)->first();
            // jika beulm ada, create detailnya dan update vh nya
            if ($cekVh == null) {
                $saveVh = $vhModel->create([
                     'user_id' => $userId,
                     'total_point' => 0,
                     'total_nominal' => 0,
                     'total_data' => 0,
                 ]);

                $saveDetailVh =   $dvhModel->create([
                    'voucher_history_id' => $saveVh->id,
                    'point' => $point,
                    'nominal' => $requestNominal,
                    'total_data' => $requestTotalData,
                    'code' => Str::random(5),
                    'type' => 'Input'
                ]);

                $detailVh = $dvhModel->where('voucher_history_id', $saveVh->id)->get();
                $total_point = collect($detailVh)->sum(function($q){
                    return $q->point;
                });
                $total_nominal = collect($detailVh)->sum(function($q){
                    return $q->nominal;
                });
                $total_data = collect($detailVh)->sum(function($q){
                    return $q->total_data;
                });
                $updateVh = $vhModel->where('id', $saveVh->id)->first();
                $updateVh->update([
                    'total_point' => $total_point,
                    'total_nominal' => $total_nominal,
                    'total_data' => $total_data
                ]);

            }else{
                // jika sudah ada, buatkan dan create detailnya
                // create voucher_history
                // create_detail_voucher_history
                $cekVhUpdte = $vhModel->where('user_id', $userId)->first();
                $saveDetailVh =  $dvhModel->create([
                    'voucher_history_id' => $cekVhUpdte->id,
                    'point' => $point,
                    'nominal' => $requestNominal,
                    'total_data' => $requestTotalData,
                    'code' => Str::random(5),
                    'type' => 'Input'
                ]);

                $detailVh = $dvhModel->where('voucher_history_id', $cekVhUpdte->id)->get();
                $total_point = collect($detailVh)->sum(function($q){
                    return $q->point;
                });
                $total_nominal = collect($detailVh)->sum(function($q){
                    return $q->nominal;
                });
                $total_data = collect($detailVh)->sum(function($q){
                    return $q->total_data;
                });
                $updateVh = $vhModel->where('id', $cekVhUpdte->id)->first();
                $updateVh->update([
                    'total_point' => $total_point,
                    'total_nominal' => $total_nominal,
                    'total_data' => $total_data
                ]);
            }

            // simpan ke tb cost_less
                $user = User::select('name','village_id')->where('id', $userId)->first();
                $village = Village::with(['district.regency.province'])->where('id', $user->village_id)->first();
                CostLess::create([
                    'date' => date('Y-m-d'),
                    'forcest_id' => 4, // voucher
                    'forecast_desc_id' => 6, // voucher referal atau voucher admina
                    'received_name' => $user->name,
                    'address' => 'DS. '.$village->name.', KEC. '.$village->district->name.','. $village->district->regency->name.','.$village->district->regency->province->name,
                    'nominal' => $total_nominal,
                    'file' => NULL,
                ]);

          if ($saveDetailVh) {
                $success = true;
                $message = 'Voucher telah diberikan';

            }else{
                $success = false;
                $message = 'Voucher gagal diberikan';
            }
            return response()->json([
                'success' => $success,
                'message' => $message,
            ]);
            
        }

    }

    public function listRewardReferal()
    {
    //     $referalModel = new Referal();
    //     $data = $referalModel->getTotalVoucherReferal();
    //     // total poin
    //     $total_point = collect($data)->sum(function($q){
    //         return $q->total_point;
    //     });

    //     // total nominal
    //      $total_nominal = collect($data)->sum(function($q){
    //         return $q->total_nominal;
    //     });
        
    //     // total referal
    //     $total_referal = collect($data)->sum(function($q){
    //        return $q->total_data;
    //    });

        return view('pages.admin.reward.history-referal');
    }

    public function listRewardAdmin()
    {
        return view('pages.admin.reward.history-admin');
    }

    public function dtListRewardReferal()
    {
        
        $vhModel = new VoucherHistory();
        $reward  = $vhModel->getListVoucher();
        if (request()->ajax()) 
        {
            return DataTables::of($reward)
                        ->addColumn('photo', function($item){
                                return '
                                    <img  class="rounded" width="40" src="'.asset('storage/'.$item->photo).'">
                                ';
                            })
                        ->addColumn('address', function($item){
                                return '
                                '.$item->village.',<br>
                                '.$item->district.'<br>
                                '.$item->regency.'<br>
                                '.$item->province.'
                                ';
                            })
                        ->addColumn('totalPoint', function($item){
                                 return '<div class="badge badge-pill badge-success">
                                        '.$item->total_point.'
                                    </div>
                                       ';
                            })
                        ->addColumn('totalNominal', function($item){
                                $gF = new GlobalProvider();
                                 return '<div class="badge badge-pill badge-success">
                                        Rp. '.$gF->decimalFormat($item->total_nominal).'
                                    </div>
                                       ';
                            })
                        ->addColumn('totalData', function($item){
                                $gF = new GlobalProvider();
                                 return '<div class="badge badge-pill badge-success">
                                        '.$gF->decimalFormat($item->total_data).'
                                    </div>
                                       ';
                            })
                        
                        ->addColumn('action', function($item){
                                 return '<a href="'.route('admin-detail-listrewardreferal', $item->id).'" class="btn btn-sm btn-sc-primary text-white">Detail</a>';
                            })
                        
                        ->rawColumns(['photo','address','time','totalPoint','totalNominal','action','totalData'])
                        ->make(true);
        }
    }

     public function dtListRewardAdmin()
    {
        
        $vhModel = new VoucherHistoryAdmin();
        $reward  = $vhModel->getListVoucher();
        if (request()->ajax()) 
        {
            return DataTables::of($reward)
                        ->addColumn('photo', function($item){
                                return '
                                    <img  class="rounded" width="40" src="'.asset('storage/'.$item->photo).'">
                                ';
                            })
                        ->addColumn('address', function($item){
                                return '
                                '.$item->village.',<br>
                                '.$item->district.'<br>
                                '.$item->regency.'<br>
                                '.$item->province.'
                                ';
                            })
                        ->addColumn('totalPoint', function($item){
                                 return '<div class="badge badge-pill badge-success">
                                        '.$item->total_point.'
                                    </div>
                                       ';
                            })
                        ->addColumn('totalNominal', function($item){
                                $gF = new GlobalProvider();
                                 return '<div class="badge badge-pill badge-success">
                                        Rp. '.$gF->decimalFormat($item->total_nominal).'
                                    </div>
                                       ';
                            })
                         ->addColumn('totalData', function($item){
                                $gF = new GlobalProvider();
                                 return '<div class="badge badge-pill badge-success">
                                        '.$gF->decimalFormat($item->total_data).'
                                    </div>
                                       ';
                            })
                        
                        ->addColumn('action', function($item){
                                 return '<a href="'.route('admin-detail-listrewardadmin', $item->id).'" class="btn btn-sm btn-sc-primary text-white">Detail</a>';
                            })
                        
                        ->rawColumns(['photo','address','time','totalPoint','totalNominal','action','totalData'])
                        ->make(true);
        }
    }

    public function detailListReward($id)
    {
        $gF = new GlobalProvider();
        $dvhModel = new VoucherHistory();
        $member   = $dvhModel->getMember($id);
        $listVucher = DetailVoucherHistory::where('voucher_history_id', $id)
                    ->where('type','Referal')    
                    ->orderBy('created_at','desc')->get();
        return view('pages.admin.reward.detail-history-referal', compact('member','listVucher','gF'));
    }

    public function detailListRewardAdmin($id)
    {
        $gF = new GlobalProvider();
        $dvhModel = new VoucherHistory();
        $member   = $dvhModel->getMember($id);
        $listVucher = DetailVoucherHistory::where('voucher_history_id', $id)
                    ->where('type','Input')    
                    ->orderBy('created_at','desc')->get();
        return view('pages.admin.reward.detail-history-admin', compact('member','listVucher','gF'));
    }

    public function downloadVoucherAdmin($id)
    {
        $gF = new GlobalProvider();

        $voucher = DetailVoucherHistoryAdmin::where('id', $id)->first();
        $customPaper = array(0,0,400.00,283.50);
        $pdf = PDF::LoadView('pages.admin.report.voucher', compact('voucher','gF'))->setPaper($customPaper, 'landscape');
        return $pdf->download('voucher-input-'.$voucher->code.'.pdf');
    }

    public function downloadVoucherReferal($id)
    {
        $gF = new GlobalProvider();

        $voucher = DetailVoucherHistory::where('id', $id)->first();
        $customPaper = array(0,0,400.00,283.50);
        $pdf = PDF::LoadView('pages.admin.report.voucher-referal', compact('voucher','gF'))->setPaper($customPaper, 'landscape');;
        return $pdf->download('voucher-referal-'.$voucher->code.'.pdf');
    }

    public function uploadTFVoucher(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:png,jpg,jpeg',
            'nominal' => 'required'
        ]);


        $voucherDetail = DetailVoucherHistory::where('id', $request->VoucherId)->first();
        $voucher_history_id = $voucherDetail->voucher_history_id;

        $voucherModel   = new VoucherHistory();
        $user          =  $voucherModel->getMember($voucherDetail->voucher_history_id);

        $fileImage = $request->file->store('assets/voucher/tf','public');

        $voucherDetail->update([
            'tf' => $fileImage,
        ]);
            
            // simpan ke table cost
            // Cost::create([
            //     'cost_category_id' => 1,
            //     'to' => $user->id,
            //     'nominal' => $request->nominal
            // ]);        

        return redirect()->back()->with(['success' => 'Berhasil upload']);

    }

    public function ReportVoucher()
    {
        $gF = new GlobalProvider();
        $voucherModel = new VoucherHistory();
        $dataVoucher  = $voucherModel->getListMember();
        $total_point_all  = collect($dataVoucher)->sum(function($q){
            return $q->total_point;
        });
        $total_data_all  = collect($dataVoucher)->sum(function($q){
            return $q->total_data;
        });
        $total_nominal_all  = collect($dataVoucher)->sum(function($q){
            return $q->total_nominal;
        });

        $data = [];
        $no = 1;
        foreach ($dataVoucher as $val) {
            $detailVoucher = DetailVoucherHistory::select('id','nominal','point','total_data','type','created_at')
                            ->where('voucher_history_id', $val->voucher_id)
                            ->get();
            $total_point = collect($detailVoucher)->sum(function($q){
                return $q->point;
            });
            $total_data = collect($detailVoucher)->sum(function($q){
                return $q->total_data;
            });
            $total_nominal = collect($detailVoucher)->sum(function($q){
                return $q->nominal;
            });
            
            $data[] = [
                'total_data' => $total_data,
                'total_nominal' => $total_nominal,
                'total_point' => $total_point,
                'voucher_id' => $val->voucher_id,
                'user_id' => $val->user_id,
                'name' => $val->name,
                'datapoint' => $detailVoucher
            ];
        }


        return view('pages.admin.reward.report-voucher', compact('data','gF','no','total_point_all','total_data_all','total_nominal_all'));
    }

    public function getTotalListVoucherReferalByMount(Request $request)
    {
        $type = $request->input('type');
         $data = DB::table('users as a')
                ->select(DB::raw('SUM(g.total_data) as total_data'), DB::raw('SUM(g.nominal) as total_nominal'),DB::raw('SUM(g.point) as total_point'))
                ->join('voucher_history as b','a.id','b.user_id')
                ->join('detail_voucher_history as g','b.id','g.voucher_history_id')
                ->where('g.type', $type);

                
        if($request->input('date') != null AND $request->input('year') != null){
            $data->whereMonth('b.created_at', $request->date);
            $data->whereYear('b.created_at', $request->year);
        }

        $data = $data->groupBy('b.id','a.photo','a.name');

        $data = $data->get();

        // total poin
        $total_point = collect($data)->sum(function($q){
            return $q->total_point;
        });

        // total nominal
         $total_nominal = collect($data)->sum(function($q){
            return $q->total_nominal;
        });
        
        // total referal
        $total_referal = collect($data)->sum(function($q){
           return $q->total_data;
       });

       return response()->json([
                'total_point' => $total_point,
                'total_nominal' => $total_nominal,
                'total_referal' => $total_referal,
            ]);

    }


    public function getListVoucherReferalByMount(Request $request)
    {
        $gF = new GlobalProvider();
        $orderBy = 'a.name';

        $data = DB::table('users as a')
                ->select('b.id','a.photo','a.name','c.name as village','d.name as district','e.name as regency','f.name as province',
                DB::raw('SUM(g.total_data) as total_data'), DB::raw('SUM(g.nominal) as total_nominal'),DB::raw('SUM(g.point) as total_point'))
                ->join('voucher_history as b','a.id','b.user_id')
                ->join('villages as c','a.village_id','c.id')
                ->join('districts as d','c.district_id','d.id')
                ->join('regencies as e','d.regency_id','e.id')
                ->join('provinces as f','e.province_id','f.id')
                ->join('detail_voucher_history as g','b.id','g.voucher_history_id')
                ->where('g.type','Referal');

        if($request->input('search.value')!=null){
            $data = $data->where(function($q)use($request){
                $q->whereRaw('LOWER(a.name) like ? ',['%'.strtolower($request->input('search.value')).'%'])
                ->orWhereRaw('LOWER(c.name) like ? ',['%'.strtolower($request->input('search.value')).'%'])
                ;
            });
        }

                
        if($request->input('date') != null AND $request->input('year') != null){
            $data->whereMonth('b.created_at', $request->date);
            $data->whereYear('b.created_at', $request->year);
        }

        $data = $data->groupBy('b.id','a.photo','a.name','c.name','d.name','e.name','f.name');

        $recordsFiltered = $data->count();
        if($request->input('length')!=-1) $data = $data->skip($request->input('start'))->take($request->input('length'));
        $data = $data->orderBy($orderBy,$request->input('order.0.dir'))->get();
        $recordsTotal = $data->count();
        
        $result = [];
        foreach ($data as $val) {
            $result[] = [
                'id' => $val->id,
                'photo' => $val->photo,
                'name' => $val->name,
                'address' => 'DS, ' .$val->village.', KEC. ' .$val->district.', '.$val->regency.', '.$val->province,
                'total_data' => $gF->decimalFormat($val->total_data),
                'total_nominal' => $gF->decimalFormat($val->total_nominal),
                'total_point' => $gF->decimalFormat($val->total_point)
            ];
        }

        return response()->json([
                'draw'=>$request->input('draw'),
                'recordsTotal'=>$recordsTotal,
                'recordsFiltered'=>$recordsFiltered,
                'data'=> $result,
            ]);

    }

    public function getListVoucherAdminByMount(Request $request)
    {
        $gF = new GlobalProvider();
        $orderBy = 'a.name';

        $data = DB::table('users as a')
                ->select('b.id','a.photo','a.name','c.name as village','d.name as district','e.name as regency','f.name as province',
                DB::raw('SUM(g.total_data) as total_data'), DB::raw('SUM(g.nominal) as total_nominal'),DB::raw('SUM(g.point) as total_point'))
                ->join('voucher_history as b','a.id','b.user_id')
                ->join('villages as c','a.village_id','c.id')
                ->join('districts as d','c.district_id','d.id')
                ->join('regencies as e','d.regency_id','e.id')
                ->join('provinces as f','e.province_id','f.id')
                ->join('detail_voucher_history as g','b.id','g.voucher_history_id')
                ->where('g.type','Input');

        if($request->input('search.value')!=null){
            $data = $data->where(function($q)use($request){
                $q->whereRaw('LOWER(a.name) like ? ',['%'.strtolower($request->input('search.value')).'%'])
                ->orWhereRaw('LOWER(c.name) like ? ',['%'.strtolower($request->input('search.value')).'%'])
                ;
            });
        }

                
        if($request->input('date') != null AND $request->input('year') != null){
            $data->whereMonth('b.created_at', $request->date);
            $data->whereYear('b.created_at', $request->year);
        }

        $data = $data->groupBy('b.id','a.photo','a.name','c.name','d.name','e.name','f.name');

        $recordsFiltered = $data->count();
        if($request->input('length')!=-1) $data = $data->skip($request->input('start'))->take($request->input('length'));
        $data = $data->orderBy($orderBy,$request->input('order.0.dir'))->get();
        $recordsTotal = $data->count();
        
        $result = [];
        foreach ($data as $val) {
            $result[] = [
                'id' => $val->id,
                'photo' => $val->photo,
                'name' => $val->name,
                'address' => 'DS, ' .$val->village.', KEC. ' .$val->district.', '.$val->regency.', '.$val->province,
                'total_data' => $gF->decimalFormat($val->total_data),
                'total_nominal' => $gF->decimalFormat($val->total_nominal),
                'total_point' => $gF->decimalFormat($val->total_point)
            ];
        }

        return response()->json([
                'draw'=>$request->input('draw'),
                'recordsTotal'=>$recordsTotal,
                'recordsFiltered'=>$recordsFiltered,
                'data'=> $result
            ]);

    }
}
