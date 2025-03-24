<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Youtube_user_info extends Model
{
    use HasFactory;
    protected $table = 'youtube_user_info';
    public $timestamps = false;
    protected $fillable = [
        'channel_id', 'channel_name','userId','video_count','view_count','subscriber_count','status'
    ];
    
}
