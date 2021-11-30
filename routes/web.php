<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', function () {
    return view('auth.login');
});

Route::get('/by_referal/downloadexcel/{user_id}/{district_id}','Admin\MemberController@memberByReferalDownloadExcel')->name('by-referal-downloadexcel');
Route::get('/by_referal/downloadpdf/{user_id}/{district_id}','Admin\MemberController@memberByReferalDownloadPDF')->name('by-referal-downloadpdf');
Route::post('/admin/dashboard/referalbymount','Admin\DashboardController@referalByMountAdmin');

Route::get('/testgetfigure','TestController@testGretFigure');


Route::group(['prefix' => 'user','middleware' => ['auth']], function(){
    Route::get('/home', 'HomeController@index')->name('home');

    // verification email
    Route::get('verifymail/{activate_token}','UserController@verificationEmail')->name('verify-email');

    Route::get('/profile', 'UserController@index')->name('user-profile');
    Route::get('/profile/edit/{id}', 'UserController@edit')->name('user-profile-edit');
    Route::get('/profile/editreferal/{id}', 'UserController@editReferal')->name('user-profile-edit-referal');
    Route::post('/profile/update/{id}', 'UserController@update')->name('user-profile-update');
    Route::post('/profile/update/referal/{id}', 'UserController@updateReferalMember')->name('user-profile-update-referal');
    Route::post('/profile/update/myprofile/{id}', 'UserController@updateMyProfile')->name('user-myprofile-update');

    Route::get('/profile/create', 'UserController@create')->name('user-create-profile');
    Route::get('/profile/reveral', 'UserController@createReveral')->name('user-create-reveral');
    Route::post('/profile/reveral/store/{id}', 'UserController@storeReveral')->name('user-store-reveral');

    Route::get('/member/download','UserController@memberReportPdf')->name('user-member-downloadpdf');
    Route::get('member/dashboard','HomeController@dashboardAdminUser')->name('member-dashboard');

    Route::group(['prefix' => 'member'], function(){
        Route::get('index','UserController@indexMember')->name('member-index');
        Route::get('create','UserController@createNewMember')->name('member-create');
        Route::post('/profile/store', 'UserController@store')->name('user-store-profile');
        Route::get('show/mymember/{id}','UserController@profileMyMember')->name('member-mymember');
        Route::get('member/card/download/{id}','UserController@downloadCard')->name('member-card-download');

        Route::get('/referal/undirect','UserController@memberByUnDirectReferal')->name('member-undirect-referal');
        Route::get('/referal/direct','UserController@memberByDirectReferal')->name('member-direct-referal');
        Route::get('/all/member/{district_id}','UserController@memberByAdminDistrict')->name('all-member-byadmin');

        Route::get('/registered/{id}','UserController@registeredNasdem');
        Route::get('/saved/{id}','UserController@savedNasdem');

        Route::get('/event','EventController@index')->name('member-event');
        Route::get('/event/absen/{event_detail_id}','EventController@storeAbsen')->name('member-event-absen');
        
        Route::get('/registered','UserController@memberRegister')->name('member-registered-user');

        Route::get('/province/{province_id}','MemberController@memberProvince')->name('adminuser-members-province');
        Route::get('/villagefilled/province/{province_id}','VillageController@villafeFilledProvince')->name('adminuser-villagefilled-province');
        Route::get('/villagefilled/regency/{regency_id}','VillageController@villafeFilledRegency')->name('adminuser-villagefilled-regency');

        Route::get('/dashboard/regency/{regency_id}','DashboardController@regency')->name('adminuser-dashboard-regency');
        Route::get('/dashboard/regency/district/{district_id}','DashboardController@district')->name('adminuser-dashboard-district');
        Route::get('/dashboard/regency/district/village/{district_id}/{village_id}','DashboardController@village')->name('adminuser-dashboard-village');

        Route::get('/dtmemberpotentialreferalByMember/{id_user}','Admin\Datatable\MemberDatatableController@dTableMemberPotentialReferalByMember');
        Route::get('/dtmemberpotentialinputByMember/{id_user}','Admin\Datatable\MemberDatatableController@dTableMemberPotentialInputByMember');

        // get page anggota berdasarkan pereferalnya
        Route::get('/by_referal/{user_id}','MemberController@memberByReferal')->name('by-referal');
        Route::get('/by_input/{user_id}','MemberController@memberByInput')->name('by-input');

        // Admin submission
        Route::get('/adminsubmission','Admin\AdminController@createMappingAdminArea')->name('member-adminsubmission');
        Route::get('/dtadminsubmissiondistrict','Admin\AdminController@dtAdminAreaDistrcit');
        Route::get('/dtadminsubmissionvillage','Admin\AdminController@dtAdminAreaVillage');
        Route::post('/savemappingadminarea/{user_id}','Admin\AdminController@saveMappingAdminArea')->name('member-savemappingadminarea');


    });


});


Route::group(['prefix' => 'admin','namespace' => 'Admin'], function(){
    Route::get('/auth','LoginController@loginForm')->name('admin-login');
    Route::post('/login','LoginController@login')->name('post-admin-login');
    Route::post('/accadmindistrict','AdminController@accAdminDistrict');
    Route::post('/accadminvillage','AdminController@accAdminVillage');


    Route::group(['middleware' => 'admin'], function(){
        Route::post('logout','LoginController@logout')->name('admin-logout');
        Route::get('/dashboard/nation','DashboardController@index')->name('admin-dashboard');
        Route::get('/dashboard/province/{province_id}','DashboardController@province')->name('admin-dashboard-province');
        Route::get('/dashboard/regency/{regency_id}','DashboardController@regency')->name('admin-dashboard-regency');
        Route::get('/dashboard/regency/district/{district_id}','DashboardController@district')->name('admin-dashboard-district');
        Route::get('/dashboard/regency/district/village/{district_id}/{village_id}','DashboardController@village')->name('admin-dashboard-village');

        Route::get('/member','MemberController@index')->name('admin-member');
        Route::get('/member/potensial','MemberController@memberPotensial')->name('admin-member-potensial');
        Route::get('/member/create','MemberController@create')->name('admin-member-create');
        Route::get('/member/create/account/{id}','MemberController@createAccount')->name('admin-member-create-account');
        Route::post('/member/create/account/store/{id}','MemberController@storeAccount')->name('admin-member-create-account-store');
        Route::post('/member/store','MemberController@store')->name('admin-member-store');

        Route::get('/member/profile/{id}','MemberController@profileMember')->name('admin-profile-member');
        Route::get('/member/profile/edit/{id}','MemberController@editMember')->name('admin-profile-member-edit');
        Route::post('/member/profile/update/{id}','MemberController@updateMember')->name('admin-profile-member-update');

        Route::get('/member/card/download/{id}','MemberController@downloadCard')->name('admin-member-card-download');

        // Admin Control
        Route::get('/admincontrol','AdminController@index')->name('admin-admincontroll');
        Route::get('/admincontrol/district/createadmin','AdminController@create')->name('admin-admincontroll-create');
        Route::get('/admincontrol/seting/{id}','AdminController@settingAdminUser')->name('admin-admincontroll-setting');
        Route::get('/admincontrol/seting/edit/{id}','AdminController@editSettingAdminUser')->name('admin-admincontroll-setting-edit');
        Route::post('/admincontrol/save/{id}','AdminController@storeSettingAdminUser')->name('admin-admincontroll-save');

        // Event
        Route::get('/event','EventController@index')->name('admin-event');
        Route::get('/event/create','EventController@create')->name('admin-event-create');
        Route::post('/event/store','EventController@store')->name('admin-event-store');
        Route::get('/event/add/member/{id}','EventController@addMemberEvent')->name('admin-event-addmember');
        Route::post('/event/add/member/store','EventController@storeAddMemberEvent')->name('admin-event-addmember-store');
        Route::get('/event/detail/{id}','EventController@evenDetials')->name('admin-event-addmember-detail');
        
        // Gallery Event
        Route::get('/event/gallery/{id}','EventGalleryController@index')->name('admin-event-gallery');
        Route::post('/event/gallery/store/{id}','EventGalleryController@store')->name('admin-event-gallery-store');
        Route::get('/event/gallery/detail/{id}','EventGalleryController@detailEventGallery');

         // report excel
        Route::get('/member/national/export','DashboardController@exportDataNationalExcel')->name('report-member-national-excel');
        Route::get('/member/province/export/{province_id}','DashboardController@exportDataProvinceExcel')->name('report-member-province-excel');
        Route::get('/member/regency/export/{regency_id}','DashboardController@exportDataRegencyExcel')->name('report-member-regency-excel');
        Route::get('/member/district/export/{district_id}','DashboardController@exportDataDistrictExcel')->name('report-member-district-excel');
        Route::get('/member/village/export/{village_id}','DashboardController@exportDataVillageExcel')->name('report-member-village-excel');
        Route::get('/member/mostreferalnationalexcel','DashboardController@memberByReferalNationalExcel')->name('report-mostreferal-excel');

        // report profesi nasional
        Route::get('/member/jobs/national','DashboardController@exportJobsNationalExcel')->name('report-jobnational-excel');
        // report profesi province
        Route::get('/member/jobs/province/{province_id}','DashboardController@exportJobsProvinceExcel')->name('report-jobprovince-excel');
        // report profesi regency
        Route::get('/member/jobs/regency/{regency_id}','DashboardController@exportJobsRegencyExcel')->name('report-jobregency-excel');
        // report profesi district
        Route::get('/member/jobs/district/{district_id}','DashboardController@exportJobsDistrictExcel')->name('report-jobdistrict-excel');
        // report profesi jobs
        Route::get('/member/jobs/village/{village_id}','DashboardController@exportJobsVillageExcel')->name('report-jobvillage-excel');
        
        Route::get('/villagefilled/national','VillageController@villafeFilledNational')->name('villagefilled-national');
        Route::get('/villagefilled/province/{province_id}','VillageController@villafeFilledProvince')->name('villagefilled-province');
        Route::get('/villagefilled/regency/{regency_id}','VillageController@villafeFilledRegency')->name('villagefilled-regency');
        Route::get('/villagefilled/district/{district_id}','VillageController@villafeFilledDistrict')->name('villagefilled-district');
        
        Route::get('/member/province/{province_id}','MemberController@memberProvince')->name('members-province');
        Route::get('/member/regency/{regency_id}','MemberController@memberRegency')->name('members-regency');
        Route::get('/member/district/{district_id}','MemberController@memberDistrict')->name('members-district');
        Route::get('/member/village/{village_id}','MemberController@memberVillage')->name('members-village');
        
        Route::get('/pdf/member/mostreferalnationalpdf','MemberController@memberByReferalNationalPDF')->name('pdf-most-referalnational');
        Route::get('/pdf/member/national','MemberController@reportMemberPdf')->name('pdf-members-national');
        Route::get('/pdf/member/province/{province_id}','MemberController@reportMemberProvincePdf')->name('pdf-members-province');
        Route::get('/pdf/member/regency/{regency_id}','MemberController@reportMemberRegencyPdf')->name('pdf-members-regency');
        Route::get('/pdf/member/district/{district_id}','MemberController@reportMemberDistrictPdf')->name('pdf-members-district');
        Route::get('/pdf/member/village/{village_id}','MemberController@reportMemberVillagePdf')->name('pdf-members-village');

        Route::get('/crop','MemberController@cropImage');
        Route::get('/member/dtmember','Datatable\MemberDatatableController@dTableMember');
        Route::get('/member/dtmemberpotentialreferal','Datatable\MemberDatatableController@dTableMemberPotentialReferal');
        Route::get('/member/dtmemberpotentialreferalByMember/{id_user}','Datatable\MemberDatatableController@dTableMemberPotentialReferalByMember');
        Route::get('/member/dtmemberpotentialinput','Datatable\MemberDatatableController@dTableMemberPotentialInput');
        Route::get('/member/dtmemberpotentialinputByMember/{id_user}','Datatable\MemberDatatableController@dTableMemberPotentialInputByMember');
        Route::post('/cropsave','MemberController@saveCropImage')->name('cropsave');

        // get page anggota berdasarkan pereferalnya
        Route::get('member/by_referal/{user_id}','MemberController@memberByReferal')->name('admin-member-by-referal');
        // Route::get('member/by_referal/downloadexcel/{user_id}/{district_id}','MemberController@memberByReferalDownloadExcel')->name('admin-member-by-referal-downloadexcel');
        // Route::get('member/by_referal/downloadpdf/{user_id}/{district_id}','MemberController@memberByReferalDownloadPDF')->name('admin-member-by-referal-downloadpdf');
        Route::get('member/by_input/{user_id}','MemberController@memberByInput')->name('admin-member-by-input');
        
        // setting
        Route::get('setting/targetmember','SettingController@settingTargetMember')->name('admin-setting-targetmember');
        Route::post('setting/save','SettingController@updateTargetMember')->name('admin-setting-targetmember-store');
        
        // Dapil
        Route::get('/dapil','DapilController@index')->name('admin-dapil');
        Route::get('/dapil/create','DapilController@create')->name('admin-dapil-create');
        Route::post('/dapil/save','DapilController@store')->name('admin-dapil-save');
        Route::get('/dapil/detail/{id}','DapilController@detail')->name('admin-dapil-detail');
        Route::get('/dapil/dapilareas/{id}','Datatable\DapilDatatableController@dtDapilAreas');
        Route::get('/dapil/createdapilarea/{regency_id}/{dapil_id}','DapilController@createDapilArea')->name('admin-dapil-createarea');
        Route::post('/dapil/savedapilarea/{dapil_id}','DapilController@saveDapilArea')->name('admin-dapil-savearea');
        
        // Caleg
        Route::get('/dapil/caleg/create/{dapil_id}','CalegController@create')->name('admin-caleg-create');
        Route::post('/dapil/caleg/save/{dapil_id}','CalegController@save')->name('admin-caleg-save');
        Route::get('/dapil/dapilcalegs/{id}','Datatable\DapilDatatableController@dtDapilCalegs');

        // set mapping admin area
        Route::post('/savemappingadminarea/{user_id}','AdminController@saveMappingAdminArea')->name('admin-savemappingadminarea');
        Route::get('/showadminsubmission','AdminController@showListAdminSubmission')->name('admin-showadminsubmission');
        Route::get('/dtadminsubmissiondistrict','AdminController@dtAdminAreaDistrcitAdmin');
        Route::get('/dtadminsubmissionvillage','AdminController@dtAdminAreaVillageAdmin');
        Route::get('/dtlistadminareadistrict/{districtID}','AdminController@dtListAdminAreaDistrict');
        Route::get('/dtlistadminareavillage/{villageID}','AdminController@dtListAdminAreaVillage');
        Route::get('/dtlistadmin','AdminController@dtListAdmin');

        
    });
});

Auth::routes();


