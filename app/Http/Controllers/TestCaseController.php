<?php

namespace App\Http\Controllers;

use App\Http\Requests\TestCaseRequest;
use App\Models\TestCase;
use App\Models\Assignment;
use App\Models\TestScenario;
use Illuminate\Http\Request;

class TestCaseController extends Controller
{
    public function store(TestCaseRequest $request, Assignment $assignment, TestScenario $test)
    {
        return $test->cases()->create($request->validated());
    }

    public function destroy(Assignment $assignment, TestScenario $test, TestCase $case)
    {
        return $case->delete();
    }
}
