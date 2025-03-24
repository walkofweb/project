<?php

namespace App\Http\Controllers\sponser;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Contract\Auth;
use Kreait\Firebase\Messaging\CloudMessage;
use Illuminate\Notifications\Notification;
use Kreait\Firebase\Messaging\Notification as FirebaseNotification;

use Illuminate\Contracts\Queue\ShouldQueue;




class SponserNotificationController extends Controller
{
    public function via($notifiable)
    {
        return ['firebase'];
    }
    public function pushNotification()
	    {
         
            $title="push notification";
            $message="order date is coming";
            $notification='';
            $factory = (new Factory)->withServiceAccount(public_path('test-firebase-project-2ca59-firebase-adminsdk-fbsvc-3d7ef3cfea.json'));
           // $messaging = $firebase->createMessaging();

            $messaging = $factory->createMessaging();

            $notification = FirebaseNotification::create($title, $message);
    
           // $message = CloudMessage::withTarget('token', $notifiable->firebase_token)
               // ->withNotification($notification);
    
            return $messaging->send($message);
            die;
        // Get the device token from the request

        $deviceToken = 'dMMgYsBaSQGvF1DB7phRJU:APA91bFsqxsjg6VRs7TWBN5aDELpeobGtAlH2GwcyUaGkAtfP1PIEtyZjfs-ZLYxzmor2XaqMlPOtHWDEkoL8rJCQVCiCZy0FvoQnLoQjdeFxcp9oMnuYGo';

        // Ensure the device token is valid

        if (empty($deviceToken)) {

            return response()->json(['error' => 'Device token is missing'], 400);

        }
            
        $notification = Notification::create('Test Title', 'Test Body');

        // Build the message

        $message = CloudMessage::withTarget('token', $deviceToken)->withNotification($notification);
       $data= $messaging->send($message);
       dd($data);

        try {

            // Send the notification

            $messaging->send($message);

            return response()->json(['message' => 'Notification sent successfully']);

        } catch (\Kreait\Firebase\Exception\Messaging\InvalidMessage $e) {

            return response()->json(['error' => 'Invalid message: ' . $e->getMessage()], 400);

        } catch (\Exception $e) {

            return response()->json(['error' => 'Error sending notification: ' . $e->getMessage()], 500);

        }

        dd( $message);

	        $data=[];
	        $data['message']= "Some message";

	        $data['booking_id']="my booking booking_id";
	        
            $tokens = [];
            $tokens[] = 'dMMgYsBaSQGvF1DB7phRJU:APA91bFsqxsjg6VRs7TWBN5aDELpeobGtAlH2GwcyUaGkAtfP1PIEtyZjfs-ZLYxzmor2XaqMlPOtHWDEkoL8rJCQVCiCZy0FvoQnLoQjdeFxcp9oMnuYGo';
	        $response = $this->sendFirebasePush($tokens,$data);

	    }
        public function sendFirebasePush($tokens, $data)
	    {
            

	        $serverKey = 'AIzaSyA4taV9kptmPnocbJ0HBfNSkVB5iMnoQ0Q';
	        
	        // prep the bundle
	        $msg = array
	        (
	            'message'   => 'testing',
	            'booking_id' => '657',
	        );

	        $notifyData = [
                 "body" => 'testing',
                 "title"=> "Port App"
            ];

	        $registrationIds = $tokens;
	        
	        if(count($tokens) > 1){
                $fields = array
                (
                    'registration_ids' => $registrationIds, //  for  multiple users
                    'notification'  => $notifyData,
                    'data'=> $msg,
                    'priority'=> 'high'
                );
            }
            else{
                
                $fields = array
                (
                    'to' => $registrationIds[0], //  for  only one users
                    'notification'  => $notifyData,
                    'data'=> $msg,
                    'priority'=> 'high'
                );
            }
	            
	        $headers[] = 'Content-Type: application/json';
	        $headers[] = 'Authorization: key='. $serverKey;

	        $ch = curl_init();
	        curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
	        curl_setopt( $ch,CURLOPT_POST, true );
	        curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
	        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
	        // curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
	        curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
	        $result = curl_exec($ch );
            
	        if ($result === FALSE) 
	        {
	            die('FCM Send Error: ' . curl_error($ch));
	        }
	        curl_close( $ch );
	        return $result;
	    }


}
