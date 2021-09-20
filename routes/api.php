<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('jobs','Auth\RegisterController@jobs')->name('api-jobs');
Route::get('educations','Auth\RegisterController@educations')->name('api-educations');
Route::get('register/check', 'Auth\RegisterController@check')->name('api-register-check');
Route::get('provinces', 'API\LocationController@provinces')->name('api-provinces');
Route::get('regencies/{province_id}', 'API\LocationController@regencies')->name('api-regencies');
Route::get('districts/{regency_id}', 'API\LocationController@districts')->name('api-districts');
Route::get('villages/{district_id}', 'API\LocationController@villages')->name('api-villages');
Route::get('typeagricultur','API\TypeOfAgriculturController@typeofagricultur')->name('api-typeofagricultur');
Route::get('nik/check', 'Auth\RegisterController@nik')->name('api-nik-check');
Route::get('register/check', 'Auth\RegisterController@check')->name('api-register-check');
Route::get('reveral/check', 'Auth\RegisterController@reveral')->name('api-reveral-check');
Route::get('reveral/name/{code}', 'Auth\RegisterController@reveralName');

Route::get('member/nation/{daterange}','API\DashboardController@memberReportPerMountNation');
Route::get('member/province/{daterange}/{provinceID}','API\DashboardController@memberReportPerMountProvince');
Route::get('member/regency/{daterange}/{regencyID}','API\DashboardController@memberReportPerMountRegency');
Route::get('member/district/{daterange}/{districtID}','API\DashboardController@memberReportPerMountDistrict');
Route::get('member/village/{daterange}/{villageID}','API\DashboardController@memberReportPerMountVillage');

// cahrt anggota terdaftar nasional
Route::get('member/rergister/national','API\DashboardController@getMemberNational');
Route::get('member/totalnational','API\DashboardController@getTotalMemberNational');
Route::get('membervsterget/national','API\DashboardController@getMemberVsTargetNational');
Route::get('member/gender/national','API\DashboardController@getGenderNational');
Route::get('member/jobs/national','API\DashboardController@getJobsNational');
Route::get('member/agegroup/national','API\DashboardController@getAgeGroupNational');
Route::get('member/genage/national','API\DashboardController@genAgeNational');
Route::get('member/inputer/national','API\DashboardController@getInputerNational');
Route::get('member/referal/national','API\DashboardController@getRegefalNational');

// 
Route::get('member/totalprovince/{province_id}','API\DashboardController@getTotalMemberProvince');
Route::get('member/rergister/province/{province_id}','API\DashboardController@getMemberProvince');

