<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Http; // Import Laravel HTTP Client
use Log;

class youtubeSocialPointCalculation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'youtubeSocialPointCalculation:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This is a  youtubeSocialPointCalculation command for calculate face book followers and rank';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $users=User::join('device_token','users.id','device_token.userId')->get();
        $i=0;
        if(!empty($users))
        {
            foreach( $users as $user){
    
                if(!empty($user->api_token))
                {
                    $user_id=$user->userId;
                    $token=$user->api_token;
                    $url = 'https://walkofweb.in/api/v1/socialPointCalculation?userId='.$user_id.'&type=4';
                    $response = Http::withToken($token)->post($url);
                    if ($response->successful()) {
                        $data = $response->json(); // Convert response to an array
                        Log::info('youtubeSocialPointCalculation Command call successful!');
            
        
                        $this->info('youtubeSocialPointCalculation Command call successful!');
                    } else {
                        Log::error('youtubeSocialPointCalculation Command Request Failed: ' . $response->status());
                        $this->error('youtubeSocialPointCalculation Command call failed!');
                        $this->info('youtubeSocialPointCalculation Command call failed!');
                    }
                }
        
        
                $i++;
            }
        }  
        else{
            Log::info('Device token not found');
        }      

        
       // $this->info('device token not found!');

    }
}
