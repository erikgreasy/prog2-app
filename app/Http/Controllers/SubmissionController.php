<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Models\User;
use App\Models\Assignment;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class SubmissionController extends Controller
{
    public function index(Request $request)
    {
        $query = Submission::query();

        if ($request->user) {
            $query->where('user_id', $request->user);
        }

        if ($request->assignment) {
            $query->where('assignment_id', $request->assignment);
        }

        return $query->get();
    }

    public function currentUserSubmissions()
    {
        /** @var User $user */
        $user = auth()->user();

        return $user->submissions;
    }

    public function show(Submission $submission)
    {
        return $submission;
    }
}
