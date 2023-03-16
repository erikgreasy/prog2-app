<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PreventDuplicitSubmission
{
    /**
     * Prevent user submitting assignment, while his last submit is still not tested/finished
     */
    public function handle(Request $request, Closure $next)
    {
        $assignment = $request->route()->parameter('assignment');

        if ($assignment->submissions()->where('user_id', auth()->id())->whereNull('points')->exists()) {
            return response()->json([
                'message' => 'Nemôžete odovzdať pokým sa spracúva vaše predošlé odovzdanie'
            ], 400);
        }

        return $next($request);
    }
}
