<?php

use App\Enums\Role;
use App\Models\User;
use App\Events\TestEvent;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VcsAuthController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

// AUTH
Route::get('/auth/redirect', [AuthController::class, 'redirect']);
Route::get('/auth/callback', [AuthController::class, 'callback']);


Route::get('/connect-github', [VcsAuthController::class, 'redirect']);

Route::get('/github-callback', [VcsAuthController::class, 'callback']);

Route::post('/websocket-handler', function() {
    TestEvent::dispatch();
});

Route::get('/login-dev', function() {
    Auth::login(
        User::where('role', Role::ADMIN->value)->first()
    );

    return redirect('/');
});

Route::get('{any}', function() {
    return view('welcome');
})->where('any', '^(?!api).*');
