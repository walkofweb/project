<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Yajra\Datatables\Datatables;
use App\Models\User_fb_pages;
use DB , session ;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Auth; 

class UserDashboardController extends Controller
{
    public function index()
    {
        $data['title']=siteTitle() ;
        //return "Dashboard" ; exit ;
       return view('user/dashboard',$data);
        
    }

    public function existfacebookuser()
    {
       $id=Auth::user()->id;
    
      $data['title']=siteTitle() ;
      $User = DB::table('users')->where('status',1)->where('isTrash',0)->where('id',$id)->first();
      if(!empty($user) && $User->social_id)
     {
       echo "yes";
     }
     else{
      echo "no";
     }
    }


   public function user_dashboard(Request $request){

        $data['title']=siteTitle() ;
   
       //   current weeks and previous week graph
       //   Top 7 Advertisers
       //   Traffic by Location
       //   Traffic by Platform         
          
        $activeUser = DB::table('users')->select(DB::raw('count(*) as totalActiveUser'))->where('status',1)->where('isTrash',0)->where('user_type','=',6)->first();
        $deactivateUser = DB::table('users')->select(DB::raw('count(*) as totalDeActiveUser'))->where('isTrash',0)->where('status',0)->where('user_type','=',6)->first();
        $newUser = DB::table('users')->select(DB::raw('count(*) as newUser'))->where('isTrash',0)->where('user_type','=',6)->whereDate('created_at', Carbon::today())->first();
        
        $activeUser_ = isset($activeUser->totalActiveUser)?$activeUser->totalActiveUser:0 ;
        $deactiveUser_ = isset($deactivateUser->totalDeActiveUser)?$deactivateUser->totalDeActiveUser:0 ;
        $newUser_ = isset($newUser->newUser)?$newUser->newUser:0 ;
        $data['active_user']=$activeUser_ ;
        $data['deactive_user']=$deactiveUser_ ;
        $data['new_user'] = $newUser_ ;
        $data['total_app_download'] = 0  ;  
   
       
       //  $date = "01 April 2023";
       //  dd(date('Y-m-d', strtotime($date)));
         echo view('user/user_dashboard',$data);
   
       }
       public function user_details(Request $request)
       {
        $data['title']=siteTitle() ;
        echo view('user/user_management',$data);
       }

       public function userlist(Request $request){
        $data['title']=siteTitle();
  
        $imgPath = config('constants.user_image');
        $id=Auth::user()->id;
      
        $usrQry = "select users.id,name,case when image is null then '' else concat('".$imgPath."',image) end as image,case when username is null then '' else concat(username) end as username,rank_ ,case when registration_from=1 then 'Android' when registration_from=2 then 'IOS' else 'Admin' end as registration_from,email, phoneNumber, followers,rank_,Date_Format(created_at,'%Y-%m-%d') as created_at,case when (select title from pe_countries where id=users.countryId and i_status=1) is null then '' else (select title from pe_countries where id=users.countryId and i_status=1) end as countryId,status,user_fb_page_info.page_followers,case when status=1 then 'Active' else 'Inactive' end as status_,0 as rankN from users left join user_fb_page_info on users.id=user_fb_page_info.user_id where user_type!=1 and isTrash=0 and users.id='$id'" ; 
  
        $usrData = DB::select($usrQry);
          $response=array();
          foreach ($usrData as $key => $value) {
            $rankT = getRankType($value->rank_);
                $rankImg = DB::table('rank_types')->select('rank_title')->where('id',$rankT)->where('status',1)->first() ; 
                $value->rank=isset($rankImg->rank_title)?$rankImg->rank_title:'' ;
                $value->rankN=$rankT ;
                $value->followers = User::getFollowersCount($value->id);
                $response[$value->id]=$rankT;
          }
  
       
        
        $tableData = Datatables::of($usrData)->make(true);  
        return $tableData; 
        
      }

       public function social_connect()
       {
        $data['title']=siteTitle();
        $userId=Auth::user()->id;
        $encryptionKey = DB::table('users')->select('encryption')->where('id',$userId)->first();
        $encryption=$encryptionKey->encryption ;
        $data['soicalInfo'] = DB::table('social_media')->select('id','title',DB::raw('concat(login_url,"/","'.$encryption.'") as login_url'))->where('social_connect',1)->get();
        echo view('user/social/index',$data);
       

       }

   
    
}
