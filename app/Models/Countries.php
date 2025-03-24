<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Countries extends Model
{
    protected $table = 'pe_countries';
	public $timestamps = false;

    protected $fillable = [
        'i_id','v_title','api_code','i_status'
    ];

}
