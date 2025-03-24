<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Social_info extends Model
{
    use HasFactory;
    
    protected $table = 'social_info';
    public $timestamps = false;
    protected $fillable = [
        'id','user_id','type','social_type','social_id','follows_count','followers_count','likes_count','status','createdOn'
    ];
}
