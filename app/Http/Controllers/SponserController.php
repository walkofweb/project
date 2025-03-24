<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Countries;
use Hash;
use DB ;
use Image ;
use File ;
use Mail ;
use VideoThumbnail ;
use Laravel\Passport\Token;
use Lcobucci\JWT\Configuration;
use lang ;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Http;

use Session ;


use Cookie;

class SponserController extends Controller
{
    //
    public function sponserSignup(){
        $deviceType=[];
        $data['userType'] = array(0=>'App user', 1=>'admin', 2=>'fb',3=>'google', 4=>' Apple user',5=>' Sponser');
        $data['device'] = array(1=>'Android ', 2=>'IOS', 3=>'Web');
       
        $data['country']=Countries::get();
       
       
        return view('Sponser/signup',$data);
      
    }
    public function register_backup(Request $request){
     	 
        //deviceType 1 android 2 ios
       
         try
        {    
        
       // dd($request->all());
            
            $rules=[            
            'txtName' => 'required',
            'txtEmail' => 'required',
            'txtPhoneNumber' => 'required|numeric',
            'txtPassword' => 'required',
            'txtConfirm_Password' => 'required',
            'txtcountry' => 'required',
            
            

           ] ;

            $validatedData = Validator::make($request->all(),$rules);
      
 
        if($validatedData->fails()){       
            return $this->errorResponse($validatedData->errors()->first(), 200);
          }
          $userType=5;
          $deviceType=$request->txtdevice_type?$request->txtdevice_type:"3";
          $name=$request->txtName;
          $usrName = substr($name,0,2);
          $password_ = $request->txtPassword ;
          $email=$request->txtEmail ; 
         
          $fairebasdeviceTypeeToken = '';     
          // new
          if(!empty($request->txtcountry))
          {
            $country_code = DB::table('pe_countries')->select(['id','api_code'])->where('id', $request->txtcountry)->first();
           
            $request['country_code'] = (!empty($country_code))?$country_code->api_code:'' ;
            $request['countryId'] = !empty($country_code)?$country_code->id:'' ;
          }
          
         
          $request['name'] = $request->txtName;
         
          $request['email'] = $request->txtEmail;
          $request['phoneNumber'] = $request->txtPhoneNumber;
          $request['registration_from'] = $deviceType ;
          $request['rank_type'] =0 ; // rand(1,5);
          $request['rank_'] =0 ;// rand(1,30);     
          $request['username'] ='' ; //$userName;
    			$request['password']=Hash::make($request['password']);
    			$request['remember_token'] = Str::random(10);
          $request['user_type'] = $userType;
        

    			$user = User::create($request->toArray());
         
    			$token = $user->createToken('walkofweb token')->accessToken;
          $message =  __('messages.user_registration') ; //"Account has been created successfully." ;
          $insertId=$user->id;
          $encryptionKey = md5('wow_intigate_23'.$insertId);
          $number = mt_rand(10000,99999);
           $username = "WOW".strtoupper($usrName).$insertId;
            
          DB::table('users')->where('id',$insertId)->update(['encryption'=>$encryptionKey,'username'=>$username]);
          $imagick=config('constants.imagick');
          // if($imagick==1){
          //    $this->qrCode($encryptionKey);
          //    $this->sendRegistrationEmail($name,$email,$password_);
          // }
          $fairebaseToken='';
			    saveDeviceToken($insertId,$fairebaseToken,$deviceType,$encryptionKey);
          $data['id']=$insertId ;
          $data['token'] = $token  ;
          $data['encryption']=$encryptionKey ;

          return $this->successResponse($data,$message,200);    

        }
        catch(\Exception $e)
        {
          return $this->errorResponse(__('messages.user_registration_err'.$e), 200);
          // return $this->errorResponse('This user is already exist.'.$e, 200);
        }
      }
      public function sponser_doLogin(Request $request)
      {
        $credentials = [
          'email' => $request->txtUserName,
          'password' => $request->txtPassword,
         'user_type' => 5, 
         'isTrash' => 0,
          'status' => 1
      ];
      
       
        if (auth()->attempt($credentials)) {
          $userData = auth()->user() ;
          $userId = $userData->id ;
          $userType = $userData->user_type ;
         $rememberMe = $request->rememberMe ;
       
          $session_data = array('userId' => $userId,
                                 'userType' => $userType,
                                 'userName' =>$userData->name,
                                 'userEmail' =>$userData->email,
                                 'userPhone' =>$userData->mobile_Number                              
                                 );
          Session::put('sponser_session', $session_data); 
 
         if($rememberMe==1){
         Cookie::queue('userName', $request->txtUserName, 60);
         Cookie::queue('userPassword', $request->txtPassword, 60);      
         }
         
         echo 'succ';
       
        }else{
 
          echo "fail" ;
        }

      }

        public function sponserLogin(Request $request){
          // User::where('id',32)->update(['password'=>'123456']); exit ;
           //echo $password =  Hash::make(123456) ; exit ;
           // $password = '12345' ;
           // echo    Hash::make($password) ;exit;
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
       
           return view('Sponser/login',$data);
         }


         public function do_login(Request $request){
   
          //    $password = '12345' ;
          // echo    Hash::make($password) ;
          // exit ;
          $credentials = [
            'email' => $request->txtUserName,
            'password' => $request->txtPassword,
           'user_type' => 5, 
           'isTrash' => 0,
            'status' => 1
        ];
        
             if (auth()->attempt($credentials)) {
               $userData = auth()->user() ;
               $userId = $userData->id ;
               $userType = $userData->user_type ;
              $rememberMe = $request->rememberMe ;
            
               $session_data = array('userId' => $userId,
                                      'userType' => $userType,
                                      'userName' =>$userData->name,
                                      'userEmail' =>$userData->email,
                                      'userPhone' =>$userData->mobile_Number                              
                                      );
               Session::put('sponser_session', $session_data); 
      
              if($rememberMe==1){
              Cookie::queue('userName', $request->txtUserName, 60);
              Cookie::queue('userPassword', $request->txtPassword, 60);      
              }
              
             return $userData;
            
             }else{
             
               echo "fail" ;
        }
             
      
          }
      

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
            $user = User::create($request->toArray());
           
            $token = $user->createToken('walkofweb token')->accessToken;
            $message =  __('messages.user_registration') ; //"Account has been created successfully." ;
            $insertId=$user->id;
            $encryptionKey = md5('wow_intigate_23'.$insertId);

            $insertId=$user->id;
            $encryptionKey = md5('wow_intigate_23'.$insertId);
            $number = mt_rand(10000,99999);
             $userName = "WOW".strtoupper($usrName).$insertId;
          //  $userName = $this->UsernameGenerate($usrName,$insertId);   
            DB::table('users')->where('id',$insertId)->update(['encryption'=>$encryptionKey,'username'=>$userName,]);
            $imagick=config('constants.imagick');
            // if($imagick==1){
            //    $this->qrCode($encryptionKey);
            //    $this->sendRegistrationEmail($name,$email,$password_);
            // }
            saveDeviceToken($insertId,$fairebaseToken,$request->deviceType,$encryptionKey);
            $data['id']=$insertId ;
            $data['token'] = $token  ;
            $data['encryption']=$encryptionKey ;
  
            return $this->successResponse($data,$message,200);    
  
          }
          catch(\Exception $e)
          {
            return $this->errorResponse(__('messages.user_registration_err'.$e), 200);
            // return $this->errorResponse('This user is already exist.'.$e, 200);
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
      public function logout(Request $request){
        $data['title']='LesGo' ;
         Auth::logout();    
         Session::flush();
        return redirect('sponser/login');
      }
  



  

    }

    

