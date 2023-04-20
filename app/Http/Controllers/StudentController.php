<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Http\Resources\StudentResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        if ($request->search) {
            $query = User::search($request->search);
        } else {
            $query = User::query();
        }

        return StudentResource::collection(
            $query
                ->where('role', Role::STUDENT->value)
                ->get()
        );
    }

    public function show(User $student)
    {
        abort_if($student->role !== Role::STUDENT->value, 404);

        return $student->load('submissions');
    }
}
