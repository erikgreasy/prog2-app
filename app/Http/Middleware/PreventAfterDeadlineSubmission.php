<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PreventAfterDeadlineSubmission
{
    public function handle(Request $request, Closure $next)
    {
        $assignment = $request->route()->parameter('assignment');

        if ($assignment->deadline < now()) {
            return response()->json([
                'message' => 'Nie je možné odovzdať po deadline!',
            ], 400);
        }

        return $next($request);
    }
}
