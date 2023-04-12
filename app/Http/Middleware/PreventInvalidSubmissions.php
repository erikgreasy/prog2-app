<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Assignment;
use Illuminate\Http\Request;

class PreventInvalidSubmissions
{
    /**
     * Prevent submitting assignment, if some of the required conditions
     * for submiting is not met.
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

        if ($assignment->submissions()->where('user_id', auth()->id())->max('try') >= $assignment->maxTries() ) {
            return response()->json([
                'message' => 'Vyčerpali ste počet možností pre odovzdanie'
            ], 400);
        }

        if ($assignment->submissions()->where('user_id', auth()->id())->whereNull('points')->exists()) {
            return response()->json([
                'message' => 'Nemôžete odovzdať pokým sa spracúva vaše predošlé odovzdanie'
            ], 400);
        }

        if ($assignment->deadline < now()) {
            return response()->json([
                'message' => 'Nie je možné odovzdať po deadline!',
            ], 400);
        }

        return $next($request);
    }
}
