<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fb_user_info extends Model
{
    use HasFactory;
    protected $table = 'fb_user_info';
	public $timestamps = false;

    protected $fillable = [
        'id','userId','total_friends_count','fb_page_followers_count','fb_page_likes_count','page_name','page_id','link','picture','category','fb_post_comments','fb_post_likes','fb_post_count','status','createdOn'
    ];
    
}
