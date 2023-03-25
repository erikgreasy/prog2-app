<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VcsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TestCaseController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\SubmissionController;
use App\Http\Controllers\UploadFileController;
use App\Http\Controllers\TestScenarioController;
use App\Http\Controllers\FulltextSearchController;
use App\Http\Middleware\OnlyCompleteVcsSubmission;
use App\Http\Controllers\CurrentAssignmentController;
use App\Http\Controllers\UserNotificationsController;
use App\Http\Controllers\AssignmentSubmissionController;
use App\Http\Controllers\MarkNotificationAsReadController;
use App\Http\Controllers\VcsAssignmentSubmissionController;
use App\Http\Controllers\ManualAssignmentSubmissionController;

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
    
    Route::group(['middleware' => [
        \App\Http\Middleware\PreventInvalidSubmissions::class,
    ]], function () {
        Route::post('/assignments/{assignment}/manual-submit', ManualAssignmentSubmissionController::class);
        Route::post('/assignments/{assignment}/submit', VcsAssignmentSubmissionController::class)
            ->middleware(OnlyCompleteVcsSubmission::class);
    });

    // Notifications
    Route::get('/notifications', UserNotificationsController::class);
    Route::post('/notifications/{notification}/mark-as-read', MarkNotificationAsReadController::class);

    Route::get('/assignments/{assignment}/submissions', [AssignmentSubmissionController::class, 'index']);
    Route::get('/assignments/{assignment}/submissions/{submissionIndex}', [AssignmentSubmissionController::class, 'show']);
    
    Route::post('/vcs/repos/store', [VcsController::class, 'store']);
    Route::get('/vcs/repos/show', [VcsController::class, 'show']);
    Route::get('/vcs/repos', [VcsController::class, 'index']);

    Route::post('/upload-file', UploadFileController::class);

    Route::group(['middleware' => ['teacher']], function() {
        Route::apiResource('/users/{user}/submissions', SubmissionController::class);
        Route::get('assignments/{assignment}/tests', [TestScenarioController::class, 'index']);
        Route::get('assignments/{assignment}/tests/{test}', [TestScenarioController::class, 'show']);
        Route::put('assignments/{assignment}/tests/{test}', [TestScenarioController::class, 'update']);
        Route::delete('assignments/{assignment}/tests/{scenario}', [TestScenarioController::class, 'destroy']);
        Route::post('assignments/{assignment}/tests', [TestScenarioController::class, 'store']);

        Route::resource('assignments/{assignment}/tests/{test}/cases', TestCaseController::class);
        
        Route::apiResource('assignments', AssignmentController::class);
        Route::apiResource('/students', StudentController::class)->except(['store', 'update', 'destroy']);
        Route::get('/fulltext-search', FulltextSearchController::class);
    });

    Route::group(['middleware' => ['admin']], function() {
        Route::apiResource('users', UserController::class);
    });
});
