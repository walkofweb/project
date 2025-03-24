<?php 


function httpPost()
{

  $curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://newqa.suffix.events/event/testCurl',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    'Cookie: SESSION_ID=78a8f8dc31e61a6f406aa4718ca00f98'
  ),
));


echo $response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);

if ($err) {
    echo "cURL Error #:" . $err;
} else {
  echo $response ;
    print_r($response);
}


}
function updateUserFollowers($userId){
    $qry="select sum(totat_followers) as totat_followers from (
      Select case when sum(page_followers) is null then 0 else sum(page_followersyy) end as totat_followers from user_fb_page_info where userId=".$userId."
      union
      Select case when sum(followers_count) is null then 0 else sum(followers_count) end as total_followers from user_instagram_info where userId=".$userId."
      union
      select case when sum(followers_count) is null then 0 else sum(followers_count) end as total_followers from tiktok_user_info where userId=".$userId."
      union
      select case when sum(followers_count) is null then 0 else sum(followers_count) end as total_followers from youtube_user_info where userId=".$userId."
      union
      select case when count(id) is null then 0 else count(id) end as total_followers from user_follows where follower_user_id=".$userId.") as userFollowers" ;
   $userFollowers=DB::select($qry);
   $totalUserFollower = isset($userFollowers[0]->totat_followers)?$userFollowers[0]->totat_followers:0 ;
   DB::table('users')->where('id',$userId)->update(['followers'=>$totalUserFollower]);
   
}

function test(){
	echo "hello";
}

function authguard(){

 $token=Auth::guard('api')->user();

 return $token ;
 // if(empty($token)){
 // 	$token=array();
 // }
}
   function sendPasswordToEmail(Array $data){  

   	 $type=isset($data['type'])?$data['type']:'' ;

       $data = array(
        'email' => $data['email'],
        'subject' =>  $data['subject'],
        'messages' => $data['message']
        );

        if($type=='user_subscribers'){
          Mail::send('emails.subscribers', $data, function($message) use ($data) {
            $to= $data['email'] ;
            $recieverName = "" ;
            $subject = $data['subject'] ;         
              $message->to($to,$recieverName)->subject($subject);                    
          });

        }else{
    
         Mail::send('emails.password', $data, function($message) use ($data) {
          $to= $data['email'] ;
          $recieverName = "" ;
          $subject = $data['subject'] ;
         
            $message->to($to,$recieverName)->subject($subject);
                    
          });
 		}
 		
        if (Mail::failures()) {
          // return response()->Fail('Sorry! Please try again latter');
          //echo "Something Error Occured" ;
          return false ;
         }else{
          return true ;
          //echo "Mail send successfully" ;
          // return response()->success('Great! Successfully send in your mail');
         }
   }



function userfollowers($userId){
      return $followers = DB::table('social_info')->select(DB::raw('sum(followers_count) as totalFollowers'))->where('status',1)->where('user_id',$userId)->get();
}


function createdAt($created_at)
    { 
        $created_at = str_replace([' seconds', ' second'], ' sec', $created_at);
        $created_at = str_replace([' minutes', ' minute'], ' min', $created_at);
        $created_at = str_replace([' hours', ' hour'], ' h', $created_at);
        $created_at = str_replace([' months', ' month'], ' m', $created_at);
        $created_at = str_replace([' before'], ' ago', $created_at);
        if(preg_match('(years|year)', $created_at)){
            $created_at = $this->created_at->toFormattedDateString();
        }

        return $created_at;
    }


function countryList(){
   $countries = DB::table('pe_countries')->select('id','title',DB::raw('api_code as countryCode'))->where('i_status',1)->get();
   return $countries ;
}

function do_upload_unlink($unlink_data=array()){

  if(!empty($unlink_data)){

    foreach($unlink_data as $val){

      if(File::exists($val)) { 
      
      unlink($val);
      }
  }

  }
 }

 function printQuery($query){
  DB::enableQueryLog();
   $query ;
   
   print_r(\DB::getQueryLog());
 }


function auth_check(){

  $current = Route::getFacadeRoot()->current();
 $uri = $current->uri();
  
  $get_SessionData = Session::get('admin_session');
  $getSaponer_SessionData = Session::get('sponser_session');
  $getUser_SessionData = Session::get('user_session');
  
  if($uri=='sponser/do_login' && empty($getSaponer_SessionData))
  {
    return redirect()->to('/sponser/login')->send();
  }
  if($uri=='/administrator/do_login' && empty($get_SessionData)){
    
      return redirect()->to('/administrator')->send();
  }
  if($uri=='/user/do_login' && empty($getUser_SessionData)){
    
    return redirect()->to('/user/dashboard/')->send();
}

  //   $get_SessionData = Session::get('admin_session');
  //   $get_sponser=Session::get('sponser_session');
  //   $get_user=Session::get('user_session');
  //   if(!empty($get_SessionData)){
      
  //       return redirect()->to('/administrator')->send();
  //   }
  //   else if(!empty($get_sponser)){
      
  //     return redirect()->to('/sponser/login')->send();
  // }
  // else{
  //   return redirect()->to('/login')->send();
  // }

}

function successResponse($data,$msg=''){
    $response = array(
      "status"=>1,
      "data"=>$data,
      "message"=>$msg
    );

    echo json_encode($response);
}


function errorResponse($data,$msg=''){
    $response = array(
      "status"=>0,
      "message"=>$msg
    );

    echo json_encode($response);
}

function siteTitle(){
  return 'Walkofweb';
}

function requestLog($data){
     DB::table('image_log')->insert(['data'=>$data]);
 }

function saveDeviceToken($userId,$deviceToken,$deviceType,$encryption,$token){
          $insertData=array(
            "userId"=>$userId ,
            "deviceToken"=>$deviceToken ,
            "deviceType"=>$deviceType,
            "encryption"=>$encryption,
            "api_token"=>$token 
          );
         
        $checkUser = DB::table('device_token')->select('id')->where('userId',$userId)->first();
       
       
        if(empty($checkUser)){          
           DB::table('device_token')->insert($insertData);
        }else {

         $updatedId = isset($checkUser->id)?($checkUser->id):0 ;
         DB::table('device_token')->where('id',$updatedId)->update(["deviceToken"=>$deviceToken,"deviceType"=>$deviceType,"api_token"=>$token]);
          
        }       

}

function checkDevice(){

  $isAndroid = is_numeric(strpos(strtolower($_SERVER["HTTP_USER_AGENT"]), "android")); 
  $isIPhone = is_numeric(strpos(strtolower($_SERVER["HTTP_USER_AGENT"]), "iphone")); 
  $isIPad = is_numeric(strpos(strtolower($_SERVER["HTTP_USER_AGENT"]), "ipad")); 
  $isMac = is_numeric(strpos(strtolower($_SERVER["HTTP_USER_AGENT"]), "mac"));
  

  $isIOS = $isIPhone || $isIPad || $isMac; 
  $data=array('isIOS'=>0,'isAndroid'=>0,'isDesktop'=>0) ; 

  if($isIOS){ 
    $data['isIOS']=1 ;
  }elseif($isAndroid){ 
    $data['isAndroid']=1 ;
  }else{
    $data['isDesktop']=1 ;
  }

  return $data ;

}

function getRankType($point){
  
  $qry=DB::select("select id from rank_types where ".$point." BETWEEN range_from AND  range_to") ;
  if(!empty($qry)){
    $rankType = isset($qry[0]->id)?$qry[0]->id:0 ;
  }else{
    $rankType = 0 ;
  }
  return $rankType ;
}

function saveNF($userId,$title,$message,$data='',$hostId=0){
           
  $insertData=array(
    "userId"=>$userId ,
    "hostId"=> 0,
    "title"=>$title ,
    "message"=>$message ,
    "data"=>$data,
    "isSend"=>1 ,
    "status"=>1,
    "isAccept"=>1
  );
  DB::table("notifications")->insert($insertData);
}

function sendPushNotification($device_token,$title,$body,$deviceType=1){  

        $SERVER_API_KEY = env('FCM_SERVER_KEY');
            
          
          $data = array(
            "registration_ids" => array($device_token),
            "notification" => array(
               "title" => $title,
                  "body" => $body['NotificationMgs'],
                  "UserId"=>$body['UserId'] ,
                  "user_id"=>$body['user_id'],
                   "Action"=>$body['Action'] 
            )
          );    
          
        // if($deviceType==2){
            
        // }else{
        //     $data = array(
        //     "registration_ids" => array($device_token),
        //     "notification" => array(
        //        "title" => $title,
        //           "body" => $body
        //     )
        //   );    
        // }




        $dataString = json_encode($data);
      
      //saveNF(11,$title,'Hello Team',$dataString);
        $headers = array(
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        );
    
        $ch = curl_init();
      
        curl_setopt($ch, CURLOPT_URL, env('FCM_URL'));
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
               
        $response = curl_exec($ch);
        $response_ = json_decode($response);
        if(!empty($response_) && isset($response_->success) && $response_->success==1){
          return true ;
          //echo 'success' ;
        }else{
          return false ;
          //echo 'fail' ;
        }
}

function removePostDir($postId){
  try{
    $dir=storage_path('app/public/post_image/'.$postId) ;
  if (is_dir($dir))
  {
  $objects = scandir($dir);
  foreach ($objects as $object)
  {
  if ($object != '.' && $object != '..')
  {
  if (filetype($dir.'/'.$object) == 'dir') {rrmdir($dir.'/'.$object);}
  else {unlink($dir.'/'.$object);}
  }
  }

  reset($objects);
  rmdir($dir);
 }
}catch(Exception $e){
           return $this->errorResponse('something went wrong', 200);  
  }

 

}

?>
