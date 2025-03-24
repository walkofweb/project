<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sponser extends Model
{
    use HasFactory;
    protected $table = 'sponser';
    public $timestamps = false;
     protected $fillable = [
        'id', 'title', 'url','image','user_id','createdBy'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'createdOn'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        
    ];
}
