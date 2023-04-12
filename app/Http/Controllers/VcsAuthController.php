<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class VcsAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('github')
            ->setScopes(['repo'])
            ->redirect();

        return redirect("https://github.com/login/oauth/authorize?client_id={$ghClientId}");
    }

    public function callback()
    {
        $githubUser = Socialite::driver('github')->user();

        auth()->user()->update([
            'github_access_token' => $githubUser->token,
            'vcs_username' => $githubUser->getNickname()
        ]);

        return redirect('/my-profile/github');
    }
}
