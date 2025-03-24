<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User_fb_pages extends Model
{
    use HasFactory;
    protected $table = 'user_fb_page_info';
	public $timestamps = false;

	 protected $fillable = [
        	
        	'id','user_id','page_followers','page_fan_count', 'page_name', 'page_id','link','picture','category'
    ];
}
