<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TokenService
{
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = (getenv('APP_ENV') === 'local') ? getenv('API_URL') : getenv('APP_URL');
    }

    /**
     * Function to get access token and refresh token
     *
     * 
     */
    public function getAuthTokens(Request $request)
    {
        $params['client_id'] = env('PASSPORT_PASSWORD_GRANT_CLIENT_ID');
        $params['client_secret'] = env('PASSPORT_PASSWORD_GRANT_CLIENT_SECRET');
        $params['grant_type'] = ($request->has('refresh_token')) ? 'refresh_token' : 'password';
        // check if the request has refresh_token or not
        if ($request->has('refresh_token')) {

            $params['refresh_token'] = $request->refresh_token;

        } else {

            $params['username'] = $request->email;
            $params['password'] = $request->password;
        }
        // send request for fetching access and refresh tokens
        $url = $this->baseUrl . '/oauth/token';
        $response = Http::withoutVerifying()->withOptions(["verify" => false])->post($url, $params);
        return $response;
    }
}
