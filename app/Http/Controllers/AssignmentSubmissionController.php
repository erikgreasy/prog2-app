<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Submission;
use Illuminate\Http\Request;

class AssignmentSubmissionController extends Controller
{
    public function index(Assignment $assignment)
    {
        return $assignment->submissions()->where('user_id', auth()->id())->get();
    }

    public function show(Assignment $assignment, int $submissionIndex)
    {
        return $assignment
            ->submissions()
            ->where('user_id', auth()->id())
            ->skip($submissionIndex - 1)
            ->firstOrFail();
    }
}
