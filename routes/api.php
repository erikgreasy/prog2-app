<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VcsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ErrorLogController;
use App\Http\Controllers\TestCaseController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\FailedJobsController;
use App\Http\Controllers\SubmissionController;
use App\Http\Controllers\UploadFileController;
use App\Http\Controllers\CurrentUserController;
use App\Http\Controllers\TestScenarioController;
use App\Http\Controllers\ClearErrorLogController;
use App\Http\Controllers\FulltextSearchController;
use App\Http\Middleware\OnlyCompleteVcsSubmission;
use App\Http\Controllers\CurrentAssignmentController;
use App\Http\Controllers\UserNotificationsController;
use App\Http\Controllers\AssignmentSubmissionController;
use App\Http\Controllers\MarkNotificationAsReadController;
use App\Http\Controllers\VcsAssignmentSubmissionController;
use App\Http\Controllers\ManualAssignmentSubmissionController;

/**
 * Public routes
 */
Route::get('assignments/published', [AssignmentController::class, 'published'])->name('assignments.published');
Route::get('assignments/slug/{assignment:slug}', [AssignmentController::class, 'showBySlug'])->name('assignments.showBySlug');
Route::get('assignments/current', CurrentAssignmentController::class)->name('assignments.current');


/**
 * Students / logged in users
 */
Route::group(['middleware' => ['auth:sanctum']], function () {
    // assignments
    Route::get('assignments/{assignment}/submissions', [AssignmentSubmissionController::class, 'index']);
    Route::get('assignments/{assignment}/submissions-count', [AssignmentSubmissionController::class, 'count']);
    Route::get('assignments/{assignment}/submissions/{submission}', [AssignmentSubmissionController::class, 'show']);

    // listing submissions
    Route::get('my-submissions', [SubmissionController::class, 'currentUserSubmissions']);

    // submitting
    Route::group(['middleware' => [\App\Http\Middleware\PreventInvalidSubmissions::class]], function () {
        Route::post('assignments/{assignment}/manual-submit', ManualAssignmentSubmissionController::class);
        Route::post('assignments/{assignment}/submit', VcsAssignmentSubmissionController::class)->middleware(OnlyCompleteVcsSubmission::class);
    });

    // general auth
    Route::get('user', CurrentUserController::class)->middleware(['auth:sanctum']);
    Route::post('logout', LogoutController::class)->middleware('auth:sanctum')->name('logout');

    // notifications
    Route::get('notifications', UserNotificationsController::class);
    Route::post('notifications/{notification}/mark-as-read', MarkNotificationAsReadController::class);

    // vcs
    Route::post('/vcs/repos/store', [VcsController::class, 'store']);
    Route::get('/vcs/repos/show', [VcsController::class, 'show']);
    Route::get('/vcs/repos', [VcsController::class, 'index']);
});


/**
 * Teachers
 */
Route::group(['middleware' => ['auth:sanctum', 'teacher']], function() {
    // assignments
    Route::apiResource('assignments/{assignment}/tests', TestScenarioController::class);
    Route::apiResource('assignments', AssignmentController::class);

    Route::get('submissions', [SubmissionController::class, 'index']);
    Route::get('submissions/{submission}', [SubmissionController::class, 'show']);

    // students
    Route::get('students', [StudentController::class, 'index']);
    Route::get('students/{student}', [StudentController::class, 'show']);

    // other
    Route::get('fulltext-search', FulltextSearchController::class);
    Route::post('upload-file', UploadFileController::class);
});


/**
 * Admins
 */
Route::group(['middleware' => ['auth:sanctum', 'admin']], function() {
    // users
    Route::apiResource('users', UserController::class);

    // failed jobs
    Route::get('failed-jobs', [FailedJobsController::class, 'index']);

    // error logs
    Route::get('error-log', ErrorLogController::class);
    Route::delete('error-log', ClearErrorLogController::class);
});
