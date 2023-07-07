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

Route::get('/email', function () {
    return view('test-email');
});

Route::post('/by_referal/downloadexcel/{user_id}/{district_id}','Admin\MemberController@memberByReferalDownloadExcel')->name('by-referal-downloadexcel');
Route::post('/by_referal/downloadpdf/{user_id}/{district_id}','Admin\MemberController@memberByReferalDownloadPDF')->name('by-referal-downloadpdf');
Route::post('/admin/dashboard/referalbymount','Admin\DashboardController@referalByMountAdmin');

Route::post('/event/delete','EventController@delete');

Route::get('/formintelegence','Admin\InformationController@shareFormIntelegencyPolitic')->name('formintelegence');
Route::post('/saveformintelegence','Admin\InformationController@saveFormIntelegencyPolitic')->name('saveformintelegence');


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
        Route::post('/bank/store', 'UserController@storeBank')->name('user-store-bank');

        Route::get('/referal/undirect','UserController@memberByUnDirectReferal')->name('member-undirect-referal');
        Route::get('/referal/direct','UserController@memberByDirectReferal')->name('member-direct-referal');
        Route::get('/all/member/{district_id}','UserController@memberByAdminDistrict')->name('all-member-byadmin');

        Route::get('/registered/{id}','UserController@registeredNasdem');
        Route::get('/saved/{id}','UserController@savedNasdem');

        Route::get('/event','EventController@index')->name('member-event');
        Route::get('/event/create','EventController@create')->name('member-event-create');
        Route::post('/event/store','EventController@store')->name('member-event-store');
        Route::get('/event/absen/{event_detail_id}','EventController@storeAbsen')->name('member-event-absen');

        #event gallery
        Route::get('/event/gallery/{id}','EventController@gallery')->name('member-event-gallery');
        Route::post('/event/gallery/store/{id}','EventController@storeGallery')->name('member-event-gallery-store');
        Route::get('/event/gallery/detail/{id}','EventController@detailEventGallery');
        Route::get('/event/edit/{id}','EventController@edit')->name('member-event-edit');
        Route::post('/event/update/{id}','EventController@update')->name('member-event-update');

        
        Route::get('/registered','UserController@memberRegisterIndex')->name('member-registered-user');
        Route::get('/registered/edit/{id}','UserController@EditmemberRegister')->name('member-registered-user-edit');
        Route::post('/registered/update/{id}','UserController@updateMemberRegister')->name('member-registered-user-update');
        Route::get('/registered/create/account/{id}','UserController@createAccount')->name('member-registered-create-account');
        Route::post('/registered/create/account/store/{id}','UserController@storeAccount')->name('member-create-account-store');

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
        
        // set figure
        Route::post('/savesetfigure','MemberController@saveFigurMember')->name('savesetfigures');

        // reward
        Route::get('/reward','RewardController@index')->name('member-reward');
        
        // intelgensi
        Route::get('/intelegence','Admin\InformationController@formIntelegencyPoliticAccounMember')->name('member-intelegensi-create');
        Route::post('/saveintelegence','Admin\InformationController@saveIntelegencyPoliticAccounMember')->name('member-intelegensi-save');
        Route::get('/listintelegence','Admin\InformationController@listIntelegencyAccounMember')->name('member-intelegensi-index');

        Route::get('/info/detalfigure/{id}','Admin\InformationController@detailFigureAccountMember')->name('member-detailfigure');

        Route::post('/info/saveintelegency','Admin\InformationController@saveIntelegencyPoliticAccounMember')->name('member-saveintelegency');
        
        // ADMIN DIBAWAH CALEG
        Route::get('/admin','AdminController@index')->name('member-admin-index');
        Route::get('/create/admin/caleg','AdminController@createAdminCaleg')->name('member-create-admin-caleg');
        Route::post('/store/admin/caleg','AdminController@storeAdminCaleg')->name('member-store-admin-caleg');
        Route::get('/dtlistadmin/{user_id}','AdminController@dtListAdmin');
		
		// ANGGOTA POTENSIAL BY ADMIN INPUT
		Route::get('referal/rekruter','MemberController@memberPotensialByAdminInput')->name('member-referal-rekruter');
        
        #CALEG
		Route::get('caleg/target','MemberController@targetMemberCaleg')->name('member-caleg-target');
		Route::get('caleg/target/edit/{districtId}/{userId}','MemberController@editTargetCaleg')->name('member-caleg-target-edit');
		Route::post('caleg/target/update/{userId}','MemberController@updateTargetDistrictCaleg')->name('member-caleg-target-update');
		Route::get('caleg/target/village/{districtId}/{userId}','MemberController@villageTargetCaleg')->name('member-caleg-target-village');
		Route::post('caleg/sinkronise/village/{districtId}/{userId}','MemberController@sinkronVillageCaleg')->name('member-caleg-sinkronisevillage');
		
        Route::get('caleg/village/target/edit/{id}','MemberController@editTargetVillageCaleg')->name('member-caleg-target-village-edit');
        Route::post('caleg/target/village/update/{id}','MemberController@updateTargetVIllageCaleg')->name('member-caleg-target-village-update');

        Route::post('/member/download/excel/caleg', 'MemberController@getDownloadExcelCaleg')->name('member-download-excel-caleg');
        Route::get('/dashboard/regency/district/caleg/{district_id}','DashboardController@districtCaleg')->name('adminuser-dashboard-district-caleg');
        Route::get('/dashboard/regency/district/village/caleg/{district_id}/{village_id}','DashboardController@villageCaleg')->name('adminuser-dashboard-village-caleg');

        Route::get('profile/{id}','MemberController@profileMember')->name('admin-profile-member');

    });


});



Route::group(['prefix' => 'admin','namespace' => 'Admin'], function(){
    Route::get('/auth','LoginController@loginForm')->name('admin-login');
    Route::post('/login','LoginController@login')->name('post-admin-login');
    Route::post('/accadmindistrict','AdminController@accAdminDistrict');
    Route::post('/accadminvillage','AdminController@accAdminVillage');

    // voucher / reward
    Route::get('/downloadvoucher/{id}','RewardController@downloadVoucherAdmin')->name('voucher-download');
    Route::get('/downloadvoucherreferal/{id}','RewardController@downloadVoucherReferal')->name('voucherreferal-download');
    
    Route::get('/info/dtintelegencyvillage','InformationController@dtListIntelegencyAccountMember');

    // BONUS SPECIAL
    Route::get('/specialbonus/refefal/data','MemberController@dataSpesialBonusReferal');
    Route::get('/specialbonus/refefal/report','MemberController@spesialBonusReportReferal')->name('admin-reward-special-referal-report');
    Route::get('/specialbonus/admin/data','MemberController@dataSpesialBonusAdmin');
    Route::get('/specialbonus/admin/report','MemberController@spesialBonusReportAdmin')->name('admin-reward-special-admin-report');
    
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
        Route::post('/member/spam','MemberController@spamMember')->name('admin-member-spam');
        
        Route::get('/member/nonactive/account/{id}','MemberController@nonActiveAccount')->name('admin-member-nonactive-account');
        Route::post('/member/nonactive/account/store/{id}','MemberController@storeAccountNonActive')->name('admin-member-nonactive-account-store');

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
        Route::get('/event/edit/{id}','EventController@edit')->name('admin-event-edit');
        Route::post('/event/store','EventController@store')->name('admin-event-store');
        Route::post('/event/update/{id}','EventController@update')->name('admin-event-update');
        Route::get('/event/delete/{id}','EventController@delete')->name('admin-event-delete');
        Route::get('/event/add/member/{id}','EventController@addMemberEvent')->name('admin-event-addmember');
        // Route::post('/event/add/member/store','EventController@storeAddMemberEvent')->name('admin-event-addmember-store');
        Route::get('/event/detail/{id}','EventController@eventDetails')->name('admin-event-addmember-detail');
        Route::get('/event/add/participant/store/{event_id}/{user_id}','EventController@storeAddParticipant');
        Route::post('/event/add/participantother/store/{event_id}','EventController@storeAddParticipantOther')->name('admin-event-partisipant-other');

        Route::get('/event/add/addgiftreceipents/{id}','EventController@addGiftRecipient')->name('admin-event-addgiftreceipents');
        Route::post('/event/add/addgiftreceipents/store/{event_id}','EventController@storeAddRecipient')->name('admin-event-addgiftreceipents-store');
        Route::post('/event/add/addgiftreceipentsfamilygroup/store/{event_id}','EventController@storeAddRecipientFamilyGroup')->name('admin-event-addgiftreceipentsfamilygroup-store');

        
        Route::post('/event/category/store','EventCategoryController@store')->name('admin-eventcategory-store');

        // Gallery Event
        Route::get('/event/gallery/{id}','EventGalleryController@index')->name('admin-event-gallery');
        Route::post('/event/gallery/store/{id}','EventGalleryController@store')->name('admin-event-gallery-store');
        Route::post('/event/gallery/update/{id}','EventGalleryController@upodateFoto')->name('admin-event-gallery-update-foto');
        Route::post('/event/gallery/store/video/{id}','EventGalleryController@storeVideo')->name('admin-event-gallery-store-video');
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
        Route::post('setting/save','SettingController@updateTarget')->name('admin-setting-targetmember-store');
        
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
        Route::get('/dapil/caleg/addadmin/{caleg_user_id}','CalegController@addAdminForCaleg')->name('admin-addadmin-caleg');
        Route::post('/dapil/saveadminvaleg/{caleg_user_id}','CalegController@saveAdminForCaleg')->name('admin-saveaddadmin-caleg');
        Route::get('/dapil/dapilcalegs/{id}','Datatable\DapilDatatableController@dtDapilCalegs');

        // set mapping admin area
        Route::post('/savemappingadminarea/{user_id}','AdminController@saveMappingAdminArea')->name('admin-savemappingadminarea');
        Route::get('/showadminsubmission','AdminController@showListAdminSubmission')->name('admin-showadminsubmission');
        Route::get('/dtadminsubmissiondistrict','AdminController@dtAdminAreaDistrcitAdmin');
        Route::get('/dtadminsubmissionvillage','AdminController@dtAdminAreaVillageAdmin');
        Route::get('/dtlistadminareadistrict/{districtID}','AdminController@dtListAdminAreaDistrict');
        Route::get('/dtlistmemberinputerbydistrict/{districtID}','AdminController@dtListMemberInputerByDistrict');
        Route::get('/dtlistmemberinputernational','AdminController@dtListMemberInputerByNational');
        Route::get('/dtlistmemberinputerbyvillage/{villageID}','AdminController@dtListMemberInputerByVillage');
        Route::get('/dtlistadminareavillage/{villageID}','AdminController@dtListAdminAreaVillage');
        Route::get('/dtlistadmin','AdminController@dtListAdmin');

        // figure
        Route::get('/dtlistmemberfigure/{villageID}','AdminController@dtListMemberFigure');

        // Reward
        Route::get('/reward','RewardController@index')->name('admin-reward');
        Route::get('/rewardadmin','RewardController@indexAdmin')->name('admin-rewardadmin');

        // Reward Special
        Route::get('/specialbonus/refefal','MemberController@spesialBonusReferal')->name('admin-reward-special-referal');
        Route::get('/specialbonus/admin','MemberController@spesialBonusAdmin')->name('admin-reward-special-admin');


        // target
        Route::get('/target','SettingController@listTarget')->name('admin-list-target');
        Route::get('/targetprovince/{province_id}','SettingController@listTargetProvince')->name('admin-list-target-province');
        Route::get('/targetregency/{regency_id}','SettingController@listTargetRegency')->name('admin-list-target-regency');
        Route::get('/targetdistrict/{district_id}','SettingController@listTargetDistric')->name('admin-list-target-district');
        Route::get('/rightchoose','SettingController@settingRightChoose')->name('admin-rightchoose');
        Route::post('/saverightchoose','SettingController@SaveRightChooseVillage')->name('admin-rightchoose-save');
        Route::get('/listrightchoose','SettingController@listRightChoose')->name('admin-listrightchoose');
        Route::get('/listrightchoose/regency/{provinceId}','SettingController@listRightChooseRegency')->name('admin-listrightchoose-regency');
        Route::get('/listrightchoose/district/{regencyId}','SettingController@listRightChooseDistrict')->name('admin-listrightchoose-district');
        Route::get('/listrightchoose/village/{districtId}','SettingController@listRightChooseVillage')->name('admin-listrightchoose-village');

        // anggota potensial download
        Route::post('/by_referal/downloadpdfall/{user_id}','MemberController@memberByReferalAllDownloadPDF')->name('by-referal-downloadpdfall');
        Route::post('/by_referal/downloadexcelall/{user_id}','MemberController@memberByReferalDownloadExcelAll')->name('by-referal-downloadexcelall');

        // voucher
        Route::post('/savecustomvoucher','RewardController@CustomSaveVoucherHistory')->name('admin-customvoucher');
        Route::post('/savecustomvoucheradmin','RewardController@CustomSaveVoucherHistoryAdmin')->name('admin-customvoucheradmin');
        Route::get('/listrewardreferal','RewardController@listRewardReferal')->name('admin-listrewardreferal');
        Route::get('/listrewardadmin','RewardController@listRewardAdmin')->name('admin-listrewardadmin');
        Route::get('/dtlistrewardreferal','RewardController@dtListRewardReferal');
        Route::get('/dtlistrewardadmin','RewardController@dtListRewardAdmin');
        Route::get('/detaillistrewardreferal/{id}','RewardController@detailListReward')->name('admin-detail-listrewardreferal');
        Route::get('/detaillistrewardadmin/{id}','RewardController@detailListRewardAdmin')->name('admin-detail-listrewardadmin');

        // intelegency
        Route::get('/info/intelegency','InformationController@formIntelegencyPolitic')->name('admin-intelegency');
        Route::post('/info/saveintelegency','InformationController@saveIntelegencyPolitic')->name('admin-saveintelegency');
        Route::get('/info/listintelegency','InformationController@listIntelegency')->name('admin-listintelegency');
        Route::get('/info/detalfigure/{id}','InformationController@detailFigure')->name('admin-detailfigure');

        Route::group(['prefix' => 'intelegency'], function(){
            Route::get('/','InformationController@index')->name('admin-intelegency-index');
        });

        
        // intelegency Pdf
        Route::get('/info/downloadpdfallbyvillage/{villageId}','InformationController@downloadPdfAllByVillageId')->name('admin-downloadpdfbyvillageid');
        Route::get('/info/downloadpdfall','InformationController@downloadPdfAll')->name('admin-downloadfigureall');

        // Bukti TF Voucher
        Route::post('voucher/tf','RewardController@uploadTFVoucher')->name('admin-vouvhertf-upload');
        Route::get('voucher/report','RewardController@ReportVoucher')->name('admin-voucher-report');
       
        // Cost 
        Route::get('cost/index','CostController@listCostPolitic')->name('admin-cost-index');
        Route::get('cost/create','CostController@create')->name('admin-cost-cost');
        Route::get('cost/edit/{id}','CostController@edit')->name('admin-cost-edit');
        Route::get('cost/index/files/{id}','CostController@listFiles')->name('admin-cost-files');
        Route::post('cost/save','CostController@store')->name('admin-cost-store');
        Route::post('cost/update/{id}','CostController@update')->name('admin-cost-update');
        Route::post('cost/uploadfile/{id}','CostController@uploadFile')->name('admin-cost-uploadfile');
        Route::post('forecast/save','CostController@addForecast')->name('admin-forecast-store');
        Route::post('forecastdesc/save','CostController@addForecastDesc')->name('admin-forecastdesc-store');
        Route::get('cost/index/pdf/{daterange}','CostController@downloadPDF');
        Route::get('cost/index/excel/{daterange}','CostController@downloadExcel');
        Route::get('cost/downloadfile/{file}','CostController@downloadFileCost')->name('admin-cost-downloadfile');

        // Download angota potensial
        Route::get('/member/potential/referal/excel','MemberController@memberPotentialReferalDownloadExcel')->name('admin-member-potential-referal-excel');
        Route::get('/member/potential/input/excel','MemberController@memberPotentialInputDownloadExcel')->name('admin-member-potential-input-excel');
        Route::get('/member/potential/referal/pdf','MemberController@memberPotentialReferalDownloadPDF')->name('admin-member-potential-referal-pdf');
        Route::get('/member/potential/input/pdf','MemberController@memberPotentialInputDownloadPDF')->name('admin-member-potential-input-pdf');

        // cost event
        Route::get('/event/cost/create/{id}','EventController@createCost')->name('admin-event-cost-create');
        Route::post('/event/cost/store/{id}','EventController@costEventStore')->name('admin-event-cost-store');

        Route::post('/member/download/excel', 'MemberController@getDownloadExcel')->name('member-download-excel');

        // KOORDINATOR
        Route::get('/koordinator/create','KoordinatorController@create')->name('admin-koordinator-create');
        Route::post('/koordinator/upload','KoordinatorController@store')->name('admin-koordinator-upload');

        #korpusat
        Route::get('/koordinator/pusat','KoordinatorController@listKorPusat')->name('admin-koordinator-pusat-index');
        Route::get('/koordinator/pusat/create','KoordinatorController@createKorPusat')->name('admin-koordinator-pusat-create');
        Route::post('/koordinator/pusat/save','KoordinatorController@saveKorPusat')->name('admin-koordinator-pusat-save');

        #koradapil
        Route::get('/koordinator/dapil/create/{id}','KoordinatorController@createKorDapil')->name('admin-koordinator-dapil-create');
        Route::post('/koordinator/dapil/save','KoordinatorController@saveKorDapil')->name('admin-koordinator-dapil-save');

        #struktur ogrganisasi
        // Route::get('/struktur','OrgDiagramController@index')->name('admin-struktur-organisasi');
        // Route::get('/strukturtest','OrgDiagramController@orgDiagramTest')->name('admin-struktur-organisasi-test');

        Route::group(['prefix' => 'struktur'], function(){
            Route::get('/dashboard','OrgDiagramController@orgDiagramTest')->name('admin-struktur-organisasi-test');
            Route::get('/village','OrgDiagramController@indexOrgVillage')->name('admin-struktur-organisasi-create');
            Route::get('/pusat','OrgDiagramController@listDataStrukturPusat')->name('admin-struktur-organisasi-pusat');
            Route::get('/village/create','OrgDiagramController@createOrgVillage')->name('admin-struktur-organisasi-village-create');
            Route::post('/village/save','OrgDiagramController@saveOrgVillage')->name('admin-struktur-organisasi-village-save');
            Route::get('/rt','OrgDiagramController@indexOrgRT')->name('admin-struktur-organisasi-rt');
            Route::get('/rt/create','OrgDiagramController@createOrgRT')->name('admin-struktur-organisasi-rt-create');
            Route::get('/rt/create/anggota/{idx}','OrgDiagramController@createOrgRTAnggota')->name('admin-struktur-organisasi-rt-create-anggota');
            Route::get('/rt/edit/anggota/{idx}','OrgDiagramController@editAnggotaOrgRT');
            Route::get('/rt/edittps/anggota/{idx}','OrgDiagramController@editTpsMember');
            Route::get('/rt/edit/{idx}','OrgDiagramController@editOrgRT');
            Route::get('/rt/edittps/{idx}','OrgDiagramController@editTps');
            Route::post('/rt/updatetps/{idx}','OrgDiagramController@updateTps')->name('update-tps-kor');
            Route::post('/rt/updatetpsmember/{idx}','OrgDiagramController@updateTpsMember')->name('update-tps-kor-member');
            Route::post('/rt/save','OrgDiagramController@saveOrgRT')->name('admin-struktur-organisasi-rt-save');
            Route::post('/rt/add/save','OrgDiagramController@saveAnggotaByKorRT')->name('admin-struktur-organisasi-rt-anggota-save');
            Route::post('/rt/add/update/{id}','OrgDiagramController@updateAnggotaByKorRT')->name('admin-struktur-organisasi-rt-anggota-update');
            Route::post('/rt/update/{id}','OrgDiagramController@updateKorRT')->name('admin-struktur-organisasi-rt-update');
            Route::get('/rt/detail/anggota/{idx}','OrgDiagramController@detailAnggotaByKorRT')->name('admin-struktur-organisasi-rt-detail-anggota');
            Route::get('/district','OrgDiagramController@indexOrgDistrict')->name('admin-struktur-organisasi-district-index');
            Route::get('/district/create','OrgDiagramController@createOrgDistrict')->name('admin-struktur-organisasi-district-create');
            Route::post('/district/save','OrgDiagramController@saveOrgDistrict')->name('admin-struktur-organisasi-district-save');
            Route::get('/dapil','OrgDiagramController@indexOrgDapil')->name('admin-struktur-organisasi-dapil-index');
            Route::get('/dapil/create','OrgDiagramController@createOrgDapil')->name('admin-struktur-organisasi-dapil-create');
            Route::post('/dapil/save','OrgDiagramController@saveOrgDapil')->name('admin-struktur-organisasi-dapil-save');
            Route::get('/pusat/create','OrgDiagramController@createOrgPusat')->name('admin-struktur-organisasi-pusat-create');
            Route::post('/pusat/save','OrgDiagramController@saveOrgPusat')->name('admin-struktur-organisasi-pusat-save');

            Route::post('/report/excel','OrgDiagramController@reportExcel')->name('admin-struktur-organisasi-report-excel');
            Route::post('/report/district/excel','OrgDiagramController@reportOrgDistrictExcel')->name('admin-struktur-organisasi-district-report-excel');
            Route::post('/report/village/excel','OrgDiagramController@reportOrgVillagetExcel')->name('admin-struktur-organisasi-village-report-excel');

            #update level org all
            Route::get('/village/update/level','OrgDiagramController@updateLelelOrgAll');


        });

        #Catatan
        Route::group(['prefix' => 'catatan'], function(){
            Route::get('/','CatatanController@index')->name('admin-catatan');
            Route::get('/create','CatatanController@create')->name('admin-catatan-create');
            Route::post('/store','CatatanController@store')->name('admin-catatan-store');
            Route::get('/edit/{id}','CatatanController@edit')->name('admin-catatan-edit');
            Route::post('/update/{id}','CatatanController@update')->name('admin-catatan-update');
            Route::post('/upload/file/{id}','CatatanController@uploadFile')->name('admin-catatan-upload-file');
            Route::get('/download/file/{id}','CatatanController@downloadFileCost')->name('admin-catatan-download-file');
        });

        #Inventory
        Route::group(['prefix' => 'inventory'], function(){
            Route::get('/','InventoryController@index')->name('admin-inventory');
            Route::get('/create','InventoryController@create')->name('admin-inventory-create');
            Route::post('/store','InventoryController@store')->name('admin-inventory-store');
            Route::get('/edit/{id}','InventoryController@edit')->name('admin-inventory-edit');
            Route::post('/update/{id}','InventoryController@update')->name('admin-inventory-update');
            Route::get('/users/{id}','InventoryController@inventoryUser')->name('admin-inventory-users');
            Route::post('/user/store/{id}','InventoryController@storeInventoryUser')->name('admin-inventory-user-store');
        });


        #Spam
        Route::group(['prefix' => 'spam'], function(){
            Route::get('/anggota','SpamController@index')->name('admin-spam-member');
        });

         #TPS
         Route::group(['prefix' => 'tps'], function(){
            Route::get('/','TpsController@index')->name('admin-tps');
            Route::get('/create','TpsController@create')->name('admin-tps-create');
            Route::post('/store','TpsController@store')->name('admin-tps-store');
            Route::get('/witnesses/{tpsId}','TpsController@witnesses')->name('admin-tps-witnesses');
            Route::post('/witnesses/store/{tpsId}','TpsController@storeWitness')->name('admin-tps-witnesses-store');
        });

        #historymonitoring
        Route::group(['prefix' => 'hisrotymonitoring'], function(){
            Route::get('/','HistoryMonitoringController@index')->name('admin-hisrotymonitoring');
            Route::post('/store','HistoryMonitoringController@store')->name('admin-hisrotymonitoring-store');
        });

        #familygroup
        Route::group(['prefix' => 'familygroup'], function(){
            Route::get('/','FamilyGroupController@index')->name('admin-familygroup');
            Route::get('/create','FamilyGroupController@create')->name('admin-familygroup-create');
            Route::post('/store','FamilyGroupController@storeGroupLeader')->name('admin-groupleader-store');
            Route::get('/member/{id}','FamilyGroupController@memberOfFamilygroup')->name('admin-familygroup-member');
            Route::post('/member/store/{id}','FamilyGroupController@storeMemberFamilyGroup')->name('admin-familygroup-member-store');
            Route::get('/edit/{id}','FamilyGroupController@editGroupLeader')->name('admin-familygroup-edit');
            Route::post('/update/{id}','FamilyGroupController@updateGroupLeader')->name('admin-familygroup-update');
            Route::get('/gift/{id}','FamilyGroupController@gift')->name('admin-familygroup-gift');
            Route::post('/store/gift/{id}','FamilyGroupController@storeAddRecipientFamilyGroup')->name('admin-groupleader-storegift');
        });
        
        Route::post('/reason/category','ReasonCategorySpamMember@store')->name('admin-spamcategory-store');

        #BANK
        Route::post('/bank/store','BankController@store')->name('admin-bank-store');

        #QESTIONNAIRE
        Route::group(['prefix' => 'questionnaire'], function(){
            Route::get('/','QuestionnaireController@index')->name('admin-questionnaire');
            Route::get('/create','QuestionnaireController@create')->name('admin-questionnaire-create');
            Route::post('/store', 'QuestionnaireController@store')->name('admin-questionnaire-store');
            Route::get('/edit/{id}', 'QuestionnaireController@edit')->name('admin-questionnaire-edit');
            Route::post('/update', 'QuestionnaireController@update')->name('admin-questionnaire-update');
            Route::get('/detail/{id}', 'QuestionnaireController@detail')->name('admin-questionnaire-detail');
        });

        #QUESTIONNAIRE TITLE
        Route::group(['prefix' => 'questionnairetitle'], function(){
            Route::get('/edit/{id}/{questionnaireId}', 'QuestionnaireTitleController@edit')->name('admin-questionnairetitle-edit');
            Route::post('/update/{id}', 'QuestionnaireTitleController@update')->name('admin-questionnairetitle-update');
            Route::get('/create', 'QuestionnaireTitleController@create')->name('admin-questionnairetitle-create');
            Route::post('/store/{id}', 'QuestionnaireTitleController@store')->name('admin-questionnairetitle-store');
        });

        #QUESTIONNAIRE QUESTION
        Route::group(['prefix' => 'questionnairequestion'], function(){
            Route::get('/{id}', 'QuestionnaireQuestionController@index')->name('admin-questionnairequestion-index');
            Route::get('/edit/{id}/{titleId}', 'QuestionnaireQuestionController@edit');
            Route::post('/store/{id}', 'QuestionnaireQuestionController@store')->name('admin-questionnairequestion-store');
            Route::post('/update/{id}', 'QuestionnaireQuestionController@update')->name('admin-questionnairequestion-update');
            Route::get('/create/{id}', 'QuestionnaireQuestionController@create');
        });

        #ANSWER CHOICE CATEGORY
        Route::group(['prefix' => 'answercategory'], function(){
            Route::get('/', 'AnswerCategoryController@index')->name('admin-answercategory');
            Route::get('/create', 'AnswerCategoryController@create')->name('admin-create-answercategory');
            Route::post('/store', 'AnswerCategoryController@store')->name('admin-store-answercategory');
            Route::get('/edit/{id}', 'AnswerCategoryController@edit');
            Route::post('/update', 'AnswerCategoryController@update')->name('admin-update-answercategory');
        });
          
    });
    
});

Route::group(['prefix' => 'laravel-filemanager', 'middleware' => 'admin'], function () {
    \UniSharp\LaravelFilemanager\Lfm::routes();
});

Auth::routes();


