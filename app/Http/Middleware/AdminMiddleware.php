<?php

namespace App\Http\Middleware;

use Closure;
use App\Enums\Role;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        abort_if(
            auth()->user()->role != Role::ADMIN->value,
            403
        );

        return $next($request);
    }
}
