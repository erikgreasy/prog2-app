<?php

namespace App\Http\Middleware;

use App\Enums\Role;
use Closure;
use Illuminate\Http\Request;

class TeacherMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $userRole = auth()->user()->role;

        abort_if(
            $userRole !== Role::TEACHER->value && $userRole !== Role::ADMIN->value, 
            403
        );
        
        return $next($request);
    }
}
