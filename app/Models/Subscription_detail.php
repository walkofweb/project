<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription_detail extends Model
{
    use HasFactory;
    protected $fillable = [
        'package_id', 'user_id','total_price','start_date','end_date','userpackage_id','cardholder_name','cvv','exp_month' ,'exp_year' ,'card_number','trans_status','redirect_url','transaction_id'
    ];

}
