<?php

namespace App\Http\Controllers\sponser;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
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

class StripePaymentController extends Controller
{
    public function showCheckout($userpckage_id)
    {
        $address_details=userpackage::join('users', 'userpackages.sponsor_id', 'users.id')->leftjoin('pe_countries', 'pe_countries.id', '=', 'users.countryId')->leftjoin('packeges', 'packeges.id', '=', 'userpackages.package_id')->select('users.id','users.name','users.username','users.email','users.phoneNumber','users.countryId','users.address','users.country_code','pe_countries.title','packeges.packege_name','packeges.price','packeges.time_limit')->where('userpackages.id',$userpckage_id)->where('userpackages.status','=',3)->first()->toArray();
      dd($address_details);
        return view('checkout');
    }

    public function stripePost(Request $request)
    {
       
     
        try {
            Stripe::setApiKey('sk_test_51IUxQhHCUbph94h9Qm5WHrLOK314ZEopEzdPIDsnc9Au51fsc0hhoqM5CuPgAR4EIWu90WW7XInQKwQ71OflMPCF003M9k81WT');

          

    

        $charge = Charge::create([
            'amount' => 1000,
            'currency' => 'usd',
            'source' => $request->stripeToken, // obtained from the frontend
            'description' => 'Payment description',
        ]);
      

        Session::flash('success', 'Payment successful!');

              

       // return back();
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

}
