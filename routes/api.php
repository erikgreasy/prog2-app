<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\SubmissionController;
use App\Http\Controllers\CurrentAssignmentController;
use App\Http\Controllers\AssignmentSubmissionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('assignments', [AssignmentController::class, 'index'])->name('assignments.index');
Route::get('assignments/slug/{assignment:slug}', [AssignmentController::class, 'showBySlug'])->name('assignments.showBySlug');
Route::get('/assignments/current', CurrentAssignmentController::class)->name('assignments.current');

Route::group(['middleware' => ['auth:sanctum']], function() {
    Route::post('/logout', LogoutController::class)->name('logout');
    Route::post('/assignments/{assignment}/submit', AssignmentSubmissionController::class);

    Route::group(['middleware' => ['teacher']], function() {
        Route::apiResource('/users/{user}/submissions', SubmissionController::class);
        Route::apiResource('users', UserController::class);
        Route::apiResource('assignments', AssignmentController::class)->except('index');
    });
});
