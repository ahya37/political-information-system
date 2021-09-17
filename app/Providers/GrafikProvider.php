<?php

namespace App\Providers;

use App\Charts\JobChart;
use App\Charts\InputerChart;
use Illuminate\Support\ServiceProvider;

class GrafikProvider extends ServiceProvider
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
    
    public function getGrafikInputer($cat_inputer)
    {
        $chart_inputer    = ''; 
        if ($cat_inputer != []) {
            $label_inputer    = collect($cat_inputer['label']);
            $data_inputer     = $cat_inputer['data'];
            $colors           = $label_inputer->map(function($item){return $rand_color = '#' . substr(md5(mt_rand()),0,6);});
            $chart_inputer    = new InputerChart();
            $chart_inputer->labels($label_inputer);
            $chart_inputer->dataset('','bar', $data_inputer)->backgroundColor($colors);
            $chart_inputer->options([
                   'legend' => false,
                   'title' => [
                       'display' => true,
                       // 'text' => 'Admin Dengan Input Terbanyak'
                   ]
            ]);
        }else{
             $label_inputer    = collect($cat_inputer);
             $data_inputer     = $cat_inputer;
             $colors           = $label_inputer->map(function($item){return $rand_color = '#' . substr(md5(mt_rand()),0,6);});
             $chart_inputer    = new InputerChart();
             $chart_inputer->labels($label_inputer);
             $chart_inputer->dataset('Jumlah','bar', $data_inputer)->backgroundColor($colors);
             $chart_inputer->options([
                   'legend' => false,
                   'title' => [
                       'display' => true,
                       // 'text' => 'Admin Dengan Input Terbanyak'
                   ]
            ]);
            
        }

        return $chart_inputer;
    }

    public function getGrafikMemberRegistered($member_registered)
    {
        $gF   = app('GlobalProvider'); // global function
        $cat_member_registered = [];
        foreach($member_registered as $val){
            if ($val->realisasi_member != 0) {
                # code...
                $cat_member_registered['label'][] = $val->name;
                $cat_member_registered['data'][]  = $gF->persen(($val->realisasi_member / $val->target_member)*100);
                $cat_member_registered['target'][] = $val->target_member;
            }
        }
        $label_member_registered    = collect($cat_member_registered['label']);
        $colors           = $label_member_registered->map(function($item){return $rand_color = '#00FF00';});
        $colors_target    = $label_member_registered->map(function($item){return $rand_color = '#CC0000';});
        $chart_member_registered    = app()->chartjs
                                    ->name('registerGrafik')
                                    ->type('bar')
                                    ->labels($cat_member_registered['label'])
                                    ->datasets([
                                        [
                                            "label" => "Terdaftar",
                                            'backgroundColor' => $colors,
                                            'data' =>  $cat_member_registered['data']
                                        ],
                                        [
                                            "label" => "Target",
                                            'backgroundColor' => $colors_target,
                                            'data' => $cat_member_registered['target']
                                        ]
                                    ])
                                    ->options([
                                        'legend' => false,
                                    ]);
        return $chart_member_registered;
    }

    public function getGrafikJobs($jobs)
    {
        $gF   = app('GlobalProvider'); // global function
        $cat_jobs =[];
        $sum_jobs = collect($jobs)->sum(function($q){return $q->total_job; }); // fungsi untuk menjumlahkan total job
        foreach ($jobs as  $val) {
            $cat_jobs['label'][] = $val->name;
            $cat_jobs['data'][] = $gF->persen(($val->total_job / $sum_jobs)*100);
        }

        $labels_jobs = collect($cat_jobs['label']);
        $data_jobs   = $cat_jobs['data'];
        $colors = $labels_jobs->map(function($item){
            return $rand_color = '#' . substr(md5(mt_rand()),0,6);
        });
        $chart_jobs = new JobChart();
        $chart_jobs->labels($labels_jobs);
        $chart_jobs->dataset('Anggota Berdasarkan Pekerjaan','pie', $data_jobs)->backgroundColor($colors);
        $chart_jobs->options([
            'tooltip' => false,
            'legend' => [
                'position' => 'bottom',
                'align' => 'right',
                'display' => false,
            ],
            'title' => [
                'display' => true,
                ]
            ]);
        $data = ['colors' => $colors,'chart_jobs' =>  $chart_jobs];
        return $data;
    }

    public function getGrafikGender($gender)
    {
        $gF   = app('GlobalProvider'); // global function

        $cat_gender = [];
        $all_gender  = [];

        // untuk menghitung jumlah keseluruhan jenis kelamin L/P
        $total_gender = 0;
        foreach ($gender as $key => $value) {
            $total_gender += $value->total;
        }

        foreach ($gender as  $val) {
            $all_gender[]  = $val->total;

            $cat_gender[] = [
                "label" => $val->gender == 0 ? 'Laki-laki' : 'Perempuan',
                "value"    => $gF->persen(($val->total/$total_gender)*100),
            ];
        }
        
        $total_male_gender   =empty($all_gender[0]) ?  0 :  $all_gender[0];; // total gender pria
        $total_female_gender = empty($all_gender[1]) ?  0 :  $all_gender[1]; // total gender wanita

        $data = ['cat_gender' => $cat_gender,'total_male_gender' => $total_male_gender,'total_female_gender' => $total_female_gender];
        return $data;
    }

    public function getGrafikRangeAge($range_age)
    {
        $cat_range_age = [];
        $cat_range_age_data = [];
        foreach ($range_age as $val) {
            $cat_range_age[]      = $val->range_age;
            $cat_range_age_data[] = [
                'y'    => $val->total
            ];
        }

        $data = ['cat_range_age' => $cat_range_age, 'cat_range_age_data' => $cat_range_age_data];
        return $data;
    }

    public function getGrafikGenAge($gen_age)
    {
        $cat_gen_age = [];
        $cat_gen_age_data = [];
        foreach ($gen_age as $val) {
            if (isset($val->gen_age) != null) {
                # code...
                $cat_gen_age[]      = $val->gen_age;
                $cat_gen_age_data[] = [
                    'y'    => $val->total
                ];
            }
        }

        $data = ['cat_gen_age' => $cat_gen_age,'cat_gen_age_data' => $cat_gen_age_data];
        return $data;
    }

    public function getGrafikReferal($referal)
    {
        $cat_referal      = [];
        $cat_referal_data = [];
        foreach ($referal as $val) {
            $cat_referal[] = $val->name; 
            $cat_referal_data[] = [
                "y" => $val->total_referal,
                // "url" => route('admin-dashboard')
            ];
        }

        $data = ['cat_referal' => $cat_referal,'cat_referal_data' => $cat_referal_data];
        return $data;
    }

   public function getGrafikMemberRegisteredDistrict($member_registered)
    {
        $gF   = app('GlobalProvider'); // global function
        $cat_member_registered = [];
        foreach($member_registered as $val){
            if ($val->realisasi_member != 0) {
                # code...
                $cat_member_registered['label'][] = $val->name;
                $cat_member_registered['data'][]  = $gF->persen(($val->realisasi_member / $val->target_member)*100);
                $cat_member_registered['target'][] = $gF->decimalFormat($val->target_member / $val->total_village);
            }
        }
        $label_member_registered    = collect($cat_member_registered['label']);
        $colors           = $label_member_registered->map(function($item){return $rand_color = '#00FF00';});
        $colors_target    = $label_member_registered->map(function($item){return $rand_color = '#CC0000';});
        $chart_member_registered    = app()->chartjs
                                    ->name('registerGrafik')
                                    ->type('bar')
                                    ->labels($cat_member_registered['label'])
                                    ->datasets([
                                        [
                                            "label" => "Terdaftar",
                                            'backgroundColor' => $colors,
                                            'data' =>  $cat_member_registered['data']
                                        ],
                                        [
                                            "label" => "Target",
                                            'backgroundColor' => $colors_target,
                                            'data' => $cat_member_registered['target']
                                        ]
                                    ])
                                    ->options([
                                        'legend' => false,
                                    ]);
        return $chart_member_registered;
    }


}
