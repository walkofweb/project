<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use GuzzleHttp\Client;
class InstagramServiceProvider extends ServiceProvider
{

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

        $this->app->bind('InstagramAPI', function(){
            return new InstagramAPI();
        });
        //
    }

    public function getUserProfile($username)
    {
        try {
            $response = $this->client->get('users/web_profile_info/', [
                'query' => [
                    'username' => $username,
                ],
                'headers' => [
                    'User-Agent' => 'Instagram 123.0.0.0', // Use a valid user agent string.
                ],
                
            ]);

            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
