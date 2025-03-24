<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Post;
use App\Models\Sponser;
use App\Models\Post_comment;
use App\Models\Post_image;
use App\Models\Post_like;
use App\Models\User_interest;
use App\Models\Advertisement;
use App\Models\Notifications ;
use App\Models\Cms ;
use App\Models\Countries ;
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
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Http;








class UserController extends Controller
{

  /**
 * @OA\Info(
 *     title="My API",
 *     version="1.0.0",
 * )
 */
     public function testData(){
      test();
        echo "Hello Walkofweb" ;

    }
    public function putsession()
    {
       session(['name' => 'John Doe']);
       
       return "Session created";
    }
public function get_session()
{
  echo session('name');
   //return session('name');


   
}


/**
 * @SWG\Get(
 *     path="/users",
 *     summary="Get a list of users",
 *     tags={"Users"},
 *     @SWG\Response(response=200, description="Successful operation"),
 *     @SWG\Response(response=400, description="Invalid request")
 * )
 */

     public function register(Request $request){
     	  
        //deviceType 1 android 2 ios
         try
        {        	
            
            $rules=[            
            'name' => 'required',
            'email' => 'email|required|unique:users',
            'phoneNumber' => 'required|numeric|unique:users',
            'password' => 'required',
            'countryId' => 'required',
            'deviceType' => 'required|numeric',
            'userType' => 'required'

           ] ;

            $validatedData = Validator::make($request->all(),$rules);
      
 
        if($validatedData->fails()){       
            return $this->errorResponse($validatedData->errors()->first(), 200);
          }

          $name=$request->name ;
          $usrName = substr($name,0,2);
          $password_ = $request->password ;
          $email=$request->email ; 
          $userType=$request->userType ; 
          $fairebaseToken = isset($request->firebase_token)?$request->firebase_token:''  ;     
          // new

          $country_code = DB::table('pe_countries')->select('api_code')->where('id', $request->countryId)->first();
          $request['country_code'] = (!empty($country_code))?$country_code->api_code:'' ;
          $request['countryCode'] = isset($request->countryCode)?$request->countryCode:'' ;
          $request['name'] = $request->name;
         
          

          $request['registration_from'] = $request->deviceType ;
          $request['rank_type'] =0 ; // rand(1,5);
          $request['rank_'] =0 ;// rand(1,30);     
          $request['username'] ='' ; //$userName;
    			$request['password']=Hash::make($request['password']);
    			$request['remember_token'] = Str::random(10);
          $request['user_type'] = $request->userType;
          $imagick=config('constants.imagick');
           
    			$user = User::create($request->toArray());
         
         
       
    			$token = $user->createToken('walkofweb token')->accessToken;
          $message =  __('messages.user_registration') ; //"Account has been created successfully." ;
          $insertId=$user->id;
          $encryptionKey = md5('wow_intigate_23'.$insertId);
          $userName = $this->UsernameGenerate($usrName,$insertId);   
          DB::table('users')->where('id',$insertId)->update(['encryption'=>$encryptionKey,'username'=>$userName,'api_token'=>$token]);
          // $imagick=config('constants.imagick');
          // if($imagick==1){
          //    $this->qrCode($encryptionKey);
          //    $this->sendRegistrationEmail($name,$email,$password_);
          // }
			    saveDeviceToken($insertId,$fairebaseToken,$request->deviceType,$encryptionKey,$token);
         // $data['id']=$insertId ;
          $userdata=User::where('id',$insertId)->first();
          $data['id']=$userdata->id?$userdata->id:'-';
          $data['token'] = $token  ;
          $data['user_type']=$userdata->user_type?$userdata->user_type:'-';
          $data['encryption']=$encryptionKey ;
          $data['countryId']=$userdata->countryId?$userdata->countryId:'-';
          $data['country_code'] = $userdata->country_code ?$userdata->country_code:'-';

          return $this->successResponse($data,$message,200);    

        }
        catch(\Exception $e)
        {
          return $this->errorResponse(__('messages.user_registration_err'.$e), 200);
          // return $this->errorResponse('This user is already exist.'.$e, 200);
        }


    }
    public function TopPositionUsers(Request $request)
    {
          $filePath = config('constants.user_image') ;
          $starImg = config('constants.star_image');
          $dummy_image=config('constants.dummy_image');
          $userData=User::select(['users.id','users.name','users.rank_type', 'users.rank_',DB::raw('ROW_NUMBER() OVER (ORDER BY rank_ DESC ) 
          AS position'),DB::raw('concat("'.$starImg.'",rt.star_img) as starImg'),DB::raw('case when (users.image is null || users.image="" )  then "'.$dummy_image.'" else concat("'.$filePath.'",image) end as profile_image')])->Join('rank_types as rt','rt.id','=','users.rank_type')->where('users.rank_','<>',0)->get();
          if(!empty($userData)){
            return $this->successResponse($userData,'Top 10 User Detail',200);
          }
          else{
            return $this->successResponse('Top 10 User Detail not found',200);
          }

         
     
    } 
    public static  function  savefbuser_details($data,$image_name,$token)
    {
     
      if($data)
        {
        
         
            if(!empty($data->error))
            {
            
              
                return response()->json(['message'=>$data->error->message,'status_code'=>400]);
            }
            else{

             
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
                   $request['facebook_token']=$token;
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
                  $user=User::updateorCreate(
                    [
                     'social_id'=>$data->id,
                    ],
                    [
                      'username'=>$usrName,
                      'facebook_token'=>$token,
                      'email'=>$email,
                      'password'=>Hash::make('my-facebook'),
                      'user_type'=>2,
                      'image'=>$image_name,
                      'short_name'=>$data->short_name,
                      'name_format'=>$data->name_format,
                      'facebook_username'=> $data->name,
                      'social_id'=>$data->id,
                      'first_name'=>$data->first_name,
                      'last_name'=>$data->last_name,
                      'name'=>$data->name,
                      'registration_from'=> 1,
                      'rank_type'=>0,
                      'rank_'=>0,
                      'remember_token'=>$data->last_name,
                      'name'=>$data->name,
                      'registration_from'=> 1,
                      'rank_type'=>Str::random(10),
                 
                      'facebook_token'=>$token
                    ]
                

                  );
                 
                



                 
                  $user=User::where('social_id',$data->id)->first();
                

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
                   $imagick=config('constants.imagick');
                
              
                  $deviceType= $request['registration_from'];
                 $fairebaseToken='';
                   saveDeviceToken($insertId,$fairebaseToken,$deviceType,$encryptionKey,$token);
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
                 
                  return response()->json(['message'=>'fb Login Registration Successfully','data'=>['User_details' =>$userData],'status_code'=>200]);

                   
                
            }
            
        }
        else{
            return response()->json(['error'=>'session expired'], 404);
        }
        
        
          
    }

   
    public function UsernameGenerate($usrName,$id){

      $number = mt_rand(10000,99999);
      $username = "WOW".strtoupper($usrName).$id ;
      if($this->UsernameExist($username)){
        return $this->UsernameGenerate($usrName);
      }
      return $username ; 
    }

    public function UsernameExist($usrName){
      return User::where(['username'=>$usrName])->exists();
    }



    public function doLogin(Request $request){

        $validator = Validator::make($request->all(), [
        'email' => 'required|max:255|email',
        'password' => 'required',
        ]);

        if($validator->fails())
        {
        return response(['errors'=>$validator->errors()->all()], 200);
        }

       
         if(is_numeric($request->get('email'))){
           $param = ['phoneNumber'=>$request->get('email')];
          }
          else if(filter_var($request->get('email'), FILTER_VALIDATE_EMAIL)) {
            $param = ["email"=>$request->get('email')] ;
          }else{
             $param = ["email"=>$request->get('email')] ;
          }

    //  ,'isTrash'=>0,'status'=>1
        $user = User::where($param)->first();
        if(!empty( $user))
        {

          Auth::login($user);

          if($user && isset($user->isTrash) && isset($user->status) && ($user->status==0 || $user->isTrash==1)){
            return $this->errorResponse("Your account is inactive! please contact with admin",200);    
          }
  
           if($user){
          //   echo $user->password;
          //   echo '<br>'. Hash::make($request->password);
          //   die;
           
              if (Hash::check($request->password, $user->password)) {
                $token = $user->createToken('walkofweb token')->accessToken; 
                User::where('id',$user->id)->update(['api_token'=>$token]);
                  
                  $data['id'] = $user->id  ;    
                  $data['user_type'] =  $user->user_type  ;  
                  $data['country_code'] =  $user->country_code  ;  
                  $data['countryId'] =  $user->countryId  ; 
                  $data['token'] = $token   ;
                  $data['encryption'] = $user->encryption ;
                  $fairebaseToken = isset($request->firebase_token)?$request->firebase_token:''  ;     
                  $message = __('messages.user_login_succ') ;
                   saveDeviceToken($user->id,$fairebaseToken,1,$user->encryption,$token);
                  if(isset($request->firebase_token)){
                    User::where('id',$user->id)->update(['firebase_token'=>$fairebaseToken,'api_token'=>$token]);
                  }
                  
                  return $this->successResponse($data,$message,200);
                 
                  } else {
                      $message = __('messages.user_login_err') ;
                      return $this->errorResponse($message,200);               
                  }
        
         } 
         else {
          $message = __('messages.user_login_err1') ;
          return $this->errorResponse($message,200);              
        }
        }
        else {
            $message = __('messages.user_login_err1') ;
            return $this->errorResponse($message,200);              
        }
       
    }

    public function updateSocialInfo(Request $request){

    	try{
// 'insta_username' => 'string',
//         'fb_username' => 'string',
//         'tiktok_username' => 'string',
       $validator = Validator::make($request->all(), [ 
        'user_interest' => 'array'
        ]);

        if($validator->fails())
        {
        return response(['errors'=>$validator->errors()->all()], 200);
        }
          $type=isset($request->type)?$request->type:0 ;
          $userId = authguard()->id ;
          if($type!=1){
             User::where('id', $userId)->update(['instagram_username'=>$request->insta_username,'facebook_username'=>$request->fb_username,'tiktok_username'=>$request->tiktok_username]);
          }
         
          DB::table('user_interests_map')->where('user_id',$userId)->delete();
          $uInterest=array();
          if(!empty($request->user_interest)){
            foreach($request->user_interest as $val){
              $uInterest[]=array('user_id'=>$userId,'interest_id'=>$val,'status'=>1);
            }
          }
          if(!empty($uInterest)){
            DB::table('user_interests_map')->insert($uInterest);
          }
          $message=__('messages.user_update_social_info') ;
          return $this->successResponse([],$message,200);   

    	} catch(\Exception $e) {
           return $this->errorResponse(__('messages.user_update_social_info_err'), 200);
        }    	

    }

    public function user_interest(Request $request){
      $userId=authguard()->id ;
       $qry="select ui.id,ui.title,ui.status,case when (select id from user_interests_map where user_id=".$userId." and interest_id=ui.id and status=1 limit 1
      ) is null then 0 else 1 end as isSelected from user_interests as ui where ui.status=1" ;
      $data=DB::select($qry);           //User_interest::all()->toArray();
      return $this->successResponse($data,"User interest list",200);
    }

      public function logout(Request $request) {
      $userId=authguard()->id;
      $bearerToken = $request->bearerToken();
      $tokenId = Configuration::forUnsecuredSigner()->parser()->parse($bearerToken)->claims()->get('jti');
      $client = Token::find($tokenId)->client;

      DB::table('oauth_access_tokens')
            ->where('id',$tokenId)
            ->where('user_id',$userId)
            ->update(['revoked' =>1]);
      return $this->successResponse([],__('messages.user_log_out'),200);   
   }

   public function save_sponser(Request $request){  
        $userId=authguard()->id;
  

         $rules=[
            'title' => 'required|string',
            'image' => 'required',
            'description'=> 'required'         
           ] ;

       try{
        $sponser='';
      if($request->hasFile('image')) {
        
       
       $imgPath='app/public/sponser_img/' ;
       
    
        $filenamewithextension = $request->file('image')->getClientOriginalName();
       
        //get filename without extension
        $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
  
        //get file extension
        $extension = $request->file('image')->getClientOriginalExtension();
  
        $filename=str_replace(' ', '_', $filename);
        $filenametostore = $filename.'_'.time().'.'.$extension;       
        $smallthumbnail = $filename.'_100_100_'.time().'.'.$extension;    
       
        //Upload File
        $request->file('image')->storeAs('public/sponser_img', $filenametostore);
        $request->file('image')->storeAs('public/sponser_img/thumb', $smallthumbnail);
       
         
        //create small thumbnail
        $smallthumbnailpath = public_path('storage/sponser_img/thumb/'.$smallthumbnail);
      
        $sponser= new Sponser;
        $sponser->name = $request->name;
        $sponser->description = $request->description;
        $sponser->image = $smallthumbnailpath;
        $sponser->createdBy = 1;
        $sponser->user_id = $userId;
        $sponser->save();
       

        $file_path = url('/').'/public/storage/sponser_img/'.$filenametostore;
        return $this->successResponse($sponser,__('messages.user_save_sponser'),200);

        }else{
            return $this->errorResponse('Invalid request.', 200);
        }

      }catch(\Exception $e)
        {
           return $this->errorResponse('Error occurred.'.$e, 200);
        }
    
   }

    public function createThumbnail($path, $width, $height)
    {
        
      $img = Image::make($path)->resize($width, $height)->save($path);
      
      
    }


    public function notificationsList(Request $request){
        //$data=Notifications::all();
       // return $this->successResponse($data,'Notifications list',200);
         $userId=authguard()->id;
        $filePath = config('constants.user_image') ; 
        $starImg = config('constants.star_image');  

        $page = $request->has('page') ? $request->get('page') : 1;
        $limit = $request->has('per_page') ? $request->get('per_page') : 10;
        $offset = ($page - 1) * $limit ;

        $notificationList='select n.id,n.hostId,u.rank_,n.title, n.message, n.isAccept,case when concat(u.image) is null then "" else concat("'.$filePath.'",u.image) end as image,concat("'.$starImg.'",rt.star_img) as starImg,isRead from notifications as n left join users as u on u.id=n.userId left join rank_types as rt on rt.id=u.rank_type where n.isAccept!=2 and n.userId='.$userId." order by n.id desc limit ".$limit." offset ".$offset ;
       

         $notificationList_ = DB::Select($notificationList);
        $data=array() ;
        $notifyList=array();
        if(!empty($notificationList_)){
          foreach ($notificationList_ as $key => $value) {
             $rankT = getRankType($value->rank_);
   //  exit ;
      $rankImg = DB::table('rank_types')->select('rank_title',DB::raw('concat("'.$starImg.'",star_img) as starImg'))->where('id',$rankT)->where('status',1)->first() ;
      $value->starImg=isset($rankImg->starImg)?$rankImg->starImg:'' ;
      $value->rank_type=$rankT ;

             $notifyList[]=$value ;
          }
        
        }
         $data['notification_list']=$notifyList ;// $notificationList_ ;

      $totalRecord=DB::table('notifications')->where('userId',$userId)->where('isAccept','!=',2)->count();      
      
     
      if(($offset+$limit) < $totalRecord){
        $data['isShowMore']=true ;  
      }else{
        $data['isShowMore']=false ;  
      }

      $data['page']=$page ; 

        return $this->successResponse($data,'Notifications list',200);
    }

    public function udpateNotifications(Request $request){

     
       $validator = Validator::make($request->all(), [        
        'notificationId' => 'required|numeric',
        'isAccept' => 'required|numeric'        
        ]);

        if($validator->fails())
        {
        return response(['errors'=>$validator->errors()->all()], 200);
        }

        if($request->hostId > 0){          
          DB::table('user_host')->where('id',$request->hostId)->update(['isAccept'=>$request->isAccept]);
        }

        if($request->isAccept==1 && $request->hostId > 0){
          $n = Notifications::where('id',$request->notificationId)->first();
            

          $u=DB::table("user_host")->select("host_user_id","userId")->where('id',$n->hostId)->first();        
          $senderUserN=User::getName($u->host_user_id);
           $userN=User::getName($u->userId);

          $insertData=array(
            "userId"=>$u->userId ,
            "title"=>"Host Request Accepted" ,
            "hostId"=>$n->hostId ,
            "message"=>$senderUserN." accepted your host request." ,
            "isAccept"=>1 ,
            "isSend"=>1 ,
            "status"=>1
          );
           
          DB::table('notifications')->insert($insertData);

          /*new*/
            
           
          
        
          $insertData=array(
            "userId"=>$u->host_user_id ,
            "title"=>"Host Request Accepted" ,
            "hostId"=>$n->hostId ,
            "message"=>$userN." has been added to your host list." ,
            "isAccept"=>1 ,
            "isSend"=>1 ,
            "status"=>1
          );
           
          DB::table('notifications')->insert($insertData);
          /*end*/
          Notifications::where('id',$request->notificationId)->delete();
        }else if($request->isAccept==0 && $request->hostId > 0){
          DB::table('user_host')->where('id',$request->hostId)->delete();
          Notifications::where('id',$request->notificationId)->delete();
        }else{
          Notifications::where('id',$request->notificationId)->update(['isAccept'=>$request->isAccept]);
        }
        
        return $this->successResponse([],'Successfull update',200);
    }

    public function deActivateUserAccount(Request $request){
      $userId=authguard()->id;
      User::where('id',$userId)->update(['isTrash'=>1]);
      return $this->successResponse([],__('messages.user_deactivate_account'),200);
    }

    public function updateUserProfile(Request $request){

      $userId=authguard()->id ;
     
      $validator = Validator::make($request->all(), [        
          'type' => 'required'
          ]);

      if($validator->fails())
        {        
        return $this->errorResponse($validator->errors()->first(), 200);
        }

      $type=$request->type ;
          if($type==2 || $type==5){
             $countryCode=DB::table("pe_countries")->select('api_code')->where('id',$request->countryId)->first();
             $contrycode=isset($countryCode->api_code)?$countryCode->api_code:'';
          }
        
      

      if($type==1){
         $validator = Validator::make($request->all(), [        
          'name' => 'required'
          ]);
      }else if($type==2){

         $validator = Validator::make($request->all(), [        
          'countryId' => 'required|numeric'
          ]);
      }else if($type==3){
         $validator = Validator::make($request->all(), [        
          'phoneNumber' => 'required|numeric'
          ]);
      }else if($type==4){
        $validator = Validator::make($request->all(), [        
          'email' => 'required|email|unique:users'
          ]);
      }else if($type==5){
         $validator = Validator::make($request->all(), [        
          'name' => 'required',
          'bio' => 'required' ,
          'location' => 'required',
          'dob' => 'required',
          'countryId'=>'required',
          'address'=>'required'
          ]);
      }
//|date_format:Y-m-d
                                    
      if($validator->fails())
        {        
         return $this->errorResponse($validator->errors()->first(), 200);
        }
          $message="" ;
      if($type==1){
         User::where('id',$userId)->update(['name'=>$request->name]);
         $message=__('messages.user_name_update') ;
      }else if($type==2){
         User::where('id',$userId)->update(['countryId'=>$request->countryId,'country_code'=>$contrycode]);
         $message=__('messages.user_country_update');
      }else if($type==3){
         //User::where('id',$userId)->update(['phoneNumber'=>$request->phoneNumber]);
      }else if($type==4){
        //User::where('id',$userId)->update(['email'=>$request->email]);
      }else if($type==5){       
        $date_=(date('Y-m-d', strtotime($request->dob)));
        User::where('id',$userId)->update(['name'=>$request->name ,'country_code'=>$contrycode,'bio'=>$request->bio ,'address'=>$request->address,'location'=>$request->location ,'dob'=>$date_,'countryId'=>$request->countryId,'instagram_username'=>$request->insta_username ,'facebook_username'=>$request->fb_username ,'tiktok_username'=>$request->tiktok_username  ]);
        $message=__('messages.user_profile_update') ;
      }
      
      $userDetail = DB::table('users')->select('id','name','email','phoneNumber','bio','location','address','countryId',DB::raw('case when dob is null then "" else Date_format(dob,"%d %b %Y") end as dob'),'instagram_username','facebook_username','tiktok_username')->where('id',$userId)->first();
      return $this->successResponse($userDetail,$message,200);
         

    }

    public function updateProfileImage(Request $request){

         $rules=[            
            'image' => 'required'            
           ] ;

          
           DB::table('image_log')->insert(['data'=>json_encode((array)$request->file('image'),true)]);
       try{

      if($request->hasFile('image')) {        
        $file=$request->file('image');
      
        $imgPath=storage_path('app/public/profile_image/');
        
        $thumb_path=storage_path('app/public/thumb/');

        $filenamewithextension = $request->file('image')->getClientOriginalName();
        //get filename without extension
        $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
        //get file extension
        $extension = $request->file('image')->getClientOriginalExtension();
        $filename=str_replace(' ', '_', $filename);
       $filenametostore = $filename.'_'.time().'.'.$extension;       
       $smallthumbnail = $filename.'_100_100_'.time().'.'.$extension; 

     

     
        //Upload File
        $request->file('image')->storeAs( $imgPath, $filenametostore);
        $request->file('image')->storeAs('public/profile_image/thumb/', $smallthumbnail);
        //create small thumbnail
        $smallthumbnailpath = $thumb_path.$smallthumbnail; 
       $file->move($imgPath, $filenametostore);
       
       // $file->move('storage/profile_image/thumb/', $smallthumbnail);
      // $this->createThumbnail($smallthumbnailpath, 100, 100);
        $userId = authguard()->id ;
           $update=array(           
            'image'=>$filenametostore
           );
        User::where('id',$userId)->update($update);

        $file_path = url('/').$imgPath.$filenametostore;
        return $this->successResponse(['image_url'=>$file_path],__('messages.user_update_profileImg'),200);

        }else{
            return $this->errorResponse('Invalid request.', 200);
        }

      }catch(\Exception $e)
        {
           return $this->errorResponse('Error occurred.'.$e, 200);
        }
    

    }

    public function termCondition(Request $request){
          $termCondition = Cms::where('slug','term_condition')->first() ;
          
          return $this->successResponse(['termCondition'=>$termCondition->description],'term condition',200);
    } 

    public function privacyPolicy(Request $request){
       $termCondition = Cms::where('slug','privacy_policy')->first() ;
        return $this->successResponse(['privacyPolicy'=>$termCondition->description],'privacy policy',200);
    }

    public function changePassword(Request $request){
       $rules=[
        "currentPassword"=>"required",
        "password"=>"required|confirmed"
       ];

      $validator=Validator::make($request->all(),$rules);
      
      if($validator->fails())
      {         
         return $this->errorResponse($validator->errors()->first(), 200);
      }
      
      $user=authguard();

       if(Hash::check($request->currentPassword, $user->password)) {
        User::where('id',$user->id)->update(['password'=>Hash::make($request->password)]);
        return $this->successResponse([],__('messages.user_password_update'),200);
       }else{
        return $this->errorResponse(__('messages.user_password_chk'), 200);
       }


    }

     public function forgotPassword(Request $request)
    {       
            $validator=Validator::make($request->all(),['email'=>'required|email']);

            if($validator->fails())
            {           
              //$validator->errors()->first()
             return $this->errorResponse(__('messages.user_pwd_otp'), 200);
            }


            $otp = "123456" ; //str_random(60) ;
            /* user update password */
            $password ="123456" ;
          $user = User::select('status','isTrash')->where('email', $request->email)->first();
          $usrStatus = isset($user->status)?$user->status:0 ;
          $usrTrash = isset($user->isTrash)?$user->isTrash:0 ;

          if(!empty($user) && ($usrStatus==0 || $usrTrash==1)){
             return $this->errorResponse("Your account is inactive! please contact with admin", 200);
          }else if(!$user){
               return $this->errorResponse(__('messages.user_opt_err'), 200);
          }

          // $user->password = \Hash::make($password);
          // $user->update();


            /* end update password */
            DB::table('password_resets')->insert([
                'email' => $request->email,
                'token' => $otp,
                'created_at' => Carbon::now()
            ]); 

           // $this->sendPwdEmail($request->email,$password,3);
         
            $message=__('messages.user_otp_succ');
            return $this->successResponse([],$message,200);

    }

    public function sendPwdEmail($email,$otp=123456,$type=0){

        if($type==1){
            $subject='Account Registration' ;
        }else if($type==2){
            $subject='Account Login' ;
        }else {
            $subject='Forgot Password' ;
        }

        $checkEmail = User::select('id')->where('email',$email)->first();

        if(!empty($checkEmail)){
          $data=array(
          'email' => $email,
          'subject' => $subject,
          'message' => $otp
          );

          $data=sendPasswordToEmail($data);   
        }else{
            return $this->errorResponse('Invalid User Id', 200);
        }

       
    }

    public function verifyOTP(Request $request){

        $validator = Validator::make($request->all(), [
        'email' => 'required|email|exists:users,email',
        'otp' => 'required'
        ]);

        if($validator->fails()){       
          return $this->errorResponse($validator->errors()->first(), 200);
        }else{
         $checkOTP = DB::table('password_resets')->where(array('token'=>$request->otp,'email'=>$request->email))->first();
         if(empty($checkOTP)){
           return $this->errorResponse('Invalid OTP', 200);
         }else{
            return $this->successResponse([],__('messages.user_otp_match'),200);

         }

        }

    }    


public function resetPassword(Request $request){

        $validator = Validator::make($request->all(), [
        'email' => 'required|email|exists:users,email',
        'password' => 'required', 
        'confirm_password' => 'required|same:password',
        'otp' => 'required' 
       ]);

    //check if payload is valid before moving on
    if($validator->fails()){       
        return $this->errorResponse($validator->errors()->first(), 200);
    }else{

         $password = $request->password;
         $tokenData = DB::table('password_resets')->where(array('token'=>$request->otp,'email'=>$request->email))->first();

         if(empty($tokenData)){
           return $this->errorResponse('Invalid OTP', 200);
         }

        $user = User::where('email', $tokenData->email)->first();
        if(!$user){
             return $this->errorResponse('Email not found', 200);
        }

        $user->password = \Hash::make($password);
        $user->update();

        return $this->successResponse([],__('messages.user_pwd_change'),200);
    
    }
    
    }

    public function user_list(Request $request){

      //filter
      //social presence 2-facebook,3-instagram,4-tiktok,5-walkofweb

       $validator = Validator::make($request->all(), [
        'countryId' => 'array',
        'interest' => 'array', 
        'to_rank' => 'numeric',
        'from_rank' => 'numeric' ,
        'to_followers' => 'numeric',
        'from_follwers' => 'numeric' ,
        'social_presence' => 'array',
        'quick_filter'=>'array'

       ]);

    //quick_filter":[{"type":1,"id":5}]
    if($validator->fails()){       
        return $this->errorResponse($validator->errors()->first(), 200);
    }

   
    $page = $request->has('page') ? $request->get('page') : 1;
    $limit = 100 ; //$request->has('per_page') ? $request->get('per_page') : 10;
    $offset = ($page - 1) * $limit ;

      $searchKey=$request->input('search_keyword') ;
      $searchKeyword='';
      if($searchKey!=''){
        $searchKeyword=" and name like '%".$searchKey."%'" ;
      }

      $userId = authguard()->id ;
      $country=$request->countryId ;
      $interest=$request->interest ;
      $to_rank=$request->from_rank ;
      $from_rank=$request->to_rank ;
      $to_followers=$request->to_followers ;
      $from_follwers=$request->from_follwers ;
      $social_presence=$request->social_presence ;

      $filePath = config('constants.user_image') ;
      $advPath = config('constants.advertisement_image') ;
      $sPath = config('constants.sponser_image') ;

     $user_interest=array() ;
     $usr = array();
     $socialInfo = array() ;
     $filter="";
     $follower_filter="" ;

      if(!empty($interest)){
        $user_interest = DB::table("user_interests_map")->select(DB::raw("GROUP_CONCAT(user_id) as userId"))->whereIn('interest_id',$interest)->get()->toArray();
      }
      
      if(!empty($user_interest) and isset($user_interest[0]->userId) and $user_interest[0]->userId > 0){
        $usr=explode(',', $user_interest[0]->userId); 
        $filter=" and id in (".$user_interest[0]->userId.")" ;
      }

      if(!empty($country)){
        $filter=$filter." and countryId in (".implode(',',$country).")";
      }

      if($to_rank!=0 and $from_rank!=0){
        $filter=$filter." and rank_ between ".$to_rank." and ".$from_rank ;
      }else if($to_rank!=0){
        $filter=$filter." and rank_ >=".$to_rank ;
      }else if($from_rank!=0){
        $filter=$filter." and rank_ <=".$from_rank ;
      }
      
      if($to_followers!=0 and $from_follwers!=0){
        $follower_filter=" and total_followersCount between ".$to_followers." and ".$from_follwers ;
      }

      if(!empty($social_presence)){      
        $socialInfo = DB::select("select * from (select GROUP_CONCAT(distinct user_id) as userId,sum(follows_count) as total_followsCount,sum(followers_count) as total_followersCount,status from social_info where status=1 and social_type in (".implode(',',$social_presence).")) as followers where status=1 ".$follower_filter);
      }

      if(empty($social_presence) and $to_followers!=0 and $from_follwers!=0){
        $socialInfo = DB::select("select * from (select GROUP_CONCAT(distinct user_id) as userId,sum(follows_count) as total_followsCount,sum(followers_count) as total_followersCount,status from social_info where status=1) as followers where status=1  ".$follower_filter);
      }

      if(!empty($socialInfo) && isset($socialInfo[0]->userId) && $socialInfo[0]->userId > 0){
        $filter=$filter." and id in (".$socialInfo[0]->userId.")";
        
      }
            
      $countryCode = DB::raw('case when country_code is null then "" else country_code end as country_code');
    
      $qry="select id,name,rank_,rank_type,case when image is null then '' else concat('".$filePath."',image) end as image,countryId,$countryCode from users where id!=".$userId." and isTrash=0 and isFeatured=0".$filter.$searchKeyword." order by rank_type desc limit ".$limit." offset ".$offset ;


      //
      $list=DB::select("select id,encryption,name,rank_,rank_type,case when image is null then '' else concat('".$filePath."',image) end as image,case when countryId is null then 0 else countryId end as countryId,$countryCode from users where id!=".$userId." and isTrash=0 and isFeatured=0".$filter.$searchKeyword." order by rank_ desc limit ".$limit." offset ".$offset);

      $totalRecord=DB::select("select id from users where id!=".$userId." and isTrash=0 and isFeatured=0".$filter.$searchKeyword." order by rank_type desc");



      $image = DB::raw('case when concat("'.$filePath.'",image) is null then "" else concat("'.$filePath.'",image) end as image') ;
      $featureUser = DB::table('users')->select('id','name','rank_','rank_type',$image)->Where('isFeatured',1)->where('isTrash',0)->get() ;
     
      $totalFeatureUser=count($featureUser);
      $totalLoginUsrFollowers = User::getFollowersCount($userId);
      $loginUsrRank = User::getUserRank($userId);
     

      $data['featured_user']=array('total_followers'=>$totalLoginUsrFollowers,'rank_'=>$loginUsrRank) ;
      $data['total_feature']=$totalFeatureUser ;
      $advertisement=User::advertisement();
      $rankType = array() ;

      
     
      $data['list']=[];
      if(!empty($list)){
          foreach($list as $key => $val) {
          	$rankT = getRankType($val->rank_);
           $rankType[$rankT][] = $val ;            
          }
      }
      
      $star_imgPath=config('constants.star_image') ;
      if(!empty($rankType)){

        foreach ($rankType as $key => $value) {
          if($key!=0){

          $rankInfo = DB::table('rank_types')->select('id','rank_title',DB::raw('concat("'.$star_imgPath.'",star_img) as starImg'))->where('id',$key)->first();
         // $value->starImg = 'rankInfostarImg ';
          $value[0]->star_img = $rankInfo->starImg;
          $rankInfo->user_list =  $value ; 
          $data['list'][]= $rankInfo;
        }
          
          
        }
      }

      $data['advertisement']=$advertisement ;     
      
      $totalRecord=count($totalRecord) ;

      if(($offset+$limit) < $totalRecord){
        $data['isShowMore']=true ;  
      }else{
        $data['isShowMore']=false ;  
      }
      $data['page']=$page ;           
      $data['qry']=$qry ;
      return $this->successResponse($data,'User List',200);
      // DB::enableQueryLog();
      //        $query = DB::getQueryLog();
      // print_r($query);
    }

    public function user_filter(Request $request){

      $countries = countryList(); //DB::table('pe_countries')->select('i_id','v_title')->where('i_status',1)->get();
      $interest = DB::table('user_interests')->select('id','title')->where('status',1)->get();
      $rank=DB::table('users')->select(DB::raw('min(rank_) as minRank, max(rank_) as maxRank'))->where('isTrash',0)->first();
      $minRank = $rank->minRank ;
      $maxRank = $rank->maxRank ;
      $followers = DB::select("select min(totalFollowers) as minFollowers, max(totalFollowers) as maxFollowers from (select sum(followers_count) as totalFollows, sum(followers_count) as totalFollowers from `social_info` where `status` = 1 group by `user_id`) as follows"); 
     


      $minFollowers = (!empty($followers))?$followers[0]->minFollowers:0 ;
      $maxFollowers =(!empty($followers))?$followers[0]->maxFollowers:0;
      $socialPresence = DB::table('social_media')->select('id','title')->where('status',1)->get() ;

      $data['countries']=$countries ;
      $data['interest']=$interest ;
      $data['rank']=array("minRank"=>$minRank,"maxRank"=>$maxRank) ;
      $data['followers']=array("minFollowers"=>(int)$minFollowers,"maxFollowers"=>(int)$maxFollowers) ;
      $data['socialPresence']=$socialPresence ;
      return $this->successResponse($data,'User Filter',200);
    }
    public function  user_advertisement_listing(Request $request)
     {
        $userId =authguard()->id ;
        $userAdvertisement = User::advertisement();
        try{
          return $this->successResponse(['User Advertisement'=>$userAdvertisement],__('messages.user Advertisement Listing'),200);

        }
        catch(\Exception $e)
        {
           return $this->errorResponse('Error occurred.'.$e, 200);
        }
      }

    public function user_detail(Request $request){
       
       requestLog(json_encode($request->all()));
       requestLog(json_encode(authguard()->encryption));
       $userId = $request->userId ; //authguard()->id ;
       $otherUser = 1 ;

       if(authguard()->encryption==$userId){
        $userId=0 ;
       }

       if($userId==0){
        $userId =authguard()->id ;
         $otherUser = 0 ;
       }

    
      
    

    if($otherUser==1){
        $checkUser = DB::table('users')->where('encryption',"$userId")->get()->toArray();       
     }else{
       $checkUser = DB::table('users')->where('id',$userId)
       ->get()->toArray();
     }
      
     
       if(empty($checkUser)){
         return $this->errorResponse(__('messages.user_detail_err'), 200);
       }


       if($otherUser==1){
           $userId=isset($checkUser[0]->id)?$checkUser[0]->id:0 ;
       }
     
      $instaUrsername=DB::raw('case when instagram_username is null then "" else instagram_username end as instagram_username');
      $fbUrsername=DB::raw('case when facebook_username is null then "" else facebook_username end as facebook_username');
     
      $tiktokUrsername=DB::raw('case when tiktok_username is null then "" else tiktok_username end as tiktok_username');
      $bio=DB::raw('case when bio is null then "" else bio end as bio');

      $filePath = config('constants.user_image') ;
      $image = DB::raw('case when concat(image) is null then "" else concat("'.$filePath.'",image) end as image') ;

      $profilePath = config('constants.profile_video') ;
      $profileVideo = DB::raw('case when concat(profile_video) is null then "" else concat("'.$profilePath.'/'.$userId.'/",profile_video) end as profile_video') ;
      $profileVideoThumb = DB::raw('case when concat(profile_video_thumbnail) is null then "" else concat("'.$profilePath.'/'.$userId.'/",profile_video_thumbnail) end as profile_video_thumbnail') ;
      
      $starImg = config('constants.star_image');
      $coverImg = config('constants.cover_image');

      $defaultCoverImg=$coverImg.'cover_photo.png' ;
      $cover_image = DB::raw('case when (cover_image is null || cover_image="" )  then "'.$defaultCoverImg.'" else concat("'.$coverImg.'",cover_image) end as cover_image') ;
     //DB::enableQueryLog();
      $userInfo = DB::table('users')->select('users.id','users.encryption','email','users.address',DB::raw('case when location is null then "" else location end as location'),DB::raw('case when countryId is null then 0 else countryId end as countryId'),'pe_countries.api_code',DB::raw('case when phoneNumber is null then "" else phoneNumber end as phoneNumber'),DB::raw('case when dob is null then "" else Date_Format(dob,"%d %b %Y") end as dob'),'name',$image,$cover_image,'username',$instaUrsername,$fbUrsername,$tiktokUrsername,'rank_type','rank_',$bio,'rt.rank_title',DB::raw('concat("'.$starImg.'",rt.star_img) as starImg'),'pv_type',$profileVideo,$profileVideoThumb,'isPrivate')
        ->leftJoin('rank_types as rt','rt.id','=','users.rank_type','users.address')
        ->leftJoin('pe_countries' ,'users.countryId','pe_countries.id')
        ->where('users.id',$userId)->where('users.isTrash',0)->first();
         $loginUserId = authguard()->id ;


      $loginUserId=authguard()->id ;

      if($userInfo->isPrivate==1 && $userId!=0){

          $checkFollowOrNot=User::checkFollowOrFollower($loginUserId,$userId);
          if($checkFollowOrNot){            
            $userInfo->isPrivate=0 ;
          }
      }

  //  echo $userInfo->rank_ ;
   // echo "<br>";
     $rankT = getRankType($userInfo->rank_);
   //  exit ;
      $rankImg = DB::table('rank_types')->select('rank_title',DB::raw('concat("'.$starImg.'",star_img) as starImg'))->where('id',$rankT)->where('status',1)->first() ;
      $userInfo->starImg=isset($rankImg->starImg)?$rankImg->starImg:'' ;
      $userInfo->rank_type=$rankT ;//
      $userInfo->rank_title=isset($rankImg->rank_title)?$rankImg->rank_title:'' ;
      
      $totalFollowers=User::getAppFollowersCount($userId);
      $userInfo->followers = $totalFollowers ;
      

      if($otherUser==1){          
           $userInfo->isFollow = User::checkFollowOrNot($loginUserId,$userId);
           $userInfo->isHost = User::checkHostOrNot($loginUserId,$userId); 
      }else{
          $userInfo->isFollow= 0 ;
          $userInfo->isHost = 0;
      }
      
      $about = DB::select("select ui.id,concat('#',ui.title) as title from user_interests_map as uim inner join user_interests as ui on ui.id=uim.interest_id where uim.user_id=".$userId);
      //$userInfo->about['interest'] = $about ;
      $userInfo->interest = $about ;
      $userInfo->isInterest = (count($about) > 0)?1:0 ;
      $userInfo->increase_rank = 0 ;
      $userAdvertisement = User::advertisement();
    //  $userInfo->advertisement = $userAdvertisement ;

      
      $totalFollows = DB::table('user_follows')->where('followed_user_id',$userId)->where('isAccept',1)->count();
      $userInfo->totalFollows = $totalFollows ; //isset($totalFollows->totalFollowscount)?(int)$totalFollows->totalFollowscount:0 ;
     
      $userInfo->shareUrl=config('constants.user_share').$userInfo->encryption  ;
      $totalPost = DB::table('posts')->select(DB::raw('count(*) as totalPost'))->where('userId',$userId)->where('status',1)->first() ;
      $totalLike = DB::table('post_likes')->select(DB::raw('id as totalLikes'))->where('status',1)->where('isLike',1)->where('user_id',$userId)->count() ;
      $totalComment = DB::table('post_comments')->select(DB::raw('count(*) as totalComments'))->where('status',1)->where('userId',$userId)->first() ;
      
      $userInfo->totalComment = isset($totalComment->totalComments)?$totalComment->totalComments:0 ;
      $userInfo->totalPosts = isset($totalPost->totalPost)?$totalPost->totalPost:0 ;
      $userInfo->totalLikes = isset($totalLike->totalLikes)?$totalLike->totalLikes:0 ;
      $userFollowers = User::getFollowers($userId);
      $userInfo->star_value = $userFollowers ;     
     

      $star_imgPath=config('constants.star_image') ;
      $filePath = config('constants.user_image') ;
      $image_ = DB::raw('case when concat("'.$filePath.'",users.image) is null then "" else concat("'.$filePath.'",users.image) end as image') ;
      $strImg=DB::raw('concat("'.$star_imgPath.'",star_img) as starImg');
     
      // $postList=Post::select('posts.id','posts.message','users.name',DB::raw('concat("@",username) as username'),'users.rank_type',$image_,$strImg,'posts.createdOn')->join('users','users.id','=','posts.userId')->join('rank_types','rank_types.id','=','users.rank_type')
      // ->where('posts.status',1)->where('userId',$userId)->where('users.isTrash',0)->get() ;

    
      $post_list = array();      
    
      //  $postLike = new Post_like();
      //  $postImage = new Post_image();
      //  $loginUserId = authguard()->id ;
      // if(!empty($postList)){
      //    foreach ($postList as $key => $value) {
      //       $postId = $value->id ;
      //       $postImgPath = config('constants.post_image').$postId.'/';           
      //       $post_image = $postImage->getPostImage($postId);
      //       $value->postImage = $post_image ;
      //       $value->isLike=$postLike->isLikeOrNot($postId,$loginUserId,0);      
      //       $totalComment = Post_comment::all()->where('postId',$postId)->count();
      //       $value->totalComment = $totalComment ; 
      //       $value->totalLike = $postLike->getTotalLike($postId);  
      //       $date = Carbon::parse($value->createdOn); // now date is a carbon instance
      //       $elapsed = $date->diffForHumans(Carbon::now());
      //       $elapsed=createdAt($elapsed) ;

      //       $value->createdOn =$elapsed ;
      //       $post_list[]=$value ;
            
      //    }
      // }

      //People Host start
      // DB::raw('concat("'.$starImg.'",rt.star_img) as starImg')
     
      $hostPeople = DB::select("select u.name,u.id,$image,case when u.image is null then '' else concat('".$filePath."',u.image) end as images,u.username,u.rank_,u.rank_type, case when u.country_code is null then '' else u.country_code end as country_code,u.countryId,concat('".$star_imgPath."',rt.star_img) as star_img from users as u inner join user_host as uh on uh.host_user_id=u.id inner join rank_types as rt on rt.id=u.rank_type where u.isTrash=0 and uh.isAccept=1 and userId=".$userId);
      $userInfo->host_people = $hostPeople ;

      // End
      $userInfo->post_list = $post_list ;   
     $totalPhoto=DB::table("post_images")->where("user_id",$userId)->where("file_type",1)->count();
     $totalVideo=DB::table("post_images")->where("user_id",$userId)->where("file_type",2)->count();
     $totalShare=DB::table("post_share")->where("user_id",$userId)->count();   
     
      $userInfo->checkOutMyWalk = array('total_photos'=>$totalPhoto,'total_followers'=>$totalFollowers,'total_videos'=>$totalVideo,'total_shares'=>$totalShare);
      $userInfo->connectWithMe = array('walkofweb_username'=>$userInfo->username,'insta_username'=>$userInfo->instagram_username,'facebook_username'=>$userInfo->facebook_username,'tiktok_username'=>$userInfo->tiktok_username);
      return $this->successResponse($userInfo,'User Detail',200);
     
    }

    public function userDetailPost(Request $request){

       $userId = $request->userId ; //authguard()->id ;
       $otherUser = 1 ;
       if($userId==0){
        $userId =authguard()->id ;
         $otherUser = 0 ;
       }
       $checkUser = DB::table('users')->where('id',$userId)->get()->toArray();
      
       if(empty($checkUser)){
         return $this->errorResponse(__('messages.user_detail_err'), 200);
       }


      $star_imgPath=config('constants.star_image') ;
      $filePath = config('constants.user_image') ;
      $image_ = DB::raw('case when concat("'.$filePath.'",users.image) is null then "" else concat("'.$filePath.'",users.image) end as image') ;
      $strImg=DB::raw('concat("'.$star_imgPath.'",star_img) as starImg');
     
      $page = $request->has('page') ? $request->get('page') : 1;
      $limit = $request->has('per_page') ? $request->get('per_page') : 10;
      $offset = ($page - 1) * $limit ;

      $postList=Post::select('posts.id','posts.message','users.name',DB::raw('concat("@",username) as username'),'users.rank_type',$image_,$strImg,'posts.createdOn')->join('users','users.id','=','posts.userId')->join('rank_types','rank_types.id','=','users.rank_type')
      ->where('posts.status',1)->where('userId',$userId)->where('users.isTrash',0)->orderBy('posts.id', 'desc')->skip($offset)->take($limit)->get() ;

    
      $post_list = array();      
      //$post_image = array() ;
       $postLike = new Post_like();
       $postImage = new Post_image();
       $loginUserId = authguard()->id ;
      if(!empty($postList)){
         foreach ($postList as $key => $value) {
            $postId = $value->id ;
            $postImgPath = config('constants.post_image').$postId.'/';           
            $post_image = $postImage->getPostImage($postId);
            $value->postImage = $post_image ;
            $value->isLike=$postLike->isLikeOrNot($postId,$loginUserId,0);      
            $totalComment = Post_comment::all()->where('postId',$postId)->count();
            $value->totalComment = $totalComment ; 
            $value->totalLike = $postLike->getTotalLike($postId);  
            $date = Carbon::parse($value->createdOn); // now date is a carbon instance
            $elapsed = $date->diffForHumans(Carbon::now());
            $elapsed=createdAt($elapsed) ;

            $value->createdOn =$elapsed ;
            $post_list[]=$value ;
            //$post_image[]=$postImage ;
         }
      }

       $totalRecord=Post::select('id')->where('posts.status',1)->where('userId',$userId)->count();      
      $data=array() ;
      $data['post_list']=$post_list ;
      if(($offset+$limit) < $totalRecord){
        $data['isShowMore']=true ;  
      }else{
        $data['isShowMore']=false ;  
      }

      $data['page']=$page ; 

      return $this->successResponse($data,'Post List',200);
    }
   
   public function following_request(Request $request){

      $validator = Validator::make($request->all(), [
      'following_userId' => 'required'
     ]);
    
      if($validator->fails()){       
          return $this->errorResponse($validator->errors()->first(), 200);
      }

      $userId = authguard()->id ;
      $checkFollowing=DB::table('user_follows')->where('followed_user_id',$userId)->where('follower_user_id',$request->following_userId)->first();
      

      $checkFollowing_=DB::table('user_follows')->where('followed_user_id',$request->following_userId)->where('follower_user_id',$userId)->first();

      if(!empty($checkFollowing) || !empty($checkFollowing_)){
        return $this->errorResponse(__('messages.user_following_exist'), 200);
      }

      DB::table('user_follows')->insert(["followed_user_id"=>$userId, "follower_user_id"=>$request->following_userId ,"isAccept"=>0]);

      return $this->successResponse([],__('messages.user_follow_req'),200);

   }

   public function accept_following(Request $request){
     
      requestLog(json_encode($request->all()));
      $validator = Validator::make($request->all(), [
      'type' => 'required',
      'following_userId' => 'required'
     ]);

    //0 >> Request Sent,1 >> Request Accept ,2 Remove,3>> pending,4>> unfollowing

      if($validator->fails()){       
          return $this->errorResponse($validator->errors()->first(), 200);
      }


      $type= $request->type ;
      $userId = authguard()->id ;

      $followingUserId = $request->following_userId ;
      
      if($request->following_userId==$userId){
        return $this->errorResponse("Invalid request", 200);
      }

      $userType_ = User::select('isPrivate')->where('id',$followingUserId)->first();//authguard()->isPrivate ;
      $userType=isset($userType_->isPrivate)?$userType_->isPrivate:0 ;
      //0 public , 1 private
      // if($type==0 || $type==1){
      //   if($userType==1){
      //   $type=0 ;
      //   }else{
      //     $type=1 ;
      //   }
      // }
      


      if($type==4){
        //upate status
        $checkFollowing=DB::table('user_follows')->where('followed_user_id',$userId)->where('follower_user_id',$request->following_userId)->delete();

        //->update(['isAccept'=>$type]);
        $message="messages.user_following_request_unfollow";
          $total_followers=User::getAppFollowersCount($request->following_userId);
       $isFollow=User::checkFollowOrNot($userId,$request->following_userId);
      return $this->successResponse(array("total_followers"=>$total_followers,"isFollow"=>$isFollow),__($message),200);
      
      }else if($type==2){
         $checkFollowing=DB::table('user_follows')->where('followed_user_id',$request->following_userId)->where('follower_user_id',$userId)->delete();
         $message="messages.user_following_request_remove";
           $total_followers=User::getAppFollowersCount($request->following_userId);
       $isFollow=User::checkFollowOrNot($userId,$request->following_userId);
      return $this->successResponse(array("total_followers"=>$total_followers,"isFollow"=>$isFollow),__($message),200);
      
      }else if($type==0){

        $checkFollowing=DB::table('user_follows')->select('id','isAccept')->where('followed_user_id',$userId)->where('follower_user_id',$request->following_userId)->first();

      $checkFollowing_=DB::table('user_follows')->select('isAccept')->where('followed_user_id',$request->following_userId)->where('follower_user_id',$userId)->first();

      $firstIsAccept=isset($checkFollowing->isAccept)?$checkFollowing->isAccept:null ;
      $updateId = isset($checkFollowing->id)?$checkFollowing->id:0 ;

        $total_followers=User::getAppFollowersCount($request->following_userId);
       $isFollow=User::checkFollowOrNot($userId,$request->following_userId);
          
            $message='messages.user_following_exist' ; 
        


      if($firstIsAccept==3){
        return $this->successResponse((object)array("total_followers"=>$total_followers,"isFollow"=>$isFollow),__($message), 200);
      }else if($firstIsAccept==1){

        DB::table('user_follows')->where("followed_user_id",$userId)->where("follower_user_id",$request->following_userId)->delete() ;
         $total_followers=User::getAppFollowersCount($request->following_userId);
        //update(["isAccept"=>0]); 
        return $this->successResponse(array("total_followers"=>$total_followers,"isFollow"=>0),__('messages.user_following_request_unfollow'),200);
  
      }else if(!empty($checkFollowing) && $firstIsAccept==0 && $userType==0){
         DB::table('user_follows')->where("followed_user_id",$userId)->where("follower_user_id",$request->following_userId)->update(["isAccept"=>1]); return $this->successResponse(array("total_followers"=>$total_followers,"isFollow"=>1),__('messages.user_following_request_public'),200);
      }else if(!empty($checkFollowing) && $firstIsAccept==0 && $userType==1){
        DB::table('user_follows')->where("followed_user_id",$userId)->where("follower_user_id",$request->following_userId)->update(["isAccept"=>3]); return $this->successResponse(array("total_followers"=>$total_followers,"isFollow"=>0),__('messages.user_following_request_private'),200);
      }

        if($userType==0){
          $isAccept=1 ;
          $message='messages.user_following_request_public' ;
           $this->sendNotifcation($userId,$request->following_userId,5);
        }else{
          $isAccept=3 ;
          $message='messages.user_following_request_private' ;
         $this->sendNotifcation($userId,$request->following_userId,4);
        }
         

      DB::table('user_follows')->insert(["followed_user_id"=>$userId, "follower_user_id"=>$request->following_userId ,"isAccept"=>$isAccept]);
   
      $total_followers=User::getAppFollowersCount($request->following_userId);
       $isFollow=User::checkFollowOrNot($userId,$request->following_userId);
      return $this->successResponse(array("total_followers"=>$total_followers,"isFollow"=>$isFollow),__($message),200);
      
      }else if($type==1){
       
       

      DB::table('user_follows')->where("followed_user_id",$request->following_userId)->where("follower_user_id",$userId)->update(["isAccept"=>1]) ;
     
      $total_followers=User::getAppFollowersCount($request->following_userId);
      $isFollow=User::checkFollowOrNot($userId,$request->following_userId);
      $this->sendNotifcation($userId,$request->following_userId,5);
      return $this->successResponse(array("total_followers"=>$total_followers,"isFollow"=>$isFollow),__('messages.user_following_req_accept'),200);
      }else{
        // $checkFollowing=DB::table('user_follows')->where('followed_user_id',$request->following_userId)->where('follower_user_id',$userId)->update(['isAccept'=>$type]);
      }

      // $isFollow=User::checkFollowOrNot($userId,$request->following_userId);
      // $total_followers=User::getAppFollowersCount($request->following_userId);
      return $this->successResponse([],__('Invalid Request'),200);

   }

   public function sendNotifcation($userId,$recieverId,$type){

        $getFirbaseToken=DB::table("device_token")->select("encryption","deviceToken","deviceType")->where('userId',$recieverId)->first();
        if(!empty($getFirbaseToken)){
          $firebaseT=isset($getFirbaseToken->deviceToken)?$getFirbaseToken->deviceToken:'';
           $deviceType=isset($getFirbaseToken->deviceType)?$getFirbaseToken->deviceType:1;
          $encryption=authguard()->encryption ;
         // isset($getFirbaseToken->encryption)?$getFirbaseToken->encryption:'';
          $usrName = User::select('name')->where('id',$userId)->first();
          $usrName_ = isset($usrName->name)?$usrName->name:'Someone' ;

          $receiverName = User::select('name')->where('id',$recieverId)->first();
          $receiverName_ = isset($receiverName->name)?$receiverName->name:'Someone' ;
         
          //add type 1 for home, 2 for Feed, 3 for notification , 4 for profile
          if($firebaseT!='' && $encryption!='' && $type==4){
             
             $title="Walkofweb";
             $msg=$usrName_." wants to follow you!!" ;
             $body=array(
                  "NotificationMgs"=> $msg,
                  "Action"=> 4,
                  "UserId"=>$encryption,
                  "user_id"=>$userId
                );

            sendPushNotification($firebaseT,$title,$body,$deviceType); 
            saveNF($recieverId,$title,$msg,json_encode($body),$hostId=0);
          }else if($firebaseT!='' && $encryption!='' && $type==5){
             $title="Walkofweb";
             //$msg=$usrName_." accepted your request!!" ;
             //$msg=$usrName_." started following you" ;
             $msg=$usrName_." accepted your follow request." ;
             $body=array(
                  "NotificationMgs"=> $msg,
                  "Action"=> 5,
                  "UserId"=>$encryption,
                  "user_id"=>$recieverId
                );

            sendPushNotification($firebaseT,$title,$body,$deviceType); 
            saveNF($recieverId,$title,$msg,json_encode($body),$hostId=0);
          }

           
        }
        
   }


   public function following_list(Request $request){

       $userId=isset($request->userId)?$request->userId:0 ;
       $follower_='follower_user_id' ;
       $uf='uf.followed_user_id' ;
       $loginUserId = authguard()->id ;
       if($userId==0){
        $userId = authguard()->id ;
        $follower_='followed_user_id' ;
        $uf='uf.follower_user_id' ;
       }

       
       $searchKey=isset($request->keyword)?$request->keyword:'' ;
      
       $userImgPath = config('constants.user_image');
       $starImg = config('constants.star_image');
       $bio = DB::raw('case when bio is null then "" else bio end as bio');
       $image = DB::raw('case when image is null then "" else concat("'.$userImgPath.'",image) end as image');
       $followingList = DB::table('user_follows as uf')->select('users.id','users.encryption','name','username',$bio,$image,'rank_type','rank_',DB::raw('case when (uf.followed_user_id='.$loginUserId.' and uf.isAccept=1) then 1 when (select id from user_follows where follower_user_id=uf.followed_user_id and followed_user_id='.$loginUserId.'
and isAccept=1) then 1 else 0 end as isFollowing'),DB::raw('concat("'.$starImg.'",rt.star_img) as starImg'))->where($follower_,$userId)      
       ->join('users', 'users.id', '=', $uf)   
       ->leftJoin('rank_types as rt','rt.id','=','users.rank_type')  
       ->where('isAccept','!=',4) 
        ->where('isTrash','=',0) 
        ->where('name','like','%'.$searchKey.'%') 
       ->get();
       
        return $this->successResponse($followingList,'Following List',200);
      
   }

   public function follower_list(Request $request){
       
      $userId=isset($request->userId)?$request->userId:0 ;
       if($userId==0){
         $userId = authguard()->id ;
       }

      
       $searchKey=isset($request->keyword)?$request->keyword:'' ;
       
       

       $userImgPath = config('constants.user_image');
       $starImg = config('constants.star_image');
       $bio = DB::raw('case when bio is null then "" else bio end as bio');
       $image = DB::raw('case when image is null then "" else concat("'.$userImgPath.'",image) end as image');

      // DB::enableQueryLog();

       $followingList = DB::table('user_follows')->select('users.id','users.encryption','name','username',$bio,$image,'rank_type','rank_',DB::raw('concat("'.$starImg.'",rt.star_img) as starImg'))->where('follower_user_id',$userId)      
       ->join('users', 'users.id', '=', 'user_follows.followed_user_id')
       ->leftJoin('rank_types as rt','rt.id','=','users.rank_type') 
       ->where('isAccept',1)  
        ->where('isTrash','=',0)  
      ->where('name','like','%'.$searchKey.'%') 
       ->get();
      
      $follower_str=array() ;

      if(!empty($followingList)){
        foreach ($followingList as $value) {
           $rankT = getRankType($value->rank_);
   //  exit ;
          $rankImg = DB::table('rank_types')->select('rank_title',DB::raw('concat("'.$starImg.'",star_img) as starImg'))->where('id',$rankT)->where('status',1)->first() ;
          $value->starImg=isset($rankImg->starImg)?$rankImg->starImg:'' ;
          $value->rank_type=$rankT ;           
           $follower_str[]=$value ;
        }
      }

       // print_r(DB::getQueryLog());
       //,DB::raw('case when isAccept=1 then 1 else 0 end as isFollowing')
        return $this->successResponse($follower_str,'Follower List',200);

   }

   public function following_request_list(Request $request){

       $userId=isset($request->userId)?$request->userId:0 ;
       if($userId==0){
        $userId = authguard()->id ;
       }
      

       
       $searchKey=isset($request->keyword)?$request->keyword:'' ;

       $userImgPath = config('constants.user_image');
       $starImg = config('constants.star_image');
       $bio = DB::raw('case when bio is null then "" else bio end as bio');
       $image = DB::raw('case when image is null then "" else concat("'.$userImgPath.'",image) end as image');
      // DB::enableQueryLog();
       $followingList = DB::table('user_follows')->select('users.id','users.encryption','name','username',$bio,$image,'rank_type','rank_','isAccept',DB::raw('concat("'.$starImg.'",rt.star_img) as starImg'))->where('follower_user_id',$userId)      
       ->join('users', 'users.id', '=', 'user_follows.followed_user_id')
        ->leftJoin('rank_types as rt','rt.id','=','users.rank_type') 
       ->where('isAccept',3)    
        ->where('name','like','%'.$searchKey.'%')  
       ->get();

       if(!empty($followingList)){

        foreach ($followingList as $key => $value) {

          $rankT = getRankType($value->rank_);
     
      $rankImg = DB::table('rank_types')->select('rank_title',DB::raw('concat("'.$starImg.'",star_img) as starImg'))->where('id',$rankT)->where('status',1)->first() ;
      $value->starImg=isset($rankImg->starImg)?$rankImg->starImg:'' ;
      $value->rank_type=$rankT ;//
      $value->rank_title=isset($rankImg->rank_title)?$rankImg->rank_title:'' ;
        }
        

    }
        


       // echo "<pre>";
       // print_r(DB::getQueryLog());
        return $this->successResponse($followingList,'Follower Request List',200);
   }

 
    public function check_rank_type(Request $request){
        
        $rank=$request->rank ;
        $rankType=0 ;

        //800 to 999 rank 1
        //600 to  799  rank 2
        //400 to 599 rank 3
        //200 to 399 rank 4
        // 1 to 199 rank 5

        if($rank > 800 && $rank < 1000){
          $rankType=1 ;
        }else if($rank > 600 && $rank < 800){
          $rankType=2 ;
        }else if($rank > 400 && $rank < 600){
          $rankType=3 ;
        }else if($rank > 200 && $rank < 400){
          $rankType=4 ;
        }else if($rank > 1 && $rank < 200){
          $rankType=5 ;
        }else{
          $rankType=0 ;
        }

        return $rankType ;
    }

     
 public function qrCode($userId=null){
      $shareUrl=env('PROFILE_SHARE').$userId ;
    
      $png= \QrCode::format('png')->size(100)->generate($shareUrl);
      $fileName='qrcode_'.$userId.'.png' ;
      
      $output_file = '/user_qrcode/'.$fileName;
      try{
        $impage_upload=  \Storage::disk('public')->put($output_file, $png);  
        if($impage_upload)
        {
          DB::table('users')->where('id',$userId)->update(['qr_code'=>$fileName]);  
          echo successResponse([],'QR Image Upload update in database successfully',200); 
        }
        else{
          return $this->errorResponse('Error occurred.'.$e, 201);
        }
    }catch(\Exception $e)
    {
      return $this->errorResponse('Error occurred.'.$e, 201);
    }
      
 }

 public function user_qrCodeDetail(Request $request){

       $userId = authguard()->id ;
       $encryption=authguard()->encryption ;
       $checkUsr=DB::table('users')->where('id',$userId)->first();
       if(empty($checkUsr)){
        return $this->errorResponse('This user id is not exist.', 200);
       }
       $userImgPath = config('constants.user_image');
       $userQrCode = config('constants.user_qrimage');
       $starImg = config('constants.star_image');  

       $img=DB::raw('case when image is null then "" else concat("'.$userImgPath.'",image) end as image');
      
       $qrCode=DB::raw('case when qr_code is null then "" else concat("'.$userQrCode.'",qr_code) end as qr_code');
       $userInfo = DB::table('users')->select('users.id','encryption','name','username','rank_type',$img,'rank_',$qrCode,DB::raw("concat('".$starImg."',rank_types.star_img) as starImg"))     
       ->leftjoin('rank_types','rank_types.id','=','users.rank_type')
       ->where('users.id',$userId)->first();
       $rankT = getRankType($userInfo->rank_);
      
   //  exit ;
      $rankImg = DB::table('rank_types')->select('rank_title',DB::raw('concat("'.$starImg.'",star_img) as starImg'))->where('id',$rankT)->where('status',1)->first() ;
      $userInfo->starImg=isset($rankImg->starImg)?$rankImg->starImg:'' ;
      $userInfo->rank_type=$rankT?$rankT:'' ;//
      
        $totalLoginUsrFollowers = User::getFollowersCount($userId);
     $userInfo->totalFollowers=(int)$totalLoginUsrFollowers ;
       
      $followingImg = DB::table('user_follows')->select('users.id',$img)->where('follower_user_id',$userId)      
       ->join('users', 'users.id', '=', 'user_follows.followed_user_id')      
       ->where('isAccept',1)
       ->limit(3)
       ->get();
       $userInfo->followerImg=$followingImg ;
     
       $profileShare = config('constants.user_share');
       $userInfo->inviteUrl = $profileShare.$userInfo->encryption;
       unset($userInfo->encryption);
       return $this->successResponse($userInfo,__('messages.user_qr_code'),200);

      
 }


 public function user_follower_list(Request $request){

   $validator = Validator::make($request->all(), [
      'userId' => 'required'
     ]);

    
    if($validator->fails()){       
      return $this->errorResponse($validator->errors()->first(), 200);
    }
    

    $searchKey=isset($request->keyword)?$request->keyword:'' ;
    $userId=$request->userId ;
    $loginUserId = authguard()->id ;
    $userImgPath = config('constants.user_image');
    $cond='' ;
    if($searchKey!=''){
      $cond=" and us.name like '%".$searchKey."%'" ;
    }
    
    $starImg = config('constants.star_image');
    $bio = DB::raw('case when bio is null then "" else bio end as bio');
    $image = DB::raw('case when image is null then "" else concat("'.$userImgPath.'",image) end as image');
    $country_code = DB::raw('case when country_code is null then "" else country_code end as country_code');
    $userFollowers=DB::select("select us.id as userId,isAccept, us.name,us.username,us.image,".$country_code.",".$bio.",".$image.",rank_type,rank_ from user_follows as uf inner join users as us on us.id=uf.followed_user_id where isAccept=1 and follower_user_id=".$userId.$cond);
    
    if(!empty($userFollowers)){

        foreach ($userFollowers as $key => $value) {

          $rankT = getRankType($value->rank_);
     
      $rankImg = DB::table('rank_types')->select('rank_title',DB::raw('concat("'.$starImg.'",star_img) as starImg'))->where('id',$rankT)->where('status',1)->first() ;
      $value->starImg=isset($rankImg->starImg)?$rankImg->starImg:'' ;
      $value->rank_type=$rankT ;//
      $value->rank_title=isset($rankImg->rank_title)?$rankImg->rank_title:'' ;
        }
        

    }


    return $this->successResponse($userFollowers,'Follower List',200);
 }

 public function sponser_detail(Request $request){

   $validator = Validator::make($request->all(), [
      'advertisementId' => 'required'
     ]);

    
    if($validator->fails()){       
      return $this->errorResponse($validator->errors()->first(), 200);
    }
    $sponserImg = config('constants.sponser_image');
    $advImg = config('constants.advertisement_image');
// DB::enableQueryLog();
    $advertisementDetail = DB::table("advertisements")->select(DB::raw('advertisements.id as advertiserId'),DB::raw('case when sponser.image is null then "" else concat("'.$sponserImg.'",sponser.image) end as sponserImg'),DB::raw('case when advertisements.image is null then 0 else concat("'.$advImg.'",advertisements.image) end as advImage'),'sponser.name','advertisements.title',DB::raw('concat(Date_Format(advertisements.start_date,"%d %M %Y")," to ",Date_Format(advertisements.end_date,"%d %M %Y")) as duration'),'advertisements.introduction','advertisements.objectives',
      'advertisements.target_audience','advertisements.media_mix','advertisements.conclusion','advertisements.media_sample')->where('advertisements.id',$request->advertisementId)
   ->join('sponser','sponser.id','=','advertisements.sponser_id')
    ->first();
 // print_r(DB::getQueryLog());
    return $this->successResponse($advertisementDetail,'Advertisement Detail',200);
 }

 public function social_connect(Request $request){
    // $response = 
    //   social connect

    // >> Tiktok , login url
    // >> Facebook,login url
    // >> Instagram,login url
    $userId = authguard()->id ;
    $encryptionKey = DB::table('users')->select('encryption')->where('id',$userId)->first();
    $encryption=$encryptionKey->encryption ;
    $soicalInfo = DB::table('social_media')->select('id','title',DB::raw('concat(login_url,"/","'.$encryption.'") as login_url'))->where('social_connect',1)->get();
    return $this->successResponse($soicalInfo,'Social Connect',200);
    
 }

 public function update_encryption(){
   $user=DB::table('posts')->get();
  

   if(!empty($user)){
     foreach ($user as $key => $value) {
      //print_r($value);
       $encryptionKey = md5('wow_intigate_23'.$value->id);
       $result= DB::table('posts')->where('id',$value->id)->update(['encryption'=>$encryptionKey]);
     
     }
     if($result)
     {
       echo "Encryption updated for user :".$value->id."<br>";
     }
     else{
       echo "Encryption not updated for user :".$value->id."<br>";
     }
   }
 }

 public function add_user_host(Request $request){

      $validator = Validator::make($request->all(), [
      'hostUserId' => 'required'
     ]);

    
    if($validator->fails()){       
      return $this->errorResponse($validator->errors()->first(), 200);
    }

      $hostUserId = $request->hostUserId ;
      $userId = authguard()->id ;
      $checkUser = DB::table('user_host')->select('id')->where('userId',$userId)->where('host_user_id',$hostUserId)->first();
      if(empty($checkUser)){
       $hostId=DB::table('user_host')->insertGetId(['userId'=>$userId,'host_user_id'=>$hostUserId]);

        
        $userN=User::getName($userId);  
        //$hostN=User::getName($hostUserId);    
        $insertData=array(
          "userId"=>$hostUserId ,
          "title"=>"Host Request" ,
          "hostId"=>$hostId ,
          "message"=>$userN." wants to add guest" ,
          "isAccept"=>0 ,
          "isSend"=>1 ,
          "status"=>1
        );
         
        DB::table('notifications')->insert($insertData);
      
       

        return $this->successResponse([],__('messages.user_add_host'),200);
      }else{       
        return $this->successResponse([],__('messages.user_host_exist'),200);
      }
 }

 public function remove_user_host(Request $request){
    $validator = Validator::make($request->all(), [
      'hostUserId' => 'required'
     ]);

    
    if($validator->fails()){       
      return $this->errorResponse($validator->errors()->first(), 200);
    }

      $hostUserId = $request->hostUserId ;
      $userId = authguard()->id ;      
      DB::table('user_host')->where('userId',$userId)->where('host_user_id',$hostUserId)->delete();
      return $this->successResponse([],__('messages.user_host_delete'),200);
 }


 public function accept_request_host(Request $request){

      $validator = Validator::make($request->all(), [
      'requestUserId' => 'required'
     ]);

    
    if($validator->fails()){       
      return $this->errorResponse($validator->errors()->first(), 200);
    }

      $requestUserId = $request->requestUserId ;
      $userId = authguard()->id ;
      $checkUser = DB::table('user_host')->where('host_user_id',$userId)->where('userId',$requestUserId)->first();
      if(!empty($checkUser)){
         DB::table('user_host')->where('host_user_id',$userId)->where('userId',$requestUserId)->update(['isAccept'=>1]);
        return $this->successResponse([],__('messages.user_accept_host'),200);
      }else{       
        return $this->successResponse([],'Invalid Request',200);
      }
 }

 public function country_list(Request $request){
    $countries = countryList();
    return $this->successResponse($countries,'Country list',200);
 }

 public function update_profile_videoLink($url,$type){
        $userId = authguard()->id ;
        DB::table('users')->where('id',$userId)->update(['pv_type'=>1,'profile_video'=>$url]);
         return $this->successResponse([],__('messages.user_profile_videoLink'),200);
 }


 public function profile_video(Request $request){

       try{
      $file=  $request->profile_video;
      
      if($request->hasFile('profile_video')){
        
       $userId=authguard()->id ;
        $imgPath='app/public/profile_video/'.$userId.'/' ;
        $filenamewithextension = $request->file('profile_video')->getClientOriginalName(); 
        $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
  
        //get file extension
        $extension = $request->file('profile_video')->getClientOriginalExtension();

        $allowedfileExtension=['mp4'];
        $check = in_array($extension,$allowedfileExtension);
        if(!$check){
          return $this->errorResponse('The '.$extension.' extension is not allowed', 200);
        }
     

        $filename=str_replace(' ', '_', $filename);
        $filenametostore = $filename.'_'.time().'.'.$extension;       
        $smallthumbnail = $filename.'_100_100_'.time().'.jpg';    
    
        //Upload File
      
       $file->move('public/profile_video/'.$userId.'/', $filenametostore);
        //$request->file('profile_video')->move('public/profile_video/'.$userId.'/', $filenametostore);
    
         $pvPath = config('constants.profile_video');
          $checkPV = DB::table('users')->select(DB::raw('case when profile_video is null then "" else profile_video end as profile_video'))->where('id',$userId)->first();
          
          if(isset($checkPV->profile_video) && $checkPV->profile_video!=''){
              $unlinkPath = storage_path($imgPath.$checkPV->profile_video) ;
              do_upload_unlink(array($unlinkPath));
          }

              
          $destination_path = storage_path('app/public').'/profile_video/'.$userId.'/';
         $thumbnail_path   = storage_path('app/public').'/profile_video/'.$userId.'/' ;
          $video_path       = $destination_path.$filenametostore; 

           VideoThumbnail::createThumbnail(
                storage_path('app/public').'/profile_video/'.$userId.'/'.$filenametostore, 
                storage_path('app/public').'/profile_video/'.$userId.'/', 
                $smallthumbnail, 
                2, 
                1920, 
                1035
                );
              

           $update=array(           
            'profile_video'=>$filenametostore,
            'profile_video_thumbnail'=>$smallthumbnail
           );

        User::where('id',$userId)->update($update);

      $file_path = url('/').'/public/profile_video/'.$userId.'/'.$filenametostore;
        return $this->successResponse(['image_url'=>$file_path],__('messages.user_profile_video_update'),200);

        
      }else{
            return $this->errorResponse('Invalid request.', 200);
        }

      }catch(\Exception $e)
        {
           return $this->errorResponse('Error occurred.'.$e, 200);
        }
        
 }     


   public function host_user_list(Request $request){

       $userId = authguard()->id ;
       $userImgPath = config('constants.user_image');      
      
       $query1="select u.id as userId,u.name,u.username,case when bio is null then '' else bio end as bio,case when image is null then '' else concat('".$userImgPath."',image) end as image,rank_type,rank_,case when uf.isAccept=1 then 1 else 0 end as isFollowing,case when uh.isAccept is null then 2 else uh.isAccept end as isUserHost from user_follows as uf inner join users as u on u.id=uf.follower_user_id left join user_host as uh on uh.userId=u.id where u.isTrash=0 and uf.isAccept=0 and followed_user_id=".$userId;

       $query2="select u.id as userId,u.name,u.username,case when bio is null then '' else bio end as bio,case when image is null then '' else concat('".$userImgPath."',image) end as image,rank_type,rank_,case when uf.isAccept=1 then 1 else 0 end as isFollowing,case when uh.isAccept is null then 2 else uh.isAccept end as isUserHost from user_follows as uf inner join users as u on u.id=uf.follower_user_id left join user_host as uh on uh.userId=u.id where u.isTrash=0 and uf.isAccept=1 and follower_user_id=".$userId;

       $query=$query1.' union '.$query2;
        $userHostList = DB::select($query);
        return $this->successResponse($userHostList,'User Host List',200);

   }

   public function user_dashboard(Request $request){

     $userId=authguard()->id ;
     $followersInfo = DB::table('social_info')->select('type',DB::raw('sum(followers_count) as totalCount'))->groupBy('type')->where('user_id',$userId)->get();
     return $this->successResponse($followersInfo,'User Dashboard',200);
     
   }

   public function cover_image(Request $request){


         $rules=[            
            'coverImage' => 'required'            
           ] ;

       try{

      if($request->hasFile('coverImage')) {
        $file= $request->file('coverImage');
       
        $imgPath='app/public/cover_image/' ;
        $this->removeCoverImage($request);
     
        $filenamewithextension = $request->file('coverImage')->getClientOriginalName();
  
        //get filename without extension
        $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
  
        //get file extension
        $extension = $request->file('coverImage')->getClientOriginalExtension();
  
        $filename=str_replace(' ', '_', $filename);
        $filenametostore = $filename.'_'.time().'.'.$extension;       
        $smallthumbnail = $filename.'_100_100_'.time().'.'.$extension;    
       
        //Upload File
        $request->file('coverImage')->storeAs('public/cover_image', $filenametostore);
        $file->move('public/cover_image/', $filenametostore);

      //  $request->file('coverImage')->storeAs('public/cover_image/thumb', $smallthumbnail);
       
         
        //create small thumbnail
       // $smallthumbnailpath = public_path('storage/cover_image/thumb/'.$smallthumbnail);
       // $this->createThumbnail($smallthumbnailpath, 100, 100);
      
        $userId = authguard()->id ;
           $update=array(           
            'cover_image'=>$filenametostore
           );
        User::where('id',$userId)->update($update);

        $file_path = config('constants.cover_image').$filenametostore;
        return $this->successResponse(['image_url'=>$file_path,"userId"=>$userId],__('messages.user_cover_msg'),200);

        }else{
            return $this->errorResponse('Invalid request.', 200);
        }

      }catch(\Exception $e)
        {
           return $this->errorResponse('Error occurred.'.$e, 200);
        }
    

    
   }

   public function sendContactUsEmail(Request $request){
 
       $name = $request->name ;
       $phoneNumber = $request->phone;
       $email=$request->email ;
       $subject= $request->subject;
       $message=$request->message ;


   
    $data = array(
      'baseUrl'=>URL('/')."/public/contactUs/visitorq.png",
      'name'=>$name,
      'email' => $email,
      'subject' =>  $subject,
      'phone_number'=>$phoneNumber ,
      'messages' => $message
      );
   
  
       Mail::send('emails.contactUs', $data, function($message) use ($data) {
        $to= 'walkofweb@gmail.com' ;
        $recieverName = "" ;
        $subject = $data['subject'] ;
          
          $message->to($to,$recieverName)->subject($subject);
          $message->bcc('amitshukla.intigate@gmail.com');        
      });

      if (Mail::failures()) {
       
        return false ;
       }else{
        return true ;
       
       }
   }

   public function advertisement_listing(Request $request){
    //$userId = authguard()->id ;
    $advImage = config('constants.sponser_image');
    $userId=isset($request->userId)?$request->userId:1 ;
    if($userId > 0){
      $cond=$userId ;
    }else{
      $cond=1 ;
    }
    

    $qry="select adv.id,sp.name,case when sp.image is null then '' else concat('".$advImage."',sp.image) end as sponserIcon,adv.introduction, case when CURDATE()  > Date(adv.end_date) then 'Expired' when adv.isAccept=0 then 'Pending' when adv.isAccept=1 then 'Approve' when adv.isAccept=2 then 'Rejected' else 'Expired' end as ads_status,case when CURDATE()  > Date(adv.end_date) then 3 else adv.isAccept end as isAccept from advertisements as adv left join sponser as sp on sp.id=adv.sponser_id where adv.createdBy!=4".$cond." and adv.isAccept=0 and sp.name is not null"  ; //.$userId ;
    // echo $qry;
    // die;
    
    $data=DB::Select($qry) ;
    

    return $this->successResponse($data,'Advertisement List',200);
  }

  public function removeProfileImage(Request $request){
    $userId = authguard()->id ;
    $profileImg='app/public/profile_image/';
    $image=DB::table('users')->select(DB::raw('case when image is null then "" else concat(image) end as image'))->where('id',$userId)->first();
    if(isset($image->image) && $image->image!=''){    
      $unlinkPath = storage_path($profileImg.$image->image) ;
      do_upload_unlink(array($unlinkPath));
   }
    DB::table('users')->where('id',$userId)->update(['image'=>'']);
    return $this->successResponse([],__('messages.user_remove_profile_img'),200);
  }


  public function removeCoverImage(Request $request){   
    $userId = authguard()->id ;
    $profileImg='app/public/cover_image/';
    $image=DB::table('users')->select(DB::raw('case when cover_image is null then "" else concat(cover_image) end as image'))->where('id',$userId)->first();
    if(isset($image->image) && $image->image!=''){    
      $unlinkPath = storage_path($profileImg.$image->image) ;
      do_upload_unlink(array($unlinkPath));
   }
    DB::table('users')->where('id',$userId)->update(['cover_image'=>'']);
    return $this->successResponse([],__('messages.user_remove_cover_img'),200);
  }

  public function advertisement_delete(Request $request){
      
      $validator = Validator::make($request->all(), [
        'id' => 'required'
       ]);
  
      
      if($validator->fails()){       
        return $this->errorResponse($validator->errors()->first(), 200);
      }

      $updatedId = $request->id ;

      DB::table('advertisements')->where('id',$updatedId)->update(['isAccept'=>4]);
      return $this->successResponse([],__('messages.user_advertisement_delete'),200);
  }
  
  public function advertisement_edit(Request $request){
    $validator = Validator::make($request->all(), [
      'updatedId' => 'required',
      'ads_title' => 'required',
      'introduction'=>'required',
      'objectives' => 'required',
      'media_mix' => 'required',
      'conclusion'=> 'required',
      'target_audience'=>'required'
     ]);

    
    if($validator->fails()){       
      return $this->errorResponse($validator->errors()->first(), 200);
    }

    $updatedId = $request->updatedId ;
    $upatedData=array(
      "title"=>$request->ads_title,
      "introduction"=>$request->introduction,
      "objectives"=>$request->objectives,
      "media_mix"=>$request->media_mix,
      "conclusion"=>$request->conclusion,
      "target_audience"=>$request->target_audience
    );
    
    DB::table('advertisements')->where('id',$updatedId)->update($upatedData);
    return $this->successResponse([],__('messages.user_adv_edit_succ'),200);

  }

  public function is_private_account(Request $request){
    $validator = Validator::make($request->all(), [
      'isPrivate' => 'required'     
     ]);

    
    if($validator->fails()){       
      return $this->errorResponse($validator->errors()->first(), 200);
    }

    $isPrivate = $request->isPrivate ;    
    $userId = authguard()->id ;
    $qry="update users set isPrivate=".$isPrivate." where id=".$userId ;
    DB::select($qry);
    return $this->successResponse([],__('messages.user_private_account'),200);
  }

   public function user_host_list(Request $request){

       $userId = authguard()->id ;
       $userImgPath = config('constants.user_image');      
       $userName = isset($request->username)?$request->username:'' ;
       $searchCond='' ;

       if($userName!=''){
        $userName=" and u.username='".$userName."'" ;
       }

       $starImg = config('constants.star_image');   

       $query1="select u.id as userId,u.name,u.username,case when bio is null then '' else bio end as bio,case when image is null then '' else concat('".$userImgPath."',image) end as image,concat('".$starImg."',rt.star_img) as starImg,rank_type,rank_ from user_host as uh inner join users as u on u.id=uh.host_user_id left join rank_types as rt on rt.id=u.rank_type where u.isTrash=0 and uh.isAccept=1 and uh.userId=".$userId;

        $userHostList = DB::select($query1);
        return $this->successResponse($userHostList,'Host List',200);

   }

   public function host_user(Request $request){

  $userId = authguard()->id ;
  $userImgPath = config('constants.user_image'); 
  $starImg = config('constants.star_image');     
  $userName = isset($request->username)?$request->username:'' ;
  $searchCond='' ;

  if($userName!=''){
    $checkUsrName=substr(strtolower($userName), 0, 3);
    if($checkUsrName!='wow'){
      return $this->errorResponse("Please enter valid username.", 200);
    }

  }

  if($userName!=''){
   $searchCond=" and u.username like '%".$userName."%'" ;
  }

  //case when uh.isAccept is null then 2 else uh.isAccept end as isUserHost,
//left join user_host as uh on uh.userId=u.id
  $query1="select u.id as userId,u.name,u.username,case when bio is null then '' else bio end as bio,case when image is null then '' else concat('".$userImgPath."',image) end as image,rank_type,rank_,case when uf.isAccept=1 then 1 else 0 end as isFollowing,concat('".$starImg."',rt.star_img) as starImg from user_follows as uf inner join users as u on u.id=uf.follower_user_id  left join rank_types as rt on rt.id=u.rank_type where u.isTrash=0  and u.id!=".$userId." and uf.isAccept=1 and followed_user_id=".$userId.$searchCond ;
//case when uh.isAccept is null then 2 else uh.isAccept end as isUserHost,
  //left join user_host as uh on uh.userId=u.id
  $query2="select u.id as userId,u.name,u.username,case when bio is null then '' else bio end as bio,case when image is null then '' else concat('".$userImgPath."',image) end as image,rank_type,rank_,case when uf.isAccept=1 then 1 else 0 end as isFollowing,concat('".$starImg."',rt.star_img) as starImg from user_follows as uf inner join users as u on u.id=uf.followed_user_id  left join rank_types as rt on rt.id=u.rank_type where u.isTrash=0 and uf.isAccept=1 and u.id!=".$userId." and follower_user_id=".$userId.$searchCond;
 //
   $query=$query1.' union '.$query2 ;
   $userHostList = DB::select($query);
   return $this->successResponse($userHostList,'Host User',200);

}

public function facebook_login(Request $request){


    try
    {   
        $rules=[         
        'social_id'=>'required',
        'deviceType' => 'required|numeric',
        'user_type'=>  'required|numeric|min:2|max:4'          
       ];

        $validatedData = Validator::make($request->all(),$rules);
  
        
    if($validatedData->fails()){       
        return $this->errorResponse($validatedData->errors()->first(), 200);
      }
     
      $name=$request->name ;
      $usrName = substr($name,0,2);
            
      // new

      $request['name'] = $request->name;  
      $request['social_id'] = $request->social_id;
      $request['registration_from'] = $request->deviceType ;
      $request['rank_type'] = rand(1,5);
      $request['rank_'] = rand(1,30);     
      $request['username'] ='' ; //$userName;
      $request['user_type'] =$request->user_type ;
      //$request['password']=Hash::make($request['password']);
      $request['remember_token'] = Str::random(10);
      
      $checkFbId = DB::table('users')->select('id')->where('social_id',$request->social_id)->first();
    
     
      $updatedId=0 ;
      if(!empty($checkFbId)){
        $updatedId=$checkFbId->id;
      } else{
       
          $validatedData = Validator::make($request->all(),[              
            'email' => 'email|unique:users',
            'phoneNumber' => 'numeric|unique:users'            
           ]);
           
            if($validatedData->fails()){ 
            if(isset($request->email) && $request->email!=""){
               $checkExistEmail=User::where('email',$request->email)->first();
                $updatedId=isset($checkExistEmail->id)?$checkExistEmail->id:0 ;
            }

            if(isset($request->phoneNumber) && $request->phoneNumber!=""){

              $checkExistPhone=User::where('phoneNumber',$request->phoneNumber)->first();
               $updatedId=isset($checkExistPhone->id)?$checkExistPhone->id:0 ;
            }
           

            }
        
      }
  //    echo "hello";

  // exit ;
      // "name"=>$request->name,
      if($updatedId > 0){
        $updateData=array(         
          "registration_from"=>$request->deviceType,
          "remember_token"=>Str::random(10),
          'social_id'=>$request->social_id
        );
        // echo "<pre>";
        // print_r($updateData);
        User::where('id',$updatedId)->update($updateData);
        $user = User::where('id',$updatedId)->first();
        $request['password']='walkofweb@123' ;
        Hash::check($request->password, $user->password);
        $token = $user->createToken('walkofweb token')->accessToken;
        $insertId=$updatedId;  
       
      }else{
        $request['password']=Hash::make('walkofweb@123');
        $user = User::create($request->toArray());
        $insertId=$user->id;
        $token = $user->createToken('walkofweb token')->accessToken;
        $userName = $this->UsernameGenerate($usrName,$insertId); 
        $encryptionKey = md5('wow_intigate_23'.$insertId);    
       
        DB::table('users')->where('id',$insertId)->update(['encryption'=>$encryptionKey,'username'=>$userName]);

         $imagick=config('constants.imagick');
        if($imagick==1){
           $this->qrCode($encryptionKey);
        }
      }
      //exit ;
      $message = "Successfull signup" ;
      
      
     
     
      $data['token'] = $token  ;
      return $this->successResponse($data,$message,200);    

    }
    catch(\Exception $e)
    {
       return $this->errorResponse(__('messages.user_social_login_msg'.$e), 200);
    }

  }

    public function checkPrivate(){
    $userId = authguard()->id ;
    $userInfo= User::select('isPrivate')->where('id',$userId)->first();
    return $this->successResponse($userInfo,__('messages.user_check_private_ac'),200); 
  }

  public function getUser_Data(){
    $userId = authguard()->id ;
   
     $starImg = config('constants.star_image');
    $filePath = config('constants.user_image') ;
    $image = DB::raw('case when concat(image) is null then "" else concat("'.$filePath.'",image) end as image') ;
    $userInfo= User::select('users.username','users.name','rank_',DB::raw('case when users.countryId is null then 0 else users.countryId end as countryId'),$image,DB::raw('case when title is null then "" else title end as country'),DB::raw('case when phoneNumber is null then "" else phoneNumber end phoneNumber'),'email',DB::raw('case when country_code is null then 0 else country_code end as country_code'),'isPrivate')->where('users.id',$userId)
    ->leftJoin('pe_countries','pe_countries.id','=','users.countryId')
    ->first();
  
    if($userInfo->rank_==null){
      $userInfo->rank_=0 ;
    }
     $rankT = getRankType($userInfo->rank_);
     
      $rankImg = DB::table('rank_types')->select('rank_title',DB::raw('concat("'.$starImg.'",star_img) as starImg'))->where('id',$rankT)->where('status',1)->first() ;
      $userInfo->starImg=isset($rankImg->starImg)?$rankImg->starImg:'' ;
      $userInfo->rank_type=$rankT ;//
      $userInfo->rank_title=isset($rankImg->rank_title)?$rankImg->rank_title:''; 


    return $this->successResponse($userInfo,"Get User data",200); 
  }
  
   public function sendRegistrationEmail($name='',$email='',$password='123456'){
  
   //Request $request
       $name = 'Walkof Live' ;      
       $email='pooja.dhamija11@gmail.com' ;

       $subject= 'Account Registration';
   
    $data = array(
      'image'=>URL('/')."/public/contactUs/visitorq.png",
      'name'=>$name,
      'email' => $email,
      'subject' =>  $subject,
      'password'=>$password
      );
     
   //echo view('emails.registration',$data);
   //exit ;
       $mail=Mail::send('emails.registration', $data, function($message) use ($data) {

     
        $to=$data['email'] ;
        $recieverName = $data['name'] ;
        $subject = $data['subject'] ;
          
          $message->to($to,$recieverName)->subject($subject);
          //$message->bcc('amitshukla.intigate@gmail.com');        
      });
      dd($mail);
      


      if (Mail::failures()) {
       
        return false ;
       }else{
        return true ;
       
       }
   }

   public function firebaseToken(Request $request){

      $userId=authguard()->id ;
      $firebaseToken = $request->firebase_token ;
      try{
       
         DB::table('users')->where('id',$userId)->update(['firebase_token'=>$firebaseToken]);
        return $this->successResponse([],__('messages.user_firebase_token'),200); 
      }
      catch(\Exception $e)
      {
         return $this->errorResponse(__('messages.user_firebase_err'), 200);
      }

   }

    public function userProfileShare(Request $request){
    
        $userId=isset($_GET['userId'])?$_GET['userId']:0 ;
       // $userInfo=User::select('id')->where('encryption',$userId)->first();
        $userId = isset($userInfo->id)?$userInfo->id:0 ;
       
        $checkDevice=checkDevice();
     
        echo "<meta property='og:image' content='https://www.walkofweb.in/public/assets/images/logo.png' />";
        
       
      
        if($checkDevice['isIOS']==1){
          $data['redirectURL']="https://www.google.co.in/" ; 
          $data['deviceType']="IOS";
        }else if($checkDevice['isAndroid']==1){
          $data['redirectURL']="https://www.google.co.in/" ; 
          $data['deviceType']="Android";
      return Redirect::to('https://play.google.com/store/apps/details?id=in.walkofweb&hl=en-IN');
        }else{
          $data['redirectURL']="https://walkofweb.in/" ;   
          $data['deviceType']="Desktop";
          return Redirect::to('https://play.google.com/store/apps/details?id=in.walkofweb&hl=en-IN');          
        }
        die;
        echo "<pre>";
        print_r($data);
    }

    public function categoryWiseUser(Request $request){
        $rankTypes=$request->rankType ;
         $rankTypes=DB::select("select * from rank_types where id=".$rankTypes)->first();

      if(!empty($rankTypes)){
       
          $from = $rankTypes->range_from ;
          $to = $rankTypes->range_to ;
          $id = $rankTypes->id ;

          $getUserByRankType=DB::select("SELECT * FROM users WHERE rank_ BETWEEN $from AND $to  order by rank_ desc limit 4")->get() ;         
      }
    }
    
    public function new_userList(Request $request){

      //filter
      //social presence 2-facebook,3-instagram,4-tiktok,5-walkofweb

       $validator = Validator::make($request->all(), [
        'countryId' => 'array',
        'interest' => 'array', 
        'to_rank' => 'numeric',
        'from_rank' => 'numeric' ,
        'to_followers' => 'numeric',
        'from_follwers' => 'numeric' ,
        'social_presence' => 'array',
        'quick_filter'=>'array'

       ]);

    //quick_filter":[{"type":1,"id":5}]
    if($validator->fails()){       
        return $this->errorResponse($validator->errors()->first(), 200);
    }
     $userId=authguard()->id ;
    // echo 'userList_'.$userId ;
    //  $userListRedis = Redis::get('userList_'.$userId);
    //  dd($userListRedis);
    // if(!isset($userListRedis)) {
        
    //   return $this->successResponse(json_decode($userListRedis, FALSE),'User List',200);
   
    // }else{

    $page = $request->has('page') ? $request->get('page') : 1;
     $limit = 100 ; //$request->has('per_page') ? $request->get('per_page') : 10;
    //$offset = ($page - 1) * $limit ;

      $searchKey=$request->input('search_keyword') ;
      $searchKeyword='';
      if($searchKey!=''){
        $searchKeyword=" and name like '%".$searchKey."%'" ;
      }

      $userId = authguard()->id ;
      $country=$request->countryId ;
      $interest=$request->interest ;
      $to_rank=$request->from_rank ;
      $from_rank=$request->to_rank ;
      $to_followers=$request->to_followers ;
      $from_follwers=$request->from_follwers ;
      $social_presence=$request->social_presence ;

      $filePath = config('constants.user_image') ;
      $advPath = config('constants.advertisement_image') ;
      $sPath = config('constants.sponser_image') ;

     $user_interest=array() ;
     $usr = array();
     $socialInfo = array() ;
     $filter="";
     $follower_filter="" ;

      if(!empty($interest)){
        $user_interest = DB::table("user_interests_map")->select(DB::raw("GROUP_CONCAT(user_id) as userId"))->whereIn('interest_id',$interest)->get()->toArray();
      }
      
      if(!empty($user_interest) and isset($user_interest[0]->userId) and $user_interest[0]->userId > 0){
        $usr=explode(',', $user_interest[0]->userId); 
        $filter=" and id in (".$user_interest[0]->userId.")" ;
      }

      if(!empty($country)){
        $filter=$filter." and countryId in (".implode(',',$country).")";
      }

      if($to_rank!=0 and $from_rank!=0){
        $filter=$filter." and rank_ between ".$to_rank." and ".$from_rank ;
      }else if($to_rank!=0){
        $filter=$filter." and rank_ >=".$to_rank ;
      }else if($from_rank!=0){
        $filter=$filter." and rank_ <=".$from_rank ;
      }
      
      if($to_followers!=0 and $from_follwers!=0){
        $follower_filter=" and total_followersCount between ".$to_followers." and ".$from_follwers ;
      }

      if(!empty($social_presence)){      
        $socialInfo = DB::select("select * from (select GROUP_CONCAT(distinct user_id) as userId,sum(follows_count) as total_followsCount,sum(followers_count) as total_followersCount,status from social_info where status=1 and social_type in (".implode(',',$social_presence).")) as followers where status=1 ".$follower_filter);
      }

      if(empty($social_presence) and $to_followers!=0 and $from_follwers!=0){
        $socialInfo = DB::select("select * from (select GROUP_CONCAT(distinct user_id) as userId,sum(follows_count) as total_followsCount,sum(followers_count) as total_followersCount,status from social_info where status=1) as followers where status=1  ".$follower_filter);
      }

      if(!empty($socialInfo) && isset($socialInfo[0]->userId) && $socialInfo[0]->userId > 0){
        $filter=$filter." and id in (".$socialInfo[0]->userId.")";
      }
            
      $countryCode = DB::raw('case when country_code is null then "" else country_code end as country_code');
     
      // New work
      $star_imgPath=config('constants.star_image') ;
      $rankTypes = DB::table('rank_types')->select('id','rank_title','range_from','range_to',DB::raw('concat("'.$star_imgPath.'",star_img) as starImg'))->orderBy('range_to', 'DESC')->get();
    
       $data['list']=array();

      if(!empty($rankTypes)){
        foreach ($rankTypes as $key => $value) {
          $from = $value->range_from ;
          $to = $value->range_to ;
          $id = $value->id ;
          $title = $value->rank_title ;
          $starImg = $value->starImg ;
          //id!=".$userId." and
        

           $list=DB::select("select id,encryption,name,rank_,rank_type,case when image is null then '' else concat('".$filePath."',image) end as image,case when countryId is null then 0 else countryId end as countryId,$countryCode,concat('".$starImg."') as star_img from users where  isTrash=0 and isFeatured=0 and rank_ BETWEEN ".$from." AND ".$to."   ".$filter.$searchKeyword." order by rank_ desc limit 4");
           if(empty($list)) continue ;
          $result=array("id"=>$id ,"rank_title"=>$title,"starImg"=>$starImg ,"user_list"=>(array)$list);
          $data['list'][]=$result ;
        }  
      }
      
      $totalFeatureUser=0 ; //count($featureUser);
      $totalRecord=array();
      $image = DB::raw('case when concat("'.$filePath.'",image) is null then "" else concat("'.$filePath.'",image) end as image') ;
      $totalLoginUsrFollowers = User::getFollowersCount($userId);
      $loginUsrRank = User::getUserRank($userId);
      $data['featured_user']=array('total_followers'=>$totalLoginUsrFollowers,'rank_'=>$loginUsrRank) ;
      $data['total_feature']=$totalFeatureUser ;
      $advertisement=User::advertisement();
      $rankType = array() ;
      $data['advertisement']=$advertisement ;     
      $totalRecord=count($totalRecord) ;

      // if(($offset+$limit) < $totalRecord){
      //   $data['isShowMore']=true ;  
      // }else{
      //   $data['isShowMore']=false ;  
      // }

      $data['isShowMore']=false ;
      $data['page']=$page ;    
      $data['unread_notification_count'] = DB::table('notifications')->where('isRead',0)->where('status',1)->where('isSend',1)->where('userId',$userId)->count();
       //Redis::set('userList_'.$userId, json_encode($data));
        return $this->successResponse($data,'User List',200);
      //}
     
      // DB::enableQueryLog();
      //        $query = DB::getQueryLog();
      // print_r($query);
    
    }



    public function new_userList123(Request $request){
     

      //filter
      //social presence 2-facebook,3-instagram,4-tiktok,5-walkofweb

       $validator = Validator::make($request->all(), [
        'countryId' => 'array',
        'interest' => 'array', 
        'to_rank' => 'numeric',
        'from_rank' => 'numeric' ,
        'to_followers' => 'numeric',
        'from_follwers' => 'numeric' ,
        'social_presence' => 'array',
        'quick_filter'=>'array'

       ]);

    //quick_filter":[{"type":1,"id":5}]
    if($validator->fails()){       
        return $this->errorResponse($validator->errors()->first(), 200);
    }
     $userId=authguard()->id ;
    //  echo 'userList_'.$userId ;
    //  die;
    //  $userListRedis = Redis::get('userList_'.$userId);
    
    // if(!isset($userListRedis)) {
        
    //   return $this->successResponse(json_decode($userListRedis, FALSE),'User List',200);
   
    // }else{

    $page = $request->has('page') ? $request->get('page') : 1;
     $limit = 100 ; //$request->has('per_page') ? $request->get('per_page') : 10;
    //$offset = ($page - 1) * $limit ;

      $searchKey=$request->input('search_keyword') ;
      $searchKeyword='';
      if($searchKey!=''){
        $searchKeyword=" and name like '%".$searchKey."%'" ;
      }

      $userId = authguard()->id ;
      $country=$request->countryId ;
      $interest=$request->interest ;
      $to_rank=$request->from_rank ;
      $from_rank=$request->to_rank ;
      $to_followers=$request->to_followers ;
      $from_follwers=$request->from_follwers ;
      $social_presence=$request->social_presence ;

      $filePath = config('constants.user_image') ;
      $advPath = config('constants.advertisement_image') ;
      $sPath = config('constants.sponser_image') ;
      $dummy_image = config('constants.dummy_image') ;
      
     $user_interest=array() ;
     $usr = array();
     $socialInfo = array() ;
     $filter="";
     $follower_filter="" ;

      if(!empty($interest)){
        $user_interest = DB::table("user_interests_map")->select(DB::raw("GROUP_CONCAT(user_id) as userId"))->whereIn('interest_id',$interest)->get()->toArray();
      }
      
      if(!empty($user_interest) and isset($user_interest[0]->userId) and $user_interest[0]->userId > 0){
        $usr=explode(',', $user_interest[0]->userId); 
        $filter=" and id in (".$user_interest[0]->userId.")" ;
      }

      if(!empty($country)){
        $filter=$filter." and countryId in (".implode(',',$country).")";
      }

      if($to_rank!=0 and $from_rank!=0){
        $filter=$filter." and rank_ between ".$to_rank." and ".$from_rank ;
      }else if($to_rank!=0){
        $filter=$filter." and rank_ >=".$to_rank ;
      }else if($from_rank!=0){
        $filter=$filter." and rank_ <=".$from_rank ;
      }
      
      if($to_followers!=0 and $from_follwers!=0){
        $follower_filter=" and total_followersCount between ".$to_followers." and ".$from_follwers ;
      }
       
      
        
      if(!empty($social_presence)){      
        $socialInfo = DB::select("select * from (select GROUP_CONCAT(distinct user_id) as userId,sum(follows_count) as total_followsCount,sum(followers_count) as total_followersCount,status from social_info where status=1 and social_type in (".implode(',',$social_presence).")) as followers where status=1 ");
      }
   
      if(empty($social_presence) and $to_followers!=0 and $from_follwers!=0){
        $socialInfo = DB::select("select * from (select GROUP_CONCAT(distinct user_id) as userId,sum(follows_count) as total_followsCount,sum(followers_count) as total_followersCount,status from social_info where status=1) as followers where status=1  ".$follower_filter);

      }
     
    
      if(!empty($socialInfo) && isset($socialInfo[0]->userId) && $socialInfo[0]->userId > 0){
        $filter=$filter." and id in (".$socialInfo[0]->userId.")";
      }
            
       $countryCode = DB::raw('case when country_code is null then "" else country_code end as country_code');
     
     
      // New work
      $star_imgPath=config('constants.star_image') ;
      $rankTypes = DB::table('rank_types')->select('id','rank_title','range_from','range_to',DB::raw('concat("'.$star_imgPath.'",star_img) as starImg'))->orderBy('range_to', 'DESC')->get();
    
    
       $data['list']=array();
     

      if(!empty($rankTypes)){
        foreach ($rankTypes as $key => $value) {
          $from = $value->range_from ;
          $to = $value->range_to ;
          $id = $value->id ;
          $title = $value->rank_title ;
          $starImg = $value->starImg ;
         //id!=".$userId." and
          echo "select id,case when id=$userId then 1 else 0 end as isLoginUser,encryption,name,rank_,rank_type,case when image is  nullthen '' else concat('".$filePath."',image) end as image,case when countryId is null then 0 else countryId end as countryId,$countryCode,concat('".$starImg."') as star_img from users where  isTrash=0 and isFeatured=0 and rank_ BETWEEN ".$from." AND ".$to."   ".$filter.$searchKeyword." order by rank_ desc limit 4";
          die;
         
           $list=DB::select("select id,case when id=$userId then 1 else 0 end as isLoginUser,encryption,name,rank_,rank_type,case when image is  nullthen '' else concat('".$filePath."',image) end as image,case when countryId is null then 0 else countryId end as countryId,$countryCode,concat('".$starImg."') as star_img from users where  isTrash=0 and isFeatured=0 and rank_ BETWEEN ".$from." AND ".$to."   ".$filter.$searchKeyword." order by rank_ desc limit 4");

           if(empty($list)) continue ;
          $result=array("id"=>$id ,"rank_title"=>$title,"starImg"=>$starImg ,"user_list"=>(array)$list);
          $data['list'][]=$result ;
        }  
      }
     
      $totalFeatureUser=0 ; //count($featureUser);
      $totalRecord=array();
      $image = DB::raw('case when concat("'.$filePath.'",image) is null then "" else concat("'.$filePath.'",image) end as image') ;
    
      $totalLoginUsrFollowers = User::getFollowersCount($userId);
    
     
      $loginUsrRank = User::getUserRank($userId);
      $data['featured_user']=array('total_followers'=>$totalLoginUsrFollowers,'rank_'=>$loginUsrRank) ;
     
      $data['total_feature']=$totalFeatureUser ;
      $advertisement=User::advertisement();
      $rankType = array() ;
      $data['advertisement']=$advertisement ;     
      $totalRecord=count($totalRecord) ;

      // if(($offset+$limit) < $totalRecord){
      //   $data['isShowMore']=true ;  
      // }else{
      //   $data['isShowMore']=false ;  
      // }

      $data['isShowMore']=false ;
      $data['page']=$page ;    
      $data['unread_notification_count'] = DB::table('notifications')->where('isRead',0)->where('status',1)->where('isSend',1)->where('userId',$userId)->count();
    
       //Redis::set('userList_'.$userId, json_encode($data));
        return $this->successResponse($data,'User List',200);
      //}
     
      // DB::enableQueryLog();
      //        $query = DB::getQueryLog();
      // print_r($query);
   // }
    
    }

    public function userListByRank(Request $request){

      //filter
      //social presence 2-facebook,3-instagram,4-tiktok,5-walkofweb

       $validator = Validator::make($request->all(), [
        'countryId' => 'array',
        'interest' => 'array', 
        'to_rank' => 'numeric',
        'from_rank' => 'numeric' ,
        'to_followers' => 'numeric',
        'from_follwers' => 'numeric' ,
        'social_presence' => 'array',
        'quick_filter'=>'array',
        'rank_type'=>'required|numeric'

       ]);

    //quick_filter":[{"type":1,"id":5}]
    if($validator->fails()){       
        return $this->errorResponse($validator->errors()->first(), 200);
    }

   
    $page = $request->has('page') ? $request->get('page') : 1;
     $limit = $request->has('per_page') ? $request->get('per_page') : 10;
    $offset = ($page - 1) * $limit ;

      $searchKey=$request->input('search_keyword') ;
      $searchKeyword='';
      if($searchKey!=''){
        $searchKeyword=" and name like '%".$searchKey."%'" ;
      }

      $userId = authguard()->id ;
      $country=$request->countryId ;
      $interest=$request->interest ;
      $to_rank=$request->from_rank ;
      $from_rank=$request->to_rank ;
      $to_followers=$request->to_followers ;
      $from_follwers=$request->from_follwers ;
      $social_presence=$request->social_presence ;

      $filePath = config('constants.user_image') ;
      $advPath = config('constants.advertisement_image') ;
      $sPath = config('constants.sponser_image') ;

     $user_interest=array() ;
     $usr = array();
     $socialInfo = array() ;
     $filter="";
     $follower_filter="" ;

      if(!empty($interest)){
        $user_interest = DB::table("user_interests_map")->select(DB::raw("GROUP_CONCAT(user_id) as userId"))->whereIn('interest_id',$interest)->get()->toArray();
      }
      
      if(!empty($user_interest) and isset($user_interest[0]->userId) and $user_interest[0]->userId > 0){
        $usr=explode(',', $user_interest[0]->userId); 
        $filter=" and id in (".$user_interest[0]->userId.")" ;
      }

      if(!empty($country)){
        $filter=$filter." and countryId in (".implode(',',$country).")";
      }

      if($to_rank!=0 and $from_rank!=0){
        $filter=$filter." and rank_ between ".$to_rank." and ".$from_rank ;
      }else if($to_rank!=0){
        $filter=$filter." and rank_ >=".$to_rank ;
      }else if($from_rank!=0){
        $filter=$filter." and rank_ <=".$from_rank ;
      }
      
      if($to_followers!=0 and $from_follwers!=0){
        $follower_filter=" and total_followersCount between ".$to_followers." and ".$from_follwers ;
      }

      if(!empty($social_presence)){      
        $socialInfo = DB::select("select * from (select GROUP_CONCAT(distinct user_id) as userId,sum(follows_count) as total_followsCount,sum(followers_count) as total_followersCount,status from social_info where status=1 and social_type in (".implode(',',$social_presence).")) as followers where status=1 ".$follower_filter);
      }

      if(empty($social_presence) and $to_followers!=0 and $from_follwers!=0){
        $socialInfo = DB::select("select * from (select GROUP_CONCAT(distinct user_id) as userId,sum(follows_count) as total_followsCount,sum(followers_count) as total_followersCount,status from social_info where status=1) as followers where status=1  ".$follower_filter);
      }

      if(!empty($socialInfo) && isset($socialInfo[0]->userId) && $socialInfo[0]->userId > 0){
        $filter=$filter." and id in (".$socialInfo[0]->userId.")";
      }
            
      $countryCode = DB::raw('case when country_code is null then "" else country_code end as country_code');
     
      // New work
      $rankTypeId = $request->rank_type ;
      $star_imgPath=config('constants.star_image') ;
      $rankTypes = DB::table('rank_types')->select('id','rank_title','range_from','range_to',DB::raw('concat("'.$star_imgPath.'",star_img) as starImg'))->orderBy('range_to', 'DESC')->where('id',$rankTypeId)->first();
    
       $data['list']=array();

      if(!empty($rankTypes)){
        
          $from = $rankTypes->range_from ;
          $to = $rankTypes->range_to ;
          $id = $rankTypes->id ;
          $title = $rankTypes->rank_title ;
          $starImg = $rankTypes->starImg ;
          	//id!=".$userId." and
           $list=DB::select("select id,case when id=$userId then 1 else 0 end as isLoginUser,encryption,name,rank_,rank_type,case when image is null then '' else concat('".$filePath."',image) end as image,case when countryId is null then 0 else countryId end as countryId,$countryCode,concat('".$starImg."') as star_img from users where  isTrash=0 and isFeatured=0 and rank_ BETWEEN ".$from." AND ".$to."   ".$filter.$searchKeyword." order by rank_ desc limit ".$limit." offset ".$offset);
          $result=array("id"=>$id ,"rank_title"=>$title,"starImg"=>$starImg ,"user_list"=>(array)$list);
          $data['list']=$result ;
        
      }
      
      $totalFeatureUser=0 ; //count($featureUser);
      $totalRecord=DB::select("select id from users where id!=".$userId." and isTrash=0 and isFeatured=0 and rank_ BETWEEN ".$from." AND ".$to."".$filter.$searchKeyword." order by rank_type desc");
      
      $image = DB::raw('case when concat("'.$filePath.'",image) is null then "" else concat("'.$filePath.'",image) end as image') ;
      // $totalLoginUsrFollowers = User::getFollowersCount($userId);
      // $loginUsrRank = User::getUserRank($userId);
      // $data['featured_user']=array('total_followers'=>$totalLoginUsrFollowers,'rank_'=>$loginUsrRank) ;
      //$data['total_feature']=$totalFeatureUser ;
      // $advertisement=User::advertisement();      
      // $data['advertisement']=$advertisement ;     
      $totalRecord=count($totalRecord) ;

      if(($offset+$limit) < $totalRecord){
        $data['isShowMore']=true ;  
      }else{
        $data['isShowMore']=false ;  
      }
      $data['page']=$page ;               
      return $this->successResponse($data,'User List By Rank',200);
    
    }

     public function check_private_account(Request $request){
    $userId = authguard()->id ;
    $checkPrivateAcount = DB::table('users')->select('isPrivate')->where('id',$userId)->first();
    return $this->successResponse($checkPrivateAcount,'User Info',200);
  }
  
}

