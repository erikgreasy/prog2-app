<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        if ($request->search) {
            $query = User::search($request->search);
        } else {
            $query = User::query();
        }

        return $query
            ->where('role', Role::STUDENT->value)
            ->get()
            ->load('finalAssignmentSubmissions');
    }

    public function show(User $student)
    {
        abort_if($student->role !== Role::STUDENT->value, 404);

        return $student->load('submissions');
    }
}
