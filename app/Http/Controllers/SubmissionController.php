<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\Assign;

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
}
