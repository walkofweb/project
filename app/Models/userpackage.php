<?php

namespace App\Models;



use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class userpackage extends Model
{
    use HasFactory;
    protected $table = 'userpackages';
    protected $fillable = [
        'id','user_id','sponsor_id','package_id','status'
    ];

    public function user()
{
    return $this->belongsTo('user');
}

}
