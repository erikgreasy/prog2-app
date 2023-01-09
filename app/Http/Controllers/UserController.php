<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(): Collection
    {
        return User::latest()->get();
    }

    public function store(Request $request)
    {
        //
    }

    public function show(User $user): User
    {
        return $user;
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $user->update($request->validated());
    }

    public function destroy($id)
    {
        //
    }
}
