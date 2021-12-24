<?php

namespace App\Providers;

use App\UserMenu;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use App\TargetNumber;
use Illuminate\Support\Str;

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
        $userMenuModel  = new UserMenu();
        $user_menu      = $userMenuModel->getUserMenu($user_id);
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
        //     if ($days >= 30 OR $days < 60 OR $days < 30) {
        //        // jika jumlah hari 30 / 1 bulan
        //        if ($data == 0) {
        //            $point = '0';
        //        }elseif ($data != 0) {
        //            $point = $data / 50;
        //            return floor($point);
        //        }
        //    }

        // rangeMonth = 1,2,4
        // $data = 



        // if ($data = 50) {
        //     $point = $data / 50;
        // }elseif ($data < 75) {
        //     $point = $data / 50;
        // }elseif ($data = 75) {
        //     $point = $data / 75;
        // }elseif ($data < 100) {
        //     $point = $data / 75;
        // }elseif ($data = 100) {
        //     $point = $data / 100;
        // }

        $const1 = 50;
        $const2 = 75;
        $const3 = 100;
        
        // jika dalam 1 bulan = $data / 50 =  $poin
        $oneMonth = $data / 50;
      
        // // jika dalam 1 bulan = $data / 75 =  $poin
        $twoMonth = $data / 75;

        // // jika dalam 1 bulan = $data / 100 = $poin
        $fourMonth = $data / 100;
        
        // $point = '';
        // if ($data >= $const1) {
        //     $point = $oneMonth;
        // }
        // if ($data < $const2) {
        //     $point = $oneMonth;
        // }
        // if ($data < $const3) {
        //     $point = $twoMonth;
        // }

        $point = [
            '0' => floor($oneMonth),
            '1' => floor($twoMonth),
            '2' => floor($fourMonth) 
        ];

        return $point[0];
        
        // poin = totalReferal / 50 
        
        // poin * 100
    }

    public function callNominal($data)
    {
        $nominal = $data * 100000;
        return $nominal;
    }
}
