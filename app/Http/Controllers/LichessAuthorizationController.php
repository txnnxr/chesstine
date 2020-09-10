<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class LichessAuthorizationController extends Controller
{
    public function index()
    {
        $client = [
            'id' => env('TEST_LICHESS_CLIENT_ID'),
            'secret' => env('TEST_LICHESS_CLIENT_SECRET'),
        ];
        $scopes = [
          'preference:read',
          'challenge:read',
      ];

        return Http::withBasicAuth(env('TEST_LICHESS_CLIENT_ID'), env('TEST_LICHESS_CLIENT_SECRET'))->get('https://oauth.lichess.org/oauth/authorize?response_type=code');
    }

    public function callback($request)
    {
        dd($request);
    }
}
