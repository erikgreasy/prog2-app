<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LogoutController extends Controller
{
    public function __invoke(Request $request)
    {
        auth()->guard('web')->logout();
 
        $request->session()->invalidate();
    
        $request->session()->regenerateToken();

        return response()->json();
    }
}
