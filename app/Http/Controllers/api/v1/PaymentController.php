<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\userpackage;
use App\Models\Subscription_detail;
use Illuminate\Support\Facades\Validator;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Charge;
use Auth;
use DB;
use Hash ;
use Session ;
use URL;
use Illuminate\Support\Facades\Crypt;

class PaymentController extends Controller
{
    //
    public function index($userpackageid)
    {
       
            $decrypted_userpackageid = Crypt::decryptString($userpackageid);
            try{
            $data=userpackage::select('users.name as UserName','packeges.packege_name','packeges.title','packeges.time_limit','packeges.price','subscription_details.cardholder_name','subscription_details.card_number','subscription_details.trans_status','subscription_details.transaction_id')->join('subscription_details','subscription_details.userpackage_id','userpackages.id')->join('packeges','userpackages.package_id','packeges.id')->join('users','userpackages.user_id','users.id')->where('userpackages.status','=',3)->where('userpackages.id',$decrypted_userpackageid)->first();
            return $this->successResponse(array("Payment_details"=>$data),__('pakaage hire of'.$data->UserName),200);   
            } catch(Exception $e){
                return $this->errorResponse(__('messages.Error in payment for hire package'), 400);	
            }
       
    }
    public function Payment_history()
    {
       
            
            try{
            $data=userpackage::select('users.name as UserName','packeges.packege_name','packeges.title','packeges.time_limit','packeges.price','subscription_details.cardholder_name','subscription_details.card_number','subscription_details.trans_status','subscription_details.transaction_id')->join('subscription_details','subscription_details.userpackage_id','userpackages.id')->join('packeges','userpackages.package_id','packeges.id')->join('users','userpackages.user_id','users.id')->where('userpackages.status','=',3)->get();
            return $this->successResponse(array("Payment_details"=>$data),__('pakaage hire' ),200);   
            } catch(Exception $e){
                return $this->errorResponse(__('messages.Error in payment for hire package'), 400);	
            }
       
    }

}
