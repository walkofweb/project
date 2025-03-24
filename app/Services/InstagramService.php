<?php

namespace App\Services;

use GuzzleHttp\Client;

class InstagramService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://i.instagram.com/api/v1/',
        ]);
    }

    public function getUserProfile($username)
    {
        try {
            $cookies = 'sessionid=your-session-id; csrftoken=your-csrf-token';
            $response = $this->client->get('users/web_profile_info/', [
                'query' => [
                    'username' => $username,
                ],
                'headers' => [
                    'User-Agent' => 'Instagram 76.0.0.15.395 Android', // Use a valid user agent string.
                    'Accept' => 'application/json',
                    'Cookie' => $cookies,
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
}
