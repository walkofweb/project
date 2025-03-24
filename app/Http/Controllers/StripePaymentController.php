<?php

namespace App\Http\Controllers;

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
use Illuminate\Support\Facades\Crypt;
class StripePaymentController extends Controller
{

    
    public function showCheckout($id)
    {
$decrypted_userpackageid = Crypt::decryptString($id);

$userpackage=userpackage:: select('userpackages.id','packeges.price','packeges.time_limit')->join('packeges','packeges.id','userpackages.package_id')->where('userpackages.id',$decrypted_userpackageid)->where('userpackages.status',3)->first();
$data['userpackage']=$userpackage ;

$Subscription_detail=Subscription_detail::where('userpackage_id',$decrypted_userpackageid)->first();
if(!empty($Subscription_detail)&& $Subscription_detail->trans_status=="succeeded")
{
    return view('expiry_page');
}
 return view('checkout',$data);
      
    }

    public function handlePayment(Request $request)
    {
        $validatedData = Validator::make($request->all(),[
            'card_number'=>'required',
            'card_cvc'=>'required',
            'card_month'=>'required',
            'card_year'=>'required',
            'userpackage_id'=>'required',
            'stripeToken'=>'required',
            'userpackage_price'=>'required',
           
    
              ]);
      
           if($validatedData->fails()){
      
                return $this->errorResponse($validatedData->errors()->first(), 200);
              }
       
        try {
            Stripe::setApiKey(config('services.stripe.secret'));

            $charge = Charge::create([
                'amount' => $request->userpackage_price * 100, // Convert amount to cents
                'currency' => 'usd',
                'source' => $request->stripeToken,
                'description' => 'Payment from Laravel app',
            ]);
            if($charge)
            {
                $charge=$charge->toArray();
          $receipt_url= $charge['receipt_url']?$charge['receipt_url']:'';
          $balance_transaction=$charge['balance_transaction']?$charge['balance_transaction']:'';
           $status=$charge['status']=$status=$charge['status']?$status=$charge['status']:'';
           $userpackage=userpackage::where('id',$request->userpackage_id)->where('status',3)->first();
           $save_user=Subscription_detail::updateorCreate(
            [
              'userpackage_id'=>$userpackage->id,
            ],
            [
              'package_id'=>$userpackage->package_id,
              'user_id'=>$userpackage->user_id,
              'total_price'=>$request->userpackage_price,
              'card_number'=>$request->card_number,
              'cvv'=>$request->card_cvc,
              'exp_month'=>$request->card_month,
              'exp_year'=>$request->card_year,
              'trans_status'=>$status,
              'transaction_id'=>$balance_transaction,
              'redirect_url'=>$receipt_url,
              'cardholder_name'=>$request->card_name
              ]
          );
          
            }
            return redirect($receipt_url)->with('Payment successful!');
           // return back()->with('success', 'Payment successful!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
