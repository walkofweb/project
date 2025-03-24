<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User_instagram_info extends Model
{
    use HasFactory;
    protected $table = 'user_instagram_info';
	public $timestamps = false;
    protected $fillable = [
        	
        'id','bussiness_accountId','userId','followers_count', 'follows_count', 'name','biography','ig_id','media_count','username'
];
}
