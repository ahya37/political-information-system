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
Route::post('addElement', 'API\LocationController@addElement');
Route::post('searchprovincesById', 'API\LocationController@getSearchProvinceById');
Route::post('searchregencyById', 'API\LocationController@getSearchRegencyById');
Route::post('searchdistrictById', 'API\LocationController@getSearchDistrictById');
Route::post('searchdapilById', 'API\LocationController@getSearchDapil');
Route::post('searchVillageById', 'API\LocationController@getSearchVillageById');
Route::post('getrtbyvillage', 'API\LocationController@getRTByVIllage');
Route::post('getkampungbyvillage', 'API\LocationController@getKampungByVIllage');
Route::post('getrtbyvillageNew', 'API\LocationController@getRTByVIllageNew');

Route::post('gettpsbyvillage', 'API\LocationController@getTpsVIllage');

Route::post('intelegency/addElement', 'Admin\InformationController@addElementFormIntelegence');


Route::get('selectvillage', 'API\LocationController@getVillageSelect');
Route::get('searchvillage', 'API\LocationController@getVillageSearch');

Route::post('getdistricts', 'API\LocationController@getDistricts');
Route::post('getvillages', 'API\LocationController@getVillages');
Route::post('getmemberbyregency', 'API\MemberController@getMemberByRegency');
Route::post('getmemberbydistrict', 'API\MemberController@getMemberByDistrict');
Route::post('getmemberbyvillage', 'API\MemberController@getMemberByVillage');

#replace address
Route::post('address/replace', 'API\MemberController@replaceAddress');
Route::post('nik/replace', 'API\MemberController@replaceNik');

Route::get('jobs', 'Auth\RegisterController@jobs')->name('api-jobs');
Route::get('educations', 'Auth\RegisterController@educations')->name('api-educations');
Route::get('register/check', 'Auth\RegisterController@check')->name('api-register-check');
Route::get('provinces', 'API\LocationController@provinces')->name('api-provinces');
Route::get('regencies/{province_id}', 'API\LocationController@regencies')->name('api-regencies');
Route::get('districts/{regency_id}', 'API\LocationController@districts')->name('api-districts');
Route::get('villages/{district_id}', 'API\LocationController@villages')->name('api-villages');
Route::get('typeagricultur', 'API\TypeOfAgriculturController@typeofagricultur')->name('api-typeofagricultur');
Route::get('nik/check', 'Auth\RegisterController@nik')->name('api-nik-check');
Route::get('register/check', 'Auth\RegisterController@check')->name('api-register-check');
Route::get('reveral/check', 'Auth\RegisterController@reveral')->name('api-reveral-check');
Route::get('reveral/name/{code}', 'Auth\RegisterController@reveralName');
Route::get('nik/name/{nik}', 'Auth\RegisterController@reveralNameByNik');

#store org
Route::post('store/org', 'Admin\OrgDiagramController@storeOrg');
Route::post('org/update', 'Admin\OrgDiagramController@updateOrg');
Route::post('org/delete', 'Admin\OrgDiagramController@deleteOrg');

Route::get('getdatamember/{village}', 'API\MemberController@getDataMemberBySortirVillage');
Route::get('getdatamemberkampung/{village}/{address}', 'API\MemberController@getDataMemberBySortirKampung');
Route::get('getdatamemberrt/{village}/{rt}', 'API\MemberController@getDataMemberBySortirRT');
Route::get('getdatamemberrtnew/{village}/{address}/{rt}', 'API\MemberController@getDataMemberBySortirRTNew');

#DATATABLE STRUKTUR ORG
Route::post('org/getdataorgvillage', 'Admin\OrgDiagramController@getDataOrgVillage');
Route::post('org/getdataorgdistict', 'Admin\OrgDiagramController@getDataOrgDistrict');
Route::post('org/getdataanggotabykorrt', 'Admin\OrgDiagramController@getListDataAnggotaByKorRt');
Route::post('org/list/rt', 'Admin\OrgDiagramController@getDataOrgRT');
Route::post('org/rt/update', 'Admin\OrgDiagramController@updateOrgRT');
Route::post('org/rt/anggota/delete', 'Admin\OrgDiagramController@deleteAnggotaByKorgRT');
Route::post('org/district/delete', 'Admin\OrgDiagramController@deleteKorCam');
Route::post('org/korrt/delete', 'Admin\OrgDiagramController@deleteKorgRT');
Route::post('org/district/update', 'Admin\OrgDiagramController@updateOrgDistrict');
Route::post('org/village/delete', 'Admin\OrgDiagramController@deleteKorDes');
Route::post('org/village/update', 'Admin\OrgDiagramController@updateOrgVillage');
Route::post('org/getdataorgdapil', 'Admin\OrgDiagramController@getDataOrgDapil');
Route::post('org/dapil/delete', 'Admin\OrgDiagramController@deleteKorDapil');
Route::post('org/getdataorgpusat', 'Admin\OrgDiagramController@getDataOrgPusat');
Route::post('org/pusat/delete', 'Admin\OrgDiagramController@deleteKorPusat');
Route::post('org/pusat/update', 'Admin\OrgDiagramController@updateOrgPusat');



Route::post('org/rt/anggotakeluargaserumah/delete', 'Admin\OrgDiagramController@deleteDataAnggotaByKortpsForFamillyGroup');
Route::post('org/rt/headkeluargaserumah/delete', 'Admin\OrgDiagramController@deleteDataHeadByKortpsForFamillyGroup');

#TPS
Route::post('/list/tps', 'Admin\TpsController@getDataTps');

#new org diagram
// Route::get('org/rt', 'Admin\OrgDiagramController@getDataOrgDiagramRT');
Route::get('org/rt', 'Admin\OrgDiagramController@getDataOrgDiagramRT');
Route::post('org/rt/new', 'Admin\OrgDiagramController@getDataOrgDiagramRTMemberNew');
Route::get('org/village', 'Admin\OrgDiagramController@getDataOrgDiagramVillage');
Route::get('org/district', 'Admin\OrgDiagramController@getDataOrgDiagramDistrict');
Route::get('org/dapil', 'Admin\OrgDiagramController@getDataOrgDiagramDapil');
Route::get('org/pusat', 'Admin\OrgDiagramController@getDataOrgDiagramPusat');
Route::post('org/village/save', 'Admin\OrgDiagramController@setSaveOrgVillage');
Route::post('org/district/save', 'Admin\OrgDiagramController@setSaveOrgDistrict');

#SET URUTAN STRUKTUR
Route::post('org/pusat/setorder/save', 'Admin\OrgDiagramController@setOrderStrukturOrgPusat');

Route::get('member/nation/{daterange}', 'API\DashboardController@memberReportPerMountNation');
Route::get('member/province/{daterange}/{provinceID}', 'API\DashboardController@memberReportPerMountProvince');
Route::get('member/regency/{daterange}/{regencyID}', 'API\DashboardController@memberReportPerMountRegency');
Route::get('member/district/{daterange}/{districtID}', 'API\DashboardController@memberReportPerMountDistrict');
Route::get('member/village/{daterange}/{villageID}', 'API\DashboardController@memberReportPerMountVillage');

// chart dashboard nasional
Route::get('member/achievment/national', 'API\DashboardController@getAchievmentsNational');
Route::get('member/rergister/national', 'API\DashboardController@getMemberNational');
Route::get('member/totalnational', 'API\DashboardController@getTotalMemberNational');
Route::get('membervsterget/national', 'API\DashboardController@getMemberVsTargetNational');
Route::get('member/gender/national', 'API\DashboardController@getGenderNational');
Route::get('member/jobs/national', 'API\DashboardController@getJobsNational');
Route::get('member/agegroup/national', 'API\DashboardController@getAgeGroupNational');
Route::get('member/genage/national', 'API\DashboardController@genAgeNational');
Route::get('member/inputer/national', 'API\DashboardController@getInputerNational');
Route::get('member/referal/national', 'API\DashboardController@getRegefalNational');

// chart dashboard province
Route::get('member/totalprovince/{province_id}', 'API\DashboardController@getTotalMemberProvince');
Route::get('member/rergister/province/{province_id}', 'API\DashboardController@getMemberProvince');
Route::get('membervsterget/province/{province_id}', 'API\DashboardController@getMemberVsTargetProvince');
Route::get('member/gender/province/{province_id}', 'API\DashboardController@getGenderProvince');
Route::get('member/jobs/province/{province_id}', 'API\DashboardController@getJobsProvince');
Route::get('member/agegroup/province/{province_id}', 'API\DashboardController@getAgeGroupProvince');
Route::get('member/genage/province/{province_id}', 'API\DashboardController@genAgeProvince');
Route::get('member/inputer/province/{province_id}', 'API\DashboardController@getInputerProvince');
Route::get('member/referal/province/{province_id}', 'API\DashboardController@getRegefalProvince');

// chart dashboard regency
Route::get('member/rergister/regency/{regency_id}', 'API\DashboardController@getMemberRegency');
Route::get('member/totalregency/{regency_id}', 'API\DashboardController@getTotalMemberRegency');
Route::get('membervsterget/regency/{regency_id}', 'API\DashboardController@getMemberVsTargetRegency');
Route::get('member/gender/regency/{regency_id}', 'API\DashboardController@getGenderRegency');
Route::get('member/jobs/regency/{regency_id}', 'API\DashboardController@getJobsRegency');
Route::get('member/agegroup/regency/{regency_id}', 'API\DashboardController@getAgeGroupRegency');
Route::get('member/genage/regency/{regency_id}', 'API\DashboardController@genAgeRegency');
Route::get('member/inputer/regency/{regency_id}', 'API\DashboardController@getInputerRegency');
Route::get('member/referal/regency/{regency_id}', 'API\DashboardController@getRegefalRegency');

// chart dashboard district
Route::get('member/totaldistrict/{district_id}', 'API\DashboardController@getTotalMemberDistrict');
Route::get('member/rergister/district/{district_id}', 'API\DashboardController@getMemberDistrict');
Route::get('membervsterget/district/{district_id}', 'API\DashboardController@getMemberVsTargetDistrict');
Route::get('member/gender/district/{district_id}', 'API\DashboardController@getGenderDistrict');
Route::get('member/jobs/district/{district_id}', 'API\DashboardController@getJobsDistrict');
Route::get('member/agegroup/district/{district_id}', 'API\DashboardController@getAgeGroupDistrict');
Route::get('member/genage/district/{district_id}', 'API\DashboardController@genAgeDistrtict');
Route::get('member/inputer/district/{district_id}', 'API\DashboardController@getInputerDistrict');
Route::get('member/referal/district/{district_id}', 'API\DashboardController@getRegefalDistrict');

// chart dashboard district
Route::get('member/totalvillage/{district_id}/{village_id}', 'API\DashboardController@getTotalMemberVillage');
Route::get('member/gender/village/{village_id}', 'API\DashboardController@getGenderVillage');
Route::get('member/jobs/village/{village_id}', 'API\DashboardController@getJobsVillage');
Route::get('member/agegroup/village/{village_id}', 'API\DashboardController@getAgeGroupVillage');
Route::get('member/genage/village/{village_id}', 'API\DashboardController@genAgeVillage');
Route::get('member/inputer/village/{village_id}', 'API\DashboardController@getInputerVillage');
Route::get('member/referal/village/{village_id}', 'API\DashboardController@getRegefalVillage');

// event galleries
Route::get('event/galleries/{eventId}', 'API\EventGalleryController@getDataEventGalleries');
Route::post('event/galleries/delete', 'Admin\EventGalleryController@deleteGallery');

Route::get('member/potensial/input', 'API\MemberController@memberPotentialInput');
Route::get('admins', 'API\AdminController@getAdmin');

// total reginal
Route::get('totalregional/nation', 'API\DashboardController@getTotalRegioanNational');
Route::get('totalregional/province/{province_id}', 'API\DashboardController@getTotalRegioanProvince');
Route::get('totalregional/regency/{regency_id}', 'API\DashboardController@getTotalRegioanRegency');
Route::get('totalregional/district/{district_id}', 'API\DashboardController@getTotalRegioanDistrict');

Route::get('adminuser/member/rergister/province/{province_id}', 'API\DashboardController@getMemberProvinceAdminUser');
Route::get('adminuser/member/rergister/regency/{regency_id}', 'API\DashboardController@getMemberRegencyAdminUser');
Route::get('adminuser/member/rergister/district/{district_id}', 'API\DashboardController@getMemberDistrictAdminUser');


Route::post('/target', 'API\AdminController@generateTarget');

#search member
Route::post('/searchmember', 'API\MemberController@getSearchMember');
Route::post('/searchmembernik', 'API\MemberController@getSearchMemberByNik');
Route::post('/searchmemberforcaleg', 'API\MemberController@getSearchMemberForCaleg');
Route::post('/memberbyid', 'API\MemberController@getMemberById');

// referal
Route::post('/dashboard/referalbymount', 'API\DashboardController@referalByMountAdmin');
Route::post('/dashboard/referalbydefault', 'API\DashboardController@referalByMountAdminByDefault');
Route::post('/dashboard/totalreferalbydefault', 'API\DashboardController@getTotalreferalByMonth');
Route::post('/dashboard/referalbymonthprovince', 'API\DashboardController@referalByMountAdminProvince');
Route::post('/dashboard/referalbymonthprovincedefault', 'API\DashboardController@referalByMountAdminProvinceDefault');
Route::post('/dashboard/totalreferalbymonthprovincedefault', 'API\DashboardController@getTotalreferalByMonthProvince');
Route::post('/dashboard/referalbymounthregency', 'API\DashboardController@referalByMountAdminRegency');
Route::post('/dashboard/referalbymounthregencydefault', 'API\DashboardController@referalByMountAdminRegencyDefault');
Route::post('/dashboard/totalreferalbymounthregencydefault', 'API\DashboardController@getTotalreferalByMonthRegency');
Route::post('/dashboard/referalbymounthdistrict', 'API\DashboardController@referalByMountAdminDistrict');
Route::post('/dashboard/referalbymounthdistrictdefault', 'API\DashboardController@referalByMountAdminDistrictDefault');
Route::post('/dashboard/totalreferalbymounthdistrictdefault', 'API\DashboardController@getTotalreferalByMonthDistrict');
Route::post('/dashboard/referalbymounthvillage', 'API\DashboardController@referalByMountAdminVillage');
Route::post('/dashboard/referalbymounthvillagedefault', 'API\DashboardController@referalByMountAdminVillageDefault');
Route::post('/dashboard/totalreferalbymounthvillagedefault', 'API\DashboardController@getTotalreferalByMonthVillage');

// input national
Route::post('/dashboard/inputbymonthpdefault', 'API\DashboardController@inputByMountAdmiNationalDefault');
Route::post('/dashboard/totalinputbymonthpdefault', 'API\DashboardController@getTotalInputByMonth');
Route::post('/dashboard/inputbymonth', 'API\DashboardController@inputByMountAdminNational');

// input province
Route::post('/dashboard/inputbymonthprovincedefault', 'API\DashboardController@inputByMountAdminProvinceDefault');
Route::post('/dashboard/totalinputbymonthprovincedefault', 'API\DashboardController@getTotalInputByMonthProvince');
Route::post('/dashboard/inputbymonthprovince', 'API\DashboardController@inputByMountAdminNational');

// input regency
Route::post('/dashboard/inputbymonthregencydefault', 'API\DashboardController@inputByMountAdminRegencyDefault');
Route::post('/dashboard/totalinputbymonthregencydefault', 'API\DashboardController@getTotalInputByMonthRegency');
Route::post('/dashboard/inputbymonthregency', 'API\DashboardController@inputByMountAdminRegency');

// input district
Route::post('/dashboard/inputbymonthdistrictdefault', 'API\DashboardController@inputByMountAdminDistrictDefault');
Route::post('/dashboard/totalinputbymonthdistrictdefault', 'API\DashboardController@getTotalInputByMonthDistrict');
Route::post('/dashboard/inputbymonthdistrict', 'API\DashboardController@inputByMountAdminDistrict');

// input villages
Route::post('/dashboard/inputbymonthvillagedefault', 'API\DashboardController@inputByMountAdminVillageDefault');
Route::post('/dashboard/totalinputbymonthvillagedefault', 'API\DashboardController@getTotalInputByMonthVillage');
Route::post('/dashboard/inputbymonthvillage', 'API\DashboardController@inputByMountAdminVillage');



Route::get('/dashboard/adminregional', 'TestController@testAdminRegionalVillage');

// get daerah kabupaten yang sudah tersimpan di tb dapils
Route::get('/dapilbyprovinceid/{id}', 'API\DapilController@getRegencyDapilByProvince');
Route::post('/getregencydapil', 'API\DapilController@getRegencyDapil');
Route::post('/getlistdapil', 'API\DapilController@getListDapil');
Route::post('/getlistdistrictdapil', 'API\DapilController@getListDistrict');
Route::post('/getlistvillagetdapil', 'API\DapilController@getListVillage');

// get kabkot untuk create event
Route::post('addparticipantevent', 'Admin\EventController@storeAddMemberEventAjax');

// reward anggota biasa
Route::get('/rewardefault', 'Admin\RewardController@getPoinByMonthDefault');
Route::post('/reward', 'Admin\RewardController@getPoinByMonth');
Route::get('/rewardefault/excel', 'Admin\RewardController@getPoinByMonthDefaultExcel');

// reward member admin
Route::get('/admin/member/rewardefault', 'Admin\RewardController@getPoinByMonthMemberAdminDefaul');
Route::post('/admin/member/reward', 'Admin\RewardController@getPoinByMonthMemberAdmin');
Route::get('/admin/member/rewardefault/excel', 'Admin\RewardController@getPoinAdminByMonthDefaultExcel');

Route::post('/savevoucher', 'Admin\RewardController@saveVoucherHistory');
Route::post('/savevoucheradmin', 'Admin\RewardController@saveVoucherHistoryAdmin');

// reward by account member
Route::post('/user/rewardefault', 'RewardController@getPoinByMonthDefaultByAccountMember');
Route::post('/user/reward', 'RewardController@getPoinByMonthByAccountMember');

Route::post('/user/rewardreferal', 'RewardController@getPoinByMonthDefaultByAccountMemberReferal');
Route::post('/user/rewardreferalbymonth', 'RewardController@getPoinByMonthByAccountMemberReferal');

// data history voucher di akun anggota
Route::post('/user/dtrewardreferal', 'RewardController@dtVoucherHistoryReferal');
Route::post('/user/dtrewardinput', 'RewardController@dtVoucherHistoryAdmin');

// target
Route::get('/list/target', 'Admin\SettingController@getListTarget');
Route::post('/update/dpt', 'Admin\SettingController@updateDptSinkronisasi');
Route::post('/dpt', 'Admin\SettingController@updateDistrictTps');

Route::post('/detailfigure', 'Admin\InformationController@detailFigure');

// list intelegensi akun anggota
Route::post('/user/member/info/dtintelegency', 'Admin\InformationController@dtListIntelegencyAccountMember');

Route::get('/getchoose/village/{id}', 'Admin\InformationController@infoRightChooseVillage');

Route::get('/info/resource/{village_id}', 'Admin\InformationController@listResourceInfo');

Route::get('/intelegensipolitik', 'Admin\InformationController@importIntelegensiToProfession');
Route::get('/getgrafikprofessionintelegensipolitik', 'Admin\InformationController@getGrafikProfesiIntelegensiPolitik');
Route::get('/getgrafikonceservedintelegensipolitik', 'Admin\InformationController@getGrafikOnceServedIntelegensiPolitik');
Route::get('/getgrafikpolitiknameintelegensipolitik', 'Admin\InformationController@getGrafikPolitikNameIntelegensiPolitik');


// ANGGOTA ADMIN
Route::get('/admin/member/totalregency/{user_id}', 'API\DashboardController@getTotalMemberByAdminMember');
Route::get('/admin/member/rergister/regency/{user_id}', 'API\DashboardController@getMemberAdminMember');
Route::get('/admin/membervsterget/{user_id}', 'API\DashboardController@getMemberVsTargetAdminMember');
Route::get('/admin/member/gender/{user_id}', 'API\DashboardController@getGenderAdminMember');
Route::get('/admin/member/jobs/{user_id}', 'API\DashboardController@getJobsAdminMember');
Route::get('/admin/member/agegroup/{user_id}', 'API\DashboardController@getAgeGroupAdminMember');
Route::get('/admin/member/genage/{user_id}', 'API\DashboardController@genAgeAdminMember');
Route::get('/admin/member/inputer/{user_id}', 'API\DashboardController@getInputerAdminMember');
Route::get('/admin/member/referal/{user_id}', 'API\DashboardController@getRegefalAdminMember');
Route::get('/admin/member/{daterange}/{user_id}', 'API\DashboardController@memberReportPerMountAdminMember');



// GRAFIK INTEGENCY
Route::get('/intelegency/byvillage/{village_id}', 'Admin\InformationController@getGrafikIntelegencyVillage');
Route::get('/intelegency/byvillage/figure/{village_id}', 'Admin\InformationController@getFigureGrafikVillage');
Route::post('/getdataintelegensipolitik', 'Admin\InformationController@getDataIntelegensiPolitik');


// Cost
Route::get('/cost/list', 'Admin\CostController@getDataCost');
Route::post('/cost/updatevillage', 'Admin\CostController@updateVillageCost');

// Target

Route::post('/target/list', 'Admin\SettingController@getDatatarget');

//
Route::post('/table/members/province', 'API\SettingController@getMember');

Route::post('/admin/member/dtmember', 'Admin\Datatable\MemberDatatableController@dTableMember');

#SPAM
Route::post('/admin/spam/member/dtmember', 'Admin\Datatable\MemberDatatableController@dTableSpamMember');
Route::post('/admin/spam/member/restore', 'Admin\SpamController@restoreSpamAnggota');


// voucher history by mount
Route::post('/admin/voucherhistory', 'Admin\RewardController@getListVoucherReferalByMount');
Route::post('/admin/totalvoucherhistory', 'Admin\RewardController@getTotalListVoucherReferalByMount');
Route::post('/admin/voucherhistoryadmin', 'Admin\RewardController@getListVoucherAdminByMount');

Route::get('/user/member/dtmember/{userId}', 'MemberController@dtMemberByAdminInput');

// KOORDINATOR
Route::post('/admin/koordinator/upload', 'Admin\KoordinatorController@upload');
Route::post('/admin/koordinator/uploadapi', 'Admin\KoordinatorController@uploadApi');
Route::post('/admin/koordinator/store', 'Admin\KoordinatorController@store');
Route::get('/admin/koordinator/pdf', 'Admin\KoordinatorController@reportPdfKoordinator');
Route::get('/admin/koordinator/lampiran', 'Admin\KoordinatorController@reportAnggotaPerRt');
Route::get('/admin/koordinator/listrt', 'Admin\KoordinatorController@lisRTVillage');
Route::get('/admin/koordinator/absensi', 'Admin\KoordinatorController@absensi');

// ORG DIAGRAM
Route::get('/ordiagram', 'Admin\OrgDiagramController@show');
Route::post('/datacoverkortps', 'Admin\OrgDiagramController@getDataCoverKorTps');
Route::post('/datatimkortps', 'Admin\OrgDiagramController@getDataTimKorTps');

#CATATAN
Route::post('/getlistcatatan', 'Admin\CatatanController@getListCatatan');
Route::post('/catatan/delete', 'Admin\CatatanController@delete');

#INVENTORY
Route::post('/getlistinventory', 'Admin\InventoryController@getListInventory');
Route::post('/getlistinventory/users', 'Admin\InventoryController@getListInventoryUsers');
Route::post('/inventory/delete', 'Admin\InventoryController@delete')->name('admin-inventory-update');
Route::post('/inventoryuser/delete', 'Admin\InventoryController@deleteInventoryUser');

Route::post('/member/potential/referal/excel/upper', 'Admin\MemberController@memberPotentialReferalDownloadExcelUpper');
Route::post('/member/potential/district/referal/excel/upper', 'Admin\MemberController@getKecamatanReferalUpper');

Route::post('cost/delete/file', 'Admin\CostController@deleteFile');
Route::post('catatan/delete/file', 'Admin\CatatanController@deleteFile');

#CELEG
Route::post('/admin/member/dtmember/caleg', 'Admin\Datatable\MemberDatatableController@dTableMemberCaleg');
Route::get('/admin/membervsterget/caleg/{user_id}', 'API\DashboardController@getMemberVsTargetAdminMemberCaleg');
Route::get('/admin/member/rergister/regency/caleg/{user_id}', 'API\DashboardController@getMemberAdminMemberCaleg');
Route::get('/admin/member/totalregency/caleg/{user_id}', 'API\DashboardController@getTotalMemberByAdminMemberCaleg');
Route::get('member/district/caleg/{daterange}/{districtID}/{userId}', 'API\DashboardController@memberReportPerMountDistrictCaleg');
Route::get('member/referal/district/caleg/{district_id}/{userId}', 'API\DashboardController@getRegefalDistrictCaleg');
Route::get('/admin/member/caleg/{daterange}/{user_id}', 'API\DashboardController@memberReportPerMountAdminMemberCaleg');
Route::get('/admin/member/referal/caleg/{user_id}', 'API\DashboardController@getRegefalAdminMemberCaleg');
Route::get('/admin/member/inputer/caleg/{user_id}', 'API\DashboardController@getInputerAdminMemberCaleg');
Route::get('/admin/member/genage/caleg/{user_id}', 'API\DashboardController@genAgeAdminMemberCaleg');
Route::get('/admin/member/agegroup/caleg/{user_id}', 'API\DashboardController@getAgeGroupAdminMemberCaleg');
Route::get('/admin/member/jobs/caleg/{user_id}', 'API\DashboardController@getJobsAdminMemberCaleg');
Route::get('/admin/member/gender/caleg/{user_id}', 'API\DashboardController@getGenderAdminMemberCaleg');
Route::get('member/inputer/district/caleg/{district_id}/{user_id}', 'API\DashboardController@getInputerDistrictCaleg');
Route::get('member/genage/district/caleg/{district_id}/{user_id}', 'API\DashboardController@genAgeDistrtictCaleg');
Route::get('member/agegroup/district/caleg/{district_id}/{user_id}', 'API\DashboardController@getAgeGroupDistrictcaleg');
Route::get('member/jobs/district/caleg/{district_id}/{user_id}', 'API\DashboardController@getJobsDistrictCaleg');
Route::get('member/gender/district/caleg/{district_id}/{user_id}', 'API\DashboardController@getGenderDistrictCaleg');
Route::get('membervsterget/district/caleg/{district_id}/{user_id}', 'API\DashboardController@getMemberVsTargetDistrictCaleg');
Route::get('adminuser/member/rergister/district/caleg/{district_id}/{user_id}', 'API\DashboardController@getMemberDistrictAdminUserCaleg');
Route::get('member/totaldistrict/caleg/{district_id}/{user_id}', 'API\DashboardController@getTotalMemberDistrictCaleg');
Route::get('member/village/caleg/{daterange}/{villageID}/{user_id}', 'API\DashboardController@memberReportPerMountVillageCaleg');
Route::get('member/referal/village/caleg/{village_id}/{user_id}', 'API\DashboardController@getRegefalVillageCaleg');
Route::get('member/inputer/village/caleg/{village_id}/{user_id}', 'API\DashboardController@getInputerVillageCaleg');
Route::get('member/genage/village/caleg/{village_id}/{user_id}', 'API\DashboardController@genAgeVillageCaleg');
Route::get('member/agegroup/village/caleg/{village_id}/{user_id}', 'API\DashboardController@getAgeGroupVillageCaleg');
Route::get('member/jobs/village/caleg/{village_id}/{user_id}', 'API\DashboardController@getJobsVillageCaleg');
Route::get('member/gender/village/caleg/{village_id}/{user_id}', 'API\DashboardController@getGenderVillageCaleg');
Route::get('member/totalvillage/caleg/{district_id}/{village_id}/{user_id}', 'API\DashboardController@getTotalMemberVillageCaleg');

#TPS
Route::post('/getdatatps', 'Admin\TpsController@getDataTpsAPI');
Route::post('/tps/witness/delete', 'Admin\TpsController@deleteWitness');

#EVENT
Route::post('/event/giftricipient/delete', 'Admin\EventController@deleteGiftRicipient');

#HISTORY MONITORING
Route::post('/historymonitoring/delete', 'Admin\HistoryMonitoringController@delete');

#FAMILY GROUP
Route::post('/familygroup/delete', 'Admin\FamilyGroupController@delete');
Route::post('/familygroupmember/delete', 'Admin\FamilyGroupController@deleteMember');
Route::get('getdatafamilygroup', 'Admin\FamilyGroupController@getDataFamilyGroup');
Route::post('getdatafamilygroup/member/{familyId}', 'Admin\FamilyGroupController@getDataMemberByFamilyGroup');

#Questionare
Route::post('/questionare', 'Admin\QuestionnaireController@getDataQuestionnaire');
Route::post('/questionare/delete', 'Admin\QuestionnaireController@delete');
Route::post('/questionare/detail/{id}', 'Admin\QuestionnaireController@detailQuestionnaire');

#Questionnaire Title
Route::post('/questionnairetitle/delete', 'Admin\QuestionnaireTitleController@delete');

#Questionnaire Question
Route::post('/questionnairequestion/{id}', 'Admin\QuestionnaireQuestionController@getData');
Route::post('/questionnairequestion/delete/{id}', 'Admin\QuestionnaireQuestionController@delete');

#Answer Choice Category
Route::post('/answerCategory', 'Admin\AnswerCategoryController@getDataAnswerCategory');
Route::post('/answerCategory/delete', 'Admin\AnswerCategoryController@deleteAnswerCategory');

Route::post('/spam/store', 'API\MemberController@storeSpamNikNull');

Route::post('/searchmemberniktype', 'API\MemberController@getSearchMemberByNiktype');

Route::group(['middleware' => 'checkToken'], function () {
    Route::post('/member/email', 'API\MemberController@getEmail');
});

#Questionnaire Respondent
Route::post('/respondent/{id}', 'Admin\QuestionnaireRespondentController@getDataRespondent');
Route::post('/respondentdetail/{id}', 'Admin\QuestionnaireRespondentController@dataAnswerRespondent');

// untuk update jumlah2 yg ada di tabel excel DPT
Route::post('/rightchoose/update/field/village','Admin\SettingController@updateFieldDPTLevelDesa')->name('admin-rightchoose-update-field-village');

// get kalkulasi anggota tercover dan belum
Route::post('/tim/calculate', 'Admin\OrgDiagramController@countMemberNotCover');


// update tps id anggota berdasarkan tps koordinator nya
Route::post('/tim/updatemytps', 'Admin\OrgDiagramController@updateMyTps');







