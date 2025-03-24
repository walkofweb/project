<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FacebookSocialiteController;
use App\Http\Controllers\LinkedinController;
use App\Http\Controllers\user\UserDashboardController;
use App\Http\Controllers\user\UserPackageController;
use App\Http\Controllers\admin\AdministratorController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\CustomerController;
use App\Http\Controllers\admin\RatingController;
use App\Http\Middleware\PreventBackHistory;
use App\Http\Controllers\admin\NotificationController;
use App\Http\Controllers\admin\CmsController;
use App\Http\Controllers\admin\MasterController;
use App\Http\Controllers\admin\PackageController;

use App\Http\Controllers\admin\PostController;
use App\Http\Controllers\admin\AdsController;
use App\Http\Controllers\admin\SocialController; 
use App\Http\Controllers\HomeControler;
use App\Http\Controllers\StripePaymentController;
use App\Http\Controllers\SponserController;
//use App\Http\Controllers\sponser\StripePaymentController;
use App\Http\Controllers\sponser\SponserDashboardController;
use App\Http\Controllers\sponser\SponserCustomerController;
use App\Http\Controllers\sponser\SponsorAdsController;
use App\Http\Controllers\sponser\SponserNotificationController;



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
Route::get('/checkoutPage/{userpackage_id}', [StripePaymentController::class,'showCheckout'])->name('checkout1');
Route::post('/stripPayment', [StripePaymentController::class, 'handlePayment'])->name('stripe.postpayment');
Route::get('exportExcel',[MasterController::class,'exportUsers'])->name('exportUsers');
Route::post('importExcel',[MasterController::class,'import'])->name('import');
Route::get('import',[MasterController::class,'importView']);
Route::get('generate-pdf', [MasterController::class, 'generatePDF']);


    Route::get('/',[Homecontroler::class,'index'])->name('home.index');
    Route::get('/aboutus',[Homecontroler::class,'aboutus'])->name('home.aboutus');
    Route::get('/feature',[Homecontroler::class,'feature'])->name('home.feature');
    Route::get('/contact-Us',[Homecontroler::class,'contactUs'])->name('home.contactUs');
    Route::get('/privacy-policy',[Homecontroler::class,'privacy_policy'])->name('home.privacy_policy');
    Route::get('/terms-conditions',[Homecontroler::class,'term_conditions'])->name('home.term_conditions');
    //return '<h3>Coming Soon.............</h3>';



//Soical Media
Route::get('insta/login',[FacebookSocialiteController::class,'redirectToInstagramProvider'])->name('insta.login');
Route::get('insta/callback',[FacebookSocialiteController::class,'instagramProviderCallback'])->name('insta.login.callback');
// new
Route::get('insta/userId',[FacebookSocialiteController::class,'ins_userid']);

// Route::get('insta/login',[FacebookSocialiteController::class,'redirectToInstagramProvider'])->name('insta.login');
// Route::get('insta/callbainsta/loginck',[FacebookSocialiteController::class,'instagramProviderCallback'])->name('insta.login.callback');

//new route
Route::get('/user/signup',[FacebookSocialiteController::class,'index']);
Route::get('/facebook',[FacebookSocialiteController::class,'redirectToFacebook'])->name('facebook');
Route::get('/existfacebookuser',[UserDashboardController::class,'existfacebookuser']);
Route::get('/facebook/callback',[FacebookSocialiteController::class,'handleCallback']);
Route::get('/user/dashboard',[HomeControler::class,'dashboard']);
Route::get('/sponser/dashboard',[SponserDashboardController::class,'index']);
Route::post('/checkout1', [StripePaymentController::class, 'showCheckout'])->name('checkout1');
Route::post('/stripe', [SponserCustomerController::class, 'stripePost'])->name('stripe.post');
Route::post('/sponser/notification', [SponserNotificationController::class, 'pushNotification'])->name('send.notification');




//linkedin
Route::get('/linkedin',[LinkedinController::class,'redirectTolinkdin'])->name('linkedin');
Route::get('/linkedin/callback',[LinkedinController::class,'linkedinhandleCallback']);

//instagrame
Route::get('/instagrame',[FacebookSocialiteController::class,'redirectToInsta'])->name('instagrame');
Route::get('/ins/callback',[FacebookSocialiteController::class,'handleInstaCallback'])->name('instagram.callback');

Route::get('/ins1',[FacebookSocialiteController::class,'inslogin']);

//end 
// google

Route::get('/loginwithgoogle',[FacebookSocialiteController::class,'loginwithgoogle'])->name('loginwithgoogle');
Route::get('/auth/google/callback',[FacebookSocialiteController::class,'callbackfromGoogle']);

//insta

//
Route::get('/insta/callback',[FacebookSocialiteController::class,'handleFacebookCallback']);
Route::get('/facebookLogin',[FacebookSocialiteController::class,'fbLogin']); //page is not found
Route::get('/fbBasicInfo',[FacebookSocialiteController::class,'fbProfileDataResponse']);
Route::get('fbSuccess',[FacebookSocialiteController::class,'fbSuccessResp'])->name('fbSuccess');;
Route::get('fbError',[FacebookSocialiteController::class,'fb_error'])->name('fbError');;

Route::get('/fbConnect/{userId}',[FacebookSocialiteController::class,'fb_connect']);
Route::get('/fbCallback',[FacebookSocialiteController::class,'fbResponse']);

Route::get('/fbProfileConnect/{userId}',[FacebookSocialiteController::class,'fb_Profile_Data']);
Route::get('/userList/{encryption}',[FacebookSocialiteController::class,'user_list']);

Route::post('/userPoints',[FacebookSocialiteController::class,'user_points']);
Route::get('/userList',[FacebookSocialiteController::class,'user_list']);

Route::get('fbProfileSuccess',[FacebookSocialiteController::class,'fbProfileSuccessResp'])->name('fbProfileSuccess');

Route::get('swagger', function () {
	
    return view('swagger');
});
Route::get('/sponserSignup',[SponserController::class,'sponserSignup']);
Route::get('/login',[HomeControler::class,'userLogin']);

Route::get('/sponser/login',[SponserController::class,'sponserLogin']);



// Route::post('/sponser/do_login',[SponserController::class,'do_login']);

Route::get('/user/logout',[HomeControler::class,'logout']);
 
 Route::get('/administrator',[AdministratorController::class,'login']);
 Route::group(['middleware'=>'PreventBackHistory'],function(){


    // user dashboard

// Route::get('/user/dashboard',[DashboardController::class,'index']);

/* Car dashboard */
 
Route::post('/administrator/do_login',[AdministratorController::class,'do_login']);
Route::get('/administrator/dashboard',[DashboardController::class,'index']);
 Route::post('/dashboard',[DashboardController::class,'admin_dashboard']);
Route::post('/sponser/do_login',[SponserController::class,'sponser_doLogin']);
Route::get('/sponser/logout',[SponserController::class,'logout']);
Route::get('/administrator/logout',[AdministratorController::class,'logout']);
 //Route::post('/sdashboard',[DashboardController::class,'admin_dashboard']);
 Route::get('/sponser/sdashboard',[SponserDashboardController::class,'index']);
 Route::post('/sdashboard',[SponserDashboardController::class,'sponser_dashboard']);
 Route::post('/updateSponserProfile',[SponserDashboardController::class,'update_sponser_profile']);
 
 Route::post('/bookingYearlyChart',[DashboardController::class,'bookingYearlyChart']);

//user panel 
Route::post('/user/do_login',[HomeControler::class,'do_login']);
 Route::get('/userManagement',[UserDashboardController::class,'user_details']);
 Route::post('/user/dashboard',[UserDashboardController::class,'user_dashboard']);
 Route::get('user_datatable',[UserDashboardController::class,'userlist']);
// 	/* Customer management */

Route::post('/customerManagement',[CustomerController::class,'index']);
Route::get('customer_datatable',[CustomerController::class,'customerlist']);
Route::post('userManagement/changeStatus',[CustomerController::class,'changeStatus']);

// 	/* Sponsor Customer management */

Route::post('sponsor/customerManagement',[SponserCustomerController::class,'index']);
Route::get('sponsor/customer_datatable',[SponserCustomerController::class,'customerlist']);
Route::get('sponsor/CkeckoutPage',[SponserCustomerController::class,'CkeckoutPage']);
Route::post('sponsor/userManagement/changeStatus',[SponserCustomerController::class,'changeStatus']);
Route::get('sponsor/package_datatable',[SponserCustomerController::class,'packagelist'])->name('sponsor.package_datatable');
Route::post('/sponsor/user_hire',[SponserCustomerController::class,'user_hire']);
Route::get('sponsor/payment_datatable',[SponserCustomerController::class,'paymentlist']);

// Route::post('/customerDetail',[CustomerController::class,'detail']);
Route::post('/delete_customer',[CustomerController::class,'delete_customer']);
Route::post('/changePassword',[CustomerController::class,'changePassword']);
Route::post('/changeAdminPassword',[CustomerController::class,'changeAdminPassword']);
Route::post('/userDetail',[CustomerController::class,'userDetail']);
Route::post('/userHost',[CustomerController::class,'userHost']);
Route::get('/userHost_datatable/{userId}/{type}',[CustomerController::class,'userHost_datatable']);
// Route::post('/masterStatus',[CustomerController::class,'masterStatus']);
Route::post('/userHostStatus',[CustomerController::class,'userHostStatus']);
Route::post('/deleteHost',[CustomerController::class,'deleteHost']);
Route::get('/userAdv_datatable/{userId}/{type}',[CustomerController::class,'userAdv_datatable']);
Route::post('/deleteUserAdv',[CustomerController::class,'deleteUserAdv']);
Route::post('/userAdvStatus',[CustomerController::class,'userAdvStatus']);
Route::post('/editUserAds',[CustomerController::class,'editUserAds']);
Route::get('/userFollower_datatable/{userId}/{type}',[CustomerController::class,'userFollower_datatable']);
Route::get('/userFollows_datatable/{userId}/{type}',[CustomerController::class,'userFollows_datatable']);

Route::post('/deleteFollower',[CustomerController::class,'deleteFollower']);
Route::post('/deleteFollows',[CustomerController::class,'deleteFollows']);








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
Route::post('/sendNotificationToAllUser',[NotificationController::class,'sendNotificationToAllUser']);


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

/* Package  */
Route::get('/packageList',[PackageController::class,'PackageList']);
Route::post('/UserpackageListing',[PackageController::class,'AddUserpackage']);
Route::post('/ChangePackage',[PackageController::class,'updatePackage']);

Route::get('allpackage_datatable',[PackageController::class,'packageListing']);
//saveCountry
Route::post('/savePackage',[PackageController::class,'savePackage']);
Route::post('/editPackage',[PackageController::class,'editPackage']);
Route::post('/deletePackage',[PackageController::class,'deletePackage']);
Route::post('/packageupdateStatus/{pickup_id}',[PackageController::class,'package_changeStatus']);
Route::get('AddUserpackage_datatable',[PackageController::class,'AddUserpackageListing']);
Route::get('package_datatable',[UserPackageController::class,'package_datatable']);

Route::post('/packageSpackage_datatabletatus',[UserPackageController::class,'packageStatus']);
Route::post('/addPackage',[UserPackageController::class,'addPackage']);
Route::post('/updatePackage',[UserPackageController::class,'updatePackage']);
Route::post('/deleteUPackage',[UserPackageController::class,'delete_package']);
Route::post('/updateStatus/{pickup_id}',[PackageController::class,'changeStatus']);
Route::post('/deleteUserPackage',[PackageController::class,'delete_userpackages']);

/* interestList */
Route::post('/interestList',[MasterController::class,'interestList']);
Route::get('interest_datatable',[MasterController::class,'interest_datatable']);
Route::post('/saveInterest',[MasterController::class,'saveInterest']);
Route::post('/deleteInterest',[MasterController::class,'deleteInterest']);
Route::post('/editInterest',[MasterController::class,'editInterest']);
Route::post('/updateInterest',[MasterController::class,'updateInterest']);
Route::post('/interestStatus',[MasterController::class,'interestStatus']);


//user
Route::post('/UserpackageList',[UserPackageController::class,'index']);

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
Route::post('/postDetail',[PostController::class,'postDetail']);
Route::post('/postComment',[PostController::class,'postCommentListing']);
Route::get('/postComments_datatable/{postId}/{type}',[PostController::class,'postComments_datatable']);
Route::post('/commentStatus',[PostController::class,'commentStatus']);
Route::post('/deleteComment',[PostController::class,'deleteComment']);
Route::get('/like_datatable/{postId}/{type}',[PostController::class,'like_datatable']);
Route::post('/likeStatus',[PostController::class,'likeStatus']);
Route::post('/deletelike',[PostController::class,'deletelike']);
Route::get('/share_datatable/{postId}/{type}',[PostController::class,'share_datatable']);
Route::post('/shareStatus',[PostController::class,'shareStatus']);
Route::post('/deleteShare',[PostController::class,'deleteShare']);
Route::get('/post_file_datatable/{postId}/{type}',[PostController::class,'post_file_datatable']);
Route::post('/postFileStatus',[PostController::class,'postFileStatus']);
Route::post('/deletePostFile',[PostController::class,'deletePostFile']);
Route::post('/delete_post',[PostController::class,'delete_post']);

/* Ads management sponsorf*/ 
Route::post('/sponsorAdsList',[SponsorAdsController::class,'sponsorAdsList']);
Route::get('/sponsor/adsDatatable',[SponsorAdsController::class,'ads_datatable']);
Route::post('/sponsor/adsStatus',[SponsorAdsController::class,'adsStatus']);
Route::post('/sponsor/adsDelete',[SponsorAdsController::class,'adsDelete']);
Route::post('/sponsor/editAds',[SponsorAdsController::class,'editAds']);
Route::post('/sponsor/updateAds',[SponsorAdsController::class,'updateAds']);
Route::post('/sponsor/SaveAdvertisement',[SponsorAdsController::class,'SaveAdvertisement']);


//end

Route::post('/advertisementDetail',[AdsController::class,'advertisementDetail']); 


/* Ads management f*/    
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
Route::post('/socialConect',[UserDashboardController::class,'social_connect']);  
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

// User Subscriptions List
Route::post('/subscriptionList',[SocialController::class,'subscriptionList']); 
Route::get('subscriberDatatable',[SocialController::class,'subscriber_datatable']); 
Route::post('/subscriberStatus',[SocialController::class,'subscriberStatus']);
Route::post('/subscriptionDelete',[SocialController::class,'subscriptionDelete']);    

//Dashboard
Route::post('/trafficByPlateForm',[DashboardController::class,'trafficByPlateForm']);   
Route::post('/trafficByLocation',[DashboardController::class,'trafficByLocation']);   
Route::post('/advertisementChart',[DashboardController::class,'advertisementChart']); 
Route::post('/currentWeekReport',[DashboardController::class,'currentWeekReport']); 

//Subscribers
Route::post('/sendSubscribersEmail',[AdsController::class,'sendSubscribersEmail']);
Route::post('/sendSubscriberMail',[AdsController::class,'sendSubscriberMail']);


 //Auth::routes();

 });


//webView
Route::get('/termCondition',[CustomerController::class, 'term_condition']);
Route::get('/privacyPolicy',[CustomerController::class, 'privacy_policy']);
Route::get('/followersGraph/{userId}',[CustomerController::class, 'followers_graph']);
Route::post('/socialMediaFollower',[CustomerController::class, 'social_media_follower']);

Route::get('/postShare',[CustomerController::class, 'postShare']); 
Route::get('/profileShare',[CustomerController::class, 'userProfileShare']);

