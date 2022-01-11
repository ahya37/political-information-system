<?php

namespace App\Providers;

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

    public function getGrafikIntelegency($inputer)
    {
        $gF = new GlobalProvider();

        $cat_inputer = [];
        foreach($inputer as $val){
            $cat_inputer['label'][] = $val->name;
            $cat_inputer['data'][]  = $gF->persen($val->total_data);
        }
        $cat_inputer_label = collect($cat_inputer['label']); 
        $colors            = $cat_inputer_label->map(function($item){return $rand_color = '#' . substr(md5(mt_rand()),0,6);});


        $data = [
            'cat_inputer_label' => $cat_inputer['label'],
            'cat_inputer_data' => $cat_inputer['data'],
            'colors' => $colors
        ];
        return $data;
    }
    
    public function getGrafikInputer($inputer)
    {
        $cat_inputer = [];
        foreach($inputer as $val){
            $cat_inputer['label'][] = $val->name;
            $cat_inputer['data'][]  = $val->total_data;
        }
        $cat_inputer_label = collect($cat_inputer['label']); 
        $colors            = $cat_inputer_label->map(function($item){return $rand_color = '#' . substr(md5(mt_rand()),0,6);});


        $data = [
            'cat_inputer_label' => $cat_inputer['label'],
            'cat_inputer_data' => $cat_inputer['data'],
            'colors' => $colors
        ];
        return $data;
    }

    public function getGrafikMemberRegistered($member_registered)
    {
        $gF   = app('GlobalProvider'); // global function
        $gF   = app('GlobalProvider'); // global function
        $cat_member_registered_label = [];
        $cat_member_registered_data = [];
        $cat_member_registered_target = [];
        foreach($member_registered as $val){
                $cat_member_registered_label[] = $val->name;
                $cat_member_registered_data[]  = $gF->persen(($val->realisasi_member / $val->target_member)*100);
                $cat_member_registered_target[] = $val->target_member;
            
        }
        $label_member_registered    = collect($cat_member_registered_label);
        $colors           = $label_member_registered->map(function($item){return $rand_color = '#00FF00';});
        $colors_target    = $label_member_registered->map(function($item){return $rand_color = '#CC0000';});

        $data = [
            'cat_member_registered_label' => $cat_member_registered_label,
            'cat_member_registered_data' => $cat_member_registered_data,
            'cat_member_registered_target' => $cat_member_registered_target,
            'colors' => $colors,
            'colors_target' => $colors_target
        ];
        
        return $data;
    }

    public function getGrafikJobs($jobs)
    {
        $gF   = app('GlobalProvider'); // global function
        $cat_jobs =[];
        $sum_jobs = collect($jobs)->sum(function($q){return $q->total_job; }); // fungsi untuk menjumlahkan total job
        foreach ($jobs as  $val) {
            $cat_jobs['label'][] = $val->name;
            $cat_jobs['data'][] = $val->total_job;
        }

        
        $labels_job = collect($cat_jobs['label']);
        $colors = $labels_job->map(function($item){
            return $rand_color = '#' . substr(md5(mt_rand()),0,6);
        });
        $data  = [
            'chart_jobs_label' => $cat_jobs['label'],
            'chart_jobs_data' => $cat_jobs['data'],
            'color_jobs'  =>  $colors
        ];
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
        foreach ($range_age as $val) {
            $cat_range_age['label'][]     = $val->range_age;
            $cat_range_age['data'][] = $val->total;
        }

        $data = [
            'cat_range_age' => $cat_range_age['label'], 
            'cat_range_age_data' => $cat_range_age['data']
        ];
        return $data;
    }

    public function getGrafikGenAge($gen_age)
    {
        $cat_gen_age = [];
        $cat_gen_age_data = [];
        foreach ($gen_age as $val) {
            if (isset($val->gen_age) != null) {
                # code...
                $cat_gen_age['label'][] = $val->gen_age;
                $cat_gen_age['data'][]  = $val->total;
            }
        }

        $data = [
            'cat_gen_age' => $cat_gen_age['label'],
            'cat_gen_age_data' => $cat_gen_age['data']
        ];
        return $data;
    }

    public function getGrafikReferal($referal)
    {
        $cat_referal      = [];
        foreach ($referal as $val) {
            $cat_referal['label'][] = $val->name; 
            $cat_referal['data'][]  = $val->total_referal;
        }

        $label_referal = collect($cat_referal['label']);
        $colors = $label_referal->map(function($item){
            return $rand_color = '#' . substr(md5(mt_rand()),0,6);
        });

        $data = [
            'cat_referal' => $cat_referal['label'],
            'cat_referal_data' => $cat_referal['data'],
            'color_referals' => $colors
        ];
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

    public function getGrafikTotalMember($province)
    {
        $cat_province= [];
        foreach ($province as $val) {
            $cat_province['label'][] = $val->province; 
            $cat_province['data'][]  = $val->total_member;
        }
        $label_province = collect($cat_province['label']);
        $colors = $label_province->map(function($item){
            return $rand_color = '#' . substr(md5(mt_rand()),0,6);
        });

        $data = [
            'label' => $cat_province['label'],
            'data' => $cat_province['data'],
            'colors_province' => $colors 
        ];
        return $data;
    }


}
