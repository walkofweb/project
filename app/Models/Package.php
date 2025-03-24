<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    
    use HasFactory;
    protected $table = 'packeges';
    protected $fillable = [
        'id','packege_name','title','image','video','posts','others','time_limit','description','price','status','isTrash'
    ];
   
    
}
