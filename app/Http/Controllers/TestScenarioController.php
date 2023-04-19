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
                TestCase::find($case['id'])->update([
                    'gcc_macro_defs' => $case['gcc_macro_defs'],
                    'stdin' => $case['stdin'],
                    'cmdin' => $case['cmdin'],
                    'stdout' => $case['stdout'],
                    'errout' => $case['errout']
                ]);
            } else {
                $test->cases()->create([
                    'gcc_macro_defs' => $case['gcc_macro_defs'],
                    'stdin' => $case['stdin'],
                    'cmdin' => $case['cmdin'],
                    'stdout' => $case['stdout'],
                    'errout' => $case['errout']
                ]);
            }
        }

        foreach ($test->cases as $prevCase) {
            $newCasesIds = collect($request->validated('cases'))->pluck('id');

            if (! $newCasesIds->contains($prevCase->id)) {
                $prevCase->delete();
            }
        }

        $test->update($request->safe()->except('cases'));
    }

    public function destroy(Assignment $assignment, TestScenario $test)
    {
        return $test->delete();
    }
}
