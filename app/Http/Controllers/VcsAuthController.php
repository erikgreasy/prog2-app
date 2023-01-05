<?php

namespace App\Http\Controllers;

use App\Github\Client;
use Illuminate\Http\Request;

class VcsAuthController extends Controller
{

    public function __construct(private Client $github)
    {
    }

    public function redirect()
    {
        $ghClientId = config('github.client_id');

        return redirect("https://github.com/login/oauth/authorize?client_id={$ghClientId}");
    }

    public function callback(Request $request)
    {
        $this->github->initializeToken($request->code);
        $accessToken = $this->github->getAccessToken();

        auth()->user()->update([
            'github_access_token' => $accessToken
        ]);

        return redirect('/my-profile');
    }
}
