<?php

namespace App\Http\Controllers;

use App\Github\Client;
use Illuminate\Http\Request;

class VcsController extends Controller
{
    public function index()
    {
        return (new Client)->getRepos();
    }

    public function store(Request $request)
    {
        auth()->user()->update([
            'github_repo' => $request->repo
        ]);
    }

    public function show()
    {
        return auth()->user()->github_repo;
    }
}
