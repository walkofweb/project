<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Http\Request;
use App\Models\Post_comment;
use App\Models\Post_image;
use App\Models\Post_like;
use App\Models\Post;
use App\Models\Social_info;
use App\Models\User_fb_pages;
use App\Models\User_instagram_info;
use App\Models\Fb_user_info;
use App\Models\Insta_user_info;
use DB ;
use GuzzleHttp\Client;
use App\Services\InstagramService;
use Hash;
use App\Models\Youtube_user_info;
use App\Http\Controllers\api\v1\UserController;
use App\Models\User;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
class SocialController extends Controller
{
    protected $instagramService;

    public function __construct(InstagramService $instagramService)
    {
        $this->instagramService = $instagramService;
    }


    public function googleUserDetails(Request $request)
    {
        $validatedData = Validator::make($request->all(),[
            'google_id'=>'required',
            'name'=>'required',
            'email'=>'required',
           'token'=>'required',
           'image'=>'required'
              ]);
      
           if($validatedData->fails()){
      
                return $this->errorResponse($validatedData->errors()->first(), 200);
              }
              $name='';
              
              $name=$request->name;
              $usrName = substr($name,0,2);
              $insertArray=array(
                'google_id'=>$request->google_id,
                'name'=>$request->name,
                'email'=>$request->email,
                'image'=>$request->image,
                'password'=>Hash::make($request->name.'@'.$request->google_id),
                'user_type'=>5,
                'username'=>$usrName
              );
             
                $save_user=User::updateorCreate(
                  [
                    'google_id'=>$request->google_id,
                  ],
                  [
                    'google_id'=>$request->google_id,
                    'name'=>$request->name,
                    'email'=>$request->email,
                    'image'=>$request->image,
                    'password'=>Hash::make($request->name.'@'.$request->google_id),
                    'user_type'=>4,
                    'username'=> $usrName,
                    'registration_from'=>1,
                    'remember_token'=> Str::random(10)
                  
                    ]
                );
              
                if($save_user)
                {
                    $user=User::where('google_id',$request->google_id)->first();
                    // dd($user);
                    // die;
                     //  $finduser=User::where('social_id', $data->id)->first()->toArray();
  
                     $username='';
                     $token =$user->createToken('walkofweb token')->accessToken;
                    
                     $message =  __('messages.user_registration') ; //"Account has been created successfully." ;
                     $insertId=$user->id;
                     $encryptionKey = md5('wow_intigate_23'.$insertId);
                     
                    // $userName = $this->UsernameGenerate($usrName,$insertId);  
                    
                     $number = mt_rand(10000,99999);
                     $u_name = "WOW".strtoupper($usrName).$insertId ;
                     $userExist= User::where(['username'=>$u_name])->exists();
                   if($userExist)
                   {
                    $u_name= User::select('username')->where(['username'=>$u_name])->first();
                    $u_name= $u_name->username;
                    
                   }
                   else{
                    $u_name= $u_name;
                   }
                   DB::table('users')->where('id',$insertId)->update(['encryption'=>$encryptionKey,'username'=>$u_name]);
                   $deviceType= $user->registration_from;
                   $fairebaseToken='';
                     saveDeviceToken($insertId,$fairebaseToken, $deviceType,$encryptionKey);
                    $data1['user_details']= $user ;
                     $data1['token'] = $token  ;
                     $data1['encryption']=$encryptionKey ;
                   
                    
                     $userData=array(
                     'id'=>$data1['user_details']['id']?$data1['user_details']['id']:'-',
                    
                      'first_name' => $data1['user_details']['first_name']?$data1['user_details']['first_name']:'-',
                      'user_type' => $data1['user_details']['user_type']?$data1['user_details']['user_type']:'-',
                     'last_name' => $data1['user_details']['last_name']?$data1['user_details']['last_name']:'-',
                     'name_format' => $data1['user_details']['name_format']?$data1['user_details']['name_format']:'-',
                     'email'=>$data1['user_details']['email']?$data1['user_details']['email']:'-',
                     'image' => $data1['user_details']['image']?$data1['user_details']['image']:'-',
                     'name' => $data1['user_details']['name']?$data1['user_details']['name']:'-',
                     'username' => $data1['user_details']['facebook_username']? $data1['user_details']['facebook_username']:'-'
                     
  
                     );
                   
                    return response()->json(['message'=>'Google Login Registration Successfully','data'=>['User_details' =>$userData],'status_code'=>200,'status' => 'success']);
  
                     

                  
                }
                
        
              
              else{
                return response()->json(['message'=>'error in save details','status_code'=>400]);
              }
             
    }

    public function getYoutubeData($channelId)
    {
        if (preg_match('/[0-9]/', $channelId)) {
            $channel_id=$channelId;
        }
        else{
            $channel_id='';
        }
        $apiKey = config('services.youtube.api_key');
        if(empty($channel_id))
        {
            
                $searchChannelUrl = "https://www.googleapis.com/youtube/v3/search";
                $channelResponse = Http::get($searchChannelUrl, [
                    'key' => $apiKey,
                    'q' => $channelId,
                    'type' => 'channel',
                    'part' => 'snippet',
                    'maxResults' => 1,
                ]);
   
            if ($channelResponse->successful() && isset($channelResponse['items'][0])) {
                $channelId = $channelResponse['items'][0]['id']['channelId'];
            }
         }

        $url = "https://www.googleapis.com/youtube/v3/channels?part=statistics&id={$channelId}&key={$apiKey}";

        // API कॉल करें
        $response = Http::get($url);
    
         
        
        if ($response->successful()) {
            $data = $response->json();
            if (!empty($data['items'])) {
                $stats = $data['items'][0];
                return response()->json(['message'=>' You tube Channel Details fetch sucessfully','data'=>['channel_details'=>$stats],'status_code'=>200,'status' => 'success']);

            }
        }
        else{
            return response()->json(['message'=>'error in Channel Details','status_code'=>400]);
        }
   
    }

    
    public function getChannelStats($channelId,Request $request)
    {
        $validatedData = Validator::make($request->all(),[
            'user_id'=>'required'
              ]);
      
           if($validatedData->fails()){
      
                return $this->errorResponse($validatedData->errors()->first(), 200);
              }

             $user_id=$request->user_id;
        
        if (preg_match('/[0-9]/', $channelId)) {
            $channel_id=$channelId;
        }
        else{
            $channel_id='';
        }
        $apiKey = config('services.youtube.api_key');
       if(empty($channel_id))
       {
           
           $searchChannelUrl = "https://www.googleapis.com/youtube/v3/search";
           $channelResponse = Http::get($searchChannelUrl, [
          'key' => $apiKey,
          'q' => $channelId,
          'type' => 'channel',
          'part' => 'snippet',
          'maxResults' => 1,
      ]);
  
      if ($channelResponse->successful() && isset($channelResponse['items'][0])) {
       $channelId = $channelResponse['items'][0]['id']['channelId'];
      }
   }

      
   try{

       
        $url = "https://www.googleapis.com/youtube/v3/channels?part=statistics&id={$channelId}&key={$apiKey}";

        // API कॉल करें
        $response = Http::get($url);
       
  
        if ($response->successful()) {
            $data = $response->json();
        //  dd($data);

            if (!empty($data['items'])) {
                $stats = $data['items'][0]['statistics'];

                
             
                $post = Youtube_user_info::updateOrCreate([
                    'userId' => $user_id
                ], [
                    'userId' => $user_id,
                    'channel_id' => $channelId,
                   
                    'video_count' => $stats['videoCount'],
                    'view_count' =>  $stats['viewCount'],
                    'subscriber_count' => $stats['subscriberCount'],
                    
                ]);
        
             
                $Pages['details']=$post->toArray();
               
           
                if($post)
                {
                   

                    $post = Youtube_user_info::updateOrCreate([
                        'userId' => $user_id
                    ], [
                        'userId' => $user_id,
                        'channel_id' => $channelId,
                       
                        'video_count' => $stats['videoCount'],
                        'view_count' =>  $stats['viewCount'],
                        'subscriber_count' => $stats['subscriberCount'],
                        
                    ]);
                    if($post)
                    {
                       $social_info= Social_info::updateOrCreate([
                        'user_id' => $user_id,
                        'type'=>6,
                        'social_type'=>6,
                        
                    ], [
                        'user_id' =>$Pages['details']['userId'],
                        'type'=>6,
                        'social_type'=>6,
                        'social_id' =>$Pages['details']['userId'],
                       
                        'follows_count' =>$stats['viewCount'],
                        'followers_count' =>$stats['subscriberCount'],
                        'status' => 1,
                        
                    ]);
                    if($social_info)
                    {
                        return response()->json(['message'=>'Saved You tube Channel Details in database sucessfully','data'=>['pages'=>$Pages['details']],'status_code'=>200,'status' => 'success']);
                    }

                       
                    }
                    else{
                        return response()->json(['message'=>'error in save details','status_code'=>400]);
                    }
                   
                   
                   // $result=  DB::table('social_info')->insert($insert);
               
                    
                }
                else{
                    return response()->json(['message'=>'error in save details','status_code'=>400]);
                }

              
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Channel not found',
                ]);
            }
        }
    } catch(Exception $e){
        return $this->errorResponse(__('Failed to fetch data'), 401);	
   }
       
    }
    // Get Instagram user follow

    public function InstagramData(Request $request)
    {
       

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://i.instagram.com/api/v1/users/web_profile_info/?username=walkofweb',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    'User-Agent: Instagram 76.0.0.15.395 Android (24/7.0;640dpi; 1440x2560; samsung; SM-G930F; herolte; samsungexynos8890; en_US; 138226743)',
    'Cookie: csrftoken=X7zHr7uy2rDGaEL6LGOm7mGbrdycENUU; ig_did=AF20967B-042A-4674-81A4-EAF7B3CD2BE4; ig_nrcb=1; mid=Z2cW1gAEAAEJ44LsghQxQz_B7zi_'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;
    
    }

    public function getInstagramUserDetails(Request $request)
    {
        $validatedData = Validator::make($request->all(),[
            'ins_id'=>'required',
            'name'=>'required',
            'usernme'=>'required',
           'mediacount'=>'required',
           'follower_count'=>'required',
           'followcount'=>'required',
           'user_id'=>'required'
              ]);
      
           if($validatedData->fails()){
      
                return $this->errorResponse($validatedData->errors()->first(), 200);
              }
              
        try{

           
                  $post = Insta_user_info::updateOrCreate([
                    'bussiness_accountId' => $request->ins_id,
                ], [
                    'userId' => $request->user_id,
                    'bussiness_accountId' => $request->ins_id,
                   
                    'followers_count' => $request->follower_count,
                    'follows_count' =>  $request->followcount,
                    'name' => $request->name,
                    'media_count' =>  $request->mediacount,
                    'username' => $request->usernme,
                    
                ]);
        
             
                $Pages['details']=$post->toArray();
                
                $insert=array();
              
                if($Pages)
                {
                  

                    $social_info= Social_info::updateOrCreate([
                        'user_id' => $Pages['details']['userId'],
                        'type'=>2,
                        'social_type'=>3,
                        
                    ], [
                        'user_id' =>$Pages['details']['userId'],
                        'type'=>2,
                        'social_type'=>3,
                        'social_id' =>$Pages['details']['bussiness_accountId'],
                       
                        'follows_count' =>$Pages['details']['follows_count'],
                        'followers_count' =>$Pages['details']['followers_count'],
                        'status' => 1,
                        
                    ]);

                   

                    if($social_info)
                    {
                        return response()->json(['message'=>'Saved You Instagramme Details in database sucessfully','data'=>['pages'=>$Pages['details']],'status_code'=>200,'status' => 'success']);
                    }
                    else{
                        return response()->json(['message'=>'error in save details','status_code'=>400]);
                    }
                    return response()->json(['message'=>'Saved You Instagramme Details in database sucessfully','data'=>['pages'=>$Pages['details']],'status_code'=>200,'status' => 'success']);
                }
                else{
                    return response()->json(['message'=>'Saved You Instagramme Details in database sucessfully','data'=>['pages'=>$Pages['details']],'status_code'=>200,'status' => 'success']);
                }
              
               
          
        
        } catch(Exception $e){
        return $this->errorResponse(__('Failed to fetch data'), 401);	
        }
       
    }
    public function AllInstagramUserDetails()
    {
        $user=Insta_user_info::get();
        try{
            if($user){
                return response()->json(['message'=>'Saved You Instagramme Details in database sucessfully','data'=>['Inata User Details'=>$user],'status_code'=>200,'status' => 'success']);
                    
                }
                else{
                    return response()->json(['message'=>'error in save details','status_code'=>400]);
                }
        

            } catch(Exception $e){
            return $this->errorResponse(__('Failed to fetch data'), 401);	
        }

    }
    public function socialLoginUserDetails()
    {
        $userId=authguard()->id;
        $Name=authguard()->name;
        $user_name=authguard()->username;
        $data=User::getFollowers($userId);
        try{

            if(!empty($data)){
                $total_followers=User::getFollowersCount($userId);
                $loginUser['loginUserDetails']['userId']=$userId;
                $loginUser['loginUserDetails']['username']=$user_name;
                $loginUser['loginUserDetails']['name']= $Name;
                $loginUser['loginUserDetails']['facebook_follower']=$data ['facebook_followers']? $data['facebook_followers']:0;
                $loginUser['loginUserDetails']['insta_follower']=$data['insta_followers']?$data['insta_followers']:0;
                $loginUser['loginUserDetails']['youtube_follower']= $data['youtube_followers']?$data['youtube_followers']:0;
                $loginUser['loginUserDetails']['tiktok_followers']=$data['walkofweb_followers']?$data['walkofweb_followers']:0;
                $loginUser['loginUserDetails']['walkofweb_followers']=$data['walkofweb_followers']?$data['walkofweb_followers']:0;
                $loginUser['loginUserDetails']['total_followers']=$total_followers?$total_followers:0;
                             
                return $this->successResponse(['User_details'=> $loginUser['loginUserDetails']],__('All Folowers of Login Users'),200);
    
            }else{
                return $this->successResponse(('Folowers of Login Users not found'),200);
    
            }
        }
        catch(\Exception $e)
        {
           return $this->errorResponse('Error occurred.'.$e, 400);
        }
      
               
       
    }

    public function social_point_calculation(Request $request){

        $userId = isset($request->userId)?$request->userId:0 ;
        $type = isset($request->type)?$request->type:0 ; //type 1 > tiktok , 2 > facebook , 3 > instagram,4 >youtube
       
        if($type==1){
            $this->tiktokPointActivity($userId);            
        }else if($type==2){
           $this->fbPointActivity($userId); 
        }else if($type==3){
            $this->instaPointActivity($userId); 
        }
        else if($type==4){
            $this->youtubeActivity($userId); 
        }
        else{
            
            return true ;
        }  

    }


    /*
    fb user details fetch and save in database
    */
    public function Fb_user_Register(Request $request)
    {
        $access_token=$request->access_token;
        $filePath = config('constants.user_image') ;
        $dummy_image=config('constants.dummy_image') ;

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://graph.facebook.com/v21.0/me?fields=email%2Cname%2Cfirst_name%2Cname_format%2Clast_name%2Cshort_name%2Cpicture%7Bheight%2Ccache_key%2Cis_silhouette%2Curl%2Cwidth%7D&access_token=$access_token");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($ch);

        $data=json_decode($result);
        $profilePictureUrl = $data->picture->data->url;
        $profilePicturePath = 'profile_image/' . $data->id . '.jpg';
        $imageContents = Http::get($profilePictureUrl)->body();
        $image_check=Storage::disk('public')->put($profilePicturePath, $imageContents);
          if($image_check==true)
          {
            $iamge_name=$data->id . '.jpg';
          }
          else
          {
            $iamge_name='';
          }

       

        
       
        return $res=UserController::savefbuser_details($data,$iamge_name,$access_token);
       
       
       
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        

        curl_close($ch);

       
       

    } 

public function facebook_exchange(Request $request)
{
     $userId = authguard()->id ;
    
     $data=User::where('id',$userId)->first();
     $shortToken=$data->facebook_token;
     if (!$shortToken) {
        return response()->json(['error' => 'No short-lived token found'], 400);
    }

    
    $appId = '6058802037574834';
    $appSecret = '60fe2bc3779e9f170628b1bafe7cc686';

    $response = Http::get("https://graph.facebook.com/v18.0/oauth/access_token", [
        'grant_type' => 'fb_exchange_token',
        'client_id' => $appId,
        'client_secret' => $appSecret,
        'fb_exchange_token' => $shortToken,
    ]);
    if ($response->successful()) {
        $data = $response->json();
        dd($data);
        //$longLivedToken = $data['access_token'];
    }
dd($response);
   
}

    
public function fbuser_detailsbyid($social_id)
{
    $finduser = User::where('social_id', $social_id)->first();
    if($finduser){

        if($finduser->user_type==0)
        {
            $user_type="App User";
        } 
        else if($finduser->user_type==1){
            $user_type="Admin";
        }
        else if($finduser->user_type==2){
            $user_type="Fb User";
        }
        else if($finduser->user_type==3){
            $user_type="Google User";
        }
        else{
            $user_type="Apple User";
        }

       

        $user_details=array(
            'id'=>$finduser->id,
            'social_id' => $finduser->social_id,
            'first_name' => $finduser->first_name,
            'last_name' => $finduser->last_name,
            'email' => $finduser->email,
            'username' => $finduser->username,
            'facebook_username' => $finduser->facebook_username,
            'name_format' => $finduser->name_format,
            'image' => $finduser->image?$filePath.$finduser->image:$dummy_image,
            'registration_from'=>$finduser->registration_from==1?'Android ':'IOS',
            'short_name'=>$finduser->short_name,
            'user_type'=>$user_type

       );
        return $user_details;
    }
    else{
        return false;
    }

    }
     /*
    fb pages details    */

    public function fbuser_accountsdetails(Request $request)
    {


        $rules=[            
            'user_id' => 'required',
            'access_token' => 'required',
          
           ] ;

            $validatedData = Validator::make($request->all(),$rules);
      
 
        if($validatedData->fails()){       
            return $this->errorResponse($validatedData->errors()->first(), 200);
          }

      

        $user =User::where('id', $request->id)->first();
       
        if(!empty($user))
        {
            $user_id=$user->id;
        }
        else{
            $user_id=$request->user_id;

        }
    
        $access_token=$request->access_token;


// Generated @ codebeautify.org
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://graph.facebook.com/v21.0/me/accounts?fields=fan_count%2Cfollowers_count%2Cpicture%7Bheight%2Curl%2Cwidth%7D%2Ccategory%2Cid%2Clink%2Cname&access_token=$access_token");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);







        $result = curl_exec($ch);
        $data=json_decode($result);
       
       
  
        $i=0;
        $pages=[];
        if(!empty($data->error))
        {
            return response()->json(['message'=>'Error: access token: Session has expired','status_code'=>400]);
        }
        else{
           
        


            foreach($data->data as $page)
            {
               
               

                $post = Fb_user_info::updateOrCreate([
                    'page_name' => $page->name
                ], [
                    'userId' => $user_id,
                    'page_id' => $page->id,
                    'page_name' => $page->name,
                    'total_friends_count' => $page->fan_count,
                    'picture' => $page->picture->data->url,
                    'link' => $page->link,
                    'category' => $page->category,
                    'fb_page_followers_count' => $page->followers_count,
                ]);

                $social_info= Social_info::updateOrCreate([
                    'social_id' =>$page->id,
                    
                ], [
                    'user_id' =>$user_id,
                    'type'=>'Facebook',
                    'social_type'=>1,
                    'social_id' =>$page->id,
                   
                  
                    'followers_count' =>$page->followers_count,
                    'status' => 1,
                    
                ]);
             
        
               
                $Pages['details'][$i]=$post->toArray();
                
             
               
               $i++;
            
            }
            die("helo");
            if($result)
            {
            return response()->json(['message'=>'Saved Fb Pages Details in database sucessfully','data'=>['pages'=>[$Pages]],'status_code'=>200]);
                
            }
            else{
                return response()->json(['message'=>'error in save details','status_code'=>400]);
            }
            curl_close($ch);
            if (curl_errno($ch)) {
                echo 'Error:' . curl_error($ch);
            }
           
        }
      


      
        
    }   

    public function fbuser_pagesdetails(Request $request)
    {
        $user =Auth::guard('api')->user();
       
        $user_id=$user->id;
        $page_id=$request->page_id;
         $page_details = User_fb_pages::where('page_id', $page_id)->where('user_id', $user_id)->get();
      
        
        if(!empty($page_details))
            {
                $page_details = $page_details->toArray();
             
                return response()->json(['message'=>'Fb Page Details','data'=>['page_details'=>$page_details],'status_code'=>200]);
            }
            else{
                return response()->json(['message'=>'No Fb Page Details found','status_code'=>400]);
            }
  

    }
    public function fbuser_pageslisting(Request $request)
    {
        $user =Auth::guard('api')->user();
        $user_id=$user->id;
        $page_id=$request->page_id;
         $page_details = User_fb_pages::select(['id','page_id','page_name','page_followers','page_fan_count'])->where('user_id', $user_id)->get();
      
        
        if(!empty($page_details))
            {
                $page_details = $page_details->toArray();
             
                return response()->json(['message'=>'Fb Page Details','data'=>['page_details'=>$page_details],'status_code'=>200]);
            }
            else{
                return response()->json(['message'=>'No Fb Page Details found','status_code'=>400]);
            }
  

    }
    public function fbuser_page_feed(Request $request)
    {
        // Generated @ codebeautify.org

        $data=[];

        $access_token=$request->access_token;
        $page_id=$request->page_id;

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "https://graph.facebook.com/v21.0/$page_id?fields=features%2Cposts.limit(10)%7Bmessage%2Ccoordinates%2Cfull_picture%2Cheight%2Cwidth%7D%2Cfan_count%2Cfeed.limit(10)%7Bcomments%7Bcreated_time%2Ccomment_count%2Cmessage%2Cmessage_tags%2Clike_count%2Cuser_likes%7D%7D&access_token=$access_token");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

$result = curl_exec($ch);

$data=json_decode($result);

if(!empty($data->error))
        {
            return response()->json(['message'=>'Error: access token: Session has expired','status_code'=>400]);
        }
        else{
            
          
             $data= json_decode(json_encode ( $data ) , true);
             return response()->json(['message'=>'Fb Page Post Details','data'=>['post details'=>$data],'status_code'=>200]);
            
           
        }
          
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close($ch);

    }

    public function fbPointActivity($userId){
       
        $sPWT = $this->getSocialMediaPoint();
       
      
        $checkSocialUser = Fb_user_info::where('userId','=',$userId)->get();
       
        $data=[];
        $total_friends_count=0;
        $total_page_followers=0;
        $fbPageFollowersWT=0;
        $fbPageFollowersWT=0;
        $fbPageLikesCountWT=0;
        $fbPageLikesCountPoint=0;
        $fbPostCommentsPoint=0;
        $fbPostLikesPoint=0;
        $fbPostCountPoint=0;
        $i=0;
       
        foreach($checkSocialUser as $val)
        {
            $total_friends_count+=$val->total_friends_count;
            $total_page_followers+=$val->fb_page_followers_count;
            $data['total_friends_count']=$total_friends_count;
            $data['page_followers']=$total_page_followers;
           
$i++;
        }
      
        
        if(!empty($data)){   
            // fb activity
            $fbTotalFriends = $data['total_friends_count'] ;
            $fbPageFollowers = $data['page_followers'] ;
            // $fbPageLikesCount = $checkSocialUser->page_fan_count ;
            // $fbPostComments = $checkSocialUser->fb_post_comments ;
            // $fbPostLikes = $checkSocialUser->fb_post_likes ;
            // $fbPostCount = $checkSocialUser->fb_post_count ;
         

            //fb weightage
            $fbFriendsWT=isset($sPWT['fb_friends_count'])?$sPWT['fb_friends_count']:0 ;
            $fbPageFollowersWT=isset($sPWT['fb_page_followers_count'])?$sPWT['fb_page_followers_count']:0 ;
            $fbPageLikesCountWT=isset($sPWT['fb_page_likes_count'])?$sPWT['fb_page_likes_count']:0 ;
            // $fbPostCommentsWT=isset($sPWT['fb_post_comments'])?$sPWT['fb_post_comments']:0 ;
            // $fbPostLikesWT=isset($sPWT['fb_post_likes'])?$sPWT['fb_post_likes']:0 ;
            // $fbPostCountWT=isset($sPWT['fb_post_count'])?$sPWT['fb_post_count']:0 ;
           
            
            // fb point calculation
         $fbTotalFriendsPoint = $fbTotalFriends * $fbFriendsWT ;
   
        
            $fbPageFollowersPoint = $fbPageFollowers * $fbPageFollowersWT ;
            //$fbPageLikesCountPoint = $fbPageLikesCount * $fbPageLikesCountWT ;
            // $fbPostCommentsPoint = $fbPostComments * $fbPostCommentsWT ;
            // $fbPostLikesPoint = $fbPostLikes * $fbPostLikesWT ;
            // $fbPostCountPoint = $fbPostCount * $fbPostCountWT ;

            $fbTotalPoint = $fbTotalFriendsPoint + $fbPageFollowersPoint   ;
          //  $fbTotalPoint = $fbTotalFriendsPoint + $fbPageFollowersPoint + $fbPageLikesCountPoint
             + $fbPostCommentsPoint + $fbPostLikesPoint + $fbPostCountPoint  ;
            

          $checkSocialPoint = $this->checkSocialPoint($userId);
       
            $updateData=array(
                'fb_friends_count'=>$fbTotalFriendsPoint ,
                'fb_page_followers_count'=>$fbPageFollowersPoint ,
                // 'fb_page_likes_count'=>$fbPageLikesCountPoint ,
                // 'fb_post_comment'=>$fbPostCommentsPoint ,
                // 'fb_post_likes'=>$fbPostLikesPoint ,
                // 'fb_post_count'=>$fbPostCountPoint 
            );
          
         
            if($checkSocialPoint==0){
                $updateData['user_id']=$userId ;
                $avgPoint = $fbTotalPoint/100 ;
                $updateData['total_point']=$fbTotalPoint ;
                $updateData['avg_point']=$avgPoint ;
                DB::table('user_social_point')->insert($updateData);
                $this->updateUserPoint($userId,$avgPoint);
            }else{        
                $currentTotalPoint =  $checkSocialPoint + $fbTotalPoint ;
                $avgPoint =  $currentTotalPoint / 100 ;   
                $updateData['total_point']=$currentTotalPoint ;
                $updateData['avg_point']=$avgPoint ;              
                DB::table('user_social_point')->where('user_id',$userId)->update($updateData);
                $this->updateTotalPoint($userId);
                 
            }
             return 1 ;

        }    
        else{
            return 0;
        }    
    }

    public function youtubeActivity($userId){
       
        $sPWT = $this->getSocialMediaPoint();
       
        
        $checkSocialUser = youtube_user_info::where('userId','=',$userId)->get();
       
       
        $data=[];
        $total_friends_count=0;
        $total_followers=0;
        $totalfollower_count=0;
        $total_view_count=0;
        $total_video_count=0;
        $total_subscriber_count=0;
        $youtubeFollowersWT=0;
       
        $youtubeview_countCountWT=0;
        $youtubevideo_countPoint=0;
       
        $youtubesubscriber_CountPoint=0;
        $youtubeTotalPoint=0;
        $youtubeTotalfollowerPoint=0;
        
        $i=0;
       
        foreach($checkSocialUser as $val)
        {
            $data['followers_count']=$total_followers+$val->followers_count;
            $data['view_count']=$total_view_count+$val->view_count;
            $data['video_count']=$total_video_count+$val->video_count;
            $data['subscriber_count']= $total_subscriber_count+$val->subscriber_count;
$i++;
        }
       
        
        if(!empty($data)){   
            // fb activity
            $totalfollower_count = $data['followers_count'] ;
            $total_view_count = $data['view_count'] ;
            $total_video_count=$data['video_count'];
            $total_subscriber_count=$data['subscriber_count'];
            // $fbPageLikesCount = $checkSocialUser->page_fan_count ;
            // $fbPostComments = $checkSocialUser->fb_post_comments ;
            // $fbPostLikes = $checkSocialUser->fb_post_likes ;
            // $fbPostCount = $checkSocialUser->fb_post_count ;
         

            //fb weightage
           
            $youtubeFollowersWT=isset($sPWT['youutube_followers_count'])?$sPWT['youutube_followers_count']:0 ;
            $fbviewCountsWT=isset($sPWT['fb_view_count'])?$sPWT['fb_view_count']:0 ;
            $fbvideoCountsWT=isset($sPWT['fb_video_count'])?$sPWT['fb_video_count']:0 ;
            $fbsubscriberCountsWT=isset($sPWT['fb_subscriber_count'])?$sPWT['fb_subscriber_count']:0 ;
            // $fbPostCommentsWT=isset($sPWT['fb_post_comments'])?$sPWT['fb_post_comments']:0 ;
            // $fbPostLikesWT=isset($sPWT['fb_post_likes'])?$sPWT['fb_post_likes']:0 ;
            // $fbPostCountWT=isset($sPWT['fb_post_count'])?$sPWT['fb_post_count']:0 ;
          
            
            // fb point calculation
            $youtubeTotalfollowerPoint = $totalfollower_count * $youtubeFollowersWT ;
            $total_view_count=$total_view_count*$fbviewCountsWT;
            $total_video_count = $total_video_count * $fbvideoCountsWT ;
            $total_subscriber_count = $total_subscriber_count * $fbsubscriberCountsWT ;
            //$fbPageLikesCountPoint = $fbPageLikesCount * $fbPageLikesCountWT ;
            // $fbPostCommentsPoint = $fbPostComments * $fbPostCommentsWT ;
            // $fbPostLikesPoint = $fbPostLikes * $fbPostLikesWT ;
            // $fbPostCountPoint = $fbPostCount * $fbPostCountWT ;checkSocialPoint

            $youtubeTotalPoint = $youtubeTotalfollowerPoint + $total_view_count + $total_video_count+ $total_subscriber_count;
        
            

            $checkSocialPoint = $this->checkSocialPoint($userId);
            
       
            $updateData=array(
                'youtube_follower_count'=>$youtubeTotalfollowerPoint ,
                'youtube_view_count'=>$total_view_count ,
                 'youtube_video_count'=>$total_video_count ,
                 'youtube_subscriber_count'=>$total_subscriber_count ,
                // 'fb_post_likes'=>$fbPostLikesPoint ,
                // 'fb_post_count'=>$fbPostCountPoint 
            );
          
            $updateData['user_id']=$userId ;
            $avgPoint = $youtubeTotalPoint/100 ;
           
            $updateData['total_point']=$youtubeTotalPoint ;
            $updateData['avg_point']=$avgPoint;
            if($checkSocialPoint==0){
                $updateData['user_id']=$userId ;
                $avgPoint = $youtubeTotalPoint/100 ;
                $updateData['total_point']=$youtubeTotalPoint ;
                $updateData['avg_point']=$avgPoint ;
                DB::table('user_social_point')->insert($updateData);
                $this->updateUserPoint($userId,$avgPoint);
            }else{        
                $currentTotalPoint =  $checkSocialPoint + $youtubeTotalPoint ;
                $avgPoint =  $currentTotalPoint / 100 ;   
                $updateData['total_point']=$currentTotalPoint ;
                $updateData['avg_point']=$avgPoint ;              
                DB::table('user_social_point')->where('user_id',$userId)->update($updateData);
                $this->updateTotalPoint($userId);
                 
            }

        }    
        else{
            return 0;
        }    
    }

    public function instaPointActivity($userId){
       
        $sPWT = $this->getSocialMediaPoint();
        
        $checkSocialUser = DB::table('insta_user_info')->where('userId',$userId)->first();
       
       
        $instaTotalPoint=0 ;

        if(!empty($checkSocialUser)){   
            // insta activity
            $instaFollowers = $checkSocialUser->followers_count ;
            $instaFollows = $checkSocialUser->follows_count ;
            $instaToalPostCount = $checkSocialUser->media_count ;
            // $instaTotalPostComment = $checkSocialUser->total_post_comment_count ;
            // $instaTotalPostLikes = $checkSocialUser->total_post_likes_count ;
          

            //insta weightage
            $instaFollowersWT=isset($sPWT['insta_followers_count'])?$sPWT['insta_followers_count']:0 ;
            $instaFollowsWT=isset($sPWT['insta_follows_count'])?$sPWT['insta_follows_count']:0 ;
            $instaToalPostCountWT=isset($sPWT['insta_total_post_counts'])?$sPWT['insta_total_post_counts']:0 ;
            // $instaTotalPostCommentWT=isset($sPWT['insta_total_post_comment_counts'])?$sPWT['insta_total_post_comment_counts']:0 ;
            // $instaTotalPostLikesWT=isset($sPWT['insta_total_post_likes_count'])?$sPWT['insta_total_post_likes_count']:0 ;                     

            // insta point calculation
            $instaFollowersPoint = $instaFollowers * $instaFollowersWT ;
            $instaFollowsPoint = $instaFollows * $instaFollowsWT ;
            // $instaToalPostCountPoint = $instaToalPostCount * $instaToalPostCountWT ;
            // $instaTotalPostCommentPoint = $instaTotalPostComment * $instaTotalPostCommentWT ;
            // $instaTotalPostLikesPoint = $instaTotalPostLikes * $instaTotalPostLikesWT ;
          

           
            $instaTotalPoint = $instaFollowersPoint + $instaFollowsPoint; 
            // $instaToalPostCountPoint
            //  + $instaTotalPostCommentPoint + $instaTotalPostLikesPoint  ;
            
//dd($instaTotalPoint);
            $checkSocialPoint = $this->checkSocialPoint($userId);
            
            $updateData=array(
                'insta_followers_count'=>$instaFollowersPoint ,
                'insta_follows_count'=>$instaFollowsPoint ,
                // 'insta_total_post_count'=>$instaToalPostCountPoint ,
                // 'insta_post_comment_count'=>$instaTotalPostCommentPoint ,
                // 'insta_post_likes_count'=>$instaTotalPostLikesPoint 
            );
           
            if($checkSocialPoint==0){
                $updateData['user_id']=$userId ;
                $avgPoint = $instaTotalPoint/100 ;
                $updateData['total_point']=$instaTotalPoint ;
                $updateData['avg_point']=$avgPoint ;
                DB::table('user_social_point')->insert($updateData);
                $this->updateUserPoint($userId,$avgPoint);
            }else{   
                $currentTotalPoint =  $checkSocialPoint + $instaTotalPoint ;
                $avgPoint =  $currentTotalPoint / 100 ;   
                $updateData['total_point']=$currentTotalPoint ;
                $updateData['avg_point']=$avgPoint ;            
                DB::table('user_social_point')->where('user_id',$userId)->update($updateData);
                $this->updateTotalPoint($userId);
                 
            }

        }        
    }
  
    public function log($data){
    	DB::table('image_log')->insert(array('data'=>json_encode($data)));
    }

    public function tiktokPointActivity($userId){
        $sPWT = $this->getSocialMediaPoint();
        $checkSocialUser = DB::table('tiktok_user_info')->where('userId',$userId)->first();
        $tiktokTotalPoint=0 ;
       
        if(!empty($checkSocialUser)){   
            // tiktok activity
            $followersCount = $checkSocialUser->followers_count ;
            $followsCount = $checkSocialUser->follows_count ;
            $likesCount = $checkSocialUser->likes_count ;
            $videoLikeCount = $checkSocialUser->video_likes_count ;
            $videoShareCount = $checkSocialUser->video_shares_count ;
            $videoCommentCount = $checkSocialUser->video_comment_count ;
            $videoViewCount = $checkSocialUser->video_view_count ;

            //titok weightage
            $tiktokFollowerWT=isset($sPWT['tiktok_followers_count'])?$sPWT['tiktok_followers_count']:0 ;
            $tiktokFollowsWT=isset($sPWT['tiktok_follows_count'])?$sPWT['tiktok_follows_count']:0 ;
            $tiktokLikesWT=isset($sPWT['tiktok_likes_count'])?$sPWT['tiktok_likes_count']:0 ;
            $tiktokVideoLikeWT=isset($sPWT['tiktok_video_likes_count'])?$sPWT['tiktok_video_likes_count']:0 ;
            $tiktokVideoCommentWT=isset($sPWT['tiktok_video_comments_count'])?$sPWT['tiktok_video_comments_count']:0 ;
            $tiktokVideoShareWT=isset($sPWT['tiktok_video_shares_count'])?$sPWT['tiktok_video_shares_count']:0 ;
            $tiktokVedioViewWT=isset($sPWT['tiktok_video_views_count'])?$sPWT['tiktok_video_views_count']:0 ;

            // tiktok point calculation
            $followerPoint = $followersCount * $tiktokFollowerWT ;
            $followsPoint = $followsCount * $tiktokFollowsWT ;
            $likesPoint = $likesCount * $tiktokLikesWT ;
            $videoLikePoint = $videoLikeCount * $tiktokVideoLikeWT  ;
            $videoSharePoint = $videoShareCount * $tiktokVideoShareWT ;
            $videoCommentPoint = $videoCommentCount * $tiktokVideoCommentWT;
            $videoViewPoint = $videoViewCount *  $tiktokVedioViewWT;

            $tiktokTotalPoint = $followerPoint + $followsPoint + $likesPoint + $videoLikePoint + $videoSharePoint + $videoCommentPoint + $videoViewPoint ;
            
     
            $checkSocialPoint = $this->checkSocialPoint($userId);
            $updateData=array(
                'tiktok_followers_count'=>$followerPoint ,
                'tiktok_follows_count'=>$followsPoint ,
                'tiktok_likes_count'=>$likesPoint ,
                'tiktok_video_likes_count'=>$videoLikePoint ,
                'tiktok_video_shares_count'=>$videoSharePoint ,
                'tiktok_video_comments_count'=>$videoCommentPoint ,
                'tiktok_video_view_count'=>$videoViewPoint 
            );
            
            if($checkSocialPoint==0){
                $updateData['user_id']=$userId ;
                $avgPoint = $tiktokTotalPoint/100 ;
                $updateData['total_point']=$tiktokTotalPoint ;
                $updateData['avg_point']=$avgPoint ;
                DB::table('user_social_point')->insert($updateData);
                $this->updateUserPoint($userId,$avgPoint);
            }else{ 
                $currentTotalPoint =  $checkSocialPoint + $tiktokTotalPoint ;
                $avgPoint =  $currentTotalPoint / 100 ;   
                $updateData['total_point']=$currentTotalPoint ;
                $updateData['avg_point']=$avgPoint ;   
                DB::table('user_social_point')->where('user_id',$userId)->update($updateData);
                $this->updateTotalPoint($userId);
                 
            }

        }     
    }
    public function updateUserRank($userId){
        $SocialInfo=Social_info::where('user_id',$userId)->get();
        $totalfollowers=0;
      

        
        if(!empty( $SocialInfo))
        {
            foreach($SocialInfo as $val)
            {
                $totalfollowers= $totalfollowers+$val->followers_count;
            }
            
        }
        $rankType=0;
        // try{
        if($totalfollowers > 800 && $totalfollowers < 1000){
            $rankType=1 ;
          }else if($totalfollowers > 600 && $totalfollowers < 800){
            $rankType=2 ;
          }else if($totalfollowers > 400 && $totalfollowers < 600){
            $rankType=3 ;
          }else if($totalfollowers > 200 && $totalfollowers < 400){
            $rankType=4 ;
          }else if($totalfollowers > 1 && $totalfollowers < 200){
            $rankType=5 ;
          }else{
            $rankType=0 ;
          }

          $save_user=User::updateorCreate(
            [
              'id'=>$userId,
            ],
            [
              
              'rank_type'=>4,
              
            
              ]
          );
          if( $save_user)
          {
            $message=__('Rank type update in User  table') ;
            return $this->successResponse([],$message,200);   
          }
      //  }
    // }
    // catch(\Exception $e) {
    //      return $this->errorResponse(__('Rank type update in User_err'), 200);
    //   }    	
         
        }

    public function updateTotalPoint($userId){
        $checkSocialPoint = DB::table('user_social_point')->where('user_id',$userId)->first();
       
        
        $fb_friends_count=$checkSocialPoint->fb_friends_count ;
        $fb_page_followers_count=$checkSocialPoint->fb_page_followers_count ;
        $fb_page_likes_count=$checkSocialPoint->fb_page_likes_count ;
        $fb_post_comment=$checkSocialPoint->fb_post_comment ;
        $fb_post_likes=$checkSocialPoint->fb_post_likes ;
        $fb_post_count=$checkSocialPoint->fb_post_count ;

        $insta_followers_count=$checkSocialPoint->insta_followers_count ;
        $insta_follows_count=$checkSocialPoint->insta_follows_count ;
        $insta_total_post_count=$checkSocialPoint->insta_total_post_count ;
        $insta_post_comment_count=$checkSocialPoint->insta_post_comment_count ;
        $insta_post_likes_count=$checkSocialPoint->insta_post_likes_count ;

        $tiktok_followers_count=$checkSocialPoint->tiktok_followers_count ;
        $tiktok_follows_count=$checkSocialPoint->tiktok_follows_count ;
        $tiktok_likes_count=$checkSocialPoint->tiktok_likes_count ;
        $tiktok_video_likes_count=$checkSocialPoint->tiktok_video_likes_count ;
        $tiktok_video_shares_count=$checkSocialPoint->tiktok_video_shares_count ;
        $tiktok_video_comments_count=$checkSocialPoint->tiktok_video_comments_count ;
        $tiktok_video_view_count=$checkSocialPoint->tiktok_video_view_count ;

        
        $youtube_followers_count=$checkSocialPoint->youtube_follower_count ;
        $youtube_view_count=$checkSocialPoint->youtube_follower_count ;
        $youtube_video_count=$checkSocialPoint->youtube_video_count ;
        $youtube_suscribe_count=$checkSocialPoint->youtube_subscriber_count ;



        $fbTotalPoint = ($fb_friends_count+$fb_page_followers_count+$fb_page_likes_count+$fb_post_comment+$fb_post_likes+$fb_post_count);	
        $instaTotalPoint = ($insta_followers_count+$insta_follows_count+$insta_total_post_count+$insta_post_comment_count+$insta_post_likes_count)	;
        $tiktokTotalPoint = ($tiktok_followers_count+$tiktok_follows_count+$tiktok_likes_count+$tiktok_video_likes_count+$tiktok_video_shares_count+$tiktok_video_comments_count+$tiktok_video_view_count);
        $youtubeTotalPoint = ($youtube_followers_count+ $youtube_view_count+ $youtube_video_count+$youtube_suscribe_count);
        
        $totalPoint = $fbTotalPoint+$instaTotalPoint+$tiktokTotalPoint+$youtubeTotalPoint;
        $avgPoint = ($totalPoint / 100) ;

        DB::table('user_social_point')->where('user_id',$userId)->update(["total_point"=>$totalPoint ,"avg_point"=>$avgPoint ]);
        if($this->updateUserPoint($userId,$avgPoint))
        {
            return 1;
        }
        else {
            return 0;
        }
    }



    public function getSocialMediaPoint(){
       
        $socialPoint = DB::table('social_media_weightage')->where('status',1)->get();  
          
        $socialWT = [] ;
        foreach($socialPoint as $key=>$val){
            $socialWT[$val->slug]=$val->weightage;
        }
        return $socialWT ;
    }

    public function checkSocialPoint($userId){
      
        $checkSocialPoint = DB::table('user_social_point')->where('user_id',$userId)->first();
        if(!empty($checkSocialPoint)){
            return $checkSocialPoint->total_point ;
        }else{
            return 0 ;
        }

    }

    public function updateUserPoint($userId,$point){
        $rankType = $this->getRankType($point);
      
        // if($point > 0){

        	$insertData=array(
        		"userId"=>$userId ,				
				"point"=>$point 
        	);
        	DB::table('cron_log')->insert($insertData);

            DB::table('users')->where('id',$userId)->update(['rank_'=>$point,'rank_type'=>$rankType]);
        //}       
        
    }

    public function getRankType($point){
        $rankType=DB::table('rank_types')->where('status',1)->get();
       
        if(!empty($rankType)){
           foreach($rankType as $key=>$val){
                if($point >=$val->range_from && $point <=$val->range_to){
                    
                    return $val->id ;
                }

           }  
        }
    }

     public function getSocialDataByCron(Request $request){
            $client_key='aw2j809lnifzn891';//"awdax30rtuj5vauj";
            $tiktokUsr=DB::table('tiktok_login_info')->where('status',1)->get()->toArray();
            if(!empty($tiktokUsr)){
                foreach ($tiktokUsr as $value) {
                    $userId = isset($value->userId)?$value->userId:0 ;
                    $refresh_token=isset($value->refresh_token)?$value->refresh_token:'' ;
                    $tiktokUsrId = $value->id ;
                    if($refresh_token!=''){
                        $data=$this->refreshToken($client_key,$refresh_token);
                        $data_ = json_decode($data);
                        $access_token = isset($data_->data->access_token)?$data_->data->access_token:'' ;
                        $refreshToken= isset($data_->data->refresh_token)?$data_->data->refresh_token:'' ; 
                       
                        $ldate = date('Y-m-d H:i:s');
                        if($refreshToken!='' && $access_token!=''){
                        DB::table('tiktok_login_info')->where('id',$tiktokUsrId)->update(['access_token'=>$access_token,'refresh_token'=>$refreshToken,'updatedOn'=>$ldate]);
                       $this->update_tiktok_point_byCron($access_token,$userId);
                        DB::table('tiktok_login_info')->where('id',$tiktokUsrId)->update(['lastUpdatedOn'=>$ldate]);
                    }

                }
                    

                }
            }            
      
    }

    public function refreshToken($client_key,$refresh_token){


        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://open-api.tiktok.com/oauth/refresh_token/',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => array('client_key' => $client_key,'grant_type' => 'refresh_token','refresh_token' => $refresh_token),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;

    }

    public function update_tiktok_point_byCron($access_token,$userId){    
   
    $userData=$this->getTiktokData(0,$access_token);
    $usrData=isset($userData->data->user)?$userData->data->user:''  ;
      
    $followingCount=isset($usrData->following_count)?$usrData->following_count:0 ;
    $followerCount=isset($usrData->follower_count)?$usrData->follower_count:0 ;
    $likeCount=isset($usrData->likes_count)?$usrData->likes_count:0 ;
    $unionId=isset($usrData->union_id)?$usrData->union_id:0 ;
    
    $query_2 = "select id from tiktok_user_info where userId=".$userId ;
    $query_3 = "update tiktok_user_info set `follows_count` = ".$followingCount.", `followers_count` = ".$followerCount.", `likes_count` = ".$likeCount." where userId=".$userId ;
    $query_4 = "insert into tiktok_user_info (union_id,`userId`,`followers_count`,`follows_count`,`likes_count`)VALUES('".$unionId."',".$userId.",".$followerCount.",".$followingCount.",".$likeCount.")" ;

      $videoData=$this->getTiktokData(1,$access_token);
      $videoData_ = isset($videoData->data)?$videoData->data:array();    
      
      $this->tiktokVideoListData($videoData_,$access_token,$userId); 
      $checkTikTokUsr = DB::select($query_2);       
       
      
      if(!empty($checkTikTokUsr)){
        $updateinfo=DB::select($query_3);  
        $this->updateUserInstaRankPoints($userId,1) ;
        return $this->successResponse([],"Successful updated tiktok data",200); 
        
      }else{
        $updateinfo=DB::select($query_4);  
        $this->updateUserInstaRankPoints($userId,1) ;
        return $this->successResponse([],"Successful add tiktok data",200); 
      }   
      /*End*/
   }


     public function tiktok_authCallback(Request $request){
    

    $access_token = $request->access_token ;   
    $refresh_token = $request->refresh_token ;   
   

    $userId = authguard()->id ;
    $this->tiktokTokenLog($userId,$access_token,$refresh_token);
   
    $userData=$this->getTiktokData(0,$access_token);
    $usrData=isset($userData->data->user)?$userData->data->user:''  ;
      
    $followingCount=isset($usrData->following_count)?$usrData->following_count:0 ;
    $followerCount=isset($usrData->follower_count)?$usrData->follower_count:0 ;
    $likeCount=isset($usrData->likes_count)?$usrData->likes_count:0 ;
    $unionId=isset($usrData->union_id)?$usrData->union_id:0 ;
    
    $query_2 = "select id from tiktok_user_info where userId=".$userId ;
    $query_3 = "update tiktok_user_info set `follows_count` = ".$followingCount.", `followers_count` = ".$followerCount.", `likes_count` = ".$likeCount." where userId=".$userId ;
    $query_4 = "insert into tiktok_user_info (union_id,`userId`,`followers_count`,`follows_count`,`likes_count`)VALUES('".$unionId."',".$userId.",".$followerCount.",".$followingCount.",".$likeCount.")" ;

      $videoData=$this->getTiktokData(1,$access_token);
      $videoData_ = isset($videoData->data)?$videoData->data:array();    
      
      $this->tiktokVideoListData($videoData_,$access_token,$userId); 
      $checkTikTokUsr = DB::select($query_2);       
       
      
      if(!empty($checkTikTokUsr)){
        $updateinfo=DB::select($query_3);  
        $this->updateUserInstaRankPoints($userId,1) ;
        return $this->successResponse([],"Successful updated tiktok data",200); 
        
      }else{
        $updateinfo=DB::select($query_4);  
        $this->updateUserInstaRankPoints($userId,1) ;
        return $this->successResponse([],"Successful add tiktok data",200); 
      }   
      /*End*/
   }

   public function updateUserInstaRankPoints($userId,$type){
        
            $curl = curl_init();

            curl_setopt_array($curl, array(
              CURLOPT_URL => env('SOCIAL_POINT_UPDATE_API'),
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS => array('userId' =>$userId,'type' =>$type),
            ));

            $response = curl_exec($curl);
             
            curl_close($curl);
            //echo $response;
     }

   public function tiktokVideoListData($req,$access_token,$userId,$commentCount=0,$likeCount=0,$share_count=0,$view_count=0){
       $cursor = isset($req->cursor)?$req->cursor:0;
       $has_more = isset($req->has_more)?$req->has_more:0; 
       $data = isset($req->videos)?$req->videos:array() ; 

  
    if(!empty($data) && count($data) > 0){
      foreach ($data as $key => $value) {
     
        $commentCount = $commentCount + $value->comment_count ;
        $likeCount = $likeCount + $value->like_count ;
        $share_count = $share_count + $value->share_count ;

        if(isset($value->view_count)){
          $view_count=$view_count + $value->view_count ;
        }

      }     

    }
    
   if($has_more==1){    
  
    $videoData = $this->getTiktokData(1,$access_token,$cursor);
    
    $this->tiktokVideoListData($videoData,$access_token,$userId,$commentCount,$likeCount,$share_count,$view_count);    
  
   }else{
     $updateQry = "update tiktok_user_info set video_likes_count=".$likeCount." ,video_shares_count=".$share_count.",video_comment_count=".$commentCount.",video_view_count=".$view_count." where userId=".$userId ; 
     DB::statement($updateQry);    
     return true ;  
   }
   }

   public function getTiktokData($type=0,$access_token,$cursor=0){

     if($type==1){
      $method="POST" ;
      $url="https://open.tiktokapis.com/v2/video/list/?fields=like_count,comment_count,share_count,view_count" ;
     }else{
      $method="GET" ;
      $url="https://open.tiktokapis.com/v2/user/info/?fields=open_id,union_id,avatar_url,follower_count,following_count,likes_count,bio_description,display_name" ;
     }

     if($cursor > 0){
      $postData='{
        "cursor":"'.$cursor.'",
        "max_count":20
      }' ;
     }else{
      $postData='{}' ;
     }


    $curl = curl_init();

      curl_setopt_array($curl, array(
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => $method,
      CURLOPT_POSTFIELDS =>$postData,
      CURLOPT_HTTPHEADER => array(
      'Authorization: Bearer '.$access_token
      ),
      ));

      $response = curl_exec($curl);

      curl_close($curl);
      return  json_decode($response);
   }

   public function tiktokTokenLog($userId=0,$access_token,$refresh_token){

    $checkQry="select id from tiktok_login_info where userId=".$userId." limit 1";
 
    $checkQryResponse=DB::select($checkQry);
       
      
      if(empty($checkQryResponse)){        
          $qry = "INSERT INTO `tiktok_login_info` (`userId`, `access_token`, `refresh_token`)VALUES (".$userId.", '".$access_token."', '".$refresh_token."') " ;
          DB::select($qry);           
      }else{        
        $updatedId = isset($checkQryResponse->id)?$checkQryResponse->id:0 ;        
        $qry = "update `tiktok_login_info` set  `access_token`='".$access_token."', `refresh_token`='".$refresh_token."' where id=".$updatedId ;
         DB::select($qry);
      }
   }

   public function exampleFunction(){
    return "Hello Data";
   }

}