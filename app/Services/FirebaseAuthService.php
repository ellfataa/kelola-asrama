<?php

namespace App\Services;

use Google\Client;

class FirebaseAuthService
{
    public static function getAccessToken(): string
    {
        $client = new Client();

        $client->setAuthConfig(
            storage_path('app/firebase/serviceAccount.json')
        );

        $client->addScope(
            'https://www.googleapis.com/auth/firebase.messaging'
        );

        $client->fetchAccessTokenWithAssertion();

        return $client->getAccessToken()['access_token'];
    }
}
