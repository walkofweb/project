<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User_follow extends Model
{
    use HasFactory;
    protected $table = 'user_follows';
	public $timestamps = false;

    protected $fillable = [
        'id','followed_user_id','follower_user_id','isAccept','followBack'
    ];
}
