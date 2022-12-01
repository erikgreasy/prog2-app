<?php

use App\Events\TestEvent;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/auth/redirect', function () {
    return Socialite::driver('google')->redirect();
});

Route::get('/auth/callback', function () {
    $googleUser = Socialite::driver('google')->user();
 
    $user = User::updateOrCreate([
        'email' => $googleUser->getEmail()
    ], [
        'name' => $googleUser->getName(),
        'password' => Hash::make('examplePassword123')
    ]);

    Auth::login($user);

    return redirect('/');
});

Route::post('/websocket-handler', function() {
    TestEvent::dispatch();
});

Route::get('/check', function() {
    dd(auth()->check());
});

Route::get('/logout', function() {
    Auth::logout();

    return redirect('/');
});

Route::get('{any}', function() {
    return view('welcome');
})->where('any', '^(?!api).*');
