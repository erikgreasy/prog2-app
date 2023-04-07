<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FailedJobsController extends Controller
{
    public function index()
    {
        return DB::table('failed_jobs')->select()->orderByDesc('failed_at')->get();
    }
}
