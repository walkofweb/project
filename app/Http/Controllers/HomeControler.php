<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\User;
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
use Session ;
use Cookie;
class HomeControler extends Controller
{
    
    public function index(){
     
       
        return view('Home.index');
    }

    public function userLogin(Request $request){
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
     
         return view('user/login',$data);
       }

       public function do_login(Request $request)
       {
        $credentials = [
          'email' => $request->txtUserName,
          'password' => $request->txtPassword,
         'user_type' => 2, 
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
             Session::put('user_session', $session_data); 
    
            if($rememberMe==1){
            Cookie::queue('userName', $request->txtUserName, 60);
            Cookie::queue('userPassword', $request->txtPassword, 60);      
            }
            
           return $userData;
          
           }else{
           
             echo "fail" ;
      }
       }
    public function logout(Request $request){
        $data['title']='LesGo' ;
         Auth::logout();    
         Session::flush();
        return redirect('/login');
      }
    public static  function dashboard(){
        $data['title']=siteTitle() ;
      // $data['userDetails']=Auth::user(); 
     
      
        return view('user/dashboard',$data);
    }
    public function aboutus(){
     

        return view('Home.aboutus');
    }
    public function feature(){
     

        return view('Home.feature');
    }

    public function contactUs(){

        return view('Home.contactUs');
    }
    public function privacy_policy(){

        return view('Home.privacy_policy');
    }
    public function term_conditions(){

        return view('Home.terms_cond');
    }
}
