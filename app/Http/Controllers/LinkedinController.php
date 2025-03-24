<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Socialite;
use Auth;
use Exception;
use App\Models\User;

use GuzzleHttp\Client;

use Hash;
use DB ;
use GuzzleHttp\Exception\RequestException;

use Illuminate\Support\Facades\Http;



class LinkedinController extends Controller
{
    public function redirectTolinkdin()
    {
        //return Socialite::driver('linkedin-openid')->scopes(['r_liteprofile', 'w_member_social', 'r_organization_admin']).redirect();
      return Socialite::driver('linkedin-openid')->scopes(['profile', 'w_member_social'])->redirect();
   
    }

    //findout follower from linkedin
    // public function linkedinfollower($data)
    // {
    //   return view('userprofile',["linkedin_details"=>$data]);
      
     
    // }
    

    public function linkedinhandleCallback(Request $request)
    {
       
        // dd(Socialite::driver('linkedin-openid'));
        $user = Socialite::driver('linkedin-openid')->user();
      

    $accessToken = $user->token; // The LinkedIn access token

    // Define the LinkedIn API endpoint to fetch connections
    $companyPageId = '350049347';


    $organizationId = '350049347'; // Replace with your organization URN ID

    $code = $request->get('code');
    

        // Exchange the authorization code for an access token

       
        $response = Http::asForm()->post('https://www.linkedin.com/oauth/v2/accessToken', [
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => 'https://walkofweb.in/linkedin/callback',
            'client_id' => '770u34mn5vnto3',
            'client_secret' => 'WPL_AP1.vKN2jvl4tbCCgAcV.2AUdwg==',
            
        ]);
        $data = json_decode($response->getBody()->getContents(), true);
        dd( $data);
    }
    public function getLinkedInFollowers($token)
    {
       
        $url = 'https://api.linkedin.com/v2/networkSizes';

        // Make an API call to LinkedIn to fetch the followers count
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get($url);

      dd($response->json());
    }
}
