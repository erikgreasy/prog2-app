<?php

namespace App\Http\Controllers;

use App\Dto\SearchResult;
use App\Enums\Role;
use App\Models\Assignment;
use App\Models\User;
use Illuminate\Http\Request;

class FulltextSearchController extends Controller
{
    public function __invoke(Request $request)
    {
        $searchKey = $request->key;

        return [
            [
                'name' => 'Zadania',
                'results' => Assignment::search($searchKey)
                    ->get()
                    ->map(function (Assignment $assignment) {
                        return new SearchResult(
                            $assignment->id,
                            $assignment->title,
                            'assignment',
                        );
                    }),
            ], 
            [
                'name' => 'Studenti',
                'results' => User::search($searchKey)
                    ->where('role', Role::STUDENT)
                    ->get()
                    ->map(function (User $user) {
                        return new SearchResult(
                            $user->id,
                            $user->name,
                            'student'
                        );
                    })
            ]
        ];
    }
}
