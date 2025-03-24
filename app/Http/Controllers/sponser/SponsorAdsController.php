<?php

namespace App\Http\Controllers\sponser;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use DB ; 
use App\models\countries ;
use App\Models\Advertisement;
use Image ;
use Illuminate\Support\Facades\Validator;

class SponsorAdsController extends Controller
{
    
    public function sponsorAdsList(Request $request){
       
        $data['title']=siteTitle();
        $data['sponser']=DB::table('sponser')->where('status',1)->get();
        echo view('Sponser/adsManagement/index',$data);

    }    

    public function ads_datatable(Request $request){
        // select adv.id,sp.name,adv.title,adv.image,case when adv.createdBy,adv.start_date,adv.end_date,adv.isAccept,adv.status,adv.createdOn from advertisements as adv
        // inner join sponser as sp on sp.id=adv.Sponser_id 
        $data['title']=siteTitle();
        $sponserImg = config('constants.sponser_image'); 
        $adsImg = config('constants.advertisement_image'); 
        $carQry="select ads.id,s.name,case when s.image is null then '' else concat('".$sponserImg."',s.image) end as sponserIcon,ads.title,
        case when ads.ad_type=1 then 'image' when ads.ad_type=2 then 'video' else '' end as adType,
        case when (ads.image is null || ads.image='') then '' else concat('".$adsImg."',ads.image) end as ads,ads.start_date,ads.end_date,case when ads.createdBy=1 then 'Admin' else 'User' end as createdBy,case when isAccept=0 then 'Pending' when isAccept=1 then 'Approved' when isAccept=2 then 'Rejected' else 'Expired' end as isAccept,case when ads.status=1 then 'Active' else 'Inactive' end as status_, ads.status from advertisements as ads left join sponser as s on s.id=ads.sponser_id where ads.start_date is not null and ads.end_date is not null and ads.isAccept!=4" ;   
                
        $carData = DB::select($carQry); 
        $tableData = Datatables::of($carData)->make(true);  
        return $tableData; 
    }

    public function adsStatus(Request $request)
    {

        $id=$request->id ;

        $qry="update advertisements set status=(case when status=1 then 0 else 1 end) where id=".$id;

        try{

            DB::select($qry);    
            echo successResponse([],'changed status successfully'); 
         
        }
         catch(\Exception $e)
        {
          echo errorResponse('error occurred'); 
         
        }

    }

    public function editAds(Request $request){

         $updatedId = isset($request->updatedId)?$request->updatedId:0 ;
         
         $advertisement = DB::table('advertisements')->where('id',$updatedId)->first() ;
         $data['sponser']=DB::table('sponser')->where('status',1)->get();
         $data['advertisement'] = $advertisement ;
         $data['updatedId']=$updatedId ;

        echo view('sponser/adsManagement/editAds',$data);
  }

  public function updateAds(Request $request){
        $data['title']=siteTitle();
        $updatedId = isset($request->updatedId)?$request->updatedId:0 ;
        $sponser_=isset($request->edit_sponser_)?$request->edit_sponser_:'' ;
        $adsTitle = isset($request->edit_ads_title)?$request->edit_ads_title:'' ;
        $adsType = isset($request->edit_ads_type)?$request->edit_ads_type:'' ;
        $startDate = isset($request->edit_startDate)?$request->edit_startDate:'' ;
        $endDate = isset($request->edit_endDate)?$request->edit_endDate:'' ;
        $isAccept = isset($request->is_accept)?$request->is_accept:0 ;
        $filenametostore='';
        

        $introduction = isset($request->introduction)?$request->introduction:'' ;
        $objectives = isset($request->objectives)?$request->objectives:'' ;
        $media_mix = isset($request->media_mix)?$request->media_mix:'' ;
        $measurement = isset($request->measurement)?$request->measurement:'' ;
        $conclusion = isset($request->conclusion)?$request->conclusion:'' ;
        $target_audience = isset($request->target_audience)?$request->target_audience:'' ;
        $media_sample = isset($request->media_sample)?$request->media_sample:'' ;
        $is_accept = isset($request->is_accept)?$request->is_accept:'' ;
        
        $type = isset($request->type)?$request->type:0 ;

        
    
        $updateData=array(
            'sponser_id'=>$sponser_ ,
            'title'=>$adsTitle,
            'ad_type'=>$adsType ,  
            'start_date'=>$startDate ,  
            'end_date'=>$endDate ,
            'isAccept'=>$is_accept        
        );
        
        if($type==1){
            $updateData['introduction']=$introduction ;
            $updateData['objectives']=$objectives ;
            $updateData['media_mix']=$media_mix ;
            $updateData['measurement']=$measurement ;
            $updateData['conclusion']=$conclusion ;
            $updateData['target_audience']=$target_audience ;
            $updateData['media_sample']=$media_sample ;
            $updateData['isAccept']=$is_accept ;
        }

        
        try{
            
            if($request->hasFile('edit_adsFile')) {
        
       
                $imgPath='app/public/sponser_image/' ;
                
              
                 $filenamewithextension = $request->file('edit_adsFile')->getClientOriginalName();
           
                 //get filename without extension
                 $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
           
                 //get file extension
                 $extension = $request->file('edit_adsFile')->getClientOriginalExtension();           
                 $filename=str_replace(' ', '_', $filename);
                 $filenametostore = $filename.'_'.time().'.'.$extension;       
                 $smallthumbnail = $filename.'_100_100_'.time().'.'.$extension;    
                
                 //Upload File
                 $request->file('edit_adsFile')->storeAs('public/sponser_image', $filenametostore);
                // $request->file('sImage')->storeAs('public/star_type_img/thumb', $smallthumbnail); 
                 //create small thumbnail
                // $smallthumbnailpath = public_path('storage/star_type_img/thumb/'.$smallthumbnail);
                // $this->createThumbnail($smallthumbnailpath, 100, 100);                 
                $checkPV = DB::table('advertisements')->select(DB::raw('case when image is null then "" else image end as image'))->where('id',$updatedId)->first();
                if(isset($checkPV->image) && $checkPV->image!=''){
                    $unlinkPath = storage_path($imgPath.$checkPV->image) ;
                    do_upload_unlink(array($unlinkPath));
                }
                $updateData['image']=$filenametostore;
                }  		
            DB::table('advertisements')->where('id',$updatedId)->update($updateData);
           echo successResponse([],'Updated successfully'); 

        }
         catch(\Exception $e)
        {
          echo errorResponse('error occurred'); 
         
        }
  }

  public function SaveAdvertisement(Request $request){

    $data['title']=siteTitle();
    
    $sponser_=isset($request->sponser_)?$request->sponser_:'' ;
    $adsTitle = isset($request->ads_title)?$request->ads_title:'' ;
    $adsType = isset($request->ads_type)?$request->ads_type:'' ;
    $startDate = isset($request->startDate)?$request->startDate:'' ;
    $endDate = isset($request->endDate)?$request->endDate:'' ;
    $filenametostore='';

    $insertData=array(
        'sponser_id'=>$sponser_ ,
        'title'=>$adsTitle,
        'ad_type'=>$adsType ,  
        'start_date'=>$startDate ,  
        'end_date'=>$endDate         
    );

    if($adsType==1){
            $validatedData = Validator::make($request->all(),[ "adsFile"=>'file|mimes:jpg,png']);            
        }else if($adsType==2){
            $validatedData = Validator::make($request->all(),[ "adsFile"=>'file|mimes:mp4']);
        }

        if($validatedData->fails()){       
            return $this->errorResponse($validatedData->errors()->first(), 200);
          }
          
    		
    try{
        
        if($request->hasFile('adsFile')) {
            $imgPath='app/public/sponser_image/' ;  
            $filenamewithextension = $request->file('adsFile')->getClientOriginalName();
             //get filename without extension
            $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
             //get file extension
            $extension = $request->file('adsFile')->getClientOriginalExtension();
             $filename=str_replace(' ', '_', $filename);
             $filenametostore = $filename.'_'.time().'.'.$extension;       
             $smallthumbnail = $filename.'_100_100_'.time().'.'.$extension; 
             //Upload File
             $request->file('adsFile')->storeAs('public/sponser_image', $filenametostore);
            $insertData['image']=$filenametostore;
            }
       DB::table('advertisements')->insert($insertData);
       echo successResponse([],'Save successfully'); 

    }
     catch(\Exception $e)
    {
      echo errorResponse('error occurred'); 
     
    }

  }

  public function adsDelete(Request $request){

    $deleteId=isset($request->id)?$request->id:'' ;
    try{
            DB::table('advertisements')->where('id', $deleteId)->delete();

          echo successResponse([],'successfully deleted'); 
    }
     catch(\Exception $e){
         echo errorResponse('error occurred'); 
     }

}



public function advertisementDetail(Request $request){

    $data['title']=siteTitle();
   
    $updatedId = isset($request->id)?$request->id:0 ;
   $advertisement = DB::table('advertisements')->where('id',$updatedId)->first() ;
//    $advertisement = Advertisement::select('advertisements.id,advertisements.sponser_id,advertisements.title,advertisements.ad_type,advertisements.image,advertisements.status,advertisements.start_date,advertisements.end_date,advertisements.introduction,advertisements.objectives,advertisements.media_mix,advertisements.conclusion,advertisements.target_audience,advertisements.media_sample,advertisements.isAccept,advertisements.createdOn,advertisements.createdBy,sponser.id as sponser_id,sponser.name,sponser.image,sponser.description,sponser.status')->leftjoin("sponser","advertisements.sponser_id","sponser.id")->where('advertisements.id',$updatedId)->first() ;
//    dd($advertisement);

$carQry="select ads.id,ads.sponser_id,ads.title,ads.ad_type,ads.image,ads.status,ads.start_date,ads.end_date,ads.introduction,ads.objectives,ads.media_mix,ads.conclusion,ads.target_audience,ads.media_sample,ads.isAccept,ads.createdOn,ads.createdBy,s.id as sponser_id,s.name,s.image,s.description,s.status from advertisements as ads left join sponser as s on s.id=ads.sponser_id where ads.start_date is not null and ads.end_date is not null and ads.isAccept!=4 and ads.id='".$updatedId."'" ;   
        
 $carData = DB::select($carQry);

    $data['sponser']=DB::table('sponser')->where('status',1)->get();
    $data['advertisement'] = $carData[0] ;
    $data['updatedId']=$updatedId ;

    echo view('admin/adsManagement/advertisementDetail',$data);

}

public function sendSubscribersEmail(Request $request){

    $updatedId = isset($request->updatedId)?$request->updatedId:0 ;
    $subscriber = DB::table('user_subscriber')->where('id',$updatedId)->first() ;  
    $data['subscriber']=$subscriber ;
   echo view('admin/subscriptions/mailSubscribers',$data);

}

public function sendSubscriberMail(Request $request){

    $id=$request->updatedId ;
    $sName=$request->subscriberName ;
    $sMessage = $request->subscriber_message ;
    $sSubject = $request->subscriber_subject ;
    $sEmail = $request->subscriberEmail ;

    $insertData=array(
        "subscriberId"=>$id,
        "subject"=>$sSubject,
    	"message"=>$sMessage            
    );
    DB::table('subscriber_mail')->insert($insertData);

    $data=array(
        'email' => $sEmail,
        'subject' => $sSubject,
        'message' => $sMessage,
        'type'=>'user_subscribers'
       );
        
    $data=sendPasswordToEmail($data);
    echo successResponse([],'Successfully sent E-mail to subscribers.'); 
  

}


}
