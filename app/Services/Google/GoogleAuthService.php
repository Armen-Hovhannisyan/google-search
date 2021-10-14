<?php

namespace App\Services\Google;

use Google\Client;
use Google\Service\Webmasters;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class GoogleAuthService
{
    /**
     * @var Client
     */
    public $client;
    /**
     * @var string
     */
    public $authSecretKey;

    /**
     * GoogleApiService constructor.
     */
    public function __construct()
    {
        $this->client = $this->getGoogleClient();
        $this->authSecretKey = config('google.google_auth_secret');
    }

    /**
     * @return string
     */
    public function getAuthUrl()
    {
        return $this->client->createAuthUrl();
    }


    /**
     * @param Request $request
     * @return Client
     */
    public function googleCallback(Request $request)
    {
        $code = $request->input('code');
        $tokenData = $this->client->fetchAccessTokenWithAuthCode($code);
        $this->saveTokenData($tokenData);
        return $this->client;
    }

    /**
     * @param $tokenData
     */
    public function saveTokenData($tokenData)
    {
        Session::put($this->authSecretKey, json_encode($tokenData));
    }

    /**
     * @param $tokenData
     * @return bool
     */
    public function isAccessTokenExpired($tokenData)
    {
        $created = 0;
        if (isset($tokenData['created'])) {
            $created = $tokenData['created'];
        }
        return ($created + ($tokenData['expires_in'] - 30)) < time();
    }

    /**
     * @return Client|null
     */
    public function getGoogleClientWithToken()
    {
        try {
            $tokenData = json_decode(Session::get($this->authSecretKey), true);
            $client = $this->client;
            if (!$tokenData) {
                return null;
            }
            if ($this->isAccessTokenExpired($tokenData)) {
                $tokenData = $client->refreshToken($tokenData['refresh_token']);
                $this->saveTokenData($tokenData);
            }
            $accessToken = $tokenData['access_token'];
            $client->setAccessToken($accessToken);
        } catch (\Exception $exception) {
            return null;
        }
        return $client;
    }

    /**
     * @return Client
     */
    private function getGoogleClient()
    {
        $client_id = env('GOOGLE_CLIENT_ID');
        $client_secret = env('GOOGLE_CLIENT_SECRET');
        $redirect_uri = env('GOOGLE_REDIRECT');
        $dev_key = env('GOOGLE_DEVELOPER_KEY');

        $client = new Client();
        $client->setClientId($client_id);
        $client->setClientSecret($client_secret);
        $client->setApplicationName(env("GOOGLE_APPLICATION_NAME"));
        $client->setDeveloperKey($dev_key);
        $client->setAccessType('offline');
        $client->setApprovalPrompt('force');
        $client->setScopes([Webmasters::WEBMASTERS, Webmasters::WEBMASTERS_READONLY]);
        $client->setRedirectUri($redirect_uri);

        return $client;
    }

}
