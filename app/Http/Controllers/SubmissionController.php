<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Models\Assignment;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class SubmissionController extends Controller
{
    public function index(User $user)
    {
        // get submissions grouped by assignment
        $assignments = Assignment::whereHas('submissions', function(Builder $query) use ($user) {
            $query->where('user_id', $user->id);
        })->with('submissions')->get();

        return $assignments;
    }

    public function show(User $user, Submission $submission)
    {
        if(auth()->user()->role === Role::STUDENT) {
            abort(403);
        }

        return $submission;
    }
}
