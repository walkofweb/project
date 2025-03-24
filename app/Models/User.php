<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use DB ;
class User extends Authenticatable
{
    use HasApiTokens,HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email','social_id','google_id','user_type','password','username','countryId','phoneNumber','country_code','rank_','rank_type','registration_from','countryCode','facebook_id','user_type','image','address','api_token','facebook_token','short_name','name_format','facebook_username','first_name','last_name'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    

    public static function advertisement(){
        $advPath =config('constants.advertisement_image') ;
        return DB::table('advertisements')->select('advertisements.id','sponser.name',DB::raw('case when (advertisements.image is null or advertisements.image="") then "" else  concat("'.$advPath.'",advertisements.image) end as image'),'advertisements.ad_type')->join('sponser', 'sponser.id', '=', 'advertisements.sponser_id')->where('advertisements.status',1)->where('advertisements.image',"!=","")->get();
    }
    


    public static function getFollowers($userId){

      $socialInfo = DB::table('social_info')->select('type','social_type','followers_count')->where('user_id',$userId)->get();
      $response=array(
        'facebook_followers'=>0,
        'insta_followers'=>0,
        'tiktok_followers'=>0,
        'walkofweb_followers'=>0,
        'youtube_followers'=>0
      );
      $i=0;
      if(!empty($socialInfo)){
      
        foreach ($socialInfo as $key => $value) {
          if($value->social_type==1 ||$value->social_type==2){
            $response['facebook_followers']+=$value->followers_count ;
          }else if($value->social_type==3){
             $response['insta_followers'] = $value->followers_count ;
           }else if($value->social_type==4){
             $response['tiktok_followers'] = $value->followers_count ;
           }else if($value->social_type==5){
             $response['walkofweb_followers'] = $value->followers_count ;
           }else if($value->social_type==6){
            $response['youtube_followers'] = $value->followers_count ;
          }
           $i++;
        }
      }
      return $response ;
    }

      public static function getFollowersCount($userId){

      $socialInfo = DB::table('social_info')->select(DB::raw('case when sum(followers_count) is null then 0 else sum(followers_count) end as totalCount'))->where('user_id',$userId)->first();
     
      $response=array();
      if(!empty($socialInfo)){
        return $socialInfo->totalCount ;
      }
       return 0 ;
      // return $totalFollowers=DB::table('social_info')->select('id')->where('user_id',$userId) 
      //  ->where('status',1)     
      //  ->count();
    }

     public static function getUserRank($userId){

      $socialInfo = DB::table('users')->select('rank_')->where('id',$userId)->first();
     
      if(!empty($socialInfo)){
        return $socialInfo->rank_ ;
      }
      return 0 ;
    }

    public static function checkFollowOrNot($loginUserId,$otherUserId){
        $follow=DB::table('user_follows')->select('id','isAccept')->where('followed_user_id',$loginUserId)->where('follower_user_id',$otherUserId)->first();
        if(!empty($follow)){
          $checkFollow=$follow->isAccept ;
        }else{
          $checkFollow=0 ;
        }
        return $checkFollow ;
    }

    public static function getName($userId){
      $userName=User::select('name')->where('id',$userId)->first();
        return isset($userName->name)?$userName->name:'' ;
    }

    public static function checkHostOrNot($loginUserId,$userId){

      $isHost = DB::table("user_host")->where('userId',$loginUserId)->where('host_user_id',$userId)->count() ;

      return $isHost ;
          
    }

    public static function getAppFollowersCount($userId){
      $totalFollower = DB::table("user_follows")->where("follower_user_id",$userId)->where("isAccept",1)->count();
      return $totalFollower ;
    }

     public static function getFollowersCount_($userId){
      $totalFollower = DB::table("user_follows")->where("follower_user_id",$userId)->where("isAccept",1)->count();
      return $totalFollower ;
    }

    public static function checkFollowOrFollower($loginUserId,$userId){
     
    $following=DB::table("user_follows")->select('id')->where('followed_user_id',$loginUserId)->where('follower_user_id',$userId)->where('isAccept',1)->count();

    $follower=DB::table("user_follows")->select('id')->where('followed_user_id',$userId)->where('follower_user_id',$loginUserId)->where('isAccept',1)->count();

    if($following > 0){
      return true ;
    }else if($follower > 0){
      return true;
    }else{
      return false ;
    }

    }

    public static function getAllFollowingAndFollowersId($loginUserId){
         $following=DB::table("user_follows")->select('id',DB::raw("group_concat(follower_user_id) as userId"))->where('followed_user_id',$loginUserId)->where('isAccept',1)->first();

        $follower=DB::table("user_follows")->select('id',DB::raw("group_concat(followed_user_id) as userId"))->where('follower_user_id',$loginUserId)->where('isAccept',1)->first();
  
  
        $following_ = isset($following->userId)?$following->userId:'' ;
         $follower_ = isset($follower->userId)?$follower->userId:'' ;

         if($following_!=''){
           $followingUId=explode(",", $following_); 
         }else{
           $followingUId=array(); 
         }
         

         if($follower_!=''){
          $followerUId=explode(",", $follower_);
         }else{
          $followerUId=array();
         }

         
         $followFollower=array_merge($followingUId,$followerUId);
         $fUser=array_unique($followFollower);

         return $fUser ;

    }
}
