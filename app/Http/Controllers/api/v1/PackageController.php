<?php

 namespace App\Http\Controllers\api\v1;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Package;
use App\Models\userpackage;
use App\Models\User;
use DB ;
use Carbon\Carbon;
use URL;
use Image ;
use Thumbnail ;
use VideoThumbnail ;
use lang ;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Crypt;



use Illuminate\Support\Facades\Storage;

class PackageController extends Controller
{
    //
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
     
    public function save_package(Request $request){      
      
           
                $userId = authguard()->id;
                $validatedData = Validator::make($request->all(),[
                    'packege_name'=>'required',
                    'title'=>'required',
                    'description'=>'required',
                    'time_limit'=>'required',
                    'posts'=>'required'
                      ]);
              
                   if($validatedData->fails()){
              
                        return $this->errorResponse($validatedData->errors()->first(), 200);
                      }

                      try{
                      $files=[];
                      $videoName='';
                      $imageName='';
                      if($request->hasfile('image')){

                        $files = $request->file('image'); 
                        $allowedfileExtension=['jpg', 'jpeg','PNG','JPEG','JPG', 'gif', 'png', 'bmp', 'svg', 'svgz', 'cgm', 'djv', 'djvu', 'ico', 'ief','jpe', 'pbm', 'pgm', 'pnm', 'ppm', 'ras', 'rgb', 'tif', 'tiff', 'wbmp', 'xbm', 'xpm', 'xwd','mp4'];
                       
                        $errors = [];
                   
                        $fileType=0 ;
                       
                        $mimeType=$files->getMimeType() ;
                         $filenamewithextension = $files->getClientOriginalName(); 
                         $extension = $files->getClientOriginalExtension();  
                        $check = in_array($extension,$allowedfileExtension);
                        $fileType = $this->checkFileType($filenamewithextension);
                       
                         $random_number=rand (1, 99);

                         if($check){
                            
                            
                            $image = new Image;
                            $getImage = $request->image;
                           $imageName = time().'.'.$getImage->extension();
                            $imagePath = public_path(). '/admin/package/images/';
                            $image->path = $imagePath;
                            $image->image = $imageName;

                            $getImage->move($imagePath, $imageName);
                         }

                              
                      
                    }
                    else{
                        $imageName='';
                    }
                   

                    if($request->hasfile('video')){
                        $files = $request->file('video');
                        $videoExtensions = ['flv','mp4','MP4','m3u8','ts','3gp','mov','avi','wmv'];
                        $extension = $files->getClientOriginalExtension();  
                        $check = in_array($extension,$allowedfileExtension);
                        if($check)
                        {
                            $video = new VideoThumbnail;
                            $getVideo = $request->video;
                            $videoName = time().'.'.$getVideo->extension();
                            $videoPath = public_path(). '/admin/package/videos/';
                            $video->path = $videoPath;
                             $video->video = $videoName;
                             $getVideo->move($videoPath, $videoName);
                        }
                      
                    }

                    else
                    {
                        $videoName='';
                    }
                  
    
                       $packege= new Package;
                       $packege->packege_name=$request->packege_name?$request->packege_name:'';
                       $packege->title=$request->title?$request->title:'';
                       $packege->image=$imageName?$imageName:'';
                       $packege->video=$videoName?$videoName:'';
                       $packege->posts=$request->posts?$request->posts:'';
                       $packege->time_limit=$request->time_limit?$request->time_limit:'';
                       $packege->description=$request->description?$request->description:'';
                       $packege->save();

                    if(!empty($packege)){  

                        
                        return $this->successResponse(array("Pakage_Details"=>$packege),__('messages.post_save_file_succ'),200);
                       }
                       else{
                        return $this->errorResponse(__('messages.package_save_file_err'), 200);
                       }
            

            } catch(Exception $e){
                return $this->errorResponse(__('messages.post_save_file_err'), 200);  
             }
    }

    public function update_package(Request $request)
        {
             
            $validatedData = Validator::make($request->all(),[
                'package_id'=>'required',
                'packege_name'=>'required',
                'title'=>'required',
                'description'=>'required',
                'time_limit'=>'required',
                'posts'=>'required'
                  ]);
              
                   if($validatedData->fails()){
              
                        return $this->errorResponse($validatedData->errors()->first(), 200);
                      }
                       
                      $packegeId=$request->package_id;
                     // $packegeId=isset($request->package_id)?$request->package_id:0 ;
                      $packege= Package::find($packegeId);


                      $files=[];
                      $videoName='';
                      $imageName='';
                    //   echo "hello=".$request->hasfile('video');
                    //   die;
                    //   dd($request->hasfile('image'));
                      if($request->hasfile('image'))
                      {

                        $files = $request->file('image'); 
                        $allowedfileExtension=['jpg', 'jpeg','PNG','JPEG','JPG', 'gif', 'png', 'bmp', 'svg', 'svgz', 'cgm', 'djv', 'djvu', 'ico', 'ief','jpe', 'pbm', 'pgm', 'pnm', 'ppm', 'ras', 'rgb', 'tif', 'tiff', 'wbmp', 'xbm', 'xpm', 'xwd','mp4'];
                       
                        $errors = [];
                   
                        $fileType=0 ;
                       
                        $mimeType=$files->getMimeType() ;
                         $filenamewithextension = $files->getClientOriginalName(); 
                         $extension = $files->getClientOriginalExtension();  
                        $check = in_array($extension,$allowedfileExtension);
                        $fileType = $this->checkFileType($filenamewithextension);
                       
                         $random_number=rand (1, 99);

                         if($check){
                            
                            
                            $image = new Image;
                            $getImage = $request->image;
                           $imageName = time().'.'.$getImage->extension();
                            $imagePath = public_path(). '/admin/package/images/';
                            $image->path = $imagePath;
                            $image->image = $imageName;

                            $getImage->move($imagePath, $imageName);
                         }

                              
                      
                    }
                    else{
                        $imageName=$packege->image;
                    }
                   

                    if($request->hasfile('video')){
                        $files = $request->file('video');
                        $videoExtensions = ['flv','mp4','MP4','m3u8','ts','3gp','mov','avi','wmv'];
                        $extension = $files->getClientOriginalExtension();  
                        $check = in_array($extension,$allowedfileExtension);
                        if($check)
                        {
                            $video = new VideoThumbnail;
                            $getVideo = $request->video;
                            $videoName = time().'.'.$getVideo->extension();
                            $videoPath = public_path(). '/admin/package/videos/';
                            $video->path = $videoPath;
                             $video->video = $videoName;
                             $getVideo->move($videoPath, $videoName);
                        }
                      
                    }

                    else
                    {
                        $videoName=$packege->video;;
                    }
                  
    



                      if(!empty($packege)){
                        $packege->packege_name=$request->packege_name?$request->packege_name:'';
                        $packege->title=$request->title?$request->title:'';
                        $packege->posts=$request->posts?$request->posts:'';
                        $packege->time_limit=$request->time_limit?$request->time_limit:'';
                        $packege->description=$request->description?$request->description:'';
                        $packege->image= $imageName? $imageName:'';
                        $packege->video=$videoName?$videoName:'';
                        $packege->save();
                        return $this->successResponse(array("Pakage_Details"=>$packege),__('messages.post_update_file_succ'),200);
                       }
                       else{
                        return $this->errorResponse(__('messages.package_not_found'), 200);
                       }

                    //  dd($packege);
        }


        // user  add package Listing
        public function userAddPackageListing()
        {
          try
          {   
            
        
          $userpackage = DB::table('userpackages')->join('packeges', 'packeges.id', '=', 'userpackages.package_id')->join('users', 'users.id', '=', 'userpackages.user_id')->orderBy('id', 'desc')->get(['userpackages.id','packeges.packege_name','packeges.title','packeges.price','packeges.description','packeges.time_limit','userpackages.status','users.name']);

           if($userpackage)
                {


                  return $this->successResponse(array("userAddeddetails"=>$userpackage),__('user Added details '),200);
                }
                else{
                 return $this->errorResponse(__('user details not found'), 200);
                }

          }
          catch(\Exception $e)
          {
            return $this->errorResponse(__('sometime wrong'.$e), 200);
            // return $this->errorResponse('This user is already exist.'.$e, 200);
          }
          
        
        
          
       
      }


      // delete user add package

      public function delete_userpackage(Request $request){
    
        $validatedData = Validator::make($request->all(),
            [
            "user_id"=>'required',
            "package_id"=>'required'
          ]); 
       
            if($validatedData->fails()){       
              return $this->errorResponse($validatedData->errors()->first(), 200);
            }

            dd($request->all());
          }
    
      
      public function delete_adduserpackage(Request $request){
    
        $validatedData = Validator::make($request->all(),
            [
            "id"=>'required'
          ]); 
       
            if($validatedData->fails()){       
              return $this->errorResponse($validatedData->errors()->first(), 200);
            }
            $userpackageId=isset($request->id)?$request->id:0 ;
            $packagepakagageData = userpackage::where('id',$userpackageId)->first();

        if(!empty($packagepakagageData))
        {
          $package = userpackage::where('id',$userpackageId)->delete();
         //print_r(DB::getQueryLog());
          return $this->successResponse(array("packageId"=>$userpackageId),__('Package deleted'),200);  
        }
        else{
          return $this->successResponse(array("packageId"=>$userpackageId),__('Package deleted already'),200);  
         
        }
        
       
       
       }

        // add package to user
        public function addPackage(Request $request)
        {
             
          $validatedData = Validator::make($request->all(),[
            'user_id'=>'required',
            'package_id'=>'required',
            
              ]);
          
               if($validatedData->fails()){
          
                    return $this->errorResponse($validatedData->errors()->first(), 200);
                }
                $packagedetails = userpackage::where('package_id',$request->package_id)->where('user_id',$request->user_id)->get()->toArray();
            
                if(!empty($packagedetails))
                {
                  return $this->errorResponse(__('Already exists package in user'), 200);
                }
                else{
                  $packege= userpackage::create($request->all());
                }
               
               
                  if(!empty($packege))
                  {
               return $this->successResponse(array("addpackage"=>$packege),__('package added sucessfully'),200);
              }
              else{
               return $this->errorResponse(__('messages.package_not_found'), 200);
              }
                
           

          }




          public function paymentStatusbyUserpackage(Request $request){      
      
            $validator = Validator::make($request->all(), [        
              'name' => 'required',
              'email' => 'required',
              'phoneNumber' => 'required',
              'countryId'=>'required',
              'country_code'=>'required',
              'address'=>'required',
              'userpackege_id'=>'required',
              'sponser_id'=>'required',
              ]);
          
   
                                        
              if($validator->fails())
                {        
                return $this->errorResponse($validator->errors()->first(), 200);
                }
              
                $userpackege_id=$request->userpackege_id;
                $sponser_id=$request->sponser_id;

              $user=User::where('id',$request->sponser_id)->update(['name'=>$request->name ,'email'=>$request->email,'phoneNumber'=>$request->phoneNumber,'country_code'=>$request->countryId,'address'=>$request->address,'countryId'=>$request->countryId ]);
                if(!empty($user))
                {
                  $userpackageData=userpackage::where('id',$userpackege_id)->first();
                  $encrypted_id = Crypt::encryptString($userpackageData->id);
                  $payment_url="https://walkofweb.in/checkoutPage/$encrypted_id";
                  $package_status=userpackage::where('id',$userpackege_id)->update(array('status'=>3,'sponsor_id'=>$sponser_id,'payment_url'=>$payment_url));
          
                  if(!empty($package_status))
                    {
                    $address_details=userpackage::join('users', 'userpackages.sponsor_id', 'users.id')->leftjoin('pe_countries', 'pe_countries.id', '=', 'users.countryId')->leftjoin('packeges', 'packeges.id', '=', 'userpackages.package_id')->select('users.id as user_id','users.name','users.username','users.email','users.phoneNumber','users.countryId','users.address','users.country_code','pe_countries.title','packeges.packege_name','packeges.price','packeges.time_limit','userpackages.id as userpackage_id','userpackages.status','userpackages.payment_url')->where('userpackages.id',$request->userpackege_id)->where('userpackages.status','=',3)->first()->toArray();

                   

                    return $this->successResponse(array("Hire User By SponserListing"=>$address_details),__('Payment status Update sucessfully'),200);
                  }
                  else{
                      return $this->errorResponse(__('messages.Error in update payment status'), 400);
                    }
                

              }
              else{
                return $this->errorResponse(__('messages.Error in update payment status'), 400);
            }

          }
          
       

          //fetch user details

          public function userdetails()
          {
            
            try
            {   
              $user_details=User::join('user_fb_page_info', 'user_fb_page_info.user_id', '=', 'users.id')->orderBy('id', 'desc')->get(['users.id','users.encryption','users.email','users.name','users.username','users.instagram_username','users.facebook_username','user_fb_page_info.page_followers','user_fb_page_info.page_fan_count']); 

              if($user_details)
                    {


                      return $this->successResponse(array("user_details"=>$user_details),__('user details '),200);
                    }
                    else{
                     return $this->errorResponse(__('user details not found'), 200);
                    }
                    

            }
            catch(\Exception $e)
            {
              return $this->errorResponse(__('somet'.$e), 200);
              // return $this->errorResponse('This user is already exist.'.$e, 200);
            }
          }

                
          // get package details according  to user
          
          public function userbyPackage(Request $request)
          {
               
            $validatedData = Validator::make($request->all(),[
              'user_id'=>'required',
              
              
                ]);
            
                 if($validatedData->fails()){
            
                      return $this->errorResponse($validatedData->errors()->first(), 200);
                  }

                  try
                  {        	
                      
                  $user_id= $request->user_id;

                    $packagedetails=userpackage::join('packeges', 'packeges.id', '=', 'userpackages.package_id')->orderBy('userpackages.id', 'desc')->where('userpackages.user_id',$user_id)->get(['userpackages.id','userpackages.package_id','userpackages.user_id','packeges.id as package_id','packeges.title','packeges.packege_name','packeges.price','packeges.description','packeges.posts','packeges.time_limit']);
                   
                    if($packagedetails)
                    {


                      return $this->successResponse(array("Pakage_Details"=>$packagedetails),__('package details according to user'),200);
                    }
                    else{
                     return $this->errorResponse(__('package details according to user not found'), 200);
                    }
                    

                    
                  }
                  catch(\Exception $e)
                  {
                    return $this->errorResponse(__('something  error'.$e), 200);
                    // return $this->errorResponse('This user is already exist.'.$e, 200);
                  }

                }



         public function delete_package(Request $request){
    
            $validatedData = Validator::make($request->all(),
                [
                "id"=>'required'
              ]); 
       
              if($validatedData->fails()){       
                return $this->errorResponse($validatedData->errors()->first(), 200);
              }
      
    
             $packageData = Package::where('id',$request->id)->first();
             $packageId=isset($request->id)?$request->id:0 ;
            // dd($packageData);
            if(!empty($packageData))
            {
              
             
              
             $package = Package::where('id',$packageId)->delete();
               //print_r(DB::getQueryLog());
                return $this->successResponse(array("Delete package"=>$packageId),__('messages.Package_deleted'),200);  
            }
            else{
              
              return $this->successResponse(__('Package already deleted'),200); 
            }
       
       }


    public function createThumbnail($path, $width, $height)
    {
      
      $img = Image::make($path)->resize($width, $height)->save($path);
    }
    public function package_details()
    {
        $packages = Package::orderBy('id', 'desc')->get()->toArray();
        return $this->successResponse(array("packages"=>$packages), __('messages.package_get_file_succ'),200);
    }
    public function packegeDetailsbyid(Request $request)
    {
        $validatedData = Validator::make($request->all(),[
            'id'=>'required'
              ]);

        if($validatedData->fails()){
            return $this->errorResponse($validatedData->errors()->first(), 200);
        }
        $id=$request->id;
        if($id){
            try{
       
                $package = Package::find($id);
                return $this->successResponse(array("package"=>$package), __('messages.package_get_file_succ'),200);
             }
             catch(Exception $e){
             return $this->errorResponse(__('messages.package_Records not_found'), 200);  
            }
        }
        else{
            return $this->errorResponse(__('messages.package_id_required'), 200);
        }
       
     
    }

}
