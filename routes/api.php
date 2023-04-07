<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VcsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TestCaseController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\FailedJobsController;
use App\Http\Controllers\SubmissionController;
use App\Http\Controllers\UploadFileController;
use App\Http\Controllers\TestScenarioController;
use App\Http\Controllers\FulltextSearchController;
use App\Http\Middleware\OnlyCompleteVcsSubmission;
use App\Http\Controllers\CurrentAssignmentController;
use App\Http\Controllers\UserNotificationsController;
use App\Http\Controllers\AssignmentSubmissionController;
use App\Http\Controllers\CurrentUserController;
use App\Http\Controllers\MarkNotificationAsReadController;
use App\Http\Controllers\VcsAssignmentSubmissionController;
use App\Http\Controllers\ManualAssignmentSubmissionController;

/**
 * ASSIGNMENTS
 */
Route::get('assignments/published', [AssignmentController::class, 'published'])->name('assignments.published');
Route::get('assignments/slug/{assignment:slug}', [AssignmentController::class, 'showBySlug'])->name('assignments.showBySlug');
Route::get('assignments/current', CurrentAssignmentController::class)->name('assignments.current');

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('assignments/{assignment}/submissions', [AssignmentSubmissionController::class, 'index']);
    Route::get('assignments/{assignment}/submissions/{submissionIndex}', [AssignmentSubmissionController::class, 'show']);
});

Route::group(['middleware' => ['auth:sanctum', 'teacher']], function() {
    Route::apiResource('assignments/{assignment}/tests', TestScenarioController::class);
    Route::apiResource('assignments/{assignment}/tests/{test}/cases', TestCaseController::class);
    Route::apiResource('assignments', AssignmentController::class);
});


/**
 * SUBMISSIONS
 */
Route::get('submissions', [SubmissionController::class, 'index'])->middleware(['auth:sanctum', 'teacher']);
Route::get('submissions/{submission}', [SubmissionController::class, 'show'])->middleware(['auth:sanctum', 'teacher']);

Route::group(['middleware' => [
    \App\Http\Middleware\PreventInvalidSubmissions::class,
]], function () {
    Route::post('assignments/{assignment}/manual-submit', ManualAssignmentSubmissionController::class);
    Route::post('assignments/{assignment}/submit', VcsAssignmentSubmissionController::class)
        ->middleware(OnlyCompleteVcsSubmission::class);
});


/**
 * AUTH
 */
Route::get('user', CurrentUserController::class)->middleware(['auth:sanctum']);
Route::post('logout', LogoutController::class)->middleware('auth:sanctum')->name('logout');


/**
 * STUDENTS
 */
Route::get('students', [StudentController::class, 'index'])->middleware(['auth:sanctum', 'teacher']);
Route::get('students/{student}', [StudentController::class, 'show'])->middleware(['auth:sanctum', 'teacher']);


/**
 * USERS
 */
Route::apiResource('users', UserController::class)->middleware(['auth:sanctum', 'admin']);


/**
 * NOTIFICATIONS
 */
Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('notifications', UserNotificationsController::class);
    Route::post('notifications/{notification}/mark-as-read', MarkNotificationAsReadController::class);
});


/**
 * VCS
 */
Route::group(['middleware' => ['auth:sanctum']], function() {
    Route::post('/vcs/repos/store', [VcsController::class, 'store']);
    Route::get('/vcs/repos/show', [VcsController::class, 'show']);
    Route::get('/vcs/repos', [VcsController::class, 'index']);
});


/**
 * OTHER
 */
Route::group(['middleware' => ['auth:sanctum', 'teacher']], function () {
    Route::get('fulltext-search', FulltextSearchController::class);
    Route::post('upload-file', UploadFileController::class);
    Route::get('failed-jobs', [FailedJobsController::class, 'index']);
});
