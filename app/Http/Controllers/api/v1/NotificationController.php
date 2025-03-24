<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\model\users;
use Hash;
use DB ;
use Image ;
use File ;
use Mail ;
use App\Models\User;
use App\Http\Controllers\api\v1\SocialController;

class NotificationController extends Controller
{
     public function save_token(Request $request){
        
        $rules=[   
            'firebase_token' => 'required',
            'deviceType' => 'required'               
           ] ;
           			
            $validatedData = Validator::make($request->all(),$rules);
           
        if($validatedData->fails()){       
            return $this->errorResponse($validatedData->errors()->first(), 200);
          }

          $userId = authguard()->id ;
          $encryption=authguard()->encryption ;
          $deviceToken=$request->firebase_token ;
          $deviceType=$request->deviceType ;
          saveDeviceToken($userId,$deviceToken,$deviceType,$encryption);			
         
          return $this->successResponse([],'Successfully Saved',200);    
    }

    public function SponserNotificationController(Request $request){
      die("hello");
      $firebaseToken = $request->device_token ;
      $title=$request->title ;
      $body=$request->body ;
      $userId = $request->userId ;
      $body1=array(
                  "NotificationMgs"=> $body,
                  "Action"=> 4,
                  "UserId"=>"test",
                  "user_id"=>$userId
                );
      sendPushNotification($firebaseToken,$title,$body1);
      //User::getAllFollowingAndFollowersId($userId);
      exit ;
      
     // $data=User::whereNotNull('id')->pluck('id')->all();
     // echo "<pre>";
     // print_r($data);
     // echo $data = (new SocialController)->exampleFunction();
     //  exit ;

    }

    public function readAllNotification(Request $request){
      $userId=authguard()->id ;
      DB::table("notifications")->where('userId',$userId)->update(['isRead'=>1]);
      return $this->successResponse([],'All notifications have been read',200);    
    }

    public function readNotification(Request $request){
      
      $rules=[   
            'notificationId' => 'required'            
           ] ;
                
      $validatedData = Validator::make($request->all(),$rules);
           
    if($validatedData->fails()){       
        return $this->errorResponse($validatedData->errors()->first(), 200);
      }

      $notificationId=$request->notificationId ;
      
      DB::table("notifications")->where('id',$notificationId)->update(['isRead'=>1]);
      return $this->successResponse([],'Notifications have been read',200);    
    }
    
  
}