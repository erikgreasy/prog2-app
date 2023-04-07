<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ClearErrorLogController extends Controller
{
    public function __invoke()
    {
        File::put(storage_path('logs/laravel.log'), '');
    }
}
