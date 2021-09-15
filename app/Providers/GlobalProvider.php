<?php

namespace App\Providers;

use App\UserMenu;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

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
}
