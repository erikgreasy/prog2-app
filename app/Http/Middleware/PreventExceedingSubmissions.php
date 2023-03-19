<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Assignment;
use Illuminate\Http\Request;

class PreventExceedingSubmissions
{
    /**
     * Stop student from submitting assignment, that he already submitted for max. number of allowed tries.
     */
    public function handle(Request $request, Closure $next)
    {
        /** @var Assignment */
        $assignment = $request->route()->parameter('assignment');

        if ($assignment->submissions()->where('user_id', auth()->id())->count() >= $assignment->maxTries() ) {
            return response()->json([
                'message' => 'Vyčerpali ste počet možností pre odovzdanie'
            ], 400);
        }

        return $next($request);
    }
}
