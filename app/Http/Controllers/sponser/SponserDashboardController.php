<?php

namespace App\Http\Controllers\sponser;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB , session ;
use Carbon\Carbon;
use DateTime;
use Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Countries;
class SponserDashboardController extends Controller
{ 

    public function index()
    {
        $data['title']=siteTitle() ;
       $id=Auth::user()->id;
     
        $data['countries']=Countries::get();
        $data['profile']=User::select('pe_countries.id as country_id','pe_countries.title','pe_countries.api_code','users.id','users.name','users.email','users.address','users.phoneNumber','users.countryId','users.image')->join('pe_countries','users.countryId','pe_countries.id')->where('users.id',$id)->first();
        
      
      
       return view('Sponser/dashboard',$data);
        
    }
    public function update_sponser_profile(Request $request)
    {
    $data['title']=siteTitle() ;
     
      

    $rules=[            
      'name' => 'required',
      'email' => 'email|required',
      'phone_no' => 'required|numeric',
      'address' => 'required',
      'country' => 'required',
      'profile_image' =>'file|mimes:jpg,png'          
     ] ;

      $validatedData = Validator::make($request->all(),$rules);


  if($validatedData->fails()){       
      return $this->errorResponse($validatedData->errors()->first(), 401);
    }


       $updatedId = isset($request->updatedId)?$request->updatedId:0 ;
    
      $name=isset($request->name)?$request->name:'' ;
      $email = isset($request->email)?$request->email:'' ;
      $phone_no = isset($request->phone_no)?$request->phone_no:'' ;
      $country = isset($request->country)?$request->country:'' ;
      $address = isset($request->address)?$request->address:'' ;
      $filenametostore='';
      if($request->hasFile('profile_image')) {
        
       
       // $imgPath='app/public/sponser_image/' ;
        
      
         $filenamewithextension = $request->file('profile_image')->getClientOriginalName();
   
         //get filename without extension
         $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
   
         //get file extension
         $extension = $request->file('profile_image')->getClientOriginalExtension();           
         $filename=str_replace(' ', '_', $filename);
         $filenametostore = $filename.'_'.time().'.'.$extension;       
        // $smallthumbnail = $filename.'_100_100_'.time().'.'.$extension;   
        $request->file('profile_image')->move('public/profile_image', $filenametostore);
      } 
      else{
        $filenametostore=$request->oldimage;
      }

      $update_records = User::where("id", $updatedId)->update(['name' => $name, 
        'email' => $email,
        'countryId' => $country,
        'phoneNumber' => $phone_no,
        'address' => $address,
        'image' => $filenametostore]);
        if($update_records)
        {
          echo successResponse([],'changed Profile successfully'); 
        }
  
     
      
    }
    
    public function sponser_dashboard(Request $request){

        $data['title']=siteTitle() ;
   
       //   current weeks and previous week graph
       //   Top 7 Advertisers
       //   Traffic by Location
       //   Traffic by Platform         
          
        $activeUser = DB::table('users')->select(DB::raw('count(*) as totalActiveUser'))->where('status',1)->where('isTrash',0)->where('user_type','=',5)->first();
        $deactivateUser = DB::table('users')->select(DB::raw('count(*) as totalDeActiveUser'))->where('isTrash',0)->where('status',0)->where('user_type','=',5)->first();
        $newUser = DB::table('users')->select(DB::raw('count(*) as newUser'))->where('isTrash',0)->where('user_type','=',5)->whereDate('created_at', Carbon::today())->first();
        
        $activeUser_ = isset($activeUser->totalActiveUser)?$activeUser->totalActiveUser:0 ;
        $deactiveUser_ = isset($deactivateUser->totalDeActiveUser)?$deactivateUser->totalDeActiveUser:0 ;
        $newUser_ = isset($newUser->newUser)?$newUser->newUser:0 ;
        $data['active_user']=$activeUser_ ;
        $data['deactive_user']=$deactiveUser_ ;
        $data['new_user'] = $newUser_ ;
        $data['total_app_download'] = 0  ;  
   
       
       //  $date = "01 April 2023";
       //  dd(date('Y-m-d', strtotime($date)));
      
         return view('Sponser/sponser_dashboard',$data);
   
       }

}
