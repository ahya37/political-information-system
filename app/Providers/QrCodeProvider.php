<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeProvider extends ServiceProvider
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

    public function create($qrCodeValue, $qrCodeNameFile)
    {
        $image = QrCode::format('png')
                 ->size(200)->errorCorrection('H')
                 ->generate($qrCodeValue);
        #simpan ke direktori
        $output_file = '/public/assets/user/qrcode/' . $qrCodeNameFile . '.png';
        Storage::disk('local')->put($output_file, $image);
        return $image;
    }
}
