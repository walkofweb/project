<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model
{
    use HasFactory;
    public $timestamps = false;
     protected $fillable = [
        'id', 'title', 'url','image','ad_type','status','start_date','end_date','introduction','objectives','media_mix','conclusion','target_audience','media_sample','isAccept'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'createdOn'
    ]

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        
    ];
}
