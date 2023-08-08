<?php 

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class CountUsiaTim
{

    public static function usia($data, $field,$perbandingan,$usia){
		
       $data = collect($data)->where($field,$perbandingan,$usia)->count(function($q){
						return $q->USIA;
		});
		
		return $data;
       
    }
	
	public static function MultiUsia($data, $field,$perbandingan1,$usia1,$perbandingan2,$usia2){
		
       $data = collect($data)
			  ->where($field,$perbandingan1,$usia1)
			  ->where($field,$perbandingan2,$usia2)
			  ->count(function($q){
						return $q->USIA;
		});
		
		return $data;
       
    } 
	
}