<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        return User::where('role', Role::STUDENT->value)->get();
    }

    public function show(User $student)
    {
        abort_if($student->role !== Role::STUDENT->value, 404);

        return $student->load('submissions');
    }
}
