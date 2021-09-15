<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class StrRandom extends ServiceProvider
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

    public function generateStrRandom($length = 10)
    {
        $string = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $stringLength = strlen($string);
        $stringRandom = '';

        for ($i=0; $i < $length ; $i++) { 
            $stringRandom .= $string[rand(0, $stringLength - 1)];
        }

        return $stringRandom;
    }
}
