<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\SubmissionController;
use App\Http\Controllers\CurrentAssignmentController;
use App\Http\Controllers\AssignmentSubmissionController;
use App\Http\Controllers\VcsController;

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

Route::get('assignments/published', [AssignmentController::class, 'published'])->name('assignments.published');
Route::get('assignments/slug/{assignment:slug}', [AssignmentController::class, 'showBySlug'])->name('assignments.showBySlug');
Route::get('/assignments/current', CurrentAssignmentController::class)->name('assignments.current');

Route::group(['middleware' => ['auth:sanctum']], function() {
    Route::post('/logout', LogoutController::class)->name('logout');
    Route::post('/assignments/{assignment}/submit', AssignmentSubmissionController::class);
    
    Route::post('/vcs/repos/store', [VcsController::class, 'store']);
    Route::get('/vcs/repos/show', [VcsController::class, 'show']);
    Route::get('/vcs/repos', [VcsController::class, 'index']);

    Route::group(['middleware' => ['teacher']], function() {
        Route::apiResource('/users/{user}/submissions', SubmissionController::class);
        Route::apiResource('assignments', AssignmentController::class);
    });

    Route::group(['middleware' => ['admin']], function() {
        Route::apiResource('users', UserController::class);
    });
});
