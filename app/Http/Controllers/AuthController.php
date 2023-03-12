<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        $googleUser = Socialite::driver('google')->user();
 
        $user = User::updateOrCreate([
            'email' => $googleUser->getEmail()
        ], [
            'name' => $googleUser->getName(),
            'password' => Hash::make('examplePassword123')
        ]);
    
        Auth::login($user);
    
        if($user->role === Role::ADMIN->value || $user->role === Role::TEACHER->value) {
            return redirect('/admin');
        }
    
        return redirect('/');
    }
}
