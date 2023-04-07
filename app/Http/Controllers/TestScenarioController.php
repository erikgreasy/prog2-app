<?php

namespace App\Http\Controllers;

use App\Models\TestCase;
use App\Models\Assignment;
use App\Models\TestScenario;
use Illuminate\Http\Request;
use App\Http\Requests\StoreTestScenarioRequest;

class TestScenarioController extends Controller
{
    public function index(Assignment $assignment)
    {
        return $assignment->testScenarios;
    }

    public function show(Assignment $assignment, TestScenario $test)
    {
        return $test;
    }

    public function store(StoreTestScenarioRequest $request, Assignment $assignment)
    {
        $assignment->testScenarios()->create($request->validated());
    }
    
    public function update(StoreTestScenarioRequest $request, Assignment $assignment, TestScenario $test)
    {
        foreach ($request->validated('cases') as $case) {
            if (isset($case['id'])) {
                TestCase::find($case['id'])->update($case);
            } else {
                $test->cases()->create($case);
            }
        }

        $assignment->testScenarios()->update($request->safe()->except('cases'));
    }

    public function destroy(Assignment $assignment, TestScenario $test)
    {
        return $test->delete();
    }
}
