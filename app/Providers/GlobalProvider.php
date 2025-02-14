<?php

namespace App\Providers;

use App\UserMenu;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use App\TargetNumber;
use Illuminate\Support\Str;
use DB;

class GlobalProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    public function __construct()
    {
        
    }

    public function decimalFormat($data) {
        $show = number_format((float)$data,0,',','.');
        return $show;
    }

    public function persen($data)
    {
        $show = number_format($data,1);
        return $show;
    }
    public function persenDpt($data)
    {
        $show = number_format((float)$data,2,',','.');
        return $show;
    }
    

    public function persenIntel($data)
    {
        $show = number_format($data,0);
        return $show;
    }

    public function cutStringCardRegency($data)
    {
        $show = substr($data, 2);
        return $show;
    }

    public function cutStringCardDistrict($data)
    {
        $show = substr($data, 4);
        return $show;
    }

    public function cutStringCardVillage($data)
    {
        $show = substr($data, 6);
        return $show;
    }
    
    public function userMenus()
    {
        $user_id = Auth::user()->id;
        $level = Auth::user()->level;

        $userMenuModel  = new UserMenu();

        $user_menu = [];

        if ($level == 4) {
            // jika anggota login itu caleg
            $user_menu      = $userMenuModel->getUserMenuCaleg($user_id);
        }else{
            $user_menu      = $userMenuModel->getUserMenu($user_id);
        }
        return $user_menu;
    }

    public function userSubmenus($menu_id)
    {
        $userMenuModel  = new UserMenu();
        $user_submenu      = $userMenuModel->getUserSubmenus($menu_id);
        return $user_submenu;
    }

    public function mountFormat($data)
    {
        switch ($data) {
            case '1':
                return 'JAN';
                break;
            case '2':
                return 'FEB';
                break;
             case '3':
                return 'Mar';
                break;
             case '4':
                return 'APR';
                break;
             case '5':
                return 'MEI';
                break;
             case '6':
                return 'JUN';
                break;
             case '7':
                return 'JUL';
                break;
             case '8':
                return 'AGU';
                break;
             case '9':
                return 'SEP';
                break;
             case '10':
                return 'OKT';
                break;
             case '11':
                return 'NOV';
                break;
             case '12':
                return 'DES';
                break;
        }
    }

    public function calculateTargetNational()
    {
         $targetMemberModel= new TargetNumber();
         $target =  $targetMemberModel->getTotalTargetMemberNational()->target;
         return $target;
    }

    public function calculateTargetProvince()
    {
         $targetMemberModel= new TargetNumber();
         $target =  $targetMemberModel->getTotalTargetMemberNational()->target;
         return $target;
    }
    
    public function cropImageKtp($ktp)
    {
        $path_img = public_path('storage/assets/user/ktp/');

        $data = $ktp;
        $image_array_1 = explode(";", $data);
        $image_array_2 = explode(",", $image_array_1[1]);
        $base64 = base64_decode($image_array_2[1]);
        $imageName = uniqid().'.jpg';
        $fileName  = $path_img.$imageName;

        #simpan ke tb
        $file  = 'assets/user/ktp/'.$imageName;
        #simpan ke direktori
        file_put_contents($fileName, $base64);

        return $file;
    }

    public function cropImagePhoto($photo)
    {
        $path_img = public_path('storage/assets/user/photo/');

        $data = $photo;
        $image_array_1 = explode(";", $data);
        $image_array_2 = explode(",", $image_array_1[1]);
        $base64 = base64_decode($image_array_2[1]);
        $imageName = uniqid().'.jpg';
        $fileName  = $path_img.$imageName;

        #simpan ke tb
        $file  = 'assets/user/photo/'.$imageName;
        #simpan ke direktori
        file_put_contents($fileName, $base64);

        return $file;
    }


    public function getPoint($data, $days)
    {
        if ($days >= 30 OR $days < 60 OR $days < 30) {
            // jika jumlah hari 30 / 1 bulan
            if ($data == 0) {
                $point = '0';
            }elseif ($data != 0) {
                $point = $data / 50;
                return floor($point);
            }
        }elseif ($days >= 60 OR $days < 120) {
            // jika jumlah hari 60 / 2 bulan
             if ($data == 0) {
                $point = '0';
            }elseif ($data != 0) {
                $point = $data / 75;
                return floor($point);
            }

        }elseif ($days >= 120) {
            // jika jumlah hari 120 hari / 4 bulan
             if ($data == 0) {
                $point = '0';
            }elseif ($data != 0) {
                $point = $data / 100;
                return floor($point);
            }
        }

    }
    public function getPointMemberAdmin($data, $days)
    {
        if ($days >= 30 AND $days < 60 OR $days < 30) {
            // jika jumlah hari 30 / 1 bulan
            if ($data == 0) {
                $point = '0';
            }elseif ($data != 0) {
                $point = $data / 200;
                return floor($point);
            }
        }elseif ($days >= 60 AND $days < 120) {
            // jika jumlah hari 60 / 2 bulan
             if ($data == 0) {
                $point = '0';
            }elseif ($data != 0) {
                $point = $data / 300;
                return floor($point);
            }

        }elseif ($days >= 120 AND $days < 180) {
            // jika jumlah hari 120 hari / 4 bulan
             if ($data == 0) {
                $point = '0';
            }elseif ($data != 0) {
                $point = $data / 400;
                return floor($point);
            }
        }elseif ($days >= 180) {
            // jika jumlah hari 180 hari / 5 bulan
             if ($data == 0) {
                $point = '0';
            }elseif ($data != 0) {
                $point = $data / 500;
                return floor($point);
            }
        }

    }

    public function getMonthCategory($days)
    {
        if ($days >= 30 AND $days < 60 OR $days < 30) {
           return 'Kategori 1 bulan';
        }elseif ($days >= 60 AND $days < 120) {
           return 'Kategori 2 bulan';
        }elseif ($days >= 120) {
           return 4;
        }
    }

    public function getMonthCategoryMemberAdmin($days)
    {
        if ($days >= 30 AND $days < 60 OR $days < 30) {
           return 'Kategori 1 bulan';
        }elseif ($days >= 60 AND $days < 120) {
           return 'Kategori 2 bulan';
        }elseif ($days >= 120 AND $days < 180) {
           return 'Kategori 4 bulan';
        }elseif ($days >= 180) {
           return 'Kategori 6 bulan';
        }
    }

    public function getPointMode($days)
    {
        if ($days >= 30 AND $days < 60 OR $days < 30) {
           return '50';
        }elseif ($days >= 60 AND $days < 120) {
           return '75';
        }elseif ($days >= 120) {
           return '100';
        }
    }

    public function getPointModeMemberAdmin($days)
    {
        if ($days >= 30 AND $days < 60 OR $days < 30) {
           return '200';
        }elseif ($days >= 60 AND $days < 120) {
           return '300';
        }elseif ($days >= 120 AND $days < 180) {
           return '400';
        }elseif ($days >= 180) {
           return '500';
        }
    }

    public function getPointNominal($data)
    {
        switch ($data) {
            case $data >= 1:
                return $data * 100000;
                break;
            default:
                return 'Belum dapat poin';
                break;
        }
    }

    public function getVoucherCode($data)
    {
        if ($data > 0) {
            $code = Str::random(5);
            return $code;
        }elseif ($data = 0) {
            return null;
        }
    }

    public function getDaysTotal($start, $end)
    {
        $dateStart = strtotime($start);
        $dateEnd = strtotime($end);

        $space = abs($dateStart - $dateEnd);
        return floor($space/(60*60*24));
    }
    
    public function calPoint($data)
    {

        $const1 = 50;
        $const2 = 75;
        $const3 = 100;
        
        // jika dalam 1 bulan = $data / 50 =  $poin
        $oneMonth = $data / 50;
      
        // // jika dalam 1 bulan = $data / 75 =  $poin
        $twoMonth = $data / 75;

        // // jika dalam 1 bulan = $data / 100 = $poin
        $fourMonth = $data / 100;

        $point = [
            '0' => floor($oneMonth),
            '1' => floor($twoMonth),
            '2' => floor($fourMonth) 
        ];

        return $point[0];
    }

    public function callNominal($data)
    {
        $nominal = $data * 100000;
        return $nominal;
    }

    public function calPointAdmin($data)
    {
        $const1 = 50;
        $const2 = 75;
        $const3 = 100;
        
        // jika dalam 1 bulan = $data / 50 =  $poin
        $oneMonth = $data / 200;
      
        // // jika dalam 2 bulan = $data / 75 =  $poin
        $twoMonth = $data / 300;

        // // jika dalam 3 bulan = $data / 100 = $poin
        $fourMonth = $data / 400;

        // jika dalam 4 bulan
        $fourMonth = $data / 400;

        // jika dalam 6 bulan
        $fourMonth = $data / 500;

        $point = [
            '0' => floor($oneMonth),
            '1' => floor($twoMonth),
            '2' => floor($fourMonth) 
        ];

        return $point[0];
        
        
    }

    public function calculateSpecialBonusReferal($data)
    {
        $nominal = 0;
        if ($data > 1000) {
            $nominal = 1000000;

        }elseif ($data >= 750 AND $data <= 999) {
            $nominal = 750000;

        }elseif ($data >= 500 AND $data < 749) {
            $nominal = 500000;

        }elseif ($data >= 250 AND $data < 499) {
            $nominal = 250000;

        }elseif ($data >= 100 AND $data < 249) {
            $nominal = 100000;
        }

        else{
            $nominal = 0;
        }

        return $nominal;
    }

    public function calculateSpecialBonusAdmin($data)
    {
        $nominal = 0;
        if ($data > 1000) {
            $nominal = 250000;

        }elseif ($data >= 500 AND $data <= 999 ) {
            $nominal = 150000;

        }elseif ($data >= 300 AND $data <= 499 ) {
            $nominal = 100000;

        }elseif ($data >= 50 AND $data <= 299) {
            $nominal = 50000;

        }else{
            $nominal = 0;
        }

        return $nominal;
    }

    public function generateLevelPengurus($data, $level, $dapil, $district, $village){

        $result = '';

        if ($level === 'pusat') { // jalankan pemeriksaan di level pusat
            $result = '';
            $tblOrgPusat = DB::table('org_diagram_pusat');
            #cek apakah jabatan ketua sudah ada
            $cek_ketua = $tblOrgPusat->where('title','KETUA')->count();
            #jika ada get idx jabatan ketua dari tb org_diagram_pusat
            if ($cek_ketua > 0) {
                $ketua = $tblOrgPusat->select('idx')->where('title','KETUA')->first();
                $result = $ketua->idx;
            }else{

                $result = 0;
            }

        }
        
        return $result;

    }

    public static function generateLevelOrg($data){
        
        $result = 0;
        if ($data == 'Ketua') {
            $result = 1;
        }elseif ($data == 'Wakil Ketua') {
            $result = 2;
        }elseif ($data == 'Sekretaris') {
            $result = 3;
        }elseif ($data == 'Wakil Sekretaris') {
            $result = 4;
        }elseif ($data == 'Bendahara') {
            $result = 5;
        }elseif ($data == 'Wakil Bendahara') {
            $result = 6;
        }

        return $result;
    }

    public static function generateLevelOrgUpdate($data){
        
        $result = 0;
        if ($data == 'KETUA') {
            $result = 1;
        }elseif ($data == 'WAKIL KETUA') {
            $result = 2;
        }elseif ($data == 'SEKRETARIS') {
            $result = 3;
        }elseif ($data == 'WAKIL SEKRETARIS') {
            $result = 4;
        }elseif ($data == 'BENDAHARA') {
            $result = 5;
        }elseif ($data == 'WAKIL BENDAHARA') {
            $result = 6;
        }

        return $result;
    }
    
}
