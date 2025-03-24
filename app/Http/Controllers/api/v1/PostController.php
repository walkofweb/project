<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Http\Request;
use App\Models\Post_comment;
use App\Models\Post_image;
use App\Models\Post_like;
use App\Models\Post;
use App\Models\User;
use DB ;
use Carbon\Carbon;
use Image ;
use Thumbnail ;
use VideoThumbnail ;
use URL;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Redirect;


class postController extends Controller
{
    

  /**
 * @OA\Info(
 *    title="Your super  ApplicationAPI",
 *    version="1.0.0",
 * )
 */
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



function convertObjectToArray($data) {
    if (is_object($data)) {
        $data = get_object_vars($data);
    }

    if (is_array($data)) {
        $d = array_map(__FUNCTION__, $data);
        print_r($d);
    }

print_r($data);
    return $data;
}


    public function save_post(Request $request){      
   // requestLog(json_encode($request->all()));
      
		   $userId = authguard()->id;
  

	      try{


	     $file_path=array();
	     $insert=array();
       $request->message = isset($request->message)?$request->message:'' ;
       $flag=0 ;
        $date = date('Y-m-d H:i:s', time());
	     $post=Post::create(['userId'=>$userId,'message'=>$request->message,'createdOn'=>$date]);
      
	     Post::where('id',$post->id)->update(['encryption'=>md5('wow_intigate_23'.$post->id)]) ;
       
      
	
			

  //   if($request->hasfile('image')){
   
  //   $allowedfileExtension=['jpg', 'jpeg','PNG','JPEG','JPG', 'gif', 'png', 'bmp', 'svg', 'svgz', 'cgm', 'djv', 'djvu', 'ico', 'ief','jpe', 'pbm', 'pgm', 'pnm', 'ppm', 'ras', 'rgb', 'tif', 'tiff', 'wbmp', 'xbm', 'xpm', 'xwd','flv','MP4','mp4','m3u8','ts','3gp','mov','avi','wmv','mp3'];

		// $files = $request->file('image'); 
		// $errors = [];


		// 	foreach($files as $file)
		// 	{
  //       $fileType=0 ;

  //       $mimeType=$file->getMimeType() ;
        
       
         
		// 		 $filenamewithextension = $file->getClientOriginalName(); 
		// 		 $extension = $file->getClientOriginalExtension();  
        
		// 	   $check = in_array($extension,$allowedfileExtension);
  //        $fileType = $this->checkFileType($filenamewithextension);

		// 	     if($check){

		// 	     	$filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
		// 	        $filename=str_replace(' ', '_', $filename);
		// 	        $filenametostore = $filename.'_'.time().'.'.$extension;       
			       
			       
			        
		// 	        $file->storeAs('public/post_image/'.$post->id.'/', $filenametostore);
  //             /* start thumbnail*/
              
  //             if($fileType==1){
  //              $smallthumbnail = $filename.'_100_100_'.time().'.jpg';    
  //              $file->storeAs('public/post_image/'.$post->id.'/', $smallthumbnail);
  //              $smallthumbnailpath = public_path('storage/post_image/'.$post->id.'/'.$smallthumbnail);
  //             $this->createThumbnail($smallthumbnailpath, 100, 100);
             
  //             }else if($fileType==2){
  //               $smallthumbnail = $filename.time().'.jpg';    
  //               $destination_path = storage_path('app/public').'/post_image/'.$post->id.'/';
  //              $thumbnail_path   = storage_path('app/public').'/post_image/'.$post->id.'/' ;
  //               $video_path       = $destination_path.$filenametostore;        
  //              // $thumbnail_status = Thumbnail::getThumbnail($video_path,$thumbnail_path,$smallthumbnail);

  //               VideoThumbnail::createThumbnail(
  //               storage_path('app/public').'/post_image/'.$post->id.'/'.$filenametostore, 
  //               storage_path('app/public').'/post_image/'.$post->id.'/', 
  //               $smallthumbnail, 
  //               2, 
  //               640, 
  //               480
  //               );
                

  //             }else{
  //               $smallthumbnail ='';    
  //             }
              
  //             /*end thumbnail*/
  //      				$insert[]=array(
  //                   'user_id'=>$userId,
		// 		            'postId'=>$post->id,
		// 		            'image'=>$filenametostore,
  //                   'file_type'=>$fileType,
  //                   'thumbnail'=>$smallthumbnail
		// 		           );

  //             $file_path[]= url('/').'/public/storage/post_image/'.$post->id.'/'.$filenametostore;       
		// 	     }else{
  //           return $this->errorResponse('The '.$extension.' extension is not allowed', 200);
  //          }
  //      		}

  //      		 if(!empty($insert)){       		 
            
  //      		 	Post_image::insert($insert);
  //      		 }

  //           }	    
             //requestLog(json_encode(array("postId"=>$post->id)));
	        return $this->successResponse(array("postId"=>$post->id),__('messages.post_save_succ'),200);   
	     } catch(Exception $e){
	      	 return $this->errorResponse(__('messages.post_save_err'), 200);	
	      }
    }

    public function postFileUpload(Request $request){
     
      requestLog(json_encode($request->all()));
      $userId = authguard()->id;
      $validatedData = Validator::make($request->all(),[
      'postId'=>'required'
        ]);

     if($validatedData->fails()){

          return $this->errorResponse($validatedData->errors()->first(), 200);
        }


       try{

       $postId=$request->postId ;
     
       $fileDuration=isset($request->fileDuration)?$request->fileDuration:0 ;

       $file_path=array();
       $insert=array();
     

       $allowedfileExtension=['jpg', 'jpeg', 'gif', 'png', 'bmp', 'svg', 'svgz', 'cgm', 'djv', 'djvu', 'ico', 'ief','jpe', 'pbm', 'pgm', 'pnm', 'ppm', 'ras', 'rgb', 'tif', 'tiff', 'wbmp', 'xbm', 'xpm', 'xwd','flv','mp4','m3u8','ts','3gp','mov','avi','wmv','mp3'];
       //echo "<pre>";
      // print_r($_FILES);
      // print_r($request->hasfile());
      // exit ;
       // $files = $request->file('image'); 
       // print_r($files);
       // exit ;
     if($request->hasfile('image')){

        $files = $request->file('image'); 
       
        $errors = [];
        // foreach($files as $file)
        // {
        $fileType=0 ;

        $mimeType=$files->getMimeType() ;
        $salt1      = bin2hex(openssl_random_pseudo_bytes(22));
         $filenamewithextension = $salt1.'.'.$files->getClientOriginalName(); 
         $extension = $files->getClientOriginalExtension(); 
         
         
         $image = $request->file('image');
         
        
         

         $check = in_array($extension,$allowedfileExtension);
     
         $fileType = $this->checkFileType($filenamewithextension);
  
           if($check){

            $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
              $filename=str_replace(' ', '_', $filename);
              $filenametostore = $filename.'_'.time().'.'.$extension;       
             
             
              
              $files->storeAs('public/post_image/'.$postId.'/', $filenametostore);
              /* start thumbnail*/
              
              if($fileType==1){
                $smallthumbnail = $filename.'_100_100_'.time().'.jpg';    
                $files->storeAs('public/post_image/'.$postId.'/', $smallthumbnail);
                $smallthumbnailpath = public_path('storage/post_image/'.$postId.'/'.$smallthumbnail);
              $destinationPath = public_path('storage/post_image/'.$postId.'/'.$smallthumbnail);
             
          //  $image->move($smallthumbnail, $filenamewithextension);
               $img = Image::make($files->getRealPath());
               $img->resize(100, 100);
               $img->save($filenamewithextension);
           
             // $this->createThumbnail($smallthumbnailpath, 100, 100);
             
              }else if($fileType==2){
                $smallthumbnail = $filename.'_100_100_'.time().'.jpg';    
                $destination_path = storage_path('app/public').'/post_image/'.$postId.'/';
               $thumbnail_path   = storage_path('app/public').'/post_image/'.$postId.'/' ;
                $video_path       = $destination_path.$filenametostore;        
                //$thumbnail_status = Thumbnail::getThumbnail($video_path,$thumbnail_path,$smallthumbnail);

                 VideoThumbnail::createThumbnail(
                storage_path('app/public').'/post_image/'.$postId.'/'.$filenametostore, 
                storage_path('app/public').'/post_image/'.$postId.'/', 
                $smallthumbnail, 
                1, 
                1920, 
                1035
                );
              

              }else{
                $smallthumbnail ='';    
              }
              
              /*end thumbnail*/
              $insert[]=array(
                    'user_id'=>$userId,
                    'postId'=>$postId,
                    'image'=>$filenametostore,
                    'file_type'=>$fileType,
                    'thumbnail'=>$smallthumbnail,
                    'fileDuration'=>$fileDuration
                   );

              $file_path= url('/').'/storage/app/public/post_image/'.$postId.'/'.$filenametostore;       
           }else{
            return $this->errorResponse('The '.$extension.' extension is not allowed', 200);  
           }
          
          
           if(!empty($insert)){  

            Post_image::insert($insert);
           }


            return $this->successResponse(array("postId"=>$postId,"file"=>$file_path),__('messages.post_save_file_succ'),200);
            }else{
              return $this->errorResponse('Invalid request', 200);  
            }    
            
       } catch(Exception $e){
           return $this->errorResponse(__('messages.post_save_file_err'), 200);  
        }
    }


       public function createThumbnail($path, $width, $height)
    {
      
      $img = Image::make($path)->resize($width, $height)->save($path);
    }


    public function save_comment(Request $request){

    	$validatedData = Validator::make($request->all(),[
    		'postId'=>'required|numeric',
    		'parentId'=>'required',
    		'comment' => 'required'
    	]);

       $comment_type=0 ;
       $fileName='';
       $fileDuration=isset($request->fileDuration)?$request->fileDuration:0 ;

    	 if($validatedData->fails()){       
            return $this->errorResponse($validatedData->errors()->first(), 200);
          }
          if(isset($_FILES['comment'])){
             $d=array(
            "parentId"=>$request->parentId ,
            "postId"=>$request->postId ,
            "file"=>json_encode($_FILES['comment'])
          );
          $iData=array(
            "data"=>json_encode($d)            
          );

          
          DB::table('image_log')->insert($iData);
          }
         

          if($request->hasfile('comment')){
            $path='comment_file/'.$request->postId ;
            $commentFile=$this->uploadImage('comment',$path,$request);
           
            if(!empty($commentFile)){
                $fileName=isset($commentFile['fileName'])?$commentFile['fileName']:'' ;
                $comment_type=isset($commentFile['fileType'])?$commentFile['fileType']:0 ;
            }
            
          }

           
          $userId = authguard()->id ;
          $request['userId'] = $userId ;
          
          if($fileName==''){
            $fileName = $request->comment ;
          }
                       
          $request['comment_type'] = $comment_type ;
          $insertData=array(
            "userId"=>$userId ,
            "parentId"=>$request->parentId ,
            "postId"=>$request->postId ,
            "comment_type"=>$comment_type ,
            "comment"=>$fileName ,
            "fileDuration"=>$fileDuration,
            "status"=>1
          );

          if(DB::table("post_comments")->insert($insertData)){           
           $data= $this->sendCommentN($request->postId,$userId);
          
            //->where('userId',$userId)
            $totalComment=Post_comment::where('postId',$request->postId)->get()->toArray();
         
            $totalComment=Post_comment::all()->where('postId',$request->postId)->count();


            $response=array("postId"=>$request->postId,"totalComment"=>$totalComment);
          	return $this->successResponse($response,__('messages.post_save_comment_succ'),200);   
          }else{
            die("hhh");
          	 return $this->errorResponse(__('messages.post_save_comment_err'), 200);
          }
    }
    
   //
    public function post_list(Request $request){
      $data=[];
      $star_imgPath=config('constants.star_image') ;
      $filePath = config('constants.user_image') ;
      $image = DB::raw('case when concat("'.$filePath.'",users.image) is null then "" else concat("'.$filePath.'",users.image) end as image') ;
      $strImg=DB::raw('concat("'.$star_imgPath.'",star_img) as starImg');
      $page = $request->has('page') ? $request->get('page') : 1;
      $limit = $request->has('per_page') ? $request->get('per_page') : 10;
      $offset = ($page - 1) * $limit ;
     
     $userId=authguard()->id ;
     
     // $postList = Cache('postList_2191');
     // print_r($postList);
     // exit ;
       //$postList = Redis::get('postList_'.$userId.$page);
        // if(isset($postList)) {

        // return $this->successResponse(json_decode($postList, FALSE),'Post List',200);

        // }else{
     $followFollwerUser=User::getAllFollowingAndFollowersId($userId);
     $followFollwerUser[]=$userId ;
    //  echo "<pre>";
    //  print_r($followFollwerUser);
    //  exit ;
     $isPrivate=authguard()->isPrivate ;
    
     //
    //   if($isPrivate==1){
    //      $comment=Post::select('posts.id','userId','isPrivate','posts.encryption','posts.message','users.name',DB::raw('concat("@",username) as username'),'users.rank_type',$image,$strImg,'posts.createdOn')->join('users','users.id','=','posts.userId')->join('rank_types','rank_types.id','=','users.rank_type')
    //   ->where('posts.status',1)->where('users.isTrash',0)
    //   ->whereIn('posts.userId', $followFollwerUser)
    //   ->orderBy('posts.id', 'desc')->skip($offset)->take($limit)->get() ;
    // }else{

    //  $getPostPrivateUsr=DB::table("posts")->join('users','users.id','=','posts.userId')->where("users.isPrivate",1)->pluck(DB::raw("distinct users.id as privateUser"))->toArray();
      //$notFollowAndPrivateUser=array_diff($getPostPrivateUsr,$followFollwerUser);

    //   $comment=Post::select('posts.id','userId','isPrivate','posts.encryption','posts.message','users.name',DB::raw('concat("@",username) as username'),'users.rank_type',$image,$strImg,'posts.createdOn')->join('users','users.id','=','posts.userId')->join('rank_types','rank_types.id','=','users.rank_type')
    //   ->where('posts.status',1)->where('users.isTrash',0) 
    //    ->whereNotIn('posts.userId', $notFollowAndPrivateUser)    
    //   ->orderBy('posts.id', 'desc')->skip($offset)->take($limit)->get() ;
    // }
     $limit = $request->has('per_page') ? $request->get('per_page') : 10;
      $offset = ($page - 1) * $limit ;
      $star_imgPath=config('constants.star_image') ;
      $filePath = config('constants.user_image') ;
     
       $image = DB::raw('case when concat("'.$filePath.'",users.image) is null then "" else concat("'.$filePath.'",users.image) end as image') ;
      
      
      $strImg=DB::raw('concat("'.$star_imgPath.'",star_img) as starImg');
     
 $page = $request->has('page') ? $request->get('page') : 1;
     $comment = Cache::remember('postList_3'.$userId.$page, 10 , function () use ($limit,$offset,$star_imgPath,$filePath,$image,$strImg,$userId,$page,$followFollwerUser){

     // echo 'postList_1'.$userId.$page ;
    
        return Post::select('posts.id','userId',DB::raw('users.encryption as userEncryption'),'isPrivate','posts.encryption','posts.message','users.rank_','users.name',DB::raw('concat("@",username) as username'),'users.rank_type',$image,$strImg,'posts.createdOn')->join('users','users.id','=','posts.userId')->leftJoin('rank_types','rank_types.id','=','users.rank_type')
      ->where('posts.status',1)->where('users.isTrash',0)->whereIn('posts.userId',$followFollwerUser)      
      ->orderBy('posts.id', 'desc')->skip($offset)->take($limit)->get()->toArray() ;
});

    	
      //   $comment=Post::select('posts.id','userId','isPrivate','posts.encryption','posts.message','users.name',DB::raw('concat("@",username) as username'),'users.rank_type',$image,$strImg,'posts.createdOn')->join('users','users.id','=','posts.userId')->join('rank_types','rank_types.id','=','users.rank_type')
      //  ->where('posts.status',1)->where('users.isTrash',0)      
      //  ->orderBy('posts.id', 'desc')->skip($offset)->take($limit)->get() ;
     
//limit ".$limit." offset ".$offset

      $response=array();
      $postLike = new Post_like();
      $postImage = new Post_image();
       $starImg = config('constants.star_image');
if(!empty($comment))
{
      
      foreach ($comment as $key => $value) {
        $postId=$value['id'] ; 


       
         $isPrivate=$value['isPrivate'] ; 
         $uId=$value['userId'] ;
        // if($isPrivate==1){
        //   $checkFollowOrNot=User::checkFollowOrFollower($userId,$uId);
        //   if(!$checkFollowOrNot){
        //     continue ;
        //   }
        // }    
            $rankT = getRankType($value['rank_']);
       

   //  exit ;
      $rankImg = DB::table('rank_types')->select('rank_title',DB::raw('concat("'.$starImg.'",star_img) as starImg'))->where('id',$rankT)->where('status',1)->first() ;
      $value['starImg']=isset($rankImg->starImg)?$rankImg->starImg:'' ;
      $value['rank_type']=$rankT ;//
      
        $totalComment =Post_comment::all()->where('postId',$postId)->where('status',1)->count();
       
        $value['totalComment'] = $totalComment ;        
        $post_image = $postImage->getPostImage($postId);
        $value['postImage'] = $post_image ;
        $value['isLike']=$postLike->isLikeOrNot($postId,$userId,0);      
        $value['totalLike'] =$postLike->getTotalLike($postId);
       
        $date = Carbon::parse($value['createdOn']); // now date is a carbon instance
        $elapsed = $date->diffForHumans(Carbon::now());
       
       // $elapsed=createdAt($elapsed) ;
       
        $value['shareUrl'] = config('constants.post_share').$value['encryption'];
        $value['createdOn'] =$elapsed ; 


        $response[]=$value ;
      }
     
    }
    else{
      return $this->errorResponse("empty comment", 200);
    }
      $totalRecord=Post::select('id')->where('posts.status',1)->join('users','users.id','=','posts.userId')->where('users.isTrash',0)->count();   
      
      $data=array() ;
      $data['response']=$response ;
      if(($offset+$limit) < $totalRecord){
        $data['isShowMore']=true ;  
      }else{
        $data['isShowMore']=false ;  
      }

      $data['page']=$page ; 
      //Redis::set('postList_'.$userId.$page, json_encode($data));
      return $this->successResponse($data,'Post List',200);  
      
      //} 
    }

   public function add_like(Request $request){
   	  $validatedData = Validator::make($request->all(),[
   	  	"postId"=>'required',
   	  	"commentId"=>'required'
   	  ]);

  	 if($validatedData->fails()){       
      return $this->errorResponse($validatedData->errors()->first(), 200);
    }

    $userId = authguard()->id ; 

    if($request->commentId==0){
     $data=Post_like::all()->where('post_id',$request->postId)->where('user_id',$userId)->where('comment_id',0)->first();   
    }else{
     $data=Post_like::all()->where('post_id',$request->postId)->where('comment_id',$request->commentId)->where('user_id',$userId)->first();    
    }
    
    if(!empty($data)){
      $isLike = ($data->isLike==1)?0:1 ;
      if($request->commentId==0){
      	Post_like::where('post_id',$request->postId)->where('comment_id',$request->commentId)->where('user_id',$userId)->update(['isLike'=>$isLike]);	
      }else{
       // 
      	Post_like::where('post_id',$request->postId)->where('comment_id',$request->commentId)->where('user_id',$userId)->update(['isLike'=>$isLike]);
        //
      }
      
    }else{
    
  
      Post_like::create(['post_id'=>$request->postId,'comment_id'=>$request->commentId,'user_id'=>$userId,'isLike'=>1]);
       $this->sendCommentN($request->postId,$userId,3);
     
      
    }

     if(!empty($data) && $request->commentId==0 && $data->isLike==0){
        $this->sendCommentN($request->postId,$userId,2);
      }else if(!empty($data) && $data->isLike==0){
        $this->sendCommentN($request->postId,$userId,3);
      }

    if($request->commentId==0){
      $isLike=Post_like::select('isLike')->where('post_id',$request->postId)->where('user_id',$userId)->where('comment_id',0)->first();
      $totalLike=Post_like::select('isLike')->where('post_id',$request->postId)->where('comment_id',0)->where('isLike',1)->count();
      $response=array("postId"=>$request->postId,"commentId"=>$request->commentId ,"totalLike"=>$totalLike,"isLike"=>$isLike->isLike);
    }else{
      $isLike=Post_like::select('isLike')->where('post_id',$request->postId)->where('comment_id',$request->commentId)->where('user_id',$userId)->first();
      $totalLike=Post_like::all()->where('post_id',$request->postId)->where('comment_id',$request->commentId)->where('isLike',1)->count();
      $response=array("postId"=>$request->postId,"commentId"=>$request->commentId,"totalLike"=>$totalLike,"isLike"=>$isLike->isLike);
    }

    return $this->successResponse($response,__('messages.post_add_like'),200);   

   }

   public function post_detail(Request $request){
      $validatedData = Validator::make($request->all(),[
        "postId"=>'required'
      ]); 
      
     if($validatedData->fails()){       
      return $this->errorResponse($validatedData->errors()->first(), 200);
    }
    $id = Post::getPostId($request->postId);
    
     
     if(empty($id)){
      return $this->errorResponse("Invalid Post Id", 200);
     }
     
     $postImage = new Post_image();
     $postId = $id ; //$request->postId ;
     $postImgPath = config('constants.post_image').$postId.'/';
     $userImg = config('constants.user_image');
     $commentfile = config('constants.comment_file').$postId.'/';
     $userId=authguard()->id ;
     //$post = Post::all()->where('id',$postId)->first();
     $post = Post::post_detail($postId);
     
     if(empty($post))
     {
      return $this->errorResponse('The '.$postId.' postId is invalid for Post Listing', 200);
      
     }
     else {
     $post->shareUrl = config('constants.post_share').$post->encryption;    
     $post->image=$postImage->getPostImage($postId);
     //$post->image=$image ;
     $postLikes = DB::table('post_likes')->select(DB::raw('count(*) as total_Like'))->where('post_id',$postId)->where('comment_id',0)->where('isLike',1)->first();
     //DB::enableQueryLog();
     $isLikes = DB::table('post_likes')->select('isLike')->where('post_id',$postId)->where('user_id',$userId)->where('comment_id',0)->first();
     //print_r(DB::getQueryLog());
     $usrImage = DB::raw('case when concat("'.$userImg.'",users.image) is null then "" else concat("'.$userImg.'",users.image) end as image');

     $starImg = config('constants.star_image');  
     $postComment = DB::table('post_comments')->select('users.name','users.username','users.rank_type',$usrImage,'post_comments.id','post_comments.fileDuration','post_comments.comment','post_comments.comment_type','post_comments.createdOn',DB::raw("concat('".$starImg."',rank_types.star_img) as starImg"))
        ->where('postId',$postId)
        ->where('parentId',0)
        ->where('post_comments.status',1)
        ->join('users', 'users.id', '=', 'post_comments.userId')        
        ->leftjoin('rank_types','rank_types.id','=','users.rank_type')   
        ->orderBy('post_comments.id', 'ASC')
        ->get();

     $response_post = array() ;
     if(!empty($postComment)){
        foreach ($postComment as $key => $value) {
          $commentLike = DB::table('post_likes')->select(DB::raw('count(*) as total_likes'))->where('comment_id',$value->id)->where('isLike',1)->where('post_id',$postId)->first();

          if(is_null($commentLike)){
            $commentLike->total_likes=0 ;
          }
          if($value->comment_type!=0){
            $value->comment = $commentfile.$value->comment ;
          }
          ///$value->createdOn="2023-02-03 17:10:52";
          $date = Carbon::parse($value->createdOn); // now date is a carbon instance
          $elapsed = $date->diffForHumans(Carbon::now());
          $elapsed=createdAt($elapsed) ;
          $value->createdOn=$elapsed ;
          $value->fileDuration = (int)$value->fileDuration ;
          $value->total_likes=isset($commentLike->total_likes)?$commentLike->total_likes:0;
          
          $isLike_=DB::table('post_likes')->select('isLike')->where('comment_id',$value->id)->where('post_id',$postId)->where('user_id',$userId)->where('status',1)->first();
          $value->isLikes=isset($isLike_->isLike)?$isLike_->isLike:0 ;
          $value->replyComment=$this->postCommentList($value->id,$userId,$postId);
          $response_post[]=$value ;
        }
     }


     
     $post->comment = [];
     if(!empty($response_post)){
        $post->comment = $response_post ;
     }

     $postShare = DB::table('post_share')->select(DB::raw('count(*) as total_share'))->where('status',1)->where('post_id',$postId)->first();

     if(!is_null($postShare)){
      $post->total_share = $postShare->total_share ;
     }else{
      $post->total_share = 0 ;
     }

     $post_comment = DB::table('post_comments')->select(DB::raw('count(*) as total_comment'))->where('status',1)->where('postId',$postId)->first();
     
     if(!is_null($post_comment)){
      $post->total_comment = $post_comment->total_comment ;
     }else{
      $post->total_comment = 0 ;
     }

     if(!is_null($postLikes)){
      $post->like= $postLikes->total_Like ;
     }else{
      $post->like=0 ;
     }
     //print_r($isLikes);
     if($isLikes==null){
     	$post->isLikes=0 ;
     }else{
     	$post->isLikes=isset($isLikes->isLike)?$isLikes->isLike:0 ;
     }

    }
     return $this->successResponse($post,'Post Detail',200);   

   }

   public function postCommentList($commentId,$userId,$postId){
   	$userImg = config('constants.user_image');
   	$starImg = config('constants.star_image');  
    $commentfile = config('constants.comment_file').$postId.'/';
   	$usrImage = DB::raw('case when concat("'.$userImg.'",users.image) is null then "" else concat("'.$userImg.'",users.image) end as image');
   	$postComment=DB::table('post_comments')->select('users.name','users.username','users.rank_type',$usrImage,'post_comments.id','post_comments.comment_type','post_comments.comment','post_comments.createdOn',DB::raw("concat('".$starImg."',rank_types.star_img) as starImg"))
        ->where('parentId',$commentId)       
        ->where('post_comments.status',1)
        ->join('users', 'users.id', '=', 'post_comments.userId') 
        ->leftjoin('rank_types','rank_types.id','=','users.rank_type')   
        ->get() ;

     if(!empty($postComment)){
        foreach ($postComment as $key => $value) {
          $date = Carbon::parse($value->createdOn); // now date is a carbon instance
          $elapsed = $date->diffForHumans(Carbon::now());
          $elapsed=createdAt($elapsed) ;
          $value->createdOn=$elapsed ;
           if($value->comment_type!=0){
            $value->comment = $commentfile.$value->comment ;
          }
           $commentLike = DB::table('post_likes')->select(DB::raw('count(*) as total_likes'))->where('comment_id',$value->id)->where('isLike',1)->count();
          if(is_null($commentLike)){
            $value->total_likes=0 ;
          }else{
            $value->total_likes=$commentLike ;
          }
          
          $isLike_=DB::table('post_likes')->select('isLike')->where('comment_id',$value->id)->where('post_id',$postId)->where('user_id',$userId)->where('status',1)->first();
          $value->isLike=isset($isLike_->isLike)?$isLike_->isLike:0;
        }
     }
     return $postComment ;

   }
   public function sponser_list(Request $request){
    
    $userId = authguard()->id ;
    $imagePath = config('constants.sponser_image');    
     //$list=DB::select('CALL GetAllSponsers("'.$imagePath.'")');
     $sponserList=DB::table('sponser')->select('id','name',DB::raw('concat("'.$imagePath.'",image) as image'),'description')->where('status',1)->whereIn('createdBy',array('0',$userId))->get();
     return $this->successResponse($sponserList,'Sponser list',200);   
   }

   public function file_list(Request $request){

    $validatedData = Validator::make($request->all(),["fileType"=>'required',
      "userId"=>'required']); 

     if($validatedData->fails()){       
      return $this->errorResponse($validatedData->errors()->first(), 200);
     }
     
     $userId = isset($request->userId)?$request->userId:0 ;
     if($userId==0){
      $userId = authguard()->id ;
     }

    $page = $request->has('page') ? $request->get('page') : 1;
    $limit = $request->has('per_page') ? $request->get('per_page') : 10;
    $offset = ($page - 1) * $limit ;

     
       $postImgPath = config('constants.post_image');
       //1>> image,2>>video,3>>audio
       if($request->fileType==1){
          $imageFile = DB::table('post_images')->select('id',DB::raw('case when thumbnail is null then "" else concat("'.$postImgPath.'",postId,"/",thumbnail) end as thumbnail'),DB::raw('case when image is null then "" else concat("'.$postImgPath.'",postId,"/",image) end as image'))->where('user_id',$userId)->where('file_type',1)->orderBy('post_images.id', 'desc')->skip($offset)->take($limit)->get();     
          $totalRecord=DB::table('post_images')->select('id')->where('user_id',$userId)->where('file_type',1)->count();      
       }else if($request->fileType==2){
          $imageFile = DB::table('post_images')->select('id',DB::raw('concat("'.$postImgPath.'",postId,"/",thumbnail) as thumbnail'),DB::raw('concat("'.$postImgPath.'",postId,"/",image) as image'))->where('user_id',$userId)->where('file_type',2)->orderBy('post_images.id', 'desc')->skip($offset)->take($limit)->get();  
          $totalRecord=DB::table('post_images')->select('id')->where('user_id',$userId)->where('file_type',2)->count();  

       }else if($request->fileType==3){
          $imageFile = DB::table('post_images')->select('id',DB::raw('concat("'.$postImgPath.'",postId,"/",image) as image'))->where('user_id',$userId)->where('file_type',3)->skip($offset)->take($limit)->get();   
          $totalRecord=DB::table('post_images')->select('id')->where('user_id',$userId)->where('file_type',3)->count();           
       }else {
           $imageFile = array() ;
           $totalRecord=0 ; 
       }


           
      $data=array() ;
      $data['response']=$imageFile ;
      
      if(($offset+$limit) < $totalRecord){
        $data['isShowMore']=true ;  
      }else{
        $data['isShowMore']=false ;  
      }

      $data['page']=$page ; 

      return $this->successResponse($data,'File list',200);   
       
      //$videoList=Post_image::where('user_id',$userId)->where('file_type',1);
   }

   
   public function save_contactus(Request $request){
     DB::table('image_log')->insert(['data'=>json_encode((array)$request->file('image'),true)]);
            // DB::table('image_log')->insert(['data'=>json_encode((array)$request->file('sponser_icon'),true)]);
      $validatedData = Validator::make($request->all(),
        [       
        "subject"=>'required',
        "message"=>'required'
      ]); 
  // "name"=>'required',       
  //       "phoneNumber"=>'required',
  //       "email"=>'required',
     if($validatedData->fails()){       
      return $this->errorResponse($validatedData->errors()->first(), 200);
     }
      $userId=authguard()->id;
    $userInfo=DB::table('users')->where('id',$userId)->first();
    $name=isset($userInfo->name)?$userInfo->name:'' ;
    $email=isset($userInfo->email)?$userInfo->email:'' ;
    $phoneNumber =isset($request->phone)?$request->phone:'' ;
    
     $insertData = array(
      "userId"=>$userId,
      "name"=>$name,       
      "phone_number"=>$phoneNumber,
      "email"=>$email,
      "subject"=>$request->subject,
      "message"=>$request->message
     );
     
     $last_id = DB::table('contactus')->insertGetId($insertData);
    
     if($request->hasfile('image')){

     $insert=[];
    $allowedfileExtension=['jpg', 'jpeg', 'gif', 'png', 'bmp', 'svg', 'svgz', 'cgm', 'djv', 'djvu', 'ico', 'ief','jpe', 'pbm', 'pgm', 'pnm', 'ppm', 'ras', 'rgb', 'tif', 'tiff', 'wbmp', 'xbm', 'xpm', 'xwd','flv','mp4','m3u8','ts','3gp','mov','avi','wmv','mp3'];

    $files = $request->file('image'); 
    $errors = [];

      foreach($files as $file)
      {
       
         $fileType=0 ;
         $mimeType=$file->getMimeType() ;
         $filenamewithextension = $file->getClientOriginalName(); 
         $extension = $file->getClientOriginalExtension();  
         $check = in_array($extension,$allowedfileExtension);
         $fileType = $this->checkFileType($filenamewithextension);
           if($check){
         
            $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
              $filename=str_replace(' ', '_', $filename);
              $filenametostore = $filename.'_'.time().'.'.$extension;       
              $smallthumbnail = $filename.'_100_100_'.time().'.'.$extension;    
             
              
              $file->storeAs('public/contactus_image/', $filenametostore);

              $insert[]=array(
                    'contact_id'=>$last_id,
                    'image'=>$filenametostore,
                    'fileType'=>$fileType
                   );

                   $file_path[]= url('/').'/public/storage/contactus_image/'.$filenametostore;
              
           }else{
            return $this->errorResponse('The '.$extension.' extension is not allowed', 200);
           }
          }

           if(!empty($insert)){     
            DB::table('contactus_files')->insert($insert) ;
           }

            }  
     return $this->successResponse([],__('messages.post_save_contactus'),200);   
   }

   public function save_advertisement(Request $request){
       
    
           // DB::table('image_log')->insert(['data'=>json_encode((array)$request->file('adv_image'),true)]);
           //  DB::table('image_log')->insert(['data'=>json_encode((array)$request->file('sponser_icon'),true)]);
    $validatedData = Validator::make($request->all(),
        [
        "sponserId"=>'required',   
        "adv_title"=>'required',
        "start_date"=>'required',
        "adv_image"=>'required',
        "end_date"=>'required',
        "description"=>'required'
      ]); 

    DB::table('image_log')->insert(['data'=>$request->sponserId]);
   
        if($validatedData->fails()){       
          return $this->errorResponse($validatedData->errors()->first(), 200);
        }

        if($request->sponserId=='other'){
          $validatedData = Validator::make($request->all(),[ 
            "sponser_title"=>'required|unique:sponser,name',
            "sponser_icon"=>'required'
          ]); 
        }

        if($validatedData->fails()){       
          return $this->errorResponse($validatedData->errors()->first(), 200);
        }

        $userId=authguard()->id;
        if($request->sponserId=='other'){
          $sponserIcon=$this->uploadImage('sponser_icon','sponser_img',$request);         
          $sp_img=isset($sponserIcon['fileName'])?$sponserIcon['fileName']:'' ;
          $insertSponser=array(
            "name"=>$request->sponser_title,
            "image"=>$sp_img,
            'createdBy'=>$userId
          );
          $sponserId=DB::table('sponser')->insertGetId($insertSponser);
          $sponserId = $sponserId ;
        }else{
          $sponserId = $request->sponserId ;
        }

          $start_date=(date('Y-m-d', strtotime($request->start_date)));
        $end_date=(date('Y-m-d', strtotime($request->end_date)));
        $advData=array(
          'sponser_id'=>$sponserId ,
          'title'=>$request->adv_title ,         
          'start_date'=>$start_date ,
          'end_date'=>$end_date ,
          'introduction'=>$request->description,
          'createdBy'=>$userId
        );

        // $advData=array(
        //   'sponser_id'=>$sponserId ,
        //   'title'=>$request->adv_title ,         
        //   'start_date'=>$request->start_date ,
        //   'end_date'=>$request->end_date ,
        //   'introduction'=>$request->description,
        //   'createdBy'=>$userId
        // );
               
        // Start
            $advImage=$this->uploadImage('adv_image','sponser_image',$request);            
            $advData['ad_type']=isset($advImage['fileType'])?$advImage['fileType']:1 ;
            $advData['image']=isset($advImage['fileName'])?$advImage['fileName']:'' ;
        // End
        DB::table('advertisements')->insert($advData);
        return $this->successResponse([],__('messages.post_save_advertisement'),200);   
   }

   public function uploadImage($image_key,$path,$request){
   //print_r($request->$image_key); exit ;
    if($request->hasfile($image_key)){
     $imgPath='app/public/'.$path.'/' ;  
     $allowedfileExtension=['jpg', 'jpeg', 'gif', 'png', 'bmp', 'svg', 'svgz', 'cgm', 'djv', 'djvu', 'ico', 'ief','jpe', 'pbm', 'pgm', 'pnm', 'ppm', 'ras', 'rgb', 'tif', 'tiff', 'wbmp', 'xbm', 'xpm', 'xwd','flv','mp4','m3u8','ts','3gp','mov','avi','wmv','mp3'];
 
     $files = $request->file($image_key); 
     $fileType=0 ;    
     $mimeType=$files->getMimeType() ; 
     $filenamewithextension = $files->getClientOriginalName(); 
     $extension = $files->getClientOriginalExtension();  
        
          $check = in_array($extension,$allowedfileExtension);
          $fileType = $this->checkFileType($filenamewithextension);
          
            if($check){
          
             $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
             $filename=str_replace(' ', '_', $filename);
             $filenametostore = $filename.'_'.time().'.'.$extension;       
             $smallthumbnail = $filename.'_100_100_'.time().'.'.$extension;    
              
               
               $files->storeAs('public/'.$path.'/', $filenametostore);
               $file_path= url('/').'/public/storage/'.$path.'/'.$filenametostore;              
              return $response=array('fileType'=>$fileType,"fileName"=>$filenametostore);
            }else{
              return array(); 
            }
          }else{
            return array(); 
          }
   }

   public function ads_sponser_list(Request $request){
    
    $userId = authguard()->id ;
    $imagePath = config('constants.sponser_image');    
     //$list=DB::select('CALL GetAllSponsers("'.$imagePath.'")');
     $sponserList=DB::table('sponser')->select('id','name')->where('status',1)->whereIn('createdBy',array('0',$userId))->get();
     $spList=array();
     if(!empty($sponserList)){
      foreach($sponserList as $val){
        $spList[]=array("id"=>(string)$val->id,"name"=>$val->name);
      }
     }
     $spList[]=array("id"=>"other","name"=>"Other");
     return $this->successResponse($spList,'Sponser list',200);  

   }

   public function delete_comment(Request $request){
    
    $validatedData = Validator::make($request->all(),
        [
        "commentId"=>'required'
      ]); 
   
        if($validatedData->fails()){       
          return $this->errorResponse($validatedData->errors()->first(), 200);
        }


     $userId=authguard()->id ;
     $commentId=isset($request->commentId)?$request->commentId:0 ;
    // DB::enableQueryLog();
     post_comment::where('id',$commentId)->where('userId',$userId)->delete();
     //print_r(DB::getQueryLog());
      return $this->successResponse([],__('messages.post_comment_deleted'),200);  
   }

   public function userPostList(Request $request){

       $userId = isset($request->userId)?$request->userId:0 ; //authguard()->id ;
       $otherUser = 1 ;
       if($userId==0){
        $userId =authguard()->id ;
         $otherUser = 0 ;
       }
       $checkUser = DB::table('users')->where('id',$userId)->get()->toArray();
      
       if(empty($checkUser)){
         return $this->errorResponse(__('messages.post_user_list'), 200);
       }

      $star_imgPath=config('constants.star_image') ;
      $filePath = config('constants.user_image') ;
      $image_ = DB::raw('case when concat("'.$filePath.'",users.image) is null then "" else concat("'.$filePath.'",users.image) end as image') ;
      $strImg=DB::raw('concat("'.$star_imgPath.'",star_img) as starImg');
      
      $page = $request->has('page') ? $request->get('page') : 1;
      $limit = $request->has('per_page') ? $request->get('per_page') : 10;
      $offset = ($page - 1) * $limit ;

      $postList=Post::select('posts.id','posts.encryption','posts.message','users.name',DB::raw('concat("@",username) as username'),'users.rank_type',$image_,$strImg,'posts.createdOn')->join('users','users.id','=','posts.userId')->leftJoin('rank_types','rank_types.id','=','users.rank_type')
      ->where('posts.status',1)->where('userId',$userId)->where('users.isTrash',0)->orderBy('posts.id', 'desc')->skip($offset)->take($limit)->get() ;
      // get star image by user rank
      $userRank_ = User::select('rank_')->where('id',$userId)->first();
      $userRank__ = isset($userRank_->rank_)?$userRank_->rank_:0 ;
      $rankT = getRankType($userRank__);
      $rankImg = DB::table('rank_types')->select(DB::raw('concat("'.$star_imgPath.'",star_img) as starImg'))->where('id',$rankT)->where('status',1)->first() ;
      $starImg=isset($rankImg->starImg)?$rankImg->starImg:'' ;
      $user_rankType=$rankT ;

      $post_list = array();      
      //$post_image = array() ;
       $postLike = new Post_like();
       $postImage = new Post_image();
       $loginUserId = authguard()->id ;
      if(!empty($postList)){
         foreach ($postList as $key => $value) {
            $postId = $value->id ;
            $value->starImg =$starImg ;
            $value->rank_type=$rankT ; 
            $postImgPath = config('constants.post_image').$postId.'/';           
            $post_image = $postImage->getPostImage($postId);
            $value->postImage = $post_image ;
            $value->isLike=$postLike->isLikeOrNot($postId,$loginUserId,0);      
            $totalComment = Post_comment::all()->where('postId',$postId)->where('status',1)->count();
            $value->totalComment = $totalComment ; 
            $value->totalLike = $postLike->getTotalLike($postId);  
            $date = Carbon::parse($value->createdOn); // now date is a carbon instance
            $elapsed = $date->diffForHumans(Carbon::now());
            $elapsed=createdAt($elapsed) ;
            $value->shareUrl = config('constants.post_share').$value->encryption;   
            $value->createdOn =$elapsed ;
            $post_list[]=$value ;
            //$post_image[]=$postImage ;
         }
      }

      $data=array() ;
     

      $totalRecord=Post::select('id')->where('posts.status',1)->join('users','users.id','=','posts.userId')->where('users.isTrash',0)->where('posts.userId',$userId)->count();      
      
      $data['response']=$post_list ;
      if(($offset+$limit) < $totalRecord){
        $data['isShowMore']=true ;  
      }else{
        $data['isShowMore']=false ;  
      }

      $data['page']=$page ; 
      return $this->successResponse($data,'User Post List',200);
    }

    public function postShare(Request $request){
   
     

        $id=isset($_GET['postId'])?$_GET['postId']:0 ;
        $redirectURL = URL::to('/').'/api/v1/postShare?postId='.$id; 
        $checkDevice=checkDevice();
        
        if($checkDevice['isIOS']==1){       
      
          return redirect()->to('https://apps.apple.com/us/app/walkofweb/id6466639776');
        }else if($checkDevice['isAndroid']==1){
          $data['redirectURL']="https://www.google.co.in/" ; 
          $data['deviceType']="Android";
          
        }else{
          $data['redirectURL']="https://walkofweb.in/" ;   
          $data['deviceType']="Desktop";
        }
        echo "<pre>";
        print_r($data);
    }

    public function sharePostDetail(Request $request){
      
      $validatedData = Validator::make($request->all(),["encryption"=>'required'
      ]); 
   
      if($validatedData->fails()){       
        return $this->errorResponse($validatedData->errors()->first(), 200);
      }

      //$postId=Post::select('id')->where('encryption',$request->encryption)->first();
      $content = new Request();
      $content['postId'] = $request->encryption ;
      return $this->post_detail($content);

    }

    public function deletePost(Request $request){
      
      $validatedData = Validator::make($request->all(),["postId"=>'required'
      ]); 
   
      if($validatedData->fails()){       
        return $this->errorResponse($validatedData->errors()->first(), 200);
      }
      $postId = $request->postId ;
      $userId = authguard()->id ;

      $check=Post::select('userId')->where('id',$postId)->first();
      $postUserId=isset($check->userId)?$check->userId:0 ;
      
      if(empty($postUserId)){
         return $this->errorResponse(__('messages.post_delete_err1'), 200);
      }else if($postUserId!=$userId){
         return $this->errorResponse(__('messages.post_delete_err'), 200);
      }

      Post::where('id',$postId)->where('userId',$userId)->delete();
      Post_comment::where('postId',$postId)->where('userId',$userId)->delete();
      Post_like::where('post_id',$postId)->where('user_id',$userId)->delete();
      DB::table('post_share')->where('post_id',$postId)->where('user_id',$userId)->delete();
      
      $postImage=DB::select("select * from post_images where postId=".$postId." and user_id=".$userId);  
      if(!empty($postImage)){        
        removePostDir($postId);       
      }

      Post_image::where('postId',$postId)->where('user_id',$userId)->delete();
      return $this->successResponse([],__('messages.post_delete_succ'),200);
    }

    public function edit_post(Request $request){

      $validatedData = Validator::make($request->all(),
        [
          "postId"=>'required',
          "message"=>'required'
      ]); 
   
      if($validatedData->fails()){       
        return $this->errorResponse($validatedData->errors()->first(), 200);
      }
      
      $postId=$request->postId ;
      $message=$request->message ;
      $loginUserId = authguard()->id ;

       $checkPost=Post::select('userId')->where('id',$postId)->first();
       $postUserId = isset($checkPost->userId)?$checkPost->userId:0 ;

       if(empty($checkPost)){
        return $this->successResponse([],__('messages.post_edit_err1'),200);
       }else if($loginUserId!=$postUserId){
        return $this->successResponse([],__('messages.post_edit_err'),200);
       }

      Post::where('id',$postId)->update(['message'=>$message]);
      return $this->successResponse([],__('messages.post_edit'),200);
    }

    public function delete_post_file(Request $request){

      $validatedData = Validator::make($request->all(),
        [
          "fileId"=>'required',
          "postId"=>'required',
          "fileName"=>'required'         
      ]); 
   
      if($validatedData->fails()){       
        return $this->errorResponse($validatedData->errors()->first(), 200);
      }
      
      $fileId=$request->fileId ;
      $postId=$request->postId ;
      $fileName=$request->fileName ;
      $loginUserId=authguard()->id ;
      $checkFile=DB::table('post_images')->select('user_id')->where('id',$fileId)->first();
      $fileUserId=isset($checkFile->user_id)?$checkFile->user_id:0 ;

      if(empty($checkFile)){
        return $this->errorResponse(__('messages.post_file_delete_err1'), 200);
      }else if($fileUserId!=$loginUserId){
        return $this->errorResponse(__('messages.post_file_delete_err'), 200);
      }

      $imgPath='app/public/post_image/'.$postId.'/'.$fileName ;
      $unlinkPath = storage_path($imgPath) ;
      do_upload_unlink(array($unlinkPath));
      
      $deletePost=DB::table('post_images')->where('id',$fileId)->where('user_id',$loginUserId)->where('postId',$postId)->delete();
      if($deletePost){
        return $this->successResponse([],__('messages.post_file_delete_succ'),200);  
      }else{
        return $this->successResponse([],"Invalid request",200);
      }
      

    }


    /**
 * @SWG\Get(
 *     path="/testAudio",
 *     summary="Get a list of users",
 *     tags={"testAudio"},
 *     @SWG\Response(response=200, description="Successful link"),
 *     @SWG\Response(response=400, description="Invalid request")
 * )
 */
    public function testAudio(){
      $fileName="https://dev.walkofweb.net/public/storage/post_image/573/audio_1694082533.mp3" ;
      //$file = Storage::get($fileName);
  return (new Response($fileName, 200))
         ->header('Content-Type', 'audio/mpeg');

    }

public function getAudio($filename)
{
  //$file = Storage::get($filename);
  return (new Response($file, 200))
         ->header('Content-Type', 'audio/aac');
}



public function save_post_report(Request $request){
   $loginUserId=authguard()->id ;

   $validatedData = Validator::make($request->all(),
        [ 
          "userId"=>'required',
          "reportId"=>'required',
          "type"=>'required',         
          "actionType"=>'required'        
      ]); 
   
      if($validatedData->fails()){       
        return $this->errorResponse($validatedData->errors()->first(), 200);
      }


      //type 1 for post 2 for comment
      // action type 1 for unfollow and 2 for report
      $type=isset($request->type)?$request->type:0 ;
      $report_id=isset($request->reportId)?$request->reportId:0 ;
      $action_type=isset($request->actionType)?$request->actionType:0 ;

      $insertData=array(
        "report_from"=>$loginUserId ,
        "report_to"=>$request->userId ,
        "type"=>$request->type ,
        "report_id"=>$request->reportId ,
        "action_type"=>$request->actionType 
      );

     

      if($type===1 && $action_type===1){       
         $last_id=DB::table("post_report")->insertGetId($insertData);
        $this->unfollow($loginUserId,$request->userId);
      }else if($action_type==2){
        $insertData=array(
        "report_from"=>$loginUserId ,
        "report_to"=>$request->userId ,
        "type"=>$request->type ,
        "report_id"=>$request->reportId ,
        "action_type"=>$request->actionType ,
        "report_comment_id"=>$request->reportCommentId,
        "report_comment"=>$request->reportComment,
        "status"=>1
       );
      $last_id=DB::table("post_report")->insertGetId($insertData);
      if($type==1){
        DB::table("posts")->where("id",$request->reportId)->update(['status'=>0]);
      }else{
        DB::table("post_comments")->where("id",$request->reportId)->update(['status'=>0]);
      }
      
      }else{
        $last_id=0;
      }

      if($request->type==1){
        return $this->successResponse(array("requestId"=>$last_id) ,"Thanks for reporting this post. We will show you less of this kind of content in the future",200);
      }else{
        return $this->successResponse(array("requestId"=>$last_id) ,"Thanks for reporting this comment. We will show you less of this kind of content in the future",200);
      }
      

}

public function report_comment_list(Request $request){
   $reportText = DB::table('report_comment')->select('id','title','description')->where('status',1)->get();
   return $this->successResponse($reportText ,"Report comment list",200);

}

public function update_report(Request $request){

   $validatedData = Validator::make($request->all(),
        [ 
          "requestId"=>'required',
          "reportCommentId"=>'required',
          "reportComment"=>'required'
          
      ]); 
   
      if($validatedData->fails()){       
        return $this->errorResponse($validatedData->errors()->first(), 200);
      }

      $updateData=array(
        "report_comment_id"=>$request->reportCommentId,
        "report_comment"=>$request->reportComment
      );  

      DB::table("post_report")->where('id',$request->requestId)->update($updateData);
      $getRInfo=DB::table("post_report")->select('report_from','report_to','type','report_id','action_type')->where('id',$request->requestId)->first();
      $reportFrom=isset($getRInfo->report_from)?$getRInfo->report_from:0 ;
      $reportTo=isset($getRInfo->report_to)?$getRInfo->report_to:0 ;
      $type=isset($getRInfo->type)?$getRInfo->type:0 ;
      $report_id=isset($getRInfo->report_id)?$getRInfo->report_id:0 ;
      $action_type=isset($getRInfo->action_type)?$getRInfo->action_type:0 ;

      if($type==1 && $action_type==2){
        DB::table("posts")->where("id",$report_id)->update(["status"=>0]);
      }else if($type==2 && $action_type==2){
        DB::table("post_comments")->where("id",$report_id)->update(["status"=>0]);
      }

      return $this->successResponse([] ,"Successfully submited your report.",200);
}

public function unfollow($loginUserId,$otherUserid){
      DB::table("user_follows")->where("followed_user_id",$loginUserId)->where("follower_user_id",$otherUserid)->delete();

      DB::table("user_follows")->where("followed_user_id",$otherUserid)->where("follower_user_id",$loginUserId)->delete();
}

public function checkFollowerOrNot(Request $request){
    $validatedData = Validator::make($request->all(),
        [ 
          "userId"=>'required'
          
      ]); 
   
      if($validatedData->fails()){       
        return $this->errorResponse($validatedData->errors()->first(), 200);
      }

      $loginUserId = authguard()->id ;
      $userId = $request->userId ;
      $checkFollowOrNot=DB::table("user_follows")->select('id')->where('followed_user_id',$userId)->where('follower_user_id',$loginUserId)->where('isAccept',1)->count();

      $checkFollowOrNot_=DB::table("user_follows")->select('id')->where('followed_user_id',$loginUserId)->where('follower_user_id',$userId)->where('isAccept',1)->count();

      if($checkFollowOrNot > 0 ||  $checkFollowOrNot_ > 0){
        $flag=1;
      }else{
        $flag=0;
      }

    return $this->successResponse(array("isFollow"=>$flag) ,"Check follow or not",200);
}

public function sendCommentN($postId,$userId,$type=1){
      $postUsrId = Post::select('posts.userId','deviceToken','deviceType')
                   ->join('users','users.id','=','posts.userId')
                   ->join('device_token','device_token.userId','=','posts.userId')
                   ->where('posts.id',$postId)->get()->first();
      
      $post_userId=isset($postUsrId->userId)?$postUsrId->userId:0 ;
      $deviceToken=isset($postUsrId->deviceToken)?$postUsrId->deviceToken:0 ;
      $deviceType=isset($postUsrId->deviceType)?$postUsrId->deviceType:0 ;
      if($userId==$post_userId){
        return true ;
      }

      $commentUsrName= User::select('name')->where('id',$userId)->get()->first();
      if($type==2){
         $msg = $commentUsrName->name." like on your post." ;
      }else if($type==3){
        $msg = $commentUsrName->name." like on your comment." ;
      }else{
         $msg = $commentUsrName->name." commented on your post." ;
      }
     

      $title="Walkofweb";
            
             $body=array(
                  "NotificationMgs"=> $msg,
                  "Action"=> 5,
                  "UserId"=>$deviceToken,
                  "user_id"=>$post_userId
                );
              
            sendPushNotification($deviceToken,$title,$body,2); 
            saveNF($post_userId,$title,$msg,json_encode($body),$hostId=0);
    }

   

}
