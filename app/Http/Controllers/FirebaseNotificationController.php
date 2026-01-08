<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use App\Services\FirebaseAuthService;
use Illuminate\Http\Client\Response;

class FirebaseNotificationController extends Controller
{
    public function send()
    {
        $accessToken = FirebaseAuthService::getAccessToken();

        /** @var Response $response */
        $response = Http::withToken($accessToken)->post(
            'https://fcm.googleapis.com/v1/projects/' .
            env('FIREBASE_PROJECT_ID') .
            '/messages:send',
            [
                'message' => [
                    'topic' => 'penghuni_asrama',
                    'notification' => [
                        'title' => 'Pengumuman Asrama',
                        'body'  => 'Harap melakukan pembayaran sebelum tanggal 10'
                    ],
                ],
            ]
        );

        return $response->json();
    }
}
