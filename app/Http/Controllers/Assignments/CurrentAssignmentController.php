<?php

namespace App\Http\Controllers\Assignments;

use App\Http\Controllers\Controller;
use App\Http\Resources\AssignmentResource;
use App\Models\Assignment;

class CurrentAssignmentController extends Controller
{
    public function __invoke()
    {
        return new AssignmentResource(
            Assignment::where('is_current', true)->firstOrFail()
        );
    }
}
