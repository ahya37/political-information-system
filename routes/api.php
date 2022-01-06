<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('searchprovinces', 'API\LocationController@getSearchProvince');
Route::post('searchregencies', 'API\LocationController@getSearchRegency');
Route::post('searchdistricts', 'API\LocationController@getSearchDistrict');
Route::post('searchvillage', 'API\LocationController@getSearchVillage');
Route::post('searchprovincesById', 'API\LocationController@getSearchProvinceById');
Route::post('searchregencyById', 'API\LocationController@getSearchRegencyById');
Route::post('searchdistrictById', 'API\LocationController@getSearchDistrictById');
Route::post('searchVillageById', 'API\LocationController@getSearchVillageById');

Route::post('getdistricts', 'API\LocationController@getDistricts');
Route::post('getvillages', 'API\LocationController@getVillages');
Route::post('getmemberbyregency', 'API\MemberController@getMemberByRegency');
Route::post('getmemberbydistrict', 'API\MemberController@getMemberByDistrict');
Route::post('getmemberbyvillage', 'API\MemberController@getMemberByVillage');

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

// chart dashboard nasional
Route::get('member/achievment/national','API\DashboardController@getAchievmentsNational');
Route::get('member/rergister/national','API\DashboardController@getMemberNational');
Route::get('member/totalnational','API\DashboardController@getTotalMemberNational');
Route::get('membervsterget/national','API\DashboardController@getMemberVsTargetNational');
Route::get('member/gender/national','API\DashboardController@getGenderNational');
Route::get('member/jobs/national','API\DashboardController@getJobsNational');
Route::get('member/agegroup/national','API\DashboardController@getAgeGroupNational');
Route::get('member/genage/national','API\DashboardController@genAgeNational');
Route::get('member/inputer/national','API\DashboardController@getInputerNational');
Route::get('member/referal/national','API\DashboardController@getRegefalNational');

// chart dashboard province
Route::get('member/totalprovince/{province_id}','API\DashboardController@getTotalMemberProvince');
Route::get('member/rergister/province/{province_id}','API\DashboardController@getMemberProvince');
Route::get('membervsterget/province/{province_id}','API\DashboardController@getMemberVsTargetProvince');
Route::get('member/gender/province/{province_id}','API\DashboardController@getGenderProvince');
Route::get('member/jobs/province/{province_id}','API\DashboardController@getJobsProvince');
Route::get('member/agegroup/province/{province_id}','API\DashboardController@getAgeGroupProvince');
Route::get('member/genage/province/{province_id}','API\DashboardController@genAgeProvince');
Route::get('member/inputer/province/{province_id}','API\DashboardController@getInputerProvince');
Route::get('member/referal/province/{province_id}','API\DashboardController@getRegefalProvince');

// chart dashboard regency
Route::get('member/rergister/regency/{regency_id}','API\DashboardController@getMemberRegency');
Route::get('member/totalregency/{regency_id}','API\DashboardController@getTotalMemberRegency');
Route::get('membervsterget/regency/{regency_id}','API\DashboardController@getMemberVsTargetRegency');
Route::get('member/gender/regency/{regency_id}','API\DashboardController@getGenderRegency');
Route::get('member/jobs/regency/{regency_id}','API\DashboardController@getJobsRegency');
Route::get('member/agegroup/regency/{regency_id}','API\DashboardController@getAgeGroupRegency');
Route::get('member/genage/regency/{regency_id}','API\DashboardController@genAgeRegency');
Route::get('member/inputer/regency/{regency_id}','API\DashboardController@getInputerRegency');
Route::get('member/referal/regency/{regency_id}','API\DashboardController@getRegefalRegency');

// chart dashboard district
Route::get('member/totaldistrict/{district_id}','API\DashboardController@getTotalMemberDistrict');
Route::get('member/rergister/district/{district_id}','API\DashboardController@getMemberDistrict');
Route::get('membervsterget/district/{district_id}','API\DashboardController@getMemberVsTargetDistrict');
Route::get('member/gender/district/{district_id}','API\DashboardController@getGenderDistrict');
Route::get('member/jobs/district/{district_id}','API\DashboardController@getJobsDistrict');
Route::get('member/agegroup/district/{district_id}','API\DashboardController@getAgeGroupDistrict');
Route::get('member/genage/district/{district_id}','API\DashboardController@genAgeDistrtict');
Route::get('member/inputer/district/{district_id}','API\DashboardController@getInputerDistrict');
Route::get('member/referal/district/{district_id}','API\DashboardController@getRegefalDistrict');

// chart dashboard district
Route::get('member/totalvillage/{district_id}/{village_id}','API\DashboardController@getTotalMemberVillage');
Route::get('member/gender/village/{village_id}','API\DashboardController@getGenderVillage');
Route::get('member/jobs/village/{village_id}','API\DashboardController@getJobsVillage');
Route::get('member/agegroup/village/{village_id}','API\DashboardController@getAgeGroupVillage');
Route::get('member/genage/village/{village_id}','API\DashboardController@genAgeVillage');
Route::get('member/inputer/village/{village_id}','API\DashboardController@getInputerVillage');
Route::get('member/referal/village/{village_id}','API\DashboardController@getRegefalVillage');

// event galleries
Route::get('event/galleries/{eventId}','API\EventGalleryController@getDataEventGalleries');

Route::get('member/potensial/input','API\MemberController@memberPotentialInput');
Route::get('admins','API\AdminController@getAdmin');

// total reginal
Route::get('totalregional/nation','API\DashboardController@getTotalRegioanNational');
Route::get('totalregional/province/{province_id}','API\DashboardController@getTotalRegioanProvince');
Route::get('totalregional/regency/{regency_id}','API\DashboardController@getTotalRegioanRegency');
Route::get('totalregional/district/{district_id}','API\DashboardController@getTotalRegioanDistrict');

Route::get('adminuser/member/rergister/province/{province_id}','API\DashboardController@getMemberProvinceAdminUser');
Route::get('adminuser/member/rergister/regency/{regency_id}','API\DashboardController@getMemberRegencyAdminUser');
Route::get('adminuser/member/rergister/district/{district_id}','API\DashboardController@getMemberDistrictAdminUser');


Route::post('/target','API\AdminController@generateTarget');

#search member
Route::post('/searchmember','API\MemberController@getSearchMember');
Route::post('/searchmemberforcaleg','API\MemberController@getSearchMemberForCaleg');
Route::post('/memberbyid','API\MemberController@getMemberById');

// referal
Route::post('/dashboard/referalbymount','API\DashboardController@referalByMountAdmin');
Route::post('/dashboard/referalbydefault','API\DashboardController@referalByMountAdminByDefault');
Route::post('/dashboard/referalbymonthprovince','API\DashboardController@referalByMountAdminProvince');
Route::post('/dashboard/referalbymonthprovincedefault','API\DashboardController@referalByMountAdminProvinceDefault');
Route::post('/dashboard/referalbymounthregency','API\DashboardController@referalByMountAdminRegency');
Route::post('/dashboard/referalbymounthregencydefault','API\DashboardController@referalByMountAdminRegencyDefault');
Route::post('/dashboard/referalbymounthdistrict','API\DashboardController@referalByMountAdminDistrict');
Route::post('/dashboard/referalbymounthdistrictdefault','API\DashboardController@referalByMountAdminDistrictDefault');
Route::post('/dashboard/referalbymounthvillage','API\DashboardController@referalByMountAdminVillage');
Route::post('/dashboard/referalbymounthvillagedefault','API\DashboardController@referalByMountAdminVillageDefault');

// input national
Route::get('/dashboard/inputbymonthpdefault','API\DashboardController@inputByMountAdmiNationalDefault');
Route::post('/dashboard/inputbymonth','API\DashboardController@inputByMountAdminNational');

// input province
Route::post('/dashboard/inputbymonthprovincedefault','API\DashboardController@inputByMountAdminProvinceDefault');
Route::post('/dashboard/inputbymonthprovince','API\DashboardController@inputByMountAdminNational');

// input regency
Route::post('/dashboard/inputbymonthregencydefault','API\DashboardController@inputByMountAdminRegencyDefault');
Route::post('/dashboard/inputbymonthregency','API\DashboardController@inputByMountAdminRegency');

// input district
Route::post('/dashboard/inputbymonthdistrictdefault','API\DashboardController@inputByMountAdminDistrictDefault');
Route::post('/dashboard/inputbymonthdistrict','API\DashboardController@inputByMountAdminDistrict');

// input villages
Route::post('/dashboard/inputbymonthvillagedefault','API\DashboardController@inputByMountAdminVillageDefault');
Route::post('/dashboard/inputbymonthvillage','API\DashboardController@inputByMountAdminVillage');



Route::get('/dashboard/adminregional','TestController@testAdminRegionalVillage');

// get daerah kabupaten yang sudah tersimpan di tb dapils
Route::post('/getregencydapil','API\DapilController@getRegencyDapil');
Route::post('/getlistdapil','API\DapilController@getListDapil');
Route::post('/getlistdistrictdapil','API\DapilController@getListDistrict');

// get kabkot untuk create event
Route::post('addparticipantevent','Admin\EventController@storeAddMemberEventAjax');

// reward anggota biasa
Route::get('/rewardefault','Admin\RewardController@getPoinByMonthDefault');
Route::post('/reward','Admin\RewardController@getPoinByMonth');

// reward member admin
Route::get('/admin/member/rewardefault','Admin\RewardController@getPoinByMonthMemberAdminDefaul');
Route::post('/admin/member/reward','Admin\RewardController@getPoinByMonthMemberAdmin');

Route::post('/savevoucher','Admin\RewardController@saveVoucherHistory');
Route::post('/savevoucheradmin','Admin\RewardController@saveVoucherHistoryAdmin');

// reward by account member
Route::post('/user/rewardefault','RewardController@getPoinByMonthDefaultByAccountMember');
Route::post('/user/reward','RewardController@getPoinByMonthByAccountMember');

Route::post('/user/rewardreferal','RewardController@getPoinByMonthDefaultByAccountMemberReferal');
Route::post('/user/rewardreferalbymonth','RewardController@getPoinByMonthByAccountMemberReferal');

// data history voucher di akun anggota
Route::post('/user/dtrewardreferal','RewardController@dtVoucherHistoryReferal');
Route::post('/user/dtrewardinput','RewardController@dtVoucherHistoryAdmin');

// target
Route::get('/list/target','Admin\SettingController@getListTarget');

Route::post('/detailfigure','Admin\InformationController@detailFigure');

// list intelegensi akun anggota
Route::post('/user/member/info/dtintelegency','Admin\InformationController@dtListIntelegencyAccountMember');


// ANGGOTA ADMIN
Route::get('/admin/member/totalregency/{user_id}','API\DashboardController@getTotalMemberByAdminMember');
Route::get('/admin/member/rergister/regency/{user_id}','API\DashboardController@getMemberAdminMember');
Route::get('/admin/membervsterget/{user_id}','API\DashboardController@getMemberVsTargetAdminMember');
Route::get('/admin/member/gender/{user_id}','API\DashboardController@getGenderAdminMember');
Route::get('/admin/member/jobs/{user_id}','API\DashboardController@getJobsAdminMember');
Route::get('/admin/member/agegroup/{user_id}','API\DashboardController@getAgeGroupAdminMember');
Route::get('/admin/member/genage/{user_id}','API\DashboardController@genAgeAdminMember');
Route::get('/admin/member/inputer/{user_id}','API\DashboardController@getInputerAdminMember');
Route::get('/admin/member/referal/{user_id}','API\DashboardController@getRegefalAdminMember');
Route::get('/admin/member/{daterange}/{user_id}','API\DashboardController@memberReportPerMountAdminMember');
