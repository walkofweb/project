<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Insta_user_info extends Model
{
    use HasFactory;
    protected $table = 'insta_user_info';
	public $timestamps = false;
    protected $fillable = [
        'id','userId','bussiness_accountId','name','username','biography','ig_id','media_count','followers_count','follows_count','total_post_count','total_post_comment_count','total_post_likes_count','status','createdOn'
    ];
}
