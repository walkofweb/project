<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB ;

class post_like extends Model
{
    use HasFactory;
     public $timestamps = false;
      protected $fillable = [
        'id', 'post_id'  ,'user_id','isLike','comment_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */	
    protected $hidden = [
        'status', 'createdOn'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
      
    ];

    public function getTotalLike($postId){
    	return DB::table('post_likes')->where('post_id',$postId)->where('comment_id',0)->where('isLike',1)->count()	;
    }

     public function isLikeOrNot($postId,$userId,$commentId){
        // DB::enableQueryLog();
        $postLike = DB::table('post_likes')->select(DB::raw('case when isLike is null then 0 else isLike end as isLike'))->where('post_id',$postId)->where('user_id',$userId)->where('comment_id',$commentId)->first() ;
       // print_r(DB::getQueryLog());
       if(empty($postLike)){
        return 0 ;
       }else{
        return $postLike->isLike ;
       } 
    }
}
