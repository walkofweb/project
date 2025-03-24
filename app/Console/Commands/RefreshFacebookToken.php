<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\User;

class RefreshFacebookToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'facebook:refresh-token';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh Facebook long-lived access token';

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
        

        $users = User::whereNotNull('facebook_token')->get();
        foreach ($users as $user) {
            $appId ='6058802037574834';
            $appSecret = '60fe2bc3779e9f170628b1bafe7cc686';

            $response = Http::get("https://graph.facebook.com/v18.0/oauth/access_token", [
                'grant_type' => 'fb_exchange_token',
                'client_id' => $appId,
                'client_secret' => $appSecret,
                'fb_exchange_token' => $user->facebook_token,
            ]);
            if ($response->successful()) {
                $data = $response->json();
                $user->update(['facebook_token' => $data['access_token']]);
                $this->info("Token refreshed for user {$user->id}");
            } else {
                $this->error("Failed to refresh token for user {$user->id}");
            }
        }
       
        //dd($users);
    }
}
