<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\v1\UserController;
use App\Http\Controllers\api\v1\PostController;
use App\Http\Controllers\api\v1\SocialController;
use App\Http\Controllers\api\v1\NotificationController;
use App\Http\Controllers\api\v1\PackageController;
use App\Http\Middleware\EnsureTokenIsValid;
use App\Http\Middleware\Cors;
use App\Http\Middleware\ImageInterceptorMiddleware ;
use App\Http\Controllers\SponserController;
use App\Http\Controllers\api\v1\PaymentController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is asgngned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/apiDetail',function(Request $request){
    return View('service_info');
 }); 
 
  Route::get('/testAudio',[PostController::class, 'testAudio']);

  Route::get('/getsession',[UserController::class, 'get_session']);
  Route::get('/regemail',[UserController::class, 'sendRegistrationEmail']);
    Route::group(['prefix' => 'v1'], function () {  
    Route::post('/socialLogin',[UserController::class, 'facebook_login']); 
    // position according to followers
    Route::get('/TopPositionUsers',[UserController::class, 'TopPositionUsers']); 
   Route::get('/qrCode/{userId}',[UserController::class, 'qrCode']);
   Route::get('/UpdateRank/{userId}',[SocialController::class, 'updateUserRank']);
 Route::post('/socialPointCalculation',[SocialController::class, 'social_point_calculation']);
 Route::post('/Fbsignup',[SocialController::class,'Fb_user_Register']);
 
 Route::post('/facebook/exchange',[SocialController::class,'facebook_exchange']);

 Route::post('/Fbuser_accountsdetails',[SocialController::class, 'fbuser_accountsdetails']);
 Route::post('/Fbuser_pagesdetails',[SocialController::class, 'fbuser_pagesdetails']);
 Route::get('/Fbuser_pageslisting',[SocialController::class, 'fbuser_pageslisting']);
 Route::post('/fbuser_page_feed',[SocialController::class, 'fbuser_page_feed']);
 

 Route::get('/fbuser_detailsbyid/{user_id}',[SocialController::class, 'fbuser_detailsbyid']);
   Route::get('/countryList',[UserController::class, 'country_list']);
 Route::get('/test',[UserController::class, 'testData']);
 
 Route::post('/sponser_signup',[SponserController::class, 'register']);
 Route::post('/do_login',[UserController::class, 'doLogin']);
 Route::post('/googleUserDetails', [SocialController::class, 'googleUserDetails']);
 Route::get('/termCondition',[UserController::class, 'termCondition']);
 Route::get('/privacyPolicy',[UserController::class, 'privacyPolicy']);
Route::post('/forgotPassword',[UserController::class, 'forgotPassword']);
Route::post('/verifyOTP',[UserController::class, 'verifyOTP']); 
Route::post('/resetPassword',[UserController::class, 'resetPassword']); 

Route::get('/postShare',[PostController::class, 'postShare']); 
Route::get('/profileShare',[UserController::class, 'userProfileShare']); 
Route::get('/updateEncryptionKey',[UserController::class, 'update_encryption']); 
Route::post('/signup',[UserController::class, 'register']);

Route::post('/sendContactUsEmail',[UserController::class,'sendContactUsEmail']);
Route::get('/clear', function() {
  
    //\Artisan::call('route:cache');
    \Artisan::call('config:cache');
     \Artisan::call('cache:clear');
     \Artisan::call('view:clear');
     echo "cache cleared";
 //  \Artisan::call('optimize:clear');
});
       
    
Route::middleware([Cors::class,EnsureTokenIsValid::class,ImageInterceptorMiddleware::class])->group(function () {
    //Notification and universal e

    // Package api
    
    Route::post('/savePackage',[PackageController::class, 'save_package']); 
    Route::get('/packegeDetails',[PackageController::class, 'package_details']); 
    Route::get('/packegeDetailsbyid',[PackageController::class, 'packegeDetailsbyid']);
    Route::post('/deletePackage',[PackageController::class, 'delete_package']);
    Route::post('/updatePackage',[PackageController::class, 'update_package']);
    Route::post('/addPackage',[PackageController::class, 'addPackage']);
    Route::post('/userbyPackage',[PackageController::class, 'userbyPackage']);
    Route::get('/fbUserDetails',[PackageController::class, 'userdetails']); 
    Route::get('/userAddPackageListing',[PackageController::class, 'userAddPackageListing']);
    Route::post('/deleteUserPackage',[PackageController::class, 'delete_adduserpackage']);
    Route::post('/deleteUserpackage',[PackageController::class, 'delete_userpacuserdetailskage']);
    Route::post('/paymentStatusbyUserpackage',[PackageController::class, 'paymentStatusbyUserpackage']);
 
    
    // end package api

    Route::post('/sharePostDetail',[PostController::class, 'sharePostDetail']); 
   Route::post('/pushNotification',[NotificationController::class, 'sendNotification']);
   Route::post('/iosPushNotification',[NotificationController::class, 'sendAPNProduction']);




   
    Route::post('/authCallback',[SocialController::class, 'tiktok_authCallback']);
     Route::post('/firebaseToken',[NotificationController::class, 'save_token']); 

     Route::post('/deletePost',[PostController::class, 'deletePost']);  

    Route::post('/deleteComment',[PostController::class, 'delete_comment']);  
    Route::post('/saveContactus',[PostController::class, 'save_contactus']); 

    Route::post('/updateSocialInfo',[UserController::class, 'updateSocialInfo']);
    Route::post('/user_interest',[UserController::class, 'user_interest']);
 
    Route::post('/logout',[UserController::class, 'logout']);
    Route::post('/save_sponser',[UserController::class, 'save_sponser']);    
    Route::post('/notificationsList',[UserController::class, 'notificationsList']);
    Route::post('/udpateNotifications',[UserController::class, 'udpateNotifications']);
    Route::post('/readAllNotifications',[NotificationController::class, 'readAllNotification']);
    Route::post('/readNotification',[NotificationController::class, 'readNotification']);



    Route::post('/deActivateUserAccount',[UserController::class, 'deActivateUserAccount']);
    Route::post('/updateUserProfile',[UserController::class, 'updateUserProfile']);
    Route::post('/updateProfileImage',[UserController::class, 'updateProfileImage']);
    Route::post('/changePassword',[UserController::class, 'changePassword']);
    Route::post('/updateCountry',[UserController::class, 'updateUserProfile']);
    //user_list
    Route::post('/userList',[UserController::class, 'new_userList']);
    Route::post('/newuserList',[UserController::class, 'new_userList']);
    Route::post('/userListByRank',[UserController::class, 'userListByRank']);

    Route::get('/user_filter',[UserController::class, 'user_filter']);
    Route::post('/userDetail',[UserController::class, 'user_detail']);
    Route::post('/userProfileDetail',[UserController::class, 'profile_detail']);
    Route::post('/userPostList',[PostController::class, 'userPostList']);
    
    //post
    Route::post('/savePost',[PostController::class, 'save_post']); 
    Route::post('/postFileUpload',[PostController::class, 'postFileUpload']); 
     
	Route::post('/saveComment',[PostController::class, 'save_comment']); 
	Route::post('/postList',[PostController::class, 'post_list']); 
    Route::post('/addLike',[PostController::class, 'add_like']); 
    Route::get('/sendPwdEmail',[PostController::class, 'sendPwdEmail']); 
    Route::post('/postDetail',[PostController::class, 'post_detail']); 
    Route::post('/postDelete',[PostController::class, 'deletePost']); 
    Route::post('/editPost',[PostController::class, 'edit_post']);
    Route::post('/deletePostFile',[PostController::class, 'delete_post_file']); 

    
    Route::post('/fileList',[PostController::class, 'file_list']);

    //youtube  
        Route::post('/youtube/channel-stats/{channelName}', [SocialController::class, 'getChannelStats']);
        Route::get('/youtube/getYoutubeData/{channelName}', [SocialController::class, 'getYoutubeData']);
        

    //  Instagramme
    Route::post('/InstagramMeUserDetails', [SocialController::class, 'getInstagramUserDetails']);
    Route::post('/InstagramData', [SocialController::class, 'InstagramData']);
    Route::get('/AllInstagramMeUserDetails', [SocialController::class, 'AllInstagramUserDetails']);

    //Google User
    
   Route::get('/socialLoginUserDetails', [SocialController::class, 'socialLoginUserDetails']);
    //user follow following
    Route::post('/acceptFollowing',[UserController::class, 'accept_following']);



    Route::post('/followingRequest',[UserController::class, 'following_request']);
    Route::post('/followingList',[UserController::class, 'following_list']); 
    Route::post('/followerList',[UserController::class, 'follower_list']); 
    Route::post('/followingRequestList',[UserController::class, 'following_request_list']); 
    Route::post('/userFollowerList',[UserController::class, 'user_follower_list']); 
    Route::post('/userQrCodeDetail',[UserController::class, 'user_qrCodeDetail']); 

    

    //Social Connect
    Route::post('/socialConnect',[UserController::class, 'social_connect']); 

    //user host
    Route::post('/addHost',[UserController::class,'add_user_host']); 
    Route::post('/removeHost',[UserController::class, 'remove_user_host']);
    Route::post('/acceptRequestHost',[UserController::class, 'accept_request_host']); 

    //user profile video
    Route::post('/addProfileVideo',[UserController::class,'profile_video']); 
    Route::post('/hostUserList',[UserController::class,'host_user_list']);  
    Route::post('/hostUser',[UserController::class,'host_user']); 
    Route::post('/userHostList',[UserController::class,'user_host_list']);  

    //user dashboard api
    Route::post('/dashboard',[UserController::class, 'user_dashboard']); 
    Route::post('/coverImage',[UserController::class, 'cover_image']); 

    // Advertisement and sponser     
    Route::post('/sponserList',[PostController::class, 'sponser_list']);
    Route::post('/saveAdvertisement',[PostController::class, 'save_advertisement']); 
    Route::get('/userAdvertisement_listing',[UserController::class, 'user_advertisement_listing']);
    Route::get('/advertisement_listing',[UserController::class, 'advertisement_listing']);
    Route::post('/advertisementDetail',[UserController::class, 'sponser_detail']); 
    Route::post('/adsSponserList',[PostController::class, 'ads_sponser_list']);
    
    //user 05-April-2023
    Route::post('/removeProfileImage',[UserController::class, 'removeProfileImage']);
    Route::post('/updateCoverImage',[UserController::class, 'updateCoverImage']);
    Route::post('/removeCoverImage',[UserController::class, 'removeCoverImage']);
    
    // Advertisement Delete and Edit API
    Route::post('/advsDelete',[UserController::class, 'advertisement_delete']);
    Route::post('/advertisementEdit',[UserController::class, 'advertisement_edit']);
    Route::post('/isPrivateAccount',[UserController::class, 'is_private_account']);

       //Check Private User Or Not
    Route::post('/checkPrivateAccount',[UserController::class, 'check_private_account']);

        //Notification Controller
    Route::post('/saveToken',[NotificationController::class, 'save_token']);

    Route::post('/getUserAccountType',[UserController::class, 'checkPrivate']); 
    Route::post('/getUserData',[UserController::class, 'getUser_Data']); 

     //Post Report API
    Route::post('/reportTextList',[PostController::class, 'report_comment_list']); 
    Route::post('/savePostReport',[PostController::class, 'save_post_report']); 
    Route::post('/updatePostReport',[PostController::class, 'update_report']); 
    Route::post('/checkFollowOrNot',[PostController::class, 'checkFollowerOrNot']);

    //payment 
    Route::get('/showPaymentByUserPackageId/{userpackageid}',[PaymentController::class,'index']); 
    Route::get('/paymentHistory',[PaymentController::class,'Payment_history']); 
});

Route::get('/getSocialData',[SocialController::class, 'getSocialDataByCron']);


});