<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
//use Socialite;
use Laravel\Socialite\Facades\Socialite;
use Auth;
use Exception;
use App\Models\User;
use App\Models\Countries;
use GuzzleHttp\Client;
use Instagram\FacebookLogin\FacebookLogin;
use App\Http\Controllers\HomeControler;
use Instagram\AccessToken\AccessToken;
 //use Instagram\User\User;
 //use Illuminate\Support\Facades\Http;
 use Illuminate\Support\Facades\Storage;


use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Facebook\Facebook;
use Hash;
use DB ;
use Image ;
use File ;
use Mail ;
use VideoThumbnail ;
use Laravel\Passport\Token;
use Lcobucci\JWT\Configuration;
use Illuminate\Support\Facades\Redis;
use lang ;
//use Laravel\Socialite\Facades\Socialite;
//use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Http;

class FacebookSocialiteController extends Controller
{
    public function index(){
      $data['country']=Countries::get();

        return view('user/signup',$data);
    }

    public function loginwithgoogle(Request $request)
    {
      return Socialite::driver('google')->redirect();
    }    
    public function callbackfromGoogle(Request $request)
    {
      $user = Socialite::driver('google')->user();
     
      $its_user=User::where('email',$user->getEmail())->first();
     
      if(!$its_user)
      {
        $save_user=User::updateorCreate(
          [
            'google_id'=>$user->getId(),
          ],
          [
            'google_id'=>$user->getId(),
            'name'=>$user->getName(),
            'email'=>$user->getEmail(),
            'password'=>Hash::make($user->getName().'@'.$user->getId()),
            'user_type'=>5
          ]
        );

      }
      else{
        $save_user=  User::where('google_id',$user->getId())->first();
      }
      Auth::login($save_user);
      return redirect()->intended('/user/dashboard/');
    
    }  
    
public function inslogin(Request $request)
{
    $items = [];
 

dd($request);

    	if($request->has('username')){



	 $client = new \GuzzleHttp\Client;

	 $url = sprintf('https://www.instagram.com/%s/media', $request->input('username'));

         $response = $client->get($url);

         $items = json_decode((string) $response->getBody(), true)['items'];



    	}
    	
    dd( $items);
}

public function ins_userid(Request $request)
{
  // Generated @ codebeautify.org
  $access_token="IGQWRQaV9uWU5TM2o1SHRHY0xuQVRFNnk0ZA1RwWjFiMkZAIdnFCR09RV0QwUlNKQUVCRkRUVTVqck5yck5fdE1oeTh0c1o4RnZApdFBTOUI5NENraWdkX3g4ZA3VqVVNBTVJSeFFnUHoxR2JEZAWdIZAWNORW5nQ252ZA1EZD";
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "https://graph.instagram.com/me?access_token=$access_token");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

$result = curl_exec($ch);
dd($result);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close($ch);

  
}

     public function redirectToFB()
    {   
       //return Socialite::driver('facebook')->redirect();
       return Socialite::driver('facebook')->scopes(['email', 'public_profile'])->redirect();
    }
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
        
    }
     
    public function redirectToInsta()
    {
      return Socialite::driver('instagram')->scopes(['public_profile','instagram_basic','instagram_manage_insights'])->redirect();

     // return Socialite::driver('facebook')->scopes(['email', 'public_profile'])->redirect();


    //  $appId = '1245733779818862';
    //   $redirectUri = urlencode(route('instagram.callback'));

    //   return redirect("https://www.facebook.com/v18.0/dialog/oauth?client_id={$appId}&redirect_uri={$redirectUri}&scope=public_profile,instagram_basic,instagram_manage_insights&response_type=code");

      

      // $appId = '400361359804168';
      // $redirectUri = urlencode(route('instagram.callback'));
  
      // return redirect("https://api.instagram.com/oauth/authorize?client_id={$appId}&redirect_uri={$redirectUri}&response_type=code");



      // $client = new \GuzzleHttp\Client;
      // $user_name="webwalkof";

      // $url = sprintf('https://www.instagram.com/%s/media', $user_name);
   
      //       $response = $client->get($url);
      //       dd($response);
   
      //      $items = json_decode((string) $response->getBody(), true)['items'];
          
//       $client = new Client();
// $crawler = $client->request('GET', 'https://www.instagram.com/webwalkof/');
// dd($crawler);

// $followers = $crawler->filter('meta[name="description"]')->attr('content');
// dd($followers);
// preg_match('/(\d+(?:,\d+)*) Followers/', $followers, $matches);


//       $response = Http::get('https://api.example.com/public/followers', [
//         'username' => 'target_user',
//     ]);
//       die("hello");
//       return Socialite::driver('instagram')->redirect();
    
     //return Socialite::driver('instagram')->scopes(['email', 'public_profile'])->redirect();

   
      

    }
    
     public function handleInstaCallback(Request $request)
    {
     die("jhgjhg");
      $code = $request->code ;
      $client_id="1111302550437078";
   
      $redirect_uri="https://walkofweb.in/ins/callback" ;
      $client_secret="b8c0cb3d4a55c70840ff12fedad7583a" ;
     
     $ch=curl_init();
         curl_setopt($ch,CURLOPT_URL,env('INSTA_ACCESS_TOKEN_URI'));
         curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE);
         curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
         curl_setopt($ch,CURLOPT_POSTFIELDS,array(
         'code'=>$code,
         'client_id'=>$client_id,
         'client_secret'=>$client_secret,
         'redirect_uri'=>$redirect_uri,
         'grant_type'=>'authorization_code'
         ));

       $data = curl_exec($ch);         
       $accessToken = json_decode($data)->access_token;
       $userId = json_decode($data)->user_id;          
      $chs = curl_init();
        curl_setopt($chs,CURLOPT_URL,"https://graph.instagram.com/v15.0/{$userId}?fields=account_type,id,username,media{caption,id,comments_count,like_count}&access_token={$accessToken}");   
              
      curl_setopt($chs,CURLOPT_RETURNTRANSFER,TRUE);
      curl_setopt($chs,CURLOPT_SSL_VERIFYPEER,false);

      $response = curl_exec($chs);

      $oAuth = json_decode($response);
            echo "<pre>";
             print_r($oAuth); exit ;
     

     }

    
    
    public function handleFacebookCallback(Request $request) 
    {
     
      $code = $request->code;
      dd($code);
      if (empty($code)) return redirect()->route('home')->with('error', 'Failed to login with Instagram.');
  
      $appId = "400361359804168";
      $secret = "3009832eb58d1311e8792e5345bc90de";
      $redirectUri = "https://walkofweb.in/ins/callback";
  
      $client = new Client();
  
      // Get access token
      $response = $client->request('POST', 'https://api.instagram.com/oauth/access_token', [
          'form_params' => [
              'app_id' => $appId,
              'app_secret' => $secret,
              'grant_type' => 'authorization_code',
              'redirect_uri' => $redirectUri,
              'code' => $code,
          ]
      ]);
  
      if ($response->getStatusCode() != 200) {
          return redirect()->route('home')->with('error', 'Unauthorized login to Instagram.');
      }
  
      $content = $response->getBody()->getContents();
      $content = json_decode($content);
     // dd($content);
  
      $accessToken = $content->access_token;
      $userId = $content->user_id;
  
      // Get user info
      $response = $client->request('GET', "https://graph.instagram.com/me?fields=id,username,account_type&access_token={$accessToken}");
  
      $content = $response->getBody()->getContents();
      $oAuth = json_decode($content);
  
      // Get instagram user name 
      $username = $oAuth->username;
  

        
         
    }
    public function fbuserdetails($data)
    {
      return view('userprofile',["fbuser_details"=>$data]);
      
     
    }


    
    public function handleCallback()
    {
      
          
                $user_details='';
                $user=Socialite::driver('facebook')->scopes(['email', 'public_profile'])->user();
                $profilePictureUrl = $user->avatar;
                $profilePicturePath = 'profile_image/' . $user->id . '.jpg';
                $imageContents = Http::get($profilePictureUrl)->body();
                $image_check=Storage::disk('public')->put($profilePicturePath, $imageContents);
                  if($image_check==true)
                  {
                    $iamge_name=$user->id . '.jpg';
                  }
                  else
                  {
                    $iamge_name='';
                  }

               
                

                  try {
                        if($user)
                        {
                          $login_userdetails=Auth::user($user);
                              if(!empty($login_userdetails)){
                                
                                  $user_details=self::updatefbuser_details($user,$iamge_name);
                                }
                                else{
                                  $user_details=self::savefbuser_details($user,$iamge_name);
                                }
                       
                              if($user_details)
                              {
                                return redirect()->intended('/user/dashboard/');
                              }
               
                     
                          else{
                            return redirect()->intended('user/login')->with('error', 'Failed to login with Facebook.');
                          }
             
                        } 
                      }
                    catch (Exception $e) {
                    dd($e->getMessage());
                    }
     }

    public static  function  updatefbuser_details($data,$image)
    {

              $filePath = config('constants.user_image') ;
              $dummy_image=config('constants.dummy_image') ;
              $access_token=$data->token;

                $ch = curl_init();
        
                curl_setopt($ch, CURLOPT_URL, "https://graph.facebook.com/v21.0/me?fields=email%2Cname%2Cfirst_name%2Cname_format%2Clast_name%2Cshort_name%2Cpicture%7Bheight%2Ccache_key%2Cis_silhouette%2Curl%2Cwidth%7D&access_token=$access_token");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        
                $result = curl_exec($ch);
        
                $data=json_decode($result);
                
                $user_id=Auth::user()->id;
                $newUser = User::find($user_id);
                $newUser->name = $data->name;
                $newUser->registration_from = 3;
                $newUser->image = $image;
                $newUser->short_name = $data->short_name;
                $newUser->name_format = $data->name_format;
                $newUser->facebook_username = $data->name;
                $newUser->social_id = $data->id;
                $newUser->user_type =2;
                $newUser->first_name =$data->first_name;
                $newUser->last_name = $data->last_name;
                
                $newUser->update();

              
               Auth::login($newUser);
             
               $data1['user_details']=[];
               $data1['user_details']= $newUser ;
              
              
               
                $userData=array(
                'id'=>$data1['user_details']['id']?$data1['user_details']['id']:'-',
               
                 'first_name' => $data1['user_details']['first_name']?$data1['user_details']['first_name']:'-',
                 'user_type' => $data1['user_details']['user_type']?$data1['user_details']['user_type']:'-',
                'last_name' => $data1['user_details']['last_name']?$data1['user_details']['last_name']:'-',
                'name_format' => $data1['user_details']['name_format']?$data1['user_details']['name_format']:'-',
                'email'=>$data1['user_details']['email']?$data1['user_details']['email']:'-',
                'image' => $data1['user_details']['image']?$filePath.$data1['user_details']['image']:$dummy_image,
                'name' => $data1['user_details']['name']?$data1['user_details']['name']:'-',
                'username' => $data1['user_details']['facebook_username']? $data1['user_details']['facebook_username']:'-'
                

                );
               
               
    return $userData;
              

    }

    public static  function  savefbuser_details($data,$image_name)
    {
      $filePath = config('constants.user_image') ;
      $dummy_image=config('constants.dummy_image') ;
     
      if($data)
        {
        
         
            if(!empty($data->error))
            {
            
              
                return response()->json(['message'=>$data->error->message,'status_code'=>400]);
            }
            else{
               
                $access_token=$data->token;
           
                $ch = curl_init();
        
                curl_setopt($ch, CURLOPT_URL, "https://graph.facebook.com/v21.0/me?fields=email%2Cname%2Cfirst_name%2Cname_format%2Clast_name%2Cshort_name%2Cpicture%7Bheight%2Ccache_key%2Cis_silhouette%2Curl%2Cwidth%7D&access_token=$access_token");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        
                $result = curl_exec($ch);
        
                $data=json_decode($result);
             
                
           
    
  
                 $name=$data->name ;
                   $usrName = substr($name,0,2);
                   
                    $password_ = 'my-facebook';
                    $email=$data->email ; 
                    $request=[];
                    $name=$data->name ;
                   $usrName = substr($name,0,2);
                   $password_ = 'my-facebook';
                   $email=$data->email ; 
                   $request['email'] =  $email;
                   $request['username'] = $usrName;
                   $request['user_type'] = 2;
                   $request['image'] = $image_name;
                   $request['short_name'] = $data->short_name;
                   $request['name_format'] = $data->name_format;
                   $request['facebook_username'] = $data->name;
                  $request['social_id'] = $data->id ;
                  $request['first_name'] =$data->first_name; // rand(1,5);
                  $request['last_name'] =$data->last_name;// rand(1,30);   
                 $request['name'] = $data->name;
                  $request['registration_from'] = 1 ;
                  $request['rank_type'] =0 ; // rand(1,5);
                  $request['rank_'] =0 ;// rand(1,30);     
                  $request['username'] ='' ; //$userName;
                  $request['password']=Hash::make('my-facebook');
                  $request['remember_token'] = Str::random(10);
                  
                  $user=User::where('email', $email)->first();
               
                
                  if(empty($user))
                  {
                    $result=  DB::table('users')->insert( $request);
                    $user=User::where('email',  $email)->first();
                  }
                 
                  else{
                    $user=User::where('email', $email)->first();
                  
                    $user->update($request);
                  }



                 
                  $user=User::where('email', $email)->first();
                  Auth::login($user);
                
                   $username='';
                   $token =$user->createToken('walkofweb token')->accessToken;
                  
                   $message =  __('messages.user_registration') ; //"Account has been created successfully." ;
                   $insertId=$user->id;
                   $encryptionKey = md5('wow_intigate_23'.$insertId);
                   
                
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
                   $imagick=config('constants.imagick');
                
                  //  if($imagick==1){
                  //     $this->qrCode($encryptionKey);
                  //     $this->sendRegistrationEmail($name,$email,$password_);
                  //  }
                  //  die;
                  $deviceType= $request['registration_from'];
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
                   'image' => $data1['user_details']['image']? $filePath.$data1['user_details']['image']: $dummy_image,
                   'name' => $data1['user_details']['name']?$data1['user_details']['name']:'-',
                   'username' => $data1['user_details']['facebook_username']? $data1['user_details']['facebook_username']:'-'
                   

                   );
                  
              return $userData;
                 
                  // return response()->json(['message'=>'fb Login Registration Successfully','data'=>['User_details' =>$userData],'status_code'=>200]);

                   
                
            }
            
        }
        else{
            return redirect('/slogin')->with('error', 'Something went wrong: ' . $e->getMessage());
           // return response()->json(['error'=>'session expired'], 404);
        }
        
        
          
    }
//start instagram

public function redirectToInstagramProvider(){
  // $appId=env('INSTA_APP_ID');     
  // $redirectUri=urlencode(env('INSTA_REDIRECT_URI'));    
$appId="400361359804168";     

  $redirectUri="https://walkofweb.in/ins/callback";  


  //instagram_basic user_media,instagram_content_publish,instagram_manage_insights,instagram_manage_comments,pages_show_list,ads_management,business_management,pages_read_engagement
//app_id={$appId}&
  // echo "https://api.instagram.com/oauth/authorize?redirect_uri={$redirectUri}&scope=user_profile,user_media&response_type=code" ;
  // exit ;
   return redirect()->to("https://api.instagram.com/oauth/authorize?redirect_uri={$redirectUri}&scope=user_profile,user_media&response_type=code");

  }




  //end

    

  

    public function instagramProviderCallback(Request $request){
die("hello");

      $code = $request->code ;
      $client_id=env('INSTA_APP_ID');
   
      $redirect_uri=env('INSTA_REDIRECT_URI') ;
      $client_secret=env('INSTA_CLIENT_SECRET') ;
     
     $ch=curl_init();
         curl_setopt($ch,CURLOPT_URL,env('INSTA_ACCESS_TOKEN_URI'));
         curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE);
         curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
         curl_setopt($ch,CURLOPT_POSTFIELDS,array(
         'code'=>$code,
         'client_id'=>$client_id,
         'client_secret'=>$client_secret,
         'redirect_uri'=>$redirect_uri,
         'grant_type'=>'authorization_code'
         ));

       $data = curl_exec($ch);         
       $accessToken = json_decode($data)->access_token;
       $userId = json_decode($data)->user_id;          
      $chs = curl_init();
        curl_setopt($chs,CURLOPT_URL,"https://graph.instagram.com/v15.0/{$userId}?fields=account_type,id,username,media{caption,id,comments_count,like_count}&access_token={$accessToken}");   
              
      curl_setopt($chs,CURLOPT_RETURNTRANSFER,TRUE);
      curl_setopt($chs,CURLOPT_SSL_VERIFYPEER,false);

      $response = curl_exec($chs);

      $oAuth = json_decode($response);
            echo "<pre>";
             print_r($oAuth); exit ;

      $username = $oAuth->username ;
      $user = ['email'=>$username,'token'=>$userId,'name'=>$username,'social_id'=> $userId,'social_type'=> 'instagram','password' => encrypt('my-facebook')];
      $user = (object)$user ;
      //$data = User::where('email',$user->email)->first();
      // if(is_null($data)){        
      // $users['name']=$user->name ;
      // $users['email']=$user->email ;      
      // $users['social_id']=$user->social_id ;  
      // $users['social_type']=$user->social_type ;
      // $users['password']=$user->password ;  
      // $data = User::create($users);
      // }
      Auth::login($data);
      return redirect('/');

}
    
     
     public function fb_error(){
         echo "Something went wrong" ;
      }


      public function fbSuccessResp(){
         echo "Facebook page and instagram bussiness account details successfully have been added" ;
      }
      /*
     FB_APP_ID=1233295267813831
 FB_APP_SECRET=49634e99dd487c9747dcd190fcd1539b */

 //login with facebook 

     public function fbLogin(Request $request){
     
      $data['title']='LesGo' ;
   
      if($request->hasCookie('userName') != false){
        $data['userName']=Cookie::get('userName') ;
      }else{
        $data['userName']="" ;
      }

      if($request->hasCookie('userPassword') != false){
         $data['userPassword']=Cookie::get('userPassword') ;
      }else{
         $data['userPassword']="" ;
      }
      
        $config = array( // instantiation config params
        'app_id' => '1097279568595198', // facebook app id
        'app_secret' => '0c29d5b6952b0fcd6f69dbbe99c95aeb', // facebook app secret
        );
       
       
        // uri facebook will send the user to after they login
        $redirectUri = 'https://walkofweb.in/fbCallback';
       
       // $redirectUri = 'https://walkofweb.in/fbCallback';
        $permissions = array( 
            'instagram_basic',
            'pages_show_list',
            'pages_read_engagement'          
        );

        // 'instagram_content_publish', 
        //     'instagram_manage_insights', 
        //     'instagram_manage_comments',
        //     'pages_show_list', 
        //     'ads_management', 
        //     'business_management', 
        //     'pages_read_engagement'
        // instantiate new facebook login
        
         $facebookLogin = new FacebookLogin( $config );
        
        
        $fbLoginUrl=$facebookLogin->getLoginDialogUrl( $redirectUri, $permissions );
       
       
        //fb profile data
         $config_ = array( // instantiation config params
        'app_id' => '1097279568595198', // facebook app id
        'app_secret' => '0c29d5b6952b0fcd6f69dbbe99c95aeb', // facebook app secret
        );

        
        $permissions_ = array(            
            'user_friends',
            'user_posts'           
        );

       

        // instantiate new facebook login
        $facebookLogin_ = new FacebookLogin($config_);
        $redirectUri_ = 'https://walkofweb.in/callback/facebook/fbBasicInfo';
        $fbLoginUrl_=$facebookLogin_->getLoginDialogUrl( $redirectUri_, $permissions_ );
       
      
      //  $instaLoginUrl ="https://walkofweb.in/insta/login";
      //  $tiktokLoginUrl="https://walkofweb.in/auth";

      
      // return view('fbLogin',["fbLoginUrl"=>$fbLoginUrl,"fbLoginUrl_"=>$fbLoginUrl_,"instaLoginUrl"=>$instaLoginUrl,"tiktokLoginUrl"=>$tiktokLoginUrl]);

      return view('user/login',["fbLoginUrl"=>$fbLoginUrl,"fbLoginUrl_"=>$fbLoginUrl_,"data"=>$data]);
      
       
     }

     
     public function fb_connect($userId){
      
       $config = array( 
        'app_id' => env('FB_BUSINESS_APP_ID'), 
        'app_secret' => env('FB_BUSINESS_APP_SECRET') 
        );       
      
        $redirectUri = env('FB_BUSINESS_REDIRECT_URI') ; 
        $permissions = array(
            'instagram_basic' ,
            'pages_show_list'  
        );
           
        $facebookLogin = new FacebookLogin($config);
        $fbLoginUrl=$facebookLogin->getLoginDialogUrl($redirectUri,$permissions); 
        return Redirect::to($fbLoginUrl."&state=".$userId);
        
     }

     public function checkUser($state){
           
            $checkUsr = DB::table('users')->select('id')->where('encryption',$state)->first();
            
            if(!empty($checkUsr)){
             return $userId=$checkUsr->id ;
            }else{
              $userId=0 ; 
              echo "Invalid Request" ; exit ;
            }
     }

      public function fbResponse(Request $request){

        dd($request->all());
        
           $state=isset($request->state)?$request->state:'' ;
           $userId=$this->checkUser($state);

         $config = array( 
          'app_id' => env('FB_BUSINESS_APP_ID'), 
          'app_secret' => env('FB_BUSINESS_APP_SECRET') 
          );
          
            $code = $request->code ;          
         
            $redirectUri = env('FB_BUSINESS_REDIRECT_URI') ; 
          
            $accessToken = new AccessToken($config);

          
            $newToken = $accessToken->getAccessTokenFromCode( $_GET['code'], $redirectUri );
            // echo "<pre>"; print_r($newToken)   ; exit ; 
            if(!$accessToken->isLongLived()){
                $newToken = $accessToken->getLongLivedAccessToken( $newToken['access_token'] );
            }

            $this->saveFbLoginToken($userId,2,$newToken['access_token'],'');
            $this->bussinessDiscovery($newToken['access_token'],$userId);
      }

      public function bussinessDiscovery($accessToken='',$userId){     
              
       $outhUrl = "https://graph.facebook.com/v15.0/me?fields=id,name,accounts{followers_count,fan_count,name,instagram_business_account}&access_token={$accessToken}" ;
         
        $response=$this->getDataFromFb($outhUrl);
        $oAuth = json_decode($response);
       
        $instaInfoUrl=[] ;
        //id  user_id        
         $insertData=array();
        if(isset($oAuth->accounts->data) && !empty($oAuth->accounts->data)){

           foreach ($oAuth->accounts->data as $key => $value) {
                $page_followerCount = $value->followers_count ;
                $page_fanCount = $value->fan_count ;
                $page_name = $value->name ;
                $page_id = $value->id ;
                if(isset($value->instagram_business_account)){
                  $instagramId = $value->instagram_business_account->id ;
                  $instaInfoUrl[]="https://graph.facebook.com/v15.0/".$instagramId."?fields=name,ig_id,username,followers_count,follows_count,media_count,media&access_token={$accessToken}";
                }else{
                  $instagramId = '' ;
                }

                $checkExistPage = DB::table('user_fb_page_info')->select('id')->where('page_id',$page_id)->first();
                if(!empty($checkExistPage)){
                  $existPageId = $checkExistPage->id; 
                  $udpateData=array(
                     'page_followers'=>$page_followerCount ,
                     'page_fan_count'=>$page_fanCount ,
                     'instagram_bussiness_acount'=>$instagramId ,
                     'page_name'=>$page_name 
                  );
                  DB::table('user_fb_page_info')->where('id',$existPageId)->update($udpateData);
                }else{
                  $insertData=array(
                  'user_id'=>$userId ,
                  'page_followers'=>$page_followerCount ,
                  'page_fan_count'=>$page_fanCount ,
                  'instagram_bussiness_acount'=>$instagramId ,
                  'page_name'=>$page_name ,
                  'page_id'=>$page_id
                  );
                   DB::table('user_fb_page_info')->insert($insertData);
                }

                
           }

           //get instagram information
          $this->getInstagramInfo($instaInfoUrl,$userId,$accessToken); 
           redirect()->to('/fbSuccess')->send();
         // redirect()->to('/userList')->send();
           
        }else{
          redirect()->to('/fbError')->send();
          
        }
     }

    public function getInstagramInfo($instaInfoUrl,$userId,$accessToken){
        if(!empty($instaInfoUrl)){
            foreach ($instaInfoUrl as $key => $value) {
              $response_=$this->getDataFromFb($value);
              $instaData = json_decode($response_);
              
              if(!empty($instaData)){

                $checkInstaInfo=DB::table('user_instagram_info')->select('id')->where('bussiness_accountId',$instaData->id)->first();

                 if(!empty($checkInstaInfo)){
                  $updateId=$checkInstaInfo->id ;  
                  $insta_updateData=array(
                  'followers_count'=>$instaData->followers_count ,
                  'follows_count'=>$instaData->follows_count ,
                  'name'=>$instaData->name ,
                  'biography'=>'' ,
                  'ig_id'=>$instaData->ig_id ,
                  'media_count'=>$instaData->media_count ,
                  'username'=>$instaData->username
                   );  
                 DB::table('user_instagram_info')->where('id',$updateId)->update($insta_updateData);
                 
                 }else{
                  $insta_insertData=array(
                  'bussiness_accountId'=>$instaData->id ,
                  'userId'=>$userId  ,
                  'followers_count'=>$instaData->followers_count ,
                  'follows_count'=>$instaData->follows_count ,
                  'name'=>$instaData->name ,
                  'biography'=>'' ,
                  'ig_id'=>$instaData->ig_id ,
                  'media_count'=>$instaData->media_count ,
                  'username'=>$instaData->username
                );  
                 DB::table('user_instagram_info')->insert($insta_insertData);
                 
                 }

                 //Get Instagram Media Data
                  $instaMediaData=isset($instaData->media->data)?$instaData->media->data:[];
                  
                  if(!empty($instaMediaData)){
                    $mediaInfo=$this->getInstaMediaInfo($instaData->media->data,$accessToken,$userId);
                  }                
              }
            }
            	 $this->updateUserInstaData($userId);
            	 $this->updateUserInstaRankPoints($userId,3);
           }
     }

     public function getInstaMediaInfo($mediaData,$accessToken,$userId){
      
      $insertData=[];     
       if(!empty($mediaData)){
         foreach ($mediaData as $key => $value) {
           $mediaId = isset($value->id)?$value->id:0 ;
           if($mediaId!=0){
             $mediaUrl="https://graph.facebook.com/v15.0/".$mediaId."?fields=id,like_count,comments_count&access_token={$accessToken}";
             $mediaData = $this->getDataFromFb($mediaUrl) ;
             $mediaData =json_decode($mediaData);
            
             $mediaId = isset($mediaData->id)?$mediaData->id:0;
             $mediaLikeCount=isset($mediaData->like_count)?$mediaData->like_count:0;
             $mediaCommentCount=isset($mediaData->comments_count)?$mediaData->comments_count:0;

             $insertData[]=array("userId"=>$userId,"media_id"=>$mediaId,"like_count"=>$mediaLikeCount ,"comment_count"=>$mediaCommentCount );
           }
         
         }
       }
       
       if(!empty($insertData)){
         DB::table("insta_media_info")->where('userId',$userId)->delete();
         DB::table("insta_media_info")->insert($insertData);
       }
       
     }

      public function getDataFromFb($url){
         $chs = curl_init();          
       curl_setopt($chs,CURLOPT_URL,$url);
        curl_setopt($chs,CURLOPT_RETURNTRANSFER,TRUE);
        curl_setopt($chs,CURLOPT_SSL_VERIFYPEER,false);
        $response = curl_exec($chs);
       
        return $response ;
     }

     public function updateUserInstaData($userId){

       $checkData=DB::table('insta_user_info')->select('id')->where('userId',$userId)->first();
       
       $instaData=DB::table('user_instagram_info')->select(DB::raw('sum(followers_count) as followers_count'),DB::raw('sum(follows_count) as follows_count'),DB::raw('sum(media_count) as media_count'))->where('userId',$userId)->first();       
       
       $instaMediaData = DB::table('insta_media_info')->select(DB::raw('sum(like_count) as like_count'),DB::raw('sum(comment_count) as comment_count'))->where('userId',$userId)->first();       

       	$iFollowersC = isset($instaData->followers_count)?$instaData->followers_count:0 ;
       	$iFollowsC = isset($instaData->follows_count)?$instaData->follows_count:0 ;
       	$iMediaCount = isset($instaData->media_count)?$instaData->media_count:0 ;
       	$iLikeCount = isset($instaMediaData->like_count)?$instaMediaData->like_count:0 ;
       	$iCommentCount = isset($instaMediaData->comment_count)?$instaMediaData->comment_count:0 ;

            $insertData=array(       			
       			'followers_count'=>$iFollowersC ,
       			'follows_count'=>$iFollowsC ,
       			'total_post_count'=>$iMediaCount ,
       			'total_post_comment_count'=>$iLikeCount ,
       			'total_post_likes_count'=>$iCommentCount 
       		);

       if(!empty($checkData)){
       		$updateId = $checkData->id ;
       		DB::table('insta_user_info')->where('id',$updateId)->update($insertData);
       }else{  		
 			$insertData['userId']=$userId ;
       		DB::table('insta_user_info')->insert($insertData);
       }

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
    
    //Fb profile data
     public function fb_Profile_Data($userId){
        
         $config = array( 
        'app_id' => env('FB_APP_ID'), 
        'app_secret' => env('FB_APP_SECRET')
        );

        $permissions = array(            
            'user_friends',
            'user_posts'           
        );

        $facebookLogin = new FacebookLogin($config);
        $redirectUri = env('FB_REDIRECT_URI');
        $fbLoginUrl=$facebookLogin->getLoginDialogUrl($redirectUri,$permissions );
        
        return Redirect::to($fbLoginUrl."&state=".$userId);       
     }


    public function fbProfileDataResponse(Request $request){
      die("hello");
     
  //  $url=   Socialite::driver('facebook')->redirect();
  //  dd($url);
  //     dd((array)$url);
            
            $state=isset($request->state)?$request->state:'' ;
            $userId=$this->checkUser($state);
           

          //   $rules=[            
          //   'code' => 'required'
          //  ] ;

           // $validatedData = Validator::make($request->all(),$rules);
      
 
        // if($validatedData->fails()){         
        //     return $this->errorResponse($validatedData->errors()->first(), 200);
        //   }

            // $config = array( 
            // 'app_id' => env('FB_APP_ID'), 
            // 'app_secret' =>env('FB_APP_SECRET') 
            // );


            $config = array( 
              'app_id' => '6058802037574834', 
              'app_secret' =>'60fe2bc3779e9f170628b1bafe7cc686' 
              );
            // $code = $request->code ;

           
            // $redirectUri = env('FB_REDIRECT_URI');
            $redirectUri = 'https://walkofweb.in/fbBasicInfo';

            // instantiate our access token class
            $accessToken = new AccessToken($config);
            // dd($accessToken);

            // exchange our code for an access token
            $newToken = $accessToken->getAccessTokenFromCode( 'zyVOAG3Y0ICWQTyXBeKWOJXSm0SEyo0OxZNGQEqm', $redirectUri );
            
                    
            if ( !$accessToken->isLongLived() ) { 
                $newToken = $accessToken->getLongLivedAccessToken( $newToken['access_token'] );
            }

            $accessToken=$newToken['access_token'] ;
            $this->saveFbLoginToken($userId,1,$newToken['access_token'],$refreshToken='');
            $this->getFBProfileData($accessToken,$userId);
            	//echo "success" ;
          if($accessToken!=''){
            redirect()->to('/fbProfileSuccess')->send();
          }else{
            redirect()->to('/fbError')->send();  
          }
          
      }

        public function fbProfileSuccessResp(){
        echo "Facebook profile details successfully has been added" ;
     }

      public function getFBProfileData($accessToken,$userId){
           
       $url="https://graph.facebook.com/v15.0/me?fields=id,name,posts{message,comments.summary(true){comments.summary(true).limit(0)},reactions.summary(true)},friends&access_token={$accessToken}";            
           
        $fbData_=$this->getDataFromFb($url);
        $fbData =json_decode($fbData_,true);
          
           $fbUserId=isset($fbData['id'])?$fbData['id']:0 ;
           $fbUserName=isset($fbData['name'])?$fbData['name']:'' ;
           $fbUserFriends=isset($fbData['friends']['summary']['total_count'])?$fbData['friends']['summary']['total_count']:0 ;
           $fbUserPost=isset($fbData['posts']['data'])?$fbData['posts']['data']:[] ;
           
           $totalComments=0;
           $totalLikes=0 ;
           $totalPost=count($fbUserPost);
           $insertCommentData=[];
            if(!empty($fbUserPost)){
              foreach ($fbUserPost as $key => $value) {
              	$message=isset($value['message'])?$value['message']:'';
                $replyComment=isset($value['comments']['data'])?$value['comments']['data']:[];
                $getReplyComment=$this->getReplyCommentCount($replyComment);
                $total_comments=isset($value['comments']['summary']['total_count'])?$value['comments']['summary']['total_count']:0 ;

                $total_likes=isset($value['reactions']['summary']['total_count'])?$value['reactions']['summary']['total_count']:0 ; 

                $totalComments=$totalComments+$total_comments+$getReplyComment ;
                $totalLikes=$totalLikes+$total_likes ;

                $insertCommentData[]=array('userId'=>$userId,'message'=>$message,'total_comment'=>($total_comments+$getReplyComment),'total_like'=>$total_likes);
                             
              }
            }

          if(!empty($insertCommentData)){
          	DB::table('fb_post_comment')->where('userId',$userId)->delete();
          	DB::table('fb_post_comment')->insert($insertCommentData);
          }

         $fbPageData = DB::table('user_fb_page_info')->select(DB::raw('sum(page_followers) as totalFollowers'),DB::raw('sum(page_fan_count) as totalLikes'))->where('user_id',$userId)->first();
         $followers = isset($fbPageData->totalFollowers)?$fbPageData->totalFollowers:0 ;
         $likes = isset($fbPageData->totalLikes)?$fbPageData->totalLikes:0 ;

         $insertData = array(
            'total_friends_count'=>$fbUserFriends ,
            'fb_page_followers_count'=>$followers ,
            'fb_page_likes_count'=>$likes ,
            'fb_post_comments'=>$totalComments ,
            'fb_post_likes'=>$totalLikes ,
            'fb_post_count'=>$totalPost 
         );

         $checkFbData = DB::table('fb_user_info')->select('id')->where('userId',$userId)->first();
         if(!empty($checkFbData)){
          DB::table('fb_user_info')->where('userId',$userId)->update($insertData);
         }else{
          $insertData['userId'] = $userId ;
          DB::table('fb_user_info')->insert($insertData);
         }
        
         $this->updateUserInstaRankPoints($userId,2);
      }

     public function getReplyCommentCount($replyComment){

        $totalComment=0 ;
       
        if(!empty($replyComment)){
          foreach ($replyComment as $key => $value){
            $replyComment = isset($value['comments']['summary']['total_count'])?$value['comments']['summary']['total_count']:0 ;
            $totalComment=$totalComment+$replyComment ;
          }
        }
         
        return $totalComment ;
     }

      public function saveFbLoginToken($userId,$type,$accessToken,$refreshToken=''){
      		
      		$checkToken=DB::table('fb_login_info')->select('id')->where('usreId',$userId)
      		            ->where('type',$type)->first();

      		if(!empty($checkToken)){
      			$updateId = isset($checkToken->id)?$checkToken->id:0 ;
      			$updateData=array(	      			
	      			"access_token"=>$accessToken ,
	      			"refresh_token"=>$refreshToken 	      			
      		     );
      			DB::table('fb_login_info')->where('id',$updateId)->update($updateData);
      		}else{
      			$insertData=array(
	      			"usreId"=>$userId ,
	      			"access_token"=>$accessToken ,
	      			"refresh_token"=>$refreshToken ,
	      			"type"=>$type 
      		     );
      			DB::table('fb_login_info')->insert($insertData);
      		}
      }

      public function user_list(Request $request){
        $userList=DB::table('users')->where('user_type','!=',1)->where('isTrash',0)->orderBy('id', 'DESC')->get();
        
       return view('fb_review.index',["userList"=>$userList]);
     }

     public function user_points(Request $request){
      $userId=$request->id ;
      $name=$request->name ;
      $socialWeightage = DB::table('social_media_weightage')->where('status',1)->get();
      $fbData=DB::table('fb_user_info')->where('userId',$userId)->first();
      $instaData=DB::table('insta_user_info')->where('userId',$userId)->first();
      $tiktokData=DB::table('tiktok_user_info')->where('userId',$userId)->first();
      $userPoint=DB::table('user_social_point')->where('user_id',$userId)->first();
      $sw=[];
      if(!empty($socialWeightage)){
        foreach ($socialWeightage as $key => $value) {
          $sw[$value->slug]=$value->weightage ;
        }
      }
      
      return view('fb_review.ajax_points',['socialW'=>$sw,'fbData'=>$fbData,'instaData'=>$instaData,'tiktokData'=>$tiktokData,'userPoint'=>$userPoint,'name'=>$name]);
     }
     
}

