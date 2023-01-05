<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use Illuminate\Http\Request;

class CurrentAssignmentController extends Controller
{
    public function __invoke()
    {
        return Assignment::where('is_current', true)->firstOrFail();
    }
}
