<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB ;

class post_image extends Model
{
    use HasFactory;

    public $timestamps = false;

     protected $fillable = [
        'id','postId','image','thumbnail'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'createdOn','status'
    ];
		
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [        
    ];

    public function getPostImage($postId='')
    {    
        //$baseUrl = URL::to('/') ;
        $imagePath=config('constants.post_image').$postId.'/';
        $data=DB::table('post_images')->select('id',DB::raw('case when (image is null || image="") then "" else CONCAT("'.$imagePath.'",image) end as image'),'file_type',DB::raw('case when (thumbnail is null || thumbnail="") then "" else CONCAT("'.$imagePath.'",thumbnail) end as thumbnail'),'fileDuration')->where('postId',$postId)->where('status',1)->get() ;
        if($data==null){
            return array() ;
        }else{
            return $data ;
        }          

    }
}
