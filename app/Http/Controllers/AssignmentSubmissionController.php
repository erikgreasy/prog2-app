<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use Illuminate\Http\Request;

class AssignmentSubmissionController extends Controller
{
    public function __invoke(Request $request, Assignment $assignment)
    {
        // check if assignment can be submitted by user
        
        // fetch code

        // send it to tester

        // store results
        return auth()->user()->submissions()->create([
            'assignment_id' => $assignment->id,
            'points' => 10,
            'report' => '',
            'ip' => $request->ip(),
        ]);
    }
}
