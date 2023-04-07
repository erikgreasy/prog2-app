<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ErrorLogController extends Controller
{
    public function __invoke()
    {
        return File::get(storage_path('logs/laravel.log'));
    }
}
