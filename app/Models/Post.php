<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon\Carbon;

class post extends Model
{
    use HasFactory;


     public $timestamps = false;

     protected $fillable = [
        'id','userId','message','createdOn','encryption'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'status'
    ];
		
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [        
    ];

     public function post_images()
    {        
        return $this->hasMany(Post_image::class,'postId','id');
    }

    public function comments(){
        return $this->hasMany(Post_comment::class,'id','postId');
    }

    
     public Static function post_detail($postId){
        $starImg = config('constants.star_image');
        $userImg = config('constants.user_image');
        $usrImage = DB::raw('case when users.image is null then "" else concat("'.$userImg.'",users.image) end as user_image');
        //DB::enableQueryLog();
        $postData = Post::select('posts.id as postId','users.name','posts.encryption','users.username','users.rank_type',$usrImage,'posts.createdOn',DB::raw("concat('".$starImg."',rank_types.star_img) as starImg"),'posts.message')
        ->join('users', 'users.id', '=', 'posts.userId')  
        ->leftjoin('rank_types','rank_types.id','=','users.rank_type')   
        ->where('posts.id',$postId)->first();
        // echo "<pre>";
        // print_r(DB::getQueryLog());
        if(!empty($postData)){
          $date = Carbon::parse($postData->createdOn); 
          $elapsed = $date->diffForHumans(Carbon::now());
         
         // $elapsed=createdAt($elapsed) ;
          $postData->createdOn=$elapsed ;
        }
        
        return $postData ;

    }
    
    public Static function getPostId($encryption){
        $postId=Post::select('id')->where('encryption',$encryption)->first();
        return isset($postId->id)?$postId->id:0 ;
    }

}
