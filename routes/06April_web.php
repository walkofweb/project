<?php

use Illuminate\Support\Facades\Route;


use App\Http\Controllers\FacebookSocialiteController;
use App\Http\Controllers\admin\AdministratorController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\CustomerController;
use App\Http\Controllers\admin\RatingController;
use App\Http\Middleware\PreventBackHistory;
use App\Http\Controllers\admin\NotificationController;
use App\Http\Controllers\admin\CmsController;
use App\Http\Controllers\admin\MasterController;
use App\Http\Controllers\admin\PostController;
use App\Http\Controllers\admin\AdsController;
use App\Http\Controllers\admin\SocialController; 

/*FacebookSocialiteController
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
    return "Coming Soon.................";
});



// Route::get('auth/facebook', [FacebookSocialiteController::class,'redirectToFB']);
// Route::get('callback/facebook', [FacebookSocialiteController::class,'handleCallback']);

Route::get('insta/login',[FacebookSocialiteController::class,'redirectToInstagramProvider'])->name('insta.login');
Route::get('insta/callback',[FacebookSocialiteController::class,'instagramProviderCallback'])->name('insta.login.callback');
Route::get('/facebookLogin',[FacebookSocialiteController::class,'fbLogin']);



//facebook page account and intagram bussiness account 
Route::get('/fbConnect/{userId}',[FacebookSocialiteController::class,'fb_connect']);
Route::get('/fbCallback',[FacebookSocialiteController::class,'fbResponse']);
Route::get('fbSuccess',[FacebookSocialiteController::class,'fbSuccessResp'])->name('fbSuccess');;
Route::get('fbError',[FacebookSocialiteController::class,'fb_error'])->name('fbError');;
Route::post('/sendContactUsEmail',[MasterController::class,'sendContactUsEmail']);

// facebook Basic
Route::get('/fbProfileConnect/{userId}',[FacebookSocialiteController::class,'fb_Profile_Data']);
Route::get('/fbBasicInfo',[FacebookSocialiteController::class,'fbProfileDataResponse']);

Route::get('/userList',[FacebookSocialiteController::class,'user_list']);
Route::get('/userList/{encryption}',[FacebookSocialiteController::class,'user_list']);
Route::post('/userPoints',[FacebookSocialiteController::class,'user_points']);


/* Start Admin Panel Routes */

Route::get('/administrator',[AdministratorController::class,'login']);
Route::post('/administrator/do_login',[AdministratorController::class,'do_login']);
Route::get('/administrator/logout',[AdministratorController::class,'logout']);

Route::group(['middleware'=>'PreventBackHistory'],function(){

/* Car dashboard */
 Route::get('/administrator/dashboard',[DashboardController::class,'index']);
 Route::post('/dashboard',[DashboardController::class,'admin_dashboard']);
 Route::post('/bookingYearlyChart',[DashboardController::class,'bookingYearlyChart']);

// 	/* Customer management */
Route::post('/customerManagement',[CustomerController::class,'index']);
Route::get('customer_datatable',[CustomerController::class,'customerlist']);
Route::post('userManagement/changeStatus',[CustomerController::class,'changeStatus']);
// Route::post('/customerDetail',[CustomerController::class,'detail']);
Route::post('/delete_customer',[CustomerController::class,'delete_customer']);
Route::post('/changePassword',[CustomerController::class,'changePassword']);
Route::post('/changeAdminPassword',[CustomerController::class,'changeAdminPassword']);

/* Contact Support */
Route::post('/contactSupport',[RatingController::class,'contactSupport']);
Route::get('/contactUs_datatable',[RatingController::class,'contactUs_datatable']);
Route::post('/delete_contactus',[RatingController::class,'delete_contactus']);

/* Notification Controller */
Route::post('/notification',[NotificationController::class,'index']);
Route::get('notify_datatable',[NotificationController::class,'notify_datatable']);

Route::post('addNotify',[NotificationController::class,'addNotify']);
Route::post('saveNotify',[NotificationController::class,'saveNotify']);
Route::post('editNotify',[NotificationController::class,'editNotify']);
Route::post('updateNotify',[NotificationController::class,'updateNotify']);
Route::post('delete_announced_list',[NotificationController::class,'delete_aNList']);
Route::post('announce_Status',[NotificationController::class,'announce_Status']);
Route::post('announce_detail',[NotificationController::class,'announce_detail']);
Route::post('/create_notification',[NotificationController::class,'detail']);
// Notification Type
Route::post('/notificationFor',[NotificationController::class,'notifyFor']);
Route::get('masterController/notificationFor_datatable',[MasterController::class,'notificationFor_datatable']);
Route::post('saveNotifyFor',[NotificationController::class,'saveNotifyFor']);
Route::post('editNFor',[NotificationController::class,'editNFor']);
Route::post('updateNFor',[NotificationController::class,'updateNFor']);
Route::post('deleteNFor',[NotificationController::class,'deleteNFor']);
Route::post('nforStatus',[NotificationController::class,'nforStatus']);

// Rank Type
Route::post('/rankType',[NotificationController::class,'rankType']);
Route::get('rankType_datatable',[MasterController::class,'rankType_datatable']);
Route::post('saveRankType',[NotificationController::class,'save_rankType']);
Route::post('editRankType',[NotificationController::class,'edit_rankType']);
Route::post('updateRank',[NotificationController::class,'updateRank']);
Route::post('deleteRank',[NotificationController::class,'delete_rank']);
Route::post('rankStatus',[NotificationController::class,'rankStatus']);

/* CMS Controller */
Route::post('/termCondition',[CmsController::class,'termCondition']);
Route::post('/saveTermCondition',[CmsController::class,'saveTermCondition']);

Route::post('/privacyPolicy',[CmsController::class,'privacyPolicy']);
Route::post('/savePrivacyPolicy',[CmsController::class,'savePrivacyPolicy']);

/* country , state and city */
Route::post('/countryList',[MasterController::class,'countryList']);
Route::get('country_datatable',[MasterController::class,'country_datatable']);
Route::post('/saveCountry',[MasterController::class,'saveCountry']);
Route::post('/deleteCountry',[MasterController::class,'deleteCountry']);
Route::post('/editCountry',[MasterController::class,'editCountry']);
Route::post('/updateCountry',[MasterController::class,'updateCountry']);
Route::post('/countryStatus',[MasterController::class,'countryStatus']);

/* interestList */
Route::post('/interestList',[MasterController::class,'interestList']);
Route::get('interest_datatable',[MasterController::class,'interest_datatable']);
Route::post('/saveInterest',[MasterController::class,'saveInterest']);
Route::post('/deleteInterest',[MasterController::class,'deleteInterest']);
Route::post('/editInterest',[MasterController::class,'editInterest']);
Route::post('/updateInterest',[MasterController::class,'updateInterest']);
Route::post('/interestStatus',[MasterController::class,'interestStatus']);

/* sponser */
Route::post('/sponserList',[MasterController::class,'sponserList']);
Route::get('sponser_datatable',[MasterController::class,'sponser_datatable']);
Route::post('/saveSponser',[MasterController::class,'save_sponser']);
Route::post('/sponserDelete',[MasterController::class,'sponserDelete']);
Route::post('/editSponser',[MasterController::class,'editSponser']);
Route::post('/updateSponser',[MasterController::class,'updateSponser']);
Route::post('/sponserStatus',[MasterController::class,'sponserStatus']);

/* post management */
Route::post('/postList',[PostController::class,'postList']);
Route::get('postDatatable',[PostController::class,'post_datatable']);
Route::post('/postStatus',[PostController::class,'postStatus']);

/* Ads management */    
Route::post('/adsList',[AdsController::class,'adsList']);
Route::get('adsDatatable',[AdsController::class,'ads_datatable']);
Route::post('/adsStatus',[AdsController::class,'adsStatus']);
Route::post('/adsDelete',[AdsController::class,'adsDelete']);
Route::post('/editAds',[AdsController::class,'editAds']);
Route::post('/updateAds',[AdsController::class,'updateAds']);
Route::post('/SaveAdvertisement',[AdsController::class,'SaveAdvertisement']);

Route::post('/advertisementDetail',[AdsController::class,'advertisementDetail']); 

/* Social Point managenent */

Route::post('/socialList',[SocialController::class,'socialList']);  
Route::get('socialDatatable',[SocialController::class,'socialDatatable']); 
Route::post('/socialStatus',[SocialController::class,'socialStatus']);
Route::post('/editsocial',[SocialController::class,'editsocial']);  
Route::post('/updatesocial',[SocialController::class,'updatesocial']); 
Route::post('/SaveSocial',[SocialController::class,'SaveSocial']); 
Route::post('/socialDelete',[SocialController::class,'socialDelete']);     


/* User Social Point */
Route::post('/userPointList',[SocialController::class,'userPointList']); 
Route::get('userPointDatatable',[SocialController::class,'userPointDatatable']); 
Route::post('/userPointStatus',[SocialController::class,'userPointStatus']);   
Route::post('/userPointDetail',[SocialController::class,'userPoint_detail']); 
//Dashboard
Route::post('/trafficByPlateForm',[DashboardController::class,'trafficByPlateForm']);   
Route::post('/trafficByLocation',[DashboardController::class,'trafficByLocation']);   
Route::post('/advertisementChart',[DashboardController::class,'advertisementChart']); 
Route::post('/currentWeekReport',[DashboardController::class,'currentWeekReport']); 

Auth::routes();

});



