<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\Datatables\Datatables;
use App\Models\User;
use DB;
use Hash ;
use Session ;
use URL;

class customerController extends Controller
{
    
    public function index(Request $request){

    	$data['title']=siteTitle();    
    	echo view('admin/customerManagement/index',$data);

    }

    public function customerData(Request $request){

    	$data['title']=siteTitle();

    	echo view('admin/customerManagement/index',$data);

    }

    public function detail(Request $request){

    	$data['title']=siteTitle();
        $userId = isset($request->userId)?$request->userId:'' ;

        $userInfo = user::find($userId) ;
        $data['userInfo']=$userInfo ;
        
    	echo view('admin/customerManagement/customerDetail',$data);

    }

    public function customerlist(Request $request){
    	$data['title']=siteTitle();

      $imgPath = config('constants.user_image');
    
    	$usrQry = "select id,name,case when image is null then '' else concat('".$imgPath."',image) end as image,case when username is null then '' else concat(username) end as username,rank_ ,case when registration_from=1 then 'Android' when registration_from=2 then 'IOS' else 'Admin' end as registration_from,email, phoneNumber, followers,rank_,Date_Format(created_at,'%Y-%m-%d') as created_at,case when (select title from pe_countries where id=users.countryId and i_status=1) is null then '' else (select title from pe_countries where id=users.countryId and i_status=1) end as countryId,status,case when status=1 then 'Active' else 'Inactive' end as status_,0 as rankN from users where user_type!=1 and isTrash=0" ; 

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

    public function changeStatus(Request $request)
    {
    	$id=$request->id ;
    	$qry="update users set status=(case when status=1 then 0 else 1 end) where id=".$id;
    	try{
           DB::select($qry);	
            return $this->successResponse([],'changed status successfully'); 
         
    	}
    	 catch(\Exception $e)
        {
          return $this->errorResponse('error occurred'); 
         
        }

    }

    public function delete_customer(Request $request){

          $id=isset($request->id)?$request->id:'' ;
        try{
               user::where('id',$id)->update(array('isTrash'=>1));
                
              return $this->successResponse([],'This user has deactivated successfully'); 
        }
         catch(\Exception $e){
             return $this->errorResponse('error occurred'); 
         }
    }

    public function changePassword(Request $request){
   
      $newPassword = isset($request->newPassword)?$request->newPassword:'' ;
      $confirmPwd = isset($request->confirmPwd)?$request->confirmPwd:'' ;
      $userId = isset($request->changeUserPwd)?$request->changeUserPwd:'' ;
      $password =  Hash::make($newPassword) ;

      $updateData = array(
        "password"=>$password
      );
       

       try{
        
              User::where('id',$userId)->update($updateData) ;           
              echo successResponse([],'changed password successfully'); 
        }
         catch(\Exception $e){
             echo errorResponse('error occurred'); 
         }
      
    }

     public function changeAdminPassword(Request $request){
   
      $newPassword = ($request->newAdminPassword)??'' ;
      $sessionData =Session::get('admin_session');
      $userId = ($sessionData['userId'])??0 ;
      $password =  Hash::make($newPassword) ;

      $updateData = array(
        "password"=>$password
      );
       

       try{
              user::where('id',$userId)->update($updateData) ;           
              echo successResponse([],'changed password successfully'); 
        }
         catch(\Exception $e){
             echo errorResponse('error occurred'); 
         }
      
    }

    public function userDetail(Request $request){
      $data['title']=siteTitle();
      $userId = $request->userId ; 
      $data['userId']=$request->userId ; 
          
      $imgPath = config('constants.user_image');
      $img=DB::raw("case when image is null then '' else concat('".$imgPath."',image) end as image") ;
      $userInfo=DB::table('users as u')
      ->select('u.id','u.name',DB::raw('case when u.followers is null then 0 else u.followers end as followers'),'u.email','c.title as country',$img,'u.phoneNumber','u.country_code','u.countryId','u.bio','u.image','created_at','rank_','rank_type',DB::raw("case when u.status=1 then 'Active' else 'Inactive' end as status"))
      ->leftjoin('pe_countries as c','c.id','=','u.countryId')
      ->where('u.id',$request->userId)
      ->first(); 
     
      $totalFollowers=User::getAppFollowersCount($userId);
      $userInfo->followers = $totalFollowers ;
      $data['userInfo']=$userInfo ;
      $interest=DB::select('select GROUP_CONCAT(ui.title) as interest from user_interests_map as uim 
      inner join user_interests as ui on ui.id=uim.interest_id
      where uim.user_id="'.$userId.'" and uim.status=1 group by uim.user_id');
      
      if(!empty($interest)){
        $userInterest = isset($interest[0]->interest)?$interest[0]->interest:'' ;
      }else{
        $userInterest = '' ;
      }
      $data['userInterest']=$userInterest ;
     
      echo view('admin/customerManagement/customerDetail',$data);
    }

    public function userHost(Request $request){

      $userId = isset($request->userId)?$request->userId:'' ; 
      $type = isset($request->type)?$request->type:'' ;
      $data['type']= $type ;
      $data['userId'] = $userId ;
       
       if($type==2){
             echo view('admin/customerManagement/hostListing',$data);
        } else if($type==3) {
             echo view('admin/customerManagement/advertisement',$data);
        } else if($type==1){
          echo view('admin/customerManagement/followers',$data);
        } else if($type==4){
          echo view('admin/customerManagement/follows',$data);
        }  
              
   }

   public function userHost_datatable(Request $request){
    $data['title']=siteTitle();
    $userId=$request->userId ;
    $type=$request->type ;
    $usrImg = config('constants.user_image');     
    $carQry="select uh.id,u.name,case when uh.isAccept=0 then 'Pending' when uh.isAccept=1 then 'Approved' when uh.isAccept=2 then 'Rejected' else '' end as status,uh.createdOn,uh.isAccept as status_ from user_host as uh inner join users as u on u.id=uh.host_user_id where uh.userId=".$userId ;  
    $carData = DB::select($carQry); 
    $tableData = Datatables::of($carData)->make(true);  
    return $tableData; 
}


public function masterStatus(Request $request)
{
  $table=$request->table_name ;
  $id=$request->id ;
  $qry="update ".$table." set status=(case when status=1 then 0 else 1 end) where id=".$id;
  try{
       DB::select($qry);	
       return $this->successResponse([],'changed status successfully');      
  }
   catch(\Exception $e)
    {
      return $this->errorResponse('error occurred');      
    }
}

public function userHostStatus(Request $request)
{
  $table=$request->table_name ;
  $id=$request->id ;
  $qry="update user_host set isAccept=(case when isAccept=1 then 2 else 1 end) where id=".$id;
  try{
       DB::select($qry);	
        return $this->successResponse([],'changed status successfully');      
  }
   catch(\Exception $e)
    {
      return $this->errorResponse('error occurred'); 
     
    }
}

public function deleteHost(Request $request){

  $id=isset($request->id)?$request->id:'' ;
try{
       DB::table('user_host')->where('id',$id)->delete();        
      return $this->successResponse([],'This user has deactivated successfully'); 
}
 catch(\Exception $e){
     return $this->errorResponse('error occurred'); 
 }

}


public function userAdv_datatable(Request $request){

  $data['title']=siteTitle();
  $userId=$request->userId ;
  $type=$request->type ;
  $sponserImg = config('constants.advertisement_image');     
  $carQry="select adv.id,sp.name,adv.title,case when adv.ad_type=1 then 'image' when adv.ad_type=2 then 'video' else '' end as adv_type,case when (adv.image is null || adv.image='') then '' else concat('".$sponserImg."',adv.image) end as image,Date_format(adv.start_date,'%d %b %Y') as start_date,Date_format(adv.end_date,'%d %b %Y') as end_date,case when adv.isAccept=0 then 'Pending'
  when adv.isAccept=1 then 'Approved' when adv.isAccept=2 then 'Rejected' else '' end as isAccept
  ,Date_format(adv.createdOn,'%d %b %Y') as createdOn,isAccept as isAccept_,ad_type as adv_type_,case when adv.status=1 then 'Active' else 'Inactive' end  as status, adv.status as status_ from advertisements as adv  inner join sponser as sp on sp.id=adv.Sponser_id where adv.start_date
  is not null and adv.end_date is not null and adv.createdBy=".$userId." and adv.isAccept!=4" ; 
//where createdBy=".$userId
  $carData = DB::select($carQry); 
  $tableData = Datatables::of($carData)->make(true);  
  return $tableData; 
}

public function deleteUserAdv(Request $request){

  $id=isset($request->id)?$request->id:'' ;
try{
       DB::table('advertisements')->where('id',$id)->delete();        
      return $this->successResponse([],'Deleted successfully'); 
}
 catch(\Exception $e){
     return $this->errorResponse('error occurred'); 
 }

}

public function userAdvStatus(Request $request)
{
  $table=$request->table_name ;
  $id=$request->id ;
  $qry="update advertisements set status=(case when status=1 then 0 else 1 end) where id=".$id;
  try{
       DB::select($qry);	
        return $this->successResponse([],'changed status successfully');      
  }
   catch(\Exception $e)
    {
      return $this->errorResponse('error occurred'); 
     
    }
}


public function editUserAds(Request $request){

  $updatedId = isset($request->updatedId)?$request->updatedId:0 ;
   $advertisement = DB::table('advertisements')->where('id',$updatedId)->first() ;
   $data['sponser']=DB::table('sponser')->where('status',1)->get();
   $data['advertisement'] = $advertisement ;
   $data['updatedId']=$updatedId ;

  echo view('admin/customerManagement/editAds',$data);
}


public function userFollower_datatable(Request $request){  

  $data['title']=siteTitle();
  $userId=$request->userId ;
  $type=$request->type ;
  $userImgPath = config('constants.user_image');     

  // $bio = DB::raw('case when bio is null then "" else bio end as bio');
  // $image = DB::raw('case when image is null then "" else concat("'.$userImgPath.'",image) end as image');
  // $country_code = DB::raw('case when country_code is null then "" else country_code end as country_code');

  $carQry="select uf.id ,us.name,case when isAccept is null then '' when isAccept=1 then 'Approve' when isAccept=2 then 'Rejected' when isAccept=0 then 'Pending' else '' end as isAccepts,uf.createdOn,isAccept as isAccepts_ from user_follows as uf inner join users as us on us.id=uf.followed_user_id where follower_user_id=".$userId;
 
  $carData = DB::select($carQry); 
  $tableData = Datatables::of($carData)->make(true);  
  return $tableData; 

}

public function userFollows_datatable(Request $request){ 
  
  $data['title']=siteTitle();
  $userId=$request->userId ;
  $type=$request->type ;
  
  $carQry="select uf.id ,us.name,uf.createdOn from user_follows as uf inner join users as us on us.id=uf.follower_user_id where isAccept=1 and followed_user_id=".$userId ; //; 
  $carData = DB::select($carQry); 
  $tableData = Datatables::of($carData)->make(true);  
  return $tableData; 
}

public function deleteFollower(Request $request){

  $id=isset($request->id)?$request->id:'' ;
try{
       DB::table('user_follows')->where('id',$id)->delete();        
      return $this->successResponse([],'Deleted successfully'); 
}
 catch(\Exception $e){
     return $this->errorResponse('error occurred'); 
 }

}

public function deleteFollows(Request $request){

  $id=isset($request->id)?$request->id:'' ;
try{
       DB::table('user_follows')->where('id',$id)->delete();        
      return $this->successResponse([],'Deleted successfully'); 
}
 catch(\Exception $e){
     return $this->errorResponse('error occurred'); 
 }

}

public function term_condition(){
    $qry=DB::table('cms')->where('slug','term_condition')->first();
    $data['term_conditions']= $qry->description ;
    echo view('web_view/term_conditions',$data);

}

public function privacy_policy(){
  $qry=DB::table('cms')->where('slug','privacy_policy')->first();
  $data['privacy_policy']= $qry->description ;
  echo view('web_view/privacy_policy',$data);
}

public function followers_graph($userId){  
  $data['title']= siteTitle() ;
  $data['userId']=$userId ;  
  echo view('web_view/followers',$data);
}

public function social_media_follower(Request $request){ 

  $baseUrl=url('/').'/public/social_icon/' ;
  $userId = isset($request->userId)?$request->userId:exit ;
   
  $fbFollowers="select sum(fb_page_followers_count) as total_fb_followers from fb_user_info where userId=".$userId;
  $instaFollowers="select sum(followers_count) as instaFollowers from insta_user_info where userId=".$userId;
   $tiktokFollowers="select sum(followers_count) as tiktokFollowers from tiktok_user_info where userId=".$userId;
   $walkofwebFollowers="select count(*) as walkofwebFollowers from user_follows where follower_user_id=".$userId." and isAccept=1";
 
  $fbF = DB::select($fbFollowers);
  $fbF_ = isset($fbF[0]->total_fb_followers)?$fbF[0]->total_fb_followers:0 ;
 
  $instaF = DB::select($instaFollowers);
  $instaF_ = isset($instaF[0]->instaFollowers)?$instaF[0]->instaFollowers:0 ;
 
  $tiktokF = DB::select($tiktokFollowers);
  $tiktokF_ = isset($tiktokF[0]->tiktokFollowers)?$tiktokF[0]->tiktokFollowers:0 ;
   
  $walkofwebF = DB::select($walkofwebFollowers);
  $walkofwebF_ = isset($walkofwebF[0]->walkofwebFollowers)?$walkofwebF[0]->walkofwebFollowers:0 ;
 
 
  $categoryImg=array(
     'Walkofweb'=>$baseUrl.'walkofweb.png',
     'Tiktok'=>$baseUrl.'tiktok.png',
     'Facebook'=>$baseUrl.'fb.png',
     'Instagram'=>$baseUrl.'insta.png'
  );
 
   $response=array(
    array("name"=>"Walkofweb","y"=>(int)$walkofwebF_),
    array("name"=>"Tiktok","y"=>(int)$tiktokF_),
    array("name"=>"Facebook","y"=>(int)$fbF_),
    array("name"=>"Instagram","y"=>(int)$instaF_)
   );
  
   $resp=array('category'=>$categoryImg,'data'=>$response);
   return json_encode($resp)     ;
   
 }

  public function postShare(Request $request){
   
     

        $id=isset($_GET['postId'])?$_GET['postId']:0 ;
        $redirectURL = URL::to('/').'/api/v1/postShare?postId='.$id; 
        $checkDevice=checkDevice();
        
        if($checkDevice['isIOS']==1){       
      
          return redirect()->to('https://apps.apple.com/us/app/walkofweb/id6466639776');
        }else if($checkDevice['isAndroid']==1){
           return Redirect::to('https://play.google.com/store/apps/details?id=com.walkofweb&hl=en-IN');
          
        }else{
          $data['redirectURL']="https://walkofweb.net/" ;   
          $data['deviceType']="Desktop";
        }
        echo "<pre>";
        print_r($data);
    }

    public function userProfileShare(Request $request){
        $userId=isset($_GET['userId'])?$_GET['userId']:0 ;
       // $userInfo=User::select('id')->where('encryption',$userId)->first();
        $userId = isset($userInfo->id)?$userInfo->id:0 ;
       
        $checkDevice=checkDevice();
        echo "<meta property='og:image' content='https://www.walkofweb.net/assets/images/logo.png' />";
        
        if($checkDevice['isIOS']==1){
           return redirect()->to('https://apps.apple.com/us/app/walkofweb/id6466639776');
        }else if($checkDevice['isAndroid']==1){
          
       return Redirect::to('https://play.google.com/store/apps/details?id=com.walkofweb&hl=en-IN');
        }else{
          $data['redirectURL']="https://walkofweb.net/" ;   
          $data['deviceType']="Desktop";
          return Redirect::to('https://play.google.com/store/apps/details?id=com.walkofweb&hl=en-IN');          
        }
        // echo "<pre>";
        // print_r($data);
    }

 
}
