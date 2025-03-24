<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contactus extends Model
{
	protected $table = 'contactus';
	public $timestamps = true;

    protected $fillable = [
        'id','name','phone_number','email', 'subject', 'message'
    ];


    
}
