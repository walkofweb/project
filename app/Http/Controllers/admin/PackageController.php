<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use DB ; 
use App\Models\Package ;
use Image ;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ImportUser;
use App\Exports\ExportUser;
use App\Models\User;
use App\Models\userpackage;

use PDF;
use File ;

class PackageController extends Controller
{
    

    public function PackageList(Request $request){

        $data['title']=siteTitle();

        echo view('admin/master/package/index',$data);

    }  
    public function AddUserpackage(Request $request){

        $data['title']=siteTitle();
       
        echo view('admin/master/package/packageuser',$data);

    }  

    public function packageListing(Request $request){
    	$data['title']=siteTitle();

      $imgPath = config('constants.user_image');
    
    	$usrQry = "select p.id,p.packege_name,p.title,p.description,p.image,p.time_limit,p.status,p.price,case when p.status=1 then 'Aactive'  else 'Inactive' end as status_,p.isTrash from packeges p where p.isTrash=0 " ; 
      
      $usrData = DB::select($usrQry);
    
       

      

     
      
      $tableData = Datatables::of($usrData)->make(true);  
      return $tableData; 
    	
    }
    public function savePackage(Request $request)
    {
      $validator = Validator::make($request->all(), [
        'pacakagename' => 'required',
        'title' => 'required',
        'time_limit' => 'required',
        'price' => 'required',
        'description' => 'required',
        'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
       ]);
  
      
      if($validator->fails()){       
        return $this->errorResponse($validator->errors()->first(), 200);
      }
     $pacakagename=$request->pacakagename;
     $title=$request->title;
     $image=$request->image;
     $time_limit=$request->time_limit;
     $post=$request->post;
     $other=$request->other;
     $price=$request->price;
     $description=$request->description;
    
    //  $img='';
    //  $check='';
    //  $videowithextension='';

    //  if($request->hasFile('image')) {
      
        
          
    //       $imgPath='public/admin/package/images' ;
          
      

    //       $filenamewithextension = $request->file('image')->getClientOriginalName();
    //         $fileType = $this->checkFileType($filenamewithextension);
           
     
    //     if( $fileType==1)
    //     {

      
    //         $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
    //         $extension = $request->file('image')->getClientOriginalExtension();
    //         $filename=str_replace(' ', '_', $filename);
    //         $filenametostore = $filename.'_'.time().'.'.$extension;       
    //         $smallthumbnail = $filename.'_100_100_'.time().'.'.$extension;    
         
    //       $request->file('image')->move(public_path('admin/package/images'), $filenametostore);
    //      // $request->file('image')->move(public_path('admin/package/images'), $smallthumbnail);
       
    //       // $img= $request->file('image')->storeAs('public/admin/package/images', $filenametostore);
          
    //     }
    //  }
     
//      if($request->hasFile('video')) {
      
     
          
//       $imgPath='public/admin/package/videos' ;
      
  

//       $filenamewithextension = $request->file('video')->getClientOriginalName();
//         $fileType = $this->checkFileType($filenamewithextension);
 
//     if( $fileType==2)
//     {
  
//         $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
//         $extension = $request->file('image')->getClientOriginalExtension();
//         $filename=str_replace(' ', '_', $filename);
//         $filenametostore = $filename.'_'.time().'.'.$extension;       
//         $smallthumbnail = $filename.'_100_100_'.time().'.'.$extension;    
     
//       $request->file('image')->move(public_path('admin/package/videos'), $filenametostore);
//       $videowithextension=$filenametostore;
//       // $img= $request->file('image')->storeAs('public/admin/package/images', $filenametostore);
      
//     }
//  }
 
     
      $update=array(           
        'packege_name'=>$pacakagename,
        'title'=>$title,
        'posts'=>$post,
        'others'=>$other,
        'time_limit'=>$time_limit,
        'description'=>$description,
        'price'=>$price,
       
       );
      
       $package = Package::create($update);
       echo successResponse([],'Save successfully'); 
      
    }


    public function updatePackage(Request $request)
    {
      

      $validator = Validator::make($request->all(), [
        'pacakagename' => 'required',
        'title' => 'required',
        'time_limit' => 'required',
        'price' => 'required',
        'description' => 'required',
       ]);
  
      
      if($validator->fails()){       
        return $this->errorResponse($validator->errors()->first(), 200);
      }
     $pacakagename=$request->pacakagename;
     $title=$request->title;
     $image=$request->image;
     $time_limit=$request->time_limit;
     $post=$request->post;
     $other=$request->other;
     $price=$request->price;
     $description=$request->description;
    
//      $img='';
//      $check='';
//      $videowithextension='';

//      if($request->hasFile('image')) {
      
        
          
//           $imgPath='public/admin/package/images' ;
          
      

//           $filenamewithextension = $request->file('image')->getClientOriginalName();
//             $fileType = $this->checkFileType($filenamewithextension);
     
//         if( $fileType==1)
//         {
//       die("sdfdsf");
//             $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
//             $extension = $request->file('image')->getClientOriginalExtension();
//             $filename=str_replace(' ', '_', $filename);
//             $filenametostore = $filename.'_'.time().'.'.$extension;       
//             $smallthumbnail = $filename.'_100_100_'.time().'.'.$extension;    
         
//           $request->file('image')->move(public_path('admin/package/images'), $filenametostore);
//         //  $request->file('image')->move(public_path('admin/package/images'), $smallthumbnail);
//           // $img= $request->file('image')->storeAs('public/admin/package/images', $filenametostore);
          
//         }
//      }
     
//      if($request->hasFile('video')) {
      
     
          
//       $imgPath='public/admin/package/videos' ;
      
  

//       $filenamewithextension = $request->file('video')->getClientOriginalName();
//         $fileType = $this->checkFileType($filenamewithextension);
 
//     if( $fileType==2)
//     {
  
//         $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
//         $extension = $request->file('image')->getClientOriginalExtension();
//         $filename=str_replace(' ', '_', $filename);
//         $filenametostore = $filename.'_'.time().'.'.$extension;       
//         $smallthumbnail = $filename.'_100_100_'.time().'.'.$extension;    
     
//       $request->file('image')->move(public_path('admin/package/videos'), $filenametostore);
//       $videowithextension=$filenametostore;
//       // $img= $request->file('image')->storeAs('public/admin/package/images', $filenametostore);
      
//     }
//  }
$update=array(           
  'packege_name'=>$pacakagename,
  'title'=>$title,
  'posts'=>$post,
  'others'=>$other,
  'time_limit'=>$time_limit,
  'description'=>$description,
  'price'=>$price,

 );
 $updatedId=$request->updatedId;
 $package = Package::where('id',$updatedId)->first() ; 
 $package->update($update); 
     
      
      
       echo successResponse([],'Update successfully'); 
      
    }
    public function checkFileType($fileName){
      
      $imageExtensions = ['jpg', 'jpeg', 'gif', 'png', 'bmp', 'svg', 'svgz', 'cgm', 'djv', 'djvu', 'ico', 'ief','jpe', 'pbm', 'pgm', 'pnm', 'ppm', 'ras', 'rgb', 'tif', 'tiff', 'wbmp', 'xbm', 'xpm', 'xwd'];

      $videoExtensions = ['flv','mp4','MP4','m3u8','ts','3gp','mov','avi','wmv'];

      $audioExtensions = ['mp3'] ;

      $explodeImage = explode('.', $fileName);
      $extension = end($explodeImage);

      if(in_array($extension, $imageExtensions))
      {
      /// Is image
        return 1 ;
      }else if(in_array($extension, $videoExtensions))
      {
      // Is video
        return 2 ;
      }else if(in_array($extension, $audioExtensions)){
        // is audio 
        return 3 ;
      }else{
        return 4 ;
      }
  }
    public function editPackage(Request $request){

      $updatedId = isset($request->updatedId)?$request->updatedId:0 ;
       $countryInfo = Package::where('id',$updatedId)->first() ;
       
       $data['packageInfo'] = $countryInfo ;
       $data['updatedId']=$updatedId ;

      echo view('admin/master/package/editPackage',$data);
}

    public function AddUserpackageListing(Request $request){
    	$data['title']=siteTitle();

      $imgPath = config('constants.user_image');
    
    	$usrQry = "select u.id,u.name,packeges.image,packeges.id as package_id,packeges.packege_name,packeges.title,packeges.time_limit,packeges.price,case when u.username is null then '' else concat(u.username) end as username,u.rank_ ,case when u.registration_from=1 then 'Android' when u.registration_from=2 then 'IOS' else 'Admin' end as registration_from,u.email, u.phoneNumber, u.followers,u.rank_,Date_Format(u.created_at,'%Y-%m-%d') as created_at,u.status,case when userpackages.status=1 then 'Approved' when userpackages.status=0 then 'Pending' else 'Reject' end as status_,0 as rankN,userpackages.id as upakege_id,userpackages.status as userpackage_status,userpackages.id as userpackage_id from users u inner join userpackages on userpackages.user_id=u.id inner join packeges on packeges.id=userpackages.package_id where u.user_type!=1 and u.isTrash=0 and userpackages.isTrash=0" ; 
      
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

    public function changeStatus(Request $request,$packege_id){
     
        $newstatus = isset($request->status)?$request->status:'' ;
        
        $userId = isset($request->changeUserPwd)?$request->changeUserPwd:'' ;
       
  
        $updateData = array(
          "status"=>$newstatus
        );
         
  
         try{	
        
            $userpackage = userpackage::where('user_id',$userId)->where('package_id',$packege_id)->first();
            $userpackage_id=$userpackage->id;
            userpackage::where('id', $userpackage_id)->update($updateData);
            echo successResponse([],'changed status successfully'); 
          }
           catch(\Exception $e){
               echo errorResponse('error occurred'); 
           }
        
      }
      public function package_changeStatus(Request $request,$packege_id){
     
       
       
  
        $id=$request->id ;

        $qry="update packeges set status=(case when status=1 then 0 else 1 end) where id=".$id;

        try{

           DB::select($qry);    
            echo successResponse([],'changed status successfully'); 
         
        }
         catch(\Exception $e)
        {
          echo errorResponse('error occurred'); 
         
        }
        
      }
      public function delete_userpackages(Request $request){

           $id=isset($request->id)?$request->id:'' ;
            try{
              userpackage::where('id',$id)->update(array('isTrash'=>1));
                    
                  return $this->successResponse([],'This user has deactivated successfully'); 
            }
            catch(\Exception $e){
                return $this->errorResponse('error occurred'); 
            }
       }
       public function deletePackage(Request $request){
        $id=isset($request->id)?$request->id:'' ;
        try{
          Package::where('id',$id)->update(array('isTrash'=>1));
                
              return $this->successResponse([],'This user has deactivated successfully'); 
        }
        catch(\Exception $e){
            return $this->errorResponse('error occurred'); 
        }

       }
       
    
}
