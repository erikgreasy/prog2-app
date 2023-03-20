<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class OnlyCompleteVcsSubmission
{
    /**
     * Prevent submission via VCS if user does not have VCS setup complete.
     */
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->user()->hasVcsSetup()) {
            return response()->json([
                'message' => 'Pre tento typ odovzdania je potrebné mať v profile kompletnú sekciu VCS'
            ], 400);
        }

        return $next($request);
    }
}
