<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use Illuminate\Http\Request;
use App\Http\Resources\AssignmentResource;

class CurrentAssignmentController extends Controller
{
    public function __invoke()
    {
        return new AssignmentResource(
            Assignment::where('is_current', true)->firstOrFail()
        );
    }
}
