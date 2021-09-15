<?php

namespace App\Providers;

use Auth;
use App\Providers\GlobalProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */

    //  public function __construct()
    //  {
         
    //  }

    public function register()
    {
        //
    }


    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('*', function($view){
            if (Auth::check()) {
                
                // menus
                $gF       = new GlobalProvider();
                $userMenu = $gF->userMenus();
                View::share(['userMenu' => $userMenu]);
            }
        });
    }
}
